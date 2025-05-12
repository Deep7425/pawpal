<?php
namespace App\Http\Controllers\API23MAR2023;
use App\Http\Controllers\API23MAR2023\APIBaseController as APIBaseController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Validator;
use Illuminate\Support\Facades\Request as Input;
use App\Models\User;
use App\Models\Coupons;
use App\Models\UserPrescription;
use App\Models\PatientFeedback;
use App\Models\ehr\Patients;
use App\Models\ehr\Appointments;
use App\Models\ehr\ChiefComplaints;
use App\Models\ehr\PatientDiagnosis;
use App\Models\ehr\PatientMedications;
use App\Models\ehr\PatientEyes;
use App\Models\ehr\PatientLabs;
use App\Models\ehr\PatientDiagnosticImagings;
use App\Models\ehr\PatientProcedures;
use App\Models\ehr\PatientAllergy;
use App\Models\ehr\PatientDentals;
use App\Models\ehr\PatientExaminations;
use App\Models\ehr\PatientVitalss;
use App\Models\ehr\FollowUp;
use App\Models\ehr\PatientImmunizations;
use App\Models\ehr\RoleUser;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\PatientReferrals;
use App\Models\ehr\clinicalNotePermissions;

use App\Models\ehr\Nutritionalinfo;
use App\Models\ehr\DietPlan;
use App\Models\ehr\MealPlanMaster;
use App\Models\ehr\PatientPhysicalExcercise;
use App\Models\ehr\DietitianReportTemplate;
use App\Models\ehr\PatientDietitianTemplate;
use App\Models\ehr\PatientDietPlanFile;
use App\Models\ehr\AppointmentOrder;

use App\Models\Doctors;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Hash;
use DB;
use URL;
use Mail;
use File;
use App;
use PDF;
use View;
use Storage;
class PatientEhrController extends APIBaseController {
	public $successStatus = 200;

	public function getPatientPrescription(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['patient_number'] = $data->get('patient_number');
		$validator = Validator::make($user_array,[
			'user_id'   => 'max:50',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$user = UserPrescription::where(['user_id'=>$user_array['user_id'],'delete_status'=>1])->orderBy('created_at','desc')->paginate(10);
			if(count($user)>0) {
				foreach($user as $val) {
					if($val->type == "1") {
						$val['prescription'] = null;
						$val['aptTime'] = date('h:i A',strtotime($val->aptTime));
					}
					else {
						if(!empty($val->prescription)) {
							$image_url = url("/").'/public/prescription-files/'.$val->prescription;
							if(does_url_exists($image_url)) {
								$val['prescription'] = $image_url;
							}
							else{
								$val['prescription'] = null;
							}
						}
						else{
							$val['prescription'] = null;
						}
					}
				}
				// usort($user, array($this, "arraySort"));
				// $perPage = 10;
				// $input = Input::all();
				// if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }
				// $offset = ($currentPage * $perPage) - $perPage;
				// $itemsForCurrentPage = array_slice($user, $offset, $perPage, false);
				// $user =  new Paginator($itemsForCurrentPage, count($user), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				$response = [
					'success' => true,
					'data'    => $user,
					'message' => 'Patient Opd',
				];
				return response()->json($response, 200);
			}
			else{
				return $this->sendResponse($user, 'No Prescription Found',$success = false);
			}
		}
	}

