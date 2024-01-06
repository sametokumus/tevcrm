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
                $data = StaffHelper::get_staff_data($staff);

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

    public function getStaffStatistics($staff_id){
        try {

            $total_company_count = Company::query()
                ->where('active', 1)
                ->where('user_id', $staff_id)
                ->count();

            $now = Carbon::now();

            $add_this_month_company = Company::query()
                ->where('active', 1)
                ->where('user_id', $staff_id)
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $activity_this_month = Activity::query()
                ->where('active', 1)
                ->where('user_id', $staff_id)
                ->whereMonth('start', $now->month)
                ->whereYear('start', $now->year)
                ->count();

            $request_this_month = OfferRequest::query()
                ->where('active', 1)
                ->where('authorized_personnel_id', $staff_id)
                ->whereMonth('created_at', $now->month)
                ->whereYear('created_at', $now->year)
                ->count();

            $sale_this_month = DB::table('sales AS s')
                ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                ->join('status_histories AS sh', function ($join) {
                    $join->on('s.sale_id', '=', 'sh.sale_id')
                        ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                })
                ->where('s.active', '=', 1)
                ->where('offer_requests.authorized_personnel_id', '=', $staff_id)
                ->whereMonth('sh.created_at', $now->month)
                ->whereYear('sh.created_at', $now->year)
                ->count();

            return response(['message' => __('İşlem başarılı.'), 'status' => 'success', 'object' => [
                'total_company_count' => $total_company_count,
                'add_this_month_company' => $add_this_month_company,
                'activity_this_month' => $activity_this_month,
                'request_this_month' => $request_this_month,
                'sale_this_month' => $sale_this_month
            ]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getStaffSituation($staff_id){
        try {

            $all_staffs = Admin::query()->where('active', 1)->get();
            $staffs = array();

            foreach ($all_staffs as $staff){
                $data = StaffHelper::get_staff_data($staff);

                array_push($staffs, $data);
            }

            usort($staffs, function ($a, $b) {
                return $b['staff_rate'] <=> $a['staff_rate'];
            });

            $position = 0;
            $staff;
            foreach ($staffs as $index => $item) {
                if ($item['staff']['id'] == $staff_id) {
                    $position = $index + 1;
                    $staff = $item;
                }
            }

            return response(['message' => __('İşlem başarılı.'), 'status' => 'success', 'object' => ['staff' => $staff, 'position' => $position, 'staffs' => $staffs]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function getAllStaffStatistics(){
        try {

            $all_staffs = Admin::query()->where('active', 1)->get();
            $staffs = array();

            foreach ($all_staffs as $staff){
                $staff_id = $staff->id;
                $data = StaffHelper::get_staff_data($staff);


                $total_company_count = Company::query()
                    ->where('active', 1)
                    ->where('user_id', $staff_id)
                    ->count();

                $now = Carbon::now();

                $add_this_month_company = Company::query()
                    ->where('active', 1)
                    ->where('user_id', $staff_id)
                    ->whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year)
                    ->count();

                $activity_this_month = Activity::query()
                    ->where('active', 1)
                    ->where('user_id', $staff_id)
                    ->whereMonth('start', $now->month)
                    ->whereYear('start', $now->year)
                    ->count();

                $request_this_month = OfferRequest::query()
                    ->where('active', 1)
                    ->where('authorized_personnel_id', $staff_id)
                    ->whereMonth('created_at', $now->month)
                    ->whereYear('created_at', $now->year)
                    ->count();

                $sale_this_month = DB::table('sales AS s')
                    ->select('s.*', 'sh.status_id AS last_status', 'sh.created_at AS last_status_created_at')
                    ->addSelect(DB::raw('YEAR(sh.created_at) AS year, MONTH(sh.created_at) AS month'))
                    ->leftJoin('statuses', 'statuses.id', '=', 's.status_id')
                    ->leftJoin('offer_requests', 'offer_requests.request_id', '=', 's.request_id')
                    ->join('status_histories AS sh', function ($join) {
                        $join->on('s.sale_id', '=', 'sh.sale_id')
                            ->where('sh.created_at', '=', DB::raw('(SELECT MAX(created_at) FROM status_histories WHERE sale_id = s.sale_id AND status_id = 7)'));
                    })
                    ->where('s.active', '=', 1)
                    ->where('offer_requests.authorized_personnel_id', '=', $staff_id)
                    ->whereMonth('sh.created_at', $now->month)
                    ->whereYear('sh.created_at', $now->year)
                    ->count();

                $data['total_company_count'] = $total_company_count;
                $data['add_this_month_company'] = $add_this_month_company;
                $data['activity_this_month'] = $activity_this_month;
                $data['request_this_month'] = $request_this_month;
                $data['sale_this_month'] = $sale_this_month;


                array_push($staffs, $data);
            }

            usort($staffs, function ($a, $b) {
                return $b['staff_rate'] <=> $a['staff_rate'];
            });

            return response(['message' => __('İşlem başarılı.'), 'status' => 'success', 'object' => ['staff' => $staff, 'position' => $position, 'staffs' => $staffs]]);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001', 'message' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
