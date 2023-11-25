<?php

namespace App\Helpers;

use App\Models\StaffPoint;
use App\Models\CurrencyLog;

class StaffHelper
{
    //calculate point and rate
    public static function get_point_rate($point, $rate)
    {
        if ($point != 0 && $rate != 0) {
            $point_rate = $point * $rate / 100;
        }else{
            $point_rate = 0;
        }
        return $point_rate;

    }
    //c1
    public static function get_request_and_sales_rate($request_count, $sale_count)
    {
        if ($sale_count != 0 && $request_count != 0) {
            $c2 = ($sale_count * 100 / $request_count) / 10;
        }else{
            $c2 = 0;
        }
        return (int)$c2;

    }
    //c2
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
    //c3
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
    //c4 single payment
    public static function get_sale_payment_point($advance, $expiry)
    {
        if ($advance == null && $expiry == null){
            return 0;
        }else {
            if ($expiry == 1){
                return 10;
            }else if ($advance >= 50){
                return 9;
            }else if ($advance >= 40){
                return 8;
            }else if ($advance >= 30){
                return 7;
            }else if ($advance >= 20){
                return 6;
            }else if ($expiry > 1 && $expiry <= 7){
                return 5;
            }else if ($expiry > 7 && $expiry <= 15){
                return 4;
            }else if ($expiry > 15 && $expiry <= 30){
                return 3;
            }else if ($expiry > 30 && $expiry <= 60){
                return 2;
            }else if ($expiry > 60 && $expiry <= 90){
                return 1;
            }else{
                return 0;
            }
        }
    }
    //c4 all payment average
    public static function get_sales_payment_point($total_payment_point, $total_payment_count)
    {
        if ($total_payment_point != 0 && $total_payment_count != 0) {
            $c4 = $total_payment_point / $total_payment_count;
        }else{
            $c4 = 0;
        }
        return (int)$c4;

    }
    //c5
    public static function get_manager_point($staff_id)
    {
        $staff_point = StaffPoint::query()->where('staff_id', $staff_id)->where('active', 1)->orderByDesc('id')->first();
        if ($staff_point) {
            $c5 = $staff_point->point;
        }else{
            // default değer 5 olarak tanımlıyoruz
            $c5 = 5;
        }
        return $c5;

    }
    //c6 single customer
    public static function get_sale_customer_point($count)
    {
        if ($count == 1){
            return 0;
        }else if ($count == 2){
            return 10;
        }else if ($count == 3){
            return 9;
        }else if ($count == 4){
            return 8;
        }else if ($count == 5){
            return 7;
        }else if ($count == 6){
            return 6;
        }else if ($count == 7){
            return 5;
        }else if ($count == 8){
            return 4;
        }else if ($count == 9){
            return 3;
        }else if ($count == 10){
            return 2;
        }else if ($count == 11){
            return 1;
        }else{
            return 0;
        }
    }
    //c6 all customer average
    public static function get_sales_customer_point($customer_total_point, $customer_total_count)
    {
        if ($customer_total_point != 0 && $customer_total_count != 0) {
            $c6 = $customer_total_point / $customer_total_count;
        }else{
            $c6 = 0;
        }
        return (int)$c6;

    }
    //c7 activity point
    public static function get_activity_point($count)
    {
        if ($count < 10) {
            return $count;
        }else{
            return 10;
        }
    }
    //c8 export point
    public static function get_export_sale_point($count)
    {
        if ($count < 10) {
            return $count;
        }else{
            return 10;
        }
    }
    //c9 first sale
    public static function get_first_sale_point($count)
    {
        if ($count < 10) {
            return $count;
        }else{
            return 10;
        }
    }

}
