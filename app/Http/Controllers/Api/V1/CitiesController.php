<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\City;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class CitiesController extends Controller
{
    public function getCities()
    {
        try {
            $cities = City::all();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function addCities(Request $request,$country_id)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            City::query()->insert([
                'country_id' => $country_id,
                'name' => $request->name
            ]);

            return response(['message' => 'Şehir ekleme işlemi başarılı.', 'status' => 'success']);

        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001']);
        }
    }

}
