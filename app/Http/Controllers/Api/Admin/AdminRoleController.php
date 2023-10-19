<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminPermissionRole;
use App\Models\AdminRole;
use App\Models\AdminStatusRole;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Schema\ValidationException;

class AdminRoleController extends Controller
{

    public function getAdmins(){
        try {
            $admins = Admin::query()
                ->leftJoin('admin_roles', 'admin_roles.id', '=', 'admins.admin_role_id')
                ->where('admins.active', 1)
                ->get(['admins.id', 'admins.admin_role_id', 'admins.name', 'admins.surname', 'admins.phone_number',
                    'admins.email', 'admin_roles.name as admin_role_name']);
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['admins' => $admins]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getAdminById($id){
        try {
            $admin = Admin::query()->where('id',$id)->where('active',1)->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['admin' => $admin]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function addAdmin(Request $request){
        try {
            $request->validate([
                'admin_role_id' => 'required|exists:admin_roles,id',
                'email' => 'required',
                'name' => 'required',
                'surname' => 'required',
                'phone_number' => 'required',
            ]);

            $admin = Admin::query()->insert([
                'admin_role_id' => $request->admin_role_id,
                'email' => $request->email,
                'name' => $request->name,
                'surname' => $request->surname,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password)
            ]);

            return response(['message' => 'Admin ekleme işlemi başarılı.','status' => 'success','object' => ['admin' => $admin]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001','e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }
    }
    public function updateAdmin(Request $request,$id){
        try {
            $request->validate([
                'admin_role_id' => 'required|exists:admin_roles,id',
                'email' => 'required',
                'name' => 'required',
                'surname' => 'required',
                'phone_number' => 'required',

            ]);

            $admin = Admin::query()->where('id',$id)->update([
                'admin_role_id' => $request->admin_role_id,
                'email' => $request->email,
                'name' => $request->name,
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
    public function updateUser(Request $request,$id){
        try {
            $request->validate([
                'email' => 'required',
                'name' => 'required',
                'surname' => 'required',
                'phone_number' => 'required',

            ]);

            $admin = Admin::query()->where('id',$id)->update([
                'email' => $request->email,
                'name' => $request->name,
                'surname' => $request->surname,
                'phone_number' => $request->phone_number
            ]);

            if ($request->password != "") {
                $admin = Admin::query()->where('id', $id)->update([
                    'password' => Hash::make($request->password)
                ]);
            }

            return response(['message' => 'Hesap güncelleme işlemi başarılı.','status' => 'success','object' => ['admin' => $admin]]);
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
    public function getAdminRoleById($id){
        try {
            $admin_role = AdminRole::query()->where('id',$id)->where('active',1)->first();
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
            $role_permissions = AdminPermissionRole::query()
                ->leftJoin('admin_permissions', 'admin_permissions.id', '=', 'admin_permission_roles.admin_permission_id')
                ->selectRaw('admin_permission_roles.*, admin_permissions.value as permission_key')
                ->where('admin_permission_roles.admin_role_id', $role_id)
                ->where('admin_permission_roles.active',1)
                ->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['role_permissions' => $role_permissions]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function addAdminRolePermission($role_id, $permission_id){
        try {
            $check_role_permission = AdminPermissionRole::query()->where('admin_role_id', $role_id)->where('admin_permission_id', $permission_id)->count();
            if($check_role_permission > 0){
                AdminPermissionRole::query()->where('admin_role_id', $role_id)->where('admin_permission_id', $permission_id)->update([
                    'active' => 1
                ]);
            }else{
                AdminPermissionRole::query()->insert([
                    'admin_role_id' => $role_id,
                    'admin_permission_id' => $permission_id
                ]);
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function deleteAdminRolePermission($role_id, $permission_id){
        try {
            $check_role_permission = AdminPermissionRole::query()->where('admin_role_id', $role_id)->where('admin_permission_id', $permission_id)->where('active',1)->count();
            if($check_role_permission > 0){
                AdminPermissionRole::query()->where('admin_role_id', $role_id)->where('admin_permission_id', $permission_id)->update([
                    'active' => 0
                ]);
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getAdminRoleStatuses($role_id){
        try {
            $role_statuses = AdminStatusRole::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'admin_status_roles.status_id')
                ->where('admin_status_roles.admin_role_id', $role_id)
                ->where('admin_status_roles.active',1)
                ->where('statuses.active',1)
                ->selectRaw('admin_status_roles.*')
                ->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['role_statuses' => $role_statuses]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function addAdminRoleStatus($role_id, $status_id){
        try {
            $check_role_status = AdminStatusRole::query()->where('admin_role_id', $role_id)->where('status_id', $status_id)->count();
            if($check_role_status > 0){
                AdminStatusRole::query()->where('admin_role_id', $role_id)->where('status_id', $status_id)->update([
                    'active' => 1
                ]);
            }else{
                AdminStatusRole::query()->insert([
                    'admin_role_id' => $role_id,
                    'status_id' => $status_id
                ]);
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function deleteAdminRoleStatus($role_id, $status_id){
        try {
            $check_role_status = AdminStatusRole::query()->where('admin_role_id', $role_id)->where('status_id', $status_id)->where('active',1)->count();
            if($check_role_status > 0){
                AdminStatusRole::query()->where('admin_role_id', $role_id)->where('status_id', $status_id)->update([
                    'active' => 0
                ]);
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }



    public function getCheckAdminRolePermission($admin_id, $permission_id){
        try {
            $role_id = Admin::query()->where('id', $admin_id)->first()->admin_role_id;
            $permission = AdminPermissionRole::query()->where('admin_role_id', $role_id)->where('admin_permission_id', $permission_id)->where('active', 1)->count();
            if ($permission > 0){
                return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['permission' => true]]);
            }else{
                return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['permission' => false]]);
            }
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

}
