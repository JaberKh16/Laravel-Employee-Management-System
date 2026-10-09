<?php
// app/Services/DailyLogService.php

namespace App\Services;

use App\Models\DailyLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DailyLogService
{
    /** Insert a single log. */
    public function log(int $userId, string $metric, float $value, ?array $meta = null, ?Carbon $when = null): DailyLog
    {
        return DailyLog::create([
            'user_id' => $userId,
            'metric' => $metric,
            'value' => $value,
            'meta' => $meta,
            'logged_at' => $when ?? now(),
        ]);
    }

    /** Bulk insert — much faster for volume. */
    public function logBatch(array $rows): void
    {
        $now = now();
        $prepared = collect($rows)->map(fn($r) => [
            'user_id' => $r['user_id'],
            'metric' => $r['metric'],
            'value' => $r['value'],
            'meta' => isset($r['meta']) ? json_encode($r['meta']) : null,
            'logged_at' => $r['logged_at'] ?? $now,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        // Chunk to avoid max_allowed_packet issues
        foreach (array_chunk($prepared, 1000) as $chunk) {
            DB::table('daily_logs')->insert($chunk);
        }
    }

    /** Rebuild the summary for a given day (idempotent). */
    public function rollupDay(Carbon $day): int
    {
        $start = $day->copy()->startOfDay();
        $end = $day->copy()->endOfDay();

        // Aggregate in one pass, then upsert.
        $rows = DB::table('daily_logs')
            ->selectRaw('user_id, metric, DATE(logged_at) as bucket, COUNT(*) as samples,
                         SUM(value) as total, AVG(value) as average,
                         MIN(value) as min_value, MAX(value) as max_value')
            ->whereBetween('logged_at', [$start, $end])
            ->groupBy('user_id', 'metric', 'bucket')
            ->get();

        if ($rows->isEmpty()) {
            return 0;
        }

        $now = now();
        $payload = $rows->map(fn($r) => [
            'user_id' => $r->user_id,
            'metric' => $r->metric,
            'bucket' => $r->bucket,
            'samples' => $r->samples,
            'total' => $r->total,
            'average' => $r->average,
            'min_value' => $r->min_value,
            'max_value' => $r->max_value,
            'created_at' => $now,
            'updated_at' => $now,
        ])->all();

        // MySQL 8 upsert
        DB::table('daily_logs_summary')->upsert(
            $payload,
            ['user_id', 'metric', 'bucket'],              // unique key
            ['samples', 'total', 'average', 'min_value', 'max_value', 'updated_at']
        );

        return count($payload);
    }

    /** Read daily rollups for a date range. */
    public function dailyForUser(int $userId, string $metric, Carbon $from, Carbon $to): Collection
    {
        return DB::table('daily_logs_summary')
            ->where('user_id', $userId)
            ->where('metric', $metric)
            ->whereBetween('bucket', [$from->toDateString(), $to->toDateString()])
            ->orderBy('bucket')
            ->get();
    }

    /** Raw samples. */
    public function samples(int $userId, string $metric, Carbon $from, Carbon $to): Collection
    {
        return DailyLog::query()
            ->where('user_id', $userId)
            ->metric($metric)
            ->between($from, $to)
            ->orderBy('logged_at')
            ->get();
    }

    /** Optional: prune raw rows older than N days (partitions drop faster). */
    public function pruneRaw(int $days = 730): int
    {
        return DB::table('daily_logs')
            ->where('logged_at', '<', now()->subDays($days))
            ->delete();
    }
}