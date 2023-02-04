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
            ]);
            SaleOffer::query()->where('sale_id', $request->sale_id)->where('offer_id', $request->offer_id)->where('offer_product_id', $request->offer_product_id)->update([
                'offer_price' => $request->price
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
                foreach ($sale_offers as $sale_offer){
                    $sub_total += $sale_offer->offer_price;
                }
                Sale::query()->where('sale_id', $request->sale_id)->update([
                    'sub_total' => $sub_total
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
            ]);
            SaleOffer::query()->where('sale_id', $request->sale_id)->where('offer_id', $request->offer_id)->where('offer_product_id', $request->offer_product_id)->update([
                'offer_price' => $request->price
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

    public function getQuoteById($sale_id)
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

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['quote' => $quote]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

}
