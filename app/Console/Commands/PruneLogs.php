<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class PruneLogs extends Command
{
    protected $signature = 'logs:prune {--days=30}'; // php artisan logs:prune --days=30

    
    protected $description = 'Delete log files older than N days';

   
    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoff = Carbon::now()->subDays($days)->getTimestamp();
        $dir = storage_path('logs');
        $count = 0;

        foreach (File::files($dir) as $file) {
            if ($file->getMTime() < $cutoff) {
                File::delete($file->getPathname());
                $this->line("Deleted {$file->getFilename()}");
                $count++;
            }
        }

        $this->info("Pruned {$count} file(s) older than {$days} days.");
        return self::SUCCESS;
    }
}
