<?php

namespace App\Http\Enums;
use Spatie\Permission\Models\Role;

enum RoleStatus: string
{
    case SuperAdmin = 'super_admin';
    case Admin      = 'admin';
    case Manager    = 'manager';
    case Employee   = 'employee';
    case User       = 'user';
    case Guest      = 'guest';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Administrator',
            self::Admin      => 'Administrator',
            self::Manager    => 'Manager',
            self::Employee   => 'Employee',
            self::User       => 'User',
            self::Guest      => 'Guest',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::SuperAdmin => 'purple',
            self::Admin      => 'indigo',
            self::Manager    => 'sky',
            self::Employee   => 'emerald',
            self::User       => 'gray',
            self::Guest      => 'purple',
        };
    }

    public function dotColor(): string
    {
        return match ($this) {
            self::SuperAdmin => 'bg-purple-500',
            self::Admin      => 'bg-indigo-500',
            self::Manager    => 'bg-sky-500',
            self::Employee   => 'bg-emerald-500',
            self::User       => 'bg-gray-400',
            self::Guest      => 'bg-purple-400',
        };
    }

    public static function dotColors(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $c) => [$c->value => $c->dotColor()])
            ->all();
    }

    /**
     * Safely resolve a slug from the DB into an enum case (null if unknown).
     */
    public static function fromSlug(?string $slug): ?self
    {
        return $slug ? self::tryFrom($slug) : null;
    }

    /**
     * Detect DB roles that are missing from the enum.
     * Useful in a scheduled command / health check.
     *
     * @return string[]  Slugs present in DB but missing in enum.
     */
    public static function missingFromEnum(): array
    {
        return array_diff(
            Role::query()->pluck('slug')->all(),
            self::values()
        );
    }
}