<?php

namespace App\Http\Enums;  

enum BranchStatus: string
{
    case Active           = 'active';
    case Inactive         = 'inactive';
    case Closed           = 'closed';
    case UnderMaintenance = 'under_maintenance';

    public function label(): string
    {
        return match ($this) {
            self::Active           => 'Active',
            self::Inactive         => 'Inactive',
            self::Closed           => 'Closed',
            self::UnderMaintenance => 'Under Maintenance',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active           => 'success',
            self::Inactive         => 'secondary',
            self::Closed           => 'dark',
            self::UnderMaintenance => 'warning',
        };
    }

    public static function operational(): array
    {
        return [self::Active, self::UnderMaintenance];
    }

    public static function nonOperational(): array
    {
        return [self::Inactive, self::Closed];
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}