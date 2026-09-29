<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Casts\IntEnumCast;
use App\Http\Casts\UserStatusCast;
use App\Http\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;

    protected $table = 'users';
    protected $fillable = [
        'username',
        'email',
        'password',
        'user_id',
        'profile_id',
        'dept_id',
        'status',
        'created_by',
        'updated_by',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'status' => IntEnumCast::class . ':' . ActiveStatus::class,
    ];



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

    // protected static function booted(): void
    // {
    //     static::created(function (User $user) {
    //         $user->profile()->create([]);
    //     });
    // }

    public function departmentInfo()
    {
        return $this->belongsTo(Department::class);
    }
}
