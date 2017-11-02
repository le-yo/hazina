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
            // Immediately process payment if Business or PayCheque loan payment
//            if($data['paybill'] == BLP_PAYBILL || $data['paybill'] == PCL_PAYBILL) {

                if(self::processLoan($data)) {
                    $data['status'] = 1;
                } else {
                    $data['status'] = 2;
                }
//            }

            // Save the payment object
            Payment::create($data);
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
    public function processLoan($data){
        $ussd = new UssdController();
        $response = $ussd->getPCLLoanfromPhone($data['phone']);


        $receipt = self::jsonp_decode($response);
        $loanID = $receipt->loan->id;
        // get repayment details
        $repayment_data = [];
        $repayment_data['dateFormat'] = 'dd MMMM yyyy';
        $repayment_data['locale'] = 'en_GB';
        $repayment_data['transactionDate'] = Carbon::parse($data['transaction_time'])->format('j F Y');
        $repayment_data['transactionAmount'] = $data['amount'];
        $repayment_data['paymentTypeId'] = 1;
        $repayment_data['note'] = 'Payment';
        $repayment_data['accountNumber'] = $data['phone'];
        $repayment_data['receiptNumber'] = $data['transaction_id'];

        // json encode repayment details
        $loan_data = json_encode($repayment_data);

        // url for posting the repayment details
        $postURl = MIFOS_URL . "/loans/" . $loanID . "/transactions?command=repayment&" . MIFOS_tenantIdentifier;

        // post the encoded repayment details
        $loanPayment = Hooks::MifosPostTransaction($postURl, $loan_data);

        // check if posting was successful
        if (array_key_exists('errors', $loanPayment)) {
            return false;
        } else {
            // update status column in payments table to processed
            $no = substr($data['phone'], -9);

            $user = Ussduser::where('phone_no', "0" . $no)->orWhere('phone_no', "254" . $no)->first();

            if ($user) {


                $url = MIFOS_URL . "/clients/" . $user->client_id . "/accounts?" . MIFOS_tenantIdentifier;
                $account = Hooks::MifosGetTransaction($url);

                $i = 0;
                $loan_balance = array();
                $loan_balance['amount'] = 0;
                $loan_balance['message'] = "Your outstanding loan balance due on ";

                $product_id = env('PCL_ID');
                foreach ($account->loanAccounts as $loanAccount) {

                    if (!empty($loanAccount->loanBalance) && ($loanAccount->status->code == 'loanStatusType.active') && ($loanAccount->productId == $product_id)) {
                        $loan_balance['amount'] = $loan_balance['amount'] + $loanAccount->loanBalance;
                        $loan_balance['message'] = $loan_balance['message'] . implode("/", array_reverse($loanAccount->timeline->expectedMaturityDate)) . " is Ksh " . number_format($loanAccount->loanBalance) . "." . PHP_EOL;
                        //$loan_balance['raw'] = $loanAccount;
                        $loan_id = $loanAccount->id;
                        $i++;
                    }
                }

                if(($loan_balance['amount']>0)){
                    $hooks = new MifosXController();
                    $next_payment = $hooks->checkNextInstallment($loanID);
                    //$loan_balance['next_payment'] = $next_payment;
                    $msg = "Dear ".self::getClientName(client_id).", we have received your payment of Kshs. ".$data['amount'].". Please clear the outstanding balance Kshs. ".$next_payment['balance']." due on ".$next_payment['next_date'].". For further assistance please call our customer care line 0704 000 999";

                }else {
                    $limit = self::getLoanLimit($user->client_id);
                    $msg = "Dear ".self::getClientName(client_id).", your salary advance loan has been repaid. You can apply for another loan immediately within your limit of Kshs ".$limit.". Thank you for choosing Uni Ltd.";
                }
                $notify = new NotifyController();
                $notify->sendSms($data['phone'],$msg);

                 }
                return true;
        }


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
