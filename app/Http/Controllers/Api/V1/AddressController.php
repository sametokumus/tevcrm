<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Address;
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
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['addresses' => $addresses]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function addUserAddresses(Request $request,$user_id)
    {
        try {
            $request->validate([
                'city_id' => 'required|exists:cities,id',
                'name' => 'required',
                'address_1' => 'required',
                'address_2' => 'required',
                'postal_code' => 'required',
                'mobile_phone' => 'required',
                'active' => 'required'
            ]);
            Address::query()->insert([
                'user_id' => $user_id,
                'city_id' => $request->city_id,
                'name' => $request->name,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'mobile_phone' => $request->mobile_phone,
                'comment' => $request->comment,
                'active' => $request->active
            ]);

            return response(['message' => 'Adres ekleme işlemi başarılı.', 'status' => 'success']);

        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001']);
        }
    }
}
