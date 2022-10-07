<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductVariationGroupType;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class ProductVariationGroupTypeController extends Controller
{
    public function addProductVariationGroupType(Request $request)
    {
        try {

            $request->validate([
                'name' => 'required'
            ]);

            $variation_group_id = ProductVariationGroupType::query()->insertGetId([
                'name' => null
            ]);

            $name_id = TextContent::query()->insertGetId([
                'original_text' => $request->name
            ]);

            ProductVariationGroupType::query()->where('id',$variation_group_id)->update([
                'name' => $name_id
            ]);
            return response(['message' => 'Ürün varyasyon grup türü ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateProductVariationGroupType(Request $request, $variation_group_type_id)
    {
        try {

            $request->validate([
                'name' => 'required',
            ]);
            $variation_group_type = ProductVariationGroupType::query()->where('id',$variation_group_type_id)->first();
            ProductVariationGroupType::query()->where('id',$variation_group_type_id)->update([
                'name' => $variation_group_type->name
            ]);
            TextContent::query()->where('id',$variation_group_type->name)->update([
                'original_text' => $request->name
            ]);

            return response(['message' => 'Ürün varyasyon grup türü güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function deleteProductVariationGroupType($variation_group_type_id)
    {
        try {

            ProductVariationGroupType::query()->where('id',$variation_group_type_id)->update([
                'active' => 0
            ]);
            $variation_group = ProductVariationGroupType::query()->where('id',$variation_group_type_id)->first();
            $text_contents = TextContent::query()->where('active',1)->get();
            foreach ($text_contents as $text_content){
                if ($variation_group->name == $text_content->id){
                    TextContent::query()->where('id',$variation_group->name)->update([
                        'active' => 0
                    ]);
                }
            }

            return response(['message' => 'Ürün varyasyon grup türü silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }
}
