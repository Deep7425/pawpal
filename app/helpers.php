<?php

use App\Models\Department;
use App\Models\State;
use App\Models\City;
use App\Models\Speciality;
use App\Models\ehr\Appointments;
use App\Models\ehr\ItemType;
use App\Models\ehr\VisitTypes;
use App\Models\ehr\RoleUser;
use App\Models\ehr\VitalsPermissions;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\ManageTrailPeriods;
use App\Models\ehr\User as ehrUser;
use App\Models\TicketUser;
use App\Models\User;
use App\Models\ehr\Plans;
use App\Models\NonHgDoctors;
use App\Models\Doctors;
use App\Models\Country;
use App\Models\ehr\CityLocalities;
use App\Models\ehr\Patients;
use App\Models\NewsFeeds;
use App\Models\DoctorRatingReviews;
use App\Models\PatientFeedback;
use App\Models\ThyrocarePackageGroup;
use App\Models\CouncilingData;
use App\Models\UniversityList;
use App\Models\WaitingTimeMaster;
use App\Models\ComplimentsMaster;
use App\Models\SubSpecialities;
use App\Models\LabReports;
use App\Models\LabCart;
use App\Models\Admin\Admin;
use App\Models\Admin\Symptoms;
use App\Models\LabOrders;
use App\Models\SpecialityGroup;
use App\Models\DoctorSlug;
use App\Models\UserActivity;
use App\Models\PlanPeriods;
use App\Models\OrganizationMaster;
use App\Models\CampTitleMaster;
use App\Models\Plans as GenniePlan;
use App\Models\CampData;
use App\Models\ehr\AppointmentTxn;
use App\Models\ehr\AppointmentOrder;
use App\Models\ehr\ReferralsMaster;
use App\Models\ehr\PrintSettings;
use App\Models\ehr\PatientRagistrationNumbers;
use App\Models\ehr\DoctorsInfo;
use App\Models\Settings;
use App\Models\Pages;
use App\Models\SalesTeam;
use App\Models\ehr\AppointmentDurationMaster;
use App\Models\Coupons;
use App\Models\UsersSubscriptions;
use App\Models\CovidHelp;
use App\Models\ReferralMaster;
use App\Models\HealthQuestion;
use App\Models\UserPrescription;
use App\Models\MedicinePrescriptions;
use App\Models\ReferralCashback;
use App\Models\UsersOnlineData;
use App\Models\UserSubscriptionsTxn;
use App\Models\DefaultLabs;
use App\Models\LabCompany;
use App\Models\LabCollection;
use App\Models\ThyrocareLab;
use App\Models\LabPackage;
use App\Models\MhQuesRange;
use App\Models\MhResultType;
use App\Models\UserDetails;
use App\Models\UserWallet;
use App\Models\SupportCategory;
use App\Models\Ticket;
use App\Models\ClientName;
use App\Models\SecurityQuestion;
use Carbon\Carbon;
use App\Models\ehr\JobCategory;
use App\Models\labCalling;
use App\Models\ehr\clinicalNotePermissions;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
define('ENCRYPTION_KEY', '__^%&Q@$&*!@#$%^&*^__');

if (! function_exists('pr')) {
    function pr($string=array()) {
        echo "<pre>";
        print_r($string);
        die;
    }
}
if (!function_exists('getDocIdByDoctor')) {
    function getDocIdByDoctor($id){
       $idss = Doctors::select('id')->where('user_id',$id)->first();
	     return @$idss->id;
    }
}
if (!function_exists('docDetailsByOPDTimings')) {
    function docDetailsByOPDTimings($id){
       $doctor = Doctors::where('id',$id)->pluck('opd_timings');
	     return $doctor;
    }
}
if (!function_exists('getworkingdays')) {
    function getworkingdays($days) {
         $dayArr = array('0'=>'Sunday','1'=>'Monday','2'=>'Tuesday','3'=>'Wednesday','4'=>'Thursday','5'=>'Friday','6'=>'Saturday');
         $mydays =array();
         foreach($days as $key=>$val){
             if(array_key_exists($val,$dayArr)){
                  $mydays[] = $dayArr[$val];
             }
         }
        return implode(',',$mydays);
    }
}
if (!function_exists('getSpecialityName')) {
	function getSpecialityName($id=null){
		$specialities = Speciality::select("spaciality")->where('id',$id)->pluck("spaciality");
		$specialitie_name ='';
		if(isset($specialities[0])){
		  $specialitie_name = $specialities[0];
		}
		return $specialitie_name;
	}
}
if (!function_exists('getSpecialityHindiName')) {
	function getSpecialityHindiName($id=null){
		$specialities = Speciality::select("spaciality_hindi")->where('id',$id)->pluck("spaciality_hindi");
		$specialitie_name ='';
		if(isset($specialities[0])){
		  $specialitie_name = $specialities[0];
		}
		return $specialitie_name;
	}
}
if (!function_exists('getSpecialistName')) {
	function getSpecialistName($id=null){
		$specialities = Speciality::where('id',$id)->first();
		$specialitie_name ='';
		if(!empty($specialities)) {
		  $specialitie_name = $specialities->specialities;
		}
		return $specialitie_name;
	}
}
if (!function_exists('getSpecialityData')) {
	function getSpecialityData($id=null){
		$specialities = Speciality::where('id',$id)->first();
		$specialitie_data = [];
		if(!empty($specialities)) {
			$specialitie_data["specialities"] = $specialities->specialities;
			if(!empty($specialities->speciality_icon)) {
				$specialitie_data["speciality_icon"] = url("/")."/public/speciality-icon/".$specialities->speciality_icon;
			}
		}
		return $specialitie_data;
	}
}

if (!function_exists('getCountrieName')) {

	    function getCountrieName($id=null)
	    {
		$cName = Country::select("name")->where('id',$id)->pluck("name");
		$name ='';
		if(isset($cName[0])){
		  $name =  $cName[0];
		}
		return $name;
	    }
    }

	 if (!function_exists('getStateName')) {

	    function getStateName($id=null)
	    {
		$sName = State::select("name")->where('id',$id)->pluck("name");
		$name ='';
		if(isset($sName[0])){
		  $name =  $sName[0];
		}
		return $name;
	    }
    }
	 if (!function_exists('getStateID')) {

 	    function getStateID($id=null)
 	    {
     		$sName = City::where(['id'=>$id])->first();
     		$name ='';
     		if(!empty($sName)){
     		  $name =  $sName->state_id;
     		}
     		return $name;
 	    }
     }
    if (!function_exists('getCityName')) {

	    function getCityName($id=null)
	    {
		
		$cName = City::select("name")->where('id',$id)->pluck("name");
		$name ='';
		if(isset($cName[0])){
		  $name =  $cName[0];
		}
		return $name;
	    }
    }
	if (!function_exists('getCitySlug')) {

	    function getCitySlug($id=null)
	    {
		$cName = City::select("slug")->where(['id'=>$id])->pluck("slug");
		$name ='';
		if(isset($cName[0])){
		  $name =  $cName[0];
		}
		return $name;
	    }
    }
	if (!function_exists('getLocalityName')) {

			function getLocalityName($id=null)
			{
			$cName = CityLocalities::select("name")->where(['id'=>$id])->pluck("name");
			$name ='';
			if(isset($cName[0])){
			  $name =  $cName[0];
			}
			return $name;
			}
	}

	if (!function_exists('getDaysByNumber')) {
		function getDaysByNumber($day){
			 $dayArr = array('0'=>'SUN','1'=>'MON','2'=>'TUE','3'=>'WED','4'=>'THU','5'=>'FRI','6'=>'SAT');
			 return $dayArr[$day];
		}
	}

	if (!function_exists('getNumberByDay')) {
		function getNumberByDay($day_name){
			 $dayArr = array('Mon'=>'1','Tue'=>'2','Wed'=>'3','Thu'=>'4','Fri'=>'5','Sat'=>'6','Sun'=>'7');
			 return $dayArr[$day_name];
		}
	}
	  if (!function_exists('checkAppointmentAvailable')) { 
		function checkAppointmentAvailable($time,$doc = null) {
			if(empty($doc) && !empty(app('request')->input('doctor'))){
				$doc = app('request')->input('doctor');
			}
		   $qry = Appointments::select("id")->where(['delete_status'=>1,'start'=>$time]);
		   if($doc != null){
			   $qry->where(['doc_id'=>base64_decode($doc)]);
		   }
		   if($qry->count() > 0){
			   return true;
		   }else{
			   return false;
		   }
		}
	  }

	function get_age($birth_date){
		$date = date('Y-m-d', (int) $birth_date);
		return floor((time() - strtotime($date))/31556926);
	}

