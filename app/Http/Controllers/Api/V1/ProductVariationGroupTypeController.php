<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductVariationGroupType;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ProductVariationGroupTypeController extends Controller
{
    public function getProductVariationGroupTypes()
    {
        try {
            $variation_group_types = ProductVariationGroupType::query()->where('active', 1)->get();
            foreach ($variation_group_types as $variation_group_type){
                $variation_group_type_name = TextContent::query()->where('id',$variation_group_type->name)->first();
                $variation_group_type['variation_group_type_name'] = $variation_group_type_name;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['variation_group_types' => $variation_group_types]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductVariationGroupTypeById($id)
    {
        try {
            $variation_group_type = ProductVariationGroupType::query()
                ->leftJoin('text_contents','text_contents.id','=','product_variation_group_types.name')
                ->where('product_variation_group_types.id', $id)
                ->where('product_variation_group_types.active', 1)
                ->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['variation_group_type' => $variation_group_type]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
