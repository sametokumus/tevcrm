<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class CategoryController extends Controller
{
    public function addCategory(Request $request)
    {
        try {
            $request->validate([
                'parent_id' => 'required',
                'name' => 'required',
                'slug' => 'required'
            ]);
           Category::query()->insert([
                'parent_id' => $request->parent_id,
                'name' => $request->name,
                'slug' => $request->slug
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
                'name' => 'required',
            ]);

            $address = Category::query()->where('id',$id)->update([
                'name' => $request->name
            ]);

            return response(['message' => 'Kategori güncelleme işlemi başarılı.','status' => 'success','object' => ['address' => $address]]);
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

            $address = Category::query()->where('id',$id)->update([
                'active' => 0,
            ]);
            return response(['message' => 'Kategori silme işlemi başarılı.','status' => 'success','object' => ['address' => $address]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