if (!function_exists('get_patient_age')) {
	function get_patient_age($birth_date){
		if(!empty($birth_date)){
			$bdate = date('Y-m-d', (int) $birth_date);
			$sdate 	=  date('Y-m-d');

			$date_diff = abs($birth_date - strtotime($sdate));
			$years = floor($date_diff / (365*60*60*24));
			$months = floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($date_diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

			$months_data = ($months)? $months." m ":"";
			if($years>0){
				return $years." y ".$months_data;
			}
			else if($months>0){
				return $months." m ";
			}
			else{
				return $days." d ";
			}
		}
		else{
			return "";
		}
	}
}

if (!function_exists('get_patient_age_api')) {
	function get_patient_age_api($birth_date){
		if(!empty($birth_date)){
			$bdate = date('Y-m-d', (int) $birth_date);
			$sdate 	=  date('Y-m-d');

			$date_diff = abs($birth_date - strtotime($sdate));
			$years = floor($date_diff / (365*60*60*24));
			$months = floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($date_diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));

			$months_data = ($months)? $months." m ":"";
			if($years>0){
				return [$years,"1"];
			}
			else if($months>0){
				return [$months,"2"];
			}
			else{
				return [$days,"3"];
			}
		}
		return null;
	}
}
if (!function_exists('get_patient_age_year')) {
	function get_patient_age_year($birth_date){
		if(!empty($birth_date)){
			$bdate = date('Y-m-d', (int) $birth_date);
			$sdate 	=  date('Y-m-d');
			$date_diff = abs($birth_date - strtotime($sdate));
			$years = floor($date_diff / (365*60*60*24));
			$months = floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($date_diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			return $years;
		}
	}
}
if (!function_exists('get_mentalH_user_age')) {
	function get_mentalH_user_age($birth_date){
		if(!empty($birth_date)){
			$bdate = date('Y-m-d', strtotime($birth_date));
			$sdate 	=  date('Y-m-d');
			$date_diff = abs(strtotime($birth_date) - strtotime($sdate));
			$years = floor($date_diff / (365*60*60*24));
			$months = floor(($date_diff - $years * 365*60*60*24) / (30*60*60*24));
			$days = floor(($date_diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
			return $years;
		}
	}
}

if (!function_exists('getItemType')) {
    function getItemType($id)
    {
	     $itemType = ItemType::where('id', $id)->first();
	     if(!empty($itemType)){
       	        $type = @$itemType->type;
	     }else{
	        $type ='';
	     }
	     return $type;
    }
}

if (!function_exists('getSpecialityList')) {

    function getSpecialityList()
    {
	$specialities = Speciality::orderBy('specialities','ASC')->where(["status"=>1,"delete_status"=>1])->get();

	return $specialities;
    }
}

if (!function_exists('getSpecialityGroupList')) {

    function getSpecialityGroupList()
    {
	$specialities = SpecialityGroup::orderBy('group_name','ASC')->where(["delete_status"=>1])->get();

	return $specialities;
    }
}

if (!function_exists('getCountriesList')) {

    function getCountriesList()
    {

	 $countries = Country::get();

         return $countries;
    }
}
if (!function_exists('getStateList')) {

    function getStateList($id)
    {
      //$id = $request->input('id');
      $states = State::where('country_id',$id)->get();

      return $states;
    }
}
if (!function_exists('getCityList')) {

    function getCityList($id)
    {

      $cities = City::where('state_id',$id)->get();


      return $cities;
    }
}
if (!function_exists('getLocalityList')) {
    function getLocalityList($id) {
      $locality = CityLocalities::where('city_id',$id)->get();
      return $locality;
    }
}

if (!function_exists('getAllHgDoctors')) {

    function getAllHgDoctors()
    {
		return Doctors::where(["hg_doctor"=>1,"claim_status"=>1,"varify_status"=>1])->count();
	}
}

if (!function_exists('getAllNonHgDoctors')) {

    function getAllNonHgDoctors()
    {
		return Doctors::where(["hg_doctor"=>0])->count();
    }
}

if (!function_exists('getAllPatients')) {

    function getAllPatients()
    {
        // Total user count
        $totalUsers = User::count();

		
        $currentMonthUsers = User::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        // Previous month user count
        $previousMonth = Carbon::now()->subMonth();
        $previousMonthUsers = User::whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();

        // Calculate percentage change
        if ($previousMonthUsers > 0) {
            $percentageChange = (($currentMonthUsers - $previousMonthUsers) / $previousMonthUsers) * 100;
        } else {
            // If there were no users in the previous month, handle the division by zero case
            $percentageChange = $currentMonthUsers > 0 ? 100 : 0;
        }

       return   [
            'total_users' => $totalUsers,
            'current_month_users' => $currentMonthUsers,
            'previous_month_users' => $previousMonthUsers,
            'percentage_change' => $percentageChange
        ];
        //dd($a);
    }
}

if (!function_exists('getAllHgActiveDoctors')) {

    function getAllHgActiveDoctors()
    {
		 $dt = date('Y-m-d');
         return ManageTrailPeriods::whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['status'=>1])->count();
	}
}

if(!function_exists('getTodayClaimDoctors')) {
    function getTodayClaimDoctors() {
		$today = '%'.date('Y-m-d').'%';
		return Doctors::select("id")->where(["delete_status"=>1,"claim_status"=>1,"varify_status"=>0])->where('updated_at','like',$today)->count();
	}
}
if (!function_exists('getTodayClaimDoctorsFour')) {
     function getTodayClaimDoctorsFour(){
        $today = '%'.date('Y-m-d').'%';
        $doctors = Doctors::select(['first_name','last_name','email','profile_pic','updated_at'])->where(["delete_status"=>1,"claim_status"=>1,"varify_status"=>0])->whereDate('updated_at',$today)->orderBy("updated_at","DESC")->limit(4)->get();
       return $doctors;
     }
}
if (!function_exists('getDateDifference')) {
    function getDateDifference($date1timestamp, $date2timestamp) {
       $all = round(($date1timestamp - $date2timestamp) / 60);
       $d = floor ($all / 1440);
       $h = floor (($all - $d * 1440) / 60);
       $m = $all - ($d * 1440) - ($h * 60);
       // pr(array('hours'=>$h, 'mins'=>$m));
       return array('hours'=>$h, 'mins'=>$m);
   }
}
	if (!function_exists('does_url_exists')) {
		function does_url_exists($url) {
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_NOBODY, true);
			curl_exec($ch);
			$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($code == 200) {
				$status = true;
			} else {
				$status = false;
			}
			curl_close($ch);
			return $status;
		}
	}
if (!function_exists('getEhrFullUrls')) {
    function getEhrFullUrls() {
		return array('url'=>getEhrUrl(),'base_path'=>getEhrBasePath(),'public_path'=>getEhrPublicPath());
	}
}
if (!function_exists('getEhrGlobalUrl')) {
    function getEhrGlobalUrl(){
		return "https://doc.healthgennie.com";
    }
}
if (!function_exists('getEhrUrl')) {
    function getEhrUrl(){
		return "https://doc.healthgennie.com";
    }
}
if (!function_exists('getEhrBasePath')) {
    function getEhrBasePath(){
		return "/var/www/html/";
    }
}
if (!function_exists('getEhrPublicPath')) {
    function getEhrPublicPath(){
		return "/var/www/html/public";
    }
}


if (!function_exists('getPhoneCode')) {
	function getPhoneCode(){
		$countries = Country::groupBy("phonecode")->orderBy("phonecode")->get();
		return $countries;
	}
}

if (!function_exists('getDoctorsByMultiSpeciality')) {
	function getDoctorsByMultiSpeciality($ids) {
		$infoData = "";
		if(count($ids) > 0){
			$infoData = Doctors::WhereIn('speciality',$ids)->get();
			$infoData = bindDocData($infoData);
		}
		return $infoData;
	}
}

if (!function_exists('bindDocDataByUnique')) {
	function bindDocDataByUnique($value) {
		// foreach ($docs_array as $key => $value) {
			$doc_paths = json_decode($value->urls);
			$opd_timings = array();

			if(!empty($value->profile_pic)){
			  $image_url = getPath("public/doctor/ProfilePics/".$value->profile_pic);
			  if(does_url_exists($image_url)) {
				$value->profile_pic = $image_url;
			  }
			  else{
				$value->profile_pic = null;
			  }
			}
			if(!empty($value->clinic_image)){
				  $image_url = getPath("public/doctor/".$value->clinic_image);
				  if(does_url_exists($image_url)) {
					$value['clinic_image'] = $image_url;
				  }
				  else{
					  $value['clinic_image'] = null;
				  }
			}
			if(!empty($value->speciality)){
				$value->speciality =array("id"=>$value->speciality, "name"=>getSpecialityName($value->speciality));
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
			  $value->city_id = array("id"=>$value->city_id,"name"=>getCityName($value->city_id),"slug"=>getCitySlug($value->city_id));
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
			// $time_slot = array();
			// if(isset($opd_time['today'])){
				// foreach($opd_time['today'] as $time_already){
					// $time_slot[] = selectTimesBySlot($time_already['start_time'],$time_already['end_time'],$increment);
				// }
			// }
			$value['today_timing_slots'] = array();
			$value['doc_rating'] = 0;
			// if(isset($value->DoctorRatingReviews)) {
				// if(count($value->DoctorRatingReviews) > 0) {
					// $rating_val = 0;
					// $rating_count = 0;
					// foreach($value->DoctorRatingReviews as $rating) {
						// $rating_val += $rating->rating;
						// $rating_count++;
					// }
					// if($rating_val > 0){
						// $rating_val = round($rating_val/$rating_count,1);
					// }
					// $value['doc_rating'] = $rating_val;
				// }
			// }
		// }
		return $value;
	}
}


if (!function_exists('bindDocData')) {
	function bindDocData($docs_array){
		foreach ($docs_array as $key => $value) {
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
				$value['speciality'] =array("id"=>$value->speciality, "name"=>getSpecialityName($value->speciality));
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
			  $value['city_id'] = array("id"=>$value->city_id,"name"=>getCityName($value->city_id),"slug"=>getCitySlug($value->city_id));
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
					$time_slot[] = selectTimesBySlot($time_already['start_time'],$time_already['end_time'],$increment);
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
}
if (!function_exists('selectTimesBySlot')) {
	function selectTimesBySlot($start_time,$end_time,$slot) {
		$output = array();
		$start  = strtotime(date('H:i',strtotime($start_time)));  //pr($start);
		$end    = strtotime(date('H:i',strtotime($end_time))); //pr($end);
		for( $i = $start; $i < $end; $i += $slot) {
			$output[] = date("g:i A",$i);
		}
		return $output;
	}
}
	if (!function_exists('encryptByCustom')) {
		function encryptByCustom($pure_string, $encryption_key) {
			$cipher     = 'AES-256-CBC';
			$options    = OPENSSL_RAW_DATA;
			$hash_algo  = 'sha256';
			$sha2len    = 32;
			$ivlen = openssl_cipher_iv_length($cipher);
			$iv = openssl_random_pseudo_bytes($ivlen);
			$ciphertext_raw = openssl_encrypt($pure_string, $cipher, $encryption_key, $options, $iv);
			$hmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
			return $iv.$hmac.$ciphertext_raw;
		}
	}
	if (!function_exists('decryptByCustom')) {
		function decryptByCustom($encrypted_string, $encryption_key) {
			$cipher     = 'AES-256-CBC';
			$options    = OPENSSL_RAW_DATA;
			$hash_algo  = 'sha256';
			$sha2len    = 32;
			$ivlen = openssl_cipher_iv_length($cipher);
			$iv = substr($encrypted_string, 0, $ivlen);
			$hmac = substr($encrypted_string, $ivlen, $sha2len);
			$ciphertext_raw = substr($encrypted_string, $ivlen+$sha2len);
			$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $encryption_key, $options, $iv);
			$calcmac = hash_hmac($hash_algo, $ciphertext_raw, $encryption_key, true);
			if(function_exists('hash_equals')) {
				if (hash_equals($hmac, $calcmac)) return $original_plaintext;
			} else {
				if ($this->hash_equals_custom($hmac, $calcmac)) return $original_plaintext;
			}
		}
	}


if (!function_exists('getVisitType')) {

    function getVisitType($id)
    {
    	$visit = VisitTypes::where('id', $id)->first();
    	$visit_name='';
    	if(!empty($visit)){
    	$visit_name = $visit->visit_type;
    	}
    	return $visit_name ;
    }
}
if (!function_exists('getWatingTime')) {

    function getWatingTime($checkinTime,$outTime)
    {
	if(empty($outTime)){
	   $all = round((strtotime(date("Y-m-d H:i:s")) - $checkinTime) / 60);
	}else{
	   $all = round(($outTime - $checkinTime) / 60);
	}

	$d = floor ($all / 1440);
	$h = floor (($all - $d * 1440) / 60);
	$m = $all - ($d * 1440) - ($h * 60);
	//Since you need just hours and mins
	//echo "<pre>";
	//print_r(array('hours'=>$h, 'mins'=>$m));die;
	//return $h.' hours'.' '.$m.' Minutes';
	return sprintf('%02d',$h).':'.sprintf('%02d',$m);
    }
}

if (!function_exists('getTotalAppointment')) {
    function getTotalAppointment() {
        // Total appointment count
        $totalAppointments = Appointments::whereIn('app_click_status', [5, 6])
            ->where('delete_status', 1)
            ->where('added_by', '!=', 24)
            ->count();

        // Current month appointment count
        $currentMonthAppointments = Appointments::whereIn('app_click_status', [5, 6])
            ->where('delete_status', 1)
            ->where('added_by', '!=', 24)
            ->whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        // Previous month appointment count
        $previousMonth = Carbon::now()->subMonth();
        $previousMonthAppointments = Appointments::whereIn('app_click_status', [5, 6])
            ->where('delete_status', 1)
            ->where('added_by', '!=', 24)
            ->whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();

        // Calculate percentage change
        if ($previousMonthAppointments > 0) {
            $percentageChange = (($currentMonthAppointments - $previousMonthAppointments) / $previousMonthAppointments) * 100;
        } else {
            // If there were no appointments in the previous month, handle the division by zero case
            $percentageChange = $currentMonthAppointments > 0 ? 100 : 0;
        }

        return [
            'total_appointments' => $totalAppointments,
            'current_month_appointments' => $currentMonthAppointments,
            'previous_month_appointments' => $previousMonthAppointments,
            'percentage_change' => $percentageChange
        ];
    }
}
	if(!function_exists('getTodayAppointment')){
        function getTodayAppointment() {
			$today = date('Y-m-d');
            $query = Appointments::select("id")->whereIn('app_click_status',array(5,6))->where("added_by","!=",24)->where("delete_status",1)->whereRaw('date(created_at) >= ?', [$today])->whereRaw('date(created_at) <= ?', [$today])->count();
			return  $query;
        }
	}

	if(!function_exists('getTodayPendingAppointment')){
        function getTodayPendingAppointment() {
			$today = date('Y-m-d');
            $query = Appointments::select('id')->where(['appointment_confirmation'=>0])->whereIn('app_click_status',array(5,6))->where("delete_status",1)->whereRaw('date(created_at) >= ?', [$today])->whereRaw('date(created_at) <= ?', [$today])->count();
			return  $query;
        }
	}

	if(!function_exists('getTodayConfirmAppointment')) {
        function getTodayConfirmAppointment() {
			$today = date('Y-m-d');
            $query = Appointments::select('id')->where(['appointment_confirmation'=>1])->whereIn('app_click_status',array(5,6))->where("delete_status",1)->whereRaw('date(created_at) >= ?', [$today])->whereRaw('date(created_at) <= ?', [$today])->count();
			return  $query;
        }
	}

	if(!function_exists('getTodayCancelAppointment')) {
        function getTodayCancelAppointment() {
			$today = date('Y-m-d');
            $query = Appointments::select('id')->where(['status'=>0])->whereIn('app_click_status',array(5,6))->where("delete_status",1)->whereRaw('date(created_at) >= ?', [$today])->whereRaw('date(created_at) <= ?', [$today])->count();
			return  $query;
        }
	}
	if(!function_exists('getIdByLocality')) {
		function getIdByLocality($locality_name,$city_name,$state_name) {
			$id = ""; $slug = ""; $state = ""; $city = ""; $locality = "";
			if(!empty($state_name)){
				$state = State::select('id')->where('name','like', '%'.$state_name.'%')->first();
			}
			if(!empty($city_name)){
				$city = City::select(['id','slug'])->where('name','like', '%'.$city_name.'%')->first();
			}
			$query = CityLocalities::select(['id','slug']);
			if(!empty($state)) {
				$query->where('state_id',$state->id);
			}
			if(!empty($city)) {
				$query->where('city_id',$city->id);
			}
			if(!empty($locality_name)){
				$query->where('name','like', $locality_name);
				$locality = $query->orderBy('top_status','DESC')->first();
			}
			if(!empty($locality)) {
				$id = $locality->id;
				$slug = $locality->slug;
			}
			return ["id"=>$id,"slug"=>$slug];
		}
	}

	if(!function_exists('getIdByCity')) {
		function getIdByCity($city_name,$state_name) {
			$slug = ""; $id = ""; $state = "";
			if(!empty($state_name)){
				$state = State::select('id')->where('name','like', '%'.$state_name.'%')->first();
			}
			$query = City::select(['id','slug']);
			if(!empty($state)) {
				$query->where('state_id',$state->id);
			}
			if(!empty($city_name)){
				$query->where('name','like', '%'.$city_name.'%');
			}
			$city = $query->first();
			if(!empty($city)){
				$id = $city->id;
				$slug = $city->slug;
			}
			return ["id"=>$id,"slug"=>$slug];
		}
	}


	if(!function_exists('getIdByState')) {
		function getIdByState($state_name) {
			$id = "";
			$query = State::select('id');
			if(!empty($state_name)) {
				$query->where('name','like', '%'.$state_name.'%');
			}
			$state = $query->first();
			if(!empty($state)){
				$id = $state->id;
			}
			return $id;
		}
	}


	if(!function_exists('getLocalityByCityId')) {
		function getLocalityByCityId($city_id) {
			$query = CityLocalities::where('status',1);
			if(!empty($city_id)) {
				$query->where('city_id',$city_id);
			}
			$localities =  $query->orderBy('top_status','DESC')->get();
			return $localities;
		}
	}
	 if (!function_exists('getDoctorSlotDuration')) {
		function getDoctorSlotDuration($doc){
			$doctor = Doctors::where('id',$doc)->pluck('slot_duration');
	     return $doctor;
		}
	  }
	   if (!function_exists('getUniqueIdPatient')) {
			function getUniqueIdPatient($str) {
				$num = 1;
				// $users =  DB::select('SELECT MAX(CAST((SUBSTRING(patient_number,2)) as UNSIGNED)) as total FROM `testing_db.patients`');
				$users =  Patients::select(DB::raw('MAX(CAST((SUBSTRING(patient_number,2)) as UNSIGNED)) as total'))->pluck('total');
				if(!empty($users)) {
				   $num = $users[0]+$num;
				}
				return $str.$num;
			}
	   }

	 if (!function_exists('getBolgLastUpdated')) {
		function getBolgLastUpdated() {
			$blogs = NewsFeeds::where('status',1)->whereRaw("find_in_set('2',news_feeds.type)")->whereDate('publish_date', '<=', date("Y-m-d"))->orderBy('show_date','desc')->limit(3)->get();
			return $blogs;
		}
	  }
	if (!function_exists('getCurrentUserName')) {
		function getCurrentUserName() {
			$user = Auth::user();
			$name =  $user->first_name." ".$user->last_name;
			return $name;
		}
	}
	if (!function_exists('getSubcriptionPlanid')) {
		function getSubcriptionPlanid($plan_duration) {
			$plan = Plans::Where('plan_price','!=',0)->Where(['plan_duration_type'=>'y','plan_type'=>1,'status'=> 1,'delete_status'=>1])->where("plan_duration",$plan_duration)->first();
			return $plan;
		}
	}


	if (!function_exists('checSubcriptionStatus')) {
		function checSubcriptionStatus($id) {
			$plan = ManageTrailPeriods::Where('user_id',$id)->first();
			$dt = date('Y-m-d');
            $result =  ManageTrailPeriods::whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['user_id'=>$id,'status'=>1])->where('user_plan_id','!=',5)->first();
			if(!empty($result)){
				return 1;
			}
			return 0;
		}
	}

	if (!function_exists('checSubscribeDoc')) {
    function checSubscribeDoc($type) {
      $user_id = array();
      $dt = date('Y-m-d');
      // subscription users
      if ($type == 1) {
        $result =  ManageTrailPeriods::whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['status'=>1])->where('user_plan_id','!=',5)->get();
        foreach ($result as $key => $value) {
          $user_id[] = $value->user_id;
        }
      }
      // trail users
      elseif ($type == 2) {
        $result =  ManageTrailPeriods::whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['status'=>1])->where('user_plan_id', 5)->get();
        foreach ($result as $key => $value) {
          $user_id[] = $value->user_id;
        }
      }
      // trail end users
      elseif ($type == 3) {
        $result =  ManageTrailPeriods::whereDate('end_trail','<', $dt)->where(['status'=>1])->get();
        foreach ($result as $key => $value) {
          $user_id[] = $value->user_id;
        }
      }
      // trail or subscription users
      elseif ($type == 4) {
        $result =  ManageTrailPeriods::whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['status'=>1])->get();
        foreach ($result as $key => $value) {
          $user_id[] = $value->user_id;
        }
      }
      return $user_id;
    }
  }

  if (!function_exists('getDoctorByOtherSpaciality')) {
		function getDoctorByOtherSpaciality($s_city_id = null) {
			$s_state_id = Session::get('state_id');
			$s_locality_id = Session::get('locality_id');
			if(empty($s_city_id)){
				$s_city_id = Session::get('city_id');
			}
			$query = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'oncall_status'=>1,'varify_status'=>1])->whereNotNull('speciality')->whereNotNull('first_name')->where("oncall_status","!=",0);
			if(!empty($s_locality_id)) {
				//$query->where('locality_id',$s_locality_id);
			}
			if(!empty($s_city_id)) {
				//$query->where('city_id',$s_city_id);
			}
			$speciaity = Speciality::where('id',1)->first();
			if(!empty($speciaity)) {
				$s_ids = Speciality::where(["group_id"=>$speciaity->group_id])->pluck('id');
				$query->whereIn('speciality',$s_ids);
			}
			$infoData = $query->limit(10)->get();
			$infoData = dataSequenceChange($infoData,1);

			if(count($infoData) > 0) {
				$infoData = bindDocData($infoData);
				$available_now = array_column($infoData, 'available_now');
				array_multisort($available_now, SORT_DESC, $infoData);
				
				$byrating = array_column($infoData, 'doc_rating');
				array_multisort($byrating, SORT_DESC, $infoData);
			}
			return $infoData;
		}
	}
	if (!function_exists('getHospitalByOtherSpaciality')) {
		function getHospitalByOtherSpaciality() {
			$s_state_id = Session::get('state_id');
			$s_city_id = Session::get('city_id');
			$s_locality_id = Session::get('locality_id');
			$query = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->whereNotNull('speciality')->whereNotNull('clinic_name')->where("oncall_status","!=",0);
			if(!empty($s_locality_id)) {
				//$query->where('locality_id',$s_locality_id);
			}
			if(!empty($s_city_id)) {
				$query->where('city_id',$s_city_id);
			}
			$query->groupBy('clinic_name');
			$infoData = $query->limit(10)->get();
			$infoData = dataSequenceChangeHelper($infoData);

			if(count($infoData) > 0) {
				$infoData = bindDocData($infoData);
			}
			return $infoData;
		}
	}

  if (!function_exists('getSuggestedDoctors')) {
    function getSuggestedDoctors($s_city_id = null) {
      if(empty($s_city_id)){
        $s_city_id = Session::get('city_id');
      }
      $s_speciality_id = Session::get('speciality_id');
      $s_state_id = Session::get('state_id');
      $dt = date('Y-m-d');
      $infoData = Doctors::with(['ManageSponsored'])->WhereHas("ManageSponsored",function($qry) use($s_state_id,$s_city_id,$dt) {
        $qry->whereRaw('FIND_IN_SET(?,state_ids)', [$s_state_id])->whereRaw('FIND_IN_SET(?,city_ids)', [$s_city_id])->whereRaw('"'.$dt.'" between `start_date` and `end_date`')->where('status',1);
      })->where(["delete_status"=>1,"sponsored_status"=>1,"speciality"=>$s_speciality_id])->groupBy('clinic_name')->offset(1)->limit(5)->get();
      if(count($infoData) > 0) {
        $infoData = bindDocData($infoData);
      }
      return $infoData;
    }
  }

  if (!function_exists('getSponsoredDoctor')) {
    function getSponsoredDoctor() {
      $s_state_id = Session::get('state_id');
      $s_city_id = Session::get('city_id');
      $dt = date('Y-m-d');
      $infoData = Doctors::with(['ManageSponsored'])->WhereHas("ManageSponsored",function($qry) use($s_state_id,$s_city_id,$dt) {
        $qry->whereRaw('FIND_IN_SET(?,state_ids)', [$s_state_id])->whereRaw('FIND_IN_SET(?,city_ids)', [$s_city_id])->whereRaw('"'.$dt.'" between `start_date` and `end_date`')->where('status',1);
      })->where(["delete_status"=>1,"sponsored_status"=>1])->groupBy('clinic_name')->limit(1)->get();
      if(count($infoData) > 0) {
        $infoData = bindDocData($infoData);
      }
      return $infoData;
    }
  }
	if (!function_exists('dataSequenceChangeHelper')) {
		function dataSequenceChangeHelper($infoData) {
			$prime_arr = [];
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
						$info['rating'] = $rating_val;
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
						$info['rating'] = $rating_val;
						$non_prime_arr[] = $info;
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
					$info['rating'] = $rating_val;
					$non_prime_arr[] = $info;
				}
			}

			$price = array();
			foreach ($prime_arr as $key => $row) {
				$price[$key] = $row['rating'];
			}
			array_multisort($price, SORT_DESC, $prime_arr);

			$price = array();
			foreach ($non_prime_arr as $key => $row) {
				$price[$key] = $row['rating'];
			}
			array_multisort($price, SORT_DESC, $non_prime_arr);

			if(count($non_prime_arr) > 0 ) {
				$infoData = array_merge($prime_arr,$non_prime_arr);
			}
			else{
				$infoData = $prime_arr;
			}
			return $infoData;
		}
	}

  if (!function_exists('getAllSpecialityByHospital')) {
    function getAllSpecialityByHospital($id=null,$name=null) {
      $spes = [];
      $query = Doctors::with(["docSpeciality"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1]);
      if(!empty($id)) {
        $query->where('practice_id',$id);
      }
      if(!empty($name)){
        $query->where('clinic_name', 'like', '%'.$name.'%');
      }
      $infoData = $query->get();
      if(count($infoData)>0){
        foreach($infoData as $data) {
          if(!empty($data->docSpeciality)){
            $spes[] = $data->docSpeciality->specialities;
          }
        }
      }
	  $spes = array_unique($spes);
      return $spes;
    }
  }

  	if (!function_exists('getCouncilingData')) {
		function getCouncilingData() {
			$data = CouncilingData::select(['id','council_name'])->where(['status'=>1,'delete_status'=>1])->get();
			return $data;
		}
	}
  if (!function_exists('getUniversityList')) {
    function getUniversityList() {
      $data = UniversityList::select(['id','name'])->where(['status'=>1,'delete_status'=>1])->get();
      return $data;
    }
  }

    if (!function_exists('getClinics')) {
    function getClinics() {
      $docs = Doctors::select(["id","practice_id","clinic_name","practice_type","city_id","locality_id","clinic_image","clinic_mobile","clinic_email","website","address_1","country_id","state_id","zipcode"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->whereNotNull('practice_id')->whereNotNull('clinic_name')->groupBy('practice_id')->get();
      return $docs;
    }
  }

    if (!function_exists('getComplimentName')) {
    function getComplimentName($id){
      $data = ComplimentsMaster::where('id', $id)->first();
      return $data->name;
    }
  }

   if (!function_exists('getWaitingTimeName')) {
    function getWaitingTimeName($id){
      $data = WaitingTimeMaster::where('id', $id)->first();
      return $data->type;
    }
  }

   if (!function_exists('getSubSpeciality')) {
    function getSubSpeciality(){
      $data = SubSpecialities::orderBy("id")->get();
      return $data;
    }
  }

  if (!function_exists('getSpecialityGrpNameById')) {
    function getSpecialityGrpNameById($id) {
		$data = SpecialityGroup::select(['group_name'])->where("id",$id)->first();
		return @$data->group_name;
    }
}

  if (!function_exists('getLabReportById')) {
    function getLabReportById($order_id) {
		$data = LabReports::where("order_id",$order_id)->first();
		if(isset($data) && $data->company_id != 0) {
			$data['report_pdf_name'] = getPath("public/lab-reports/".$data->report_pdf_name);
		}
		return $data;
    }
}

 if (!function_exists('getThyrocareData')) {
    function getThyrocareData($type=null) {
		if($type == "ALL") {
			$products = ThyrocareLab::get();
		}
		else{
			$products = ThyrocareLab::where(['type'=>$type])->get();
		}
      return $products;
    }
  }
   if (!function_exists('getThyrocarePackageGroup')) {
    function getThyrocarePackageGroup($type=null) {
      $groups = ThyrocarePackageGroup::select('group_name')->where(['delete_status'=>1])->orderBy('id' ,'ASC')->get();
	  return $groups;
    }
  }

  if (!function_exists('findObjectById')) {
    function findObjectById($type=null) {
      $array = array( /* your array of objects */ );

        foreach ( $array as $element ) {
            if ( $id == $element->id ) {
                return $element;
            }
        }
            return false;
        }
     }

	  if (!function_exists('getLabDetails')) {
      function getLabDetails($type=null) {
          $item = null;
          foreach($array as $struct) {
              if ($v == $struct->ID) {
                  $item = $struct;
                  break;
              }
          }
          return $user;
      }
    }

	 if (!function_exists('getCompliments')) {
      function getCompliments($key=null) {
        $array = array("1"=>"Quality of Medical Care", "2"=>"Staff Assistance/ Support", "3"=>"Caring & Compassionate", "4"=>"Outstanding Customer Service", "5"=>"Timely Problem/ Issue Resolution", "6"=>"Superior Facilities");
        return $array[$key];
      }
    }
	 if (!function_exists('getTimeElapsedString')) {
		function getTimeElapsedString($datetime, $full = false){
		  $now = new DateTime;
		  $ago = new DateTime($datetime);
		  $diff = $now->diff($ago);

		  $diff->w = floor($diff->d / 7);
		  $diff->d -= $diff->w * 7;

		  $string = array(
			  'y' => 'year',
			  'm' => 'month',
			  'w' => 'week',
			  'd' => 'day',
			  'h' => 'hour',
			  'i' => 'minute',
			  's' => 'second',
		  );
		  foreach ($string as $k => &$v) {
			  if ($diff->$k) {
				  $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			  } else {
				  unset($string[$k]);
			  }
		  }

		  if (!$full) $string = array_slice($string, 0, 1);
		  return $string ? implode(', ', $string) . ' ago' : 'just now';
		  }
	  }

	    if (!function_exists('getUserName')) {
			function getUserName($user_id){
			  $user = User::where('id', $user_id)->first();
			  return @$user->first_name.' '.@$user->last_name;
			}
		  }
	if (!function_exists('getDoctorRatingByHospital')) {
		function getDoctorRatingByHospital($practice_id,$clinic_name=null) {
			if(!empty($practice_id)) {
				$practices = Doctors::where("practice_id",$practice_id)->pluck("id");
			}
			else if(!empty($clinic_name)){
				$practices = Doctors::where("clinic_name",$clinic_name)->pluck("id");
			}
			$feedback = PatientFeedback::whereIn("doc_id",$practices)->where(["status"=>1,"delete_status"=>1,"publish_admin"=>1])->whereNotNull("user_id")->get();
			foreach($feedback as $data) {
				$data['doc_name'] = getDoctorNameById($data->doc_id);
			}
			return $feedback;
		}
	}


  if (!function_exists('getLabCart')) {
    function getLabCart($user_id = null) {
	  if(empty($user_id)){	
       $user_id = @Auth::user()->id;
	  }
	  $labcart = LabCart::where(['user_id' => $user_id])->get();
		// dd($labcart);
      $cartProducts = [];
	  $products = [];
      foreach ($labcart as $key => $cart) {
		if($cart->type == 0){
		if($cart->product_type == "POP"){
			$cart->product_type = "POP";
		}	
		$products = ThyrocareLab::where(['type'=>$cart->product_type])->get()->toArray();
			if(count($products) > 0) {
				foreach ($products as $key => $product) {
				  if (!empty($cartProducts)) {
					if ($product['name'] == $cart->product_name && $product['code'] == $cart->product_code) {
						$product['cart_id'] = $cart->id;
						$product['lab_cart_type'] = 'thy';
						
						// $product['childs'] = json_decode($product['childs'],true);
						// $product['rate'] = json_decode($product['rate'],true);
						// $product['imageMaster'] = json_decode($product['imageMaster'],true);
						array_push($cartProducts, $product);
					}
				  }
				  else {
					if ($product['name'] == $cart->product_name && $product['code'] == $cart->product_code) {
					  $product['cart_id'] = $cart->id;
					  $product['lab_cart_type'] = 'thy';
					  // $product['childs'] = json_decode($product['childs'],true);
					  // $product['rate'] = json_decode($product['rate'],true);
					  // $product['imageMaster'] = json_decode($product['imageMaster'],true);
					  $cartProducts[] =  $product;
					}
				  }
			   }
			}
			Session::put('lab_company_type', 0);
			Session::save();
		}
		else if($cart->type != 0) {
			if ($cart->product_type == 'OFFER') {
				$lab = LabPackage::with("LabCompany")->where('id',$cart->product_code)->first();
				if(!empty($lab)){
					$lab['cost'] = $lab->price;
					$lab['offer_rate'] = $lab->discount_price;
					$lab['default_labs'] = ['title'=>$lab->title];
					$lab['DefaultLabs'] = ['title'=>$lab->title];
				}
				$lab['cart_id'] = $cart->id;
				$lab['lab_cart_type'] = 'package';
				// $labIds = !empty($lab->lab_id)? explode(",",$lab->lab_id) : [];
			    // $lab['labs'] = count($labIds) > 0 ? LabCollection::with("DefaultLabs","LabCompany")->whereIn('id',$labIds)->get() : [];
				$cartProducts[] =  $lab;
			}
			else{
				$lab = LabCollection::with("DefaultLabs","LabCompany")->where('id',$cart->product_code)->first();
				$lab['cart_id'] = $cart->id;
				$lab['title'] = @$lab->DefaultLabs->title;
				$lab['lab_cart_type'] = 'custom';
				$cartProducts[] =  $lab;
			}
			Session::put('lab_company_type', $cart->type);
			Session::save();
		}
      }
      return $cartProducts;
    }
  }

if (!function_exists('getLabCartNew')) {
    function getLabCartNew($user_id = null) {
	  if(empty($user_id)){	
       $user_id = @Auth::user()->id;
	  }
	  $labcart = LabCart::where(['user_id' => $user_id])->get();
      $cartProducts = [];
	  $products = [];
      foreach ($labcart as $key => $cart) {
		if($cart->type == 0){
		if($cart->product_type == "POP"){
			$cart->product_type = "POP";
		}	
		$products = ThyrocareLab::where(['type'=>$cart->product_type])->get()->toArray();
			if(count($products) > 0) {
				foreach ($products as $key => $product) {
				  if (!empty($cartProducts)) {
					if ($product['name'] == $cart->product_name && $product['code'] == $cart->product_code) {
						$product['cart_id'] = $cart->id;
						$product['lab_cart_type'] = 'thy';
						
						// $product['childs'] = json_decode($product['childs'],true);
						// $product['rate'] = json_decode($product['rate'],true);
						// $product['imageMaster'] = json_decode($product['imageMaster'],true);
						array_push($cartProducts, $product);
					}
				  }
				  else {
					if ($product['name'] == $cart->product_name && $product['code'] == $cart->product_code) {
					  $product['cart_id'] = $cart->id;
					  $product['lab_cart_type'] = 'thy';
					  // $product['childs'] = json_decode($product['childs'],true);
					  // $product['rate'] = json_decode($product['rate'],true);
					  // $product['imageMaster'] = json_decode($product['imageMaster'],true);
					  $cartProducts[] =  $product;
					}
				  }
			   }
			}
			Session::put('lab_company_type', 0);
			Session::save();
		}
		else if($cart->type != 0) {
			if ($cart->product_type == 'OFFER') {
				$lab = LabPackage::with("LabCompany")->where('id',$cart->product_code)->first();
				if(!empty($lab)){
					$lab['cost'] = $lab->price;
					$lab['offer_rate'] = $lab->discount_price;
					$lab['default_labs'] = ['title'=>$lab->title];
					$lab['DefaultLabs'] = ['title'=>$lab->title];
				}
				$lab['cart_id'] = $cart->id;
				$lab['lab_cart_type'] = 'package';
				// $labIds = !empty($lab->lab_id)? explode(",",$lab->lab_id) : [];
			    // $lab['labs'] = count($labIds) > 0 ? LabCollection::with("DefaultLabs","LabCompany")->whereIn('id',$labIds)->get() : [];
				$cartProducts[] =  $lab;
			}
			else{
				$lab = LabCollection::with("DefaultLabs","LabCompany")->where('id',$cart->product_code)->first();
				$lab['cart_id'] = $cart->id;
				$lab['lab_cart_type'] = 'custom';
				$cartProducts[] =  $lab;
			}
			Session::put('lab_company_type', $cart->type);
			Session::save();
		}
      }
      return $cartProducts;
    }
  }
    if (!function_exists('getSubscriptionLabData')) {
    function getSubscriptionLabData($code) {
		$cartProducts = [];
		$arr2 = [];
		$Allproduct = File::get(public_path('thyrocare-data/All.txt'));
		$Allproduct = json_decode($Allproduct,true);
		$arr1 = $Allproduct['MASTERS']['OFFER'];
		$arr3 = $Allproduct['MASTERS']['PROFILE'];

		$ofr_arr = File::get(public_path('thyrocare-data/Offer.txt'));
		if(!empty($ofr_arr)){
			$arr2 = json_decode($ofr_arr, true);
			$arr2 = @array_merge($arr1,$arr2);
		}
		else{
			$arr2 = @array_merge($arr1,$arr2);
		}


		$products = @array_merge($arr2,$arr3);

		if(count($products) > 0) {
			foreach ($products as $key => $product) {
				if ($product['code'] == $code) {
					$cartProducts[] =  $product;
				}
		   }
		}
		return $cartProducts;
     }
  }

  if (!function_exists('getLabOrdersCount')) {
     function getLabOrdersCount($type) {
       $user_id = Auth::user()->id;
       //all Orders
       if ($type == 1) {
			
			$ordersCount = LabOrders::where(["user_id" => $user_id])->count();
			$ordersCount = $ordersCount;
       }
        //Upcomming Orders
       elseif ($type == 2) {
        $ordersCount	=	LabOrders::where(["user_id" => $user_id])->whereIn('order_status', array('YET TO CONFIRM', 'YET TO ASSIGN', 'Y'))->count();
       }
       //Upcomming Done
       elseif ($type == 3) {
		    
			$ordersCount	=	LabOrders::where(["user_id" => $user_id])->whereNotIn('order_status',array('YET TO CONFIRM', 'YET TO ASSIGN', 'Y' , 'CANCELLED'))->count();
			 $ordersCount =  $ordersCount;
       }
       //Upcomming Cancel
       elseif ($type == 4) {
        $ordersCount	=	LabOrders::where(["user_id" => $user_id])->where('order_status', 'CANCELLED')->count();
       }
     return $ordersCount;
     }
  }

   if (!function_exists('getDoctorNameById')) {
	function getDoctorNameById($id){
	  $user = Doctors::select(["first_name","last_name"])->where('id', $id)->first();
	  return @$user->first_name.' '.@$user->last_name;
	}
  }

  if(!function_exists('checkAdminUserModulePermission')) {
    function checkAdminUserModulePermission($moduleId){
         $user_id = Session::get('userdata')->id;
       $permissions = Admin::select('module_permissions')->Where('id', $user_id)->first();
        if(!empty($permissions) && $permissions->module_permissions != null){
          if(in_array($moduleId, explode(',',$permissions->module_permissions))){
            return true;
          }
          else{
            return false;
          }
        }
        else{
           return false;
         }
     }
   }
    if(!function_exists('getAdminUserPermissionModule')) {
    function getAdminUserPermissionModule(){
        $user_id = Session::get('userdata')->id;
		$permissions = Admin::select('module_permissions')->Where('id', $user_id)->first();
		// dd($permissions);
        if(!empty($permissions) && $permissions->module_permissions != null) {
          return explode(',',$permissions->module_permissions);
		}
		else return [];
		}
	}
	 if(!function_exists('dataSequenceChange')) {
			function dataSequenceChange($infoData,$speciaity_id = null) {
			$prime_arr = [];
			$verified_doc_arr = [];
			$non_prime_arr = [];
			foreach($infoData as $info) {
				$info['is_prime']  = 0;
				if(!empty($info->practice_id)) {
					/*if(checSubcriptionStatus($info->practice_id) == 1) {
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
						$info['rating'] = $rating_val;
						$prime_arr[] = $info;
					}*/
					// else{
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
						$info['rating'] = $rating_val;
						$verified_doc_arr[] = $info;
					// }
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
					$info['rating'] = $rating_val;
					$non_prime_arr[] = $info;
				}
			}

			$price = array();
			foreach ($prime_arr as $key => $row) {
				$price[$key] = $row['rating'];
			}
			array_multisort($price, SORT_DESC, $prime_arr);

			$price = array();
			foreach ($verified_doc_arr as $key => $row) {
				$price[$key] = $row['rating'];
			}
			array_multisort($price, SORT_DESC, $verified_doc_arr);

			$price = array();
			foreach ($non_prime_arr as $key => $row) {
				$price[$key] = $row['rating'];
			}
			array_multisort($price, SORT_DESC, $non_prime_arr);
			if(count($verified_doc_arr) > 0 ) {
				$prime_arr = array_merge($prime_arr,$verified_doc_arr);

				if(count($prime_arr) > 0 && $speciaity_id != null) {
					$docs_arr = [];
					foreach($prime_arr as $info){
						if($info->speciality == $speciaity_id){
							$docs_arr[] = $info;
						}
					}
					foreach($prime_arr as $info){
						if($info->speciality != $speciaity_id){
							$docs_arr[] = $info;
						}
					}
					$prime_arr = $docs_arr;
				}
			}
			if(count($non_prime_arr) > 0 ) {
				if(count($non_prime_arr) > 0 && $speciaity_id != null) {
					$non_prime_docs_arr = [];
					foreach($non_prime_arr as $info){
						if($info->speciality == $speciaity_id){
							$non_prime_docs_arr[] = $info;
						}
					}
					foreach($non_prime_arr as $info){
						if($info->speciality != $speciaity_id){
							$non_prime_docs_arr[] = $info;
						}
					}
					$non_prime_arr = $non_prime_docs_arr;
				}
				$infoData = array_merge($prime_arr,$non_prime_arr);
			}
			else{
				$infoData = $prime_arr;
			}

			return $infoData;
		}
	}

	if (!function_exists('getDoctorIdBySlug')) {
		function getDoctorIdBySlug($slug){
		  $doc_id = DoctorSlug::select(["doc_id"])->where('name_slug',$slug)->pluck("doc_id");
		  return $doc_id;
		}
	}
	if (!function_exists('getClinicIdBySlug')) {
		function getClinicIdBySlug($slug){
		  $doc_id = DoctorSlug::select(["practice_id"])->where('clinic_name_slug',$slug)->groupBy("practice_id")->pluck("practice_id");
		  return $doc_id;
		}
	}

	if (!function_exists('getClinicSlug')) {
		function getClinicSlug($doctor_data,$i) {
			$clinicSlug = "";
			$flag = false;
			if(!empty($doctor_data->clinic_name)) {
				$clinic_name = $doctor_data->clinic_name;
				$clinicSlug = clean($clinic_name);
			}
			if(!empty($doctor_data->docSpeciality)){
				$clinicSlug = $clinicSlug."-".$doctor_data->docSpeciality->slug;
			}
			if(!empty($doctor_data->getCityName)) {
				$clinicSlug = $clinicSlug."-".$doctor_data->getCityName->slug;
			}
			if(strpos($clinicSlug, $i) !== false) {
				$clinicSlug = rtrim($clinicSlug, $i).$i;
			}
			else {
				if(strpos(substr($clinicSlug, -2),"-") !== false) {
					$clinicSlug = substr_replace($clinicSlug ,$i,-1);
				}
				else{
					$slugs = DoctorSlug::where(["clinic_name_slug"=>$clinicSlug])->count();
					if($slugs > 0){
						$clinicSlug = $clinicSlug."-".$i;
					}
				}

			}

			$returnData = findClinicSlug($clinicSlug,$i);
			if($returnData['status'] == 0) {
				$flag = true;
			}
			if($flag === true) {
				return $clinicSlug;
			}
			else{
				return getClinicSlug($doctor_data,$returnData['i']);
			}
		}
	}

	if (!function_exists('getDoctorSlug')) {
		function getDoctorSlug($doctor_data,$i) {
		
			$docSlug = "";
			$docName = $doctor_data->first_name." ".$doctor_data->last_name;
			$docName = clean($docName);
			$flag = false;

			if(!empty($doctor_data->docSpeciality)) {
				$docSlug = "dr-".$docName."-".$doctor_data->docSpeciality->slug;
			}
			else{
				if(!empty($doctor_data->getCityName)){
					$docSlug = "dr-".$docName."-".$doctor_data->getCityName->slug;
				}
				else{
					$docSlug = "dr-".$docName;
				}
			}
			
			if(stripos($docSlug, $i) !== false) {
				//dd($docSlug);
				$docSlug = rtrim($docSlug, $i).$i;
				
			}
			else {
			
				if(stripos(substr($docSlug, -2),"-") !== false) {
					$docSlug = substr_replace($docSlug ,$i,-1);
				}
				else{
					$slugs = DoctorSlug::where(["name_slug"=>$docSlug])->count();
					if($slugs > 0){
						$docSlug = $docSlug."-".$i;
					}
				}
			}

			$returnData = findDocSlug($docSlug,$i);
			if($returnData['status'] == 0) {
				$flag = true;
			}
			if($flag === true) {
				return $docSlug;
			}
			else{
				return getDoctorSlug($doctor_data,$returnData['i']);
			}
		}
	}

	if (!function_exists('clean')) {
		function clean($string) {
		   $string = str_replace(' ', '-',strtolower(trim($string)));
		   return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
		}
	}
	
	if (!function_exists('cleanForSql')) {
		function cleanForSql($string) {
		   // $string = str_replace(' ', ' ',strtolower(trim($string)));
		    if(strpos($string, ".") !== false && ($string == trim($string) && strpos($string, ' ') !== false)) {
				$string = trim(str_replace(' ', ' ', $string));
		   }
			else{
				$string = trim(str_replace(' ', '_', $string));
			}
		   // echo "n";die;
		   // if ($string == trim($string) && strpos($string, ' ') !== false  ) {
				
				// $string = trim(str_replace(' ', '_', $string));
		   // }
			$string = preg_replace('!\s+!', ' ', $string);
			$string = trim(preg_replace('/[^A-Za-z0-9_\s]/', '_', $string));
			$string = preg_replace('/_+/', '_', $string);
			return $string;
		}
	}

	if (!function_exists('findDocSlug')) {
		function findDocSlug($slug,$i) {
			$slug_exists = DoctorSlug::where(["name_slug"=>$slug])->count();
			$i = $i + 1;
			if($slug_exists > 0) {
			   return ["status"=>1,"i"=>$i];
			}
			else {
				return ["status"=>0,"i"=>$i];
			}
		}
	}

	if (!function_exists('findClinicSlug')) {
		function findClinicSlug($slug,$i) {
			$slug_exists = DoctorSlug::where(["clinic_name_slug"=>$slug])->count();

			$i = $i + 1;
			if($slug_exists > 0) {
			   return ["status"=>1,"i"=>$i];
			}
			else {
				return ["status"=>0,"i"=>$i];
			}
		}
	}

	if (!function_exists('getBlogNameBySlug')) {
		function getBlogNameBySlug($slug) {
			$blog = NewsFeeds::select(["title","image","video","blog_desc"])->where('slug',$slug)->first();
			if(!empty($blog)){
				$blog['image'] = url("/")."/public/newsFeedFiles/".$blog['image'];
				$blog['video'] = $blog['video'];
			}
			return $blog;
		}
	}

	if (!function_exists('getTitleBySlug')) {
		function getTitleBySlug($info_type = null,$slug = null) {
			$keyword = ""; $description = "";
			if($info_type == "Speciality"){
				$cName = Session::get('search_from_city_name');
				if(!empty(Session::get('search_from_locality_name'))){
					if(trim(Session::get('search_from_locality_name')) != trim(Session::get('search_from_city_name'))){
						$cName = Session::get('search_from_locality_name').", ".Session::get('search_from_city_name');
					}
				}
				$speciaity = Speciality::select(["id","keywords","description","meta_title"])->where('slug',$slug)->first();
				if(!empty($speciaity)) {
					// $keywords = explode(",",$speciaity->keywords);
					// if($keywords) {
						// if(isset($keywords[1])) {
							// $keyword = $keywords[1];
						// }
						// else{
							// $keyword = @$keywords[0];
						// }
					// }
					$keyword = "Best ".$speciaity->keywords." in ".$cName." | Health Gennie";
					$description = $speciaity->description;
					$inCity = ""; $inCityKeyword = "";
					if(Session::get('search_from_locality_name') && Session::get('search_from_city_name') && Session::get('locality_id')) {
						$inCity = " in ".Session::get('search_from_locality_name').", ".Session::get('search_from_city_name').".";
						$inCityKeyword = " in ".Session::get('search_from_locality_name').", ".Session::get('search_from_city_name')." | ";
					}
					else if(Session::get('search_from_city_name')) {
						$inCity = " in ".Session::get('search_from_city_name').".";
						$inCityKeyword = " in ".Session::get('search_from_city_name')." | ";
					}
					$description = str_replace(".",$inCity, $description);
					$description = str_replace("|",trim($inCityKeyword), $description);
					if(!empty($speciaity->meta_title)) {
						$keyword = $speciaity->meta_title;
						$description = $speciaity->description;
					}
				}
			}
			else if($info_type == "Doctors" || $info_type == "doctor_all" || $info_type == "doctorsIn") {
				$cName = Session::get('search_from_city_name');
				if(!empty(Session::get('search_from_locality_name'))){
					if(trim(Session::get('search_from_locality_name')) != trim(Session::get('search_from_city_name'))){
						$cName = Session::get('search_from_locality_name');
					}
				}
				$dName = "Dr. ".Session::get('search_from_search_bar');
				$dSplty = Session::get('dSplty');
				if($info_type == "Doctors") {
					$cName = Session::get('dCtN');
					if(!empty(Session::get('dLtN'))){
						if(trim(Session::get('dLtN')) != trim(Session::get('dCtN'))){
							$cName = Session::get('dLtN');
						}
					}
					$keyword = "$dName - $dSplty | Health Gennie";
					$description = "$dName is a $dSplty in $cName. Book appointments online, view fees & address for $dName | Health Gennie";
				}
				else{
					$keyword = "Doctors in $cName | Health Gennie";
					$description = "Find out the list of top doctors in $cName near you at Health Gennie. Search by specialties and book instant appointment with them, view their fees, user feedbacks & address.";
				}
			}
			else if($info_type == "hospital" || $info_type == "hos_all" || $info_type == "hospitalIn") {
				$cName = Session::get('search_from_city_name');
				if(!empty(Session::get('search_from_locality_name'))){
					if(trim(Session::get('search_from_locality_name')) != trim(Session::get('search_from_city_name'))){
						$cName = Session::get('search_from_locality_name').", ".Session::get('search_from_city_name');
					}
				}
				$hosName = Session::get('search_from_search_bar');
				$dSplty = Session::get('dSplty');
				if($info_type == "hospital"){
					$keyword = "$hosName, $dSplty Hospital in $cName | Health Gennie";
					$description = "$hosName, $dSplty Hospital in $cName. Book doctors appointment online, view address for $hosName in $cName | Health Gennie";
				}
				else{
					$keyword = "Hospitals in $cName | Health Gennie";
					$description = "Find out the list of top hospitals in ".Session::get('search_from_city_name')." near you at Health Gennie. Book an instant appointment with the best doctors according to specialty and view their fees, user feedbacks & address.";
				}
			}
			else if($info_type == "Clinic" || $info_type == "clinic_all" || $info_type == "clinicIn") {
				$cName = Session::get('search_from_city_name');
				if(!empty(Session::get('search_from_locality_name'))){
					if(trim(Session::get('search_from_locality_name')) != trim(Session::get('search_from_city_name'))){
						$cName = Session::get('search_from_locality_name').", ".Session::get('search_from_city_name');
					}
				}
				$clinicName = Session::get('search_from_search_bar');
				if($info_type == "Clinic") {
					$cName = Session::get('dCtN');
					if(!empty(Session::get('dLtN'))){
						if(trim(Session::get('dLtN')) != trim(Session::get('dCtN'))){
							$cName = Session::get('dLtN').", ".Session::get('dCtN');
						}
					}
					$keyword = "$clinicName in $cName | Health Gennie";
					$description = "$clinicName in $cName. Book appointments online, view doctor fees, address, for $clinicName in $cName | Health Gennie";
				}
				else{
					$keyword = "Clinics in $cName | Health Gennie";
					$description = "Find out the list of top clinics in ".Session::get('search_from_city_name')." near you at Health Gennie. Book an instant appointment with the best doctors according to specialty and view their fees, user feedbacks & address.";
				}
			}
			else if($info_type == "symptoms") {
				$cName = Session::get('search_from_city_name');
				$keyword = "Best Doctors For ".ucwords(Session::get('search_from_search_bar'))." in ".$cName." | Health Gennie";
			}
			return ["keyword"=>$keyword,"description"=>null];
		}
	}
	if (!function_exists('getH1TagBySlug')) {
		function getH1TagBySlug($info_type = null,$slug = null) {
			$keyword = "";
			$cName = Session::get('search_from_city_name');
			if(!empty(Session::get('search_from_locality_name'))){
				if(trim(Session::get('search_from_locality_name')) != trim(Session::get('search_from_city_name'))){
					$cName = Session::get('search_from_locality_name').", ".Session::get('search_from_city_name');
				}
			}
			if($info_type == "Speciality") {
				$speciaity = Speciality::select(["keywords"])->where('slug',$slug)->first();
				if(!empty($speciaity)) {
					// $keywords = explode(",",$speciaity->keywords);
					// if($keywords) {
						// if(isset($keywords[1])) {
							// $keyword = $keywords[1];
						// }
						// else{
							// $keyword = @$keywords[0];
						// }
					// }
					$keyword = $speciaity->keywords." in ".$cName;
				}
			}
			else if($info_type == "Doctors" || $info_type == "doctor_all" || $info_type == "doctorsIn") {
				$keyword = "Doctors in $cName";
			}
			else if($info_type == "hospital" || $info_type == "hos_all" || $info_type == "hospitalIn") {
				$keyword = "Hospital in $cName";
			}
			else if($info_type == "Clinic" || $info_type == "clinic_all" || $info_type == "clinicIn") {
				$keyword = "Clinic in $cName";
			}
			return $keyword;
		}
	}

	if (!function_exists('getTopDocSpeciality')) {
		function getTopDocSpeciality(){
			$ids = [1,49,5,27,7,131,60,20,25];
			$ids_ordered = implode(',', $ids);
			// $specialities = Speciality::whereIn('id',$ids)->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->orderBy("order_no","ASC")->get();
			$specialities = Speciality::orderBy("order_no","ASC")->limit(9)->get();
			if(count($specialities) > 0){
				foreach($specialities as $spa){
					if(!empty($spa->speciality_icon)) {
						$spa->speciality_icon = url("/")."/public/speciality-icon/".$spa->speciality_icon;
					}
					else{
						$spa->speciality_icon = null;
					}
				}
			}
			return $specialities;
		}
	}

	if (!function_exists('getPrimeDoctorsByCity')) {
		function getPrimeDoctorsByCity($city = null) {
			$dt = date('Y-m-d');
            $usersIds = ManageTrailPeriods::select('user_id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['status'=>1])->where('user_plan_id','!=',5)->whereNotNull('user_id')->whereHas('user.doctorInfo', function($q)  use ($city) {$q->Where(['city_id'=>$city]);})->groupBy('user_id')->limit(5)->where('user_id','!=',24)->pluck("user_id");

			$docs = Doctors::where(['delete_status'=>1])->whereNotNull('user_id')->whereIn('user_id',$usersIds)->get();
			if(count($docs)>0){
				$docs = bindDocData($docs);
			}
			return $docs;
		}
	}
	if (!function_exists('getGroupBySpeciality')) {
    function getGroupBySpeciality($type){
      if (Session::has('city_id')){
        $city_id = Session::get('city_id');
      }
      else{
        $city_id = 3378;
      }
      if ($type == 1) {
        $specialities = Doctors::with('docSpeciality')->select(["speciality",'id', DB::raw('count(*) as total')])->Where(['city_id'=>$city_id, 'delete_status'=>1])->whereNotNull('speciality')->groupBy('speciality')->get();
        }
      elseif ($type == 2) {
        $specialities = Doctors::with('docSpeciality')->select(["speciality",'id', DB::raw('count(*) as total')])->Where(['city_id'=>$city_id, 'practice_type' => 1, 'delete_status'=>1])->whereNotNull('speciality')->groupBy(['speciality','practice_id'])->get();
      }
      elseif ($type == 3) {
        $specialities = Doctors::with('docSpeciality')->select(["speciality",'id', DB::raw('count(*) as total')])->Where(['city_id'=>$city_id, 'practice_type' => 2, 'delete_status'=>1])->whereNotNull('speciality')->groupBy(['speciality','practice_id'])->get();
      }
      elseif ($type == 4) {
        $specialities = Doctors::with(['SymptomsSpeciality.Symptom','SymptomsSpeciality'=>function($qry){$qry->groupBy('speciality_id');}])->select(["speciality"])->Where(['city_id'=>$city_id, 'delete_status'=>1])->whereNotNull('speciality')->groupBy(['speciality'])->get();
      }

      return $specialities;
    }
  }

  if (!function_exists('getPatientFeedback')) {
    function getPatientFeedback() {
      if (Session::has('city_id')){
        $city_id = Session::get('city_id');
      }
      else{
        $city_id = 3378;
      }
      $feedback = PatientFeedback::with(['Doctors'=>function($qry) use($city_id)  {$qry->Where(["status"=>1,"delete_status"=>1,"varify_status"=>1,'claim_status'=>1,'city_id'=>$city_id]);}])->where(["status"=>1,"delete_status"=>1,"publish_admin"=>1])->whereNotNull("user_id")->groupBy(['doc_id'])->limit(2)->get();
      // foreach($feedback as $data) {
      //   $data['doc_name'] = getDoctorNameById($data->doc_id);
      // }
      return $feedback;
    }
  }

	if (!function_exists('getGenniePlan')) {
    function getGenniePlan() {
		$plans = GenniePlan::Where("delete_status",'1')->groupBy('slug')->orderBy('id', 'desc')->get();
      return $plans;
    }
  }

	if(!function_exists('GetMAC')) {
		function GetMAC() {
		   ob_start();
		   $macaddress = "";
		   if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			 system('ipconfig /all'); //Execute external program to display output
			 $mycom = ob_get_contents(); // Capture the output into a variable
			 $findme = "Physical";
			 $pmac = strpos(ob_get_contents(), $findme); // Find the position of Physical text
			 $macaddress = substr(ob_get_contents(),($pmac+36),17); // Get Physical Address
			}
			else {
			 $data = system('ifconfig');
			 $find_mac = "Ethernet HWaddr"; //find the "Physical" & Find the position of Physical text
			 $pmac = strpos(ob_get_contents(), $find_mac);
			 $macaddress = substr(ob_get_contents(),($pmac+38),17);
		   }
		   ob_clean();
		   return $macaddress;
		}
	}


  if (!function_exists('getUserAgent')) {
    function getUserAgent() {
      return  $_SERVER['HTTP_USER_AGENT'];
    }
  }
  if (!function_exists('getIP')) {
    function getIP() {
      $mainIp = '';
     /* if (getenv('HTTP_CLIENT_IP'))
        $mainIp = getenv('HTTP_CLIENT_IP');
      else if(getenv('HTTP_X_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_X_FORWARDED_FOR');
      else if(getenv('HTTP_X_FORWARDED'))
        $mainIp = getenv('HTTP_X_FORWARDED');
      else if(getenv('HTTP_FORWARDED_FOR'))
        $mainIp = getenv('HTTP_FORWARDED_FOR');
      else if(getenv('HTTP_FORWARDED'))
        $mainIp = getenv('HTTP_FORWARDED');
      else if(getenv('REMOTE_ADDR'))
        $mainIp = getenv('REMOTE_ADDR');
      else
        $mainIp = 'UNKNOWN';*/

		if(!empty($_SERVER['HTTP_CLIENT_IP'])){
			//ip from share internet
			$mainIp = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
			//ip pass from proxy
			$mainIp = $_SERVER['HTTP_X_FORWARDED_FOR'];
		}else{
			$mainIp = $_SERVER['REMOTE_ADDR'];
		}

      return $mainIp;
    }
  }
  if (!function_exists('getOS')) {
    function getOS() {
      $user_agent = getUserAgent();
      $os_platform    =   "Unknown OS Platform";
      $os_array       =   array(
        '/windows nt 10/i'     	=>  'Windows 10',
        '/windows nt 6.3/i'     =>  'Windows 8.1',
        '/windows nt 6.2/i'     =>  'Windows 8',
        '/windows nt 6.1/i'     =>  'Windows 7',
        '/windows nt 6.0/i'     =>  'Windows Vista',
        '/windows nt 5.2/i'     =>  'Windows Server 2003/XP x64',
        '/windows nt 5.1/i'     =>  'Windows XP',
        '/windows xp/i'         =>  'Windows XP',
        '/windows nt 5.0/i'     =>  'Windows 2000',
        '/windows me/i'         =>  'Windows ME',
        '/win98/i'              =>  'Windows 98',
        '/win95/i'              =>  'Windows 95',
        '/win16/i'              =>  'Windows 3.11',
        '/macintosh|mac os x/i' =>  'Mac OS X',
        '/mac_powerpc/i'        =>  'Mac OS 9',
        '/linux/i'              =>  'Linux',
        '/ubuntu/i'             =>  'Ubuntu',
        '/iphone/i'             =>  'iPhone',
        '/ipod/i'               =>  'iPod',
        '/ipad/i'               =>  'iPad',
        '/android/i'            =>  'Android',
        '/blackberry/i'         =>  'BlackBerry',
        '/webos/i'              =>  'Mobile'
      );

      foreach ($os_array as $regex => $value) {
        if (preg_match($regex, $user_agent)) {
          $os_platform    =   $value;
        }
      }
      return $os_platform;
    }
  }
  if (!function_exists('getBrowser')) {
    function getBrowser() {
      $user_agent= getUserAgent();

      $browser        =   "Unknown Browser";

      $browser_array  =   array(
        '/msie/i'       =>  'Internet Explorer',
        '/Trident/i'    =>  'Internet Explorer',
        '/firefox/i'    =>  'Firefox',
        '/safari/i'     =>  'Safari',
        '/chrome/i'     =>  'Chrome',
        '/edge/i'       =>  'Edge',
        '/opera/i'      =>  'Opera',
        '/netscape/i'   =>  'Netscape',
        '/maxthon/i'    =>  'Maxthon',
        '/konqueror/i'  =>  'Konqueror',
        '/ubrowser/i'   =>  'UC Browser',
        '/mobile/i'     =>  'Mobile Browser'
      );

      foreach ($browser_array as $regex => $value) {

        if (preg_match($regex, $user_agent)) {
          $browser    =   $value;
        }

      }

      return $browser;
    }
  }
  if (!function_exists('getDevice')) {
    function getDevice() {
      $tablet_browser = 0;
      $mobile_browser = 0;

      if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $tablet_browser++;
      }

      if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
        $mobile_browser++;
      }

      if ((strpos(strtolower($_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
        $mobile_browser++;
      }

      $mobile_ua = strtolower(substr(getUserAgent(), 0, 4));
      $mobile_agents = array(
        'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
        'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
        'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
        'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
        'newt','noki','palm','pana','pant','phil','play','port','prox',
        'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
        'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
        'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
        'wapr','webc','winw','winw','xda ','xda-');

      if (in_array($mobile_ua,$mobile_agents)) {
        $mobile_browser++;
      }

      if (strpos(strtolower(getUserAgent()),'opera mini') > 0) {
        $mobile_browser++;
                //Check for tablets on opera mini alternative headers
        $stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
          $tablet_browser++;
        }
      }

      if ($tablet_browser > 0) {
               // do something for tablet devices
        return 'Tablet';
      }
      else if ($mobile_browser > 0) {
               // do something for mobile devices
        return 'Mobile';
      }
      else {
               // do something for everything else
        return 'Computer';
      }
    }
  }
  if (!function_exists('saveUserActivity')) {
    function saveUserActivity($request, $action, $table_name=null, $table_id=null) {
      $log = [];
      $log['action'] = $action;
      $log['table_name'] = $table_name;
      $log['table_id'] = $table_id;
      $log['method'] = $request->method();
      $log['url'] = $request->fullUrl();
      $log['ip'] = $request->ip();
      $log['agent'] = $request->header('user-agent');
      $log['device'] = getDevice();
      $log['os'] = getOS();
      $log['browser'] = getBrowser();
      $log['user_id'] = auth()->check() ? auth()->user()->id : 0;
      // $location     = @file_get_contents("http://ipinfo.io/{$log['ip']}/geo");
      // $details = json_decode(file_get_contents("http://ipinfo.io/{$PublicIP}/json"));// Send to ipinfo
      // $location     = json_decode($location);
      // $location     = json_encode($location);
      $log['location'] = json_encode($request,true);

      UserActivity::create($log);
    }
  }

  if (!function_exists('thousandsCurrencyFormat')) {
    function thousandsCurrencyFormat($num) {

      if($num>1000) {

            $x = round($num);
            $x_number_format = number_format($x);
            $x_array = explode(',', $x_number_format);
            $x_parts = array('k', 'm', 'b', 't');
            $x_count_parts = count($x_array) - 1;
            $x_display = $x;
            $x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
            $x_display .= $x_parts[$x_count_parts - 1];

            return $x_display;

      }

      return $num;
    }
  }

  if (!function_exists('checkUserSubcriptionStatus')) {
		function checkUserSubcriptionStatus($id) {
			$dt = date('Y-m-d');
			$result = PlanPeriods::select('subscription_id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where('user_id', $id)->where('remaining_appointment', '!=', '0')->where('status', '1')->first();
			if(!empty($result)){
				return 1;
			}
			return 0;
		}
	}

	if(!function_exists('getUserPlan')) {
		function getUserPlan($id) {
			$plan = PlanPeriods::with("UserSubscribedPlans")->where('user_id',$id)->first();
			return $plan;
		}
	}
	if(!function_exists('getOrganizations')) {
		function getOrganizations() {
			$data = OrganizationMaster::select('id','title')->where("delete_status", 1)->orderBy('id', 'asc')->get();
			return $data;
		}
	}
	if (!function_exists('checkAppointmentIsElite')) {
		function checkAppointmentIsElite($appId,$userId = null) { 
			$app = 0;
						if(!empty($userId)){
				$userId = getParentId($userId);
				$plans = PlanPeriods::select('appointment_ids')->where('user_id',$userId)->get();
				$appointment_ids = "";
				if(count($plans)>0){
					foreach($plans as $val){
						$appointment_ids .= $val->appointment_ids.",";
					}
				}
				if(!empty($appointment_ids)){
					$appointmentIds = explode(",",$appointment_ids);
					if(count($appointmentIds)>0){
						if(in_array($appId,$appointmentIds)){
							$app = 1;
						}
					}
				}
			}
			else{
				$app = PlanPeriods::select('id')->whereRaw("find_in_set('".$appId."',plan_periods.appointment_ids)")->count();
			} 
			if($app > 0){ 
				return 1;
			}
			return 0;
		}
	}
	if(!function_exists('getCampTitleMaster')) {
		function getCampTitleMaster() {
			$data = CampTitleMaster::select('id','title')->where("delete_status", 1)->orderBy('id', 'desc')->get();
			return $data;
		}
	}
	if(!function_exists('getOrganizationIdByName')) {
		function getOrganizationIdByName($id) {
			$data = OrganizationMaster::select('title')->where("id", $id)->first();
			return @$data->title;
		}
	}
	if (!function_exists('availPackDetails')) {
		function availPackDetails($code) {
			$item = "";
			// $all_product = File::get(public_path('thyrocare-data/All.txt'));
			// if(!empty(findPackageData($all_product,$code))) {
				// $item = findPackageData($all_product,$code);
			// }
			return $item;
		}
	}

	if (!function_exists('findPackageData')) {
		function findPackageData($lab_array,$code) {
			$thyProductsArray = [];
			$lab_array = json_decode($lab_array);
			$item = "";
			$testProducts = @$lab_array->MASTERS->TESTS;
			$profileProducts = @$lab_array->MASTERS->PROFILE;
			$offerProducts = @$lab_array->MASTERS->OFFER;

			if(!empty($testProducts) > 0){
				foreach($testProducts as $struct){
					$thyProductsArray[] = $struct;
				}
			}
			if(!empty($profileProducts) > 0){
				foreach($profileProducts as $struct){
					$thyProductsArray[] = $struct;
				}
			}
			foreach($thyProductsArray as $product) {
				if ($product->code == $code) {
					$item = $product;
					break;
				}
			}
			return $item;
		}
	}
	if (!function_exists('getRefdocDetails')) {
		function getRefdocDetails($id)
		{
		   $doctor =   ReferralsMaster::where('id',$id)->first();
			 return $doctor;
		}
	}

	if (!function_exists('getAppointmentTxnDetails')) {
		function getAppointmentTxnDetails($id) {
			$app =  AppointmentTxn::where('appointment_id',$id)->first();
			return $app;
		}
	}

  if (!function_exists('getCurrentDoctor')) {
      function getCurrentDoctor($docid=null)
      {
        $user_id = Auth::id();
        $user = Auth::user();
        if (!empty($docid)) {
          $user_id = $docid;
        }
        //echo $user;die;
        $role = 2;
        if ($user->hasRole('doctor')) {
            $details = DoctorsInfo::where('user_id', $user_id)->first();
            $role = $details->manage_eye;
        }
        return $role;
      }
  }

  if(!function_exists('getFreqDurType')) {
    function getFreqDurType($id, $type) {
      // Frequency Type
      if ($type == 1) {
        $FrequencyType = array("1"=>"Daily", "2"=>"Weekly", "3"=>"Monthly", "4"=>"Yearly",'5'=>'Stat','6'=>'PRN');
        return $FrequencyType[$id];
      }
      // Durtions Type
      elseif ($type == 2) {
        $FrequencyType = array("1"=>"Day", "2"=>"Week", "3"=>"Month", "4"=>"Year");
        return $FrequencyType[$id];
      }
    }
  }

  if (!function_exists('getAccessVitals')) {
    function getAccessVitals($docid = null)
    {
        $user = Auth::id();
        if (!empty($docid)) {
          $user = $docid;
        }
      $practice =  RoleUser::select(['practice_id'])->where(['user_id'=>$user])->first();
      $VitalsPermissions = VitalsPermissions::select('vitals_access')->where('practice_id',$practice->practice_id)->where(['status'=>1,'delete_status'=>1])->first();
      if (count($VitalsPermissions) == 0) {
        $AccessVitals = array('1','2','3','4','5','6','7','8');
      }
      else {
        $AccessVitals = json_decode($VitalsPermissions->vitals_access);
      }
      return $AccessVitals;
    }
  }
	if (!function_exists('checkFirstTeleAppointment')) {
		function checkFirstTeleAppointment($pId,$fee = null){
			$success = 0;
			// $teleFee = getSetting("tele_first_appt_price_free")[0];
			// $appointment = Appointments::where(['pId'=>$pId,"delete_status"=>1,"appointment_confirmation"=>1,"type"=>3])->count();
			// if($appointment > 0  || $fee > $teleFee) {
				// $success = 0;
			// }
			return $success;
		}
	}
	
	if (!function_exists('getPrintDetails')) {
	  function getPrintDetails($module = null,$user = null) {
		$practice = RoleUser::select(['user_id','role_id','practice_id'])->where(['user_id'=>$user])->first();
		$printData = PrintSettings::where(['user_id'=>$practice->practice_id])->first();
		if($module==null){$module=1;}
		if($printData){
		  if($module == 1)
		  {//clinic note
			  $arr = array('practice_id'=>$printData->user_id,'id'=>$printData->id,'print_layout'=>$printData->note_print_layout,'print_layout_settings'=>json_decode($printData->note_print_settings,true));
			  return $arr;
		  }
		  else if($module == 2)
		  {//billing
			  $arr = array('practice_id'=>$printData->user_id,'id'=>$printData->id,'print_layout'=>$printData->billing_print_layout,'print_layout_settings'=>json_decode($printData->billing_print_settings,true));
			  return $arr;
		  }
		  else if($module == 3)
		  {//pharmacy
			  $arr = array('practice_id'=>$printData->user_id,'id'=>$printData->id,'print_layout'=>$printData->pharmacy_print_layout,'print_layout_settings'=>json_decode($printData->pharmacy_print_settings,true));
			  return $arr;
		  }
		  else if($module == 4)
		  {//laboratory
			  $arr = array('practice_id'=>$printData->user_id,'id'=>$printData->id,'print_layout'=>$printData->laboratory_print_layout,'print_layout_settings'=>json_decode($printData->laboratory_print_settings,true));
			  return $arr;
		  }
		  else if($module == 5)
		  {//radiology
			  $arr = array('practice_id'=>$printData->user_id,'id'=>$printData->id,'print_layout'=>$printData->radiology_print_layout,'print_layout_settings'=>json_decode($printData->radiology_print_settings,true));
			  return $arr;
		  }
		}
		return $module;
	  }
	}
	
	if (!function_exists('get_patient_reg_no')){
		function get_patient_reg_no($pid,$user_id=null){
		  if(!empty($pid)){
			$user = Auth::id();
			if(!empty($user_id)){
				$user = $user_id;
			}
			
			$practice =  RoleUser::select(['user_id','role_id','practice_id'])->where(['user_id'=>$user])->first();
			$query = PatientRagistrationNumbers::where(['pid'=>$pid,'added_by'=>$practice->practice_id,'status'=>1])->first();

			$reg_no = 0;
			if(!empty($query)){
			  $reg_no = $query->reg_no;
			}
			return $reg_no;
		}
		else return 0;
		}
	}
	  if (!function_exists('getDoctorName')) {

        function getDoctorName($id)
        {
          $doctor = DoctorsInfo::select('first_name','last_name')->where('user_id',$id)->first();
          $Name ='';
          if(!empty($doctor)){
            $Name = $doctor->first_name." ".$doctor->last_name;
          }
          return $Name;
        }
    }
	if (!function_exists('getAllDoctorsUnderPractice')) {
		function getAllDoctorsUnderPractice($practice_id = null, $clinic_name = null){
			$total = 1;
			if(!empty($practice_id)) {
				$total = Doctors::select('id')->Where(['practice_id'=>$practice_id])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->count();
			}
			else if(!empty($clinic_name)) {
				$total = Doctors::select('id')->where('clinic_name', 'like', '%'.$clinic_name.'%')->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->count();
			}
			return $total;
		}
	}
	if (!function_exists('getSetting')) {
        function getSetting($key) {
          $setting = Settings::where('key',$key)->first();
		  $value = "";
		  if(!empty($setting)) {
			  $value = explode(",",$setting->value);
			  Log::info('$value', [$value]);
		  }
          return $value;
        }
    }
	
	if (!function_exists('getDoctorSulgById')) {
		function getDoctorSulgById($id){
		  $slug = DoctorSlug::select(["name_slug"])->where('doc_id',$id)->first();
		  return $slug->name_slug;
		}
	}
	
	if (!function_exists('getSpecialityIconById')) {
		function getSpecialityIconById($id){
			$speclty = Speciality::select("speciality_icon")->where('id',$id)->first();
			if(!empty($speclty) && $speclty->speciality_icon){
				return url("/")."/public/speciality-icon/".$speclty->speciality_icon;
			}
			else {
				return url("/")."img/doctor-ico.png";
			}
		}
	}
	if (!function_exists('getsystemInfo')) {
        function getsystemInfo($server) {
			if(isMobileDevice()){
				$device = 'MOBILE';
			}
			else{
				$device = 'SYSTEM';
			}
			return array('os'=>'','device'=>$device);
		}
	}
	if (!function_exists('isMobileDevice')) {
		function isMobileDevice() {
			$tablet_browser = 0;
			$mobile_browser = 0;
			if(preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower(@$_SERVER['HTTP_USER_AGENT']))) {
				$tablet_browser++;
			}
			if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower(@$_SERVER['HTTP_USER_AGENT']))) {
				$mobile_browser++;
			}
			 
			if ((strpos(strtolower(@$_SERVER['HTTP_ACCEPT']),'application/vnd.wap.xhtml+xml') > 0) or ((isset($_SERVER['HTTP_X_WAP_PROFILE']) or isset($_SERVER['HTTP_PROFILE'])))) {
				$mobile_browser++;
			}
			 
			$mobile_ua = strtolower(substr(@$_SERVER['HTTP_USER_AGENT'], 0, 4));
			$mobile_agents = array(
				'w3c ','acs-','alav','alca','amoi','audi','avan','benq','bird','blac',
				'blaz','brew','cell','cldc','cmd-','dang','doco','eric','hipt','inno',
				'ipaq','java','jigs','kddi','keji','leno','lg-c','lg-d','lg-g','lge-',
				'maui','maxo','midp','mits','mmef','mobi','mot-','moto','mwbp','nec-',
				'newt','noki','palm','pana','pant','phil','play','port','prox',
				'qwap','sage','sams','sany','sch-','sec-','send','seri','sgh-','shar',
				'sie-','siem','smal','smar','sony','sph-','symb','t-mo','teli','tim-',
				'tosh','tsm-','upg1','upsi','vk-v','voda','wap-','wapa','wapi','wapp',
				'wapr','webc','winw','winw','xda ','xda-');
			 
			if (in_array($mobile_ua,$mobile_agents)) {
				$mobile_browser++;
			}
			 
			if (strpos(strtolower(@$_SERVER['HTTP_USER_AGENT']),'opera mini') > 0) {
				$mobile_browser++;
				$stock_ua = strtolower(isset($_SERVER['HTTP_X_OPERAMINI_PHONE_UA'])?$_SERVER['HTTP_X_OPERAMINI_PHONE_UA']:(isset($_SERVER['HTTP_DEVICE_STOCK_UA'])?@$_SERVER['HTTP_DEVICE_STOCK_UA']:''));
				if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $stock_ua)) {
				  $tablet_browser++;
				}
			}
			if ($tablet_browser > 0) {
			   // do something for tablet devices
			   // print 'is tablet';
			   return false;
			}
			else if ($mobile_browser > 0) {
			   // do something for mobile devices
			   // print 'is mobile';
			   return true;
			}
			else {
			   // do something for everything else
			   // print 'is desktop';
				return false;
			}
		}
	}
	if (!function_exists('latestAppointmentFeedback')) {
		function latestAppointmentFeedback(){
			$user_id = Auth::id();
			$res = null;
			if(!empty($user_id)){
				$date = date('Y-m-d');
				$appointment = Appointments::where(['pId'=>$user_id,"delete_status"=>1,"appointment_confirmation"=>1])->whereRaw('date(start) >= ?', [$date])->first();
				if(!empty($appointment)) {
					$res = array();
					$feedback = PatientFeedback::where("appointment_id",$appointment->id)->count();
					if($feedback == 0) {	
						$success = true;	
						$res['doc_id'] = $appointment->doc_id;
						$res['clinic_name'] = @$appointment->practiceDetails->clinic_name;
						$res['appointment_id'] = $appointment->id;
					}
				}
			}
			return $res;
		}
	}
	if (!function_exists('getTermsBySLug')) {
		function getTermsBySLug($slug,$type=null){
			$description = "";
			$page = "";
			if(!empty($type)){
				$page = Pages::where(['slug'=>$slug,"lng"=>$type])->first();
			}
			if(empty($page)){
				$page = Pages::where(['slug'=>$slug,"lng"=>"en"])->first();
			}
			if(!empty($page)){
				$description = $page->description;
			}
			return $description;
		}
	}
	if (!function_exists('getDocSpeciality')) {
		function getDocSpeciality(){
			$sec_arr = [];
			$spec =  Speciality::orderBy("order_no","ASC")->whereNotIn('id',[203])->limit(28)->get();
			foreach($spec as $value){
				if(!empty($value->speciality_icon)){
					$value['speciality_icon'] = url("/")."/public/speciality-icon/".$value->speciality_icon;
				}
				if(!empty($value->speciality_image)){
					$value['speciality_image'] = url("/")."/public/speciality-images/".$value->speciality_image;
				}
				$sec_arr[] = $value;
			}
			$sec_arr = array_chunk($sec_arr,4);
			return $sec_arr;
		}
	}
	
	if (!function_exists('getDocSpecialityMobile')) {
		function getDocSpecialityMobile(){
			$spec = Speciality::orderBy("order_no","ASC")->limit(15)->get();
			foreach($spec as $value){
				if(!empty($value->speciality_icon)){
					$value['speciality_icon'] = url("/")."/public/speciality-icon/".$value->speciality_icon;
				}
				if(!empty($value->speciality_image)){
					$value['speciality_image'] = url("/")."/public/speciality-images/".$value->speciality_image;
				}
			}
			return $spec;
		}
	}
	
	if (!function_exists('getAllDocSpecialityMobile')) {
		function getAllDocSpecialityMobile(){
			$spec =  Speciality::orderBy("order_no","ASC")->whereNotIn('id',[203])->limit(28)->get();
			foreach($spec as $value){
				if(!empty($value->speciality_icon)){
					$value['speciality_icon'] = url("/")."/public/speciality-icon/".$value->speciality_icon;
				}
				if(!empty($value->speciality_image)){
					$value['speciality_image'] = url("/")."/public/speciality-images/".$value->speciality_image;
				}
			}
			return $spec;
		}
	}
	if (!function_exists('checkCountry')) {
		function checkCountry(){
			 $code = 'IN';
			if($code == 'IN'){
                return true;
			}else{
                return false;
			}     
		}
	}
	
