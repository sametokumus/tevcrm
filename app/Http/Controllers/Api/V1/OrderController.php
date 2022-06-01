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
use App\Models\ProductImage;
use App\Models\ProductRule;
use App\Models\ProductVariation;
use App\Models\User;
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
            $cart = Cart::query()->where('cart_id', $request->cart_id)->where('active', 1)->first();

            if(isset($cart)) {

                $order_status = OrderStatus::query()->where('is_default', 1)->first();
                $order_quid = Uuid::uuid();
                $shipping_id = $request->shipping_address_id;
                $billing_id = $request->billing_address_id;
                $shipping = Address::query()->where('id', $shipping_id)->first();
                $country = Country::query()->where('id', $shipping->country_id)->first();
                $city = City::query()->where('id', $shipping->city_id)->first();
                $shipping_address = $shipping->name . " - " . $shipping->surname . " - " . $shipping->address_1 . " - " . $shipping->address_2 . " - " . $shipping->postal_code . " - " . $shipping->phone . " - " . $country->name . " - " . $city->name;


                $billing = Address::query()->where('id', $billing_id)->first();
                $billing_country = Country::query()->where('id', $billing->country_id)->first();
                $billing_city = City::query()->where('id', $billing->city_id)->first();
                $billing_address = $billing->name . "-" . $billing->surname . " - " . $billing->address_1 . " - " . $billing->address_2 . " - " . $billing->postal_code . " - " . $billing->phone . " - " . $billing_country->name . " - " . $billing_city->name;

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
                    'shipping_type' => $request->delivery_type,
                    'shipping_price' => $request->shipping_price,
                    'subtotal' => $request->subtotal,
                    'total' => $request->total,
                    'is_partial' => $request->is_partial,
                    'is_paid' => $request->is_paid
                ]);

                Cart::query()->where('cart_id', $request->cart_id)->update([
                    'user_id' => $request->user_id,
                    'is_order' => 1,
                    'active' => 0
                ]);
                $user_discount = User::query()->where('id', $request->user_id)->first()->user_discount;
                $carts = CartDetail::query()->where('cart_id', $request->cart_id)->get();
                foreach ($carts as $cart) {
                    $product = Product::query()->where('id', $cart->product_id)->first();
                    $variation = ProductVariation::query()->where('id', $cart->variation_id)->first();
                    $rule = ProductRule::query()->where('variation_id', $variation->id)->first();
                    if ($rule->discounted_price == null || $rule->discount_rate == 0){
                        $price = $rule->regular_price - ($rule->regular_price / 100 * $user_discount);
                        $tax = $price / 100 * $rule->tax_rate;
                        $total = ($price + $tax) * $request->quantity;
                    }else{
                        $price = $rule->regular_price - ($rule->regular_price / 100 * ($user_discount + $rule->discount_rate));
                        $tax = $price / 100 * $rule->tax_rate;
                        $total = ($price + $tax) * $request->quantity;
                    }
                    OrderProduct::query()->insert([
                        'order_id' => $order_quid,
                        'product_id' => $product->id,
                        'variation_id' => $variation->id,
                        'name' => $product->name,
                        'sku' => $variation->sku,
                        'regular_price' => $rule->regular_price,
                        'regular_tax' => $rule->regular_tax,
                        'discounted_price' => $rule->discounted_price,
                        'discounted_tax' => $rule->discounted_tax,
                        'discount_rate' => $rule->discount_rate,
                        'tax_rate' => $rule->tax_rate,
                        'user_discount' => $user_discount,
                        'quantity' => $cart->quantity,
                        'total' => $total
                    ]);
                }

                OrderStatusHistory::query()->insert([
                    'order_id' => $order_quid,
                    'status_id' => $order_status->id
                ]);

                return response(['message' => 'Sipariş ekleme işlemi başarılı.', 'status' => 'success']);
            }else{
                return response(['message' => 'Sepet Bulunamadı.', 'status' => 'cart-001']);
            }

        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'e' => $throwable->getMessage()]);
        }
    }

    public function getOrdersByUserId($user_id){
        try {
            $orders = Order::query()->where('user_id',$user_id)->get(['order_id', 'created_at as order_date', 'total', 'status_id']);
            foreach ($orders as $order){
                $product_count = OrderProduct::query()->where('order_id', $order->order_id)->get()->count();
                $product = OrderProduct::query()->where('order_id', $order->order_id)->first();
                $product_image = ProductImage::query()->where('variation_id', $product->variation_id)->first()->image;
                $status_name = OrderStatus::query()->where('id', $order->status_id)->first()->name;
                $order['product_count'] = $product_count;
                $order['product_image'] = $product_image;
                $order['payment_type'] = "Kredi Kartı";
                $order['status_name'] = $status_name;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['orders' => $orders]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }

    public function getOrderById($order_id){
        try {
            $orders = Order::query()->where('order_id',$order_id)->get();
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['orders' => $orders]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
