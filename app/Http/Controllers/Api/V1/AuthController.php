<?php

namespace App\Http\Controllers\Api\V1;

use App\Mail\UserWelcome;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'user_name' => 'required',
            'email' => 'required|email',
            'phone_number' => 'required',
            'password' => 'required'
        ]);

        $userId = User::query()->insertGetId([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'token' => Str::random(60)
        ]);

        $user = User::query()->whereId($userId)->first();

        $user->sendApiConfirmAccount($user);
        $message = 'Kullanıcı Başarıyla Oluşturuldu Sisteme Giriş İçin Mailinizi Kontrol Ediniz.';

        return response(['message' => $message], 200);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response([
                'message' => ['Email ve Şifrenizi Kontrol Ediniz.']
            ], 500);
        }
//twilio sms doğrulama tr de çalışmıyor
//        if ($user->hasVerifiedEmail()) {
//
//            $sid = getenv("TWILIO_ACCOUNT_SID");
//            $token = getenv("TWILIO_AUTH_TOKEN");
//            $twilio = new Client($sid, $token);
//            $sms_verification = $twilio->verify->v2->services("VA782c51c282e06385dcd3f16fbad69225")
//                ->verifications
//                ->create($user->phonenumber, "sms")
//
//        }

        $userToken = $user->createToken('api-token')->plainTextToken;

        return response(['token' => $userToken], 200);

    }

    public function logout (Request $request) {
        auth()->user()->tokens()->delete();
        return response(['message' => 'You have been successfully logged out.'], 200);
    }

    /**
     * Kullanıcı Email Onaylama
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function verify(Request $request) {
        $user = User::where('token', $request['token'])->first();
        if ($user->hasVerifiedEmail()) {
            $message['error'] = 'Daha Önceden Email Doğrulandı';
            return response(['message' => $message, 'code' => 422]);
        }
        $user->email_verified_at = now();
        $user->verified = true;
        $user->active = true;
        $user->token = null;
        $user->save();
        /*
           $setDelay = Carbon::parse($user->email_verified_at)->addSeconds(10);
           Bu kısımda isterseniz Kullanıcıya Hoşgeldinizi Maili İçin Gecikme Verebilirsiniz.
           Mail::queue(new \App\Mail\UserWelcome($user->name, $user->email))->delay($setDelay);
          */
        Mail::queue(new UserWelcome($user->name, $user->email));
        $message['success'] = 'Kullanıcı Email Doğrulandı';
        return response(['message' => $message, 'code' => 200]);
    }

    /**
     * Yeniden Mail Gönderme İşlemi
     *
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function resend(Request $request) {

        $user = User::query()->where('email', $request['email'])->first();
        if ($user->hasVerifiedEmail()) {
            $message['info'] = 'Daha Önceden Email Doğrulandı';
            return response(['message' => $message, 'code' => 422]);
        }
        $user->sendApiConfirmAccount($user);
        $message['info'] = 'Yeniden Mail Gönderildi';
        return response(['message' => $message, 'code' => 200]);
    }


}
