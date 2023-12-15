<?php

namespace App\Helpers;

use App\Events\StatusChange;
use App\Notifications\StatusChangeNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class StatusNotifyHelper
{
    public static function SendToNotification($id, $title, $message, $receiver_id)
    {
        try {

            event(new StatusChange($id, $title, $message, $receiver_id));

            return true;
        }catch (\Exception $e){
            Log::info('Notify not send: ' . $id . ' / title:' . $title . ' / message:' . $message . ' / receiver_id:' . $receiver_id . ' / exception:' . $e);

            return false;
        }
    }
    public static function SendToMail($id, $title, $message, $receiver)
    {
        try {

//            $receiver->notify(new StatusChangeNotification());

            Mail::queue(new \App\Mail\StatusChangeMail($receiver->name, $receiver->email));

            return true;
        }catch (\Exception $e){
            Log::info('Mail not send: ' . $id . ' / title:' . $title . ' / message:' . $message . ' / receiver:' . $receiver . ' / exception:' . $e);

            return false;
        }
    }

}
