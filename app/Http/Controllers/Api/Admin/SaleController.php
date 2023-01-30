<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\Product;
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
                ->leftJoin('statuses', 'stasuses.id', '=', 'sales.status_id')
                ->leftJoin('companies', 'companies.id', '=', 'sales.customer_id')
                ->selectRaw('sales.*, companies.name as customer_name, statuses.name as status_name')
                ->where('sales.active',1)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSaleById($sale_id)
    {
        try {
            $sales = Sale::query()
                ->leftJoin('statuses', 'stasuses.id', '=', 'sales.status_id')
                ->leftJoin('companies', 'companies.id', '=', 'sales.customer_id')
                ->selectRaw('sales.*, companies.name as customer_name, statuses.name as status_name')
                ->where('sales.active',1)
                ->where('sales.sale_id',$sale_id)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
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

}
