<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Supplier;
use App\Models\SupplierContact;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class SupplierController extends Controller
{

    public function getSuppliers()
    {
        try {
            $suppliers = Supplier::query()->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['suppliers' => $suppliers]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSupplierById($supplier_id)
    {
        try {
            $supplier = Address::query()->where('id', $supplier_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['supplier' => $supplier]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addSupplier(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            Supplier::query()->insert([
                'name' => $request->name
            ]);

            return response(['message' => __('Müşteri ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateSupplier(Request $request,$supplier_id){
        try {
            $request->validate([
                'name' => 'required',
            ]);

            Supplier::query()->where('id', $supplier_id)->update([
                'name' => $request->name
            ]);

            return response(['message' => __('Müşteri güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteSupplier($supplier_id){
        try {

            Supplier::query()->where('id',$supplier_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Müşteri silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getSupplierAddresses($supplier_id)
    {
        try {
            $addresses = Address::query()->where('type_id',$supplier_id)->where('type',2)->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['addresses' => $addresses]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSupplierAddressById($address_id)
    {
        try {
            $address = Address::query()->where('id',$address_id)->where('type',2)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['address' => $address]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addSupplierAddress(Request $request)
    {
        try {
            $request->validate([
                'supplier_id' => 'required',
                'name' => 'required',
                'address' => 'required',
                'city_id' => 'required',
                'country_id' => 'required',
                'phone' => 'required',
                'fax' => 'required',
            ]);
            $address_id = Address::query()->insertGetId([
                'type' => 2,
                'type_id' => $request->supplier_id,
                'name' => $request->name,
                'address' => $request->address,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'phone' => $request->phone,
                'fax' => $request->fax,
            ]);

            return response(['message' => __('Adres ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateSupplierAddress(Request $request,$address_id){
        try {
            $request->validate([
                'supplier_id' => 'required',
                'name' => 'required',
                'address' => 'required',
                'city_id' => 'required',
                'country_id' => 'required',
                'phone' => 'required',
                'fax' => 'required',
            ]);

            Address::query()->where('id', $address_id)->update([
                'type' => 2,
                'type_id' => $request->supplier_id,
                'name' => $request->name,
                'address' => $request->address,
                'country_id' => $request->country_id,
                'city_id' => $request->city_id,
                'phone' => $request->phone,
                'fax' => $request->fax,
            ]);

            return response(['message' => __('Adres güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteSupplierAddress($address_id){
        try {

            Address::query()->where('id',$address_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Adres silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getSupplierContacts($supplier_id)
    {
        try {
            $contacts = SupplierContact::query()->where('supplier_id',$supplier_id)->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['contacts' => $contacts]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSupplierContactById($contact_id)
    {
        try {
            $contact = SupplierContact::query()->where('id',$contact_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['contact' => $contact]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addSupplierContact(Request $request)
    {
        try {
            $request->validate([
                'supplier_id' => 'required',
                'title' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
            ]);
            SupplierContact::query()->insert([
                'supplier_id' => $request->supplier_id,
                'title' => $request->title,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            return response(['message' => __('Yetkili ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateSupplierContact(Request $request,$contact_id){
        try {
            $request->validate([
                'supplier_id' => 'required',
                'title' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
            ]);

            SupplierContact::query()->where('id', $contact_id)->update([
                'supplier_id' => $request->supplier_id,
                'title' => $request->title,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
            ]);

            return response(['message' => __('Yetkili güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteSupplierContact($contact_id){
        try {

            SupplierContact::query()->where('id',$contact_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Yetkili silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

}
