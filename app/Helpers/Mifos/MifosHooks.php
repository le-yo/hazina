<?php

namespace App\Helpers\Mifos;

use App\ApiLog;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use function GuzzleHttp\Promise\exception_for;

class MifosHooks
{
    /**
     * Performs a GET request to Mifos
     *
     * @param $endpoint
     * @return mixed|string
     */
    public function get($endpoint)
    {
        $url = MIFOS_URL.'/'.$endpoint;
        $client = new Client(['verify' => false]);
        $credentials = base64_encode(MIFOS_UN.':'.MIFOS_PASS);

        try {
            $data = $client->get($url,
                [
                    'headers' => [
                        'Authorization' => 'Basic '.$credentials,
                        'Content-Type' => 'application/json',
                        'Fineract-Platform-TenantId' => env('MIFOS_TENANT')
                    ]
                ]
            );

            $response = json_decode((string) $data->getBody(), true);
        } catch (BadResponseException $exception) {
            $response = json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }

        ApiLog::query()->create([
            'request_url' => $url,
            'request_type' => 'GET',
            'request_body' => null,
            'response_body' => json_encode($response)
        ]);

        return $response;
    }

    /**
     * Performs POST request to Mifos
     *
     * @param $endpoint
     * @param $body
     * @return mixed|string
     */
    public function post($endpoint, $body)
    {
        $url = MIFOS_URL.'/'.$endpoint;
        $client = new Client(['verify' => false]);
        $credentials = base64_encode(MIFOS_UN.':'.MIFOS_PASS);

        try {
            $data = $client->post($url,
                [
                    'headers' => [
                        'Authorization' => 'Basic '.$credentials,
                        'Content-Type' => 'application/json',
                        'Fineract-Platform-TenantId' => env('MIFOS_TENANT')
                    ],
                    'body' => json_encode($body)
                ]
            );

            $response = json_decode((string) $data->getBody(), true);
        } catch (BadResponseException $exception) {
            $response = json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }

        ApiLog::query()->create([
            'request_url' => $url,
            'request_type' => 'POST',
            'request_body' => json_encode($body),
            'response_body' => json_encode($response)
        ]);

        return $response;
    }

    /**
     * Performs POST request to Mifos
     *
     * @param $endpoint
     * @param $body
     * @return mixed|string
     */
    public function put($endpoint, $body)
    {
        $url = MIFOS_URL.'/'.$endpoint;
        $client = new Client(['verify' => false]);
        $credentials = base64_encode(MIFOS_UN.':'.MIFOS_PASS);

        try {
            $data = $client->put($url,
                [
                    'headers' => [
                        'Authorization' => 'Basic '.$credentials,
                        'Content-Type' => 'application/json',
                        'Fineract-Platform-TenantId' => env('MIFOS_TENANT')
                    ],
                    'body' => json_encode($body)
                ]
            );

            $response = json_decode((string) $data->getBody(), true);
        } catch (BadResponseException $exception) {
            $response = json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }

        ApiLog::query()->create([
            'request_url' => $url,
            'request_type' => 'PUT',
            'request_body' => json_encode($body),
            'response_body' => json_encode($response)
        ]);

        return $response;
    }

    /**
     * Retrieves a client from mifos
     *
     * @param $clientId
     * @return mixed|string
     */
    public static function retrieveClient($clientId)
    {
        $hook = new MifosHooks();
        $response = $hook->get('clients/'.$clientId);

        if(array_key_exists('accountNo', $response))
        {
            return ['status' => 'success', 'data' => $response];
        } else {
            return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
        }
    }

    /**
     * Retrieves a client from mifos by phone
     *
     * @param $clientPhone
     * @return mixed|string
     */
    public static function checkClientByPhone($clientPhone)
    {
        $hook = new MifosHooks();
        $response = $hook->get('search?query='.$clientPhone.'&resource=clients&exactMatch=true');

        if($response !== null && (isset($response[0]) || array_key_exists('0', $response)))
        {
            return ['status' => 'success', 'data' => $response[0]];
        } else {
            if($response !== null && (isset($response['errors']) || array_key_exists('errors', $response)))
            {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            } else {
                return ['status' => 'error', 'message' => 'Client not found with phone number '.$clientPhone];
            }
        }
    }

