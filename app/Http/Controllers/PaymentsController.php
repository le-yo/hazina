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
            'xls' => 'required',
        ]);

        if ($validator->fails()) {
            return Redirect::back()
                ->with('error', 'Please upload an EXCEL file!');
        }

        // getting the file extension
        $extension = $request->file('xls')->getClientOriginalExtension();

        // validate if it is a valid excel sheet
        if ($extension != 'xls') {
            return Redirect::back()
                ->with('error', 'Please upload a valid EXCEL file!');
        }

        // get the original name
        $originalName = $request->file('xls')->getClientOriginalName();
        $exploaded_name = explode(".", $originalName);
        $paybill = $exploaded_name[0];

        // check if the excel has been named accordingly
        if($paybill !== STL_PAYBILL && $paybill !== BLP_PAYBILL && $paybill !== PCL_PAYBILL) {
            return Redirect::back()->with('error', 'Please rename the excel to the respective paybill number and make sure all transactions are of that number!');
        }

        // renaming the file
        $fileName = rand(11111,99999).'.'.$extension;

        // move it to the storage folder in the public assets
        $request->file('xls')->move('storage',$fileName);

        // Load the excel
        Excel::load('storage/'.$fileName, function($reader) use ($paybill) {
            $results = $reader->get();

            // Loop through each row in the sheet
            $results -> each(function($sheet) use ($paybill) {
                // Check if the excel loaded has the required format
                if(!isset($sheet->receipt_no)){
                    return Redirect::back()->with('error', 'Please upload the right excel file!');
                }

                $transaction_id = $sheet->receipt_no;
                $payments = Payment::where('transaction_id', '=',$transaction_id)->first();

                // Check if the payment transaction exists
                if(is_null($payments)){
                    $exploded_other_party_info = explode("-",$sheet->other_party_info);

                    $phone = '+'.trim($exploded_other_party_info[0]);
                    $client_name = $exploded_other_party_info[1];
                    $amount = $sheet->paid_in;
                    if ($paybill == BLP_PAYBILL || $paybill == PCL_PAYBILL) {
                        $data = [];
                        $data['phone'] = $phone;
                        $data['paybill'] = $paybill;
                        if(self::processLoan($data) == true){
                            $status = 1;
                        } else {
                            $status = 2;
                        }
                    } else {
                        $status = null;
                    }
                    $account_no = $sheet->ac_no;
                    if(!isset($account_no)){
                       $account_no = 'NULL';
                    }
                    $transaction_time = $sheet->completion_time;

                    // Add the payment object to the payments table
                    DB::table('payments')->insert([
                        'phone' => $phone,
                        'client_name' => $client_name,
                        'transaction_id' => $transaction_id,
                        'status' => $status,
                        'amount' => $amount,
                        'paybill' => $paybill,
                        'account_no' => $account_no,
                        'transaction_time' => Carbon::createFromFormat('d/m/Y H:i:s', $transaction_time)->format('Y-m-d H:i:s'),
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
        if($success){
            return Redirect::back()->with('success', 'Transactions successfully uploaded!');
        } else {
            return Redirect::back()->with('error', 'Some transactions already exist!');

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
}