if (!function_exists('get_patient_dobByAge')) {
	function get_patient_dobByAge($age,$type){
		$dob = "";
		if($type == "1"){ 
			$dob = date("d-m-Y", strtotime("-".$age." year"));
		}
		if($type == "2"){
			$dob = date("d-m-Y", strtotime("-".$age." months"));
		}
		if($type == "3"){
			$dob = date("d-m-Y", strtotime("-".$age." day"));
		}
		return $dob;
	}
}

if(!function_exists('getAppoimentDurations')) {
    function getAppoimentDurations($user_id = null) {
      $durations = AppointmentDurationMaster::select('*')->where(['status'=>1,'delete_status'=>1])->whereIn('added_by', array(1,$user_id))->get();
      return $durations;
    }
}

if(!function_exists('checkAvailableSlot')) {
    function checkAvailableSlot($start,$doc_id,$increment_time) {
		$end = date('Y-m-d H:i:s',strtotime($start)+$increment_time); //echo $start." ".$end; die;
		$qry = Appointments::select("id")->where(['delete_status'=>1,'doc_id'=>$doc_id])->whereBetween('start',[$start, $end]);
		// dd($qry->get());
		if($qry->count() > 0) {
			return checkAvailableSlot(date('Y-m-d H:i:s',strtotime($start)+$increment_time),$doc_id,$increment_time);
		}
		else{
			return date("H:i:s",strtotime($start)+$increment_time);
		}
    }
}

