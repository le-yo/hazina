<?php

namespace App\Helpers\Safaricom;

use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Oriceon\Settings\Facades\Settings;

class Mpesa
{
    /**
     * Generates token for Safaricom Daraja Requests
     *
     * @return mixed
     */
    public static function generateToken()
    {
        $client = new Client();
        $baseUrl = env('SAFARICOM_BASE_URL');
        $credentials = base64_encode(env('SAFARICOM_KEY').':'.env('SAFARICOM_SECRET'));

        try {
            $response = $client->get($baseUrl.'oauth/v1/generate?grant_type=client_credentials', [
                'headers' => [
                    'Authorization' => 'Basic '.$credentials,
                    'Content-Type' => 'application/json',
                ]
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception)
        {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    /**
     * Performs a 'GET' request to Safaricom Daraja
     *
     * @param $endpoint
     * @return mixed
     */
    public static function get($endpoint)
    {
        $client = new Client();
        $baseUrl = env('SAFARICOM_BASE_URL');
        $token = Settings::get('mpesa-api.token');

        try {
            $response = $client->get($baseUrl.$endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'Content-Type' => 'application/json',
                ]
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception)
        {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    /**
     * Performs a 'POST' request to Safaricom Daraja
     *
     * @param $endpoint
     * @param $requestBody
     * @return mixed
     */
    public static function post($endpoint, $requestBody)
    {
        $client = new Client();
        $baseUrl = env('SAFARICOM_BASE_URL');
        $token = Settings::get('mpesa-api.token');

        try {
            $response = $client->post($baseUrl.$endpoint, [
                'headers' => [
                    'Authorization' => 'Bearer '.$token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $requestBody
            ]);

            return json_decode((string) $response->getBody(), true);
        } catch (BadResponseException $exception)
        {
            return json_decode((string) $exception->getResponse()->getBody()->getContents(), true);
        }
    }

    /**
     * Performs an STK Push request
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public static function requestSTKPush($data)
    {
        try {
            $shortCode = env('SAFARICOM_PAYBILL');
            $passKey = env('SAFARICOM_PASS');
            $callbackUrl = 'http://37.139.17.247/mpesa/receiver';
//            $callbackUrl = url('http://stuff.com/mpesa/receive/stk/push/response');
            $time = Carbon::now()->format('YmdHis');

            $requestBody = [
                'BusinessShortCode' => $shortCode,
                'Password' => base64_encode($shortCode.$passKey.$time),
                'Timestamp' => $time,
                'TransactionType' => 'CustomerPayBillOnline',
                'Amount' => $data['amount'],
                'PartyA' => $data['phone'],
                'PartyB' => $shortCode,
                'PhoneNumber' => $data['phone'],
                'CallBackURL' => $callbackUrl,
                'AccountReference' => $data['account'],
                'TransactionDesc' => $data['description']
            ];

            $response = Mpesa::post('mpesa/stkpush/v1/processrequest', $requestBody);

            return response()->json(['status' => 'success', 'data' => $response]);
        } catch (\Exception $exception) {
            return response()->json(['status' => 'error', 'message' => $exception->getMessage()], $exception->getCode());
        }
    }
}

