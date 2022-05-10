<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Carrier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CarrierController extends Controller
{
    public function getCarriers()
    {
        try {
            $carriers = Carrier::query()->where('active',1)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['carriers' => $carriers]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getCarrierById($id)
    {
        try {
            $carriers = Carrier::query()->where('id',$id)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['carriers' => $carriers]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

}
