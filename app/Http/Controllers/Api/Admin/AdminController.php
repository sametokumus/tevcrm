<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Nette\Schema\ValidationException;

class AdminController extends Controller
{
    public function updateAdmin(Request $request,$id){
        try {
            $request->validate([
                'admin_role_id' => 'required|exists:admin_roles,id',
                'email' => 'required',
                'surname' => 'required',
                'phone_number' => 'required',
            ]);

            $admin_role = Admin::query()->where('id',$id)->update([
                'admin_role_id' => $request->admin_role_id,
                'email' => $request->email,
                'surname' => $request->surname,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password)
            ]);

            return response(['message' => 'Admin güncelleme işlemi başarılı.','status' => 'success','object' => ['admin_role' => $admin_role]]);
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
}
