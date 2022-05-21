<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'ana_ürün_kod',
        'alt_ürün_kod',
        'ana_ürün_ad',
        'mikro_ürün_ad',
        'mikro_ürün_kod',
        'renk',
        'birim',
        'paket_tipi',
        'kmk',
        'marka',
        'arama_kelimeleri',
        'açıklama',
        'kısa_açıklama',
        'notlar',
        'ağırlık',
        'seo_başlık',
        'seo_kelimeler',
        'alt_ürün_var',
        'resim',
        'cins'
    ];
}
