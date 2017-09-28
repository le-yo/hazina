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


//routes
Route::get('/', 'WelcomeController@index');
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
Route::post('loan-approved', 'HooksController@loan_approved_hook');
Route::post('loan-disbursed', 'HooksController@loan_disbursed_hook');
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

Route::get('/', function () {
    return view('welcome');
});
