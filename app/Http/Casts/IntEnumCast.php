<?php

namespace App\Http\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use InvalidArgumentException;

class IntEnumCast implements CastsAttributes
{
    public function __construct(protected string $enumClass)
    {
        if (! enum_exists($this->enumClass)) {
            throw new InvalidArgumentException("Class {$this->enumClass} is not a valid enum.");
        }
    }

    public function get($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        return $this->enumClass::from((int) $value);
    }

    public function set($model, string $key, $value, array $attributes)
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof $this->enumClass) {
            return $value->value;
        }

        return $this->enumClass::from((int) $value)->value;
    }
}