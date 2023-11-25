<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\CustomerHelper;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\PaymentTerm;
use App\Models\Quote;
use App\Models\Sale;
use App\Models\SaleOffer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;

class CompanyController extends Controller
{
    public function getCompanies()
    {
        try {
            $companies = Company::query()->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['companies' => $companies]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getPotentialCustomers()
    {
        try {
            $companies = Company::query()->where('active',1)->where('is_potential_customer', 1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['companies' => $companies]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCustomers()
    {
        try {
            $companies = Company::query()->where('active',1)->where('is_customer', 1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['companies' => $companies]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getSuppliers()
    {
        try {
            $companies = Company::query()->where('active',1)->where('is_supplier', 1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['companies' => $companies]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCompanyById($company_id)
    {
        try {
            $company = Company::query()->where('id', $company_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['company' => $company]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addCompany(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]);
            $company_id = Company::query()->insertGetId([
                'name' => $request->name,
                'website' => $request->website,
                'email' => $request->email,
                'phone' => $request->phone,
                'fax' => $request->fax,
                'address' => $request->address,
                'country_id' => $request->country,
                'tax_office' => $request->tax_office,
                'tax_number' => $request->tax_number,
                'is_potential_customer' => $request->is_potential_customer,
                'is_customer' => $request->is_customer,
                'is_supplier' => $request->is_supplier,
                'linkedin' => $request->linkedin,
                'skype' => $request->skype,
                'online' => $request->online,
                'registration_number' => $request->registration_number,
                'payment_term' => $request->payment_term
            ]);
            if ($request->hasFile('logo')) {
                $rand = uniqid();
                $image = $request->file('logo');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/img/company/'), $image_name);
                $image_path = "/img/company/" . $image_name;
                Company::query()->where('id',$company_id)->update([
                    'logo' => $image_path
                ]);
            }

            return response(['message' => __('Firma ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateCompany(Request $request,$company_id){
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required',
                'phone' => 'required',
            ]);
            Company::query()->where('id', $company_id)->update([
                'name' => $request->name,
                'website' => $request->website,
                'email' => $request->email,
                'phone' => $request->phone,
                'fax' => $request->fax,
                'address' => $request->address,
                'country_id' => $request->country,
                'tax_office' => $request->tax_office,
                'tax_number' => $request->tax_number,
                'is_potential_customer' => $request->is_potential_customer,
                'is_customer' => $request->is_customer,
                'is_supplier' => $request->is_supplier,
                'linkedin' => $request->linkedin,
                'skype' => $request->skype,
                'online' => $request->online,
                'registration_number' => $request->registration_number,
                'payment_term' => $request->payment_term
            ]);
            if ($request->hasFile('logo')) {
                $rand = uniqid();
                $image = $request->file('logo');
                $image_name = $rand . "-" . $image->getClientOriginalName();
                $image->move(public_path('/img/company/'), $image_name);
                $image_path = "/img/company/" . $image_name;
                Company::query()->where('id',$company_id)->update([
                    'logo' => $image_path
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

    public function deleteCompany($company_id){
        try {

            Company::query()->where('id',$company_id)->update([
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

    public function getBestCustomer(){
        try {

            $all_companies = Company::query()->where('active', 1)->get();
            $companies = array();

            foreach ($all_companies as $company){
                $data = array();
                $data['company'] = $company;

                //Sipariş/Teklif Oranı
                $request_count = OfferRequest::query()->where('company_id', $company->id)
                    ->whereBetween('created_at', [now()->subDays(90), now()])
                    ->count();

                $sale_count = Sale::query()->where('customer_id', $company->id)
                    ->whereBetween('created_at', [now()->subDays(90), now()])
                    ->count();




                //Toplam İş Hacmi ve karlılık
                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('s.customer_id', $company->id)
                    ->where('s.active', '=', 1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                    ->whereBetween('s.created_at', [now()->subDays(90), now()])
                    ->get();

                $sale = array();
                $usd_price = 0;
                $total_profit_rate = 0;
                $total_item_count = 0;
                $total_payment_point = 0;
                $total_payment_count = 0;

                foreach ($sale_items as $item){

                    //satış
                    $total_price = $item->grand_total;
                    if ($item->grand_total_with_shipping != null){
                        $total_price = $item->grand_total_with_shipping;
                    }

                    if ($item->currency == 'TRY'){
                        $usd_price += $total_price / $item->usd_rate;
                    }else if ($item->currency == 'USD'){
                        $usd_price += $total_price;
                    }else if ($item->currency == 'EUR'){
                        $usd_price += $total_price / $item->usd_rate * $item->eur_rate;
                    }

                    //satın alma
                    $sale_offers = SaleOffer::query()->where('sale_id', $item->sale_id)->where('active', 1)->get();
                    $total_offer_price = 0;
                    //tedarik gideri
                    foreach ($sale_offers as $sale_offer){
                        $offer_product = OfferProduct::query()->where('id', $sale_offer->offer_product_id)->where('active', 1)->first();
                        $total_offer_price += $offer_product->converted_price;
                    }
                    if ($total_offer_price != 0) {
                        $total_expense = $total_offer_price;
                    }else{
                        $total_expense = 0;
                    }
                    //ek giderler
                    $expenses = Expense::query()->where('sale_id', $item->sale_id)->where('active', 1)->get();
                    foreach ($expenses as $expense){
                        if ($expense->currency == $item->currency){
                            $total_expense += $expense->price;
                            $expense['converted_price'] = $expense->price;
                        }else{
                            if ($expense->currency == 'TRY') {
                                $sc = strtolower($item->currency);
                                $expense_price = $expense->price / $item->{$sc.'_rate'};
                            }else{
                                if ($item->currency == 'TRY') {
                                    $ec = strtolower($expense->currency);
                                    $expense_price = $expense->price * $item->{$ec.'_rate'};
                                }else{
                                    $ec = strtolower($expense->currency);
                                    $sc = strtolower($item->currency);
                                    if ($item->{$sc.'_rate'} != 0) {
                                        $expense_price = $expense->price * $item->{$ec . '_rate'} / $sale->{$sc . '_rate'};
                                    }else{
                                        $expense_price = 0;
                                    }
                                }
                            }
                            $total_expense += $expense_price;
                        }
                    }

                    //kar oranı
                    if ($total_expense != 0) {
                        $profit_rate = 100 * ($total_price - $total_expense) / $total_expense;
                    }else{
                        $profit_rate = 0;
                    }

                    $total_profit_rate += $profit_rate;
                    $total_item_count++;


                    //ödeme yöntemi
                    $quote = Quote::query()->where('sale_id', $item->sale_id)->where('active', 1)->first();
                    if ($quote){
                        $pt = PaymentTerm::query()->where('id', $quote->payment_term)->first();
                        if ($pt){
                            $total_payment_point += CustomerHelper::get_sale_payment_point($pt->advance, $pt->expiry);
                            $total_payment_count++;
                        }
                    }

                }



                $data['c1'] = CustomerHelper::get_company_point($company->id);

                $data['request_count'] = $request_count;
                $data['sale_count'] = $sale_count;
                $data['c2'] = CustomerHelper::get_request_and_sales_rate($request_count, $sale_count);

                $data['total_profit_rate'] = $total_profit_rate;
                $data['total_item_count'] = $total_item_count;
                $data['c3'] = CustomerHelper::get_sales_profit_rate($total_profit_rate, $total_item_count);

                $data['usd_price'] = $usd_price;
                $data['c4'] = CustomerHelper::get_sales_total_rate($usd_price);

                $data['total_payment_point'] = $total_payment_point;
                $data['total_payment_count'] = $total_payment_count;
                $data['c5'] = CustomerHelper::get_sales_payment_point($total_payment_point, $total_payment_count);



                array_push($companies, $data);
            }

            usort($companies, function ($a, $b) {
                return $b['c4'] <=> $a['c4'];
            });


            return response(['message' => __('İşlem başarılı.'), 'status' => 'success', 'object' => ['companies' => $companies]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
