<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class NotifyController extends Controller
{

    public function addNotifySetting(Request $request)
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

}
