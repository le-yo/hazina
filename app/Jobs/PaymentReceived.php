<?php

namespace App\Jobs;

use App\Helpers\Mifos\MifosHooks;
use App\Hooks;
use App\Http\Controllers\MifosXController;
use App\Http\Controllers\NotifyController;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\UssdController;
use App\Jobs\Job;
use App\Payment;
use App\setting;
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
    public function checkAccountPrefix($account){
        $settings = setting::all();
        foreach ($settings as $setting){
            $shortname = $setting->short_name;
            if(strtolower($shortname) == strtolower(substr($account, 0, strlen($shortname)))){
                $account = substr($account, strlen($shortname));

            }
        }
        return $account;
    }

    public function getTransactionClient($data){
        $user = FALSE;
        $data['externalId'] = self::checkAccountPrefix($data['externalId']);
        //check if external id has some prefix
        $externalid = $data['externalId'];

        $url = MIFOS_URL . "/clients?sqlSearch=(c.external_id%20like%20%27" . $externalid . "%27)&" . MIFOS_tenantIdentifier;
        // Get all clients
        $client = Hooks::MifosGetTransaction($url, $post_data = '');

        if (isset($client->totalFilteredRecords)) {
            if ($client->totalFilteredRecords == 0) {
                //search by phone
                $no = substr($data['externalId'], -9);

                $url = MIFOS_URL . "/search?exactMatch=false&query=" . $no . "&&resource=clients,clientIdentifiers&" . MIFOS_tenantIdentifier;

                // Get all clients
                $client = Hooks::MifosGetTransaction($url, $post_data = '');
                if (isset($client[0]->entityId)) {
                    $usr = array();
                    $usr['client_id'] = $client[0]->entityId;
                    if ($client[0]->entityStatus->code == 'clientStatusType.active') {
                        $usr['active_status'] = 1;
                    } else {
                        $usr['active_status'] = 0;
                    }
                    $user = (object) $usr;

                    return $user;
                }
                if (!isset($client[0]->entityId)) {
                    $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%27254" . $no . "%27)&" . MIFOS_tenantIdentifier;

                    // Get all clients
                    $client = Hooks::MifosGetTransaction($url, $post_data = '');
                    if ($client->totalFilteredRecords == 0) {
                        $no = substr($data['phone'], -9);

                        $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%270" . $no . "%27)&" . MIFOS_tenantIdentifier;

                        // Get all clients
                        $client = Hooks::MifosGetTransaction($url, $post_data = '');
                        if ($client->totalFilteredRecords == 0) {
                            $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%27254" . $no . "%27)&" . MIFOS_tenantIdentifier;

                            // Get all clients
                            $client = Hooks::MifosGetTransaction($url, $post_data = '');
                        }
                    }
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

    public function processUserLoan($payment_data,$payment=null){

        //get user
        $payment_data['externalId'] = $payment_data['account_no'];
        $user = self::getTransactionClient($payment_data);

//        if(!$user){
//            $payment->comment = "No User found with either the account provided or phone number of the payee";
//            $payment->save();
//        }

//        print_r($user);
//        exit;
        if($user) {
            $loanAccounts = self::getClientLoanAccountsInAscendingOrder($user->client_id);

            foreach ($loanAccounts as $key=>$la){
                $shortname = $la->shortProductName;
                $externalid_sub_string = strtolower(substr($payment_data['externalId'], 0, strlen($shortname)));
                if(strtolower($shortname) == $externalid_sub_string){
                    $tmp = $loanAccounts[$key];
                    unset($loanAccounts[$key]);
                    array_unshift($loanAccounts,$tmp);
                }
            }

            $latest_loan = end($loanAccounts);
            $loan_id = '';
            $loan = '';
            $loan_payment_received = $payment_data['amount'];
//        print_r(json_encode($loanAccounts));
//        exit;
            foreach ($loanAccounts as $la) {
//            echo $la->status->id.PHP_EOL;
//            continue;
                if ($la->status->id == 300) {
                    if (($la->loanBalance < $loan_payment_received) && ($la->id != $latest_loan->id)) {
//                        $loan_payment_received = $loan_payment_received - $la->loanBalance;
                        $amount = $la->loanBalance;
                    } else {
                        $amount = $loan_payment_received;
//                        $loan_payment_received = 0;
                    }
                    // get repayment details
                    $repayment_data = [];
                    $repayment_data['dateFormat'] = 'dd MMMM yyyy';
                    $repayment_data['locale'] = 'en_GB';
                    $repayment_data['transactionDate'] = Carbon::parse($payment_data['transaction_time'])->format('j F Y');
                    $repayment_data['transactionAmount'] = $amount;
                    $repayment_data['paymentTypeId'] = 6;
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
                        foreach ($loanPayment->errors as $error) {
                            if ($error->userMessageGlobalisationCode == 'error.msg.loan.transaction.cannot.be.before.disbursement.date') {
                                //send the payment to savings
                                if(self::depositToDrawDownAccount($user->client_id,$loan_payment_received,$payment_data)){
                                    $loan_payment_received = 0;
                                    $payment = Payment::find($payment_data['id']);
                                    $payment->status = 1;
                                    $payment->update();
                                    return redirect('/')->with('error', 'We had a problem processing repayment for '.$payment->client_name.' but have pushed the payment to draw down account');
                                    break;
                                }else{
                                    if(self::depositToCCFAccount($user->client_id,$loan_payment_received,$payment_data)){
                                        $loan_payment_received = 0;
                                        $payment = Payment::find($payment_data['id']);
                                        $payment->status = 1;
                                        $payment->update();
                                        return redirect('/')->with('error', 'We had a problem processing repayment for '.$payment->client_name.' but have pushed the payment to CCF account');
                                        break;
                                    }
                                }
                            }
                        }
//                        $payment->comment = "Problem processing loan repayment";
//                        $payment->save();
                        return false;
                    } else {
                        if (($la->loanBalance < $loan_payment_received) && ($la->id != $latest_loan->id)) {
                            $loan_payment_received = $loan_payment_received - $la->loanBalance;
                        } else {
                            $loan_payment_received = 0;
                        }
                        echo "processed successfully";
                        if ($loan_payment_received == 0) {
                            return true;
                        }
                    }
                }

                if ($loan_payment_received == 0) {
                    break;
                }
            }

            if($loan_payment_received >0){
                //send to savings
                self::depositToDrawDownAccount($user->client_id,$loan_payment_received,$payment_data);
                $payment = Payment::whereTransactionId($payment_data['transaction_id'])->first();
                $payment->status = 1;
                $payment->save();
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
    }


    public function processLoan($payment_data){

        if($payment_data['paybill'] == 4017901){
            self::processUserLoan($payment_data);
        }


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
    public function depositToDrawDownAccount($client_id,$amount,$data){

        $savingsaccounts = self::getClientSavingsAccountsInAscendingOrder($client_id);
        $processed = 0;
        foreach ($savingsaccounts as $key=>$sa){
            $shortname = $sa->shortProductName;
            $externalid_sub_string = 'TAC';
            if(strtolower($shortname) == strtolower($externalid_sub_string) && $sa->status->id==300){
                if ($sa->status->id == 300) {
                    $deposit_data = [];
                    $deposit_data['locale'] = 'en_GB';
                    $deposit_data['dateFormat'] = 'dd MMMM yyyy';
                    $deposit_data['transactionDate'] = Carbon::parse($data['transaction_time'])->format('j F Y');
                    $deposit_data['transactionAmount'] = $amount;
                    $deposit_data['paymentTypeId'] = 6;
                    $deposit_data['accountNumber'] = $data['phone'];
                    $deposit_data['receiptNumber'] = $data['transaction_id'];
                    $deposit_data = json_encode($deposit_data);

                    // url for posting the repayment details
                    $postURl = MIFOS_URL . "/savingsaccounts/" . $sa->id . "/transactions?command=deposit&" . MIFOS_tenantIdentifier;
                    // post the encoded repayment details
                    $savingsPayment = Hooks::MifosPostTransaction($postURl, $deposit_data);

                    // check if posting was successful
                    if (array_key_exists('errors', $savingsPayment)) {
//                        $payment->comment = "Problem processing loan repayment";
//                        $payment->save();
                        return false;
                    } else {
                        return $savingsPayment;
                    }
                }
            }else{
                unset($savingsaccounts[$key]);
            }
        }

    }
    public function depositToCCFAccount($client_id,$amount,$data){

        $savingsaccounts = self::getClientSavingsAccountsInAscendingOrder($client_id);
        $processed = 0;
        foreach ($savingsaccounts as $key=>$sa){
            $shortname = $sa->shortProductName;
            $externalid_sub_string = 'CCF';
            if(strtolower($shortname) == strtolower($externalid_sub_string) && $sa->status->id==300){
                if ($sa->status->id == 300) {
                    $deposit_data = [];
                    $deposit_data['locale'] = 'en_GB';
                    $deposit_data['dateFormat'] = 'dd MMMM yyyy';
                    $deposit_data['transactionDate'] = Carbon::parse($data['transaction_time'])->format('j F Y');
                    $deposit_data['transactionAmount'] = $amount;
                    $deposit_data['paymentTypeId'] = 6;
                    $deposit_data['accountNumber'] = $data['phone'];
                    $deposit_data['receiptNumber'] = $data['transaction_id'];
                    $deposit_data = json_encode($deposit_data);

                    // url for posting the repayment details
                    $postURl = MIFOS_URL . "/savingsaccounts/" . $sa->id . "/transactions?command=deposit&" . MIFOS_tenantIdentifier;
                    // post the encoded repayment details
                    $savingsPayment = Hooks::MifosPostTransaction($postURl, $deposit_data);

                    // check if posting was successful
                    if (array_key_exists('errors', $savingsPayment)) {
//                        $payment->comment = "Problem processing loan repayment";
//                        $payment->save();
                        return false;
                    } else {
                        return $savingsPayment;
                    }
                }
            }else{
                unset($savingsaccounts[$key]);
            }
        }

    }
    function getClientSavingsAccountsInAscendingOrder($client_id)
    {

        $url = MIFOS_URL . "/clients/" . $client_id . "/accounts?fields=savingsAccounts&" . MIFOS_tenantIdentifier;
        $savingsAccounts = Hooks::MifosGetTransaction($url);

        if (!empty($savingsAccounts->savingsAccounts)) {
            $savingsAccounts = $savingsAccounts->savingsAccounts;
        } else {
            $savingsAccounts = array();
        }
        return $savingsAccounts;
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
