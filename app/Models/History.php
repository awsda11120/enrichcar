<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;
    protected $fillable = [
        'CarId',
        'DateRenew',
        'TypeRenewIns',
        'TypeRenewTax',
        'Receive',
        'ProofOfReceive',
        'SumRenew',
        'SumTax',
        'InsIncome',
        'TaxIncome',
        'SumDelivery',
        'SumCost',
        // 'status_check'
    ];
}
