<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Test;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class TestController extends Controller
{
    public function getTests()
    {
        try {
            $tests = Test::query()
                ->leftJoin('categories', 'categories.id', '=', 'tests.category_id')
                ->selectRaw('tests.*, categories.name as category_name')
                ->where('tests.active',1)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['tests' => $tests]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }
    public function getTestsByCategoryId($category_id)
    {
        try {
            $tests = Test::query()
                ->leftJoin('categories', 'categories.id', '=', 'tests.category_id')
                ->selectRaw('tests.*, categories.name as category_name')
                ->where('tests.category_id',$category_id)
                ->where('tests.active',1)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['tests' => $tests]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }

    public function getTestById($test_id)
    {
        try {
            $test = Test::query()
                ->leftJoin('categories', 'categories.id', '=', 'tests.category_id')
                ->selectRaw('tests.*, categories.name as category_name')
                ->where('tests.id', $test_id)
                ->where('tests.active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['test' => $test]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addTest(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required'
            ]);
            $user = Auth::user();
            Test::query()->insertGetId([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'sample_count' => $request->sample_count,
                'sample_description' => $request->sample_description,
                'total_day' => $request->total_day,
                'price' => $request->price
            ]);

            return response(['message' => __('Test ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateTest(Request $request, $test_id){
        try {
            $request->validate([
                'name' => 'required',
            ]);
            Test::query()->where('id', $test_id)->update([
                'category_id' => $request->category_id,
                'name' => $request->name,
                'sample_count' => $request->sample_count,
                'sample_description' => $request->sample_description,
                'total_day' => $request->total_day,
                'price' => $request->price
            ]);

            return response(['message' => __('Test güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001','ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getTraceAsString()]);
        }
    }

    public function deleteTest($test_id){
        try {

            Test::query()->where('id',$test_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Test silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
