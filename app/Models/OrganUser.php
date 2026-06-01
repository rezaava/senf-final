<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganUser extends Model
{
    use HasFactory;

    public function organ()
    {
        return $this->belongsTo(Organ::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
