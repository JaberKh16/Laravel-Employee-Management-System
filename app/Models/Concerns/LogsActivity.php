<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Log;
use Throwable;

trait LogsActivity
{
    protected function actor(): array
    {
        $user = auth()->user();
        return [
            'by_id'       => $user?->id,
            'by_username' => $user?->username ?? 'system',
        ];
    }

    protected function logContext(array $extra = []): array
    {
        return array_merge([
            'model' => static::class,
            'id'    => $this->getKey(),
        ], $this->actor(), $extra);
    }

    public function logActivity(string $action, string $level = 'info', array $extra = []): void
    {
        try {
            Log::channel('employee')->log(
                $level,
                sprintf('%s %s #%s', ucfirst($action), class_basename($this), $this->getKey()),
                $this->logContext($extra)
            );
        } catch (Throwable $e) {
            // Never let logging break business logic
            Log::warning('logActivity failed', [
                'model'  => static::class,
                'id'     => $this->getKey(),
                'action' => $action,
                'error'  => $e->getMessage(),
            ]);
        }
    }
}