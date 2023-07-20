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



//            $sale['sale_notes'] = SaleNote::query()->where('sale_id', $sale_id)->get();
//
//            $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
//            $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
//            $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
//            $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
//            $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
//            $sale['request'] = $offer_request;
//
//            $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
//            foreach ($sale_offers as $sale_offer){
//                $sale_offer['supplier_name'] = Company::query()->where('id', $sale_offer->supplier_id)->first()->name;
//                $sale_offer['product_name'] = Product::query()->where('id', $sale_offer->product_id)->first()->product_name;
//                $sale_offer['product_ref_code'] = Product::query()->where('id', $sale_offer->product_id)->first()->ref_code;
//                $offer_pcs_price = $sale_offer->offer_price / $sale_offer->offer_quantity;
//                $sale_offer['offer_pcs_price'] = number_format($offer_pcs_price, 2,".","");
//                $sale_offer->offer_price = number_format($sale_offer->offer_price, 2,",",".");
//                $sale_offer->pcs_price = number_format($sale_offer->pcs_price, 2,",",".");
//                $sale_offer->total_price = number_format($sale_offer->total_price, 2,",",".");
//                $sale_offer->discounted_price = number_format($sale_offer->discounted_price, 2,",",".");
//                $sale_offer['measurement_name_tr'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_tr;
//                $sale_offer['measurement_name_en'] = Measurement::query()->where('id', $sale_offer->measurement_id)->first()->name_en;
//
//                $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->first();
//                $request_product = OfferRequestProduct::query()->where('id', $offer_product->request_product_id)->first();
//                $sale_offer['sequence'] = $request_product->sequence;
//
//            }
//            $sale['sale_offers'] = $sale_offers;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'pro' => $item]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
