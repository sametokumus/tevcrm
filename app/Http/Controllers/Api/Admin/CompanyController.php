<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\CustomerHelper;
use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Admin;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyPoint;
use App\Models\Country;
use App\Models\District;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\PaymentTerm;
use App\Models\Product;
use App\Models\Quote;
use App\Models\Sale;
use App\Models\SaleOffer;
use App\Models\StaffPoint;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;

class CompanyController extends Controller
{
    public function getCustomers()
    {
        try {
            $customers = Company::query()->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['customers' => $customers]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCustomerById($customer_id)
    {
        try {
            $customer = Company::query()->where('id', $customer_id)->where('active',1)->first();

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
                'email' => 'required',
                'phone' => 'required',
            ]);
            $user = Auth::user();
            Company::query()->insertGetId([
                'name' => $request->name,
                'website' => $request->website,
                'email' => $request->email,
                'phone' => $request->phone,
                'fax' => $request->fax,
                'address' => $request->address,
                'country_id' => $request->country,
                'tax_office' => $request->tax_office,
                'tax_number' => $request->tax_number,
                'user_id' => $user->id
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

    public function updateCustomer(Request $request, $customer_id){
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]);
            $user = Auth::user();
            Company::query()->where('id', $customer_id)->update([
                'name' => $request->name,
                'website' => $request->website,
                'email' => $request->email,
                'phone' => $request->phone,
                'fax' => $request->fax,
                'address' => $request->address,
                'country_id' => $request->country,
                'tax_office' => $request->tax_office,
                'tax_number' => $request->tax_number,
                'user_id' => $user->id
            ]);

            return response(['message' => __('Müşteri güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001','ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getTraceAsString()]);
        }
    }

    public function deleteCustomer($customer_id){
        try {

            Company::query()->where('id',$customer_id)->update([
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
}
