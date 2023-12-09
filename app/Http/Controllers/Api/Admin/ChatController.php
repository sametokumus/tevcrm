<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\CompanyChat;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PublicChat;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    public static function getPublicChatMessages($page)
    {
        try {

            $perPage = 20;

            $messages = PublicChat::query()
                ->where('active', 1)
                ->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page);

            foreach ($messages as $message){
                $message['sender'] = Admin::query()->where('id', $message->sender_id)->first();
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['messages' => $messages]]);
        }catch (\Exception $e) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'chat-002', 'e' => $e->getMessage()]);
        }
    }

    public static function sendPublicChatMessage(Request $request)
    {
        try {
            $message_text = $request->message;
            $user = Admin::query()->where('id', $request->user_id)->first();

            $message_id = Uuid::uuid();
            $message_id = PublicChat::query()->insertGetId([
                'message_id' => $message_id,
                'sender_id' => $request->user_id,
                'message' => $message_text
            ]);

            $message = PublicChat::query()->where('id', $message_id)->first();

            event(new CompanyChat($message, $user));
            Log::info('Chat message send: ' . $message . ' / user:' . $user);

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        }catch (\Exception $e) {
            Log::error('Error sending chat message: ' . $e->getMessage());
            return response(['message' => __('Hatalı işlem.'), 'status' => 'chat-001', 'e' => $e->getMessage()]);
        }

    }
}
