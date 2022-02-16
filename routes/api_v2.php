<?php

use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\UserController;
use App\Http\Controllers\Api\V2\ResetPasswordController;
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

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});


Route::post('auth/login/', [AuthController::class, 'login']);
Route::post('auth/register/', [AuthController::class, 'register']);
Route::get('email/verify/{token}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('email/resendVerifyEmail', [AuthController::class, 'resend']);
Route::get('login/verify/{code}', [AuthController::class, 'loginVerify']);

Route::get('password/find/{token}', [ResetPasswordController::class, 'find']);
Route::post('password/sendtoken', [ResetPasswordController::class, 'store']);
Route::post('password/reset',[ResetPasswordController::class, 'resetPassword']);

//Route::middleware('auth:sanctum')->get('/users', function (Request $request) {
//    return User::all();
//});

Route::middleware('auth:sanctum')->group(function (){

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/getUsers', [UserController::class, 'getUsers']);
    Route::get('/getUser/{id}', [UserController::class, 'getUser']);
    Route::post('/updateUser/{id}', [UserController::class, 'updateUser']);

});
