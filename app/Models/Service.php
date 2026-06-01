<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Organ;

class Service extends Model
{
    use HasFactory;
    public function organ()
    {
        return $this->belongsTo(Organ::class, 'organ_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'service_users')->withPivot('id')->withTimestamps();
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function contracts()
    {
        return $this->belongsToMany(ContractTemplate::class, 'contract_services')->withPivot('percentage')->withTimestamps();
    }
    public function carts()
    {
        return $this->belongsToMany(Cart::class)
            ->withPivot('appointment_id', 'price', 'price_offer', 'user_image')->withTimestamps();
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'usable');
    }
    public function favorites()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_services')->withTimestamps();
    }
}
