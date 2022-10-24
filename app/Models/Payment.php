<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'agreement_id',
        'payment_number',
        'settlement_month',
        'redemption_payment',
        'advance_payment_amount',
        'total_amount',
    ];
}
