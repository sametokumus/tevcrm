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
use App\Models\OfferProduct;
use App\Models\OfferRequestProduct;
use App\Models\Product;
use App\Models\Sale;
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
                ->where('offers.offer_id',$offer_id)
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
}
