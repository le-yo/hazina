<?php namespace App\Http\Controllers;

use App\Configuration;
use App\Helpers\Mifos\MifosHooks;
use App\Hooks;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\menu;
use App\menu_items;
use App\PreapprovedClients;
use App\ussd_logs;
use App\ussd_response;
use App\Ussduser;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\NotifyController;

use App\Dash;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Security\Core\User\User;

class UssdController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $payment_data = array();
        $payment_data['externalId'] = "254705337894";
        $payment_data['phone'] = "254705337894";
        $payment_controller = new PaymentsController();
        $user = $payment_controller->getTransactionClient($payment_data);
        exit;

        $url = MIFOS_URL . "/clients?sqlSearch=(c.externalId=" . $externalid . ")&" . MIFOS_tenantIdentifier;


        $client = Hooks::MifosGetTransaction($url);
        print_r($client);
        exit;
        error_reporting(0);
        header('Content-type: text/plain');
        set_time_limit(100000);

        //get inputs
        $sessionId = $_REQUEST["sessionId"];
        $serviceCode = $_REQUEST["serviceCode"];
        $phoneNumber = $_REQUEST["phoneNumber"];
        $text = $_REQUEST["text"];   //


        $exploded_text = '';
        if (!empty($text)) {
            //getExtension URL
            $exploded_text = explode("*", $text, 2);
            $choice = $exploded_text[0];
        }


        $data = ['phone' => $phoneNumber, 'text' => $text, 'service_code' => $serviceCode, 'session_id' => $sessionId];

        //log USSD request
        ussd_logs::create($data);

        //verify that the user exists
        $no = substr($phoneNumber, -9);

        $user = UssdUser::where('phone_no', "0" . $no)->orWhere('phone_no', "254" . $no)->orWhere('email', "254" . $no)->first();

        if (!$user) {
            $user = self::verifyPhonefromMifos($no);
        }
        if (self::user_is_starting($text)) {

            //lets get the home menu
            //reset user
            if ($user) {
                self::resetUser($user);
            }

            //user authentication
            $message = '';
            $response = self::authenticateUser($user, $message);
            self::sendResponse($response, 1, $user);
        } else {
            //message is the latest stuff
            $result = explode("*", $text);
            if (empty($result)) {
                $message = $text;
            } else {
                end($result);
                // move the internal pointer to the end of the array
                $message = current($result);
            }
            //store ussd response


            //switch based on user session

            switch ($user->session) {

                case 0 :
                    //neutral user
                    break;
                case 1 :
                    //user authentication
                    $response = self::authenticateUser($user, $message);
                    break;
                case 2 :
                    $response = self::continueUssdProgress($user, $message);
                    //echo "Main Menu";
                    break;
                case 3 :
                    //confirm USSD Process
                    $response = self::confirmUssdProcess($user, $message);
                    break;
                case 4 :
                    //Go back menu
                    $response = self::confirmGoBack($user, $message);
                    break;
                case 5 :
                    //Go back menu
                    $response = self::resetPIN($user, $message);
                    break;
                case 6 :
                    //accept terms and conditions
                    $response = self::acceptTerms($user, $message);
                    break;
                default:
                    break;
            }

            self::sendResponse($response, 1, $user);
        }


    }

    public function selfRegistration($user, $message)
    {
        $response = '';
        switch ($user->progress) {

            case 0 :
                $menu = menu::find(9);
                $response = self::nextMenuSwitch($user, $menu);
                $message = "Dear esteemed Client, thank you for your interest in our Salary Advance Loan product. Please read and understand our terms and conditions on www.unilimited.co.ke";
                $notify = new NotifyController();
                $notify->sendSms($user->phone_no, $message);
                self::sendResponse($response, 1);
                break;
            case 1 :
                //check terms and conditions acceptance
                if (self::validationVariations($message, 1, "yes")) {

                } elseif (self::validationVariations($message, 2, "no")) {
                    $response = "Thank you for using Uni Limited.";
                    self::sendResponse($response, 3);
                } else {
                    //not confirmed
                    $response = "Please enter 1 or 2";
                    //restart the process
                    $output = "By proceeding you agree to the terms and conditions on www.unilimited.co.ke" . PHP_EOL . '1. I Agree' . PHP_EOL . '2. I Disagree';
                    $response = $response . PHP_EOL . $output;
                    return $response;
                }
                break;
            case 2 :
                if (1 === preg_match('~[0-9]~', $message)) {
                    $response = "Name should not contain numbers." . PHP_EOL;
                    $user->progress = $user->progress - 1;
                    $user->save();
                } else {

                    $exploded_name = explode(" ", $message);
                    if (count($exploded_name) < 2) {
                        $response = "Enter at least two names.";
                        $user->progress = $user->progress - 1;
                        $user->save();
                    } else {
                        if ((strlen($exploded_name[0]) < 2) || (strlen($exploded_name[1]) < 2)) {
                            $response = "A name must contain at least two characters.";
                            $user->progress = $user->progress - 1;
                            $user->save();
                        }
                    }
                }
                break;
            case 3 :
                if ((!is_numeric($message)) || (strlen($message) < 6) || (strlen($message) > 8)) {
                    $response = "National ID number should be numeric and must be between 6 and 8 digits" . PHP_EOL;
                    $user->progress = $user->progress - 1;
                    $user->save();
                } else {
                    $user->progress = $user->progress + 2;
                    $user->save();
                }

                break;
            case 4 :
                if (self::validationVariations($message, 1, "M")) {

                } elseif (self::validationVariations($message, 2, "F")) {

                } else {
                    //not confirmed
                    $response = "Please enter 1 or 2";
                    $step = $user->progress;

                    $menuItem = menu_items::whereMenuIdAndStep(9, $step)->first();
                    if ($menuItem) {

                        $user->menu_item_id = $menuItem->id;
                        $user->menu_id = 9;
                        $user->progress = $step;
                        $user->save();
                        return $response . $menuItem->description;
                    }

                }
                break;
            case 5 :
//                $date = explode("-",$message);

                if (strlen($message) != self::getConfig('date_str_length')) {
                    $response = self::getConfig('invalid_date_str_length') . PHP_EOL;
                    $user->progress = $user->progress - 1;
                    $user->save();
                } else {
                    //check if user is below min age or above max age
                    $age = Carbon::createFromDate(substr($message, -4), substr($message, 2, 2), substr($message, 0, 2))->age;
                    $min = self::getConfig('min_loanee_age');
                    $max = self::getConfig('max_loanee_age');
                    if (($age < $min) || ($age > $max)) {
                        $error = self::getConfig('age_error_message');
                        $error = str_replace("{min}", $min, $error);
                        $error = str_replace("{max}", $max, $error);
                        $response = $error . PHP_EOL;
                        $user->progress = $user->progress - 1;
                        $user->save();
                    }
                }
                break;
            case 6 :
                if (is_numeric($message)) {
                    $response = "Employer name should not contain numbers." . PHP_EOL;
                    $user->progress = $user->progress - 1;
                    $user->save();
                }
                break;
            case 7 :
                if ((!is_numeric($message)) || (trim($message) < 1000)) {
                    $response = "Salary must be numeric and above Kshs. 1,000" . PHP_EOL;
                    $user->progress = $user->progress - 1;
                    $user->save();
                    return $response . "Enter Net Salary";
                }
                break;
            default:

                break;
        }

        $step = $user->progress + 1;

        $menuItem = menu_items::whereMenuIdAndStep(9, $step)->first();
        if ($menuItem) {

            $user->menu_item_id = $menuItem->id;
            $user->menu_id = 9;
            $user->progress = $step;
            $user->save();
            return $response . $menuItem->description;
        } else {
            $response = self::confirmBatch($user, $menu);
            return $response;

        }


    }

    public function acceptTerms($user, $message)
    {

        switch ($user->progress) {

            case 0 :
                //neutral user
                break;
            case 1:
                if (self::validationVariations($message, 1, "yes")) {
                    //if confirmed
                    $user->terms_accepted = 1;
                    $user->terms_accepted_on = Carbon::now();
                    $user->save();
                    self::resetUser($user);
                    //user authentication
                    $message = '';
                    $response = self::authenticateUser($user, $message);
                    self::sendResponse($response, 1, $user);

                } elseif (self::validationVariations($message, 2, "no")) {
                    $response = "Thank you for using Uni Limited.";
                    self::sendResponse($response, 3);
                } else {
                    //not confirmed
                    $response = "Please enter 1 or 2";
                    //restart the process
                    $output = 'Welcome to Uni Limited.' . PHP_EOL . 'By proceeding you agree to the terms and conditions on www.unilimited.co.ke' . PHP_EOL . '1. I Agree' . PHP_EOL . '2. I Disagree';
                    $response = $response . PHP_EOL . $output;
                    return $response;
                }
                break;
            default:
                break;
        }


    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function getConfig($slug)
    {
        return Configuration::whereSlug($slug)->first()->value;
    }

    public function resetPIN($user, $message)
    {

        switch ($user->progress) {

            case 0 :
                //neutral user
                break;
            case 1 :
                //verify ID Number
                if (self::verifyID($user, $message)) {
                    //regeneration PIN
                    return self::reGenerateUserPin($user);
                } else {
                    if ($user->difficulty_level < 3) {
                        //increase the difficulty level
                        $user->difficulty_level = $user->difficulty_level + 1;
                        $user->save();
                        $response = "Your ID did not match our records, please retry." . PHP_EOL . "Enter your national ID";
                        return $response;

                    } else {
                        $response = "Failed .Please contact Customer Care on 0704 000 999";
                        self::sendResponse($response, 3);

                    }

                };
                break;
            default:
                break;
        }

    }

    public function sendRequest2($phone, $session_id, $url, $text, $serviceCode)
    {
        //$url     = $_SERVER['HTTP_HOST'] . "/Sms/api";
        $qry_str = "?phoneNumber=" . trim($phone) . "&text=" . urlencode($text) . "&sessionId=" . $session_id . "&serviceCode=" . $serviceCode;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $qry_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '10');
        $content = trim(curl_exec($ch));
        curl_close($ch);

        return $content;
    }

    public function verifyID($user, $message)
    {
        $id = self::getClientId($user->client_id);
        if (trim($message) == $id) {
            return true;
        } else {
            return false;
        }
    }

    public function getLoanApplicationErrorMessage($data, $user)
    {


        $error_msg = 'We had a problem processing your loan. Kindly retry or contact Customer Care on 0704 000 999';
        foreach ($data->errors as $error) {

            if ($error->userMessageGlobalisationCode == 'validation.msg.loan.principal.amount.is.not.within.min.max.range') {

                $error_msg = "The loan amount must be between " . $error->args[1]->value . " and " . $error->args[2]->value;
                return $error_msg;
                break;
            }
            if ($error->userMessageGlobalisationCode == 'validation.msg.loan.groupId.cannot.be.blank') {

                $error_msg = "You need to belong to a group in order to apply for a loan. Kindly Call customer care on ".env('CUSTOMERCARE_NUMBER');
                return $error_msg;
                break;
            }

        }

        return $error_msg;

    }

    public function getLoanIdfromPhone($phoneNumber)
    {
        $data = array();
        $no = substr($phoneNumber, -9);

        $user = UssdUser::where('phone_no', "0" . $no)->orWhere('phone_no', "254" . $no)->orWhere('email', "254" . $no)->first();
        if (!$user) {
            $data['error'] = 1;
            $data['message'] = 'No user found with given phone number found';
            return json_encode($data);
        }

        $loanAccounts = self::getClientLoanAccounts($user->client_id);
        $loan_id = '';

        if (count($loanAccounts) < 1) {
            $user = self::verifyPhonefromMifos($no);
            $loanAccounts = self::getClientLoanAccounts($user->client_id);
        }
        foreach ($loanAccounts as $la) {
            if (($la->status->active == 1) && ($la->productId == STL_ID)) {
                $loan_id = $la->id;
                break;
            }
        }
        if (empty($loan_id)) {
            $data['error'] = 1;
            $data['message'] = 'User does not have an active loan of given product';

            return json_encode($data);

        }
        $data['success'] = 1;
        $data['message'] = 'Loan Id Found';
        $data['loan_id'] = $loan_id;
        return json_encode($data);
    }

    public function getLoanfromPhone($phoneNumber)
    {
        $data = array();
        $no = substr($phoneNumber, -9);

        $user = UssdUser::where('phone_no', "0" . $no)->orWhere('phone_no', "254" . $no)->first();
        if (!$user) {
            $user = self::verifyPhonefromMifos($no);
            if (!$user) {
                $data['error'] = 1;
                $data['message'] = 'No user found with given phone number found';
                return json_encode($data);
            }
        }
        $loanAccounts = self::getClientLoanAccounts($user->client_id);
        $loan_id = '';
        $loan = '';
        foreach ($loanAccounts as $la) {
            if (($la->status->active == 1) && ($la->productId == STL_ID)) {
                $loan = $la;
                $loan_id = $la->id;
                break;
            }
        }

        if (empty($loan_id)) {
            $data['error'] = 1;
            $data['message'] = 'User does not have an active loan of given product';

            return json_encode($data);

        }
        $data['success'] = 1;
        $data['message'] = 'Loan Found';
        $data['loan'] = $loan;
        return response()->json($data)->setCallback('loanCallback');
    }

    public function getBLPLoanfromPhone($phoneNumber)
    {
        $data = array();
        $no = substr($phoneNumber, -9);

        $user = Ussduser::where('phone_no', "0" . $no)->orWhere('phone_no', "254" . $no)->first();
//		print_r($user);
//		exit;
        if (!$user) {
            $user = self::verifyPhonefromMifos($no);
            if (!$user) {
                $data['error'] = 1;
                $data['message'] = 'No user found with given phone number found';
                $data['no_of_loans'] = 2;
                return json_encode($data);
            }
        }
        $loanAccounts = self::getClientLoanAccounts($user->client_id);
        $loan_id = '';
        $loan = '';
        $no_of_loans = 0;
        foreach ($loanAccounts as $la) {
            if (($la->status->active == 1) && ($la->productId == BLP_ID || $la->productId == ASF_ID)) {
                $loan = $la;
                $loan_id = $la->id;
                $no_of_loans = $no_of_loans + 1;
            }
        }

        if (empty($loan_id)) {
            $data['error'] = 1;
            $data['message'] = 'User does not have an active loan of given product';

            return json_encode($data);

        }
        $data['success'] = 1;
        $data['message'] = 'Loan Found';
        $data['loan'] = $loan;
        $data['no_of_loans'] = $no_of_loans;
        return response()->json($data)->setCallback('loanCallback');
    }

    public function getPCLLoanfromPhone($phoneNumber)
    {
        $data = array();
        $no = substr($phoneNumber, -9);

        $user = Ussduser::where('phone_no', "0" . $no)->orWhere('phone_no', "254" . $no)->first();

        if (!$user) {
            //check on mifos
            $user = self::verifyPhonefromMifos($no);
            if (!$user) {
                $data['error'] = 1;
                $data['message'] = 'No user found with given phone number found';
                return json_encode($data);
            }

        }

        $loanAccounts = self::getClientLoanAccounts($user->client_id);

        $loan_id = '';
        $loan = '';
        foreach ($loanAccounts as $la) {
            if (($la->status->active == 1) && ($la->productId == PCL_ID)) {
                $loan = $la;
                $loan_id = $la->id;
//                break;
            }
        }

        if (empty($loan_id)) {
            $data['error'] = 1;
            $data['message'] = 'User does not have an active loan of given product';

            return json_encode($data);

        }
        $data['success'] = 1;
        $data['message'] = 'Loan Found';
        $data['loan'] = $loan;
        return response()->json($data)->setCallback('loanCallback');
    }

    public function confirmGoBack($user, $message)
    {

        if (self::validationVariations($message, 1, "yes")) {
            //go back to the main menu
            self::resetUser($user);

            $user->menu_id = 2;
            $user->session = 2;
            $user->progress = 1;
            $user->save();
            //get home menu
            $menu = menu::find(2);

            $menu_items = self::getMenuItems($menu->id);


            $i = 1;
            $response = $menu->title . PHP_EOL;
            foreach ($menu_items as $key => $value) {
                $response = $response . $i . ": " . $value->description . PHP_EOL;
                $i++;
            }


            self::sendResponse($response, 1, $user);
            exit;

        } elseif (self::validationVariations($message, 2, "no")) {
            $response = "Thank you for being our valued customer";
            self::sendResponse($response, 3, $user);

        } else {
            $response = '';
            self::sendResponse($response, 2, $user);
            exit;


        }

    }

    public function getMiniStatement($loan_id)
    {

        $url = MIFOS_URL . "/loans/" . $loan_id . "?associations=transactions&" . MIFOS_tenantIdentifier;

        $transactions = Hooks::MifosGetTransaction($url);

        $transactions = array_reverse($transactions->transactions);

        $transactions = array_slice($transactions, 0, 5);
        //build up the transactions

        $statement = "";
        foreach ($transactions as $transaction) {
            $exploded = explode(".", $transaction->type->code);
            //print_r($exploded);
            //exit;
            if (strtolower(trim($exploded[1])) == 'accrual') {
                $exploded[1] = 'interest';
            }
            $date = implode("/", array_reverse($transaction->date));
            $statement = $statement . $date . ": Loan " . $exploded[1] . " - Ksh " . number_format($transaction->amount) . PHP_EOL;

        }
        return $statement;
    }


    function getClientAccounts($client_id)
    {
        $url = MIFOS_URL . "/clients/" . $client_id . "/transactions?" . MIFOS_tenantIdentifier;
        $accounts = Hooks::MifosGetTransaction($url);
        return $accounts;
    }

    function getClientStatement($client_id, $user)
    {
        $url = MIFOS_URL . "/clients/" . $client_id . "/accounts?fields=loanAccounts&" . MIFOS_tenantIdentifier;
        $loanAccounts = Hooks::MifosGetTransaction($url);
        $loanAccounts = array_reverse($loanAccounts->loanAccounts);
        $i = 0;
        $statement = '';
        foreach ($loanAccounts as $loanAccount) {
            if (($loanAccount->status->code == "loanStatusType.active") && (!empty($loanAccount->loanBalance))) {
                //we need the loan id to get statement
                $statement = $statement . self::getMiniStatement($loanAccount->id);
            }
        }

        if (empty($statement)) {
            $statement = "Your account does not have any previous transactions";
            self::sendResponse($statement, 2, $user);
            exit;
        }
        return $statement;
    }

    public function getLoan($loan_id)
    {
        $url = MIFOS_URL . "/loans/" . $loan_id . "?" . MIFOS_tenantIdentifier;
        $loan = Hooks::MifosGetTransaction($url);
        return $loan;
    }


    public function getLoanBalance($client_id, $product_id = PCL_ID)
    {
        $url = MIFOS_URL . "/clients/" . $client_id . "/accounts?" . MIFOS_tenantIdentifier;
        $account = Hooks::MifosGetTransaction($url);

        $i = 0;
        $loan_balance = array();
        $loan_balance['amount'] = 0;
        $loan_balance['installment_amount'] = 0;
        $loan_balance['message'] = "Your outstanding loan balance due on:".PHP_EOL;
        $loan_balance_message = '';
        $next_payment = array();
        $next_payment['next_date'] = 'None';
        $i = 0;
//
//        print_r($account);
//        exit;

                $hooks = new MifosXController();
        foreach ($account->loanAccounts as $loanAccount) {
            if (!empty($loanAccount->loanBalance) && ($loanAccount->status->code == 'loanStatusType.active') && ($loanAccount->productId == $product_id)) {
                $loan_balance['amount'] = $loan_balance['amount'] + $loanAccount->loanBalance;
//                $loan_balance['message'] = $loan_balance['message'] . implode("/", array_reverse($loanAccount->timeline->expectedMaturityDate)) . " is Ksh " . number_format($loanAccount->loanBalance) . "." . PHP_EOL;
                //$loan_balance['raw'] = $loanAccount;
                $loan_id = $loanAccount->id;
                $i++;
                $next_payment = $hooks->checkNextInstallment($loan_id);
//                print_r($next_payment['next_installment']);
//                exit;
                //$loan_balance['next_payment'] = $next_payment;
//                $msg = "Kshs. " . number_format($next_payment['balance'], 2);

                $loan_balance_message = $loan_balance_message . $next_payment['next_date'].": Kshs. " . number_format($next_payment['next_installment'], 2).PHP_EOL;
                $loan_balance['installment_amount'] = $loan_balance['installment_amount'] + $next_payment['next_installment'];
//                if ($next_payment['overdue_status'] == 0) {
//                    $msg = $msg . PHP_EOL . $next_payment['next_date'].": Kshs. " . number_format($next_payment['next_installment'], 2);
//                } elseif ($next_payment['overdue_status'] == 1) {
//                    $msg = $msg . PHP_EOL . $next_payment['next_date'].": Kshs. " . number_format($next_payment['next_installment'], 2);
//                } else {
//                    $msg = $msg . PHP_EOL . "Overdue Installment: Kshs. " . number_format($next_payment['next_installment'], 2) . " was due on " . $next_payment['next_date'];
//                }
//
//                $loan_balance_message = $loan_balance_message.$msg.PHP_EOL;
            }
        }

        if($i>1){
            $loan_balance['message'] = "Total Due:".PHP_EOL.$loan_balance_message."Total Installment amount Kshs:".number_format($loan_balance['installment_amount'],2).PHP_EOL."Total Outstanding Loan balance: Kshs. ".number_format($loan_balance['amount'],2);
        }else{
            $loan_balance['message'] = "Your outstanding loan balance is Kshs: ".number_format($loan_balance['amount'],2).PHP_EOL."Next Installment amount Kshs:".number_format($loan_balance['installment_amount'],2)." due on ".$next_payment['next_date'];
        }


//        if (($product_id == PCL_ID) && ($loan_balance['amount'] > 0)) {
//            $hooks = new MifosXController();
//            $next_payment = $hooks->checkNextInstallment($loan_id);
//            //$loan_balance['next_payment'] = $next_payment;
//            $msg = "Loan balance: Kshs. " . number_format($next_payment['balance'], 2);
//            if ($next_payment['overdue_status'] == 0) {
//                $msg = $msg . PHP_EOL . "Next Installment: Kshs. " . number_format($next_payment['next_installment'], 2) . " due on " . $next_payment['next_date'];
//            } elseif ($next_payment['overdue_status'] == 1) {
//                $msg = $msg . PHP_EOL . "Next Installment: Kshs. " . number_format($next_payment['next_installment'], 2) . " due today";
//            } else {
//                $msg = $msg . PHP_EOL . "Overdue Installment: Kshs. " . number_format($next_payment['next_installment'], 2) . " was due on " . $next_payment['next_date'];
//            }
//
//            $loan_balance['message'] = $msg;
//        }
        return $loan_balance;
    }

    function getClientLoanAccounts($client_id)
    {
//        print_r($client_id);
//        exit;
        $url = MIFOS_URL . "/clients/" . $client_id . "/accounts?fields=loanAccounts&" . MIFOS_tenantIdentifier;
        $loanAccounts = Hooks::MifosGetTransaction($url);
        if (!empty($loanAccounts->loanAccounts)) {
            $loanAccounts = array_reverse($loanAccounts->loanAccounts);
        } else {
            $loanAccounts = array();
        }
        return $loanAccounts;
    }

    public function getProductList()
    {
        $url = MIFOS_URL . "/loanproducts?" . MIFOS_tenantIdentifier;
        $loanproducts = Hooks::MifosGetTransaction($url);
        $data = array();
        foreach ($loanproducts as $loanproduct) {
            $dat = array();
            $dat['product_name'] = $loanproduct->name;
            $dat['productId'] = $loanproduct->id;
            $dat['loanTermFrequency'] = $loanproduct->repaymentFrequencyType->id;
            $dat['loanTermFrequencyType'] = $loanproduct->repaymentFrequencyType->id;
            $dat['loanType'] = "individual";
            if (!empty($loanproduct->minNumberOfRepayments)) {
                $dat['numberOfRepayments'] = $loanproduct->minNumberOfRepayments;
                $dat['minNumberOfRepayments'] = $loanproduct->minNumberOfRepayments;
            } else {
                $dat['numberOfRepayments'] = 1;
                $dat['minNumberOfRepayments'] = 1;

            }
            if (!empty($loanproduct->maxNumberOfRepayments)) {
                $dat['maxNumberOfRepayments'] = $loanproduct->maxNumberOfRepayments;
            } else {
                $dat['maxNumberOfRepayments'] = 1;

            }
            $dat['repaymentEvery'] = $loanproduct->repaymentEvery;
            $dat['repaymentFrequencyType'] = $loanproduct->repaymentFrequencyType->id;
            $dat['interestRatePerPeriod'] = $loanproduct->interestRatePerPeriod;
            $dat['amortizationType'] = $loanproduct->amortizationType->id;
            $dat['interestType'] = $loanproduct->interestType->id;
            $dat['interestCalculationPeriodType'] = $loanproduct->interestCalculationPeriodType->id;
            $dat['transactionProcessingStrategyId'] = $loanproduct->transactionProcessingStrategyId;
            $dat['maxOutstandingLoanBalance'] = $loanproduct->transactionProcessingStrategyId;

            array_push($data, $dat);

        }
        return $data;
    }

    public function getClientId($client_id)
    {
        $url = MIFOS_URL . "/clients/" . $client_id . "/identifiers?" . MIFOS_tenantIdentifier;
        $client = Hooks::MifosGetTransaction($url);
        return $client[0]->documentKey;
    }


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


    public function isPclUser($id)
    {
        return TRUE;
//        $url = MIFOS_URL . "/datatables/Is%20Payday%20User/" . $id . "?" . MIFOS_tenantIdentifier;
//        $pcl_status = Hooks::MifosGetTransaction($url);
//        return $pcl_status;
    }

    public function getLoanLimit($id)
    {
//        return 0;
        $url = MIFOS_URL . "/datatables/user_loan_limit/" . $id . "?" . MIFOS_tenantIdentifier;
        $limit = Hooks::MifosGetTransaction($url);
        if (count($limit) > 0) {
            $limit = $limit[0]->user_loan_limit;
            $balance = self::getLoanBalance($id);

            if (!empty($balance['amount'])) {
               $limit = $limit-$balance['amount'];
            }
            return $limit;
        } else {
            return 0;
        }
    }

    public function confirmUssdProcess($user, $message)
    {
        $menu = menu::find($user->menu_id);
        if (self::validationVariations($message, 1, "yes")) {
            //if confirmed

            if (self::postUssdConfirmationProcess($user)) {
                $response = $menu->confirmation_message;
            } else {
                $response = "We had a problem processing your request. Please contact Customer Care on 0704 000 999";
            }

            self::resetUser($user);

            $notify = new NotifyController();
            $notify->sendSms($user->phone_no, $response);

            self::sendResponse($response, 2, $user);

        } elseif (self::validationVariations($message, 2, "no")) {
            if ($user->menu_id == 3) {
                self::resetUser($user);
                $menu = menu::find(2);
                $user->menu_id = 2;
                $user->session = 2;
                $user->progress = 1;
                $user->save();
                //get home menu
                $menu = menu::find(2);
                $menu_items = self::getMenuItems($menu->id);
                $i = 1;
                $response = $menu->title . PHP_EOL;
                foreach ($menu_items as $key => $value) {
                    $response = $response . $i . ": " . $value->description . PHP_EOL;
                    $i++;
                }
                self::sendResponse($response, 1, $user);
            }


            $response = self::nextMenuSwitch($user, $menu);
            return $response;

        } else {
            //not confirmed
            $response = "Please enter 1 or 2";
            //restart the process
            $output = self::confirmBatch($user, $menu);

            $response = $response . PHP_EOL . $output;
            return $response;
        }


    }

    public function pclLoanApplication($user)
    {

        $notify = new NotifyController();
        $loanAccounts = self::getClientLoanAccounts($user->client_id);

        $loan_id = '';
        $loan = '';
        foreach ($loanAccounts as $la) {
            if (($la->status->active == 1) && ($la->productId == PCL_ID)) {
                $loan = $la;
                $loan_id = $la->id;
                $loan_balance = 0;
                if (!empty($la->loanBalance) && ($la->status->code == 'loanStatusType.active') && ($la->productId == PCL_ID)) {
                    $loan_balance['amount'] = $loan_balance['amount'] + $la->loanBalance;
                    $loan_balance['message'] = $loan_balance['message'] . implode("/", array_reverse($la->timeline->expectedMaturityDate)) . " is Ksh " . number_format($la->loanBalance) . "." . PHP_EOL;
                    $loan_balance['raw'] = $la;
                }
                break;
            }
        }

        if (!empty($loan_balance['amount'])) {
            $error_msg = "Your outstanding Salary Advance Loan balance of Kshs. " . number_format($loan_balance['amount'],2) . " needs to be repaid before applying for a new Salary Advance Loan. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
            //$error_msg = "Sorry. You have an outstanding loan balance of Ksh ".$balance['amount'].". Please pay and apply for loan.";
            //$notify->sendSms($user->phone_no,$error_msg);
            self::sendResponse($error_msg, 2, $user);
            //$response = $balance['message'];
            //$response = "Your current loan balance is Ksh 0";
        }
        if ($loanAccounts[0]->status->pendingApproval == 1) {
            $error_msg = "Your previous Salary Advance Loan application is pending approval. You will receive a confirmation SMS on disbursement of funds to your M-pesa account. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
            //$error_msg = "Sorry .You have an outstanding loan balance of Ksh ".$loan->principal.". Please pay and apply for loan.";
            $notify->sendSms($user->phone_no, $error_msg);
            self::sendResponse($error_msg, 2, $user);

        }
        if ($loanAccounts[0]->status->waitingForDisbursal == 1) {
            $error_msg = "Your previous Salary Advance Loan application is pending disbursement. You will receive a confirmation SMS on disbursement of funds to your M-pesa account. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
            //$error_msg = "Sorry .You have an outstanding loan balance of Ksh ".$loan->principal.". Please pay and apply for loan.";
            $notify->sendSms($user->phone_no, $error_msg);
            self::sendResponse($error_msg, 2, $user);

        } 

        //get the loan being applied for
        $loan = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 7)->orderBy('id', 'DESC')->first()->response;
        //get period
        $installments = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 00)->orderBy('id', 'DESC')->first()->response;

        $MifosX = new MifosXController();
        $data = $MifosX->applyPCLLoan($user->client_id, $loan, $installments);

        if (empty($data->loanId)) {
            $error = self::getLoanApplicationErrorMessage($data, $user);

            $notify->sendSms($user->phone_no, $error);
            //self::resetUser($user);
            self::sendResponse($error, 2, $user);
            exit;
        } else {
            $now = Carbon::now()->toDateTimeString();
            $new_loan = $now.": New loan application from ".$user->name." amount ".number_format($loan,2).".";
            $local_hour_now = Carbon::now()->hour;
            if(($local_hour_now < 9) || ($local_hour_now > 17) || Carbon::now()->isWeekend()) { 
                $notify->sendSms("254707773267", $new_loan);
                $notify->sendSms("254705099230", $new_loan);
//                $notify->sendSms("254728355429", $new_loan);
                $notify->sendSms("254797654085", $new_loan);
            }
            return TRUE;
        }
    }

    public function postUssdConfirmationProcess($user)
    {

        switch ($user->confirm_from) {
            case 3:
                //check if it is pc user

                if ($user->is_pcl_user == 1) {
                    return self::pclLoanApplication($user);
                }

                //check if there are any errors

                $notify = new NotifyController();
                $loanAccounts = self::getClientLoanAccounts($user->client_id);
                if (!is_numeric($loanAccounts[0])) {

                    $loan = self::getLoan($loanAccounts[0]->id);
                    $balance = self::getLoanBalance($user->client_id);
                    if (!empty($balance['amount'])) {
                        $error_msg = "Your outstanding Salary Advance Loan balance of Kshs. " . $balance['amount'] . " needs to be repaid before applying for a new Salary Advance Loan. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
                        self::sendResponse($error_msg, 2, $user);
                    }
                    if ($loanAccounts[0]->status->pendingApproval == 1) {
                        $error_msg = "Your previous Salary Advance Loan application is pending approval. You will receive a confirmation SMS on disbursement of funds to your M-pesa account. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
                        $notify->sendSms($user->phone_no, $error_msg);
                        self::sendResponse($error_msg, 2, $user);

                    }
                    if ($loanAccounts[0]->status->waitingForDisbursal == 1) {
                        $error_msg = "Your previous Salary Advance Loan application is pending approval. You will receive a confirmation SMS on disbursement of funds to your M-pesa account. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
                        $notify->sendSms($user->phone_no, $error_msg);
                        self::sendResponse($error_msg, 2, $user);

                    }
                }

                //get the loan being applied for
                $loan = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 7)->orderBy('id', 'DESC')->first()->response;
                $MifosX = new MifosXController();
                $data = $MifosX->applyLoan($user->client_id, $loan);

                if (empty($data->loanId)) {
                    $error = self::getLoanApplicationErrorMessage($data, $user);


                    $notify->sendSms($user->phone_no, $error);
                    //self::resetUser($user);
                    self::sendResponse($error, 2, $user);
                    exit;
                } else {
                    $now = Carbon::now()->toDateString();
                    $new_loan = $now.": New loan application from ".$user->name." amount ".number_format($loan,2).".";
                    $notify->sendSms("254707773267", $new_loan);
                    $notify->sendSms("254705099230", $new_loan);
                    return true;
                }
                break;
            case 9:

                //get the user
                $full_name = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 10)->orderBy('id', 'DESC')->first()->response;
                $id = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 11)->orderBy('id', 'DESC')->first()->response;
