<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\ehr\Appointments;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\EmailTemplate;
use App\Models\User;
use App\Models\Doctors;
use App\Models\ehr\AppointmentOrder;
use App\Models\ehr\ChiefComplaints;
use App\Models\ehr\MedicineOrders;
use App\Models\ehr\PatientMedications;
use App\Models\ehr\LabOrders as LabOrderEhr;
use App\Models\ehr\PatientLabs;
use App\Models\ehr\PatientSubLabs;
use App\Models\ehr\PatientAllergy;
use App\Models\ehr\PatientProcedures;
use App\Models\ehr\PatientDiagnosis;
use App\Models\ehr\RadiologyOrders;
use App\Models\ehr\PatientDiagnosticImagings;
use App\Models\ehr\PatientAdvice;
use App\Models\ehr\PatientVitalss;
use App\Models\ehr\PatientImmunizations;
use App\Models\ehr\PatientReferrals;
use App\Models\ehr\PatientDentals;
use App\Models\ehr\PatientEyes;
use App\Models\ehr\Nutritionalinfo;
use App\Models\ehr\DietPlan;
use App\Models\ehr\PatientPhysicalExcercise;
use App\Models\ehr\PatientDietitianTemplate;
use App\Models\ehr\PatientDietPlanFile;
use App\Models\ehr\PatientEom;
use App\Models\ehr\PatientSle;
use App\Models\ehr\PatientSystematicIllness;
use App\Models\ehr\PatientSleCanvas;
use App\Models\ehr\PatientExaminations;
use App\Models\ehr\PatientFundus;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class AppointmentController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function cancelAppointment(Request $request)
	{

		try {

			if ($request->isMethod('post')) {
				$data = $request->all();
				Appointments::where('id', $data['appId'])->update(array(
					'status' => 0,
					'appointment_confirmation' => 1,
					'cancel_reason' => "cancelbypatient",
				));
				$appointmentData = Appointments::with(['User.DoctorInfo', 'Patient'])->where('id', $data['appId'])->first();
				$doc = Doctors::where('user_id', $data['userId'])->first();
				$practice_id = $doc->practice_id;
				$practiceData =  PracticeDetails::where(['user_id' => $practice_id])->first();
				$pName = ucfirst($appointmentData->Patient->first_name) . " " . @$appointmentData->Patient->last_name;
				$doctorname = $appointmentData->User->DoctorInfo->first_name . ' ' . $appointmentData->User->DoctorInfo->last_name;
				$appointDate = date('d-m-Y', strtotime($appointmentData->start));
				$appointtime = date('h:i A', strtotime($appointmentData->start));
				if (Parent::is_connected() == 1) {
					if (!empty($appointmentData->Patient->email)) {
						$to = $appointmentData->Patient->email;
						$EmailTemplate = EmailTemplate::where('slug', 'cancelappointmentmailPatient')->first();
						if ($EmailTemplate) {
							$body = $EmailTemplate->description;

							$mailMessage = str_replace(
								array('{{pat_name}}', '{{clinic_name}}', '{{date}}', '{{time}}', '{{doctorname}}', '{{mobile}}'),
								array($pName, $practiceData->clinic_name, $appointDate, $appointtime, $doctorname, $appointmentData->User->DoctorInfo->mobile),
								$body
							);
							$datas = array('to' => $to, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'practiceData' => $practiceData, 'subject' => $EmailTemplate->subject);
							try {
								Mail::send('emails.mailtempPractice', $datas, function ($message) use ($datas) {
									$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
								});
							} catch (\Exception $e) {
								// Never reached
							}
						}
					}
					$docName = "Dr. " . ucfirst($appointmentData->User->DoctorInfo->first_name) . " " . $appointmentData->User->DoctorInfo->last_name;
					$patientname = $appointmentData->Patient->first_name . ' ' . $appointmentData->Patient->last_name;
					$appointDate = date('d-m-Y', strtotime($appointmentData->start));
					$appointtime = date('h:i A', strtotime($appointmentData->start));
					if (!empty($appointmentData->Patient->mobile_no)) {
						$app_link = "https://www.healthgennie.com/download";
						$message = urlencode("Your appointment with Dr. " . $doctorname . ", on " . $appointDate . " at " . $appointtime . " has been cancelled. For Better Experience Download Health Gennie App " . $app_link . " Thanks Team Health Gennie");
						$this->sendSMS($appointmentData->Patient->mobile_no, $message, '1707161735108546905');
					}


					$to = $appointmentData->User->email;
					$EmailTemplate = EmailTemplate::where('slug', 'cancelappointmentmaildoctor')->first();
					if ($EmailTemplate) {
						$body = $EmailTemplate->description;
						$mailMessage = str_replace(
							array('{{doc_name}}', '{{clinic_name}}', '{{date}}', '{{time}}', '{{patientname}}'),
							array($docName, $practiceData->clinic_name, $appointDate, $appointtime, $patientname),
							$body
						);
						$datas = array('to' => $to, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'practiceData' => $practiceData, 'subject' => $EmailTemplate->subject);
						try {
							Mail::send('emails.mailtempPractice', $datas, function ($message) use ($datas) {
								$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
							});
						} catch (\Exception $e) {
							// Never reached
						}
					}
					if (!empty(@$appointmentData->User->DoctorInfo->mobile)) {
						$message = urlencode("Dear " . $docName . ", Appointment of " . $patientname . ", with you on " . $appointDate . " at " . $appointtime . " has been cancelled Thanks Team Health Gennie");
						$this->sendSMS($appointmentData->User->DoctorInfo->mobile, $message, '1707161587827747448');
					}
				}
				return 1;
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function changeAppointment(Request $request)
	{

		try {
			$app_id = base64_decode($request->aPiD);
			$appointments = [];
			$doctor = "";
			if (!empty($app_id)) {
				$appointment = Appointments::with(['patient', 'visitType', 'practiceDetails', 'user.doctorInfo.docSpeciality'])->where('id', $app_id)->first();
				$doctor = Doctors::where('user_id', $appointment->doc_id)->first();
				if (!empty($doctor->profile_pic)) {
					$doctor['profile_pic'] = getPath("public/doctor/ProfilePics/" . $doctor->profile_pic);
				} else {
					$doctor['profile_pic'] = null;
				}
				$appointment['time'] = 0;
				$appTime = date('H:i', strtotime($appointment->start));
				// echo strtotime('12:00')."\n";
				// pr(strtotime($appTime));
				if (strtotime($appTime) < strtotime('12:00')) {
					$appointment['time'] = 1;
				} else if (strtotime($appTime) >= strtotime('12:00') && strtotime($appTime) < strtotime('16:00')) {
					$appointment['time'] = 2;
				} else if (strtotime($appTime) >= strtotime('16:00') && strtotime($appTime) < strtotime('22:00')) {
					$appointment['time'] = 3;
				}
			}
			return view($this->getView('appointments.reschedule-appointment'), ['appointment' => $appointment, 'doctor' => $doctor]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function userAppointment(Request $request) {
		$user = \Auth::user();
		$appointments = []; 
		if(!empty($user->pId)){
			$p_ids = User::select("pId")->where(["parent_id"=>$user->pId])->pluck("pId")->toArray();
		
			array_push($p_ids,$user->pId);
			
			$appointments = Appointments::with(['patient','visitType','practiceDetails','user.doctorInfo.docSpeciality'])->whereIn('pID',$p_ids)->where("delete_status",1)->where('doc_id','!=',2219)->orderBy('start','DESC')->paginate(10);	
			foreach($appointments as $app){
				$app['AppointmentTxn'] = getAppointmentTxnDetails($app->id);
				$app['prescription'] = getPath("uploads/PatientDocuments/".$app->patient->patient_number."/misc/clinicalNotePrint.pdf");
				$followup_count = getFollowUpCount($app->doc_id);
				if($app->visit_type == 6 || $app->type == null) {
					$app['is_followup'] = false;
					$app['followupDone'] = false;
				}
				else{
					$followUp = followupExist($app->start,$followup_count,$app->id,$app->doc_id,$app->pId);
					$app['is_followup'] = $followUp['success'];
					$app['followupDone'] = $followUp['flag'];
				}
				$app['followup_count'] = $followup_count;
			}
		}
		return view($this->getView('appointments.appointment'),['appointments'=>$appointments]);
	}

	public function changeAppointmentSlot(Request $request)
	{
		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$docData = Doctors::where(['id' => $data['doc_id']])->first();
				$app_id = $data['app_id'];
				$increment_time = $docData->slot_duration * 60;

				$date = date("Y-m-d", strtotime($data['date']));
				$time = date("H:i:s", $data['time']);
				$start_date = date("Y-m-d H:i:s", strtotime($date . " " . $time));
				$end_date = date('Y-m-d H:i:s', strtotime($date . " " . $time) + $increment_time);

				$appointment = Appointments::where(['id' => $app_id])->update([
					'start' =>  $start_date,
					'end' =>  $end_date,
					'app_click_status' =>  6,
				]);
				$appointment = Appointments::with(['patient'])->where('id', $app_id)->first();
				if (Parent::is_connected() == 1) {
					$docName = "Dr. " . ucfirst($docData->first_name) . " " . $docData->last_name;
					$patientname = $appointment->patient->first_name . ' ' . $appointment->patient->last_name;
					$appointDate = date('d-m-Y', strtotime($appointment->start));
					$appointtime = date('h:i A', strtotime($appointment->start));
					$doc_email = $docData->email;
					$EmailTemplate = EmailTemplate::where('slug', 'confirmappointmentmaildoctor')->first();
					$confirm_url = url("/") . '/appointment-confirm?id=' . base64_encode($appointment->id);
					if ($EmailTemplate) {
						$body = $EmailTemplate->description;
						$mailMessage = str_replace(
							array('{{doc_name}}', '{{clinic_name}}', '{{date}}', '{{time}}', '{{patientname}}', '{{confirm_link}}'),
							array($docName, $docData->clinic_name, $appointDate, $appointtime, $patientname, $confirm_url),
							$body
						);
						$datas = array('to' => $doc_email, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'practiceData' => $docData, 'subject' => $EmailTemplate->subject);
						try {
							Mail::send('emails.mailtempPractice', $datas, function ($message) use ($datas) {
								$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
							});
						} catch (\Exception $e) {
							// Never reached
						}
					}
					$EmailTemplate = EmailTemplate::where('slug', 'appointmentmailadmin')->first();
					if ($EmailTemplate) {
						$body = $EmailTemplate->description;
						$mailMessage = str_replace(
							array('{{doc_name}}', '{{date}}', '{{time}}', '{{patientname}}'),
							array($docName, $appointDate, $appointtime, $patientname),
							$body
						);
						$datas = array('to' => "info@healthgennie.com", 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'practiceData' => $docData, 'subject' => $EmailTemplate->subject);
						try {
							Mail::send('emails.mailtempPractice', $datas, function ($message) use ($datas) {
								$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
							});
						} catch (\Exception $e) {
							// Never reached
						}
					}
					if (!empty($appointment->patient->mobile_no)) {
						$app_link = "www.healthgennie.com/download";
						$message = urlencode("Dear " . $patientname . " Thanks for requesting an appointment with " . $docName . " on Health Gennie. Your appointment will be confirmed shortly.\nFor Better Experience Download Health Gennie App\n" . $app_link);
						$this->sendSMS($appointment->patient->mobile_no, $message, '1707161526893824568');


						$admin_msg = urlencode("This patient(" . $patientname . ") of appointment reschedule with " . $docName . " on " . $appointDate . " at " . $appointtime . ". Doctor Mobile : " . $docData->mobile_no . ". Patient Mobile : " . $appointment->patient->mobile_no . " Please click below link for more info " . $confirm_url);
						$this->sendSMS(8905557257, $admin_msg, '1707161804129054577');
					}
					if (!empty($docData->mobile_no)) {
						$message = urlencode("Dear " . $docName . ", " . $patientname . " has requested an appointment with you on " . $appointDate . " at " . $appointtime . ". Please click the link below to confirm or deny the appointment. Health Gennie Team.\n " . $confirm_url . "");
						$this->sendSMS($docData->mobile_no, $message, '1707161526866904533');
					}
				}
				Session::flash('message', "Appointment rescheduled successfully.");
				return redirect()->route('userAppointment');
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function downloadReceipt(Request $request)
	{
		try {
			$app_id = base64_decode($request->aPiD);
			$appointments = [];
			$doctor = "";
			if (!empty($app_id)) {
				$appointment = Appointments::with(['patient.PatientRagistrationNumbers', 'practiceDetails', 'user.doctorInfo.docSpeciality'])->where('id', $app_id)->first();
				$doctor = Doctors::with('docSpeciality')->where('user_id', $appointment->doc_id)->first();
				$pdf = PDF::loadView('appointments.DownloadReceiptPDF', compact('appointment', 'doctor'));
				return $pdf->download('Health-Gennie-Appointment.pdf');
			}
			return redirect()->route('userAppointment');
		} catch (Exception $e) {

			return $e->getMessage(); 
		} 
	}
	public function showAppointmentTxn(Request $request)
	{
		try {
			$app_id = base64_decode($request->aPiD);
			if (!empty($app_id)) {
				$appointment = Appointments::Where('id', $app_id)->first();
				$appointment['AppointmentTxn'] = getAppointmentTxnDetails($app_id);
				return view($this->getView('appointments.viewPayment'), ['appointment' => $appointment]);
			}
			return redirect()->route('userAppointment');
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function appointmentDetails(Request $request) {
		$app_id = base64_decode($request->aPiD);
		$appointment = Appointments::with(['patient.PatientRagistrationNumbers','practiceDetails','user.doctorInfo.docSpeciality'])->where('id',$app_id)->first();
		$doctor = Doctors::with('docSpeciality')->where('user_id',$appointment->doc_id)->first();
		$patient_number = @$appointment->patient->patient_number;
		$appointment_id = $appointment->id;

		$path = 'uploads/PatientDocuments/'.$patient_number.'/appointments/'.$appointment_id;
		$documents = [];
		$files = [];
		if(Storage::disk('s3')->exists($path)) {
			$files = Storage::disk('s3')->files($path);
		}
		// pr($files);
		if(isset($files) && count($files)){
		 foreach($files as $file) {
			$docName = substr($file, strrpos($file, '/') + 1);
			$file_ext = explode('.', $docName);
			$file_ext_count = count($file_ext);
			$cnt = $file_ext_count - 1;
			$file_extension = $file_ext[$cnt];
			$documents[] = ['doc_name'=>$docName,'file_extension'=>$file_extension,'document'=>getPath($file)];
		 }
		}
		return view($this->getView('appointments.appointment-details'),['appointment'=>$appointment,'doctor'=>$doctor,'documents'=>$documents]);
	}

	public function followupAppointment(Request $request)
	{

		try {

			if ($request->isMethod('post')) {
				$patientInfo = $request->all();
				$user_array['order_by'] = $patientInfo['order_by'];
				$docData = Doctors::select(["id", "user_id", "practice_id", "consultation_fees", "oncall_fee", "slot_duration", "first_name", "last_name"])->where(['user_id' => $patientInfo['doc_id']])->first();
				$user_array['doc_id']   =  $docData->id;
				$user_array['doc_name']   = $docData->first_name . " " . $docData->last_name;
				$user_array['p_id']   = $patientInfo['p_id'];
				$user_array['visit_type'] = 1;
				$user_array['blood_group'] = NULL;
				$user_array['consultation_fees'] = 0;
				$user_array['appointment_date'] = date("Y-m-d");
				$start_date = date("Y-m-d H:i:s");
				$increment_time = $docData->slot_duration * 60;
				$user_array['time'] = checkAvailableSlot($start_date, $docData->user_id, $increment_time);
				$user_array['slot_duration'] = $docData->slot_duration;
				$user_array['onCallStatus'] = 1;
				$user_array['isFirstTeleAppointment'] = 1;
				$user_array['isDirectAppt'] = 1;
				$service_charge = 0;
				$user_array['service_charge'] = $service_charge;
				$user_array['is_subscribed'] = 1;
				$user_array['gender'] = $patientInfo['gender'];
				$user_array['patient_name'] = $patientInfo['patient_name'];
				$user_array['dob'] = date("d-m-Y", $patientInfo['dob']);
				$user_array['mobile_no'] = $patientInfo['mobile_no'];
				$user_array['other_mobile_no'] = null;
				$user_array['otherPatient'] = 0;
				$user_array['coupon_id'] = null;
				$user_array['coupon_discount'] = null;
				$user_array['call_type'] = 1;
				$user_array['referral_code'] = null;
				$user_array['is_peak'] = 0;
				$user_array['finalConsultaionFee'] = $user_array['consultation_fees'];
				$user_array['isfollowup'] = 1;
				$user_array['apptId'] = $patientInfo['appId'];
				$user_array['patient_id'] = $patientInfo['patient_id'];
				$charge =  0;
				$tax =  0;
				$gst =  0;
				$service_charge_meta = ["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];
				$order = AppointmentOrder::create([
					'type' => 0,
					'service_charge_meta' =>  json_encode($service_charge_meta),
					'service_charge' =>  $service_charge,
					'order_subtotal' =>  $user_array['consultation_fees'],
					'order_total' =>  $user_array['consultation_fees'],
					'order_status' =>  0,
					'app_date' => $start_date,
					'doc_id' =>  $docData->user_id,
					'order_from' => 1,
					'order_by' => $user_array['order_by'],
					'coupon_id' => $user_array['coupon_id'],
					'coupon_discount' => $user_array['coupon_discount'],
					'meta_data' => json_encode($user_array),
				]);
				$newApptId = Parent::putAppointmentDataApp($order, '', '');
				$appointment_id = $user_array['apptId'];
				$patient_id = $user_array['patient_id'];

				$checkChief = ChiefComplaints::where(['appointment_id' => $appointment_id])->first();
				if (!empty($checkChief) > 0) {
					ChiefComplaints::create([
						'appointment_id' =>  $newApptId,
						'pId' =>  $patient_id,
						'data' =>  $checkChief->data
					]);
				}
				$medData = PatientMedications::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($medData) > 0) {
					$order = MedicineOrders::create([
						'appointment_id' => $newApptId,
						'patient_id' => $patient_id,
						'order_by' => $docData->user_id,
						'doctor_type' => 1,
						'practice_id' => $docData->practice_id,
					]);
					$orderId = $order->id;
					foreach ($medData as $med) {
						PatientMedications::create([
							'appointment_id' => $newApptId,
							'patient_id' => $med->patient_id,
							'drug_id' => $med->drug_id,
							'strength' => $med->strength,
							'unit' => $med->unit,
							'frequency' => $med->frequency,
							'frequency_type' => $med->frequency_type,
							'duration' => $med->duration,
							'duration_type' => $med->duration_type,
							'medi_instruc' => $med->medi_instruc,
							'notes' => $med->notes,
							'order_id' => $orderId,
							'added_by' => $med->added_by
						]);
					}
				}
				$labs = PatientLabs::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($labs) > 0) {
					$order = LabOrderEhr::create([
						'patient_id' => $patient_id,
						'order_by' => $docData->user_id,
						'doctor_type' => 1,
						'practice_id' => $docData->practice_id,
					]);
					$orderId = $order->id;
					foreach ($labs as $lab) {
						$patientLabs = PatientLabs::create([
							'appointment_id' => $newApptId,
							'patient_id' => $lab->patient_id,
							'pack_status' => 1,
							'pack_id' => $lab->pack_id,
							'lab_id' => $lab->lab_id,
							'instructions' => $lab->instructions,
							'order_id' => $orderId,
							'added_by' => $lab->added_by,
						]);
						$subLabs = PatientSubLabs::where(['parent_id' => $lab->id, 'delete_status' => 1])->get();
						if (count($subLabs)) {
							foreach ($subLabs as $raw) {
								PatientSubLabs::create([
									'parent_id' => $patientLabs->id,
									'lab_id' => $raw->lab_id,
									'sub_lab_id' => $raw->sub_lab_id,
									'order_id' => $orderId,
									'added_by' => $raw->added_by
								]);
							}
						}
					}
				}
				$allergy = PatientAllergy::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($allergy) > 0) {
					foreach ($allergy as $raw) {
						PatientAllergy::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'allergy_type' => $raw->allergy_type,
							'allergy_id' => $raw->allergy_id,
							'allergy_reactions' => $raw->allergy_reactions,
							'severity' => $raw->severity,
							'notes' => $raw->notes,
							'added_by' => $raw->added_by
						]);
					}
				}
				$procedure = PatientProcedures::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($procedure) > 0) {
					foreach ($procedure as $raw) {
						PatientProcedures::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'procedure_type' => $raw->procedure_type,
							'procedure_id' => $raw->procedure_id,
							'notes' => $raw->notes,
							'added_by' => $raw->added_by
						]);
					}
				}
				$diagnosis = PatientDiagnosis::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				foreach ($diagnosis as $diagno) {
					PatientDiagnosis::create([
						'appointment_id' => $newApptId,
						'patient_id' => $diagno->patient_id,
						'diagnosis_id' => $diagno->diagnosis_id,
						'notes' => $diagno->notes,
						'added_by' => $diagno->added_by
					]);
				}
				$diImaging = PatientDiagnosticImagings::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($diImaging) > 0) {
					$order = RadiologyOrders::create([
						'patient_id' => $patient_id,
						'order_by' => $docData->user_id,
						'doctor_type' => 1,
						'practice_id' => $docData->practice_id,
					]);
					foreach ($diImaging as $raw) {
						PatientDiagnosticImagings::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'lab_id' => $raw->lab_id,
							'order_id' => $order->id,
							'instructions' => $raw->instructions,
						]);
					}
				}
				$advice = PatientAdvice::where(['appointment_id' => $appointment_id])->first();
				if (!empty($advice)) {
					PatientAdvice::create([
						'appointment_id' =>  $newApptId,
						'pId' =>  $advice->patient_id,
						'data' =>  $advice->data,
						'added_by' => $advice->practice_id
					]);
				}
				$pVitals = PatientVitalss::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->first();
				if (!empty($pVitals)) {
					PatientVitalss::create([
						'appointment_id' => $newApptId,
						'patient_id' => $pVitals->patient_id,
						'heightCm' => $pVitals->heightCm,
						'weight' => $pVitals->heightCm,
						'bmi' => $pVitals->heightCm,
						'bp_systolic' => $pVitals->heightCm,
						'bp_diastolic' => $pVitals->heightCm,
						'pulse_rate' => $pVitals->heightCm,
						'temprature' => $pVitals->heightCm,
						'head_circumference' => $pVitals->head_circumference,
						'sbp_systolic' => $pVitals->sbp_systolic,
						'sbp_diastolic' => $pVitals->sbp_diastolic,
						'random_blood_sugar' => $pVitals->random_blood_sugar,
						'fasting_blood_sugar' => $pVitals->fasting_blood_sugar,
						'temperature_f' => $pVitals->temperature_f,
						'notes' => $pVitals->notes,
						'added_by' => $pVitals->added_by,
					]);
				}
				$examinations = PatientExaminations::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($examinations) > 0) {
					foreach ($examinations as $raw) {
						PatientExaminations::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'bodySite_id' => $raw->bodySite_id,
							'le_observation' => $raw->le_observation,
							're_observation' => $raw->re_observation,
							'added_by' => $raw->added_by
						]);
					}
				}
				$immunization = PatientImmunizations::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($immunization) > 0) {
					foreach ($immunization as $raw) {
						PatientImmunizations::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'vaccine_id' => $raw->vaccine_id,
							'immunization_type' => $raw->immunization_type,
							'immunization_id' =>  $raw->immunization_id,
							'dose_qty' => $raw->dose_qty,
							'dose_unit' => $raw->dose_unit,
							'dose_status' => $raw->dose_status,
							'schedule_id' => $raw->schedule_id,
							'route' => $raw->route,
							'other_route' => $raw->other_route,
							'body_location' => $raw->body_location,
							'comment' => $raw->comment,
							'given_by' => $raw->given_by,
							'given_date' => $raw->given_date,
							'added_by' => $raw->added_by
						]);
					}
				}
				$pReferral = PatientReferrals::where(['appointment_id' => $appointment_id, 'delete_status' => 1])->first();
				if (!empty($pReferral) > 0) {
					PatientReferrals::create([
						'appointment_id' => $newApptId,
						'patient_id' => $added_by->patient_id,
						'referral_date' => $pReferral->referral_date,
						'referral_by' => $pReferral->referral_by,
						'email' => $pReferral->email,
						'phone_no' => $pReferral->phone_no,
						'referral_to' => $pReferral->referral_to,
						'referral_to_other' => $pReferral->referral_to_other,
						'speciality_id' => $pReferral->speciality_id,
						'added_by' => $pReferral->added_by
					]);
				}
				$dentals = PatientDentals::where(['appointment_id' => $appointment_id, 'delete_status' => 1])->get();
				if (count($dentals) > 0) {
					foreach ($dentals as $raw) {
						PatientDentals::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'dental_id' => $raw->dental_id,
							'dental_procedure' => $raw->dental_procedure,
							'dental_note' => $raw->dental_note,
							'added_by' => $raw->added_by
						]);
					}
				}
				$eyes = PatientEyes::where(['appointment_id' => $appointment_id, 'delete_status' => 1])->first();
				if (!empty($eyes)) {
					PatientEyes::create([
						'appointment_id' => $newApptId,
						'patient_id' => $eyes->patient_id,
						'va_check' => $eyes->va_check,
						'r_va_h' => $eyes->r_va_h,
						'r_va_m' => $eyes->r_va_m,
						'l_va_h' => $eyes->l_va_h,
						'l_va_m' => $eyes->l_va_m,
						'bcva_check' => $eyes->bcva_check,
						'r_bcva' => $eyes->r_bcva,
						'l_bcva' => $eyes->l_bcva,
						'iop_check' => $eyes->iop_check,
						'r_iop' => $eyes->r_iop,
						'r_iop_other' => $eyes->r_iop_other,
						'l_iop' => $eyes->l_iop,
						'l_iop_other' => $eyes->l_iop_other,
						'ar_check' => $eyes->ar_check,
						'r_ar' => $eyes->r_ar,
						'r_ar_input1' => $eyes->r_ar_input1,
						'r_ar_input2' => $eyes->r_ar_input2,
						'l_ar' => $eyes->l_ar,
						'l_ar_input1' => $eyes->l_ar_input1,
						'l_ar_input2' => $eyes->l_ar_input2,
						'dilar_check' => $eyes->dilar_check,
						'r_dilar_input1' => $eyes->r_dilar_input1,
						'r_dilar_input2' => $eyes->r_dilar_input2,
						'r_dilar_input3' => $eyes->r_dilar_input3,
						'r_dilar_input4' => $eyes->r_dilar_input4,
						'l_dilar_input1' => $eyes->l_dilar_input1,
						'l_dilar_input2' => $eyes->l_dilar_input2,
						'l_dilar_input3' => $eyes->l_dilar_input3,
						'l_dilar_input4' => $eyes->l_dilar_input4,
						'k1k2_check' => $eyes->k1k2_check,
						'r_k1k2_text' => $eyes->r_k1k2_text,
						'r_k1k2_axis' => $eyes->r_k1k2_axis,
						'l_k1k2_text' => $eyes->l_k1k2_text,
						'l_k1k2_axis' => $eyes->l_k1k2_axis,
						'axl_check' => $eyes->axl_check,
						'r_axl' => $eyes->r_axl,
						'l_axl' => $eyes->l_axl,
						'iol_check' => $eyes->iol_check,
						'r_iol' => $eyes->r_iol,
						'l_iol' => $eyes->l_iol,
						'syringing_check' => $eyes->syringing_check,
						'r_syringing' => $eyes->r_syringing,
						'l_syringing' => $eyes->l_syringing,
						'color_vision_check' => $eyes->color_vision_check,
						'r_color_vision_text' => $eyes->r_color_vision_text,
						'r_color_vision_type' => $eyes->r_color_vision_type,
						'l_color_vision_text' => $eyes->l_color_vision_text,
						'l_color_vision_type' => $eyes->l_color_vision_type,
						'pgp_check' => $eyes->pgp_check,
						'r_pgp' => $eyes->r_pgp,
						'r_pgp_shp' => $eyes->r_pgp_shp,
						'r_pgp_cg' => $eyes->r_pgp_cg,
						'r_pgp_axis' => $eyes->r_pgp_axis,
						'l_pgp' => $eyes->l_pgp,
						'l_pgp_shp' => $eyes->l_pgp_shp,
						'l_pgp_cg' => $eyes->l_pgp_cg,
						'l_pgp_axis' => $eyes->l_pgp_axis,
						'r_pgp_b' => $eyes->r_pgp_b,
						'r_pgp_shp_b' => $eyes->r_pgp_shp_b,
						'r_pgp_cg_b' => $eyes->r_pgp_cg_b,
						'r_pgp_axis_b' => $eyes->r_pgp_axis_b,
						'l_pgp_b' => $eyes->l_pgp_b,
						'l_pgp_shp_b' => $eyes->l_pgp_shp_b,
						'l_pgp_cg_b' => $eyes->l_pgp_cg_b,
						'l_pgp_axis_b' => $eyes->l_pgp_axis_b,
						'retinoscopy_check' => $eyes->retinoscopy_check,
						'r_ratinoscopy' => $eyes->r_ratinoscopy,
						'r_ar_sph_sign' => $eyes->r_ar_sph_sign,
						'r_ar_cyl_sign' => $eyes->r_ar_cyl_sign,
						'r_dil_sph_sign' => $eyes->r_dil_sph_sign,
						'r_dil_cyl_sign' => $eyes->r_dil_cyl_sign,
						'r_ogp_sph_sign' => $eyes->r_ogp_sph_sign,
						'r_ogp_cyl_sign' => $eyes->r_ogp_cyl_sign,
						'r_ogp_sph_sign2' => $eyes->r_ogp_sph_sign2,
						'r_ogp_cyl_sign2' => $eyes->r_ogp_cyl_sign2,
						'l_ar_sph_sign' => $eyes->l_ar_sph_sign,
						'l_ar_cyl_sign' => $eyes->l_ar_cyl_sign,
						'l_dil_sph_sign' => $eyes->l_dil_sph_sign,
						'l_dil_cyl_sign' => $eyes->l_dil_cyl_sign,
						'l_ogp_sph_sign' => $eyes->l_ogp_sph_sign,
						'l_ogp_cyl_sign' => $eyes->l_ogp_cyl_sign,
						'l_ogp_sph_sign2' => $eyes->l_ogp_sph_sign2,
						'l_ogp_cyl_sign2' => $eyes->l_ogp_cyl_sign2,
						'added_by' => $eyes->added_by
					]);
				}
				$nutritional_info = Nutritionalinfo::where(['appointment_id' => $appointment_id, 'delete_status' => 1])->first();
				if (!empty($nutritional_info)) {
					Nutritionalinfo::create([
						'appointment_id' =>  $newApptId,
						'patient_id' =>  $nutritional_info->patient_id,
						'doc_id' =>  $nutritional_info->doc_id,
						'eating_habits' => $nutritional_info->eating_habits,
						'medical_concern' => $nutritional_info->medical_concern,
						'disease' => $nutritional_info->disease,
						'disease_option' => $nutritional_info->disease_option,
						'medical_treatment' => $nutritional_info->medical_treatment,
						'medical_treatment_option' => $nutritional_info->medical_treatment_option,
						'allergy' => $nutritional_info->allergy,
						'physical_activity' => $nutritional_info->physical_activity,
						'work_schedule_from' => $nutritional_info->work_schedule_from,
						'life_style' => $nutritional_info->life_style,
						'body_type' => $nutritional_info->body_type,
						'energy_calories' => $nutritional_info->energy_calories,
						'protein' => $nutritional_info->protein,
						'fat' => $nutritional_info->fat,
						'calcium' => $nutritional_info->calcium,
						'added_by' => $nutritional_info->added_by,
						'status' => 1,
					]);
				}
				$diet_plan = DietPlan::where(['appointment_id' => $appointment_id, 'delete_status' => 1])->get();
				if (count($diet_plan) > 0) {
					foreach ($diet_plan as $raw) {
						DietPlan::create([
							'appointment_id' =>  $newApptId,
							'patient_id' =>  $raw->patient_id,
							'doc_id' =>  $raw->doc_id,
							'no_of_meal' => $raw->no_of_meal,
							'meal_plan_id' => $raw->meal_plan_id,
							'meal_plan_time' => $raw->meal_plan_time,
							'menu' => $raw->menu,
							'menu_exchange' => $raw->menu_exchange,
							'ingredient' => $raw->ingredient,
							'added_by' => $raw->added_by,
							'status' => 1,
						]);
					}
				}
				$physical_excercise = PatientPhysicalExcercise::where(['appointment_id' => $appointment_id, 'delete_status' => 1])->get();
				if (count($physical_excercise) > 0) {
					foreach ($physical_excercise as $raw) {
						PatientPhysicalExcercise::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'physical_excercise_id' => $raw->physical_excercise_id,
							'instructions' => $raw->instructions,
							'added_by' => $raw->added_by
						]);
					}
				}
				$dietitian_template = PatientDietitianTemplate::where(['appointment_id' => $appointment_id, 'delete_status' => 1])->first();
				if (!empty($dietitian_template)) {
					PatientDietitianTemplate::create([
						'appointment_id' => $newApptId,
						'patient_id' => $raw->patient_id,
						'dietitian_temp_id' => $raw->dietitian_temp_id,
						'instructions' => $raw->instructions,
						'added_by' => $raw->added_by
					]);
				}
				$patient_diet_file = PatientDietPlanFile::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->first();
				if (!empty($patient_diet_file)) {
					PatientDietPlanFile::create([
						'appointment_id' =>  $newApptId,
						'patient_id' =>  $patient_diet_file->patient_id,
						'template_name' =>  $patient_diet_file->template_name,
						'doc_id' =>  $patient_diet_file->doc_id,
						'file_name' =>  $patient_diet_file->file_name,
						'added_by' => $patient_diet_file->added_by,
						'status' => 1,
					]);
				}
				$patient_eom = PatientEom::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($patient_eom) > 0) {
					foreach ($patient_eom as $raw) {
						PatientEom::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'eom_id' => $raw->eom_id,
							'eom_type' => $raw->eom_type,
							'added_by' => $raw->added_by
						]);
					}
				}
				$patient_sle = PatientSle::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($patient_sle) > 0) {
					foreach ($patient_sle as $raw) {
						PatientSle::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'sle_eye' => $raw->sle_eye,
							'sle_id' => $raw->sle_id,
							'notes' => $raw->notes,
							'added_by' => $raw->added_by
						]);
					}
				}
				$patient_sys_ill = PatientSystematicIllness::where(['appointment_id' => $appointment_id, 'patient_id' => $patient_id, 'delete_status' => 1])->get();
				if (count($patient_sys_ill) > 0) {
					foreach ($patient_sys_ill as $raw) {
						PatientSystematicIllness::create([
							'appointment_id' => $newApptId,
							'patient_id' => $raw->patient_id,
							'notes' => $raw->notes,
							'added_by' => $raw->added_by
						]);
					}
				}
				$PatientSleCanvas = PatientSleCanvas::where(['appointment_id' => $appointment_id])->first();
				if (!empty($PatientSleCanvas)) {
					PatientSleCanvas::create([
						'appointment_id' => $newApptId,
						'patient_id' => $raw->patient_id,
						'canvas_img' => $raw->canvas_img,
						'added_by' => $raw->added_by
					]);
				}
				$patient_fundus = PatientFundus::where(['appointment_id' => $appointment_id, 'delete_status' => 1])->first();
				if (!empty($patient_fundus)) {
					PatientFundus::create([
						'appointment_id' => $newApptId,
						'patient_id' => $patient_fundus->patient_id,
						'fundus_img_check' => $patient_fundus->fundus_img_check,
						'fundus_img_right_eye' => $patient_fundus->fundus_img_right_eye,
						'fundus_img_left_eye' => $patient_fundus->fundus_img_left_eye,
						'fundus_master_id_left' =>  $patient_fundus->fundus_master_id_left,
						'fundus_master_id_right' => $patient_fundus->fundus_master_id_right,
						'added_by' => $patient_fundus->added_by
					]);
				}
				return 1;
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function downloadApptReceipt(Request $request)
	{
		try {

			$app_id = base64_decode($request->aPiD);
			$doctor = "";
			if (!empty($app_id)) {
				$appointment = Appointments::with(['patient.PatientRagistrationNumbers', 'AppointmentOrder', 'AppointmentTxn'])->where('id', $app_id)->first();
				$pdf = PDF::loadView('appointments.DownloadApptReceiptPDF', compact('appointment'));
				return $pdf->download('Appointment-receipt.pdf');
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
}
