<?php

use App\Http\Controllers\Api\Admin\AuthController;
use App\Http\Controllers\Api\Admin\AdminUserComments;
use App\Http\Controllers\Api\Admin\AdminRoleController;
use App\Http\Controllers\Api\Admin\AdminPermissionController;
use \App\Http\Controllers\Api\Admin\AdminPermissionRolesController;
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


Route::post('login', [AuthController::class, 'login'])->name('admin.login');


Route::middleware(['auth:sanctum', 'type.admin'])->group(function (){

    Route::get('logout', [AuthController::class, 'logout'])->name('admin.logout');
    Route::post('register', [AuthController::class, 'register'])->name('admin.register');
    Route::get('adminUserComment/getAdminUserComment', [AdminUserComments::class, 'getAdminUserComment']);
    Route::post('adminUserComment/addAdminUserComment', [AdminUserComments::class, 'addAdminUserComment']);
    Route::get('adminRole/getAdminRole', [AdminRoleController::class, 'getAdminRole']);
    Route::post('adminRole/addAdminRole', [AdminRoleController::class, 'addAdminRole']);
    Route::get('adminPermission/getAdminPermission', [AdminPermissionController::class, 'getAdminPermission']);
    Route::post('adminPermission/addAdminPermission', [AdminPermissionController::class, 'addAdminPermission']);
    Route::get('adminPermissionRole/getAdminPermissionRoles', [AdminPermissionRolesController::class, 'getAdminPermissionRoles']);
    Route::post('adminPermissionRole/addAdminPermissionRoles', [AdminPermissionRolesController::class, 'addAdminPermissionRoles']);

});

