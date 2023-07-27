<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\CurrencyHelper;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminStatusRole;
use App\Models\CancelNote;
use App\Models\Company;
use App\Models\CurrencyLog;
use App\Models\Employee;
use App\Models\Measurement;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\OrderConfirmationDetail;
use App\Models\PackingList;
use App\Models\PackingListProduct;
use App\Models\Product;
use App\Models\ProformaInvoiceDetails;
use App\Models\PurchasingOrderDetails;
use App\Models\Quote;
use App\Models\RfqDetails;
use App\Models\Sale;
use App\Models\SaleNote;
use App\Models\SaleOffer;
use App\Models\SaleTransaction;
use App\Models\SaleTransactionPayment;
use App\Models\Status;
use App\Models\StatusHistory;
use App\Models\User;
use App\Models\UserContactRule;
use App\Models\UserDocumentCheck;
use App\Models\UserProfile;
use DateTime;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Nette\Schema\ValidationException;
use Carbon\Carbon;

class SaleController extends Controller
{

    public function deleteSale($sale_id)
    {
        try {

            SaleOffer::query()->where('sale_id', $sale_id)->update([
                'active' => 0
            ]);
            Sale::query()->where('sale_id', $sale_id)->update([
                'active' => 0
            ]);

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSales()
    {
        try {
            $sales = Sale::query()
                ->leftJoin('contacts', 'contacts.id', '=', 'sales.owner_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name, contacts.short_code as owner_short_code')
                ->where('sales.active',1)
                ->get();

            foreach ($sales as $sale) {
                $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale->sale_id)->get();

                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
                $sale['request'] = $offer_request;
                $sale['status'] = Status::query()->where('id', $sale->status_id)->first();
//                $sale_offer = SaleOffer::query()->where('sale_id', $sale->sale_id)->first();
//                $sale['currency'] = '';
//                if ($sale_offer){
//                    if ($sale_offer->offer_currency != '' && $sale_offer->offer_currency != null){
//                        $sale['currency'] = $sale_offer->offer_currency;
//                    }
//                }

                $current_time = Carbon::now();
                if ($sale->updated_at != null){
                    $updated_at = $sale->updated_at;
//                    $updated_at = Carbon::parse($sale->updated_at);
//                    $updated_at = $updated_at->subHours(3);
                }else{
                    $updated_at = $sale->created_at;
//                    $updated_at = Carbon::parse($sale->created_at);
//                    $updated_at = $updated_at->subHours(3);
                }

                $difference = $updated_at->diffForHumans($current_time);
                $sale['diff_last_day'] = $difference;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getFilteredSales(Request $request, $user_id)
    {
        try {
            $admin = Admin::query()->where('id', $user_id)->first();

            $sales = Sale::query()
                ->leftJoin('contacts', 'contacts.id', '=', 'sales.owner_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                ->where('sales.active',1);

            if ($request->owner != 0){
                $sales = $sales->where('sales.owner_id', $request->owner);
            }

            if ($request->status != 0){
                $sales = $sales->where('sales.status_id', $request->status);
            }

            if ($request->authorized_personnel != 0){
                $sales = $sales->where('offer_requests.authorized_personnel_id', $request->authorized_personnel);
            }

            if ($request->purchasing_staff != 0){
                $sales = $sales->where('offer_requests.purchasing_staff_id', $request->purchasing_staff);
            }

            if ($request->company != 0){
                $sales = $sales->where('offer_requests.company_id', $request->company);
            }

            if ($request->company_employee != '' && $request->company_employee != 0){
                $sales = $sales->where('offer_requests.company_employee_id', $request->company_employee);
            }

            $sales = $sales
                ->selectRaw('sales.*, statuses.name as status_name, contacts.short_code as owner_short_code')
                ->get();

            foreach ($sales as $sale) {
                $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale->sale_id)->get();

                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
                $sale['request'] = $offer_request;
                $sale['status'] = Status::query()->where('id', $sale->status_id)->first();
//                $sale_offer = SaleOffer::query()->where('sale_id', $sale->sale_id)->first();
//                $sale['currency'] = '';
//                if ($sale_offer){
//                    if ($sale_offer->offer_currency != '' && $sale_offer->offer_currency != null){
//                        $sale['currency'] = $sale_offer->offer_currency;
//                    }
//                }

                $current_time = Carbon::now();
                if ($sale->updated_at != null){
                    $updated_at = $sale->updated_at;
//                    $updated_at = Carbon::parse($sale->updated_at);
//                    $updated_at = $updated_at->subHours(3);
                }else{
                    $updated_at = $sale->created_at;
//                    $updated_at = Carbon::parse($sale->created_at);
//                    $updated_at = $updated_at->subHours(3);
                }

                $difference = $updated_at->diffForHumans($current_time);
                $sale['diff_last_day'] = $difference;

            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getActiveSales($user_id)
    {
        try {
            $admin = Admin::query()->where('id', $user_id)->first();

            $sales = Sale::query()
                ->leftJoin('contacts', 'contacts.id', '=', 'sales.owner_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name, contacts.short_code as owner_short_code')
                ->where('sales.active',1)
//                ->where('statuses.period','continue')
                ->whereRaw("(statuses.period = 'continue' OR statuses.period = 'approved')")
                ->get();

            foreach ($sales as $sale) {

                $status_role = AdminStatusRole::query()->where('admin_role_id', $admin->admin_role_id)->where('status_id', $sale->status_id)->where('active', 1)->count();
                if ($status_role > 0){
                    $sale['authorization'] = 1;
                }else{
                    $sale['authorization'] = 0;
                }

                $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale->sale_id)->get();

                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
                $sale['request'] = $offer_request;
                $sale['status'] = Status::query()->where('id', $sale->status_id)->first();
//                $sale_offer = SaleOffer::query()->where('sale_id', $sale->sale_id)->first();
//                $sale['currency'] = '';
//                if ($sale_offer){
//                    if ($sale_offer->offer_currency != '' && $sale_offer->offer_currency != null){
//                        $sale['currency'] = $sale_offer->offer_currency;
//                    }
//                }

                $current_time = Carbon::now();
                if ($sale->updated_at != null){
                    $updated_at = $sale->updated_at;
//                    $updated_at = Carbon::parse($sale->updated_at);
//                    $updated_at = $updated_at->subHours(3);
                }else{
                    $updated_at = $sale->created_at;
                    $updated_at = Carbon::parse($sale->created_at);
                    $updated_at = $updated_at->subHours(3);
                }

                $difference = $updated_at->diffForHumans($current_time);
                $sale['diff_last_day'] = $difference;

            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getApprovedSales($user_id)
    {
        try {
            $admin = Admin::query()->where('id', $user_id)->first();

            $sales = Sale::query()
                ->leftJoin('contacts', 'contacts.id', '=', 'sales.owner_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name, contacts.short_code as owner_short_code')
                ->where('sales.active',1)
//                ->where('statuses.period','approved')
                ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                ->get();

            foreach ($sales as $sale) {

                $status_role = AdminStatusRole::query()->where('admin_role_id', $admin->admin_role_id)->where('status_id', $sale->status_id)->where('active', 1)->count();
                if ($status_role > 0){
                    $sale['authorization'] = 1;
                }else{
                    $sale['authorization'] = 0;
                }

                $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale->sale_id)->get();

                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
                $sale['request'] = $offer_request;
                $sale['status'] = Status::query()->where('id', $sale->status_id)->first();
//                $sale_offer = SaleOffer::query()->where('sale_id', $sale->sale_id)->first();
//                $sale['currency'] = '';
//                if ($sale_offer){
//                    if ($sale_offer->offer_currency != '' && $sale_offer->offer_currency != null){
//                        $sale['currency'] = $sale_offer->offer_currency;
//                    }
//                }

                $current_time = Carbon::now();
                if ($sale->updated_at != null){
                    $updated_at = $sale->updated_at;
//                    $updated_at = Carbon::parse($sale->updated_at);
//                    $updated_at = $updated_at->subHours(3);
                }else{
                    $updated_at = $sale->created_at;
                    $updated_at = Carbon::parse($sale->created_at);
                    $updated_at = $updated_at->subHours(3);
                }

                $difference = $updated_at->diffForHumans($current_time);
                $sale['diff_last_day'] = $difference;

            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCancelledSales($user_id)
    {
        try {
            $admin = Admin::query()->where('id', $user_id)->first();

            $sales = Sale::query()
                ->leftJoin('contacts', 'contacts.id', '=', 'sales.owner_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name, contacts.short_code as owner_short_code')
                ->where('sales.active',1)
                ->where('statuses.id',23)
                ->get();

            foreach ($sales as $sale) {

                $status_role = AdminStatusRole::query()->where('admin_role_id', $admin->admin_role_id)->where('status_id', $sale->status_id)->where('active', 1)->count();
                if ($status_role > 0){
                    $sale['authorization'] = 1;
                }else{
                    $sale['authorization'] = 0;
                }

                $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale->sale_id)->get();


                $cancel = CancelNote::query()->where('sale_id', $sale->sale_id)->first();
                $sale['cancel_note'] = '';
                if ($cancel){
                    $sale['cancel_note'] = $cancel->note;
                }

                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
                $sale['request'] = $offer_request;
                $sale['status'] = Status::query()->where('id', $sale->status_id)->first();
//                $sale_offer = SaleOffer::query()->where('sale_id', $sale->sale_id)->first();
//                $sale['currency'] = '';
//                if ($sale_offer){
//                    if ($sale_offer->offer_currency != '' && $sale_offer->offer_currency != null){
//                        $sale['currency'] = $sale_offer->offer_currency;
//                    }
//                }

                $current_time = Carbon::now();
                if ($sale->updated_at != null){
                    $updated_at = $sale->updated_at;
//                    $updated_at = Carbon::parse($sale->updated_at);
//                    $updated_at = $updated_at->subHours(3);
                }else{
                    $updated_at = $sale->created_at;
                    $updated_at = Carbon::parse($sale->created_at);
                    $updated_at = $updated_at->subHours(3);
                }

                $difference = $updated_at->diffForHumans($current_time);
                $sale['diff_last_day'] = $difference;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSaleById($sale_id)
    {
        try {
            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale_id)->get();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
            $sale['request'] = $offer_request;

            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,".","");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

            }
            $sale['sale_offers'] = $sale_offers;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale' => $sale]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getApproveOfferBySaleId($sale_id, $user_id, $revize)
    {
        try {
            if ($revize == 0) {
                Sale::query()->where('sale_id', $sale_id)->update([
                    'status_id' => 26
                ]);

                StatusHistory::query()->insert([
                    'sale_id' => $sale_id,
                    'status_id' => 26,
                    'user_id' => $user_id,
                ]);
            }else if ($revize == 1){
                Sale::query()->where('sale_id', $sale_id)->update([
                    'status_id' => 32
                ]);

                StatusHistory::query()->insert([
                    'sale_id' => $sale_id,
                    'status_id' => 32,
                    'user_id' => $user_id,
                ]);
            }else{
                return response(['message' => __('Hatalı işlem.'), 'status' => 'query-001']);
            }



            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getRejectOfferBySaleId($sale_id, $user_id, $revize)
    {
        try {
            if ($revize == 0) {
                Sale::query()->where('sale_id', $sale_id)->update([
                    'status_id' => 27
                ]);

                StatusHistory::query()->insert([
                    'sale_id' => $sale_id,
                    'status_id' => 27,
                    'user_id' => $user_id,
                ]);
            }else if ($revize == 1){
                Sale::query()->where('sale_id', $sale_id)->update([
                    'status_id' => 33
                ]);

                StatusHistory::query()->insert([
                    'sale_id' => $sale_id,
                    'status_id' => 33,
                    'user_id' => $user_id,
                ]);
            }else{
                return response(['message' => __('Hatalı işlem.'), 'status' => 'query-001']);
            }



            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addSale(Request $request)
    {
        try {
            $request->validate([
                'request_id' => 'required',
            ]);
            $sale_id = Sale::query()->where('request_id', $request->request_id)->first()->sale_id;
            Sale::query()->where('sale_id', $sale_id)->update([
                'status_id' => 4,
                'sub_total' => null,
                'freight' => null,
                'vat' => null,
                'grand_total' => null,
                'shipping_price' => null,
                'grand_total_with_shipping' => null
            ]);

            StatusHistory::query()->insert([
                'sale_id' => $sale_id,
                'status_id' => 4,
                'user_id' => $request->user_id,
            ]);

            SaleOffer::query()->where('sale_id', $sale_id)->update([
                'active' => 0
            ]);

            foreach ($request->offers as $offer){
                $measurement = Measurement::query()->where('name_tr', $offer['measurement'])->where('active', 1)->first();
                SaleOffer::query()->insert([
                    'sale_id' => $sale_id,
                    'offer_id' => $offer['offer_id'],
                    'offer_product_id' => $offer['offer_product_id'],
                    'product_id' => $offer['product_id'],
                    'supplier_id' => $offer['supplier_id'],
                    'date_code' => "",
                    'package_type' => "",
                    'request_quantity' => $offer['request_quantity'],
                    'offer_quantity' => $offer['offer_quantity'],
                    'measurement_id' => $measurement->id,
                    'pcs_price' => $offer['pcs_price'],
                    'total_price' => $offer['total_price'],
                    'discount_rate' => $offer['discount_rate'],
                    'discounted_price' => $offer['discounted_price'],
                    'vat_rate' => $offer['vat_rate'],
                    'currency' => $offer['currency'],
                    'lead_time' => $offer['lead_time'],
                    'sale_price' => $offer['converted_price'],
                    'sale_currency' => $offer['converted_currency'],
                ]);
            }

            return response(['message' => __('Satış ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['sale_id' => $sale_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateSaleStatus(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'status_id' => 'required',
                'user_id' => 'required',
            ]);
            Sale::query()->where('sale_id', $request->sale_id)->update([
                'status_id' => $request->status_id,
            ]);

            StatusHistory::query()->insert([
                'sale_id' => $request->sale_id,
                'status_id' => $request->status_id,
                'user_id' => $request->user_id,
            ]);
            $status = Status::query()->where('id', $request->status_id)->first();

            return response(['message' => __('Durum güncelleme işlemi başarılı.'), 'status' => 'success', 'object' => ['period' => $status->period]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function addCancelSaleNote(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'user_id' => 'required',
                'note' => 'required',
            ]);

            CancelNote::query()->insert([
                'sale_id' => $request->sale_id,
                'user_id' => $request->user_id,
                'note' => $request->note,
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getSaleOfferById($offer_product_id)
    {
        try {
            $sale_offer = SaleOffer::query()->where('offer_product_id',$offer_product_id)->first();
            $sale_offer['company'] = Company::query()->where('id', $sale_offer->supplier_id)->where('active', 1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale_offer' => $sale_offer]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSaleOffersByOfferId($offer_id)
    {
        try {
            $offer = Offer::query()
                ->leftJoin('companies', 'companies.id', '=', 'offers.supplier_id')
                ->selectRaw('offers.*, companies.name as company_name')
                ->where('offers.offer_id',$offer_id)
                ->where('offers.active',1)
                ->first();

            $offer['global_id'] = Sale::query()->where('request_id', $offer->request_id)->first()->id;
            $offer['owner_id'] = Sale::query()->where('request_id', $offer->request_id)->first()->owner_id;
            $offer['product_count'] = OfferProduct::query()->where('offer_id', $offer_id)->where('active', 1)->count();
            $offer['company'] = Company::query()
                ->leftJoin('countries', 'countries.id', '=', 'companies.country_id')
                ->selectRaw('companies.*, countries.lang as country_lang')
                ->where('companies.id', $offer->supplier_id)
                ->first();


            $products = SaleOffer::query()
                ->leftJoin('offer_products', 'offer_products.id', '=', 'sale_offers.offer_product_id')
                ->selectRaw('offer_products.*')
                ->where('offer_products.offer_id', $offer->offer_id)
                ->where('offer_products.active', 1)
                ->where('sale_offers.active', 1)
                ->get();

            $offer_sub_total = 0;
            $offer_vat = 0;
            $offer_grand_total = 0;
            foreach ($products as $product){
                $offer_request_product = OfferRequestProduct::query()->where('id', $product->request_product_id)->first();
                $product_detail = Product::query()->where('id', $offer_request_product->product_id)->first();
                $product['ref_code'] = $product_detail->ref_code;
                $product['product_name'] = $product_detail->product_name;
                $vat = $product->total_price / 100 * $product->vat_rate;
                $product['vat'] = number_format($vat, 2,".","");
                $product['grand_total'] = number_format($product->total_price + $vat, 2,".","");

                $offer_sub_total += $product->total_price;
                $offer_vat += $vat;
                $offer_grand_total += $product->total_price + $vat;
                $product['measurement_name_tr'] = Measurement::query()->where('id', $product->measurement_id)->first()->name_tr;
                $product['measurement_name_en'] = Measurement::query()->where('id', $product->measurement_id)->first()->name_en;

                $product['sequence'] = $offer_request_product->sequence;
            }

            $offer['products'] = $products;
            $offer['sub_total'] = number_format($offer_sub_total, 2,".","");
            $offer['vat'] = number_format($offer_vat, 2,".","");
            $offer['grand_total'] = number_format($offer_grand_total, 2,".","");

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['offer' => $offer]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addSaleOfferPrice(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'user_id' => 'required',
            ]);
            $sale_offer_detail = SaleOffer::query()->where('id', $request->id)->first();
            $sale_id = $sale_offer_detail->sale_id;
            $offer_id = $sale_offer_detail->offer_id;
            $offer_product_id = $sale_offer_detail->offer_product_id;

            SaleOffer::query()->where('id', $request->id)->update([
                'offer_price' => $request->offer_price,
                'offer_currency' => $request->offer_currency,
                'offer_lead_time' => $request->offer_lead_time
            ]);

            $offer_check = SaleOffer::query()->where('sale_id', $sale_id)->where('active', 1)->where('offer_price', null)->count();
            if ($offer_check == 0) {
                Sale::query()->where('sale_id', $sale_id)->update([
                    'status_id' => 5
                ]);

                StatusHistory::query()->insert([
                    'sale_id' => $sale_id,
                    'status_id' => 5,
                    'user_id' => $request->user_id,
                ]);

                $sale_offers = SaleOffer::query()->where('sale_id', $sale_id)->where('active', 1)->get();
                $sub_total = 0;
                $vat = 0;
                foreach ($sale_offers as $sale_offer){
                    $sub_total += $sale_offer->offer_price;
                    $vat += $sale_offer->offer_price / 100 * $sale_offer->vat_rate;
                }

                $grand_total = $sub_total + $vat;
                Sale::query()->where('sale_id', $sale_id)->update([
                    'sub_total' => $sub_total,
                    'vat' => $vat,
                    'grand_total' => $grand_total
                ]);
            }

            return response(['message' => __('Satış fiyatı ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateSaleOfferPrice(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'user_id' => 'required',
                'offer_id' => 'required',
                'offer_product_id' => 'required',
                'price' => 'required',
                'currency' => 'required',
            ]);
            SaleOffer::query()->where('sale_id', $request->sale_id)->where('offer_id', $request->offer_id)->where('offer_product_id', $request->offer_product_id)->update([
                'offer_price' => $request->price,
                'offer_currency' => $request->currency
            ]);

            return response(['message' => __('Satış fiyatı güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getRfqDetailById($offer_id)
    {
        try {
            $rfq_detail = RfqDetails::query()->where('offer_id', $offer_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['rfq_detail' => $rfq_detail]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function updateRfqDetail(Request $request)
    {
        try {
            $request->validate([
                'offer_id' => 'required',
            ]);
            $count = RfqDetails::query()->where('offer_id', $request->offer_id)->count();
            if ($count == 0){
                RfqDetails::query()->insert([
                    'offer_id' => $request->offer_id,
                    'note' => $request->note,
                ]);
            }else {
                RfqDetails::query()->where('offer_id', $request->offer_id)->update([
                    'note' => $request->note,
                ]);
            }

            return response(['message' => __('Detay güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getQuoteBySaleId($sale_id)
    {
        try {

            $quote_count = Quote::query()->where('sale_id', $sale_id)->count();
            if ($quote_count == 0){
                $quote_id = Uuid::uuid();
                Quote::query()->insert([
                    'quote_id' => $quote_id,
                    'sale_id' => $sale_id
                ]);
            }
            $quote = Quote::query()->where('sale_id', $sale_id)->first();
            $sale = Sale::query()->where('sale_id', $sale_id)->first();
            $quote['freight'] = $sale->freight;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['quote' => $quote]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function updateQuote(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'quote_id' => 'required',
            ]);
            Quote::query()->where('id', $request->quote_id)->update([
                'payment_term' => $request->payment_term,
                'lead_time' => $request->lead_time,
                'delivery_term' => $request->delivery_term,
                'country_of_destination' => $request->country_of_destination,
                'note' => $request->note
            ]);
            $sale = Sale::query()->where('sale_id', $request->sale_id)->first();
            $grand_total = null;
            if ($request->freight == ""){
                $freight = null;
                $grand_total = $sale->sub_total + $sale->vat;
            }else{
                $freight = $request->freight;
                $grand_total = $sale->sub_total + $sale->vat + $freight;
            }

            Sale::query()->where('sale_id', $request->sale_id)->update([
                'freight' => $freight,
                'grand_total' => $grand_total
            ]);

            return response(['message' => __('Bilgi güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getPurchasingOrderDetailById($offer_id)
    {
        try {
            $purchasing_order_detail = PurchasingOrderDetails::query()->where('offer_id', $offer_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['purchasing_order_detail' => $purchasing_order_detail]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addPurchasingOrderDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'offer_id' => 'required',
                'note' => 'required',
            ]);
            PurchasingOrderDetails::query()->insert([
                'sale_id' => $request->sale_id,
                'offer_id' => $request->offer_id,
                'note' => $request->note
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updatePurchasingOrderDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'offer_id' => 'required',
                'note' => 'required',
            ]);
            PurchasingOrderDetails::query()->where('sale_id', $request->sale_id)->where('offer_id', $request->offer_id)->update([
                'note' => $request->note
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getOrderConfirmationDetailById($sale_id)
    {
        try {
            $order_confirmation_detail = OrderConfirmationDetail::query()->where('sale_id', $sale_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['order_confirmation_detail' => $order_confirmation_detail]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addOrderConfirmationDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'note' => 'required',
            ]);
            OrderConfirmationDetail::query()->insert([
                'sale_id' => $request->sale_id,
                'note' => $request->note
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateOrderConfirmationDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'note' => 'required',
            ]);
            OrderConfirmationDetail::query()->where('sale_id', $request->sale_id)->update([
                'note' => $request->note
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function getProformaInvoiceDetailById($sale_id)
    {
        try {
            $proforma_invoice_detail = ProformaInvoiceDetails::query()->where('sale_id', $sale_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['proforma_invoice_detail' => $proforma_invoice_detail]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function updateProformaInvoiceDetail(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
            ]);
            $count = ProformaInvoiceDetails::query()->where('sale_id', $request->sale_id)->count();
            if ($count == 0){
                ProformaInvoiceDetails::query()->insert([
                    'sale_id' => $request->sale_id,
                    'note' => $request->note,
                    'payment_term' => $request->payment_term,
                ]);
            }else {
                ProformaInvoiceDetails::query()->where('sale_id', $request->sale_id)->update([
                    'note' => $request->note,
                    'payment_term' => $request->payment_term,
                ]);
            }

            return response(['message' => __('Detay güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateShippingPrice(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
            ]);
            $sale = Sale::query()->where('sale_id', $request->sale_id)->first();
            $grand_total_with_shipping = $sale->grand_total + $request->shipping_price;
            Sale::query()->where('sale_id', $request->sale_id)->update([
                'shipping_price' => $request->shipping_price,
                'grand_total_with_shipping' => $grand_total_with_shipping,
            ]);

            return response(['message' => __('Detay güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }



    public function getSaleDetailInfo($sale_id)
    {
        try {
            $sale = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
            $sale['request'] = $offer_request;

            $sale['customer'] = Company::query()->where('id', $sale->customer_id)->first();
            $sale['product_count'] = SaleOffer::query()->where('sale_id', $sale_id)->where('active', 1)->count();
            $sale['total_product_count'] = SaleOffer::query()->where('sale_id', $sale_id)->where('active', 1)->sum('offer_quantity');

            $total_price = $sale->grand_total;
            if ($sale->grand_total_with_shipping != null){
                $total_price = $sale->grand_total_with_shipping;
            }
            $payed_price = 0;
            $transaction = SaleTransaction::query()->where('sale_id', $sale_id)->first();

            if ($transaction) {
                $payments = SaleTransactionPayment::query()
                    ->where('sale_transaction_payments.transaction_id', $transaction->transaction_id)
                    ->where('sale_transaction_payments.active', 1)
                    ->get();

                foreach ($payments as $payment){
                    $payed_price += $payment->payment_price;
                }
            }

            $remaining_price = $total_price - $payed_price;

            $sale['total_price'] = $total_price;
            $sale['payed_price'] = number_format($payed_price, 2, ".", "");
            $sale['remaining_price'] = number_format($remaining_price, 2, ".", "");

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale' => $sale]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getSaleStatusHistory($sale_id)
    {
        try {
            $actions = StatusHistory::query()
                ->selectRaw('sale_id, id')
                ->orderByDesc('id')
                ->where('sale_id', $sale_id)
                ->get();

            $previous_status = 0;
            foreach ($actions as $action){
                $last_status = StatusHistory::query()->where('id', $action->id)->first();
                $last_status['status_name'] = Status::query()->where('id', $last_status->status_id)->first()->name;
                $admin = Admin::query()->where('id', $last_status->user_id)->first();
                $last_status['user_name'] = $admin->name." ".$admin->surname;
                $sale = Sale::query()->where('sale_id', $action->sale_id)->first();
                $customer = Company::query()->where('id', $sale->customer_id)->first();
                $sale['customer_name'] = $customer->name;
                $previous_status = StatusHistory::query()->where('id', '<' ,$action->id)->where('sale_id', $action->sale_id)->orderByDesc('id')->first();
                if (!empty($previous_status)) {
                    $previous_status['status_name'] = Status::query()->where('id', $previous_status->status_id)->first()->name;
                    $action['previous_status'] = $previous_status;
                }else{
                    $previous_status['status_name'] = "-";
                    $action['previous_status'] = 0;
                }

                $action['last_status'] = $last_status;
                $action['sale'] = $sale;

            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['actions' => $actions]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getSaleSuppliers($sale_id)
    {
        try {
            $offers = SaleOffer::query()
                ->selectRaw('sale_offers.supplier_id, SUM(total_price) as total_price, COUNT(supplier_id) as product_count')
                ->groupBy('sale_offers.supplier_id')
                ->orderBy('sale_offers.supplier_id')
                ->where('sale_id', $sale_id)
                ->get();

            foreach ($offers as $offer){
                $supplier = Company::query()->where('id', $offer->supplier_id)->first();
                $offer['supplier'] = $supplier;
                $currency = SaleOffer::query()->where('sale_id', $sale_id)->where('supplier_id', $offer->supplier_id)->first()->currency;
                $offer['currency'] = $currency;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['offers' => $offers]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }


    public function removeCancelledSales()
    {
        try {
            $sales = Sale::query()->where('status_id', 25)->get();

            foreach ($sales as $sale){

                CancelNote::query()->where('sale_id', $sale->sale_id)->delete();
                OrderConfirmationDetail::query()->where('sale_id', $sale->sale_id)->delete();
                PurchasingOrderDetails::query()->where('sale_id', $sale->sale_id)->delete();

                $offer = Offer::query()->where('request_id', $sale->request_id)->first();
                OfferProduct::query()->where('offer_id', $offer->offer_id)->delete();
                RfqDetails::query()->where('offer_id', $offer->offer_id)->delete();
                Offer::query()->where('request_id', $sale->request_id)->delete();

                OfferRequestProduct::query()->where('request_id', $sale->request_id)->delete();
                OfferRequest::query()->where('request_id', $sale->request_id)->delete();

                SaleOffer::query()->where('sale_id', $sale->sale_id)->delete();
                Sale::query()->where('id', $sale->id)->delete();

            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }





    public function getSaleNotes($sale_id)
    {
        try {
            $sale_notes = SaleNote::query()->where('sale_id', $sale_id)->get();
            foreach ($sale_notes as $sale_note){
                $user = Admin::query()->where('id', $sale_note->user_id)->first();
                $sale_note['user_name'] = $user->name.' '.$user->surname;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale_notes' => $sale_notes]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function addSaleNote(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
                'user_id' => 'required',
                'note' => 'required',
            ]);

            SaleNote::query()->insert([
                'sale_id' => $request->sale_id,
                'user_id' => $request->user_id,
                'note' => $request->note,
            ]);

            return response(['message' => __('Not ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }



    public function getLastCurrencyLog()
    {
        try {
            $currency_log = CurrencyLog::query()->orderByDesc('id')->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['currency_log' => $currency_log]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getLiveCurrencyLog()
    {
        try {

            $xml = null;
            $url = 'https://www.tcmb.gov.tr/kurlar/today.xml';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $data = curl_exec($ch);

            if ($data !== false) {
                $xml = simplexml_load_string($data);
            }

            curl_close($ch);

            if(empty($xml)){
                throw new \Exception('currency-001');
            }

            $eur_rate = $xml->Currency[3]->ForexSelling;
            $usd_rate = $xml->Currency[0]->ForexSelling;
            $gbp_rate = $xml->Currency[4]->ForexSelling;

            CurrencyLog::query()->insert([
                'usd' => $usd_rate,
                'eur' => $eur_rate,
                'gbp' => $gbp_rate,
                'day' => Carbon::now()->format('Y-m-d'),
            ]);

            return response(['message' => 'İşlem başarılı.','status' => 'success']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001','error' => $queryException->getMessage()]);
        } catch (\Exception $exception){
            if ($exception->getMessage() == 'currency-001'){
                return  response(['message' => 'Anlık bir hata nedeniyle döviz kuru alınamadı.Lütfen tekrar deneyiniz.','status' => 'currency-001']);
            }
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001', 'err' => $exception->getMessage()]);
        }
    }

    public function getCheckSaleCurrencyLog($request_id)
    {
        try {
            $sale = Sale::query()->where('request_id', $request_id)->first();
            if ($sale->usd_rate != null && $sale->eur_rate != null && $sale->gbp_rate != null && $sale->currency != null){
                return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['has_currency' => true, 'sale' => $sale]]);
            }else{
                return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['has_currency' => false]]);
            }

//            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['currency_log' => $currency_log]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function addSaleCurrencyLog(Request $request, $request_id)
    {
        try {
//            $request->validate([
//                'request_id' => 'required',
//            ]);

            Sale::query()->where('request_id', $request_id)->update([
                'usd_rate' => $request->usd_rate,
                'eur_rate' => $request->eur_rate,
                'gbp_rate' => $request->gbp_rate,
                'currency' => $request->currency,
            ]);


            $offers = Offer::query()
                ->leftJoin('companies', 'companies.id', '=', 'offers.supplier_id')
                ->selectRaw('offers.*, companies.name as company_name')
                ->where('offers.request_id', $request_id)
                ->where('offers.active', 1)
                ->get();

            foreach ($offers as $offer) {
                $products = OfferProduct::query()->where('offer_id', $offer->offer_id)->where('active', 1)->get();
                foreach ($products as $product) {

                    $convertible_price = $product->total_price;
                    if ($product->discount_rate > 0){
                        $convertible_price = $product->discounted_price;
                    }

                    $supply_price = CurrencyHelper::ChangePrice($product->currency, $request->currency, $convertible_price, $request->eur_rate, $request->usd_rate, $request->gbp_rate);

                    OfferProduct::query()->where('id', $product->id)->update([
                        'converted_price' => $supply_price,
                        'converted_currency' => $request->currency
                    ]);

                }






                $offer['products'] = $products;
            }


            return response(['message' => __('Kur ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }


    public function getCurrencyLogs()
    {
        try {
            $currency_logs = CurrencyLog::query()->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['currency_logs' => $currency_logs]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addCurrencyLog(Request $request)
    {
        try {
//            $request->validate([
//                'request_id' => 'required',
//            ]);

            CurrencyLog::query()->insert([
                'usd' => $request->usd,
                'eur' => $request->eur,
                'gbp' => $request->gbp,
                'day' => Carbon::now()->format('Y-m-d'),
            ]);


            return response(['message' => __('Kur ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
    public function updateOldCurrencies()
    {
        try {
            $sales = Sale::select('status_histories.*')
                ->leftJoin('status_histories', 'sales.sale_id', '=', 'status_histories.sale_id')
                ->where('sales.active', 1)
                ->where('sales.usd_rate', '1.00')
                ->where('status_histories.status_id', 4)
                ->where('status_histories.id', function ($query) {
                    $query->select(StatusHistory::raw('MAX(id)'))
                        ->from('status_histories')
                        ->whereRaw('status_histories.sale_id = sales.sale_id')
                        ->where('status_histories.status_id', 4);
                })
                ->get();

//            $date1 = date('Ym', strtotime($sale->created_at));
//            $date2 = date('dmY', strtotime($sale->created_at));
//            $xml = null;
//            $url = 'https://www.tcmb.gov.tr/kurlar/'.$date1.'/'.$date2.'.xml';
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $data = curl_exec($ch);
//            $sale['eur_rate2'] = $url;
//            $sale['eur_rate'] = $data;
//            if ($data !== false) {
//                $xml = simplexml_load_string($data);
//            }
//
//            curl_close($ch);





            foreach ($sales as $sale){

// Assuming $sale->created_at is a valid date string in Y-m-d format (e.g., "2023-07-27")
                $createdDate = Carbon::parse($sale->created_at);

// Check if the created date falls on a weekend (Saturday or Sunday)
                $dayOfWeek = $createdDate->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

                if ($dayOfWeek === Carbon::SUNDAY || $dayOfWeek === Carbon::SATURDAY) {
                    // If it's Sunday (0) or Saturday (6), find the previous Friday
                    $previousFriday = $createdDate->copy()->previous(Carbon::FRIDAY);
                    $previousFridayDate = $previousFriday->format('Y-m-d');
                } else {
                    // If it's not a weekend, the created_at date remains unchanged
                    $previousFridayDate = $createdDate->format('Y-m-d');
                }

// Now you can use $previousFridayDate for further processing or displaying the correct date.

// Convert the modified date back to the desired format (dmY)
                $date1 = Carbon::createFromFormat('Y-m-d', $previousFridayDate)->format('Ym');
                $date2 = Carbon::createFromFormat('Y-m-d', $previousFridayDate)->format('dmY');

//                $date1 = date('Ym', strtotime($createdDate));
//                $date2 = date('dmY', strtotime($createdDate));

                $xml = null;
                $url = 'https://www.tcmb.gov.tr/kurlar/'.$date1.'/'.$date2.'.xml';

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($ch);
                $sale['eur_rate2'] = $url;
                $sale['eur_rate'] = $data;

                if ($data !== false) {
                    $xml = simplexml_load_string($data);
                }

                curl_close($ch);


//                $sale['eurBuying'] = (float) $xml->Currency[3]->ForexBuying;
//
//                if(empty($xml)){
//                    throw new \Exception('currency-001');
//                }
//
                $sale['eur_rate'] = (float) $xml->Currency[3]->ForexSelling;
                $sale['usd_rate'] = (float) $xml->Currency[0]->ForexSelling;
                $sale['gbp_rate'] = (float) $xml->Currency[4]->ForexSelling;

            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sale]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function deleteCurrencyLog($log_id)
    {
        try {
            CurrencyLog::query()->where('id', $log_id)->delete();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSaleByRequestId($request_id)
    {
        try {
            $sale = Sale::query()->where('request_id', $request_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale' => $sale]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getPackingableProductsBySaleId($sale_id)
    {
        try {
            $sale = Sale::query()
                ->selectRaw('sales.*')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->first();


            $sale['status_name'] = Status::query()->where('id', $sale->status_id)->first()->name;
            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale->sale_id)->get();

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
            $sale['request'] = $offer_request;

//            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            $sale_offers = SaleOffer::query()
                ->where('sale_id', $sale->sale_id)
                ->where('active', 1)
//                ->whereNotIn('id', function ($query) {
//                    $query->select('sale_offer_id')
//                        ->from('packing_list_products');
//                })
                ->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,".","");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

                $sale_offer['packing_count'] = PackingListProduct::query()->where('active', 1)->where('sale_offer_id', $sale_offer->id)->sum('quantity');

            }
            $sale['sale_offers'] = $sale_offers;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale' => $sale]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getPackingListsBySaleId($sale_id)
    {
        try {
            $packing_lists = PackingList::query()
                ->where('active',1)
                ->where('sale_id',$sale_id)
                ->get();

            foreach ($packing_lists as $packing_list) {
                $packing_list['count'] = PackingListProduct::query()->where('packing_list_id', $packing_list->packing_list_id)->count();
            }


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['packing_lists' => $packing_lists]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getPackingListProductsById($packing_list_id)
    {
        try {
            $packing_list = PackingList::query()->where('packing_list_id', $packing_list_id)->first();


            $sale = Sale::query()
                ->selectRaw('sales.*')
                ->where('sales.active',1)
                ->where('sales.sale_id',$packing_list->sale_id)
                ->first();


            $sale['status_name'] = Status::query()->where('id', $sale->status_id)->first()->name;
            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale->sale_id)->get();
            $sale['packing_note'] = $packing_list->note;

            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
            $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
            $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
            $sale['request'] = $offer_request;

//            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            $sale_offers = SaleOffer::query()
                ->join('packing_list_products', 'packing_list_products.sale_offer_id', '=', 'sale_offers.id')
                ->selectRaw('sale_offers.*')
                ->where('sale_offers.sale_id', $sale->sale_id)
                ->where('packing_list_products.packing_list_id', $packing_list_id)
                ->where('sale_offers.active', 1)
                ->get();
            foreach ($sale_offers as $sale_offer){
                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,".","");
                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;

                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
                $sale_offer['sequence'] = $request_product->sequence;

                $sale_offer['packing_count'] = PackingListProduct::query()
                    ->where('active', 1)
                    ->where('sale_offer_id', $sale_offer->id)
                    ->where('packing_list_id', $packing_list->packing_list_id)
                    ->first()
                    ->quantity;

            }
            $sale['sale_offers'] = $sale_offers;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale' => $sale]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addPackingList(Request $request)
    {
        try {
            $request->validate([
                'sale_id' => 'required',
            ]);

            $packing_list_id = Uuid::uuid();

            PackingList::query()->insert([
                'packing_list_id' => $packing_list_id,
                'sale_id' => $request->sale_id,
            ]);

            foreach ($request->packing_list as $offer){
                PackingListProduct::query()->insert([
                    'packing_list_id' => $packing_list_id,
                    'sale_offer_id' => $offer['sale_offer_id'],
                    'quantity' => $offer['quantity'],
                ]);
            }

            return response(['message' => __('Gönderi listesi oluşturma işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function deletePackingList($packing_list_id){
        try {

            PackingList::query()->where('packing_list_id',$packing_list_id)->update([
                'active' => 0,
            ]);

            PackingListProduct::query()->where('packing_list_id',$packing_list_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Gönderi listesi silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function updatePackingListNote(Request $request)
    {
        try {
            $request->validate([
                'packing_list_id' => 'required',
            ]);
            PackingList::query()->where('packing_list_id', $request->packing_list_id)->update([
                'note' => $request->note
            ]);

            return response(['message' => __('Detay güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

}
