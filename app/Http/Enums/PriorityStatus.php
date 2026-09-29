<?php

namespace App\Http\Enums;

enum PriorityStatus: string
{
    case Low    = 'low';
    case Medium = 'medium';
    case High   = 'high';
    case Urgent = 'urgent';

    public function label(): string
    {
        return match ($this) {
            self::Low    => 'Low',
            self::Medium => 'Medium',
            self::High   => 'High',
            self::Urgent => 'Urgent',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Low    => 'secondary',
            self::Medium => 'info',
            self::High   => 'warning',
            self::Urgent => 'danger',
        };
    }

    public function weight(): int
    {
        return match ($this) {
            self::Low    => 1,
            self::Medium => 2,
            self::High   => 3,
            self::Urgent => 4,
        };
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