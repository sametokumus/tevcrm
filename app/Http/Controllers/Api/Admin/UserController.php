<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(){
        try {
            $users = User::query()
                ->leftJoin('user_profiles','user_profiles.user_id','=','users.id')
                ->selectRaw('users.*, user_profiles.name, user_profiles.surname')
                ->get();
            return response(['message' => 'İşlem başarılı.','status' => 'success','object' => ['users' => $users]]);
        } catch (QueryException $queryException){
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001','err' => $queryException->getMessage()]);
        }
    }
}
