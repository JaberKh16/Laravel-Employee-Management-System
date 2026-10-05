<?php

namespace App\Models;

use App\Http\Casts\IntEnumCast;
use App\Http\Casts\UserStatusCast;
use App\Http\Enums\ActiveStatus;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

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

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function role()
    {
        // If you have a single role_id column on users table:
        return $this->belongsTo(Role::class, 'role_id');

        // OR if you're using Spatie's multi-role:
        // Don't use a `role()` relation — use `roles()` (plural, from Spatie trait)
    }


    public function getStatusLabelAttribute(): string
    {
        $status = $this->status;   // cast → ActiveStatus enum (or null)

        if ($status instanceof ActiveStatus) {
            return $status->label();
        }

        if ($status === null) {
            return 'Unknown';
        }

        return ActiveStatus::tryFrom((int) $status)?->label() ?? (string) $status;
    }
}
