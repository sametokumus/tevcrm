<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\DeliveryPrice;
use App\Models\RegionalDeliveryPrice;
use Illuminate\Http\Request;

class DeliveryController extends Controller
{
    public function syncCitiesToRegionalDelivery()
    {
        try {
            $cities = City::query()->get();
            foreach ($cities as $city){
                $delivery_prices = DeliveryPrice::query()->where('active', 1)->get();
                foreach ($delivery_prices as $delivery_price){
                    $check_regional_delivery = RegionalDeliveryPrice::query()->where('city_id', $city->id)->where('delivery_price_id', $delivery_price->id)->where('active', 1)->count();
                    if($check_regional_delivery == 0){
                        RegionalDeliveryPrice::query()->insert([
                            'city_id' => $city->id,
                            'delivery_price_id' => $delivery_price->id,
                            'price' => $delivery_price->price
                        ]);
                    }
                }
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function resetAllPricesToDefault()
    {
        try {
            $delivery_prices = DeliveryPrice::query()->where('active', 1)->get();
            foreach ($delivery_prices as $delivery_price){
                RegionalDeliveryPrice::query()->where('delivery_price_id', $delivery_price->id)->update([
                    'price' => $delivery_price->price
                ]);
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function resetPricesToDefaultByCityId($city_id)
    {
        try {
            $delivery_prices = DeliveryPrice::query()->where('active', 1)->get();
            foreach ($delivery_prices as $delivery_price){
                RegionalDeliveryPrice::query()->where('delivery_price_id', $delivery_price->id)->where('city_id', $city_id)->update([
                    'price' => $delivery_price->price
                ]);
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function resetPricesToDefaultByDeliveryPriceId($delivery_price_id)
    {
        try {
            $delivery_price = DeliveryPrice::query()->where('active', 1)->where('id', $delivery_price_id)->first();
            RegionalDeliveryPrice::query()->where('delivery_price_id', $delivery_price_id)->update([
                'price' => $delivery_price->price
            ]);
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
