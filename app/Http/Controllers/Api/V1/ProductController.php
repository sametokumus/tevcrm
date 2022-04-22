<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProduct()
    {
        try {
            $products = Product::query()->where('active',1)->get();
            $products_category = Product::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $products,'products_category' => $products_category]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
