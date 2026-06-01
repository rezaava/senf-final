<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'code',
        'type',
        'value',
        'usage_limit',
        'organ_id',
    ];

    /**
     * رابطه با ارگان
     */
    public function organ()
    {
        return $this->belongsTo(Organ::class);
    }
}
