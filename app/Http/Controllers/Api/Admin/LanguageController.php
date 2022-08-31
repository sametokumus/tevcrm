<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\Product;
use App\Models\TextContent;
use App\Models\Translation;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LanguageController extends Controller
{
    public function addLanguage(Request $request){
        try {
                Language::query()->insert([
                    'name' => $request->name
                ]);
            return response(['message' => 'Dil ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }


    public function addTranslations(Request $request){
        $translations = Translation::query()->where('active',1)->get();
        foreach ($translations as $translation){
            $translation_row = Translation::query()->where('translation',$translation->translation)->first();
            if (isset($translation_row)){
                $translationi_id = $translation_row->id;
            }
            else{
                Translation::query()->updateOrCreate([
                    'text_content_id' => $request->text_content_id,
                    'language_id' => $request->language_id,
                    'translation' => $request->translation,
                ]);
            }
        }
            return response('Dil ekleme işlemi başarılı');
    }

}
