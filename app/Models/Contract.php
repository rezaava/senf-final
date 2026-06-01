<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;
    protected $fillable = [
        'template_id',
        'from_user_id',
        'to_user_id',
        'organ_id',
        'status',
        'start_date',
        'end_date',
        'signed_at'
    ];

    public function template()
    {
        return $this->belongsTo(ContractTemplate::class);
    }

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function toUser()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
    public function organ()
    {
        return $this->belongsTo(Organ::class, 'organ_id');
    }
}
