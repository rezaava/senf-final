<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public function Organs()
    {
        return $this->belongsToMany(Organ::class, 'organ_categories')->withTimestamps();
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'category_services')->withTimestamps();
    }
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }
    public function child()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

}
