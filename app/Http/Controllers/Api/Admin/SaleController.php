<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\Product;
use App\Models\PurchasingOrderDetails;
use App\Models\Quote;
use App\Models\Sale;
use App\Models\SaleOffer;
use App\Models\StatusHistory;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class SaleController extends Controller
{

    public function getSales()
    {
        try {
            $sales = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.name as status_name')
                ->where('sales.active',1)
                ->get();

            foreach ($sales as $sale) {
                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
                $sale['request'] = $offer_request;
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
            }
            $sale['sale_offers'] = $sale_offers;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sale' => $sale]]);
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
                'status_id' => 4
            ]);

            StatusHistory::query()->insert([
                'sale_id' => $sale_id,
                'status_id' => 4,
                'user_id' => $request->user_id,
            ]);

            foreach ($request->offers as $offer){
                SaleOffer::query()->insert([
                    'sale_id' => $sale_id,
                    'offer_id' => $offer['offer_id'],
                    'offer_product_id' => $offer['offer_product_id'],
                    'product_id' => $offer['product_id'],
                    'supplier_id' => $offer['supplier_id'],
                    'date_code' => $offer['date_code'],
                    'package_type' => $offer['package_type'],
                    'request_quantity' => $offer['request_quantity'],
                    'offer_quantity' => $offer['offer_quantity'],
                    'pcs_price' => $offer['pcs_price'],
                    'total_price' => $offer['total_price'],
                    'discount_rate' => $offer['discount_rate'],
                    'discounted_price' => $offer['discounted_price'],
                    'vat_rate' => $offer['vat_rate'],
                    'currency' => $offer['currency'],
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
            ]);
            Sale::query()->where('sale_id', $request->sale_id)->update([
                'status_id' => $request->status_id,
            ]);

            return response(['message' => __('Durum güncelleme işlemi başarılı.'), 'status' => 'success']);
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

    public function addSaleOfferPrice(Request $request)
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

            $offer_check = SaleOffer::query()->where('sale_id', $request->sale_id)->where('offer_price', null)->count();
            if ($offer_check == 0) {
                Sale::query()->where('sale_id', $request->sale_id)->update([
                    'status_id' => 5
                ]);

                StatusHistory::query()->insert([
                    'sale_id' => $request->sale_id,
                    'status_id' => 5,
                    'user_id' => $request->user_id,
                ]);

                $sale_offers = SaleOffer::query()->where('sale_id', $request->sale_id)->get();
                $sub_total = 0;
                $vat = 0;
                foreach ($sale_offers as $sale_offer){
                    $sub_total += $sale_offer->offer_price;
                    $vat += $sale_offer->offer_price / 100 * $sale_offer->vat_rate;
                }
                Sale::query()->where('sale_id', $request->sale_id)->update([
                    'sub_total' => $sub_total,
                    'vat' => $vat
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

}
