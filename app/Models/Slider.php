<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'image', 'link', 'organ_id', 'status'];

    public function organ()
    {
        return $this->belongsTo(Organ::class);
    }
}