//                if($gender == 1){
//                    $gender = 'M';
//                }elseif($gender == 2){
//                    $gender = 'F';
//                }
//                $g = $gender;
//                if($gender == 'M'){
//                    $g = 1;
//                }elseif($gender == 'F'){
//                    $g = 2;
//                }
//                $dob = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 13)->orderBy('id', 'DESC')->first()->response;
                $employer = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 14)->orderBy('id', 'DESC')->first()->response;
                $salary = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 15)->orderBy('id', 'DESC')->first()->response;

                $name = explode(" ", $full_name, 3);
                $reg_data = array();
                if (count($name) > 2) {
                    $reg_data['firstname'] = $name[0];
                    $reg_data['middlename'] = $name[1];
                    $reg_data['lastname'] = $name[2];
                } elseif (count($name) == 2) {
                    $reg_data['firstname'] = $name[0];
                    $reg_data['lastname'] = $name[1];
//                    $reg_data['lastname'] = $name[2];
                } else {
                    $reg_data['fullname'] = $full_name;
                }
                $reg_data['officeId'] = 1;
                $reg_data['externalId'] = $id;
                $reg_data['dateFormat'] = "dd MMMM yyyy";
                $reg_data['locale'] = "en";
//                $reg_data['genderId'] = $g;
//                $reg_data['clientTypeId'] = "individual";
//                $reg_data['legalFormType'] = "person";
//                $reg_data['Date of Birth'] = $dob;
                $reg_data['active'] = false;
                $reg_data['datatables'] = array(
                    ["registeredTableName" => "Employer",
                        "data" => array("Employer" => $employer)],
//                    ["registeredTableName"=>"DOB",
//                        "data" => array("DOB"=>$dob)],
//                    ["registeredTableName"=>"Gender",
//                        "data" => array("Gender"=>$gender)],
                    ["registeredTableName" => "Net Salary",
                        "data" => array(
                            "Net Salary" => $salary,
                            "locale" => "en",
                        )],
                );

                $reg_data['active'] = false;

                $reg_data['mobileNo'] = "254" . substr($user->phone_no, -9);

                // url for posting the application details
                $postURl = MIFOS_URL . "/clients?" . MIFOS_tenantIdentifier;
                // post the encoded application details
                $data = Hooks::MifosPostTransaction($postURl, json_encode($reg_data));
