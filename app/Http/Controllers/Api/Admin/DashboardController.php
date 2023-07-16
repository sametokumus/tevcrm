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
                ->where('sales.active',1)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->limit(12)
                ->get();

            $sales = array();
            foreach ($last_months as $last_month){
                $try_sale = Sale::query()
                    ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, currency, SUM(grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->groupByRaw('YEAR(created_at), MONTH(created_at), currency')
                    ->whereYear('created_at', $last_month->year)
                    ->whereMonth('created_at', $last_month->month)
                    ->where('currency', 'TRY')
                    ->first();
                $usd_sale = Sale::query()
                    ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, currency, SUM(grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->groupByRaw('YEAR(created_at), MONTH(created_at), currency')
                    ->whereYear('created_at', $last_month->year)
                    ->whereMonth('created_at', $last_month->month)
                    ->where('currency', 'USD')
                    ->first();
                $eur_sale = Sale::query()
                    ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, currency, SUM(grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->groupByRaw('YEAR(created_at), MONTH(created_at), currency')
                    ->whereYear('created_at', $last_month->year)
                    ->whereMonth('created_at', $last_month->month)
                    ->where('currency', 'EUR')
                    ->first();
                $gbp_sale = Sale::query()
                    ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, currency, SUM(grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->groupByRaw('YEAR(created_at), MONTH(created_at), currency')
                    ->whereYear('created_at', $last_month->year)
                    ->whereMonth('created_at', $last_month->month)
                    ->where('currency', 'GBP')
                    ->first();


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = '0.00';
                $sale['usd_sale'] = '0.00';
                $sale['eur_sale'] = '0.00';
                $sale['gbp_sale'] = '0.00';
                if ($try_sale) {
                    $sale['try_sale'] = $try_sale->monthly_total;
                }
                if ($usd_sale) {
                    $sale['usd_sale'] = $usd_sale->monthly_total;
                }
                if ($eur_sale) {
                    $sale['eur_sale'] = $eur_sale->monthly_total;
                }
                if ($gbp_sale) {
                    $sale['gbp_sale'] = $gbp_sale->monthly_total;
                }
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
    public function getApprovedMonthlySales()
    {
        try {
            $last_months = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month')
                ->where('sales.active',1)
                ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at)')
                ->orderByRaw('YEAR(sales.created_at) DESC, MONTH(sales.created_at) DESC')
                ->limit(12)
                ->get();

            $sales = array();
            foreach ($last_months as $last_month){
                $try_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), sales.currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'TRY')
                    ->first();
                $usd_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'USD')
                    ->first();
                $eur_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'EUR')
                    ->first();
                $gbp_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'GBP')
                    ->first();


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = '0.00';
                $sale['usd_sale'] = '0.00';
                $sale['eur_sale'] = '0.00';
                $sale['gbp_sale'] = '0.00';
                if ($try_sale) {
                    $sale['try_sale'] = $try_sale->monthly_total;
                }
                if ($usd_sale) {
                    $sale['usd_sale'] = $usd_sale->monthly_total;
                }
                if ($eur_sale) {
                    $sale['eur_sale'] = $eur_sale->monthly_total;
                }
                if ($gbp_sale) {
                    $sale['gbp_sale'] = $gbp_sale->monthly_total;
                }
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
    public function getPotentialSales()
    {
        try {
            $last_months = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month')
                ->where('sales.active',1)
                ->whereRaw("(statuses.period = 'continue')")
                ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at)')
                ->orderByRaw('YEAR(sales.created_at) DESC, MONTH(sales.created_at) DESC')
                ->limit(12)
                ->get();

            $sales = array();
            foreach ($last_months as $last_month){
                $try_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'continue')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), sales.currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'TRY')
                    ->first();
                $usd_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'continue')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'USD')
                    ->first();
                $eur_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'continue')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'EUR')
                    ->first();
                $gbp_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'continue')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'GBP')
                    ->first();


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = '0.00';
                $sale['usd_sale'] = '0.00';
                $sale['eur_sale'] = '0.00';
                $sale['gbp_sale'] = '0.00';
                if ($try_sale) {
                    $sale['try_sale'] = $try_sale->monthly_total;
                }
                if ($usd_sale) {
                    $sale['usd_sale'] = $usd_sale->monthly_total;
                }
                if ($eur_sale) {
                    $sale['eur_sale'] = $eur_sale->monthly_total;
                }
                if ($gbp_sale) {
                    $sale['gbp_sale'] = $gbp_sale->monthly_total;
                }
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
    public function getCancelledPotentialSales()
    {
        try {
            $last_months = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month')
                ->where('sales.active',1)
                ->whereRaw("(statuses.period = 'cancelled')")
                ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at)')
                ->orderByRaw('YEAR(sales.created_at) DESC, MONTH(sales.created_at) DESC')
                ->limit(12)
                ->get();

            $sales = array();
            foreach ($last_months as $last_month){
                $try_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'cancelled')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), sales.currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'TRY')
                    ->first();
                $usd_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'cancelled')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'USD')
                    ->first();
                $eur_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'cancelled')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'EUR')
                    ->first();
                $gbp_sale = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.currency, SUM(sales.grand_total) AS monthly_total')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'cancelled')")
                    ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at), currency')
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
                    ->where('sales.currency', 'GBP')
                    ->first();


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = '0.00';
                $sale['usd_sale'] = '0.00';
                $sale['eur_sale'] = '0.00';
                $sale['gbp_sale'] = '0.00';
                if ($try_sale) {
                    $sale['try_sale'] = $try_sale->monthly_total;
                }
                if ($usd_sale) {
                    $sale['usd_sale'] = $usd_sale->monthly_total;
                }
                if ($eur_sale) {
                    $sale['eur_sale'] = $eur_sale->monthly_total;
                }
                if ($gbp_sale) {
                    $sale['gbp_sale'] = $gbp_sale->monthly_total;
                }
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


    public function getMonthlySalesLastTwelveMonths()
    {
        try {
            $last_months = Sale::query()
                ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month')
                ->where('sales.active',1)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->limit(12)
                ->get();

            $sales = array();
            foreach ($last_months as $last_month){
                $sale_items = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, sales.*')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved' OR statuses.period = 'continue')")
                    ->whereYear('created_at', $last_month->year)
                    ->whereMonth('created_at', $last_month->month)
                    ->get();


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $try_price = 0;
                $usd_price = 0;
                $eur_price = 0;

                foreach ($sale_items as $item){

                    if ($item->currency == 'TRY'){
                        $try_price += $item->grand_total;
                        $usd_price += $item->grand_total / $item->usd_rate;
                        $eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $usd_price += $item->grand_total;
                        $try_price += $item->grand_total * $item->usd_rate;
                        $eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $eur_price += $item->grand_total;
                        $try_price += $item->grand_total * $item->eur_rate;
                        $usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }
                }


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = number_format($try_price, 2,".","");
                $sale['usd_sale'] = number_format($usd_price, 2,".","");
                $sale['eur_sale'] = number_format($eur_price, 2,".","");
                array_push($sales, $sale);
            }


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