if(!function_exists('getTotalAppointmentByUser')){
	function getTotalAppointmentByUser($id) {
		return Appointments::select("id")->where('pId',$id)->where("delete_status",1)->whereIn('app_click_status',array(5,6))->where("added_by","!=",24)->count();
	}
}
if(!function_exists('getTotalChild')){
	function getTotalChild($id) {
		return User::select('id')->where("parent_id",$id)->count();
	}
}
if (!function_exists('charAt')) {
   function charAt($str, $pos)
  {
    return mb_substr($str,$pos, 1, 'UTF-8');
  }
}
if (!function_exists('indexOf')) {
   function indexOf($string, $value) {
      $strlen = mb_strlen($string);
      while ($strlen) {
          $array[] = mb_substr($string,0,1,"UTF-8");
          $string = mb_substr($string,1,$strlen,"UTF-8");
          $strlen = mb_strlen($string);
      }
      $index = array_search($value,$array);
      if (!empty($index)) {
         return $index;
      }
      else{
        return '-1';
      }
    }
}
if (!function_exists('HindiStringArray')) {
   function HindiStringArray($string) {
      $strlen = mb_strlen($string);
      while ($strlen) {
          $array[] = mb_substr($string,0,1,"UTF-8");
          $string = mb_substr($string,1,$strlen,"UTF-8");
          $strlen = mb_strlen($string);
      }
      return $array;
  }
}
if (!function_exists('searchMultipleValue')) {
   function searchMultipleValue($array, $search_list) {
      // Create the result array
      $result = array();
      // Iterate over each array element
      foreach ($array as $key => $value) {
          $nextIndex = $key + 1;
          $arrayCount = count($array) - 1;
          // Iterate over each search condition
          if (!empty($value) && $key < $arrayCount) {
             if ($value == $search_list[0] && $array[$nextIndex] == $search_list[1] )
            {
              $result[] = $key;
            }
          }
          // Append array element's key to the
      }
      // Return result
      return $result;
  }
}
if (!function_exists('UnicodeToKrutiDev')) {
   function UnicodeToKrutiDev($HindiString)
   {

   $array_one = array("‘",   "’",   "“",   "”",   "(",    ")",   "{",    "}",   "=", "।",  "?",  "-",  "µ", "॰", ",", ".", "् ", "०",  "१",  "२",  "३",     "४",   "५",  "६",   "७",   "८",   "९", "x", "फ़्",  "क़",  "ख़",  "ग़", "ज़्", "ज़",  "ड़",  "ढ़",   "फ़",  "य़",  "ऱ",  "ऩ", "त्त्",   "त्त",     "क्त",  "दृ",  "कृ", "ह्न",  "ह्य",  "हृ",  "ह्म",  "ह्र",  "ह्",   "द्द",  "क्ष्", "क्ष", "त्र्", "त्र","ज्ञ", "छ्य",  "ट्य",  "ठ्य",  "ड्य",  "ढ्य", "द्य","द्व", "श्र",  "ट्र",    "ड्र",    "ढ्र",    "छ्र",   "क्र",  "फ्र",  "द्र",   "प्र",   "ग्र", "रु",  "रू", "्र", "ओ",  "औ",  "आ",   "अ",   "ई",   "इ",  "उ",   "ऊ",  "ऐ",  "ए", "ऋ", "क्",  "क",  "क्क",  "ख्",   "ख",    "ग्",   "ग",  "घ्",  "घ",    "ङ", "चै",   "च्",   "च",   "छ",  "ज्", "ज",   "झ्",  "झ",   "ञ", "ट्ट",   "ट्ठ",   "ट",   "ठ",   "ड्ड",   "ड्ढ",  "ड",   "ढ",  "ण्", "ण", "त्",  "त",  "थ्", "थ",  "द्ध",  "द", "ध्", "ध",  "न्",  "न", "प्",  "प",  "फ्", "फ",  "ब्",  "ब", "भ्",  "भ",  "म्",  "म", "य्",  "य",  "र",  "ल्", "ल",  "ळ",  "व्",  "व", "श्", "श",  "ष्", "ष",  "स्",   "स",   "ह", "ऑ",   "ॉ",  "ो",   "ौ",   "ा",   "ी",   "ु",   "ू",   "ृ",   "े",   "ै", "ं",   "ँ",   "ः",   "ॅ",    "ऽ",  "् ", "्");


   $array_two = array("^", "*",  "Þ", "ß", "¼", "½", "¿", "À", "¾", "A", "\\", "&", "&", "Œ", "]","-","~ ", "å",  "ƒ",  "„",   "…",   "†",   "‡",   "ˆ",   "‰",   "Š",   "‹","Û", "¶",   "d",    "[k",  "x",  "T",  "t",   "M+", "<+", "Q",  ";",    "j",   "u", "Ù",   "Ùk",   "ä",    "–",   "—", "à",   "á",    "â",   "ã",   "ºz",  "º",   "í", "{", "{k",  "«", "=","K", "Nî",   "Vî",    "Bî",   "Mî",   "<î", "|","}", "J",   "Vª",   "Mª",  "<ªª",  "Nª",   "Ø",  "Ý",   "æ", "ç", "xz", "#", ":", "z", "vks",  "vkS",  "vk",    "v",   "bZ",  "b",  "m",  "Å",  ",s",  ",",   "_", "D",  "d",    "ô",     "[",     "[k",    "X",   "x",  "?",    "?k",   "³", "pkS",  "P",    "p",  "N",   "T",    "t",   "÷",  ">",   "¥", "ê",      "ë",      "V",  "B",   "ì",       "ï",     "M",  "<",  ".", ".k", "R",  "r",   "F", "Fk",  ")",    "n", "/",  "/k",  "U", "u", "I",  "i",   "¶", "Q",   "C",  "c",  "H",  "Hk", "E",   "e", "¸",   ";",    "j",  "Y",   "y",  "G",  "O",  "o", "'", "'k",  "\"", "\"k", "L",   "l",   "g", "v‚",    "‚",    "ks",   "kS",   "k",     "h",    "q",   "w",   "`",    "s",    "S", "a",    "¡",    "%",     "W",   "·",   "~ ", "~");

    $HindiString = str_replace("क़","क़", $HindiString);
    $HindiString = str_replace("ख़‌","ख़", $HindiString);
    $HindiString = str_replace("ग़","ग़", $HindiString);
    $HindiString = str_replace("ज़","ज़", $HindiString);
    $HindiString = str_replace("ड़","ड़", $HindiString);
    $HindiString = str_replace("ढ़","ढ़", $HindiString);
    $HindiString = str_replace("ऩ","ऩ", $HindiString);
    $HindiString = str_replace("फ़","फ़", $HindiString);
    $HindiString = str_replace("य़","य़", $HindiString);
    $HindiString = str_replace("ऱ","ऱ", $HindiString);

    $position_of_f = indexOf($HindiString, "ि");

    while ($position_of_f != -1 ) {
      $char_at = $position_of_f - 1;
      $character_left_to_f = charAt($HindiString, $char_at);
      $HindiString = str_replace($character_left_to_f.'ि','f'.$character_left_to_f, $HindiString);
      $position_of_f = $position_of_f - 1;
      while ((charAt($HindiString, $position_of_f-1) == "्") & ($position_of_f != 0)) {
       $string_to_be_replaced = charAt($HindiString, $position_of_f-2). "्";
       $HindiString = str_replace($string_to_be_replaced.'f','f'.$string_to_be_replaced, $HindiString);
       $position_of_f = $position_of_f - 2;
      }
     $position_of_f = indexOf($HindiString, "ि"); // search for f ahead of the current
    }

    $set_of_matras = "ािीुूृेैोौं:ँॅ";


    $searchValue = array('र','्');
        // dd($HindiString);
    $result = searchMultipleValue(HindiStringArray($HindiString), $searchValue);
    if (count($result) > 0) {
      foreach ($result as $key => $value) {
        $position_of_half_R = $value;
        $probable_position_of_Z = $position_of_half_R + 2;
        $character_right_to_probable_position_of_Z = charAt($HindiString, $probable_position_of_Z+2);
        while (indexOf($set_of_matras, $character_right_to_probable_position_of_Z) != -1) {
          $probable_position_of_Z = $probable_position_of_Z + 1;
          $character_right_to_probable_position_of_Z = charAt($HindiString, $probable_position_of_Z+1);
        }
        $string_to_be_replaced_Z = charAt($HindiString, $probable_position_of_Z);
        $HindiString = str_replace('र्'.$string_to_be_replaced_Z,$string_to_be_replaced_Z.'Z', $HindiString);
      }
    }

    for ($input_symbol_idx=0; $input_symbol_idx < count($array_one); $input_symbol_idx++) {
      $HindiString = str_replace($array_one[$input_symbol_idx], $array_two[$input_symbol_idx], $HindiString);
    }

    return $HindiString;

   }
}
if(!function_exists('getSalesTeam')) {
   function getSalesTeam() {
	 $data = SalesTeam::orderBy('id','ASC')->get();
	 return $data;
   }
}

