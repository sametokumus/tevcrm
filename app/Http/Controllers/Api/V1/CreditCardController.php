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
            $credit_cards = CreditCard::query()->where('active',1)->get();
            foreach ($credit_cards as $credit_card){
                $credit_card_installment = CreditCardInstallment::query()->where('credit_card_id',$credit_card->id)->get();
                $credit_card['installment'] = $credit_card_installment;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['credit_card' => $credit_card]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

}
