<?php

namespace App\Http\Controllers;


use App\Hooks;
use App\Jobs\PaymentReceived;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

$success = false;

class PaymentsController extends Controller
{

    public function __contruct() {
        $this->middleware('sentinel.auth', ['except' => 'receiver']);
    }

    /**
     * Receives all the payments from Safaricom
     * @param Request $request
     */
    public function receiver(Request $request) {
        $input = $request->getContent();

        $payment = (new PaymentReceived($input))->delay(5);
        $this->dispatch($payment);
    }

    public function getreceiver(Request $request) {
        $input = $request->getContent();
        $content = explode('&',$input);

        $TransID = explode("=",$content[1]);
        $TransID = end($TransID);

        $TransTime = explode("=",$content[2]);
        $TransTime = end($TransTime);

        $TransAmount = explode("=",$content[3]);
        $TransAmount = end($TransAmount);

        $BusinessShortCode = explode("=",$content[4]);
        $BusinessShortCode = end($BusinessShortCode);

        $BillRefNumber = explode("=",$content[5]);
        $BillRefNumber = end($BillRefNumber);

        $OrgAccountBalance = explode("=",$content[7]);
        $OrgAccountBalance = end($OrgAccountBalance);

        $MSISDN = explode("=",$content[9]);
        $MSISDN = end($MSISDN);

        $FirstName = explode("=",$content[10]);
        $MiddleName = explode("=",$content[11]);
        $LastName = explode("=",$content[12]);

        $FirstName = end($FirstName);
        $MiddleName = end($MiddleName);
        $LastName = end($LastName);

        $xml = '<?xml version="1.0" encoding="utf-8" ?><soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"><soapenv:Body><ns1:C2BPaymentConfirmationRequest xmlns:ns1="http://cps.huawei.com/cpsinterface/c2bpayment"><TransType>Pay Bill</TransType><TransID>'.$TransID.'</TransID><TransTime>'.$TransTime.'</TransTime><TransAmount>'.$TransAmount.'</TransAmount><BusinessShortCode>'.$BusinessShortCode.'</BusinessShortCode><BillRefNumber>'.$BillRefNumber.'</BillRefNumber><OrgAccountBalance>'.$OrgAccountBalance.'</OrgAccountBalance><MSISDN>'.$MSISDN.'</MSISDN><KYCInfo><KYCName>[Personal Details][First Name]</KYCName><KYCValue>'.$FirstName.'</KYCValue></KYCInfo><KYCInfo><KYCName>[Personal Details][Middle Name]</KYCName><KYCValue>'.$MiddleName.'</KYCValue></KYCInfo><KYCInfo><KYCName>[Personal Details][Last Name]</KYCName><KYCValue>'.$LastName.'</KYCValue></KYCInfo></ns1:C2BPaymentConfirmationRequest></soapenv:Body></soapenv:Envelope>';

        $payment = (new PaymentReceived($xml))->delay(5);
        $this->dispatch($payment);
    }

    public function collectionSheet($id) {
        $payment = Payment::find($id);
        //Get Collection Sheet
        $postURl = MIFOS_URL . "/centers/".$payment->account_no."?command=generateCollectionSheet&" . MIFOS_tenantIdentifier;

        $data = array();
        $data['dateFormat'] = 'dd MMMM yyyy';
        $data['locale'] = 'en_GB';
        $data['calendarId'] = 6;
        $data['transactionDate'] = Carbon::now()->format('d M Y');

        // post the encoded application details
        $collectionSheet = Hooks::MifosPostTransaction($postURl, json_encode($data));



        if(count($collectionSheet) == 0){
            session(['error' => 'Invalid account or centre ID']);
            return redirect()->back()->with('Error', 'Invalid account or centre ID');
        }
        //get totals
        $sum = array();
        $final_array = array();
        $totals_sum = array();
        foreach ($collectionSheet->groups as $group){
            $final_group = array();
            $group_sum = [
                'loan'=>array(),
                'savings'=> array(),
            ];


            foreach ($group->clients as $client){
                $final_client = array();
                //rearrange client loans
                if(isset($client->loans)) {

                    foreach ($client->loans as $loan) {

                        if(count($loan)>0) {
                            if(!isset($group_sum['loan'][$loan->productId])){
                                $group_sum['loan'][$loan->productId] = 0;
                            }
                            if(!isset($totals_sum['loan'][$loan->productId])){
                                $totals_sum['loan'][$loan->productId] = 0;
                            }
                            $final_client['loan'][$loan->productId] = $loan;
                            $group_sum['loan'][$loan->productId] = $group_sum['loan'][$loan->productId] + $loan->totalDue;
                            $totals_sum['loan'][$loan->productId] = $totals_sum['loan'][$loan->productId] + $loan->totalDue;
                        }
                    }
                }

                //rearrange client savings
                if(isset($client->savings)) {
                    foreach ($client->savings as $saving) {
                        if(!isset($group_sum['savings'][$saving->productId])) {
                            $group_sum['savings'][$saving->productId] =0;
                        }
                        if(!isset($totals_sum['savings'][$saving->productId])) {
                            $totals_sum['savings'][$saving->productId] =0;
                        }
                        $final_client['savings'][$saving->productId] = $saving;
                            $group_sum['savings'][$saving->productId] = $group_sum['savings'][$saving->productId] + $saving->dueAmount;
                            $totals_sum['savings'][$saving->productId] = $totals_sum['savings'][$saving->productId] + $saving->dueAmount;
                    }
                }
                $final_group[$client->clientId] = $final_client;
            }
            $sum[$group->groupId] = $group_sum;
            $final_array[$group->groupId]=$final_group;
        }

        //initializing loans
        if(!isset($totals_sum['savings'][7])) {
            $totals_sum['savings'][7] =0;
        }
        if(!isset($totals_sum['savings'][3])) {
            $totals_sum['savings'][3] =0;
        }
        if(!isset($totals_sum['loan'][2])) {
            $totals_sum['loan'][2] =0;
        }
        if(!isset($totals_sum['loan'][5])) {
            $totals_sum['loan'][5] =0;
        }
        if(!isset($totals_sum['loan'][6])) {
            $totals_sum['loan'][6] =0;
        }
        $success = 'CollectionSheet retrieved successfully';
        return view('payment.collection',compact('collectionSheet','success','sum','final_array','totals_sum'));
    }

