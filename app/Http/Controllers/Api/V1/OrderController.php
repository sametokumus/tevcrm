<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatusHistory;
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
            $order = Order::query()->where('user_id',$request->user_id)->first();
            if ($order){
                $order_id = $order->order_id;
            }else{
                $add_order_id = Order::query()->insertGetId([
                    'order_id' => Uuid::uuid(),
                    'user_id' => $request->user_id,
                    'carrier_id' => $request->carrier_id,
                    'cart_id' => $request->cart_id,
                    'shipping_address_id' => $request->shipping_address_id,
                    'billing_address_id' => $request->billing_address_id,
                    'status_id' => $request->status_id,
                    'shipping_address' => $request->shipping_address,
                    'billing_address' => $request->billing_address,
                    'comment' => $request->comment,
                    'shipping_number' => $request->shipping_number,
                    'invoice_number' => $request->invoice_number,
                    'invoice_date' => $request->invoice_date,
                    'delivery_date' => $request->delivery_date,
                    'total_discount' => $request->total_discount,
                    'total_discount_tax' => $request->total_discount_tax,
                    'total_shipping' => $request->total_shipping,
                    'total_shipping_tax' => $request->total_shipping_tax,
                    'total' => $request->total,
                    'total_tax' => $request->total_tax,
                    'is_partial' => $request->is_partial,
                    'is_paid' => $request->is_paid
                ]);

                $order_id = Order::query()->where('order_id',$add_order_id)->first()->order_id;
            }

            $order_products = json_decode(json_encode($request->order_products));
            foreach ($order_products as $order_product){
                OrderProduct::query()->insert([
                    'order_id' => $order_id,
                    'product_id' => $order_product->product_id,
                    'name' => $order_product->name,
                    'sku' => $order_product->sku,
                    'price' => $order_product->price,
                    'tax' => $order_product->tax,
                    'quantity' => $order_product->quantity
                ]);
            }
            $order_status_id = Order::query()->where('order_id',$order_id)->first()->status_id;

            OrderStatusHistory::query()->insert([
                'order_id' => $order_id,
                'status_id' => $order_status_id
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
