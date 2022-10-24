<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    protected $fillable = [
        'leasing_subject',
        'contract_cost',
        'payment_amount',
        'total_amount',
        'validity_start',
        'validity_end',
    ];
}
