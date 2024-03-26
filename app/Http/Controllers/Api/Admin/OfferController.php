<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\StatusHistoryHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Measurement;
use App\Models\Offer;
use App\Models\OfferDetail;
use App\Models\OfferProduct;
use App\Models\OfferRequestProduct;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Status;
use App\Models\Test;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class OfferController extends Controller
{
    public function addOffer(Request $request)
    {
        try {
            $request->validate([
                'customer' => 'required'
            ]);
            $offer_id = Offer::query()->insertGetId([
                'customer_id' => $request->customer,
                'employee_id' => $request->employee,
                'manager_id' => $request->manager,
                'lab_manager_id' => $request->lab_manager,
                'description' => $request->description
            ]);
            StatusHistoryHelper::addStatusHistory($offer_id, 1);

            Accounting::query()->insert([
                'offer_id' => $offer_id
            ]);

            return response(['message' => __('Teklif ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['offer_id' => $offer_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
    public function updateOffer(Request $request)
    {
        try {
            $request->validate([
                'customer' => 'required'
            ]);
            Offer::query()->where('offer_id', $request->offer_id)->insertGetId([
                'customer_id' => $request->customer,
                'employee_id' => $request->employee,
                'manager_id' => $request->manager,
                'lab_manager_id' => $request->lab_manager,
                'description' => $request->description
            ]);

            return response(['message' => __('Teklif güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function deleteOffer($offer_id){
        try {

            Offer::query()->where('offer_id', $offer_id)->update([
                'active' => 0,
            ]);

            OfferDetail::query()->where('offer_id', $offer_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Teklif silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001','ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getOffers()
    {
        try {
            $offers = Offer::query()
                ->where('offers.active',1)
                ->get();

            foreach ($offers as $offer){
                if ($offer->manager_id != null){
                    $offer['manager'] = Admin::query()->where('id', $offer->manager_id)->first();
                }else{
                    $offer['manager'] = null;
                }
                if ($offer->lab_manager_id != null){
                    $offer['lab_manager'] = Admin::query()->where('id', $offer->lab_manager_id)->first();
                }else{
                    $offer['lab_manager'] = null;
                }
                $offer['customer'] = Company::query()->where('id', $offer->customer_id)->first();
                if ($offer->employee_id != null){
                    $offer['employee'] = Employee::query()->where('id', $offer->employee_id)->first();
                }else{
                    $offer['employee'] = null;
                }
                $offer['status'] = Status::query()->where('id', $offer->status_id)->first();

                $offer['global_id'] = "LB.".$offer->id;

                $accounting = Accounting::query()->where('offer_id', $offer->id)->where('active', 1)->first();
                $offer['accounting'] = $accounting;

            }

//            $offer['global_id'] = Sale::query()->where('request_id', $offer->request_id)->first()->id;
//            $offer['owner_id'] = Sale::query()->where('request_id', $offer->request_id)->first()->owner_id;
//            $offer['product_count'] = OfferProduct::query()->where('offer_id', $offer_id)->where('active', 1)->count();
//            $offer['company'] = Company::query()
//                ->leftJoin('countries', 'countries.id', '=', 'companies.country_id')
//                ->selectRaw('companies.*, countries.lang as country_lang')
//                ->where('companies.id', $offer->supplier_id)
//                ->first();
//
//            $products = OfferProduct::query()->where('offer_id', $offer->offer_id)->where('active', 1)->get();
//            $offer_sub_total = 0;
//            $offer_vat = 0;
//            $offer_grand_total = 0;
//            foreach ($products as $product){
//                $offer_request_product = OfferRequestProduct::query()->where('id', $product->request_product_id)->first();
//                $product_detail = Product::query()->where('id', $offer_request_product->product_id)->first();
//                $product['ref_code'] = $product_detail->ref_code;
//                $product['product_name'] = $product_detail->product_name;
//                $vat = $product->total_price / 100 * $product->vat_rate;
//                $product['vat'] = number_format($vat, 2,".","");
//                $product['grand_total'] = number_format($product->total_price + $vat, 2,".","");
//
//                $offer_sub_total += $product->total_price;
//                $offer_vat += $vat;
//                $offer_grand_total += $product->total_price + $vat;
//                $product['measurement_name_tr'] = Measurement::query()->where('id', $product->measurement_id)->first()->name_tr;
//                $product['measurement_name_en'] = Measurement::query()->where('id', $product->measurement_id)->first()->name_en;
//
//                $product->discount_rate = number_format($product->discount_rate, 2,",",".");
//                $product->discounted_price = number_format($product->discounted_price, 2,",",".");
//                $product->grand_total = number_format($product->grand_total, 2,",",".");
//                $product->total_price = number_format($product->total_price, 2,",",".");
//                $product->pcs_price = number_format($product->pcs_price, 2,",",".");
//                $product->vat_rate = number_format($product->vat_rate, 2,",",".");
//            }
//
//            $offer['products'] = $products;
//            $offer['sub_total'] = number_format($offer_sub_total, 2,".","");
//            $offer['vat'] = number_format($offer_vat, 2,".","");
//            $offer['grand_total'] = number_format($offer_grand_total, 2,".","");

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['offers' => $offers]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getOfferById($offer_id)
    {
        try {
            $offer = Offer::query()
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

            $products = OfferProduct::query()->where('offer_id', $offer->offer_id)->where('active', 1)->get();
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

                $product->discount_rate = number_format($product->discount_rate, 2,",",".");
                $product->discounted_price = number_format($product->discounted_price, 2,",",".");
                $product->grand_total = number_format($product->grand_total, 2,",",".");
                $product->total_price = number_format($product->total_price, 2,",",".");
                $product->pcs_price = number_format($product->pcs_price, 2,",",".");
                $product->vat_rate = number_format($product->vat_rate, 2,",",".");
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
    public function getOfferInfoById($offer_id)
    {
        try {
            $offer = Offer::query()
                ->where('offers.id',$offer_id)
                ->where('offers.active',1)
                ->first();

            $offer['manager'] = null;
            $offer['lab_manager'] = null;
            $offer['employee'] = null;

            if ($offer->manager_id != null) {
                $offer['manager'] = Admin::query()->where('id', $offer->manager_id)->first();
            }
            if ($offer->manager_id != null) {
                $offer['lab_manager'] = Admin::query()->where('id', $offer->lab_manager_id)->first();
            }
            if ($offer->employee_id != null) {
                $offer['employee'] = Employee::query()->where('id', $offer->employee_id)->first();
            }
            $offer['customer'] = Company::query()
                ->where('companies.id', $offer->customer_id)
                ->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['offer' => $offer]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getOfferTestsById($offer_id)
    {
        try {
            $offer_details = OfferDetail::query()
                ->leftJoin('categories', 'categories.id', '=', 'offer_details.category_id')
                ->selectRaw('offer_details.*, categories.name as category_name')
                ->where('offer_details.offer_id', $offer_id)
                ->where('offer_details.active',1)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['offer_details' => $offer_details]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addTestToOffer($offer_id, $test_id)
    {
        try {
            $test = Test::query()->where('id', $test_id)->first();
            OfferDetail::query()->insert([
                'offer_id' => $offer_id,
                'test_id' => $test_id,
                'category_id' => $test->category_id,
                'name' => $test->name,
                'sample_count' => $test->sample_count,
                'sample_description' => $test->sample_description,
                'total_day' => $test->total_day,
                'price' => $test->price
            ]);
            $accounting = Accounting::query()->where('offer_id', $offer_id)->where('active', 1)->first();
            $test_total_price = $accounting->test_total + $test->price;
            Accounting::query()->where('id', $accounting->id)->update([
                'test_total' => $test_total_price
            ]);

            return response(['message' => __('Teklif ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['offer_id' => $offer_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
    public function updateTestToOffer(Request $request, $offer_detail_id)
    {
        try {
            OfferDetail::query()->where('id', $offer_detail_id)->update([
                'product_name' => $request->product_name,
                'sample_count' => $request->sample_count,
                'sample_description' => $request->sample_description,
            ]);

            return response(['message' => __('Teklif güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
    public function deleteTestToOffer($offer_detail_id)
    {
        try {
            $offer_detail = OfferDetail::query()->where('id', $offer_detail_id)->first();
            $accounting = Accounting::query()->where('offer_id', $offer_detail->offer_id)->where('active', 1)->first();
            $test_total_price = $accounting->test_total - $offer_detail->price;
            Accounting::query()->where('id', $accounting->id)->update([
                'test_total' => $test_total_price
            ]);
            OfferDetail::query()->where('id', $offer_detail_id)->update([
                'active' => 0
            ]);

            return response(['message' => __('Test silme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
    public function getOfferSummaryById($offer_id)
    {
        try {
            $summary = Accounting::query()
                ->where('offer_id',$offer_id)
                ->where('active',1)
                ->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['summary' => $summary]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function updateOfferSummary(Request $request, $offer_id)
    {
        try {
            $accounting = Accounting::query()->where('offer_id', $offer_id)->where('active', 1)->first();
            $price = $accounting->test_total;

            if ($request->discount != "") {
                $discount = null;
                if ($request->discount_type == 1){
                    $discount = $price / 100 * $request->discount;
                    $price = $price - $discount;
                }else if ($request->discount_type == 2){
                    $discount = $request->discount;
                    $price = $price - $discount;
                }
                Accounting::query()->where('id', $accounting->id)->update([
                    'discount' => $discount,
                    'sub_total' => $price
                ]);
            }else{
                Accounting::query()->where('id', $accounting->id)->update([
                    'sub_total' => $price
                ]);
            }

            if ($request->vat_rate != "") {
                $vat = $price / 100 * $request->vat_rate;
                $price = $price + $vat;
                Accounting::query()->where('id', $accounting->id)->update([
                    'vat' => $vat,
                    'vat_rate' => $request->vat_rate,
                    'grand_total' => $price
                ]);
            }else{
                Accounting::query()->where('id', $accounting->id)->update([
                    'grand_total' => $price
                ]);
            }

            return response(['message' => __('Teklif muhasebe işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
}
