<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\UserDocumentCheck;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class UserController extends Controller
{
    function objectToArray(&$object)
    {
        return @json_decode(json_encode($object), true);
    }

    public function getUser($id){
        try {
            $user = User::query()->where('id',$id)->first();
            $user_profile = UserProfile::query()->where('user_id',$id)->first();
            return response(['message' => 'İşlem Başarılı.','status' => 'success','object' => ['user' => $user,'user_profile' => $user_profile]]);
        } catch (QueryException $queryException){
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        }
    }

    public function changePassword(Request $request,$user_id){
        try {
            $change_password = User::query()->where('id',$user_id)->update([
                'password' => Hash::make($request->password)
            ]);
            return response(['message' => 'Şifre değiştirme işlemi başarılı.','status' => 'success','object' => ['change_password' => $change_password]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001','e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }
    }

    public function updateUser(Request $request,$user_id){
        try {
            $profile = json_decode($request->profile);
            $productArray = $this->objectToArray($profile);
            Validator::make($productArray, [
                'name' => 'required',
                'email' => 'required',
                'phone_number' => 'required'
            ]);
//            $request->validate([
//                'user_name' => 'required',
//                'email' => 'required',
//                'phone_number' => 'required'
//            ]);

            $user = User::query()->where('id',$user_id)->update([
                'user_name' => $profile->user_name,
                'email' => $profile->email,
                'phone_number' => $profile->phone_number
            ]);

            $user_profile = UserProfile::query()->where('user_id',$user_id)->update([
                'name' => $profile->name,
                'surname' => $profile->surname,
                'birthday' => \Illuminate\Support\Carbon::parse($profile->birthday)->format('Y-m-d'),
                'gender' => $profile->gender,
                'tc_citizen' => $profile->tc_citizen,
                'tc_number' => $profile->tc_number
            ]);
            if ($request->hasFile('profile_photo')) {
                $rand = uniqid();
                $image = $request->file('profile_photo');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/ProfilePhoto/'), $image_name);
                $image_path = "/images/ProfilePhoto/" . $image_name;
                $user_profile = UserProfile::query()->where('user_id',$user_id)->update([
                    'profile_photo' => $image_path
                ]);
            }
            $user_document_checks = $profile->user_document_checks;
            foreach ($user_document_checks as $user_document_check){
                UserDocumentCheck::query()->where('user_id',$user_id)->where('document_id',$user_document_check->document_id)->update([
                    'value' => $user_document_check->value
                ]);
            }
            return response(['message' => 'Güncelleme işlemi başarılı.','status' => 'success','object' => ['user' => $user,'user_profile' => $user_profile]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001','e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }
    }

    public function deleteUser($id){
        try {

            $user = User::query()->where('id',$id)->update([
                'active' => 0,
            ]);
            return response(['message' => 'Kullanıcı silme işlemi başarılı.','status' => 'success','object' => ['user' => $user]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }


}
