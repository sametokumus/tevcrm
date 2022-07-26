<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Coupons;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function useCoupon(Request $request)
    {
        try {
            $coupon = Coupons::query()->where('active',1)->where('code', $request->code)->first();
            $date = date('Y-m-d h:i:s');

            if ($coupon->count_of_used >= $coupon->count_of_uses) {
                throw new \Exception('coupon-001');
            }else if ($date < $coupon->start_date || $date > $coupon->end_date){
                throw new \Exception('coupon-002');
            }else if ($coupon->user_id != $request->user_id || $coupon->user_id != 0){
                throw new \Exception('coupon-003');
            }else {

                return response(['message' => 'İşlem Başarılı.', 'status' => 'success', 'object' => ['coupon' => $coupon]]);
            }
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001']);
        } catch (\Exception $exception){
            if ($exception->getMessage() == 'coupon-001'){
                return response(['message' => 'Kuponunuz için tanımlanan kullanım hakkı dolmuştur.', 'status' => 'coupon-001']);
            }else if ($exception->getMessage() == 'coupon-002'){
                return response(['message' => 'Kuponunuz için tanımlanan kullanım süresi geçmiştir.', 'status' => 'coupon-002']);
            }else if ($exception->getMessage() == 'coupon-003'){
                return response(['message' => 'Hesabınıza tanımlı bir kupon bulunamadı.', 'status' => 'coupon-003']);
            }
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001']);
        }
    }
}
