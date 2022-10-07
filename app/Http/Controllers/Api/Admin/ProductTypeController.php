<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductType;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class ProductTypeController extends Controller
{
    public function addProductType(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required'
            ]);
            $product_type_id = ProductType::query()->insertGetId([
                'name' => null
            ]);

            $name_id = TextContent::query()->insertGetId([
                'original_text' => $request->name
            ]);

            ProductType::query()->where('id',$product_type_id)->update([
                'name' => $name_id
            ]);

            return response(['message' => 'Ürün tipi ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }

    public function updateProductType(Request $request,$id){
        try {
            $request->validate([
                'name' => 'required',
            ]);
            $product_type = ProductType::query()->where('id',$id)->first();
            ProductType::query()->where('id',$id)->update([
                'name' => $product_type->name
            ]);

            TextContent::query()->where('id',$product_type->name)->update([
                'original_text' => $request->name
            ]);

            return response(['message' => 'Ürün tipi güncelleme işlemi başarılı.','status' => 'success','object' => ['product_type' => $product_type]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteProductType($id){
        try {

            ProductType::query()->where('id',$id)->update([
                'active' => 0,
            ]);
            $product_type = ProductType::query()->where('id',$id)->first();
            $text_contents = TextContent::query()->where('active',1)->get();
            foreach ($text_contents as $text_content){
                if ($product_type->name == $text_content->id){
                    TextContent::query()->where('id',$product_type->name)->update([
                        'active' => 0
                    ]);
                }
            }
            return response(['message' => 'Ürün tipi silme işlemi başarılı.','status' => 'success','object' => ['product_type' => $product_type]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

}
