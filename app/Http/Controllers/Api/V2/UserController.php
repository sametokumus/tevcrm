<?php

namespace App\Http\Controllers\Api\V2;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getUsers(){
        return User::all();
    }
    public function getUser($id){
        return User::whereId($id)->first();
    }
    public function updateUser($id, Request $request){
        $request->validate([
            'name' => 'required'
        ]);

        $user = User::whereId($id)->update([
            'name' => $request->name
        ]);

        return response(['user' => $user], 200);
    }
}
