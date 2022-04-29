<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','country_id', 'city_id','district_id', 'title', 'name','surname','address_1','phone','comment','active'
    ];

    public function user(){
        return $this->belongsTo('App\Models\Address');
    }
}
