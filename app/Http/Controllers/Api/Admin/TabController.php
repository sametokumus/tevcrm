<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductTab;
use App\Models\ProductTabContent;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class TabController extends Controller
{

    public function addTab(Request $request)
    {

        try {

            $request->validate([
                'title'=>'required'
            ]);
            $tab_id = ProductTab::query()->insertGetId([
                'title' => null
            ]);

            $title_id = TextContent::query()->insertGetId([
                'original_text' => $request->title
            ]);

            ProductTab::query()->where('id',$tab_id)->update([
                'title' => $title_id
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

    public function updateTab(Request $request,$id){
        try {
            $tab =ProductTab::query()->where('id',$id)->first();
            ProductTab::query()->where('id',$id)->update([
                'title' => $tab->title
            ]);

            TextContent::query()->where('id',$tab->title)->update([
               'original_text' => $request->title
            ]);

            return response(['message' => 'Ürün sekmesi güncelleme işlemi başarılı.','status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001','ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteTab($id){
        try {
            ProductTab::query()->where('id',$id)->update([
                'active'=>0
            ]);
            $tab = ProductTab::query()->where('id',$id)->first();
            $text_contents = TextContent::query()->where('active',1)->get();
            foreach ($text_contents as $text_content){
                if ($tab->title == $text_content->id){
                    TextContent::query()->where('id',$tab->title)->update([
                        'active' => 0
                    ]);
                }
            }
            return response(['message' => 'Ürün sekmesi silme işlemi başarılı.','status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001','ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

}
