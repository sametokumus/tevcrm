<?php

namespace App\Http\Controllers\Api\V1;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDocument;
use App\Models\ProductImage;
use App\Models\ProductRule;
use App\Models\ProductTab;
use App\Models\ProductTabContent;
use App\Models\ProductTags;
use App\Models\ProductType;
use App\Models\ProductVariation;
use App\Models\ProductVariationGroup;
use App\Models\ProductVariationGroupType;
use App\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getAllProduct()
    {
        try {
            $products = Product::query()->where('active',1)->get();
            foreach ($products as $product){
                $brands = Product::query()->where('brand_id',$product->brand_id)->get();
                $product_types = ProductType::query()->where('id',$product->type_id)->get();
                $product_documents = ProductDocument::query()->where('product_id',$product->id)->get();
                $product_variation_groups = ProductVariationGroup::query()->where('product_id',$product->id)->get();
                foreach ($product_variation_groups as $product_variation_group){
                    $product_variation_group['name'] = ProductVariationGroupType::query()->where('id',$product_variation_group->id)->get();
                    $product_variation_group['variations'] = ProductVariation::query()->where('variation_group_id',$product_variation_group->id)->get();
                    $variations = ProductVariation::query()->where('variation_group_id',$product_variation_group->id)->get();
                    foreach ($variations as $variation){
                        $product_variation_group['images'] = ProductImage::query()->where('variation_id',$variation->id)->get();
                        $product_variation_group['rule'] = ProductRule::query()->where('variation_id',$variation->id)->get();
                    }
                }
                $product_tags = ProductTags::query()->where('product_id',$product->id)->get();
                foreach ($product_tags as $product_tag){
                    $product_tag['name'] = Tag::query()->where('id',$product_tag->tag_id)->first()->name;
                }
                $product_categories = ProductCategory::query()->where('product_id',$product->id)->get();
                foreach ($product_categories as $product_category){
                    $product_category['categories'] = Category::query()->where('id',$product_category->category_id)->get();
                }
                $product['brand'] = $brands;
                $product['product_type'] = $product_types;
                $product['product_documents'] = $product_documents;
                $product['variation_groups'] = $product_variation_groups;
                $product['product_tags'] = $product_tags;
                $product['product_categories'] = $product_categories;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $product]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getAllProductById($id){
        try {
            $product = Product::query()->where('id',$id)->where('active',1)->first();
            $brand = Brand::query()->where('id',$product->brand_id)->first();
            $product_type = ProductType::query()->where('id',$product->type_id)->first();
            $product_documents = ProductDocument::query()->where('product_id',$product->id)->where('active',1)->get();
            $product_tags = ProductTags::query()
                ->leftJoin('tags','tags.id','=','product_tags.tag_id')
                ->selectRaw('tags.*')
                ->where('product_id',$product->id)
                ->where('product_tags.active',1)
                ->get();
            $product_categories = ProductCategory::query()
                ->leftJoin('categories','categories.id','=','product_categories.category_id')
                ->selectRaw('categories.*')
                ->where('product_id',$product->id)
                ->where('product_categories.active',1)
                ->get();

            $product_variation_groups = ProductVariationGroup::query()
                ->leftJoin('product_variation_group_types','product_variation_group_types.id','=','product_variation_groups.group_type_id')
                ->leftJoin('products','products.id','=','product_variation_groups.product_id')
                ->selectRaw('product_variation_groups.* , product_variation_group_types.name as type_name')
                ->where('product_variation_groups.active',1)
                ->where('products.id',$id)
                ->get();
            $variations = ProductVariation::query()
                ->leftJoin('product_variation_groups','product_variation_groups.id','=','product_variations.variation_group_id')
                ->leftJoin('products','products.id','=','product_variation_groups.product_id')
                ->selectRaw('product_variations.*')
                ->where('product_variations.active',1)
                ->where('products.active',1)
                ->where('products.id',$id)
                ->get();

            foreach ($variations as $variation){
                $rule = ProductRule::query()->where('variation_id',$variation->id)->first();
                $images = ProductImage::query()->where('variation_id',$variation->id)->get();
                $variation['rule'] = $rule;
                $variation['images'] = $images;
            }

            $featured_variation = ProductVariation::query()->where('id', $product->featured_variation)->first();
            $rule = ProductRule::query()->where('variation_id',$featured_variation->id)->first();
            $images = ProductImage::query()->where('variation_id',$featured_variation->id)->get();
            $featured_variation['rule'] = $rule;
            $featured_variation['images'] = $images;

            $product['brand'] = $brand;
            $product['product_type'] = $product_type;
            $product['product_documents'] = $product_documents;
            $product['product_tags'] = $product_tags;
            $product['product_categories'] = $product_categories;
            $product['variation_groups'] = $product_variation_groups;
            $product['variations'] = $variations;
            $product['featured_variation'] = $featured_variation;
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $product]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

    public function getAllProductWithVariationById($product_id, $variation_id){
        try {
            $product = Product::query()->where('id',$product_id)->where('active',1)->first();
            $brand = Brand::query()->where('id',$product->brand_id)->first();
            $product_type = ProductType::query()->where('id',$product->type_id)->first();
            $product_documents = ProductDocument::query()->where('product_id',$product->id)->where('active',1)->get();
            $product_tags = ProductTags::query()
                ->leftJoin('tags','tags.id','=','product_tags.tag_id')
                ->selectRaw('tags.*')
                ->where('product_id',$product->id)
                ->where('product_tags.active',1)
                ->get();
            $product_categories = ProductCategory::query()
                ->leftJoin('categories','categories.id','=','product_categories.category_id')
                ->selectRaw('categories.*')
                ->where('product_id',$product->id)
                ->where('product_categories.active',1)
                ->get();

            $product_variation_groups = ProductVariationGroup::query()
                ->leftJoin('product_variation_group_types','product_variation_group_types.id','=','product_variation_groups.group_type_id')
                ->leftJoin('products','products.id','=','product_variation_groups.product_id')
                ->selectRaw('product_variation_groups.* , product_variation_group_types.name as type_name')
                ->where('product_variation_groups.active',1)
                ->where('products.id',$product_id)
                ->get();
            $variations = ProductVariation::query()
                ->leftJoin('product_variation_groups','product_variation_groups.id','=','product_variations.variation_group_id')
                ->leftJoin('products','products.id','=','product_variation_groups.product_id')
                ->selectRaw('product_variations.*')
                ->where('product_variations.active',1)
                ->where('products.active',1)
                ->where('products.id',$product_id)
                ->get();

            foreach ($variations as $variation){
                $rule = ProductRule::query()->where('variation_id',$variation->id)->first();
                $images = ProductImage::query()->where('variation_id',$variation->id)->get();
                $variation['rule'] = $rule;
                $variation['images'] = $images;
            }

            $featured_variation = ProductVariation::query()->where('id', $variation_id)->first();
            $rule = ProductRule::query()->where('variation_id',$variation_id)->first();
            $images = ProductImage::query()->where('variation_id',$variation_id)->get();
            $featured_variation['rule'] = $rule;
            $featured_variation['images'] = $images;

            $product['brand'] = $brand;
            $product['product_type'] = $product_type;
            $product['product_documents'] = $product_documents;
            $product['product_tags'] = $product_tags;
            $product['product_categories'] = $product_categories;
            $product['variation_groups'] = $product_variation_groups;
            $product['variations'] = $variations;
            $product['featured_variation'] = $featured_variation;
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $product]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

    public function getProduct()
    {
        try {
            $products = Product::query()
                ->leftJoin('brands','brands.id','=','products.brand_id')
                ->leftJoin('product_types','product_types.id','=','products.type_id')
                ->leftJoin('product_variations','product_variations.id','=','products.featured_variation')
                ->select(DB::raw('(select image from product_images where variation_id = product_variations.id order by id asc limit 1) as image'))
                ->leftJoin('product_rules','product_rules.variation_id','=','product_variations.id')
                ->selectRaw('products.* ,products.id as product_id ,brands.name as brand_name,product_types.name as type_name, product_rules.*')
                ->where('products.active',1)
                ->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

    public function getProductsByCategoryId($category_id){
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
                ->selectRaw('products.* ,brands.name as brand_name,product_types.name as type_name, product_rules.*')
                ->where('products.active',1)
                ->where('product_categories.active',1)
                ->where('product_categories.category_id',$category_id)
                ->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

    public function getProductById($id)
    {
        try {
            $product = Product::query()->where('id',$id)->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $product]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductTagById($product_id)
    {
        try {
            $product_tags = ProductTags::query()->where('product_id',$product_id)->get();
            foreach ($product_tags as $product_tag){
                $tag_name = Tag::query()->where('id',$product_tag->tag_id)->get();
                $product_tag['tag'] = $tag_name;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_tags' => $product_tags]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductCategoryById($product_id)
    {
        try {
            $product_categories = ProductCategory::query()->where('product_id',$product_id)->get();
            foreach ($product_categories as $product_category){
                $category_name = Category::query()->where('id',$product_category->category_id)->get();
                $product_category['category'] = $category_name;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_categories' => $product_categories]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductDocumentById($product_id)
    {
        try {
            $product_documents = ProductDocument::query()->where('product_id',$product_id)->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_documents' => $product_documents]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductVariationGroupById($product_id)
    {
        try {
            $product_variation_groups = ProductVariationGroup::query()->where('product_id',$product_id)->get();
            foreach ($product_variation_groups as $product_variation_group){
                $variation_group_type = ProductVariationGroupType::query()->where('id',$product_variation_group->group_type_id)->first();
                $product_variation_group['variation_group_type'] = $variation_group_type;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_variation_groups' => $product_variation_groups]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductVariationById($id)
    {
        try {
            $product_variation = ProductVariation::query()->where('id',$id)->first();
            $rules = ProductRule::query()->where('variation_id',$id)->first();
            $product_variation['rule'] = $rules;
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_variation' => $product_variation]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductVariationsById($id)
    {
        try {
            $product_variations = ProductVariationGroup::query()
                ->leftJoin('product_variations', 'product_variations.variation_group_id', '=', 'product_variation_groups.id')
                ->where('product_variation_groups.product_id', $id)
                ->selectRaw('product_variations.*');

            foreach ($product_variations as $product_variation){
                $rules = ProductRule::query()->where('variation_id',$product_variation->id)->first();
                $product_variation['rule'] = $rules;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_variations' => $product_variations]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getVariationsImageById($product_id)
    {
        try {
            $product_variations = ProductVariationGroup::query()
                ->leftJoin('product_variations', 'product_variations.variation_group_id', '=', 'product_variation_groups.id')
                ->where('product_variation_groups.product_id', $product_id)
                ->selectRaw('product_variations.*');

            foreach ($product_variations as $product_variation){
                $images = ProductImage::query()->where('variation_id',$product_variation->id)->where('active',1)->get();
                $product_variation['images'] = $images;
            }

            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_variations' => $product_variations]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getVariationImageById($variation_id)
    {
        try {
            $variation_images = ProductImage::query()->where('variation_id',$variation_id)->get();

            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['variation_images' => $variation_images]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductTabsById($product_id)
    {
        try {
            $product_tabs = ProductTabContent::query()->where('product_id',$product_id)->where('active',1)->get();
            foreach ($product_tabs as $product_tab){
                $tab = ProductTab::query()->where('id',$product_tab->product_tab_id)->first();
                $product_tab['tab'] = $tab;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_tabs' => $product_tabs]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProductTabById($tab_id)
    {
        try {
            $product_tab = ProductTabContent::query()->where('id',$tab_id)->first();
            $tab = ProductTab::query()->where('id',$product_tab->product_tab_id)->first();
            $product_tab['tab'] = $tab;

            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product_tab' => $product_tab]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getBrandByIdProduct(){
        try {

            $brands = Brand::query()->where('active',1)->get();
            foreach ($brands as $brand){
                $product_count = Product::query()->where('brand_id',$brand->id)->count();
                $products = Product::query()->limit(3)->where('brand_id',$brand->id)->get();
                $brand['count'] = $product_count;
                foreach ($products as $product){
                    $product_variation_groups = ProductVariationGroup::query()
                        ->leftJoin('product_variation_group_types','product_variation_group_types.id','=','product_variation_groups.group_type_id')
                        ->leftJoin('products','products.id','=','product_variation_groups.product_id')
                        ->selectRaw('product_variation_groups.* , product_variation_group_types.name as type_name')
                        ->where('product_variation_groups.active',1)
                        ->where('products.id',$product->id)
                        ->get();

                    $variations = ProductVariation::query()
                        ->leftJoin('product_variation_groups','product_variation_groups.id','=','product_variations.variation_group_id')
                        ->leftJoin('products','products.id','=','product_variation_groups.product_id')
                        ->leftJoin('product_rules','product_rules.variation_id','=','product_variations.id')
                        ->selectRaw('product_variations.*')
                        ->where('product_variations.active',1)
                        ->where('products.id',$product->id)
                        ->get();

                    foreach ($variations as $variation){
                        $rule = ProductRule::query()->where('variation_id',$variation->id)->first();
                        $images = ProductImage::query()->where('variation_id',$variation->id)->get();
                        $variation['rule'] = $rule;
                        $variation['images'] = $images;
                    }

                    $product['product_variation_groups'] = $product_variation_groups;
                    $product['variations'] = $variations;

                }
                $brand['products'] = $products;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['brands' => $brands]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

}
