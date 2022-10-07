<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductTab;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TabController extends Controller
{
    public function getTabs()
    {
        try {
            $tabs = ProductTab::query()->where('active',1)->get();
            foreach ($tabs as $tab){
                $tab_title = TextContent::query()->where('id',$tab->title)->first();
                $tab['tab_title'] = $tab_title;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['tabs' => $tabs]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getTabById($type_id)
    {
        try {
            $tabs = ProductTab::query()
                ->leftJoin('text_contents','text_contents.id','=','product_tabs.title')
                ->where('product_tabs.active',1)
                ->where('product_tabs.id',$type_id)
                ->first();

            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['tabs' => $tabs]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
