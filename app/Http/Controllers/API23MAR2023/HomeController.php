<?php
namespace App\Http\Controllers\API23MAR2023;

use App\Http\Controllers\API23MAR2023\APIBaseController as APIBaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Hash;
use DB;
use URL;
use File;
use Mail;
use App\Models\NewsFeeds;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\PpSliders;
use App\Models\Doctors;
use App\Models\NonHgDoctors;
use App\Models\Speciality;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\ratingReviews;
use App\Models\SearchResults;
use App\Models\CityLocalities;
use App\Models\Admin\SymptomsSpeciality;
use App\Models\Admin\Symptoms;
use App\Models\Admin\SymptomTags;
use App\Models\OutSideAppointments;
use App\Models\ehr\User as ehrUser;
use App\Models\ehr\Appointments;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\RoleUser;
use App\Models\ehr\Patients;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\DoctorsInfo;
use App\Models\ehr\PracticeDocuments;
use App\Models\ehr\PatientRagistrationNumbers;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Models\WaitingTimeMaster;
use App\Models\ComplimentsMaster;
use App\Models\ehr\AppointmentOrder;
use App\Models\ReferralMaster;
use App\Models\PlanPeriods;
use Softon\Indipay\Facades\Indipay;
use Carbon\Carbon;
use App\Models\LabOrders;
use App\Models\UsersOTP;
use App\Models\UserSubscribedPlans;
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
use Storage;
use PaytmWallet;
class HomeController extends APIBaseController {

	public function getNewsFeedsData(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$query = NewsFeeds::where(["status"=>1])->whereRaw("find_in_set('2',news_feeds.type)");
		$news_feed_data = $query->orderBy("show_date","DESC")->paginate(6);
		foreach($news_feed_data as $value) {
			if(!empty($value->image)) {
				$image_url = url("/")."/public/newsFeedFiles/".$value->image;
				$value['image'] = $image_url;
			}
			else{
				$value['image'] = null;
			}
		}
		return $this->sendResponse($news_feed_data, '',$success = true);
    }
	public function getCountryPhoneCode(Request $request){
		$countries = Country::groupBy("phonecode")->orderBy("phonecode")->get();
		return $this->sendResponse($countries, '',$success = true);
	}

	public function getPatientPortalSliders(Request $request) {
        $pp_slider_data = PpSliders::orderBy("id","ASC")->Where(['delete_status'=>1])->get();
		foreach($pp_slider_data as $value){
		   $value['image'] = url("/")."/public/slidersImages/".$value->image;
		}
		return $this->sendResponse($pp_slider_data, '',$success = true);
    }

	public function getMaxLongitude($lat,$lng,$distance){
		$R = 6371; //constant earth radius. You can add precision here if you wish
		$maxLon = round($lng + rad2deg(asin($distance/$R) / cos(deg2rad($lat))),6);
		return $maxLon;
	}

