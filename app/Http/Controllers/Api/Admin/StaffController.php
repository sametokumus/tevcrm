<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Company;
use App\Models\StaffTarget;
use App\Models\StaffTargetType;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
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

// Create a Carbon instance for the first day of the specified month
                    $date = Carbon::createFromDate(null, $monthId, 1);

// Get the month name in Turkish
                    $month_name = trans("date.months.$monthId");
                }

                $target['month_name'] = $month_name;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['targets' => $targets]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
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
}
