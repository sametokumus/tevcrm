<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductRule;
use App\Models\ProductTab;
use App\Models\ProductTabContent;
use App\Models\ProductTags;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDocument;
use App\Models\ProductVariation;
use App\Models\ProductVariationGroup;
use App\Models\ProductVariationGroupType;
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

    public function addFullProduct(Request $request)
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

            if ($request->hasFile('product_documents')) {
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
            foreach ($product_categories as $product_category) {
                ProductCategory::query()->insert([
                    'product_id' => $product_id,
                    'category_id' => $product_category->category_id
                ]);
            }

            $tags = $product->tags;
            foreach ($tags as $tag) {
                $tag_row = Tag::query()->where('name', $tag->name)->first();
                if (isset($tag_row)) {
                    $tag_id = $tag_row->id;
                } else {
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

    public function updateFullProduct(Request $request, $product_id)
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
            Product::query()->where('id', $product_id)->update([
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

            if ($request->hasFile('product_documents')) {
                $file_namess = array();
                foreach ($request->file('product_documents') as $key => $product_document) {
                    $rand = uniqid();
                    $file_name = $rand . "-" . $product_document->getClientOriginalName();
                    $product_document->move(public_path('/images/ProductDocument/'), $file_name);
                    $file_path = "/images/ProductDocument/" . $file_name;
                    array_push($file_namess, $file_path);

                    ProductDocument::query()->where('product_id', $product_id)->update([
                        'file' => $file_path,
                        'product_id' => $product_id,
                    ]);
                }
            }
            ProductCategory::query()->where('product_id', $product_id)->update([
                'active' => 0
            ]);

            $product_categories = $product->product_categories;
            foreach ($product_categories as $product_category) {
                $pc = ProductCategory::query()->where('product_id', $product_id)->where('category_id', $product_category->category_id)->first();
                if (isset($pc)) {
                    ProductCategory::query()->where('id', $pc->id)->update([
                        'product_id' => $product_id,
                        'category_id' => $product_category->category_id,
                        'active' => 1
                    ]);
                } else {
                    ProductCategory::query()->insert([
                        'product_id' => $product_id,
                        'category_id' => $product_category->category_id
                    ]);
                }
            }
            ProductTags::query()->where('product_id', $product_id)->update([
                'active' => 0
            ]);
            $tags = $product->tags;
            foreach ($tags as $tag) {
                $tag_row = Tag::query()->where('name', $tag->name)->first();
                if (isset($tag_row)) {
                    $tag_id = $tag_row->id;
                } else {
                    $tag_id = Tag::query()->insertGetId([
                        'name' => $tag->name
                    ]);
                }

                ProductTags::query()->where('tag_id', $tag_id)->where('product_id', $product_id)->update([
                    'tag_id' => $tag_id,
                    'product_id' => $product_id,
                    'active' => 1
                ]);
            }

            return response(['message' => 'Ürün güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'ar' => $throwable->getMessage()]);
        }
    }

    public function deleteProduct($id)
    {
        try {

            $product = Product::query()->where('id', $id)->update([
                'active' => 0
            ]);
            return response(['message' => 'Ürün silme işlemi başarılı.', 'status' => 'success', 'object' => ['product' => $product]]);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'ar' => $throwable->getMessage()]);
        }
    }

    public function addFullProductVariationGroup(Request $request)
    {
        try {

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'name' => 'required',
                'order' => 'required',
            ]);

            $variation_group_type = ProductVariationGroupType::query()->where('name', $request->name)->first();
            if (isset($variation_group_type)) {
                $variation_group_type_id = $variation_group_type->id;
            } else {
                $variation_group_type_id = ProductVariationGroupType::query()->insertGetId([
                    'name' => $request->name
                ]);
            }
            ProductVariationGroup::query()->insert([
                'product_id' => $request->product_id,
                'group_type_id' => $variation_group_type_id,
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

    public function updateFullProductVariationGroup(Request $request, $variation_group_type_id)
    {
        try {

            $request->validate([
                'name' => 'required',
            ]);

            ProductVariationGroupType::query()->where('id', $variation_group_type_id)->update([
                'active' => 0
            ]);

            $variation_group_type = ProductVariationGroupType::query()->where('name', $request->name)->first();
            if (isset($variation_group_type)) {
                $variation_group_type_id = $variation_group_type->id;
            } else {
                $variation_group_type_id = ProductVariationGroupType::query()->insertGetId([
                    'name' => $request->name
                ]);
            }
            ProductVariationGroup::query()->where('group_type_id', $variation_group_type_id)->update([
                'product_id' => $request->product_id,
                'group_type_id' => $variation_group_type_id,
                'order' => $request->order
            ]);

            return response(['message' => 'Ürün varyasyon grubu güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function deleteFullProductVariationGroup($variation_group_id)
    {
        try {

            ProductVariationGroup::query()->where('id', $variation_group_id)->update([
                'active' => 0
            ]);

            return response(['message' => 'Ürün varyasyon grubu silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function addProductVariation(Request $request)
    {
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
            foreach ($product_rules as $product_rule) {
                ProductRule::query()->insert([
                    'variation_id' => $product_variation_id,
                    'quantity_stock' => $product_rule->quantity_stock,
                    'quantity_min' => $product_rule->quantity_min,
                    'quantity_step' => $product_rule->quantity_step,
                    'status' => $product_rule->status
                ]);
            }

            return response(['message' => 'Ürün varyasyon ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateProductVariation(Request $request, $id)
    {
        try {

            $request->validate([
                'variation_group_id' => 'required|exists:product_variations,id',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required',
                'regular_price' => 'required',
                'discounted_price' => 'required',
                'regular_tax' => 'required',
                'discounted_tax' => 'required'

            ]);

            ProductVariation::query()->where('id', $id)->update([
                'variation_group_id' => $request->variation_group_id,
                'name' => $request->name,
                'description' => $request->description,
                'sku' => $request->sku,
                'regular_price' => $request->regular_price,
                'discounted_price' => $request->discounted_price,
                'regular_tax' => $request->regular_tax,
                'discounted_tax' => $request->discounted_tax
            ]);

//            $product_variation_id = ProductVariation::query()->where('id',$id)->first();

            ProductRule::query()->where('variation_id', $id)->update([
                'variation_id' => $id,
                'quantity_stock' => $request->quantity_stock,
                'quantity_min' => $request->quantity_min,
                'quantity_step' => $request->quantity_step,
                'status' => $request->status
            ]);

            return response(['message' => 'Ürün varyasyon güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function deleteProductVariation($id)
    {
        try {

            ProductVariation::query()->where('id', $id)->update([
                'active' => 0
            ]);

            return response(['message' => 'Ürün varyasyon silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function addProductImage(Request $request)
    {
        try {

            if ($request->hasFile('product_images')) {
                $file_namess = array();
                foreach ($request->file('product_images') as $key => $product_document) {
                    $rand = uniqid();
                    $file_name = $rand . "-" . $product_document->getClientOriginalName();
                    $product_document->move(public_path('/images/ProductImage/'), $file_name);
                    $file_path = "/images/ProductImage/" . $file_name;
                    array_push($file_namess, $file_path);

                    ProductImage::query()->insert([
                        'image' => $file_path,
                        'variation_id' => $request->variation_id,
                        'name' => $request->name,
                        'order' => $request->order,
                    ]);
                }
            }

            return response(['message' => 'Ürün resim ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function addProductTab(Request $request)
    {

        try {
            $product_tab_row = ProductTab::query()->where('title', $request->title)->first();

            if (isset($product_tab_row)) {
                $product_tab_id = $product_tab_row->id;
            } else {
                $product_tab_id = ProductTab::query()->insertGetId([
                    'title' => $request->title
                ]);
            }
            ProductTabContent::query()->insert([
                'product_id' => $request->product_id,
                'product_tab_id' => $product_tab_id,
                'content_text' => $request->content_text
            ]);

            return response(['message' => 'Ürün sekmesi ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateProductTab(Request $request, $id)
    {
        try {
            ProductTabContent::query()->where('product_tab_id', $request->product_tab_id)->update([
                'active' => 0
            ]);

            $product_tab_row = ProductTabContent::query()->where('product_tab_id', $id)->first();

            if (isset($product_tab_row)) {
                ProductTabContent::query()->where('product_tab_id', $product_tab_row->id)->update([
                    'product_id' => $request->product_id,
                    'product_tab_id' => $product_tab_row->id,
                    'content_text' => $request->content_text,
                    'active' => 1
                ]);
            } else {
                ProductTabContent::query()->insert([
                    'product_id' => $request->product_id,
                    'product_tab_id' => $request->product_tab_id,
                    'content_text' => $request->content_text
                ]);
            }

            return response(['message' => 'Ürün sekmesi güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'ar' => $throwable->getMessage()]);
        }
    }

    public function addProduct(Request $request)
    {
        try {
            $request->validate([
                'brand_id' => 'required|exists:brands,id',
                'type_id' => 'required|exists:product_types,id',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required'
            ]);

            Product::query()->insert([
                'brand_id' => $request->brand_id,
                'type_id' => $request->type_id,
                'name' => $request->name,
                'description' => $request->description,
                'sku' => $request->sku,
                'delivery_price' => $request->delivery_price,
                'delivery_tax' => $request->delivery_tax,
                'is_free_shipping' => $request->is_free_shipping,
                'view_all_images' => $request->view_all_images,
            ]);
            return response(['message' => 'Ürün ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function updateProduct(Request $request,$id)
    {
        try {
            $request->validate([
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

            Product::query()->where('id',$id)->update([
                'brand_id' => $request->brand_id,
                'type_id' => $request->type_id,
                'name' => $request->name,
                'description' => $request->description,
                'sku' => $request->sku,
                'delivery_price' => $request->delivery_price,
                'delivery_tax' => $request->delivery_tax,
                'is_free_shipping' => $request->is_free_shipping,
                'view_all_images' => $request->view_all_images,
            ]);
            return response(['message' => 'Ürün güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function addProductTag(Request $request)
    {
        try {
            $request->validate([
                'tag_id' => 'required',
                'product_id' => 'required',
            ]);

            ProductTags::query()->insert([
                'tag_id' => $request->tag_id,
                'product_id' => $request->product_id
            ]);
            return response(['message' => 'Ürün etiketi ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function deleteProductTag($id)
    {
        try {
            ProductTags::query()->where('id',$id)->update([
                'active' =>0
            ]);
            return response(['message' => 'Ürün etiketi silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function addProductCategory(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required',
                'product_id' => 'required',
            ]);

            ProductCategory::query()->insert([
                'product_id' => $request->product_id,
                'category_id' => $request->category_id
            ]);
            return response(['message' => 'Ürün kategorisi ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function deleteProductCategory($id)
    {
        try {
            ProductCategory::query()->where('id',$id)->update([
                'active' =>0
            ]);
            return response(['message' => 'Ürün kategorisi silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function addProductDocument(Request $request){
        try {
            $document_id = ProductDocument::query()->insertGetId([
                'product_id' => $request->product_id,
                'title' => $request->title
            ]);
            if ($request->hasFile('file')) {
                $rand = uniqid();
                $image = $request->file('file');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/ProductDocument/'), $image_name);
                $image_path = "/images/ProductDocument/" . $image_name;

                ProductDocument::query()->where('id', $document_id)->update([
                    'file' => $image_path,
                ]);
            }
            return response(['message' => 'Ürün dökümanı ekleme işlemi başarılı.','status' => 'succcess']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateProductDocument(Request $request,$id){
        try {
            ProductDocument::query()->where('id',$id)->update([
                'product_id' => $request->product_id,
                'title' => $request->title
            ]);
            if ($request->hasFile('file')) {
                $rand = uniqid();
                $image = $request->file('file');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/ProductDocument/'), $image_name);
                $image_path = "/images/ProductDocument/" . $image_name;

                ProductDocument::query()->where('id', $id)->update([
                    'file' => $image_path,
                ]);
            }
            return response(['message' => 'Ürün dökümanı güncelleme işlemi başarılı.','status' => 'succcess']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function deleteProductDocument($id)
    {
        try {
            ProductDocument::query()->where('id',$id)->update([
                'active' => 0
            ]);
            return response(['message' => 'Ürün dökümanı silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function addProductVariationGroup(Request $request){
        try {
            ProductVariationGroup::query()->insert([
                'product_id' => $request->product_id,
                'group_type_id' => $request->group_type_id,
                'order' => $request->order
            ]);
            return response(['message' => 'Ürün varyasyon grubu ekleme işlemi başarılı.','status' => 'succcess']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateProductVariationGroup(Request $request,$id){
        try {
            ProductVariationGroup::query()->where('id',$id)->update([
                'product_id' => $request->product_id,
                'group_type_id' => $request->group_type_id,
                'order' => $request->order
            ]);
            return response(['message' => 'Ürün varyasyon grubu güncelleme işlemi başarılı.','status' => 'succcess']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function deleteProductVariationGroup($id){
        try {
            ProductVariationGroup::query()->where('id',$id)->update([
                'active' => 0
            ]);
            return response(['message' => 'Ürün varyasyon grubu silme işlemi başarılı.','status' => 'succcess']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function addVariationAndRule(Request $request){
        try {
            $request->validate([
                'variation_group_id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required',
                'regular_price' => 'required',
                'discounted_price' => 'required',
                'regular_tax' => 'required',
                'discounted_tax' => 'required'
            ]);

            $variation_id = ProductVariation::query()->insertGetId([
                'variation_group_id' => $request->variation_group_id,
                'name' => $request->name,
                'description' => $request->description,
                'sku' => $request->sku,
                'regular_price' => $request->regular_price,
                'discounted_price' => $request->discounted_price,
                'regular_tax' => $request->regular_tax,
                'discounted_tax' => $request->discounted_tax
            ]);
            ProductRule::query()->insert([
                'variation_id' => $variation_id,
                'quantity_stock' => $request->quantity_stock,
                'quantity_min' => $request->quantity_min,
                'quantity_step' => $request->quantity_step
            ]);
            return response(['message' => 'Varyasyon ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateVariationAndRule(Request $request,$id){
        try {
            $request->validate([
                'variation_group_id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required',
                'regular_price' => 'required',
                'discounted_price' => 'required',
                'regular_tax' => 'required',
                'discounted_tax' => 'required'
            ]);

            ProductVariation::query()->where('id',$id)->update([
                'variation_group_id' => $request->variation_group_id,
                'name' => $request->name,
                'description' => $request->description,
                'sku' => $request->sku,
                'regular_price' => $request->regular_price,
                'discounted_price' => $request->discounted_price,
                'regular_tax' => $request->regular_tax,
                'discounted_tax' => $request->discounted_tax
            ]);
            ProductRule::query()->where('variation_id',$id)->update([
                'quantity_stock' => $request->quantity_stock,
                'quantity_min' => $request->quantity_min,
                'quantity_step' => $request->quantity_step
            ]);
            return response(['message' => 'Varyasyon güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function deleteVariationAndRule($id){
        try {
            ProductVariation::query()->where('id',$id)->update([
                'active' => 0
            ]);
            return response(['message' => 'Varyasyon grubu silme işlemi başarılı.','status' => 'succcess']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateVariationImage(Request $request,$id){
        try {
            $request->validate([
                'variation_id' => 'required',
                'name' => 'required',
                'image' => 'required',
            ]);
            ProductImage::query()->where('id',$id)->update([
                'variation_id' => $request->variation_id,
                'name' => $request->name
            ]);
            if ($request->hasFile('image')) {
                $rand = uniqid();
                $image = $request->file('image');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/ProductImage/'), $image_name);
                $image_path = "/images/ProductImage/" . $image_name;

                ProductImage::query()->where('id', $id)->update([
                    'image' => $image_path,
                ]);
            }

            return response(['message' => 'Varyasyon resim güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function deleteVariationImage($id){
        try {
            ProductImage::query()->where('id',$id)->update([
                'active' => 0
            ]);
            return response(['message' => 'Varyasyon resmi silme işlemi başarılı.','status' => 'succcess']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }


}
