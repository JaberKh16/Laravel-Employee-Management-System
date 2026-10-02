<?php

namespace App\Http\Casts;

use BackedEnum;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class EnumCast implements CastsAttributes
{
    public function __construct(protected string $enumClass)
    {
        if (!enum_exists($this->enumClass)) {
            throw new InvalidArgumentException("Class {$this->enumClass} is not a valid enum.");
        }
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return null;
        }

        return $this->enumClass::tryFrom($value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof $this->enumClass) {
            return $value->value;
        }

        if (is_string($value)) {
            return $this->enumClass::from($value)->value;
        }

        return $value;
    }
}