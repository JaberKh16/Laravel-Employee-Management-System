<?php

namespace App\Exports;

use App\Http\Enums\ActiveStatus;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(protected $users) {}

    public function collection()
    {
        return $this->users;
    }

    /**
     * Column order must match map() order exactly.
     */
    public function headings(): array
    {
        return [
            // --- User ---
            'ID',
            'Username',
            'Email',
            'Status',
            'Email Verified At',

            // --- Profile: Identity ---
            'First Name',
            'Middle Name',
            'Last Name',
            'Full Name',
            'Initials',
            'Birthdate',
            'Age',
            'Gender',

            // --- Profile: Contact ---
            'Phone',
            'Website',
            'LinkedIn',
            'Twitter',

            // --- Profile: Address ---
            'Address',
            'Zip Code',
            'City',
            'State',
            'Country',

            // --- Profile: Media ---
            'Avatar URL',
            'Bio',

            // --- Meta ---
            'Created At',
            'Updated At',
        ];
    }

    public function map($user): array
    {
        $profile = $user->profile;

        return [
            // --- User ---
            $user->id,
            (string) $user->username,
            (string) $user->email,
            $this->resolveStatus($user->status),
            optional($user->email_verified_at)->format('Y-m-d H:i:s'),

            // --- Profile: Identity ---
            $this->str($profile?->first_name),
            $this->str($profile?->middle_name),
            $this->str($profile?->last_name),
            $this->fullName($profile, $user),
            $this->initials($profile, $user),
            $this->date($profile?->birthdate),
            $this->age($profile?->birthdate),
            $this->gender($profile?->gender),

            // --- Profile: Contact ---
            $this->str($profile?->phone),
            $this->str($profile?->website),
            $this->str($profile?->linkedin),
            $this->str($profile?->twitter),

            // --- Profile: Address ---
            $this->str($profile?->address),
            $this->str($profile?->zip_code),
            $this->str(optional($profile?->city)->name),
            $this->str(optional($profile?->state)->name),
            $this->str(optional($profile?->country)->name),

            // --- Profile: Media ---
            $this->str($profile?->avatar_url),
            $this->str($profile?->bio),

            // --- Meta ---
            optional($user->created_at)->format('Y-m-d H:i:s'),
            optional($user->updated_at)->format('Y-m-d H:i:s'),
        ];
    }

    // ============================================================
    // SAFE FORMATTERS
    // ============================================================

    /**
     * Convert enum / int / string to a human-readable status.
     */
    protected function resolveStatus($status): string
    {
        if ($status instanceof ActiveStatus) {
            return $status->label();
        }

        if ($status === null) {
            return 'Unknown';
        }

        return ActiveStatus::tryFrom((int) $status)?->label()
            ?? (string) $status;
    }

    /**
     * Null-safe string cast.
     */
    protected function str($value): string
    {
        return (string) ($value ?? '');
    }

    /**
     * Format a date-ish value to Y-m-d (or '' if null).
     */
    protected function date($value): string
    {
        if (!$value) return '';

        // Carbon instance (from $casts = ['birthdate' => 'date'])
        if ($value instanceof \DateTimeInterface) {
            return $value->format('Y-m-d');
        }

        // String fallback
        try {
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        } catch (\Throwable) {
            return (string) $value;
        }
    }

    /**
     * Compute age in years from birthdate.
     */
    protected function age($birthdate): string
    {
        if (!$birthdate) return '';

        try {
            return (string) \Carbon\Carbon::parse($birthdate)->age;
        } catch (\Throwable) {
            return '';
        }
    }

    /**
     * Prefer profile full_name; fall back to user->username.
     */
    protected function fullName($profile, $user): string
    {
        // Use the profile accessor if available
        if ($profile && !empty($profile->full_name)) {
            return $profile->full_name;
        }

        // Fallback: first + last on user (if your users table has them)
        $first = $user->first_name ?? '';
        $last  = $user->last_name  ?? '';
        $full  = trim("{$first} {$last}");

        return $full !== '' ? $full : (string) $user->username;
    }

    /**
     * Prefer the profile initials accessor; fall back to username's first 2 chars.
     */
    protected function initials($profile, $user): string
    {
        if ($profile && !empty($profile->initials)) {
            return $profile->initials;
        }

        return strtoupper(mb_substr((string) $user->username, 0, 2)) ?: 'U';
    }

    /**
     * Normalize gender to a title-cased label.
     */
    protected function gender($gender): string
    {
        if (!$gender) return '';

        // If you later cast gender to an enum, call ->label() here
        return ucfirst((string) $gender);
    }
}