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
                'payment_term' => $request->payment_term,
                'user_id' => $request->user_id
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

    public function getAddressesByCompanyId($company_id)
    {
        try {
            $addresses = Address::query()->where('company_id', $company_id)->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['addresses' => $addresses]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getAddressById($address_id)
    {
        try {
            $address = Address::query()->where('id', $address_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['address' => $address]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addCompanyAddress(Request $request)
    {
        try {
            $request->validate([
                'company_id' => 'required',
                'name' => 'required',
                'address' => 'required',
            ]);
            Address::query()->insertGetId([
                'company_id' => $request->company_id,
                'name' => $request->name,
                'address' => $request->address,
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

    public function updateCompanyAddress(Request $request){
        try {
            $request->validate([
                'name' => 'required',
                'address' => 'required',
            ]);

            $address = Address::query()->where('id',$request->address_id)->update([
                'name' => $request->name,
                'address' => $request->address
            ]);

            return response(['message' => __('Adres güncelleme işlemi başarılı.'),'status' => 'success','object' => ['address' => $address]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteCompanyAddress($address_id){
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

    public function getBestCustomer(){
        try {

            $all_companies = Company::query()->where('active', 1)->get();
            $companies = array();

            foreach ($all_companies as $company){
                $data = array();
                $data['company'] = $company;

                //Sipariş/Teklif Oranı - c2
                $request_count = OfferRequest::query()->where('company_id', $company->id)
                    ->whereBetween('created_at', [now()->subDays(90), now()])
                    ->count();

                $sale_count = Sale::query()->where('customer_id', $company->id)
                    ->whereBetween('created_at', [now()->subDays(90), now()])
                    ->count();




                //Toplam İş Hacmi ve karlılık - c4
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

                    //satış - c4
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

                    //c3
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


                    //ödeme yöntemi - c5
                    $quote = Quote::query()->where('sale_id', $item->sale_id)->where('active', 1)->first();
                    if ($quote){
                        $pt = PaymentTerm::query()->where('id', $quote->payment_term)->first();
                        if ($pt){
                            $total_payment_point += CustomerHelper::get_sale_payment_point($pt->advance, $pt->expiry);
                            $total_payment_count++;
                        }
                    }

                }



                $c1 = CustomerHelper::get_company_point($company->id);
                $data['c1'] = $c1;

                $data['request_count'] = $request_count;
                $data['sale_count'] = $sale_count;
                $c2 = CustomerHelper::get_request_and_sales_rate($request_count, $sale_count);
                $data['c2'] = $c2;

                $data['total_profit_rate'] = $total_profit_rate;
                $data['total_item_count'] = $total_item_count;
                $c3 = CustomerHelper::get_sales_profit_rate($total_profit_rate, $total_item_count);
                $data['c3'] = $c3;

                $data['usd_price'] = $usd_price;
                $c4 = CustomerHelper::get_sales_total_rate($usd_price);
                $data['c4'] = $c4;

                $data['total_payment_point'] = $total_payment_point;
                $data['total_payment_count'] = $total_payment_count;
                $c5 = CustomerHelper::get_sales_payment_point($total_payment_point, $total_payment_count);
                $data['c5'] = $c5;

                $c1_rate = CustomerHelper::get_point_rate($c1, 25);
                $c2_rate = CustomerHelper::get_point_rate($c2, 22);
                $c3_rate = CustomerHelper::get_point_rate($c3, 20);
                $c4_rate = CustomerHelper::get_point_rate($c4, 18);
                $c5_rate = CustomerHelper::get_point_rate($c5, 15);

                $data['c1_rate'] = $c1_rate;
                $data['c2_rate'] = $c2_rate;
                $data['c3_rate'] = $c3_rate;
                $data['c4_rate'] = $c4_rate;
                $data['c5_rate'] = $c5_rate;


                $company_rate = $c1_rate + $c2_rate + $c3_rate + $c4_rate + $c5_rate;
                $data['company_rate'] = number_format($company_rate, 2, '.', '');


                array_push($companies, $data);
            }

            usort($companies, function ($a, $b) {
                return $b['company_rate'] <=> $a['company_rate'];
            });

            $return_companies = array_slice($companies, 0, 10);


            return response(['message' => __('İşlem başarılı.'), 'status' => 'success', 'object' => ['companies' => $return_companies]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getCompaniesByStaffId($staff_id){
        try {

            $companies = Company::query()->where('active', 1)->where('user_id', $staff_id)->get();

            return response(['message' => __('İşlem başarılı.'), 'status' => 'success', 'object' => ['companies' => $companies]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getSaledProductsByCompanyId($company_id)
    {
        try {
            $products = SaleOffer::query()
                ->leftJoin('sales', 'sales.sale_id', '=', 'sale_offers.sale_id')
                ->leftJoin('statuses', 'statuses.id', '=', 'sales.status_id')
                ->selectRaw('product_id, sum(offer_quantity) as total_quantity')
                ->where('sale_offers.active',1)
                ->where('sales.active',1)
                ->where('sales.customer_id', $company_id)
                ->whereIn('statuses.period', ['completed', 'approved'])
                ->groupBy('product_id')
                ->orderByDesc('total_quantity')
                ->get();

            foreach ($products as $product){
                $product_detail = Product::query()->where('id', $product->product_id)->first();
                $product['product_detail'] = $product_detail;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['products' => $products]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getCompanyPointsByCompanyId($company_id)
    {
        try {
            $points = CompanyPoint::query()
                ->where('company_id', $company_id)
                ->where('active', 1)
                ->orderByDesc('id')
                ->get();

            foreach ($points as $point){
                $user = Admin::query()->where('id', $point->user_id)->first();
                $point['user_name'] = $user->name." ".$user->surname;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['points' => $points]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addCompanyPoint(Request $request)
    {
        try {
            $request->validate([
                'company_id' => 'required',
                'user_id' => 'required',
                'point' => 'required',
            ]);
            CompanyPoint::query()->insert([
                'company_id' => $request->company_id,
                'user_id' => $request->user_id,
                'point' => $request->point
            ]);

            return response(['message' => __('Hedef ekleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001', 'a' => $throwable->getMessage()]);
        }
    }
}
