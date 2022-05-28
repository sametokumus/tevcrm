<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportPrice extends Model
{
    use HasFactory;
    protected $fillable = ['web_servis_kodu',
        'urun_adi',
        'fiyati',
        'indirimli_fiyati',
        'kdv_dahil_fiyati',
        'kdv',
        'alis_fiyatı',
        'yeni_urun_mu',
        'indirimli_goster',
        'tanitimli_goster',
        'sira_no',
        'currency',
        ];
}
