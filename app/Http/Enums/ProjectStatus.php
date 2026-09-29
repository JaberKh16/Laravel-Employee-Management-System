<?php

namespace App\Http\Enums;

enum ProjectStatus: string
{
    case Active     = 'active';
    case Inactive   = 'inactive';
    case Planning   = 'planning';
    case InProgress = 'in_progress';
    case OnHold     = 'on_hold';
    case Completed  = 'completed';
    case Cancelled  = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Active     => 'Active',
            self::Inactive   => 'Inactive',
            self::Planning   => 'Planning',
            self::InProgress => 'In Progress',
            self::OnHold     => 'On Hold',
            self::Completed  => 'Completed',
            self::Cancelled  => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active     => 'success',
            self::Inactive   => 'secondary',
            self::Planning   => 'info',
            self::InProgress => 'primary',
            self::OnHold     => 'warning',
            self::Completed  => 'success',
            self::Cancelled  => 'danger',
        };
    }

    /**
     * Statuses where the project is still being worked on.
     *
     * @return array<int, self>
     */
    public static function open(): array
    {
        return [
            self::Active,
            self::Planning,
            self::InProgress,
            self::OnHold,
        ];
    }

    /**
     * Statuses that mean the project is finished (one way or another).
     *
     * @return array<int, self>
     */
    public static function closed(): array
    {
        return [
            self::Inactive,
            self::Archived,
            self::Completed,
            self::Cancelled,
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