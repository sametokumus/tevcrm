<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(){
        return User::all();
    }
    public function getUser($id){
        $user = User::whereId($id)->first();
        return $user->name;
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