    public function saveCollectionSheet($id){

    }

    public function collectionSheetPost(Request $request){
        return json_encode($request->all());
    }

    /**
     * Gets the loan details matching with the phone number of the transaction
     * Posts the loan repayment details to the respective loan id
     *
     * @param $note
     * @param $id
     * @return mixed
     */
    public function loanRepayment($note, $id) {
        // get details from payments table using the id
        $payment = Payment::findOrFail($id);

        if(($payment->status == 1)){
            //payment already processed
            return redirect()->back()->with('error', 'Payment had already been processed');
        }

        if(strtolower(trim($note)) == 'manual'){
            //update status and return with manual processed
            $payment->status =1;
            $payment->save();

            return redirect()->back()->with('success', 'Transaction successfully processed manually');

        }

        if(strtolower(trim($note)) == 'unrecognized'){
            //update status and return with unrecognized
            $payment->status =2;
            $payment->save();

            return redirect()->back()->with('success', 'Transaction moved to unrecognized tab');

        }
        if(strtolower(trim($note)) == 'comment'){
            // update comments column on payments table
            $payment->comments = Input::get('comment');
            $payment->save();

            return redirect()->back()->with('success', 'Comment successfully added to '.$payment->client_name);

        }
        // match the phone no from the payments table with loan details for Mifos from url
        $url = recipients_URL.$payment->phone;

        // receive the response and decode it
        $response = file_get_contents($url);     
        $response = json_decode($response);

        // check if the response is successful
        if(isset($response->success)) {
            // get the specific loan using the loan id
            $loanID = $response->loan_id;

            // get repayment details
            $repayment_data = [];
            $repayment_data['dateFormat'] = 'dd MMMM yyyy';
            $repayment_data['locale'] = 'en_GB';
            $repayment_data['transactionDate'] = Carbon::parse($payment->transaction_time)->format('j F Y');
            $repayment_data['transactionAmount'] = $payment->amount;
            $repayment_data['paymentTypeId'] = 1;
            $repayment_data['note'] = $note;
            $repayment_data['accountNumber'] = $payment->phone;
            $repayment_data['receiptNumber'] = $payment->transaction_id;

            // json encode repayment details
            $loan_data = json_encode($repayment_data);

            // url for posting the repayment details
            $postURl = MIFOS_URL."/loans/".$loanID."/transactions?command=repayment&".MIFOS_tenantIdentifier;

            // post the encoded repayment details
            $loanPayment = Hooks::MifosPostTransaction($postURl, $loan_data);

            // check if posting was successful
            if(!$loanPayment) {
                return redirect()->back()->with('error', 'An error has occurred, please try again');
            } else {
                // update status column in payments table to processed
                $payment->status =1;
                $payment->save();

                return redirect()->back()->with('success', 'Successfully conducted loan repayment for '.$payment->client_name);
            }
        } else {
            return redirect()->back()->with('error', 'An error has occurred, please try again');
        }
    }

