<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\ProductVariation;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Nette\Schema\ValidationException;

class CartController extends Controller
{
    public function addCart(Request $request){
        try {
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);


            $cart = Cart::query()->where('user_id', $request->user_id)->where('active',1)->first();
            if(isset($cart)){
                $cart_id = $cart->cart_id;
            }else{
                $added_cart_id = Cart::query()->insertGetId([
                    'user_id' => $request->user_id,
                    'cart_id' => Uuid::uuid()
                ]);
                $cart_id = Cart::query()->where('id',$added_cart_id)->first()->cart_id;
            }
            CartDetail::query()->insert([
                'cart_id' => $cart_id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
            return response(['message' => 'Sepet ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','e'=> $throwable->getMessage()]);
        }
    }

    public function updateCartProduct(Request $request,$id){
        try {
            $cart_details = CartDetail::query()->where('id',$id)->first();

            return response(['message' => 'Sepet güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','e'=> $throwable->getMessage()]);
        }
    }

}
