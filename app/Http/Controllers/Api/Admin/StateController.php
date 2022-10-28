<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class StateController extends Controller
{
    public function getStatesByCountryId($country_id)
    {
        try {
            $states = State::query()->where('country_id',$country_id)->orderBy('name')->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success','states' => $states]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
