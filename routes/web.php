<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { return view('login'); });
Route::get('/login', function () { return view('login'); });
Route::get('/dashboard', function () { return view('dashboard'); });
Route::get('/news-feed', function () { return view('news-feed'); });
Route::get('/teams', function () { return view('admins'); });
Route::get('/roles', function () { return view('roles'); });
Route::get('/potential-customers', function () { return view('potential-customers'); });
Route::get('/customers', function () { return view('customers'); });
Route::get('/suppliers', function () { return view('suppliers'); });
Route::get('/company-detail/{id}', function () { return view('company-detail'); });
Route::get('/offer-requests', function () { return view('offer-requests'); });
Route::get('/offer-request', function () { return view('add-offer-request'); });
Route::get('/offer-request/{id}', function () { return view('update-offer-request'); });
Route::get('/offer/{id}', function () { return view('offer'); });
Route::get('/sales', function () { return view('sales'); });


Route::get('/sw-1', function () { return view('sw-step1'); });
Route::get('/sw-2/{request_id}', function () { return view('sw-step2'); });
Route::get('/sw-3/{sale_id}', function () { return view('sw-step3'); });


Route::get('/offer-print/{offer_id}', function () { return view('offer-print'); });
Route::get('/quote-print/{sale_id}', function () { return view('quote-print'); });
Route::get('/purchasing-order-print/{sale_id}', function () { return view('purchasing-order-print'); });
Route::get('/order-confirmation-print/{sale_id}', function () { return view('order-confirmation-print'); });
Route::get('/proforma-invoice-print/{sale_id}', function () { return view('proforma-invoice-print'); });
Route::get('/invoice-print/{sale_id}', function () { return view('invoice-print'); });


Route::get('/settings', function () { return view('settings'); });

Route::get('/products', function () { return view('products'); });
Route::get('/brands', function () { return view('brands'); });
Route::get('/categories', function () { return view('categories'); });


Route::post('/lang', function(Request $request) {
    $locale = $request->input('lang');
    App::setLocale($locale);
    session()->put('locale', $locale);
});
