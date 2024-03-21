<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class CategoryController extends Controller
{
    public function getCategory()
    {
        try {
            $categories = Category::query()->where('parent_id',0)->where('active',1)->get();
            foreach ($categories as $category){
                $sub_categories = Category::query()->where('parent_id',$category->id)->where('active',1)->get();

                foreach ($sub_categories as $category2){
                    $sub_categories2 = Category::query()->where('parent_id',$category2->id)->where('active',1)->get();
                    $category2['sub_categories'] = $sub_categories2;
                }

                $category['sub_categories'] = $sub_categories;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['categories' => $categories]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getCategoryByParentId($parent_id)
    {
        try {
            $categories = Category::query()->where('parent_id',$parent_id)->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['categories' => $categories]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function getCategoryById($category_id)
    {
        try {
            $category = Category::query()->where('id',$category_id)->where('active',1)->first();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['category' => $category]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
    public function addCategory(Request $request)
    {
        try {
            $request->validate([
                'parent_id' => 'required',
                'name' => 'required'
            ]);
            Category::query()->insert([
                'parent_id' => $request->parent_id,
                'name' => $request->name
            ]);
            return response(['message' => 'Kategori ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

    public function updateCategory(Request $request,$id){
        try {
            $request->validate([
                'parent_id' => 'required',
                'name' => 'required'
            ]);

            $category = Category::query()->where('id',$id)->update([
                'parent_id' => $request->parent_id,
                'name' => $request->name
            ]);

            return response(['message' => 'Kategori güncelleme işlemi başarılı.','status' => 'success','object' => ['category' => $category]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteCategory($id){
        try {

            Category::query()->where('id',$id)->update([
                'active' => 0
            ]);
            return response(['message' => 'Kategori silme işlemi başarılı.','status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
