<?php
/**
 * Created by PhpStorm.
 * User: guru
 * Date: 2/17/16
 * Time: 3:37 PM
 */

namespace App;


class Hooks {

    public static function MifosGetTransaction($url,$post_data=null){

        $data = ['slug' => 'mifos_get_request', 'text' => $post_data];
        //log request
        Log::create($data);
//        print_r($url);
//        exit;
        $post_data="";
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
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, MIFOS_UN.':'.MIFOS_PASS);

//        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        if ($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
        $data = ['slug' => 'mifos_get_response', 'text' => $data];
        //log response
        Log::create($data);
        curl_close($ch);
        $response = json_decode($data);
        return $response;
    }
    public static function MifosPostTransaction($url,$post_data){

        $data = ['slug' => 'mifos_post_request', 'text' => $post_data];
        //log request
        Log::create($data);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$post_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($post_data))
        );
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($ch, CURLOPT_USERPWD, MIFOS_UN.':'.MIFOS_PASS);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $data = curl_exec($ch);
        if ($errno = curl_errno($ch)) {
            $error_message = curl_strerror($errno);
            echo "cURL error ({$errno}):\n {$error_message}";
        }
//        print_r($data);exit;
        curl_close($ch);

        $data = ['slug' => 'mifos_post_response', 'text' => $data];

        //log response
        Log::create($data);

        $response = json_decode($data);

        return $response;
    }

}