    /**
     * Retrieves a client from mifos by National Id
     *
     * @param $clientNationalId
     * @return mixed|string
     */
    public static function checkClientByNationalId($clientNationalId)
    {
        $hook = new MifosHooks();
        $response = $hook->get('search?query='.$clientNationalId.'&resource=clientidentifiers&exactMatch=true');

        if($response !== null && (isset($response[0]) || array_key_exists('0', $response)))
        {
            return ['status' => 'success', 'data' => $response[0]];
        } else {
            if($response !== null && (isset($response['errors']) || array_key_exists('errors', $response)))
            {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            } else {
                return ['status' => 'error', 'message' => 'Client not found with National ID number '.$clientNationalId];
            }
        }
    }

    /**
     * Retrieves a client's accounts from mifos
     *
     * @param $clientId
     * @return mixed|string
     */
    public static function retrieveClientAccounts($clientId)
    {
        $hook = new MifosHooks();
        $response = $hook->get('clients/'.$clientId.'/accounts');

        if(!isset($response['errors']) || !array_key_exists('errors', $response))
        {
            return ['status' => 'success', 'data' => $response];
        } else {
            if(isset($response['errors']) || array_key_exists('errors', $response))
            {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            } else {
                return ['status' => 'error', 'message' => 'Client with client id '.$clientId.' has no accounts yet'];
            }
        }
    }

    /**
     * Retrieves a template of the client details required by mifos
     *
     * @return mixed|string
     */
    public static function retrieveClientTemplate()
    {
        $hook = new MifosHooks();
        $response = $hook->get('clients/template');

        if(!isset($response['errors']) || !array_key_exists('errors', $response))
        {
            return ['status' => 'success', 'data' => $response];
        } else {
            if(isset($response['errors']) || array_key_exists('errors', $response))
            {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            } else {
                return ['status' => 'error', 'message' => 'Something went wrong. Please try again'];
            }
        }
    }

    /**
     * Retrieves a template of the client identifier details required by mifos
     *
     * @param $clientId
     * @return mixed|string
     */
    public static function retrieveClientIdentifierTemplate($clientId)
    {
        $hook = new MifosHooks();
        $response = $hook->get('clients/'.$clientId.'/identifiers/template');

        if(!isset($response['errors']) || !array_key_exists('errors', $response))
        {
            return ['status' => 'success', 'data' => $response];
        } else {
            if(isset($response['errors']) || array_key_exists('errors', $response))
            {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            } else {
                return ['status' => 'error', 'message' => 'Something went wrong. Please try again'];
            }
        }
    }

