<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Language;
use App\Models\ProductImage;
use App\Models\ProductPackageType;
use App\Models\ProductRule;
use App\Models\ProductSeo;
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
use App\Models\TextContent;
use App\Models\Translation;
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
                'short_description' => $product->short_description,
                'notes' => $product->notes,
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
                'short_description' => $product->short_description,
                'notes' => $product->notes,
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


            $request->validate([
                'variation_group_id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required',
            ]);


            $variation_id = ProductVariation::query()->insertGetId([
                'variation_group_id' => $request->variation_group_id,
                'name' => $request->name,
                'description' => $request->description,
                'sku' => $request->sku,

            ]);


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

//            $request->validate([
//                'variation_group_id' => 'required|exists:product_variations,id',
//                'name' => 'required',
//                'description' => 'required',
//                'sku' => 'required',
//            ]);

            ProductVariation::query()->where('id', $id)->update([
                'variation_group_id' => $request->variation_group_id,
                'name' => $request->name,
                'description' => $request->description,
                'sku' => $request->sku
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
                foreach ($request->file('product_images') as $key => $product_document) {
                    $rand = uniqid();
                    $file_name = $rand . "-" . $product_document->getClientOriginalName();
                    $product_document->move(public_path('/images/ProductImage/'), $file_name);
                    $file_path = "/images/ProductImage/" . $file_name;

                    ProductImage::query()->insert([
                        'image' => $file_path,
                        'product_id' => $request->product_id,
                        'name' => $request->name,
                        'order' => $request->order
                    ]);
                }
                return response(['message' => 'Ürün resim ekleme işlemi başarılı.', 'status' => 'success']);

            }

            return response(['message' => 'Eklenecek ürün görseli bulunamadı.', 'status' => 'files-001']);
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

            ProductTabContent::query()->insert([
                'product_id' => $request->product_id,
                'product_tab_id' => $request->product_tab_id,
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

    public function updateProductTab(Request $request)
    {
        try {


            $product_tab_row = ProductTabContent::query()->where('product_id', $request->product_id)->where('product_tab_id', $request->product_tab_id)->first();

            if (isset($product_tab_row)) {
                ProductTabContent::query()->where('product_tab_id', $product_tab_row->id)->update([
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

    public function deleteProductTab($tab_id)
    {
        try {

            ProductTabContent::query()->where('id', $tab_id)->update([
                'active' => 0
            ]);

            return response(['message' => 'Ürün sekmesi silme işlemi başarılı.', 'status' => 'success']);
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

            $product_id = Product::query()->insertGetId([
                'brand_id' => $request->brand_id,
                'type_id' => $request->type_id,
                'name' => null,
                'description' => null,
                'short_description' => null,
                'notes' => null,
                'sku' => $request->sku,
                'is_free_shipping' => $request->is_free_shipping
            ]);

           $name_id = TextContent::query()->insertGetId([
                'original_text' => $request->name
            ]);
            $description_id = TextContent::query()->insertGetId([
                'original_text' => $request->description
            ]);
            $short_description_id = TextContent::query()->insertGetId([
                'original_text' => $request->short_description
            ]);
            $notes_id = TextContent::query()->insertGetId([
                'original_text' => $request->notes
            ]);
            Product::query()->where('id',$product_id)->update([
                'name'  =>$name_id,
                'description' => $description_id,
                'short_description' => $short_description_id,
                'notes' => $notes_id
            ]);

            $discounted_price = null;
            $discounted_tax = null;
            $regular_tax = $request->regular_price / (100 + $request->tax_rate) * $request->tax_rate;
            if ($request->discount_rate != 0) {
                $discounted_price = $request->regular_price / 100 * (100 - $request->discount_rate);
                $discounted_tax = $discounted_price / (100 + $request->tax_rate) * $request->tax_rate;
            }

            ProductRule::query()->where('id', $product_id)->insert([
                'product_id' => $product_id,
                'quantity_stock' => $request->quantity_stock,
                'discount_rate' => $request->discount_rate,
                'tax_rate' => $request->tax_rate,
                'regular_price' => $request->regular_price,
                'regular_tax' => $regular_tax,
                'discounted_price' => $discounted_price,
                'discounted_tax' => $discounted_tax
            ]);

            return response(['message' => 'Ürün ekleme işlemi başarılı.', 'status' => 'success', 'object' => ['product_id' => $product_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $request->validate([
                'brand_id' => 'required|exists:brands,id',
                'type_id' => 'required|exists:product_types,id',
                'name' => 'required',
                'description' => 'required',
                'sku' => 'required',
                'is_free_shipping' => 'required',
                'view_all_images' => 'required'
            ]);

            Product::query()->where('id', $id)->update([
                'brand_id' => $request->brand_id,
                'type_id' => $request->type_id,
                'sku' => $request->sku,
                'is_free_shipping' => $request->is_free_shipping,
                'view_all_images' => $request->view_all_images,
            ]);

            $product = Product::query()->where('id', $id)->first();

            TextContent::query()->where('id', $product->name)->update([
                'original_text' => $request->name
            ]);
            TextContent::query()->where('id', $product->description)->update([
                'original_text' => $request->description
            ]);
            TextContent::query()->where('id', $product->short_description)->update([
                'original_text' => $request->short_description
            ]);
            TextContent::query()->where('id', $product->notes)->update([
                'original_text' => $request->notes
            ]);


            $discounted_price = null;
            $discounted_tax = null;
            $regular_tax = $request->regular_price / (100 + $request->tax_rate) * $request->tax_rate;
            if ($request->discount_rate != 0) {
                $discounted_price = $request->regular_price / 100 * (100 - $request->discount_rate);
                $discounted_tax = $discounted_price / (100 + $request->tax_rate) * $request->tax_rate;
            }


            ProductRule::query()->where('id', $id)->update([
                'product_id' => $id,
                'quantity_stock' => $request->quantity_stock,
                'discount_rate' => $request->discount_rate,
                'tax_rate' => $request->tax_rate,
                'regular_price' => $request->regular_price,
                'regular_tax' => $regular_tax,
                'discounted_price' => $discounted_price,
                'discounted_tax' => $discounted_tax
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

    public function deleteProductTag(Request $request)
    {
        try {
            ProductTags::query()->where('product_id', $request->product_id)->where('tag_id', $request->tag_id)->update([
                'active' => 0
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

    public function deleteProductCategory(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required',
                'product_id' => 'required',
            ]);

            ProductCategory::query()->where('product_id', $request->product_id)->where('category_id', $request->category_id)->update([
                'active' => 0
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

    public function addProductDocument(Request $request)
    {
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
            return response(['message' => 'Ürün dökümanı ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateProductDocument(Request $request, $id)
    {
        try {
            ProductDocument::query()->where('id', $id)->update([
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
            return response(['message' => 'Ürün dökümanı güncelleme işlemi başarılı.', 'status' => 'success']);
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
            ProductDocument::query()->where('id', $id)->update([
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

    public function addProductVariationGroup(Request $request)
    {
        try {
            ProductVariationGroup::query()->insert([
                'product_id' => $request->product_id,
                'group_type_id' => $request->group_type_id,
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

    public function updateProductVariationGroup(Request $request, $id)
    {
        try {
            ProductVariationGroup::query()->where('id', $id)->update([
                'product_id' => $request->product_id,
                'group_type_id' => $request->group_type_id,
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

    public function deleteProductVariationGroup($id)
    {
        try {
            ProductVariationGroup::query()->where('id', $id)->update([
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

    public function updateProductImage(Request $request, $id)
    {
        try {
            $request->validate([
                'product_id' => 'required',
                'name' => 'required',
            ]);
            ProductImage::query()->where('id', $id)->update([
                'product_id' => $request->product_id,
                'name' => $request->name,
                'order' => $request->order
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

    public function deleteProductImage($id)
    {
        try {
            ProductImage::query()->where('id', $id)->update([
                'active' => 0
            ]);
            return response(['message' => 'Varyasyon resmi silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function addProductSeo(Request $request)
    {
        try {
            $product_seo = ProductSeo::query()->where('product_id', $request->product_id)->first();
            if (isset($product_seo)) {
                ProductSeo::query()->where('id', $product_seo->id)->update([
                    'title' => $request->title,
                    'keywords' => $request->keywords,
                    'search_keywords' => $request->search_keywords
                ]);
            } else {
                ProductSeo::query()->insert([
                    'product_id' => $request->product_id,
                    'title' => $request->title,
                    'keywords' => $request->keywords,
                    'search_keywords' => $request->search_keywords
                ]);
            }

            return response(['message' => 'Ürün SEO ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function deleteProductSeo($id)
    {
        try {
            ProductSeo::query()->where('id', $id)->update([
                'active' => 0
            ]);
            return response(['message' => 'Ürün SEO silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateBrandIdDiscountRate(Request $request, $brand_id)
    {
        try {
            $products = Product::query()->where('brand_id', $brand_id)->get();
            foreach ($products as $product) {
                $product_variation_groups = ProductVariationGroup::query()->where('product_id', $product->id)->get();
                foreach ($product_variation_groups as $product_variation_group) {
                    $product_variations = ProductVariation::query()->where('variation_group_id', $product_variation_group->id)->get();
                    foreach ($product_variations as $product_variation) {
                        $product_rule = ProductRule::query()->where('variation_id', $product_variation->id)->first();

                        if ($request->discount_rate == 0) {
                            $discount_rate = null;
                            $discounted_price = null;
                            $discounted_tax = null;
                        } else {

                            $discount_rate = $request->discount_rate;
                            $discounted_price = $product_rule->regular_price / 100 * (100 - $discount_rate);
                            $discounted_tax = $discounted_price / 100 * $product_rule->tax_rate;
                        }
                        ProductRule::query()->where('variation_id', $product_variation->id)->update([
                            'discount_rate' => $discount_rate,
                            'discounted_price' => $discounted_price,
                            'discounted_tax' => $discounted_tax
                        ]);
                    }
                }
            }
            return response(['message' => 'İskonto güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateTypeIdDiscountRate(Request $request, $type_id)
    {
        try {
            $products = Product::query()->where('type_id', $type_id)->get();
            foreach ($products as $product) {
                $product_variation_groups = ProductVariationGroup::query()->where('product_id', $product->id)->get();
                foreach ($product_variation_groups as $product_variation_group) {
                    $product_variations = ProductVariation::query()->where('variation_group_id', $product_variation_group->id)->get();
                    foreach ($product_variations as $product_variation) {
                        $product_rule = ProductRule::query()->where('product_id', $product->id)->first();

                        if ($request->discount_rate == 0) {
                            $discount_rate = null;
                            $discounted_price = null;
                            $discounted_tax = null;
                        } else {

                            $discount_rate = $request->discount_rate;
                            $discounted_price = $product_rule->regular_price / 100 * (100 - $discount_rate);
                            $discounted_tax = $discounted_price / 100 * $product_rule->tax_rate;
                        }
                        ProductRule::query()->where('product_id', $product->id)->update([
                            'discount_rate' => $discount_rate,
                            'discounted_price' => $discounted_price,
                            'discounted_tax' => $discounted_tax
                        ]);
                    }
                }
            }
            return response(['message' => 'İskonto güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateCategoryIdDiscountRate(Request $request, $category_id)
    {
        try {
            $product_categories = ProductCategory::query()->where('category_id', $category_id)->get();
            foreach ($product_categories as $product_category) {
                $product = Product::query()->where('id', $product_category->product_id)->first();
                $product_variation_groups = ProductVariationGroup::query()->where('product_id', $product->id)->get();
                foreach ($product_variation_groups as $product_variation_group) {
                    $product_variations = ProductVariation::query()->where('variation_group_id', $product_variation_group->id)->get();
                    foreach ($product_variations as $product_variation) {
                        $product_rule = ProductRule::query()->where('product_id', $product_category->product_id)->first();

                        if ($request->discount_rate == 0) {
                            $discount_rate = null;
                            $discounted_price = null;
                            $discounted_tax = null;
                        } else {

                            $discount_rate = $request->discount_rate;
                            $discounted_price = $product_rule->regular_price / 100 * (100 - $discount_rate);
                            $discounted_tax = $discounted_price / 100 * $product_rule->tax_rate;
                        }
                        ProductRule::query()->where('product_id', $product_category->product_id)->update([
                            'discount_rate' => $discount_rate,
                            'discounted_price' => $discounted_price,
                            'discounted_tax' => $discounted_tax
                        ]);
                    }
                }
            }
            return response(['message' => 'İskonto güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

}
