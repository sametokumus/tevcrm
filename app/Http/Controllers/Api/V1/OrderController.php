<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartDetail;
use App\Models\City;
use App\Models\Country;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\ProductRule;
use App\Models\ProductVariation;
use Faker\Provider\Uuid;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Nette\Schema\ValidationException;

class OrderController extends Controller
{

    public function addOrder(Request $request)
    {
        try {
            $order_status = OrderStatus::query()->where('is_default',1)->first();
            $order_quid = Uuid::uuid();
            $shipping_id = $request->shipping_address_id;
            $billing_id = $request->billing_address_id;
            $shipping = Address::query()->where('id',$shipping_id)->first();
            $country = Country::query()->where('id',$shipping->country_id)->first();
            $city = City::query()->where('id',$shipping->city_id)->first();
            $shipping_address = $shipping->name. " - " .$shipping->surname. " - " .$shipping->address_1. " - " .$shipping->address_2. " - " .$shipping->postal_code. " - " .$shipping->phone. " - ". $country->name. " - " .$city->name;


            $billing = Address::query()->where('id',$billing_id)->first();
            $billing_country = Country::query()->where('id',$billing->country_id)->first();
            $billing_city = City::query()->where('id',$billing->city_id)->first();
            $billing_address = $billing->name. "-" .$billing->surname. " - " .$billing->address_1. " - " .$billing->address_2. " - " .$billing->postal_code. " - " .$billing->phone. " - ". $billing_country->name. " - " .$billing_city->name;

                $order_id = Order::query()->insertGetId([
                    'order_id' => $order_quid,
                    'user_id' => $request->user_id,
                    'carrier_id' => $request->carrier_id,
                    'cart_id' => $request->cart_id,
                    'status_id' => $order_status->id,
                    'shipping_address_id' => $request->shipping_address_id,
                    'billing_address_id' => $request->billing_address_id,
                    'shipping_address' => $shipping_address,
                    'billing_address' => $billing_address,
                    'comment' => $request->comment,
                    'total_discount' => $request->total_discount,
                    'total_discount_tax' => $request->total_discount_tax,
                    'total_shipping' => $request->total_shipping,
                    'total_shipping_tax' => $request->total_shipping_tax,
                    'total' => $request->total,
                    'total_tax' => $request->total_tax,
                    'is_partial' => $request->is_partial,
                    'is_paid' => $request->is_paid
                ]);

                Cart::query()->where('cart_id',$request->cart_id)->update([
                    'user_id' => $request->user_id
                ]);

                $carts = CartDetail::query()->where('cart_id',$request->cart_id)->get();
            foreach ($carts as $cart){
                $product = Product::query()->where('id',$cart->product_id)->first();
                $variation = ProductVariation::query()->where('id',$cart->variation_id)->first();
                $rule = ProductRule::query()->where('variation_id',$variation->id)->first();
                OrderProduct::query()->insert([
                    'order_id' => $order_quid,
                    'product_id' => $product,
                    'name' => $product->name,
                    'sku' => $variation->sku,
                    'price' => $cart->price,
                    'tax' => '',
                    'quantity' => $cart->quantity
                ]);
            }

            OrderStatusHistory::query()->insert([
                'order_id' => $order_quid,
                'status_id' => $order_status->id
            ]);

            return response(['message' => 'Sipariş ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'e' => $throwable->getMessage()]);
        }
    }
}
