<?php
namespace App\Models\Exports;

use App\Models\Doctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppoinmentexportByorg implements FromCollection,WithHeadings,ShouldQueue {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $this->setValues($data);
    }
	public function headings(): array {
        return ['Sr. No.','Appointment Date','Doctor','Patient Name  (Pid) (Mobile)','Gender/Age','Created At',];
    }
	
	public function collection() {
        return collect($this->data);
    }
	
	public function setValues($appointments){
		$appointmenArray = array();
		foreach($appointments as $i => $element) {
			
			$appointmentIds = [];
			
			$working_status =  json_decode(@$element->working_status);
			if(@$element->tot_spend_time){
				$tot_spend_time =  json_decode(@$element->tot_spend_time,true);
				$waitingTime=(int) $tot_spend_time['hours'].":".$tot_spend_time['mins'];
			}else{
				$waitingTime=(int) 0;
			}
			
			if(isset($element->AppointmentOrder->PlanPeriods) && count($element->AppointmentOrder->PlanPeriods)>0){
				$appointment_ids = "";
				foreach($element->AppointmentOrder->PlanPeriods as $val){
					$appointment_ids .= $val->appointment_ids.",";
				}
				$appointmentIds = [];
				if(!empty($appointment_ids)){
					$appointmentIds = explode(",",$appointment_ids);
				}
			}
			$appointmentOrder = @$element->AppointmentOrder;
			$appointmentTxn = @$element->AppointmentTxn;
			$user = @$element->User;
			$meta_data = @json_decode(@$appointmentOrder->meta_data);
			$paymentstatus = null;
			if((!empty($appointmentTxn) && @$appointmentOrder->type == "1") || empty($appointmentOrder)) {
				$paymentstatus = 'Paid';
			}
			else if(@$appointmentOrder->type == "0"){
				// if(checkAppointmentIsElite($element->id,@$element->AppointmentOrder->order_by) == 0) {
				if(in_array($element->id,$appointmentIds)) {
					$paymentstatus = "Free By Plan";
				}
				else {
					$paymentstatus = "Free Direct";
				}
			}
			else if(@$appointmentOrder->type == "2"){
				$paymentstatus = 'Cash';
			}
			if(isset($appointmentOrder)){
				if($appointmentOrder->order_from == '1') {
					$bookfrom =	"APP";
				}
				elseif($appointmentOrder->order_from == '0'){
					$bookfrom =	"WEB";
				}
				elseif($appointmentOrder->order_from == '2'){
					$bookfrom =	"Admin";
				}
				if(isset($appointmentTxn) && !empty($appointmentTxn->received_by)){
					$bookfrom .= " ".getNameByLoginId($appointmentTxn->received_by);
				}
				if(isset($meta_data->isPaytmTab) && $meta_data->isPaytmTab == "true"){
					$bookfrom .= " (Paytm)";
				}
				elseif(isset($meta_data->organization)){
					$bookfrom .= " ".$meta_data->organization;
				}
				if(isset($appointmentOrder) && $appointmentOrder->hg_miniApp == 1) {
					$bookfrom = "(Help India)";
				}
			}
			else {
				if(@$element->app_click_status == '5'){ $bookfrom = "APP";} elseif(@$element->app_click_status == '6'){ $bookfrom = "WEB";}
			}
			if (@$element->status != '1') {
				$status = 'Cancelled';
			}
			elseif (@$element->appointment_confirmation == '1') {
				$status = 'Confirmed';
			}
			else{
				$status = 'Pending';
			}
			$pStatus = "";
			if(!empty($appointmentTxn)) {
				$pStatus = @$appointmentTxn->tran_status;
			}
			$docFeeToPay=0;
			if(!empty($appointmentOrder) && @$appointmentOrder->type == "0"){
				// $docFeeToPay= getPlanFeebyDoc($user->id,@$appointmentOrder->order_subtotal);
				$docFeeToPay= @$element->Doctors->DoctorData->plan_consult_fee;
			}elseif(!empty($appointmentOrder) && @$appointmentOrder->type != "0" && $element->type == '3'  && @$meta_data->isDirectAppt == '0'){
				$docFeeToPay= number_format(@$appointmentOrder->order_subtotal,2);
			}elseif(!empty($appointmentOrder) && @$appointmentOrder->type != "0" && $element->type != '3')
			{
				$docFeeToPay= 0;
			}
			else{
				// $docFeeToPay= number_format(getPlanFeebyDoc($user->id,@$appointmentOrder->order_subtotal),2);
				$docFeeToPay= @number_format(@$element->Doctors->DoctorData->plan_consult_fee,2);
			}
			$accNo = "";
			if(!empty($user->DoctorInfo->acc_no) && \Session::get('id') == 22) {
				$accNo = "(".replacewithStar(@$user->DoctorInfo->acc_no,4).")";
			}
			$wsts = "closed";
			if(!$working_status) {
				$wsts = "Open";
			}
			$userLabs = "";
			if(@$element->PatientLabs) {
				foreach($element->PatientLabs as $raw) {
					if($raw->pack_status == 1 ) {
						$userLabs .= @$raw->LabPack->title.",";
					}
					else{
						$userLabs .= @$raw->labs->title.",";
					}
				}
			}
			$vaR="";
			if(@$element->PatientDiagnosticImagings->appointment_id){
				$vaR='Yes';
			}else{
				$vaR='No';
			}
			
			$appointmenArray[] = array(
				 $i+1,
				 date('d-m-Y',strtotime(@$element->start)),
				 @$element->User->DoctorInfo->docSpeciality->spaciality,
				 // @$user->DoctorInfo->getStateName->name,
				 // @$user->DoctorInfo->getCityName->name,
				 @$element->Patient->first_name." ".@$element->Patient->last_name." (".@$element->pId.") (".@$element->Patient->mobile_no.")",
				 @$meta_data->gender."/".get_patient_age(@$element->Patient->dob),
				 date('d-m-Y',strtotime(@$element->created_at)),
				
				
			);
		 }
		 return $appointmenArray;
	}
}