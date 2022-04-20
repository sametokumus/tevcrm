<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

    public function updateUser(Request $request,$id){
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

            $user_profile = UserProfile::query()->where('id',$id)->update([
                'name' => $request->name,
                'surname' => $request->surname,
                'birthday' => \Illuminate\Support\Carbon::parse($request->date)->format('Y-m-d'),
                'gender' => $request->gender,
                'tc_citizen' => $request->tc_citizen,
                'tc_number' => $request->tc_number
            ]);
            if ($request->hasFile('profile_photo')) {
                $rand = uniqid();
                $image = $request->file('profile_photo');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/ProfilePhoto/'), $image_name);
                $image_path = "/images/ProfilePhoto/" . $image_name;
                $user_profile = UserProfile::query()->where('user_id',$id)->update([
                    'profile_photo' => $image_path
                ]);
            }

            return response(['message' => 'Güncelleme işlemi başarılı.','status' => 'success','object' => ['user' => $user,'user_profile' => $user_profile]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001']);
        }
    }

}
