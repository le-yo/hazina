<?php

namespace App\Jobs;

use App\Hooks;
use App\Http\Controllers\PaymentsController;
use App\Http\Controllers\UssdController;
use App\Jobs\Job;
use App\Payment;
use App\TransactionLog;
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
            return true;
        }
    }
}
