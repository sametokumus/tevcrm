<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\CountriesController;
use App\Http\Controllers\Api\V1\CitiesController;
use App\Http\Controllers\Api\V1\SearchController;
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


Route::post('auth/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('auth/register', [AuthController::class, 'register']);
Route::get('auth/verify/{token}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('auth/resend-verify-email', [AuthController::class, 'resend']);

Route::get('password/find/{token}', [ResetPasswordController::class, 'find']);
Route::post('password/send-token', [ResetPasswordController::class, 'store']);
Route::post('password/reset',[ResetPasswordController::class, 'resetPassword']);


Route::middleware(['auth:sanctum', 'type.user'])->group(function (){

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/user/getUsers', [UserController::class, 'getUsers']);
    Route::get('/user/getUser/{id}', [UserController::class, 'getUser']);
    Route::post('/user/updateUser/{user_id}', [UserController::class, 'updateUser']);
    Route::get('/user/deleteUser/{id}', [UserController::class, 'deleteUser']);
    Route::post('/user/changePassword/{user_id}', [UserController::class, 'changePassword']);
    Route::post('/user/addUserFavorite', [UserController::class, 'addUserFavorite']);
    Route::get('/user/deleteUserFavorite/{user_id}', [UserController::class, 'deleteUserFavorite']);
    Route::get('/user/getUserFavorites/{user_id}', [UserController::class, 'getUserFavorites']);
    Route::post('/user/addRefundRequest', [UserController::class, 'addRefundRequest']);


    Route::get('/addresses/getAddressesByUserId/{user_id}', [AddressController::class, 'getAddressesByUserId']);
    Route::get('/addresses/getAddressByUserIdAddressId/{user_id}/{address_id}', [AddressController::class, 'getAddressByUserIdAddressId']);
    Route::post('/addresses/addUserAddresses/{user_id}', [AddressController::class, 'addUserAddresses']);
    Route::post('/addresses/updateUserAddresses/{address_id}/{user_id}', [AddressController::class, 'updateUserAddresses']);
    Route::get('/addresses/deleteUserAddresses/{address_id}', [AddressController::class, 'deleteUserAddresses']);


    Route::get('/countries/getCountries', [CountriesController::class, 'getCountries']);
    Route::post('/countries/addCountries', [CountriesController::class, 'addCountries']);

    Route::get('/cities/getCitiesByCountryId/{country_id}', [CitiesController::class, 'getCitiesByCountryId']);
    Route::get('/cities/getDistrictsByCityId/{city_id}', [CitiesController::class, 'getDistrictsByCityId']);
    Route::post('/cities/addCities/{country_id}', [CitiesController::class, 'addCities']);


});
