<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class UserController extends Controller
{
    public function getUsers(){
        try {
            $users = User::query()->where('active',1)->where('verified',1)->get();
            return response(['message' => 'İşlem başarılı.','status' => 'success','object' => ['users' => $users]]);
        } catch (QueryException $queryException){
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        }
    }

    public function getUser($id){
        try {
            $user = User::query()->where('id',$id)->first();
            return response(['message' => 'İşlem Başarılı.','status' => 'success','object' => ['user' => $user]]);
        } catch (QueryException $queryException){
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        }
    }

    public function updateUser($id, Request $request){
        try {
            $request->validate([
                'user_name' => 'required',
                'email' => 'required',
                'phone_number' => 'required'
            ]);

            $user = User::query()->where('id',$id)->update([
                'user_name' => $request->user_name,
                'email' => $request->email,
                'phone_number' => $request->phone_number
            ]);

            return response(['message' => 'Güncelleme işlemi başarılı.','status' => 'success','object' => ['user' => $user]], 200);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001']);
        }
    }
}
