<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\CreditCard;
use App\Models\CreditCardInstallment;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CreditCardController extends Controller
{
    public function getCreditCarts(){
        try {
            $credit_cart_installments = CreditCardInstallment::query()->where('active',1)->get();
            foreach ($credit_cart_installments as $credit_cart_installment){
                $credit_cart = CreditCard::query()->where('id',$credit_cart_installment->credit_cart_id);
                $credit_cart_installment['installment'] = $credit_cart;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['credit_cart_installments' => $credit_cart_installments]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

}
