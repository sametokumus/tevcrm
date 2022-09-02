<?php

namespace App\Imports;

use App\Models\ImportProduct;
use Maatwebsite\Excel\Concerns\ToModel;


class ProductImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row){
        return new ImportProduct([
            'type_id' => $row[0],
            'sku' => $row[1],
            'name' => $row[2],
            'description' => $row[3],
            'quantity_stock' => $row[4],
            'regular_price' => $row[5]
        ]);
    }
}
