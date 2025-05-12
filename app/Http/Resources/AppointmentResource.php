<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $appointmentIds = [];
        $working_status = json_decode($this->working_status);

        // Calculate waiting time
        if (!empty($this->tot_spend_time)) {
            $tot_spend_time = json_decode($this->tot_spend_time, true);
            $waitingTime = (int) $tot_spend_time['hours'] . ":" . $tot_spend_time['mins'];
        } else {
            $waitingTime = (int) 0;
        }

        // Check appointment plan periods
        if (isset($this->AppointmentOrder->PlanPeriods) && count($this->AppointmentOrder->PlanPeriods) > 0) {
            $appointment_ids = "";
            foreach ($this->AppointmentOrder->PlanPeriods as $val) {
                $appointment_ids .= $val->appointment_ids . ",";
            }
            if (!empty($appointment_ids)) {
                $appointmentIds = explode(",", $appointment_ids);
            }
        }

        $appointmentOrder = @$this->AppointmentOrder;
        $appointmentTxn = @$this->AppointmentTxn;
        $user = @$this->User;
        $meta_data = @json_decode(@$appointmentOrder->meta_data);

        // Determine payment status
        $paymentstatus = null;
        if ((!empty($appointmentTxn) && @$appointmentOrder->type == "1") || empty($appointmentOrder)) {
            $paymentstatus = 'Paid';
        } else if (@$appointmentOrder->type == "0") {
            if (in_array($this->id, $appointmentIds)) {
                $paymentstatus = "Free By Plan";
            } else {
                $paymentstatus = "Free Direct";
            }
        } else if (@$appointmentOrder->type == "2") {
            $paymentstatus = 'Cash';
        }

        // Determine visit status
        $visitSts = ($this->visit_type == 6) ? "(Follow Up)" : "";

        // Determine booking source
        if (isset($appointmentOrder)) {
            if ($appointmentOrder->order_from == '1') {
                $bookfrom = "APP";
            } elseif ($appointmentOrder->order_from == '0') {
                $bookfrom = "WEB";
            } elseif ($appointmentOrder->order_from == '2') {
                $bookfrom = "Admin";
            }

            if (isset($appointmentTxn) && !empty($appointmentTxn->received_by)) {
                $bookfrom .= " " . getNameByLoginId($appointmentTxn->received_by);
            }
            if (isset($meta_data->isPaytmTab) && $meta_data->isPaytmTab == "true") {
                $bookfrom .= " (Paytm)";
            } elseif (isset($meta_data->organization)) {
                $bookfrom .= " " . $meta_data->organization;
            }
            if (isset($appointmentOrder) && $appointmentOrder->hg_miniApp == 1) {
                $bookfrom = "(Help India)";
            }
        } else {
            if (@$this->app_click_status == '5') {
                $bookfrom = "APP";
            } elseif (@$this->app_click_status == '6') {
                $bookfrom = "WEB";
            }
        }

        // Determine appointment status
        if (@$this->status != '1') {
            $status = 'Cancelled';
        } elseif (@$this->appointment_confirmation == '1') {
            $status = 'Confirmed';
        } else {
            $status = 'Pending';
        }

        // Determine transaction status
        $pStatus = !empty($appointmentTxn) ? @$appointmentTxn->tran_status : '';

        // Determine doctor fee to pay
        $docFeeToPay = 0;
        if (!empty($appointmentOrder) && @$appointmentOrder->type == "0") {
            $docFeeToPay = @$this->Doctors->DoctorData->plan_consult_fee;
        } elseif (!empty($appointmentOrder) && @$appointmentOrder->type != "0" && $this->type == '3' && @$meta_data->isDirectAppt == '0') {
            $docFeeToPay = number_format(@$appointmentOrder->order_subtotal, 2);
        } else {
            $docFeeToPay = number_format(@$this->Doctors->DoctorData->plan_consult_fee, 2);
        }

        // Determine lab tests
        $userLabs = "";
        if (@$this->PatientLabs) {
            foreach ($this->PatientLabs as $raw) {
                $userLabs .= $raw->pack_status == 1 ? @$raw->LabPack->title . "," : @$raw->labs->title . ",";
            }
        }

      
       

        // Determine diagnostic imaging
        $diagnosticImaging = @$this->PatientDiagnosticImagings->appointment_id ? 'Yes' : 'No';

        // Return the array with all the fields
        return [
            'Sr No' => '',
             'AppointmentID' => $this->id,
            'Appointment Date' => date('d-m-Y', strtotime(@$this->start)),
            'Time' => date('H:i:s', strtotime(@$this->start)),
            'Checkout Date' => !empty($this->check_out) ? date('d-m-Y H:i:s', @$this->check_out) : '',
            'Order ID' => @$appointmentOrder->id,
            'Disease' => str_replace(',', '0', getChiefComplaints(@$this->chiefComplaints)),
            'Lab Test' => $userLabs,
            'Diagnostic Imaging' => $diagnosticImaging,
            'Doctor Name' => @$this->Doctors->first_name . " " . @$this->Doctors->last_name . " (" . @$this->Doctors->id . ") (" . @$this->Doctors->mobile . ")",
            'Patient Name' => @$this->Patient->first_name . " " . @$this->Patient->last_name . " (" . @$this->pId . ") (" . @$this->Patient->mobile_no . ")",
            'Gender/Age' => @$this->Patient->gender . "/" . get_patient_age(@$this->Patient->dob),
            'Type' => @$this->type == '3' ? 'Tele Consult' : 'In Clinic',
            'Doc Fee To Pay' => $docFeeToPay,
            'Consultation Fee (Rs.)' => @$appointmentOrder->order_subtotal ?? @$this->consultation_fees,
            'Total Pay (Rs.)' => @$appointmentOrder->order_total ?? @$this->consultation_fees,
            'Payment Status' => @$paymentstatus . " " . @$visitSts,
            'From' => @$bookfrom,
            'Rating' => (!empty(@$appointmentOrder->rating)) ? @$appointmentOrder->rating . " STAR" : "",
            'Organization' => (!empty(@$this->UserPP->OrganizationMaster)) ? @$this->UserPP->OrganizationMaster->title : '',
            'Created At' => date('d-m-Y', strtotime(@$this->created_at)),
            'Created At Time' => date('H:i:s', strtotime(@$this->created_at)),
            'Status' => $status,
            'Payment Transaction Status' => $pStatus,
            'Working Status' => !$working_status ? 'Open' : 'Closed',
            'Waiting Time' => $waitingTime,
            'Referral Code' => isset($this->UserPP->UsersSubscriptions) && $this->UserPP->UsersSubscriptions->order_status == 1 ? @$this->UserPP->UsersSubscriptions->ReferralMaster->code : ''
        ];
    }
}
