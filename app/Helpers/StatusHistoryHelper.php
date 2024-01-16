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
use Illuminate\Support\Facades\Log;

class StatusHistoryHelper
{
    public static function addStatusHistory($sale_id, $status_id, $user_id)
    {
        StatusHistory::query()->insert([
            'sale_id' => $sale_id,
            'status_id' => $status_id,
            'user_id' => $user_id,
        ]);

        $new_status = Status::query()->where('id', $status_id)->first();

        $notify_settings = StatusNotifySetting::query()
            ->where('active', 1)
            ->where('status_id', $status_id)
            ->get();

        foreach ($notify_settings as $notify_setting){

            //send to role
            $role_id = $notify_setting->role_id;
            if ($role_id != null){

                $role_users = Admin::query()->where('active', 1)->where('admin_role_id', $role_id)->get();
                foreach ($role_users as $role_user){
                    $notify_id = Uuid::uuid();
                    $setting_id = $notify_setting->id;
                    $sale = Sale::query()->where('sale_id', $sale_id)->first();
                    $sale_owner = Contact::query()->where('id', $sale->owner_id)->first();
                    $short_code = $sale_owner->short_code.'-'.$sale->id;
                    $sender_id = $user_id;
                    $sender = Admin::query()->where('id', $user_id)->first();
                    $sender_name = $sender->name.' '.$sender->surname;
                    $notify = '<b>'.$sender_name.'</b> tarafından <a href="/sale-detail/'.$sale->sale_id.'"><b>'.$short_code.'</b></a> numaralı siparişin durumu <b>"'.$new_status->name.'"</b> olarak güncellendi.';

                    if ($notify_setting->is_notification == 1){
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => $setting_id,
                            'sale_id' => $sale_id,
                            'sender_id' => $sender_id,
                            'receiver_id' => $role_user->id,
                            'notify' => $notify,
                            'type'=> 1
                        ]);

                        $check_send = StatusNotifyHelper::SendToNotification($notify_id, $short_code, $notify, $role_user->id);
                    }

                    if ($notify_setting->is_mail == 1){
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => $setting_id,
                            'sale_id' => $sale_id,
                            'sender_id' => $sender_id,
                            'receiver_id' => $role_user->id,
                            'notify' => $notify,
                            'type'=> 2
                        ]);

                        $check_send = StatusNotifyHelper::SendToMail($notify_id, $short_code, strip_tags($notify), $role_user);
                    }
                }

            }

            //send to receivers
            if ($notify_setting->receivers != '[]'){
                $receiversArray = json_decode($notify_setting->receivers, true);
                foreach ($receiversArray as $receiverId) {
                    $receiver = Admin::query()->where('id', $receiverId)->first();

                    $notify_id = Uuid::uuid();
                    $setting_id = $notify_setting->id;
                    $sale = Sale::query()->where('sale_id', $sale_id)->first();
                    $sale_owner = Contact::query()->where('id', $sale->owner_id)->first();
                    $short_code = $sale_owner->short_code.'-'.$sale->id;
                    $sender_id = $user_id;
                    $sender = Admin::query()->where('id', $user_id)->first();
                    $sender_name = $sender->name.' '.$sender->surname;
                    $notify = '<b>'.$sender_name.'</b> tarafından <a href="/sale-detail/'.$sale->sale_id.'"><b>'.$short_code.'</b></a> numaralı siparişin durumu <b>"'.$new_status->name.'"</b> olarak güncellendi.';

                    if ($notify_setting->is_notification == 1){
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => $setting_id,
                            'sale_id' => $sale_id,
                            'sender_id' => $sender_id,
                            'receiver_id' => $receiver->id,
                            'notify' => $notify,
                            'type'=> 1
                        ]);

                        $check_send = StatusNotifyHelper::SendToNotification($notify_id, $short_code, $notify, $receiver->id);
                    }

                    if ($notify_setting->is_mail == 1){
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => $setting_id,
                            'sale_id' => $sale_id,
                            'sender_id' => $sender_id,
                            'receiver_id' => $receiver->id,
                            'notify' => $notify,
                            'type'=> 2
                        ]);

                        $check_send = StatusNotifyHelper::SendToMail($notify_id, $short_code, strip_tags($notify), $receiver);
                    }
                }

            }

        }

        return true;
    }

}
