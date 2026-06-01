<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorWorkingHour extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
