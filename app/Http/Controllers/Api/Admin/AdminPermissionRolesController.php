<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminPermissionRole;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class AdminPermissionRolesController extends Controller
{
    public function getAdminPermissionRoles(){
        try {
            $admin_permission_roles = AdminPermissionRole::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['admin_permission_roles' => $admin_permission_roles]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function addAdminPermissionRoles(Request $request)
    {
        try {
            $request->validate([
                'admin_role_id' => 'required|exists:admin_roles,id',
                'admin_permission_id' => 'required|exists:admin_permissions,id'
            ]);

            AdminPermissionRole::query()->insert([
                'admin_role_id' => $request->admin_role_id,
                'admin_permission_id' => $request->admin_permission_id,
                'read' => $request->read,
                'write' => $request->write
            ]);

            return response(['message' => 'Yetki rolü ekleme işlemi başarılı.', 'status' => 'success']);

        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }

}
