<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\StatusHistoryHelper;
use App\Http\Controllers\Controller;
use App\Models\Accounting;
use App\Models\Offer;
use App\Models\OfferProduct;
use App\Models\OfferRequestProduct;
use App\Models\Sale;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class OfferController extends Controller
{
    public function addOffer(Request $request)
    {
        try {
            $request->validate([
                'customer' => 'required'
            ]);
            $offer_id = Offer::query()->insertGetId([
                'customer_id' => $request->customer,
                'employee_id' => $request->employee,
                'manager_id' => $request->manager,
                'lab_manager_id' => $request->lab_manager,
                'description' => $request->description
            ]);
            StatusHistoryHelper::addStatusHistory($offer_id, 1);

            Accounting::query()->insert([
                'offer_id' => $offer_id
            ]);

            return response(['message' => __('Teklif ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['offer_id' => $offer_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }
}
