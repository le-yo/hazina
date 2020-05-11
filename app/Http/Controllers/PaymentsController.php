<?php

namespace App\Http\Controllers;


use App\Hooks;
use App\Jobs\PaymentReceived;
use App\Log;
use App\Payment;
use App\TransactionLog;
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
$count = 0;
$failed = 0;

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
        $xml = new \DOMDocument();
        $xml->loadXML($input);
        if (($xml->getElementsByTagName('C2BPaymentValidationRequest')->length) > 0) {
            //TODO::check if payment is valid
            $data = ['content' => $input];
            // Create a transaction log object
            $transaction = TransactionLog::create($data);
            $center = $xml->getElementsByTagName('BillRefNumber')->item(0)->nodeValue;

            if(self::checkIfCenterExists($center)){
                $validationResponse = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:c2b="http://cps.huawei.com/cpsinterface/c2bpayment">
                        <soapenv:Header/>
                <soapenv:Body>
                    <c2b:C2BPaymentValidationResult>
                    <ResultCode>0</ResultCode>
	                    <ResultDesc>Service processing successful</ResultDesc>
	                    <ThirdPartyTransID>' . $transaction->id . '</ThirdPartyTransID>
                </c2b:C2BPaymentValidationResult>
                </soapenv:Body>
                </soapenv:Envelope>';
            }else{
                //get the phone

                $phone = "254".substr(trim($xml->getElementsByTagName('MSISDN')->item(0)->nodeValue), -9);
                $firstname = $xml->getElementsByTagName('KYCValue')->item(0)->nodeValue;
                $eng_message = "Dear ".$firstname.", your payment has not been received. Please use your Center ID as Account Number. Please contact your manager to get your Center ID.";
                $swa_message = $firstname.", pesa uliotuma haijakwenda. Tafadhali tumia nambari ya kikundi chako kama Account Number. Wasiliana na meneja wako kupata nambari ya kikundi.";

                self::sendSMS($phone,$eng_message);
                self::sendSMS($phone,$swa_message);

                $validationResponse = '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:c2b="http://cps.huawei.com/cpsinterface/c2bpayment">
                        <soapenv:Header/>
                <soapenv:Body>
                    <c2b:C2BPaymentValidationResult>
                    <ResultCode>C2B00012</ResultCode>
	                    <ResultDesc>Invalid Account Number</ResultDesc>
	                    <ThirdPartyTransID>' . $transaction->id . '</ThirdPartyTransID>
                </c2b:C2BPaymentValidationResult>
                </soapenv:Body>
                </soapenv:Envelope>';
            }
            header('Content-type: text/xml');
            echo trim($validationResponse);
            exit;
            }else{
            $payment = (new PaymentReceived($input))->delay(5);
            $this->dispatch($payment);
            $data = array();
            $data['code'] = 0;
            $data['message'] = "Payment received Successfully";
            return response()->json($data);
        }


    }

    public function sendSMS($to,$message){

        $data = ['slug' => 'send_sms_get', 'content' => $to." ".$message];
        //log request
        Log::create($data);
        $url = "http://rslr.connectbind.com:8080/bulksms/bulksms?username=itld-hazina&password=H4z1na5T&type=0&dlr=1&destination=".$to."&source=HAZINAGROUP&message=".urlencode($message);

        $ch = curl_init();
        $data = "";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        //curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        if ($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
        $dt = ['slug' => 'send_sms_response', 'content' => $data];
        //log response
        Log::create($dt);
        curl_close($ch);
        return $data;
    }

    public function checkIfCenterExists($id){
        $postURl = MIFOS_URL . "/centers/".$id."?command=generateCollectionSheet&" . MIFOS_tenantIdentifier;

        $data = array();
        $data['dateFormat'] = 'dd MMMM yyyy';
        $data['locale'] = 'en_GB';
        $data['calendarId'] = 7;
        $data['transactionDate'] = Carbon::now()->format('d M Y');

        // post the encoded application details
        $collectionSheet = Hooks::MifosPostTransaction($postURl, json_encode($data));
        if(isset($collectionSheet->dueDate)){
            return TRUE;
        }else{
            return FALSE;
        }
    }

    public function collectionSheet($id) {
        $payment = Payment::find($id);
        //Get Collection Sheet
        $postURl = MIFOS_URL . "/centers/".$payment->account_no."?command=generateCollectionSheet&" . MIFOS_tenantIdentifier;

        $data = array();
        $data['dateFormat'] = 'dd MMMM yyyy';
        $data['locale'] = 'en_GB';
        $data['calendarId'] = 7;
        $data['transactionDate'] = Carbon::now()->format('d M Y');

        // post the encoded application details
        $collectionSheet = Hooks::MifosPostTransaction($postURl, json_encode($data));


        if(!isset($collectionSheet->loanProducts)){
            session(['error' => 'Invalid account or centre ID']);
            return redirect()->back()->with('Error', 'Invalid account or centre ID');
        }

//        print_r($collectionSheet);
//        exit;
//        print_r($collectionSheet);
//        exit;
        //get totals
        $sum = array();
        $final_array = array();
        $totals_sum = array();
        //initialization
        foreach ($collectionSheet->loanProducts as $lp){
            $totals_sum['loan'][$lp->id] =0;
        }
        foreach ($collectionSheet->savingsProducts as $sp){
            $totals_sum['savings'][$sp->id] =0;
        }
        foreach ($collectionSheet->groups as $group){
            $final_group = array();

            foreach ($collectionSheet->loanProducts as $lp){
                $group_sum['loan'][$lp->id] =0;
            }
            foreach ($collectionSheet->savingsProducts as $sp){
                $group_sum['savings'][$sp->id] =0;
            }

            foreach ($group->clients as $client){
                $final_client = array();
                //rearrange client loans
                if(isset($client->loans)) {

                    foreach ($client->loans as $loan) {

                        if($loan) {
//                            if(!isset($group_sum['loan'][$loan->productId])){
//                                $group_sum['loan'][$loan->productId] = 0;
//                            }
//                            if(!isset($totals_sum['loan'][$loan->productId])){
//                                $totals_sum['loan'][$loan->productId] = 0;
//                            }
                            if(!isset($loan->chargesDue)){
                                $loan->chargesDue = 0;
                            }else{
                                $loan->totalDue = $loan->totalDue + $loan->chargesDue;
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
//                        if(!isset($group_sum['savings'][$saving->productId])) {
//                            $group_sum['savings'][$saving->productId] =0;
//                        }
//                        if(!isset($totals_sum['savings'][$saving->productId])) {
//                            $totals_sum['savings'][$saving->productId] =0;
//                        }
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
        $total_due=0;
       foreach ($totals_sum['loan'] as $ts){
            $total_due = $total_due + $ts;
       }
        foreach ($totals_sum['savings'] as $ts){
            $total_due = $total_due + $ts;
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
        return view('payment.newcollection',compact('collectionSheet','success','sum','final_array','totals_sum','payment','total_due'));
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
            $repayment_data['paymentTypeId'] = 6;
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
    public function getComment($id) {
        $payment = Payment::findOrFail($id);
        $payment->comments = Input::get('comment');
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
                                    $payment = Payment::whereId($payment_data['id'])->first();
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


    public function processLoan($data){
        // match the phone no from the payments table with loan details for Mifos from url
        if($data['paybill'] == 650880) {
            $url = recipients_BLP_URL.$data['phone'];
        } elseif ($data['paybill'] == 682684) {
            $url = recipients_PCL_URL.$data['phone'];
        }

        // receive the response and decode it
        $response = file_get_contents($url);
        $receipt = self::jsonp_decode($response);

        // check if the response is successful
        if(isset($receipt->success)) {
            // get the specific loan using the loan id
            $loanID = $receipt->loan->id;

            // get repayment details
            $repayment_data = [];
            $repayment_data['dateFormat'] = 'dd MMMM yyyy';
            $repayment_data['locale'] = 'en_GB';
            $repayment_data['transactionDate'] = Carbon::parse($data['transaction_time'])->format('j F Y');
            $repayment_data['transactionAmount'] = $data['amount'];
            $repayment_data['paymentTypeId'] = 6;
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
                return true;
            }
        }else{
            return false;
        }
    }

    /**
     * Uploads payment details from an excel sheet to the database
     *
     * @param Request $request
     * @return mixed
     */
    public function uploadPayments(Request $request){

        $validator = Validator::make($request->all(), [
            'file' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->with('error', 'Please upload an EXCEL file!');
        }

        // getting the file extension
        $extension = $request->file('file')->getClientOriginalExtension();

        // validate if it is a valid excel sheet
        if ($extension != 'xls') {
            $data = [
              'error'=>  "Please upload a valid EXCEL file"
            ];
            return json_encode($data);
//            return Redirect::back()
//                ->with('error', 'Please upload a valid EXCEL file!');
        }

        // get the original name
//        $originalName = $request->file('file')->getClientOriginalName();
//        $exploaded_name = explode(".", $originalName);
//        $paybill = $exploaded_name[0];
//
//        // check if the excel has been named accordingly
//        if($paybill !== STL_PAYBILL && $paybill !== BLP_PAYBILL && $paybill !== PCL_PAYBILL) {
//            $data = [
//                'error'=>  "Please rename the excel to the respective paybill number and make sure all transactions are of that number"
//            ];
//            return json_encode($data);
//        }

        // renaming the file
        $fileName = rand(11111,99999).'.'.$extension;

        // move it to the storage folder in the public assets
        $request->file('file')->move('storage',$fileName);

        global $count;
        $count = 0;
        global $failed;
        $failed = 0;
        // Load the excel
        Excel::load('storage/'.$fileName, function($reader) use ($count) {

            $results = $reader->get();

            // Loop through each row in the sheet
            $results -> each(function($sheet) use ($count) {
                // Check if the excel loaded has the required format

                $paybill = $sheet->paybill;
                if(!isset($sheet->receipt_no)){
                    $data = [
                        'error'=>  "Excel is not in the right format. Kindly check the file"
                    ];
                    return json_encode($data);
                }

                $transaction_id = $sheet->receipt_no;

                $payments = Payment::where('transaction_id', '=',$transaction_id)->first();

                // Check if the payment transaction exists
                if(is_null($payments)){

                    $exploded_other_party_info = explode("-",$sheet->other_party_info);

                    $phone = '+'.trim($exploded_other_party_info[0]);
                    $client_name = $exploded_other_party_info[1];
                    $amount = $sheet->paid_in;
                        $data = [];
                        $data['phone'] = $phone;
                        $data['paybill'] = $paybill;
                        $data['account_no'] = $sheet->ac_no;
                        $account_no = $sheet->ac_no;
                        if(self::processUserLoan($data) == true){
                            global $count;
                            $count = $count+1;
                            $status = 1;
                        } else {
                            global $failed;
                            $failed = $failed+1;
                            $status = 2;
                        }
                    if(!isset($account_no)){
                       $account_no = 'NULL';
                    }
                    $transaction_time = $sheet->completion_time;
                    if(strpos("/",$transaction_time) !==false){
                       $transaction_time = Carbon::createFromFormat('d/m/Y H:i:s', $transaction_time)->format('Y-m-d H:i:s');
                    }else{
                       $transaction_time = Carbon::createFromFormat('d-m-Y H:i:s', $transaction_time)->format('Y-m-d H:i:s');
                    }

                    // Add the payment object to the payments table
                    DB::table('payments')->insert([
                        'phone' => $phone,
                        'client_name' => $client_name,
                        'transaction_id' => $transaction_id,
                        'status' => $status,
                        'amount' => $amount,
                        'paybill' => $paybill,
                        'account_no' => $account_no,
                        'transaction_time' => $transaction_time,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    global $success;
                    $success = true;
                }
            });
        });

        // deletes the excel once it has been used
        File::delete('public/storage/'.$fileName);

        global $success;
        global $count;
        global $failed;
        if($success){
            $data = [
              'success'=>  $count.' transactions successfully uploaded. '.$failed.' transactions failed'
            ];
            return json_encode($data);
//            return Redirect::back()->with('success', $count.' transactions successfully uploaded!');
        } else {
            $data = [
                'success'=>  $count.' transactions successfully uploaded. '.$failed.' transactions failed'
            ];
            return json_encode($data);

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
//        foreach ($savingsaccounts as $sa) {
//            if ($sa->status->id == 300) {
//                $deposit_data = [];
//                $deposit_data['locale'] = 'en_GB';
//                $deposit_data['dateFormat'] = 'dd MMMM yyyy';
//                $deposit_data['transactionDate'] = Carbon::parse($data['transaction_time'])->format('j F Y');
//                $deposit_data['transactionAmount'] = $amount;
//                $deposit_data['paymentTypeId'] = 6;
//                $deposit_data['accountNumber'] = $data['phone'];
//                $deposit_data['receiptNumber'] = $data['transaction_id'];
//                $deposit_data = json_encode($deposit_data);
//
//                // url for posting the repayment details
//                $postURl = MIFOS_URL . "/savingsaccounts/" . $sa->id . "/transactions?command=deposit&" . MIFOS_tenantIdentifier;
//                // post the encoded repayment details
//                $savingsPayment = Hooks::MifosPostTransaction($postURl, $deposit_data);
//
//                // check if posting was successful
//                if (array_key_exists('errors', $savingsPayment)) {
////                        $payment->comment = "Problem processing loan repayment";
////                        $payment->save();
//                    return false;
//                } else {
//                    return $savingsPayment;
//                }
//            }
//
//        }

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
