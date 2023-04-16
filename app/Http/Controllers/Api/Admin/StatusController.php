<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\OfferRequest;
use App\Models\Sale;
use App\Models\Status;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Nette\Schema\ValidationException;

class StatusController extends Controller
{
    public function getStatuses()
    {
        try {
            $statuses = Status::query()->where('active',1)->orderBy('sequence')->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['statuses' => $statuses]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getAuthorizeStatuses($user_id)
    {
        try {
            $admin = Admin::query()->where('id', $user_id)->first();
            $statuses = Status::query()
                ->leftJoin('admin_status_roles', 'admin_status_roles.status_id', '=', 'statuses.id')
                ->where('statuses.active',1)
                ->where('admin_status_roles.admin_role_id', $admin->admin_role_id)
                ->where('admin_status_roles.active',1)
                ->orderBy('sequence')
                ->get('statuses.*');

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['statuses' => $statuses]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
    public function getChangeableStatuses()
    {
        try {
            $statuses = Status::query()->where('active',1)->where('change_list',1)->orderBy('sequence')->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['statuses' => $statuses]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
