<?php

namespace App\Models;

use App\Http\Casts\EnumCast;
use App\Http\Enums\ActiveStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'name',
        'description',
        'floor',
        'status',
        'manager_id',
    ];

    protected $casts = [
        'status' => EnumCast::class . ':' . ActiveStatus::class,
    ];

    /* ---------- Relations ---------- */

    public function manager()
    {
        return $this->belongsTo(\App\Models\User::class, 'manager_id');
    }

    /* ---------- Scopes ---------- */

    public function scopeSearch($query, ?string $term)
    {
        if (!$term) return $query;

        return $query->where('name', 'like', "%{$term}%");
    }

    public function scopeActive($query)
    {
        return $query->where('status', ActiveStatus::Active->value);
    }

    /* ---------- Helpers ---------- */

    public function isActive(): bool
    {
        return $this->status === ActiveStatus::Active;
    }
}