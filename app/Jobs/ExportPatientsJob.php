<?php
namespace App\Jobs;

use App\Models\Exports\QueriesExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Maatwebsite\Excel\Facades\Excel;

class ExportPatientsJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $patData;
    protected $filename;

    public function __construct($patData, $filename)
    {
        $this->patData = $patData; // Now passing the data array, not the query
        $this->filename = $filename;
    }

    public function handle()
    {
        try {
            // Prepare the array for Excel export
            $patDataArray = array();
            $patDataArray[] = array('Sr. No.', 'Appointment', 'Subscribed', 'Organization', 'Registered Type', 'Name', 'Gender', 'Age', 'Mobile',
                'Email', 'Address', 'City', 'State', 'Location', 'Note', 'Date');
            
            foreach ($this->patData as $i => $pat) {
                $locality = "";
                $subAdministrativeArea = "";
                $administrativeArea = "";
                $dType = $this->getDeviceType($pat['device_type'], $pat['login_type']);
                
                if (!empty($pat['location_meta'])) {
                    $location_meta = json_decode($pat['location_meta']);
                    $locality = $location_meta->locality ?? '';
                    $subAdministrativeArea = $location_meta->subAdministrativeArea ?? '';
                    $administrativeArea = $location_meta->administrativeArea ?? '';
                }

                $patDataArray[] = array(
                    $i + 1,
                    $pat['pId'] ? $pat['tot_appointment'] : 0,
                    !empty($pat['UsersSubscriptions']) ? 'Yes' : 'No',
                    $pat['OrganizationMaster']['title'] ?? '',
                    $dType,
                    $pat['first_name'] . " " . $pat['last_name'],
                    $pat['gender'],
                    get_patient_age($pat['dob']),
                    $pat['mobile_no'],
                    $pat['email'],
                    $pat['address'],
                    $pat['State']['name'] ?? '',
                    $pat['getCityName']['name'] ?? '',
                    $locality . ", " . $subAdministrativeArea . ", " . $administrativeArea,
                    $pat['note'],
                    ($pat['created_at'] != null) ? date("d-m-Y H:i", strtotime($pat['created_at'])) : "",
                );
            }
$filePath =  $this->filename;


        // Store the Excel file in the public/export directory
        Excel::store(new QueriesExport($patDataArray), $filePath, 'public_export');

            // Export the Excel file
        //    Excel::store(new QueriesExport($patDataArray), $this->filename);

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
        }
    }

    private function getDeviceType($device_type, $login_type)
    {
        switch ($device_type) {
            case "1":
                return "Android";
            case "2":
                return "IOS";
            case "3":
                return ($login_type == "3") ? "PAYTM" : "WEB";
            default:
                return "Unknown";
        }
    }
}
