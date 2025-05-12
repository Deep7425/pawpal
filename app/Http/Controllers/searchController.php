<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Doctors;
use App\Models\City;
use App\Models\ehr\CityLocalities;
/**ehr db models */
use App\Models\ehr\User as ehrUser;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\DoctorsInfo;
use App\Models\ehr\StaffsInfo;
use App\Models\ehr\RoleUser;
use App\Models\ehr\OpdTimings;
use App\Models\ehr\Plans;
use App\Models\ehr\ManageTrailPeriods;
use App\Models\ehr\PatientRagistrationNumbers;
use App\Models\ehr\Patients;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\Appointments;
use App\Models\ehr\PracticeDocuments;
use App\Models\ehr\clinicalNotePermissions;
use App\Models\Admin\SymptomsSpeciality;
use App\Models\Admin\Symptoms;
use App\Models\Admin\SymptomTags;
use App\Models\OtpPracticeDetails;
use App\Models\Speciality;
use App\Models\PatientFeedback;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\CouncilingData;
use App\Models\UniversityList;
use App\Models\DoctorSlug;
use Exception;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
class searchController extends Controller {

	public function getDataByCity($city) {

		$city = City::where(["slug"=>$city])->first();
		if(!empty($city)) {
			Session::put('search_from_locality_name', $city->name);
			Session::put('state_id', $city->State->id);
			Session::put('city_id', $city->id);
			Session::put('search_from_state_name',$city->State->name);
			Session::put('search_from_city_name', $city->name);
			Session::put('search_from_city_slug', $city->slug);
		}
		if(!empty($city)){
			return abort(404);
		}
		else{
			return abort(404);
		}

	}
	public function findAllDoctorsByCity($city) {

		try{
			dd('k');
		// $city = str_replace('-',' ', $city);
		Session::put('info_type', "doctor_all");
		$city = City::where(["slug"=>$city])->first();
		if(!empty($city)) {
			Session::put('search_from_locality_name', $city->name);
			Session::put('state_id', $city->State->id);
			Session::put('city_id', $city->id);
			Session::put('search_from_state_name',$city->State->name);
			Session::put('search_from_city_name', $city->name);
			Session::put('search_from_city_slug', $city->slug);
			Session::put('search_from_search_bar', "doctors");
			Session::put('search_text', "Doctors");
		}
		if(!empty($city)) {
			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->whereNotNull('first_name')->where("oncall_status","!=",0);
			if(!empty($city)){
				//$qry->where('city_id',$city->id);
			}
			$infoData = $qry->get();
			$infoData = dataSequenceChange($infoData);

			$perPage = 10;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }
			$offset = ($currentPage * $perPage) - $perPage;
			$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
			$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

			if(count($infoData) > 0) {
				$infoData = bindDocData($infoData);
			}
			return view($this->getView('doctors.doctors'),['infoData'=>$infoData]);
		}
		else{
			return abort(404);
		}

		}catch(Exception $e){

			return $e->getMessage();

		}
	}

	public function findAllClinicsByCity($city) {

		try{
			
		// $city = str_replace('-',' ', $city);
		Session::put('info_type', "clinic_all");
		$city = City::where(["slug"=>$city])->first();
		if(!empty($city)) {
			Session::put('search_from_locality_name', $city->name);
			Session::put('state_id', $city->State->id);
			Session::put('city_id', $city->id);
			Session::put('search_from_state_name',$city->State->name);
			Session::put('search_from_city_name', $city->name);
			Session::put('search_from_city_slug', $city->slug);
			Session::put('search_from_search_bar', "clinics");
			Session::put('search_text', "Clinics");
		}
		if(!empty($city)) {
			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1,"practice_type"=>1])->whereNotNull('clinic_name')->where("oncall_status","!=",0);
			if(!empty($city)){
				$qry->where('city_id',$city->id);
			}
			$infoData = $qry->groupBy('clinic_name')->get();
			$infoData = dataSequenceChange($infoData);

			$perPage = 10;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }
			$offset = ($currentPage * $perPage) - $perPage;
			$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
			$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

			if(count($infoData) > 0) {
				$infoData = bindDocData($infoData);
			}
			return view($this->getView('doctors.hospital_list'),['infoData'=>$infoData]);
		}
		else{
			return abort(404);
		}

		}catch(Exception $e){

			return $e->getMessage();

		}

	}

	public function findAllHospitalsByCity($city) {
		// $city = str_replace('-',' ', $city);
		Session::put('info_type', "hos_all");
		$city = City::where(["slug"=>$city])->first();
		if(!empty($city)) {
			//Session::put('search_from_locality_name', $city->name);
			Session::put('state_id', $city->State->id);
			Session::put('city_id', $city->id);
			Session::put('search_from_state_name',$city->State->name);
			Session::put('search_from_city_name', $city->name);
			Session::put('search_from_city_slug', $city->slug);
			Session::put('search_from_search_bar', "hospital");
			Session::put('search_text', "Hospital");
		}

		if(!empty($city)) {
			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1,"practice_type"=>2])->whereNotNull('clinic_name')->where("oncall_status","!=",0);
			if(!empty($city)){
				$qry->where('city_id',$city->id);
			}
			$infoData = $qry->groupBy('clinic_name')->get();
			$infoData = dataSequenceChange($infoData);

			$perPage = 10;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }
			$offset = ($currentPage * $perPage) - $perPage;
			$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
			$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

			if(count($infoData) > 0) {
				$infoData = bindDocData($infoData);
			}
			return view($this->getView('doctors.hospital_list'),['infoData'=>$infoData]);
		}
		else{
			return abort(404);
		}
	}

	public function loadMore(Request $request,$city,$type = null,$name = null){


			$speciaity = Speciality::where('slug',$type)->first();
			$symptoms = null;
			$search = '';
			if(empty($speciaity)) {
				$symptoms = Symptoms::with(["SymptomsSpeciality","SymptomTags"])->where(['slug'=>$type])->first();
			}
			$city = City::where(["name"=>$city])->first();
			$locality = CityLocalities::where(["slug"=>$name])->first();
			Session::forget('bySpacialityId');
			Session::forget('dCtN');
			Session::forget('dLtN');
	
			if(!empty($locality)){
				Session::put('locality_id', $locality->id);
				Session::put('search_from_locality_name', $locality->name);
				Session::put('locality_slug', $locality->slug);
			}
			else{
				if(!empty($city)) {
					Session::put('search_from_locality_name', $city->name);
				}
				Session::forget('locality_id');
				Session::forget('locality_slug');
			}
			if(!empty($city)) {
				Session::put('state_id', $city->State->id);
				Session::put('city_id', $city->id);
				Session::put('search_from_state_name',$city->State->name);
				Session::put('search_from_city_name', $city->name);
				Session::put('search_from_city_slug', $city->slug);
			}
	
			if(!empty($speciaity)) {
				Session::put('grp_id', $speciaity->group_id);
				Session::put('search_id', $speciaity->id);
				Session::put('info_type', "Speciality");
				Session::put('search_from_search_bar', $speciaity->spaciality);
				Session::put('search_text', $speciaity->speciality_text);
				$qry = Doctors::with(["docSpeciality","DoctorRatingReviews",'DoctorSlug' ,'getCityName'])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->whereNotNull('speciality')->where("oncall_status","!=",0);
	
				if(!empty($locality)) {
					// $qry->where('locality_id',$locality->id);
				}
				if(!empty($city) && $request->ctype == 2) {
					$qry->where('city_id',$city->id);
				}
				if(!empty($speciaity)) {
					$s_ids = Speciality::where(["group_id"=>$speciaity->group_id])->pluck('id');
					$qry->whereIn('speciality',$s_ids);
				}
				if($request->locality == "true" || $request->dexp != "" || $request->fmin != "" || $request->fmax != "" || $request->ctype != "") {
					$qry = $this->filteredDoctorData($qry,@$_COOKIE['locality'],$request->dexp,$request->fmin,$request->fmax,$request->ctype);
				}
				if (!empty($request->input('search'))) {
					$search = $request->input('search');
					 $search = str_replace("-"," ",$search);
			   }
			   
			 
			   $infoData = $qry->limit(4)->get();
			   $profilePicUrls = [];
			   foreach ($infoData as $info) {
				   if (!empty($info->profile_pic)) {
					$profilePicUrls[] = getPath('public/doctor/ProfilePics/'.$info->profile_pic);
				   }
			   }
			   
				$infoDataNotFound = $infoData;
				if(count($infoData) <= 0 && !empty($speciaity) && !empty($city)){
					$infoData = Doctors::with(["docSpeciality","DoctorRatingReviews"])
					->where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])
					->whereNotNull('speciality')
					->where("oncall_status", "!=", 0)
					->where('city_id', $city->id)
					->whereIn('speciality', $s_ids)
					->limit(4)
					->get();
					
				}
				
				$infoData = dataSequenceChange($infoData,$speciaity->id);
				
				$infoData = bindDocData($infoData);
				$available_now = array_column($infoData, 'available_now');
				array_multisort($available_now, SORT_DESC, $infoData);
	
				$byrating = array_column($infoData, 'doc_rating');
				array_multisort($byrating, SORT_DESC, $infoData);
				if(count($infoData) > 0) {
					if(!empty($city)) { 
						$currCityDocs = array();
						$othCityDocs = array();
						$kkDoc = array();
						foreach($infoData as $doc) {
							if($doc['city_id']['id'] == $city->id){
							   array_push($kkDoc,$doc);
							}else if($doc['city_id']['id'] != $city->id){
							   array_push($currCityDocs,$doc);
							}else{
							   array_push($othCityDocs,$doc);
							}
						}
						// dd($kkDoc);
						$finArr = array_merge($kkDoc,$currCityDocs);
						$infoData = array_merge($finArr,$othCityDocs);
					}
				}
				// if ($request->ajax()) {
				// 	$infoData = $this->loadMoreData($qry, 2); // Load 10 records for example
				// 	return response()->json($infoData);
				// }

				if ($request->ajax()) {
					
					$infoData = $this->loadMoreData($qry, 2);
			
					foreach ($infoData as &$doctor) {
						if (!empty($doctor->profile_pic)) {
						$doctor->profile_pic_url = getPath('public/doctor/ProfilePics/'.$doctor->profile_pic); 
						} else {
							$doctor->profile_pic_url = asset('/img/doctor-icon-new');
						}
					}
                     
				
					return response()->json($infoData);
				}
				

			
				return view($this->getView('doctors.doctors'),['infoData'=>$infoData,'city'=>$city,'infoDataNotFound'=>$infoDataNotFound , 'profilePicUrls' => $profilePicUrls]);
			}
			else if($type == "speciality") {
				
				Session::forget('grp_id');
				// Session::put('search_id', $speciaity->id);
				Session::put('info_type', "Speciality");
				$data = $request->all();
				$specialities = [];
				if(!empty($data['speciality'])) {
					$specialities = explode(",",$data['speciality']);
					$speciaityIds = Speciality::whereIn('slug',$specialities)->pluck("id");
					Session::put('bySpacialityId', $speciaityIds);
				}
	
				$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('speciality');
	
				if(!empty($locality)) {
					//$qry->where('locality_id',$locality->id);
				}
				if(!empty($city)) {
					//$qry->where('city_id',$city->id);
				}
				if(count($specialities) > 0 ) {
					$qry->whereHas('docSpeciality', function($q)  use ($specialities) {$q->whereIn('slug',$specialities);});
				}
	
				if($request->locality == "true" || $request->dexp != "" || $request->fmin != "" || $request->fmax != "" || $request->ctype != "") {
					$qry = $this->filteredDoctorData($qry,@$_COOKIE['locality'],$request->dexp,$request->fmin,$request->fmax,$request->ctype);
				}
				$infoData = $qry->get();
				$infoDataNotFound = $infoData;
				if(count($infoData) <= 0 && count($specialities) > 0 && !empty($city)){
					$query = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('speciality')->where('city_id',$city->id);
					if(count($specialities) > 0 ) {
						$query->whereHas('docSpeciality', function($q)  use ($specialities) {$q->whereIn('slug',$specialities);});
					}
					$infoData = $query->get();
				
				}
				
				$infoData = dataSequenceChange($infoData);
				$perPage = 10;
				if(count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				dd($infoData);
				return view($this->getView('doctors.doctors'),['infoData'=>$infoData,'city'=>$city,'infoDataNotFound'=>$infoDataNotFound]);
			}
	
			else if($type == "doctor") {
				$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0);
				if(!empty($name)) {
					if($name == "dr-umesh-kumar-jain-internal-medicine") {
						return abort(410);
					}
					$doc_id = getDoctorIdBySlug($name);
					if(!empty($doc_id) && count($doc_id) > 0 ) {
						$qry->Where(['id'=>$doc_id]);
						Session::put('search_id', $doc_id);
						Session::put('info_type', "Doctors");
						if(!empty($city)) {
							$qry->where('city_id',$city->id);
						}
					}
					else{
						Session::forget('info_type');
						return abort(404);
					}
				}
				else{
					Session::forget('info_type');
					return abort(404);
				}
				$infoData = $qry->first();
				
				if(!empty($infoData)) {
					Session::put('search_from_search_bar', $infoData->first_name." ".$infoData->last_name);
					Session::put('search_text', $infoData->first_name." ".$infoData->last_name);
					Session::put('dSplty', @$infoData->docSpeciality->spaciality);
					// $infoData = bindDocDataByUnique($infoData);
					Session::put('dCtN', @$infoData->city_id['name']);
					Session::put('dLtN', @$infoData->locality_id['name']);
	
					return view($this->getView('doctors.doctor-info'),['infoData'=>$infoData]);
				}
				else{
					Session::forget('info_type');
					return abort(404);
				}
			}
			else if($type == "doctors" || $type == "doctorsIn") {
				if($type == "doctorsIn") {
					Session::put('info_type', $type);
					Session::put('search_from_search_bar', $name);
					Session::put('search_text', $name);
				}
				else{
					Session::put('info_type', "doctor_all");
					Session::put('search_from_search_bar', $type);
				}
				$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('first_name');
				if(!empty($locality)) {
					// $qry->where('locality_id',$locality->id);
				}
				if(!empty($city)){
					// $qry->where('city_id',$city->id);
				}
				if($request->locality == "true" || $request->dexp != "" || $request->fmin != "" || $request->fmax != "" || $request->ctype != "") {
					$qry = $this->filteredDoctorData($qry,@$_COOKIE['locality'],$request->dexp,$request->fmin,$request->fmax,$request->ctype);
				}
				if($type == "doctorsIn") {
					if(!empty($name)) {
						$qry->where(DB::raw('concat(first_name," ",IFNULL(last_name,""))'), 'like', '%'.$name.'%');
					}
				}
				$infoData = $qry->get();
				$infoData = dataSequenceChange($infoData);
	
				$perPage = 10;
				$input = Input::all();
				if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }
	
				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
	
				if(count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.doctors'),['infoData'=>$infoData]);
			}
			else if($type == "hospital" || $type == "clinic") {
				$infoData = "";
				$infoDoctors = "";
	
				$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0);
				if(!empty($name)) {
					$doc_id = getClinicIdBySlug($name);
					if(!empty($doc_id)  && count($doc_id) > 0 ){
						$qry->Where(['practice_id'=>$doc_id]);
						Session::put('search_id', $doc_id);
						Session::put('info_type', "Clinic");
					}
					else{
						Session::forget('info_type');
						return abort(404);
					}
				}
				else{
					Session::forget('info_type');
					return abort(404);
				}
				$docData = $qry->first();
				if(!empty($docData)) {
					Session::put('search_from_search_bar',@$docData->clinic_name);
					Session::put('search_text', @$docData->clinic_name);
					Session::put('dSplty', @$docData->docSpeciality->spaciality);
					if(!empty($docData->practice_id)){
						$infoDoctors = Doctors::with(["docSpeciality","DoctorRatingReviews"])->Where(['practice_id'=>$docData->practice_id])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->get();
					}
					else{
						$infoDoctors = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where('clinic_name', 'like', '%'.$docData->clinic_name.'%')->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->get();
					}
					$infoData = bindDocDataByUnique($docData);
					Session::put('dCtN', @$infoData->city_id['name']);
					Session::put('dLtN', @$infoData->locality_id['name']);
				}
				else{
					return abort(404);
				}
				if(!empty($infoDoctors)) {
					$infoDoctors = bindDocData($infoDoctors);
				}
				return view($this->getView('doctors.hospital-info'),['infoData'=>$infoData,'infoDoctors'=>$infoDoctors]);
			}
			else if($type == "hospitals" || $type == "clinics"  || $type == "clinicIn"  || $type == "hospitalIn" ) {
				if($type == "hospitals") {
					Session::put('info_type', "hos_all");
					Session::put('search_from_search_bar', $type);
					Session::put('search_text', $type);
				}
				else if($type == "clinics") {
					Session::put('info_type', "clinic_all");
					Session::put('search_from_search_bar', $type);
					Session::put('search_text', $type);
				}
				else{
					Session::put('info_type', $type);
					Session::put('search_from_search_bar', $name);
					Session::put('search_text', $type);
				}
	
				$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->whereNotNull('clinic_name')->where("oncall_status","!=",0);
				if($type == "hospitals" || $type == "hospitalIn") {
					$qry->where('practice_type',2);
				}
				else if($type == "clinics" || $type == "clinicIn") {
					$qry->where('practice_type',1);
				}
				if(!empty($locality)) {
					// $qry->where('locality_id',$locality->id);
				}
				if(!empty($city)){
					// $qry->where('city_id',$city->id);
				}
				if($request->locality == "true" || $request->dexp != "" || $request->fmin != "" || $request->fmax != "" || $request->ctype != "") {
					$qry = $this->filteredDoctorData($qry,@$_COOKIE['locality'],$request->dexp,$request->fmin,$request->fmax,$request->ctype);
				}
				if($type == "clinicIn" || $type == "hospitalIn"){
					if(!empty($name)) {
						$qry->where('clinic_name', 'like', '%'.$name.'%');
					}
				}
				$infoData = $qry->groupBy('clinic_name')->get();
				$infoData = dataSequenceChange($infoData);
	
				$perPage = 10;
				$input = Input::all();
				if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }
				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				if(count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.hospital_list'),['infoData'=>$infoData]);
			}
			else if(!empty($symptoms)) {
				Session::put('info_type', "symptoms");
				Session::put('search_from_search_bar', $type);
				Session::put('search_text', $type);
				if($name == 'speciality') {
					$spaciality_ids = [];
					if(count($symptoms->SymptomsSpeciality)>0){
						foreach($symptoms->SymptomsSpeciality  as $raw){
							$spaciality_ids[] = $raw->speciality_id;
						}
					}
					$query = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('speciality')->where('city_id',$city->id);
					if(count($spaciality_ids) > 0 ) {
						$query->whereHas('docSpeciality', function($q)  use ($spaciality_ids) {$q->whereIn('id',$spaciality_ids);});
					}
					$infoData = $query->get();
					$infoData = dataSequenceChange($infoData);
					$perPage = 10;
					$input = Input::all();
					if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }
	
					$offset = ($currentPage * $perPage) - $perPage;
					$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
					$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
					if(count($infoData) > 0) {
						$infoData = bindDocData($infoData);
					}
					return view($this->getView('doctors.doctors'),['infoData'=>$infoData,'city'=>$city,'infoDataNotFound'=>null]);
				}
				return view($this->getView('doctors.smptoms_details'),['infoData'=>$symptoms]);
			}
			else {
				return abort(404);
			}
		}


	public function findDoctorCityByType(Request $request,$city,$type = null,$name = null) {
		$speciaity = Speciality::where('slug',$type)->first();
		$symptoms = null;
		$search = '';
		if(empty($speciaity)) {
			$symptoms = Symptoms::with(["SymptomsSpeciality","SymptomTags"])->where(['slug'=>$type])->first();
		}
		$city = City::where(["slug"=>$city])->first();
		$locality = CityLocalities::where(["slug"=>$name])->first();
		Session::forget('bySpacialityId');
		Session::forget('dCtN');
		Session::forget('dLtN');

		if(!empty($locality)){
			Session::put('locality_id', $locality->id);
			Session::put('search_from_locality_name', $locality->name);
			Session::put('locality_slug', $locality->slug);
		}
		else{
			if(!empty($city)) {
				Session::put('search_from_locality_name', $city->name);
			}
			Session::forget('locality_id');
			Session::forget('locality_slug');
		}
		if(!empty($city)) {
			Session::put('state_id', $city->State->id);
			Session::put('city_id', $city->id);
			Session::put('search_from_state_name',$city->State->name);
			Session::put('search_from_city_name', $city->name);
			Session::put('search_from_city_slug', $city->slug);
		}

		if(!empty($speciaity)) {
			Session::put('grp_id', $speciaity->group_id);
			Session::put('search_id', $speciaity->id);
			Session::put('info_type', "Speciality");
			Session::put('search_from_search_bar', $speciaity->spaciality);
			Session::put('search_text', $speciaity->speciality_text);
			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->whereNotNull('speciality')->where("oncall_status","!=",0);

			if(!empty($locality)) {
				// $qry->where('locality_id',$locality->id);
			}
			if(!empty($city) && $request->ctype == 2) {
				$qry->where('city_id',$city->id);
			}
			if(!empty($speciaity)) {
				$s_ids = Speciality::where(["group_id"=>$speciaity->group_id])->pluck('id');
				$qry->whereIn('speciality',$s_ids);
			}
			if($request->locality == "true" || $request->dexp != "" || $request->fmin != "" || $request->fmax != "" || $request->ctype != "") {
				$qry = $this->filteredDoctorData($qry,@$_COOKIE['locality'],$request->dexp,$request->fmin,$request->fmax,$request->ctype);
			}
			if (!empty($request->input('search'))) {
				$search = $request->input('search');
				 $search = str_replace("-"," ",$search);
		   }
		   $infoData = $qry->limit(4)->get();

			$infoDataNotFound = $infoData;
			if(count($infoData) <= 0 && !empty($speciaity) && !empty($city)){
				$infoData = Doctors::with(["docSpeciality","DoctorRatingReviews"])
				->where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])
				->whereNotNull('speciality')
				->where("oncall_status", "!=", 0)
				->where('city_id', $city->id)
				->whereIn('speciality', $s_ids)
				->limit(4)
				->get();
			}
			
			$infoData = dataSequenceChange($infoData,$speciaity->id);
			
			$infoData = bindDocData($infoData);
			$available_now = array_column($infoData, 'available_now');
			array_multisort($available_now, SORT_DESC, $infoData);

			$byrating = array_column($infoData, 'doc_rating');
			array_multisort($byrating, SORT_DESC, $infoData);
			if(count($infoData) > 0) {
				if(!empty($city)) { 
					$currCityDocs = array();
					$othCityDocs = array();
					$kkDoc = array();
					foreach($infoData as $doc) {
						if($doc['city_id']['id'] == $city->id){
						   array_push($kkDoc,$doc);
						}else if($doc['city_id']['id'] != $city->id){
						   array_push($currCityDocs,$doc);
						}else{
						   array_push($othCityDocs,$doc);
						}
					}
					// dd($kkDoc);
					$finArr = array_merge($kkDoc,$currCityDocs);
					$infoData = array_merge($finArr,$othCityDocs);
				}
			}
			if ($request->ajax()) {
                $infoData = $this->loadMoreData($qry, 2); // Load 10 records for example
                return response()->json($infoData);
            }
		
			return view($this->getView('doctors.doctors'),['infoData'=>$infoData,'city'=>$city,'infoDataNotFound'=>$infoDataNotFound]);
		}
		else if($type == "speciality") {
			
			Session::forget('grp_id');
			// Session::put('search_id', $speciaity->id);
			Session::put('info_type', "Speciality");
			$data = $request->all();
			$specialities = [];
			if(!empty($data['speciality'])) {
				$specialities = explode(",",$data['speciality']);
				$speciaityIds = Speciality::whereIn('slug',$specialities)->pluck("id");
				Session::put('bySpacialityId', $speciaityIds);
			}

			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('speciality');

			if(!empty($locality)) {
				//$qry->where('locality_id',$locality->id);
			}
			if(!empty($city)) {
				//$qry->where('city_id',$city->id);
			}
			if(count($specialities) > 0 ) {
				$qry->whereHas('docSpeciality', function($q)  use ($specialities) {$q->whereIn('slug',$specialities);});
			}

			if($request->locality == "true" || $request->dexp != "" || $request->fmin != "" || $request->fmax != "" || $request->ctype != "") {
				$qry = $this->filteredDoctorData($qry,@$_COOKIE['locality'],$request->dexp,$request->fmin,$request->fmax,$request->ctype);
			}
			$infoData = $qry->get();
			$infoDataNotFound = $infoData;
			if(count($infoData) <= 0 && count($specialities) > 0 && !empty($city)){
				$query = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('speciality')->where('city_id',$city->id);
				if(count($specialities) > 0 ) {
					$query->whereHas('docSpeciality', function($q)  use ($specialities) {$q->whereIn('slug',$specialities);});
				}
				$infoData = $query->get();
			
			}
			
			$infoData = dataSequenceChange($infoData);
			$perPage = 10;
			if(count($infoData) > 0) {
				$infoData = bindDocData($infoData);
			}
			return view($this->getView('doctors.doctors'),['infoData'=>$infoData,'city'=>$city,'infoDataNotFound'=>$infoDataNotFound]);
		}

		else if($type == "doctor") {
			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0);
			if(!empty($name)) {
				if($name == "dr-umesh-kumar-jain-internal-medicine") {
					return abort(410);
				}
				$doc_id = getDoctorIdBySlug($name);
				if(!empty($doc_id) && count($doc_id) > 0 ) {
					$qry->Where(['id'=>$doc_id]);
					Session::put('search_id', $doc_id);
					Session::put('info_type', "Doctors");
					if(!empty($city)) {
						$qry->where('city_id',$city->id);
					}
				}
				else{
					Session::forget('info_type');
					return abort(404);
				}
			}
			else{
				Session::forget('info_type');
				return abort(404);
			}
			$infoData = $qry->first();
			
			if(!empty($infoData)) {
				Session::put('search_from_search_bar', $infoData->first_name." ".$infoData->last_name);
				Session::put('search_text', $infoData->first_name." ".$infoData->last_name);
				Session::put('dSplty', @$infoData->docSpeciality->spaciality);
				// $infoData = bindDocDataByUnique($infoData);
				Session::put('dCtN', @$infoData->city_id['name']);
				Session::put('dLtN', @$infoData->locality_id['name']);

				return view($this->getView('doctors.doctor-info'),['infoData'=>$infoData]);
			}
			else{
				Session::forget('info_type');
				return abort(404);
			}
		}
		else if($type == "doctors" || $type == "doctorsIn") {
			if($type == "doctorsIn") {
				Session::put('info_type', $type);
				Session::put('search_from_search_bar', $name);
				Session::put('search_text', $name);
			}
			else{
				Session::put('info_type', "doctor_all");
				Session::put('search_from_search_bar', $type);
			}
			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('first_name');
			if(!empty($locality)) {
				// $qry->where('locality_id',$locality->id);
			}
			if(!empty($city)){
				// $qry->where('city_id',$city->id);
			}
			if($request->locality == "true" || $request->dexp != "" || $request->fmin != "" || $request->fmax != "" || $request->ctype != "") {
				$qry = $this->filteredDoctorData($qry,@$_COOKIE['locality'],$request->dexp,$request->fmin,$request->fmax,$request->ctype);
			}
			if($type == "doctorsIn") {
				if(!empty($name)) {
					$qry->where(DB::raw('concat(first_name," ",IFNULL(last_name,""))'), 'like', '%'.$name.'%');
				}
			}
			$infoData = $qry->get();
			$infoData = dataSequenceChange($infoData);

			$perPage = 10;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }

			$offset = ($currentPage * $perPage) - $perPage;
			$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
			$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

			if(count($infoData) > 0) {
				$infoData = bindDocData($infoData);
			}
			return view($this->getView('doctors.doctors'),['infoData'=>$infoData,'city'=>$city]);
		}
		else if($type == "hospital" || $type == "clinic") {
			$infoData = "";
			$infoDoctors = "";

			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0);
			if(!empty($name)) {
				$doc_id = getClinicIdBySlug($name);
				if(!empty($doc_id)  && count($doc_id) > 0 ){
					$qry->Where(['practice_id'=>$doc_id]);
					Session::put('search_id', $doc_id);
					Session::put('info_type', "Clinic");
				}
				else{
					Session::forget('info_type');
					return abort(404);
				}
			}
			else{
				Session::forget('info_type');
				return abort(404);
			}
			$docData = $qry->first();
			if(!empty($docData)) {
				Session::put('search_from_search_bar',@$docData->clinic_name);
				Session::put('search_text', @$docData->clinic_name);
				Session::put('dSplty', @$docData->docSpeciality->spaciality);
				if(!empty($docData->practice_id)){
					$infoDoctors = Doctors::with(["docSpeciality","DoctorRatingReviews"])->Where(['practice_id'=>$docData->practice_id])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->get();
				}
				else{
					$infoDoctors = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where('clinic_name', 'like', '%'.$docData->clinic_name.'%')->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->get();
				}
				$infoData = bindDocDataByUnique($docData);
				Session::put('dCtN', @$infoData->city_id['name']);
				Session::put('dLtN', @$infoData->locality_id['name']);
			}
			else{
				return abort(404);
			}
			if(!empty($infoDoctors)) {
				$infoDoctors = bindDocData($infoDoctors);
			}
			return view($this->getView('doctors.hospital-info'),['infoData'=>$infoData,'infoDoctors'=>$infoDoctors,'city'=>$city]);
		}
		else if($type == "hospitals" || $type == "clinics"  || $type == "clinicIn"  || $type == "hospitalIn" ) {
			if($type == "hospitals") {
				Session::put('info_type', "hos_all");
				Session::put('search_from_search_bar', $type);
				Session::put('search_text', $type);
			}
			else if($type == "clinics") {
				Session::put('info_type', "clinic_all");
				Session::put('search_from_search_bar', $type);
				Session::put('search_text', $type);
			}
			else{
				Session::put('info_type', $type);
				Session::put('search_from_search_bar', $name);
				Session::put('search_text', $type);
			}

			$qry = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->whereNotNull('clinic_name')->where("oncall_status","!=",0);
			if($type == "hospitals" || $type == "hospitalIn") {
				$qry->where('practice_type',2);
			}
			else if($type == "clinics" || $type == "clinicIn") {
				$qry->where('practice_type',1);
			}
			if(!empty($locality)) {
				// $qry->where('locality_id',$locality->id);
			}
			if(!empty($city)){
				// $qry->where('city_id',$city->id);
			}
			if($request->locality == "true" || $request->dexp != "" || $request->fmin != "" || $request->fmax != "" || $request->ctype != "") {
				$qry = $this->filteredDoctorData($qry,@$_COOKIE['locality'],$request->dexp,$request->fmin,$request->fmax,$request->ctype);
			}
			if($type == "clinicIn" || $type == "hospitalIn"){
				if(!empty($name)) {
					$qry->where('clinic_name', 'like', '%'.$name.'%');
				}
			}
			$infoData = $qry->groupBy('clinic_name')->get();
			$infoData = dataSequenceChange($infoData);

			$perPage = 10;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }
			$offset = ($currentPage * $perPage) - $perPage;
			$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
			$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
			if(count($infoData) > 0) {
				$infoData = bindDocData($infoData);
			}
			return view($this->getView('doctors.hospital_list'),['infoData'=>$infoData]);
		}
		else if(!empty($symptoms)) {
			Session::put('info_type', "symptoms");
			Session::put('search_from_search_bar', $type);
			Session::put('search_text', $type);
			if($name == 'speciality') {
				$spaciality_ids = [];
				if(count($symptoms->SymptomsSpeciality)>0){
					foreach($symptoms->SymptomsSpeciality  as $raw){
						$spaciality_ids[] = $raw->speciality_id;
					}
				}
				$query = Doctors::with(["docSpeciality","DoctorRatingReviews"])->where(["delete_status"=>1,'status'=>1,'varify_status'=>1])->where("oncall_status","!=",0)->whereNotNull('speciality')->where('city_id',$city->id);
				if(count($spaciality_ids) > 0 ) {
					$query->whereHas('docSpeciality', function($q)  use ($spaciality_ids) {$q->whereIn('id',$spaciality_ids);});
				}
				$infoData = $query->get();
				$infoData = dataSequenceChange($infoData);
				$perPage = 10;
				$input = Input::all();
				if (isset($input['page']) && !empty($input['page'])) { $currentPage = (int) $input['page']; } else { $currentPage = 1; }

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				if(count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.doctors'),['infoData'=>$infoData,'city'=>$city,'infoDataNotFound'=>null]);
			}
			return view($this->getView('doctors.smptoms_details'),['symps'=>$symptoms]);
		}
		else {
			return abort(404);
		}
	}




	public function filteredDoctorData($qry,$locality,$exp,$fmin,$fmax,$ctype) {
		$exp = base64_decode($exp);
		$fmin = base64_decode($fmin);
		$fmax = base64_decode($fmax);
		$ctype = base64_decode($ctype);
		if (!empty($ctype)) {
			if ($ctype == 1) {
				$qry->whereRaw("find_in_set('1',doctors.oncall_status)");
			}
			elseif ($ctype == 2) {
				$qry->whereRaw("find_in_set('2',doctors.oncall_status)");
			}
		}
		if(!empty($locality) && count(json_decode($locality,true)) > 0) {
			$filterLocality = json_decode($locality,true);
			$qry->whereIn('locality_id',$filterLocality);
		}
		if(!empty($exp)){
			if($exp == '5'){
				 $qry->whereBetween('experience',[1,5]);
			}
			else if($exp == '10'){
				$qry->whereBetween('experience',[5,10]);
			}
			else if($exp == '15'){
				$qry->whereBetween('experience',[10,15]);
			}
			else if($exp == '20'){
				$qry->whereBetween('experience',[15,20]);
			}
			else if($exp == '1'){
				$qry->where('experience','>=',20);
			}
		}
		if($fmin != "" && $fmin > 0) {
			if ($ctype == 1) {
				$qry->where('oncall_fee',">=",$fmin);
			}
			else if ($ctype == 2) {
				$qry->where('consultation_fees' , ">=",$fmin);
			}
			else{
				$qry->where('consultation_fees' , ">=",$fmin);
			}
		}
		if(!empty($fmax) && $fmax <= 10000) {
			if ($ctype == 1) {
				$qry->where('oncall_fee',"<=",$fmax);
			}
			else if ($ctype == 2) {
				$qry->where('consultation_fees',"<=",$fmax);
			}
			else{
				$qry->where('consultation_fees',"<=",$fmax);
			}
		}
		return $qry;
	}


// 	public function getProfilePicUrl($path)
// {
//     if (filter_var($path, FILTER_VALIDATE_URL)) {
//         return response()->json(['url' => $path]);
//     }
//     $url = Storage::disk('s3')->temporaryUrl($path, Carbon::now()->addHours(24));
//     return response()->json(['url' => $url]);
// }

}