if(!function_exists('checkOpdTimeById')) {
   function checkOpdTimeById($doc_id,$current_date,$time,$duration = 5) {
	 $doctor = Doctors::select(["opd_timings","slot_duration"])->where(['id'=> $doc_id])->first();
	 $nameOfDay = date('N', strtotime($current_date));
	 if($nameOfDay == "7"){
		$nameOfDay = "0";
	 }
	 if(!empty($doctor->opd_timings)) {
		 foreach(json_decode($doctor->opd_timings) as $key=>$schedule) { 
			if(!empty($schedule->days) && in_array($nameOfDay,$schedule->days)) { 
				foreach($schedule->timings as $k=>$v) {  //echo $v->start_time." ".$v->end_time;die;
					if(strtotime($v->start_time) <= $time && $time < strtotime($v->end_time)){ //echo $k;die;
						if(isset($v->teleconsultation) &&  $v->teleconsultation == "1") {
							$duration = $v->tele_appt_duration;
							break;
						}
						else if(isset($v->teleconsultation) &&  $v->teleconsultation == "0") {
							$duration = $doctor->slot_duration;
							break;
						}
					}
				}
			}
		}
	 }
	 return $duration;
   }
}

if (!function_exists('getCouponById')) {
    function getCouponById($id){
       $idss = Coupons::select('coupon_code')->where('id',$id)->first();
	     return @$idss->coupon_code;
    }
}  
if (!function_exists('getTopLocality')) {
    function getTopLocality($id) {
      $locality = CityLocalities::where('city_id',$id)->orderBy("top_status","DESC")->limit(8)->get();
      return $locality;
    }
}

