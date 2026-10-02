<?php

namespace App\Http\Enums;

enum DepartmentStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';

    /**
     * All backed values — used by migrations & validation rules.
     *
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::Active   => 'Active',
            self::Inactive => 'Inactive',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active   => 'emerald',
            self::Inactive => 'gray',
        };
    }
}