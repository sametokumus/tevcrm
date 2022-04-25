<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUserComment;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class AdminUserComments extends Controller
{
    public function getAdminUserComment(){
        try {
            $admin_user_comments = AdminUserComment::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['admin_user_comments' => $admin_user_comments]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function addAdminUserComment(Request $request)
    {
        try {
            $request->validate([
                'admin_id' => 'required|exists:admins,id',
                'user_id' => 'required|exists:users,id',
                'comment' => 'required',
            ]);
            AdminUserComment::query()->insert([
                'admin_id' => $request->admin_id,
                'user_id' => $request->user_id,
                'comment' => $request->comment,
            ]);

            return response(['message' => 'yorum ekleme işlemi başarılı.', 'status' => 'success']);

        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }

    public function updateAdminUserComment(Request $request,$id){
        try {
            $request->validate([
                'comment' => 'required',
            ]);

            $admin_permission_role = AdminUserComment::query()->where('id',$id)->update([
                'comment' => $request->comment,
            ]);

            return response(['message' => 'Yorum güncelleme işlemi başarılı.','status' => 'success','object' => ['admin_permission_role' => $admin_permission_role]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }
    }

    public function deleteAdminUserComment($id){
        try {

            $admin_permission_role = AdminUserComment::query()->where('id',$id)->update([
                'active' => 0,
            ]);
            return response(['message' => 'Yorum silme işlemi başarılı.','status' => 'success','object' => ['admin_permission_role' => $admin_permission_role]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

}
