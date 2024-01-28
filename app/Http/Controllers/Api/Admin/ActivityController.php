<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\ActivityTask;
use App\Models\ActivityType;
use App\Models\Admin;
use App\Models\Company;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class ActivityController extends Controller
{
    //Activity
    public function getActivities()
    {
        try {
            $activities = Activity::query()
                ->where('end', '>=', now())
                ->where('active',1)
                ->get();
            foreach ($activities as $activity){
                $activity['user'] = Admin::query()->where('id', $activity->user_id)->first();
                $activity['tasks'] = ActivityTask::query()->where('activity_id', $activity->id)->where('active', 1)->get();
                $activity['type'] = ActivityType::query()->where('id', $activity->type_id)->where('active', 1)->first();
                $activity['company'] = Company::query()->where('id', $activity->company_id)->where('active', 1)->first();
                $activity['employee'] = Employee::query()->where('id', $activity->employee_id)->where('active', 1)->first();
                $activity['task_count'] = ActivityTask::query()->where('activity_id', $activity->id)->where('active', 1)->count();
                $activity['completed_task_count'] = ActivityTask::query()->where('activity_id', $activity->id)->where('is_completed', 1)->where('active', 1)->count();

                $current_time = Carbon::now();
                $current_time->setTimezone('GMT+3');
                $start_time = Carbon::parse($activity->start);

                $difference = $start_time->diff($current_time);
                $days = $difference->days;
                $time1 = $difference->format('%H');
                $time2 = $difference->format('%I');

                $custom_diff = ($days > 0 ? $days . ' gün ' : '') . $time1 . ' saat ' . $time2 . ' dakika';

                $activity['diff_end_day'] = $custom_diff;
                $activity['current_time'] = $current_time;
                $activity['start_time'] = $start_time;
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['activities' => $activities]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getPastActivities()
    {
        try {
            $activities = Activity::query()
                ->where('end', '<', now())
                ->where('active',1)
                ->get();
            foreach ($activities as $activity){
                $activity['user'] = Admin::query()->where('id', $activity->user_id)->first();
                $activity['tasks'] = ActivityTask::query()->where('activity_id', $activity->id)->where('active', 1)->get();
                $activity['type'] = ActivityType::query()->where('id', $activity->type_id)->where('active', 1)->first();
                $activity['company'] = Company::query()->where('id', $activity->company_id)->where('active', 1)->first();
                $activity['employee'] = Employee::query()->where('id', $activity->employee_id)->where('active', 1)->first();
                $activity['task_count'] = ActivityTask::query()->where('activity_id', $activity->id)->where('active', 1)->count();
                $activity['completed_task_count'] = ActivityTask::query()->where('activity_id', $activity->id)->where('is_completed', 1)->where('active', 1)->count();
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['activities' => $activities]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getActivitiesByCompanyId($company_id)
    {
        try {
            $activities = Activity::query()->where('active',1)->where('company_id', $company_id)->get();
            foreach ($activities as $activity){
                $activity['user'] = Admin::query()->where('id', $activity->user_id)->first();
                $activity['tasks'] = ActivityTask::query()->where('activity_id', $activity->id)->where('active', 1)->get();
                $activity['type'] = ActivityType::query()->where('id', $activity->type_id)->where('active', 1)->first();
                $activity['company'] = Company::query()->where('id', $activity->company_id)->where('active', 1)->first();
                $activity['employee'] = Employee::query()->where('id', $activity->employee_id)->where('active', 1)->first();
                $activity['task_count'] = ActivityTask::query()->where('activity_id', $activity->id)->where('active', 1)->count();
                $activity['completed_task_count'] = ActivityTask::query()->where('activity_id', $activity->id)->where('is_completed', 1)->where('active', 1)->count();
            }

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['activities' => $activities]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getActivityById($activity_id)
    {
        try {
            $activity = Activity::query()->where('id', $activity_id)->where('active',1)->first();
            $activity['tasks'] = ActivityTask::query()->where('activity_id', $activity->id)->where('active', 1)->get();
            $activity['type'] = ActivityType::query()->where('id', $activity->type_id)->where('active', 1)->first();
            $activity['company'] = Company::query()->where('id', $activity->company_id)->where('active', 1)->first();
            $activity['employee'] = Employee::query()->where('id', $activity->employee_id)->where('active', 1)->first();
            $activity['task_count'] = ActivityTask::query()->where('activity_id', $activity->id)->where('active', 1)->count();
            $activity['completed_task_count'] = ActivityTask::query()->where('activity_id', $activity->id)->where('is_completed', 1)->where('active', 1)->count();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['activity' => $activity]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addActivity(Request $request)
    {
        try {
            $request->validate([
                'type_id' => 'required',
                'title' => 'required',
                'company_id' => 'required',
                'employee_id' => 'required',
            ]);
            $activity_id = Activity::query()->insertGetId([
                'user_id' => $request->user_id,
                'type_id' => $request->type_id,
                'title' => $request->title,
                'description' => $request->description,
                'company_id' => $request->company_id,
                'employee_id' => $request->employee_id,
                'start' => $request->start,
                'end' => $request->end,
            ]);

            foreach ($request->tasks as $task){
                ActivityTask::query()->insertGetId([
                    'activity_id' => $activity_id,
                    'title' => $task['title'],
                    'is_completed' => $task['is_completed'],
                ]);
            }


            return response(['message' => __('Aktivite ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['activity_id' => $activity_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateActivity(Request $request,$activity_id){
        try {
            $request->validate([
                'type_id' => 'required',
                'title' => 'required',
                'company_id' => 'required',
                'employee_id' => 'required',
            ]);

            Activity::query()->where('id', $activity_id)->update([
                'user_id' => $request->user_id,
                'type_id' => $request->type_id,
                'title' => $request->title,
                'description' => $request->description,
                'company_id' => $request->company_id,
                'employee_id' => $request->employee_id,
                'start' => $request->start,
                'end' => $request->end,
            ]);

            return response(['message' => __('Aktivite güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteActivity($activity_id){
        try {

            Activity::query()->where('id',$activity_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Aktivite silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    //Activity Tasks
    public function getActivityTasksByCompanyId($company_id)
    {
        try {
            $tasks = ActivityTask::query()->where('active',1)->where('company_id', $company_id)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['tasks' => $tasks]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getActivityTaskById($task_id)
    {
        try {
            $task = ActivityTask::query()->where('id', $task_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['task' => $task]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addActivityTask(Request $request)
    {
        try {
            $request->validate([
                'activity_id' => 'required',
                'title' => 'required',
            ]);
            $task_id = ActivityTask::query()->insertGetId([
                'activity_id' => $request->activity_id,
                'title' => $request->title,
            ]);

            return response(['message' => __('Aktivite görev ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['task_id' => $task_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateActivityTask(Request $request,$task_id){
        try {
            $request->validate([
                'activity_id' => 'required',
                'title' => 'required',
            ]);

            ActivityTask::query()->where('id', $task_id)->update([
                'activity_id' => $request->activity_id,
                'title' => $request->title,
            ]);

            return response(['message' => __('Aktivite görev güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteActivityTask($task_id){
        try {

            ActivityTask::query()->where('id',$task_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Aktivite görev silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function completeActivityTask($task_id){
        try {
            ActivityTask::query()->where('id',$task_id)->update([
                'is_completed' => 1,
            ]);
            return response(['message' => __('Aktivite görev tamamlandı işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function unCompleteActivityTask($task_id){
        try {
            ActivityTask::query()->where('id',$task_id)->update([
                'is_completed' => 0,
            ]);
            return response(['message' => __('Aktivite görev tamamlanmadı işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    //Activity Types
    public function getActivityTypes()
    {
        try {
            $activity_types = ActivityType::query()->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['activity_types' => $activity_types]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function getActivityTypeById($type_id)
    {
        try {
            $activity_type = ActivityType::query()->where('id', $type_id)->where('active',1)->first();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['activity_type' => $activity_type]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }

    public function addActivityType(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
            ]);
            $activity_type_id = ActivityType::query()->insertGetId([
                'name' => $request->name,
            ]);

            return response(['message' => __('Aktivite tip ekleme işlemi başarılı.'), 'status' => 'success', 'object' => ['activity_type_id' => $activity_type_id]]);
        } catch (ValidationException $validationException) {
            return response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'), 'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001','a' => $queryException->getMessage()]);
        } catch (\Throwable $throwable) {
            return response(['message' => __('Hatalı işlem.'), 'status' => 'error-001','a' => $throwable->getMessage()]);
        }
    }

    public function updateActivityType(Request $request,$type_id){
        try {
            $request->validate([
                'name' => 'required',
            ]);

            ActivityType::query()->where('id', $type_id)->update([
                'name' => $request->name,
            ]);

            return response(['message' => __('Aktivite tip güncelleme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }

    public function deleteActivityType($type_id){
        try {

            ActivityType::query()->where('id',$type_id)->update([
                'active' => 0,
            ]);
            return response(['message' => __('Aktivite tip silme işlemi başarılı.'),'status' => 'success']);
        } catch (ValidationException $validationException) {
            return  response(['message' => __('Lütfen girdiğiniz bilgileri kontrol ediniz.'),'status' => 'validation-001']);
        } catch (QueryException $queryException) {
            return  response(['message' => __('Hatalı sorgu.'),'status' => 'query-001']);
        } catch (\Throwable $throwable) {
            return  response(['message' => __('Hatalı işlem.'),'status' => 'error-001','ar' => $throwable->getMessage()]);
        }
    }
}
