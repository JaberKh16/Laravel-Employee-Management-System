<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = 'users';
    protected $fillable = [
        'username',
        'email',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    // public function getFullNameAttribute()
    // {
    //     return "{$this->first_name} {$this->last_name}";
    // }


    // public function setFirstNameAttribute($value)
    // {
    //     $this->attributes['first_name'] = ucfirst($value);
    // }



    // public function setLastNameAttribute($value)
    // {
    //     $this->attributes['last_name'] = ucfirst($value);
    // }

    public function parent()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->profile()->create([]);
        });
    }
}
