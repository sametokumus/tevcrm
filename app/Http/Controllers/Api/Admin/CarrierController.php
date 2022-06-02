<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carrier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class CarrierController extends Controller
{
    public function addCarrier(Request $request){
        try {
            $carrier_id = Carrier::query()->insertGetId([
                'name' => $request->name,
                'price' => $request->price,
                'delivery_text' => $request->delivery_text,
            ]);
            if ($request->hasFile('logo')) {
                $rand = uniqid();
                $image = $request->file('logo');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/Carrier/'), $image_name);
                $image_path = "/images/Carrier/" . $image_name;

                Carrier::query()->where('id', $carrier_id)->update([
                    'logo' => $image_path,
                ]);
            }
            return response(['message' => 'Kargo firması ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'e' => $throwable->getMessage()]);
        }
    }

    public function updateCarrier(Request $request,$id){
        try {
            Carrier::query()->where('id',$id)->update([
                'name' => $request->name,
                'price' => $request->price,
                'delivery_text' => $request->delivery_text,
            ]);
            if ($request->hasFile('logo')) {
                $rand = uniqid();
                $image = $request->file('logo');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/images/Carrier/'), $image_name);
                $image_path = "/images/Carrier/" . $image_name;

                Carrier::query()->where('id',$id)->update([
                    'logo' => $image_path,
                ]);
            }
            return response(['message' => 'Kargo firması güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'e' => $throwable->getMessage()]);
        }
    }

    public function deleteCarrier($id)
    {
        try {
            Carrier::query()->where('id',$id)->update([
                'active' =>0
            ]);
            return response(['message' => 'Kargo silme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }

    }

    public function getCarriers(){
        try {
           $carriers = Carrier::query()->where('active',1)->get();
            return response(['message' => 'Kargo silme işlemi başarılı.', 'status' => 'success','object' => ['carriers' => $carriers]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'er' => $throwable->getMessage()]);
        }
    }

}
