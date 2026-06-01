<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'type',
        'path',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
