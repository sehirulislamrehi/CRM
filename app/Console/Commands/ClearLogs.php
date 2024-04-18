<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearLogs extends Command
{
    protected $signature = 'logs:clear';

    protected $description = 'Clear log files';

    public function handle(): void
    {
        $logPath = storage_path('logs');

        // Use File::glob to get all log files in storage/logs
        $logFiles = File::glob($logPath . '/*.log');

        if (!empty($logFiles)) {
            // Delete each log file
            foreach ($logFiles as $file) {
                File::delete($file);
            }

            $this->info('Log files in storage/logs have been cleared!');
        } else {
            $this->info('No log files found in storage/logs.');
        }
    }
}
