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
        $data['externalId'] = $xml->getElementsByTagName('BillRefNumber')->item(0)->nodeValue;
        $data['transaction_time'] = $xml->getElementsByTagName('TransTime')->item(0)->nodeValue;
        $data['paybill'] = $xml->getElementsByTagName('BusinessShortCode')->item(0)->nodeValue;

        // Check wether the transaction exists
        $transaction = Payment::whereTransactionId($data['transaction_id'])->first();

        if($transaction == null) {
            $payment = Payment::create($data);

                if(self::processLoan($data,$payment)) {
                    $payment->status = 1;
                } else {
                    $payment->status = 2;
                }
            $payment->save();
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
    public function getTransactionClient($data){

        $PaymentController = new PaymentsController();
        return $PaymentController->getTransactionClient($data);

        $user = FALSE;
        $externalid = $data['externalId'];

        $url = MIFOS_URL . "/clients?sqlSearch=(c.external_id%20like%20%27" . $externalid . "%27)&" . MIFOS_tenantIdentifier;
        // Get all clients
        $client = Hooks::MifosGetTransaction($url, $post_data = '');

        if (isset($client->totalFilteredRecords)) {
            if ($client->totalFilteredRecords == 0) {
                //search by phone
                $no = substr($data['phone'], -9);

                $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%270" . $no . "%27)&" . MIFOS_tenantIdentifier;

                // Get all clients
                $client = Hooks::MifosGetTransaction($url, $post_data = '');

                if ($client->totalFilteredRecords == 0) {
                    $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%27254" . substr($externalid, -9) . "%27)&" . MIFOS_tenantIdentifier;

                    // Get all clients
                    $client = Hooks::MifosGetTransaction($url, $post_data = '');
                }
            }
            if ($client->totalFilteredRecords > 0) {
                $client = $client->pageItems[0];
                $usr = array();
                $usr['client_id'] = $client->id;
                if ($client->status->code == 'clientStatusType.active') {
                    $usr['active_status'] = 1;
                } else {
                    $usr['active_status'] = 0;
                }
                $user = (object) $usr;
            }
        }
        return $user;
    }
//    public function getTransactionClient($data){
//        $externalid = $data['externalId'];
//
//        $url = MIFOS_URL . "/clients?sqlSearch=(c.external_id%20like%20%27" . $externalid . "%27)&" . MIFOS_tenantIdentifier;
//        // Get all clients
//        $client = Hooks::MifosGetTransaction($url, $post_data = '');
//
//        if ($client->totalFilteredRecords == 0) {
//            //search by phone
//            $no = substr($data['phone'],-9);
//
//            $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%27254" . $no . "%27)&" . MIFOS_tenantIdentifier;
//
//            // Get all clients
//            $client = Hooks::MifosGetTransaction($url, $post_data = '');
//
//            if ($client->totalFilteredRecords == 0) {
//                $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%27254" . substr($externalid,-9) . "%27)&" . MIFOS_tenantIdentifier;
//
//                // Get all clients
//                $client = Hooks::MifosGetTransaction($url, $post_data = '');
//            }
//        }
//        $user = FALSE;
//        if ($client->totalFilteredRecords > 0) {
//            $client = $client->pageItems[0];
//            $usr = array();
//            $usr['client_id'] = $client->id;
//            if ($client->status->code == 'clientStatusType.active') {
//                $usr['active_status'] = 1;
//            } else {
//                $usr['active_status'] = 0;
//            }
//            $user = (object) $usr;
//        }
//
//        return $user;
//    }

    public function verifyPhonefromMifos($no)
    {
        // Get the url for retrieving the specific loan
        $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%27" . $no . "%27)&" . MIFOS_tenantIdentifier;

        // Get all clients
        $client = Hooks::MifosGetTransaction($url, $post_data = '');


        if ($client->totalFilteredRecords == 0) {
            $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%270" . $no . "%27)&" . MIFOS_tenantIdentifier;

            // Get all clients
            $client = Hooks::MifosGetTransaction($url, $post_data = '');

            if ($client->totalFilteredRecords == 0) {
                $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%27254" . $no . "%27)&" . MIFOS_tenantIdentifier;

                // Get all clients
                $client = Hooks::MifosGetTransaction($url, $post_data = '');
            }
        }

