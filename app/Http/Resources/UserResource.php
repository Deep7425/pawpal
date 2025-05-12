<?php

namespace App\Http\Resources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Decoding the location metadata
        $locality = "";
        $subAdministrativeArea = "";
        $administrativeArea = "";
        $location_meta = json_decode($this->location_meta);
        
        if (!empty($location_meta)) {
            $locality = $location_meta->locality ?? '';
            $subAdministrativeArea = $location_meta->subAdministrativeArea ?? '';
            $administrativeArea = $location_meta->administrativeArea ?? '';
        }

        // Handling device type
        $dType = "";
        if ($this->device_type == "1") {
            $dType = "Android";
        } elseif ($this->device_type == "2") {
            $dType = "IOS";
        } elseif ($this->device_type == "3") {
            if ($this->login_type == "3") {
                $dType = "PAYTM";
            } else {
                $dType = "WEB";
            }
        }

        // Return the array with all the fields
        return [
            'Sr No' => $this->id,
            'Appointment' => !empty($this->pId) ? $this->tot_appointment : 0,
            'Subscribed' => !empty($this->UsersSubscriptions) ? 'Yes' : 'No',
            'Organization' => @$this->OrganizationMaster->title,
            'Registered Type' => $dType,
            'Name' => $this->first_name . ' ' . $this->last_name,
            'Gender' => $this->gender,
            'Age' => get_patient_age($this->dob),
            'Mobile' => $this->mobile_no,
            'Email' => $this->email,
            'Address' => $this->address,
            'State' => @$this->State->name,
            'City' => @$this->getCityName->name,
            'Location' => $locality . ', ' . $subAdministrativeArea . ', ' . $administrativeArea,
            'Note' => $this->note,
            'Date' => ($this->created_at != null) ? date("d-m-Y H:i", strtotime($this->created_at)) : '',
        ];

			
    }
}
