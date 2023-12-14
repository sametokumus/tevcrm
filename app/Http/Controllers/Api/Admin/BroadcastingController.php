<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BroadcastingController extends Controller
{
    public function authenticate(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                abort(403, 'Unauthorized');
            }

            return response()->json([
                'id' => $user->id,
                'name' => $user->name,
                'title' => $user->title,
                'message' => $user->message,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error in BroadcastingController: ' . $e->getMessage() . ' req: '.$request);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
