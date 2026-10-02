<?php

namespace App\Http\Enums;  

enum BranchStatus: string
{
    case Active           = 'active';
    case Inactive         = 'inactive';
    case Closed           = 'closed';
    case UnderMaintenance = 'under_maintenance';

    /**
     * All backed values — used by migrations & validation rules.
     *
     * @return string[]
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Statuses that count as "operational".
     *
     * @return BranchStatus[]
     */
    public static function operational(): array
    {
        return [self::Active, self::UnderMaintenance];
    }

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
            self::Active           => 'emerald',
            self::Inactive         => 'gray',
            self::Closed           => 'red',
            self::UnderMaintenance => 'amber',
        };
    }
}