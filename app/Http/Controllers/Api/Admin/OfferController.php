<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequestProduct;
use App\Models\Product;
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
                'request_id' => 'required',
                'supplier_id' => 'required',
            ]);
            $offer_id = Uuid::uuid();
            Offer::query()->insertGetId([
                'request_id' => $request->request_id,
                'offer_id' => $offer_id,
                'supplier_id' => $request->supplier_id
            ]);

            foreach ($request->products as $product){
                $has_product = OfferProduct::query()->where('request_product_id', $product['request_product_id'])->where('active', 1)->first();
                if (!$has_product) {
                    $request_product = OfferRequestProduct::query()->where('id', $product['request_product_id'])->first();
                    OfferProduct::query()->insert([
                        'offer_id' => $offer_id,
                        'request_product_id' => $product['request_product_id'],
                        'quantity' => $request_product['quantity']
                    ]);
                }
            }

            return response(['message' => __('Teklif ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
}
