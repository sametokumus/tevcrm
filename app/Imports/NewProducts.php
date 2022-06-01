<?php

namespace App\Imports;

use App\Models\ImportPrice;
use App\Models\NewProduct;
use App\Models\ImportProduct;
use Maatwebsite\Excel\Concerns\ToModel;


class NewProducts implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row){
        return new NewProduct([

            'ana_urun_kod' => $row[0],
            'alt_urun_kod' => $row[1],
            'ana_urun_ad' => $row[2],
            'micro_urun_ad' => $row[3],
            'micro_urun_kod' => $row[4],
            'renk' => $row[5],
            'fiyat' => $row[6],
            'kdv_dahil' => $row[7],
            'para_birimi' => $row[8],
            'yeni_urun' => $row[9],
            'kampanyalı_urun' => $row[10],
            'one_cikan_urun' => $row[11]
        ]);
    }
}
