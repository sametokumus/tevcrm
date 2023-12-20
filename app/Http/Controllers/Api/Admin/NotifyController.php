<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\StaffTargetHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Contact;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleOffer;
use App\Models\StaffTarget;
use App\Models\Status;
use App\Models\StatusHistory;
use App\Models\StatusNotify;
use App\Models\StatusNotifySetting;
use App\Models\SystemNotifyOption;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class NotifyController extends Controller
{

    public function addNotifySetting(Request $request)
    {
        try {
            $request->validate([
                'status_id' => 'required'
            ]);

            $role_id = null;
            if ($request->role_id != 0){$role_id = $request->role_id;}
            StatusNotifySetting::query()->insertGetId([
                'status_id' => $request->status_id,
                'role_id' => $role_id,
                'receivers' => json_encode($request->receivers),
                'is_notification' => $request->to_notification,
                'is_mail' => $request->to_mail
            ]);
            return response(['message' => 'Bildirim ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function updateNotifySetting(Request $request)
    {
        try {
            $request->validate([
                'status_id' => 'required'
            ]);

            $role_id = null;
            if ($request->role_id != 0){$role_id = $request->role_id;}
            StatusNotifySetting::query()->where('id', $request->id)->update([
                'status_id' => $request->status_id,
                'role_id' => $role_id,
                'receivers' => json_encode($request->receivers),
                'is_notification' => $request->to_notification,
                'is_mail' => $request->to_mail
            ]);
            return response(['message' => 'Bildirim güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function deleteNotifySetting($setting_id)
    {
        try {

            StatusNotifySetting::query()->where('id', $setting_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Bildirim silme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001', 'ar' => $throwable->getMessage()]);
        }
    }

    public function getNotifySettings()
    {
        try {
            $settings = StatusNotifySetting::query()
                ->where('active', 1)
                ->get();

            foreach ($settings as $setting){
                $role_name = '';
                $receiver_names = '';

                $status_name = Status::query()->where('id', $setting->status_id)->first()->name;

                if ($setting->role_id != null){
                    $role_name = AdminRole::query()->where('id', $setting->role_id)->first()->name;
                }

                if ($setting->receivers != '[]'){
                    $receiversArray = json_decode($setting->receivers, true);
                    foreach ($receiversArray as $receiverId) {
                        $receiver = Admin::query()->where('id', $receiverId)->first();
                        $receiverName = $receiver->name.' '.$receiver->surname;
                        $receiver_names .= $receiverName . ", ";
                    }
                }

                $setting['status_name'] = $status_name;
                $setting['role_name'] = $role_name;
                $setting['receiver_names'] = substr($receiver_names, 0, -2);
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['settings' => $settings]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }

    public function getNotifySettingById($setting_id)
    {
        try {

            $setting = StatusNotifySetting::query()
                ->where('active', 1)
                ->where('id', $setting_id)
                ->first();

            $role_name = '';
            $receiver_names = '';

            $status_name = Status::query()->where('id', $setting->status_id)->first()->name;

            if ($setting->role_id != null){
                $role_name = AdminRole::query()->where('id', $setting->role_id)->first()->name;
            }

            if ($setting->receivers != '[]'){
                $receiversArray = json_decode($setting->receivers, true);
                foreach ($receiversArray as $receiverId) {
                    $receiver = Admin::query()->where('id', $receiverId)->first();
                    $receiverName = $receiver->name.' '.$receiver->surname;
                    $receiver_names .= $receiverName . ", ";
                }
            }

            $setting['status_name'] = $status_name;
            $setting['role_name'] = $role_name;
            $setting['receiver_names'] = substr($receiver_names, 0, -2);

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['setting' => $setting]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getReadNotifyById($notify_id)
    {
        try {

            StatusNotify::query()->where('notify_id', $notify_id)->update([
                'is_read' => 1
            ]);

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getReadAllNotifyByUserId($user_id)
    {
        try {

            StatusNotify::query()->where('receiver_id', $user_id)->where('type', 1)->update([
                'is_read' => 1
            ]);

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getNotReadNotifyCountByUserId($user_id)
    {
        try {

            $count = StatusNotify::query()
                ->where('receiver_id', $user_id)
                ->where('is_read', 0)
                ->where('active', 1)
                ->count();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['count' => $count]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getNotifiesByUserId($user_id)
    {
        try {

            $notifies = StatusNotify::query()
                ->where('receiver_id', $user_id)
                ->where('active', 1)
                ->orderByDesc('id')
                ->limit(30)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['notifies' => $notifies]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getCheckSystemNotifies()
    {
        try {
            $option_1 = SystemNotifyOption::query()->where('id', 1)->first();

            //option 1
            if ($option_1->is_open == 1) {
                $option_1_offers = Offer::query()
                    ->leftJoin('sales', 'sales.request_id', '=', 'offers.request_id')
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->whereRaw("(statuses.sequence >= 19 AND statuses.sequence <= 23)")
                    ->where('offers.po_url', '!=', null)
                    ->selectRaw('offers.*, sales.sale_id as sale_id, sales.owner_id as owner_id, sales.id as global_id')
                    ->get();

                foreach ($option_1_offers as $offer) {
                    $op = OfferProduct::query()
                        ->where('offer_id', $offer->offer_id)
                        ->where('active', 1)
                        ->where('lead_time', '!=', null)
                        ->orderBy('lead_time')
                        ->first();
                    $offer_request = OfferRequest::query()->where('request_id', $offer->request_id)->first();
                    $owner = Contact::query()->where('id', $offer->owner_id)->first();
                    $min_lead_time = $op->lead_time;
                    $now = Carbon::now();
                    $poDate = Carbon::parse($offer->po_date);
                    $plusLeadTime = $poDate->addDays($min_lead_time);
                    $plusLeadTimeFormatted = $plusLeadTime->format('Y-m-d');

                    $daysDifference = $now->diffInDays($plusLeadTime);
                    $offer['diff'] = $daysDifference;

                    if ($daysDifference == 1) {
                        $notify_id = Uuid::uuid();
                        $notify = '<b>' . $owner->short_code . '-' . $offer->global_id . '</b> numaralı siparişin tedarik sürecinin tamamlanmasına <b>1 gün</b> kaldı.';
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => 1,
                            'sale_id' => $offer->sale_id,
                            'sender_id' => 0,
                            'receiver_id' => $offer_request->purchasing_staff_id,
                            'notify' => $notify,
                            'type' => 3
                        ]);
                    }
                }
            }


            $option_3 = SystemNotifyOption::query()->where('id', 3)->first();

            //option 3
            if ($option_3->is_open == 1) {
                $option_3_sales = Sale::query()
                    ->where('sales.status_id', 2)
                    ->where('sales.active', 1)
                    ->selectRaw('sales.*')
                    ->get();

                foreach ($option_3_sales as $sale){
                    $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
                    $owner = Contact::query()->where('id', $sale->owner_id)->first();
                    $status_change_date = StatusHistory::query()
                        ->where('sale_id', $sale->sale_id)
                        ->where('active', 1)
                        ->orderByDesc('id')
                        ->first()
                        ->created_at;
                    $last_action_date = Carbon::parse($status_change_date);

                    $check_notify = StatusNotify::query()
                        ->where('sale_id', $sale->sale_id)
                        ->where('type', 3)
                        ->where('setting_id', 3)
                        ->where('receiver_id', $offer_request->purchasing_staff_id)
                        ->orderByDesc('id')
                        ->first();
                    if ($check_notify){
                        $last_action_date = Carbon::parse($check_notify->created_at);
                    }

                    $now = Carbon::now();
                    $daysDifference = $now->diffInDays($last_action_date);
                    $sale['diff'] = $daysDifference;

                    if ($daysDifference == 3) {
                        $notify_id = Uuid::uuid();
                        $notify = '<b>' . $owner->short_code . '-' . $sale->id . '</b> numaralı sipariş için <b>Tedarikçi teklifleri</b> henüz sisteme işlenmedi.';
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => 3,
                            'sale_id' => $sale->sale_id,
                            'sender_id' => 0,
                            'receiver_id' => $offer_request->purchasing_staff_id,
                            'notify' => $notify,
                            'type' => 3
                        ]);
                    }

                }
            }



            $option_7 = SystemNotifyOption::query()->where('id', 7)->first();

            //option 7
            if ($option_7->is_open == 1) {
                $option_7_sales = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->where('statuses.sequence', '<', 9)
                    ->where('statuses.action', '!=', 'cancelled')
                    ->where('sales.active', 1)
                    ->selectRaw('sales.*, statuses.sequence, statuses.action')
                    ->get();

                foreach ($option_7_sales as $sale){
                    $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
                    $owner = Contact::query()->where('id', $sale->owner_id)->first();
                    $status_change_date = StatusHistory::query()
                        ->where('sale_id', $sale->sale_id)
                        ->where('active', 1)
                        ->orderByDesc('id')
                        ->first()
                        ->created_at;
                    $last_action_date = Carbon::parse($status_change_date);

                    $check_notify = StatusNotify::query()
                        ->where('sale_id', $sale->sale_id)
                        ->where('type', 3)
                        ->where('setting_id', 7)
                        ->where('receiver_id', $offer_request->purchasing_staff_id)
                        ->orderByDesc('id')
                        ->first();
                    if ($check_notify){
                        $last_action_date = Carbon::parse($check_notify->created_at);
                    }

                    $now = Carbon::now();
                    $daysDifference = $now->diffInDays($last_action_date);
                    $sale['diff'] = $daysDifference;

                    if ($daysDifference == 3) {
                        $notify = '<b>' . $owner->short_code . '-' . $sale->id . '</b> numaralı sipariş için müşteriye henüz <b>Teklif</b> iletilmedi.';
                        $notify_id = Uuid::uuid();
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => 7,
                            'sale_id' => $sale->sale_id,
                            'sender_id' => 0,
                            'receiver_id' => $offer_request->purchasing_staff_id,
                            'notify' => $notify,
                            'type' => 3
                        ]);
                        $notify_id = Uuid::uuid();
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => 7,
                            'sale_id' => $sale->sale_id,
                            'sender_id' => 0,
                            'receiver_id' => $offer_request->authorized_personnel_id,
                            'notify' => $notify,
                            'type' => 3
                        ]);
                    }
                }

            }



            $option_8 = SystemNotifyOption::query()->where('id', 8)->first();

            //option 8
            if ($option_8->is_open == 1) {
                $option_8_sales = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->where('sales.status_id', 6)
                    ->where('sales.active', 1)
                    ->selectRaw('sales.*, statuses.sequence, statuses.action')
                    ->get();

                foreach ($option_8_sales as $sale){
                    $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
                    $owner = Contact::query()->where('id', $sale->owner_id)->first();
                    $status_change_date = StatusHistory::query()
                        ->where('sale_id', $sale->sale_id)
                        ->where('status_id', 6)
                        ->where('active', 1)
                        ->orderByDesc('id')
                        ->first()
                        ->created_at;
                    $last_action_date = Carbon::parse($status_change_date);

                    $check_notify = StatusNotify::query()
                        ->where('sale_id', $sale->sale_id)
                        ->where('type', 3)
                        ->where('setting_id', 8)
                        ->where('receiver_id', $offer_request->authorized_personnel_id)
                        ->orderByDesc('id')
                        ->first();
                    if ($check_notify){
                        $last_action_date = Carbon::parse($check_notify->created_at);
                    }

                    $now = Carbon::now();
                    $daysDifference = $now->diffInDays($last_action_date);
                    $sale['diff'] = $daysDifference;

                    if ($daysDifference == 2) {
                        $notify = '<b>' . $owner->short_code . '-' . $sale->id . '</b> numaralı sipariş için müşteri henüz <b>dönüş yapmadı.</b>';
                        $notify_id = Uuid::uuid();
                        StatusNotify::query()->insert([
                            'notify_id' => $notify_id,
                            'setting_id' => 8,
                            'sale_id' => $sale->sale_id,
                            'sender_id' => 0,
                            'receiver_id' => $offer_request->authorized_personnel_id,
                            'notify' => $notify,
                            'type' => 3
                        ]);
                    }
                }

            }



            $option_9 = SystemNotifyOption::query()->where('id', 9)->first();

            //option 9
            if ($option_9->is_open == 1) {
                $option_9_sales = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->where('statuses.period', 'approved')
                    ->where('sales.active', 1)
                    ->selectRaw('sales.*, statuses.sequence, statuses.action')
                    ->get();

                foreach ($option_9_sales as $sale){
                    $check_delivery = StatusHistory::query()
                        ->where('sale_id', $sale->sale_id)
                        ->where('status_id', 19)
                        ->where('active', 1)
                        ->orderByDesc('id')
                        ->first();

                    if (!$check_delivery) {
                        $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
                        $owner = Contact::query()->where('id', $sale->owner_id)->first();
                        $status_confirmed_date = StatusHistory::query()
                            ->where('sale_id', $sale->sale_id)
                            ->where('status_id', 7)
                            ->where('active', 1)
                            ->orderByDesc('id')
                            ->first()
                            ->created_at;


                        $lead_time = SaleOffer::query()
                            ->where('sale_id', $sale->sale_id)
                            ->where('active', 1)
                            ->where('lead_time', '!=', null)
                            ->orderBy('lead_time')
                            ->first()
                            ->lead_time;

                        $status_confirmed_date = Carbon::parse($status_confirmed_date);
                        $lastActionPlusLeadTime = $status_confirmed_date->addDays($lead_time);
                        $last_action_date = $lastActionPlusLeadTime->format('Y-m-d H:i:s');
                        $now = Carbon::now();

                        $send_notify = false;
                        if ($now > $last_action_date){
                            $daysDifference = $now->diffInDays($last_action_date);
                            if ($daysDifference == 2) {
                                $notify = '<b>' . $owner->short_code . '-' . $sale->id . '</b> numaralı siparişin teslimatı için <b>son 2 gün.</b>';
                                $send_notify = true;
                            }
                        }else if($now == $last_action_date){
                            $send_notify = true;
                            $notify = '<b>' . $owner->short_code . '-' . $sale->id . '</b> numaralı siparişin teslimatı için <b>bugün son gün.</b>';
                        }else{

                            $check_notify = StatusNotify::query()
                                ->where('sale_id', $sale->sale_id)
                                ->where('type', 3)
                                ->where('setting_id', 9)
                                ->where('receiver_id', $offer_request->authorized_personnel_id)
                                ->orderByDesc('id')
                                ->first();
                            if ($check_notify) {
                                $last_action_date = Carbon::parse($check_notify->created_at);
                                $daysDifference = $now->diffInDays($last_action_date);

                                if ($daysDifference == 2) {
                                    $notify = '<b>' . $owner->short_code . '-' . $sale->id . '</b> numaralı siparişin <b>teslimat süresi geçti.</b>';
                                    $send_notify = true;
                                }

                            }

                        }

                        if ($send_notify){
                            $notify_id = Uuid::uuid();
                            StatusNotify::query()->insert([
                                'notify_id' => $notify_id,
                                'setting_id' => 9,
                                'sale_id' => $sale->sale_id,
                                'sender_id' => 0,
                                'receiver_id' => $offer_request->authorized_personnel_id,
                                'notify' => $notify,
                                'type' => 3
                            ]);
                        }
                    }
                }

            }



            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['option_9_sales' => $option_9_sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e'=>$queryException->getMessage()]);
        }
    }

}
