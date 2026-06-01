<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorTimeOff extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'start_at',
        'end_at',
        'reason',
    ];

    protected $dates = ['start_at', 'end_at'];
}
