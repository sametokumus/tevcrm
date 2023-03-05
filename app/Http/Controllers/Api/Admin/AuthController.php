<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\Schema\ValidationException;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {
            $request->validate([
                'admin_role_id' => 'required|exists:admin_roles,id',
                'email' => 'required|email',
                'name' => 'required',
                'surname' => 'required',
                'phone_number' => 'required',
                'password' => 'required'
            ]);


            Admin::query()->insert([
                'admin_role_id' => $request->admin_role_id,
                'email' => $request->email,
                'name' => $request->name,
                'surname' => $request->surname,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);

            return response(['message' => 'Admin başarıyla oluşturuldu sisteme giriş için epostanızı kontrol ediniz.','status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','e' => $throwable->getMessage()]);
        }

    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            $admin = Admin::query()->where('email', $request->email)->first();

            if (!$admin || !Hash::check($request->password, $admin->password)) {
                throw new \Exception('auth-001');
            }

            $admin_token = $admin->createToken('api-token', ['role:admin'])->plainTextToken;
            Admin::query()->where('id', $admin->id)->update([
                'token' => $admin_token
            ]);

            $admin->token = $admin_token;

            return response(['message' => 'Başarılı.', 'status' => 'success', 'object' => ['admin' => $admin]]);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        } catch (\Exception $exception) {
            if ($exception->getMessage() == 'auth-001') {
                return response(['message' => 'Eposta veya şifre hatalı.', 'status' => 'auth-001']);
            }
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'e' => $exception->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();
            return response(['message' => 'Çıkış başarılı.','status' => 'success']);
        } catch (\Exception $exception){
            return response(['message' => 'Hatalı işlem.','status' => 'error-001']);
        }
    }

}
