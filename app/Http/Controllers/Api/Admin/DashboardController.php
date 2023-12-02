<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Expense;
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
    public function getMonthlyApprovedSalesLastTwelveMonths($owner_id)
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

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'approved')
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month);

                if ($owner_id != 0){
                    $sale_items = $sale_items
                        ->where('s.owner_id', $owner_id);
                }

                $sale_items = $sale_items
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


                $sale['try_sale'] = number_format($try_price, 2,".","");
                $sale['usd_sale'] = number_format($usd_price, 2,".","");
                $sale['eur_sale'] = number_format($eur_price, 2,".","");
                array_push($sales, $sale);
            }

            $total_sales['try_total'] = number_format($try_total, 2,".","");
            $total_sales['usd_total'] = number_format($usd_total, 2,".","");
            $total_sales['eur_total'] = number_format($eur_total, 2,".","");


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales, 'total_sales' => $total_sales, 'l'=>$last_months]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getMonthlyApprovedSalesLastTwelveMonthsByAdmin($admin_id)
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

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'approved')
                    ->where('offer_requests.authorized_personnel_id', '=', $admin_id)
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month)
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


                $sale['try_sale'] = number_format($try_price, 2,".","");
                $sale['usd_sale'] = number_format($usd_price, 2,".","");
                $sale['eur_sale'] = number_format($eur_price, 2,".","");
                array_push($sales, $sale);
            }

            $total_sales['try_total'] = number_format($try_total, 2,".","");
            $total_sales['usd_total'] = number_format($usd_total, 2,".","");
            $total_sales['eur_total'] = number_format($eur_total, 2,".","");


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales, 'total_sales' => $total_sales, 'l'=>$last_months]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getMonthlyCompletedSalesLastTwelveMonths($owner_id)
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

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                        ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 24)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'completed')
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month);

                if ($owner_id != 0){
                    $sale_items = $sale_items
                        ->where('s.owner_id', $owner_id);
                }

                $sale_items = $sale_items
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
    public function getMonthlyCompletedSalesLastTwelveMonthsByAdmin($admin_id)
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

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                        ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 24)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'completed')
                    ->where('offer_requests.authorized_personnel_id', '=', $admin_id)
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month)
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
    public function getMonthlyPotentialSalesLastTwelveMonths($owner_id)
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

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                        ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 5)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'continue')
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month);

                if ($owner_id != 0){
                    $sale_items = $sale_items
                        ->where('s.owner_id', $owner_id);
                }

                $sale_items = $sale_items
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
    public function getMonthlyPotentialSalesLastTwelveMonthsByAdmin($admin_id)
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

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                        ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 5)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'continue')
                    ->where('offer_requests.authorized_personnel_id', '=', $admin_id)
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month)
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
    public function getMonthlyCancelledSalesLastTwelveMonths($owner_id)
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

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND (status_id=23 or status_id=25 or status_id=28))'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'cancelled')
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month);

                if ($owner_id != 0){
                    $sale_items = $sale_items
                        ->where('s.owner_id', $owner_id);
                }

                $sale_items = $sale_items
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
    public function getMonthlyCancelledSalesLastTwelveMonthsByAdmin($admin_id)
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

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND (status_id=23 or status_id=25 or status_id=28))'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'cancelled')
                    ->where('offer_requests.authorized_personnel_id', '=', $admin_id)
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month)
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
    public function getTotalSales($owner_id)
    {
        try {

            $sales = array();

            $sale_items = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.period as period')
                ->where('sales.active',1)
                ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved' OR statuses.period = 'continue' OR statuses.period = 'cancelled')");

            if ($owner_id != 0){
                $sale_items = $sale_items
                    ->where('sales.owner_id', $owner_id);
            }

            $sale_items = $sale_items
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

    public function getLastMonthSales($owner_id)
    {
        try {

            $sales = array();

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

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


            $continue = array();
            $approved = array();
            $completed = array();
            $cancelled = array();


            $day_count = 0;


            $firstDayOfMonth = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();


            $continue_serie = array();
            $continue_serie_try = array();

            for ($date = $firstDayOfMonth; $date <= $lastDayOfMonth; $date->addDay()) {
                $day_count++;

                $daily_total_continue_sales = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 5)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'continue')
                    ->whereDate('sh.created_at', $date->toDateString());

                if ($owner_id != 0){
                    $daily_total_continue_sales = $daily_total_continue_sales
                        ->where('s.owner_id', $owner_id);
                }

                $daily_total_continue_sales = $daily_total_continue_sales
                    ->get();

                $daily_continue_try_price = 0;
                $daily_continue_usd_price = 0;
                $daily_continue_eur_price = 0;

                foreach ($daily_total_continue_sales as $sl){

                    if ($sl->currency == 'TRY'){
                        $daily_continue_try_price += $sl->grand_total;
                        $daily_continue_usd_price += $sl->grand_total / $sl->usd_rate;
                        $daily_continue_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $daily_continue_usd_price += $sl->grand_total;
                        $daily_continue_try_price += $sl->grand_total * $sl->usd_rate;
                        $daily_continue_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $daily_continue_eur_price += $sl->grand_total;
                        $daily_continue_try_price += $sl->grand_total * $sl->eur_rate;
                        $daily_continue_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                    if ($sl->currency == 'TRY'){
                        $continue_try_price += $sl->grand_total;
                        $continue_usd_price += $sl->grand_total / $sl->usd_rate;
                        $continue_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $continue_usd_price += $sl->grand_total;
                        $continue_try_price += $sl->grand_total * $sl->usd_rate;
                        $continue_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $continue_eur_price += $sl->grand_total;
                        $continue_try_price += $sl->grand_total * $sl->eur_rate;
                        $continue_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                }

                $continue_serie_this_day = array();
                $continue_serie_this_day['date'] = $date->toDateString();
                $continue_serie_this_day['try'] = number_format($daily_continue_try_price, 2,".","");
                $continue_serie_this_day['usd'] = number_format($daily_continue_usd_price, 2,".","");
                $continue_serie_this_day['eur'] = number_format($daily_continue_eur_price, 2,".","");

                array_push($continue_serie, $continue_serie_this_day);
                array_push($continue_serie_try, number_format($daily_continue_try_price, 2,".",""));
            }
            $continue['continue_serie'] = $continue_serie;
            $continue['continue_serie_try'] = $continue_serie_try;



            $firstDayOfMonth2 = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth2 = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();

            $approved_serie = array();
            $approved_serie_try = array();

            for ($date = $firstDayOfMonth2; $date <= $lastDayOfMonth2; $date->addDay()) {

                $daily_total_approved_sales = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'approved')
                    ->whereDate('sh.created_at', $date->toDateString());

                if ($owner_id != 0){
                    $daily_total_approved_sales = $daily_total_approved_sales
                        ->where('s.owner_id', $owner_id);
                }

                $daily_total_approved_sales = $daily_total_approved_sales
                    ->get();

                $daily_approved_try_price = 0;
                $daily_approved_usd_price = 0;
                $daily_approved_eur_price = 0;

                foreach ($daily_total_approved_sales as $sl){

                    if ($sl->currency == 'TRY'){
                        $daily_approved_try_price += $sl->grand_total;
                        $daily_approved_usd_price += $sl->grand_total / $sl->usd_rate;
                        $daily_approved_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $daily_approved_usd_price += $sl->grand_total;
                        $daily_approved_try_price += $sl->grand_total * $sl->usd_rate;
                        $daily_approved_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $daily_approved_eur_price += $sl->grand_total;
                        $daily_approved_try_price += $sl->grand_total * $sl->eur_rate;
                        $daily_approved_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                    if ($sl->currency == 'TRY'){
                        $approved_try_price += $sl->grand_total;
                        $approved_usd_price += $sl->grand_total / $sl->usd_rate;
                        $approved_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $approved_usd_price += $sl->grand_total;
                        $approved_try_price += $sl->grand_total * $sl->usd_rate;
                        $approved_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $approved_eur_price += $sl->grand_total;
                        $approved_try_price += $sl->grand_total * $sl->eur_rate;
                        $approved_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                }

                $approved_serie_this_day = array();
                $approved_serie_this_day['date'] = $date->toDateString();
                $approved_serie_this_day['try'] = number_format($daily_approved_try_price, 2,".","");
                $approved_serie_this_day['usd'] = number_format($daily_approved_usd_price, 2,".","");
                $approved_serie_this_day['eur'] = number_format($daily_approved_eur_price, 2,".","");

                array_push($approved_serie, $approved_serie_this_day);
                array_push($approved_serie_try, number_format($daily_approved_try_price, 2,".",""));
            }
            $approved['approved_serie'] = $approved_serie;
            $approved['approved_serie_try'] = $approved_serie_try;



            $firstDayOfMonth3 = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth3 = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();

            $completed_serie = array();
            $completed_serie_try = array();

            for ($date = $firstDayOfMonth3; $date <= $lastDayOfMonth3; $date->addDay()) {

                $daily_total_completed_sales = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 24)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'completed')
                    ->whereDate('sh.created_at', $date->toDateString());

                if ($owner_id != 0){
                    $daily_total_completed_sales = $daily_total_completed_sales
                        ->where('s.owner_id', $owner_id);
                }

                $daily_total_completed_sales = $daily_total_completed_sales
                    ->get();

                $daily_completed_try_price = 0;
                $daily_completed_usd_price = 0;
                $daily_completed_eur_price = 0;

                foreach ($daily_total_completed_sales as $sl){

                    if ($sl->currency == 'TRY'){
                        $daily_completed_try_price += $sl->grand_total;
                        $daily_completed_usd_price += $sl->grand_total / $sl->usd_rate;
                        $daily_completed_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $daily_completed_usd_price += $sl->grand_total;
                        $daily_completed_try_price += $sl->grand_total * $sl->usd_rate;
                        $daily_completed_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $daily_completed_eur_price += $sl->grand_total;
                        $daily_completed_try_price += $sl->grand_total * $sl->eur_rate;
                        $daily_completed_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                    if ($sl->currency == 'TRY'){
                        $completed_try_price += $sl->grand_total;
                        $completed_usd_price += $sl->grand_total / $sl->usd_rate;
                        $completed_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $completed_usd_price += $sl->grand_total;
                        $completed_try_price += $sl->grand_total * $sl->usd_rate;
                        $completed_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $completed_eur_price += $sl->grand_total;
                        $completed_try_price += $sl->grand_total * $sl->eur_rate;
                        $completed_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                }

                $completed_serie_this_day = array();
                $completed_serie_this_day['date'] = $date->toDateString();
                $completed_serie_this_day['try'] = number_format($daily_completed_try_price, 2,".","");
                $completed_serie_this_day['usd'] = number_format($daily_completed_usd_price, 2,".","");
                $completed_serie_this_day['eur'] = number_format($daily_completed_eur_price, 2,".","");

                array_push($completed_serie, $completed_serie_this_day);
                array_push($completed_serie_try, number_format($daily_completed_try_price, 2,".",""));
            }
            $completed['completed_serie'] = $completed_serie;
            $completed['completed_serie_try'] = $completed_serie_try;




            $firstDayOfMonth4 = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth4 = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();

            $cancelled_serie = array();
            $cancelled_serie_try = array();

            for ($date = $firstDayOfMonth4; $date <= $lastDayOfMonth4; $date->addDay()) {

                $daily_total_cancelled_sales = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND (status_id=23 OR status_id=25 OR status_id=28))'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'cancelled')
                    ->whereDate('sh.created_at', $date->toDateString());

                if ($owner_id != 0){
                    $daily_total_cancelled_sales = $daily_total_cancelled_sales
                        ->where('s.owner_id', $owner_id);
                }

                $daily_total_cancelled_sales = $daily_total_cancelled_sales
                    ->get();

                $daily_cancelled_try_price = 0;
                $daily_cancelled_usd_price = 0;
                $daily_cancelled_eur_price = 0;

                foreach ($daily_total_cancelled_sales as $sl){

                    if ($sl->currency == 'TRY'){
                        $daily_cancelled_try_price += $sl->grand_total;
                        $daily_cancelled_usd_price += $sl->grand_total / $sl->usd_rate;
                        $daily_cancelled_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $daily_cancelled_usd_price += $sl->grand_total;
                        $daily_cancelled_try_price += $sl->grand_total * $sl->usd_rate;
                        $daily_cancelled_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $daily_cancelled_eur_price += $sl->grand_total;
                        $daily_cancelled_try_price += $sl->grand_total * $sl->eur_rate;
                        $daily_cancelled_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                    if ($sl->currency == 'TRY'){
                        $cancelled_try_price += $sl->grand_total;
                        $cancelled_usd_price += $sl->grand_total / $sl->usd_rate;
                        $cancelled_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $cancelled_usd_price += $sl->grand_total;
                        $cancelled_try_price += $sl->grand_total * $sl->usd_rate;
                        $cancelled_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $cancelled_eur_price += $sl->grand_total;
                        $cancelled_try_price += $sl->grand_total * $sl->eur_rate;
                        $cancelled_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                }

                $cancelled_serie_this_day = array();
                $cancelled_serie_this_day['date'] = $date->toDateString();
                $cancelled_serie_this_day['try'] = number_format($daily_cancelled_try_price, 2,".","");
                $cancelled_serie_this_day['usd'] = number_format($daily_cancelled_usd_price, 2,".","");
                $cancelled_serie_this_day['eur'] = number_format($daily_cancelled_eur_price, 2,".","");

                array_push($cancelled_serie, $cancelled_serie_this_day);
                array_push($cancelled_serie_try, number_format($daily_cancelled_try_price, 2,".",""));
            }
            $cancelled['cancelled_serie'] = $cancelled_serie;
            $cancelled['cancelled_serie_try'] = $cancelled_serie_try;





            $continue['try_sale'] = number_format($continue_try_price, 2,".","");
            $continue['usd_sale'] = number_format($continue_usd_price, 2,".","");
            $continue['eur_sale'] = number_format($continue_eur_price, 2,".","");

            $approved['try_sale'] = number_format($approved_try_price, 2,".","");
            $approved['usd_sale'] = number_format($approved_usd_price, 2,".","");
            $approved['eur_sale'] = number_format($approved_eur_price, 2,".","");

            $completed['try_sale'] = number_format($completed_try_price, 2,".","");
            $completed['usd_sale'] = number_format($completed_usd_price, 2,".","");
            $completed['eur_sale'] = number_format($completed_eur_price, 2,".","");

            $cancelled['try_sale'] = number_format($cancelled_try_price, 2,".","");
            $cancelled['usd_sale'] = number_format($cancelled_usd_price, 2,".","");
            $cancelled['eur_sale'] = number_format($cancelled_eur_price, 2,".","");


            $sales['continue'] = $continue;
            $sales['approved'] = $approved;
            $sales['completed'] = $completed;
            $sales['cancelled'] = $cancelled;
            $sales['day_count'] = $day_count;


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getLastMonthSalesByAdmin($admin_id)
    {
        try {

            $sales = array();

            $currentMonth = Carbon::now()->month;
            $currentYear = Carbon::now()->year;

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


            $continue = array();
            $approved = array();
            $completed = array();
            $cancelled = array();


            $day_count = 0;


            $firstDayOfMonth = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();


            $continue_serie = array();
            $continue_serie_try = array();

            for ($date = $firstDayOfMonth; $date <= $lastDayOfMonth; $date->addDay()) {
                $day_count++;

                $daily_total_continue_sales = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 5)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'continue')
                    ->where('offer_requests.authorized_personnel_id', '=', $admin_id)
                    ->whereDate('sh.created_at', $date->toDateString())
                    ->get();

                $daily_continue_try_price = 0;
                $daily_continue_usd_price = 0;
                $daily_continue_eur_price = 0;

                foreach ($daily_total_continue_sales as $sl){

                    if ($sl->currency == 'TRY'){
                        $daily_continue_try_price += $sl->grand_total;
                        $daily_continue_usd_price += $sl->grand_total / $sl->usd_rate;
                        $daily_continue_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $daily_continue_usd_price += $sl->grand_total;
                        $daily_continue_try_price += $sl->grand_total * $sl->usd_rate;
                        $daily_continue_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $daily_continue_eur_price += $sl->grand_total;
                        $daily_continue_try_price += $sl->grand_total * $sl->eur_rate;
                        $daily_continue_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                    if ($sl->currency == 'TRY'){
                        $continue_try_price += $sl->grand_total;
                        $continue_usd_price += $sl->grand_total / $sl->usd_rate;
                        $continue_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $continue_usd_price += $sl->grand_total;
                        $continue_try_price += $sl->grand_total * $sl->usd_rate;
                        $continue_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $continue_eur_price += $sl->grand_total;
                        $continue_try_price += $sl->grand_total * $sl->eur_rate;
                        $continue_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                }

                $continue_serie_this_day = array();
                $continue_serie_this_day['date'] = $date->toDateString();
                $continue_serie_this_day['try'] = number_format($daily_continue_try_price, 2,".","");
                $continue_serie_this_day['usd'] = number_format($daily_continue_usd_price, 2,".","");
                $continue_serie_this_day['eur'] = number_format($daily_continue_eur_price, 2,".","");

                array_push($continue_serie, $continue_serie_this_day);
                array_push($continue_serie_try, number_format($daily_continue_try_price, 2,".",""));
            }
            $continue['continue_serie'] = $continue_serie;
            $continue['continue_serie_try'] = $continue_serie_try;



            $firstDayOfMonth2 = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth2 = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();

            $approved_serie = array();
            $approved_serie_try = array();

            for ($date = $firstDayOfMonth2; $date <= $lastDayOfMonth2; $date->addDay()) {

                $daily_total_approved_sales = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'approved')
                    ->where('offer_requests.authorized_personnel_id', '=', $admin_id)
                    ->whereDate('sh.created_at', $date->toDateString())
                    ->get();

                $daily_approved_try_price = 0;
                $daily_approved_usd_price = 0;
                $daily_approved_eur_price = 0;

                foreach ($daily_total_approved_sales as $sl){

                    if ($sl->currency == 'TRY'){
                        $daily_approved_try_price += $sl->grand_total;
                        $daily_approved_usd_price += $sl->grand_total / $sl->usd_rate;
                        $daily_approved_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $daily_approved_usd_price += $sl->grand_total;
                        $daily_approved_try_price += $sl->grand_total * $sl->usd_rate;
                        $daily_approved_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $daily_approved_eur_price += $sl->grand_total;
                        $daily_approved_try_price += $sl->grand_total * $sl->eur_rate;
                        $daily_approved_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                    if ($sl->currency == 'TRY'){
                        $approved_try_price += $sl->grand_total;
                        $approved_usd_price += $sl->grand_total / $sl->usd_rate;
                        $approved_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $approved_usd_price += $sl->grand_total;
                        $approved_try_price += $sl->grand_total * $sl->usd_rate;
                        $approved_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $approved_eur_price += $sl->grand_total;
                        $approved_try_price += $sl->grand_total * $sl->eur_rate;
                        $approved_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                }

                $approved_serie_this_day = array();
                $approved_serie_this_day['date'] = $date->toDateString();
                $approved_serie_this_day['try'] = number_format($daily_approved_try_price, 2,".","");
                $approved_serie_this_day['usd'] = number_format($daily_approved_usd_price, 2,".","");
                $approved_serie_this_day['eur'] = number_format($daily_approved_eur_price, 2,".","");

                array_push($approved_serie, $approved_serie_this_day);
                array_push($approved_serie_try, number_format($daily_approved_try_price, 2,".",""));
            }
            $approved['approved_serie'] = $approved_serie;
            $approved['approved_serie_try'] = $approved_serie_try;



            $firstDayOfMonth3 = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth3 = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();

            $completed_serie = array();
            $completed_serie_try = array();

            for ($date = $firstDayOfMonth3; $date <= $lastDayOfMonth3; $date->addDay()) {

                $daily_total_completed_sales = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 24)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'completed')
                    ->where('offer_requests.authorized_personnel_id', '=', $admin_id)
                    ->whereDate('sh.created_at', $date->toDateString())
                    ->get();

                $daily_completed_try_price = 0;
                $daily_completed_usd_price = 0;
                $daily_completed_eur_price = 0;

                foreach ($daily_total_completed_sales as $sl){

                    if ($sl->currency == 'TRY'){
                        $daily_completed_try_price += $sl->grand_total;
                        $daily_completed_usd_price += $sl->grand_total / $sl->usd_rate;
                        $daily_completed_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $daily_completed_usd_price += $sl->grand_total;
                        $daily_completed_try_price += $sl->grand_total * $sl->usd_rate;
                        $daily_completed_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $daily_completed_eur_price += $sl->grand_total;
                        $daily_completed_try_price += $sl->grand_total * $sl->eur_rate;
                        $daily_completed_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                    if ($sl->currency == 'TRY'){
                        $completed_try_price += $sl->grand_total;
                        $completed_usd_price += $sl->grand_total / $sl->usd_rate;
                        $completed_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $completed_usd_price += $sl->grand_total;
                        $completed_try_price += $sl->grand_total * $sl->usd_rate;
                        $completed_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $completed_eur_price += $sl->grand_total;
                        $completed_try_price += $sl->grand_total * $sl->eur_rate;
                        $completed_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                }

                $completed_serie_this_day = array();
                $completed_serie_this_day['date'] = $date->toDateString();
                $completed_serie_this_day['try'] = number_format($daily_completed_try_price, 2,".","");
                $completed_serie_this_day['usd'] = number_format($daily_completed_usd_price, 2,".","");
                $completed_serie_this_day['eur'] = number_format($daily_completed_eur_price, 2,".","");

                array_push($completed_serie, $completed_serie_this_day);
                array_push($completed_serie_try, number_format($daily_completed_try_price, 2,".",""));
            }
            $completed['completed_serie'] = $completed_serie;
            $completed['completed_serie_try'] = $completed_serie_try;




            $firstDayOfMonth4 = Carbon::create($currentYear, $currentMonth, 1)->startOfDay();
            $lastDayOfMonth4 = Carbon::create($currentYear, $currentMonth, 1)->lastOfMonth()->endOfDay();

            $cancelled_serie = array();
            $cancelled_serie_try = array();

            for ($date = $firstDayOfMonth4; $date <= $lastDayOfMonth4; $date->addDay()) {

                $daily_total_cancelled_sales = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND (status_id=23 OR status_id=25 OR status_id=28))'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'cancelled')
                    ->where('offer_requests.authorized_personnel_id', '=', $admin_id)
                    ->whereDate('sh.created_at', $date->toDateString())
                    ->get();

                $daily_cancelled_try_price = 0;
                $daily_cancelled_usd_price = 0;
                $daily_cancelled_eur_price = 0;

                foreach ($daily_total_cancelled_sales as $sl){

                    if ($sl->currency == 'TRY'){
                        $daily_cancelled_try_price += $sl->grand_total;
                        $daily_cancelled_usd_price += $sl->grand_total / $sl->usd_rate;
                        $daily_cancelled_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $daily_cancelled_usd_price += $sl->grand_total;
                        $daily_cancelled_try_price += $sl->grand_total * $sl->usd_rate;
                        $daily_cancelled_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $daily_cancelled_eur_price += $sl->grand_total;
                        $daily_cancelled_try_price += $sl->grand_total * $sl->eur_rate;
                        $daily_cancelled_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                    if ($sl->currency == 'TRY'){
                        $cancelled_try_price += $sl->grand_total;
                        $cancelled_usd_price += $sl->grand_total / $sl->usd_rate;
                        $cancelled_eur_price += $sl->grand_total / $sl->eur_rate;
                    }else if ($sl->currency == 'USD'){
                        $cancelled_usd_price += $sl->grand_total;
                        $cancelled_try_price += $sl->grand_total * $sl->usd_rate;
                        $cancelled_eur_price += $sl->grand_total / $sl->eur_rate * $sl->usd_rate;
                    }else if ($sl->currency == 'EUR'){
                        $cancelled_eur_price += $sl->grand_total;
                        $cancelled_try_price += $sl->grand_total * $sl->eur_rate;
                        $cancelled_usd_price += $sl->grand_total / $sl->usd_rate * $sl->eur_rate;
                    }

                }

                $cancelled_serie_this_day = array();
                $cancelled_serie_this_day['date'] = $date->toDateString();
                $cancelled_serie_this_day['try'] = number_format($daily_cancelled_try_price, 2,".","");
                $cancelled_serie_this_day['usd'] = number_format($daily_cancelled_usd_price, 2,".","");
                $cancelled_serie_this_day['eur'] = number_format($daily_cancelled_eur_price, 2,".","");

                array_push($cancelled_serie, $cancelled_serie_this_day);
                array_push($cancelled_serie_try, number_format($daily_cancelled_try_price, 2,".",""));
            }
            $cancelled['cancelled_serie'] = $cancelled_serie;
            $cancelled['cancelled_serie_try'] = $cancelled_serie_try;





            $continue['try_sale'] = number_format($continue_try_price, 2,".","");
            $continue['usd_sale'] = number_format($continue_usd_price, 2,".","");
            $continue['eur_sale'] = number_format($continue_eur_price, 2,".","");

            $approved['try_sale'] = number_format($approved_try_price, 2,".","");
            $approved['usd_sale'] = number_format($approved_usd_price, 2,".","");
            $approved['eur_sale'] = number_format($approved_eur_price, 2,".","");

            $completed['try_sale'] = number_format($completed_try_price, 2,".","");
            $completed['usd_sale'] = number_format($completed_usd_price, 2,".","");
            $completed['eur_sale'] = number_format($completed_eur_price, 2,".","");

            $cancelled['try_sale'] = number_format($cancelled_try_price, 2,".","");
            $cancelled['usd_sale'] = number_format($cancelled_usd_price, 2,".","");
            $cancelled['eur_sale'] = number_format($cancelled_eur_price, 2,".","");


            $sales['continue'] = $continue;
            $sales['approved'] = $approved;
            $sales['completed'] = $completed;
            $sales['cancelled'] = $cancelled;
            $sales['day_count'] = $day_count;


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['sales' => $sales]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }


    public function getMonthlyApprovedSalesLastTwelveMonthsByAdmins($owner_id)
    {
        try {
            $last_months = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month')
                ->where('sales.active',1)
                ->whereIn('statuses.period', ['completed', 'approved'])
                ->groupByRaw('YEAR(sales.created_at), MONTH(sales.created_at)')
                ->orderByRaw('YEAR(sales.created_at) DESC, MONTH(sales.created_at) DESC')
                ->limit(12)
                ->get();

            $admins = Admin::all();

            foreach ($admins as $admin) {

                $sales = array();
                $total_sales = array();
                $try_total = 0;
                $usd_total = 0;
                $eur_total = 0;
                $sale_count = 0;

                foreach ($last_months as $last_month) {
                    $sale_items = Sale::query()
                        ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                        ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                        ->selectRaw('YEAR(sales.created_at) AS year, MONTH(sales.created_at) AS month, sales.*')
                        ->where('offer_requests.authorized_personnel_id', $admin->id)
                        ->where('sales.active', 1)
                        ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                        ->whereYear('sales.created_at', $last_month->year)
                        ->whereMonth('sales.created_at', $last_month->month);

                    if ($owner_id != 0){
                        $sale_items = $sale_items
                            ->where('sales.owner_id', $owner_id);
                    }

                    $sale_items = $sale_items
                        ->get();


                    $try_price = 0;
                    $usd_price = 0;
                    $eur_price = 0;

                    foreach ($sale_items as $item) {
                        $sale_count++;

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
                $total_sales['sale_count'] = $sale_count;


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
    public function getCustomerOrderTimes()
    {
        try {

            $has_sale_companies = Company::query()
                ->select('companies.*', DB::raw('MAX(sales.created_at) as last_sale_date'))
                ->leftJoin('sales', 'sales.customer_id', '=', 'companies.id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->where('companies.active', 1)
                ->where('sales.active', 1)
                ->groupBy('companies.id')
                ->havingRaw('MAX(sales.created_at) IS NOT NULL')
                ->havingRaw('MAX(CASE WHEN statuses.period = "completed" OR statuses.period = "approved" THEN 1 ELSE 0 END) > 0')
                ->orderBy('last_sale_date')
                ->get();


            $no_sale_companies = null;
//            $no_sale_companies = Company::query()
//                ->where('companies.active', 1)
//                ->where('sales.active', 1)
//                ->leftJoin('sales', 'companies.id', '=', 'sales.customer_id')
//                ->select('companies.*', DB::raw('MAX(sales.created_at) as last_sale_date'))
//                ->groupBy('companies.id')
//                ->havingRaw('MAX(sales.created_at) IS NULL')
//                ->get();


            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['has_sale_companies' => $has_sale_companies, 'no_sale_companies' => $no_sale_companies]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }


    public function getTotalProfitRate($owner_id)
    {
        try {

            //Karlılık
            $sale_items = Sale::query()
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('sales.*, statuses.period as period')
                ->where('sales.active',1)
                ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')");

            if ($owner_id != 0){
                $sale_items = $sale_items
                    ->where('sales.owner_id', $owner_id);
            }

            $sale_items = $sale_items
                ->get();

            $sale_total = 0;
            $offer_total = 0;
            foreach ($sale_items as $sale){
                $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
                foreach ($sale_offers as $sale_offer){
                    if ($sale->currency == 'TRY'){
                        $sale_total += $sale_offer->sale_price;
                        $offer_total += $sale_offer->offer_price;
                    }else if ($sale->currency == 'USD'){
                        $sale_total += $sale_offer->sale_price * $sale->usd_rate;
                        $offer_total += $sale_offer->offer_price * $sale->usd_rate;
                    }else if ($sale->currency == 'EUR'){
                        $sale_total += $sale_offer->sale_price * $sale->eur_rate;
                        $offer_total += $sale_offer->offer_price * $sale->eur_rate;
                    }
                }

                //ek giderler
                $expenses = Expense::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
                foreach ($expenses as $expense){
                    if ($expense->currency == $sale->currency){
                        $sale_total += $expense->price;
                    }else{
                        if ($expense->currency == 'TRY') {
                            $sc = strtolower($sale->currency);
                            $expense_price = $expense->price / $sale->{$sc.'_rate'};
                        }else{
                            if ($sale->currency == 'TRY') {
                                $ec = strtolower($expense->currency);
                                $expense_price = $expense->price * $sale->{$ec.'_rate'};
                            }else{
                                $ec = strtolower($expense->currency);
                                $sc = strtolower($sale->currency);
                                if ($sale->{$sc.'_rate'} != 0) {
                                    $expense_price = $expense->price * $sale->{$ec . '_rate'} / $sale->{$sc . '_rate'};
                                }else{
                                    $expense_price = 0;
                                }
                            }
                        }
                        $sale_total += $expense_price;
                    }
                }


            }
            $profit_rate = 100 * ($offer_total - $sale_total) / $sale_total;
            $profit_rate = number_format($profit_rate, 2,",","");

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['profit_rate' => $profit_rate]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }
    public function getMonthlyProfitRatesLastTwelveMonths($owner_id)
    {
        try {
            $last_months = Sale::query()
                ->selectRaw('YEAR(created_at) AS year, MONTH(created_at) AS month')
                ->where('sales.active',1)
                ->groupByRaw('YEAR(created_at), MONTH(created_at)')
                ->orderByRaw('YEAR(created_at) DESC, MONTH(created_at) DESC')
                ->limit(12)
                ->get();

            $profit_rates = array();
            foreach ($last_months as $last_month){

                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('statuses.period', '=', 'approved')
                    ->whereYear('sh.created_at', $last_month->year)
                    ->whereMonth('sh.created_at', $last_month->month);

                if ($owner_id != 0){
                    $sale_items = $sale_items
                        ->where('s.owner_id', $owner_id);
                }

                $sale_items = $sale_items
                    ->get();

                $month = array();
                $month['year'] = $last_month->year;
                $month['month'] = $last_month->month;

                $sale_total = 0;
                $offer_total = 0;
                foreach ($sale_items as $sale){

                    $sale_offers = SaleOffer::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
                    foreach ($sale_offers as $sale_offer){
                        if ($sale->currency == 'TRY'){
                            $sale_total += $sale_offer->sale_price;
                            $offer_total += $sale_offer->offer_price;
                        }else if ($sale->currency == 'USD'){
                            $sale_total += $sale_offer->sale_price * $sale->usd_rate;
                            $offer_total += $sale_offer->offer_price * $sale->usd_rate;
                        }else if ($sale->currency == 'EUR'){
                            $sale_total += $sale_offer->sale_price * $sale->eur_rate;
                            $offer_total += $sale_offer->offer_price * $sale->eur_rate;
                        }
                    }

                    //ek giderler
                    $expenses = Expense::query()->where('sale_id', $sale->sale_id)->where('active', 1)->get();
                    foreach ($expenses as $expense){
                        if ($expense->currency == $sale->currency){
                            $sale_total += $expense->price;
                        }else{
                            if ($expense->currency == 'TRY') {
                                $sc = strtolower($sale->currency);
                                $expense_price = $expense->price / $sale->{$sc.'_rate'};
                            }else{
                                if ($sale->currency == 'TRY') {
                                    $ec = strtolower($expense->currency);
                                    $expense_price = $expense->price * $sale->{$ec.'_rate'};
                                }else{
                                    $ec = strtolower($expense->currency);
                                    $sc = strtolower($sale->currency);
                                    if ($sale->{$sc.'_rate'} != 0) {
                                        $expense_price = $expense->price * $sale->{$ec . '_rate'} / $sale->{$sc . '_rate'};
                                    }else{
                                        $expense_price = 0;
                                    }
                                }
                            }
                            $sale_total += $expense_price;
                        }
                    }


                }

                if ($offer_total == 0 || $sale_total == 0) {
                    $profit_rate = 0;
                }else {
                    $profit_rate = 100 * ($offer_total - $sale_total) / $sale_total;
                }

                $month['offer_total'] = $offer_total;
                $month['sale_total'] = $sale_total;
                $month['profit_rate'] = number_format($profit_rate, 2,".","");
                array_push($profit_rates, $month);
            }



            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['profit_rates' => $profit_rates]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }




}
