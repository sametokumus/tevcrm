<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductTags;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDocument;
use App\Models\Tag;
use Illuminate\Auth\Events\Validated;
use Illuminate\Contracts\Support\ValidatedData;
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
                Tag::query()->updateOrCreate([
                    'name' => $tag->name
                ]);
            }

            $product_tags = $product->product_tags;
            foreach ($product_tags as $product_tag){
                ProductTags::query()->updateOrCreate([
                    'tag_id' => $product_tag->tag_id,
                    'product_id' => $product_tag->product_id
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

}
