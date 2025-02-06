<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'CustomerName',
        'NationalID',
        'PhoneNumber',
        'Address',
       
];


public function carr(){
return $this->belongsToMany(Car::class, 'customer_car');
}
}
