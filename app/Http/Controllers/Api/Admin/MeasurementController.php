<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Measurement;
use App\Models\Status;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function getMeasurements()
    {
        try {
            $measurements = Measurement::query()->where('active',1)->orderBy('sequence')->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['measurements' => $measurements]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getMeasurementById($id)
    {
        try {
            $measurement = Measurement::query()->where('id', $id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['measurement' => $measurement]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
