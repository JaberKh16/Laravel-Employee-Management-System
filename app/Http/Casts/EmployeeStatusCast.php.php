<?php

namespace App\Http\Casts;

use App\Http\Enums\EmployeeStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class EmployeeStatusCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?EmployeeStatus
    {
        if ($value === null || $value === '') {
            return null;
        }

        return EmployeeStatus::tryFrom($value);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof EmployeeStatus) {
            return $value->value;
        }

        if (is_string($value)) {
            return EmployeeStatus::from($value)->value;
        }

        return $value;
    }
}