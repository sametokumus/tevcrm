<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function getCitiesByStateId($state_id)
    {
        try {
            $cities = City::query()->where('state_id',$state_id)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['cities' => $cities]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
