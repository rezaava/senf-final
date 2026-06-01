<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Organ extends Model
{
    use HasFactory;
    public function Categories()
    {
        return $this->belongsToMany(Category::class, 'organ_categories')->withTimestamps();
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'organ_users')->withPivot('status')->withTimestamps();
    }
    public function Manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }
    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
    public function operator()
    {
        return $this->hasMany(User::class, 'organ_id');
    }
    public function requests(): MorphMany
    {
        return $this->morphMany(Request::class, 'requestable');
    }
    public function services()
    {
        return $this->hasMany(Service::class, 'organ_id');
    }
    public function requests_owner()
    {
        return $this->hasMany(Request::class, 'organ_id');
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class, 'organ_id');
    }
    public function contractTemplate()
    {
        return $this->hasMany(ContractTemplate::class, 'organ_id');
    }
    public function sliders()
    {
        return $this->hasMany(Slider::class);
    }
    public function banners()
    {
        return $this->hasMany(Banner::class);
    }
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'usable');
    }
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
