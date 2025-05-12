<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Define the resource structure
        return [
            'Sr. No.' => $this->id,
            'Sale By' => $this->added_by ? getNameByLoginId($this->added_by) : null,
            'Order ID' => $this->order_id,
            'User Name' => $this->User->first_name . ' ' . $this->User->last_name,
            'Mobile' => $this->User->mobile_no,
            'Payment Mode' => $this->getPaymentMode(),
            'Plan Type' => $this->getPlanType(),
            'Plan Actual rate' => $this->plan_price,
            'Discount offer' => $this->discount_price,
            'Tax' => $this->tax,
            'Payble Amount' => $this->order_total,
            'Ref Code' => $this->getReferralCode(),
            'Corporate name' => getOrganizationIdByName($this->organization_id),
            'Order Status' => $this->getOrderStatus(),
            'Subscription Date' => date('d-m-Y', strtotime($this->created_at)),
            'Subs Time' => date('H:i', strtotime($this->created_at)),
            'Total Done Appointment' => $this->getTotalAppointments(),
            'Remark' => $this->remark,
        ];
    }

    private function getPaymentMode()
    {
        switch ($this->payment_mode) {
            case "1": return "Online Payment";
            case "2": return "Cheque";
            case "3": return "Cash";
            case "4": return "Admin Online";
            case "5": return "Free";
            default: return "Unknown";
        }
    }

    private function getOrderStatus()
    {
        switch ($this->order_status) {
            case "0": return "Pending";
            case "1": return "Completed";
            case "2": return "Cancelled";
            case "3": return "Failure Transaction";
            default: return "Unknown";
        }
    }

    private function getPlanType()
    {
        return !empty($this->PlanPeriods) && !empty($this->PlanPeriods->Plans)
            ? $this->PlanPeriods->Plans->plan_title . " -" . number_format($this->PlanPeriods->Plans->price - $this->PlanPeriods->Plans->discount_price, 2)
            : "N/A";
    }

    private function getReferralCode()
    {
        $ref_code = !empty($this->ReferralMaster) ? $this->ReferralMaster->code : $this->User->mobile_no;
        if ($this->hg_miniApp == '1') {
            $ref_code .= "\n(Help India)";
        } elseif ($this->hg_miniApp == '2') {
            $ref_code .= "\n(E-Mitra)";
        }
        return $ref_code;
    }

    private function getTotalAppointments()
    {
        $appointment_ids = '';
        if (count($this->UserSubscribedPlans) > 0) {
            foreach ($this->UserSubscribedPlans as $plan) {
                if (!empty($plan->PlanPeriods) && !empty($plan->PlanPeriods->appointment_ids)) {
                    $appointment_ids .= $plan->PlanPeriods->appointment_ids . ',';
                }
            }
        }
        
        if (!empty($appointment_ids)) {
            $apptIds = explode(",", $appointment_ids);
            return Appointments::whereIn("id", $apptIds)->where("delete_status", 1)->count();
        }
        
        return 0;
    }
}
