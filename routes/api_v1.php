<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\UserController;
use App\Http\Controllers\Api\V1\ResetPasswordController;
use App\Http\Controllers\Api\V1\AddressController;
use App\Http\Controllers\Api\V1\CountriesController;
use App\Http\Controllers\Api\V1\CitiesController;
use App\Http\Controllers\Api\V1\ContactRulesController;
use App\Http\Controllers\Api\V1\UserContactRulesController;
use App\Http\Controllers\Api\V1\UserDocumentController;
use App\Http\Controllers\Api\V1\UserDocumentChecksController;
use App\Http\Controllers\Api\V1\BrandController;
use App\Http\Controllers\Api\V1\ProductTypeController;
use App\Http\Controllers\Api\V1\CategoryController;
use App\Http\Controllers\Api\V1\ProductController;
use App\Http\Controllers\Api\V1\ProductDocumentController;
use App\Http\Controllers\Api\V1\UserProfileController;
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
    Route::post('/user/updateUser/{user_id}/{document_id}', [UserController::class, 'updateUser']);
    Route::get('/user/deleteUser/{id}', [UserController::class, 'deleteUser']);

    Route::get('/addresses/getAddressesByUserId/{user_id}', [AddressController::class, 'getAddressesByUserId']);
    Route::post('/addresses/addUserAddresses/{user_id}', [AddressController::class, 'addUserAddresses']);
    Route::post('/addresses/updateUserAddresses/{address_id}/{user_id}', [AddressController::class, 'updateUserAddresses']);
    Route::get('/addresses/deleteUserAddresses/{address_id}', [AddressController::class, 'deleteUserAddresses']);


    Route::get('/countries/getCountries', [CountriesController::class, 'getCountries']);
    Route::post('/countries/addCountries', [CountriesController::class, 'addCountries']);

    Route::get('/cities/getCities', [CitiesController::class, 'getCities']);
    Route::post('/cities/addCities/{country_id}', [CitiesController::class, 'addCities']);

    Route::get('/contactRules/getContactRules', [ContactRulesController::class, 'getContactRules']);
    Route::post('/contactRules/addContactRules', [ContactRulesController::class, 'addContactRules']);
    Route::post('/contactRules/updateContactRules/{id}', [ContactRulesController::class, 'updateContactRules']);

    Route::get('/contactRules/getContactRulesByUserId/{user_id}', [UserContactRulesController::class, 'getContactRulesByUserId']);
    Route::post('/contactRules/updateContactRulesByUserId/{user_id}/{contact_rule_id}', [UserContactRulesController::class, 'updateContactRulesByUserId']);

    Route::get('/userDocuments/getUserDocuments', [UserDocumentController::class, 'getUserDocuments']);
    Route::post('/userDocuments/addUserDocuments', [UserDocumentController::class, 'addUserDocuments']);
    Route::post('/userDocuments/updateUserDocuments/{document_id}', [UserDocumentController::class, 'updateUserDocuments']);
    Route::get('/userDocuments/deleteUserDocuments/{document_id}', [UserDocumentController::class, 'deleteUserDocuments']);

    Route::get('/userDocuments/getUserDocumentChecksByUserId/{user_id}', [UserDocumentChecksController::class, 'getUserDocumentChecksByUserId']);
    Route::post('/userDocuments/updateUserDocumentChecksByUserId/{document_id}/{user_id}', [UserDocumentChecksController::class, 'updateUserDocumentChecksByUserId']);
//    Route::post('/userDocuments/deleteUserDocumentsChecksByUserId/{document_id}', [UserDocumentChecksController::class, 'deleteUserDocumentsChecksByUserId']);


    Route::get('/brand/getBrand', [BrandController::class, 'getBrand']);
    Route::get('/productType/getProductType', [ProductTypeController::class, 'getProductType']);
    Route::get('/category/getCategory', [CategoryController::class, 'getCategory']);
    Route::get('/product/getProduct', [ProductController::class, 'getProduct']);
    Route::get('/productDocument/getProductDocument', [ProductDocumentController::class, 'getProductDocument']);

});