if(!function_exists('getTotalAppointmentByDate')) {
	function getTotalAppointmentByDate() {
		$today = date('Y-m-d');
		return Appointments::select('id')->whereIn('app_click_status',array('5','6'))->where("delete_status",1)->where("added_by","!=",24)->whereDate('start','=',$today)->whereDate('created_at', '!=', $today)->count();
	}
}
if (!function_exists('checkUserSubcription')) {
	function checkUserSubcription($id) {
		$dt = date('Y-m-d');
		$result = UsersSubscriptions::select('id')->where('user_id',$id)->where('order_status','1')->count();
		if($result > 0){
			return 1;
		}
		return 0;
	}
}

if(!function_exists('getNameByLoginId')) {
function getNameByLoginId($id) {
   $query = Admin::select('name')->where('id',$id)->first();
   return @$query->name;
}
}

if(!function_exists('getNameLocationByLoginId')) {
	function getNameLocationByLoginId($id) {
		$query = Admin::select('name','city')->where('id',$id)->first();
		return $query;
	}
}
if(!function_exists('getPath')) {
function getPath($path) {
	// $path = "";
	// if(Storage::disk('s3')->exists($path)) {
		// $path = Storage::disk('s3')->url($path);
		$path = Storage::disk('s3')->temporaryUrl($path, Carbon::now()->addHour(24));
	// }

  return $path;
}
}





if(!function_exists('countExistsAppointment')) {
	function countExistsAppointment($pid,$added_by) {
		return Appointments::where(['pId'=>$pid])->where('added_by','!=',$added_by)->where("delete_status",1)->count();
	}
}

