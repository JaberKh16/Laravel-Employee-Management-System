<?php

namespace App\Http\Enums;

enum UserStatus: string
{
    case Active   = 'active';
    case Inactive = 'inactive';
    case Prospect = 'prospect';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Active   => 'Active',
            self::Inactive => 'Inactive',
            self::Prospect => 'Prospect',
            self::Archived => 'Archived',
        };
    }
}