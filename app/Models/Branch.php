<?php

namespace App\Models;

use App\Http\Casts\EnumCast;
use App\Http\Enums\BranchStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'branch';

    protected $fillable = [
        'name',
        'code',
        'description',   // added — factory writes it, so it must be fillable
        'email',
        'phone',
        'address',
        'country_id',
        'state_id',
        'city_id',
        'zip_code',
        'manager_name',
        'status',
    ];

    protected $casts = [
        'status' => EnumCast::class . ':' . BranchStatus::class,
    ];

    /* ---------- Relations ---------- */
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

    /* ---------- Scopes ---------- */
    public function scopeSearch($query, ?string $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('email', 'like', "%{$term}%")
                ->orWhere('phone', 'like', "%{$term}%");
        });
    }

    public function scopeOperational($query)
    {
        return $query->whereIn('status', array_map(
            fn(BranchStatus $s) => $s->value,
            BranchStatus::operational()
        ));
    }

    /* ---------- Accessors ---------- */
    public function isOperational(): bool
    {
        return in_array($this->status, BranchStatus::operational(), true);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->status?->label() ?? '—';
    }

    public function getInitialsAttribute(): string
    {
        $name = $this->name ?? 'B';
        $parts = preg_split('/\s+/', trim($name));
        $initials = '';
        foreach (array_slice($parts, 0, 2) as $p) {
            $initials .= mb_substr($p, 0, 1);
        }

        return mb_strtoupper($initials ?: 'B');
    }

    public function getLocationAttribute(): string
    {
        return collect([
            $this->city?->name,
            $this->state?->name,
            $this->country?->name,
        ])->filter()->implode(', ');
    }
}