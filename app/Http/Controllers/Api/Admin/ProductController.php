<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;

class ProductController extends Controller
{
    public function getProducts()
    {
        try {
            $products = Product::query()
                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                ->selectRaw('products.*, brands.name as brand_name, categories.name as category_name')
                ->where('products.active', 1)
                ->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        }
    }

    public function getProductById($id)
    {
        try {
            $product = Product::query()
                ->leftJoin('brands', 'brands.id', '=', 'products.brand_id')
                ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
                ->selectRaw('products.*, brands.name as brand_name, categories.name as category_name')
                ->where('products.active', 1)
                ->where('products.id', $id)
                ->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['product' => $product]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function addImportedProducts(Request $request)
    {
        try {
            foreach ($request->products as $product){
                if(!empty($product[4])) {
                    $brand = Brand::query()->where('name', $product[4])->where('active', 1)->first();
                    if ($brand) {
                        $brand_id = $brand->id;
                    } else {
                        $brand_id = Brand::query()->insertGetId([
                            'name' => $product[4]
                        ]);
                    }
                }else{
                    $brand_id = null;
                }
                if(!empty($product[5])) {
                    $category = Category::query()->where('name', $product[5])->where('active', 1)->first();
                    if ($category) {
                        $category_id = $category->id;
                    } else {
                        $category_id = Category::query()->insertGetId([
                            'name' => $product[5]
                        ]);
                    }
                }else{
                    $category_id = null;
                }

                $product_id = Product::query()->insertGetId([
                    'brand_id' => $brand_id,
                    'category_id' => $category_id,
                    'ref_code' => $product[1],
                    'product_name' => $product[0],
                    'stock_code' => $product[3],
                    'stock_quantity' => $product[6],
                    'date_code' => $product[2],
                    'price' => $product[7],
                    'currency' => $product[8],
                ]);
            }

            return response(['message' => 'Ürün ekleme işlemi başarılı.', 'status' => 'success', 'object' => ['product_id' => $product_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function addProduct(Request $request)
    {
        try {
//            $request->validate([
//                'stock_code' => 'required'
//            ]);

            $product_id = Product::query()->insertGetId([
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'ref_code' => $request->ref_code,
                'product_name' => $request->product_name,
                'stock_code' => $request->stock_code,
                'stock_quantity' => $request->stock_quantity,
                'date_code' => $request->date_code,
                'price' => $request->price,
                'currency' => $request->currency,
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
//            $request->validate([
//                'stock_code' => 'required'
//            ]);

            Product::query()->where('id', $id)->update([
                'brand_id' => $request->brand_id,
                'category_id' => $request->category_id,
                'ref_code' => $request->ref_code,
                'product_name' => $request->product_name,
                'stock_code' => $request->stock_code,
                'stock_quantity' => $request->stock_quantity,
                'date_code' => $request->date_code,
                'currency' => $request->currency,
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

    public function updateProductName(Request $request, $id)
    {
        try {
//            $request->validate([
//                'stock_code' => 'required'
//            ]);

            Product::query()->where('id', $id)->update([
                'product_name' => $request->product_name
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
}
