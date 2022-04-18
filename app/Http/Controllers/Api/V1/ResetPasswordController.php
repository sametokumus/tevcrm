<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\ResetPassword;
use App\Models\User;
use App\Notifications\ResetPasswordNotify;
use App\Notifications\ResetPasswordSuccess;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'email' => 'required|string|email|exists:users'
        ]);
//        if ($validator->fails()) {
//            $message['errors'] = $validator->errors();
//            return response()->json(['message' => $message, 'code' => 400]);
//        }
        $user = User::query()->where('email', $request['email'])->first();
        if (!$user) {
            $message['error'] = 'Mail Adresi Bulunamadı';
            return response()->json(['message' => $message, 'code' => 404]);
        }
        $resetpassword = ResetPassword::query()->updateOrCreate(
            [
                'email' => $user->email,
            ],
            [
                'email' => $user->email,
                'token' => Str::random(45),
            ]
        );
        if ($user && $resetpassword) {
            $user->notify(new ResetPasswordNotify($resetpassword->token));
        }
        $message['success'] = 'Email Şifre Yenileme Linki Gönderildi';
        return response()->json(['message' => $message, 'code' => 201]);
    }

    /**
     * Find Token
     *
     * @param[string] token
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function find($token) {
        $resetpassword = ResetPassword::query()->where('token', $token)->first();
        if (!$token) {
            $message['error'] = 'Token Geçersiz';
            return response()->json(['message' => $message, 'code' => 404]);
        }
        if (Carbon::parse($resetpassword->created_at)->addMinutes(720)->isPast()) {
            $resetpassword->delete();
            $message['error'] = 'Token Geçersiz';
            return response()->json(['message' => $message, 'code' => 404]);
        }
        $message['success'] = 'Başarılı';
        return response()->json(['resetpassword' => $resetpassword, 'code' => 200]);
    }

    /**
     * ResetPassword Token Store
     *
     * @param[string] email
     * @param[string] password
     * @param[string] token
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function resetPassword(Request $request) {
//        $validator = $request->validate([
//            'email' => 'required|string|email|exists:users',
//            'token' => 'required|string',
//            'password' => 'required|string|min:6|confirmed',
//        ]);
//        if ($validator->fails()) {
//            $message['errors'] = $validator->errors();
//            return response()->json(['message' => $message, 'code' => 400]);
//        }
        $resetpassword = ResetPassword::query()->updateOrCreate(
            [
                'email' => $request->email,
                'token' => $request->token,
            ]
        )->first();
        if (!$resetpassword) {
            $message['error'] = 'Mail Adresi Bulunamadı';
            return response()->json(['message' => $message, 'code' => 404]);
        }
        $user = User::query()->where('email', $request->email)->first();
        if (!$user) {
            $message['error'] = 'Kullanıcı Bulunamadı';
            return response()->json(['message' => $message, 'code' => 404]);
        }
        User::query()->where('email', $request->email)->update([
            'password' => Hash::make($request->password)
        ]);
        ResetPassword::query()->where('email', $request->email)->delete();
        $user->notify(new ResetPasswordSuccess());
        $message['success'] = 'Kullanıcı Şifresi Başarıyla Değiştirildi.';
        return response()->json(['message' => $message, 'code' => 201]);
    }
}
