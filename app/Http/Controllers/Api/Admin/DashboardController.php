<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\SaleOffer;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            $total_sales = array();
            $try_total = 0;
            $usd_total = 0;
            $eur_total = 0;
            foreach ($last_months as $last_month){
                $sale_items = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved' OR statuses.period = 'continue')")
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
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

                $try_total += $try_price;
                $usd_total += $usd_price;
                $eur_total += $eur_price;


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = number_format($try_price, 2,".","");
                $sale['usd_sale'] = number_format($usd_price, 2,".","");
                $sale['eur_sale'] = number_format($eur_price, 2,".","");
                array_push($sales, $sale);
            }

            $total_sales['try_total'] = number_format($try_total, 2,".","");
            $total_sales['usd_total'] = number_format($usd_total, 2,".","");
            $total_sales['eur_total'] = number_format($eur_total, 2,".","");


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales, 'total_sales' => $total_sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getMonthlyApprovedSalesLastTwelveMonths()
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
            $total_sales = array();
            $try_total = 0;
            $usd_total = 0;
            $eur_total = 0;
            foreach ($last_months as $last_month){
                $sale_items = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'approved')")
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
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

                $try_total += $try_price;
                $usd_total += $usd_price;
                $eur_total += $eur_price;


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = number_format($try_price, 2,".","");
                $sale['usd_sale'] = number_format($usd_price, 2,".","");
                $sale['eur_sale'] = number_format($eur_price, 2,".","");
                array_push($sales, $sale);
            }

            $total_sales['try_total'] = number_format($try_total, 2,".","");
            $total_sales['usd_total'] = number_format($usd_total, 2,".","");
            $total_sales['eur_total'] = number_format($eur_total, 2,".","");


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales, 'total_sales' => $total_sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getMonthlyCompletedSalesLastTwelveMonths()
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
            $total_sales = array();
            $try_total = 0;
            $usd_total = 0;
            $eur_total = 0;
            foreach ($last_months as $last_month){
                $sale_items = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'completed')")
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
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

                $try_total += $try_price;
                $usd_total += $usd_price;
                $eur_total += $eur_price;


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = number_format($try_price, 2,".","");
                $sale['usd_sale'] = number_format($usd_price, 2,".","");
                $sale['eur_sale'] = number_format($eur_price, 2,".","");
                array_push($sales, $sale);
            }

            $total_sales['try_total'] = number_format($try_total, 2,".","");
            $total_sales['usd_total'] = number_format($usd_total, 2,".","");
            $total_sales['eur_total'] = number_format($eur_total, 2,".","");


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales, 'total_sales' => $total_sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getMonthlyPotentialSalesLastTwelveMonths()
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
            $total_sales = array();
            $try_total = 0;
            $usd_total = 0;
            $eur_total = 0;
            foreach ($last_months as $last_month){
                $sale_items = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'continue')")
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
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

                $try_total += $try_price;
                $usd_total += $usd_price;
                $eur_total += $eur_price;


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = number_format($try_price, 2,".","");
                $sale['usd_sale'] = number_format($usd_price, 2,".","");
                $sale['eur_sale'] = number_format($eur_price, 2,".","");
                array_push($sales, $sale);
            }

            $total_sales['try_total'] = number_format($try_total, 2,".","");
            $total_sales['usd_total'] = number_format($usd_total, 2,".","");
            $total_sales['eur_total'] = number_format($eur_total, 2,".","");


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales, 'total_sales' => $total_sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getMonthlyCancelledSalesLastTwelveMonths()
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
            $total_sales = array();
            $try_total = 0;
            $usd_total = 0;
            $eur_total = 0;
            foreach ($last_months as $last_month){
                $sale_items = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                    ->where('sales.active',1)
                    ->whereRaw("(statuses.period = 'cancelled')")
                    ->whereYear('sales.created_at', $last_month->year)
                    ->whereMonth('sales.created_at', $last_month->month)
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

                $try_total += $try_price;
                $usd_total += $usd_price;
                $eur_total += $eur_price;


                $sale = array();
                $sale['year'] = $last_month->year;
                $sale['month'] = $last_month->month;
                $sale['try_sale'] = number_format($try_price, 2,".","");
                $sale['usd_sale'] = number_format($usd_price, 2,".","");
                $sale['eur_sale'] = number_format($eur_price, 2,".","");
                array_push($sales, $sale);
            }

            $total_sales['try_total'] = number_format($try_total, 2,".","");
            $total_sales['usd_total'] = number_format($usd_total, 2,".","");
            $total_sales['eur_total'] = number_format($eur_total, 2,".","");


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales, 'total_sales' => $total_sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getTotalSales()
    {
        try {

            $sales = array();

            $sale_items = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.period as period')
                ->where('sales.active',1)
                ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved' OR statuses.period = 'continue' OR statuses.period = 'cancelled')")
                ->get();


            $continue_try_price = 0;
            $continue_usd_price = 0;
            $continue_eur_price = 0;
            $approved_try_price = 0;
            $approved_usd_price = 0;
            $approved_eur_price = 0;
            $completed_try_price = 0;
            $completed_usd_price = 0;
            $completed_eur_price = 0;
            $cancelled_try_price = 0;
            $cancelled_usd_price = 0;
            $cancelled_eur_price = 0;

            foreach ($sale_items as $item){

                if($item->period == 'continue'){

                    if ($item->currency == 'TRY'){
                        $continue_try_price += $item->grand_total;
                        $continue_usd_price += $item->grand_total / $item->usd_rate;
                        $continue_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $continue_usd_price += $item->grand_total;
                        $continue_try_price += $item->grand_total * $item->usd_rate;
                        $continue_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $continue_eur_price += $item->grand_total;
                        $continue_try_price += $item->grand_total * $item->eur_rate;
                        $continue_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'approved'){

                    if ($item->currency == 'TRY'){
                        $approved_try_price += $item->grand_total;
                        $approved_usd_price += $item->grand_total / $item->usd_rate;
                        $approved_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $approved_usd_price += $item->grand_total;
                        $approved_try_price += $item->grand_total * $item->usd_rate;
                        $approved_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $approved_eur_price += $item->grand_total;
                        $approved_try_price += $item->grand_total * $item->eur_rate;
                        $approved_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'completed'){

                    if ($item->currency == 'TRY'){
                        $completed_try_price += $item->grand_total;
                        $completed_usd_price += $item->grand_total / $item->usd_rate;
                        $completed_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $completed_usd_price += $item->grand_total;
                        $completed_try_price += $item->grand_total * $item->usd_rate;
                        $completed_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $completed_eur_price += $item->grand_total;
                        $completed_try_price += $item->grand_total * $item->eur_rate;
                        $completed_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'cancelled'){

                    if ($item->currency == 'TRY'){
                        $cancelled_try_price += $item->grand_total;
                        $cancelled_usd_price += $item->grand_total / $item->usd_rate;
                        $cancelled_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $cancelled_usd_price += $item->grand_total;
                        $cancelled_try_price += $item->grand_total * $item->usd_rate;
                        $cancelled_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $cancelled_eur_price += $item->grand_total;
                        $cancelled_try_price += $item->grand_total * $item->eur_rate;
                        $cancelled_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }

            }

            $continue = array();
            $continue['try_sale'] = number_format($continue_try_price, 2,".","");
            $continue['usd_sale'] = number_format($continue_usd_price, 2,".","");
            $continue['eur_sale'] = number_format($continue_eur_price, 2,".","");

            $approved = array();
            $approved['try_sale'] = number_format($approved_try_price, 2,".","");
            $approved['usd_sale'] = number_format($approved_usd_price, 2,".","");
            $approved['eur_sale'] = number_format($approved_eur_price, 2,".","");

            $completed = array();
            $completed['try_sale'] = number_format($completed_try_price, 2,".","");
            $completed['usd_sale'] = number_format($completed_usd_price, 2,".","");
            $completed['eur_sale'] = number_format($completed_eur_price, 2,".","");

            $cancelled = array();
            $cancelled['try_sale'] = number_format($cancelled_try_price, 2,".","");
            $cancelled['usd_sale'] = number_format($cancelled_usd_price, 2,".","");
            $cancelled['eur_sale'] = number_format($cancelled_eur_price, 2,".","");




            $sales['continue'] = $continue;
            $sales['approved'] = $approved;
            $sales['completed'] = $completed;
            $sales['cancelled'] = $cancelled;


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getLastMonthSales()
    {
        try {

            $sales = array();

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

            $sale_items = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.period as period')
                ->where('sales.active', 1)
                ->whereIn('statuses.period', ['completed', 'approved', 'continue', 'cancelled'])
                ->whereMonth('sales.created_at', '=', $currentMonth)
                ->whereYear('sales.created_at', '=', $currentYear)
                ->get();


            $continue_try_price = 0;
            $continue_usd_price = 0;
            $continue_eur_price = 0;
            $approved_try_price = 0;
            $approved_usd_price = 0;
            $approved_eur_price = 0;
            $completed_try_price = 0;
            $completed_usd_price = 0;
            $completed_eur_price = 0;
            $cancelled_try_price = 0;
            $cancelled_usd_price = 0;
            $cancelled_eur_price = 0;
            $approved_count = 0;
            $approved_serie = array();

            foreach ($sale_items as $item){

                if($item->period == 'continue'){

                    if ($item->currency == 'TRY'){
                        $continue_try_price += $item->grand_total;
                        $continue_usd_price += $item->grand_total / $item->usd_rate;
                        $continue_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $continue_usd_price += $item->grand_total;
                        $continue_try_price += $item->grand_total * $item->usd_rate;
                        $continue_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $continue_eur_price += $item->grand_total;
                        $continue_try_price += $item->grand_total * $item->eur_rate;
                        $continue_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'approved'){
                    $approved_count++;
                    $x = array();
                    $x['price'] = $item->grand_total;
                    $x['id'] = $item->id;
                    array_push($approved_serie, $x);

                    if ($item->currency == 'TRY'){
                        $approved_try_price += $item->grand_total;
                        $approved_usd_price += $item->grand_total / $item->usd_rate;
                        $approved_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $approved_usd_price += $item->grand_total;
                        $approved_try_price += $item->grand_total * $item->usd_rate;
                        $approved_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $approved_eur_price += $item->grand_total;
                        $approved_try_price += $item->grand_total * $item->eur_rate;
                        $approved_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'completed'){

                    if ($item->currency == 'TRY'){
                        $completed_try_price += $item->grand_total;
                        $completed_usd_price += $item->grand_total / $item->usd_rate;
                        $completed_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $completed_usd_price += $item->grand_total;
                        $completed_try_price += $item->grand_total * $item->usd_rate;
                        $completed_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $completed_eur_price += $item->grand_total;
                        $completed_try_price += $item->grand_total * $item->eur_rate;
                        $completed_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'cancelled'){

                    if ($item->currency == 'TRY'){
                        $cancelled_try_price += $item->grand_total;
                        $cancelled_usd_price += $item->grand_total / $item->usd_rate;
                        $cancelled_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $cancelled_usd_price += $item->grand_total;
                        $cancelled_try_price += $item->grand_total * $item->usd_rate;
                        $cancelled_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $cancelled_eur_price += $item->grand_total;
                        $cancelled_try_price += $item->grand_total * $item->eur_rate;
                        $cancelled_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }

            }

            $continue = array();
            $continue['try_sale'] = number_format($continue_try_price, 2,".","");
            $continue['usd_sale'] = number_format($continue_usd_price, 2,".","");
            $continue['eur_sale'] = number_format($continue_eur_price, 2,".","");

            $approved = array();
            $approved['try_sale'] = number_format($approved_try_price, 2,".","");
            $approved['usd_sale'] = number_format($approved_usd_price, 2,".","");
            $approved['eur_sale'] = number_format($approved_eur_price, 2,".","");
            $approved['count'] = $approved_count;
            $approved['approved_serie'] = $approved_serie;

            $completed = array();
            $completed['try_sale'] = number_format($completed_try_price, 2,".","");
            $completed['usd_sale'] = number_format($completed_usd_price, 2,".","");
            $completed['eur_sale'] = number_format($completed_eur_price, 2,".","");

            $cancelled = array();
            $cancelled['try_sale'] = number_format($cancelled_try_price, 2,".","");
            $cancelled['usd_sale'] = number_format($cancelled_usd_price, 2,".","");
            $cancelled['eur_sale'] = number_format($cancelled_eur_price, 2,".","");



//            $dailyTotalApprovedSales = Sale::query()
//                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
//                ->selectRaw('DATE_FORMAT(sales.created_at, "%Y-%m-%d") as date, SUM(sales.total_amount) as total')
//                ->where('sales.active', 1)
//                ->whereIn('statuses.period', ['approved'])
//                ->whereMonth('sales.created_at', '=', $currentMonth)
//                ->whereYear('sales.created_at', '=', $currentYear)
//                ->groupBy(DB::raw('DATE_FORMAT(sales.created_at, "%Y-%m-%d")'))
//                ->toSql();

            $firstDayOfMonth = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();

            $dailyTotalSales = [];
            $allDays = [];

            for ($date = $firstDayOfMonth; $date <= $lastDayOfMonth; $date->addDay()) {
                $allDays[$date->toDateString()] = 0;
            }

            $salesData = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('DATE_FORMAT(sales.created_at, "%Y-%m-%d") as date, SUM(grand_total) as total')
                ->where('sales.active', 1)
                ->whereIn('statuses.period', ['approved'])
                ->whereMonth('sales.created_at', $currentMonth)
                ->whereYear('sales.created_at', $currentYear)
                ->groupBy(DB::raw('DATE_FORMAT(sales.created_at, "%Y-%m-%d")'))
                ->get();
            $approved['sales_data'] = $salesData;

            foreach ($salesData as $sale) {
                $dailyTotalSales[$sale->date] = $sale->total;
            }

            $dailyTotalApprovedSales = array_merge($allDays, $dailyTotalSales);
            $approved['daily_sales'] = $dailyTotalApprovedSales;




            $sales['continue'] = $continue;
            $sales['approved'] = $approved;
            $sales['completed'] = $completed;
            $sales['cancelled'] = $cancelled;


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }


    public function getMonthlyApprovedSalesLastTwelveMonthsByAdmins()
    {
        try {
            $last_months = Sale::query()
                ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month')
                ->where('sales.active',1)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->limit(12)
                ->get();

            $admins = Admin::all();

            foreach ($admins as $admin) {

                $sales = array();
                $total_sales = array();
                $try_total = 0;
                $usd_total = 0;
                $eur_total = 0;

                foreach ($last_months as $last_month) {
                    $sale_items = Sale::query()
                        ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                        ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                        ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                        ->where('offer_requests.authorized_personnel_id', $admin->id)
                        ->where('sales.active', 1)
                        ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                        ->whereYear('sales.created_at', $last_month->year)
                        ->whereMonth('sales.created_at', $last_month->month)
                        ->get();


                    $sale = array();
                    $sale['year'] = $last_month->year;
                    $sale['month'] = $last_month->month;
                    $try_price = 0;
                    $usd_price = 0;
                    $eur_price = 0;

                    foreach ($sale_items as $item) {

                        if ($item->currency == 'TRY') {
                            $try_price += $item->grand_total;
                            $usd_price += $item->grand_total / $item->usd_rate;
                            $eur_price += $item->grand_total / $item->eur_rate;
                        } else if ($item->currency == 'USD') {
                            $usd_price += $item->grand_total;
                            $try_price += $item->grand_total * $item->usd_rate;
                            $eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                        } else if ($item->currency == 'EUR') {
                            $eur_price += $item->grand_total;
                            $try_price += $item->grand_total * $item->eur_rate;
                            $usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                        }
                    }

                    $try_total += $try_price;
                    $usd_total += $usd_price;
                    $eur_total += $eur_price;


                    $sale = array();
                    $sale['year'] = $last_month->year;
                    $sale['month'] = $last_month->month;
                    $sale['try_sale'] = number_format($try_price, 2, ".", "");
                    $sale['usd_sale'] = number_format($usd_price, 2, ".", "");
                    $sale['eur_sale'] = number_format($eur_price, 2, ".", "");
                    array_push($sales, $sale);
                }

                $total_sales['try_total'] = number_format($try_total, 2, ".", "");
                $total_sales['usd_total'] = number_format($usd_total, 2, ".", "");
                $total_sales['eur_total'] = number_format($eur_total, 2, ".", "");


                $admin['total_sales'] = $total_sales;

            }


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['admins' => $admins]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }
    public function getMonthlyApprovedSalesLastTwelveMonthsByAdminId($admin_id)
    {
        try {
            $last_months = Sale::query()
                ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month')
                ->where('sales.active',1)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->limit(12)
                ->get();

            $admin = Admin::query()->where('id', $admin_id)->first();



                $sales = array();
                $total_sales = array();
                $try_total = 0;
                $usd_total = 0;
                $eur_total = 0;

                foreach ($last_months as $last_month) {
                    $sale_items = Sale::query()
                        ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                        ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                        ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                        ->where('offer_requests.request_id.authorized_personnel_id', $admin->id)
                        ->where('sales.active', 1)
                        ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                        ->whereYear('sales.created_at', $last_month->year)
                        ->whereMonth('sales.created_at', $last_month->month)
                        ->get();


                    $sale = array();
                    $sale['year'] = $last_month->year;
                    $sale['month'] = $last_month->month;
                    $try_price = 0;
                    $usd_price = 0;
                    $eur_price = 0;

                    foreach ($sale_items as $item) {

                        if ($item->currency == 'TRY') {
                            $try_price += $item->grand_total;
                            $usd_price += $item->grand_total / $item->usd_rate;
                            $eur_price += $item->grand_total / $item->eur_rate;
                        } else if ($item->currency == 'USD') {
                            $usd_price += $item->grand_total;
                            $try_price += $item->grand_total * $item->usd_rate;
                            $eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                        } else if ($item->currency == 'EUR') {
                            $eur_price += $item->grand_total;
                            $try_price += $item->grand_total * $item->eur_rate;
                            $usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                        }
                    }

                    $try_total += $try_price;
                    $usd_total += $usd_price;
                    $eur_total += $eur_price;


                    $sale = array();
                    $sale['year'] = $last_month->year;
                    $sale['month'] = $last_month->month;
                    $sale['try_sale'] = number_format($try_price, 2, ".", "");
                    $sale['usd_sale'] = number_format($usd_price, 2, ".", "");
                    $sale['eur_sale'] = number_format($eur_price, 2, ".", "");
                    array_push($sales, $sale);
                }

                $total_sales['try_total'] = number_format($try_total, 2, ".", "");
                $total_sales['usd_total'] = number_format($usd_total, 2, ".", "");
                $total_sales['eur_total'] = number_format($eur_total, 2, ".", "");


                $admin['total_sales'] = $total_sales;




            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['admin' => $admin]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }


    public function getMostValuableCustomers()
    {
        try {

            $companies = Company::all();

            $company_sales = array();
            foreach ($companies as $company) {


                $sale_items = Sale::query()
                    ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                    ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                    ->where('offer_requests.company_id', $company->id)
                    ->where('sales.active', 1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                    ->get();


                $data = array();
                $data['id'] = $company->id;
                $sale_total = 0;

                foreach ($sale_items as $item) {
                    $try_price = 0;

                    if ($item->currency == 'TRY') {
                        $try_price += $item->grand_total;
                    } else if ($item->currency == 'USD') {
                        $try_price += $item->grand_total * $item->usd_rate;
                    } else if ($item->currency == 'EUR') {
                        $try_price += $item->grand_total * $item->eur_rate;
                    }

                    $sale_total += $try_price;
                }

                $data['sale_price'] = number_format($sale_total, 2, ".", "");
                $data['detail'] = Company::query()->where('id', $company->id)->first();
                array_push($company_sales, $data);


            }

            $sortedCompanies = collect($company_sales)->sortByDesc('sale_price')->take(10)->values()->all();



            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['companies' => $sortedCompanies]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }
    public function getMostValuableCustomers2()
    {
        try {

            $sales = array();

            $customers = Sale::query()
                ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('SUM(grand_total) AS monthly_total')
                ->where('sales.active',1)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->limit(12)
                ->get();

            $try_sale = Sale::query()
                ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month, currency, SUM(grand_total) AS monthly_total')
                ->where('sales.active',1)
                ->groupByRaw('YEAR(created_at), MONTH(created_at), currency')
                ->whereYear('created_at', $last_month->year)
                ->whereMonth('created_at', $last_month->month)
                ->where('currency', 'TRY')
                ->first();

            $sale_items = Sale::query()
                ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.period as period')
                ->where('sales.active',1)
                ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->get();


            $continue_try_price = 0;
            $continue_usd_price = 0;
            $continue_eur_price = 0;
            $approved_try_price = 0;
            $approved_usd_price = 0;
            $approved_eur_price = 0;
            $completed_try_price = 0;
            $completed_usd_price = 0;
            $completed_eur_price = 0;
            $cancelled_try_price = 0;
            $cancelled_usd_price = 0;
            $cancelled_eur_price = 0;

            foreach ($sale_items as $item){

                if($item->period == 'continue'){

                    if ($item->currency == 'TRY'){
                        $continue_try_price += $item->grand_total;
                        $continue_usd_price += $item->grand_total / $item->usd_rate;
                        $continue_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $continue_usd_price += $item->grand_total;
                        $continue_try_price += $item->grand_total * $item->usd_rate;
                        $continue_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $continue_eur_price += $item->grand_total;
                        $continue_try_price += $item->grand_total * $item->eur_rate;
                        $continue_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'approved'){

                    if ($item->currency == 'TRY'){
                        $approved_try_price += $item->grand_total;
                        $approved_usd_price += $item->grand_total / $item->usd_rate;
                        $approved_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $approved_usd_price += $item->grand_total;
                        $approved_try_price += $item->grand_total * $item->usd_rate;
                        $approved_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $approved_eur_price += $item->grand_total;
                        $approved_try_price += $item->grand_total * $item->eur_rate;
                        $approved_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'completed'){

                    if ($item->currency == 'TRY'){
                        $completed_try_price += $item->grand_total;
                        $completed_usd_price += $item->grand_total / $item->usd_rate;
                        $completed_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $completed_usd_price += $item->grand_total;
                        $completed_try_price += $item->grand_total * $item->usd_rate;
                        $completed_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $completed_eur_price += $item->grand_total;
                        $completed_try_price += $item->grand_total * $item->eur_rate;
                        $completed_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }else if($item->period == 'cancelled'){

                    if ($item->currency == 'TRY'){
                        $cancelled_try_price += $item->grand_total;
                        $cancelled_usd_price += $item->grand_total / $item->usd_rate;
                        $cancelled_eur_price += $item->grand_total / $item->eur_rate;
                    }else if ($item->currency == 'USD'){
                        $cancelled_usd_price += $item->grand_total;
                        $cancelled_try_price += $item->grand_total * $item->usd_rate;
                        $cancelled_eur_price += $item->grand_total / $item->eur_rate * $item->usd_rate;
                    }else if ($item->currency == 'EUR'){
                        $cancelled_eur_price += $item->grand_total;
                        $cancelled_try_price += $item->grand_total * $item->eur_rate;
                        $cancelled_usd_price += $item->grand_total / $item->usd_rate * $item->eur_rate;
                    }

                }

            }

            $continue = array();
            $continue['try_sale'] = number_format($continue_try_price, 2,".","");
            $continue['usd_sale'] = number_format($continue_usd_price, 2,".","");
            $continue['eur_sale'] = number_format($continue_eur_price, 2,".","");

            $approved = array();
            $approved['try_sale'] = number_format($approved_try_price, 2,".","");
            $approved['usd_sale'] = number_format($approved_usd_price, 2,".","");
            $approved['eur_sale'] = number_format($approved_eur_price, 2,".","");

            $completed = array();
            $completed['try_sale'] = number_format($completed_try_price, 2,".","");
            $completed['usd_sale'] = number_format($completed_usd_price, 2,".","");
            $completed['eur_sale'] = number_format($completed_eur_price, 2,".","");

            $cancelled = array();
            $cancelled['try_sale'] = number_format($cancelled_try_price, 2,".","");
            $cancelled['usd_sale'] = number_format($cancelled_usd_price, 2,".","");
            $cancelled['eur_sale'] = number_format($cancelled_eur_price, 2,".","");




            $sales['continue'] = $continue;
            $sales['approved'] = $approved;
            $sales['completed'] = $completed;
            $sales['cancelled'] = $cancelled;


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
