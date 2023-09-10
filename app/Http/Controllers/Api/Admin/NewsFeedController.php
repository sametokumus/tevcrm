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
use App\Models\SaleOffer;
use App\Models\Status;
use App\Models\StatusHistory;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    public function getSaleHistoryActions()
    {
        try {
            $actions = StatusHistory::query()
                ->selectRaw('sale_id, max(id) as id')
                ->groupBy('sale_id')
                ->orderByDesc('id')
                ->limit(20)
                ->get();

            foreach ($actions as $action){
                $last_status = StatusHistory::query()->where('id', $action->id)->first();
                $last_status['status_name'] = Status::query()->where('id', $last_status->status_id)->first()->name;
                $admin = User::query()->where('id', $last_status->user_id)->first();
                $last_status['user_name'] = '';
                if ($admin) {
                    if ($admin->name != null && $admin->surname != null) {
                        $last_status['user_name'] = $admin->name . " " . $admin->surname;
                    }
                }
                $sale = Sale::query()->where('sale_id', $action->sale_id)->first();
                $customer = Company::query()->where('id', $sale->customer_id)->first();
                $sale['customer_name'] = $customer->name;
                $previous_status = StatusHistory::query()->where('id', '!=' ,$action->id)->where('sale_id', $action->sale_id)->orderByDesc('id')->first();
                if (!empty($previous_status)) {
                    $previous_status['status_name'] = Status::query()->where('id', $previous_status->status_id)->first()->name;
                }else{
                    $previous_status['status_name'] = "-";
                }

                $action['last_status'] = $last_status;
                $action['previous_status'] = $previous_status;
                $action['sale'] = $sale;

            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['actions' => $actions]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getTopRequestedProducts()
    {
        try {
            $products = OfferRequestProduct::query()
                ->selectRaw('product_id, sum(quantity) as total_quantity')
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')
                ->limit(10)
                ->get();

            foreach ($products as $product){
                $product_detail = Product::query()->where('id', $product->product_id)->first();
                $product['product_detail'] = $product_detail;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getTopSaledProducts()
    {
        try {
            $products = SaleOffer::query()
                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_offers.sale_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('product_id, sum(offer_quantity) as total_quantity')
                ->where('sale_offers.active',1)
                ->where('sales.active',1)
                ->whereIn('statuses.period', ['completed', 'approved'])
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')
                ->limit(10)
                ->toSql();

//            foreach ($products as $product){
//                $product_detail = Product::query()->where('id', $product->product_id)->first();
//                $product['product_detail'] = $product_detail;
//            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSaleStats()
    {
        try {
            $stats = array();

            $stats['total_request'] = Sale::query()->get()->count();
            $stats['total_sale'] = Sale::query()->where('status_id', '>=', 7)->where('status_id', '<=', 20)->get()->count();
            $stats['active_sale'] = Sale::query()->where('status_id', '>=', 1)->where('status_id', '<=', 17)->get()->count();
            $stats['total_product'] = Product::query()->get()->count();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['stats' => $stats]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
