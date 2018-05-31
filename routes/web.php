<?php
define('MIFOS_URL', env('MIFOS_URL'));
define('MIFOS_tenantIdentifier', env('MIFOS_Tenant_Identifier'));
define('MIFOS_UN', env('MIFOS_UN'));
define('MIFOS_PASS', env('MIFOS_PASS'));
define('STL_ID', env('STL_ID'));
define('PCL_ID', env('PCL_ID'));
define('BLP_ID', env('BLP_ID'));
define('ASF_ID', env('ASF_ID'));
define('OVERDUE_URL', env('OVERDUE_URL'));
define('recipients_PCL_URL', env('recipients_PCL_URL'));
define('recipients_URL', env('recipients_URL'));
define('recipients_STL_URL', env('recipients_STL_URL'));
define('recipients_BLP_URL', env('recipients_BLP_URL'));
define('STL_PAYBILL', env('STL_PAYBILL'));
define('BLP_PAYBILL', env('BLP_PAYBILL'));
define('PCL_PAYBILL', env('PCL_PAYBILL'));


//routes
Route::post('ussd', 'UssdController@index');

Route::resource('dashboard', 'DashController');

Route::get('emails/verified/{code}',
    'Auth\AuthController@activateAccount');

//Route::resource([
//    'auth' => 'Auth\AuthController',
//    'password' => 'Auth\PasswordController',
//]);

//Hook routes
Route::post('user-activated', 'HooksController@user_activated_hook');
Route::post('user-edit', 'HooksController@user_edit_hook');
Route::post('loan-approved', 'HooksController@loan_approved_hook');
Route::post('loan-disbursed', 'HooksController@loan_disbursed_hook');
//Route::post('user_activated', 'HooksController@user_activated_hook');
Route::post('loan-extension', 'HooksController@loan_extension_hook');
Route::post('loan-repayment', 'HooksController@loan_repayment_hook');
Route::get('getLoanId/{phone}', 'UssdController@getLoanIdfromPhone');
Route::get('getBLPLoanId/{phone}', 'UssdController@getBLPLoanfromPhone');
Route::get('getPCLLoanId/{phone}', 'UssdController@getPCLLoanfromPhone');
Route::get('getLoan/{phone}', 'UssdController@getLoanfromPhone');
Route::get('schedule/{clientId}/{amount}/{loanProductId}/{repaymentPeriods}', 'MifosXController@calculateRepaymentSchedule');


// Mpesa
Route::post('payments/receiver', ['as' => 'payments.receiver', 'uses' => 'PaymentsController@receiver']);

// Process Payments and Payments functions
Route::get('makePayment/{note}/{id}/', 'PaymentsController@loanRepayment');
Route::post('payments/comment/{id}', 'PaymentsController@getComment');
Route::get('payments/calculator/{id}', 'PaymentsController@getOutstandingLoan');

// Excel payments upload
Route::post('payments/upload', 'PaymentsController@uploadPayments');
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/home', ['as' => 'home', 'uses' => function() {
    if (!Auth::check()) {
        return view('welcome');
    } else {
        return view('payment.index');
        $now = \Carbon\Carbon::now();
        //today
        $today_sum = \App\Payment::where('transaction_time','>=',$now->subDay(1))->sum('amount');
        $today_count = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subDay(1))->count();
        //this week
        $week_sum = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subWeek(1))->sum('amount');
        $week_count = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subWeek(1))->count();

        //this month
        $month_sum = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subMonth(1))->sum('amount');
        $month_count = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subMonth(1))->count();

        //to date
        $to_date_sum = \App\Payment::all()->sum('amount');
        $to_date_count = \App\Payment::all()->count();

        return view('dashboard', compact('today_sum','today_count','week_sum','week_count','month_count','month_sum','to_date_count','to_date_sum'));
    }
}]);

Route::get('/', ['as' => '/', 'uses' => function() {
    if (!Auth::check()) {
        return view('welcome');
    } else {
        return view('payment.index');
        $now = \Carbon\Carbon::now();
        //today
        $today_sum = \App\Payment::where('transaction_time','>=',$now->subDay(1))->sum('amount');
        $today_count = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subDay(1))->count();
        //this week
        $week_sum = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subWeek(1))->sum('amount');
        $week_count = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subWeek(1))->count();

        //this month
        $month_sum = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subMonth(1))->sum('amount');
        $month_count = \App\Payment::where('transaction_time','>=',\Carbon\Carbon::now()->subMonth(1))->count();

        //to date
        $to_date_sum = \App\Payment::all()->sum('amount');
        $to_date_count = \App\Payment::all()->count();

        return view('dashboard', compact('today_sum','today_count','week_sum','week_count','month_count','month_sum','to_date_count','to_date_sum'));
    }
}]);


