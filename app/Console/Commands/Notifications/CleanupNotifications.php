<?php

namespace App\Console\Commands\Notifications;

use App\Models\Notification;
use Illuminate\Console\Command;

class CleanupNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup
                            {--expired-days=0 : Delete expired notifications after N days past expiry (0 = immediately)}
                            {--trashed-days=90 : Force-delete soft-deleted notifications older than N days}
                            {--dry-run : Show what would be deleted without actually deleting}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired and old soft-deleted notifications';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $expiredAfter = (int) $this->option('expired-days');
        $trashedAfter = (int) $this->option('trashed-days');
        $dryRun = (bool) $this->option('dry-run');

        $this->info('🧹  Notification cleanup started');
        $this->newLine();

        // ── Step 1: Soft-delete expired notifications ─────────
        $expiredQuery = Notification::query()
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now()->subDays($expiredAfter));

        $expiredCount = $expiredQuery->count();

        if ($expiredCount > 0) {
            $this->line("  <fg=yellow>Expired:</> {$expiredCount} notification(s) past expiry" .
                ($expiredAfter > 0 ? " (older than {$expiredAfter} day(s))" : ''));

            if ($dryRun) {
                $this->line('  <fg=gray>↳ dry-run, skipping delete</>');
            } else {
                $expiredQuery->delete(); // soft delete
                $this->line('  <fg=green>↳ soft-deleted</>');
            }
        } else {
            $this->line('  <fg=gray>Expired:</> none');
        }

        // ── Step 2: Force-delete old trashed notifications ────
        $trashedQuery = Notification::onlyTrashed()
            ->where('deleted_at', '<', now()->subDays($trashedAfter));

        $trashedCount = $trashedQuery->count();

        if ($trashedCount > 0) {
            $this->line("  <fg=yellow>Trashed:</> {$trashedCount} soft-deleted row(s) older than {$trashedAfter} day(s)");

            if ($dryRun) {
                $this->line('  <fg=gray>↳ dry-run, skipping force-delete</>');
            } else {
                $trashedQuery->forceDelete();
                $this->line('  <fg=green>↳ permanently removed</>');
            }
        } else {
            $this->line('  <fg=gray>Trashed:</> none');
        }

        // ── Step 3: Prune dismissed older than 30 days ────────
        $dismissedQuery = Notification::onlyTrashed()
            ->where('is_dismissed', true)
            ->where('dismissed_at', '<', now()->subDays(30));

        $dismissedCount = $dismissedQuery->count();

        if ($dismissedCount > 0) {
            $this->line("  <fg=yellow>Dismissed:</> {$dismissedCount} dismissed older than 30 day(s)");

            if ($dryRun) {
                $this->line('  <fg=gray>↳ dry-run, skipping</>');
            } else {
                $dismissedQuery->forceDelete();
                $this->line('  <fg=green>↳ permanently removed</>');
            }
        } else {
            $this->line('  <fg=gray>Dismissed:</> none');
        }

        // ── Summary ───────────────────────────────────────────
        $this->newLine();
        $total = $expiredCount + $trashedCount + $dismissedCount;

        if ($dryRun) {
            $this->warn("🔍  Dry-run complete. Would process {$total} row(s).");
        } elseif ($total === 0) {
            $this->info('✅  Nothing to clean up.');
        } else {
            $this->info("✅  Cleanup complete. Processed {$total} row(s).");
        }

        return self::SUCCESS;
    }
}