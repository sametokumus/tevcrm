<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUsers(){
        try {
            $users = User::query()->where('active',1)->where('verified',1)->get();
            return response(['message' => 'İşlem başarılı.','status' => 'success','object' => ['users' => $users]]);
        } catch (QueryException $queryException){
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        }
    }
}
