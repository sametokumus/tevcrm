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
    Route::post('productType/updateProductType/{id}', [ProductTypeController::class, 'updateProductType']);
    Route::get('productType/deleteProductType/{id}', [ProductTypeController::class, 'deleteProductType']);

    Route::post('category/addCategory', [CategoryController::class, 'addCategory']);
    Route::post('category/updateCategory/{id}', [CategoryController::class, 'updateCategory']);
    Route::get('category/deleteCategory/{id}', [CategoryController::class, 'deleteCategory']);

    Route::post('product/addFullProduct', [ProductController::class, 'addFullProduct']);
    Route::post('product/updateFullProduct/{id}', [ProductController::class, 'updateFullProduct']);
    Route::get('product/deleteProduct/{id}', [ProductController::class, 'deleteProduct']);
    Route::post('product/addProduct', [ProductController::class, 'addProduct']);
    Route::post('product/updateProduct/{id}', [ProductController::class, 'updateProduct']);

    Route::post('product/addProductTag', [ProductController::class, 'addProductTag']);
    Route::post('product/deleteProductTag', [ProductController::class, 'deleteProductTag']);

    Route::post('product/addProductCategory', [ProductController::class, 'addProductCategory']);
    Route::post('product/deleteProductCategory', [ProductController::class, 'deleteProductCategory']);

    Route::post('product/addProductDocument', [ProductController::class, 'addProductDocument']);
    Route::post('product/updateProductDocument/{id}', [ProductController::class, 'updateProductDocument']);
    Route::get('product/deleteProductDocument/{id}', [ProductController::class, 'deleteProductDocument']);

    Route::post('product/addProductVariationGroup', [ProductController::class, 'addProductVariationGroup']);
    Route::post('product/updateProductVariationGroup/{id}', [ProductController::class, 'updateProductVariationGroup']);
    Route::get('product/deleteProductVariationGroup/{id}', [ProductController::class, 'deleteProductVariationGroup']);


    Route::post('product/addFullProductVariationGroup', [ProductController::class, 'addFullProductVariationGroup']);
    Route::post('product/updateFullProductVariationGroup/{id}', [ProductController::class, 'updateFullProductVariationGroup']);
    Route::get('product/deleteFullProductVariationGroup/{id}', [ProductController::class, 'deleteFullProductVariationGroup']);

    Route::post('productVariationGroupType/addProductVariationGroupType', [ProductVariationGroupTypeController::class, 'addProductVariationGroupType']);
    Route::post('productVariationGroupType/updateProductVariationGroupType/{id}', [ProductVariationGroupTypeController::class, 'updateProductVariationGroupType']);
    Route::get('productVariationGroupType/deleteProductVariationGroupType/{id}', [ProductVariationGroupTypeController::class, 'deleteProductVariationGroupType']);

    Route::post('product/addProductVariation', [ProductController::class, 'addProductVariation']);
    Route::post('product/updateProductVariation/{id}', [ProductController::class, 'updateProductVariation']);
    Route::get('product/deleteProductVariation/{id}', [ProductController::class, 'deleteProductVariation']);

    Route::post('variationAndRule/addVariationAndRule', [ProductController::class, 'addVariationAndRule']);
    Route::post('variationAndRule/updateVariationAndRule/{id}', [ProductController::class, 'updateVariationAndRule']);
    Route::get('variationAndRule/deleteVariationAndRule/{id}', [ProductController::class, 'deleteVariationAndRule']);

    Route::post('variationImage/updateVariationImage/{id}', [ProductController::class, 'updateVariationImage']);
    Route::get('variationImage/deleteVariationImage/{id}', [ProductController::class, 'deleteVariationImage']);

    Route::post('product/addProductImage', [ProductController::class, 'addProductImage']);

    Route::post('orderStatus/addOrderStatus', [OrderStatusController::class, 'addOrderStatus']);
    Route::post('orderStatus/updateOrderStatus/{id}', [OrderStatusController::class, 'updateOrderStatus']);
    Route::get('orderStatus/deleteOrderStatus/{id}', [OrderStatusController::class, 'deleteOrderStatus']);

    Route::post('order/updateOrder/{id}', [OrderController::class, 'updateOrder']);


    Route::post('product/addProductTab', [ProductController::class, 'addProductTab']);
    Route::post('product/updateProductTab', [ProductController::class, 'updateProductTab']);
    Route::get('product/deleteProductTab/{tab_id}', [ProductController::class, 'deleteProductTab']);

    Route::post('tab/addTab', [TabController::class, 'addTab']);
    Route::post('tab/updateTab/{id}', [TabController::class, 'updateTab']);
    Route::get('tab/deleteTab/{id}', [TabController::class, 'deleteTab']);

    Route::post('tag/addTag', [TagController::class, 'addTag']);
    Route::post('tag/updateTag/{id}', [TagController::class, 'updateTag']);
    Route::get('tag/deleteTag/{id}', [TagController::class, 'deleteTag']);


    Route::get('cart/getAllCart', [CartController::class, 'getAllCart']);

    Route::post('carrier/addCarrier', [CarrierController::class, 'addCarrier']);
    Route::post('carrier/updateCarrier/{id}', [CarrierController::class, 'updateCarrier']);
    Route::get('carrier/deleteCarrier/{id}', [CarrierController::class, 'deleteCarrier']);



});

