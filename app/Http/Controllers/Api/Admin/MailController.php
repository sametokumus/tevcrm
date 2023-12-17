<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OfferRequestMail;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Contact;
use App\Models\EmailLayout;
use App\Models\Employee;
use App\Models\Note;
use App\Models\Offer;
use App\Models\Sale;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Nette\Schema\ValidationException;

class MailController extends Controller
{
    public function addLayout(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'text' => 'required',
            ]);
            EmailLayout::query()->insert([
                'name' => $request->name,
                'subject' => $request->subject,
                'text' => $request->text
            ]);

            return response(['message' => __('Şablon ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateLayout(Request $request, $layout_id){
        try {
            $request->validate([
                'name' => 'required',
                'text' => 'required',
            ]);

            EmailLayout::query()->where('id', $layout_id)->update([
                'name' => $request->name,
                'subject' => $request->subject,
                'text' => $request->text
            ]);

            return response(['message' => __('Şablon güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
    public function deleteLayout($layout_id){
        try {

            EmailLayout::query()->where('id',$layout_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Şablon silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
    public function getLayouts()
    {
        try {
            $layouts = EmailLayout::query()->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['layouts' => $layouts]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getLayoutById($layout_id)
    {
        try {
            $layout = EmailLayout::query()->where('id', $layout_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['layout' => $layout]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function sendMailOfferToSupplier(Request $request){
        try {
            $request->validate([
                'subject' => 'required',
                'text' => 'required',
            ]);

            $staff = Admin::query()->where('id', $request->staff_id)->first();
            foreach ($request->receivers as $receiver_id) {
                $receiver = Employee::query()->where('id', $receiver_id)->first();
                $offer = Offer::query()
                    ->where('offers.request_id',$request->request_id)
                    ->where('offers.supplier_id',$receiver->company_id)
                    ->where('offers.active',1)
                    ->first();
                $sale = Sale::query()->where('request_id', $request->request_id)->first();
                $owner = Contact::query()->where('id', $sale->owner_id)->first();

                $attachment_name = $owner->short_code.'-RFQ-'.$sale->id.'.pdf';
                $attachment_url = $offer->rfq_url;

                Mail::to($receiver->email)->send(new OfferRequestMail($receiver->email, $staff->email, $request->subject, $request->text, $attachment_name, $attachment_url));

            }

            return response(['message' => __('Mailleriniz tedarikçilerinize iletildi.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
