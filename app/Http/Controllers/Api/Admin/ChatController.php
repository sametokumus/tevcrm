<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\CompanyChat;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public static function sendCompanyChatMessage(Request $request)
    {
        try {

            $message = $request->input("message", null);
            $user = User::query()->where('id', $request->user_id)->first();
            event(new CompanyChat($message, $user));
            Log::info('Chat message send: ' . $message . ' / user:' . $user);

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        }catch (\Exception $e) {
            Log::error('Error sending chat message: ' . $e->getMessage());

            return response(['message' => __('Hatalı işlem.'), 'status' => 'chat-001', 'e' => $e->getMessage()]);
        }

    }
}
