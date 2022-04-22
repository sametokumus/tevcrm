<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function getBrand()
    {
        try {
            $brands = Brand::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['brands' => $brands]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
