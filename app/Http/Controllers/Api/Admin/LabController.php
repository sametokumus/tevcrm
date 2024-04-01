<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Laboratory;
use App\Models\Test;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class LabController extends Controller
{
    public function getLabs()
    {
        try {
            $labs = Laboratory::query()
                ->where('laboratories.active',1)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['labs' => $labs]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }
    public function getLabById($lab_id)
    {
        try {
            $lab = Laboratory::query()
                ->where('laboratories.id', $lab_id)
                ->where('laboratories.active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['lab' => $lab]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addLab(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required'
            ]);
            Laboratory::query()->insert([
                'name' => $request->name,
                'lab_code' => $request->lab_code,
                'last_no' => $request->last_no
            ]);

            return response(['message' => __('Laboratuvar ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateLab(Request $request, $lab_id){
        try {
            $request->validate([
                'name' => 'required',
            ]);
            Laboratory::query()->where('id', $lab_id)->update([
                'name' => $request->name,
                'lab_code' => $request->lab_code,
                'last_no' => $request->last_no
            ]);

            return response(['message' => __('Laboratuvar güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001','ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getTraceAsString()]);
        }
    }

    public function deleteLab($lab_id){
        try {

            Laboratory::query()->where('id', $lab_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Laboratuvar silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
