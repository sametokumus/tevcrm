<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupons;
use App\Models\UsedCoupons;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class CouponController extends Controller
{
    public function addCoupon(Request $request)
    {
        try {
            $request->validate([
                'code' => 'required',
                'count_of_uses' => 'required',
                'count_of_used' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'type' => 'required',
                'discount' => 'required'
            ]);
            $coupon_id = Coupons::query()->insertGetId([
                'code' => $request->code,
                'count_of_uses' => $request->count_of_uses,
                'count_of_used' => $request->count_of_used,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'discount' => $request->discount,
                'user_id' => $request->user_id
            ]);
            return response(['message' => 'Kupon ekleme işlemi başarılı.', 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.', 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => 'Hatalı sorgu.', 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => 'Hatalı işlem.', 'status' => 'error-001','er' => $throwable->getMessage()]);
        }
    }
    public function updateCoupon(Request $request,$id){
        try {
            $request->validate([
                'code' => 'required',
                'count_of_uses' => 'required',
                'count_of_used' => 'required',
                'start_date' => 'required',
                'end_date' => 'required',
                'type' => 'required',
                'discount' => 'required'
            ]);

            $coupon = Coupons::query()->where('id',$id)->update([
                'code' => $request->code,
                'count_of_uses' => $request->count_of_uses,
                'count_of_used' => $request->count_of_used,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'type' => $request->type,
                'discount' => $request->discount,
                'user_id' => $request->user_id
            ]);

            return response(['message' => 'Kupon güncelleme işlemi başarılı.','status' => 'success','object' => ['coupon' => $coupon]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => 'Lütfen girdiğiniz bilgileri kontrol ediniz.','status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => 'Hatalı sorgu.','status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => 'Hatalı işlem.','status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
