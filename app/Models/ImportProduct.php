<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'ana_urun_kod',
        'alt_urun_kod',
        'ana_urun_ad',
        'mikro_urun_ad',
        'mikro_urun_kod',
        'renk',
        'birim',
        'paket_tipi',
        'kmk',
        'marka',
        'arama_kelimeleri',
        'aciklama',
        'kisa_aciklama',
        'notlar',
        'agirlik',
        'seo_baslik',
        'seo_kelimeler',
        'alt_urun_var',
        'resim',
        'cins',
    ];
}
