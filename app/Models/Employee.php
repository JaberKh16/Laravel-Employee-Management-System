<?php

namespace App\Models;

use App\Http\Casts\EnumCast;
use App\Http\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'employees';   

    protected $fillable = [
        'user_id',
        'job_profile_id',
        'address',
        'department_id',
        'country_id',
        'city_id',
        'state_id',
        'zip_code',
        'birthdate',
        'hired_date',
        'status',
    ];

    protected $casts = [
        'status'     => EnumCast::class . ':' . EmployeeStatus::class,
        'birthdate'  => 'date',
        'hired_date' => 'date',
    ];

    // ═══════════════════════════════════════════════════════════
    // SCOPES
    // ═══════════════════════════════════════════════════════════
    public function scopeEmployed($query)
    {
        return $query->whereIn('status', array_map(
            fn (EmployeeStatus $s) => $s->value,
            EmployeeStatus::employed()
        ));
    }

    // ═══════════════════════════════════════════════════════════
    // HELPERS
    // ═══════════════════════════════════════════════════════════
    public function isEmployed(): bool
    {
        return in_array($this->status, EmployeeStatus::employed(), true);
    }

    public function getFullNameAttribute(): string
    {
        $p = $this->user?->profile;
        return trim(($p?->first_name ?? '') . ' ' . ($p?->last_name ?? ''));
    }

    public function getInitialsAttribute(): string
    {
        $f = mb_substr($this->user?->profile?->first_name ?? '', 0, 1);
        $l = mb_substr($this->user?->profile?->last_name  ?? '', 0, 1);
        return mb_strtoupper($f . $l) ?: 'E';
    }

    // ═══════════════════════════════════════════════════════════
    // RELATIONS
    // ═══════════════════════════════════════════════════════════

    public function jobProfile()
    {
        return $this->hasOne(JobProfile::class, 'id', 'job_profile_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function department()
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    public function state()
    {
        return $this->belongsTo(State::class)->withDefault();
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withDefault();
    }
}