<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;


class ImportController extends Controller
{
    public function productExcelImport(Request $request){
        Excel::import(new ProductImport, $request->file('file'));
        return response(['mesaj' => 'başarılı']);
    }
}
