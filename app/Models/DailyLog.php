<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyLog extends Model
{
    use HasFactory;
    
    protected $table = 'daily_logs';

    protected $fillable = [
        'user_id',
        'logged_at',
        'metric',
        'value',
        'meta',
    ];

    protected $casts = [
        'logged_at' => 'datetime',
        'meta' => 'array',
        'value' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBetween($query, $from, $to)
    {
        return $query->whereBetween('logged_at', [$from, $to]);
    }

    public function scopeMetric($query, string $metric)
    {
        return $query->where('metric', $metric);
    }
}
