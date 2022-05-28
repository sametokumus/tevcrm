<?php

namespace App\Imports;

use App\Models\ImportPrice;
use App\Models\ImportProduct;
use Maatwebsite\Excel\Concerns\ToModel;


class PriceImports implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row){
        return new ImportPrice([

            'web_servis_kodu' => $row[0],
            'urun_adi' => $row[1],
            'fiyati' => $row[2],
            'indirimli_fiyati' => $row[3],
            'kdv_dahil_fiyati' => $row[4],
            'kdv' => $row[5],
            'alis_fiyatı' => $row[6],
            'yeni_urun_mu' => $row[7],
            'indirimli_goster' => $row[8],
            'tanitimli_goster' => $row[9],
            'sira_no' => $row[10],
            'currency' => $row[11]
        ]);
    }
}
