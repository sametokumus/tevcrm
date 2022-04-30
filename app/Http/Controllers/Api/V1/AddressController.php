<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Address;
use App\Models\CorporateAddresses;
use App\Models\Country;
use App\Models\District;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class AddressController extends Controller
{

    public function getAddressesByUserId($user_id)
    {
        try {
            $addresses = Address::query()->where('user_id', $user_id)->where('active',1)->get();
            foreach ($addresses as $address){
                $address['country'] = Country::query()->where('id',$address->country_id)->first()->name;
                $address['city'] = City::query()->where('id',$address->city)->first()->name;
                $address['district'] = District::query()->where('id',$address->district)->first()->name;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['addresses' => $addresses]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function addUserAddresses(Request $request,$user_id)
    {
        try {
            $request->validate([
                'country_id' => 'required|exists:countries,id',
                'city_id' => 'required|exists:cities,id',
                'district_id' => 'required|exists:districts,id',
                'title' => 'required',
                'name' => 'required',
                'surname' => 'required',
                'address_1' => 'required',
                'phone' => 'required',
                'type' => 'required',
            ]);
            $address_id = Address::query()->insertGetId([
                'user_id' => $user_id,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'district_id' => $request->district_id,
                'title' => $request->title,
                'name' => $request->name,
                'surname' => $request->surname,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'comment' => $request->comment,
                'type' => $request->type,
            ]);

            if ($request->type == 2) {
                CorporateAddresses::query()->insert([
                    'address_id' => $address_id,
                    'tax_number' => $request->tax_number,
                    'tax_office' => $request->tax_office,
                    'company_name' => $request->company_name
                ]);
                return response(['message' => 'Kurumsal adres ekleme işlemi başarılı.', 'status' => 'success']);
            }
            return response(['message' => 'Bireysel adres ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateUserAddresses(Request $request,$address_id,$user_id){
        try {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'name' => 'required',
                'address_1' => 'required',
                'address_2' => 'required',
                'postal_code' => 'required',
                'phone' => 'required',
                'comment' => 'required',
                'type' => 'required',
                'tax_number' => 'required',
                'tax_office' => 'required',
                'company_name' => 'required',
            ]);

            $address = Address::query()->where('id',$address_id)->update([
                'user_id' => $user_id,
                'city_id' => $request->city_id,
                'name' => $request->name,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'comment' => $request->comment,
                'type' => $request->type
                ]);

            if ($request->type == 1) {
                CorporateAddresses::query()->where('id',$address_id)->update([
                    'tax_number' => $request->tax_number,
                    'tax_office' => $request->tax_office,
                    'company_name' => $request->company_name
                ]);

                return response(['message' => 'Kurumsal adres düzenleme işlemi başarılı.', 'status' => 'success']);
            }

            return response(['message' => 'Adres güncelleme işlemi başarılı.','status' => 'success','object' => ['address' => $address]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteUserAddresses($id){
        try {

            $address = Address::query()->where('id',$id)->update([
                'active' => 0,
            ]);
            return response(['message' => 'Adres silme işlemi başarılı.','status' => 'success','object' => ['address' => $address]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

}