if(!function_exists('covidhelpersCount')) {
	function covidhelpersCount() {
		return CovidHelp::select('id')->count();
	}
}
if (!function_exists('checkCovidSubscription')) {
	function checkCovidSubscription($mobile) {
		$id = @User::where('mobile_no',$mobile)->where("parent_id",0)->first()->id;
		if(checkUserSubcription($id) == 0){
			return "NO";
		}else { return "YES";}
	}
}
if (!function_exists('getTestNameByIdEng')) {
	function getTestNameByIdEng($test_id){
		 $dataArr = array('1'=>'Fasting','2'=>'Before Breakfast','3'=>'After Breakfast','4'=>'Before Lunch','5'=>'After Lunch','6'=>'After Dinner','7'=>'Before Dinner','8'=>'Before Sleep','0'=>'Other');
		 return $dataArr[$test_id];
	}
}
if (!function_exists('getTestNameByIdHin')) {
	function getTestNameByIdHin($test_id){
		 // $dataArr = array('1'=>'उपवास','2'=>'नाश्ते से पहले','3'=>'नाश्ते के बाद','4'=>'दोपहर के भोजन से पहले','5'=>'दोपहर के भोजन के बाद','6'=>'रात के खाने के बाद','7'=>'रात के खाने से पहले','8'=>'सोने से पहले','0'=>'अन्य');
		 $dataArr = array('1'=>'miokl','2'=>"uk'rs ls igys",'3'=>"uk'rs ds ckn",'4'=>'nksigj ds Hkkstu ls igys','5'=>'nksigj ds Hkkstu ds ckn','6'=>'jkr ds [kkus ds ckn','7'=>'jkr ds [kkus ls igys','8'=>'lksus ls igys','0'=>'vU;');
		 return $dataArr[$test_id];
	}
}
if (!function_exists('checkUserSpecialistAppt')) {
	function checkUserSpecialistAppt($id) {
		$app = PlanPeriods::select('specialist_appointment_cnt')->whereRaw("find_in_set('".$id."',plan_periods.appointment_ids)")->first();
		if(!empty($app)) {
			return $app->specialist_appointment_cnt;
		}
		return 0;
	}
}
if (!function_exists('checkFirstDirectTeleAppointment')) {
	function checkFirstDirectTeleAppointment(){
		$success = 1;
		$is_peak = 0;
		if(isset(Auth::user()->mobile_no)) {
			$p_ids = User::select("pId")->where(["mobile_no"=>Auth::user()->mobile_no])->pluck("pId")->toArray();
			$appointment = Appointments::select('id')->whereIn('pID',$p_ids)->where(["delete_status"=>1,"appointment_confirmation"=>1,"type"=>3])->count();
			if($appointment > 0) {
				$success = 0;
				$dt = date('Y-m-d');
				$is_subscribed = PlanPeriods::select('id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where('user_id',Auth::id())->where('remaining_appointment', '!=', '0')->where('status', '1')->count();	
				if($is_subscribed > 0){
					$success = 1;
				}
				else  if("22" <= date("H") ||  date("H") < "10") {
					// $is_peak = 1;
					// $success = 0;
				}
			}else{
				$dt = date('Y-m-d');
				$is_subscribed = PlanPeriods::select('id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where('user_id',Auth::id())->where('remaining_appointment', '!=', '0')->where('status', '1')->count();	
				if($is_subscribed > 0){
					$success = 1;
				}
				else  if("22" <= date("H") ||  date("H") < "10") {
					// $is_peak = 1;
					// $success = 0;
				}
			}
			$lab = LabOrders::select("is_free_appt")->where(["user_id"=>Auth::id(),"status"=>1,"is_free_appt"=>1])->first();
			if(!empty($lab) && $is_peak == 0) {
				$success = 1;
			}
		}
		return $success;
	}
}
if (!function_exists('checkFirstDirectTeleAppointmentPaytm')) {
	function checkFirstDirectTeleAppointmentPaytm(){
		$success = 1;
		if(isset(Auth::user()->mobile_no)) {
			$p_ids = User::select("pId")->where(["mobile_no"=>Auth::user()->mobile_no])->pluck("pId")->toArray();
			$appointment = Appointments::whereIn('pID',$p_ids)->where(["delete_status"=>1,"appointment_confirmation"=>1,"type"=>3])->count();
			if($appointment > 0) {
				$success = 0;
				$dt = date('Y-m-d');
				$is_subscribed = PlanPeriods::select('id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where('user_id',Auth::id())->where('remaining_appointment', '!=', '0')->where('status', '1')->count();	
				if($is_subscribed > 0){
					$success = 1;
				}
				// else  if("22" <= date("H") ||  date("H") < "10") {
					// $success = 0;
				// }
			}
		}
		return $success;
	}
}
if (!function_exists('checkPatientCount')) {
	function checkPatientCount($id) {
		$result = UsersSubscriptions::with("UserSubscribedPlans","PlanPeriods")->where('user_id',$id)->where('order_status','1')->whereHas('PlanPeriods', function($q){
          $q->Where('status', 1);
        })->first();
		 //pr($result);
		$max_patient_count = 0;
		if(!empty($result)){
			if(count($result->UserSubscribedPlans)>0){
				foreach($result->UserSubscribedPlans as $raw) {
					$meta_data = json_decode($raw->meta_data,true);
					if(isset($meta_data['max_patient_count'])){
                        $max_patient_count = $meta_data['max_patient_count'];
					}else{
						$max_patient_count = 6;
					}
				}
			}
		}
		//echo $max_patient_count;die;
		return $max_patient_count;
	}
}
if(!function_exists('getParentId')){
	function getParentId($id) {
		$uid = "";
		$user = User::select('parent_id')->where("id",$id)->first();
		// $id = @User::where('mobile_no',$mobile)->where("parent_id",0)->first()->id;
		if(!empty($user) && $user->parent_id != '0') {
			// $uid = $user->parent_id;
			$uid = User::select('id')->where("pId",@$user->parent_id)->first()->id;
		}
		else{
			$uid = $id;
		}
		return $uid;
	}
}


if(!function_exists('getDoctorDocumentType')) {
 function getDoctorDocumentType($type=null) {
	$data = array('1' => 'Profile Picture','2' => 'Clinic Picture','3' => 'Registration Certificate','4' => 'Degree','5' => 'Address Proof','6' => 'Aadhar Card','7' => 'Pan Card','8' => 'Driving Licence');
	if(!empty($type)) {
		$data = $data[$type];
	}
	return $data;
 }
}


if(!function_exists('getDocTypeClass')) {
 function getDocTypeClass($type) {
  $data = array('1' => 'profilePic','2' => 'clinicPic','3' => 'regCet','4' => 'degree','5' => 'addPrf','6' => 'Aadhar Card','7' => 'Pan Card','8' => 'Driving Licence');
  if (!empty($type)) {
	$data = $data[$type];
  }
  return $data;
 }
}
if(!function_exists('checkAppointmentIsExist')){
	function checkAppointmentIsExist($id) {
		$appt =  Appointments::where('id',$id)->where("delete_status",1)->count();
		if($appt > 0){
			return true;
		}
		else {
			return false;
		}
	}
}
if(!function_exists('getTotApptByAppt')){
	function getTotApptByAppt($ids) {
		return Appointments::whereIn('id',$ids)->where("delete_status",1)->count();
	}
}
if(!function_exists('getPlanIdToSlug')){
	function getPlanIdToSlug($slug) {
		$ids =  GenniePlan::where('slug',$slug)->pluck("id");
		return $ids;
	}
}
if(!function_exists('getDiscount')) {
	function getDiscount($type,$discount,$plan_id) {
	if($type == '2') {
		$plan = GenniePlan::where('id',$plan_id)->first();
		$planRate = $plan->price - $plan->discount_price;
		$finalRate = ($planRate * $discount) / 100;
	}
	else{
		$finalRate = $discount;
	}
	return $finalRate;
	}
}
if(!function_exists('checkRefCodeIsExist')){
	function checkRefCodeIsExist($refCode) {
		$code =  ReferralMaster::select("id")->where('code',$refCode)->where('delete_status',1)->count();
		if($code > 0){
			return true;
		} else return false;
	}
}
if(!function_exists('getQuesByType')){
	function getQuesByType($type,$lang = null,$id=null) {
		if($lang == null){
			$lang = 1;
		}
		else{
			$lang = $lang;
		}
		$query = HealthQuestion::where(["lang"=>$lang,'type'=>$type,'delete_status'=>1]);
		if(!empty($id)){
			$query->where('id','!=',$id);
		}
		$ques = $query->get();
		return $ques;
	}
}
if(!function_exists('getPlanFeebyDoc')){
	function getPlanFeebyDoc($user_id,$fee) {
		$doc =  Doctors::select("id")->with('DoctorData')->where('user_id',$user_id)->first();
		if(!empty($doc) && !empty($doc->DoctorData) && !empty($doc->DoctorData->plan_consult_fee)) {
			$fee = $doc->DoctorData->plan_consult_fee;
		}
		return $fee;
	}
}
if(!function_exists('checkTextHindi')){
	function checkTextHindi($string) {
		$regex = '~^[a-zA-Z]+$~';
		if(preg_match($regex, $string)) {
			return false;
		} else {
			return true;
		}
	}
}
if(!function_exists('getFollowUpCount')){
	function getFollowUpCount($user_id) {
		$followup_count = null;
		$doc =  Doctors::with('DoctorData')->select("id")->where('user_id',$user_id)->first();
		if(!empty($doc) && !empty($doc->DoctorData) && !empty($doc->DoctorData->followup_count)) {
			$followup_count = $doc->DoctorData->followup_count;
		}
		return $followup_count;
	}
}
if(!function_exists('followupExist')){
	function followupExist($appDate,$count,$apptId,$doc_id,$patient_id) {
		$flag = false;
		if(!empty($count)){
			$appts = AppointmentOrder::where(['doc_id'=>$doc_id,'patient_id'=>$patient_id])->get();
			if(count($appts)){
				foreach($appts as $raw){
					$meta_data = json_decode($raw->meta_data,true);
					if(isset($meta_data['apptId']) && $meta_data['apptId'] == $apptId){
						$flag = true;
					}
				}
			}
			$acDt = date('Y-m-d', strtotime($appDate. ' + '.$count.' days'));
			if($acDt >= date('Y-m-d') && $flag == false) {
				return ['success'=>true,'flag'=>$flag];
			}
			else return ['success'=>false,'flag'=>$flag];
		}
		else return ['success'=>false,'flag'=>$flag];
	}
}
if(!function_exists('getCouponCodes')){
	function getCouponCodes($type) {
		$dt = date('Y-m-d');
		$arr = [];
		$coupons = Coupons::where(['type'=>$type])->where('coupon_last_date','>',$dt)->where(['is_show'=>'1','status' => '1','delete_status'=>'1'])->orderBy("id","desc")->get();
		if(count($coupons)>0){
			foreach($coupons as $query){
				$arr[] = array('status'=>'1','coupon_id'=>$query->id,'coupon_rate'=>$query->coupon_discount,'other_text'=>$query->other_text,'coupon_code'=>$query->coupon_code,'apply_type'=>$query->apply_type,'coupon_discount_type'=>$query->coupon_discount_type,'term_conditions'=>$query->term_conditions);
			}
		}
		return $arr;
	}
}
if(!function_exists('getRefCodes')){
	function getRefCodes() {
		$dt = date('Y-m-d');
		$codes = ReferralMaster::where('code_last_date','>',$dt)->where(['is_show'=>'1','status' => '1','delete_status'=>'1'])->orderBy("id","desc")->get();
		return $codes;
	}
}
if(!function_exists('getUnits')){
	function getUnits($id=null) {
    $arr = array(1=>'IU',2=>'L',3=>'gm',4=>'kg',5=>'ml',6=>'mcg',7=>'mcg',8=>'mg',9=>'cu',10=>'gauge',11=>'%');
    if ($id != null) {
      $arr =  isset($arr[$id]) ? $arr[$id] : null;
    }
		return $arr;
	}
}
if(!function_exists('getGSTList')){
	function getGSTList($id=null) {
    $arr = array(0=>'GST 0',5=>'GST 5',12=>'GST 12',18=>'GST 18',24=>'GST 24');
    if ($id != null) {
      $arr =  isset($arr[$id]) ? $arr[$id] : null;
    }
		return $arr;
	}
}
if (!function_exists('getPlanDetails')) {
    function getPlanDetails($id) {
		$plan = GenniePlan::where('id',$id)->where("delete_status",'1')->first();
		return $plan;
    }
}
if (!function_exists('getOrderUrl')) {
    function getOrderUrl($orderBy) {
		$appOrder = AppointmentOrder::select(["id"])->where(["order_by"=>$orderBy])->where('order_status','0')->orderBy('id','DESC')->first();
		if(!empty($appOrder)){
		$orderId = base64_encode($appOrder->id);
		return route('apptPayment',[base64_encode($appOrder->id)]);
		}
		else return '';
    }
}
if (!function_exists('getAdmins')) {
    function getAdmins() {
		$admins = Admin::where("delete_status",1)->where("status",1)->get();
		return $admins;
    }
}

if (!function_exists('sendWhatAppMsg')) {
	function sendWhatAppMsg($post_data,$mobile_no){
		  $curl = curl_init();
		  curl_setopt_array($curl, [
		  CURLOPT_URL => "https://live-server-2748.wati.io/api/v1/sendTemplateMessage?whatsappNumber=91".$mobile_no,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => "",
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 30,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => "POST",
		  CURLOPT_POSTFIELDS => json_encode($post_data),
		  CURLOPT_HTTPHEADER => [
			paytmAuthToken(),
			"Content-Type: application/json-patch+json"
		  ],
		]);
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
		if ($err) {
			return 0;
		} else {
			return 1;
		}
	}
}
if (!function_exists('getCurlValue')) {
	function getCurlValue($file, $contentType, $filename) {
		if (function_exists('curl_file_create')) {
			return curl_file_create($file, $contentType, $filename);
		}
		$value = "@{$file};filename=" . $filename;
		if ($contentType) {
			$value .= ';type=' . $contentType;
		}
		return $value;
	}
}
if (!function_exists('writeClinicNoteFile')) {
  function writeClinicNoteFile($patient_number,$pres) {
	    $presPath = null;
	    if(isset($pres->p_meta_data) && !empty($pres->p_meta_data)) {
			$pmeta = json_decode($pres->p_meta_data);
			if($pmeta->eye==1) {
				$note = 'ehr_print.clinical_note_print_eye_App';
			}
			else{
				$note = 'ehr_print.clinical_note_printApp';
			}
			$html = view($note,['chart'=>$pmeta->chart,'chart_height'=>$pmeta->chart_height,'patient'=>$pmeta->patient,'chiefComplaints'=>$pmeta->chiefComplaints,'labs'=>$pmeta->labs,'pAllergies'=>$pmeta->pAllergies,'procedures'=>$pmeta->procedures,'pDiagnos'=>$pmeta->pDiagnos,'pVitals'=>$pmeta->pVitals,'immunizations'=>$pmeta->immunizations,'examinations'=>$pmeta->examinations,'proce_order'=>$pmeta->proce_order,'treatments'=>$pmeta->treatments,'patientDiagnosticImagings'=>$pmeta->patientDiagnosticImagings,'practice_detail'=>$pmeta->practice_detail,'rows'=>$pmeta->rows,'pReferral'=>$pmeta->pReferral,'dentals'=>$pmeta->dentals,'followUp'=>$pmeta->followUp,'eyes'=>$pmeta->eyes,'nutritional_info'=>$pmeta->nutritional_info,'diet_plan'=>$pmeta->diet_plan,'physical_excercise'=>$pmeta->physical_excercise,'dietitian_template'=>$pmeta->dietitian_template,'patient_sle'=>$pmeta->patient_sle,'PatientSleCanvas'=>$pmeta->PatientSleCanvas,'patient_fundus'=>$pmeta->patient_fundus,'eyesExam'=>$pmeta->eyesExam,'patient_sys_ill'=>$pmeta->patient_sys_ill,'patient_advice'=>$pmeta->patient_advice,'psycologicalAssesment'=>$pmeta->psycologicalAssesment,'eye'=>$pmeta->eye,'dietPatientNote'=>@$pmeta->dietPatientNote,'PsycologicalEvaluationReport'=>@$pmeta->per])->render();
			$output = PDF::loadHTML($html)->output();
			$docPath = 'uploads/PatientDocuments/'.$patient_number.'/misc/';
			storeFileAwsBucket($docPath, 'clinicalNotePrint.pdf', $output);
			$presPath = getPath("uploads/PatientDocuments/".$patient_number."/misc/clinicalNotePrint.pdf");
		}
		else if(isset($pres->prescription) && !empty($pres->prescription)) {
			if($pres->type == "1") {
				$docPath = 'uploads/PatientDocuments/'.$patient_number.'/misc/';
				$output = PDF::loadHTML($pres->prescription)->output();
				storeFileAwsBucket($docPath,'clinicalNotePrint.pdf', $output);
				$presPath = getPath("uploads/PatientDocuments/".$patient_number."/misc/clinicalNotePrint.pdf");
			}
			else{
				$presPath = getPath("public/pat-pres-files/".$pres->prescription);
			}
		}
		return $presPath;
	}
}

if (!function_exists('getApptDocs')) {
    function getApptDocs($patient_number,$appointment_id) {
        $files_arr = [];
		$file_arr = [];
		$path = 'uploads/PatientDocuments/'.$patient_number;
		$path_url = 'uploads/PatientDocuments/'.$patient_number;
		$is_doc = 0;
      $files = Storage::disk('s3')->directories($path."/");
	  foreach($files as $i => $fl) {
        $fl = substr($fl, strrpos($fl, '/') + 1);
        if (!empty($fl) && $fl != 'misc' && $fl == 'appointments') {
          if (Storage::disk('s3')->exists($path."/".$fl."/".$appointment_id)) {
            $file_name = Storage::disk('s3')->files($path."/".$fl."/".$appointment_id);
			$file_arr = [];
            foreach($file_name as $file_nm) {
			  $file_nm = basename($file_nm);
              if(!empty($file_nm) && substr($file_nm, 0, 4) == "pres") {
                $file_arr[] = getPath($path_url . "/" .$fl."/".$appointment_id."/".$file_nm);
              }
            }
            if(count($file_arr)>0){
              $is_doc = 1;
            }
          }
        }
      }
    $files_arr['patient_documents'] = $file_arr;
    $files_arr['is_doc'] = $is_doc;
		return $files_arr;
    }
}
if (!function_exists('getUserType')) {
  function getUserType($uid) {
	$doc = Doctors::select('doc_type')->where('user_id',$uid)->first();
	$doc_type = "Dr. ";
	if(!empty($doc) && $doc->doc_type == 1) {
		$doc_type = "";
	}
	else if(!empty($doc) && $doc->doc_type == 2) {
		$doc_type = "";
	}
	return $doc_type;
  }
}
if (!function_exists('getPresFile')) {
  function getPresFile($appId) {
	$presDta = UserPrescription::select(["prescription","p_meta_data","type","patient_number"])->where(['appointment_id'=>$appId])->orderBy("id","DESC")->first();
	return writeClinicNoteFile($presDta->patient_number,$presDta);
  }
}
if (!function_exists('getPresRecord')) {
	function getPresRecord($presIds){
		$presIds = explode(",",$presIds);
		$pres = MedicinePrescriptions::select("prescription")->whereIn("id",$presIds)->orderBy('id','DESC')->get();
		if(count($pres)>0){
			foreach($pres as $raw){
				$raw['presUrl'] = getPath("/public/lab-req-prescription/".$raw->prescription);
			}
		}
		return $pres;
	}
}
if (!function_exists('getPresRecordByUser')) {
	function getPresRecordByUser($userId){
		$pres = MedicinePrescriptions::select("prescription")->where("user_id",$userId)->orderBy('id','DESC')->get();
		if(count($pres)>0){
			foreach($pres as $raw){
				$raw['presUrl'] = getPath("/public/lab-req-prescription/".$raw->prescription);
			}
		}
		return $pres;
	}
}
if(!function_exists('getRefCodeAll')){
	function getRefCodeAll() {
		$dt = date('Y-m-d');
		$ids = [4,40,55,56,18];
		$codes = ReferralMaster::where('code_last_date','>',$dt)->where(['status' => '1','delete_status'=>'1'])->get();
		return $codes;
	}
}
if(!function_exists('getRefCodeNameById')){
	function getRefCodeNameById($id) {
		$code = "";
		$refDetails = ReferralMaster::where('id',$id)->first();
		if(!empty($refDetails)){
			$code = $refDetails->code;
		}
		else{
			$checkCode = User::select(['id','mobile_no'])->where(['id'=> $id])->first();
			if(!empty($checkCode)) {
				$code = $checkCode->mobile_no;
			}
		}
		return $code;
	}
}
if(!function_exists('checkEligibilityAppt')){
	function checkEligibilityAppt($mobile_no,$isDirect) {
		$max_patient_count = 0;
		$users = User::where(['mobile_no'=>$mobile_no])->orderBy("parent_id")->get();
		$k = 0;
		if(count($users)>0){
			foreach($users as $i=> $user) {
				if($user->parent_id == 0) {
					$max_patient_count = checkPatientCount($user->id);
				}
				else{
					$k = $k+1;
				}
			}
		}
		$sts = true;
		if($isDirect == 1){
			if($max_patient_count > 0 && $max_patient_count <= $k){
				$sts = false;
			}
		}
		return $sts;
	}
}
if(!function_exists('replacewithStar')){
	function replacewithStar($str,$num) {
		$len = strlen($str);
		$len = $len - $num;
		$star = "*";
		for($i = 1; $len > $i; $i ++){
			$star.= "*";
		}
		return substr_replace($str,$star,0,$len);
	}
}
if(!function_exists('checkApptThisCode')){
	function checkApptThisCode($addedBy,$orgId) {
		$uId = getParentId($addedBy);
		$isExist = User::where(["id"=>$uId,"organization"=>$orgId])->count();
		if($isExist > 0){
			return true;
		}
		else return false;
	}
}
if (!function_exists('totalSubscription')) {
	function totalSubscription() {
		$result = UsersSubscriptions::with('PlanPeriods')->whereHas('PlanPeriods', function($q){
          $q->Where('status', 1);
        })->where('order_status','1')->count();
		return $result;
	}
}
if (!function_exists('getTotRef')) {
	function getTotRef($user_id) {
		$tRef = ReferralCashback::select('referral_id','referred_id')->where('referral_id',$user_id)->where(['status'=>1,'paytm_status'=>'DE_001'])->count();
		return $tRef;
	}
}
if(!function_exists('getItemsByKey')){
	function getItemsByKey($key) {
		$array = UsersOnlineData::groupBy($key)->whereNotNull($key)->get();
		return $array;
	}
}
if(!function_exists('getDetailsByTid')){
	function getDetailsByTid($tid) {
		Log::info('ssssss', [$tid]);
		// dd($tid);
		$app =  AppointmentTxn::with("AppointmentOrder")->where('tracking_id',$tid)->first();
		return $app;
	}
}
if (!function_exists('getDetailsByOid')) {
    function getDetailsByOid($id) {
		$order = AppointmentOrder::where('id',$id)->first();
		return $order;
    }
}
if(!function_exists('getSubTxnDetailsByTid')){
	function getSubTxnDetailsByTid($tid) {
		$app =  UserSubscriptionsTxn::with("UsersSubscriptions")->where('tracking_id',$tid)->first();
		return $app;
	}
}
if (!function_exists('getSubTxnDetailsByOid')) {
    function getSubTxnDetailsByOid($id) {
		$order = UsersSubscriptions::where('order_id',$id)->first();
		return $order;
    }
}
if(!function_exists('getLabCompanies')){
	function getLabCompanies() {
		$result = LabCompany::where('status',1)->get();
		return $result;
	}
}
if(!function_exists('getLabCompaniesAdmin')){
	function getLabCompaniesAdmin() {
		$result = LabCompany::get();
		return $result;
	}
}
if(!function_exists('getDefaultLabs')){
	function getDefaultLabs() {
		$result = DefaultLabs::where('delete_status',1)->get();
		return $result;
	}
}
if(!function_exists('getResponseByCurl')){
	function getResponseByCurl($postdata,$url) {
		$post_data = json_encode($postdata);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		$response = curl_exec($ch);
		return json_decode($response,true);
	}
}
if(!function_exists('getOtherLab')){
	function getOtherLab($search,$company_id = null) {
		$search = strtolower($search); 
		 $query = LabCollection::with("DefaultLabs","LabCompany")->where(['delete_status'=>1,'status'=>1]);
		 $query->whereHas("LabCompany",function($qry) {
			$qry->where('status',1);
		 });
		 if(!empty($search)) {
			 $query->whereHas("DefaultLabs",function($q) use($search) {
				$q->where('title',$search);
			 });
		 }
		 if(!empty($company_id)){
			 $query->where('company_id',$company_id);
		 }
		 $labs = $query->orderBy('id', 'desc')->get();
		return $labs;
	}
}
if (!function_exists('checkThisItemInCart')) {
    function checkThisItemInCart($id) {
		$user_id = @Auth::user()->id;
		if(!empty($user_id)) {
			return LabCart::select('id')->where(['product_code' => $id,'user_id' => $user_id])->count() > 0 ? true : false;
		}
		else{
			$LabCart = Session::get("CartPackages");
			return !empty($LabCart) && array_search($id, array_column($LabCart, 'lab_id')) !== false ?  true : false;
		}
    }
}
if (!function_exists('setLabTypeSession')) {
   function setLabTypeSession() {
		$user_id = @Auth::user()->id;
		$labType = @LabCart::where(['user_id' => $user_id])->pluck('type')[0];
		return $labType;
   }
}
if(!function_exists('getPopularTest')){
	function getPopularTest() {
		$result = DefaultLabs::whereIn('id',[1437,24,14,16,62,1303,34,17,1463])->where('delete_status',1)->get();
		return $result;
	}
}
if(!function_exists('getAllThyrocareData')) {
    function getAllThyrocareData() {
		$products = ThyrocareLab::whereIn('type',['TEST','PROFILE','POP'])->get()->toArray();
		// $arr = [];
		// if(count($products)>0) {
			// foreach($products as $raw) {
				// $raw['childs'] = json_decode($raw['childs']);
				// $raw['rate'] = json_decode($raw['rate']);
				// $raw['imageMaster'] = json_decode($raw['imageMaster']);
				// $arr[] = $raw;
			// }
		// }
		// $products = json_decode(json_encode($products),true);
		return $products;
    }
}
if(!function_exists('getTestNameByLabName')) {
	function getTestNameByLabName($name) {
		return @ThyrocareLab::where('common_name',$name)->first()->testNames;
	}
}
if (!function_exists('getUserDetails')) {
	function getUserDetails($user_id){
	  return @User::where('id', $user_id)->first();
	}
}
if (!function_exists('getComapnyDetails')) {
	function getComapnyDetails($id){
		$id = ($id == 0) ? 2 : $id;
		return @LabCompany::where('id', $id)->pluck('title')[0];
	}
}
if (!function_exists('getThyrocareKey_Mobile')) {
function getThyrocareKey_Mobile() {
	$post_data = array(
	'username' => '9414061829',
	'password' => '256EE3',
	'portalType' => '',
	'userType' => 'dsa',
	'facebookId' => 'string',
	'mobile' => 'string',
	);
	$response = getResponseByCurl($post_data,"https://velso.thyrocare.cloud/api/Login/Login");
	return ['API_KEY'=>$response['apiKey'],'dsa_mobile'=>$response['mobile']];
}}
if (!function_exists('getLabPackageByCompany')) {
	function getLabPackageByCompany($id) {
		return @LabPackage::with(["LabCompany","DefaultLabs"])->where(['company_id'=>$id])->get();
	}
}
if(!function_exists('getAllLabCompanies')){
	function getAllLabCompanies() {
		$result = LabCompany::get();
		return $result;
	}
}
if(!function_exists('getLabComGrp')){
	function getLabComGrp() {
		$result = LabCompany::select('id','title')->get();
		$comArr = [];
		foreach($result as $raw){
			$comArr[$raw->id] = $raw->title;
		}
		return $comArr;
	}
}
if (!function_exists('availGenniePackDetails')) {
	function availGenniePackDetails($id) {
		$plan = GenniePlan::where('id',$id)->where("delete_status",'1')->first();
		return $plan;
	}
}
if (!function_exists('getUniqueRefCode')) {
function getUniqueRefCode($referral_code) {
	if(refCodeExist($referral_code) > 0) {
		return getUniqueRefCode(getRefCode());
	}
	else return $referral_code;
}}
if (!function_exists('getRefCode')) {
function getRefCode(){
	return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"),10,6);
}}
if (!function_exists('refCodeExist')) {
function refCodeExist($refCode){
	UserDetails::select("id")->where('referral_code',$refCode)->count();
}}
if (!function_exists('createUsersReferralCode')) {
function createUsersReferralCode($user_id) {
	if(UserDetails::select("id")->where('user_id',$user_id)->count() == 0){
		$referral_code = getUniqueRefCode(getRefCode());
		UserDetails::create([
			'user_id'=>$user_id,
			'referral_code'=>$referral_code,
		]);
		return true;
	}
	return true;
}}
if (!function_exists('getUserIdByRefCode')) {
function getUserIdByRefCode($refCode,$userId = null) {
	if(!empty($userId)){
		return @UserDetails::whereRaw("BINARY `referral_code`= ?",[$refCode])->where('user_id','!=',$userId)->first()->user_id;
	}
	else 
	return @UserDetails::whereRaw("BINARY `referral_code`= ?",[$refCode])->first()->user_id;
	// return @UserDetails::select("user_id")->where('referral_code',$refCode)->first()->user_id;
}}
if (!function_exists('getRefCodeByUserId')) {
function getRefCodeByUserId($userId){
	return @UserDetails::select("referral_code")->where('user_id',$userId)->first()->referral_code;
}}
if(!function_exists('updateWallet')) {
function updateWallet($userId,$type,$slug) {
	$rewardAmt = getSetting($slug)[0];
	if($rewardAmt > 0){
		$detail = UserDetails::select('wallet_amount')->where(['user_id'=>$userId])->first();
		$wallet_amount = $detail->wallet_amount + $rewardAmt;
		UserWallet::create([
			'user_id' => $userId,
			'type' => $type,
			'amount' => $rewardAmt
		]);
		UserDetails::where('user_id',$userId)->update(['wallet_amount'=>$wallet_amount]);
	}
	return true;
}}
if(!function_exists('availWalletAmount')) {
function availWalletAmount($userId,$type,$amt) {
		if(!empty($amt) && $amt > 0){
			$detail = UserDetails::select('wallet_amount')->where(['user_id'=>$userId])->first();
			$wallet_amount = $detail->wallet_amount - $amt;
			UserWallet::create([
				'user_id' => $userId,
				'entity_type' => 0,
				'type' => $type,
				'amount' => $amt
			]);
			UserDetails::where('user_id',$userId)->update(['wallet_amount'=>$wallet_amount]);
		}
	return true;
}}
if (!function_exists('getChiefComplaints')) {
    function getChiefComplaints($res) {
		$result = null;
    	if(isset($res) && count($res)>0){
			$chiefArray = [];
			foreach($res as $raw){
				$chief = json_decode($raw->data,true);
				if(isset($chief)) {
					$complaints = array_column($chief, 'complaint_name');
					$chiefArray[] = implode(",",$complaints);
				}
			}
			$result = implode(",",$chiefArray);
		}
		return $result;
    }
}
if (!function_exists('getQuizQues')) {
    function getQuizQues() {
		$questions =  DB::table('quiz_questions')->where('oid',4)->get();
		return $questions;
    }
}
if(!function_exists('getOrganizationIdBySlug')) {
	function getOrganizationIdBySlug($slug) {
		$data = OrganizationMaster::select('id')->where("slug", $slug)->first();
		return @$data->id;
	}
}
if (!function_exists('setDocType')) {
	function setDocType($type = null) {
		$text = 'Dr.';
		if($type == '1'){
			$text = '';
		}
		else if($type == '2') {
			$text = '';
		}
		return $text;
	}
}
if (!function_exists('getCounsellerList')) {
    function getCounsellerList(){
       $docs = Doctors::select('id','first_name','last_name')->where('doc_type',1)->get();
	   return $docs;
    }
}
if (!function_exists('getAppPages')) {
    function getAppPages() {
		 $pages = DB::table('app_pages')->where('delete_status',1)->orderBy('name','ASC')->get();
	     return $pages;
    }
}

if(!function_exists('getQuesAnsdata')) {
	function getQuesAnsdata($id,$ans=null) {
		
		if($ans=='optionA'){
			$data = DB::table('quiz_ques_demo')->select('question','optionA')->where('id',$id)->first();
			$data->answer=$data->optionA;
		}
		if($ans=='optionB'){
			$data = DB::table('quiz_ques_demo')->select('question','optionB')->where('id',$id)->first();
			$data->answer=$data->optionB;
		}
		if($ans=='optionC'){
			$data = DB::table('quiz_ques_demo')->select('question','optionC')->where('id',$id)->first();
			$data->answer=$data->optionC;
		}
		if($ans=='optionD'){
			$data = DB::table('quiz_ques_demo')->select('question','optionD')->where('id',$id)->first();
			$data->answer=$data->optionD;
		}
		return $data;
	}
}
if(!function_exists('getActualprice')){
	function getActualprice($product_name,$company_id){
			$packages = LabPackage::select('actual_cost')->where(['company_id'=>$company_id])->where(['delete_status'=>1])->where('title',$product_name)->first();
			if($packages==''){
				$query = LabCollection::with("DefaultLabs")->select('actual_cost')->where('delete_status', '=', '1');
				$query->whereHas("DefaultLabs",function($q) use($product_name){
					$q->where('title',$product_name);
				 });
				$packages=$query->where('company_id',$company_id)->first(); 
			}
			if($packages){
				return $packages->actual_cost;
			}else{
				return 0;
			}
		
	}
}

if(!function_exists('array_flatten')){
	function array_flatten($array) {
	  $result = [];
	  foreach ($array as $element) {
		if (is_array($element)) {
		  $result = array_merge($result, array_flatten($element));
		} else {
		  $result[] = $element;
		}
	  }
	  return $result;
	}
}
if (!function_exists('getSubsRefCodeByUserId')) {
	function getSubsRefCodeByUserId($pId) {
		$refCode = "";
		$mobile_no = User::select('mobile_no')->where('pId',$pId)->first()->mobile_no;
		$uid = @User::where('mobile_no',$mobile_no)->where("parent_id",0)->first()->id;
		$userRef = UsersSubscriptions::with('ReferralMaster')->where('user_id',$uid)->where('order_status','1')->first();
		if(!empty($userRef->ReferralMaster)) {
			$refCode = $userRef->ReferralMaster->code;
		}
		return $refCode;
	}
}
if (!function_exists('getLanguages')) {
    function getLanguages() {
        $languages = DB::table('doctor_lng')->where('status',1)->get();
        return $languages;
    }
}
if(!function_exists('editLab')){
	function editLab(){
		
	}
}
if (!function_exists('getDepartmentName')) {

    function getDepartmentName()
    {
        $cName = Department::get();

        return $cName;
    }
}
if (!function_exists('getDepartmentUser')) {

    function getDepartmentUser()
    {
        $cName = Department::get();

        return $cName;
    }
    if (!function_exists('getDepartmentUser')) {

        function getDepartmentUser($id=null)
        {
            $cName = TicketUser::select("name")->where('id',$id)->pluck("name");
            $name ='';
            if(isset($cName[0])){
                $name =  $cName[0];
            }
            return $name;
        }
    }
}
if (!function_exists('getLocationSuB')) {
    function getLocationSuB() {
        $referralLocations = ReferralMaster::whereNotNull('location')->pluck('location')->unique();
        return $referralLocations;
    }
}

if (!function_exists('getLocation')) {
	function getLocation() {
		// $data = DB::table('pw_other_locations')->select('city')->get();
		// $data = DB::table('pw_other_locations')->distinct()->select('city')->get();
		$data = DB::table('referral_master')
        ->whereNotNull('location')
        ->distinct()
        ->select('location')
        ->get();
		return $data;
	}
}


if (!function_exists('getSubLocation')) {
    function getSubLocation() {
        $referralLocations = ReferralMaster::whereNotNull('sub_location')->pluck('sub_location')->unique();
        return $referralLocations;
    }
}
if (!function_exists('getSymptoms')) {
    function getSymptoms() {
		 $symptom = DB::table('symptoms')->where('delete_status',1)->orderBy('name','ASC')->get();
	     return $symptom;
    }
}
if (!function_exists('getSymptomsWithStatus')) {
    function getSymptomsWithStatus() {
	$symptom =  'symptom';
    $description = 'description';
    $treatment =  'treatment';
    $cause = 'cause';
    $strategy =  'strategy';
    $assess_program = 'assess_program';
    $symp_details = 'symp_details';
    $slug = 'slug';

    $pointerSymp = Symptoms::select('id','icon',$symptom,$description,$treatment,$cause,$strategy,$assess_program,$symp_details , $slug)->where('mh_status',1)->Where(['status'=>1])->get();
    
	     return $pointerSymp;
    }
}

if (!function_exists('getSymp')) {
    function getSymp($id) {
		$symptom =  'symptom';
    $description = 'description';
    $treatment =  'treatment';
    $cause = 'cause';
    $strategy =  'strategy';
    $assess_program = 'assess_program';
    $symp_details = 'symp_details';
		$pointerSymp = Symptoms::select('id','icon',$symptom,$description,$treatment,$cause,$strategy,$assess_program,$symp_details)->where('id' , $id)->where('mh_status',1)->Where(['status'=>1])->first();
		return $pointerSymp;
	}}



if (!function_exists('getQuestionSuggestion')) {
    function getQuestionSuggestion($id) {
		$suggestion = NULL;	
		 $res = MhQuesRange::select('suggestion')->where('id',$id)->first();
	     if(!empty($res)){
			$suggestion = $res->suggestion;
		 }
		return $suggestion; 
    }
}

if (!function_exists('getQuestionRangeById')) {
    function getQuestionRangeById($id) {
		 return MhQuesRange::where('id',$id)->first();
    }
}


if(!function_exists('getSubscriptionData')){

    function getSubscriptionData()
    {
        // Total subscription count
        $totalSubscriptions = UsersSubscriptions::all()->count();

        // Current month subscription count
        $currentMonthSubscriptions = UsersSubscriptions::whereYear('created_at', Carbon::now()->year)
            ->whereMonth('created_at', Carbon::now()->month)
            ->count();

        // Previous month subscription count
        $previousMonth = Carbon::now()->subMonth();
        $previousMonthSubscriptions = UsersSubscriptions::whereYear('created_at', $previousMonth->year)
            ->whereMonth('created_at', $previousMonth->month)
            ->count();

        // Calculate percentage change
        if ($previousMonthSubscriptions > 0) {
            $percentageChange = (($currentMonthSubscriptions - $previousMonthSubscriptions) / $previousMonthSubscriptions) * 100;
        } else {
            // If there were no subscriptions in the previous month, handle accordingly
            if ($currentMonthSubscriptions > 0) {
                $percentageChange = 100; // Indicating a new growth
            } else {
                $percentageChange = 0; // No change
            }
        }

        $a =  [
            'total_subscriptions' => $totalSubscriptions,
            'current_month_subscriptions' => $currentMonthSubscriptions,
            'previous_month_subscriptions' => $previousMonthSubscriptions,
            'percentage_change' => $percentageChange
        ];
        return $a;
    }

}

if(!function_exists('getSubscriptionYear')){
	function getSubscriptionYear($year)
	{
		$data = UsersSubscriptions::whereYear('created_at' , $year)->get();
		return $data;
	}
}
if (!function_exists('checkActiveSubs')) {
	function checkActiveSubs($userId) {
		return UsersSubscriptions::with('PlanPeriods')->where('user_id',$userId)->where('order_status','1')->whereHas('PlanPeriods', function($q) {
          $q->Where('status', 1);
        })->count();
	}
}

if (!function_exists('resizeImage')) {
	function resizeImage($image, $maxSize) {
		// Get original image dimensions
		list($width, $height) = getimagesize($image->getPathname());

		// Calculate new dimensions to resize the image while maintaining aspect ratio
		$aspectRatio = $width / $height;
		$newWidth = sqrt($maxSize * $aspectRatio);
		$newHeight = $newWidth / $aspectRatio;

		// Create a new image resource based on the original image
		$resizedImage = imagecreatetruecolor($newWidth, $newHeight);

		// Read the original image
		switch ($image->getClientMimeType()) {
			case 'image/jpeg':
				$originalImage = imagecreatefromjpeg($image->getPathname());
				break;
			case 'image/png':
				$originalImage = imagecreatefrompng($image->getPathname());
				break;
			case 'image/gif':
				$originalImage = imagecreatefromgif($image->getPathname());
				break;
			default:
				return false; // Unsupported image format
		}

		// Resize the image
		imagecopyresampled($resizedImage, $originalImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

		// Destroy the original image resource
		imagedestroy($originalImage);

		// Output the resized image to a buffer
		ob_start();
		imagejpeg($resizedImage, null, 100); // You can adjust the quality here if needed
		$resizedImageData = ob_get_contents();
		ob_end_clean();

		// Destroy the resized image resource
		imagedestroy($resizedImage);

		return $resizedImageData;
	}
}
if (!function_exists('getMhResultType')) {
	function getMhResultType() {
		$items = MhResultType::all()->groupBy('symp_id');
		$options = [];
		foreach ($items as $type => $groupedItems) {
			$symp = DB::table('symptoms')->select('symptom')->where('id',$type)->first();
			$options[$symp->symptom] = $groupedItems->pluck('title', 'id')->toArray();
		}
		return $options;
	}
}
if (!function_exists('checkResultType')) {
	function checkResultType($id) {
		$res = MhResultType::select("title","title_hindi")->where("id",$id)->first();
		return $res->title;
	}
}

if (!function_exists('formatNumber')) {
	function formatNumber($number) {
		if ($number >= 1000000) {
			return round($number / 1000000, 1) . 'M'; // For millions
		} elseif ($number >= 1000) {
			return round($number / 1000, 1) . 'K'; // For thousands
		}
		return $number; // Return the number as it is for less than 1K
	}
}
if (!function_exists('getPatientAdvise')) {
    function getPatientAdvise($res) {
		$result = null;
    	if(isset($res) && count($res)>0){
			$chiefArray = [];
			foreach($res as $raw){
				$advice = json_decode($raw->data,true);
				if(isset($advice)) {
					$complaints = array_column($advice, 'advice');
					$chiefArray[] = implode(",",$complaints);
				}
			}
			$result = implode(",",$chiefArray);
		}
		return $result;
    }
}

if (!function_exists('getCaseCategory')) {

	function getCaseCategory($caseType=null)
	{
		 $category = null;
		  $category = SupportCategory::where('case_type' , $caseType)->get();
		  return $category;
	}
  
  }
  if (!function_exists('getClientName')) {
	function getClientName()
	{
		$query = ClientName::select('client_name' ,'prefix')->get();
	    return $query;
	}
  }

  if (!function_exists('getProfile')) {
	function getProfile()
	{ 
		$id = Session::get('id');

		$query = Admin::select('logo')->where('id' , $id)->first();
	    return $query;
	}
  }
  if (!function_exists('getQuestion')) {
	function getQuestion()
	{ 
		

		$question = SecurityQuestion::get();
	    return $question;
	}
  }
	if (!function_exists('storeFileAwsBucket')) {
		function storeFileAwsBucket($path, $fileName=null, $file=null) {
			try
			{
				if (!Storage::disk('s3')->exists($path))
				{
					Storage::disk('s3')->makeDirectory($path);
				}
				if ($fileName != null && $file != null) {
				  Storage::disk('s3')->put($path.$fileName, $file);
				}
			}
			catch(\Exception $e) {
			}
		}
	}
  if (!function_exists('refCodesLocation')) {
	function refCodesLocation()
	{
	   $records = ReferralMaster::select('id','location')->pluck('location','id');
	   return $records;
	}
}

if (!function_exists('excelData')) {
    function excelData($name) {
        $fileName = null;
        $directory = public_path('storage/exportCSV');
        $directoryPath = public_path('storage/exportCSV');

        // Ensure the directory exists
        if (!is_dir($directoryPath)) {
            mkdir($directoryPath, 0777, true); // Create the directory with necessary permissions
        }
        $files = scandir($directoryPath);

        if ($files !== false && count($files) > 2) {
            // Exclude '.' and '..' entries
            $files = array_diff($files, ['.', '..']);

            // Filter files containing the specified $name
            $filteredFiles = array_filter($files, function ($file) use ($name) {
                return strpos($file, $name) !== false;
            });

            if (!empty($filteredFiles)) {
                // Sort files by modification time (most recent first)
                usort($filteredFiles, function ($a, $b) use ($directory) {
                    return filemtime($directory . '/' . $b) - filemtime($directory . '/' . $a);
                });

                // Get the most recent file
                $fileName = $filteredFiles[0];
            }
        }
        return $fileName;
    }
}




if (!function_exists('getAllSymptom')) {
	function getAllSymptom() {
	    $ids = [359, 34, 1203, 87, 1068, 115, 375 , 1009];
    $symptom = Symptoms::with(['SymptomsSpeciality', 'SymptomTags'])
                ->whereIn('id', $ids) // Filter by specific ID
                ->where('delete_status', 1)
                ->where('status', 1)
                ->get();
                return $symptom;
	}
}


if (!function_exists('getDoctor')) {
    function getDoctor() {
        $doctors = Doctors::select('id', 'first_name', 'last_name')->get();
		
        return $doctors;
    }
}
if (!function_exists('getTopPhycologist')) {
    function getTopPhycologist() {
		$docs = Doctors::with(["docSpeciality","DoctorRatingReviews","getCityName","getStateName"])->where(["delete_status"=>1,'status'=>1,'oncall_status'=>1,'varify_status'=>1])->whereNotNull('speciality')->where("oncall_status","!=",0)->whereIn('speciality',[66,101,106,122])->orderBy('experience','DESC')->limit(8)->get();
		if($docs->count()>0){
			foreach($docs as $value){
				if(!empty($value->profile_pic)) {
					$image_url = getPath("public/doctor/ProfilePics/".$value->profile_pic);
					$value['profile_pic'] = $image_url;
				}
				else{
					$value['profile_pic'] = null;
				}
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
		}
		return $docs;
    }
}
if (!function_exists('getCities')) {
    function getCities() {
        $languages = DB::table('admins')->select("city")->whereNotNull("city")->groupBy("city")->get();
        return $languages;
    }
}
if(!function_exists('getCurrentSubscription')) {
	function getCurrentSubscription($id,$date) {
		$data = UsersSubscriptions::where(["added_by"=>$id, 'order_status'=>1])->whereDate('created_at',$date)->get();
		return $data;
	}
}
if (!function_exists('getlabCallingStatus')) {
    function getlabCallingStatus($id) {
        $data = labCalling::where(["appointment_id" => $id])->first();
        // Check if data is not null before accessing its properties
        if ($data !== null) {
            $status = $data->status;
            return $status;
        } else {
            // Return a default status or handle the case where no data is found
            return 0; // Or any default value you prefer
        }
    }
}
if(!function_exists('checkClinicalNoteModulePermission')) {
function checkClinicalNoteModulePermission($moduleId, $user=null){
   $practice =  RoleUser::select(['user_id','role_id','practice_id'])->where(['user_id'=>$user])->first();
   $permissions = clinicalNotePermissions::where(['user_id'=>$user,'practice_id'=>$practice->practice_id])->first();
	if(!empty($permissions) > 0 && $permissions->modules_access != null){
	  if(in_array($moduleId, explode(',',$permissions->modules_access))){
		return true;
	  }
	  else{
		return false;
	  }
	}
	else{
	   return false;
	 }
 }
}
if (!function_exists('getJobCategoryName')) {
	function getJobCategoryName($id){
		$data = JobCategory::where('id',$id)->where(['status'=>1,'delete_status'=>0])->first();
		if(!empty($data)){
		  $name = $data->title;
		}
		else{
		  $name = "";
		}
	  return $name;
	}
}
if (!function_exists('checkDoctorBlog')) {
	function checkDoctorBlog($docId) {
		$docs = NewsFeeds::where('doctor_id',$docId)->count();
		return $docs > 0 ? $docs : false;
	}
}
if (!function_exists('supportTicket')) {
	function supportTicket() {
		$today = Carbon::today(); 
		$ticket = Ticket::whereDate('created_at', $today)->count();
		return $ticket > 0 ? $ticket : false;
	}
}
if (!function_exists('getSupportTicket')) {
	function getSupportTicket() {
		$today = Carbon::today(); 

		$ticket = Ticket::whereDate('created_at', $today)->get();

		return $ticket;
	}
}

/** New Helper */


