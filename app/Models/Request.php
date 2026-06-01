<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Request extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'status',
        'description',
        'reject',
    ];
    public function requestable(): MorphTo
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    public function organ()
    {
        return $this->belongsTo(Organ::class,'organ_id');
    }
}
