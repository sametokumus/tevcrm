<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\Sale;
use App\Models\SaleOffer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class DemoController extends Controller
{
    public function deleteOwnerData($owner_id){
        try {

            $sales = Sale::query()
                ->where('owner_id', '!=', 1)
                ->get();

            foreach ($sales as $sale){
                $sale_id = $sale->sale_id;
                $request_id = $sale->request_id;

                OfferRequest::query()->where('request_id', $request_id)->delete();
                OfferRequestProduct::query()->where('request_id', $request_id)->delete();

                $offers = Offer::query()->where('request_id', $request_id)->get();
                foreach ($offers as $offer){
                    OfferProduct::query()->where('offer_id', $offer->offer_id)->delete();
                }
                Offer::query()->where('request_id', $request_id)->delete();
            }

            SaleOffer::query()->where('sale_id', $sale_id)->delete();
            Sale::query()
                ->where('owner_id', '!=', 1)
                ->delete();
            return response(['message' => __('Şablon silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
