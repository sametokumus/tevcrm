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
            $brand = Product::query()->where('brand_id',$product->brand_id)->first();
            $product_type = ProductType::query()->where('id',$product->type_id)->first();
            $product_document = ProductDocument::query()->where('product_id',$product->id)->first();
            $product_variation_group = ProductVariationGroup::query()->where('product_id',$product->id)->first();
            $product_variation_group['name'] = ProductVariationGroupType::query()->where('id',$product_variation_group->group_type_id)->first();
            $product_variation_group['variations'] = ProductVariation::query()->where('variation_group_id',$product_variation_group->id)->first();
            $variation = ProductVariation::query()->where('variation_group_id',$product_variation_group->id)->first();
            $product_variation_group['images'] = ProductImage::query()->where('variation_id',$variation->id)->first();
            $product_variation_group['rule'] = ProductRule::query()->where('variation_id',$variation->id)->first();
            $product_tag = ProductTags::query()->where('product_id',$product->id)->first();
            $product_tag['name'] = Tag::query()->where('id',$product_tag->tag_id)->first();
            $product_category = ProductCategory::query()->where('product_id',$product->id)->first();
            $product_category['categories'] = Category::query()->where('id',$product_category->category_id)->first();

            $product['brand'] = $brand;
            $product['product_type'] = $product_type;
            $product['product_document'] = $product_document;
            $product['variation_group'] = $product_variation_group;
            $product['product_tag'] = $product_tag;
            $product['product_category'] = $product_category;
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $product]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getProduct()
    {
        try {
            $products = Product::query()->where('active', 1)->get();
            foreach ($products as $product){
                $product['brand_name'] = Brand::query()->where('id', $product->brand_id)->first()->name;
                $variation_group = ProductVariationGroup::query()->where('product_id', $product->id)->first();
                $variation = ProductVariation::query()->where('variation_group_id', $variation_group->id)->first();
                $product['variation_id'] = $variation->id;
                $product['image'] = ProductImage::query()-where('variation_id', $variation->id)->first()->image;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
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
                $category_name = Tag::query()->where('id',$product_category->category_id)->get();
                $product_tag['category'] = $category_name;
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
            $product_variations = ProductVariationGroup::join('product_variations', 'product_variations.variation_group_id', '=', 'product_variation_groups.id')
                ->where('product_variation_groups.product_id', $id)
                ->get(['product_variations.*']);

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
            $product_variations = ProductVariationGroup::join('product_variations', 'product_variations.variation_group_id', '=', 'product_variation_groups.id')
                ->where('product_variation_groups.product_id', $product_id)
                ->get(['product_variations.*']);

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

}
