<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
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
            $statuses = Status::query()->where('active',1)->get();

            return response(['message' => __('İşlem Başarılı.'), 'status' => 'success', 'object' => ['statuses' => $statuses]]);
        } catch (QueryException $queryException) {
            return response(['message' => __('Hatalı sorgu.'), 'status' => 'query-001']);
        }
    }
}
