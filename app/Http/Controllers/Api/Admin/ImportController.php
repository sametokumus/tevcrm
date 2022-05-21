<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\ImportProduct;
use App\Models\Product;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProductImport;


class ImportController extends Controller
{
    public function productExcelImport(Request $request){
        Excel::import(new ProductImport, $request->file('file'));
        return response(['mesaj' => 'başarılı']);
    }

//    public function addAllProduct(){
//        $import_products = ImportProduct::all();
//        foreach ($import_products as $import_product){
//            Product::query()->insert([
//                'sku' => $import_product->ana_urun_kodu,
//                'name' => $import_product->ana_urun_ad,
//                'brand_id'
//            ]);
//        }
//    }
}
