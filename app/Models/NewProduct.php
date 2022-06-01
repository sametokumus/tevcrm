<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'ana_urun_kod',
'alt_urun_kod',
'ana_urun_ad',
'micro_urun_ad',
'micro_urun_kod',
'renk',
'fiyat',
'kdv_dahil',
'para_birimi',
'yeni_urun',
'kampanyalı_urun',
'one_cikan_urun'
    ];
}
