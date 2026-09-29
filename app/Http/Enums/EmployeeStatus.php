<?php

namespace App\Http\Enums;

enum EmployeeStatus: string
{
    case Active     = 'active';
    case Inactive   = 'inactive';
    case OnLeave    = 'on_leave';
    case Suspended  = 'suspended';
    case Resigned   = 'resigned';
    case Terminated = 'terminated';

    public function label(): string
    {
        return match ($this) {
            self::Active     => 'Active',
            self::Inactive   => 'Inactive',
            self::OnLeave    => 'On Leave',
            self::Suspended  => 'Suspended',
            self::Resigned   => 'Resigned',
            self::Terminated => 'Terminated',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active     => 'success',
            self::Inactive   => 'secondary',
            self::OnLeave    => 'warning',
            self::Suspended  => 'danger',
            self::Resigned   => 'dark',
            self::Terminated => 'danger',
        };
    }

    /**
     * Statuses where the employee is still on the payroll.
     *
     * @return array<int, self>
     */
    public static function employed(): array
    {
        return [
            self::Active,
            self::OnLeave,
            self::Suspended,
        ];
    }

    /**
     * Statuses where the employee has left the company.
     *
     * @return array<int, self>
     */
    public static function separated(): array
    {
        return [
            self::Resigned,
            self::Terminated,
        ];
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->label();
        }
        return $options;
    }
}