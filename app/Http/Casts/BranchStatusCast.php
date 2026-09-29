<?php

namespace App\Http\Casts;

use App\Http\Enums\BranchStatus;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class BranchStatusCast implements CastsAttributes
{
    public function get($model, string $key, $value, array $attributes): ?BranchStatus
    {
        if ($value === null || $value === '') {
            return null;
        }

        return BranchStatus::tryFrom($value);
    }

    public function set($model, string $key, $value, array $attributes): ?string
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof BranchStatus) {
            return $value->value;
        }

        if (is_string($value)) {
            return BranchStatus::from($value)->value;
        }

        return $value;
    }
}