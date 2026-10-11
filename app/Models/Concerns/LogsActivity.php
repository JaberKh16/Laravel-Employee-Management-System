<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Log;

trait LogsActivity
{
    protected function actor(): array
    {
        $user = auth()->user();
        return [
            'by_id' => $user?->id,
            'by_username' => $user?->username ?? 'system',
        ];
    }

    protected function logContext(array $extra = []): array
    {
        return array_merge([
            'model' => static::class,
            'id' => $this->getKey(),
        ], $this->actor(), $extra);
    }

    public function logActivity(string $action, string $level = 'info', array $extra = []): void
    {
        Log::channel('employee')->log($level, "{$action} " . class_basename($this), $this->logContext($extra));
    }
}