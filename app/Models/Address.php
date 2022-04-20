<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id','city_id','name','address_1','phone','mobile_phone','comment','active'
    ];

    public function user(){
        return $this->belongsTo('App\Models\Address');
    }
}
