<?php

namespace App\Helpers;

use App\Events\StatusChange;
use App\Models\Admin;
use App\Models\Contact;
use App\Models\Sale;
use App\Models\Status;
use App\Models\StatusHistory;
use App\Models\StatusNotify;
use App\Models\StatusNotifySetting;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StatusHistoryHelper
{
    public static function addStatusHistory($offer_id, $status_id)
    {
        $user_id = Auth::user()->id;
        StatusHistory::query()->insert([
            'offer_id' => $offer_id,
            'status_id' => $status_id,
            'user_id' => $user_id,
        ]);

        return true;
    }

}
