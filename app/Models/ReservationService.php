<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationService extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation_id',
        'service_id',
        'duration_minutes',
        'base_price',
        'final_price',
        'pricing_mode',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getEffectivePriceAttribute()
    {
        return $this->final_price ?? $this->base_price;
    }
}