//                print_r($data);
//                exit;
                //datatables
                if (empty($data->clientId)) {

                    if (strpos($data->defaultUserMessage, 'already exists')) {

//                        $client = self::getUser($data->clientId);
//                        $user->terms_accepted = 1;
//                        $user->phone_no = $client->mobileNo;
//                        $user->terms_accepted_on = Carbon::now();
                        $user->delete();
                        $error_msg = 'A user with similar details has already been registered. Kindly redial to proceed';
                    } else {
                        $error_msg = 'We had a problem processing your registration. Kindly retry or contact Customer Care on 0704 000 999';
                    }
                    self::sendResponse($error_msg, 3, $user);
                } else {
                    $user->client_id = $data->clientId;
                    $user->save();
                    $no = substr($user->phone_no, -9);

                    $client = PreapprovedClients::where('mobile_number', "0" . $no)->orWhere('mobile_number', "254" . $no)->orWhere('mobile_number',$no)->first();
                    if($client){
                        //activate client
                        self::activateClient($user->client_id);
                    }

//                    //send identifier
                    $identifier = array(
                        "documentTypeId" => "2",
                        "documentKey" => $id,
                        "description" => "Document has been verified",
                        "status" => "active",
                    );
                    $postURl = MIFOS_URL . "/clients/" . $data->clientId . "/identifiers?" . MIFOS_tenantIdentifier;
                    // post the encoded application details
                    $data = Hooks::MifosPostTransaction($postURl, json_encode($identifier));
//                print_r($data);
//                exit;
                    $user = self::verifyPhonefromMifos(substr($user->phone_no, -9));
                    $user->terms_accepted = 1;
                    $user->terms_accepted_on = Carbon::now();
                    $user->save();
                    self::resetUser($user);
                    $menu = menu::find(9);
                    $response = "Dear " . $full_name . ", you will receive a confirmation SMS on activation. For further assistance please call our customer care line ".env('CUSTOMERCARE_NUMBER');
                    self::resetUser($user);
                    $notify = new NotifyController();
                    $notify->sendSms($user->phone_no, $response);
                    self::sendResponse($response, 3, $user);
                }
                break;

            default :
                return true;
                break;
        }

    }

    public function activateClient($clientId)
    {
        try {
            $body = [
                'locale' => 'en',
                'dateFormat' => 'dd MMMM yyyy',
                'activationDate' => Carbon::now()->format('d M Y')
            ];

            $hook = new MifosHooks();
            $response = $hook->post('clients/'.$clientId.'?command=activate', $body);

            if(array_key_exists('resourceId', $response))
            {
                return ['status' => 'success', 'data' => $response];
            } else {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            }
        } catch (\Exception $exception) {
            return ['status' => 'error', 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    public function getUser($client_id)
    {

        $url = MIFOS_URL . "/clients/" . $client_id . "?" . MIFOS_tenantIdentifier;
        $client = Hooks::MifosGetTransaction($url, $post_data = "");

        return $client;

    }

    public function continueUssdProgress($user, $message)
    {
        $menu = menu::find($user->menu_id);
        //check the user menu

        switch ($menu->type) {
            case 0:
                //authentication mini app

                break;
            case 1:
                //continue to another menu

                $response = self::continueUssdMenu($user, $message, $menu);

                break;
            case 2:
                //continue to a processs
                $response = self::continueSingleProcess($user, $message, $menu);
                break;
            case 3:
                //infomation mini app
                //
                self::infoMiniApp($user, $menu);
                break;
            default :
                self::resetUser($user);
                $response = "An authentication error occurred";
                break;
        }

        return $response;

    }


    public function getMultipleLoanBalance($client_id, $product_id = PCL_ID)
    {

        $clients_url = MIFOS_URL . "/clients/" . $client_id . "/accounts?" . MIFOS_tenantIdentifier;
        $account = Hooks::MifosGetTransaction($clients_url);

        $i = 0;
        $loan_balance = array();
        foreach ($account->loanAccounts as $loanAccount) {

            if (!empty($loanAccount->loanBalance) && ($loanAccount->status->code == 'loanStatusType.active')) {
                $loan_balance[$loanAccount->productId]['amount'] = $loan_balance['amount'] + $loanAccount->loanBalance;
                $loan_balance[$loanAccount->productId]['message'] = "Ksh " . number_format($loanAccount->loanBalance,2) . " due on " . implode("/", array_reverse($loanAccount->timeline->expectedMaturityDate)) . "." . PHP_EOL;
                $loan_id = $loanAccount->id;
                $i++;
            }
        }

        if (count($loan_balance) == 0) {
            $response = "Your current loan balance is Ksh 0";
        } elseif (count($loan_balance) == 1) {
            $value = reset($loan_balance);
            $response = "Your outstanding loan balance is " . $value['message'];

            if (($product_id == PCL_ID) && ($value['amount'] > 0)) {
                $hooks = new MifosXController();
                $next_payment = $hooks->checkNextInstallment($loan_id);
                //$loan_balance['next_payment'] = $next_payment;
                $response = "Loan balance: Ksh " . number_format($next_payment['balance']) . PHP_EOL . "Next Installment: Ksh " . number_format($next_payment['next_installment']) . " due on " . $next_payment['next_date'];
            }
        } elseif (count($loan_balance) > 1) {
            $response = "Your current loan balance:" . PHP_EOL;
            if (!empty($loan_balance[STL_ID]['amount'])) {
                $response = $response . "Short Term Loan: " . $loan_balance[STL_ID]['message'];
            }
            if (!empty($loan_balance[BLP_ID]['amount'])) {
                $response = $response . "Business Loan: " . $loan_balance[BLP_ID]['message'];
            }
            if (!empty($loan_balance[PCL_ID]['amount'])) {
                $response = $response . "Paycheck Loan: " . $loan_balance[PCL_ID]['message'];
            }
        }
        return $response;
    }


    public function infoMiniApp($user, $menu)
    {
        switch ($menu->id) {
            case 4:
                //get the loan balance
                $balance = self::getLoanBalance($user->client_id);

                if (empty($balance['amount'])) {
                    $response = "You have no outstanding loan balance";
                } else {
                    $response = $balance['message'];

//                    if($user->is_pcl_user == 1){
//                        $response = "Please transfer Ksh " . number_format($balance['amount'] ). " to Paybill Number 963334 to pay your loan";
//                    }else{
//                        $response = "Please transfer Ksh " . number_format($balance['amount'] ) . " to Paybill Number 963334 to pay your loan";
//                    }
                    $notify = new NotifyController();
                    $notify->sendSms($user->phone_no, $response);
                }
                self::sendResponse($response, 2, $user);
                //$notify = new NotifyController();
                //$notify->sendSms($user->phone_no, $balance['message']);
                //self::sendResponse($balance['message'], 2, $user);
//                $balance = self::getLoanBalance(35);
//
//                print_r($balance);
//                exit;
//                //get the loan balance
//                $balance = self::getMultipleLoanBalance($user->client_id,PCL_ID);
//
//                if (empty($balance['amount'])) {
//                    $response = "You have no outstanding loan balance";
//                } else {
//                    if($user->is_pcl_user == 1){
//                        $response = "Please transfer Ksh " . number_format($balance['amount'] ). " to Paybill Number 963334 to pay your loan";
//                    }else{
//                        $response = "Please transfer Ksh " . number_format($balance['amount'] ) . " to Paybill Number 963334 to pay your loan";
//                    }
//                    $notify = new NotifyController();
//                    $notify->sendSms($user->phone_no, $response);
//                }
//                //self::resetUser($user);
//                self::sendResponse($response, 2, $user);
                break;
            case 3:
                //get the loan balance
                $balance = self::getLoanBalance($user->client_id);
                $notify = new NotifyController();
                $notify->sendSms($user->phone_no, $balance['message']);
                self::sendResponse($balance['message'], 2, $user);
                break;
            case 6:
                //get the loan balance
                $balance = self::getLoanBalance($user->client_id);
                if (empty($balance['amount'])) {
                    $response = "Failed. Loan extension request cannot be completed. You do not have a loan";
                } else {
                    $extension_fee = $balance['amount'] * 0.1;
                    $response = "Please transfer extension fee of Ksh " . $extension_fee . " to Paybill No. 963334 to receive loan extension";
                    $notify = new NotifyController();
                    $notify->sendSms($user->phone_no, $response);
                }
                self::resetUser($user);
                self::sendResponse($response, 2, $user);
                break;
            case 7:
                //get the loan balance
                $statement = "Mini Statement:" . PHP_EOL . self::getClientStatement($user->client_id, $user);
                $notify = new NotifyController();
                $notify->sendSms($user->phone_no, $statement);
                self::resetUser($user);
                self::sendResponse($statement, 2, $user);
                break;
            default :
                $response = $menu->confirmation_message;
                $response = str_replace('{phone}', $user->phone_no, $response);
                $notify = new NotifyController();
                $notify->sendSms($user->phone_no, $response);
                self::sendResponse($response, 2, $user);
                break;
        }

    }

    public function continueSingleProcess($user, $message, $menu)
    {


        if ($menu->id == 9) {
            self::storeUssdResponse($user, $message);
            $response = self::selfRegistration($user, $message);
            self::sendResponse($response, 1);
        }


        //validate input to be numeric
        $menuItem = menu_items::whereMenuIdAndStep($menu->id, $user->progress)->first();

        $message = str_replace(",", "", $message);


        if ($user->progress == 2) {
            //verify stuff here:
            if ((trim($message) < 4) && (trim($message) > 0)) {
                self::storeUssdResponse($user, $message);
                $response = self::confirmBatch($user, $menu);
                return $response;
                //confirm batch
            } else {
                $response = "Incorrect period." . PHP_EOL . "Select loan period:" . PHP_EOL . "1. 1 Month" . PHP_EOL . "2. 2 Months" . PHP_EOL . "3. 3 Months";
                $user->menu_item_id = 00;
                $user->menu_id = $menu->id;
                $user->progress = 2;
                $user->save();
                return $response;
            }
        }

        if ((is_numeric(trim($message))) && (1000 <= $message)) {
            self::storeUssdResponse($user, $message);
            //get the real user


            //get user specific loan limit
            $limit = self::getLoanLimit($user->client_id);
            if (($message > $limit) && ($limit > 0)) {
                $response = "Dear Customer, you have requested for a loan above your limit. Please re-apply within your limit of Kshs " . number_format($limit) . ". For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
                self::sendResponse($response, 2, $user);
                exit;
            }
            //Get user loan limit, if it exists check the amount applied is less than this. If it doesn't exists, endelea or if it is 0
            //return response with your loan
            //save to the db
            //check if user is a pcl user

//            $pcl_status = self::isPclUser($user->client_id);
////			print_r($pcl_status);
////			exit;
//            if ($pcl_status) {
//                if ($pcl_status[0]->is_pcl_user == 'true') {
            $user->is_pcl_user = 1;
            //get some menu
            $response = "Select loan period:" . PHP_EOL . "1. 1 month" . PHP_EOL . "2. 2 months" . PHP_EOL . "3. 3 months";
            $user->menu_item_id = 00;
            $user->menu_id = $menu->id;
            $user->progress = 2;
            $user->save();
            return $response;
//                } else {
//                    $user->is_pcl_user = 0;
//                    $user->save();
//                }
//            } else {
//                $user->is_pcl_user = 0;
//                $user->save();
//            }
            //check if we have another step


            $step = $user->progress + 1;
            $menuItem = menu_items::whereMenuIdAndStep($menu->id, $step)->first();
            if ($menuItem) {

                $user->menu_item_id = $menuItem->id;
                $user->menu_id = $menu->id;
                $user->progress = $step;
                $user->save();
                return $menuItem->description;
            } else {
                $response = self::confirmBatch($user, $menu);
                return $response;

            }
        } else {
            if ((trim($message) < 999) || (trim($message) > 100000)) {

                $response = "Requested Loan amount must be from Ksh 1,000 to Ksh 100,000. Enter loan amount:";

            } else {
                $response = "Invalid Amount" . PHP_EOL . $menuItem->description;
            }

        }

        return $response;
    }

    public function confirmBatch($user, $menu)
    {
        //confirm this stuff
        $menu_items = self::getMenuItems($user->menu_id);
        if(!empty($menu->confirmation_title)){
        $confirmation = "Confirm: " . $menu->confirmation_title;
        }else{
        $confirmation = "Confirm: " . $menu->title;
        }
        $amount = 0;
        foreach ($menu_items as $key => $value) {

            $response = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, $value->id)->orderBy('id', 'DESC')->first();
            if (($value->confirmation_phrase == "Salary") || ($value->confirmation_phrase == "Amount")) {
                $amount = $response->response;
                $response->response = "Kshs. " . number_format($response->response, 2);
//               if($response->response == 1){
//               }else{
//                   $response->response = "Female";
//               }
                $confirmation = $confirmation . PHP_EOL . $value->confirmation_phrase . ": " . $response->response;
            } elseif ($value->confirmation_phrase != "IGNORE") {
                $confirmation = $confirmation . PHP_EOL . $value->confirmation_phrase . ": " . $response->response;
            }

        }
        if (($user->is_pcl_user == 1) && ($user->menu_id != "9")) {
            $response = ussd_response::whereUserIdAndMenuIdAndMenuItemId($user->id, $user->menu_id, 00)->orderBy('id', 'DESC')->first();
            $MifosX = new MifosXController();
            $monthly_payment = $MifosX->calculateFullRepaymentSchedule($user->client_id, $amount, PCL_ID, $response->response);

            if ($response->response < 2) {

                $confirmation = $confirmation . PHP_EOL . "Period: " . $response->response . " months";
                $confirmation = $confirmation . PHP_EOL . "Expected payment: " . $monthly_payment;

            } else {
                $confirmation = $confirmation . PHP_EOL . "Period: " . $response->response . " month";
                $confirmation = $confirmation . PHP_EOL . "REPAYMENT(S) : " . PHP_EOL . $monthly_payment;
            }
        }

        $response = $confirmation . PHP_EOL . "1. Yes" . PHP_EOL . "2. No";

        $user->session = 3;
        $user->confirm_from = $user->menu_id;
        $user->save();

        return $response;
    }

    public function continueUssdMenu($user, $message, $menu)
    {
        //verify response
        $menu_items = self::getMenuItems($user->menu_id);

        $i = 1;
        $choice = "";
        $next_menu_id = 0;
        foreach ($menu_items as $key => $value) {
            if (self::validationVariations(trim($message), $i, $value->description)) {
                $choice = $value->id;
                $next_menu_id = $value->next_menu_id;

                break;
            }
            $i++;
        }
        if (empty($choice)) {
            //get error, we could not understand your response
            $response = "We could not understand your response" . PHP_EOL;
            $i = 1;
            $response = $menu->title . PHP_EOL;
            foreach ($menu_items as $key => $value) {
                $response = $response . $i . ": " . $value->description . PHP_EOL;
                $i++;
            }

            return $response;
            //save the response
        } else {
            //there is a selected choice
            $menu = menu::find($next_menu_id);
            //next menu switch
            $response = self::nextMenuSwitch($user, $menu);
            return $response;
        }

    }

    public function nextMenuSwitch($user, $menu)
    {

        switch ($menu->type) {
            case 0:
                //authentication mini app

                break;
            case 1:
                //continue to another menu
                $menu_items = self::getMenuItems($menu->id);
                $i = 1;
                $response = $menu->title . PHP_EOL;
                foreach ($menu_items as $key => $value) {
                    $response = $response . $i . ": " . $value->description . PHP_EOL;
                    $i++;
                }

                $user->menu_id = $menu->id;
                $user->menu_item_id = 0;
                $user->progress = 0;
                $user->save();
                //self::continueUssdMenu($user,$message,$menu);
                break;
            case 2:
                //start a process
//				print_r($menu);
//				exit;
                self::storeUssdResponse($user, $menu->id);

                $response = self::singleProcess($menu, $user, 1);
                return $response;

                break;
            case 3:
                self::infoMiniApp($user, $menu);
                break;
            default :
                self::resetUser($user);
                $response = "An authentication error occurred";
                break;
        }

        return $response;

    }

    public function singleProcess($menu, $user, $step)
    {
        if ($menu->id == 3) {
            self::checkLoanPrerequisite($user, PCL_ID);
        }

        $menuItem = menu_items::whereMenuIdAndStep($menu->id, $step)->first();
        if ($menuItem) {
            //update user data and next request and send back
            $user->menu_item_id = $menuItem->id;
            $user->menu_id = $menu->id;
            $user->progress = $step;
            $user->session = 2;
            $user->save();
            return $menuItem->description;

        }

    }

    public function checkLoanPrerequisite($user, $product_id)
    {
        $mifosX = new MifosXController();
        // get the group id
//        $groupId = $mifosX->getUserGroupId($user->client_id);

        $balance = self::getLoanBalance($user->client_id, $product_id);
        $limit = self::getLoanLimit($user->client_id);
        $client = new Client([
            'verify' => false
        ]);
//        $data = $client->request('GET', OVERDUE_URL.$groupId);

//        $overdueLoans = collect(json_decode($data->getBody()))->unique();

//        $checkClientName = array_where($overdueLoans->toArray(), function($key, $value) use ($user) {
//            return ucfirst($value) !== self::getClientName($user->client_id);
//        });

        if ($limit<1) {
            $error_msg = "Your outstanding Salary Advance Loan balance of Kshs. " . $balance['amount'] . " needs to be repaid before applying for a new Salary Advance Loan. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
            self::sendResponse($error_msg, 2, $user);
        }

//        if ($overdueLoans->count() !== 0)
//        {
//            switch ($checkClientName)
//            {
//                case count($checkClientName) == 1:
//                    $message = "Dear Customer, unfortunately your loan request has been rejected because your group member ".implode(", ", $checkClientName)." has not repaid or extended his/her loan. We request that you as group guarantors ensure that loans are repaid or extended immediately, only afterwards you are entitled for another loan. In case of questions call customer care.";
//                    break;
//                case count($checkClientName) > 1:
//                    $message = "Dear Customer, unfortunately your loan request has been rejected because your group members ".implode(", ", $checkClientName)." have not repaid or extended their loans. We request that you as group guarantors ensure that loans are repaid or extended immediately, only afterwards you are entitled for another loan. In case of questions call customer care.";
//                    break;
//                default:
//                    $message = "";
//                    break;
//            }
//
//            $error_msg = "Your application has been rejected. Kindly get in touch with management.";
//            $notify = new NotifyController();
//            $notify->sendSms($user->phone_no, $message);
//            self::sendResponse($error_msg, 2, $user);
//        }

//        if (self::getGroupEmbargoStatus($user->client_id) == 'true') {
//            $error_msg = "Your application has been rejected. Kindly get in touch with management.";
//            $message = "Kwako Mteja, twasikitika kuwa ombi lako la mkopo limekataliwa. Utaweza kuomba mkopo mwengine pindi utakapotembelewa na afisa wetu. Kwa maswali yoyote piga nambari yetu ya wateja 0790000999.";
//            $notify = new NotifyController();
//            $notify->sendSms($user->phone_no, $message);
//            self::sendResponse($error_msg, 2, $user);
//        }

        $loanAccounts = self::getClientLoanAccounts($user->client_id);

        foreach ($loanAccounts as $la) {
            if (($la->status->active == 1) && ($la->productId == $product_id)&& ($limit<1)) {
                $error_msg = "Please clear your current loan in order to apply for another loan";
                $notify = new NotifyController();
                $notify->sendSms($user->phone_no, $error_msg);
                self::sendResponse($error_msg, 2, $user);
                break;
            } elseif (($la->status->pendingApproval == 1) && ($la->productId == $product_id)) {
                $error_msg = "Your previous Salary Advance Loan application is pending approval. You will receive a confirmation SMS on disbursement of funds to your M-pesa account. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
                $notify = new NotifyController();
                $notify->sendSms($user->phone_no, $error_msg);
                self::sendResponse($error_msg, 2, $user);
                break;
            } elseif (($la->status->waitingForDisbursal == 1) && ($la->productId == $product_id)) {
                $error_msg = "Your previous Salary Advance Loan application is pending disbursement. You will receive a confirmation SMS on disbursement of funds to your M-pesa account. For further assistance please call our customer care line: ".env('CUSTOMERCARE_NUMBER');
                $notify = new NotifyController();
                $notify->sendSms($user->phone_no, $error_msg);
                self::sendResponse($error_msg, 2, $user);
                break;
            }
        }
        return TRUE;
    }

    public function validationVariations($message, $option, $value)
    {
        if ((trim(strtolower($message)) == trim(strtolower($value))) || ($message == $option) || ($message == "." . $option) || ($message == $option . ".") || ($message == "," . $option) || ($message == $option . ",")) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function sendResponse($response, $type, $user = null)
    {
        $sessionId = $_REQUEST["sessionId"];
        $serviceCode = $_REQUEST["serviceCode"];
        $phoneNumber = $_REQUEST["phoneNumber"];

        $data = ['phone' => $phoneNumber, 'text' => $response, 'service_code' => $serviceCode, 'session_id' => $sessionId];

        //log USSD request
        ussd_logs::create($data);

        if ($type == 1) {
            $output = "CON ";
        } elseif ($type == 2) {
            $output = "CON ";
            $response = $response . PHP_EOL . "1. Back to main menu" . PHP_EOL . "2. Log out";
            $user->session = 4;
            $user->progress = 0;
            $user->save();
        } else {
            $output = "END ";
        }
        $output .= $response;
        header('Content-type: text/plain');
        echo $output;
        exit;
    }

    public function storeUssdResponse($user, $message)
    {
        $data = ['user_id' => $user->id, 'menu_id' => $user->menu_id, 'menu_item_id' => $user->menu_item_id, 'response' => $message];
        return ussd_response::create($data);
    }

    public function underMaintenance()
    {
        $response = "Welcome to Uni Limited." . PHP_EOL . "System is under maintenance. Please check back later";
        self::sendResponse($response, 3);
    }

    public function authenticateUser($user, $message)
    {

        //has user accepted terms and conditions?
        if (!self::has_user_accepted_terms($user)) {
            $menu = menu::find(9);
            $response = self::nextMenuSwitch($user, $menu);
            self::sendResponse($response, 1);
        }
        $user = self::is_user_active($user);
        if ($user->active_status != 1) {
            $response = "Dear " . $user->name . ", you will receive a confirmation SMS on activation. For further assistance please call our customer care line ".env('CUSTOMERCARE_NUMBER');
            self::sendResponse($response, 3);
        }

        if (self::is_user_pin_set($user)) {
            switch ($user->progress) {
                case 0 :
                    $response = "Welcome to Uni Limited." . PHP_EOL . "Enter your PIN (Forgot PIN? Type 0)";
                    $user->session = 1;
                    $user->progress = 1;
                    $user->menu_id = 1;
                    $user->menu_item_id = 1;
                    $user->save();
                    break;
                case 1:

                    if (self::verifyPin($user, $message)) {

                        //get main menu
                        $user->menu_id = 2;
                        $user->session = 2;
                        $user->progress = 1;
                        $user->save();
                        //get home menu
                        $menu = menu::find(2);
                        $menu_items = self::getMenuItems($menu->id);
                        $i = 1;
                        $response = $menu->title . PHP_EOL;
                        foreach ($menu_items as $key => $value) {
                            $response = $response . $i . ": " . $value->description . PHP_EOL;
                            $i++;
                        }

                        return $response;

                    } else {

//						//check if we need to reset PIN
                        if ($message == '0') {
                            //reset pin process
                            $response = "Please enter your National ID";
                            $user->session = 5;
                            $user->progress = 1;
                            $user->save();
                            return $response;
                        }
                        //check difficulty level
                        if ($user->difficulty_level < 2) {
                            $response = "Failed.You have entered wrong PIN.Please try again or enter 0 to Reset" . PHP_EOL . "Enter your PIN";
                            $difficulty_level = $user->difficulty_level + 1;
                            $user->difficulty_level = $difficulty_level;
                            $user->save();
                            return $response;
                        } else {
                            $response = "Failed. Reply with 0 to reset PIN";
                            self::sendResponse($response, 1, $user);
                        }
                    }
                    break;
                default :
                    self::resetUser($user);
                    $response = "An authentication error occurred";
                    break;
            }


            return $response;
        } else {

            $user = self::generateUserPIN($user);

            $response = "CON Your PIN has been generated and set to " . $user->pin . PHP_EOL . "Welcome on board";
            $msg = "Your PIN has been generated and set to " . $user->pin . PHP_EOL . "Welcome on board";
            $notify = new NotifyController();
            $notify->sendSms($user->phone_no, $msg);
            $response = $response . PHP_EOL . "1. Proceed to main menu" . PHP_EOL . "2. Cancel";
            $user->session = 4;
            $user->progress = 0;
            $user->save();

            header('Content-type: text/plain');
            echo $response;
            exit;
        }
        exit;

    }

    public function reGenerateUserPin($user)
    {
        $user = self::generateUserPIN($user);
        $response = "Your PIN has been regenerated and set to " . $user->pin . ". Use the new PIN to Login";
        $notify = new NotifyController();
        $notify->sendSms($user->phone_no, $response);
        self::sendResponse($response, 3, $user);
    }

    public function getNextMenuItem($parent_id, $order)
    {
        return DB::table('menu_items')->where('parent_id', '=', $parent_id)->where('_order', '=', $order)->get();

    }

    public function verifyPin($user, $message)
    {
        if ($user->pin == trim($message)) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function generateUserPIN($user)
    {

        $digits = 4;
        $PIN = rand(pow(10, $digits - 1), pow(10, $digits) - 1);

        $user->pin = $PIN;

        //send PIN via SMS
        if ($user->save()) {
            return $user;
        }

    }

    public function is_user_pin_set($user)
    {

        if ($user->pin != 0) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function is_user_active($user)
    {
        if ($user->active_status == 1) {
            return $user;
        } else {
            $user = self::verifyPhonefromMifos(substr($user->phone_no, -9));
            return $user;
        }

    }

    public function has_user_accepted_terms($user)
    {
        if ($user->terms_accepted == 1) {
            return TRUE;
        } else {
            return FALSE;
        }

    }

    public function resetUser($user)
    {
        $user->session = 0;
        $user->progress = 0;
        $user->menu_id = 0;
        $user->difficulty_level = 0;
        $user->confirm_from = 0;
        $user->menu_item_id = 0;

        return $user->save();

    }

    public function getMenu($id)
    {
        return menu::find($id);

    }

    public static function getMenuItems($menu_id)
    {
        $menu_items = menu_items::whereMenuId($menu_id)->get();
        return $menu_items;

    }

    public function user_is_starting($text)
    {
        if (strlen($text) > 0) {
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function sendRequest($phone, $session_id, $url, $text, $serviceCode)
    {
        //$url     = $_SERVER['HTTP_HOST'] . "/Sms/api";
        $qry_str = "?phoneNumber=" . trim($phone) . "&text=" . urlencode($text) . "&sessionId=" . $session_id . "&serviceCode=" . $serviceCode;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . $qry_str);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, '3');
        $content = trim(curl_exec($ch));
        curl_close($ch);

        return $content;
    }

    public function getGroupEmbargoStatus($clientId)
    {
        $mifosX = new MifosXController();
        // get the group id
        $groupId = $mifosX->getUserGroupId($clientId);

        // load the url for getting the embargo s tatus
        $url = MIFOS_URL . "/datatables/is_embargo/" . $groupId . "?" . MIFOS_tenantIdentifier;

        // grab the datatable details from Mifos
        $embargo_status = Hooks::MifosGetTransaction($url, $post_data = "");

        return $embargo_status[0]->is_embargo;

    }

    public function getClientName($clientId)
    {
        $url = MIFOS_URL . "/clients/" . $clientId . "?" . MIFOS_tenantIdentifier;

        $client = Hooks::MifosGetTransaction($url, $post_data = "");

        $clientName = $client->displayName;

        return ucfirst($clientName);
    }
}
