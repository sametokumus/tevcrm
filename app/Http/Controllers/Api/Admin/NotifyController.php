<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
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
                'receivers' => $request->receivers,
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
                'receivers' => $request->receivers,
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

}
