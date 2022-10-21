<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\AdminUserComments;
use App\Http\Controllers\Api\Admin\AdminRoleController;
use App\Http\Controllers\Api\Admin\AdminPermissionController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\ProductTypeController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\TabController;
use App\Http\Controllers\Api\Admin\OrderStatusController;
use App\Http\Controllers\Api\Admin\ProductVariationGroupTypeController;
use App\Http\Controllers\Api\Admin\TagController;
use App\Http\Controllers\Api\Admin\CartController;
use App\Http\Controllers\Api\Admin\CarrierController;
use App\Http\Controllers\Api\Admin\OrderController;
use App\Http\Controllers\Api\Admin\ImportController;
use App\Http\Controllers\Api\Admin\ShippingTypeController;
use App\Http\Controllers\Api\Admin\CreditCardController;
use App\Http\Controllers\Api\Admin\UserController;
use App\Http\Controllers\Api\Admin\SliderController;
use App\Http\Controllers\Api\Admin\SeoController;
use App\Http\Controllers\Api\Admin\CouponController;
use App\Http\Controllers\Api\Admin\DeliveryController;
use App\Http\Controllers\Api\Admin\PopupController;
use App\Http\Controllers\Api\Admin\SubscribeController;
use App\Http\Controllers\Api\Admin\LanguageController;

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

//
Route::post('login', [AuthController::class, 'login'])->name('admin.login');


Route::middleware(['auth:sanctum', 'type.admin'])->group(function (){

    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::post('register', [AuthController::class, 'register'])->name('admin.register');

    Route::get('adminRole/getAdmins', [AdminRoleController::class, 'getAdmins']);
    Route::get('adminRole/getAdminById/{id}', [AdminRoleController::class, 'getAdminById']);
    Route::post('adminRole/addAdmin', [AdminRoleController::class, 'addAdmin']);
    Route::post('adminRole/updateAdmin/{id}', [AdminRoleController::class, 'updateAdmin']);
    Route::get('adminRole/deleteAdmin/{id}', [AdminRoleController::class, 'deleteAdmin']);

    Route::get('adminRole/getAdminRoles', [AdminRoleController::class, 'getAdminRoles']);
    Route::get('adminRole/getAdminRoleById/{id}', [AdminRoleController::class, 'getAdminRoleById']);
    Route::post('adminRole/addAdminRole', [AdminRoleController::class, 'addAdminRole']);
    Route::post('adminRole/updateAdminRole/{role_id}', [AdminRoleController::class, 'updateAdminRole']);
    Route::get('adminRole/deleteAdminRole/{role_id}', [AdminRoleController::class, 'deleteAdminRole']);

    Route::get('adminRole/getAdminRolePermissions/{role_id}', [AdminRoleController::class, 'getAdminRolePermissions']);
    Route::get('adminRole/addAdminRolePermission/{role_id}/{permission_id}', [AdminRoleController::class, 'addAdminRolePermission']);
    Route::get('adminRole/deleteAdminRolePermission/{role_id}/{permission_id}', [AdminRoleController::class, 'deleteAdminRolePermission']);

    Route::get('adminPermission/getAdminPermissions', [AdminPermissionController::class, 'getAdminPermissions']);
    Route::post('adminPermission/addAdminPermission', [AdminPermissionController::class, 'addAdminPermission']);
    Route::post('adminPermission/updateAdminPermission/{id}', [AdminPermissionController::class, 'updateAdminPermission']);
    Route::get('adminPermission/deleteAdminPermission/{id}', [AdminPermissionController::class, 'deleteAdminPermission']);




    Route::post('excel/productExcelImport', [ImportController::class, 'productExcelImport']);
    Route::post('excel/priceExcelImport', [ImportController::class, 'priceExcelImport']);
    Route::get('excel/addAllProduct', [ImportController::class, 'addAllProduct']);
    Route::get('excel/addProductPrice', [ImportController::class, 'addProductPrice']);
    Route::get('excel/productVariationUpdate', [ImportController::class, 'productVariationUpdate']);
    Route::get('excel/setProductCategory', [ImportController::class, 'setProductCategory']);
    Route::post('excel/newProduct', [ImportController::class, 'newProduct']);
    Route::post('excel/postNewProducts', [ImportController::class, 'postNewProducts']);
    Route::get('excel/updateProductNew', [ImportController::class, 'updateProductNew']);



});

