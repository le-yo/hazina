<?php

namespace App\Http\Controllers;

use App\Hooks;
use App\Outbox;
use App\Reminder;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReminderController extends Controller
{

    public function send(){
        $mifos = new MifosXController();
        $response = $mifos->listAllDueAndOverdueClients();
        foreach ($response as $sd){
            $exploded = explode("-",$sd['Due Date']);
            $due_date = Carbon::createFromDate($exploded[0], $exploded[1], $exploded[2]);
            $diff = Carbon::now()->diffInDays($due_date,false);
            $dd = $due_date->toDateString();
            if($diff<0){
                $reminder = Reminder::whereDaysOverdue($diff)->first();
            }else{
                $reminder = Reminder::whereDaysTo($diff)->first();
            }
            self::sendReminder($reminder,$sd);
        }
        return;
    }

    public function sendReminder($reminder,$sd){
        if($reminder){
            //populate outbox
            $message = new Outbox();
            $message->phone = $sd['Mobile No'];
            $message->reminder_id = $reminder->id;
            $message->status = 0;
            $message->content = json_encode($sd);
            $search  = array('{phone}', '{due_date}', '{balance}', '{name}');
            $replace = array($sd['Mobile No'], $sd['Due Date'], number_format($sd['Total Due'],2), $sd['Client Name']);
            $subject = $reminder->message;
            $msg = str_replace($search, $replace, $subject);
            $message->message = $msg;
            $message->save();
            $notify = new NotifyController();
//            if(strlen($sd['Mobile No'])>7){
            $notify->sendSms($sd['Mobile No'],$msg);
            $message->status= 1;
            $message->save();
//            }
        }
    }
}
