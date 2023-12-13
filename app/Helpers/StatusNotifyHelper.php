<?php

namespace App\Helpers;

use App\Events\StatusChange;
use Illuminate\Support\Facades\Log;

class StatusNotifyHelper
{
    public static function SendToNotification($id, $title, $message)
    {
        try {

            event(new StatusChange($id, $title, $message));

            return true;
        }catch (\Exception $e){
            Log::info('Notify not send: ' . $id . ' / title:' . $title . ' / message:' . $message . ' / exception:' . $e);

            return false;
        }
    }

}
