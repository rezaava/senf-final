<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'type',
        'path',
        'organ_id',
        'operator_id',
    ];

    public function organ()
    {
        return $this->belongsTo(Organ::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
    public function usable()
    {
        return $this->morphTo();
    }
}