	public function getPatientOpd(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['pid'] = $data->get('pid');
		$validator = Validator::make($user_array,[
			'pid'   => 'max:50',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$user = array();
			if(!empty($user_array['pid'])) {
				$p_ids = User::select("pId")->where(["parent_id"=>$user_array['pid']])->pluck("pId")->toArray();
				array_push($p_ids,$user_array['pid']);
				// if(count($users)){
					// foreach($users as $usr){
						// array_push($p_ids,$usr->pId);
					// }
				// }
				$user = Appointments::with(['user.doctorInfo','Doctors','practiceDetails','AppointmentOrder','NotifyUserSms','PatientFeedback'])->whereIn('pID',$p_ids)->where('delete_status',1)->where('doc_id','!=',2219)->orderBy('start','desc')->paginate(12);
			}
			if(count($user) > 0) {
				foreach($user as $val) {
					$val['patient'] = $this->getUserDetail($val->pId);
					/*$val['AppointmentTxn'] = getAppointmentTxnDetails($val->id);
					if($val->practiceDetails->specialization){
						$val->practiceDetails['spaciality'] =  array("id"=>$val->practiceDetails->specialization,"specialization"=>getSpecialityName($val->practiceDetails->specialization));
					}
					else{
						$val->practiceDetails['spaciality'] = array("id"=>"","specialization"=>"");
					}*/
					if(isset($val->practiceDetails)){
						$val->practiceDetails['country'] = array("id"=>$val->practiceDetails->country_id,"name"=>getCountrieName($val->practiceDetails->country_id));
						$val->practiceDetails['state'] = array("id"=>$val->practiceDetails->state_id,"name"=>getStateName($val->practiceDetails->state_id));
						$val->practiceDetails['city'] = array("id"=>$val->practiceDetails->city_id,"name"=>getCityName($val->practiceDetails->city_id));
					}

					$val['doc_pic'] = null;
					if(!empty($val->user) && !empty($val->user->doctorInfo)) {
						$val['doc_pic'] = $this->getDoctorImg($val->user->doctorInfo->profile_pic);
					}

					if(!empty($val->user) && $val->user->doctorInfo) {
						$val['doc_speciality'] = array("id"=>$val->user->doctorInfo->speciality,"spaciality"=>getSpecialityName($val->user->doctorInfo->speciality),"spaciality_hindi"=>getSpecialityHindiName($val->user->doctorInfo->speciality));
					}
					else{
						$val['doc_speciality'] = array("id"=>"","spaciality"=>"","spaciality_hindi"=>"");
					}
					if(!empty($val->start)){
						$val['app_time_start'] = date('h:i A',strtotime($val->start));
					}
					else{
						$val['app_time_start'] = "";
					}
					/*if($this->getYearForOpd(date('Y',strtotime($val->start))) == date('Y',strtotime($val->start))) {
						$val['opd_year'] = date('Y',strtotime($val->start));
					}
					if(!empty($val->end)){
						$val['app_time_end'] = date('h:i A',strtotime($val->end));
					}
					else{
						$val['app_time_end'] = "";
					}
					if(!empty($val->start) && !empty($val->end)) {
						$to_time = strtotime($val->start);
						$from_time = strtotime($val->end);
						$interval = round(abs($to_time - $from_time) / 60,2);
						$val['app_duration'] = $interval." Minute";
					}
					else{ $val['app_duration'] = "";}
					if(!empty($val->start)) {
						if(strtotime($val->start) < strtotime(date("Y-m-d h:i A"))) {
							$val['visited_app_time_status'] = 1;
						}
						else{
							$val['visited_app_time_status'] = 0;
						}
					}
					else{
						$val['visited_app_time_status'] = 0;
					}*/
					if($val->AppointmentOrder==null) { //echo "kpas";die;
						//$val['is_elite'] = checkAppointmentIsElite($val->id);
						unset($val['AppointmentOrder']);
						$val['appointment_order'] = array('type'=> 6);
					}
					$val['is_elite'] = checkAppointmentIsElite($val->id);
					$val['prescription'] = null;
					if(isset($val->AppointmentOrder) && $val->AppointmentOrder->type == '0') {
						$val['consultation_fees'] = getSetting("tele_main_price")[0];
					}
					$presDta = UserPrescription::where(['appointment_id'=>$val->id])->count();
					if($val->visit_status == 1 && $presDta > 0) {
						$val['prescription'] = 1;
					}
					$val['name'] = '';
					if(!empty($val->user) && !empty($val->user->id)){
						$val['name'] = $this->getDoctorHindiName($val->user->id);
					}
					$val['fees_show'] = '1';
					if(!empty($val->user) && !empty($val->user->id)){
						$val['fees_show'] = $this->checkDoctorFeeShow($val->user->id);
					}
					$followup_count = getFollowUpCount($val->doc_id);
					if($val->visit_type == 6 || $val->type == null) {
						$val['is_followup'] = false;
						$val['followupDone'] = false;
					}
					else{
						$followUp = followupExist($val->start,$followup_count,$val->id,$val->doc_id,$val->pId);
						$val['is_followup'] = $followUp['success'];
						$val['followupDone'] = $followUp['flag'];
					}
					$val['followup_count'] = $followup_count;
					if(!empty($val->PatientFeedback)){
						$val['feedback_done'] = 1;
					}
					else{
						$val['feedback_done'] = 0;
					}
				}
				return $this->sendResponse($user, 'Patient Opd.',$success = true);
			}
			else{
				return $this->sendResponse($user, 'No Appointment Not Found',$success = false);
			}
		}
	}
	public function getDoctorHindiName($id) {
		$name = '';
		$doc = Doctors::select("name")->where("user_id",$id)->first();
		if(!empty($doc)){
			$name = @$doc->name;
		}
		return $name;
	}
	public function checkDoctorFeeShow($id) {
		$show = 1;
		$doc = Doctors::select("fees_show")->where("user_id",$id)->first();
		if(!empty($doc)){
			$show = @$doc->fees_show;
		}
		return $show;
	}
	public function getPatientPrescriptionData(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['appointment_id'] = $data->get('appointment_id');
		$validator = Validator::make($user_array,[
			'appointment_id'   => 'max:50',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$res = $this->getClinicalNoteByApp($user_array['appointment_id'])["url"];
			return $this->sendResponse($res, 'Patient Appointment.',$success = true);
		}
	}
	public function getUserDetail($pid) {
		$user = User::select(["id","patient_number","gender","dob","first_name","last_name","mobile_no","email"])->where('pId', $pid)->first();
		$user["age"] = null;
		if(!empty($user->dob)) {
			$user["age"] = strtoupper(trim(get_patient_age($user->dob)));
		}
		return $user;
	}
	public function getDoctorImg($img) {
		$image_url = getPath("public/doctor/ProfilePics/".$img);
		return  $image_url;
	}
	public function arraySort($a,$b) {
		return strtotime($b->created_at) - strtotime($a->created_at);
	}
	public function getYearForOpd($yr){
		$current_year = date('Y');
		$range = range($current_year, $current_year-10);
		$years = array_combine($range, $range);
		return $years[$yr];
	}
	public function getClinicalNoteByApp($app_id) {
		$presDta = UserPrescription::select(["type","prescription","p_meta_data","patient_number"])->where(['appointment_id'=>$app_id])->orderBy("id","ASC")->first();
		$preUrl = writeClinicNoteFile($presDta->patient_number,$presDta);
		return ['url'=>$preUrl,'type'=>$presDta->type];
	}
	
	public function getClinicalNoteByAppOld(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['appointment_id'] = $data->get('app_id');
		$user_array['doc_id'] = $data->get('doc_id');
		$user_array['user_id'] = $data->get('user_id');
		$user_array['is_print'] = $data->get('is_print');
		$user_array['is_url'] = $data->get('is_url');
		$validator = Validator::make($user_array,[
			'appointment_id'   => 'required|max:50',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$chiefComplaints = []; $treatments = []; $labs = [];  $pAllergies = []; $procedures = []; $pDiagnos = []; $pVitals = [];$immunizations = []; $examinations = []; $dentals = []; $eyes = []; $proce_order = [];  $patientDiagnosticImagings = []; $pReferral = []; $nutritional_info = []; $physical_excercise = []; $dietitian_template = []; $diet_plan = [];
			$chart_height = ""; $chart = "";
			
			$practice =  RoleUser::select(['user_id','role_id','practice_id'])->where(['user_id'=>$user_array['doc_id']])->first();
			$practice_detail =  PracticeDetails::with('PrintSettings')->where(['user_id'=>$practice->practice_id])->first();
			
			$clinical_note_permission = clinicalNotePermissions::where(['user_id'=>$user_array['doc_id'],'practice_id' => $practice->practice_id])->first();
			$permission = explode(',', $clinical_note_permission->modules_access);

			$rows = ['chief'=>'0','diagnosis'=>'0','treatment'=>'0','labOrder'=>'0','di'=>'0','Procedures'=>'0','allergies'=>'0','vitals'=>'0','immunization'=>'0','exam'=>'0','pOrder'=>'0','referral'=>'0','dental'=>'0','followUp'=>'1','eyes'=>'0','pchart'=>'0','nutritional_info'=>'0','diet_plan'=>'0','physical_excercise'=>'0','dietitian_template'=>'0'];

			$patient =  Appointments::with(['Patient.PatientRagistrationNumbers'])->where(['id'=>$user_array['appointment_id'],'delete_status'=>1])->first();

			if(in_array('1',$permission)) {
				$chiefComplaints =  ChiefComplaints::where(['appointment_id'=>$user_array['appointment_id']])->first();
				$rows['chief'] = '1';
			}

			if(in_array('2',$permission)) {
				$pDiagnos = PatientDiagnosis::with(['Diagnosis'])->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['diagnosis'] = '1';
			}
			if(in_array('4',$permission)) {
				$treatments =  PatientMedications::with(['ItemDetails.ItemType'])->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['treatment'] = '1';
			}
			if(in_array('5',$permission)) {
				$eyes = PatientEyes::where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->first();
				$rows['eyes'] = '1';
			}
			if(in_array('6',$permission)) {
				$labs =  PatientLabs::with(['Labs'])->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['labOrder'] = '1';
			}
			if(in_array('7',$permission)) {
				$patientDiagnosticImagings =  PatientDiagnosticImagings::with(['RadiologyMaster'])->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['di'] = '1';
			}
			if(in_array('8',$permission)) {
				$procedures = PatientProcedures::with(['Procedures'])->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->where(function ($query) {
					$query->where('order_date', '=', date("Y-m-d"))
						->orWhereNull('order_date');
					})->get();
				$rows['Procedures'] = '1';
			}
			if(in_array('9',$permission)) {
				$pAllergies= PatientAllergy::with(['Allergies'])->where(['appointment_id'=>$user_array['appointment_id'],'patient_id'=>$patient->pId,'delete_status'=>1])->get();
				$rows['allergies'] = '1';
			}
			if(in_array('10',$permission)) {
				$dentals = PatientDentals::where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['dental'] = '1';
			}

			if(in_array('12',$permission)) {
				$examinations = PatientExaminations::with(['BodySites'])->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['exam'] = '1';
			}
			if(in_array('13',$permission)) {
				$pVitals = PatientVitalss::where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->first();
				$rows['vitals'] = '1';
			}
			if(in_array('14',$permission)) {
				$immunizations = PatientImmunizations::with(['Immunizations'])->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['immunization'] = '1';
			}
			if(in_array('15',$permission)) {
				$proce_order = PatientProcedures::with(['Procedures'])->where(['appointment_id'=>$user_array['appointment_id'],'procedure_type'=>'order','status'=>0,'delete_status'=>1])->get();
				$rows['pOrder'] = '1';
			}
			if(in_array('16',$permission)) {
				$pReferral = PatientReferrals::where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->first();
				$rows['referral'] = '1';
			}
			
			//new modules
			if(in_array('21',$permission)) {
				$nutritional_info = Nutritionalinfo::where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->first();
				$rows['nutritional_info'] = '1';
			}
			if(in_array('18',$permission)) {
				$diet_plan = DietPlan::with('MealPlanMaster')->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['diet_plan'] = '1';
			}
			if(in_array('19',$permission)) {
				$physical_excercise = PatientPhysicalExcercise::with("PhysicalExcerciseMaster")->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['physical_excercise'] = '1';
			}
			if(in_array('20',$permission)) {
				$dietitian_template = PatientDietitianTemplate::with("DietitianReportTemplate")->where(['appointment_id'=>$user_array['appointment_id'],'delete_status'=>1])->get();
				$rows['dietitian_template'] = '1';
			}
			$followUp = FollowUp::where('appointment_id',$user_array['appointment_id'])->where(['added_by'=>$practice->practice_id])->first();
			
			if(in_array('17',$permission)) {
				if(Storage::disk('s3')->exists('/uploads/PatientDocuments/'.$patient->Patient->patient_number.'/misc/growthchart.png')) {
					$chart = getPath('uploads/PatientDocuments/'.$patient->Patient->patient_number.'/misc/growthchart.png');
				}
				if(get_age($patient->Patient->dob) <= 5) {
					if(Storage::disk('s3')->exists('uploads/PatientDocuments/'.$patient->Patient->patient_number.'/misc/growthchartHeight.png')) {
						$chart_height = getPath('uploads/PatientDocuments/'.$patient->Patient->patient_number.'/misc/growthchartHeight.png');
					}
				}
				$rows['pchart'] = '1';
			}
			
			$docPath = 'uploads/PatientDocuments/'.$patient->Patient->patient_number.'/misc/';
			if(!Storage::disk('s3')->exists($docPath)) {
				Storage::disk('s3')->makeDirectory($docPath);
			}
			// if(!is_dir($docPath)){
				 // File::makeDirectory($docPath, $mode = 0777, true, true);
			// }
			// if(!file_exists($docPath.'clinicalNotePrint.pdf')) {
				// File::copy(public_path().'/htmltopdfview.pdf', $docPath.'clinicalNotePrint.pdf');
				// File::makeDirectory($docPath.'clinicalNotePrint.pdf', $mode = 0777, true, true);
			// }
			$html = view('ehr_print.clinical_note_print',compact('chart','chart_height','patient','chiefComplaints','labs','pAllergies','procedures','pDiagnos','pVitals','immunizations','examinations','proce_order','treatments','patientDiagnosticImagings','practice_detail','rows','pReferral','dentals','followUp','eyes','nutritional_info','diet_plan','physical_excercise','dietitian_template'))->render();
			$output = PDF::loadHTML($html)->output();
			Storage::disk('s3')->put($docPath.'clinicalNotePrint.pdf', $output);
			// file_put_contents(public_path()."/PatientDocuments/".$patient->Patient->patient_number."/misc/clinicalNotePrint.pdf", $output);
			// $pdf_url = 	url("/")."/public/PatientDocuments/".$patient->Patient->patient_number."/misc/clinicalNotePrint.pdf";
			$pdf_url = 	getPath($docPath.'clinicalNotePrint.pdf');
			return $this->sendResponse($pdf_url,'',true);
		}
	}

	public function uploadDocument(Request $request) {
		if($request->isMethod('post')) {
			$data = $request->all();
			$validator = Validator::make($data, [
				// 'patient_number' => 'required',
				'pid' => 'required',
				'document' =>'required|mimes:jpeg,png,jpg,pdf'
			]);
			if($validator->fails()){
				$message = $validator->messages();
				$err = $message->first('document') !== null ? $message->first('document') : 'Please upload valid document';
				return $this->sendError($err);
			}
			else{
				if($request->hasFile('document')) {
					$images = $request->file('document');
					$fileName = strtolower(str_replace(" ","",$images->getClientOriginalName()));
					$file_size_byte = filesize($images);
					if($file_size_byte >= 1048576*10){
						return $this->sendError('File Size '.number_format($file_size_byte / 1048576, 10) . ' MB is exceed,(Max : 10 Mb Allowed)');
					}
					$filepath = 'uploads/PatientDocuments/'.$data['patient_number'].'/appointments/'.$data['appointment_id'].'/'; 
					// if(!is_dir($filepath)){
					 // File::makeDirectory($filepath, $mode = 0777, true, true);
					// }
					// pr($filepath);
					if(!Storage::disk('s3')->exists($filepath)) {
						Storage::disk('s3')->makeDirectory($filepath);
					}
					Storage::disk('s3')->put($filepath.$fileName, file_get_contents($images));
					Appointments::where('id', $data['appointment_id'])->update(array('is_document_uploaded' => 1));
					// $request->file('document')->move($filepath, $fileName);
					// $this->compress($fileName, $filepath);
					// @file_get_contents(getEhrUrl()."/AppointmentFileWriteByUrl?p_num=".$data['patient_number']."&appointment_id=".$data['appointment_id']."&fileName=".$fileName);

					$path = 'uploads/PatientDocuments/'.$data['patient_number'].'/appointments'."/".$data['appointment_id'];
					$path_url = "uploads/PatientDocuments/".$data['patient_number'];
					$file_arr = [];
					$S3AllFiles = Storage::disk('s3')->files($path);
					if(count($S3AllFiles)>0){
					  foreach ($S3AllFiles as $key => $file) {
						 $lastString = substr($file, strrpos($file, '/') + 1);	
						 $filesArr[] = $lastString;
					  }
					}
					// pr($filesArr);
					// if(is_dir($path."/".$data['appointment_id'])) {
						// $file_name = array_slice(scandir($path."/".$data['appointment_id']) , 2);
						foreach($filesArr as $file_nm) {
							if(!empty($file_nm) && $file_nm != "htmltopdfview.pdf"){
								$file_ext = explode('.', $file_nm);
								$file_ext_count = count($file_ext);
								$cnt = $file_ext_count - 1;
								$file_extension = $file_ext[$cnt];
								$file_arr[] = array("document" => getPath($path_url."/appointments/".$data['appointment_id']."/".$file_nm),'doc_name'=>$file_nm,"file_ext" => $file_extension);
							}
						}
					// }
					return $this->sendResponse($file_arr, 'Patient Prescription Uploaded Successfully.',true);
				}
				else{
					return $this->sendError('File Does not exist');
				}
			}
		}
	}

		public function compress($source, $destination) {
			$info = getimagesize($destination."/".$source);
			if ($info['mime'] == 'image/jpeg')
				$image = imagecreatefromjpeg($destination."/".$source);
			elseif ($info['mime'] == 'image/png')
				$image = imagecreatefrompng($destination."/".$source);
			else return $destination;
			imagejpeg($image, $destination."/".$source, 70);
			return $destination;
		}
		
		public function getUserDocument(Request $request){
			if($request->isMethod('post')) {
				$data = Input::json();
				$user_array = array();
				$user_array['patient_number'] = $data->get('patient_number');
				$user_array['appointment_id'] = $data->get('appointment_id');
				$validator = Validator::make($user_array, ['appointment_id' => 'required|max:50', ]);
				if ($validator->fails()){
					return $this->sendError('Validation Error.', $validator->errors());
				}
				else{
					$path = 'uploads/PatientDocuments/'.$user_array['patient_number'].'/appointments'."/".$user_array['appointment_id'];
					$path_url = 'uploads/PatientDocuments/'.$user_array['patient_number'];
					$file_arr = [];
					$filesArr = [];
					$S3AllFiles = Storage::disk('s3')->files($path);
					if(count($S3AllFiles)>0){
					  foreach ($S3AllFiles as $key => $file) {
						 $lastString = substr($file, strrpos($file, '/') + 1);	
						 $filesArr[] = $lastString;
					  }
					}
					// pr($filesArr);
					// if (is_dir($path."/".$user_array['appointment_id'])) { 
						// $file_name = array_slice(scandir($path."/".$user_array['appointment_id']) , 2);
						foreach($filesArr as $file_nm) {
							if(!empty($file_nm) && $file_nm != "htmltopdfview.pdf"){
								$file_ext = explode('.', $file_nm);
								$file_ext_count = count($file_ext);
								$cnt = $file_ext_count - 1;
								$file_extension = $file_ext[$cnt];
								$file_arr[] = array("document" => getPath($path_url."/appointments/".$user_array['appointment_id']."/".$file_nm),'doc_name'=>$file_nm,"file_ext" => strtolower($file_extension),"src"=>getPath($path_url."/appointments/".$user_array['appointment_id']."/".$file_nm));
							}
						}
					// }
					// $file_arr[] = ["document"=>"http://103.66.73.17/HGlive/uploads/PatientDocuments/P2850/appointments/11840/1598617616.mp3","doc_name"=>"1598618343.mp3","file_ext"=>"mp3","src"=>"http://103.66.73.17/HGlive/uploads/PatientDocuments/P2850/appointments/11840/1598617616.mp3"];
					
					// $file_arr[] = ["document"=>"https://www.healthgennie.com/public/PatientDocuments/P24221/appointments/52191/1598703929829.jpg","doc_name"=>"1598703929829.jpg","file_ext"=>"jpg","src"=>"https://www.healthgennie.com/public/PatientDocuments/P24221/appointments/52191/1598703929829.jpg"];
					return $this->sendResponse($file_arr, '',true);
				}
			}
		}
	
		public function downloadReceipt(Request $request){
			$data=Input::json();
			$user_array=array();
			$user_array['app_id'] = $data->get('app_id');
			$user_array['lng'] = $data->get('lng');
			$validator = Validator::make($user_array, [
				'app_id' => 'required|max:50',
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$appointment = Appointments::with(['patient.PatientRagistrationNumbers','practiceDetails','user.doctorInfo.docSpeciality'])->where('id',$user_array['app_id'])->first();
				$doctor = Doctors::with('docSpeciality')->where('user_id',$appointment->doc_id)->first();
				if($user_array['lng'] == "hi"){
					$appointmentData = view('appointments.DownloadReceiptPDFHindi',compact('appointment','doctor'))->render();
				}
				else{
					$appointmentData = view('appointments.DownloadReceiptPDF',compact('appointment','doctor'))->render();
				}
				$output = PDF::loadHTML($appointmentData)->output();
				file_put_contents(public_path()."/pdfviewforAppointment.pdf", $output);
				$pdf_url = 	url("/")."/public/pdfviewforAppointment.pdf?".time();
				return $this->sendResponse($pdf_url,'',true);
			}
		}

		public function deleteDocument(Request $request){
			$data=Input::json();
			$user_array=array();
			$user_array['patient_number'] = $data->get('patient_number');
			$user_array['appointment_id'] = $data->get('appointment_id');
			$user_array['document'] = $data->get('document');
			
			$validator = Validator::make($user_array, [
				'document' => 'required',
				'patient_number' => 'required',
				'appointment_id' => 'required',
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$success = false;
				$filename = 'uploads/PatientDocuments/'.$user_array['patient_number'].'/appointments/'.$user_array['appointment_id']."/".$user_array['document'];
				if(Storage::disk('s3')->exists($filename)) {
				   Storage::disk('s3')->delete($filename);
				   $success = true;
				}
				$path = 'uploads/PatientDocuments/'.$user_array['patient_number'].'/appointments'."/".$user_array['appointment_id'];
				$path_url = 'uploads/PatientDocuments/'.$user_array['patient_number'];
				$filesArr = [];
				$file_arr = [];
				$S3AllFiles = Storage::disk('s3')->files($path);
				if(count($S3AllFiles)>0){
				  foreach ($S3AllFiles as $key => $file) {
					 $lastString = substr($file, strrpos($file, '/') + 1);	
					 $filesArr[] = $lastString;
				  }
				}
				// if(!empty($path."/".$user_array['appointment_id'])) {
					// $file_name = array_slice(scandir($path."/".$user_array['appointment_id']) , 2);
					foreach($filesArr as $file_nm) {
						if(!empty($file_nm) && $file_nm != "htmltopdfview.pdf"){
							$file_ext = explode('.', $file_nm);
							$file_ext_count = count($file_ext);
							$cnt = $file_ext_count - 1;
							$file_extension = $file_ext[$cnt];
							$file_arr[] = array("document" => getPath($path_url."/appointments/".$user_array['appointment_id']."/".$file_nm),'doc_name'=>$file_nm,"file_ext" => $file_extension,"src"=>getPath($path_url."/appointments/".$user_array['appointment_id']."/".$file_nm));
						}
					}
				// }
				return $this->sendResponse($file_arr, '',$success);
			}
		}
		
		public function deletePrescription(Request $request){
			$data=Input::json();
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$validator = Validator::make($user_array, [
				'id' => 'required|max:50',
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$success = false;
				if(!empty($user_array['id'])) {
					$user = UserPrescription::where(['id'=>$user_array['id']])->first();
					$filename = public_path().'/prescription-files/'.$user->prescription;
					if(file_exists($filename)){
						File::delete($filename);
					}
					UserPrescription::where(['id'=>$user_array['id']])->delete();
					$success = true;
				}
				return $this->sendResponse('', '',$success);
			}
		}
		
		public function feedback(Request $request) {
			$data=Input::json();
			$user_array=array();
			$user_array['appointment_id'] = $data->get('appointment_id');
			$user_array['doc_id'] = $data->get('doc_id');
			$user_array['experience'] = $data->get('experience');
			$user_array['publish_status'] = $data->get('publish_status');
			$user_array['rating'] = $data->get('rating');
			$user_array['recommendation'] = $data->get('recommendation');
			$user_array['suggestions'] = $data->get('suggestions');
			$user_array['user_id'] = $data->get('user_id');
			$user_array['visit_type'] = $data->get('visit_type');
			$user_array['waiting_time'] = $data->get('waiting_time');
			$validator = Validator::make($user_array, [
				'doc_id' => 'required|max:50',
				'rating' => 'required|max:50',
				'user_id' => 'required|max:50',
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$success = true;
				// if(!empty($user_array['appointment_id'])) {
					// $user_array['doc_id'] =  @Doctors::select('id')->where('user_id',$user_array['doc_id'])->first()->id;
				// }
				$feedback = PatientFeedback::create([
					'appointment_id' =>  $user_array['appointment_id'],
					'doc_id' =>  $user_array['doc_id'],
					'experience' =>  $user_array['experience'],
					'publish_status' =>  $user_array['publish_status'],
					'rating' =>  $user_array['rating'],
					'recommendation' =>  $user_array['recommendation'],
					'suggestions' =>  $user_array['suggestions'],
					'user_id' =>  $user_array['user_id'],
					'visit_type' =>  $user_array['visit_type'],
					'waiting_time' =>  $user_array['waiting_time'],
				]);
				$user = User::where('id', $user_array['user_id'])->first();
				if(!empty($user) && !empty($user->mobile_no)) {
					$message = urlencode("Dear ".$user->first_name.", Thanks for your valuable feedback Team Health Gennie");
					$this->sendSMS($user->mobile_no,$message,'1707161588020533679');
				}
				return $this->sendResponse($feedback, 'Thanks for your valuable feedback.',$success);
			}
		}
		public function latestappointmentfeedback(Request $request){
			$data=Input::json();
			$user_array=array();
			$user_array['pId'] = $data->get('pId');
			$user_array['mobile_no'] = $data->get('mobile_no');
			$validator = Validator::make($user_array, [
				// 'pId' => 'required|max:50',
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$res = ["appointment_id"=>null,"doc_id"=>null,"clinic_name"=>null];
				$success = false;
				$date = date('Y-m-d H:i:s');
				if(!empty($user_array['mobile_no'])){
					$pIds = User::select("pId")->where(["mobile_no"=>$user_array['mobile_no']])->pluck("pId");
					$appointment = Appointments::with(['patient','AppointmentOrder','user.doctorInfo.docSpeciality','practiceDetails.state','practiceDetails.city','Doctors'])->whereIn('pId',$pIds)->where(["delete_status"=>1,"appointment_confirmation"=>1])->where('doc_id','!=',2219)
					    //->whereRaw('date(start) >= ?', date('Y-m-d H:i:s'))
					    ->whereDate('start','>=',date('Y-m-d'))
					    ->whereTime('start', '>=',date('H:i:s'))
					    ->orderBy('start','ASC')->first();
					//pr($appointment);
				}
				else{
					$appointment = Appointments::with(['patient','AppointmentOrder','user.doctorInfo.docSpeciality','practiceDetails.state','practiceDetails.city','Doctors'])->where(['pId'=>$user_array['pId'],"delete_status"=>1,"appointment_confirmation"=>1])->where('doc_id','!=',2219)
					   //->whereRaw('date(start) >= ?', date('Y-m-d'))
					   ->whereDate('start','>=',date('Y-m-d'))
					   ->whereTime('start', '>=',date('H:i:s'))
					   ->orderBy('start','ASC')->first();
				}
				if(!empty($appointment)) {
					// $appointment['patient'] = $this->getUserDetail($appointment->pId);
					$appointment['patient']["age"] = null;
					if(!empty($appointment->patient->dob)) {
						$appointment['patient']["age"] = strtoupper(trim(get_patient_age($appointment->patient->dob)));
					}
					// pr($appointment['patient']);
					$feedback = PatientFeedback::where("appointment_id",$appointment->id)->count();
					if($feedback == 0 && strtotime($appointment->end) < strtotime($date) && $appointment->visit_status == 1) {	
						$success = true;	
					}
					// echo strtotime($date).'---'.strtotime($appointment->end);die;
					// if(strtotime($appointment->end) > strtotime($date) && $appointment->visit_status != 1) {	//echo "kaps";die;
						// $success = false;	
					// }
					$appointment['doc_id'] = $appointment->doc_id;
					$appointment['name'] = $this->getDoctorHindiName($appointment->doc_id);
					$appointment['clinic_name'] = @$appointment->user->doctorInfo->first_name." ".$appointment->user->doctorInfo->last_name;
					$appointment['appointment_id'] = $appointment->id;
					$appointment['doc_type'] = @$appointment->Doctors->doc_type;
					$appointment['doc_pic'] = null;
					if(!empty($appointment->user->doctorInfo)) {
						$appointment['doc_pic'] = $this->getDoctorImg($appointment->user->doctorInfo->profile_pic);
					}
					$appointment['AppointmentTxn'] = getAppointmentTxnDetails($appointment->id);
					if($appointment->AppointmentOrder==null){ 
						//$val['is_elite'] = checkAppointmentIsElite($val->id);
						unset($appointment['AppointmentOrder']);
						$appointment['appointment_order'] = array('type'=> 6);
					}
					if(!empty($appointment->user) && $appointment->user->doctorInfo) {
						$appointment['doc_speciality'] = array("id"=>$appointment->user->doctorInfo->speciality,"spaciality"=>getSpecialityName($appointment->user->doctorInfo->speciality),"spaciality_hindi"=>getSpecialityHindiName($appointment->user->doctorInfo->speciality));
					}
					else{
						$appointment['doc_speciality'] = array("id"=>"","spaciality"=>"","spaciality_hindi"=>"");
					}
					if(!empty($appointment->AppointmentOrder) && $appointment->AppointmentOrder->type == '0'){
						$appointment['consultation_fees'] = getSetting("tele_main_price")[0];
					}
					if($feedback == 0 && strtotime($appointment->end) < strtotime($date) && $appointment->visit_status != 1) {	
						$success = false;	
						$appointment = null;
					}
				}
				return $this->sendResponse($appointment, '',$success);
			}
		}
		
		public function checkAppointmentCouponCode(Request $request) {
			 if($request->isMethod('post')) {
				$data = Input::json();
				$user_array=array();
				$user_array['fee'] = $data->get('fee');
				$user_array['doc_id'] = $data->get('doc_id');
				$user_array['coupon_code'] = $data->get('coupon_code');
				$user_array['isFirstTeleAppointment'] = $data->get('isFirstTeleAppointment');
				$user_array['lng'] = $data->get('lng');
				$user_array['p_id'] = $data->get('p_id');
				$validator = Validator::make($user_array, [
					'coupon_code'   => 'required',
				]);
				if($user_array['isFirstTeleAppointment'] == 1) {
					 if($user_array['lng'] == "hi") {
						return $this->sendResponse('',' कूपन कोड लागू नहीं है',false);
					 }
					 else{
						 return $this->sendResponse('','Coupon Code not applicable ',false);
					 }
				}

				/*if($user_array['isFirstTeleAppointment'] == 0 && $data->get('isDirectAppt') == 1 && strcasecmp("indhg", $user_array['coupon_code'] == 0) ) {
					 if($user_array['lng'] == "hi") {
						return $this->sendResponse('','नि: शुल्क नियुक्ति के लिए कूपन कोड लागू नहीं है',false);
					 }
					 else{
						 return $this->sendResponse('','This Coupon Code not applicable for instant appointment',false);
					 }
				} */

				if($data->get('isDirectAppt') == 0 && strcasecmp("freehg", $user_array['coupon_code']) == 0 ) {
					if($user_array['lng'] == "hi") {
					return $this->sendResponse('','यह कूपन कोड मान्य नहीं है।',false);
					}
					else{
					return $this->sendResponse('','Coupon Code Not Matched.',false);
					}
				}

				if($data->get('isDirectAppt') == 1 && strcasecmp("freehg", $user_array['coupon_code'])  == 0 ) {
					$coupon_data =  Coupons::where("coupon_code",$user_array['coupon_code'])->where("delete_status","1")->where("type","2")->first();
					$p_id = getParentId($user_array['p_id']); //pr($p_id);
					$countCoupon = AppointmentOrder::where('coupon_id',$coupon_data->id)->where('order_by',$p_id)->where('order_status',1)->count();
					
					if($countCoupon > 0){
						if($user_array['lng'] == "hi") {
						return $this->sendResponse('','कूपन कोड पहले से ही इस्तेमाल किया जा चुका है ',false);
						}
						else{
						return $this->sendResponse('','Coupon Code Is Already Used.',false);
						}
					}
				}
				
				if($data->get('appt_type') == 2 && strcasecmp("gennie50", $user_array['coupon_code'])  == 0 ) {
					 if($user_array['lng'] == "hi") {
						return $this->sendResponse('','कूपन कोड केवल टेली परामर्श नियुक्तियों के लिए लागू है',false);
					 }
					 else{
						 return $this->sendResponse('','Coupon code only applicable for tele consultation appointments.',false);
					 }
				}
				
				if($data->get('appt_type') == 1 && strcasecmp("gennie50", $user_array['coupon_code']) == 0) {
					 if($user_array['fee'] > 500) {
						 if($user_array['lng'] == "hi") {
							return $this->sendResponse('','कूपन कोड केवल ₹500 या ₹500 से कम के डॉक्टर परामर्श शुल्क के लिए लागू है।',false);
						 }
						 else{
							 return $this->sendResponse('','Coupon code only applicable for ₹ 500 or below ₹ 500  doctor consultation fee.',false);
						 }
					 }
				}

				if($validator->fails()) {
					 if($user_array['lng'] == "hi") {
						return $this->sendResponse('','कूपन कोड आवश्यक है',false);
					 }
					 else{
						 return $this->sendResponse('','Coupon Code Is Required',false);
					 }
				}
				else {
					 $success = false;
					 $dt = date('Y-m-d');
					 // $coupon_data =  Coupons::whereRaw("BINARY `coupon_code`= ?",[$user_array['coupon_code']])->where(['type'=>'2'])->first();
					 $coupon_data =  Coupons::where("coupon_code",$user_array['coupon_code'])->where("delete_status","1")->where("type","2")->first();
					 if(!empty($coupon_data)){
					 	 if($coupon_data->coupon_sub_type != $data->get('appt_type')) {
					 	 	  if($user_array['lng'] == "hi") {
								return $this->sendResponse('','यह कूपन कोड मान्य नहीं है।',false);
								}
								else{
								return $this->sendResponse('','Coupon Code Not Matched.',false);
							   }
					 	 }else{
								if($coupon_data->status != '1') {
								if($user_array['lng'] == "hi") {
								return $this->sendResponse('','कूपन कोड सक्रिय नहीं है',false);
								}
								else{
								return $this->sendResponse('','Coupon Code does Not Active',false);
								}
								}
								else if($coupon_data->coupon_last_date < $dt){
								if($user_array['lng'] == "hi") {
								return $this->sendResponse('','कूपन कोड समाप्त हो गया है',false);
								}
								else{
								return $this->sendResponse('','Coupon Code Is Expired',false);
								}
								}
								else{
								$success = true;
								if($user_array['lng'] == "hi") {
								return $this->sendResponse($coupon_data, 'कूपन सफलतापूर्वक लागू किया गया।',$success);
								}
								else{
								return $this->sendResponse($coupon_data, 'Coupon Applied Successfully.',$success);
								}
								}
					 	}
						 
					 }
					 else{
						 if($user_array['lng'] == "hi") {
							return $this->sendResponse('','यह कूपन कोड मान्य नहीं है।',false);
						 }
						 else{
							 return $this->sendResponse('','Coupon Code Not Matched.',false);
						 }
					 }
				}
			}
		}

		public function fetchApptDetails(Request $request) {
			$data=Input::json();
			$user_array=array();
			$user_array['appt_id'] = $data->get('appt_id');
			$validator = Validator::make($user_array,[
				'appt_id'   => 'required',
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$appt = Appointments::with(['Doctors.docSpeciality','patient'])->where('id',$user_array['appt_id'])->first();
				if(!empty($appt->Doctors) && $appt->Doctors->docSpeciality) {
					$appt['doc_speciality'] = array("id"=>$appt->Doctors->docSpeciality->id,"spaciality"=>$appt->Doctors->docSpeciality->spaciality,"spaciality_hindi"=> $appt->Doctors->docSpeciality->spaciality_hindi);
				}
				else{
					$appt['doc_speciality'] = array("id"=>"","spaciality"=>"","spaciality_hindi"=>"");
				}
				$appt['doc_pic'] = null;
				if(!empty($appt->Doctors) && !empty($appt->Doctors->profile_pic)) {
					$appt['doc_pic'] = $this->getDoctorImg($appt->Doctors->profile_pic);
				}
				return $this->sendResponse($appt, 'Patient Appointment Details.',true);
			}
		}
}
