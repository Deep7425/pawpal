<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Facades\Excel; 
use Maatwebsite\Excel\Facades\Excel; 
use App\Models\Exports\AppointmentExport;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateAppointmentExportJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels, InteractsWithQueue;
    use Dispatchable, Queueable, SerializesModels, InteractsWithQueue;

    protected $appointments;
    protected $fileName;
    protected $fileName;

    public function __construct($appointments, $fileName)
    public function __construct($appointments, $fileName)
    {
        $this->appointments = $appointments;
        $this->fileName = $fileName;
        $this->fileName = $fileName;
    }

    public function handle()
    {
        // Define the storage path
        $path =  base_path(). 'public/exports';

        // Store the Excel file in local storage (or public disk if needed)
        Excel::store(new AppointmentExport($this->appointments),  $this->fileName, $path);

        // You can log or notify the user when the file is ready
        \Log::info("Export generated and stored at: " . $path);
    }
}
