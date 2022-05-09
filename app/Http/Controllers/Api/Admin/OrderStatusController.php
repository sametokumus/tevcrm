<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderStatus;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class OrderStatusController extends Controller
{
    public function addOrderStatus(Request $request){
        try {
            $order_status = OrderStatus::query()->where('name',$request->name)->first();
            if ($order_status){
                return response(['message' => 'Böyle bir kayıt veritabanında bulunmakta','status' => 'query-002']);
            }
            OrderStatus::query()->insert([
                'name' => $request->name
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
