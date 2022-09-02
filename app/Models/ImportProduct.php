<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImportProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_id',
        'sku',
        'name',
        'description',
        'quantity_stock',
        'regular_price',
    ];
}
