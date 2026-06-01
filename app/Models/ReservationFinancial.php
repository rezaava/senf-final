<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationFinancial extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation_id',
        'min_paid_amount',
        'final_total_amount',
        'salon_share',
        'operator_share',
        'platform_fee',
        'salon_contract_id',
        'operator_contract_id',
        'pricing_locked_at',
    ];

    protected $casts = [
        'pricing_locked_at' => 'datetime',
    ];
}
