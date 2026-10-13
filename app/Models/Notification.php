<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'audience',
        'audience_value',
        'type',
        'category',
        'title',
        'message',
        'icon',
        'action_url',
        'action_label',
        'related_type',
        'related_id',
        'is_read',
        'read_at',
        'is_dismissed',
        'dismissed_at',
        'priority',
        'group_key',
        'group_count',
        'data',
        'expires_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'is_dismissed' => 'boolean',
        'read_at' => 'datetime',
        'dismissed_at' => 'datetime',
        'expires_at' => 'datetime',
        'data' => 'array',
        'group_count' => 'integer',
    ];

    // ── Relationships ────────────────────────────────────
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ── Scopes ───────────────────────────────────────────
    public function scopeForUser(Builder $q, int $userId): Builder
    {
        return $q->where('user_id', $userId);
    }

    public function scopeUnread(Builder $q): Builder
    {
        return $q->where('is_read', false);
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_dismissed', false)
            ->where(function ($q) {
                $q->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            });
    }

    public function scopeRecent(Builder $q, int $days = 30): Builder
    {
        return $q->where('created_at', '>=', now()->subDays($days));
    }

    // ── Helpers ──────────────────────────────────────────
    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }

    public function markAsUnread(): void
    {
        $this->update(['is_read' => false, 'read_at' => null]);
    }

    public function dismiss(): void
    {
        $this->update(['is_dismissed' => true, 'dismissed_at' => now()]);
    }

    // ── Accessors ────────────────────────────────────────
    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans(short: true);
    }

    public function getCategoryColorAttribute(): array
    {
        return match ($this->category) {
            'success' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'dark_bg' => 'dark:bg-emerald-500/10', 'dark_text' => 'dark:text-emerald-400'],
            'warning' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'dark_bg' => 'dark:bg-amber-500/10', 'dark_text' => 'dark:text-amber-400'],
            'error' => ['bg' => 'bg-red-50', 'text' => 'text-red-600', 'dark_bg' => 'dark:bg-red-500/10', 'dark_text' => 'dark:text-red-400'],
            'info' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'dark_bg' => 'dark:bg-blue-500/10', 'dark_text' => 'dark:text-blue-400'],
            'action' => ['bg' => 'bg-violet-50', 'text' => 'text-violet-600', 'dark_bg' => 'dark:bg-violet-500/10', 'dark_text' => 'dark:text-violet-400'],
            default => ['bg' => 'bg-gray-50', 'text' => 'text-gray-600', 'dark_bg' => 'dark:bg-gray-700/50', 'dark_text' => 'dark:text-gray-400'],
        };
    }

    public function prunable(): Builder
    {
        return static::onlyTrashed()
                     ->where('deleted_at', '<=', now()->subDays(90));
    }

    protected function pruning(): void
    {
        // Optional: dispatch event, log, etc.
    }
}
