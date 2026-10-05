<?php

namespace App\Models;

use App\Http\Casts\EnumCast;
use App\Http\Enums\EmployeeStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'address',
        'department_id',
        'country_id',
        'city_id',
        'state_id',
        'zip_code',
        'birthdate',
        'date_hired',
    ];

    protected $casts = [
        'status' => EnumCast::class . ':' . EmployeeStatus::class,
    ];

    public function isEmployed(): bool
    {
        return in_array($this->status, EmployeeStatus::employed(), true);
    }

    public function scopeEmployed($query)
    {
        return $query->whereIn('status', array_map(
            fn (EmployeeStatus $s) => $s->value,
            EmployeeStatus::employed()
        ));
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->withDefault();
    }

    public function city()
    {
        return $this->belongsTo(City::class)->withDefault();
    }

    public function country()
    {
        return $this->belongsTo(Country::class)->withDefault();
    }

    public function state()
    {
        return $this->belongsTo(State::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
