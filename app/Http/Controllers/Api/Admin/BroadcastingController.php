<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BroadcastingController extends Controller
{
    public function authenticate(Request $request)
    {
        // Perform any authentication logic here
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized');
        }

        return response()->json(['id' => $user->id, 'name' => $user->name]);
    }
}
