<?php

namespace App\Helpers;

use App\Models\CurrencyLog;

class CustomerHelper
{
    public static function get_request_and_sales_rate($request_count, $sale_count)
    {
        if ($sale_count != 0 && $request_count != 0) {
            $c2 = ($sale_count * 100 / $request_count) / 10;
        }else{
            $c2 = 0;
        }
        return (int)$c2;

    }
    public static function get_sales_total_rate($usd_price)
    {
        if ($usd_price == 0){
            return 0;
        }else if ($usd_price > 0 && $usd_price < 10000){
            return 1;
        }else if ($usd_price >= 10000 && $usd_price < 20000){
            return 2;
        }else if ($usd_price >= 20000 && $usd_price < 30000){
            return 3;
        }else if ($usd_price >= 30000 && $usd_price < 40000){
            return 4;
        }else if ($usd_price >= 40000 && $usd_price < 50000){
            return 5;
        }else if ($usd_price >= 50000 && $usd_price < 60000){
            return 6;
        }else if ($usd_price >= 60000 && $usd_price < 70000){
            return 7;
        }else if ($usd_price >= 70000 && $usd_price < 80000){
            return 8;
        }else if ($usd_price >= 80000 && $usd_price < 90000){
            return 9;
//        }else if ($usd_price >= 90000){
        }else{
            return 10;
        }
    }
    public static function get_sales_profit_rate($total_profit_rate, $total_item_count)
    {
        if ($total_profit_rate == 0 || $total_item_count == 0){
            return 0;
        }else {
            $rate = $total_profit_rate / $total_item_count;
        }
        if ($rate < 10){
            return 0;
        }else if ($rate >= 10 && $rate < 20){
            return 1;
        }else if ($rate >= 20 && $rate < 30){
            return 2;
        }else if ($rate >= 30 && $rate < 40){
            return 3;
        }else if ($rate >= 40 && $rate < 50){
            return 4;
        }else if ($rate >= 50 && $rate < 60){
            return 5;
        }else if ($rate >= 60 && $rate < 70){
            return 6;
        }else if ($rate >= 70 && $rate < 80){
            return 7;
        }else if ($rate >= 80 && $rate < 90){
            return 8;
        }else if ($rate >= 90 && $rate < 100){
            return 9;
//        }else if ($rate >= 100){
        }else{
            return 10;
        }
    }

}
