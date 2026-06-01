<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContractTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'created_by',
        'organ_id',
        'target_role',
        'type',
        'text',
        'percentage',
        'amount'
    ];
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    public function organ()
    {
        return $this->belongsTo(Organ::class, 'organ_id');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'contract_services',)->withPivot('percentage')->withTimestamps();
    }
}
