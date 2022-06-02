<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\OrderStatus;
use App\Models\OrderStatusHistory;
use App\Models\PaymentType;
use App\Models\ProductImage;
use App\Models\ShippingType;
use App\Models\UserProfile;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class OrderController extends Controller
{
    public function updateOrder(Request $request, $id)
    {
        try {
            $order = Order::query()->where('order_id', $id)->first();
            Order::query()->where('order_id', $id)->update([
                'order_id' => $request->order_id,
                'user_id' => $request->user_id,
                'carrier_id' => $request->carrier_id,
                'cart_id' => $request->cart_id,
                'status_id' => $request->status_id,
                'shipping_address_id' => $request->shipping_address_id,
                'billing_address_id' => $request->billing_address_id,
                'shipping_address' => $request->shipping_address,
                'billing_address' => $request->billing_address,
                'comment' => $request->comment,
                'shipping_number' => $request->shipping_number,
                'shipping_date' => $request->shipping_date,
                'invoice_number' => $request->invoice_number,
                'invoice_date' => $request->invoice_date,
                'total_discount' => $request->total_discount,
                'total_discount_tax' => $request->total_discount_tax,
                'total_shipping' => $request->total_shipping,
                'total_shipping_tax' => $request->total_shipping_tax,
                'total' => $request->total,
                'total_tax' => $request->total_tax,
                'is_partial' => $request->is_partial,
                'is_paid' => $request->is_paid
            ]);
            if ($order->status_id != $request->status_id) {
                OrderStatusHistory::query()->insert([
                    'status_id' => $request->status_id,
                    'order_id' => $order->order_id
                ]);
            }
            return response(['message' => 'Sipariş güncelleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001', 'e' => $throwable->getMessage()]);
        }
    }

    public function getOnGoingOrders(){
        try {
            $orders = Order::query()
                ->leftJoin('order_statuses','order_statuses.id','=','orders.status_id')
                ->where('order_statuses.run_on',1)
                ->get(['orders.id', 'orders.order_id', 'orders.created_at as order_date', 'orders.total', 'orders.status_id',
                    'orders.shipping_type','orders.user_id','orders.payment_type'
                ]);
            foreach ($orders as $order){
                $product_count = OrderProduct::query()->where('order_id', $order->order_id)->get()->count();
                $product = OrderProduct::query()->where('order_id', $order->order_id)->first();
                $product_image = ProductImage::query()->where('variation_id', $product->variation_id)->first()->image;
                $status_name = OrderStatus::query()->where('id', $order->status_id)->first()->name;
                $shipping_type = ShippingType::query()->where('id',$order->shipping_type)->first()->name;
                $user_profile = UserProfile::query()->where('user_id',$order->user_id)->first(['name','surname']);
                $payment_type = PaymentType::query()->where('id',$order->payment_type)->first()->name;

                $order['product_count'] = $product_count;
                $order['product_image'] = $product_image;
                $order['payment_type'] = $order->payment_type;
                $order['status_name'] = $status_name;
                $order['shipping_number'] = $order->shipping_number;
                $order['shipping_type_name'] = $shipping_type;
                $order['user_profile'] = $user_profile;
                $order['payment_type_name'] = $payment_type;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['orders' => $orders]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','â' => $queryException->getMessage()]);
        }
    }

    public function getCompletedOrders(){
        try {
            $orders = Order::query()
                ->leftJoin('order_statuses','order_statuses.id','=','orders.status_id')
                ->where('order_statuses.run_on',0)
                ->get(['orders.id', 'orders.order_id', 'orders.created_at as order_date', 'orders.total', 'orders.status_id',
                    'orders.shipping_type','orders.user_id','orders.payment_type'
                ]);
            foreach ($orders as $order){
                $product_count = OrderProduct::query()->where('order_id', $order->order_id)->get()->count();
                $product = OrderProduct::query()->where('order_id', $order->order_id)->first();
                $product_image = ProductImage::query()->where('variation_id', $product->variation_id)->first()->image;
                $status_name = OrderStatus::query()->where('id', $order->status_id)->first()->name;
                $shipping_type = ShippingType::query()->where('id',$order->shipping_type)->first()->name;
                $user_profile = UserProfile::query()->where('user_id',$order->user_id)->first(['name','surname']);
                $payment_type = PaymentType::query()->where('id',$order->payment_type)->first()->name;

                $order['product_count'] = $product_count;
                $order['product_image'] = $product_image;
                $order['payment_type'] = $order->payment_type;
                $order['status_name'] = $status_name;
                $order['shipping_number'] = $order->shipping_number;
                $order['shipping_type_name'] = $shipping_type;
                $order['user_profile'] = $user_profile;
                $order['payment_type_name'] = $payment_type;
            }
            return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['orders' => $orders]]);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        }
    }
}
