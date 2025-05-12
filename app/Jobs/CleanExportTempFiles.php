<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CleanExportTempFiles implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // Delete all temporary export files older than 1 hour
        $files = Storage::disk('temp')->files();
        $now = now()->timestamp;
        
        foreach ($files as $file) {
            if (str_contains($file, 'appointments_part_')) {
                $lastModified = Storage::disk('temp')->lastModified($file);
                if ($now - $lastModified > 3600) { // 1 hour
                    Storage::disk('temp')->delete($file);
                }
            }
        }
    }
}
