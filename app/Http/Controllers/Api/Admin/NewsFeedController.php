<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Employee;
use App\Models\OfferRequest;
use App\Models\OfferRequestProduct;
use App\Models\Sale;
use App\Models\StatusHistory;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NewsFeedController extends Controller
{
    public function getSaleHistoryActions()
    {
        try {
            $actions = StatusHistory::query()
                ->select(['sale_id', 'max(id) as id'])
                ->groupBy('sale_id')
                ->orderBy('id')
                ->limit(5)
                ->get();

//            $sales = Sale::query()
//                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
//                ->selectRaw('sales.*, statuses.name as status_name')
//                ->where('sales.active',1)
//                ->get();
//
//            foreach ($sales as $sale) {
//                $offer_request = OfferRequest::query()->where('request_id', $sale->request_id)->where('active', 1)->first();
//                $offer_request['product_count'] = OfferRequestProduct::query()->where('request_id', $offer_request->request_id)->where('active', 1)->count();
//                $offer_request['authorized_personnel'] = Admin::query()->where('id', $offer_request->authorized_personnel_id)->where('active', 1)->first();
//                $offer_request['company'] = Company::query()->where('id', $offer_request->company_id)->where('active', 1)->first();
//                $offer_request['company_employee'] = Employee::query()->where('id', $offer_request->company_employee_id)->where('active', 1)->first();
//                $sale['request'] = $offer_request;
//            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['actions' => $actions]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
