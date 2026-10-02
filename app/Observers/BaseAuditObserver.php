<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

abstract class BaseAuditObserver
{
    abstract protected function label(): string;

    public function created(Model $model): void
    {
        $this->log('created', $model);
    }
    public function deleted(Model $model): void
    {
        $this->log('deleted', $model);
    }
    public function restored(Model $model): void
    {
        $this->log('restored', $model);
    }
    public function forceDeleted(Model $model): void
    {
        $this->log('forceDeleted', $model);
    }

    public function updated(Model $model): void
    {
        $this->log('updated', $model, [
            'changes' => $model->getChanges(),
            'original' => $model->getOriginal(),
        ]);
    }

    protected function log(string $event, Model $model, array $extra = []): void
    {
        Log::info("{$this->label()} {$event}", array_merge([
            'id' => $model->getKey(),
            'attributes' => $model->getAttributes(),
            'actor_id' => auth()->id(),
            'actor_name' => optional(auth()->user())->username ?? 'system',
            'ip' => request()->ip(),
        ], $extra));
    }
}