    /**
     * Creates client on mifos
     *
     * @param $client
     * @return array
     */
    public static function createClient($client)
    {
        try {
            $body = [
                'locale' => 'en',
                'officeId' => 1,
                'active' => false,
                'dateFormat' => 'dd MMMM yyyy',
                'firstname' => $client->first_name,
                'lastname' => $client->last_name,
                'mobileNo' => $client->phone,
                'genderId' => ($client->gender_id == 1) ? env('MALE_ID', 18) : env('FEMALE_ID', 19),
                'clientTypeId' => env('CLIENT_TYPE_ID', 22),
                'externalId' => $client->national_id,
                'activationDate' => Carbon::now()->format('d M Y')
            ];

            $hook = new MifosHooks();
            $response = $hook->post('clients', $body);

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

    /**
     * Activates a client on mifos
     *
     * @param $clientId
     * @return array
     */
    public static function activateClient($clientId)
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

    /**
     * Updates client on mifos
     *
     * @param $client
     * @return array
     */
    public static function updateClient($client)
    {
        try {
            $body = [];
            $body['firstname'] = $client->first_name;
            $body['lastname'] = $client->last_name;
            $body['mobileNo'] = $client->phone;
            $body['genderId'] = ($client->gender_id == 1) ? env('MALE_ID', 18) : env('FEMALE_ID', 19);

            $hook = new MifosHooks();
            $response = $hook->put('clients/'.$client['mifos_client_id'], $body);

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

    /**
     * Retrieves a loan from mifos
     *
     * @param $loanId
     * @return mixed|string
     */
    public static function retrieveLoan($loanId)
    {
        $hook = new MifosHooks();
        $response = $hook->get('loans/'.$loanId.'?associations=repaymentSchedule,transactions');

        if(array_key_exists('accountNo', $response))
        {
            return ['status' => 'success', 'data' => $response];
        } else {
            return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
        }
    }

    /**
     * Creates a loan on mifos
     *
     * @param $data
     * @return array
     */
    public static function calculateLoanSchedule($data)
    {
        try {
            $body = [
                'locale' => 'en',
                'loanType' => 'individual',
                'dateFormat' => 'dd MMMM yyyy',
                'productId' => $data['product_id'],
                'clientId' => $data['client_id'],
                'principal' => $data['principal'],
                'loanTermFrequency' => $data['term'],
                'loanTermFrequencyType' => 2,
                'numberOfRepayments' => $data['no_of_repayments'],
                'repaymentEvery' => 1,
                'repaymentFrequencyType' => 2,
                'interestRatePerPeriod' => $data['interest_rate'],
                'interestRateFrequencyType' => 2,
                'amortizationType' => 1,
                'interestType' => 1,
                'interestCalculationPeriodType' => 1,
                'transactionProcessingStrategyId' => 2,
                'submittedOnDate' => Carbon::now()->format('d F Y'),
                'expectedDisbursementDate' => Carbon::parse($data['disbursement_date'])->format('d F Y')
            ];

            $hook = new MifosHooks();
            $response = $hook->post('loans?command=calculateLoanSchedule', $body);

            if(array_key_exists('totalPrincipalExpected', $response))
            {
                return ['status' => 'success', 'data' => $response];
            } else {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            }
        } catch (\Exception $exception) {
            return ['status' => 'error', 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    /**
     * Creates a loan on mifos
     *
     * @param $data
     * @return array
     */
    public static function createLoan($data)
    {
        try {
            $body = [
                'locale' => 'en',
                'loanType' => 'individual',
                'dateFormat' => 'dd MMMM yyyy',
                'productId' => $data['product_id'],
                'clientId' => $data['client_id'],
                'principal' => $data['principal'],
                'loanTermFrequency' => $data['term'],
                'loanTermFrequencyType' => 1,
                'numberOfRepayments' => $data['no_of_repayments'],
                'repaymentEvery' => 1,
                'repaymentFrequencyType' => 1,
                'interestRatePerPeriod' => $data['interest_rate'],
                'interestRateFrequencyType' => 2,
                'amortizationType' => 1,
                'interestType' => 1,
                'interestCalculationPeriodType' => 1,
                'transactionProcessingStrategyId' => 2,
                'submittedOnDate' => Carbon::now()->format('d F Y'),
                'expectedDisbursementDate' => Carbon::parse($data['disbursement_date'])->format('d F Y')
            ];

            $hook = new MifosHooks();
            $response = $hook->post('loans', $body);

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

    /**
     * Approves a loan on mifos
     *
     * @param $loanId
     * @return array
     */
    public static function approveLoan($loanId)
    {
        try {
            $body = [
                'locale' => 'en',
                'dateFormat' => 'dd MMMM yyyy',
                'approvedOnDate' => Carbon::now()->format('d F Y')
            ];

            $hook = new MifosHooks();
            $response = $hook->post('loans/'.$loanId.'?command=approve', $body);

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

    /**
     * Disburses a loan on mifos
     *
     * @param $loanId
     * @return array
     */
    public static function disburseLoan($loanId)
    {
        try {
            $body = [
                'locale' => 'en',
                'dateFormat' => 'dd MMMM yyyy',
                'actualDisbursementDate' => Carbon::now()->format('d F Y')
            ];

            $hook = new MifosHooks();
            $response = $hook->post('loans/'.$loanId.'?command=disburse', $body);

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

    /**
     * Reschedules a loan
     *
     * @param $data
     * @return array
     */
    public static function rescheduleLoan($data)
    {
        try {
            $body = [
                'locale' => 'en',
                'loanId' => $data['loan_id'],
                'rescheduleReasonId' => $data['reason_id'],
                'dateFormat' => 'dd MMMM yyyy',
                'rescheduleFromDate' => Carbon::parse($data['reschedule_from_date'])->format('j F Y'),
                'submittedOnDate' => Carbon::now()->format('j F Y'),
                'adjustedDueDate' => Carbon::parse($data['adjusted_due_date'])->format('j F Y')
            ];

            $mifosHooks = new MifosHooks();
            $response = $mifosHooks->post('rescheduleloans', $body);

            if(array_key_exists('resourceId', $response))
            {
                $postApprovalData = [
                    'locale' => 'en',
                    'dateFormat' => 'dd MMMM yyyy',
                    'approvedOnDate' => Carbon::now()->format('j F Y')
                ];

                $mifosHooks = new MifosHooks();
                $response = $mifosHooks->post('rescheduleloans/'.$response['resourceId'].'?command=approve', $postApprovalData);

                return ['status' => 'success', 'data' => $response];
            } else {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            }
        }   catch (\Exception $exception) {
                return ['status' => 'error', 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    /**
     * Retrieves a savings account
     *
     * @param $savingsId
     * @return array
     */
    public static function retrieveSavings($savingsId)
    {
        $hook = new MifosHooks();
        $response = $hook->get('savingsaccounts/'.$savingsId.'?associations=all');

        if(array_key_exists('accountNo', $response))
        {
            return ['status' => 'success', 'data' => $response];
        } else {
            return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
        }
    }

    /**
     * Creates a savings account
     *
     * @param $data
     * @return array
     */
    public static function createSavings($data)
    {
        try{
            $savingsDetail = [
                'clientId' => $data['client_id'],
                'productId' => $data['product_id'],
                'locale' => 'en',
                'dateFormat'=> 'dd MMMM yyyy',
                'submittedOnDate'=> Carbon::now()->format('d F Y'),

            ];
            $hook = new MifosHooks();
            $response = $hook->post('savingsaccounts', $savingsDetail);

            if(array_key_exists('resourceId', $response))
            {
                return ['status' => 'success', 'data' => $response];
            } else {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            }
        } catch (\Exception $exception){
            return ['status' => 'error', 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }

    }

    /**
     * Withdraws a given amount from a savings account
     *
     * @param $data
     * @return array
     */
    public static function withdrawFromSavings($data)
    {
        try {
            $savingsDetail = [
                'locale' => 'en',
                'dateFormat' => 'dd MMMM yyyy',
                'paymentTypeId' => 1,
                'receiptNumber' => strtoupper(str_random(10)),
                'transactionAmount' => $data['withdrawal_amount'],
                'transactionDate'=> Carbon::now()->format('d F Y')

            ];

            $hook = new MifosHooks();
            $response = $hook->post('savingsaccounts/'.$data['savings_id'].'/transactions?command=withdrawal', $savingsDetail);

            if(array_key_exists('resourceId', $response))
            {
                return ['status' => 'success', 'data' => $response];
            } else {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            }
        } catch (\Exception $exception){
            return ['status' => 'error', 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }

    /**
     * Deposits a given amount to a savings account
     *
     * @param $data
     * @return array
     */
    public static function depositFromSavings($data)
    {
        try {
            $savingsDetail = [
                'locale' => 'en',
                'dateFormat' => 'dd MMMM yyyy',
                'paymentTypeId' => 1,
                'receiptNumber' => strtoupper(str_random(10)),
                'transactionAmount' => $data['deposit_amount'],
                'transactionDate'=> Carbon::now()->format('d F Y')
            ];

            $hook = new MifosHooks();
            $response = $hook->post('savingsaccounts/'.$data['savings_id'].'/transactions?command=deposit', $savingsDetail);

            if(array_key_exists('resourceId', $response))
            {
                return ['status' => 'success', 'data' => $response];
            } else {
                return ['status' => 'error', 'message' => implode(' & ', array_pluck($response['errors'], 'developerMessage')), 'code' => $response['httpStatusCode']];
            }
        } catch (\Exception $exception){
            return ['status' => 'error', 'message' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }
}