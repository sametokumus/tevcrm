<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Employee;
use App\Models\Measurement;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleNote;
use App\Models\SaleOffer;
use App\Models\Status;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;

class MobileController extends Controller
{
    public function getOrder($sale_id)
    {
        try {
            $sale = Sale::query()
                ->where('active',1)
                ->where('id',$sale_id)
                ->first();

            $item = array();
            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->first();
            $company = Company::query()->where('id', $offer_request->company_id)->first();
            $status = Status::query()->where('id', $sale->status_id)->first();

            $item['company_id'] = $company->id;
            $item['company'] = $company->name;
            $item['completed'] = 0;
            if ($status->mobile_id == 41){
                $item['completed'] = 1;
            }
            $item['confirmation_no'] = 'SMY-'.$sale_id;
            $item['creation_date'] = Carbon::parse($sale->created_at)->format('d.m.Y h:i:s');
            $item['currency'] = $sale->currency;
            $item['order_date'] = Carbon::parse($sale->created_at)->format('d.m.Y h:i:s');
            $item['order_id'] = $sale->id;
            $item['pieces'] = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->sum('offer_quantity');
            $item['po_no'] = $sale->id;
            $item['pr_no'] = $sale->id;
            $item['price_total'] = $sale->grand_total;
            $item['ref_no'] = $sale->id;
            $item['subject'] = "";
            $item['user_id'] = "";

            $order_items = array();
            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
            $i = 1;
            foreach ($sale_offers as $sale_offer){
                $product = Product::query()->where('id', $sale_offer->product_id)->first();
                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
                $measurement = Measurement::query()->where('id', $offer_product->measurement_id)->first();

                $order_item = array();
                $order_item['auto_date'] = Carbon::parse($sale_offer->created_at)->format('d.m.Y h:i:s');
                $order_item['delivery_day'] = $sale_offer->offer_lead_time;
                $order_item['description'] = $product->ref_code;
                $order_item['item_name'] = $product->product_name;
                $order_item['order_number'] = $i;
                $order_item['progress'] = 0;
                $order_item['state'] = $status->mobile_id;
                $order_item['total_price'] = $sale_offer->sale_price;
                $order_item['unit'] = $sale_offer->offer_quantity;
                $order_item['unit_price'] = number_format(($sale_offer->sale_price / $sale_offer->offer_quantity), 2,".","");
                $order_item['unit_type'] = $measurement->name_tr;

                array_push($order_items, $order_item);
                $i++;
            }

            $item['order_items'] = $order_items;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'pro' => $item]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
