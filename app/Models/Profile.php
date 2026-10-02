<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'middle_name',
        'phone',
        'avatar',
        'birthdate',
        'gender',
        'bio',
        'address',
        'zip_code',
        'country_id',
        'state_id',
        'city_id',
        'website',
        'linkedin',
        'twitter',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    // ---------- Relations ----------
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    // ============================================================
    // ACCESSORS
    // ============================================================


    public function getFullNameAttribute(): string
    {
        $first = $this->first_name ?? $this->user?->first_name ?? '';
        $last = $this->last_name ?? $this->user?->last_name ?? '';
        return trim("{$first} {$last}");
    }


    public function getInitialsAttribute(): string
    {
        $first = mb_substr((string) $this->first_name, 0, 1);
        $last = mb_substr((string) $this->last_name, 0, 1);

        return mb_strtoupper($first . $last) ?: 'U';
    }


    public function getAvatarUrlAttribute(): string
    {
        if (!$this->avatar) {
            // Prefer full name; fall back to username; then "User"
            $name = $this->full_name
                ?: ($this->user->username ?? null)
                ?: 'User';

            return 'https://ui-avatars.com/api/?name=' . urlencode($name);
        }

        // External URL (http/https)
        if (str_starts_with($this->avatar, 'http')) {
            return $this->avatar;
        }

        // Local storage path
        return asset('storage/' . ltrim($this->avatar, '/'));
    }

    // ============================================================
    // MUTATORS
    // ============================================================

    /**
     * Capitalize the first letter of each word in first_name.
     * "john"      → "John"
     * "john paul" → "John Paul"
     */
    public function setFirstNameAttribute($value): void
    {
        $this->attributes['first_name'] = $value
            ? ucwords(strtolower(trim($value)))
            : null;
    }

    /**
     * Same for last_name.
     */
    public function setLastNameAttribute($value): void
    {
        $this->attributes['last_name'] = $value
            ? ucwords(strtolower(trim($value)))
            : null;
    }


    public function scopeSearch($query, ?string $term)
    {
        if (!$term)
            return $query;

        return $query->where(function ($q) use ($term) {
            $q->where('first_name', 'like', "%{$term}%")
                ->orWhere('last_name', 'like', "%{$term}%")
                ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$term}%"]);
        });
    }

}
