<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
class CompressLogs extends Command
{
    protected $signature = 'logs:compress {--days=2}';
    protected $description = 'Gzip log files older than N days';

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
            if ($file->getExtension() !== 'log')
                continue;
            if ($file->getMTime() >= $cutoff)
                continue;

            $src = $file->getPathname();
            $dst = $src . '.gz';

            $in = fopen($src, 'rb');
            $out = gzopen($dst, 'wb9');
            while (!feof($in)) {
                gzwrite($out, fread($in, 1024 * 512));
            }
            fclose($in);
            gzclose($out);

            File::delete($src);
            $this->line("Compressed {$file->getFilename()}");
            $count++;
        }

        $this->info("Compressed {$count} file(s).");
        return self::SUCCESS;
    }
}
