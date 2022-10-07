<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\TextContent;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function getCategory()
    {
        try {
            $categories = Category::query()->where('parent_id',0)->where('active',1)->get();
            foreach ($categories as $category){
                $category_name = TextContent::query()->where('id',$category->name)->first();
                $category['category_name'] = $category_name;
                $sub_categories = Category::query()->where('parent_id',$category->id)->where('active',1)->get();
                $category['sub_categories'] = $sub_categories;
                foreach ($sub_categories as $sub_category){
                    $sub_category_name = TextContent::query()->where('id',$sub_category->name)->first();
                    $sub_category['sub_category_name'] = $sub_category_name;
                }

            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['categories' => $categories]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getParentCategory()
    {
        try {
            $categories = Category::query()->where('parent_id',0)->where('active',1)->get();
            foreach ($categories as $category){
                $category_name = TextContent::query()->where('id',$category->name)->first();
                $category['category_name'] = $category_name;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['categories' => $categories]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getCategoryById($category_id)
    {
        try {
            $category = Category::query()
                ->leftJoin('text_contents','text_contents.id','=','categories.name')
                ->where('categories.id',$category_id)
                ->where('categories.active',1)
                ->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['category' => $category]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
