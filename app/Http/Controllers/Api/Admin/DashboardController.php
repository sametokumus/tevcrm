<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\SaleOffer;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function getMonthlySales()
    {
        try {
            $last_months = Sale::query()
                ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month')
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->limit(12)
                ->get();
//            return $last_months;

            $sales = array();
            foreach ($last_months as $last_month){
                $try_sale = Sale::query()
                    ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, currency, SUM(grand_total) AS monthly_total')
                    ->groupByRaw('YEAR(created_at), MONTH(created_at), currency')
                    ->whereYear('created_at', $last_month->year)
                    ->whereMonth('created_at', $last_month->month)
                    ->where('currency', 'TRY')
                    ->toSql();
                return $try_sale;

                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = $try_sale->monthly_total;
                array_push($sales, $sale);
            }



//            foreach ($sales as $sale) {
//                $year = $sale->year;
//                $month = $sale->month;
//                $currency = $sale->currency;
//                $monthlyTotal = $sale->monthly_total;
//
//            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
