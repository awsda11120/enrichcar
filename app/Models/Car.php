<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;
    protected $fillable = [
        'CusId',
        'CarNumber',
        'CarCity',
        'CarWeight',
        'CarCC',
        'InsuranceType',
        'TaxType',
        'TypeId',
        'TaxId',
        'RegistrationDate',
        'BookOwner',
        'TaxHistoryDate',
        'SelectOption',
        'InsHistoryDate'
    ];


    public function custo(){
        return $this->belongsToMany(Customer::class, 'customer_car');
    }
}
