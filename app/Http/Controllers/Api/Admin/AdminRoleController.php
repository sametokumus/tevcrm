<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPermissionRole;
use App\Models\AdminRole;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Schema\ValidationException;

class AdminRoleController extends Controller
{
    public function addAdmin(Request $request){
        try {
            $request->validate([
                'admin_role_id' => 'required|exists:admin_roles,id',
                'email' => 'required',
                'surname' => 'required',
                'phone_number' => 'required',
            ]);

            $admin = Admin::query()->insert([
                'admin_role_id' => $request->admin_role_id,
                'email' => $request->email,
                'surname' => $request->surname,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password)
            ]);

            return response(['message' => 'Admin ekleme işlemi başarılı.','status' => 'success','object' => ['admin' => $admin]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }
    }
    public function updateAdmin(Request $request,$id){
        try {
            $request->validate([
                'admin_role_id' => 'required|exists:admin_roles,id',
                'email' => 'required',
                'surname' => 'required',
                'phone_number' => 'required',
            ]);

            $admin = Admin::query()->where('id',$id)->update([
                'admin_role_id' => $request->admin_role_id,
                'email' => $request->email,
                'surname' => $request->surname,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password)
            ]);

            return response(['message' => 'Admin güncelleme işlemi başarılı.','status' => 'success','object' => ['admin' => $admin]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }
    }
    public function deleteAdmin($id){
        try {

            $admin_role = Admin::query()->where('id',$id)->update([
                'active' => 0,
            ]);
            return response(['message' => 'Admin silme işlemi başarılı.','status' => 'success','object' => ['admin_role' => $admin_role]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getAdminRoles(){
        try {
            $admin_roles = AdminRole::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['admin_roles' => $admin_roles]]);
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

            return response(['message' => 'Role ekleme işlemi başarılı.', 'status' => 'success']);

        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }
    public function updateAdminRole(Request $request,$id){
        try {
            $request->validate([
                'name' => 'required',
            ]);

            $admin_role = AdminRole::query()->where('id',$id)->update([
                'name' => $request->name
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
    public function deleteAdminRole($id){
        try {

            $admin_role = AdminRole::query()->where('id',$id)->update([
                'active' => 0
            ]);
//            AdminPermissionRole::query()->where('id',$permission_role_id)->update([
//                'active' => 0
//            ]);
            return response(['message' => 'Role silme işlemi başarılı.','status' => 'success','object' => ['admin_role' => $admin_role]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getAdminRolePermissions($role_id){
        try {
            $role_permissions = AdminPermissionRole::query()->where('role_id', $role_id)->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['role_permissions' => $role_permissions]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function addAdminRolePermission($role_id, $permission_id){
        try {
            $check_role_permission = AdminPermissionRole::query()->where('role_id', $role_id)->where('permission_id', $permission_id)->where('active',0)->count();
            if($check_role_permission > 0){
                AdminPermissionRole::query()->where('role_id', $role_id)->where('permission_id', $permission_id)->update([
                    'active' => 1
                ]);
            }else{
                AdminPermissionRole::query()->insert([
                    'role_id' => $role_id,
                    'permission_id' => $permission_id
                ]);
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function deleteAdminRolePermission($role_id, $permission_id){
        try {
            $check_role_permission = AdminPermissionRole::query()->where('role_id', $role_id)->where('permission_id', $permission_id)->where('active',1)->count();
            if($check_role_permission > 0){
                AdminPermissionRole::query()->where('role_id', $role_id)->where('permission_id', $permission_id)->update([
                    'active' => 0
                ]);
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

}
