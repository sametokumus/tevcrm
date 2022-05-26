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
use App\Http\Controllers\Api\V1\CartController;
use App\Http\Controllers\Api\V1\OrderController;
use App\Http\Controllers\Api\V1\TabController;
use App\Http\Controllers\Api\V1\ProductVariationGroupTypeController;
use App\Http\Controllers\Api\V1\TagController;
use App\Http\Controllers\Api\V1\CarrierController;
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



});

Route::post('/cart/addCart', [CartController::class, 'addCart']);
Route::post('/cart/updateCartProduct', [CartController::class, 'updateCartProduct']);
Route::post('/cart/deleteCartProduct', [CartController::class, 'deleteCartProduct']);
Route::get('/cart/getCartById/{cart_id}', [CartController::class, 'getCartById']);
Route::get('/cart/getUserAllCartById/{user_id}', [CartController::class, 'getUserAllCartById']);
Route::get('/cart/getClearCartById/{cart_id}', [CartController::class, 'getClearCartById']);

Route::post('/order/addOrder',[OrderController::class,'addOrder']);

Route::get('/brand/getBrands', [BrandController::class, 'getBrands']);
Route::get('/brand/getBrandById/{id}', [BrandController::class, 'getBrandById']);
Route::get('/productType/getProductType', [ProductTypeController::class, 'getProductType']);
Route::get('/productType/getProductTypeById/{type_id}', [ProductTypeController::class, 'getProductTypeById']);
Route::get('/category/getCategory', [CategoryController::class, 'getCategory']);
Route::get('/category/getParentCategory', [CategoryController::class, 'getParentCategory']);
Route::get('/category/getCategoryById/{category_id}', [CategoryController::class, 'getCategoryById']);

Route::get('/product/getAllProduct', [ProductController::class, 'getAllProduct']);
Route::get('/product/getAllProductById/{id}', [ProductController::class, 'getAllProductById']);
Route::get('/product/getAllProductWithVariationById/{product_id}/{variation_id}', [ProductController::class, 'getAllProductWithVariationById']);

Route::get('/product/getProduct', [ProductController::class, 'getProduct']);
Route::get('/product/getProductById/{id}', [ProductController::class, 'getProductById']);

Route::get('/product/getProductsByCategoryId/{category_id}', [ProductController::class, 'getProductsByCategoryId']);
Route::get('/product/getProductsBySlug/{slug}', [ProductController::class, 'getProductsBySlug']);

Route::get('/product/getBrandByIdProduct', [ProductController::class, 'getBrandByIdProduct']);




Route::get('/product/getProductTagById/{product_id}', [ProductController::class, 'getProductTagById']);
Route::get('/product/getProductCategoryById/{product_id}', [ProductController::class, 'getProductCategoryById']);
Route::get('/product/getProductDocumentById/{product_id}', [ProductController::class, 'getProductDocumentById']);
Route::get('/product/getProductVariationGroupById/{product_id}', [ProductController::class, 'getProductVariationGroupById']);
Route::get('/product/getProductVariationById/{id}', [ProductController::class, 'getProductVariationById']);
Route::get('/product/getProductVariationsById/{id}', [ProductController::class, 'getProductVariationsById']);
Route::get('/product/getVariationsImageById/{product_id}', [ProductController::class, 'getVariationsImageById']);
Route::get('/product/getVariationImageById/{variation_id}', [ProductController::class, 'getVariationImageById']);
Route::get('/product/getProductTabsById/{product_id}', [ProductController::class, 'getProductTabsById']);
Route::get('/product/getProductTabById/{tab_id}', [ProductController::class, 'getProductTabById']);


Route::get('/productDocument/getProductDocument', [ProductDocumentController::class, 'getProductDocument']);


Route::get('/productVariationGroupType/getProductVariationGroupTypes', [ProductVariationGroupTypeController::class, 'getProductVariationGroupTypes']);
Route::get('/productVariationGroupType/getProductVariationGroupTypeById/{id}', [ProductVariationGroupTypeController::class, 'getProductVariationGroupTypeById']);


Route::get('/productType/getProductTypes', [ProductTypeController::class, 'getProductTypes']);
Route::get('/productType/getProductVariationById/{variation_id}', [ProductTypeController::class, 'getProductTypeById']);

Route::get('/tab/getTabs', [TabController::class, 'getTabs']);
Route::get('/tab/getTabById/{tab_id}', [TabController::class, 'getTabById']);

Route::get('/tag/getTags', [TagController::class, 'getTags']);
Route::get('/tag/getTagById/{id}', [TagController::class, 'getTagById']);

Route::get('/carrier/getCarriers', [CarrierController::class, 'getCarriers']);
Route::get('/carrier/getCarrierById/{id}', [CarrierController::class, 'getCarrierById']);
