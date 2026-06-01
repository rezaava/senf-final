<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;
    protected $fillable = ['image', 'link', 'organ_id', 'status'];

    public function organ()
    {
        return $this->belongsTo(Organ::class);
    }
}
