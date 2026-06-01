<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions;
    use HasApiTokens, HasFactory, Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'mobile',
        'organ_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = bcrypt($password);
    }
    public function organs()
    {
        return $this->belongsToMany(Organ::class, 'organ_users')->withPivot('status')->withTimestamps();
    }
    public function OrganManaging()
    {
        return $this->hasMany(Organ::class, 'manager_id');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
    public function organSelected()
    {
        return $this->belongsTo(Organ::class, 'organ_id');
    }
    public function requests(): MorphMany
    {
        return $this->morphMany(Request::class, 'requestable');
    }
    public function requests_owner()
    {
        return $this->hasMany(Request::class, 'user_id');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_users')->withTimestamps();
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
    public function reserved()
    {
        return $this->hasMany(Appointment::class, 'customer');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // contracts
    // قراردادهایی که کاربر ایجاد کرده
    public function sentContracts()
    {
        return $this->hasMany(Contract::class, 'from_user_id');
    }

    // قراردادهایی که باید تایید یا امضا کنه
    public function receivedContracts()
    {
        return $this->hasMany(Contract::class, 'to_user_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function gallery()
    {
        return $this->morphMany(Gallery::class, 'usable');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }
    public function reservations_operator()
    {
        return $this->hasMany(Reservation::class, 'operator_id');
    }
    public function favorites()
    {
        return $this->belongsToMany(Service::class, 'favorites')->withTimestamps();
    }
}
