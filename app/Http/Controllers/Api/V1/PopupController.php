<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Popup;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class PopupController extends Controller
{
    public function getPopups()
    {
        try {
            $popups = Popup::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['popups' => $popups]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getPopupById($popup_id){
        try {
            $popup = Popup::query()->where('id',$popup_id)->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['popup' => $popup]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getActivePopup(){
        try {
            $popup = Popup::query()->where('active',1)->orderByDesc('id')->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['popup' => $popup]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
