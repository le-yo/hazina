<?php

namespace App\Jobs;

use App\Hooks;
use App\Http\Controllers\MifosXController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\UssdController;
use App\Jobs\Job;
use App\Payment;
use App\TransactionLog;
use App\Ussduser;
use Carbon\Carbon;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class PaymentReceived extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    protected $payload;

    /**
     * Create a new job instance.
     *
     * @param $payload
     */
    public function __construct($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Load the xml to the data variable
        $data = ['content' => $this->payload];

        // Create a transaction log object
        TransactionLog::create($data);

        $xml = new \DOMDocument();
        $xml -> loadXML($this->payload);

        // Strip the xml and store them in the data variable
        $data['phone'] = "+254".substr(trim($xml->getElementsByTagName('MSISDN')->item(0)->nodeValue), -9);
        if($xml->getElementsByTagName('KYCInfo')->length == 2) {
            $data['client_name'] = $xml->getElementsByTagName('KYCValue')->item(0)->nodeValue.' '.$xml->getElementsByTagName('KYCValue')->item(1)->nodeValue;
        } elseif($xml->getElementsByTagName('KYCInfo')->length == 3) {
            $data['client_name'] = $xml->getElementsByTagName('KYCValue')->item(0)->nodeValue.' '.$xml->getElementsByTagName('KYCValue')->item(1)->nodeValue.' '.$xml->getElementsByTagName('KYCValue')->item(2)->nodeValue;
        }
        $data['transaction_id'] = $xml->getElementsByTagName('TransID')->item(0)->nodeValue;
        $data['amount'] = $xml->getElementsByTagName('TransAmount')->item(0)->nodeValue;
        $data['account_no'] = $xml->getElementsByTagName('BillRefNumber')->item(0)->nodeValue;
        $data['transaction_time'] = $xml->getElementsByTagName('TransTime')->item(0)->nodeValue;
        $data['paybill'] = $xml->getElementsByTagName('BusinessShortCode')->item(0)->nodeValue;

        // Check wether the transaction exists
        $transaction = Payment::whereTransactionId($data['transaction_id'])->first();

        if($transaction == null) {
            $payment = Payment::create($data);

                if(self::processLoan($data)) {
                    $data['status'] = 1;
                } else {
                    $data['status'] = 2;
                }

        }
    }

    /**
     * Decodes jsonp responses
     *
     * @param $jsonp
     * @param bool $assoc
     * @return mixed
     */
    public function jsonp_decode($jsonp, $assoc = false) {
        if($jsonp !== '[' && $jsonp !== '{') {
            $jsonp = substr($jsonp, strpos($jsonp, '('));
        }
        return json_decode(trim($jsonp,'();'), $assoc);
    }

    /**
     * Process Loan
     *
     * @param $data
     * @return bool
     */
    public function getClientName($clientId)
    {
        $url = MIFOS_URL."/clients/".$clientId."?".MIFOS_tenantIdentifier;

        $client = Hooks::MifosGetTransaction($url, $post_data = "");

        $clientName = $client->displayName;

        return ucfirst($clientName);
    }
    public function processLoan($payment_data){
        echo "hapa";
        exit;
        $ussd = new UssdController();

        //$response = $ussd->getPCLLoanfromPhone($data['phone']);

        $data = array();
        $no = substr($payment_data['phone'], -9);

        $user = Ussduser::where('phone_no', "0" . $no)->orWhere('phone_no', "254" . $no)->first();

        if (!$user) {
            //check on mifos
            $user = $ussd->verifyPhonefromMifos($no);
            if (!$user) {
                $data['error'] = 1;
                $data['message'] = 'No user found with given phone number found';
                return json_encode($data);
            }

        }

        $loanAccounts = self::getClientLoanAccountsInAscendingOrder($user->client_id);

        $latest_loan = end($loanAccounts);
        $loan_id = '';
        $loan = '';
        $loan_payment_received = $payment_data['amount'];
        foreach ($loanAccounts as $la) {
            if (($la->status->active == 1) && ($la->productId == PCL_ID) && ($loan_payment_received>0)) {

                if(($la->loanBalance < $loan_payment_received) && ($la->id !=$latest_loan->id)){
                    $loan_payment_received = $loan_payment_received - $la->loanBalance;
                    $amount = $la->loanBalance;
                }else{
                    $amount = $loan_payment_received;
                    $loan_payment_received=0;
                }
                // get repayment details
                $repayment_data = [];
                $repayment_data['dateFormat'] = 'dd MMMM yyyy';
                $repayment_data['locale'] = 'en_GB';
                $repayment_data['transactionDate'] = Carbon::parse($payment_data['transaction_time'])->format('j F Y');
                $repayment_data['transactionAmount'] = $amount;
                $repayment_data['paymentTypeId'] = 1;
                $repayment_data['note'] = 'Payment';
                $repayment_data['accountNumber'] = $payment_data['phone'];
                $repayment_data['receiptNumber'] = $payment_data['transaction_id'];

                // json encode repayment details
                $loan_data = json_encode($repayment_data);

                // url for posting the repayment details
                $postURl = MIFOS_URL . "/loans/" . $la->id . "/transactions?command=repayment&" . MIFOS_tenantIdentifier;

                // post the encoded repayment details
                $loanPayment = Hooks::MifosPostTransaction($postURl, $loan_data);

                // check if posting was successful
                if (array_key_exists('errors', $loanPayment)) {
                    return false;
                }
            }

            if($loan_payment_received ==0){
                break;
            }
        }
                $ussd = new UssdController();
                $balance = $ussd->getLoanBalance($user->client_id);

                if($balance['amount']>0){
                    $hooks = new MifosXController();

                    $next_payment = $hooks->checkNextInstallment($latest_loan->id);
                    //$loan_balance['next_payment'] = $next_payment;
                    $msg = "Dear ".self::getClientName($user->client_id).", thank you we have received your payment of Kshs. ".number_format($payment_data['amount'],2).". Please clear the outstanding balance Kshs. ".number_format($balance['installment_amount'],2)." due on ".$next_payment['next_date'].". To repay your total outstanding balance, please send Kshs. ".number_format($balance['amount'],2)." to our M-pesa paybill number 963334. For any assistance please call our customer care line 0704 000 999";
                }else {
                    $limit = self::getLoanLimit($user->client_id);
                    $msg = "Dear ".self::getClientName($user->client_id).", your Salary Advance Loan has been fully repaid. You can apply for another Salary Advance Loan immediately within your limit of Kshs ".number_format($limit,2).". Thank you for choosing Uni Ltd.";
                }
                $notify = new NotifyController();
                $notify->sendSms($payment_data['phone'],$msg);
            return true;
    }

    function getClientLoanAccountsInAscendingOrder($client_id)
    {
        $url = MIFOS_URL . "/clients/" . $client_id . "/accounts?fields=loanAccounts&" . MIFOS_tenantIdentifier;
        $loanAccounts = Hooks::MifosGetTransaction($url);
        if (!empty($loanAccounts->loanAccounts)) {
            $loanAccounts = $loanAccounts->loanAccounts;
        } else {
            $loanAccounts = array();
        }
        return $loanAccounts;
    }

    public function getLoanLimit($id)
    {
//        return 0;
        $url = MIFOS_URL . "/datatables/user_loan_limit/" . $id . "?" . MIFOS_tenantIdentifier;
        $limit = Hooks::MifosGetTransaction($url);
        if (count($limit) > 0) {
            return $limit[0]->user_loan_limit;
        } else {
            return 0;
        }
    }
}
