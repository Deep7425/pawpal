<?php
namespace App\Models\Exports;

use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\ehr\Appointments;

class SubscriptionExport implements FromCollection,WithHeadings {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $this->setValues($data);
    }
	public function headings(): array {
        return ['Sr. No.','Sale By','Location','Order ID','User Name','Mobile','Payment Mode','Plan Type','Plan Actual rate','Discount offer','Tax','Payble Amount','Ref Code','Corportae name','Order Status','Subscription Date','Subs Time','Txn Id','Total Done Appointment','Remark','Device Type','Cancel Reason'];
    }
	public function collection() {
        return collect($this->data);
    }
	
	public function setValues($allData) {
        $i = 1;
		$mobileShow = checkAdminUserModulePermission(63);
        foreach($allData as $element) {
			$meta_data = !empty($element->meta_data) ? json_decode($element->meta_data) : null;
			$pType = '';
			if(@$meta_data->payment_type == 'bank') {
				$pType = 'Bank';	
			}
			else if(@$meta_data->payment_type == 'emi') {
				$pType = 'EMI';
			}
            yield [
                $i,
                $element->admin->name ?? '',
                $element->admin->city ?? '',
                $element->order_id ?? '',
                $element->User->first_name . " " . $element->User->last_name,
                $mobileShow ? $element->User->mobile_no : '*******'.substr($element->User->mobile_no,7) ?? '',
                $this->getPaymentMode($element->payment_mode)." \n".$pType,
                $this->getPlanType($element),
                $element->plan_price ?? 0,
                $element->discount_price ?? 0,
                $element->tax ?? 0,
                $element->payment_mode == 5 ? 0 : $element->order_total,
                $this->getReferralCode($element),
                $element->OrganizationMaster->title ?? '',
                $this->getOrderStatus($element->order_status),
                date('d-m-Y', strtotime($element->created_at)),
                date('H:i', strtotime($element->created_at)),
                $element->UserSubscriptionsTxn->tracking_id ?? '',
                $this->getTotalAppointments($element),
                $element->remark ?? '',
                $this->getDeviceType($element),
                $element->reason
            ];
            $i++;
        }
    }
	private function getPaymentMode($payment_mode)
	{
		$modes = [
			"1" => "Online Payment",
			"2" => "Cheque",
			"3" => "Cash",
			"4" => "Admin Online",
			"5" => "Free"
		];

		return $modes[$payment_mode] ?? '';
	}

	private function getOrderStatus($order_status)
	{
		$statuses = [
			"0" => "Pending",
			"1" => "Completed",
			"2" => "Cancelled",
			"3" => "Failure Transaction"
		];

		return $statuses[$order_status] ?? '';
	}

	private function getPlanType($element)
	{
		if (!empty($element->PlanPeriods) && !empty($element->PlanPeriods->Plans)) {
			$price = @$element->PlanPeriods->Plans->price ?? 0;
			$discount_price = @$element->PlanPeriods->Plans->discount_price ?? 0;
			return @$element->PlanPeriods->Plans->plan_title . " -" . number_format($price - $discount_price, 2);
		}
		return '';
	}

	private function getTotalAppointments($element){
		$appointment_ids = '';
		if (count($element->UserSubscribedPlans) > 0) {
			foreach ($element->UserSubscribedPlans as $plan) {
				if (!empty($plan->PlanPeriods) && !empty($plan->PlanPeriods->appointment_ids)) {
					$appointment_ids .= $plan->PlanPeriods->appointment_ids . ",";
				}
			}
		}

		if (!empty($appointment_ids)) {
			$apptIds = explode(",", rtrim($appointment_ids, ","));
			return Appointments::select("id")->whereIn("id", $apptIds)->where("delete_status", 1)->count();
		}

		return 0;
	}

	private function getReferralCode($element){
		$ref_code = !empty($element->ReferralMaster) ? $element->ReferralMaster->code : $element->User->mobile_no;
		if ($element->hg_miniApp == '1') {
			$ref_code .= "\n(Help India)";
		} else if ($element->hg_miniApp == '2') {
			$ref_code .= "\n(E-Mitra)";
		}

		return $ref_code;
	}

	private function getDeviceType($element)
	{
		$types = [
			"1" => "Android",
			"2" => "IOS",
			"3" => $element->User->login_type == "3" ? "PAYTM" : "WEB"
		];

		return $types[$element->User->device_type] ?? '';
	}

}