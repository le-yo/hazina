<?php namespace App\Http\Controllers;

use App\HookLogsModel;
use App\Hooks;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\ussd_logs;
use App\Ussduser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HooksController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    //user_creation hook
    public function user_activated_hook(Request $request)
    {
        //Get json from mifos
        $request->headers->set('content-type', 'application/json');
        $d = $request->all();

//        HookLogsModel::create(['details' => json_encode($d)]);
    echo "received";
        $client = self::getUser($d['clientId']);

        //get client
        if ($client) {

            //get Loan limit
            $loan_limit = self::getClientLimit($client->id);
//            $name = self::getClientName($client->id);
            echo "loan_limit".$loan_limit;
            //Send messages
            if ($loan_limit) {
                $message = 'Dear {name}, you can now apply for a Salary Advance Loan of up-to Kshs. '.number_format($loan_limit,2).' through your phone. Dial *696# to generate your PIN. For further assistance please call our customer care line: '.env('CUSTOMERCARE_NUMBER');
            } else {
                $message = "Dear {name}, you can now apply for a Salary Advance Loan through your phone. Dial *696# to generate your PIN. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
            }
            $message = str_replace("{name}",$client->displayName,$message);
            $message = str_replace("{limit}",$loan_limit,$message);
            echo "sending message...";
            print_r(self::sendMessage($client->mobileNo, $message));
            echo "message sent...";

        }


    }
    public function getClientName($clientId)
    {
        $url = MIFOS_URL."/clients/".$clientId."?".MIFOS_tenantIdentifier;

        $client = Hooks::MifosGetTransaction($url, $post_data = "");

        $clientName = $client->displayName;

        return ucfirst($clientName);
    }
    public function user_edit_hook(Request $request)
    {
        //Get json from mifos
        $request->headers->set('content-type', 'application/json');
        $d = $request->all();
//        print_r($d);
//        exit;

        HookLogsModel::create(['details' => json_encode($d)]);
    echo "received";
        $client = self::getUser($d['clientId']);
//        print_r($client);
//        exit;
        //get client
        if ($client) {

            $user = Ussduser::whereClientId($d['clientId'])->first();
            $usr = array();
            $usr['name'] = $client->displayName;
            $usr['client_id'] = $client->id;
            $usr['office_id'] = $client->officeId;
            $usr['phone_no'] = $client->mobileNo;
            $usr['email'] = $client->mobileNo;
            $usr['session'] = 0;
            $usr['pin'] = 0;
            $usr['progress'] = 0;
            $usr['email'] = $client->mobileNo;
            $usr['confirm_from'] = 0;
            $usr['menu_item_id'] = 0;
            $usr['is_pcl_user'] = 1;
            if($client->status->code == 'clientStatusType.active'){
                $usr['active_status'] = 1;
            }else{
                $usr['active_status'] = 0;
            }
            $user = Ussduser::whereClientId($d['clientId'])->first();
            if($user){
                DB::table('users')->where('id',$user->id)->update($usr);
                $message = "Dear ".$client->displayName.", your registered phone number has successfully been changed to ".$client->mobileNo." as per your request. Please dial *696# to access our services. For further queries contact us on 0704000999";
                echo $message;
                if(isset($d['changes']['mobileNo'])){
                self::sendMessage($client->mobileNo,$message);
                }
                echo "hapa";
            }
//            else{
//                $user = Ussduser::create($usr);
//            }

        }


    }

    public function getUser($client_id)
    {

        $url = MIFOS_URL . "/clients/" . $client_id . "?" . MIFOS_tenantIdentifier;
        $client = Hooks::MifosGetTransaction($url, $post_data = "");

        return $client;

    }

    public function getClientLimit($client_id)
    {
        $url = MIFOS_URL . "/datatables/user_loan_limit/" . $client_id . "?" . MIFOS_tenantIdentifier;
        $limit = Hooks::MifosGetTransaction($url, $post_data = "");
        if (count($limit) > 0) {
            return $limit[0]->user_loan_limit;
        } else {
//            $url = MIFOS_URL . "/datatables/Gross Salary/" . $client_id . "?" . MIFOS_tenantIdentifier;
//            $limit = Hooks::MifosGetTransaction(urlencode($url), $post_data = "");
//            if (count($limit) > 0) {
//                return number_format($limit[0]->gross_salary*2/3,2);
//            }else{
            return 0;
//            }
        }

    }

    public function loan_approved_hook(Request $request)
    {
        $request->headers->set('content-type', 'application/json');
        $d = $request->all();


        /**
         * Log the hook data
         */
        HookLogsModel::create(['details' => json_encode($d)]);

        if ($d) {
            $client_id = $d['clientId'];
            $group_id = self::getUserGroupId($client_id);
            $client = self::getUser($client_id);

            $loan = self::getloan($d['loanId']);
        }
        if ($group_id) {
            $group = self::getGroupMembers($group_id);
            if ($group) {

                $group_members_phones = [];
                if ($group->clientMembers) {
                    foreach ($group->clientMembers as $group_member) {
                        if ((!empty($group_member->mobileNo)) && ($group_member->mobileNo != $client->mobileNo)) {
                            $phone = $group_member->mobileNo;
                            array_push($group_members_phones, $phone);
                        }
                    }
                }

                //check the loan product id. we may need to call to find the product id.
                $client_loan = self::getLoan($d['loanId']);
                if ($client_loan->loanProductId == STL_ID) {
                    $message = "Ms " . $client->displayName . " requested a loan of Ksh. ".number_format($loan->principal).". Please SMS “Reject” within the next 15 minutes to number 40994 if you DON'T agree with the loan";
                    $notify = new NotifyController();
                    $recipients = implode(',', $group_members_phones);

                    $notify->sendSms($recipients, $message);

                    $send_loan_data = env('LOANS_URL').$client->mobileNo."/".$loan->id;
                    file_get_contents($send_loan_data);
                } elseif ($client_loan->loanProductId == BLP_ID) {
                    $message = "Ms " . $client->displayName . " requested a loan of Ksh. ".number_format($loan->principal).". Please SMS “Reject” within the next 15 minutes to number 40994 if you DON'T agree with the loan";
                    $notify = new NotifyController();
                    $recipients = implode(',', $group_members_phones);

                    $notify->sendSms($recipients, $message);
                    $send_loan_data = env('LOANS_URL').$client->mobileNo."/".$loan->id;
                    file_get_contents($send_loan_data);

                } elseif ($client_loan->loanProductId == PCL_ID || $client_loan->loanProductId == ASF_ID) {
                    // $message = "Ms " . $client->displayName . " requested a paycheque loan of Ksh" . $loan->principal . ". Please SMS “Reject” within the next 15 minutes to number 40994 if you DON'T agree with the loan";
                    // $notify = new NotifyController();
                    // $recipients = implode(',', $group_members_phones);

                    // $notify->sendSms($recipients, $message);
                    $send_loan_data = env('LOANS_URL').$client->mobileNo."/".$loan->id;
                    file_get_contents($send_loan_data);
                }

            }

        }


        //get the group_id from the user

        //get the group members

        //send the the message
    }

    public function loan_extension_hook(Request $request)
    {
        $request->headers->set('content-type', 'application/json');
        $d = $request->all();


        /**
         * Log the hook data
         */
        HookLogsModel::create(['details' => json_encode($d)]);
    }


    public function loan_repayment_hook(Request $request)
    {
        $request->headers->set('content-type', 'application/json');
        $d = $request->all();
        if (is_array($d)) {

            $client = self::getUser($d['clientId']);
            //print_r($client_loan->loanProductId);exit;
            if ($client) {
                
                //check the loan product id. we may need to call to find the product id.
                $client_loan = self::getLoan($d['loanId']);
                //if loan product id is 1 continue
                if($client_loan->loanProductId == STL_ID) {
                    if ($d['changes']['note']) {
                        $note = trim(strtolower($d['changes']['note']));
                        switch ($note) {
                            case "full":
                                $loan_limit = self::getClientLimit($d['clientId']);
                                if($loan_limit > 1) {
                                    $message = "Dear Customer, Your loan has been fully repaid. You are eligible for another loan. Your loan limit is Ksh. ".number_format($loan_limit).". Thank You for being our valued customer.";
                                }else{
                                    $message = "Dear Customer, Your loan has been fully repaid. Thank You for being our valued customer.";
                                }
                                break;
                            case "fully paid":
                                $loan_limit = self::getClientLimit($d['clientId']);
                                if($loan_limit > 1) {
                                    $message = "Dear Customer, Your loan has been fully repaid. You are eligible for another loan. Your loan limit is Ksh. ".number_format($loan_limit).". Thank You for being our valued customer.";
                                }else{
                                    $message = "Dear Customer, Your loan has been fully repaid. Thank You for being our valued customer.";
                                }
                                break;
                            case "ext":
                                $loan = self::getLoan($d['loanId']);
                                $maturity_date = self::getLoanMaturityDateGivenLoanId($d['loanId']);
                                $balance = $loan->summary->totalOutstanding;
                                $message = "Dear Customer, Thank you for your payment! Your loan has been extended. New repayment date is ".$maturity_date.". Remaining balance is Ksh. ".number_format($balance).".";
                                break;
                            case "payment":
                                $loan = self::getLoan($d['loanId']);
                                $maturity_date = self::getLoanMaturityDateGivenLoanId($d['loanId']);
                                $balance = $loan->summary->totalOutstanding;
                                $response = self::getNextPayment($d['loanId']);
                                if($response['overdue'] > 0){
                                    $message = "Dear Customer, Thank you for your payment! We have received Ksh. ".number_format($d['changes']['transactionAmount']).". Remaining balance is Ksh. ".number_format($response['next_payment']).". Repayment date ".$response['next_date'].".";
                                }
                                else {
                                    $message = "Dear Customer, Thank you for your payment! We have received Ksh. ".number_format($d['changes']['transactionAmount']).". Remaining balance is Ksh. ".number_format($balance).". Repayment date ".$maturity_date.".";
                                }
                                break;
                            default:
                                break;
                        }
                    }
                    $notify = new NotifyController();
                    $notify->sendSms($client->mobileNo,$message);
                } elseif ($client_loan->loanProductId == BLP_ID || $client_loan->loanProductId == ASF_ID) { //if product id is 3 format message accordingly
                    // Get the loan details
                    // print_r($items);exit;
                    $loan_limit = self::getClientLimit($d['clientId']);
                    $loan = self::getLoan($d['loanId']);

                    if($loan->summary->totalOutstanding > 0){
                        $response = self::getNextPayment($d['loanId']);

                        if($response['overdue'] > 0){
                            $today = Carbon::now()->format('j F Y');
                            if ($response['next_date'] == $today)
                            {
                                $message = "Dear Customer, Thank you for your payment! We have received Ksh. ".number_format($d['changes']['transactionAmount']).". You still have an outstanding payment due today. Kindly pay additional Ksh. ".number_format($response['next_payment']).".";
                            } else
                            {
                                $message = "Dear Customer, Thank you for your payment! We have received Ksh. ".number_format($d['changes']['transactionAmount']).". You still have an outstanding payment due ".$response['next_date'].". Kindly pay additional Ksh. ".number_format($response['next_payment']).".";
                            }
                        }else{
                            $message = "Dear Customer, Thank you for your payment! We have received Ksh. ".number_format($d['changes']['transactionAmount']).". Your next payment is Ksh. ".number_format($response['next_payment'])." on ".$response['next_date'].". ".($client_loan->loanProductId == ASF_ID ? '' : 'Total remaining loan Ksh. '.number_format($loan->summary->totalOutstanding).'.')."";
                        }
                    }else{
                        $message = "Dear Customer, Your loan is fully repaid. You are eligible for another loan. Thank You for being our valued customer.";
                    }
                    $notify = new NotifyController();
                    $notify->sendSms($client->mobileNo,$message);
                } elseif ($client_loan->loanProductId == PCL_ID) {
                    $loan = self::getLoan($d['loanId']);

                    if($loan->summary->totalOutstanding > 0){
                        $response = self::getNextPayment($d['loanId']);

                        if($response['overdue'] > 0){
                            $today = Carbon::now()->format('j F Y');
                            if ($response['next_date'] == $today)
                            {
                                $message = "Dear Customer, Thank you for your payment! We have received Ksh. ".number_format($d['changes']['transactionAmount']).". You still have an outstanding payment due today. Kindly pay additional Ksh. ".number_format($response['next_payment']).".";
                            } else
                            {
                                $message = "Dear Customer, Thank you for your payment! We have received Ksh. ".number_format($d['changes']['transactionAmount']).". You still have an outstanding payment due ".$response['next_date'].". Kindly pay additional Ksh. ".number_format($response['next_payment']).".";
                            }
                        }else{
                            $message = "Dear Customer, Thank you for your payment! We have received Ksh. ".number_format($d['changes']['transactionAmount']).". Your next payment is Ksh. ".number_format($response['next_payment'])." on ".$response['next_date'].". Total remaining loan Ksh. ".number_format($loan->summary->totalOutstanding).".";
                        }
                    }else{
                        $message = "Dear Customer, your loan is fully repaid. Thank You for being our valued customer.";
                    }
                    $notify = new NotifyController();
                    $notify->sendSms($client->mobileNo,$message);

                }

            }
        }


        /**
         * Log the hook data
         */
        HookLogsModel::create(['details' => json_encode($d)]);
    }
    public function getNextPayment($loan_id){
        $next_payment = 0;
        $overdue = 0;
        $next_date = '';

        // Get the loan details
        $loan = self::getLoan($loan_id);

        // Initialize empty array
        $items = [];

        // Get the periods for the schedule
        $paymentPeriods = $loan->repaymentSchedule->periods;

        // Loop through all the periods
        for ($i = 0; $i < count($paymentPeriods); $i++)
        {
            // Push only the peroids that have not been paid for
            if (array_key_exists('complete', $paymentPeriods[$i]) && $paymentPeriods[$i]->complete == false) {
                array_push($items, $paymentPeriods[$i]);
            }
        }

        // Get the Dates
        $today = Carbon::now()->format('Y m d');
        $dueDate = Carbon::parse($items[0]->dueDate[0].'-'.$items[0]->dueDate[1].'-'.$items[0]->dueDate[2])->format('Y m d');

        $message = '';
        foreach ($items as $key=>$value){
            $date = Carbon::parse($value->dueDate[0].'-'.$value->dueDate[1].'-'.$value->dueDate[2])->format('d-m-Y');
            $message = $message.$date." : Kshs. ".number_format($value->totalOutstandingForPeriod,2).PHP_EOL;
        }
        // Check if due date has passed and add overdue charges
        if ($dueDate < $today)
        {
            $next_payment = $items[0]->totalOutstandingForPeriod;
            $next_date = Carbon::parse($items[0]->dueDate[0].'-'.$items[0]->dueDate[1].'-'.$items[0]->dueDate[2])->format('j F Y');
            $overdue = $next_payment;
        }
        elseif ($dueDate > $today)
        {
            $next_payment = $items[0]->totalOutstandingForPeriod;
            $next_date = Carbon::parse($items[0]->dueDate[0].'-'.$items[0]->dueDate[1].'-'.$items[0]->dueDate[2])->format('j F Y');
            $overdue = 0;
        }
        elseif($dueDate == $today)
        {
            $next_payment = $items[0]->totalOutstandingForPeriod;
            $next_date = Carbon::parse($items[0]->dueDate[0].'-'.$items[0]->dueDate[1].'-'.$items[0]->dueDate[2])->format('j F Y');
            $overdue = $next_payment;
        }

        // Store the data in a response
        $response = array(
          'next_payment' => $next_payment,
          'next_date' => $next_date,
          'overdue' => $overdue,
            'schedule'=>$message,
        );

        return $response;
    }
    public function sendMessage($to, $message)
    {
        $notify = new NotifyController();
        $notify->sendSms($to, $message);
    }

    public function getGroupMembers($group_id)
    {
        $url = MIFOS_URL . "/groups/" . $group_id . "?associations=clientMembers&" . MIFOS_tenantIdentifier;
        $group = Hooks::MifosGetTransaction($url, $post_data = "");
        return $group;

    }

    public function getLoan($loan_id)
    {
        $url = MIFOS_URL . "/loans/" . $loan_id . "?associations=repaymentSchedule&" . MIFOS_tenantIdentifier;
        $loan = Hooks::MifosGetTransaction($url, $post_data = "");

        return $loan;
    }


    /**
     * fetches the client group_id
     *
     * @param $user_id
     * @return int group_id
     */
    protected function getUserGroupId($user_id)
    {
        $url = MIFOS_URL . "/clients/" . $user_id . "?" . MIFOS_tenantIdentifier;
        $user = Hooks::MifosGetTransaction($url, $post_data = "");

        $groups = $user->groups;

        return $groups[0]->id;

    }

    public function loan_disbursed_hook(Request $request)
    {
        $request->headers->set('content-type', 'application/json');
        $d = $request->all();


        /**
         * Log the hook data
         */
        HookLogsModel::create(['details' => json_encode($d)]);

        if ($d) {
            $client_id = $d['clientId'];
            $client = self::getUser($client_id);

            //check the loan product id. we may need to call to find the product id.
            $client_loan = self::getLoan($d['loanId']);
//            print_r($client_loan);exit;
                $response = self::getNextPayment($d['loanId']);
                $schedule = $response['schedule'];
                $message = "Dear ".$client->displayName.", your loan Kshs. ".number_format($client_loan->summary->principalDisbursed,2).", has been disbursed to your M-Pesa Account. The loan must be repaid via our M-pesa paybill Number 963334 on or before the due date(s):".PHP_EOL.$schedule."For further assistance please call our customer care line ".env('CUSTOMERCARE_NUMBER');
                self::sendMessage($client->mobileNo, $message);
                $send_loan_data = env('LOANS_URL').$client->mobileNo."/".$client_loan->id;
                file_get_contents($send_loan_data);
        }
    }


    /**
     * @param $loanId
     * @return string - example; 23 March 2016
     */
    protected function getLoanMaturityDateGivenLoanId($loanId)
    {
        $loan = self::getLoan($loanId);
        $expectedMaturityDate = $loan->timeline->expectedMaturityDate;

        $due_date = Carbon::createFromDate($expectedMaturityDate[0], $expectedMaturityDate[1], $expectedMaturityDate[2])->format('j F Y');

        return $due_date;
    }

    /**
     * @param $loanId
     * @return string - example; 23 March 2016
     */
    protected function getExpectedFirstRepaymentDateGivenLoanId($loan_Id)
    {
        $loan = self::getLoan($loan_Id);
        $expectedFirstRepaymentDate = $loan->expectedFirstRepaymentOnDate;
        $repayment_date = Carbon::createFromDate($expectedFirstRepaymentDate[0], $expectedFirstRepaymentDate[1], $expectedFirstRepaymentDate[2])->format('j F Y');
        return $repayment_date;
    }

    public function getLoanRepaymentSchedule($loan_id)
    {
        $loan = self::getLoan($loan_id);
        //$url = MIFOS_URL . "/rescheduleloans/1?command=previewLoanReschedule&" . MIFOS_tenantIdentifier;
        //$loan = Hooks::MifosGetTransaction($url, $post_data = "");
//      loa

        print_r($loan);
        exit;
        return $loan;
    }



    public function getNextPaymentDate($loan_id)
    {
        $loan = self::getLoan($loan_id);
        $disbursement_date = Carbon::createFromDate($loan->timeline->actualDisbursementDate[0], $loan->timeline->actualDisbursementDate[1], $loan->timeline->actualDisbursementDate[2]);
        $diff = Carbon::now()->diffInMonths($disbursement_date);

        $next_date = $disbursement_date->addMonth($diff+1);
        return $next_date->toDateString();
    }

    public function getNextExpectedPayment($loan_id){

    }

}
