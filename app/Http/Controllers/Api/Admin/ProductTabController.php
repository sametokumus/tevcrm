<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductTab;
use App\Models\ProductTabContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class ProductTabController extends Controller
{

    public function addProductTab(Request $request)
    {

        try {
                $product_tab_row = ProductTab::query()->where('title',$request->title)->first();

                if (isset($product_tab_row)){
                    $product_tab_id = $product_tab_row->id;
                }else{
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

    public function updateProductTab(Request $request,$id){
        try {
            ProductTabContent::query()->where('product_tab_id',$request->product_tab_id)->update([
                'active' => 0
            ]);

            $product_tab_row = ProductTabContent::query()->where('product_tab_id',$id)->first();

            if (isset($product_tab_row)){
                ProductTabContent::query()->where('product_tab_id',$product_tab_row->id)->update([
                    'product_id' => $request->product_id,
                    'product_tab_id' => $product_tab_row->id,
                    'content_text' => $request->content_text,
                    'active' => 1
                ]);
            }else{
                ProductTabContent::query()->insert([
                    'product_id' => $request->product_id,
                    'product_tab_id' => $request->product_tab_id,
                    'content_text' => $request->content_text
                ]);
            }

            return response(['message' => 'Ürün sekmesi güncelleme işlemi başarılı.','status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001','ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }


}
