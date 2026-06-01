<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'operator_id',
        'organ_id',
        'start_at',
        'end_at',
        'status',
        'pricing_status',
        'total_price',
        'reserved_until',
        'cart_id',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
    ];
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    public function costumer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function services()
    {
        return $this->hasMany(ReservationService::class);
    }

    public function financial()
    {
        return $this->hasOne(ReservationFinancial::class);
    }

    public function media()
    {
        return $this->hasMany(ReservationMedia::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
    public function organ()
    {
        return $this->belongsTo(Organ::class, 'organ_id');
    }
}
