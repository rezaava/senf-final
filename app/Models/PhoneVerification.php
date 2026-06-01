<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneVerification extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'code',
        'expires_at',
        'attempts',
        'send_count',
        'ip_address',
        'user_agent',
    ];
    protected $casts = [
        'expires_at' => 'datetime',
    ];
}
