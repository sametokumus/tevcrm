<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\CreditCard;
use App\Models\CreditCardInstallment;
use App\Models\ProductRule;
use App\Models\User;
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

    public function getCreditCardById($credit_card_id,$cart_id){
        try {
            $credit_card = CreditCard::query()->where('id',$credit_card_id)->first();
            $credit_card_installments = CreditCardInstallment::query()->where('credit_card_id',$credit_card->id)->get();
            foreach ($credit_card_installments as $credit_card_installment){
                $cart = Cart::query()->where('cart_id',$cart_id)->first();
                $cart_details = CartDetail::query()->where('cart_id',$cart_id)->get();
                $user_discount = User::query()->where('id',$cart->user_id)->first()->user_discount;
                $total_price = 0;
                foreach ($cart_details as $cart_detail){
                    $product_rule = ProductRule::query()->where('variation_id',$cart_detail->variation_id)->first();
                    if ($product_rule->discount_rate == null || $product_rule->discount_rate == ''){
                        $price = $product_rule->regular_price / 100 * ((($user_discount - $credit_card_installment->discount) * -1) + 100);
                    }else{
                        $price = $product_rule->regular_price / 100 * ((($product_rule->discount_rate + $user_discount - $credit_card_installment->discount) * -1) + 100);
                    }
                    $total_price += ($price * $cart_detail->quantity);
                }
                $credit_card_installment['sub_total'] = $total_price;
                $credit_card_installment['tax'] = $total_price / 100 * $product_rule->tax_rate;
                $credit_card_installment['total'] = $total_price + ($total_price / 100 * $product_rule->tax_rate);
            }
            $credit_card['installment'] = $credit_card_installments;
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['credit_card' => $credit_card]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        }
    }

}
