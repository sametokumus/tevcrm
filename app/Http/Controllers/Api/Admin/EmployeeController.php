<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Employee;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class EmployeeController extends Controller
{
    public function getEmployees()
    {
        try {
            $employees = Employee::query()->where('active',1)->get();
            foreach ($employees as $employee){
                $employee['company'] = Company::query()->where('id', $employee->company_id)->where('active', 1)->first();
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['employees' => $employees]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getEmployeesByCompanyId($company_id)
    {
        try {
            $employees = Employee::query()->where('active',1)->where('company_id', $company_id)->get();
            foreach ($employees as $employee){
                $employee['company'] = Company::query()->where('id', $employee->company_id)->where('active', 1)->first();
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['employees' => $employees]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getEmployeeById($employee_id)
    {
        try {
            $employee = Employee::query()->where('id', $employee_id)->where('active',1)->first();
            $employee['company'] = Company::query()->where('id', $employee->company_id)->where('active', 1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['employee' => $employee]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addEmployee(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'company_id' => 'required',
            ]);
            $employee_id = Employee::query()->insertGetId([
                'company_id' => $request->company_id,
                'title' => $request->title,
                'name' => $request->name,
                'phone' => $request->phone,
                'mobile' => $request->mobile,
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

    public function updateEmployee(Request $request,$employee_id){
        try {
            $request->validate([
                'name' => 'required',
                'company_id' => 'required',
            ]);

            Employee::query()->where('id', $employee_id)->update([
                'company_id' => $request->company_id,
                'title' => $request->title,
                'name' => $request->name,
                'phone' => $request->phone,
                'mobile' => $request->mobile,
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

    public function deleteEmployee($employee_id){
        try {

            Employee::query()->where('id',$employee_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Temsilci silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
