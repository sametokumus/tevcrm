<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;

class LanguageController extends Controller
{

    public function emptyLanguage(){
        return redirect()->route('front.home',['language_key' => \app()->getLocale()]);
    }

    public function language($language_key)
    {
        try {
            App::setLocale($language_key);
            session()->put('locale', $language_key);

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['locale' => App::getLocale()]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
