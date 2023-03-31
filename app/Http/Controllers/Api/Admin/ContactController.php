<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Contact;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class ContactController extends Controller
{

    public function getContacts()
    {
        try {
            $contacts= Contact::query()->where('active', 1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['contacts' => $contacts]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getContactById($contact_id)
    {
        try {
            $contact= Contact::query()->where('id', $contact_id)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['contact' => $contact]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function updateContact(Request $request){
        try {
            $request->validate([
                'name' => 'required',
            ]);
            Contact::query()->where('id', $request->contact_id)->update([
                'name' => $request->name,
                'authorized_name' => $request->authorized_name,
                'address' => $request->address,
                'phone' => $request->phone,
                'email' => $request->email,
                'registration_no' => $request->fax,
                'short_code' => $request->country
            ]);
            if ($request->hasFile('logo')) {
                $rand = uniqid();
                $image = $request->file('logo');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/img/contact/'), $image_name);
                $image_path = "/img/contact/" . $image_name;
                Contact::query()->where('id',$request->contact_id)->update([
                    'logo' => $image_path
                ]);
            }
            if ($request->hasFile('footer')) {
                $rand = uniqid();
                $image = $request->file('footer');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/img/contact/'), $image_name);
                $image_path = "/img/contact/" . $image_name;
                Contact::query()->where('id',$request->contact_id)->update([
                    'footer' => $image_path
                ]);
            }
            if ($request->hasFile('signature')) {
                $rand = uniqid();
                $image = $request->file('signature');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/img/contact/'), $image_name);
                $image_path = "/img/contact/" . $image_name;
                Contact::query()->where('id',$request->contact_id)->update([
                    'signature' => $image_path
                ]);
            }

            return response(['message' => __('Firma güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001','ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getTraceAsString()]);
        }
    }

    public function deleteContact($contact_id){
        try {

            Contact::query()->where('id',$contact_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Firma silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
