<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductTab;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductTabController extends Controller
{
    public function getProductTab()
    {
        try {
            $product_tabs = ProductTab::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_tabs' => $product_tabs]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getProductTabById($type_id)
    {
        try {
            $product_type = ProductTab::query()->where('active',1)->where('id',$type_id)->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_type' => $product_type]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
