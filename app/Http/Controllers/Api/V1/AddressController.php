<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Address;
use App\Models\CorporateAddresses;
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
                'phone' => 'required',
                'comment' => 'required',
                'type' => 'required',
                'tax_number' => 'required',
                'tax_office' => 'required',
                'company_name' => 'required',
            ]);
            $address_id = Address::query()->insertGetId([
                'user_id' => $user_id,
                'city_id' => $request->city_id,
                'name' => $request->name,
                'address_1' => $request->address_1,
                'address_2' => $request->address_2,
                'postal_code' => $request->postal_code,
                'phone' => $request->phone,
                'comment' => $request->comment,
                'type' => $request->type,
            ]);

            if ($request->type == 1) {
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
}