//Datatables
Route::get('payments/datatables', ['as' => 'payments.datatables', 'uses' => 'DatatablesController@getPayments']);
Route::get('payments/datatables/unprocessed', ['as' => 'payments.datatables.processed', 'uses' => 'DatatablesController@getProcessedPayments']);
Route::get('payments/datatables/unrecognized', ['as' => 'payments.datatables.unrecognized', 'uses' => 'DatatablesController@getUnrecognizedPayments']);


Auth::routes();


//toreview

// Payments
Route::get('business-loan/payments', ['as' => 'business-loan.payments', 'uses' => 'PaymentsController@businessLoanPaymentsIndex']);
Route::get('payday-loan/payments', ['as' => 'payday-loan.payments', 'uses' => 'PaymentsController@paydayLoanPaymentsIndex']);

// Loans
Route::get('get/loan/{loanId}', ['as' => 'get.loan', 'uses' => 'LoansController@getLoan']);
Route::get('store/loan/{phone}/{id}', ['as' => 'store.loan', 'uses' => 'LoansController@saveLoans']);
Route::post('loans/disburse', ['as' => 'disburse.loans', 'uses' => 'LoansController@disburseLoans']);
Route::get('get/short-term/loans/', ['as' => 'get.short-term.loans', 'uses' => 'LoansController@getSTLLoans']);
Route::get('get/business/loans/', ['as' => 'get.business.loans', 'uses' => 'LoansController@getBLPLoans']);
Route::get('get/payday/loans/', ['as' => 'get.payday.loans', 'uses' => 'LoansController@getPLCLoans']);
Route::get('short-term/loans', ['as' => 'short-term.loans', 'uses' => 'LoansController@shortTermLoansIndex']);
Route::get('business-loan/loans', ['as' => 'business-loan.loans', 'uses' => 'LoansController@businessLoansIndex']);
Route::get('payday-loan/loans', ['as' => 'payday-loan.loans', 'uses' => 'LoansController@paydayLoansIndex']);

// Datatables
// Payments Datatables
Route::get('payments/datatables/{paybill}', ['as' => 'payments.datatables', 'uses' => 'DatatablesController@getPayments']);
Route::get('payments/datatables/processed/{paybill}', ['as' => 'payments.datatables.processed', 'uses' => 'DatatablesController@getProcessedPayments']);
Route::get('payments/datatables/unrecognized/{paybill}', ['as' => 'payments.datatables.unrecognized', 'uses' => 'DatatablesController@getUnrecognizedPayments']);

// Loans Datatables
Route::get('loans/approved/datatables/{loanProductId}', ['as' => 'loans.approved.datatables', 'uses' => 'DatatablesController@getApprovedLoan']);
Route::get('loans/disbursed/datatables/{loanProductId}', ['as' => 'loans.disbursed.datatables', 'uses' => 'DatatablesController@getDisbursedLoan']);

// Mpesa
Route::post('payments/receiver', ['as' => 'payments.receiver', 'uses' => 'PaymentsController@receiver']);

// Process Payments and Payments functions
Route::get('makePayment/{note}/{id}/', 'PaymentsController@loanRepayment');
Route::post('payments/comment/{id}', 'PaymentsController@getComment');
Route::get('payments/calculator/{id}', 'PaymentsController@getOutstandingLoan');
Route::get('payments/extend/{id}', 'PaymentsController@extendLoan');

// Excel payments upload
Route::post('payments/upload', 'PaymentsController@uploadPayments');

//Reminders
Route::get('reminder/send', 'ReminderController@send');


// Users
Route::resource('users', 'UserController');

//Route::get('/user/create', ['as' => 'users.create', 'uses' => 'UserController@create']);


Route::get('preapproved-clients','ClientsController@index');
Route::get('preapproved-clients/upload','ClientsController@upload');
Route::post('preapproved-clients/upload','ClientsController@storeUpload');