    /**
     * Updates the comments column in the payments table
     *
     * @param $id
     * @return mixed
     */
    public function getTransactionClient($data){
        $user = FALSE;
        $externalid = $data['externalId'];

        $url = MIFOS_URL . "/clients?sqlSearch=(c.external_id%20like%20%27" . $externalid . "%27)&" . MIFOS_tenantIdentifier;
        // Get all clients
        $client = Hooks::MifosGetTransaction($url, $post_data = '');

        if (isset($client->totalFilteredRecords)) {
        if ($client->totalFilteredRecords == 0) {
            //search by phone
            $no = substr($data['externalId'], -9);

            $url = MIFOS_URL . "/clients?sqlSearch=(c.mobile_no%20like%20%270" . $no . "%27)&" . MIFOS_tenantIdentifier;

            // Get all clients
            $client = Hooks::MifosGetTransaction($url, $post_data = '');

            if ($client->totalFilteredRecords == 0) {
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

    public function getComment($id) {
        $payment = Payment::find($id)->toArray();

        $data = array();
        $data['externalId'] = Input::get('comment');
        $data['phone'] = 'invalid';
        $user = self::getTransactionClient($data);

        if(!$user){
            return redirect('/')->with('error', 'No user found with the given external id');
        }
        $payment['account_no'] = Input::get('comment');
        $payment['externalId'] = Input::get('comment');

        if(self::processUserLoan($payment)) {
            $payment = Payment::findOrFail($id);
            $payment->status = 1;
            $payment->account_no = Input::get('comment');
            $payment->update();
        return redirect('/')->with('success', 'Payment Procesed successfully');
        } else {
            $payment = Payment::findOrFail($id);
            $payment->status = 0;
            $payment->update();
        return redirect('/')->with('error', 'We had a problem processing payment for '.$payment->client_name);
        }
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


    public function processUserLoan($payment_data,$payment=null){

        //get user

        $user = self::getTransactionClient($payment_data);
//        if(!$user){
//            $payment->comment = "No User found with either the account provided or phone number of the payee";
//            $payment->save();
//        }

//        print_r($user);
//        exit;
        if($user) {
            $loanAccounts = self::getClientLoanAccountsInAscendingOrder($user->client_id);

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


    public function processLoan($payment_data,$payment=null){

        //get user

        $user = self::getTransactionClient($payment_data);
//        if(!$user){
//            $payment->comment = "No User found with either the account provided or phone number of the payee";
//            $payment->save();
//        }

        if($user) {
            $loanAccounts = self::getClientLoanAccountsInAscendingOrder($user->client_id);
            $latest_loan = end($loanAccounts);
            $loan_id = '';
            $loan = '';
            $loan_payment_received = $payment_data['amount'];
            foreach ($loanAccounts as $la) {
                if (($la->status->active == 1) && ($loan_payment_received > 0)) {

                    if (($la->loanBalance < $loan_payment_received) && ($la->id != $latest_loan->id)) {
                        $loan_payment_received = $loan_payment_received - $la->loanBalance;
                        $amount = $la->loanBalance;
                    } else {
                        $amount = $loan_payment_received;
                        $loan_payment_received = 0;
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
                } else {
                    $payment->comment = "user has no active loan";
                    $payment->save();
                }

                if ($loan_payment_received == 0) {
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
    }

    public function editExternalid($id) {
        $payment = Payment::findOrFail($id);
        $payment->comments = Input::get('externalid');
        $payment->update();

        return redirect('/')->with('success', 'Comment successfully added to '.$payment->client_name);
    }



    /**
     * Requests payment details from the database and encodes them into json
     *
     * @param Request $request
     * @param $id
     * @return string
     */
    public function getOutstandingLoan(Request $request, $id) {
        if($request->ajax()) {
            $payment = Payment::findOrFail($id);
            
            return json_encode($payment);
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
        if($jsonp[0] !== '[' && $jsonp[0] !== '{') {
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
    /**
     * Uploads payment details from an excel sheet to the database
     *
     * @param Request $request
     * @return mixed
     */

    public function uploadPayments(Request $request){
        $count = 0;
        ini_set('max_execution_time',0);

        $validator = Validator::make($request->all(), [
            'xls' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->with('error', 'Please upload an EXCEL file!');
        }

        $extension = $request->file('xls')->getClientOriginalExtension(); // getting the file extension

        if ($extension != 'xls' && $extension != 'xlsx') {
            return Redirect::back()
                ->with('error', 'Please upload a valid EXCEL file!');
        }

        $fileName = rand(11111,99999).'.'.$extension; // renaming the file

        $request->file('xls')->move('storage',$fileName); // move it to the storage folder in the public assets

        Excel::load('storage/'.$fileName, function($reader){
//          Excel::filter('chunk')->load('file.csv')->chunk(250, function($results)
//          Excel::filter('chunk')->load('storage/'.$fileName)->chunk(98,function ($reader){
//              foreach($reader as $row){

//              }
            $results = $reader->get();
            $results -> each(function($sheet,$count) {
//                print_r($sheet);
//exit;
                if(!isset($sheet->receipt_no)){
                    return Redirect::back()
                        ->with('error', 'please upload the right excel file!');
                }

                $transaction_id = $sheet->receipt_no;
                $payments = Payment::where('transaction_id', '=',$transaction_id)->first();

                if(is_null($payments)){
                    $exploded_other_party_info =  explode("-",$sheet->other_party_info);

                    $phone = '+'.trim($exploded_other_party_info[0]);

                    $client_name = $exploded_other_party_info[1];
                    $amount = $sheet->paid_in;
                    $status = $sheet->transaction_status;
                    $account_no = $sheet->ac_no;
                    if(!isset($account_no)){
                        $account_no = 'NULL';
                    }
                    $transaction_time = $sheet->completion_time;

//                    $transact_time=date('d/m/Y H:i:s', strtotime($transaction_time));

//                    $transact_time=Carbon::parse($transaction_time)->toDateTimeString();
                    $transaction_time=Carbon::createFromFormat('d-m-Y H:i:s', $transaction_time)->format('Y-m-d H:i:s');
//                    print_r($transaction_time);
//                    exit;
//
//                       print_r($transact_time);
//                         exit;
                    if($amount != null) {


                        DB::table('payments')->insert([
                            'phone' => $phone,
                            'client_name' => $client_name,
                            'transaction_id' => $transaction_id,
                            'amount' => $amount,
                            'account_no' => $account_no,
                            'transaction_time' => $transaction_time,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);

                        $payment = Payment::whereTransactionId($transaction_id)->first()->toArray();
                        $data = array();
                        $data['externalId'] = $account_no;
                        $data['phone'] = $phone;
                        $user = self::getTransactionClient($data);

                        if(!$user){
                        $payment['externalId'] = $account_no;
                        if(self::processUserLoan($payment)) {
                            $payment = Payment::whereTransactionId($transaction_id)->first();
                            $payment->status = 1;
                            $payment->update();
                            $count = $count+1;
                        }
                        }

//                        $data = array(
//                            'phone' => $phone,
//                            'client_name' => $client_name,
//                            'transaction_id' => $transaction_id,
//                            'paybill' => '949494',
//                            'account_no' => $account_no,
//                            'externalId' => $account_no,
//                            'amount' => $amount,
//                            'transaction_time' => $transaction_time,
////                            'api_key' => 'bSVff??$3CX-W&DQ=qvqfNg58#D2=3',
//                        );
//                        print_r($data);
//                        exit;
//                        $url = 'http://dev.oldstarehians.org/mpesa-payments/payment-notification';
//                        $url = 'http://oldstarehians.org/index.php/mpesa-payments/payment-notification';
//                        self::execute($data, $url);
                    }

                    global $success;
                    $success = true;
                }
            });
        });
        File::delete('storage/'.$fileName);
        global $success;
        if($success){
            return Redirect::back()->with('success', $count.' transactions successfully processed!');
        } else {
            return Redirect::back()->with('error', 'Transactions already exists!');
        }
    }
    /**
     * Lists payments made for Business Loans
     *
     * @return \Illuminate\View\View
     */
    public function businessLoanPaymentsIndex()
    {
        return view('payments.business_loan');
    }

    /**
     * Lists payments made for Payday Loans
     *
     * @return \Illuminate\View\View
     */
    public function paydayLoanPaymentsIndex()
    {
        return view('payments.payday_loan');
    }

    public function depositToDrawDownAccount($client_id,$amount,$data){

        $savingsaccounts = self::getClientSavingsAccountsInAscendingOrder($client_id);

        foreach ($savingsaccounts as $sa) {
            if ($sa->status->id == 300) {
                $deposit_data = [];
                $deposit_data['locale'] = 'en_GB';
                $deposit_data['dateFormat'] = 'dd MMMM yyyy';
                $deposit_data['transactionDate'] = Carbon::parse($data['transaction_time'])->format('j F Y');
                $deposit_data['transactionAmount'] = $amount;
                $deposit_data['paymentTypeId'] = 1;
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
                exit;
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

    public function processOldPayments(){
        $payments = Payment::whereStatus(0)->orderBy('id', 'DESC')->get();
        foreach ($payments as $payment){
            $payment = $payment->toArray();
            $payment['externalId'] = $payment['account_no'];
            if(self::processUserLoan($payment)) {
                $payment = Payment::findOrFail($payment['id']);
                $payment->status = 1;
                $payment->update();
            }
        }
    }
}
