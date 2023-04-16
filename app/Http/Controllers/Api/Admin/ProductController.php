<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
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
}
