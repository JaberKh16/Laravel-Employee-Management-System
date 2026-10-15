<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
   
    protected $commands = [
        //
    ];

    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        // usage
        // php artisan schedule:run
        // php artisan schedule:list

        // Roll up YESTERDAY every day at 00:10
        $schedule->command('logs:rollup')
            ->dailyAt('00:10')
            ->name('daily-logs-rollup')
            ->withoutOverlapping();

        // Also re-roll today's data every hour (so the dashboard is near-live)
        $schedule->command('logs:rollup --date=' . now()->toDateString())
            ->hourly()
            ->name('hourly-logs-rollup')
            ->withoutOverlapping();

        // Partition maintenance: add next month + drop >2y (02:30)
        $schedule->command('logs:partitions')
            ->dailyAt('02:30')
            ->name('daily-logs-partitions')
            ->withoutOverlapping();

        $schedule->command('logs:prune --days=30')
            ->dailyAt('03:00')
            ->withoutOverlapping();

        // Clean up expired and old soft-deleted notifications (03:00)
        Schedule::command('notifications:cleanup')
            ->daily()
            ->at('03:00')
            ->name('notifications:cleanup')
            ->description('Clean up expired and old soft-deleted notifications')
            ->withoutOverlapping()
            ->onOneServer()
            ->runInBackground();

        // daily prune of soft-deleted records (optional)
        $schedule->command('model:prune')
            ->dailyAt('03:30')
            ->name('daily-model-prune')
            ->withoutOverlapping()
            ->onOneServer()
            ->runInBackground();
    }


    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
