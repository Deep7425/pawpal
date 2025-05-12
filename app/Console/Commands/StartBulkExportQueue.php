<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Artisan;

class StartBulkExportQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:start-bulk-export';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start the queue worker for bulk export CSV queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Run the Artisan command to start the queue worker
        Artisan::call('queue:work', [
            '--queue' => 'bulkExportCSV,default',
            '--stop-when-empty' => true
        ]);

        $this->info('Queue worker started for bulkExportCSV');
    }
}
