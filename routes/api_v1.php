<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('auth/login/', [AuthController::class, 'login']);
Route::post('auth/register/', [AuthController::class, 'register']);
Route::get('auth/verify/{token}', [AuthController::class, 'verify']);
Route::post('auth/resendVerifyEmail', [AuthController::class, 'resend']);

Route::get('password/find/{token}', [ResetPasswordController::class, 'find']);
Route::post('password/sendtoken', [ResetPasswordController::class, 'store']);
Route::post('password/reset',[ResetPasswordController::class, 'resetPassword']);


Route::middleware('auth:sanctum')->group(function (){

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::get('/getUser/{id}', [UserController::class, 'getUser']);
    Route::post('/updateUser/{id}', [UserController::class, 'updateUser']);

});
