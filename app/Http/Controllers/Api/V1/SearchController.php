<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductImage;
use App\Models\ProductRule;
use App\Models\ProductSeo;
use App\Models\ProductType;
use App\Models\ProductVariation;
use App\Models\ProductVariationGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function categoryByIdSearch(Request $request){
        try {
            $products = ProductCategory::query()
                ->leftJoin('products','products.id','=','product_categories.product_id')
                ->leftJoin('brands','brands.id','=','products.brand_id')
                ->leftJoin('product_types','product_types.id','=','products.type_id')
                ->leftJoin('product_variation_groups','product_variation_groups.product_id','=','products.id')
                ->select(DB::raw('(select id from product_variation_groups where product_id = products.id order by id asc limit 1) as variation_group'))
                ->leftJoin('product_variations','product_variations.id','=','product_variation_groups.id')
                ->select(DB::raw('(select image from product_images where variation_id = product_variations.id order by id asc limit 1) as image'))
                ->leftJoin('product_rules','product_rules.variation_id','=','product_variations.id')
                ->selectRaw('brands.name as brand_name,product_types.name as type_name, product_rules.*,product_seos.*, products.*')
                ->leftJoin('product_seos','product_seos.product_id','=','products.id')
                ->where('products.active',1)
                ->where('product_categories.active',1)
                ->where('product_categories.category_id',$request->category_id)
                ->where('product_seos.search_keywords','like','%'.$request->search_keywords.'%')
                ->get();

            if ($request->category_id == 0){
                $products = ProductCategory::query()
                    ->leftJoin('products','products.id','=','product_categories.product_id')
                    ->leftJoin('brands','brands.id','=','products.brand_id')
                    ->leftJoin('product_types','product_types.id','=','products.type_id')
                    ->leftJoin('product_variation_groups','product_variation_groups.product_id','=','products.id')
                    ->select(DB::raw('(select id from product_variation_groups where product_id = products.id order by id asc limit 1) as variation_group'))
                    ->leftJoin('product_variations','product_variations.id','=','product_variation_groups.id')
                    ->select(DB::raw('(select image from product_images where variation_id = product_variations.id order by id asc limit 1) as image'))
                    ->leftJoin('product_rules','product_rules.variation_id','=','product_variations.id')
                    ->selectRaw('brands.name as brand_name,product_types.name as type_name, product_rules.*,product_seos.*, products.*')
                    ->leftJoin('product_seos','product_seos.product_id','=','products.id')
                    ->where('products.active',1)
                    ->where('product_categories.active',1)
                    ->where('product_seos.search_keywords','like','%'.$request->search_keywords.'%')
                    ->get();
                return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $products]]);

            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

    public function filterProducts(Request $request){
        try {
            $products = Product::query();
            if ($request->category_id != ""){
                $products = $products
                    ->leftJoin('product_categories', 'product_categories.product_id', '=', 'products.id');

                $category_explodes = explode(",","$request->category_id");
                $products->where(function (Builder $products) use ($category_explodes){
                    for ($i = 0; $i < (count($category_explodes)-1); $i++){
                        $products->orWhere('product_categories.category_id', $category_explodes[$i]);
                    }
                });
            }

            if ($request->brand_id != ""){
                $products = $products
                    ->leftJoin('brands', 'brands.id', '=', 'products.brand_id');

                $brand_explodes = explode(",","$request->brand_id");
                $products->where(function (Builder $products) use ($brand_explodes){
                    for ($i = 0; $i < (count($brand_explodes)-1); $i++){
                        $products->orWhere('products.brand_id', $brand_explodes[$i]);
                    }
                });
            }

            if ($request->color != ""){
                $products = $products
                    ->leftJoin('product_variation_groups', 'product_variation_groups.product_id', '=', 'products.id')
                    ->leftJoin('product_variations', 'product_variations.variation_group_id', '=', 'product_variation_groups.id');

                $color_explodes = explode(",","$request->color");
                $products->where(function (Builder $products) use ($color_explodes){
                    for ($i = 0; $i < (count($color_explodes)-1); $i++){
                        $products->orWhere('product_variations.name', $color_explodes[$i]);
                    }
                });
            }
            $products = $products->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }
}
