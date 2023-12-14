<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\StaffTargetHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminRole;
use App\Models\Product;
use App\Models\StaffTarget;
use App\Models\Status;
use App\Models\StatusNotify;
use App\Models\StatusNotifySetting;
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

            StatusNotify::query()->where('receiver_id', $user_id)->update([
                'is_read' => 1
            ]);

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

}
