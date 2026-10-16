<?php

namespace App\Http\Enums;

use PhpOffice\PhpSpreadsheet\Calculation\TextData\CaseConvert;

enum ActiveStatus: int
{
    case Active   = 1;
    case Inactive = 0;
    case Pending = 2;
    case Confirmed = 3;
    case Banned = 4;
   
    /**
     * All backed values — used by validation rules.
     *
     * @return int[]
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
            self::Pending => 'Pending',
            self::Confirmed  => 'Confirmed',
            self::Banned  => 'Banned'
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active   => 'emerald',
            self::Inactive => 'gray',
            self::Pending  => 'amber',
            self::Confirmed => 'green',
            self::Banned => 'red'
        };
    }
}