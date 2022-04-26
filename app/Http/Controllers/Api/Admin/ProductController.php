<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductImage;
use App\Models\ProductRule;
use App\Models\ProductTags;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDocument;
use App\Models\ProductVariation;
use App\Models\ProductVariationGroup;
use App\Models\Tag;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class ProductController extends Controller
{
    function objectToArray(&$object)
    {
        return @json_decode(json_encode($object), true);
    }
    public function addProduct(Request $request)
{
        try {

            $product = json_decode($request->product);
            $productArray = $this->objectToArray($product);

            Validator::make($productArray, [
                'brand_id' => 'required|exists:brands,id',
                'type_id' => 'required|exists:product_types,id',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required',
                'delivery_price' => 'required',
                'delivery_tax' => 'required',
                'is_free_shipping' => 'required',
                'view_all_images' => 'required'
            ]);
            $product_id = Product::query()->insertGetId([
                'brand_id' => $product->brand_id,
                'type_id' => $product->type_id,
                'name' => $product->name,
                'description' => $product->description,
                'sku' => $product->sku,
                'delivery_price' => $product->delivery_price,
                'delivery_tax' => $product->delivery_tax,
                'is_free_shipping' => $product->is_free_shipping,
                'view_all_images' => $product->view_all_images
            ]);

            if($request->hasFile('product_documents')){
                $file_namess = array();
                foreach ($request->file('product_documents') as $key => $product_document) {
                    $rand = uniqid();
                    $file_name = $rand . "-" . $product_document->getClientOriginalName();
                    $product_document->move(public_path('/images/ProductDocument/'), $file_name);
                    $file_path = "/images/ProductDocument/" . $file_name;
                    array_push($file_namess, $file_path);

                    ProductDocument::query()->insert([
                        'file' => $file_path,
                        'product_id' => $product_id,
                    ]);
                }
            }

            $product_categories = $product->product_categories;
            foreach ($product_categories as $product_category){
                    ProductCategory::query()->insert([
                        'product_id' => $product_id,
                        'category_id' => $product_category->category_id
                    ]);
            }

            $tags = $product->tags;
            foreach ($tags as $tag){
                $tag_row = Tag::query()->where('name',$tag->name)->first();
                if(isset($tag_row)){
                    $tag_id = $tag_row->id;
                }else {
                    $tag_id = Tag::query()->insertGetId([
                        'name' => $tag->name
                    ]);
                }
                ProductTags::query()->updateOrCreate([
                    'tag_id' => $tag_id,
                    'product_id' => $product_id
                ]);
            }

            return response(['message' => 'Ürün ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateProduct(Request $request,$product_id){
        try {
            $product = json_decode($request->product);
            $productArray = $this->objectToArray($product);

            Validator::make($productArray, [
                'brand_id' => 'required|exists:brands,id',
                'type_id' => 'required|exists:product_types,id',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required',
                'delivery_price' => 'required',
                'delivery_tax' => 'required',
                'is_free_shipping' => 'required',
                'view_all_images' => 'required'
            ]);
            $product_id = Product::query()->where('id',$product_id)->update([
                'brand_id' => $product->brand_id,
                'type_id' => $product->type_id,
                'name' => $product->name,
                'description' => $product->description,
                'sku' => $product->sku,
                'delivery_price' => $product->delivery_price,
                'delivery_tax' => $product->delivery_tax,
                'is_free_shipping' => $product->is_free_shipping,
                'view_all_images' => $product->view_all_images
            ]);

            if($request->hasFile('product_documents')){
                $file_namess = array();
                foreach ($request->file('product_documents') as $key => $product_document) {
                    $rand = uniqid();
                    $file_name = $rand . "-" . $product_document->getClientOriginalName();
                    $product_document->move(public_path('/images/ProductDocument/'), $file_name);
                    $file_path = "/images/ProductDocument/" . $file_name;
                    array_push($file_namess, $file_path);

                    ProductDocument::query()->where('product_id',$product_id)->update([
                        'file' => $file_path,
                        'product_id' => $product_id,
                    ]);
                }
            }
            ProductCategory::query()->where('product_id',$product_id)->update([
                'active' => 0
            ]);

            $product_categories = $product->product_categories;
            foreach ($product_categories as $product_category){
                $pc = ProductCategory::query()->where('product_id', $product_id)->where('category_id',$product_category->category_id)->first();
                if(isset($pc)){
                    ProductCategory::query()->where('id',$pc->id)->update([
                        'product_id' => $product_id,
                        'category_id' => $product_category->category_id,
                        'active' => 1
                    ]);
                } else{
                    ProductCategory::query()->insert([
                        'product_id' => $product_id,
                        'category_id' => $product_category->category_id
                    ]);
                }
            }
            ProductTags::query()->where('product_id',$product_id)->update([
                'active' => 0
            ]);
            $tags = $product->tags;
            foreach ($tags as $tag){
                $tag_row = Tag::query()->where('name',$tag->name)->first();
                if(isset($tag_row)){
                    $tag_id = $tag_row->id;
                }else {
                    $tag_id = Tag::query()->insertGetId([
                        'name' => $tag->name
                    ]);
                }

                ProductTags::query()->where('tag_id',$tag_id)->where('product_id',$product_id)->update([
                    'tag_id' => $tag_id,
                    'product_id' => $product_id,
                    'active' => 1
                ]);
            }

            return response(['message' => 'Ürün güncelleme işlemi başarılı.','status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function addProductVariationGroup(Request $request){
        try {

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'name' => 'required',
                'order' => 'required',
            ]);

            ProductVariationGroup::query()->insert([
                'product_id' => $request->product_id,
                'name' => $request->name,
                'order' => $request->order
            ]);
            return response(['message' => 'Ürün varyasyon grubu ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function addProductVariation(Request $request){
        try {

            $product_variation = json_decode($request->product_variations);
            $productArray = $this->objectToArray($product_variation);

            Validator::make($productArray, [
                'variation_group_id' => 'required|exists:variation,id',
                'name' => 'required|exists:products,id',
                'description' => 'required',
                'sku' => 'required',
                'regular_price' => 'required',
                'discounted_price' => 'required',
                'regular_tax' => 'required',
                'discounted_tax' => 'required'

            ]);

//            $product_variations = $product_variation;

//            foreach ($product_variations as $product_variation){
                $product_variation_id = ProductVariation::query()->insertGetId([
                    'variation_group_id' => $product_variation->variation_group_id,
                    'name' => $product_variation->name,
                    'description' => $product_variation->description,
                    'sku' => $product_variation->sku,
                    'regular_price' => $product_variation->regular_price,
                    'discounted_price' => $product_variation->discounted_price,
                    'regular_tax' => $product_variation->regular_tax,
                    'discounted_tax' => $product_variation->discounted_tax
                ]);

//            }
            $product_rules = $product_variation->product_rules;
            foreach ($product_rules as $product_rule){
                ProductRule::query()->insertGetId([
                    'variation' => $product_variation_id,
                    'quantity_stock' => $product_rule->quantity_stock,
                    'quantity_min' => $product_rule->quantity_min,
                    'quantity_step' => $product_rule->quantity_step,
                    'status' => $product_rule->status
                ]);
            }


//            $product_rules = $variation->product_rules;
//            foreach ($product_rules as $product_rule){
//                ProductRule::query()->insert([
//                    'variation_id' => $variation_id,
//                    'quantity_stock' => $product_rule->quantity_stock,
//                    'quantity_min' => $product_rule->quantity_min,
//                    'quantity_step' => $product_rule->quantity_step,
//                    'status' => $product_rule->status
//
//                ]);
//            }
//            $product_images = $variation->product_images;
//            foreach ($product_images as $product_image){
//                ProductImage::query()->insert([
//                    'variation_id' => $variation_id,
//                    'name' => $product_image->name,
//                    'order' => $product_image->order,
//                ]);
//
//            }

            return response(['message' => 'Ürün varyasyon ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

}