	function searchDoctors(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['search_key'] = $data->get('search_key');
		$user_array['address'] = $data->get('address');
		$user_array['city'] = $data->get('city');
		$user_array['locality'] = $data->get('locality');
		$user_array['lat'] = $data->get('lat');
		$user_array['lng'] = $data->get('lng');
		$user_array['user_id'] = $data->get('user_id');
		$docs_array = [];
		$success = false;
		if($user_array['search_key'] != '') {
			
			$firstChar = trim(strtolower(substr($user_array['search_key'], 0, 3)));
			if($firstChar == "dr." || $firstChar == "dr") {
				$user_array['search_key'] = trim(str_replace($firstChar,'',strtolower($user_array['search_key'])));
			}
			/** For Doctor Name wise**/
			$dotor_data = Doctors::with("DoctorRatingReviews")->Where(['delete_status'=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0);
			/** Clinic name wise**/
			$doctor_clinic = Doctors::with("DoctorRatingReviews")->select(["id","user_id","clinic_name","practice_id","clinic_image","practice_type"])->Where(['delete_status'=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0);

			if(!empty($user_array['city'])) {
				//$dotor_data->where('city_id',$user_array['city']);
				//$doctor_clinic->where('city_id',$user_array['city']);
			}
			if(!empty($user_array['locality'])) {
				//$dotor_data->where('locality_id',$user_array['locality']);
				///$doctor_clinic->where('locality_id',$user_array['locality']);
			}
			  $search_key = $user_array['search_key'];
			  // if($user_array['lng'] == "hi") {
					// $dotor_data->where(function ($q) use ($search_key) {
						// $q->where('name', 'like', '%'.$search_key.'%')
						// ->orWhere('name', 'SOUNDS LIKE', '%'.$search_key);
					// });
			  // }
			  // else{
				  $explodName = explode(" ", $search_key);
				  $search_key2 = preg_replace('/[^A-Za-z0-9\-]/', '', $search_key);
				  if (strlen($search_key2) == 2) {
					$search_key = preg_replace('/[^A-Za-z0-9\-]/', '', $search_key);
					$search_key = wordwrap($search_key, 1, " ", true);
					$explodName = explode(" ", $search_key);
					$dotor_data->where(function ($q) use ($search_key, $explodName) {
						$q->where(\DB::raw("INSERT(LEFT(REPLACE(REPLACE(CONCAT(first_name, ' ', IFNULL(last_name,'')), '.', ''), ' ', ''),2), 2, 0, ' ')"), 'LIKE', $search_key.'%')
						->orWhere(function ($q) use ($search_key, $explodName) {
						  $q->Where(\DB::raw("LEFT(first_name, 1)"), '=', $explodName[0])
						  ->Where(\DB::raw("LEFT(IFNULL(last_name,''), 1)"), '=', $explodName[1]);
						});
					});
				  }
				  else{
						$dotor_data->where(function ($q) use ($search_key) {
							$q->where(DB::raw('concat(first_name," ",IFNULL(last_name,""))'), 'like', '%'.$search_key.'%')
							->orWhere('first_name', 'SOUNDS LIKE', '%'.$search_key);
						});
						if (isset($explodName[0]) && isset($explodName[1])) {
						  $dotor_data->orWhere(function ($q) use ($search_key, $explodName) {
							$q->Where(\DB::raw("LEFT(first_name, 1)"), '=', $explodName[0])
							->Where(\DB::raw("IFNULL(last_name,'')"), 'like', '%'.$explodName[1].'%');
						  });
						}
				  }
			  // }
			/** For Doctor Name **/
			$doc_data_by_name = [];
			if($user_array['lng'] == "hi") {
				$doc_data_by_name = $dotor_data->limit(5)->get();
			}
			else{
				$doc_data_by_name = $dotor_data->limit(5)->get();
			}
			if(count($doc_data_by_name) > 0 ) {
				$docs_array["Doctors"] = $this->bindDocData($doc_data_by_name);
				$success = true;
			}
			else{
				$docs_array["Doctors"] = [];
			}
			$clinic_array = [];	
			$hospital_array = [];
			/** Clinic name **/
			$doc_data_by_clinic_name = [];
			if($user_array['lng'] == "hi") {
				$doc_data_by_clinic_name = $doctor_clinic->where('clinic_name', 'like', '%'.$user_array['search_key'].'%')->groupBy('practice_id')->orderBy('hg_doctor','DESC')->limit(5)->get();
			}
			else{
				$doc_data_by_clinic_name = $doctor_clinic->where('clinic_name', 'like', '%'.$user_array['search_key'].'%')->groupBy('practice_id')->orderBy('hg_doctor','DESC')->limit(5)->get();
			}
			if(count($doc_data_by_clinic_name) > 0) {
				foreach($doc_data_by_clinic_name as $val) {
					if(!empty($val->clinic_image)){
						$image_url = getPath("public/doctor/".$val->clinic_image);
						$val['clinic_image'] = $image_url;
					}
					else{
						$val['clinic_image'] = null;
					}
					$val['doc_rating'] = 0;
					if(isset($val->DoctorRatingReviews)) {
						if(count($val->DoctorRatingReviews) > 0) {
							$rating_val = 0;
							$rating_count = 0;
							foreach($val->DoctorRatingReviews as $rating) {
								$rating_val += $rating->rating;
								$rating_count++;
							}
							if($rating_val > 0){
								$rating_val = round($rating_val/$rating_count,1);
							}
							$val['doc_rating'] = $rating_val;
						}
					}
					if($val->practice_type == 2){
						$hospital_array[] = $val;
					} 
					else {
						$clinic_array[] = $val;
					}
				}
				$docs_array["Clinic"] = $clinic_array;
				$docs_array["Hospital"] = $hospital_array;
				$success = true;
			}
			else{
				$docs_array["Clinic"] = [];
				$docs_array["Hospital"] = [];
			}

			/** Specialty name **/
			
			// $doc_data_by_spaciality = Speciality::where(DB::raw('concat(specialities," ",IFNULL(spaciality,""))'), 'like', '%'.$search_key.'%')->limit(5)->get();
			// if(count($doc_data_by_spaciality) > 0) {
				// foreach($doc_data_by_spaciality as $value){
					// if(!empty($value->speciality_icon)){
						// $value['speciality_icon'] = url("/")."/public/speciality-icon/".$value->speciality_icon;
					// }
				// }
				// $docs_array["Speciality"] = $doc_data_by_spaciality;
				// $success = true;
			// }else{
				// $docs_array["Speciality"] = [];
			// }
			
			$search_key = $user_array['search_key'];
			$sptArr = [];
			$doc_data_by_spaciality = [];
			if($user_array['lng'] == "hi") {
				$doc_data_by_spaciality = Speciality::where(DB::raw('concat(specialities," ",IFNULL(spaciality,""))'), 'like', '%'.$search_key.'%')->limit(5)->get();
			}
			else{
				$doc_data_by_spaciality = Speciality::where(DB::raw('concat(specialities," ",IFNULL(spaciality,""))'), 'like', '%'.$search_key.'%')->limit(5)->get();
			}
			
			if(count($doc_data_by_spaciality) > 0) {
				foreach($doc_data_by_spaciality as $value){
					if(!empty($value->speciality_icon)){
						$value['speciality_icon'] = url("/")."/public/speciality-icon/".$value->speciality_icon;
					}
				}
				$sptArr = $doc_data_by_spaciality;
				$success = true;
			}
			$docBySptTags = [];
			if($user_array['lng'] == "hi") {
				$docBySptTags = Speciality::where('tags', 'like', '%'.$search_key.'%')->limit(5)->get();	
			}			
			else{
				$docBySptTags = Speciality::where('tags', 'like', '%'.$search_key.'%')->limit(5)->get();
			}
			if(count($docBySptTags) > 0) {
				foreach($docBySptTags as $value){
					if(!empty($value->speciality_icon)){
						$value['speciality_icon'] = url("/")."/public/speciality-icon/".$value->speciality_icon;
					}
					if(!empty($value->tags)) {
						$tags = explode(",",$value->tags);
						$value['specialities'] = $value->specialities." ($tags[0])";
					}
					
				}
				$success = true;
				$sptArr = $doc_data_by_spaciality->merge($docBySptTags);
			}
			$docs_array["Speciality"] = $sptArr; 
			
			if($user_array['lng'] == "hi") {
				$symptomps_query = Symptoms::with(['SymptomsSpeciality','SymptomTags'])->Where(['status'=>1,'mh_status'=>0])->Where('symptom_hindi', 'like', '%'.$search_key.'%');
				$symptomps_query->OrWhereHas("SymptomTags",function($qry) use($search_key) {
						$qry->Where('text','like','%'.$search_key.'%');
				});
				$doc_data_by_symptomps = $symptomps_query->limit(5)->get();
			}
			else{
				$symptomps_query = Symptoms::with(['SymptomsSpeciality','SymptomTags'])->Where(['status'=>1,'mh_status'=>0])->Where('symptom', 'like', '%'.$search_key.'%');
				$symptomps_query->OrWhereHas("SymptomTags",function($qry) use($search_key) {
						$qry->Where('text','like','%'.$search_key.'%');
				});
				$doc_data_by_symptomps = $symptomps_query->limit(5)->get();
			}
		    
			if(count($doc_data_by_symptomps) > 0) {
				$docs_array["symptoms"] = $doc_data_by_symptomps;
				$success = true;
			}
			else{
				$docs_array["symptoms"] = [];
			}
			if(count($docs_array["Doctors"]) <= 0 || count($docs_array["Clinic"]) <= 0 || count($docs_array["Speciality"]) <= 0 || count($docs_array["symptoms"]) <= 0) {
				$this->saveSerachResultData($user_array['user_id'],$user_array['search_key']);
			}
		}
		return $this->sendResponse($docs_array, 'Doctor Details get Successfully.',$success);
	}

	public function saveSerachResultData($user_id,$search_key) {
		$results = SearchResults::where(["user_id"=>$user_id,"type"=>0])->first();
		if(!empty($results)) {
			$result_data = $results->result.",".$search_key;
			SearchResults::where('id',$results->id)->update(array(
			  'result' => $result_data,
			));
		}
		else{
			SearchResults::create([
			  'user_id' => $user_id,
			  'result' => $search_key,
			  "type"=>0,
			]);
		}
	}
	public function bindDocOpdTiming($schedule) {
		$opd_time = array();
		if(!empty($schedule)){
			$opd_timings[] = json_decode($schedule,true);
			if(count($opd_timings)) {
				foreach($opd_timings as $opd) {
					foreach($opd as $optime) {
						if(!empty($optime['days'])) {
							for($i = 0; $i<count($optime['days']);$i++) {
								$mytimings = array();
								foreach($optime['timings'] as $time) {
									$time['start_time'] = date('g:i A',strtotime($time['start_time']));
									$time['end_time'] = date('g:i A',strtotime($time['end_time']));
									$mytimings[] = $time;
								}
								$opd_time[getDaysByNumber($optime['days'][$i])] = (array) $mytimings;
								$nameOfDay = date('N');
								if($nameOfDay == "7"){
									$nameOfDay = "0";
								}
								if($nameOfDay == $optime['days'][$i]){
									$opd_time["today"] = (array) $mytimings;
								}
							}
						}
					}
				}
			}
		}
		return $opd_time;
	}
	public function bindDocData($docs_array){
		foreach ($docs_array as $key => $value) {
			$doc_paths = json_decode($value->urls);
			$opd_timings = array();
			if(!empty($value->profile_pic)) {
				$image_url = getPath("public/doctor/ProfilePics/".$value->profile_pic);
				$value['profile_pic'] = $image_url;
			}
			else{
				$value['profile_pic'] = null;
			}
			if(!empty($value->clinic_image)){
				$image_url = getPath("public/doctor/".$value->clinic_image);
				$value['clinic_image'] = $image_url;
			}
			if(!empty($value->speciality)){
				$value['speciality'] =array("id"=>$value->speciality, "name"=>getSpecialityName($value->speciality),"spaciality_hindi"=>getSpecialityHindiName($value->speciality));
			}
			else{
				$value['speciality'] = (object) array();
			}

			if(!empty($value->country_id)){
			  $value['country_id'] = array("id"=>$value->country_id,"name"=>getCountrieName($value->country_id));
			}

			if(!empty($value->state_id)){
			  $value['state_id'] = array("id"=>$value->state_id,"name"=>getStateName($value->state_id));
			}
			if(!empty($value->city_id)){
			  $value['city_id'] = array("id"=>$value->city_id,"name"=>getCityName($value->city_id));
			}
			if(!empty($value->locality_id)){
			  $value['locality_id'] = array("id"=>$value->locality_id,"name"=>getLocalityName($value->locality_id));
			}
			$value['available_now'] = 0;
			$increment = 900;
			if(!empty($value->slot_duration)){
				$increment = $value->slot_duration*60;
			}
			$opd_time = array();
			if(!empty($value->opd_timings)){
				$opd_timings[] = json_decode($value->opd_timings,true);
				if(count($opd_timings)) {
					foreach($opd_timings as $opd){
						foreach($opd as $optime){
							if(!empty($optime['days'])){
								for($i = 0; $i<count($optime['days']);$i++){
									$mytimings = array();
									$nameOfDay = date('N');
									if($nameOfDay == "7"){
										$nameOfDay = "0";
									}
									foreach($optime['timings'] as $time){
										$time['start_time'] = date('g:i A',strtotime($time['start_time']));
										$time['end_time'] = date('g:i A',strtotime($time['end_time']));
										$mytimings[] = $time;
									}
									$opd_time[getDaysByNumber($optime['days'][$i])] = (array) $mytimings;
									if($nameOfDay == $optime['days'][$i]) { 
										$time_slots = [];
										$opd_time["today"] = (array) $mytimings;
										foreach($optime['timings'] as $k=>$v){
											$startTime = strtotime(date("g:i A"));
											while($startTime <= strtotime($v['end_time'])) {
											  $time_slots[] = $startTime;
											  $startTime += $increment;
											}
										}
										if(count($time_slots) > 0){
											$value['available_now'] = 1;
										}
									}
								}
							}
						}
					}
				}
				$value['opd_timings'] = $opd_time;
			}

			
			$time_slot = array();
			if(isset($opd_time['today'])){
				foreach($opd_time['today'] as $time_already){
					$time_slot[] = $this->selectTimesBySlot($time_already['start_time'],$time_already['end_time'],$increment);
				}
			}
			$value['today_timing_slots'] = array();
			$value['doc_rating'] = 0;
			if(isset($value->DoctorRatingReviews)) {
				if(count($value->DoctorRatingReviews) > 0) {
					$rating_val = 0;
					$rating_count = 0;
					foreach($value->DoctorRatingReviews as $rating) {
						$rating_val += $rating->rating;
						$rating_count++;
					}
					if($rating_val > 0){
						$rating_val = round($rating_val/$rating_count,1);
					}
					$value['doc_rating'] = $rating_val;
				}
			}
		} 
		return $docs_array;
	}

	public function bindDocDataByUnique($value) {
		// foreach ($docs_array as $key => $value) {
			$doc_paths = json_decode($value->urls);
			$opd_timings = array();

			if(!empty($value->profile_pic)){
			    $image_url = getPath("public/doctor/ProfilePics/".$value->profile_pic);
				$value->profile_pic = $image_url;
			}
			if(!empty($value->clinic_image)){
				$image_url = getPath("public/doctor/".$value->clinic_image);
				$value['clinic_image'] = $image_url;
			}
			if(!empty($value->speciality)){
				$value->speciality =array("id"=>$value->speciality, "name"=>getSpecialityName($value->speciality),"spaciality_hindi"=>getSpecialityHindiName($value->speciality));
			}
			else{
				$value->speciality = (object) array();
			}

			if(!empty($value->country_id)){
			  $value->country_id = array("id"=>$value->country_id,"name"=>getCountrieName($value->country_id));
			}

			if(!empty($value->state_id)){
			  $value->state_id = array("id"=>$value->state_id,"name"=>getStateName($value->state_id));
			}
			if(!empty($value->city_id)){
			  $value->city_id = array("id"=>$value->city_id,"name"=>getCityName($value->city_id));
			}
			if(!empty($value->locality_id)){
			  $value['locality_id'] = array("id"=>$value->locality_id,"name"=>getLocalityName($value->locality_id));
			}
			$opd_time = array();
			$increment = 900;
			if(!empty($value->slot_duration)){
				$increment = $value->slot_duration*60;
			}
			$value['available_now'] = 0;
			if(!empty($value->opd_timings)){
				$opd_timings[] = json_decode($value->opd_timings,true);
				if(count($opd_timings)) {
					foreach($opd_timings as $opd){
						foreach($opd as $optime){
							if(!empty($optime['days'])){
								for($i = 0; $i<count($optime['days']);$i++){
									$mytimings = array();
									$nameOfDay = date('N');
									if($nameOfDay == "7"){
										$nameOfDay = "0";
									}
									foreach($optime['timings'] as $time){
										$time['start_time'] = date('g:i A',strtotime($time['start_time']));
										$time['end_time'] = date('g:i A',strtotime($time['end_time']));
										$mytimings[] = $time;
									}
									$opd_time[getDaysByNumber($optime['days'][$i])] = (array) $mytimings;
									if($nameOfDay == $optime['days'][$i]) { 
										$time_slots = [];
										$opd_time["today"] = (array) $mytimings;
										foreach($optime['timings'] as $k=>$v){
											$startTime = strtotime(date("g:i A"));
											while($startTime <= strtotime($v['end_time'])) {
											  $time_slots[] = $startTime;
											  $startTime += $increment;
											}
										}
										if(count($time_slots) > 0){
											$value['available_now'] = 1;
										}
									}
								}
							}
						}
					}
				}
			}
			$value['opd_timings'] = $opd_time;
			$time_slot = array();
			if(isset($opd_time['today'])){
				foreach($opd_time['today'] as $time_already){
					$time_slot[] = $this->selectTimesBySlot($time_already['start_time'],$time_already['end_time'],$increment);
				}
			}
			$value['today_timing_slots'] = array();
			$value['doc_rating'] = 0;
			if(isset($value->DoctorRatingReviews)) {
				if(count($value->DoctorRatingReviews) > 0) {
					$rating_val = 0;
					$rating_count = 0;
					foreach($value->DoctorRatingReviews as $rating) {
						$rating_val += $rating->rating;
						$rating_count++;
						$rating['user_name'] = @$rating->user->first_name." ".@$rating->user->last_name;
						$suggestion_array = array(); 
						$rating["suggestion_array"] = array(); 
						if(!empty($rating->suggestions)) {
							$suggestions = explode(",",$rating->suggestions); 
							if(count($suggestions) > 0){
								foreach($suggestions as $sugs){
									$suggestion_array[] = array("id"=>$sugs,'name'=>getComplimentName($sugs));	
								}
							}
							$rating["suggestion_array"] = $suggestion_array;
						}
					}
					
					if($rating_val > 0){
						$rating_val = round($rating_val/$rating_count,1);
					}
					$value['doc_rating'] = $rating_val;
				}
			}
		// }
		return $value;
	}

	public function getDocSpeciality(Request $request){
		$getSpeciality =  Speciality::orderBy("order_no","ASC")->whereNotIn('id',[203])->limit(28)->get();
		foreach($getSpeciality as $value){
			if(!empty($value->speciality_icon)){
				$value['speciality_icon'] = url("/")."/public/speciality-icon/".$value->speciality_icon;
			}
		}
		return $this->sendResponse($getSpeciality, '',true);
	}

	public function getDocBySpeciality(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['speciality_id'] = $data->get('speciality_id');
		$user_array['group_id'] = $data->get('group_id');
		$user_array['city'] = $data->get('city');
		$user_array['locality'] = $data->get('locality');
		$user_array['conType'] = $data->get('conType');
		$doctors = [];
		$suggested_data = [];
		$success = false;
		$doctor_spaciality = Doctors::where(['status'=>1,'delete_status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('speciality');
		if($user_array['speciality_id'] == '0') {	
			$user_array['speciality_id'] = '1';	
		}
		if(!empty($user_array['city']) && $user_array['conType'] == "inclinic") {
			$doctor_spaciality->where('city_id',$user_array['city']);
		}
		/*if(!empty($user_array['locality']) && !empty($user_array['speciality_id'])) {
			$doctor_spaciality->where('locality_id',$user_array['locality']);
		}*/
		if(is_array($user_array['speciality_id'])){
			$doctor_spaciality->whereIn('speciality',$user_array['speciality_id']);
		}
		else {
			if(isset($user_array['group_id']) && !empty($user_array['group_id'])) {
				$s_ids = Speciality::where(["group_id"=>$user_array['group_id']])->pluck('id');
				$doctor_spaciality->whereIn('speciality',$s_ids);
			}
			else if(!empty($user_array['speciality_id'])){
				$speciaity = Speciality::select("group_id")->where('id',$user_array['speciality_id'])->first();
				$s_ids = Speciality::where(["group_id"=>$speciaity->group_id])->pluck('id');
				$doctor_spaciality->whereIn('speciality',$s_ids);
			}
		}
		$doctors = $doctor_spaciality->get();
		if(count($doctors) > 0 ) {
			
			
			$doctors = dataSequenceChange($doctors,$user_array['speciality_id']);
			$doctors = $this->bindDocData($doctors);
			$available_now = array_column($doctors, 'available_now');
			array_multisort($available_now, SORT_DESC, $doctors);
			 
			$byrating = array_column($doctors, 'doc_rating');
			array_multisort($byrating, SORT_DESC, $doctors);
			if(!empty($user_array['city'])) {
				$currCityDocs = array();
                $othCityDocs = array();
                 $kkDoc = array();
                $stateID = City::where('id',$user_array['city'])->first();
			    foreach ($doctors as $doc) { 
			     	if($doc['city_id']['id'] == $user_array['city'] && $doc['state_id']['id'] == $stateID->state_id){
                       //array_push($currCityDocs,$doc);
			     		array_push($kkDoc,$doc);
			     	}else if($doc['city_id']['id'] != $user_array['city'] && $doc['state_id']['id'] == $stateID->state_id){
                       array_push($currCityDocs,$doc);
			     	}else{
                       array_push($othCityDocs,$doc);
			     	}
			    }
			    $finArr = array_merge($kkDoc,$currCityDocs);
			    $doctors = array_merge($finArr,$othCityDocs);
		    }
			$perPage = 10;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }
			$offset = ($currentPage * $perPage) - $perPage;
			$itemsForCurrentPage = array_slice($doctors, $offset, $perPage, false);
			$doctors =  new Paginator($itemsForCurrentPage, count($doctors), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));				
			$success = true;
		}
		else if(count($doctors) <= 0 ) {
			if(!empty($user_array['city'])) {
				$suggested_data = $this->getDataBySpecialityInCity($user_array['city'],$user_array['speciality_id'],$user_array['group_id']);
				$suggested_data = dataSequenceChange($suggested_data,$user_array['speciality_id']);
				// $docs_arr = [];
				// foreach($suggested_data as $info){
					// if($info->speciality == $user_array['speciality_id']){
						// $docs_arr[] = $info;
					// }
				// }
				// foreach($suggested_data as $info){
					// if($info->speciality != $user_array['speciality_id']){
						// $docs_arr[] = $info;
					// }
				// }
				// $suggested_data = $docs_arr;
				$perPage = 10;
				$input = Input::all();
				if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($suggested_data, $offset, $perPage, false);
				$suggested_data =  new Paginator($itemsForCurrentPage, count($suggested_data), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				$suggested_data = $this->bindDocData($suggested_data);
			}
		}
		$response = [
            'success' => $success,
            'data'    => $doctors,
            'suggestion_data' => $suggested_data,
            'message' => '',
        ];
        return response()->json($response, 200);
	}
	
	
	public function searchDoctorsByFilters(Request $request) {
		$data=Input::json()->all();
		
  		$user_array=array();
		$user_array['sort_by_consult_fee'] = $data['sort_by_consult_fee'];
		$user_array['consult_fee'] = $data['consult_fee'];
		$user_array['consult_fee_max'] = $data['consult_fee_max'];
		$user_array['speciality'] = $data['speciality'];
		$user_array['group_id'] = $data['group_id'];
		$user_array['search_key'] = $data['search_key'];
		$user_array['city'] = $data['city'];
		$user_array['locality'] = $data['locality'];
		$user_array['search_type'] = $data['search_type'];
		$user_array['gender'] = $data['gender'];
		$user_array['rating'] = $data['rating'];
		$user_array['conType'] = $data['conType'];
		$user_array['by_home'] = $data['by_home'];
		$doctors = [];
		$other_doctors = [];
		$suggested_data = [];
		$success = false;

		if(!empty($user_array['search_type'])) {
			$firstChar = trim(strtolower(substr($user_array['search_key'], 0, 3)));
			if($firstChar == "dr." || $firstChar == "dr") {
				$user_array['search_key'] = trim(str_replace($firstChar,'',strtolower($user_array['search_key'])));
			}
			if($user_array['speciality'] == "0"){
				$user_array['speciality'] = "1";
			}
			$doc_query = Doctors::with("DoctorRatingReviews")->Where(['status'=>1,'delete_status'=>1,'varify_status'=>1])->where("oncall_status","!=",0);
			$qry_locality = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(['status'=>1,"delete_status"=>1,'varify_status'=>1])->where("oncall_status","!=",0);
			
			if($user_array['search_type'] == "doctor") {
				$doc_query->where(DB::raw('concat(first_name," ",last_name," ")') , 'like', '%'.$user_array['search_key'].'%');
				$qry_locality->where(DB::raw('concat(first_name," ",last_name," ")') , 'like', '%'.$user_array['search_key'].'%');
			}
			else if($user_array['search_type'] == "clinic") {
				$doc_query->where('clinic_name', 'like', '%'.$user_array['search_key'].'%');
				$qry_locality->where('clinic_name', 'like', '%'.$user_array['search_key'].'%');
			}
			else if($user_array['search_type'] == "bySpeciality"){
				$search_key = $user_array['search_key'];
				if(!empty($search_key)){
					$doc_query->whereHas("docSpeciality",function($q) use($search_key) {$q->Where('specialities', 'like', '%'.$search_key.'%');})->whereNotNull('speciality');
					$qry_locality->whereHas("docSpeciality",function($q) use($search_key) {$q->Where('specialities', 'like', '%'.$search_key.'%');})->whereNotNull('speciality');
				}
			}
			else if($user_array['search_type'] == "symptom") {
				$search_key = $user_array['search_key'];
				$doc_query->whereHas("SymptomsSpeciality.Symptom",function($q) use($search_key) {$q->Where(['status'=>1])->Where('symptom', 'like', '%'.$search_key.'%');});
				$qry_locality->whereHas("SymptomsSpeciality.Symptom",function($q) use($search_key) {$q->Where(['status'=>1])->Where('symptom', 'like', '%'.$search_key.'%');});
			}

			if(!empty($user_array['speciality'])) {
				if(isset($user_array['group_id']) && !empty($user_array['group_id'])) {
					$s_ids = Speciality::where(["group_id"=>$user_array['group_id']])->pluck('id');
					$doc_query->whereIn('speciality',$s_ids)->whereNotNull('speciality');
					$qry_locality->whereIn('speciality',$s_ids)->whereNotNull('speciality');
				}
				else{
					$doc_query->where('speciality',$user_array['speciality'])->whereNotNull('speciality');
					$qry_locality->where('speciality',$user_array['speciality'])->whereNotNull('speciality');
				}
			}
			
			if(!empty($user_array['city']) && $user_array['conType'] == "inclinic") {
				// $doc_query->where('city_id',$user_array['city']);
				// $qry_locality->where('city_id',$user_array['city']);
			}
			if(!empty($user_array['locality'])) {
				// $doc_query->where('locality_id',$user_array['locality']);
			}
			if(!empty($user_array['gender'])){
				$doc_query->where(["gender"=>$user_array['gender']]);
				$qry_locality->where(["gender"=>$user_array['gender']]);
			}
			
			if($user_array['conType'] == "inclinic"){
				$doc_query->whereRaw("find_in_set('2',doctors.oncall_status)");
				$qry_locality->whereRaw("find_in_set('2',doctors.oncall_status)");
			}
			if($user_array['conType'] == "tele"){
				$doc_query->whereRaw("find_in_set('1',doctors.oncall_status)");
				$qry_locality->whereRaw("find_in_set('1',doctors.oncall_status)");
			}
		
			if(!empty($user_array['consult_fee'])) {
				if($user_array['conType'] == "inclinic") {
					$doc_query->where("consultation_fees",">=",$user_array['consult_fee'])->where("consultation_fees","<=",$user_array['consult_fee_max'])->whereNotNull('consultation_fees');
					$qry_locality->where("consultation_fees","<=",$user_array['consult_fee'])->where("consultation_fees","<=",$user_array['consult_fee_max'])->whereNotNull('consultation_fees');
				}
				if($user_array['conType'] == "tele"  || $user_array['conType'] == "all"){
					$doc_query->where("oncall_fee",">=",$user_array['consult_fee'])->where("oncall_fee","<=",$user_array['consult_fee_max'])->whereNotNull('oncall_fee');
					$qry_locality->where("oncall_fee","<=",$user_array['consult_fee'])->where("oncall_fee","<=",$user_array['consult_fee_max'])->whereNotNull('oncall_fee');
				}
			}
			if($user_array['sort_by_consult_fee']) {
				if($user_array['conType'] == "tele"  || $user_array['conType'] == "all"){
					$doc_query->whereNotNull('oncall_fee')->where("oncall_fee","!=",0);
					$qry_locality->whereNotNull('oncall_fee');
				}
				if($user_array['conType'] == "inclinic") {
					$doc_query->whereNotNull('consultation_fees')->where("consultation_fees","!=",0);
					$qry_locality->whereNotNull('consultation_fees');
				}
			}
			$doctors = $doc_query->get();
			if(count($doctors) > 0 ) {
				$doctors = dataSequenceChange($doctors,$user_array['speciality']);
				if($user_array['search_type'] == "bySpeciality" && !empty($user_array['speciality']) && empty($user_array['search_key'])) {
					// $docs_arr = [];
					// foreach($doctors as $info){
						// if($info->speciality == $user_array['speciality']){
							// $docs_arr[] = $info;
						// }
					// }
					// foreach($doctors as $info){
						// if($info->speciality != $user_array['speciality']){
							// $docs_arr[] = $info;
						// }
					// }
					// $doctors = $docs_arr;
				}
				if($user_array['rating']) {
					$sort = array();
					foreach($doctors as $k=>$v) {
						$sort['doc_rating'][$k] = $v['doc_rating'];
					}
					array_multisort($sort['doc_rating'], SORT_DESC, $doctors);
				}
				
				if($user_array['sort_by_consult_fee']) {
					if($user_array['conType'] == "inclinic") {	
						$sort = array();
						foreach($doctors as $k=>$v) {
							$sort['consultation_fees'][$k] = $v['consultation_fees'];
						}
						array_multisort($sort['consultation_fees'], SORT_ASC, $doctors);
					}
					if($user_array['conType'] == "tele"  || $user_array['conType'] == "all"){
						$sort = array();
						foreach($doctors as $k=>$v) {
							$sort['oncall_fee'][$k] = $v['oncall_fee'];
						}
						array_multisort($sort['oncall_fee'], SORT_ASC, $doctors);
					}
				}

				if(!empty($user_array['city'])) {
				$currCityDocs = array();
                $othCityDocs = array();
                $kkDoc = array();
                $stateID = City::where('id',$user_array['city'])->first();
			    foreach ($doctors as $doc) {  //pr($doc->city_id);
			    	
			     	if($doc->city_id == $user_array['city'] && $doc->state_id == $stateID->state_id){
                       array_push($kkDoc,$doc);
			     	}else if($doc->city_id != $user_array['city'] && $doc->state_id == $stateID->state_id){
                       array_push($currCityDocs,$doc);
			     	}else{
                       array_push($othCityDocs,$doc);
			     	}
			    }
			    // pr($kkDoc);
			    //pr($currCityDocs);
			    $finArr = array_merge($kkDoc,$currCityDocs);
			    $doctors = array_merge($finArr,$othCityDocs);
		        }
				
				$success = true;
				$perPage = 10;
				$input = Input::all();
				if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($doctors, $offset, $perPage, false);
				$doctors =  new Paginator($itemsForCurrentPage, count($doctors), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				$doctors = $this->bindDocData($doctors);
			}
			else if(count($doctors) <= 0 ) {
				if(!empty($user_array['city'])) {
					$suggested_data = $qry_locality->get();
					$suggested_data = dataSequenceChange($suggested_data,$user_array['speciality']);
					
					if($user_array['search_type'] == "bySpeciality" && !empty($user_array['speciality']) && empty($user_array['search_key'])) {
						// $docs_arr = [];
						// foreach($suggested_data as $info){
							// if($info->speciality == $user_array['speciality']){
								// $docs_arr[] = $info;
							// }
						// }
						// foreach($suggested_data as $info){
							// if($info->speciality != $user_array['speciality']){
								// $docs_arr[] = $info;
							// }
						// }
						// $suggested_data = $docs_arr;
					}
					if($user_array['rating']) {
						$sort = array();
						foreach($suggested_data as $k=>$v) {
							$sort['doc_rating'][$k] = $v['doc_rating'];
						}
						array_multisort($sort['doc_rating'], SORT_DESC, $suggested_data);
					}
					if($user_array['sort_by_consult_fee']) {
						if($user_array['conType'] == "inclinic") {	
							$sort = array();
							foreach($suggested_data as $k=>$v) {
								$sort['consultation_fees'][$k] = $v['consultation_fees'];
							}
							array_multisort($sort['consultation_fees'], SORT_ASC, $suggested_data);
						}
						if($user_array['conType'] == "tele"  || $user_array['conType'] == "all"){
							$sort = array();
							foreach($suggested_data as $k=>$v) {
								$sort['oncall_fee'][$k] = $v['oncall_fee'];
							}
							array_multisort($sort['oncall_fee'], SORT_ASC, $suggested_data);
						}
					}
					if(!empty($user_array['city'])) {
						$currCityDocs = array();
						$othCityDocs = array();
						$stateID = City::where('id',$user_array['city'])->first();
						foreach($suggested_data as $doc) {
							if($doc->city_id == $user_array['city'] && $doc->state_id == $stateID->state_id){
							   array_push($currCityDocs,$doc);
							}else if($doc->city_id != $user_array['city'] && $doc->state_id == $stateID->state_id){
							   array_push($currCityDocs,$doc);
							}else{
							   array_push($othCityDocs,$doc);
							}
						}
						$suggested_data = array_merge($currCityDocs,$othCityDocs);
					}
					$perPage = 10;
					$input = Input::all();
					if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }

					$offset = ($currentPage * $perPage) - $perPage;
					$itemsForCurrentPage = array_slice($suggested_data, $offset, $perPage, false);
					$suggested_data =  new Paginator($itemsForCurrentPage, count($suggested_data), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
					$suggested_data = $this->bindDocData($suggested_data);
				}
			}
		}
		$response = [
            'success' => $success,
            'data'    => $doctors,
            'suggestion_data' => $suggested_data,
            'message' => '',
        ];
        return response()->json($response, 200);
	}
	
	public function getDataBySpecialityInCity($s_city_id,$speciality_id,$grp_id) {
		$qry_locality = Doctors::with(["docSpeciality","DoctorRatingReviews"])->select(["id","profile_pic","speciality","city_id","address_1","first_name","last_name","qualification","experience","opd_timings","oncall_status","oncall_fee","consultation_fees"])->where(["delete_status"=>1,'varify_status'=>1])->whereNotNull('speciality');
		if(!empty($s_city_id)){ 
			$qry_locality->where('city_id',$s_city_id);
		}
		if(!empty($speciality_id) && is_array($speciality_id) && empty($grp_id)) { 
			$qry_locality->WhereIn('speciality',$speciality_id); 
		}
		else {
			if(!empty($grp_id)) {
				$s_ids = Speciality::where(["group_id"=>$grp_id])->pluck('id');
				$qry_locality->whereIn('speciality',$s_ids);
			}
			else if(!empty($speciality_id)){ 
				$qry_locality->Where('speciality',$speciality_id);
			}
		}
		return $qry_locality->get();
	}
	
	public function dataSequenceChangeInApi($infoData) {
		$prime_arr = [];
		$verified_doc_arr = [];
		$non_prime_arr = [];
		foreach($infoData as $info) {
			if(!empty($info->practice_id)) {
				if(checSubcriptionStatus($info->practice_id) == 1) {
					$info['is_prime']  = 1;
					$rating_val = 0;
					if(isset($info->DoctorRatingReviews)) {
						if(count($info->DoctorRatingReviews) > 0) {
							$rating_count = 0;
							foreach($info->DoctorRatingReviews as $rating) {
								$rating_val += $rating->rating;
								$rating_count++;
							}
							if($rating_val > 0){
								$rating_val = round($rating_val/$rating_count,1);
							}
						}
					}
					$info['doc_rating'] = $rating_val;
					$prime_arr[] = $info;
				}
				else{
					$info['is_prime']  = 0;
					$rating_val = 0;
					if(isset($info->DoctorRatingReviews)) {
						if(count($info->DoctorRatingReviews) > 0) {
							$rating_count = 0;
							foreach($info->DoctorRatingReviews as $rating) {
								$rating_val += $rating->rating;
								$rating_count++;
							}
							if($rating_val > 0){
								$rating_val = round($rating_val/$rating_count,1);
							}
						}
					}
					$info['doc_rating'] = $rating_val;
					$verified_doc_arr[] = $info;
				}
			}
			else{
				$info['is_prime']  = 0;
				$rating_val = 0;
				if(isset($info->DoctorRatingReviews)) {
					if(count($info->DoctorRatingReviews) > 0) {
						$rating_count = 0;
						foreach($info->DoctorRatingReviews as $rating) {
							$rating_val += $rating->rating;
							$rating_count++;
						}
						if($rating_val > 0){
							$rating_val = round($rating_val/$rating_count,1);
						}
					}
				}
				$info['doc_rating'] = $rating_val;
				$non_prime_arr[] = $info;
			}
		}

		$price = array();
		foreach ($prime_arr as $key => $row) {
			$price[$key] = $row['doc_rating'];
		}
		array_multisort($price, SORT_DESC, $prime_arr);

		$price = array();
		foreach ($verified_doc_arr as $key => $row) {
			$price[$key] = $row['doc_rating'];
		}
		array_multisort($price, SORT_DESC, $verified_doc_arr);
		
		$price = array();
		foreach ($non_prime_arr as $key => $row) {
			$price[$key] = $row['doc_rating'];
		}
		array_multisort($price, SORT_DESC, $non_prime_arr);

		if(count($verified_doc_arr) > 0 ) {
			$prime_arr = array_merge($prime_arr,$verified_doc_arr);
		}
		if(count($non_prime_arr) > 0 ) {
			$infoData = array_merge($prime_arr,$non_prime_arr);
		}
		else{
			$infoData = $prime_arr;
		}
		return $infoData;
	}

	public function getDocById(Request $request){
		$data=Input::json();
  		$user_array=array();
		$user_array['member_id'] = $data->get('member_id');
		$value = [];
		$success = false;
		$opd_timings = array();
		if(!empty($user_array['member_id'])) {
			$value = Doctors::select(["id","profile_pic","speciality","city_id","address_1","first_name","last_name","qualification","experience","opd_timings","oncall_status","oncall_fee","consultation_fees"])->where(['delete_status'=>1,'status'=>1])->where(["member_id"=>$user_array['member_id']])->first();
		}
		if(!empty($value)>0) {
			$doc_paths = json_decode($value->urls);
			if(!empty($value->profile_pic)){
				$image_url = getPath("public/doctor/ProfilePics/".$value->profile_pic);
				$value['profile_pic'] = $image_url;
			}

			if(!empty($value->clinic_image)){
				$image_url = getPath("public/doctor/".$value->clinic_image);
				$value['clinic_image'] = $image_url;
			}
			if(!empty($value->speciality)){
				$value->speciality =array("id"=>$value->speciality, "name"=>getSpecialityName($value->speciality),"spaciality_hindi"=>getSpecialityHindiName($value->speciality));
			}
			else{
				$value->speciality = (object) array();
			}

			if(!empty($value->country_id)){
			  $value->country_id = array("id"=>$value->country_id,"name"=>getCountrieName($value->country_id));
			}

			if(!empty($value->state_id)){
			  $value->state_id = array("id"=>$value->state_id,"name"=>getStateName($value->state_id));
			}
			if(!empty($value->city_id)){
			  $value->city_id = array("id"=>$value->city_id,"name"=>getCityName($value->city_id));
			}
			if(!empty($value->locality_id)){
			  $value['locality_id'] = array("id"=>$value->locality_id,"name"=>getLocalityName($value->locality_id));
			}
			$opd_time = array();
			if(!empty($value->opd_timings)){
				$opd_timings[] = json_decode($value->opd_timings,true);
				if(count($opd_timings)) {
					foreach($opd_timings as $opd){
						foreach($opd as $optime){
							if(!empty($optime['days'])){
								for($i = 0; $i<count($optime['days']);$i++){
									$mytimings = array();
									foreach($optime['timings'] as $time){
										$time['start_time'] = date('g:i A',strtotime($time['start_time']));
										$time['end_time'] = date('g:i A',strtotime($time['end_time']));
										$mytimings[] = $time;
									}
									$opd_time[getDaysByNumber($optime['days'][$i])] = (array) $mytimings;
									$nameOfDay = date('N');
									if($nameOfDay == "7"){
										$nameOfDay = "0";
									}
									if($nameOfDay == $optime['days'][$i]){
										$opd_time["today"] = (array) $mytimings;
									}
								}
							}
						}
					}
				}
				$value['opd_timings'] = $opd_time;
			}

			$increment = 900;
			if(!empty($value->slot_duration)){
				$increment = $value->slot_duration*60;
			}
			$time_slot = array();
			if(isset($opd_time['today'])){
				foreach($opd_time['today'] as $time_already){
					$time_slot[] = $this->selectTimesBySlot($time_already['start_time'],$time_already['end_time'],$increment);
				}
			}

			$from = "";
			$to = "";
			$slot_array = array();
			$value['today_timing_slots'] = $slot_array;
			$value['doc_rating'] = 0;
			if(isset($value->DoctorRatingReviews)) {
				if(count($value->DoctorRatingReviews) > 0) {
					$rating_val = 0;
					$rating_count = 0;
					foreach($value->DoctorRatingReviews as $rating) {
						$rating_val += $rating->rating;
						$rating_count++;
					}
					if($rating_val > 0){
						$rating_val = round($rating_val/$rating_count,1);
					}
					$value['doc_rating'] = $rating_val;
				}
			}
			$success = true;
		}
		return $this->sendResponse($value, '',$success);
	}

	function selectTimesBySlot($start_time,$end_time,$slot) {
		$output = array();
		$start  = strtotime(date('H:i',strtotime($start_time)));  //pr($start);
		$end    = strtotime(date('H:i',strtotime($end_time))); //pr($end);
		for( $i = $start; $i < $end; $i += $slot) {
			$output[] = date("g:i A",$i);
		}
		return $output;
	}

	public function getCountry(Request $request){
		$countryList =  Country::where('id',101)->orderBy("id")->get();
		return $this->sendResponse($countryList, '',true);
	}


	public function getState(Request $request) {
		$data=Input::json();
        $user_array=array();
		$user_array['country_id'] =$data->get('country_id');
		$validator = Validator::make($user_array, [
            'country_id'                => 'required|max:255',
            ],[
            'country_id.required'   => 'Country Id field cannot be empty',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else{
			$stateList =  State::where('country_id',$user_array['country_id'])->get();
			if(!empty($stateList)){
				return $this->sendResponse($stateList, '',true);
			}
			else{
				return $this->sendError('Country does not exist');
			}
		}
	}

	public function getCity(Request $request){
		$data=Input::json();
        $user_array=array();
		$user_array['state_id'] =$data->get('state_id');
		$validator = Validator::make($user_array, [
            'state_id'                => 'required|max:255',
            ],[
            'state_id.required'   => 'State Id field cannot be empty',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else{
			$cityList = City::where('state_id',$user_array['state_id'])->limit(10)->get();
			// $cityList = City::where('state_id',$user_array['state_id'])->get();
			if(!empty($cityList)){
				return $this->sendResponse($cityList, '',true);
			}
			else{
				return $this->sendError('Country does not exist');
			}
		}
	}
	
	public function searchCity(Request $request){
		$data=Input::json();
        $user_array=array();
		$user_array['state_id'] =$data->get('state_id');
		$user_array['search_key'] =$data->get('search_key');
		$validator = Validator::make($user_array, [
            'state_id'                => 'required',
            ],[
            'state_id.required'   => 'State Id field cannot be empty',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else{
			$cityList = City::where('name', 'like', '%'.$user_array['search_key'].'%')->where('state_id',$user_array['state_id'])->limit(20)->get();
			if(!empty($cityList)){
				return $this->sendResponse($cityList, '',true);
			}
			else{
				return $this->sendError('City does not exist');
			}
		}
	}

	public function getMyProfile(Request $request) { 
	  if($request->isMethod('post')) {
		$data = Input::json();
		$user_array=array();
		$user_array['id'] = $data->get('id');
		$validator = Validator::make($user_array,[
		  'id'   => 'required|max:50',
		]);
		if($validator->fails()) {
		  return $this->sendError($validator->errors());
		}
		else{
			$userInfo = [];
			if(!empty($user_array['id'])) {
				$userInfo = User::where('id',$user_array['id'])->first();
				$userInfo['country_id'] = array("id"=>($userInfo->country_id!=null)?$userInfo->country_id:"","name"=>getCountrieName($userInfo->country_id));
				$userInfo['state_id'] = array("id"=>($userInfo->state_id!=null)?$userInfo->state_id:"","name"=>getStateName($userInfo->state_id));
				$userInfo['city_id'] = array("id"=>($userInfo->city_id!=null)?$userInfo->city_id:"","name"=>getCityName($userInfo->city_id));
				if(!empty($userInfo->OrganizationMaster) && !empty($userInfo->OrganizationMaster->logo)){
					$logo = url("/")."/public/organization_logo/".$userInfo->OrganizationMaster->logo;
				}
				else{
					$logo = null;
				}
				$is_subscribed = PlanPeriods::select('subscription_id')->where('user_id', $user_array['id'])->where('remaining_appointment', '!=', '0')->where('status', '1')->first();
				$userInfo['is_subscribed'] = 0;
				$userInfo['max_appointment_fee'] = 0;
				if (!empty($is_subscribed)) {
					$UserSubscribedPlans = UserSubscribedPlans::select('meta_data')->where('subscription_id', $is_subscribed->subscription_id)->first();
					$plan_meta = json_decode($UserSubscribedPlans->meta_data);
					$max_fee = @$plan_meta->max_appointment_fee;
					$userInfo['max_appointment_fee'] = $max_fee;
				}
				
				$userInfo->organization = array("id"=>($userInfo->organization!=null)?$userInfo->organization:"","title"=>getOrganizationIdByName(@$userInfo->organization),"logo"=>$logo);
				 if(!empty($userInfo->image)) {
					$image_url = getPath("public/patients_pics/".$userInfo->image);
					$userInfo['image'] = $image_url;
				}
				$userInfo["dob_type"] = 0;
				if(!empty($userInfo->dob)) {
					$userInfo["dob_type"] = get_patient_age_api($userInfo->dob)[1];
					$userInfo["dob"] = get_patient_age_api($userInfo->dob)[0];
				}
				$userInfo['token'] = @$request->bearerToken();
				$userInfo['expires_at'] = "";
				// $userInfo->first_name = trim(@$userInfo->first_name." ".@$userInfo->last_name);
				$user_details = UserDetails::select("user_id","referral_code","referred_id","wallet_amount")->where('user_id',$user_array['id'])->first();
				$user_details['referred_code'] = getRefCodeByUserId(@$user_details->referred_id);
				$userInfo['user_details'] = $user_details;
				if(!empty($userInfo)){
					return $this->sendResponse($userInfo,'',true);
				}
				else{
					return $this->sendResponse($userInfo,'No record Found..',false);
				}
			 }
			 else{
				return $this->sendError('Practice does not exist');
			 }
		 }
	  }
	}

	public function updateMyProfile(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json();
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$user_array['first_name'] = $data->get('first_name');
			$user_array['last_name'] = $data->get('last_name');
			$user_array['address'] = $data->get('address');
			$user_array['mobile_no'] = $data->get('mobile_no');
			$user_array['email'] = $data->get('email');
			$user_array['gender'] = $data->get('gender');
			$user_array['dob'] = $data->get('dob');
			$user_array['dob_type'] = $data->get('dob_type');
			$user_array['city_id'] = $data->get('city_id');
			$user_array['state_id'] = $data->get('state_id');
			$user_array['country_id'] = $data->get('country_id');
			$user_array['zipcode'] = $data->get('zipcode');
			$user_array['aadhar_no'] = $data->get('aadhar_no');
			$user_array['profession_type'] = $data->get('profession_type');
			$user_array['organization'] = $data->get('organization');
			$user_array['referred_code'] = $data->get('referred_code');
			$validator = Validator::make($user_array, [
				'id'   => 'required|max:50',
				'first_name'   => 'required|max:50',
				// 'last_name'   => 'required|max:50',
				'mobile_no'   => 'required|max:10',
				'dob'   => 'required',
				'gender'   => 'required',
				// 'aadhar_no'   => 'max:12|min:12',
				//'email'   => 'required|max:50|email'
			],[
            'first_name.required'  => 'Name field cannot be empty',
			]
			);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$user =  User::where('id', $user_array['id'])->first();
				$user_array['old_number'] =  $user->mobile_no;
				$user_array['old_email'] =  $user->email;
				$errors = [];
				if($user_array['old_email'] != $user_array['email']) {
					$email_exists = User::where(['email'=>trim($user_array['email'])])->whereNotNull('email')->where('email','!=','')->count();
					if($email_exists > 0){
						$errors["email"] = ['This email already exists !! In case of any query please contact us.'];
					}
				}
				if($user_array['old_number'] != $user_array['mobile_no']) {
					$no_exists = User::where(['mobile_no'=>trim($user_array['mobile_no'])])->where(['parent_id'=>0])->where('pId','!=',null)->count();
					if($no_exists > 0) {
						$errors["mobile_no"] = ['Mobile Number already exist.'];
					}
				}
				if(count($errors)>0){
					return $this->sendError($errors);
				}
				// $first_name = $user_array['first_name'];
				// $last_name = ' ';
				// if(!empty($user_array['first_name'])){
					// $name = explode(" ",$user_array['first_name']);
					// $first_name = $name[0];
					// $last_name = (isset($name[1]) ? $name[1] : ' ');
				// }
				$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$user_array['dob_type']);
				$first_name = trim(strtok($user_array['first_name'], ' '));
				$last_name = trim(strstr($user_array['first_name'], ' '));
				$password = null;
				if(!empty($user_array['email']) && empty($user->email)) {
					$pwd = rand(10000000,99999999);
					$password = bcrypt($pwd);
					$to = $user_array['email'];
					if(!empty($to)) {
						$username = $user_array['first_name'];
						$EmailTemplate = EmailTemplate::where('slug','updateemailpassword')->first();
						if($EmailTemplate) {
							$body = $EmailTemplate->description;
							$mailMessage = str_replace(array('{{username}}', '{{pwd}}'),array($username,$pwd),$body);
							$datas = array('to' =>$to,'from' => 'info@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'subject'=>$EmailTemplate->subject);
							try{
							Mail::send('emails.all', $datas, function( $message ) use ($datas) {
							   $message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
							});
							}
							catch(\Exception $e){
								  // Never reached
							}
						}
					}
				}
				if(!empty($user->patient_number)) {
					User::where('id', $user_array['id'])->update(array(
					  'address' => $user_array['address'],
					  'email' => trim($user_array['email']),
					  'city_id' => $user_array['city_id'],
					  'state_id' => $user_array['state_id'],
					  'country_id' => $user_array['country_id'],
					  'zipcode' => $user_array['zipcode'],
					  'organization' => $user_array['organization'],
					  'profession_type' => $user_array['profession_type'],
					  'password' => $password,
					  'profile_status' => 1,
					));
				}
				else{
					User::where('id', $user_array['id'])->update(array(
					  'first_name' => $first_name,
					  'last_name' => $last_name,
					  'address' => $user_array['address'],
					  'email' => trim($user_array['email']),
					  'gender' => $user_array['gender'],
					  //'aadhar_no' => $user_array['aadhar_no'],
					  'dob' => (isset($user_array['dob']) ? strtotime($user_array['dob']) : null),
					  'city_id' => $user_array['city_id'],
					  'state_id' => $user_array['state_id'],
					  'country_id' => $user_array['country_id'],
					  'zipcode' => $user_array['zipcode'],
					  'organization' => $user_array['organization'],
					  'profession_type' => $user_array['profession_type'],
					  'password' => $password,
					  'profile_status' => 1,
					));
				}
				if(!empty($user->patient_number)) {
					Patients::where('patient_number', $user->patient_number)->update(array(
					  // 'first_name' => $first_name,
					  // 'last_name' => $last_name,
					  'address' => $user_array['address'],
					  'email' => trim($user_array['email']),
					  // 'gender' => $user_array['gender'],
					  //'aadhar_no' => $user_array['aadhar_no'],
					  // 'dob' => (isset($user_array['dob']) ? strtotime($user_array['dob']) : null),
					  'city_id' => $user_array['city_id'],
					  'state_id' => $user_array['state_id'],
					  'country_id' => $user_array['country_id'],
					  'zipcode' => $user_array['zipcode'],
					));
				}
				$user =  User::where('id', $user_array['id'])->first();
				if(!empty($user->image)) {
					$image_url = getPath("public/patients_pics/".$user->image);
					$user['image'] = $image_url;
				}
				$user['is_subscribed'] = 0;
				$user['max_appointment_fee'] = 0;
				$is_subscribed = PlanPeriods::select('subscription_id')->where('id', $user_array['id'])->where('remaining_appointment', '!=', '0')->where('status', '1')->first();
				if (!empty($is_subscribed)) {
					$UserSubscribedPlans = UserSubscribedPlans::select('meta_data')->where('subscription_id', $is_subscribed->subscription_id)->first();
					// $user['is_subscribed'] = 1;
					$plan_meta = json_decode($UserSubscribedPlans->meta_data);
					$max_fee = @$plan_meta->max_appointment_fee;
					$user['max_appointment_fee'] = $max_fee;
				}

				$logo = null;
				if(!empty($user->OrganizationMaster) && !empty($user->OrganizationMaster->logo)){
					$logo = url("/")."/public/organization_logo/".$user->OrganizationMaster->logo;
				}
				$user->organization = array("id"=>($user->organization!=null)?$user->organization:"","title"=>getOrganizationIdByName(@$user->organization),"logo"=>$logo);

				$user["need_otp"] = 0;
				$user['user_otp_id'] = "";
				if($user_array['old_number'] != $user_array['mobile_no']) {
					$user["need_otp"] = 1;
					$otp = rand(100000,999999);
					UsersOTP::where(['mobile_no'=>$user_array['mobile_no']])->orWhere('user_id',$user->id)->delete();
					$currentDate = date('Y-m-d H:i:s');
					$expiry_date = date('Y-m-d H:i:s', strtotime('+1 minutes', strtotime($currentDate)));
					User::where('id',$user->id)->update(['otp'=>$otp]);
					$userOtp = UsersOTP::create([
						 'user_id' =>  $user->id,
						 'mobile_no' =>  $user_array['mobile_no'],
						 'fcm_token' =>  $user->fcm_token,
						 'expiry_date' =>  $expiry_date,
						 'otp' =>  $otp
					]);
					if(!empty($user_array['mobile_no'])) {
					   $message =  urlencode("Your Health Gennie OTP is ".$otp."\nThis otp is valid for 60 seconds Thanks Team Health Gennie");
					   $this->sendSMS($user_array['mobile_no'],$message,'1707165547064979677');
					}
					$user['user_otp_id'] = $userOtp->id;
					$user['otp'] = "";
				}
				$user['token'] = @$request->bearerToken();
				$user['expires_at'] = "";
				$user["dob_type"] = 0;
				if(!empty($user->dob)) {
					$user["dob_type"] = get_patient_age_api($user->dob)[1];
					$user["dob"] = get_patient_age_api($user->dob)[0];
				}
				$refUserId = getUserIdByRefCode($user_array['referred_code']);
				if(!empty($refUserId)) {
					UserDetails::where('user_id',$user->id)->update(['referred_id'=>$refUserId]);
				}
				$user_details = UserDetails::select("user_id","referral_code","referred_id","wallet_amount")->where('user_id',$user->id)->first();
				$user_details['referred_code'] = getRefCodeByUserId(@$user_details->referred_id);
				$user['user_details'] = $user_details;
				
				return $this->sendResponse($user, 'Profile update Successfullyy.',true);
			}
		}
	}
	public function uploadUserFileByApi($fileName,$old_image) {
		@file_get_contents(getEhrUrl()."/patientFileWriteByUrl?fileName=".$fileName."&old_profile_pic=".$old_image);
		if(isset($old_image) && !empty($old_image)) {
			$oldFilename = public_path()."/patients_pics/".$old_image;
			if(file_exists($oldFilename)) {
			   File::delete($oldFilename);
			}
		}
    }
	public function getUserImage(Request $request) {
    	if($request->isMethod('post')) {
			$data = $request->all();
			
			$validator = Validator::make($data, [
				'id' => 'required'
			]);
  		if($validator->fails()){
  			return $this->sendError('Validation Error.', $validator->errors());
        }
		else{
				$image = null;
				if($request->hasFile('image')){
					$images = $request->file('image');
					
				   $profile_pic = str_replace(" ","",$images->getClientOriginalName());
				   $extension = pathinfo($profile_pic, PATHINFO_EXTENSION);
				   $fileName = time().".".$extension;
					
					$filepath = 'public/patients_pics/';
					Storage::disk('s3')->put($filepath.$fileName,file_get_contents($images));
					$image = $fileName;
					
					// if(!empty($data['image_old'])) {
					  // $Oldfilename = 'public/patients_pics/'.$data['image_old'];
					  // if(Storage::disk('s3')->exists($Oldfilename)) {
						 // Storage::disk('s3')->delete($Oldfilename);
					  // }
					// }
				}
				else{
					if(!empty($data['image_old'])) {
						$image = $data['image_old'];
					}
				}
				$image_old = (!empty($data['image_old'])) ? $data['image_old'] : null;
				$patient = User::where('id', $data['id'])->update(array('image'=>$image));
				$user = User::where('id', $data['id'])->first();
				if(!empty($user->image)) {
					$image_url = getPath("public/patients_pics/".$user->image);
					$user['image'] = $image_url;
				}
				if(!empty($user->patient_number)) {
					Patients::where('patient_number', $user->patient_number)->update(array(
					  'image' => $image,
					));
				}
				if(!empty($user->OrganizationMaster) && !empty($user->OrganizationMaster->logo)){
					$logo = url("/")."/public/organization_logo/".$user->OrganizationMaster->logo;
				}
				else{
					$logo = null;
				}
				
				$user->organization = array("id"=>($user->organization!=null)?$user->organization:"","title"=>getOrganizationIdByName(@$user->organization),"logo"=>$logo);
				
				$user['token'] = @$request->bearerToken();
				$user['expires_at'] = "";
				$user["dob_type"] = 0;
				if(!empty($user->dob)) {
					$user["dob_type"] = get_patient_age_api($user->dob)[1];
					$user["dob"] = get_patient_age_api($user->dob)[0];
				}
				$user_details = UserDetails::select("user_id","referral_code","referred_id","wallet_amount")->where('user_id',$user->id)->first();
				$user_details['referred_code'] = getRefCodeByUserId(@$user_details->referred_id);
				$user['user_details'] = $user_details;
				return $this->sendResponse($user, 'Image Updated Successfully.',true);
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
	
	function searchDoctorsByAddress(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['search_key'] = $data->get('search_key');
		$docs_array = [];
		$success = false;
		if($user_array['search_key'] != '') {
			$search_key = $user_array['search_key'];
			$docs_array = Doctors::with(["getCityName","getStateName","getCountryName"])->select(["id","profile_pic","speciality","city_id","state_id","address_1","first_name","last_name","qualification","experience","opd_timings","oncall_status","oncall_fee","consultation_fees"])->select('address_1','city_id','lat','lng')->Where(['delete_status'=>1,'status'=>1])->where('address_1', 'like', '%'.$user_array['search_key'].'%')->orderBy("id","ASC")->get();
			$success = true;
		}
		return $this->sendResponse($docs_array, 'Doctor Details get Successfully.',$success);
	}

	public function addAppointmentOld(Request $request) {
		$data=Input::json();
		$user_array=array();
        $user_array['order_by']   =$data->get('order_by');
        $user_array['doc_id']   =$data->get('doc_id');
        $user_array['doc_name']   =$data->get('doc_name');
        $user_array['p_id']   =$data->get('p_id');
        $user_array['visit_type'] = $data->get('visit_type');
        $user_array['blood_group'] = (!empty($data->get('blood_group'))?$data->get('blood_group'):NULL);
        $user_array['consultation_fees'] = $data->get('consultation_fees');
        $user_array['appointment_date'] = $data->get('appointment_date');
        $user_array['time'] = $data->get('time');
        $user_array['slot_duration'] = $data->get('slot_duration');
        $user_array['onCallStatus'] = $data->get('onCallStatus');
        $user_array['isFirstTeleAppointment'] = $data->get('isFirstTeleAppointment');
        $user_array['isDirectAppt'] = $data->get('isDirectAppt');
        $user_array['service_charge'] = $data->get('service_charge');
        $user_array['is_subscribed'] = $data->get('is_subscribed');
        $user_array['gender'] = $data->get('gender');
        $user_array['patient_name'] = $data->get('patient_name');
        $user_array['dob'] = $data->get('dob');
        $user_array['mobile_no'] = $data->get('mobile_no');
        $user_array['otherPatient'] = $data->get('otherPatient');
        $user_array['coupon_id'] = $data->get('coupon_id');
        $user_array['coupon_discount'] = $data->get('coupon_discount');
        $user_array['call_type'] = $data->get('call_type');
        $user_array['referral_code'] = $data->get('referral_code');
		
		$validator = Validator::make($user_array, [
			'doc_id'                => 'required|max:50',
			// 'doc_name'                => 'required|max:50',
			'p_id'                => 'required|max:50',
			// 'time'             => 'required|max:50',
			// 'slot_duration'             => 'required|max:50'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			if($user_array['isFirstTeleAppointment'] == 1 || $user_array['isDirectAppt'] == 1) {
				$user_array['doc_id'] = getSetting("direct_appt_doc_id")[0];
				$docData = Doctors::select(["user_id","consultation_fees","oncall_fee","slot_duration","first_name","last_name"])->where(['id'=>$user_array['doc_id']])->first();
				$increment_time = $docData->slot_duration*60;
				$user_array['appointment_date'] = date("Y-m-d");
				$start_date = date("Y-m-d H:i:s");
				$user_array['time'] = checkAvailableSlot($start_date,$docData->user_id,$increment_time);
				//for instant doc
				if(date('N') == 7){
				    //$user_array['appointment_date'] = date("Y-m-d");
				    $user_array['appointment_date'] = date('Y-m-d', strtotime('+1 day', strtotime($user_array['appointment_date'])));
				    $mydate = date('Y-m-d H:i:s',strtotime('10:00:00'));
				    $nexday = date('Y-m-d', strtotime('+1 day', strtotime($mydate)));
				    $nexdateTime = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($mydate)));
				    $user_array['time'] = checkAvailableSlot($nexdateTime,$docData->user_id,$increment_time);
				    $finalDateTime =  date('Y-m-d H:i:s',strtotime($nexday.' '.$user_array['time']));
				    $start_date = $finalDateTime;
				}else{
				if(date("H") < 10){
                    //$user_array['appointment_date'] = date("Y-m-d");
				    $start_date = date('Y-m-d H:i:s',strtotime(date("Y-m-d").' '.'10:00:00'));
				    $user_array['time'] = checkAvailableSlot($start_date,$docData->user_id,$increment_time);
				    $finalDateTime =  date('Y-m-d H:i:s',strtotime(date("Y-m-d").' '.$user_array['time']));
				    $start_date = $finalDateTime;
				}else if(date("H") >= 22){
				  //echo date('Y-m-d h:i:s', strtotime('+1 day', strtotime($datetime)));
				   // $user_array['appointment_date'] = date("Y-m-d");
					if(date('N') == 6){
                        $user_array['appointment_date'] = date('Y-m-d', strtotime('+2 day', strtotime($user_array['appointment_date'])));
					    $mydate = date('Y-m-d H:i:s',strtotime('10:00:00'));
					    $nexday = date('Y-m-d', strtotime('+2 day', strtotime($mydate)));
					    $nexdateTime = date('Y-m-d H:i:s', strtotime('+2 day', strtotime($mydate)));
					}else{
                        $user_array['appointment_date'] = date('Y-m-d', strtotime('+1 day', strtotime($user_array['appointment_date'])));
					    $mydate = date('Y-m-d H:i:s',strtotime('10:00:00'));
					    $nexday = date('Y-m-d', strtotime('+1 day', strtotime($mydate)));
					    $nexdateTime = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($mydate)));
					}
				    $user_array['time'] = checkAvailableSlot($nexdateTime,$docData->user_id,$increment_time);
				    $finalDateTime =  date('Y-m-d H:i:s',strtotime($nexday.' '.$user_array['time']));
				    $start_date = $finalDateTime;
				}
				}
				// for instant doc end
				$fee = $data->get('totpay');
				$charge =  0;
				$tax =  0;
				$gst =  0;
				$service_charge_meta = 	["service_charge_rupee"=>$charge,"tax_in_percent"=>$tax,"gst"=>$gst];
				$service_charge = (!empty($data->get('service_charge'))?$data->get('service_charge'):0);
				$consultation_fees = $docData->consultation_fees;
				if($user_array['onCallStatus'] == "1") {
					if(isset($user_array['isDirectAppt']) && $user_array['isDirectAppt'] == '1'){
						$consultation_fees = getSetting("direct_tele_appt_fee")[0];	
					}
					else{
						$consultation_fees = $docData->oncall_fee;	
					}
				}
				$order_subtotal = $consultation_fees;
				$user_array['doc_name'] = $docData->first_name." ".$docData->last_name;
				$user_array['slot_duration'] = $docData->slot_duration;
				$user_array['consultation_fees'] = $order_subtotal;
				
				$p_ids = User::select("pId")->where(["mobile_no"=>$user_array['mobile_no']])->pluck("pId")->toArray();
				$appointment = Appointments::whereIn('pID',$p_ids)->where(["delete_status"=>1,"appointment_confirmation"=>1,"type"=>3])->count();
				$dt = date('Y-m-d');
				$plan_data =  PlanPeriods::whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['user_id'=>$user_array['order_by'],'status'=>1])->where('remaining_appointment','>',0)->first();
				if(!empty($plan_data) && $user_array['isDirectAppt'] == 1) {
					$appointment = 0;
					$user_array['isFirstTeleAppointment'] = 1;
					$user_array['isDirectAppt'] = 1;
				}
				if($user_array['isFirstTeleAppointment'] == 1 && $user_array['isDirectAppt'] == 1 && $appointment == 0) {
					$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$data->get('dob_type'));
					$order = AppointmentOrder::create([
					  'type'	 => 0,
					  'service_charge_meta' =>  json_encode($service_charge_meta),
					  'service_charge' =>  $service_charge,
					  'order_subtotal' =>  $order_subtotal,
					  'order_total' =>  $fee,
					  'order_status' =>  0,
					  'app_date' => $start_date,
					  'doc_id' =>  $docData->user_id,
					  'order_from' => 1,
					  'order_by' => $user_array['order_by'],
					  'coupon_id' => $user_array['coupon_id'],
					  'coupon_discount' => $user_array['coupon_discount'],
					  'meta_data' => json_encode($user_array),
					]);
					$appointment_id = Parent::putAppointmentDataApp($order,'','');
					if(!empty($appointment_id) && !empty($plan_data)) {
						if(!empty($plan_data->appointment_ids)){
							$appointment_ids = explode(",",$plan_data->appointment_ids);
							array_push($appointment_ids,$appointment_id);
							$appointment_ids =  implode(',',$appointment_ids);
						}
						else{
							$appointment_ids = $appointment_id;
						}
						$remaining_appointment_count =  $plan_data->remaining_appointment;
						PlanPeriods::where('id',$plan_data->id)->update(array('remaining_appointment' => ($remaining_appointment_count-1),'appointment_ids'=>$appointment_ids));
					}
					return $this->sendResponse($order,'Appointment Added Successfully.',true);
				}
				else if($appointment > 0 && $user_array['isFirstTeleAppointment'] != 1 && $user_array['isDirectAppt'] == 1) {
					$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$data->get('dob_type'));
					$appointment_order = AppointmentOrder::create([
						  'type'	 => 1, 
						  'service_charge_meta' =>  json_encode($service_charge_meta),
						  'service_charge' =>  $service_charge,
						  'order_subtotal' =>  $order_subtotal,
						  'order_total' =>  $fee,
						  'order_status' =>  0,
						  'app_date' => $start_date,
						  'doc_id' =>  $docData->user_id,
						  'order_from' => 1,
						  'order_by' => $user_array['order_by'],
						  'coupon_id' => $user_array['coupon_id'],
						  'coupon_discount' => $user_array['coupon_discount'],
						  //'referral_code' => $referral_id,
						  'meta_data' => json_encode($user_array),
					]);
					$appointment_order['order_id'] = $appointment_order->id;
					return $this->sendResponse($appointment_order,'Appointment Added Successfully.',true);
				}
				else{
					return $this->sendError('Your first appointment has been processed.', $validator->errors());
				}
			}
			else{
				$docData = Doctors::select(["user_id","consultation_fees","oncall_fee","slot_duration","first_name","last_name"])->where(['id'=>$user_array['doc_id']])->first();
				$user_array['slot_duration'] = checkOpdTimeById($user_array['doc_id'],$user_array['appointment_date'],strtotime($user_array['time']),$docData->slot_duration);
				$increment_time = $user_array['slot_duration']*60;
				$date = date("Y-m-d",strtotime($user_array['appointment_date']));
				$time = date("H:i:s",strtotime($user_array['time']));

				$start_date = date("Y-m-d H:i:s",strtotime($date." ".$time));
				$end_date = date('Y-m-d H:i:s',strtotime($date." ".$time)+$increment_time);
				//$referral_id = ReferralMaster::select("id")->where(["code"=>trim($user_array['referral_code'])])->first()->id;
				$fromD = date('Y-m-d H:i:s');
				$toD = date('Y-m-d H:i:s', strtotime($fromD)-300);
				$order_exists = AppointmentOrder::where(['doc_id'=>$docData->user_id,'order_status'=>0])->where('app_date' ,'like','%'.$start_date.'%')->where('created_at', '<=', $fromD)->where('created_at', '>=', $toD)->get();
				if(count($order_exists) > 0) {
					return $this->sendError('Time slot not available. Please choose another time slot.','Time slot not available. Please choose another time slot.');
				}
				$fee = $data->get('totpay');
				$charge =  getSetting("service_charge_rupee")[0];
				$tax =  getSetting("tax_in_percent")[0];
				$gst =  getSetting("gst")[0];
				$service_charge_meta = 	["service_charge_rupee"=>$charge,"tax_in_percent"=>$tax,"gst"=>$gst];
				$service_charge = (!empty($data->get('service_charge'))?$data->get('service_charge'):0);
				//$order_subtotal = $fee - $service_charge;
				$consultation_fees = $docData->consultation_fees;
				if($user_array['onCallStatus'] == "1") {
					if(isset($user_array['isDirectAppt']) && $user_array['isDirectAppt'] == '1'){
						$consultation_fees = getSetting("direct_tele_appt_fee")[0];	
					}
					else{
						$consultation_fees = $docData->oncall_fee;	
					}
				}
				$order_subtotal = $consultation_fees;

			}
			$user_array['consultation_fees'] = $order_subtotal;
			$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$data->get('dob_type'));
			$appointment_order = AppointmentOrder::create([
				  'type'	 => 1, 
				  'service_charge_meta' =>  json_encode($service_charge_meta),
				  'service_charge' =>  $service_charge,
				  'order_subtotal' =>  $order_subtotal,
				  'order_total' =>  $fee,
				  'order_status' =>  0,
				  'app_date' => $start_date,
				  'doc_id' =>  $docData->user_id,
				  'order_from' => 1,
				  'order_by' => $user_array['order_by'],
				  'coupon_id' => $user_array['coupon_id'],
				  'coupon_discount' => $user_array['coupon_discount'],
				  //'referral_code' => $referral_id,
				  'meta_data' => json_encode($user_array),
			]);
			$appointment_order['order_id'] = $appointment_order->id;
			return $this->sendResponse($appointment_order,'Appointment Added Successfully.',true);
		}
	}
	
	public function addAppointment(Request $request) {
		$data=Input::json();
		$user_array=array();
        $user_array['order_by']   =$data->get('order_by');
        $user_array['doc_id']   = 49179;
        $user_array['doc_name']   =$data->get('doc_name');
        $user_array['p_id']   =$data->get('p_id');
        $user_array['visit_type'] = $data->get('visit_type');
        $user_array['blood_group'] = (!empty($data->get('blood_group'))?$data->get('blood_group'):NULL);
        $user_array['consultation_fees'] = $data->get('consultation_fees');
        $user_array['appointment_date'] = $data->get('appointment_date');
        $user_array['time'] = $data->get('time');
        $user_array['slot_duration'] = $data->get('slot_duration');
        $user_array['onCallStatus'] = $data->get('onCallStatus');
        $user_array['isFirstTeleAppointment'] = $data->get('isFirstTeleAppointment');
        $user_array['isDirectAppt'] = $data->get('isDirectAppt');
        $user_array['service_charge'] = $data->get('service_charge');
        $user_array['is_subscribed'] = $data->get('is_subscribed');
        $user_array['gender'] = $data->get('gender');
        $user_array['patient_name'] = $data->get('patient_name');
        $user_array['dob'] = $data->get('dob');
        $user_array['mobile_no'] = $data->get('mobile_no');
        $user_array['other_mobile_no'] = $data->get('other_mobile_no');
        $user_array['otherPatient'] = $data->get('otherPatient');
        $user_array['coupon_id'] = $data->get('coupon_id');
        $user_array['coupon_discount'] = $data->get('coupon_discount');
        $user_array['call_type'] = $data->get('call_type');
        $user_array['referral_code'] = $data->get('referral_code');
        $user_array['is_peak'] = $data->get('is_peak');
        $user_array['finalConsultaionFee'] = $data->get('finalConsultaionFee');
        $user_array['availWalletAmt'] = $data->get('availWalletAmt');
        $user_array['_from'] = $data->get('_from');
		
		$validator = Validator::make($user_array, [
			'doc_id'                => 'required|max:50',
			// 'doc_name'                => 'required|max:50',
			'p_id'                => 'required|max:50',
			// 'time'             => 'required|max:50',
			// 'slot_duration'             => 'required|max:50'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			// if($user_array['otherPatient'] == 1) {
			$puser = User::select("first_name","last_name","id")->where(["mobile_no"=>$user_array['mobile_no']])->where('parent_id',0)->first();
			if(empty($puser->first_name)) {
				$user_array['otherPatient'] = 0;
			}
			elseif($user_array['p_id'] != $puser->id) {
				$user_array['otherPatient'] = 0;
			}
			else{
				// $patName = $puser->first_name." ".$puser->last_name;
				// if(strtolower($user_array['patient_name']) == strtolower(trim($patName))) {
					// $user_array['otherPatient'] = 0;
				// }
				$isUser = User::select("first_name","last_name","id")->where(["mobile_no"=>$user_array['mobile_no']])->get();
				if(count($isUser)>0) {
					$isOther = 0;
					foreach($isUser as $raw) {
						$patName = $raw->first_name." ".$raw->last_name;
						if(strtolower(trim($user_array['patient_name'])) == strtolower(trim($patName))) {
							$isOther = 0;
							$user_array['p_id'] = $raw->id;
							$user_array['otherPatient'] = 0;
							break;
						}
						else{
							$isOther = 1;
						}
					}
					if($isOther > 0) {
						$user_array['otherPatient'] = 1;
					}
				}
			}
			$dt = date('Y-m-d');
			$plan_data =  PlanPeriods::select('id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['user_id'=>$user_array['order_by'],'status'=>1])->where('remaining_appointment','>',0)->count();
			if($plan_data > 0) {
				$user_array['is_subscribed'] = 1;
			}
			if($user_array['isFirstTeleAppointment'] == 1 || $user_array['isDirectAppt'] == 1) {
				$user_array['doc_id'] = 49179;
				// $user_array['doc_id'] = getSetting("direct_appt_doc_id")[0];
				// if($user_array['is_peak'] == "1") {
					// $user_array['doc_id'] = getSetting("direct_appt_doc_id")[1];
				// }
				$docData = Doctors::select(["user_id","consultation_fees","oncall_fee","slot_duration","first_name","last_name"])->where(['id'=>$user_array['doc_id']])->first();
				$increment_time = $docData->slot_duration*60;
				$user_array['appointment_date'] = date("Y-m-d");
				$start_date = date("Y-m-d H:i:s");
				$user_array['time'] = checkAvailableSlot($start_date,$docData->user_id,$increment_time);
				$fee = $data->get('totpay');
				$charge =  0;
				$tax =  0;
				$gst =  0;
				$service_charge_meta = 	["service_charge_rupee"=>$charge,"tax_in_percent"=>$tax,"gst"=>$gst];
				$service_charge = (!empty($data->get('service_charge'))?$data->get('service_charge'):0);
				$consultation_fees = $docData->consultation_fees;
				if($user_array['onCallStatus'] == "1") {
					if($user_array['isDirectAppt'] == '1') {
						$consultation_fees = getSetting("direct_tele_appt_fee")[0];	
					}
					else{
						$consultation_fees = $docData->oncall_fee;	
					}
				}
				if(!empty($user_array['finalConsultaionFee'])) {
					$consultation_fees = $user_array['finalConsultaionFee'];	
				}
				$order_subtotal = $consultation_fees;
				$user_array['doc_name'] = $docData->first_name." ".$docData->last_name;
				$user_array['slot_duration'] = $docData->slot_duration;
				$user_array['consultation_fees'] = $order_subtotal;
				
				$p_ids = User::select("pId")->where(["mobile_no"=>$user_array['mobile_no']])->pluck("pId")->toArray();
				$appointment = Appointments::whereIn('pID',$p_ids)->where(["delete_status"=>1,"appointment_confirmation"=>1,"type"=>3])->count();
				if($plan_data > 0 && $user_array['isDirectAppt'] == 1 && $user_array['is_peak'] != "1") {
					$appointment = 0;
					$user_array['isFirstTeleAppointment'] = 1;
					$user_array['isDirectAppt'] = 1;
				}
				$isAppt_free = 0;
				$lab = LabOrders::select(["id","is_free_appt"])->where(["user_id"=>$user_array['order_by']])->where("is_free_appt","1")->first();
				if(!empty($lab)){
					$isAppt_free = $lab->is_free_appt;
				}
					
				if($user_array['isFirstTeleAppointment'] == 1 && $user_array['isDirectAppt'] == 1 && $appointment == 0) {
					$endT = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))-300);
					$appEx = AppointmentOrder::where(['order_by'=>$user_array['order_by']])->whereIn('order_status',[0,1])->where('created_at', '>=', $endT)->where('created_at', '<=', date('Y-m-d H:i:s'))->count();
					if($appEx > 0) {
						return $this->sendError('Appointment received. We’ll contact you soon.','Appointment received. We’ll contact you soon.');
					}
					$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$data->get('dob_type'));
					$order = AppointmentOrder::create([
					  'type'	 => 0,
					  'service_charge_meta' =>  json_encode($service_charge_meta),
					  'service_charge' =>  $service_charge,
					  'order_subtotal' =>  $order_subtotal,
					  'order_total' =>  $fee,
					  'order_status' =>  0,
					  'app_date' => $start_date,
					  'doc_id' =>  $docData->user_id,
					  'order_from' => 1,
					  'order_by' => $user_array['order_by'],
					  'coupon_id' => $user_array['coupon_id'],
					  'coupon_discount' => $user_array['coupon_discount'],
					  'meta_data' => json_encode($user_array),
					]);
					// if($user_array['onCallStatus'] == "1") {
						// updateWallet($user_array['order_by'],2,'appt_reward');
						// availWalletAmount($user_array['order_by'],6,$user_array['availWalletAmt']);
					// }
					$appointment_id = Parent::putAppointmentDataApp($order,'','');
					return $this->sendResponse($order,'Appointment Added Successfully.',true);
				}
				else if($user_array['isFirstTeleAppointment'] != 1 && $user_array['isDirectAppt'] == 1) {
					// echo "b"; die;
					$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$data->get('dob_type'));
					$appointment_order = AppointmentOrder::create([
						  'type'	 => 1, 
						  'service_charge_meta' =>  json_encode($service_charge_meta),
						  'service_charge' =>  $service_charge,
						  'order_subtotal' =>  $order_subtotal,
						  'order_total' =>  $fee,
						  'order_status' =>  0,
						  'app_date' => $start_date,
						  'doc_id' =>  $docData->user_id,
						  'order_from' => 1,
						  'order_by' => $user_array['order_by'],
						  'coupon_id' => $user_array['coupon_id'],
						  'coupon_discount' => $user_array['coupon_discount'],
						  //'referral_code' => $referral_id,
						  'meta_data' => json_encode($user_array),
					]);
					$appointment_order['order_id'] = $appointment_order->id;
					return $this->sendResponse($appointment_order,'Appointment Added Successfully.',true);
				}
				else if($isAppt_free == 1 && $user_array['isDirectAppt'] == 1) {
					$endT = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s'))-300);
					$appEx = AppointmentOrder::where(['order_by'=>$user_array['order_by']])->whereIn('order_status',[0,1])->where('created_at', '>=', $endT)->where('created_at', '<=', date('Y-m-d H:i:s'))->count();
					if($appEx > 0) {
						return $this->sendError('Appointment received. We’ll contact you soon.','Appointment received. We’ll contact you soon.');
					}
					$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$data->get('dob_type'));
					$appointment_order = AppointmentOrder::create([
						  'type'	 => 0, 
						  'service_charge_meta' =>  json_encode($service_charge_meta),
						  'service_charge' =>  $service_charge,
						  'order_subtotal' =>  $order_subtotal,
						  'order_total' =>  $fee,
						  'order_status' =>  0,
						  'app_date' => $start_date,
						  'doc_id' =>  $docData->user_id,
						  'order_from' => 1,
						  'order_by' => $user_array['order_by'],
						  'coupon_id' => $user_array['coupon_id'],
						  'coupon_discount' => $user_array['coupon_discount'],
						  //'referral_code' => $referral_id,
						  'meta_data' => json_encode($user_array),
					]);
					$appointment_order['order_id'] = $appointment_order->id;
					if(!empty($lab)) {
						LabOrders::where(["id"=>$lab->id])->update([
							'is_free_appt' => 0,
						]);
					}
					Parent::putAppointmentDataApp($appointment_order,'','');
					return $this->sendResponse($appointment_order,'Appointment Added Successfully.',true);
				}
				else if($user_array['is_peak'] == "1") {
					// echo "d"; die;
					$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$data->get('dob_type'));
					$appointment_order = AppointmentOrder::create([
						  'type'	 => 1, 
						  'service_charge_meta' =>  json_encode($service_charge_meta),
						  'service_charge' =>  $service_charge,
						  'order_subtotal' =>  $order_subtotal,
						  'order_total' =>  $fee,
						  'order_status' =>  0,
						  'app_date' => $start_date,
						  'doc_id' =>  $docData->user_id,
						  'order_from' => 1,
						  'order_by' => $user_array['order_by'],
						  'coupon_id' => $user_array['coupon_id'],
						  'coupon_discount' => $user_array['coupon_discount'],
						  //'referral_code' => $referral_id,
						  'meta_data' => json_encode($user_array),
					]);
					$appointment_order['order_id'] = $appointment_order->id;
					return $this->sendResponse($appointment_order,'Appointment Added Successfully.',true);
				}
				else{
					return $this->sendError('Your first appointment has been processed.', $validator->errors());
				}
			}
			else{
				$docData = Doctors::select(["user_id","consultation_fees","oncall_fee","slot_duration","first_name","last_name"])->where(['id'=>$user_array['doc_id']])->first();
				$user_array['slot_duration'] = checkOpdTimeById($user_array['doc_id'],$user_array['appointment_date'],strtotime($user_array['time']),$docData->slot_duration);
				$increment_time = $user_array['slot_duration']*60;
				$date = date("Y-m-d",strtotime($user_array['appointment_date']));
				$time = date("H:i:s",strtotime($user_array['time']));

				$start_date = date("Y-m-d H:i:s",strtotime($date." ".$time));
				$end_date = date('Y-m-d H:i:s',strtotime($date." ".$time)+$increment_time);
				//$referral_id = ReferralMaster::select("id")->where(["code"=>trim($user_array['referral_code'])])->first()->id;
				$fromD = date('Y-m-d H:i:s');
				$toD = date('Y-m-d H:i:s', strtotime($fromD)-300);
				$order_exists = AppointmentOrder::where(['doc_id'=>$docData->user_id])->whereIn('order_status',[0,1])->where('app_date' ,'like','%'.$start_date.'%')->where('created_at', '<=', $fromD)->where('created_at', '>=', $toD)->get();
				if(count($order_exists) > 0) {
					return $this->sendError('Time slot not available. Please choose another time slot.','Time slot not available. Please choose another time slot.');
				}
				$fee = $data->get('totpay');
				$charge =  getSetting("service_charge_rupee")[0];
				$tax =  getSetting("tax_in_percent")[0];
				$gst =  getSetting("gst")[0];
				$service_charge_meta = 	["service_charge_rupee"=>$charge,"tax_in_percent"=>$tax,"gst"=>$gst];
				$service_charge = (!empty($data->get('service_charge'))?$data->get('service_charge'):0);
				//$order_subtotal = $fee - $service_charge;
				$consultation_fees = $docData->consultation_fees;
				if($user_array['onCallStatus'] == "1") {
					if(isset($user_array['isDirectAppt']) && $user_array['isDirectAppt'] == '1'){
						$consultation_fees = getSetting("direct_tele_appt_fee")[0];	
					}
					else{
						$consultation_fees = $docData->oncall_fee;	
					}
				}
				if(!empty($user_array['finalConsultaionFee'])) {
					$consultation_fees = $user_array['finalConsultaionFee'];	
				}
				$order_subtotal = $consultation_fees;
			}
			$user_array['consultation_fees'] = $order_subtotal;
			$user_array['dob'] = get_patient_dobByAge($user_array['dob'],$data->get('dob_type'));
			$appointment_order = AppointmentOrder::create([
				  'type'	 => 1, 
				  'service_charge_meta' =>  json_encode($service_charge_meta),
				  'service_charge' =>  $service_charge,
				  'order_subtotal' =>  $order_subtotal,
				  'order_total' =>  $fee,
				  'order_status' =>  0,
				  'app_date' => $start_date,
				  'doc_id' =>  $docData->user_id,
				  'order_from' => 1,
				  'order_by' => $user_array['order_by'],
				  'coupon_id' => $user_array['coupon_id'],
				  'coupon_discount' => $user_array['coupon_discount'],
				  //'referral_code' => $referral_id,
				  'meta_data' => json_encode($user_array),
			]);
			$appointment_order['order_id'] = $appointment_order->id;
			return $this->sendResponse($appointment_order,'Appointment Added Successfully.',true);
		}
	}
	
	public function sendUserAppointmentMailSms($appointment_id) {
		if(Parent::is_connected()==1) {
			$appointment =  Appointments::where('id',$appointment_id)->first();
			$docData = Doctors::where(['user_id'=>$appointment->doc_id])->first();
			$docName = "Dr. ".ucfirst($docData->first_name)." ".$docData->last_name;
			$patientname = $appointment->patient->first_name.' '.$appointment->patient->last_name;
			$appointDate = date('d-m-Y',strtotime($appointment->start));
			$appointtime = date('h:i A',strtotime($appointment->start));
			
			if(!empty($appointment->Patient->email)) {         
				$EmailTemplate = EmailTemplate::where('slug','patientappointment')->first();
				$to = $appointment->Patient->email;
				if($EmailTemplate && !empty($to)) {
					$body = $EmailTemplate->description;
					$tbl = '<table style="width: 100%;" cellpadding="0" cellspacing="0"><tbody><tr><td colspan="2" style="color:#189ad4; font-size: 15px;font-weight:500; padding: 15px 0px 0px;">Dear '.$patientname.'</td></tr><tr><td colspan="2" style="color:#333; font-size: 13px;font-weight:500; padding: 4px 0px 15px;">if you wish to reschedule or cancel your appointment, please call us at <br> '.$docData->mobile.'</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Appointment Dr.</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Dr. '.@$docData->first_name." ".@$docData->last_name.'</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Date and Time</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">'.date('d-m-Y, h:i:sa',strtotime($appointment->start)).'</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Address</td><td style="border:1px solid #ccc; font-size: 13px; color:#189ad4; padding: 5px 10px;">'.$docData->address_1.', '.$docData->address_2.','.getCityName($docData->city_id).','.getStateName($docData->state_id).','.getCountrieName($docData->country_id).','.$docData->zipcode.'</td></tr><tr><td colspan="2" style="font-size: 13px; color:#333; padding:10px 0px 10px;">if you wish to reschedule or cancel your appointment,please call us at '.$docData->mobile.'</td></tr><tr><td colspan="2" style="font-size: 13px; color:#333; padding:10px 0px 10px;"><strong>Thanks <br>'.$docData->clinic_name.'</strong></td></tr></tbody></table>';
					
					$mailMessage = str_replace(array('{{pat_name}}','{{clinic_name}}','{{clinic_phone}}','{{appointmenttable}}'),
					array($patientname,$docData->clinic_name,$docData->mobile,$tbl),$body);
					$to_docname = '';
					$datas = array('to' =>$to,'from' => 'noreply@healthgennie.com','mailTitle'=>$EmailTemplate->title,'practiceData'=>$docData,'content'=>$mailMessage,'subject'=>$EmailTemplate->subject);
					try{
					Mail::send( 'emails.mailtempPractice', $datas, function( $message ) use ($datas) {
						$message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
					});
					}
					catch(\Exception $e){
							  // Never reached
						}
				}
			}
			if (!empty($docData->mobile_no)) {
				$message = urlencode("Dear ".$docName.", ".$patientname." has booked a tele consultation with you on ".$appointDate." and ".$appointtime." Patient contact number is ".$appointment->Patient->mobile_no." please call the patient at the appointment time and send the digital prescription from Health Gennie app Thanks Team Health Gennie");
				$this->sendSMS($docData->mobile_no,$message,'1707161735128760937');
			}
			if(!empty($appointment->Patient->mobile_no)) {
				$message = urlencode("Dear ".ucfirst($appointment->Patient->first_name)." ".$appointment->Patient->last_name.", Your Tele Consultation with Dr ".$appointment->User->DoctorInfo->first_name." ".$appointment->User->DoctorInfo->last_name."  on  ".$appointDate." and ".$appointtime."  has been confirmed. Please be ready to pick up the call at the consultation time.\nThanks Team Health Gennie.");
				$this->sendSMS($appointment->Patient->mobile_no,$message,'1707161587979652683');
				
				$admin_msg = urlencode("This patient(".$patientname.") of tele consultaion appointment with ".$docName." on ".$appointDate." at ".$appointtime." Doctor Mobile : ".$docData->mobile_no." Patient Mobile : ".$appointment->Patient->mobile_no." Thanks Team Health Gennie");
				$this->sendSMS(8302053965,$admin_msg,'1707161735123037290');
			}
				
			$EmailTemplate = EmailTemplate::where('slug','appointmentmailadmin')->first();
			if($EmailTemplate) {
				$body = $EmailTemplate->description;
				$mailMessage = str_replace(array('{{doc_name}}','{{date}}','{{time}}','{{patientname}}'),
				array($docName,$appointDate,$appointtime,$patientname),$body);
				$datas = array('to' =>"info@healthgennie.com",'from' => 'noreply@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'practiceData'=>$docData,'subject'=>$EmailTemplate->subject);
				try{
				Mail::send('emails.mailtempPractice', $datas, function( $message ) use ($datas) {
					$message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
				});
				}
				catch(\Exception $e){
							  // Never reached
						}
			}
		}
	}
	function appointmentCheckout(Request $request) {
		  $data = $request->all();
		  $params =  json_decode(base64_decode($data['params']));
		  // pr($params);
		  $user_array = array();
		  $user_array['orderId'] = $params->orderId;
		  $user_array['payable_amt'] = $params->payable_amt;

		  $validator = Validator::make($user_array, [
			'orderId'   =>  'required',
			'payable_amt'   =>  'required',
		  ]);
		  if($validator->fails()){
			return $this->sendError($validator->errors());
		  }
		  else {
			  $order = AppointmentOrder::select("order_by")->where(["id"=>$user_array['orderId']])->first();
			$parameters = [];
			// $parameters["MID"] = "yNnDQV03999999736874"; 			
			// $parameters["ORDER_ID"] = $user_array['orderId']; 
			// $parameters["CUST_ID"] = @$order->order_by; 
			// $parameters["TXN_AMOUNT"] = $user_array['payable_amt']; 
			// $parameters["CALLBACK_URL"] = url('paytmresponse'); 
			// $order = Indipay::gateway('Paytm')->prepare($parameters);
			// return Indipay::process($order);
			$user = User::where("id",$order->order_by)->first();
			$mbl = isset($user->mobile_no) ? $user->mobile_no : '0000000000';
			$email = !empty($user->email)   ? $user->email : 'test@mailinator.com';
			$parameters["order"] = $user_array['orderId'];
			$parameters["amount"] = $user_array['payable_amt'];
			if($user->mobile_no == 7691079774){
				$parameters["amount"] = 1;
			}
			$parameters["user"] = $order->order_by;
			$parameters["mobile_number"] = $mbl;
			$parameters["email"] = $email;
			$parameters["callback_url"] = url('paytmresponse');
			$payment = PaytmWallet::with('receive');
			$payment->prepare($parameters);
			return $payment->receive();
		}
	}
		
	public function appointmentConfirmationByDoc(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['appointment_id'] = $data->get('appointment_id');
		$validator = Validator::make($user_array, [
			'appointment_id' => 'required|max:50',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			Appointments::where('id', $user_array['appointment_id'])->update(array(
				'appointment_confirmation' => 1
			));
		}
	}
	public function cancelAppointment(Request $request){
		$data=Input::json();
		$user_array=array();
		$user_array['appointment_id']   =$data->get('app_id');
        $user_array['cancel_reason'] = $data->get('cancel_reason');
        $user_array['other_reason'] = (!empty($data->get('other_reason'))?$data->get('other_reason'):NULL);

		$validator = Validator::make($user_array, [
			'appointment_id'       => 'required|max:50'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			if(empty($user_array['cancel_reason'])){
				$user_array['cancel_reason'] = "cancelbypatient";
			}
			 
			$appointment = Appointments::where('id', $user_array['appointment_id'])->update(array(
				'status' => 0,
				'appointment_confirmation' => 1,
				'cancel_reason' => $user_array['cancel_reason'],
				'other_cancel_reason' => $user_array['other_reason']
			));
			$appointmentData = Appointments::with(['User.DoctorInfo','Patient'])->where('id',$user_array['appointment_id'])->first();
			$practice =  RoleUser::select(['user_id','role_id','practice_id'])->where(['user_id'=>$appointmentData->doc_id])->first();
			$practiceData = "";
			if(!empty($practice)){
				$practiceData =  PracticeDetails::where(['user_id'=>$practice->practice_id])->first();
			}
			else{
				return $this->sendError('Practice does not exist');
			}
			$pName = !empty($appointmentData->Patient->first_name) ? ucfirst($appointmentData->Patient->first_name) : "Sir/Madam";
			$doctorname = $appointmentData->User->DoctorInfo->first_name.' '.$appointmentData->User->DoctorInfo->last_name;
			$appointDate = date('d-m-Y',strtotime($appointmentData->start));
			$appointtime = date('h:i A',strtotime($appointmentData->start));


			if (!empty($appointmentData->Patient->email)) {
				$to = $appointmentData->Patient->email;
				$EmailTemplate = EmailTemplate::where('slug','cancelappointmentmailPatient')->first();
				if($EmailTemplate){
					$body = $EmailTemplate->description;
					$mailMessage = str_replace(array('{{pat_name}}','{{clinic_name}}','{{date}}','{{time}}','{{doctorname}}','{{mobile}}'),
					array($pName,$practiceData->clinic_name,$appointDate,$appointtime,$doctorname,$appointmentData->User->DoctorInfo->mobile),$body);
					$datas = array( 'to' =>$to,'from' => 'noreply@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'practiceData'=>$practiceData,'subject'=>$EmailTemplate->subject);
					try{
					Mail::send( 'emails.mailtempPractice', $datas, function( $message ) use ($datas)
					{
						$message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
					});
					}
					catch(\Exception $e){
						  // Never reached
					}
				}
			}
			if(!empty($appointmentData->Patient->mobile_no)) {
				$app_link = "www.healthgennie.com/download";
				$message = urlencode("Your appointment with Dr. ".$doctorname.", on ".$appointDate." at ".$appointtime." has been cancelled. For Better Experience Download Health Gennie App".$app_link." Thanks Team Health Gennie");
				$this->sendSMS($appointmentData->Patient->mobile_no,$message,'1707161735108546905');
			}
            if(!empty($appointmentData->User->email)) {
                $docName = "Dr. ".ucfirst($appointmentData->User->DoctorInfo->first_name)." ".$appointmentData->User->DoctorInfo->last_name;
                $patientname = $appointmentData->Patient->first_name.' '.$appointmentData->Patient->last_name;
                $appointDate = date('d-m-Y',strtotime($appointmentData->start));
                $appointtime = date('h:i A',strtotime($appointmentData->start));
				// if (in_array("1", $user_array['dNotify'])) {
					$to = $appointmentData->User->email;
					$EmailTemplate = EmailTemplate::where('slug','cancelappointmentmaildoctor')->first();
					if($EmailTemplate){
						$body = $EmailTemplate->description;
						$mailMessage = str_replace(array('{{doc_name}}','{{clinic_name}}','{{date}}','{{time}}','{{patientname}}'),
						array($docName,$practiceData->clinic_name,$appointDate,$appointtime,$patientname),$body);
						$datas = array( 'to' =>$to,'from' => 'noreply@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'practiceData'=>$practiceData,'subject'=>$EmailTemplate->subject);
						try{
						Mail::send( 'emails.mailtempPractice', $datas, function( $message ) use ($datas)
						{
							$message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
						});
						}
						catch(\Exception $e){
							  // Never reached
						}
					}
				// }
				if(!empty($appointmentData->User->DoctorInfo->mobile)) {
					$message = urlencode("Dear ".$docName.", Appointment of ".$patientname.", with you on ".$appointDate." At ".$appointtime." has been cancelled Thanks Team Health Gennie");
					$this->sendSMS($appointmentData->User->DoctorInfo->mobile,$message,'1707161587827747448');
				}
			}
      return $this->sendResponse($appointmentData,'Appointment cancel Successfully.',true);
    }
  }

	public function updateSchedule(Request $request){
		$data=Input::json();
		$user_array=array();
		$user_array['appointment_id']   =$data->get('app_id');
		$user_array['hg_doctor']   =$data->get('hg_doctor');
        $user_array['doc_id']   =$data->get('doc_id');
        $user_array['doc_name']   =$data->get('doc_name');
        $user_array['p_id']   =$data->get('p_id');
        $user_array['visit_type'] = $data->get('visit_type');
        $user_array['blood_group'] = (!empty($data->get('blood_group'))?$data->get('blood_group'):NULL);
        $user_array['consultation_fees'] = $data->get('consultation_fees');
        $user_array['appointment_date'] = $data->get('appointment_date');
        $user_array['time'] = $data->get('time');
        $user_array['slot_duration'] = $data->get('slot_duration');

		$validator = Validator::make($user_array, [
			'appointment_id' => 'required|max:50'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$increment_time = $user_array['slot_duration']*60;
			$date = date("Y-m-d",strtotime($user_array['appointment_date']));
			$time = date("H:i:s",strtotime($user_array['time']));

			$start_date = date("Y-m-d H:i:s",strtotime($date." ".$time));
			$end_date = date('Y-m-d H:i:s',strtotime($date." ".$time)+$increment_time);
			Appointments::where('id', $user_array['appointment_id'])->update(array(
				 'start' =>  $start_date,
				 'end'   =>  $end_date,
				 'appointment_confirmation'   =>  0,
				  'app_click_status' =>  5,
			));
			$docData = Doctors::where(['id'=>$user_array['doc_id']])->first();
		$appointment = Appointments::with(['patient'])->where('id', $user_array['appointment_id'])->first();
		if(Parent::is_connected()==1) {
			$docName = "Dr. ".ucfirst($docData->first_name)." ".$docData->last_name;
			$patientname = $appointment->patient->first_name.' '.$appointment->patient->last_name;
			$appointDate = date('d-m-Y',strtotime($appointment->start));
			$appointtime = date('h:i A',strtotime($appointment->start));
			$doc_email = $docData->email;
			$EmailTemplate = EmailTemplate::where('slug','confirmappointmentmaildoctor')->first();
			$confirm_url = url("/").'/appointment-confirm?id='.base64_encode($appointment->id);
			if($EmailTemplate) {
				$body = $EmailTemplate->description;
				$mailMessage = str_replace(array('{{doc_name}}','{{clinic_name}}','{{date}}','{{time}}','{{patientname}}','{{confirm_link}}'),
				array($docName,$docData->clinic_name,$appointDate,$appointtime,$patientname,$confirm_url),$body);
				$datas = array( 'to' =>$doc_email,'from' => 'noreply@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'practiceData'=>$docData,'subject'=>$EmailTemplate->subject);
				try{
				Mail::send('emails.mailtempPractice', $datas, function( $message ) use ($datas)
				{
					$message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
				});
				}
				catch(\Exception $e){
							  // Never reached
						}
			}  
			$EmailTemplate = EmailTemplate::where('slug','appointmentmailadmin')->first();
			if($EmailTemplate) {
				$body = $EmailTemplate->description;
				$mailMessage = str_replace(array('{{doc_name}}','{{date}}','{{time}}','{{patientname}}'),
				array($docName,$appointDate,$appointtime,$patientname),$body);
				$datas = array( 'to' =>"info@healthgennie.com",'from' => 'noreply@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'practiceData'=>$docData,'subject'=>$EmailTemplate->subject);
				try{
				Mail::send('emails.mailtempPractice', $datas, function( $message ) use ($datas){
					$message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
				});
				}
				catch(\Exception $e){
							  // Never reached
						}
			}
			if(!empty($appointment->patient->mobile_no)){
				$app_link = "www.healthgennie.com/download";
				$message = urlencode("Dear ".$patientname." Thanks for requesting an appointment with ".$docName." on Health Gennie. Your appointment will be confirmed shortly. For Better Experience Download Health Gennie App ".$app_link);
				$this->sendSMS($appointment->patient->mobile_no,$message,'1707161526893824568');
				
				$admin_msg = urlencode("This patient(".$patientname.") of appointment reschedule with ".$docName." on ".$appointDate." at ".$appointtime." Doctor Mobile : ".$docData->mobile_no." Patient Mobile : ".$appointment->patient->mobile_no."\n Please click below link for more info ".$confirm_url." Thanks Team Health Gennie");
				$this->sendSMS(8302053965,$admin_msg,'1707161587954005574');
			}
			if (!empty($docData->mobile_no)) {
				$message = urlencode("Dear ".$docName.", ".$patientname." has requested an appointment with you on ".$appointDate." at ".$appointtime.". Please click the link below to confirm or deny the appointment. Team Health Gennie. ".$confirm_url." Thanks Team Health Gennie");
				$this->sendSMS($docData->mobile_no,$message,'1707161587942145631');
			}
		}
        return $this->sendResponse($appointment,'Schedule Updated Successfully.',true);
      }
    }

	public function getDoctorSlots(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['doc_id'] = $data->get('doc_id');
		$user_array['date'] = $data->get('date');
		$user_array['type'] = $data->get('type');
		$success = false;
		$opd_timings = array();
		$current_date = $user_array['date'];
		$nameOfDay = date('N', strtotime($current_date));
		
		if($nameOfDay == "7"){
			$nameOfDay = "0";
		}
		$selected_date = date('d-m-Y', strtotime($current_date));
        $doctor =  Doctors::select(["opd_timings","slot_duration","user_id"])->Where(['delete_status'=>1,'status'=>1])->where(['id'=> $user_array['doc_id']])->orderBy("id")->first();

		if(!empty($doctor)) {
			$opd_time = array();
			$increment = 900;
			if(!empty($doctor->slot_duration)){
				$increment = $doctor->slot_duration*60;
			}
			$time_slot = array();
			if(!empty($doctor->opd_timings)){
				foreach(json_decode($doctor->opd_timings) as $key=>$schedule){
					if(!empty($schedule->days) && in_array($nameOfDay,$schedule->days)) {
						foreach($schedule->timings as $k=>$v) {
							if($user_array['type'] == "1" && isset($v->teleconsultation) &&  $v->teleconsultation == "1") {
								if(!empty($v->tele_appt_duration)){
									$increment = $v->tele_appt_duration*60;
								}
								$startTime = strtotime($v->start_time);
								while($startTime <= strtotime($v->end_time)) {
								  $time_slot[] = $startTime;
								  $startTime += $increment;
								}	
							}
							else if($user_array['type'] == "2" && isset($v->teleconsultation) &&  $v->teleconsultation == "0") {
								$startTime = strtotime($v->start_time);
								while($startTime <= strtotime($v->end_time)) {
								  $time_slot[] = $startTime;
								  $startTime += $increment;
								}
							}
						}
					}
				}
			}
				
			$from = "";
			$slot_array = array();
			// $arr = [];
			if(count($time_slot)>0){
				foreach($time_slot as $k=>$val){
					$from = date('Y-m-d H:i:s',strtotime($selected_date." ".date("h:i A",$val)));
					if($val < strtotime('12:00')) {
						if(strtotime($selected_date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
							if(checkAppointmentAvailable($from,base64_encode($doctor->user_id))) {
								$val = array("time"=>date("h:i A",$val),"book"=>"1");
							}
							else{
								$val = array("time"=>date("h:i A",$val),"book"=>"0");
							}
						}
						else{
							$val = array("time"=>date("h:i A",$val),"book"=>"1");
						}
						$slot_array["M"][] = $val;
					}
					if($val >= strtotime('12:00') && $val < strtotime('16:00')) {
						if(strtotime($selected_date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
							if(checkAppointmentAvailable($from,base64_encode($doctor->user_id))) {
								$val = array("time"=>date("h:i A",$val),"book"=>"1");
							}
							else{
								$val = array("time"=>date("h:i A",$val),"book"=>"0");
							}
						}
						else{
							$val = array("time"=>date("h:i A",$val),"book"=>"1");
						}
						$slot_array["A"][] = $val;
					}
					if($val >= strtotime('16:00') && $val < strtotime('24:00')) {
						if(strtotime($selected_date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
							if(checkAppointmentAvailable($from,base64_encode($doctor->user_id))) {
								$val = array("time"=>date("h:i A",$val),"book"=>"1");
							}
							else{
								$val = array("time"=>date("h:i A",$val),"book"=>"0");
							}	
						}
						else{
							$val = array("time"=>date("h:i A",$val),"book"=>"1");
						}
						$slot_array["E"][] = $val;
					}
				}
			}
			$doctor['timing_slots'] = $slot_array;
			return $this->sendResponse($doctor,'Doctor Timing slots.',true);
		}
		else{
			return $this->sendError('Slots does not exist');
		}
	}
	public function getDoctorCounsultMaxFees(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$success = false;
		$fee =  Doctors::Where(['delete_status'=>1,'status'=>1])->orderBy("consultation_fees","DESC")->first()->consultation_fees;
		if(!empty($fee)) {
			return $this->sendResponse($fee,'',true);
		}
		else{
			$fee = "0";
			return $this->sendResponse($fee,'',true);
		}
	}

	public function getDoctorSlotsByDay(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['doc_id'] = $data->get('doc_id');
		$user_array['day'] = $data->get('day');
		$doctors = [];
		$success = false;
		$opd_timings = array();

		$nameOfDay = $user_array['day'];
		$doctor =  Doctors::select(["opd_timings","slot_duration"])->Where(['delete_status'=>1,'status'=>1])->where(['id'=> $user_array['doc_id']])->orderBy("id")->first();
		if(!empty($doctor)) {

				$opd_time = array();
				if(!empty($doctor->opd_timings)){
					$opd_timings[] = json_decode($doctor->opd_timings,true);
					if(count($opd_timings)) {
						foreach($opd_timings as $opd){
							foreach($opd as $optime){
								if(!empty($optime['days'])){
									for($i = 0; $i<count($optime['days']);$i++){
										$mytimings = array();
										foreach($optime['timings'] as $time){
											$time['start_time'] = date('g:i A',strtotime($time['start_time']));
											$time['end_time'] = date('g:i A',strtotime($time['end_time']));
											$mytimings[] = $time;
										}
										$opd_time[getDaysByNumber($optime['days'][$i])] = (array) $mytimings;
										$nameOfDay = date('N');
										if($nameOfDay == "7"){
											$nameOfDay = "0";
										}
										if($nameOfDay == $optime['days'][$i]){
											$opd_time["today"] = (array) $mytimings;
										}
									}
								}
							}
						}
					}
					$doctor['opd_timings'] = $opd_time;
				}

				$increment = 900;
				if(!empty($doctor->slot_duration)){
					$increment = $doctor->slot_duration*60;
				}
				$time_slot = array();
				if(isset($opd_time['slot'])){
					foreach($opd_time['slot'] as $time_already){
						$time_slot[] = $this->selectTimesBySlot($time_already['start_time'],$time_already['end_time'],$increment);
					}
				}

				$slot_array = array();
				if(count($time_slot)>0){
					foreach($time_slot as $slot){
						foreach($slot as $i => $ss){

							$ss = array("time"=>$ss,"book"=>"1");
							$time_type = date('H',strtotime($slot[$i]));
							if($time_type < "12"){
								$slot_array["M"][] = $ss;
							}
							else if($time_type >= "12" && $time_type < "16"){
								$slot_array["A"][] = $ss;
							}
							else if($time_type >= "16" && $time_type <= "24"){
								$slot_array["E"][] = $ss;
							}
							// else{
								// $slot_array["N"][] = $ss;
							// }
						}
					}
				}
			$doctor['timing_slots'] = $slot_array;
			$doctor['doc_rating'] = 0;
			// if(isset($doctor->DoctorRatingReviews)) {
				// if(count($doctor->DoctorRatingReviews) > 0) {
					// $rating_val = 0;
					// $rating_count = 0;
					// foreach($doctor->DoctorRatingReviews as $rating) {
						// $rating_val += $rating->rating;
						// $rating_count++;
					// }
					// if($rating_val > 0){
						// $rating_val = round($rating_val/$rating_count,1);
					// }
					// $doctor['doc_rating'] = $rating_val;
				// }
			// }
			return $this->sendResponse($doctor,'Doctor Timing slots.',true);
		}
		else{
			return $this->sendError('Slots does not exist');
		}
	}

	public function getDocByPractice(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['clinic_name'] = $data->get('clinic_name');
		$user_array['practice_id'] = $data->get('practice_id');
		$user_array['user_id'] = $data->get('user_id');
		$doctors = "";
		if(!empty($user_array['practice_id'])){
			/** Specialty name **/
			$doctors = Doctors::Where(['delete_status'=>1,'status'=>1])->where(['practice_id'=> $user_array['practice_id']])->get();
			if(count($doctors) > 0 ) {
				$doctors = dataSequenceChange($doctors);
				$doctors = $this->bindDocData($doctors);
			}
		}
		return $this->sendResponse($doctors, '',true);
	}
	
	public function checkAppointmentStatus(Request $request){
		$data=Input::json();
  		$user_array=array();
		$user_array['appointment_id'] = $data->get('appointment_id');
		$user_array['p_id'] = $data->get('p_id');
		$success = false;
		$appointment = "";
		if(!empty($user_array['appointment_id'])){
			$appointment = Appointments::select("appointment_confirmation")->where(['id'=>$user_array['appointment_id'],"appointment_confirmation"=>1])->count();
			if($appointment > 0 ) {
				$success = true;
			}
		}
		return $this->sendResponse('', '',$success);
	}
	
	public function checkFirstTeleAppointment(Request $request){
		$data=Input::json();
  		$user_array=array();
		$user_array['pId'] = $data->get('pId');
		$user_array['fee'] = $data->get('fee');
		//$success = false;
		$success = true;
		$appointment = "";
		$fee = getSetting("tele_first_appt_price_free")[0];
		/* if(!empty($user_array['pId'])){
			$appointment = Appointments::where(['pId'=>$user_array['pId'],"delete_status"=>1,"appointment_confirmation"=>1,"type"=>3])->count();
			if($appointment > 0  || $user_array['fee'] > $fee) {
				$success = true;
			}
		}
		else if($user_array['fee'] > $fee) {
			$success = true;
		} */
		return $this->sendResponse($fee, '',$success);
	}
	
public function checkFirstDirectTeleAppointment(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['mobile_no'] = $data->get('mobile_no');
		$user_array['user_id'] = $data->get('user_id');
		$success = false;
		$main_price = getSetting("tele_main_price")[0];
		$fee = getSetting("direct_tele_appt_fee")[0];
		$available = 0;
		$is_peak = 0;
		$is_subscribedKey = 0;
		if(!empty($user_array['mobile_no'])) {
			$p_ids = User::select("pId")->where(["mobile_no"=>$user_array['mobile_no']])->pluck("pId")->toArray();
			$appointment = Appointments::whereIn('pID',$p_ids)->where(["delete_status"=>1,"appointment_confirmation"=>1,"type"=>3])->count();
			if($appointment > 0 ) {
				$success = false;
				if(isset($user_array['user_id'])) { 
					$dt = date('Y-m-d');
					$is_subscribed = PlanPeriods::select('id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where('user_id', $user_array['user_id'])->where('remaining_appointment', '!=', '0')->where('status', '1')->count();	
					if($is_subscribed > 0){
						$is_subscribedKey = 1;
						$success = true;
					}
				}
			}else{
				$success = true;
				if(isset($user_array['user_id'])) { 
					$dt = date('Y-m-d');
					$is_subscribed = PlanPeriods::select('id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where('user_id', $user_array['user_id'])->where('remaining_appointment', '!=', '0')->where('status', '1')->count();	
					if($is_subscribed > 0){
						$is_subscribedKey = 1;
						
					}
				}
			}
		}
		$times = getSetting("direct_appt_schedule_time");
		if(isset($times[0])) {
			$time = explode("-",$times[0]);
			if($time[0] <= date("H") &&  date("H") < $time[1]) {
				$available = 1;
			}
		}
		if(isset($times[1])) {
			$days = explode("-",$times[1]);
			if(count($days) > 0){
				if(!in_array(date('N'),$days)){
					$available = 0;
				}
			}
		}	
		/***    For Is peak condition..***/
		 // if("22" <= date("H") ||  date("H") < "10") {
			 // $is_peak = 1;
			 // $main_price = getSetting("peak_hour_price")[0];
			 // $success = false;
			 // if($is_subscribedKey == 1){
                  // $is_peak = 0;
                  // $success = true;
			 // }
		 // }
		$lab = LabOrders::select("is_free_appt")->where(["user_id"=>$user_array['user_id'],"status"=>1,"is_free_appt"=>1])->first();
		if(!empty($lab) && $is_peak == 0){
			$success = true;
		}
		$arr = ["fee"=>$fee,"main_price"=>$main_price,"available"=>$available,"is_subscribed"=>$is_subscribedKey,"is_peak"=>$is_peak];
		return $this->sendResponse($arr, '',$success);
	}

	public function staticPages(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['slug'] = $data->get('slug');
		$user_array['lng'] = $data->get('lng');
		$user_array['_from'] = $data->get('_from');
		if($user_array['_from'] == '1') {
			if(!empty($user_array['lng'])) {
				$page = DB::table('pages')->where(["status"=>1,'slug'=>'direct-appointment-app-vaani-old'])->where("lng",$user_array['lng'])->first();
			}
			if(empty($page)) {
				$page = DB::table('pages')->where(["status"=>1,'slug'=>'direct-appointment-app-vaani-old'])->where("lng","en")->first();
			}
		}
		else {
			if(!empty($user_array['lng'])) {
				$page = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug']])->where("lng",$user_array['lng'])->first();
			}
			if(empty($page)) {
				$page = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug']])->where("lng","en")->first();
			}
		}
		$success = false;
		if(!empty($page)) {
			$success = true;
		}
		return $this->sendResponse($page, '',$success);
    }

	public function moveTo1MgSite(Request $request) {
		$data=Input::json();

  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['email'] = $data->get('email');
		$user_array['name'] = $data->get('name');

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.1mg.com/webservices/merchants/generate-merchant-hash");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		$user_id = $user_array['user_id'];
		$email = $user_array['email'];
		$name = $user_array['name'];
		$datas = array(
			'api_key' => 'cadc8dff-c1a8-4bf9-9a1f-cbb1d8ea8ed9',
			'user_id' => $user_id,
			'email' => $email,
			'name' => $name,
			'redirect_url' => 'https://www.1mg.com/',
			'source' => 'health_gennie'
		);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $datas);
		$output = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);
		$hashs = json_decode($output)->hash;
		$data_link = 'https://www.1mg.com?_source=health_gennie&merchant_token='.$hashs;
		return $this->sendResponse($data_link, '',true);
    }
	
	public function getSponseredDoc(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['city_id'] = $data->get('city_id');
		$doctors = "";
		$success = false;
		if(!empty($user_array['city_id'])) {
			/** Specialty name **/
			  $s_city_id  = $user_array['city_id'];
			  $dt = date('Y-m-d');
			  $doctors = Doctors::with(['ManageSponsored'])->WhereHas("ManageSponsored",function($qry) use($s_city_id,$dt) {
				$qry->whereRaw('FIND_IN_SET(?,city_ids)', [$s_city_id])->whereRaw('"'.$dt.'" between `start_date` and `end_date`')->where('status',1);
			  })->where(["delete_status"=>1,"sponsored_status"=>1])->groupBy('clinic_name')->first();
			  
			  if(!empty($doctors)) {
				$doctors = $this->bindDocDataByUnique($doctors);
				$doctors['contact_no_support'] = 8302072136;
				$success = true;
			  }
			  
		}
		return $this->sendResponse($doctors, '',$success);
	}
	
	
	public function getDocDetailById(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['doc_id'] = $data->get('doc_id');
		$value = [];
		$success = false;
		$opd_timings = array();
		if(!empty($user_array['doc_id'])) {
			$value = Doctors::with('DoctorRatingReviews')->Where(['delete_status'=>1])->where(["id"=>$user_array['doc_id']])->first();
		}
		// $complement_master = ComplimentsMaster::orderBy('id')->get();
		if(!empty($value)>0) {
			$doc_paths = json_decode($value->urls);
			if(!empty($value->profile_pic)){
				$image_url = getPath("public/doctor/ProfilePics/".$value->profile_pic);
				$value['profile_pic'] = $image_url;
			}

			if(!empty($value->clinic_image)){
				$image_url = getPath("public/doctor/".$value->clinic_image);
				$value['clinic_image'] = $image_url;
			}
			if(!empty($value->speciality)){
				$value->speciality =array("id"=>$value->speciality, "name"=>getSpecialityName($value->speciality),"spaciality_hindi"=>getSpecialityHindiName($value->speciality));
			}
			else{
				$value->speciality = (object) array();
			}

			if(!empty($value->country_id)){
			  $value->country_id = array("id"=>$value->country_id,"name"=>getCountrieName($value->country_id));
			}

			if(!empty($value->state_id)){
			  $value->state_id = array("id"=>$value->state_id,"name"=>getStateName($value->state_id));
			}
			if(!empty($value->city_id)){
			  $value->city_id = array("id"=>$value->city_id,"name"=>getCityName($value->city_id));
			}
			if(!empty($value->locality_id)){
			  $value['locality_id'] = array("id"=>$value->locality_id,"name"=>getLocalityName($value->locality_id));
			}
			$opd_time = array();
			if(!empty($value->opd_timings)){
				$opd_timings[] = json_decode($value->opd_timings,true);
				if(count($opd_timings)) {
					foreach($opd_timings as $opd){
						foreach($opd as $optime){
							if(!empty($optime['days'])){
								for($i = 0; $i<count($optime['days']);$i++){
									$mytimings = array();
									foreach($optime['timings'] as $time){
										$time['start_time'] = date('g:i A',strtotime($time['start_time']));
										$time['end_time'] = date('g:i A',strtotime($time['end_time']));
										$mytimings[] = $time;
									}
									$opd_time[getDaysByNumber($optime['days'][$i])] = (array) $mytimings;
									$nameOfDay = date('N');
									if($nameOfDay == "7"){
										$nameOfDay = "0";
									}
									if($nameOfDay == $optime['days'][$i]){
										$opd_time["today"] = (array) $mytimings;
									}
								}
							}
						}
					}
				}
				$value['opd_timings'] = $opd_time;
			}

			$increment = 900;
			if(!empty($value->slot_duration)){
				$increment = $value->slot_duration*60;
			}
			$time_slot = array();
			if(isset($opd_time['today'])){
				foreach($opd_time['today'] as $time_already){
					$time_slot[] = $this->selectTimesBySlot($time_already['start_time'],$time_already['end_time'],$increment);
				}
			}
			$value['doc_rating'] = 0;
			if(isset($value->DoctorRatingReviews)) {
				if(count($value->DoctorRatingReviews) > 0) {
					$rating_val = 0;
					$rating_count = 0;
					foreach($value->DoctorRatingReviews as $rating) {
						$rating_val += $rating->rating;
						$rating_count++;
						$rating['user_name'] = @$rating->user->first_name." ".@$rating->user->last_name;
						$suggestion_array = array(); 
						$rating["suggestion_array"] = array(); 
						if(!empty($rating->suggestions)) {
							$suggestions = explode(",",$rating->suggestions); 
							if(count($suggestions) > 0){
								foreach($suggestions as $sugs){
									$suggestion_array[] = array("id"=>$sugs,'name'=>getComplimentName($sugs));	
								}
							}
							$rating["suggestion_array"] = $suggestion_array;
						} 
					}
					if($rating_val > 0){
						$rating_val = round($rating_val/$rating_count,1);
					}
					$value['doc_rating'] = $rating_val;
					$value['varify_status'] = 0;
				}
			}
			$success = true;
		}
		return $this->sendResponse($value, '',$success);
	}
	
	public function getHospitalInfoById(Request $request) {
		$data = Input::json();
		$user_array = array();

		$user_array['id'] = $data->get('id');
		$user_array['name'] = $data->get('name');

		$validator = Validator::make($user_array, [
			'id' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$success = false;
			$hosInfo = [];
			$infoDoctors = "";
			$speciality_arr = [];
			$id = base64_decode($request->input('id'));
			$docData = Doctors::with(["docSpeciality"])->Where(['id'=>$user_array['id']])->first();
			if(!empty($docData)) {
				if(!empty($docData->practice_id)) {
					$infoDoctors = Doctors::with(["docSpeciality","DoctorRatingReviews"])->Where(['practice_id'=>$docData->practice_id])->where(['delete_status'=>1])->get();
				}
				else {
					$infoDoctors = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where('clinic_name', 'like', '%'.$user_array['name'].'%')->where(['delete_status'=>1,"status"=>1])->get();
				}
				// $docData->DoctorRatingReviews = [];
				$docData->DoctorRatingReviews = getDoctorRatingByHospital($docData->practice_id,$docData->clinic_name);
				
				$hosInfo = $this->bindDocDataByUnique($docData);
				if(!empty($infoDoctors)){
					foreach($infoDoctors as $info){
						if(!empty($info->docSpeciality)){ 
							if(!in_array($info->docSpeciality->specialities,$speciality_arr)){
								$speciality_arr[] = $info->docSpeciality->specialities;
							}
						}
					}
				}
				$success = true;
			}
			$hosInfo['speciality_services'] = $speciality_arr;
			$hosInfo['doctors'] = "";
			if(!empty($infoDoctors)) {
				$hosInfo['doctors'] = $this->bindDocData($infoDoctors);
			}
			return $this->sendResponse($hosInfo, '',$success);
		}
	}
	
	public function checkVersionOfApp(Request $request) {
		$data = Input::json();
		$xml=simplexml_load_file("new-app-update.xml") or die("Error: Cannot create object");
		if($data->get('type') == 'android'){
            return $this->sendResponse($xml->version, '',true);   
		}else{
            return $this->sendResponse($xml->iosversion, '',true);
		}
	}
	public function notifyDoctorForVcall(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['user_id'] = $data->get("user_id");
		$validator = Validator::make($user_array, [
			'user_id' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$notificationKeyDoc = "AAAAIC4y15c:APA91bEL_YYIe4KZOcC-_HogaAC80aabtIxTDBGYMExAdzAVcyEqQhEvfdrDuxE8mFe7bgrE44l3SdIpDNyOZvbonOuhfSV91Z0AtQa8R_YYNajL9YpF62Xc0AWuwOqaZTuiabxqtdCA";
			$notifyres = "";
			$message = 'User joined successfully';
			$title = 'Accepted';
			$subtitle = 'Video call';
			$tickerText = 'text here...';
			$today_date = date("Y-m-d");
			$current_time = date("H:i");
			$user = ehrUser::where(['id'=>$user_array['user_id']])->first();
			$fcm_token = $user->fcm_token;
			$device_id = $user->device_id;
			if($device_id == 1 && !empty($fcm_token)) { 
				$notifyres = $this->pn($notificationKeyDoc,$fcm_token,$message,$title,$subtitle,$tickerText,'video');
			}
			else if($device_id == 2 && !empty($fcm_token)) { 
				$notifyres = $this->iosNotificationSend($fcm_token,$message,$title,'','video');
			}
			
			return $this->sendResponse($notifyres,'Notification sent successfully....',true);
		}
	}
	public function makeFollowUpAppt(Request $request){
		$data = Input::json();
		$patientInfo = $data->get("data");
		$user_array['order_by']   = $patientInfo['order_by'];
		$docData = Doctors::select(["id","user_id","practice_id","consultation_fees","oncall_fee","slot_duration","first_name","last_name"])->where(['user_id'=>$patientInfo['doc_id']])->first();
		$user_array['doc_id']   =  $docData->id;
        $user_array['doc_name']   = $docData->first_name." ".$docData->last_name;
        $user_array['p_id']   = $patientInfo['p_id'];
        $user_array['visit_type'] = 1;
        $user_array['blood_group'] = NULL;
        $user_array['consultation_fees'] = 0;
        $user_array['appointment_date'] = date("Y-m-d");
		$start_date = date("Y-m-d H:i:s");
		$increment_time = $docData->slot_duration*60;
		$user_array['time'] = checkAvailableSlot($start_date,$docData->user_id,$increment_time);
        $user_array['slot_duration'] = $docData->slot_duration;
        $user_array['onCallStatus'] = 1;
        $user_array['isFirstTeleAppointment'] = 1;
        $user_array['isDirectAppt'] = 1;
		$service_charge = 0;
        $user_array['service_charge'] = $service_charge;
        $user_array['is_subscribed'] = 1;
        $user_array['gender'] = $patientInfo['gender'];
        $user_array['patient_name'] = $patientInfo['patient_name'];
		$user_array['dob'] = date("d-m-Y",$patientInfo['dob']);
        $user_array['mobile_no'] = $patientInfo['mobile_no'];
        $user_array['other_mobile_no'] = $patientInfo['other_mobile_no'];
        $user_array['otherPatient'] = $patientInfo['otherPatient'];
        $user_array['coupon_id'] = $patientInfo['coupon_id'];
        $user_array['coupon_discount'] = $patientInfo['coupon_discount'];
        $user_array['call_type'] = 1;
        $user_array['referral_code'] = null;
        $user_array['is_peak'] = 0;
        $user_array['finalConsultaionFee'] = $user_array['consultation_fees'];
        $user_array['isfollowup'] = 1;
        $user_array['apptId'] = $patientInfo['apptId'];
        $user_array['patient_id'] = $patientInfo['patient_id'];
		$charge =  0;
		$tax =  0;
		$gst =  0;
		$service_charge_meta = ["service_charge_rupee"=>$charge,"tax_in_percent"=>$tax,"gst"=>$gst];
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
		$newApptId = Parent::putAppointmentDataApp($order,'','');
		$appointment_id = $user_array['apptId'];
		$patient_id = $user_array['patient_id'];
		
		$checkChief = ChiefComplaints::where(['appointment_id'=>$appointment_id])->first();
		if(!empty($checkChief) > 0) {
			ChiefComplaints::create([
			   'appointment_id' =>  $newApptId,
			   'pId' =>  $patient_id,
			   'data'=>  $checkChief->data
			]);
		}
		$medData = PatientMedications::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($medData) > 0){
			$order = MedicineOrders::create([
				'appointment_id'=>$newApptId,
				'patient_id'=>$patient_id,
				'order_by' => $docData->user_id,
				'doctor_type' => 1,
				'practice_id' => $docData->practice_id,
			]);
			$orderId = $order->id;
			foreach($medData as $med) {
				PatientMedications::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$med->patient_id,
					'drug_id' => $med->drug_id,
					'strength' => $med->strength,
					'unit' => $med->unit,
					'frequency' =>$med->frequency,
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
		$labs = PatientLabs::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($labs) > 0 ) {
			$order = LabOrderEhr::create([
			   'patient_id'=>$patient_id,
			   'order_by' => $docData->user_id,
			   'doctor_type' => 1,
			   'practice_id' => $docData->practice_id,
			]);
			$orderId = $order->id;
			foreach($labs as $lab) {
				$patientLabs = PatientLabs::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$lab->patient_id,
					'pack_status' => 1,
					'pack_id' => $lab->pack_id,
					'lab_id' => $lab->lab_id,
					'instructions' => $lab->instructions,
					'order_id' => $orderId,
					'added_by' => $lab->added_by,
				]);
				$subLabs = PatientSubLabs::where(['parent_id'=>$lab->id,'delete_status'=>1])->get();
				if(count($subLabs)){
					foreach($subLabs as $raw){
						PatientSubLabs::create([
						 'parent_id'=>$patientLabs->id,
						 'lab_id' => $raw->lab_id,
						 'sub_lab_id' => $raw->sub_lab_id,
						 'order_id' => $orderId,
						 'added_by' => $raw->added_by
						]);
					}
				}
			}
		}
		$allergy = PatientAllergy::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($allergy)>0) {
			foreach($allergy as $raw){
				PatientAllergy::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$raw->patient_id,
					'allergy_type' => $raw->allergy_type,
					'allergy_id' => $raw->allergy_id,
					'allergy_reactions' => $raw->allergy_reactions,
					'severity' => $raw->severity,
					'notes' => $raw->notes,
					'added_by' => $raw->added_by
				 ]);
			}
		}
		$procedure = PatientProcedures::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($procedure)>0){
			foreach($procedure as $raw){
				PatientProcedures::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$raw->patient_id,
					'procedure_type' => $raw->procedure_type,
					'procedure_id' => $raw->procedure_id,
					'notes' => $raw->notes,
					'added_by' => $raw->added_by
				]);
			}
		}
		$diagnosis = PatientDiagnosis::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		foreach($diagnosis as $diagno){
			PatientDiagnosis::create([
				'appointment_id'=>$newApptId,
				'patient_id'=>$diagno->patient_id,
				'diagnosis_id' => $diagno->diagnosis_id,
				'notes' => $diagno->notes,
				'added_by' => $diagno->added_by
			]);
		}
		$diImaging = PatientDiagnosticImagings::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($diImaging)>0){
			$order = RadiologyOrders::create([
			   'patient_id'=>$patient_id,
			   'order_by' => $docData->user_id,
			   'doctor_type' => 1,
			   'practice_id' => $docData->practice_id,
			]);
			foreach($diImaging as $raw){
				PatientDiagnosticImagings::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$raw->patient_id,
					'lab_id' => $raw->lab_id,
					'order_id' => $order->id,
					'instructions' => $raw->instructions,
				]);
			}
		}
		$advice = PatientAdvice::where(['appointment_id'=>$appointment_id])->first();
		if(!empty($advice)) {
			PatientAdvice::create([
			   'appointment_id' =>  $newApptId,
			   'pId' =>  $advice->patient_id,
			   'data'=>  $advice->data,
			   'added_by' => $advice->practice_id
			]);
		}
		$pVitals = PatientVitalss::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->first();
		if(!empty($pVitals)){
			PatientVitalss::create([
				'appointment_id'=>$newApptId,
				'patient_id'=>$pVitals->patient_id,
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
				'random_blood_sugar'=> $pVitals->random_blood_sugar,
				'fasting_blood_sugar'=> $pVitals->fasting_blood_sugar,
				'temperature_f'=> $pVitals->temperature_f,
				'notes' => $pVitals->notes,
				'added_by' => $pVitals->added_by,
			]);
		}
		$examinations = PatientExaminations::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($examinations) > 0) {
			foreach($examinations as $raw) {
				PatientExaminations::create([
				 'appointment_id'=>$newApptId,
				 'patient_id'=>$raw->patient_id,
				 'bodySite_id' => $raw->bodySite_id,
				 'le_observation' => $raw->le_observation,
				 're_observation' => $raw->re_observation,
				 'added_by' => $raw->added_by
				]);
			}
		}
		$immunization = PatientImmunizations::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($immunization)>0) {
			foreach($immunization as $raw){
				PatientImmunizations::create([
				   'appointment_id'=>$newApptId,
				   'patient_id'=>$raw->patient_id,
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
        $pReferral = PatientReferrals::where(['appointment_id'=>$appointment_id,'delete_status'=>1])->first();
		if(!empty($pReferral) > 0) {
			PatientReferrals::create([
				'appointment_id'=>$newApptId,
				'patient_id'=>$added_by->patient_id,
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
        $dentals = PatientDentals::where(['appointment_id'=>$appointment_id,'delete_status'=>1])->get();
		if(count($dentals)>0){
			foreach($dentals as $raw){
				PatientDentals::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$raw->patient_id,
					'dental_id' => $raw->dental_id,
					'dental_procedure' => $raw->dental_procedure,
					'dental_note' => $raw->dental_note,
					'added_by' => $raw->added_by
				]);
			}
		}
        $eyes = PatientEyes::where(['appointment_id'=>$appointment_id,'delete_status'=>1])->first();
		if(!empty($eyes)){
			PatientEyes::create([
			  'appointment_id'=> $newApptId,
			  'patient_id'=> $eyes->patient_id,
			  'va_check'=> $eyes->va_check,
			  'r_va_h'=>$eyes->r_va_h,
			  'r_va_m'=>$eyes->r_va_m,
			  'l_va_h'=>$eyes->l_va_h,
			  'l_va_m'=>$eyes->l_va_m,
			  'bcva_check'=>$eyes->bcva_check,
			  'r_bcva'=>$eyes->r_bcva,
			  'l_bcva'=>$eyes->l_bcva,
			  'iop_check'=>$eyes->iop_check,
			  'r_iop'=>$eyes->r_iop,
			  'r_iop_other'=>$eyes->r_iop_other,
			  'l_iop'=>$eyes->l_iop,
			  'l_iop_other'=>$eyes->l_iop_other,
			  'ar_check'=>$eyes->ar_check,
			  'r_ar'=>$eyes->r_ar,
			  'r_ar_input1'=>$eyes->r_ar_input1,
			  'r_ar_input2'=>$eyes->r_ar_input2,
			  'l_ar'=>$eyes->l_ar,
			  'l_ar_input1'=>$eyes->l_ar_input1,
			  'l_ar_input2'=>$eyes->l_ar_input2,
			  'dilar_check'=>$eyes->dilar_check,
			  'r_dilar_input1'=>$eyes->r_dilar_input1,
			  'r_dilar_input2'=>$eyes->r_dilar_input2,
			  'r_dilar_input3'=>$eyes->r_dilar_input3,
			  'r_dilar_input4'=>$eyes->r_dilar_input4,
			  'l_dilar_input1'=>$eyes->l_dilar_input1,
			  'l_dilar_input2'=>$eyes->l_dilar_input2,
			  'l_dilar_input3'=>$eyes->l_dilar_input3,
			  'l_dilar_input4'=>$eyes->l_dilar_input4,
			  'k1k2_check'=>$eyes->k1k2_check,
			  'r_k1k2_text'=>$eyes->r_k1k2_text,
			  'r_k1k2_axis'=>$eyes->r_k1k2_axis,
			  'l_k1k2_text'=>$eyes->l_k1k2_text,
			  'l_k1k2_axis'=>$eyes->l_k1k2_axis,
			  'axl_check'=>$eyes->axl_check,
			  'r_axl'=>$eyes->r_axl,
			  'l_axl'=>$eyes->l_axl,
			  'iol_check'=>$eyes->iol_check,
			  'r_iol'=>$eyes->r_iol,
			  'l_iol'=>$eyes->l_iol,
			  'syringing_check'=>$eyes->syringing_check,
			  'r_syringing'=>$eyes->r_syringing,
			  'l_syringing'=>$eyes->l_syringing,
			  'color_vision_check'=>$eyes->color_vision_check,
			  'r_color_vision_text'=>$eyes->r_color_vision_text,
			  'r_color_vision_type'=>$eyes->r_color_vision_type,
			  'l_color_vision_text'=>$eyes->l_color_vision_text,
			  'l_color_vision_type'=>$eyes->l_color_vision_type,
			  'pgp_check'=>$eyes->pgp_check,
			  'r_pgp'=>$eyes->r_pgp,
			  'r_pgp_shp'=>$eyes->r_pgp_shp,
			  'r_pgp_cg'=>$eyes->r_pgp_cg,
			  'r_pgp_axis'=>$eyes->r_pgp_axis,
			  'l_pgp'=>$eyes->l_pgp,
			  'l_pgp_shp'=>$eyes->l_pgp_shp,
			  'l_pgp_cg'=>$eyes->l_pgp_cg,
			  'l_pgp_axis'=>$eyes->l_pgp_axis,
			  'r_pgp_b'=>$eyes->r_pgp_b,
			  'r_pgp_shp_b'=>$eyes->r_pgp_shp_b,
			  'r_pgp_cg_b'=>$eyes->r_pgp_cg_b,
			  'r_pgp_axis_b'=>$eyes->r_pgp_axis_b,
			  'l_pgp_b'=>$eyes->l_pgp_b,
			  'l_pgp_shp_b'=>$eyes->l_pgp_shp_b,
			  'l_pgp_cg_b'=>$eyes->l_pgp_cg_b,
			  'l_pgp_axis_b'=>$eyes->l_pgp_axis_b,
			  'retinoscopy_check'=>$eyes->retinoscopy_check,
			  'r_ratinoscopy'=>$eyes->r_ratinoscopy,
			  'r_ar_sph_sign'=>$eyes->r_ar_sph_sign,
			  'r_ar_cyl_sign'=>$eyes->r_ar_cyl_sign,
			  'r_dil_sph_sign'=>$eyes->r_dil_sph_sign,
			  'r_dil_cyl_sign'=>$eyes->r_dil_cyl_sign,
			  'r_ogp_sph_sign'=>$eyes->r_ogp_sph_sign,
			  'r_ogp_cyl_sign'=>$eyes->r_ogp_cyl_sign,
			  'r_ogp_sph_sign2'=>$eyes->r_ogp_sph_sign2,
			  'r_ogp_cyl_sign2'=>$eyes->r_ogp_cyl_sign2,
			  'l_ar_sph_sign'=>$eyes->l_ar_sph_sign,
			  'l_ar_cyl_sign'=>$eyes->l_ar_cyl_sign,
			  'l_dil_sph_sign'=>$eyes->l_dil_sph_sign,
			  'l_dil_cyl_sign'=>$eyes->l_dil_cyl_sign,
			  'l_ogp_sph_sign'=>$eyes->l_ogp_sph_sign,
			  'l_ogp_cyl_sign'=>$eyes->l_ogp_cyl_sign,
			  'l_ogp_sph_sign2'=>$eyes->l_ogp_sph_sign2,
			  'l_ogp_cyl_sign2'=>$eyes->l_ogp_cyl_sign2,
			  'added_by' => $eyes->added_by
			]);
		}
		$nutritional_info = Nutritionalinfo::where(['appointment_id'=>$appointment_id,'delete_status'=>1])->first();
		if(!empty($nutritional_info)){
			Nutritionalinfo::create([
			   'appointment_id' =>  $newApptId,
			   'patient_id' =>  $nutritional_info->patient_id,
			   'doc_id' =>  $nutritional_info->doc_id,
			   'eating_habits'=> $nutritional_info->eating_habits,
			   'medical_concern'=> $nutritional_info->medical_concern,
			   'disease'=> $nutritional_info->disease,
			   'disease_option'=> $nutritional_info->disease_option,
			   'medical_treatment'=> $nutritional_info->medical_treatment,
			   'medical_treatment_option'=> $nutritional_info->medical_treatment_option,
			   'allergy'=> $nutritional_info->allergy,
			   'physical_activity'=> $nutritional_info->physical_activity,
			   'work_schedule_from'=> $nutritional_info->work_schedule_from,
			   'life_style'=> $nutritional_info->life_style,
			   'body_type'=> $nutritional_info->body_type,
			   'energy_calories'=> $nutritional_info->energy_calories,
			   'protein'=> $nutritional_info->protein,
			   'fat'=> $nutritional_info->fat,
			   'calcium'=> $nutritional_info->calcium,
			   'added_by'=> $nutritional_info->added_by,
			   'status'=> 1,
			]);
		}
		$diet_plan = DietPlan::where(['appointment_id'=>$appointment_id,'delete_status'=>1])->get();
		if(count($diet_plan)>0){
			foreach($diet_plan as $raw){
				DietPlan::create([
				   'appointment_id' =>  $newApptId,
				   'patient_id' =>  $raw->patient_id,
				   'doc_id' =>  $raw->doc_id,
				   'no_of_meal'=> $raw->no_of_meal,
				   'meal_plan_id'=> $raw->meal_plan_id,
				   'meal_plan_time'=> $raw->meal_plan_time,
				   'menu'=> $raw->menu,
				   'menu_exchange'=> $raw->menu_exchange,
				   'ingredient'=> $raw->ingredient,
				   'added_by'=> $raw->added_by,
				   'status'=> 1,
				]);
			}
		}
        $physical_excercise = PatientPhysicalExcercise::where(['appointment_id'=>$appointment_id,'delete_status'=>1])->get();
		if(count($physical_excercise)>0){
			foreach($physical_excercise as $raw){
				PatientPhysicalExcercise::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$raw->patient_id,
					'physical_excercise_id' => $raw->physical_excercise_id,
					'instructions' => $raw->instructions,
					'added_by' => $raw->added_by
				]);
			}
		}
        $dietitian_template = PatientDietitianTemplate::where(['appointment_id'=>$appointment_id,'delete_status'=>1])->first();
		if(!empty($dietitian_template)) {
			PatientDietitianTemplate::create([
				'appointment_id'=>$newApptId,
				'patient_id'=>$raw->patient_id,
				'dietitian_temp_id' => $raw->dietitian_temp_id,
				'instructions' => $raw->instructions,
				'added_by' => $raw->added_by
			]);
		}
		$patient_diet_file = PatientDietPlanFile::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->first();
		if(!empty($patient_diet_file)){
			PatientDietPlanFile::create([
				'appointment_id' =>  $newApptId,
				'patient_id' =>  $patient_diet_file->patient_id,
				'template_name' =>  $patient_diet_file->template_name,
				'doc_id' =>  $patient_diet_file->doc_id,
				'file_name' =>  $patient_diet_file->file_name,
				'added_by'=> $patient_diet_file->added_by,
				'status'=> 1,
			]);
		}
		$patient_eom = PatientEom::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($patient_eom)>0) {
			foreach($patient_eom as $raw) {
				PatientEom::create([
				  'appointment_id' => $newApptId,
				  'patient_id' => $raw->patient_id,
				  'eom_id' => $raw->eom_id,
				  'eom_type' => $raw->eom_type,
				  'added_by' => $raw->added_by
				]);
			}
		}
        $patient_sle = PatientSle::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($patient_sle)>0){
			foreach($patient_sle as $raw) {
				PatientSle::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$raw->patient_id,
					'sle_eye' => $raw->sle_eye,
					'sle_id' => $raw->sle_id,
					'notes' => $raw->notes,
					'added_by' => $raw->added_by
				]);
			}
		}
        $patient_sys_ill = PatientSystematicIllness::where(['appointment_id'=>$appointment_id,'patient_id'=>$patient_id,'delete_status'=>1])->get();
		if(count($patient_sys_ill)>0){
			foreach($patient_sys_ill as $raw){
				PatientSystematicIllness::create([
					'appointment_id'=>$newApptId,
					'patient_id'=>$raw->patient_id,
					'notes' => $raw->notes,
					'added_by' => $raw->added_by
				]);
			}
		}
        $PatientSleCanvas = PatientSleCanvas::where(['appointment_id'=>$appointment_id])->first();
		if(!empty($PatientSleCanvas)){
			PatientSleCanvas::create([
				'appointment_id'=>$newApptId,
				'patient_id'=>$raw->patient_id,
				'canvas_img' => $raw->canvas_img,
				'added_by' => $raw->added_by
			]);
		}
        $patient_fundus = PatientFundus::where(['appointment_id'=>$appointment_id,'delete_status'=>1])->first();
		if(!empty($patient_fundus)) {
			PatientFundus::create([
				'appointment_id'=>$newApptId,
				'patient_id'=>$patient_fundus->patient_id,
				'fundus_img_check'=> $patient_fundus->fundus_img_check,
				'fundus_img_right_eye' => $patient_fundus->fundus_img_right_eye,
				'fundus_img_left_eye' => $patient_fundus->fundus_img_left_eye,
				'fundus_master_id_left' =>  $patient_fundus->fundus_master_id_left,
				'fundus_master_id_right' => $patient_fundus->fundus_master_id_right,
				'added_by' => $patient_fundus->added_by
			]);
		}
		if($patientInfo['lng'] == "hi"){
			return $this->sendResponse('','फॉलोअप अपॉइंटमेंट अनुरोध सफलतापूर्वक भेजा गया।',true);		
		}
		else{
			return $this->sendResponse('','Follow Up appointment request Successfully Sent.',true);
		}
	}
	function callRecording(Request $request) {
        if($request->isMethod('post')) {
            $data=Input::json();
            $user_array=array();
            $user_array['cname'] =$data->get('cname');
            $user_array['uid'] =$data->get('uid');
            $user_array['appId'] =$data->get('appId');
            $user_array['token'] =$data->get('token');
            $validator = Validator::make($user_array, [
                'cname' => 'required',
                'uid' => 'required',
                // 'clientRequest' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
            else{
                $cname = $user_array['cname'];
                $uid = $user_array['uid'];
                $username = "b40992b895d74aaba3baa73a3c1ca947";
                $password = "fd5ea8f897514b5f8c60fa200d51bb5b";
                $AuthSecret = base64_encode("$username:$password");
                $url = "https://api.agora.io/v1/apps/a86c9bb907644baea3271616b3aa1f16/cloud_recording/acquire";
                // Initialize cURL session
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS =>"{\n  \"cname\": \"$cname\",\n  \"uid\": \"$uid\",\n  \"clientRequest\":{\n  }\n}",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: Basic $AuthSecret"
                    ),
                ));
                // Execute cURL request
                $response = curl_exec($curl);
                $f_res = json_decode($response,true);
                $resourceId = $f_res["resourceId"];
                
                curl_close($curl);

                $appId = $user_array['appId'];
                $token = $user_array['token'];
                // Endpoint URL
                $url = "https://api.agora.io/v1/apps/$appId/cloud_recording/resourceid/$resourceId/mode/individual/start";
                $sData = json_encode([
                    "cname" => $cname,
                    "uid" => $uid,
                    "clientRequest" => [
                        "token" => $token,
                        "appsCollection" => [
                            "combinationPolicy" => "postpone_transcoding",
                        ],
                        "recordingConfig" => [
                            "channelType" => 0,
                            "maxIdleTime"=> 30,
                            "streamTypes"=> 0,
                            "streamMode"=> "original",
                            "subscribeVideoUids" => [],
                            "subscribeAudioUids" > [],
                            "subscribeUidGroup" => 0
                        ],
                        "transcodeOptions" => [
                            "container" => [
                                "format" => "m4a"
                            ],
                            "transConfig" => [
                                "transMode" => "audioMix"
                            ],
                            "audio" => [
                                "sampleRate" => "48000",
                                "bitrate" => "48000",
                                "channels" => "2"
                            ]
                        ],
                        "storageConfig" => [
                            "secretKey" => "8a67mk3QsL77EIFMKlDItxzITJkMWFY55n4denDu",
                            "vendor" => 1,
                            "region" => 14,
                            "bucket" => "healthgenniebucket",
                            "accessKey" => "AKIASTWRNNXRLWQI5AXY",
                            "fileNamePrefix" => [
                                "vdorecords"
                            ]
                        ],
                        
                    ]
                ]);
                $sCurl = curl_init();
                curl_setopt_array($sCurl, [
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 30,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS => $sData,
                    CURLOPT_HTTPHEADER => [
                        "Content-Type: application/json",
                        "Authorization: Basic $AuthSecret"
        
                    ],
                ]);
                $sResponse = curl_exec($sCurl);
                $s_res = json_decode($sResponse,true);
				if(isset($s_res)) {
					DB::table('vdo_records')->insert([
						'uid' => $uid,
						'sid' => $s_res['sid'],
						'cname' => $cname,
						'resourceId' => $resourceId,
						'appId' => $appId,
						'meta_data' => $sResponse,
					]);
				}
                return $this->sendResponse($s_res,'',true);
            }
        }
    }
    function getRecordingStatus(Request $request) {
        if($request->isMethod('post')) {
            $data=Input::json();
            $user_array=array();
            $user_array['sid'] =$data->get('sid');
            $user_array['uid'] =$data->get('uid');
            $user_array['resourceId'] =$data->get('resourceId');
            $validator = Validator::make($user_array, [
                'sid' => 'required',
                'uid' => 'required',
                // 'clientRequest' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
            else{
                $uid = $user_array['uid'];
                $sid = $user_array['sid'];
                $resourceId = $user_array['resourceId'];
                $username = "b40992b895d74aaba3baa73a3c1ca947";
                $password = "fd5ea8f897514b5f8c60fa200d51bb5b";
                $AuthSecret = base64_encode("$username:$password");
                $url = "https://api.agora.io/v1/apps/a86c9bb907644baea3271616b3aa1f16/cloud_recording/resourceid/$resourceId/sid/$sid/mode/individual/query";
                // Initialize cURL session
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json",
                        "Authorization: Basic $AuthSecret"
                    ),
                ));
                $response = curl_exec($curl);
				$ress = json_decode($response,true);	
                curl_close($curl);
				if(!empty($sid)) {
					DB::table('vdo_records')->where("sid",$sid)->update([
						'updated_m_data' => $response,
					]);
				}
                return $this->sendResponse($ress,'',true);
            }
        }
    }
    public function stopCallRecording(Request $request) {
        if($request->isMethod('post')) {
            $data=Input::json();
            $user_array=array();
            $user_array['resourceId'] =$data->get('resourceId');
            $user_array['uid'] =$data->get('uid');
            $user_array['cname'] =$data->get('cname');
            $user_array['sid'] =$data->get('sid');
            $validator = Validator::make($user_array, [
                'sid' => 'required',
                'uid' => 'required',
                'resourceId' => 'required',
                // 'clientRequest' => 'required',
            ]);
            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }
            else{
                $resourceId = $user_array['resourceId'];
                $cname = $user_array['cname'];
                $uid = $user_array['uid'];
                $sid = $user_array['sid'];
                $username = "b40992b895d74aaba3baa73a3c1ca947";
                $password = "fd5ea8f897514b5f8c60fa200d51bb5b";
                $AuthSecret = base64_encode("$username:$password");
                // Endpoint URL
                $url = "https://api.agora.io/v1/apps/a86c9bb907644baea3271616b3aa1f16/cloud_recording/resourceid/$resourceId/sid/$sid/mode/individual/stop";
                // Set up cURL options
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => $url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => "POST",
                    CURLOPT_POSTFIELDS =>"{\n  \"cname\": \"$cname\",\n  \"uid\": \"$uid\",\n  \"clientRequest\":{\n  }\n}",
                    CURLOPT_HTTPHEADER => array(
                        "Content-Type: application/json;charset=utf-8",
                        "Authorization: Basic $AuthSecret"
                    ),
                ));
                $response = curl_exec($curl);
				$ress = json_decode($response,true);
				if(!empty($sid)) {
					DB::table('vdo_records')->where("sid",$sid)->update([
						'stop_m_data' => $response,
					]);
				}
                return $this->sendResponse($ress,'',true);
            }
        }
    }
}