<?php

namespace App\Helpers;

use App\Events\StatusChange;
use Illuminate\Support\Facades\Log;

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

}
