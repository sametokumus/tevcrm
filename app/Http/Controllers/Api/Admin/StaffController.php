<?php

namespace App\Http\Controllers\Api\Admin;

use App\Helpers\CustomerHelper;
use App\Helpers\StaffHelper;
use App\Helpers\StaffTargetHelper;
use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Expense;
use App\Models\OfferProduct;
use App\Models\OfferRequest;
use App\Models\PaymentTerm;
use App\Models\Quote;
use App\Models\Sale;
use App\Models\SaleOffer;
use App\Models\StaffTarget;
use App\Models\StaffTargetType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Nette\Schema\ValidationException;
use Carbon\Carbon;

class StaffController extends Controller
{

    public function getStaffTargets()
    {
        try {
            $targets = StaffTarget::query()
                ->leftJoin('staff_target_types', 'staff_target_types.id', '=', 'staff_targets.type_id')
                ->where('staff_targets.active', 1)
                ->selectRaw('staff_targets.*, staff_target_types.name as type_name')
                ->get();

            foreach ($targets as $target){
                $admin = Admin::query()->where('id', $target->admin_id)->first();
                $target['admin'] = $admin;

                if ($target->month == 0){
                    $month_name = 'Tüm Yıl';
                }else{
                    $monthId = $target->month;
                    $month_name = trans("date.months.$monthId");
                }

                $target['month_name'] = $month_name;

                $target['status'] = StaffTargetHelper::getTargetStatus($target->id);
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['targets' => $targets]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'e' => $queryException->getMessage()]);
        }
    }

    public function getStaffTargetsByStaffId($staff_id)
    {
        try {
            $targets = StaffTarget::query()
                ->leftJoin('staff_target_types', 'staff_target_types.id', '=', 'staff_targets.type_id')
                ->where('staff_targets.admin_id', $staff_id)
                ->where('staff_targets.active', 1)
                ->selectRaw('staff_targets.*, staff_target_types.name as type_name')
                ->get();

            foreach ($targets as $target){
                $admin = Admin::query()->where('id', $target->admin_id)->first();
                $target['admin'] = $admin;

                if ($target->month == 0){
                    $month_name = 'Tüm Yıl';
                }else{
                    $monthId = $target->month;
                    $month_name = trans("date.months.$monthId");
                }

                $target['month_name'] = $month_name;

                $target['status'] = StaffTargetHelper::getTargetStatus($target->id);
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['targets' => $targets]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getStaffTargetById($target_id)
    {
        try {

            $target = StaffTarget::query()
                ->leftJoin('staff_target_types', 'staff_target_types.id', '=', 'staff_targets.type_id')
                ->where('staff_targets.id', $target_id)
                ->where('staff_targets.active', 1)
                ->selectRaw('staff_targets.*, staff_target_types.name as type_name')
                ->first();

            $admin = Admin::query()->where('id', $target->admin_id)->first();
            $target['admin'] = $admin;

            if ($target->month == 0){
                $month_name = 'Tüm Yıl';
            }else{
                $monthId = $target->month;
                $month_name = trans("date.months.$monthId");
            }

            $target['month_name'] = $month_name;

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['target' => $target]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addStaffTarget(Request $request)
    {
        try {
            $request->validate([
                'admin_id' => 'required',
                'type_id' => 'required',
                'target' => 'required',
            ]);
            $target_id = StaffTarget::query()->insertGetId([
                'admin_id' => $request->admin_id,
                'type_id' => $request->type_id,
                'target' => $request->target,
                'currency' => $request->currency,
                'month' => $request->month,
                'year' => $request->year
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

    public function updateStaffTarget(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'type_id' => 'required',
                'target' => 'required',
            ]);
            StaffTarget::query()->where('id', $request->id)->update([
                'type_id' => $request->type_id,
                'target' => $request->target,
                'currency' => $request->currency,
                'month' => $request->month,
                'year' => $request->year
            ]);

            return response(['message' => __('Hedef güncelleme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001', 'ar' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001', 'ar' => $throwable->getTraceAsString()]);
        }
    }

    public function deleteStaffTarget($target_id)
    {
        try {

            StaffTarget::query()->where('id', $target_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Hedef silme işlemi başarılı.'), 'status' => 'success']);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001', 'ar' => $throwable->getMessage()]);
        }
    }

    public function getStaffTargetTypes()
    {
        try {
            $target_types = StaffTargetType::query()
                ->where('active', 1)
                ->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['target_types' => $target_types]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getBestStaff(){
        try {

            $all_staffs = Admin::query()->where('active', 1)->get();
            $staffs = array();

            foreach ($all_staffs as $staff){
                $data = array();
                $data['staff'] = $staff;

                //Sipariş/Teklif Oranı
                $request_count = OfferRequest::query()->where('authorized_personnel_id', $staff->id)
                    ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->count();

                $sale_count = Sale::query()
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                    ->where('offer_requests.authorized_personnel_id', $staff->id)
                    ->whereBetween('sales.created_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->count();




                //Toplam İş Hacmi ve karlılık
                $sale_items = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('offer_requests.authorized_personnel_id', $staff->id)
                    ->where('s.active', '=', 1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                    ->whereBetween('sh.created_at', [now()->startOfMonth(), now()->endOfMonth()])
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
                            $total_payment_point += StaffHelper::get_sale_payment_point($pt->advance, $pt->expiry);
                            $total_payment_count++;
                        }
                    }

                }




                //c6
                $c6_sales = DB::table('sales AS s')
                    ->selectRaw('companies.*, COUNT(*) as sale_count')
                    ->leftJoin('companies', 'companies.id', '=', 's.customer_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('offer_requests.authorized_personnel_id', $staff->id)
                    ->where('s.active', '=', 1)
                    ->whereRaw("(statuses.period = 'completed' OR statuses.period = 'approved')")
                    ->whereBetween('sh.created_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->groupBy('s.customer_id')
                    ->get();

                $customer_total_count = 0;
                $customer_total_point = 0;
                foreach ($c6_sales as $item){
                    $customer_total_point += StaffHelper::get_sale_customer_point($item->sale_count);
                    $customer_total_count++;
                }




                //c7
                $activity_count = Activity::query()
                    ->leftJoin('activity_types', 'activity_types.id', '=', 'activities.type_id')
                    ->where('activity_types.face_to_face', 1)
                    ->where('activities.user_id', $staff->id)
                    ->where('activities.active', 1)
                    ->whereBetween('activities.start', [now()->startOfMonth(), now()->endOfMonth()])
                    ->count();




                //c8
                $export_sale_count = Sale::query()
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 'sales.request_id')
                    ->where('sales.type_id', 2)
                    ->where('offer_requests.authorized_personnel_id', $staff->id)
                    ->whereBetween('sales.created_at', [now()->startOfMonth(), now()->endOfMonth()])
                    ->count();



                //c9
                $c9_sales = DB::table('sales AS s')
                    ->select('s.*')
                    ->leftJoin('companies', 'companies.id', '=', 's.customer_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('offer_requests.authorized_personnel_id', $staff->id)
                    ->where('s.active', 1)
                    ->where(function ($query) {
                        $query->where('statuses.period', 'completed')
                            ->orWhere('statuses.period', 'approved');
                    })
                    ->whereNotExists(function ($query) {
                        $query->select(DB::raw(1))
                            ->from('sales AS s2')
                            ->whereRaw('s2.customer_id = s.customer_id AND s2.created_at < s.created_at');
                    })
                    ->get();

                $first_sale_count = 0;
                foreach ($c9_sales as $item) {
                    if (now()->format('Y-m') == \Carbon\Carbon::parse($item->created_at)->format('Y-m')) {
                        $first_sale_count++;
                    }
                }



                $data['request_count'] = $request_count;
                $data['sale_count'] = $sale_count;
                $c1 = StaffHelper::get_request_and_sales_rate($request_count, $sale_count);
                $data['c1'] = $c1;

                $data['usd_price'] = $usd_price;
                $c2 = StaffHelper::get_sales_total_rate($usd_price);
                $data['c2'] = $c2;

                $data['total_profit_rate'] = $total_profit_rate;
                $data['total_item_count'] = $total_item_count;
                $c3 = StaffHelper::get_sales_profit_rate($total_profit_rate, $total_item_count);
                $data['c3'] = $c3;

                $data['total_payment_point'] = $total_payment_point;
                $data['total_payment_count'] = $total_payment_count;
                $c4 = StaffHelper::get_sales_payment_point($total_payment_point, $total_payment_count);
                $data['c4'] = $c4;

                $c5 = StaffHelper::get_manager_point($staff->id);
                $data['c5'] = $c5;

                $data['customer_total_point'] = $customer_total_point;
                $data['customer_total_count'] = $customer_total_count;
                $c6 = StaffHelper::get_sales_customer_point($customer_total_point, $customer_total_count);
                $data['c6'] = $c6;

                $c7 = StaffHelper::get_activity_point($activity_count);
                $data['c7'] = $c7;

                $c8 = StaffHelper::get_export_sale_point($export_sale_count);
                $data['c8'] = $c8;

                $c9 = StaffHelper::get_first_sale_point($first_sale_count);
                $data['c9'] = $c9;

                $c1_rate = StaffHelper::get_point_rate($c1, 17);
                $c2_rate = StaffHelper::get_point_rate($c2, 15);
                $c3_rate = StaffHelper::get_point_rate($c3, 14);
                $c4_rate = StaffHelper::get_point_rate($c4, 13);
                $c5_rate = StaffHelper::get_point_rate($c5, 11);
                $c6_rate = StaffHelper::get_point_rate($c6, 9);
                $c7_rate = StaffHelper::get_point_rate($c7, 9);
                $c8_rate = StaffHelper::get_point_rate($c8, 6);
                $c9_rate = StaffHelper::get_point_rate($c9, 5);

                $data['c1_rate'] = $c1_rate;
                $data['c2_rate'] = $c2_rate;
                $data['c3_rate'] = $c3_rate;
                $data['c4_rate'] = $c4_rate;
                $data['c5_rate'] = $c5_rate;
                $data['c6_rate'] = $c6_rate;
                $data['c7_rate'] = $c7_rate;
                $data['c8_rate'] = $c8_rate;
                $data['c9_rate'] = $c9_rate;


                $staff_rate = $c1_rate + $c2_rate + $c3_rate + $c4_rate + $c5_rate + $c6_rate + $c7_rate + $c8_rate + $c9_rate;
                $data['staff_rate'] = number_format($staff_rate, 2, '.', '');


                array_push($staffs, $data);
            }

            usort($staffs, function ($a, $b) {
                return $b['staff_rate'] <=> $a['staff_rate'];
            });


            return response(['message' => __('İşlem başarılı.'), 'status' => 'success', 'object' => ['staffs' => $staffs]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
