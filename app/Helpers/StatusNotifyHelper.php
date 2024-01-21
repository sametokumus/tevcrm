<?php

namespace App\Helpers;

use App\Events\StatusChange;
use App\Mail\StatusChangeMail;
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
    public static function SendToMail($id, $title, $message, $receiver, $action_link, $notify_logo)
    {
        try {

//            Mail::to($receiver->email)->send(new StatusChangeMail($receiver->email, $title, $message));
            $receiver->notify(new StatusChangeNotification($title, $message, $action_link, $notify_logo));

            return true;
        }catch (\Exception $e){
            Log::info('Mail not send: ' . $id . ' / title:' . $title . ' / message:' . $message . ' / receiver:' . $receiver . ' / exception:' . $e);

            return false;
        }
    }

}
