<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductTypeController extends Controller
{
    public function getProductTypes()
    {
        try {
            $product_types = ProductType::query()->where('active',1)->get();
            foreach ($product_types as $product_type){
                $type_name = TextContent::query()->where('id',$product_type->name)->first();
                $product_type['type_name'] = $type_name;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_type' => $product_types]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getProductTypeById($type_id)
    {
        try {
            $product_type = ProductType::query()
                ->leftJoin('text_contents','text_contents.id','=','product_types.name')
                ->where('product_types.active',1)
                ->where('product_types.id',$type_id)
                ->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_type' => $product_type]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
