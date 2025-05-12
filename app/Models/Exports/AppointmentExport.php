<?php

namespace App\Models\Exports;

use App\Models\Doctors;
use Illuminate\Http\Request;
use App\Models\ehr\Appointments;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Carbon\Carbon;

class AppointmentExport implements FromQuery, WithHeadings, WithMapping, ShouldQueue, WithChunkReading
{
    use Exportable;

    protected $request;
    protected $rowNumber = 0;
    protected $offset = 0;
    protected $limit = 0;

    public function __construct(array $filters, int $offset = 0, int $limit = 0)
    {
        $this->filters = $filters;
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function query()
    {
        $query = Appointments::with([
            'AppointmentTxn',
            'AppointmentOrder.PlanPeriods',
            'User.DoctorInfo.docSpeciality',
            'Patient',
            'NotifyUserSms',
            'Doctors.DoctorData',
            'UserPP.OrganizationMaster',
            'PatientLabs.labs',
            'PatientLabs.LabPack',
            'chiefComplaints',
            'PatientLabsOne',
            'PatientDiagnosticImagings',
            'UserPP.UsersSubscriptions.ReferralMaster'
        ])
        ->whereIn('app_click_status', [5,6])
        ->where('appointments.added_by', '!=', 24)
        ->where('appointments.delete_status', 1);

        // Apply additional filters if necessary
        $this->applyQueryFilters($query, $this->request);

        if ($this->limit > 0) {
            $query->skip($this->offset)->take($this->limit);
        }

        return $query->orderBy('appointments.id', 'desc');
    }

     protected function applyQueryFilters($query): void
    {
        if (!empty($this->filters['date_range'])) {
            $dates = explode(' - ', $this->filters['date_range']);
            if (count($dates) === 2) {
                $startDate = Carbon::createFromFormat('m/d/Y', trim($dates[0]))->startOfDay();
                $endDate = Carbon::createFromFormat('m/d/Y', trim($dates[1]))->endOfDay();
                $query->whereBetween('appointments.start', [$startDate, $endDate]);
            }
        }

        if (!empty($this->filters['doctor_id'])) {
            $query->where('appointments.user_id', $this->filters['doctor_id']);
        }

        if (!empty($this->filters['organization_id'])) {
            $query->whereHas('UserPP.OrganizationMaster', function($q) {
                $q->where('id', $this->filters['organization_id']);
            });
        }

        if (!empty($this->filters['status'])) {
            if ($this->filters['status'] === 'cancelled') {
                $query->where('appointments.status', '!=', 1);
            } elseif ($this->filters['status'] === 'confirmed') {
                $query->where('appointments.appointment_confirmation', 1);
            } else {
                $query->where('appointments.appointment_confirmation', 0)
                      ->where('appointments.status', 1);
            }
        }

        if (!empty($this->filters['payment_status'])) {
            if ($this->filters['payment_status'] === 'paid') {
                $query->whereHas('AppointmentTxn');
            } else {
                $query->whereDoesntHave('AppointmentTxn');
            }
        }
    }


    public function headings(): array
    {
        return [
            'Sr. No.', 'Appointment Date', 'Time', 'Checkout Date', 'Order ID', 'Disease', 'Lab Test',
            'Diagnostic Imaging', 'Doctor Info', 'Patient Info', 'Gender/Age', 'Type',
            'Doc Fee To Pay', 'Consultation Fee', 'Total Pay', 'Payment Status',
            'From', 'Rating', 'Organization', 'Created Date', 'Created Time', 'Status',
            'Payment Txn Status', 'Appointment Status', 'Waiting Time', 'Ref Code'
        ];
    }

    public function map($element): array
    {
        $this->rowNumber++;

        $appointmentDate = $element->start ? Carbon::parse($element->start)->format('d-m-Y') : '';
        $appointmentTime = $element->start ? Carbon::parse($element->start)->format('H:i:s') : '';
        $checkoutDate = $element->check_out ? Carbon::createFromTimestamp($element->check_out)->format('d-m-Y H:i:s') : '';

        $orderId = $element->AppointmentOrder->id ?? '';
        $disease = $element->chiefComplaints->pluck('name')->implode(', ') ?? '';
        
        $labTests = $element->PatientLabs->map(function ($lab) {
            $packStatus = $lab->pack_status == 1;
            $labPackTitle = optional($lab->LabPack)->title;
            $labsTitle = optional($lab->labs)->title;
            return $packStatus ? $labPackTitle : $labsTitle;
        })->implode(', ') ?? '';

        $diagnosticImaging = $element->PatientDiagnosticImagings && $element->PatientDiagnosticImagings->count() > 0 ? 'Yes' : 'No';

        $doctorInfo = optional($element->User->DoctorInfo)->first_name . ' ' .
                     optional($element->User->DoctorInfo)->last_name . ' (' .
                     $element->User->id . ') (' .
                     optional($element->User->DoctorInfo)->mobile . ') ' .
                     optional($element->User->DoctorInfo->docSpeciality)->specialities;
        
        $patientInfo = optional($element->Patient)->name . ' (' .
                      $element->pId . ') (' .
                      optional($element->Patient)->mobile_no . ')';
        
        $gender = optional($element->Patient)->gender;
        $dob = optional($element->Patient)->dob;
        $age = $dob ? Carbon::parse($dob)->age : null;
        $genderAge = $gender . '/' . ($age ?? '');

        $type = $element->type == '3' ? 'Tele Consult' : 'In Clinic';
        $docFeeToPay = $element->Doctors->DoctorData->plan_consult_fee ?? 0;
        $consultationFee = $element->AppointmentOrder->order_subtotal ?? $element->consultation_fees;
        $totalPay = $element->AppointmentOrder->order_total ?? $element->consultation_fees;
        $paymentStatus = $element->AppointmentTxn ? 'Paid' : 'Unpaid';
        $from = $element->AppointmentOrder->order_from ?? '';
        $rating = $element->AppointmentOrder->rating ?? '';
        $organization = optional(optional($element->UserPP)->OrganizationMaster)->title;

        $createdDate = $element->created_at ? $element->created_at->format('d-m-Y') : '';
        $createdTime = $element->created_at ? $element->created_at->format('H:i:s') : '';
        $status = $element->status != '1' ? 'Cancelled' :
                 ($element->appointment_confirmation == '1' ? 'Confirmed' : 'Pending');
        $paymentTxnStatus = $element->AppointmentTxn->tran_status ?? '';
        $appointmentStatus = empty($element->working_status) ? 'Open' : 'Closed';
        $waitingTime = $element->tot_spend_time ? json_decode($element->tot_spend_time, true)['hours'] . ':' . json_decode($element->tot_spend_time, true)['mins'] : '0:0';
        $refCode = optional(optional(optional($element->UserPP)->UsersSubscriptions)->ReferralMaster)->code ?? '';

        return [
            $this->rowNumber,
            $appointmentDate,
            $appointmentTime,
            $checkoutDate,
            $orderId,
            $disease,
            $labTests,
            $diagnosticImaging,
            $doctorInfo,
            $patientInfo,
            $genderAge,
            $type,
            $docFeeToPay,
            $consultationFee,
            $totalPay,
            $paymentStatus,
            $from,
            $rating,
            $organization,
            $createdDate,
            $createdTime,
            $status,
            $paymentTxnStatus,
            $appointmentStatus,
            $waitingTime,
            $refCode
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
