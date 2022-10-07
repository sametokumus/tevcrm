<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ProductTab;
use App\Models\Tag;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function getTags()
    {
        try {
            $tags = Tag::query()->where('active',1)->get();
            foreach ($tags as $tag){
                $tag_name = TextContent::query()->where('id',$tag->name)->first();
                $tag['tag_name'] = $tag_name;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['tags' => $tags]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getTagById($id)
    {
        try {
            $tags = Tag::query()
                ->leftJoin('text_contents','text_contents.id','=','tags.name')
                ->selectRaw('tags.*, text_contents.*')
                ->where('tags.active',1)
                ->where('tags.id',$id)
                ->first();

            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['tags' => $tags]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
