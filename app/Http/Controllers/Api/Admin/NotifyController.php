<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\StaffTargetHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\PackingList;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleOffer;
use App\Models\SaleTransaction;
use App\Models\SaleTransactionPayment;
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
                'is_mail' => $request->to_mail,
                'message' => $request->message
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
                'is_mail' => $request->to_mail,
                'message' => $request->message
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

            StatusNotify::query()
                ->where('receiver_id', $user_id)
                ->whereRaw('(type = 1 OR type = 3)')
                ->update([
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
                ->whereRaw('(type = 1 OR type = 3)')
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
                ->whereRaw('(type = 1 OR type = 3)')
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
                    if ($op) {
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
                            $notify = '<a href="/sale-detail/' . $offer->sale_id . '"><b>' . $owner->short_code . '-' . $offer->global_id . '</b></a> numaralı siparişin tedarik sürecinin tamamlanmasına <b>1 gün</b> kaldı.';
                            StatusNotify::query()->insert([
                                'notify_id' => $notify_id,
                                'setting_id' => 1,
                                'sale_id' => $offer->sale_id,
                                'sender_id' => 0,
                                'receiver_id' => $offer_request->purchasing_staff_id,
                                'notify' => $notify,
                                'type' => 3
                            ]);
                        } else if ($daysDifference == 0) {
                            $notify_id = Uuid::uuid();
                            $notify = '<a href="/sale-detail/' . $offer->sale_id . '"><b>' . $owner->short_code . '-' . $offer->global_id . '</b></a> numaralı siparişin tedarik sürecinin tamamlanmasının <b>son günü</b>.';
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

                    if ($daysDifference >= 3) {
                        $notify_id = Uuid::uuid();
                        $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı sipariş için <b>Tedarikçi teklifleri</b> henüz sisteme işlenmedi.';
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



            $option_4 = SystemNotifyOption::query()->where('id', 4)->first();

            //option 4
            if ($option_4->is_open == 1) {
                $companies = Company::query()
                    ->select('companies.*')
                    ->where('companies.active', 1)
                    ->where('companies.is_customer', 1)
                    ->get();

                $option_4_companies = array();

                foreach ($companies as $company){

                    $sale = Sale::query()
                        ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                        ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                        ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                        ->where('sales.active', 1)
                        ->where('offer_requests.company_id', $company->id)
                        ->selectRaw('sales.*, statuses.sequence, statuses.action')
                        ->orderByDesc('id')
                        ->first();

                    if ($sale){

                        $admins = Admin::query()->where('admin_role_id', 3)->where('active', 1)->get();

                        foreach ($admins as $admin) {

                            $history = StatusHistory::query()->where('sale_id', $sale->sale_id)->where('status_id', 7)->where('active', 1)->orderByDesc('id')->first();

                            $last_action_date = Carbon::parse($history->created_at);

                            $check_notify = StatusNotify::query()
                                ->where('type', 3)
                                ->where('setting_id', 4)
                                ->where('receiver_id', $admin->id)
                                ->orderByDesc('id')
                                ->first();
                            if ($check_notify) {
                                $last_action_date = Carbon::parse($check_notify->created_at);
                            }

                            $now = Carbon::now();
                            $daysDifference = $now->diffInDays($last_action_date);

                            if ($daysDifference >= 60) {
                                $notify = '<b>' . $company->name . '</b> 60 gündür <b>teklif onaylamadı.</b>';
                                $notify_id = Uuid::uuid();
                                StatusNotify::query()->insert([
                                    'notify_id' => $notify_id,
                                    'setting_id' => 4,
                                    'sale_id' => null,
                                    'sender_id' => 0,
                                    'receiver_id' => $admin->id,
                                    'notify' => $notify,
                                    'type' => 3
                                ]);
                                $company['days_difference'] = $daysDifference;
                                array_push($option_4_companies, $company);
                            }

                        }

                    }else{

                        $admins = Admin::query()->where('admin_role_id', 3)->where('active', 1)->get();

                        foreach ($admins as $admin) {

                            $check_notify = StatusNotify::query()
                                ->where('type', 3)
                                ->where('setting_id', 4)
                                ->where('receiver_id', $admin->id)
                                ->orderByDesc('id')
                                ->first();
                            if ($check_notify){
                                $last_action_date = Carbon::parse($check_notify->created_at);
                                $now = Carbon::now();
                                $daysDifference = $now->diffInDays($last_action_date);

                                if ($daysDifference >= 60){
                                    $notify = '<b>' . $company->name . '</b> 60 gündür <b>teklif onaylamadı.</b>';
                                    $notify_id = Uuid::uuid();
                                    StatusNotify::query()->insert([
                                        'notify_id' => $notify_id,
                                        'setting_id' => 4,
                                        'sale_id' => null,
                                        'sender_id' => 0,
                                        'receiver_id' => $admin->id,
                                        'notify' => $notify,
                                        'type' => 3
                                    ]);
                                    $company['days_difference'] = $daysDifference;
                                    array_push($option_4_companies, $company);
                                }
                            }else{

                                $notify = '<b>' . $company->name . '</b> 60 gündür <b>teklif onaylamadı.</b>';
                                $notify_id = Uuid::uuid();
                                StatusNotify::query()->insert([
                                    'notify_id' => $notify_id,
                                    'setting_id' => 4,
                                    'sale_id' => null,
                                    'sender_id' => 0,
                                    'receiver_id' => $admin->id,
                                    'notify' => $notify,
                                    'type' => 3
                                ]);
                                $company['days_difference'] = 'nothing';
                                array_push($option_4_companies, $company);

                            }

                        }

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

                    if ($daysDifference >= 3) {
                        $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı sipariş için müşteriye henüz <b>Teklif</b> iletilmedi.';
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

                    if ($daysDifference >= 2) {
                        $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı sipariş için müşteri henüz <b>dönüş yapmadı.</b>';
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


                        $sale_offer = SaleOffer::query()
                            ->where('sale_id', $sale->sale_id)
                            ->where('active', 1)
                            ->where('lead_time', '!=', null)
                            ->orderBy('lead_time')
                            ->first();

                        if ($sale_offer) {
                            $lead_time = $sale_offer->lead_time;
                            $status_confirmed_date = Carbon::parse($status_confirmed_date);
                            $lastActionPlusLeadTime = $status_confirmed_date->addDays($lead_time);
                            $last_action_date = $lastActionPlusLeadTime->format('Y-m-d H:i:s');
                            $now = Carbon::now();

                            $send_notify = false;
                            if ($now > $last_action_date) {
                                $daysDifference = $now->diffInDays($last_action_date);
                                if ($daysDifference == 2) {
                                    $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı siparişin teslimatı için <b>son 2 gün.</b>';
                                    $send_notify = true;
                                }
                                $sale['diff'] = $daysDifference;

                                if ($daysDifference == 0){
                                    $send_notify = true;
                                    $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı siparişin teslimatı için <b>bugün son gün.</b>';
                                    $sale['diff'] = 0;
                                }

                            } else if ($now < $last_action_date) {

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
                                        $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı siparişin <b>teslimat süresi geçti.</b>';
                                        $send_notify = true;
                                    }
                                    $sale['diff'] = $daysDifference;

                                    if ($daysDifference == 0){
                                        $send_notify = true;
                                        $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı siparişin teslimatı için <b>bugün son gün.</b>';
                                        $sale['diff'] = 0;
                                    }

                                }
                            } else {
                                $send_notify = true;
                                $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı siparişin teslimatı için <b>bugün son gün.</b>';
                                $sale['diff'] = 0;
                            }

                            if ($send_notify) {
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

            }



            $option_10 = SystemNotifyOption::query()->where('id', 10)->first();

            //option 10
            if ($option_10->is_open == 1) {

                $packing_lists = PackingList::query()
                    ->select('packing_lists.*', 'sales.status_id', 'statuses.name')
                    ->leftJoin('sale_transactions', 'sale_transactions.packing_list_id', '=', 'packing_lists.packing_list_id')
                    ->leftJoin('sales', 'sales.sale_id', '=', 'packing_lists.sale_id')
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->where('packing_lists.active', 1)
                    ->whereNull('sale_transactions.packing_list_id')
                    ->where('statuses.period', 'approved')
                    ->get();

                $option_10_sales = array();


                foreach ($packing_lists as $packing_list){

                    $sale = Sale::query()
                        ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                        ->where('statuses.period', 'approved')
                        ->where('sales.active', 1)
                        ->where('sales.sale_id', $packing_list->sale_id)
                        ->selectRaw('sales.*, statuses.sequence, statuses.action')
                        ->first();

                    if ($sale) {

                        $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
                        $owner = Contact::query()->where('id', $sale->owner_id)->first();
                        $packing_date = $packing_list->created_at;

                        $last_action_date = Carbon::parse($packing_date);

                        $check_notify = StatusNotify::query()
                            ->where('sale_id', $sale->sale_id)
                            ->where('type', 3)
                            ->where('setting_id', 10)
                            ->orderByDesc('id')
                            ->first();

                        if ($check_notify) {
                            $last_action_date = Carbon::parse($check_notify->created_at);
                        }

                        $now = Carbon::now();
                        $daysDifference = $now->diffInDays($last_action_date);
                        $sale['diff'] = $daysDifference;

                        if ($daysDifference >= 1) {
                            $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı sipariş için <b>fatura oluşturmanız gerekiyor.</b>';

                            $account_staffs = Admin::query()->where('admin_role_id', 5)->where('active', 1)->get();

                            foreach ($account_staffs as $staff) {
                                $notify_id = Uuid::uuid();
                                StatusNotify::query()->insert([
                                    'notify_id' => $notify_id,
                                    'setting_id' => 10,
                                    'sale_id' => $sale->sale_id,
                                    'sender_id' => 0,
                                    'receiver_id' => $staff->id,
                                    'notify' => $notify,
                                    'type' => 3
                                ]);
                            }
                        }

                        array_push($option_10_sales, $sale);

                    }

                }

            }



            $option_11 = SystemNotifyOption::query()->where('id', 11)->first();

            //option 11
            if ($option_11->is_open == 1) {
                $option_11_sales = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->where('statuses.period', 'approved')
                    ->where('sales.active', 1)
                    ->selectRaw('sales.*, statuses.sequence, statuses.action')
                    ->get();

                foreach ($option_11_sales as $sale){
                    $check_invoice_status = StatusHistory::query()
                        ->where('sale_id', $sale->sale_id)
                        ->where('status_id', 22)
                        ->where('active', 1)
                        ->orderByDesc('id')
                        ->first();

                    if ($check_invoice_status) {

                        $transactions = SaleTransaction::query()
                            ->where('sale_id', $sale->sale_id)
                            ->where('active', 1)
                            ->get();

                        foreach ($transactions as $transaction) {
                            $transaction_payment = SaleTransactionPayment::query()->where('transaction_id', $transaction->transaction_id)->where('active', 1)->first();
                            if ($transaction_payment->payment_status_id == 1) {

                                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
                                $owner = Contact::query()->where('id', $sale->owner_id)->first();
                                $status_invoice_due_date = $transaction_payment->due_date;

                                $last_action_date = Carbon::parse($status_invoice_due_date);

                                $check_notify = StatusNotify::query()
                                    ->where('sale_id', $sale->sale_id)
                                    ->where('type', 3)
                                    ->where('setting_id', 11)
                                    ->where('receiver_id', $offer_request->authorized_personnel_id)
                                    ->orderByDesc('id')
                                    ->first();
                                if ($check_notify){
                                    $last_action_date = Carbon::parse($check_notify->created_at);
                                }

                                $now = Carbon::now();
                                $daysDifference = $now->diffInDays($last_action_date);
                                $transaction_payment['diff'] = $daysDifference;

                                if ($daysDifference == 2) {
                                    $notify = '<a href="/sale-detail/'.$sale->sale_id.'"><b>' . $owner->short_code . '-' . $sale->id . '</b></a> numaralı sipariş için <b>ödenmeyen fatura</b> bulunuyor.';
                                    $notify_id = Uuid::uuid();
                                    StatusNotify::query()->insert([
                                        'notify_id' => $notify_id,
                                        'setting_id' => 11,
                                        'sale_id' => $sale->sale_id,
                                        'sender_id' => 0,
                                        'receiver_id' => $offer_request->authorized_personnel_id,
                                        'notify' => $notify,
                                        'type' => 3
                                    ]);
                                }

                            }
                            $transaction['transaction_payment'] = $transaction_payment;
                        }
                        $sale['transactions'] = $transactions;
                    }
                }

            }



            $option_12 = SystemNotifyOption::query()->where('id', 12)->first();

            //option 12
            if ($option_12->is_open == 1) {
                $companies = Company::query()
                    ->select('companies.*')
                    ->where('companies.active', 1)
                    ->where('companies.is_customer', 1)
                    ->get();

                $option_12_companies = array();

                foreach ($companies as $company){

                    $offer_request = OfferRequest::query()->where('company_id', $company->id)->orderByDesc('id')->first();

                    if ($offer_request){

                        $admins = Admin::query()->where('admin_role_id', 3)->where('active', 1)->get();

                        foreach ($admins as $admin) {

                            $last_action_date = Carbon::parse($offer_request->created_at);

                            $check_notify = StatusNotify::query()
                                ->where('type', 3)
                                ->where('setting_id', 12)
                                ->where('receiver_id', $admin->id)
                                ->orderByDesc('id')
                                ->first();
                            if ($check_notify) {
                                $last_action_date = Carbon::parse($check_notify->created_at);
                            }

                            $now = Carbon::now();
                            $daysDifference = $now->diffInDays($last_action_date);

                            if ($daysDifference >= 30) {
                                $notify = '<b>' . $company->name . '</b> uzun zamandır <b>talep göndermedi.</b>';
                                $notify_id = Uuid::uuid();
                                StatusNotify::query()->insert([
                                    'notify_id' => $notify_id,
                                    'setting_id' => 12,
                                    'sale_id' => null,
                                    'sender_id' => 0,
                                    'receiver_id' => $admin->id,
                                    'notify' => $notify,
                                    'type' => 3
                                ]);
                                $company['days_difference'] = $daysDifference;
                                array_push($option_12_companies, $company);
                            }

                        }

                    }else{

                        $admins = Admin::query()->where('admin_role_id', 3)->where('active', 1)->get();

                        foreach ($admins as $admin) {

                            $check_notify = StatusNotify::query()
                                ->where('type', 3)
                                ->where('setting_id', 12)
                                ->where('receiver_id', $admin->id)
                                ->orderByDesc('id')
                                ->first();
                            if ($check_notify){
                                $last_action_date = Carbon::parse($check_notify->created_at);
                                $now = Carbon::now();
                                $daysDifference = $now->diffInDays($last_action_date);

                                if ($daysDifference >= 30){
                                    $notify = '<b>' . $company->name . '</b> 30 gündür <b>talep göndermedi.</b>';
                                    $notify_id = Uuid::uuid();
                                    StatusNotify::query()->insert([
                                        'notify_id' => $notify_id,
                                        'setting_id' => 12,
                                        'sale_id' => null,
                                        'sender_id' => 0,
                                        'receiver_id' => $admin->id,
                                        'notify' => $notify,
                                        'type' => 3
                                    ]);
                                    $company['days_difference'] = $daysDifference;
                                    array_push($option_12_companies, $company);
                                }
                            }else{

                                $notify = '<b>' . $company->name . '</b> 30 gündür <b>talep göndermedi.</b>';
                                $notify_id = Uuid::uuid();
                                StatusNotify::query()->insert([
                                    'notify_id' => $notify_id,
                                    'setting_id' => 12,
                                    'sale_id' => null,
                                    'sender_id' => 0,
                                    'receiver_id' => $admin->id,
                                    'notify' => $notify,
                                    'type' => 3
                                ]);
                                $company['days_difference'] = 'nothing';
                                array_push($option_12_companies, $company);

                            }

                        }

                    }
                }

            }



            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['option_4_companies' => $option_4_companies]]);
//            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['option_12_sales' => $option_12_sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e'=>$queryException->getMessage()]);
        }
    }

}
