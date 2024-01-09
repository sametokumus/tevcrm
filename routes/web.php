<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use \App\Http\Controllers\Api\Admin\BroadcastingController;

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
Route::get('/my-dashboard', function () { return view('my-dashboard'); });
Route::get('/staff-dashboard', function () { return view('staff-dashboard'); });
Route::get('/news-feed', function () { return view('news-feed'); });
Route::get('/teams', function () { return view('admins'); });
Route::get('/my-account', function () { return view('account'); });
Route::get('/roles', function () { return view('roles'); });
Route::get('/potential-customers', function () { return view('potential-customers'); });
Route::get('/customers', function () { return view('customers'); });
Route::get('/suppliers', function () { return view('suppliers'); });
Route::get('/company-detail/{id}', function () { return view('company-detail'); });
Route::get('/customer-dashboard/{id}', function () { return view('customer-dashboard'); });
Route::get('/offer-requests', function () { return view('offer-requests'); });
Route::get('/offer-request', function () { return view('add-offer-request'); });
Route::get('/new-offer-request', function () { return view('new-offer-request'); });
Route::get('/offer-request-products/{id}', function () { return view('offer-request-products'); });
Route::get('/offer-request/{id}', function () { return view('update-offer-request'); });
Route::get('/offer/{id}', function () { return view('offer'); });
Route::get('/offer-rev/{id}', function () { return view('offer-rev'); });
Route::get('/sales', function () { return view('sales'); });
Route::get('/sales-cancelled', function () { return view('sales-cancelled'); });
Route::get('/approved-sales', function () { return view('approved-sales'); });
Route::get('/sale-detail/{sale_id}', function () { return view('sale-detail'); });
Route::get('/packing-list/{sale_id}', function () { return view('packing-list'); });


Route::get('/sw-1', function () { return view('sw-step1'); });
Route::get('/sw-2/{request_id}', function () { return view('sw-step2'); });
Route::get('/sw-2-new/{request_id}', function () { return view('sw-step2-new'); });
Route::get('/sw-3/{sale_id}', function () { return view('sw-step3'); });
Route::get('/sw-3-rev/{sale_id}', function () { return view('sw-step3-rev'); });
Route::get('/sw-4/{sale_id}', function () { return view('sw-step4-new'); });
Route::get('/sw-4-rev/{sale_id}', function () { return view('sw-step4-new'); });


Route::get('/offer-print/{offer_id}', function () { return view('offer-print'); });
Route::get('/quote-print/{sale_id}', function () { return view('quote-print'); });
Route::get('/purchasing-order-print/{sale_id}', function () { return view('purchasing-order-print'); });
Route::get('/order-confirmation-print/{sale_id}', function () { return view('order-confirmation-print'); });
Route::get('/proforma-invoice-print/{sale_id}', function () { return view('proforma-invoice-print'); });
Route::get('/invoice-print/{sale_id}', function () { return view('invoice-print'); });
Route::get('/packing-list-print/{sale_id}', function () { return view('packing-list-print'); });
Route::get('/pl-invoice-print/{sale_id}', function () { return view('pl-invoice-print'); });


Route::get('/notify-settings', function () { return view('notify-settings'); });
Route::get('/email-layouts', function () { return view('email-layouts'); });

Route::get('/settings', function () { return view('settings'); });
Route::get('/currency-logs', function () { return view('currency-logs'); });
Route::get('/offer-request', function () { return view('add-offer-request'); });
Route::get('/contacts', function () { return view('contacts'); });
Route::get('/contact-detail/{id}', function () { return view('contact-detail'); });

Route::get('/products', function () { return view('products'); });
Route::get('/brands', function () { return view('brands'); });
Route::get('/categories', function () { return view('categories'); });

Route::get('/activities', function () { return view('activities'); });
Route::get('/past-activities', function () { return view('past-activities'); });

Route::get('/staff-targets', function () { return view('staff-targets'); });
Route::get('/target-dashboard', function () { return view('target-dashboard'); });


Route::get('/accounting-dashboard', function () { return view('accounting-dashboard'); });
Route::get('/pending-accounting', function () { return view('pending-accounting'); });
Route::get('/ongoing-accounting', function () { return view('ongoing-accounting'); });
Route::get('/completed-accounting', function () { return view('completed-accounting'); });
Route::get('/accounting-detail/{sale_id}', function () { return view('accounting-detail'); });


Route::get('/mobile-documents/{sale_id}', function () { return view('mobile-documents'); });


Route::post('/lang', function(Request $request) {
    $locale = $request->input('lang');
    App::setLocale($locale);
    session()->put('locale', $locale);
});


Route::get('fonts/ChakraPetch/ChakraPetch-Regular.ttf', function () {
    return response()->file(public_path('fonts/ChakraPetch/ChakraPetch-Regular.ttf'));
});



Route::get('/chat', function (\BeyondCode\LaravelWebSockets\Apps\AppProvider $appProvider) {
    return view('chat', [
        "port" => env("LARAVEL_WEBSOCKETS_PORT"),
        "host" => env("LARAVEL_WEBSOCKETS_HOST"),
        "authEndpoint" => "/api/sockets/connect",
        "logChannel" => \BeyondCode\LaravelWebSockets\Dashboard\DashboardLogger::LOG_CHANNEL_PREFIX,
        "apps" => $appProvider->all()
    ]);
});

