<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\EnumCast;                 // the generic string-enum cast
use App\Http\Enums\BranchStatus;

class Branch extends Model
{
    use HasFactory;
    protected $table = 'branch';

    protected $casts = [
        'status' => EnumCast::class . ':' . BranchStatus::class,
    ];

    public function isOperational(): bool
    {
        return in_array($this->status, BranchStatus::operational(), true);
    }

    public function scopeOperational($query)
    {
        return $query->whereIn('status', array_map(
            fn (BranchStatus $s) => $s->value,
            BranchStatus::operational()
        ));
    }
}
