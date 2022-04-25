<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermissionRole;
use App\Models\AdminRole;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class AdminRoleController extends Controller
{
    public function getAdminRole(){
        try {
            $admin_role = AdminRole::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['admin_role' => $admin_role]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function addAdminRole(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            AdminRole::query()->insert([
                'name' => $request->name,
            ]);

            AdminPermissionRole::query()->insert([
                'admin_role_id' => $request->admin_role_id,
                'admin_permission_id' => $request->admin_permission_id,
                'read' => $request->read,
                'write' => $request->write
            ]);

            return response(['message' => 'Role ekleme işlemi başarılı.', 'status' => 'success']);

        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }

    public function updateAdminRole(Request $request,$id,$permission_role_id){
        try {
            $request->validate([
                'name' => 'required',
            ]);

            $admin_role = AdminRole::query()->where('id',$id)->update([
                'name' => $request->name
            ]);

            AdminPermissionRole::query()->where('id',$permission_role_id)->update([
                'read' => $request->read,
                'write' => $request->write
            ]);

            return response(['message' => 'Role güncelleme işlemi başarılı.','status' => 'success','object' => ['admin_role' => $admin_role]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }
    }

    public function deleteAdminRole($id,$permission_role_id){
        try {

            $admin_role = AdminRole::query()->where('id',$id)->update([
                'active' => 0
            ]);
            AdminPermissionRole::query()->where('id',$permission_role_id)->update([
                'active' => 0
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
