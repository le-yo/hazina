<?php

namespace App\Http\Controllers;

use App\Hooks;
use App\Outbox;
use App\Reminder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    //
    public function send(){
        $mifos = new MifosXController();
//        $url = 'https://unidemo.mifosconnect.com/fineract-provider/api/v1/runreports/Due%20and%20Overdue?R_startDate=2018-01-25&R_endDate=2018-01-30&R_officeId=1&R_currencyId=-1&R_loanProductId=1&'.MIFOS_tenantIdentifier;
        $response = $mifos->listDueClientsByProduct(1);

//        print_r($response);
//        exit;
        $sampleData = '[{
	"Office": "Head Office",
	"Mobile No": "254728355429",
	"Client Name": "Leonard Korir",
	"Client Account Number": "IND000006391",
	"Loan Account Number": "PCL000038262",
	"Product": "Paycheque Loan",
	"Activation Date": "2016-11-16",
	"Due Date": "2018-01-29",
	"Loan Amount": "13334.000000",
	"Principal Due": "13334.000000",
	"Interest Due": "1200.000000",
	"Penalty Due": "0.000000",
	"Total Due": "14534.000000",
	"Total Overdue": null,
	"Last Payment Date": "2017-12-20"
}]';

        //$due_clients = $mifos->listDueClientsByProduct(1);
//        foreach (\GuzzleHttp\json_decode($sampleData,true) as $sd){
        foreach ($response as $sd){
            $exploded = explode("-",$sd['Due Date']);
            $due_date = Carbon::createFromDate($exploded[0], $exploded[1], $exploded[2]);
            $diff = Carbon::now()->diffInDays($due_date);
            $dd = $due_date->toDateString();
            self::sendReminder($diff,$sd);
//            print_r($diff);
        }
//        print_r("hapa");
//        exit;


    }

    public function sendReminder($diff,$sd){

       if($diff>-1){
        $reminder = Reminder::whereDaysTo($diff)->first();
       }else{
           $diff = 0-$diff;
        $reminder = Reminder::whereDaysOverdue($diff)->first();
       }

        if($reminder){
            //populate outbox
            $message = new Outbox();
            $message->phone = $sd['Mobile No'];
            $message->reminder_id = $reminder->id;
            $message->status = 0;
            $search  = array('{phone}', '{due_date}', '{balance}', '{name}');
            $replace = array($sd['Mobile No'], $sd['Due Date'], number_format($sd['Total Due'],2), $sd['Client Name']);
            $subject = $reminder->message;
            $msg = str_replace($search, $replace, $subject);
            $message->message = $msg;
            $message->save();
            $notify = new NotifyController();
            $notify->sendSms($sd['Mobile No'],$msg);
            $message->status= 1;
            $message->save();
        }



    }
}
