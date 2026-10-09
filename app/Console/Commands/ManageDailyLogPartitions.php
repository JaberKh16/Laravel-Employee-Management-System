<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ManageDailyLogPartitions extends Command
{
    protected $signature = 'logs:partitions'; // php artisan logs:partitions
    protected $description = 'Add next month + drop old daily_logs partitions';


    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $next = Carbon::now()->startOfMonth()->addMonths(2);
        $pname = 'p' . $next->format('Y_m');

        $exists = DB::table('information_schema.partitions')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'daily_logs')
            ->where('partition_name', $pname)
            ->exists();

        if (!$exists) {
            $this->info("Adding partition {$pname} (< {$next->toDateString()})");
            DB::statement(sprintf(
                "ALTER TABLE daily_logs REORGANIZE PARTITION pmax INTO (
                    PARTITION %s VALUES LESS THAN (TO_DAYS('%s')),
                    PARTITION pmax VALUES LESS THAN MAXVALUE
                )",
                $pname,
                $next->toDateString()
            ));
        } else {
            $this->line("Partition {$pname} already exists.");
        }

        // Drop partitions older than 2 years
        $cutoff = Carbon::now()->subYears(2)->startOfMonth();
        $old = DB::table('information_schema.partitions')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', 'daily_logs')
            ->where('partition_name', '!=', 'pmax')
            ->get(['partition_name']);

        foreach ($old as $p) {
            if (preg_match('/^p(\d{4})_(\d{2})$/', $p->partition_name, $m)) {
                $date = Carbon::createFromDate((int) $m[1], (int) $m[2], 1);
                if ($date->lt($cutoff)) {
                    $this->warn("Dropping old partition {$p->partition_name}");
                    DB::statement("ALTER TABLE daily_logs DROP PARTITION {$p->partition_name}");
                }
            }
        }

        return self::SUCCESS;
    }
}
