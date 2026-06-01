<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;
    protected $fillable = [
        'organ_id',
        'user_id',
        'service_id',
        'day',
        'start_time',
        'end_time',
        'status',
        'free_customer',
        'price',
        'price_offer',
        'customer',
        'user_image',
        'cart_id'
    ];

    public function organ()
    {
        return $this->belongsTo(Organ::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function operator()
    {
        return $this->belongsTo(User::class,'operator_id');
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function reservedBy()
    {
        return $this->belongsTo(User::class, 'customer');
    }
    public function getCustomerNameAttribute()
    {
        return $this->reservedBy->name ?? $this->free_customer;
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
}
