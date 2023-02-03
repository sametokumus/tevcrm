<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Employee;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StatusHistory;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class OfferRequestController extends Controller
{
    public function getOfferRequests()
    {
        try {
            $offer_requests = OfferRequest::query()->where('active',1)->get();
            foreach ($offer_requests as $offer_request){
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['offer_requests' => $offer_requests]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getOfferRequestById($offer_request_id)
    {
        try {
            $offer_request = OfferRequest::query()->where('request_id',$offer_request_id)->where('active',1)->first();
            $offer_request_products = OfferRequestProduct::query()->where('request_id', $offer_request_id)->where('active', 1)->get();
            foreach ($offer_request_products as $offer_request_product){
                $product = Product::query()->where('id', $offer_request_product->product_id)->first();
                $offer_request_product['ref_code'] = $product->ref_code;
                $offer_request_product['product_name'] = $product->product_name;
            }
            $offer_request['products'] = $offer_request_products;
            $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
            $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
            $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['offer_request' => $offer_request]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addOfferRequest(Request $request)
    {
        try {
            $request->validate([
                'user_id' => 'required',
                'company_id' => 'required',
            ]);
            $request_id = Uuid::uuid();
            OfferRequest::query()->insertGetId([
                'request_id' => $request_id,
                'user_id' => $request->user_id,
                'authorized_personnel_id' => $request->authorized_personnel_id,
                'company_id' => $request->company_id,
                'company_employee_id' => $request->company_employee_id,
            ]);

            foreach ($request->products as $product){
                $has_product = Product::query()->where('ref_code', $product['ref_code'])->where('active', 1)->first();
                if ($has_product) {
                    $product_id = $has_product->id;
                }else{
                    $product_id = Product::query()->insertGetId([
                        'ref_code' => $product['ref_code'],
                        'product_name' => $product['product_name'],
                    ]);
                }
                OfferRequestProduct::query()->insert([
                    'request_id' => $request_id,
                    'product_id' => $product_id,
                    'quantity' => $product['quantity']
                ]);
            }

            $sale_id = Uuid::uuid();
            Sale::query()->insert([
                'sale_id' => $sale_id,
                'request_id' => $request_id,
                'customer_id' => $request->company_id,
                'status_id' => 1
            ]);
            StatusHistory::query()->insert([
                'sale_id' => $sale_id,
                'status_id' => 1,
                'user_id' => $request->user_id,
            ]);

            return response(['message' => __('Talep ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['request_id' => $request_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateOfferRequest(Request $request, $request_id)
    {
        try {
            $request->validate([
                'user_id' => 'required',
            ]);
            OfferRequest::query()->where('request_id', $request_id)->update([
                'user_id' => $request->user_id,
                'authorized_personnel_id' => $request->authorized_personnel_id,
                'company_id' => $request->company_id,
                'company_employee_id' => $request->company_employee_id,
            ]);

            return response(['message' => __('Talep güncelleme işlemi başarılı.'), 'status' => 'success', 'object' => ['request_id' => $request_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function addProductToOfferRequest(Request $request, $request_id)
    {
        try {
            $request->validate([
//                'ref_code' => 'required',
            ]);

                $has_product = Product::query()->where('ref_code', $request->ref_code)->where('active', 1)->first();
                if ($has_product) {
                    $product_id = $has_product->id;
                }else{
                    $product_id = Product::query()->insertGetId([
                        'ref_code' => $request->ref_code,
                        'product_name' => $request->product_name,
                    ]);
                }
                $rp_id = OfferRequestProduct::query()->insertGetId([
                    'request_id' => $request_id,
                    'product_id' => $product_id,
                    'quantity' => $request->quantity
                ]);

            return response(['message' => __('Talep ürün ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['product_id' => $rp_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function deleteProductToOfferRequest($request_product_id){
        try {

            OfferRequestProduct::query()->where('id',$request_product_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Talep ürün silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getOfferRequestsByCompanyId($company_id)
    {
        try {
            $offer_requests = OfferRequest::query()
                ->leftJoin('sales', 'sales.request_id', '=', 'offer_requests.request_id')
                ->selectRaw('offer_requests.*')
                ->where('sales.status_id',3)
                ->where('offer_requests.company_id',$company_id)
                ->where('offer_requests.active',1)
                ->where('sales.active',1)
                ->get();
            foreach ($offer_requests as $offer_request){
                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['offer_requests' => $offer_requests]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
