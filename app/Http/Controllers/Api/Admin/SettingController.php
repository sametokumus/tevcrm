<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use App\Models\DeliveryTerm;
use App\Models\PaymentTerm;
use App\Models\PaymentType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class SettingController extends Controller
{
    //Payment Terms
    public function getPaymentTerms()
    {
        try {
            $payment_terms = PaymentTerm::query()->where('active',1)->get();
            foreach ($payment_terms as $payment_term){
                $payment_term['payment_type_name'] = '';
                if ($payment_term['payment_type_id'] != 0){
                    $payment_term['payment_type_name'] = PaymentType::query()->where('id', $payment_term['payment_type_id'])->first()->name;
                }
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['payment_terms' => $payment_terms]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getPaymentTermById($term_id)
    {
        try {
            $payment_term = PaymentTerm::query()->where('id', $term_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['payment_term' => $payment_term]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addPaymentTerm(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            $payment_term_id = PaymentTerm::query()->insertGetId([
                'name' => $request->name,
            ]);

            return response(['message' => __('Payment term ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['payment_term_id' => $payment_term_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updatePaymentTerm(Request $request,$term_id){
        try {
            $request->validate([
                'name' => 'required',
            ]);

            PaymentTerm::query()->where('id', $term_id)->update([
                'name' => $request->name,
            ]);

            return response(['message' => __('Payment term güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deletePaymentTerm($term_id){
        try {

            PaymentTerm::query()->where('id',$term_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Payment term silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }


    //Delivery Term
    public function getDeliveryTerms()
    {
        try {
            $delivery_terms = DeliveryTerm::query()->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['delivery_terms' => $delivery_terms]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getDeliveryTermById($term_id)
    {
        try {
            $delivery_term = DeliveryTerm::query()->where('id', $term_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['delivery_term' => $delivery_term]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addDeliveryTerm(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            $delivery_term_id = DeliveryTerm::query()->insertGetId([
                'name' => $request->name,
            ]);

            return response(['message' => __('Delivery term ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['delivery_term_id' => $delivery_term_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateDeliveryTerm(Request $request,$term_id){
        try {
            $request->validate([
                'name' => 'required',
            ]);

            DeliveryTerm::query()->where('id', $term_id)->update([
                'name' => $request->name,
            ]);

            return response(['message' => __('Delivery term güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteDeliveryTerm($term_id){
        try {

            DeliveryTerm::query()->where('id',$term_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Delivery term silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
