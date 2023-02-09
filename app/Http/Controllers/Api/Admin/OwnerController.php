<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequestProduct;
use App\Models\OwnerBankInfo;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StatusHistory;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class OwnerController extends Controller
{

    public function getBankInfos()
    {
        try {
            $bank_infos = OwnerBankInfo::query()->where('active', 1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['bank_infos' => $bank_infos]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getBankInfoById($info_id)
    {
        try {
            $bank_info = OwnerBankInfo::query()->where('id', $info_id)->where('active', 1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['bank_info' => $bank_info]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addBankInfo(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'detail' => 'required',
            ]);
            OwnerBankInfo::query()->insert([
                'name' => $request->name,
                'detail' => $request->detail
            ]);

            return response(['message' => __('Banka bilgisi ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateBankInfo(Request $request)
    {
        try {
            $request->validate([
                'info_id' => 'required',
                'detail' => 'required',
                'name' => 'required',
            ]);
            OwnerBankInfo::query()->where('id', $request->info_id)->update([
                'name' => $request->name,
                'detail' => $request->detail
            ]);

            return response(['message' => __('Banka bilgisi güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function deleteBankInfo($info_id)
    {
        try {
            OwnerBankInfo::query()->where('id', $info_id)->update([
                'active' => 0
            ]);

            return response(['message' => __('Banka bilgisi silme işlemi başarılı.'), 'status' => 'success']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

}
