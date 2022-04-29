<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\AdminUserComments;
use App\Http\Controllers\Api\Admin\AdminRoleController;
use App\Http\Controllers\Api\Admin\AdminPermissionController;
//use \App\Http\Controllers\Api\Admin\AdminPermissionRolesController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\ProductTypeController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\AdminController;
use App\Http\Controllers\Api\Admin\ProductTabController;
//use App\Http\Controllers\Api\Admin\ProductDocumentController;

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


Route::post('login', [AuthController::class, 'login'])->name('admin.login');


Route::middleware(['auth:sanctum', 'type.admin'])->group(function (){

    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::post('register', [AuthController::class, 'register'])->name('admin.register');

    Route::get('adminUserComment/getAdminUserComment', [AdminUserComments::class, 'getAdminUserComment']);
    Route::post('adminUserComment/addAdminUserComment', [AdminUserComments::class, 'addAdminUserComment']);
    Route::post('adminUserComment/updateAdminUserComment/{id}', [AdminUserComments::class, 'updateAdminUserComment']);
    Route::get('adminUserComment/deleteAdminUserComment/{id}', [AdminUserComments::class, 'deleteAdminUserComment']);

    Route::get('adminRole/getAdminRole', [AdminRoleController::class, 'getAdminRole']);
    Route::post('adminRole/addAdminRole', [AdminRoleController::class, 'addAdminRole']);
    Route::post('adminRole/updateAdminRole/{id}/{permission_role_id}', [AdminRoleController::class, 'updateAdminRole']);
    Route::get('adminRole/deleteAdminRole/{id}/{permission_role_id}', [AdminRoleController::class, 'deleteAdminRole']);

    Route::post('admin/updateAdmin/{id}', [AdminController::class, 'updateAdmin']);
    Route::get('admin/deleteAdmin/{id}', [AdminController::class, 'deleteAdmin']);


    Route::get('adminPermission/getAdminPermission', [AdminPermissionController::class, 'getAdminPermission']);
    Route::post('adminPermission/addAdminPermission', [AdminPermissionController::class, 'addAdminPermission']);
    Route::post('adminPermission/updateAdminPermission/{id}', [AdminPermissionController::class, 'updateAdminPermission']);
    Route::get('adminPermission/deleteAdminPermission/{id}', [AdminPermissionController::class, 'deleteAdminPermission']);

//    Route::get('adminPermissionRole/getAdminPermissionRoles', [AdminPermissionRolesController::class, 'getAdminPermissionRoles']);
//    Route::post('adminPermissionRole/addAdminPermissionRoles', [AdminPermissionRolesController::class, 'addAdminPermissionRoles']);
//    Route::post('adminPermissionRole/updateAdminPermissionRoles/{id}', [AdminPermissionRolesController::class, 'updateAdminPermissionRoles']);
//    Route::get('adminPermissionRole/deleteAdminPermissionRoles/{id}', [AdminPermissionRolesController::class, 'deleteAdminPermissionRoles']);

    Route::post('brand/addBrand', [BrandController::class, 'addBrand']);
    Route::post('brand/updateBrand/{id}', [BrandController::class, 'updateBrand']);
    Route::get('brand/deleteBrand/{id}', [BrandController::class, 'deleteBrand']);

    Route::post('productType/addProductType', [ProductTypeController::class, 'addProductType']);
    Route::post('productType/updateProduct/{product_id}', [ProductTypeController::class, 'updateProduct']);
    Route::get('productType/deleteProductType/{id}', [ProductTypeController::class, 'deleteProductType']);

    Route::post('category/addCategory', [CategoryController::class, 'addCategory']);
    Route::post('category/updateCategory/{id}', [CategoryController::class, 'updateCategory']);
    Route::get('category/deleteCategory/{id}', [CategoryController::class, 'deleteCategory']);

    Route::post('product/addProduct', [ProductController::class, 'addProduct']);
    Route::post('product/updateProduct/{id}', [ProductController::class, 'updateProduct']);

    Route::post('product/addProductVariationGroup', [ProductController::class, 'addProductVariationGroup']);
    Route::post('product/updateProductVariationGroup/{id}', [ProductController::class, 'updateProductVariationGroup']);

    Route::post('product/addProductVariation', [ProductController::class, 'addProductVariation']);
    Route::post('product/updateProductVariation/{id}', [ProductController::class, 'updateProductVariation']);

    Route::post('product/addProductImage', [ProductController::class, 'addProductImage']);

    Route::post('productTab/addProductTab', [ProductTabController::class, 'addProductTab']);
    Route::post('productTab/updateProductTab/{id}', [ProductTabController::class, 'updateProductTab']);



});

