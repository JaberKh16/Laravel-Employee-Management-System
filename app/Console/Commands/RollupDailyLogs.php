<?php

namespace App\Console\Commands;

use App\Services\DailyLogService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class RollupDailyLogs extends Command
{
    protected $signature = 'logs:rollup {--date= : YYYY-MM-DD, defaults to yesterday}'; 
    // php artisan logs:rollup 
    // php artisan logs:rollup --date=YYYY-MM-DD
    protected $description = 'Build daily_logs_summary for a given day';

  
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DailyLogService $logs): int
    {
        $date = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday();

        $count = $logs->rollupDay($date);
        $this->info("Rolled up {$count} rows for {$date->toDateString()}");

        return self::SUCCESS;
    }
}
