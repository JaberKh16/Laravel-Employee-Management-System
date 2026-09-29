<?php

namespace App\Http\Casts;

use App\Http\Enums\UserStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class UserStatusCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        return UserStatus::from($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof UserStatus) {
            return $value->value;
        }

        // Allow setting via string
        if (is_string($value)) {
            return UserStatus::from($value)->value;
        }

        return $value;
    }
}