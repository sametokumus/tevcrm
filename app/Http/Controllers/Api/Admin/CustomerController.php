<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Admin;
use App\Models\Appointment;
use App\Models\City;
use App\Models\Country;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\State;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class CustomerController extends Controller
{

    public function getCustomers()
    {
        try {
            $customers = Customer::query()->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['customers' => $customers]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCustomerById($customer_id)
    {
        try {
            $customer = Customer::query()->where('id', $customer_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['customer' => $customer]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addCustomer(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            Customer::query()->insert([
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

    public function updateCustomer(Request $request,$customer_id){
        try {
            $request->validate([
                'name' => 'required',
            ]);

            Customer::query()->where('id', $customer_id)->update([
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

    public function deleteCustomer($customer_id){
        try {

            Customer::query()->where('id',$customer_id)->update([
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

    public function getCustomerAddresses($customer_id)
    {
        try {
            $addresses = Address::query()->where('type_id',$customer_id)->where('type',1)->where('active',1)->get();

            foreach ($addresses as $address){
                $address['country'] = Country::query()->where('id', $address->country_id)->first()->name;
                $address['state'] = State::query()->where('id', $address->state_id)->first()->name;
                $address['city'] = City::query()->where('id', $address->city_id)->first()->name;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['addresses' => $addresses]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCustomerAddressById($address_id)
    {
        try {
            $address = Address::query()->where('id',$address_id)->where('type',1)->where('active',1)->first();
            $address['country'] = Country::query()->where('id', $address->country_id)->first()->name;
            $address['state'] = State::query()->where('id', $address->state_id)->first()->name;
            $address['city'] = City::query()->where('id', $address->city_id)->first()->name;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['address' => $address]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addCustomerAddress(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required',
                'name' => 'required',
                'address' => 'required',
                'city_id' => 'required',
                'state_id' => 'required',
                'country_id' => 'required',
                'phone' => 'required',
                'fax' => 'required',
            ]);
            $address_id = Address::query()->insertGetId([
                'type' => 1,
                'type_id' => $request->customer_id,
                'name' => $request->name,
                'address' => $request->address,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
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

    public function updateCustomerAddress(Request $request,$address_id){
        try {
            $request->validate([
                'customer_id' => 'required',
                'name' => 'required',
                'address' => 'required',
                'city_id' => 'required',
                'country_id' => 'required',
                'state_id' => 'required',
                'phone' => 'required',
                'fax' => 'required',
            ]);

            Address::query()->where('id', $address_id)->update([
                'type' => 1,
                'type_id' => $request->customer_id,
                'name' => $request->name,
                'address' => $request->address,
                'country_id' => $request->country_id,
                'state_id' => $request->state_id,
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

    public function deleteCustomerAddress($address_id){
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

    public function getCustomerContacts($customer_id)
    {
        try {
            $contacts = CustomerContact::query()->where('customer_id',$customer_id)->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['contacts' => $contacts]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCustomerContactById($contact_id)
    {
        try {
            $contact = CustomerContact::query()->where('id',$contact_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['contact' => $contact]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addCustomerContact(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required',
                'title' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
            ]);
            CustomerContact::query()->insert([
                'customer_id' => $request->customer_id,
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

    public function updateCustomerContact(Request $request,$contact_id){
        try {
            $request->validate([
                'customer_id' => 'required',
                'title' => 'required',
                'name' => 'required',
                'phone' => 'required',
                'email' => 'required',
            ]);

            CustomerContact::query()->where('id', $contact_id)->update([
                'customer_id' => $request->customer_id,
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

    public function deleteCustomerContact($contact_id){
        try {

            CustomerContact::query()->where('id',$contact_id)->update([
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

    public function getAppointments($customer_id)
    {
        try {
            $appointments = Appointment::query()->where('customer_id',$customer_id)->where('active',1)->get();
            foreach ($appointments as $appointment){
                $staff = Admin::query()->where('id', $appointment->staff_id)->first();
                $appointment['staff'] = $staff->name." ".$staff->surname;
                $appointment['address'] = Address::query()->where('id', $appointment->address_id)->first()->name;
                $appointment['contact'] = CustomerContact::query()->where('id', $appointment->contact_id)->first()->name;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['appointments' => $appointments]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getAppointmentById($appointment_id)
    {
        try {
            $appointment = Appointment::query()->where('id',$appointment_id)->where('active',1)->first();
            $staff = Admin::query()->where('id', $appointment->staff_id)->first();
            $appointment['staff'] = $staff->name." ".$staff->surname;
            $appointment['address'] = Address::query()->where('id', $appointment->address_id)->first()->name;
            $appointment['contact'] = CustomerContact::query()->where('id', $appointment->contact_id)->first()->name;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['appointment' => $appointment]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addAppointment(Request $request)
    {
        try {
            $request->validate([
                'customer_id' => 'required',
                'address_id' => 'required',
                'contact_id' => 'required',
                'staff_id' => 'required',
                'date' => 'required',
            ]);
            Appointment::query()->insert([
                'customer_id' => $request->customer_id,
                'address_id' => $request->address_id,
                'contact_id' => $request->contact_id,
                'staff_id' => $request->staff_id,
                'date' => $request->date
            ]);

            return response(['message' => __('Randevu oluşturma işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateAppointment(Request $request, $appointment_id)
    {
        try {
            $request->validate([
                'customer_id' => 'required',
                'address_id' => 'required',
                'contact_id' => 'required',
                'staff_id' => 'required',
                'date' => 'required',
            ]);
            Appointment::query()->where('id', $appointment_id)->update([
                'customer_id' => $request->customer_id,
                'address_id' => $request->address_id,
                'contact_id' => $request->contact_id,
                'staff_id' => $request->staff_id,
                'date' => $request->date,
                'notes' => $request->notes
            ]);

            return response(['message' => __('Randevu güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function deleteAppointment($appointment_id){
        try {

            Appointment::query()->where('id',$appointment_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Randevu silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

}
