<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermission;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class AdminPermissionController extends Controller
{
    public function getAdminPermission(){
        try {
            $admin_permission = AdminPermission::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['admin_permission' => $admin_permission]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function addAdminPermission(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'value' => 'required',
            ]);
            AdminPermission::query()->insert([
                'name' => $request->name,
                'value' => $request->value,
                'order' => $request->order
            ]);

            return response(['message' => 'Yetki ekleme işlemi başarılı.', 'status' => 'success']);

        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }

    public function updateAdminPermission(Request $request,$id){
        try {
            $request->validate([
                'name' => 'required',
                'value' => 'required',
            ]);

            $admin_permission = AdminPermission::query()->where('id',$id)->update([
                'name' => $request->name,
                'value' => $request->value,
                'order' => $request->order
            ]);

            return response(['message' => 'Yetki güncelleme işlemi başarılı.','status' => 'success','object' => ['admin_permission' => $admin_permission]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }
    }

    public function deleteAdminPermission($id){
        try {

            $admin_role = AdminPermission::query()->where('id',$id)->update([
                'active' => 0,
            ]);
            return response(['message' => 'Role silme işlemi başarılı.','status' => 'success','object' => ['admin_role' => $admin_role]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

}
