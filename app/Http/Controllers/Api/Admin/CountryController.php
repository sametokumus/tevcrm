<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function getCountries(){
        try {
            $countries = Country::query()->orderBy('order')->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['countries' => $countries]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
