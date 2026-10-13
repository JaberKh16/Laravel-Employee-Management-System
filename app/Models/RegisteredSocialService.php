<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class RegisteredSocialService extends Model
{
    use HasFactory, SoftDeletes;

    /** Cache key used by AppServiceProvider to inject Socialite configs. */
    public const CACHE_KEY = 'social_services.enabled';

    /** Cache TTL in seconds (default: 1 hour). */
    public const CACHE_TTL = 3600;

    protected $table = 'registered_social_services';

    protected $fillable = [
        'provider',
        'label',
        'client_id',
        'client_secret',
        'redirect_uri',
        'scopes',
        'extra',
        'is_enabled',
        'is_default',
        'sort_order',
        'icon',
        'brand_color',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scopes'     => 'array',
        'extra'      => 'array',
        'is_enabled' => 'boolean',
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Never leak the client secret through toArray()/JSON.
     * Everything else is fine to expose.
     */
    protected $hidden = [
        'client_secret',
    ];

    // ============================================================
    // BOOT — cache invalidation + normalization
    // ============================================================

    protected static function booted(): void
    {
        static::saving(function (self $model) {
            // Always normalize provider slug
            $model->provider = Str::lower(trim((string) $model->provider));

            // Fallback: derive icon/label/color from provider key if missing
            if (! $model->icon) {
                $model->icon = $model->provider;
            }

            if (! $model->label) {
                $model->label = Str::headline($model->provider);
            }

            // Ensure sort_order is an int
            $model->sort_order = (int) ($model->sort_order ?? 0);

            // If this row is being set as default, unset others
            if ($model->is_default && $model->isDirty('is_default')) {
                static::where('id', '!=', $model->id ?? 0)
                    ->where('is_default', true)
                    ->update(['is_default' => false]);
            }
        });

        static::saved(fn () => static::flushCache());
        static::deleted(fn () => static::flushCache());
        static::restored(fn () => static::flushCache());
    }

    // ============================================================
    // RELATIONS
    // ============================================================

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // ============================================================
    // SCOPES
    // ============================================================

    public function scopeEnabled(Builder $q): Builder
    {
        return $q->where('is_enabled', true);
    }

    public function scopeDisabled(Builder $q): Builder
    {
        return $q->where('is_enabled', false);
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort_order')->orderBy('label');
    }

    public function scopeProvider(Builder $q, string $provider): Builder
    {
        return $q->where('provider', Str::lower($provider));
    }

    // ============================================================
    // ACCESSORS
    // ============================================================

    public function getDisplayColorAttribute(): string
    {
        return $this->brand_color ?: '#2563eb';
    }

    public function getInitialAttribute(): string
    {
        return Str::upper(Str::substr($this->label ?? $this->provider ?? 'S', 0, 1));
    }

    /**
     * URL the login page uses for the "Sign in with X" button.
     */
    public function getAuthUrlAttribute(): string
    {
        return route('social.redirect', $this->provider);
    }

    /**
     * Whether this provider is ready to be used (creds + redirect).
     */
    public function getIsConfiguredAttribute(): bool
    {
        return ! empty($this->client_id)
            && ! empty($this->client_secret)
            && ! empty($this->redirect_uri);
    }

    // ============================================================
    // HELPERS
    // ============================================================

    /**
     * Config array Socialite expects.
     */
    public function toSocialiteConfig(): array
    {
        return [
            'client_id'     => $this->client_id,
            'client_secret' => $this->client_secret,
            'redirect'      => $this->redirect_uri,
        ];
    }

    /**
     * Register this service's credentials into Laravel's runtime config
     * so `Socialite::driver($provider)` picks them up.
     */
    public function registerIntoConfig(): void
    {
        config()->set("services.{$this->provider}", $this->toSocialiteConfig());
    }

    /**
     * Bootstraps ALL enabled services into config in one call.
     * Uses cache to avoid a DB hit on every request.
     */
    public static function bootstrapIntoConfig(): void
    {
        $rows = Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            try {
                return static::query()
                    ->enabled()
                    ->ordered()
                    ->get()
                    ->map(fn (self $s) => [
                        'provider'      => $s->provider,
                        'client_id'     => $s->client_id,
                        'client_secret' => $s->client_secret,
                        'redirect'      => $s->redirect_uri,
                        'scopes'        => $s->scopes ?? [],
                        'is_default'    => $s->is_default,
                    ])
                    ->values()
                    ->all();
            } catch (\Throwable $e) {
                // Table may not exist yet (e.g. during first migrate)
                return [];
            }
        });

        foreach ($rows as $row) {
            config()->set("services.{$row['provider']}", [
                'client_id'     => $row['client_id'],
                'client_secret' => $row['client_secret'],
                'redirect'      => $row['redirect'],
            ]);
        }
    }

    /**
     * Manually forget the cached list (called on save/delete + by admin controller).
     */
    public static function flushCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }

    /**
     * The "default" service row (if any).
     */
    public static function default(): ?self
    {
        return static::query()->enabled()->where('is_default', true)->first();
    }

    /**
     * All enabled services for the login page.
     */
    public static function enabledForLogin(): \Illuminate\Support\Collection
    {
        return static::query()
            ->enabled()
            ->ordered()
            ->get()
            ->filter(fn (self $s) => $s->is_configured);
    }
}