//        print_r($client);
//        exit;
        $user = FALSE;
        if ($client->totalFilteredRecords > 0) {
            $user = UssdUser::where('phone_no', '254' . $no)->orWhere('phone_no', "0" . $no)->first();
            $client = $client->pageItems[0];
            $usr = array();
            $usr['name'] = $client->displayName;
            $usr['client_id'] = $client->id;
            $usr['office_id'] = $client->officeId;
            $usr['phone_no'] = $client->mobileNo;
            $usr['session'] = 0;
            $usr['progress'] = 0;
            $usr['email'] = $client->mobileNo;
            $usr['confirm_from'] = 0;
            $usr['menu_item_id'] = 0;
            $usr['is_pcl_user'] = 1;
            if ($client->status->code == 'clientStatusType.active') {
                $usr['active_status'] = 1;
            } else {
                $usr['active_status'] = 0;
            }
            //get if user is a pcl user
//            $pcl_status = self::isPclUser($client->id);
//            if (count($pcl_status) > 0) {
//                if ($pcl_status[0]->is_pcl_user == 'true') {
//                    $usr['is_pcl_user'] = 1;
//                } else {
//                    $usr['is_pcl_user'] = 0;
//                }
//            } else {
//                $usr['is_pcl_user'] = 0;
//            }
            $user = UssdUser::where('phone_no', '254' . $no)->orWhere('phone_no', "0" . $no)->first();
            if ($user) {
                DB::table('users')->where('id', $user->id)->update($usr);
                $user = UssdUser::where('phone_no', '254' . $no)->orWhere('phone_no', "0" . $no)->first();
            } else {
                $user = Ussduser::create($usr);
            }
        } else {
            $usr = array();
            $usr['name'] = "Another User";
            $usr['client_id'] = 0;
            $usr['office_id'] = 0;
            $usr['phone_no'] = '254' . $no;
            $usr['session'] = 0;
            $usr['progress'] = 0;
            $usr['email'] = '254' . $no;
            $usr['confirm_from'] = 0;
            $usr['menu_item_id'] = 0;
            $usr['is_pcl_user'] = 1;
            $user = Ussduser::create($usr);
        }
        return $user;
    }
    public function processLoan($payment_data,$payment=null){

        //get user

        $user = self::getTransactionClient($payment_data);
        if(!$user){
            $payment->comment = "No User found with either the account provided or phone number of the payee";
            $payment->save();
        }
        $loanAccounts = self::getClientLoanAccountsInAscendingOrder($user->client_id);
        $latest_loan = end($loanAccounts);
        $loan_id = '';
        $loan = '';
        $loan_payment_received = $payment_data['amount'];
        foreach ($loanAccounts as $la) {
//            if (($la->status->active == 1) && ($loan_payment_received>0)) {

                if (($la->status->id == 300) && ($loan_payment_received>0)) { 
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
                    $payment->comment = "Problem processing loan repayment";
                    $payment->save();
                    return false;
                }
            }

            if($loan_payment_received ==0){
                break;
            }
        }
//                $ussd = new UssdController();
//                $balance = $ussd->getLoanBalance($user->client_id);
//
//                if($balance['amount']>0){
//                    $hooks = new MifosXController();
//
//                    $next_payment = $hooks->checkNextInstallment($latest_loan->id);
//                    //$loan_balance['next_payment'] = $next_payment;
//                    $msg = "Dear ".self::getClientName($user->client_id).", thank you we have received your payment of Kshs. ".number_format($payment_data['amount'],2).". Please clear the outstanding balance Kshs. ".number_format($balance['installment_amount'],2)." due on ".$next_payment['next_date'].". To repay your total outstanding balance, please send Kshs. ".number_format($balance['amount'],2)." to our M-pesa paybill number 963334. For any assistance please call our customer care line 0704 000 999";
//                }else {
//                    $limit = self::getLoanLimit($user->client_id);
//                    $msg = "Dear ".self::getClientName($user->client_id).", your Salary Advance Loan has been fully repaid. You can apply for another Salary Advance Loan immediately within your limit of Kshs ".number_format($limit,2).". Thank you for choosing Uni Ltd.";
//                }
//                $notify = new NotifyController();
//                $notify->sendSms($payment_data['phone'],$msg);
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
