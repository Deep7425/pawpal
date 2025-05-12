<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Doctors;

/**ehr db models */

use App\Models\ehr\User as ehrUser;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\DoctorsInfo;
use App\Models\ehr\StaffsInfo;
use App\Models\ehr\RoleUser;
use App\Models\ehr\OpdTimings;
use App\Models\ehr\Plans;
use App\Models\ehr\CityLocalities;
use App\Models\ehr\ManageTrailPeriods;
use App\Models\ehr\PatientRagistrationNumbers;
use App\Models\ehr\Patients;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\Appointments;
use App\Models\ehr\PracticeDocuments;
use App\Models\ehr\clinicalNotePermissions;
use App\Models\Admin\SymptomsSpeciality;
use App\Models\ehr\AppointmentOrder;
use App\Models\Admin\Symptoms;
use App\Models\Admin\SymptomTags;
use App\Models\OtpPracticeDetails;
use App\Models\Speciality;
use App\Models\PatientFeedback;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\CouncilingData;
use App\Models\UniversityList;
use App\Models\DoctorSlug;
use App\Models\PlanPeriods;
use App\Models\LabOrders;
use App\Models\DoctorData;
use App\Http\Controllers\PaytmChecksum;
use App\Models\CcavenueResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Support\Facades\Storage;
use PaytmWallet;
use Razorpay\Api\Api;
use Carbon\Carbon;


class DocController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public	function setCityStateIds(Request $request)
	{
		$city_id = '';
		$state_id = '';
		$locality_id = '';
		if (!empty($request->city_name) || !empty($request->state_name) || !empty($request->locality)) {
			if (!empty(getIdByLocality($request->locality, $request->city_name, $request->state_name))) {
				$locality_id = getIdByLocality($request->locality, $request->city_name, $request->state_name);
			}
			if (!empty(getIdByCity($request->city_name, $request->state_name))) {
				$city_id = getIdByCity($request->city_name, $request->state_name);
			}
			if (!empty(getIdByState($request->state_name))) {
				$state_id = getIdByState($request->state_name);
			}
		}

		$ids['locality_id'] = $locality_id['id'];
		$ids['city_id'] = $city_id['id'];
		$ids['state_id'] = $state_id;
		$ids['city_slug'] = $city_id['slug'];
		$ids['locality_slug'] = $locality_id['slug'];
		return $ids;
	}

	public function verifyDocDetails(Request $request)
	{
		if (!empty($request->docInfo)) {
			$docInfo = $request->docInfo;
			if ($request->v_type == 1) {
				$query = Doctors::select(["id", "first_name", "last_name", "mobile_no", "email", "varify_status"])->where(['mobile_no' => $docInfo]);
				if (!empty($request->doc_id)) {
					$query->where('id', '!=', $request->doc_id);
				}
				$user_exists = $query->first();
			} else {
				$qry = Doctors::select(["id", "first_name", "last_name", "mobile_no", "email", "varify_status"])->where(['email' => $docInfo]);
				if (!empty($request->doc_id)) {
					$qry->where('id', '!=', $request->doc_id);
				}
				$user_exists = $qry->first();
			}
			$doc_id = "";
			if (!empty($user_exists)) {
				$doc_id = @$user_exists->id;
				$name = @$user_exists->first_name . " " . @$user_exists->last_name;
				return array("name" => $name, "varify_status" => $user_exists->varify_status, "status" => "1", "id" => $doc_id, "mobile_no" => @$user_exists->mobile_no);
			} else {
				return array("name" => "", "status" => "0", "varify_status" => @$user_exists->varify_status, "id" => $doc_id, "mobile_no" => @$user_exists->mobile_no);
			}
		}
		array("name" => "", "status" => "3", "id" => "");
	}

	public function verifyDocDetailsEdit(Request $request)
	{
		if (!empty($request->docInfo)) {
			$docInfo = $request->docInfo;
			if ($request->v_type == 1) {
				$query = Doctors::where(['mobile_no' => $docInfo]);
				if (!empty($request->doc_id)) {
					$query->where('id', '!=', $request->doc_id);
				}
				$user_exists = $query->count();
			} else {
				$qry = Doctors::where(['email' => $docInfo]);
				if (!empty($request->doc_id)) {
					$qry->where('id', '!=', $request->doc_id);
				}
				$user_exists = $qry->count();
			}
			if ($user_exists > 0) {
				return 1;
			} else {
				return 2;
			}
		}
		return 3;
	}

	public function setSessionLocality(Request $request)
	{
		$lat = "";
		$lng = "";
		$city_name = "";
		$state_name = "";
		$locality_name = "";
		$locality_id = "";
		$city_id = "";
		$state_id = "";
		$city_slug = "";
		$locality_slug = "";
		$locality = "";
		$conType = "";
		if (!empty($request->lat)) {
			$lat = $request->lat;
		}
		if (!empty($request->lng)) {
			$lng = $request->lng;
		}
		if (!empty($request->city_name)) {
			$city_name = $request->city_name;
		}
		if (!empty($request->state_name)) {
			$state_name = $request->state_name;
		}
		if (!empty($request->locality)) {
			$locality = $request->locality;
		}

		if (!empty($request->locality_id)) {
			$locality_id = $request->locality_id;
		}
		if (!empty($request->city_id)) {
			$city_id = $request->city_id;
		}
		if (!empty($request->state_id)) {
			$state_id = $request->state_id;
		}
		if (!empty($request->city_slug)) {
			$city_slug = $request->city_slug;
		}
		if (!empty($request->locality_slug)) {
			$locality_slug = $request->locality_slug;
		}
		if (!empty($request->conType)) {
			$conType = $request->conType;
		}

		Session::put('session_lat', $lat);
		Session::put('session_lng', $lng);
		Session::put('search_from_city_name', $city_name);
		Session::put('search_from_state_name', $state_name);
		Session::put('search_from_locality_name', $locality);
		Session::put('state_id', $state_id);
		Session::put('city_id', $city_id);
		Session::put('locality_id', $locality_id);
		Session::put('locality_slug', $locality_slug);
		Session::put('search_from_city_slug', $city_slug);
		Session::put('conType', $conType);
		return 1;
	}
	public function sendClaimOtp(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$otp = rand(100000, 999999);
			$otp_details = "";
			if (!empty($data['id'])) {
				OtpPracticeDetails::where('id', $data['id'])->update(['otp' => $otp, 'mobile_no' => $data['mobile_number']]);
				$otp_details = OtpPracticeDetails::where('id', $data['id'])->first();
			} else {
				$otp_details = OtpPracticeDetails::create(['user_id' => $data['id'], 'otp' => $otp, 'mobile_no' => $data['mobile_number']]);
			}
			if (!empty($data['mobile_number'])) {
				$message = urlencode("Use " . $otp . " to verify your mobile number Thanks Team Health Gennie");
				$this->sendSMS($data['mobile_number'], $message, '1707161588017225680');
			}
			return $otp_details->id;
		}
	}
	public function verifyClaimOtp(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$otp = $data['otp'];
			$otp_details = "";
			if (!empty($data['id'])) {
				saveUserActivity($request, 'verifyClaimOtp', 'otp_practice_details ', $data['id']);
				$otp_details = OtpPracticeDetails::where('id', $data['id'])->first();
				if ($otp_details->otp == $otp) {
					return 1;
				} else {
					return 2;
				}
			}
		}
	}


	public function addDoc(Request $request)
	{
		Session::forget('info_type');
		if ($request->isMethod('post')) {
			$data = $request->all();
			$validator = Validator::make($data, [
				'first_name' => 'required|max:255',
				'last_name' => 'required|max:50',
				'clinic_name' => 'required|max:255',
				'bank_name' => 'max:100',
				'ifsc_no' => 'max:11',
				'acc_no' => 'max:255',
				'mobile_no' => 'required|numeric|min:11',
				// 'paytm_no' => 'min:11',
				'email' => 'required|email|max:50',
				// 'clinic_email' => 'email|max:50',
				'practice_type' => 'required|max:10',
				// 'consultation_fees' => 'required|numeric',
				// 'consultation_discount' => 'max:6',
				'address_1' => 'required|max:255',
				'recommend' => 'max:150',
				'website' => 'max:100',
				'qualification' => 'required|max:150',
				'speciality' => 'required',
				'clinic_speciality' => 'required',
				'experience' => 'required|max:2',
				'reg_no' => 'required|max:50',
				'reg_year' => 'required|max:4',
				'reg_council' => 'required|max:200',
				'content' => 'max:1500',
				'last_obtained_degree' => 'max:100',
				'degree_year' => 'max:4',
				'university' => 'max:200',
				'country_id' => 'required|max:10',
				'state_id' => 'required|max:10',
				'city_id' => 'required|max:10',
				// 'zipcode' => 'max:6',
				'slot_duration' => 'required|max:10',
				// 'oncall_status' => 'required',
			]);
			if ($validator->fails()) {
				$errors = $validator->errors();
				if (!empty($data['id'])) {
					return redirect()->route('addDoc', ['id' => base64_encode($data['id']), 'mbl' => 'true'])->withErrors($validator)->withInput();
				} else {
					return redirect('claim-doctor')->withErrors($validator)->withInput();
				}
			} else {
				$mapLat = "";
				$mapLong = "";
				$fileName = "";
				$clincFileName = "";
				$old_image = "";

				if (!empty($data['profile_picBlob'])) {
					$imgdata = base64_decode($data['profile_picBlob']);
					$image_name =  time() . '.png';
					Storage::disk('s3')->put("public/doctor/ProfilePics/" . $image_name, $imgdata);
					$fileName = $image_name;
					unset($data['profile_picBlob']);
					if (isset($data['old_profile_pic']) && !empty($data['old_profile_pic'])) {
						$oldFilename = "public/doctor/ProfilePics/" . $data['old_profile_pic'];
						if (Storage::disk('s3')->exists($oldFilename)) {
							Storage::disk('s3')->delete($oldFilename);
						}
						$old_image = $data['old_profile_pic'];
					}
				} else {
					$fileName = isset($data['old_profile_pic']) ? $data['old_profile_pic'] : "";
				}

				if (!empty($data['clinic_imageBlob'])) {
					$imgdata = base64_decode($data['clinic_imageBlob']);
					// $filepath = public_path().'/patients_pics/';
					$image_name =  time() . '.png';
					// $file = getEhrBasePath()."/public/doctor/".$image_name;
					// $file = public_path()."/clinic/".$image_name;
					// file_put_contents($file,$imgdata);
					Storage::disk('s3')->put("public/doctor/" . $image_name, $imgdata);
					//die;
					$clincFileName = $image_name;
					unset($data['clinic_imageBlob']);
					$old_clinic_image = "";
					if (isset($data['old_clinic_image']) && !empty($data['old_clinic_image'])) {
						$oldClinicFilename = "public/doctor/" . $data['old_clinic_image'];
						if (Storage::disk('s3')->exists($oldClinicFilename)) {
							Storage::disk('s3')->delete($oldClinicFilename);
						}
						// if(file_exists($oldClinicFilename)){
						File::delete($oldClinicFilename);
						// }
						$old_clinic_image = $data['old_clinic_image'];
					}
				} else {
					$clincFileName = isset($data['old_clinic_image']) ? $data['old_clinic_image'] : "";
				}

				if (!empty($data['id'])) {
					$edit_doc_new = $this->addNewDoctor($data, $fileName, $clincFileName, $mapLat, $mapLong);
					if (!empty($data['mobile_no'])) {
						$username = ucfirst($data['first_name']) . " " . $data['last_name'];
						$message = urlencode("Dear Dr. " . $username . ", Thanks for registering Your profile on HealthGennie. Your profile verification is under process.We may contact you if required for further verification, please call us at 8302072136 for more information Thanks Team Health Gennie");
						$this->sendSMS($data['mobile_no'], $message, '1707161588012951244');
					}
					Session::put('offer_doc_id', $data['id']);
					Session::flash('message', "Profile Claimed Successfully we will contact soon");
					saveUserActivity($request, 'addDoc', 'doctors', $data['id']);
					return redirect()->route('hgOffersPlans', ['doc_id' => base64_encode($data['id'])]);
				} else {
					$add_new_doc = $this->addNewDoctor($data, $fileName, $clincFileName, $mapLat, $mapLong);
					if (!empty($data['mobile_no'])) {
						$username = ucfirst($data['first_name']) . " " . $data['last_name'];
						$message = urlencode("Dear Dr. " . $username . ", Thanks for registering Your profile on HealthGennie. Your profile verification is under process.We may contact you if required for further verification, please call us at 8302072136 for more information Thanks Team Health Gennie");
						$this->sendSMS($data['mobile_no'], $message, '1707161588012951244');
					}
					Session::flash('message', "Profile Claimed Successfully we will contact soon");
					Session::put('offer_doc_id', $add_new_doc);
					saveUserActivity($request, 'addDoc', 'doctors', $add_new_doc);
					return redirect()->route('hgOffersPlans', ['doc_id' => base64_encode($add_new_doc)]);
				}
			}
		}
		$docData = "";
		if (!empty($request->id)) {
			$docData = Doctors::Where(['id' => base64_decode($request->id)])->first();
			if (!empty($docData->country_id)) {
				$country_id = $docData->country_id;
				$state_id   = $docData->state_id;
			} else {
				$country_id = '101';
				$state_id   = '32';
			}

			if (!empty($docData->city_id)) {
				$city_id = $docData->city_id;
			} else {
				$city_id = '3378';
			}
			if (!empty($docData->profile_pic)) {
				$docData['profile_pic_url'] = getPath("public/doctor/ProfilePics/" . $docData->profile_pic);
			} else {
				$docData['profile_pic_url'] = null;
			}
			if (!empty($docData->clinic_image)) {
				$docData['clinic_image_url'] = getPath("public/doctor/" . $docData->clinic_image);
			} else {
				$docData['clinic_image_url'] = null;
			}
			$stateList =  parent::getUpdateStateList($country_id);
			$cityList =  parent::getUpdateCityList($state_id);
			$localityList =  parent::getUpdateLocalityList($city_id);
			return view($this->getView('doctors.edit-doctor'), ['docData' => $docData, 'stateList' => $stateList, 'cityList' => $cityList, 'localityList' => $localityList]);
		} else {
			return view($this->getView('doctors.add-doctor'), ['docData' => $docData]);
		}
	}

	function addNewDoctor($data, $fileName, $clincFileName, $mapLat, $mapLong)
	{
		if ($data['reg_council'] == '0') {
			$council_data = CouncilingData::create(["council_name" => $data['reg_council_other']]);
			$reg_council = $council_data->id;
		} else {
			$reg_council = $data['reg_council'];
		}

		if ($data['university'] == '0') {
			$uni_data = UniversityList::create(["name" => $data['other_university']]);
			$university = $uni_data->id;
		} else {
			$university = $data['university'];
		}
		$urls = json_encode(getEhrFullUrls());
		$clinic_id = "";
		if (!empty($data['clinic_id'])) {
			$clinic_id = $data['clinic_id'];
		}
		$schedule = "";
		if (isset($data['schedule']) && count($data['schedule']) > 0) {
			$schedule = json_encode($data['schedule']);
		}
		if (!empty($data['id'])) {
			$new_doc_id = $data['id'];
			Doctors::where('id', $data['id'])->update(array(
				'doc_type' => isset($data['doc_type']) ? $data['doc_type'] : 0,
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				'gender' =>  isset($data['gender']) ? $data['gender'] : "",
				'mobile_no' => $data['mobile_no'],
				'email' => $data['email'],
				'speciality' => $data['speciality'],
				'clinic_speciality' => $data['clinic_speciality'],
				'experience' => $data['experience'],
				'qualification' => $data['qualification'],
				'reg_no' => $data['reg_no'],
				'reg_year' => $data['reg_year'],
				'reg_council' => $reg_council,
				'last_obtained_degree' => $data['last_obtained_degree'],
				'degree_year' => $data['degree_year'],
				'university' => $university,
				'content' => $data['content'],
				'clinic_name' => $data['clinic_name'],
				'clinic_mobile' => $data['clinic_mobile'],
				'clinic_email' => $data['clinic_email'],
				'practice_type' =>  isset($data['practice_type']) ? $data['practice_type'] : "",
				'recommend' => $data['recommend'],
				'website' => $data['website'],
				'note' => $data['note'],
				'address_1' => $data['address_1'],
				'country_id' => $data['country_id'],
				'state_id' => $data['state_id'],
				'locality_id' => $data['locality_id'],
				'city_id' => $data['city_id'],
				'zipcode' => $data['zipcode'],
				'slot_duration' =>  $data['slot_duration'],
				'consultation_fees' => $data['consultation_fees'],
				// 'consultation_discount' => $data['consultation_discount'],
				'oncall_status' => (isset($data['tele_status']) ? $data['tele_status'] : 1),
				'oncall_fee' => (isset($data['oncall_fee']) ? $data['oncall_fee'] : null),
				'acc_name' => (isset($data['acc_name']) ? $data['acc_name'] : null),
				'acc_no' => (isset($data['acc_no']) ? $data['acc_no'] : null),
				'ifsc_no' => (isset($data['ifsc_no']) ? $data['ifsc_no'] : null),
				'bank_name' => (isset($data['bank_name']) ? $data['bank_name'] : null),
				'paytm_no' => (isset($data['paytm_no']) ? $data['paytm_no'] : null),
				'profile_pic' => $fileName,
				'clinic_image' => $clincFileName,
				'my_visits' => '{"1":{"id":"1","amount":"' . $data['consultation_fees'] . '"},"2":{"id":"4","amount":""}}',
				'claim_status' => 1,
				'claim_profile_web' => 1,
				'profile_status' => 1,
				'opd_timings' => $schedule,
				'lat' => $mapLat,
				'lng' => $mapLong,
				'practice_id' => $clinic_id,
			));
			$doctor_data = Doctors::where('id', $data['id'])->first();
			if ($data['followup_count'] > 0) {
				DoctorData::where('doc_id', $data['id'])->update(['followup_count' => $data['followup_count']]);
			}

			if (!empty($data['clinic_id'])) {
				$slug = DoctorSlug::where('practice_id', $clinic_id)->first();
				$clinicSlug = $slug->clinic_name_slug;
			} else {
				$clinicSlug = getClinicSlug($doctor_data, '1');
			}

			DoctorSlug::where('doc_id', $data['id'])->update(array(
				'practice_id' => $clinic_id,
				'clinic_name_slug' => strtolower($clinicSlug),
				"city_id" => $doctor_data->city_id
			));
		} else {
			$doctor_data = Doctors::create([
				'doc_type' => isset($data['doc_type']) ? $data['doc_type'] : 0,
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				'gender' =>  isset($data['gender']) ? $data['gender'] : "",
				'mobile_no' => $data['mobile_no'],
				'email' => $data['email'],
				'speciality' => $data['speciality'],
				'clinic_speciality' => $data['clinic_speciality'],
				'experience' => $data['experience'],
				'qualification' => $data['qualification'],
				'reg_no' => $data['reg_no'],
				'reg_year' => $data['reg_year'],
				'reg_council' => $reg_council,
				'last_obtained_degree' => $data['last_obtained_degree'],
				'degree_year' => $data['degree_year'],
				'university' => $university,
				'content' => $data['content'],
				'clinic_name' => $data['clinic_name'],
				'clinic_mobile' => $data['clinic_mobile'],
				'clinic_email' => $data['clinic_email'],
				'practice_type' =>  isset($data['practice_type']) ? $data['practice_type'] : "",
				'recommend' => $data['recommend'],
				'website' => $data['website'],
				'note' => $data['note'],
				'address_1' => $data['address_1'],
				'country_id' => $data['country_id'],
				'state_id' => $data['state_id'],
				'locality_id' => $data['locality_id'],
				'city_id' => $data['city_id'],
				'zipcode' => $data['zipcode'],
				'consultation_fees' => $data['consultation_fees'],
				// 'consultation_discount' => $data['consultation_discount'],
				'oncall_status' => (isset($data['tele_status']) ? $data['tele_status'] : 1),
				'oncall_fee' => (isset($data['oncall_fee']) ? $data['oncall_fee'] : null),
				'acc_name' => (isset($data['acc_name']) ? $data['acc_name'] : null),
				'acc_no' => (isset($data['acc_no']) ? $data['acc_no'] : null),
				'ifsc_no' => (isset($data['ifsc_no']) ? $data['ifsc_no'] : null),
				'bank_name' => (isset($data['bank_name']) ? $data['bank_name'] : null),
				'paytm_no' => (isset($data['paytm_no']) ? $data['paytm_no'] : null),
				'profile_pic' => $fileName,
				'clinic_image' => $clincFileName,
				'opd_timings' => $schedule,
				'lat' => $mapLat,
				'lng' => $mapLong,
				'slot_duration' =>  $data['slot_duration'],
				'urls' => $urls,
				'claim_status' => 1,
				'claim_profile_web' => 1,
				'profile_status' => 1,
				'my_visits' => '{"1":{"id":"1","amount":"' . $data['consultation_fees'] . '"},"2":{"id":"4","amount":""}}',
				'practice_id' => $clinic_id,
			]);


			if (!empty($data['clinic_id'])) {
				$slug = DoctorSlug::where('practice_id', $clinic_id)->first();
				$clinicSlug = $slug->clinic_name_slug;
			} else {
				$clinicSlug = getClinicSlug($doctor_data, '1');
			}
			if ($data['followup_count'] > 0) {
				DoctorData::create(['doc_id' => $doctor_data->id, 'followup_count' => $data['followup_count']]);
			}
			$docSlug = getDoctorSlug($doctor_data, '1');
			DoctorSlug::create(['doc_id' => $doctor_data->id, 'name_slug' => strtolower($docSlug), 'clinic_name_slug' => strtolower($clinicSlug), "city_id" => $doctor_data->city_id]);
			$new_doc_id = $doctor_data->id;
		}
		return $new_doc_id;
	}

	public function get_lat_long($address)
	{
		$address = str_replace(" ", "+", $address);
		$lat = 0;
		$long = 0;
		if (parent::is_connected() == 1) {
			$json = @file_get_contents("https://maps.google.com/maps/api/geocode/json?address=urlencode($address)&sensor=false&key=AIzaSyDrzKrcKQqGvZQjuMZtDQy3MHOpNjPmjnU");
			$json = json_decode($json);
			// pr($json->['results']);
			$lat = @$json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
			$long = @$json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
			return $lat . ',' . $long;
		}
		return $lat . ',' . $long;
	}

	public function doctorInfo(Request $request)
	{
		$data = $request->all();
		$infoData = [];
		if ($request->isMethod('post')) {
			Session::put('state_id', @$data['state_id']);
			Session::put('city_id', @$data['city_id']);
			Session::put('locality_id', @$data['locality_id']);
			Session::put('search_from_state_name', @$data['state_name']);

			Session::put('session_lat', $request->input('lat'));
			Session::put('session_lng', $request->input('lng'));
			Session::put('search_from_city_name', $request->input('city_name'));
			Session::put('search_from_locality_name', $request->input('locality'));
			Session::put('conType', $request->input('conType'));

			$params = array();
			if (!empty($request->input('info_type'))) {
				$params['info_type'] = base64_encode($request->input('info_type'));
			}
			if ($request->input('id') != "") {
				$params['id'] = base64_encode($request->input('id'));
			}
			if ($request->input('grp_id') != "") {
				$params['grp_id'] = base64_encode($request->input('grp_id'));
			}
			if (!empty($request->input('data_search'))) {
				if (!empty($request->input('info_type'))) {
					if ($request->input('info_type') == "Speciality") {
						Session::put('speciality_id', $request->input('id'));
					}
				}
				$params['data_search'] = base64_encode($request->input('data_search'));
			}
			if (!empty($request->input('filter_by_locality_put'))  && count(json_decode($request->input('filter_by_locality_put'))) > 0) {
				$params['filter_by_locality_put'] = $request->input('filter_by_locality_put');
			}
			if (!empty($request->input('filter_by_gender_put'))) {
				$params['filter_by_gender_put'] = base64_encode($request->input('filter_by_gender_put'));
			}
			if (!empty($request->input('filter_by_exp'))) {
				$params['filter_by_exp'] = base64_encode($request->input('filter_by_exp'));
			}
			if (!empty($request->input('sort_by'))) {
				$params['sort_by'] = base64_encode($request->input('sort_by'));
			}
			if (!empty($request->input('by_rate'))) {
				$params['by_rate'] = base64_encode($request->input('by_rate'));
			}
			if ($request->input('consult_fee_min') != '') {
				$params['consult_fee_min'] = base64_encode($request->input('consult_fee_min'));
			}
			if (!empty($request->input('consult_fee_max'))) {
				$params['consult_fee_max'] = base64_encode($request->input('consult_fee_max'));
			}
			if (!empty($request->input('bySpacialityId'))) {
				$params['bySpacialityId'] = base64_encode($request->input('bySpacialityId'));
			}
			if (!empty($request->input('conType'))) {
				$params['conType'] = base64_encode($request->input('conType'));
			}
			return redirect()->route('doctorInfo', $params)->withInput();
		} else {
			$data = $request->all();
			$search = base64_decode($request->input('data_search'));
			$filter_by_locality_put = $request->input('filter_by_locality_put');
			$filter_by_gender_put = base64_decode($request->input('filter_by_gender_put'));
			$filter_by_exp = base64_decode($request->input('filter_by_exp'));
			$consult_fee_min = base64_decode($request->input('consult_fee_min'));
			$consult_fee_max = base64_decode($request->input('consult_fee_max'));
			$bySpacialityId = base64_decode($request->input('bySpacialityId'));
			$sort_by = base64_decode($request->input('sort_by'));
			$by_rate = base64_decode($request->input('by_rate'));

			$s_state_id = Session::get('state_id');
			$s_city_id = Session::get('city_id');
			$s_locality_id = Session::get('locality_id');
			Session::put('search_from_search_bar', $search);

			$type = base64_decode($request->input('info_type'));
			$id = base64_decode($request->input('id'));
			$conType = base64_decode($request->input('conType'));

			if ($type == "Doctors") {
				$infoData = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->Where(['id' => $id])->first();
				if (!empty($infoData)) {
					$infoData = bindDocDataByUnique($infoData);
				}
				return view($this->getView('doctors.doctor-info'), ['infoData' => $infoData]);
			} else if ($type == "Clinic") {
				$query = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(['status' => 1, "delete_status" => 1])->whereNotNull('clinic_name');
				if (!empty($s_locality_id)) {
					//$query->where('locality_id',$s_locality_id);
				}
				if (!empty($s_city_id)) {
					//$query->where('city_id',$s_city_id);
				}
				if (empty($id) || $id == 0) {
					$query->where('clinic_name', 'like', '%' . $search . '%');
				} else {
					$query->where(['practice_id' => $id]);
				}

				$query = $this->filteredDocData($type, $query, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$query = $this->sortByFilterd($query, $type, $sort_by);
				if (empty($id) || $id == 0) {
					$infoData = $query->groupBy('clinic_name')->get();

					$infoData = dataSequenceChange($infoData);

					$perPage = 10;
					$input = $request->all();
					if (isset($input['page']) && !empty($input['page'])) {
						$currentPage = $input['page'];
					} else {
						$currentPage = 1;
					}

					$offset = ($currentPage * $perPage) - $perPage;
					$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
					$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

					if (count($infoData) > 0) {
						$infoData = bindDocData($infoData);
					}
					return view($this->getView('doctors.hospital_list'), ['infoData' => $infoData]);
				} else {
					$infoData = $query->get();
					$infoData = dataSequenceChange($infoData);

					$perPage = 10;
					$input = $request->all();
					if (isset($input['page']) && !empty($input['page'])) {
						$currentPage = $input['page'];
					} else {
						$currentPage = 1;
					}

					$offset = ($currentPage * $perPage) - $perPage;
					$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
					$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
					if (count($infoData) > 0) {
						$infoData = bindDocData($infoData);
					}
					return view($this->getView('doctors.doctors'), ['infoData' => $infoData]);
				}
			} else if ($type == "clinicIn") {
				$query = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(['status' => 1, "delete_status" => 1])->whereNotNull('clinic_name');
				if (!empty($s_locality_id)) {
					//$query->where('locality_id',$s_locality_id);
				}
				if (!empty($s_city_id)) {
					$query->where('city_id', $s_city_id);
				}
				if (empty($search)) {
					$query->where('clinic_name', 'like', '%' . $search . '%');
				}

				$query = $this->filteredDocData($type, $query, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$query = $this->sortByFilterd($query, $type, $sort_by);
				$infoData = $query->groupBy('clinic_name')->get();

				$infoData = dataSequenceChange($infoData);

				$perPage = 10;
				$input = $request->all();
				if (isset($input['page']) && !empty($input['page'])) {
					$currentPage = $input['page'];
				} else {
					$currentPage = 1;
				}

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

				if (count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.hospital_list'), ['infoData' => $infoData]);
			} else if ($type == "Hospital") {
				$query = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, "practice_type" => 2, "status" => 1])->whereNotNull('clinic_name');
				if (!empty($s_locality_id)) {
					//$query->where('locality_id',$s_locality_id);
				}
				if (!empty($s_city_id)) {
					//$query->where('city_id',$s_city_id);
				}
				if (empty($id) || $id == 0) {
					$query->where('clinic_name', 'like', '%' . $search . '%');
				} else {
					$query->where(['practice_id' => $id]);
				}

				$query = $this->filteredDocData($type, $query, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$query = $this->sortByFilterd($query, $type, $sort_by);
				if (empty($id) || $id == 0) {
					$infoData = $query->groupBy('clinic_name')->get();

					$infoData = dataSequenceChange($infoData);

					$perPage = 10;
					$input = $request->all();
					if (isset($input['page']) && !empty($input['page'])) {
						$currentPage = $input['page'];
					} else {
						$currentPage = 1;
					}

					$offset = ($currentPage * $perPage) - $perPage;
					$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
					$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

					if (count($infoData) > 0) {
						$infoData = bindDocData($infoData);
					}
					return view($this->getView('doctors.hospital_list'), ['infoData' => $infoData]);
				} else {
					$infoData = $query->get();
					$infoData = dataSequenceChange($infoData);

					$perPage = 10;
					$input = $request->all();
					if (isset($input['page']) && !empty($input['page'])) {
						$currentPage = $input['page'];
					} else {
						$currentPage = 1;
					}

					$offset = ($currentPage * $perPage) - $perPage;
					$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
					$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
					if (count($infoData) > 0) {
						$infoData = bindDocData($infoData);
					}
					return view($this->getView('doctors.doctors'), ['infoData' => $infoData]);
				}
			} else if ($type == "hospitalIn") {
				$query = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, "practice_type" => 2, "status" => 1])->whereNotNull('clinic_name');
				if (!empty($s_locality_id)) {
					//$query->where('locality_id',$s_locality_id);
				}
				if (!empty($s_city_id)) {
					//$query->where('city_id',$s_city_id);
				}
				if (empty($search)) {
					$query->where('clinic_name', 'like', '%' . $search . '%');
				}

				$query = $this->filteredDocData($type, $query, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$query = $this->sortByFilterd($query, $type, $sort_by);
				$infoData = $query->groupBy('clinic_name')->get();

				$infoData = dataSequenceChange($infoData);

				$perPage = 10;
				$input = $request->all();
				if (isset($input['page']) && !empty($input['page'])) {
					$currentPage = $input['page'];
				} else {
					$currentPage = 1;
				}

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

				if (count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.hospital_list'), ['infoData' => $infoData]);
			} else if ($type == "Speciality") {
				$grp_id = base64_decode($request->input('grp_id'));
				$qry = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->whereNotNull('speciality');

				$qry_locality = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->whereNotNull('speciality');

				if (!empty($s_locality_id)) {
					if (empty($filter_by_locality_put) && count(json_decode($filter_by_locality_put, true)) <= 0) {
						//$qry_locality->where('locality_id',$s_locality_id);
						//$qry->where('locality_id',$s_locality_id);
						//$qry_locality->where('locality_id',$s_locality_id);
					}
				}
				if (!empty($s_city_id)) {
					//$qry_locality->where('city_id',$s_city_id);
					//$qry->where('city_id',$s_city_id);
				}
				if (!empty($bySpacialityId) && count(json_decode($bySpacialityId, true)) > 0) {
					$qry->WhereIn('speciality', json_decode($bySpacialityId, true));
					$qry_locality->WhereIn('speciality', json_decode($bySpacialityId, true));
				} else {
					$s_ids = Speciality::where(["group_id" => $grp_id])->pluck('id');
					$qry_locality->whereIn('speciality', $s_ids);
					$qry->whereIn('speciality', $s_ids);
				}
				$qry = $this->filteredDocData($type, $qry, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$qry = $this->sortByFilterd($qry, $type, $sort_by);
				$infoData = $qry->get();
				$infoDataNotFound = $infoData;
				$specialityLocalityData = $qry_locality->get();

				if ($infoDataNotFound->count() == 0 &&  $specialityLocalityData->count() > 0) {
					$infoData = $infoData->merge($specialityLocalityData);
				}

				$infoData = dataSequenceChange($infoData, $id);

				if ($infoData->count() > 0) {
					$infoData = bindDocData($infoData);
					$available_now = array_column($infoData, 'available_now');
					array_multisort($available_now, SORT_DESC, $infoData);

					$byrating = array_column($infoData, 'doc_rating');
					array_multisort($byrating, SORT_DESC, $infoData);
				}
				// $docs_arr = [];
				// foreach($infoData as $info){
				// if($info->speciality == $id){
				// $docs_arr[] = $info;
				// }
				// }
				// foreach($infoData as $info){
				// if($info->speciality != $id){
				// $docs_arr[] = $info;
				// }
				// }
				// $infoData = $docs_arr;
				$perPage = 10;
				$input = $request->all();
				if (isset($input['page']) && !empty($input['page'])) {
					$currentPage = $input['page'];
				} else {
					$currentPage = 1;
				}

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				$docDataBYSpeciality = $specialityLocalityData;
				return view($this->getView('doctors.doctors'), ['infoData' => $infoData, 'docDataBYSpeciality' => $docDataBYSpeciality, 'infoDataNotFound' => $infoDataNotFound]);
			} else if ($type == "doctorsIn") {
				$qry = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, "status" => 1])->whereNotNull('first_name');
				if (!empty($s_locality_id)) {
					//$qry->where('locality_id',$s_locality_id);
				}
				if (!empty($s_city_id)) {
					//$qry->where('city_id',$s_city_id);
				}
				$qry->where(DB::raw('concat(first_name," ",IFNULL(last_name,""))'), 'like', '%' . $search . '%');
				$qry = $this->filteredDocData($type, $qry, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$qry = $this->sortByFilterd($qry, $type, $sort_by);
				$infoData = $qry->get();
				$infoData = dataSequenceChange($infoData);

				$perPage = 10;
				$input = $request->all();
				if (isset($input['page']) && !empty($input['page'])) {
					$currentPage = $input['page'];
				} else {
					$currentPage = 1;
				}

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				if (count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.doctors'), ['infoData' => $infoData]);
			} else if ($type == "doctor_all") {
				$qry = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->whereNotNull('first_name');
				if (!empty($s_locality_id)) {
					if (empty($filter_by_locality_put) && count(json_decode($filter_by_locality_put, true)) <= 0) {
						//$qry->where('locality_id',$s_locality_id);
					}
				}
				if (!empty($s_city_id)) {
					//$qry->where('city_id',$s_city_id);
				}

				$qry = $this->filteredDocData($type, $qry, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$qry = $this->sortByFilterd($qry, $type, $sort_by);

				$infoData = $qry->get();
				$infoData = dataSequenceChange($infoData);

				$perPage = 10;
				$input = $request->all();
				if (isset($input['page']) && !empty($input['page'])) {
					$currentPage = $input['page'];
				} else {
					$currentPage = 1;
				}

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

				if (count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.doctors'), ['infoData' => $infoData]);
			} else if ($type == "clinic_all") {
				$qry = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, "practice_type" => 1, "status" => 1])->whereNotNull('clinic_name');
				if (!empty($s_locality_id)) {
					if (empty($filter_by_locality_put) && count(json_decode($filter_by_locality_put, true)) <= 0) {
						//$qry->where('locality_id',$s_locality_id);
					}
				}
				if (!empty($s_city_id)) {
					//$qry->where('city_id',$s_city_id);
				}
				$qry = $this->filteredDocData($type, $qry, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$qry = $this->sortByFilterd($qry, $type, $sort_by);
				$infoData = $qry->groupBy('clinic_name')->get();
				$infoData = dataSequenceChange($infoData);

				$perPage = 10;
				$input = $request->all();
				if (isset($input['page']) && !empty($input['page'])) {
					$currentPage = $input['page'];
				} else {
					$currentPage = 1;
				}

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

				if (count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.hospital_list'), ['infoData' => $infoData]);
			} else if ($type == "hos_all") {
				$qry = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, "practice_type" => 2, "status" => 1])->whereNotNull('clinic_name');
				if (!empty($s_locality_id)) {
					if (empty($filter_by_locality_put) && count(json_decode($filter_by_locality_put, true)) <= 0) {
						//$qry->where('locality_id',$s_locality_id);
					}
				}
				if (!empty($s_city_id)) {
					//$qry->where('city_id',$s_city_id);
				}
				$qry = $this->filteredDocData($type, $qry, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType);
				$qry = $this->sortByFilterd($qry, $type, $sort_by);
				$infoData = $qry->groupBy('clinic_name')->get();
				$infoData = dataSequenceChange($infoData);

				$perPage = 10;
				$input = $request->all();
				if (isset($input['page']) && !empty($input['page'])) {
					$currentPage = $input['page'];
				} else {
					$currentPage = 1;
				}

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($infoData, $offset, $perPage, false);
				$infoData =  new Paginator($itemsForCurrentPage, count($infoData), $perPage, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));

				if (count($infoData) > 0) {
					$infoData = bindDocData($infoData);
				}
				return view($this->getView('doctors.hospital_list'), ['infoData' => $infoData]);
			} else if ($type == "symptoms") {
				$qry = Symptoms::with(["SymptomsSpeciality", "SymptomTags"])->Where(['status' => 1]);
				if (empty($id) || $id == 0) {
					$qry->Where('symptom', 'like', '%' . $search . '%')->OrWhereHas("SymptomTags", function ($qryss) use ($search) {
						$qryss->Where('text', 'like', '%' . $search . '%');
					});
					$infoData = $qry->paginate(10);
					return view($this->getView('doctors.smptoms_list'), ['infoData' => $infoData]);
				} else {
					$qry->Where(['id' => $id]);
					$infoData = $qry->first();
					return view($this->getView('doctors.smptoms_details'), ['infoData' => $infoData]);
				}
			}
		}
	}
	public function customPaginate($items, $perPage, $pageStart = 1)
	{
		//$infoData = $this->customPaginate($infoData->toArray(),2,$currentPage);
		// Start displaying items from this number;
		// $infoData = new Paginator(5, $infoData->count(), $perPage, $currentPage);
		// $arr_splice = array_slice($infoData->toArray(), $offset, $perPage, false);
		$offset = ($pageStart * $perPage) - $perPage;

		$itemsForCurrentPage = collect($items)->slice($offset, $perPage)->all();
		return new Paginator(
			$itemsForCurrentPage,
			count($items),
			$perPage,
			Paginator::resolveCurrentPage(),
			array('path' => Paginator::resolveCurrentPath())
		);
	}
	public function getDoctorInfo(Request $request)
	{
		$data = $request->all();
		$id = base64_decode($request->input('id'));
		$infoData = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->Where(['id' => $id])->first();
		if (!empty($infoData)) {
			$infoData = bindDocDataByUnique($infoData);
		}
		return view($this->getView('doctors.doctor-info'), ['infoData' => $infoData]);
	}

	public function getHospitalInfoById(Request $request)
	{
		$data = $request->all();
		$infoData = "";
		$infoDoctors = "";
		$id = base64_decode($request->input('id'));
		$docData = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->Where(['id' => $id])->first();
		if (!empty($docData)) {
			if (!empty($docData->practice_id)) {
				$infoDoctors = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->Where(['practice_id' => $docData->practice_id])->where(['delete_status' => 1])->get();
			} else {
				$infoDoctors = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where('clinic_name', 'like', '%' . $docData->clinic_name . '%')->where(['delete_status' => 1])->get();
			}

			$infoData = bindDocDataByUnique($docData);
		}
		if (!empty($infoDoctors)) {
			$infoDoctors = bindDocData($infoDoctors);
		}
		return view($this->getView('doctors.hospital-info'), ['infoData' => $infoData, 'infoDoctors' => $infoDoctors]);
	}

	public function getHospitalInfo(Request $request)
	{
		$data = $request->all();
		if (!empty($request->input('id'))) {
			$id = base64_decode($request->input('id'));
			$infoData = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->Where(['user_id' => $id])->first();
			$infoDoctors = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->Where(['practice_id' => $id])->where(['delete_status' => 1])->get();
		} else {
			$search = base64_decode($request->input('data_search'));
			$infoData = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where('clinic_name', 'like', '%' . $search . '%')->first();
			$infoDoctors = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where('clinic_name', 'like', '%' . $search . '%')->where(['delete_status' => 1])->get();
		}

		if (!empty($infoData)) {
			$infoData = bindDocDataByUnique($infoData);
		}
		if (!empty($infoDoctors)) {
			$infoDoctors = bindDocData($infoDoctors);
		}
		return view($this->getView('doctors.hospital-info'), ['infoData' => $infoData, 'infoDoctors' => $infoDoctors]);
	}

	public function sortByFilterd($qry, $type, $sort_by)
	{
		//$qry->orderBy('hg_doctor','DESC');
		//$qry->orderBy('first_name','ASC');
		if (!empty($sort_by)) {
			if ($type == "doctor_all" || $type == "doctorsIn" || $type == "Speciality" || $type == "Clinic") {
				if ($sort_by == "name_asc") {
					$qry->orderBy('first_name', 'ASC');
				}
				if ($sort_by == "name_dsc") {
					$qry->orderBy('first_name', 'DESC');
				}
			} else if ($type == "clinic_all") {
				if ($sort_by == "name_asc") {
					$qry->orderBy('clinic_name', 'ASC');
				}
				if ($sort_by == "name_dsc") {
					$qry->orderBy('clinic_name', 'DESC');
				}
			}
		}
		return $qry;
	}

	public function sortByRating($qry, $type, $by_rate)
	{
		if (!empty($by_rate)) {
			if ($type == "doctor_all" || $type == "doctorsIn" || $type == "Speciality" || $type == "Clinic") {
				if ($by_rate == "name_asc") {
					$qry->orderBy('first_name', 'ASC');
				}
				if ($by_rate == "name_dsc") {
					$qry->orderBy('first_name', 'DESC');
				}
			} else if ($type == "clinic_all") {
				if ($by_rate == "name_asc") {
					$qry->orderBy('clinic_name', 'ASC');
				}
				if ($by_rate == "name_dsc") {
					$qry->orderBy('clinic_name', 'DESC');
				}
			}
		}
		return $qry;
	}
	public function filteredDocData($type, $qry, $filter_by_locality_put, $s_locality_id, $filter_by_gender_put, $filter_by_exp, $s_city_id, $s_state_id, $consult_fee_min, $consult_fee_max, $conType)
	{
		if (!empty($filter_by_locality_put) && count(json_decode($filter_by_locality_put, true)) > 0) {
			$filterLocality = json_decode($filter_by_locality_put, true);
			$qry->whereIn('locality_id', $filterLocality);
		} else if (!empty($s_locality_id)) {
			//$qry->where('locality_id',$s_locality_id);
		}
		// dd($qry->get());
		if (!empty($filter_by_gender_put) && count(json_decode($filter_by_gender_put, true)) > 0) {
			$filterByGender = json_decode($filter_by_gender_put, true);
			if ($filterByGender['Male'] == 1) {
				$qry->where('gender', "Male");
			}
			if ($filterByGender['Female'] == 1) {
				$qry->where('gender', "Female");
			}
		}
		if (!empty($filter_by_exp)) {
			if ($filter_by_exp == '5') {
				$qry->whereBetween('experience', [1, 5]);
			} else if ($filter_by_exp == '10') {
				$qry->whereBetween('experience', [5, 10]);
			} else if ($filter_by_exp == '15') {
				$qry->whereBetween('experience', [10, 15]);
			} else if ($filter_by_exp == '20') {
				$qry->whereBetween('experience', [15, 20]);
			} else if ($filter_by_exp == '1') {
				$qry->where('experience', '>=', 20);
			}
		}
		if (!empty($s_city_id)) {
			//$qry->where('city_id',$s_city_id);
		}
		if (!empty($s_state_id)) {
			$qry->where('state_id', $s_state_id);
		}
		if ($consult_fee_min != "") {
			if ($consult_fee_min > 0) {
				$qry->where('consultation_fees', ">=", $consult_fee_min);
			}
		}
		if (!empty($consult_fee_max)) {
			if ($consult_fee_max < 10000) {
				$qry->where('consultation_fees', "<=", $consult_fee_max);
			}
		} elseif ($conType == "tele") {
			$qry->where(['oncall_status' => 1]);
			if (!empty($consult_fee_min)) {
				$qry->where("oncall_fee", ">=", $consult_fee_min)->where("oncall_fee", "<=", $consult_fee_max)->whereNotNull('oncall_fee');
			}
		}
		return $qry;
	}

	public function bookSlot(Request $request)
	{
		if (\Auth::user() == null) {
			$data = $request->all();
			Session::put('loginFrom', '3');
			Session::put('appDoctorData', $data);
			return redirect()->route('login');
		}

		$doctor = "";
		$app_id = "";
		Log::info('======================', [$request->all()]);
		if (!empty($request->input('doc'))) {
			$doctor = Doctors::where(['id' => base64_decode($request->input('doc'))])->first();
			if (!empty(@$doctor->profile_pic)) {
				$image_url = getPath("public/doctor/ProfilePics/" . @$doctor->profile_pic);
				@$doctor->profile_pic = $image_url;
			} else {
				@$doctor->profile_pic = null;
			}
		}
		if (!empty($request->input('app_id'))) {
			$app_id = $request->input('app_id');
		}
		$isDirect = '0';
		if (!empty($request->input('isDirect'))) {
			$isDirect = $request->input('isDirect');
		}
		$_from = '0';
		if (!empty($request->input('_from'))) {
			$_from = $request->input('_from');
		}
		if (!empty($request->input('isPaytm'))) {
			$isFree = checkFirstDirectTeleAppointmentPaytm();
		} else {
			$isFree = checkFirstDirectTeleAppointment();
			// pr($isFree);
		}

		$user = Auth::user();
		$users = User::where(['mobile_no' => $user->mobile_no])->orderBy("parent_id")->get();
		if (Session::has('organisationLogin')) {
			return view($this->getView('doctors.book-appointment-org'), ['doctor' => $doctor, 'app_id' => $app_id, 'isFree' => 1, 'isDirect' => $isDirect, 'users' => $users]);
		} else {
			
			return view($this->getView('doctors.book-appointment'), ['doctor' => $doctor, 'app_id' => $app_id, 'isFree' => $isFree, 'isDirect' => $isDirect, 'users' => $users, '_from' => $_from]);
		}
	}

	public function onlineConsult(Request $request)
	{
		Session::put('is_camp', '1');
		if (\Auth::user() == null) {
			Session::put('loginFrom', '6');
			Session::put('loginFromConsult', '1');
			return redirect()->route('login');
		}
		$doctor = "";
		$app_id = "";
		$doctor = Doctors::where(['id' => 49188])->first();
		if (!empty($doctor->profile_pic)) {
			$image_url = getPath("public/doctor/ProfilePics/" . $doctor->profile_pic);
			$doctor->profile_pic = $image_url;
		} else {
			$doctor->profile_pic = null;
		}
		if (!empty($request->input('app_id'))) {
			$app_id = $request->input('app_id');
		}
		$isDirect = '1';
		$isFree = '1';
		$user = Auth::user();
		$users = User::where(['mobile_no' => $user->mobile_no])->orderBy("parent_id")->get();
		return view($this->getView('appointments.consult-online'), ['doctor' => $doctor, 'app_id' => $app_id, 'isFree' => $isFree, 'isDirect' => $isDirect, 'users' => $users]);
	}

	public function showSlot(Request $request)
	{
		$doc_id = $request->id;
		$type = $request->type;
		$doctor = Doctors::where(['id' => $doc_id])->first();
		if (!empty($doctor->profile_pic)) {
			$image_url = getPath("public/doctor/ProfilePics/" . $doctor->profile_pic);
			$doctor->profile_pic = $image_url;
		} else {
			$doctor->profile_pic = null;
		}
		return view($this->getView('doctors.ajaxpages.doctor-appointment-slots'), ['doctor' => $doctor, 'type' => $type]);
	}

	public function showDocInfo(Request $request)
	{
		$doc_id = $request->id;
		$type = $request->type;
		$doctor = Doctors::where(['id' => $doc_id])->first();
		if (!empty($doctor->profile_pic)) {
			$image_url = getPath("public/doctor/ProfilePics/" . $doctor->profile_pic);
			$doctor->profile_pic = $image_url;
		} else {
			$doctor->profile_pic = null;
		}
		return view($this->getView('doctors.ajaxpages.doctor-slots'), ['doctor' => $doctor, 'type' => $type]);
	}


	public function loadSlots(Request $request)
	{
		if ($request->isMethod('get')) {

			$doc_id = $request->doctor;

			$type = $request->type;
			$doctor = Doctors::where(['id' => $doc_id])->first();
			$date = $request->date;
			$unixTimestamp = strtotime($date);
			$dayOfWeek = date("w", $unixTimestamp);

			return view($this->getView('doctors.ajaxpages.loadSlots'), ['dayOfWeek' => $dayOfWeek, 'doctor' => $doctor, 'date' => $date, 'type' => $type]);
		}
	}


	public function loadDocSlots(Request $request)
	{
		if ($request->isMethod('get')) {
			$data = $request->all();
			$doc_id = $request->doctor;

			$type = $request->type;

			$doctor = Doctors::where(['id' => $doc_id])->first();
			$date = $request->date;

			$unixTimestamp = strtotime($date);
			$dayOfWeek = date("w", $unixTimestamp);

			return view($this->getView('doctors.ajaxpages.load-doc-slot'), ['dayOfWeek' => $dayOfWeek, 'doctor' => $doctor, 'date' => $date, 'type' => $type]);
		}
	}


	public function appointmentProcess(Request $request)
	{
		$doctor = "";
		if (!empty($request->input('doc'))) {
			$doctor = Doctors::where(['id' => base64_decode($request->input('doc'))])->first();
		}
		return view($this->getView('doctors.ajaxpages.appointment-process'), ['doctor' => $doctor]);
	}
	public function bookSlotConfirm(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			
		
			$validator = Validator::make($data, [
				'p_id' => 'required',
				'patient_name' => 'required|max:50',
				'gender' => 'required',
				'age' => 'required|numeric',
				'mobile_no' => 'required|numeric',
				'total_fee' => 'required',
				'consultation_fees' => 'required',
			]);
			if ($validator->fails()) {
				$errors = $validator->errors()->all();
				$parameters = ["status" => 4, 'errors' => $errors];
				return $parameters;
			}
			$appointmentTime = intval($data['time']); 
			// Get current timestamp
			// $currentTimestamp = Carbon::now()->timestamp;
			// Check if appointment time matches current time or is within 2 minutes
			// if ( base64_decode($data['isFree']) != '1' && ($appointmentTime - $currentTimestamp) <= 120) {
				// return ["status" => 9];
			// }

			$docData = Doctors::where(['id' => base64_decode($data['doctor'])])->first();
			
			$user_array = array();
			$user_array['order_by'] = $data['order_by'];
			$user_array['doc_id']  = $docData->id;
			$user_array['doc_name'] = $docData->first_name . " " . $docData->last_name;
			$user_array['p_id'] = $data['p_id'];
			$user_array['visit_type'] = null;
			$user_array['blood_group'] = null;
			$user_array['consultation_fees'] = base64_decode($data['consultation_fees']);
			$user_array['finalConsultaionFee'] = base64_decode($data['consultation_fees']);
			$user_array['appointment_date'] = $data['date'];
			$user_array['time'] = date("H:i:s", $data['time']);
			$user_array['slot_duration'] = $docData->slot_duration;
			$user_array['onCallStatus'] = base64_decode($data['onCallStatus']);
			$user_array['isFirstTeleAppointment'] = null;
			$user_array['service_charge'] = base64_decode($data['service_charge']);
			$user_array['is_subscribed'] = $data['is_subscribed'];
			$user_array['gender'] = $data['gender'];
			$user_array['patient_name'] = $data['patient_name'];
			$user_array['dob'] = $data['dob'];
			$user_array['mobile_no'] = $data['mobile_no'];
			$user_array['other_mobile_no'] = $data['other_mobile_no'];
			$user_array['otherPatient'] = $data['otherPatient'];
			$user_array['coupon_id'] = $data['coupon_id'];
			$user_array['isPaytmTab'] = @$data['isPaytmTab'];
			$user_array['availWalletAmt'] = @$data['availWalletAmt'];
			$user_array['_from'] = base64_decode(@$data['_from']);
		
			// $user_array['appoint_type'] = @$data['appoint_type'];

			
			
			$user_array['hg_miniApp'] = 0;
			if (Session::get('wltSts') != null) {
				$user_array['hg_miniApp'] = 1;
			} else if (Session::get('lanEmitraData') != null) {
				$user_array['hg_miniApp'] = 2;
			}
			$user_array['coupon_discount'] =  base64_decode($data['coupon_discount']);
			$user_array['isDirectAppt'] = "0";
			if ("22" <= date("H") ||  date("H") < "10") {
				$user_array['is_peak'] = "1";
			} else {
				$user_array['is_peak'] = "0";
			}
			$user_array['call_type'] = "1";
			$user_array['referral_code'] = null;

			$increment_time = $docData->slot_duration * 60;
			$date = date("Y-m-d", strtotime($data['date']));
			$time = date("H:i:s", $data['time']);
			$start_date = date("Y-m-d H:i:s", strtotime($date . " " . $time));
			$end_date = date('Y-m-d H:i:s', strtotime($date . " " . $time) + $increment_time);

			$fromD = date('Y-m-d H:i:s');
			$toD = date('Y-m-d H:i:s', strtotime($fromD) - 300);
			$order_exists = AppointmentOrder::where(['doc_id' => $docData->user_id, 'order_status' => 0])->where('app_date', 'like', '%' . $start_date . '%')->where('created_at', '<=', $fromD)->where('created_at', '>=', $toD)->get();
			if (count($order_exists) > 0) {
				return ["status" => 2];
			}
			$fee = base64_decode($data['total_fee']);
			$charge =  getSetting("service_charge_rupee")[0];
			$tax =  getSetting("tax_in_percent")[0];
			$gst =  getSetting("gst")[0];
			$service_charge_meta = 	["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];
			$service_charge = (isset($data['service_charge']) ? base64_decode($data['service_charge']) : 0);
			// $order_subtotal = $fee - $service_charge;
			$order_subtotal = $user_array['consultation_fees'];

			/***Free Appointment section***/
			if (base64_decode($data['isDirect']) == '1') {
				$user_array['isDirectAppt'] = '1';
				$user_array['doc_id'] = getSetting("direct_appt_doc_id")[0];
				if ($user_array['is_peak'] == "1") {
					$user_array['doc_id'] = getSetting("direct_appt_doc_id")[1];
				}
				$increment_time = $docData->slot_duration * 60;
				$user_array['appointment_date'] = date("Y-m-d");
				$start_date = date("Y-m-d H:i:s");
				$user_array['time'] = checkAvailableSlot($start_date, $docData->user_id, $increment_time);

				$charge =  0;
				$tax =  0;
				$gst =  0;
				$service_charge_meta = 	["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];

				$p_ids = User::select("pId")->where(["mobile_no" => $user_array['mobile_no']])->pluck("pId")->toArray();
				$appointment = Appointments::whereIn('pID', $p_ids)->where(["delete_status" => 1, "appointment_confirmation" => 1, "type" => 3])->count();
				// pr($appointment);
				$dt = date('Y-m-d');
				$plan_data =  PlanPeriods::whereDate('start_trail', '<=', $dt)->whereDate('end_trail', '>=', $dt)->where(['user_id' => $user_array['order_by'], 'status' => 1])->where('remaining_appointment', '>', 0)->first();
				if (!empty($plan_data) && $user_array['is_peak'] != "1") {
					$appointment = 0;
				}
				$isAppt_free = 0;
				$lab = LabOrders::select(["id", "is_free_appt"])->where(["user_id" => $user_array['order_by']])->where("is_free_appt", "1")->first();
				if (!empty($lab)) {
					$isAppt_free = $lab->is_free_appt;
				}
				$fee = getSetting("direct_tele_appt_fee")[0];
				$user_array['consultation_fees'] = $fee;
				$user_array['finalConsultaionFee'] = $fee;
				$order_subtotal = $fee;
				// echo $appointment."--".$isAppt_free;
				// die;
				if ($data['isPaytmTab'] == "true") {
					$isFree = checkFirstDirectTeleAppointmentPaytm();
				} else {
					$isFree = checkFirstDirectTeleAppointment();
				}
				// pr($isAppt_free);

	

				if ($user_array['hg_miniApp'] == 1 && $user_array['is_subscribed'] == 0) {
					$order = AppointmentOrder::create([
						'type'	 => 1,
						'service_charge_meta' =>  json_encode($service_charge_meta),
						'service_charge' =>  $service_charge,
						'order_subtotal' =>  $order_subtotal,
						'order_total' =>  $fee,
						'order_status' =>  0,
						'app_date' => $start_date,
						'doc_id' =>  $docData->user_id,
						'order_from' => 0,
						'order_by' => $user_array['order_by'],
						'coupon_id' => $user_array['coupon_id'],
						'coupon_discount' => $user_array['coupon_discount'],
						'hg_miniApp' => $user_array['hg_miniApp'],
						'appt_type' => @$user_array['appoint_type'],
						'meta_data' => json_encode($user_array),
					]);

				

					$parameters = [
						"status" => 7,
						'tid' => base64_encode(strtotime("now")),
						'order_id' => base64_encode($order->id),
						'order_by' => base64_encode($user_array['order_by']),
						'amount' => base64_encode($fee),
						'merchant_param1' => base64_encode("Health Gennie Appointment"),
					];
					return $parameters;
				}
				if ($user_array['hg_miniApp'] == 2) {
					$fee = 250;
					$user_array['consultation_fees'] = $fee;
					$user_array['finalConsultaionFee'] = $fee;
					$order_subtotal = $fee;
					if ($user_array['is_subscribed'] == 1) {
						$order = AppointmentOrder::create([
							'type'	 => 0,
							'service_charge_meta' =>  json_encode($service_charge_meta),
							'service_charge' =>  $service_charge,
							'order_subtotal' =>  $order_subtotal,
							'order_total' =>  $fee,
							'order_status' =>  0,
							'app_date' => $start_date,
							'doc_id' =>  $docData->user_id,
							'order_from' => 0,
							'order_by' => $user_array['order_by'],
							'coupon_id' => $user_array['coupon_id'],
							'coupon_discount' => $user_array['coupon_discount'],
							'hg_miniApp' => $user_array['hg_miniApp'],
							'appt_type' => @$user_array['appoint_type'],
							'meta_data' => json_encode($user_array),
						]);
						Parent::putAppointmentDataApp($order, '', $order);
						$parameters = [
							"status" => 8,
							'return_url' => @Session::get('lanEmitraData')['decryptData']['RETURNURL'],
							'msg' => 'Appointment Created Successfully.',
							'app_order_id' => $order->id,
						];
						return $parameters;
					} else {
						$order = AppointmentOrder::create([
							'type'	 => 1,
							'service_charge_meta' =>  json_encode($service_charge_meta),
							'service_charge' =>  $service_charge,
							'order_subtotal' =>  $order_subtotal,
							'order_total' =>  $fee,
							'order_status' =>  0,
							'app_date' => $start_date,
							'doc_id' =>  $docData->user_id,
							'order_from' => 0,
							'order_by' => $user_array['order_by'],
							'coupon_id' => $user_array['coupon_id'],
							'coupon_discount' => $user_array['coupon_discount'],
							'hg_miniApp' => $user_array['hg_miniApp'],
							'appt_type' => @$user_array['appoint_type'],
							'meta_data' => json_encode($user_array),
						]);
						$getsess = Session::get('lanEmitraData');
						$tranData = $this->doitTrans($getsess, $order, $user_array['mobile_no']);
						$arr = [];
						CcavenueResponse::create([
							'slug' => 'emitra_response',
							'meta_data' => json_encode($tranData),
						]);
						if ($tranData['TRANSACTIONSTATUS'] == 'SUCCESS') {
							$arr['tracking_id'] = $tranData['TRANSACTIONID'];
							$arr['order_bank_ref_no'] = $tranData['RECEIPTNO'];
							Parent::putAppointmentDataApp($order, '', $arr);
						}
						$parameters = ["status" => 8, 'return_url' => @Session::get('lanEmitraData')['decryptData']['RETURNURL'], 'msg' => $tranData['MSG'], 'tracking_id' => @$arr['tracking_id']];
						return $parameters;
					}
				} else if (($isFree == '1' || $isAppt_free == 1)) {
					$order = AppointmentOrder::create([
						'type'	 => 0,
						'service_charge_meta' =>  json_encode($service_charge_meta),
						'service_charge' =>  $service_charge,
						'order_subtotal' =>  $order_subtotal,
						'order_total' =>  $fee,
						'order_status' =>  0,
						'app_date' => $start_date,
						'doc_id' =>  $docData->user_id,
						'order_from' => 0,
						'order_by' => $user_array['order_by'],
						'coupon_id' => $user_array['coupon_id'],
						'coupon_discount' => $user_array['coupon_discount'],
						'hg_miniApp' => $user_array['hg_miniApp'],
						'appt_type' => @$user_array['appoint_type'],
						'meta_data' => json_encode($user_array),
					]);
					$appointment_id = Parent::putAppointmentDataApp($order, '', '');
					return ["status" => 5];
				} else if ($isFree == '0') {
					$appointment_order = AppointmentOrder::create([
						'type'	 => 1,
						'service_charge_meta' =>  json_encode($service_charge_meta),
						'service_charge' =>  $service_charge,
						'order_subtotal' =>  $order_subtotal,
						'order_total' =>  $fee,
						'order_status' =>  0,
						'app_date' => $start_date,
						'doc_id' =>  $docData->user_id,
						'order_from' => 0,
						'order_by' => $user_array['order_by'],
						'coupon_id' => $data['coupon_id'],
						'hg_miniApp' => $user_array['hg_miniApp'],
						'appt_type' => @$user_array['appoint_type'],
						'coupon_discount' => $user_array['coupon_discount'],
						'meta_data' => json_encode($user_array),
					]);
					if ($data['isPaytmTab'] == "true") {
						$txnToken = $this->appointmentCheckoutPaytm($appointment_order->id, $user_array['order_by'], $fee);
						$parameters = [
							"status" => 3,
							'tid' => base64_encode(strtotime("now")),
							'order_id' => base64_encode($appointment_order->id),
							'order_by' => base64_encode($user_array['order_by']),
							'amount' => base64_encode($fee),
							'txnToken' => base64_encode($txnToken),
							'MID' => base64_encode("MiniAp78932858151828"),
						];
						return $parameters;
					} else {
						$parameters = [
							"status" => 1,
							'tid' => base64_encode(strtotime("now")),
							'order_id' => base64_encode($appointment_order->id),
							'order_by' => base64_encode($user_array['order_by']),
							'amount' => base64_encode($fee),
							'merchant_param1' => base64_encode("Health Gennie Appointment"),
						];
						return $parameters;
					}
				} else {
					return ["status" => 6];
				}
			}

			/***END section***/
			if ($user_array['hg_miniApp'] == 1) {
				$order = AppointmentOrder::create([
					'type'	 => 1,
					'service_charge_meta' =>  json_encode($service_charge_meta),
					'service_charge' =>  $service_charge,
					'order_subtotal' =>  $order_subtotal,
					'order_total' =>  $fee,
					'order_status' =>  0,
					'app_date' => $start_date,
					'doc_id' =>  $docData->user_id,
					'order_from' => 0,
					'order_by' => $user_array['order_by'],
					'coupon_id' => $user_array['coupon_id'],
					'coupon_discount' => $user_array['coupon_discount'],
					'hg_miniApp' => $user_array['hg_miniApp'],
					'appt_type' => $user_array['appoint_type'],
					'meta_data' => json_encode($user_array),
				]);
				$parameters = [
					"status" => 7,
					'tid' => base64_encode(strtotime("now")),
					'order_id' => base64_encode($order->id),
					'order_by' => base64_encode($user_array['order_by']),
					'amount' => base64_encode($fee),
					'merchant_param1' => base64_encode("Health Gennie Appointment"),
				];
				return $parameters;
			} else if ($user_array['hg_miniApp'] == 2) {
				$order = AppointmentOrder::create([
					'type'	 => 1,
					'service_charge_meta' =>  json_encode($service_charge_meta),
					'service_charge' =>  $service_charge,
					'order_subtotal' =>  $order_subtotal,
					'order_total' =>  $fee,
					'order_status' =>  0,
					'app_date' => $start_date,
					'doc_id' =>  $docData->user_id,
					'order_from' => 0,
					'order_by' => $user_array['order_by'],
					'coupon_id' => $user_array['coupon_id'],
					'coupon_discount' => $user_array['coupon_discount'],
					'hg_miniApp' => $user_array['hg_miniApp'],
					'appt_type' => $user_array['appoint_type'],
					'meta_data' => json_encode($user_array),
				]);
				$getsess = Session::get('lanEmitraData');
				$tranData = $this->doitTrans($getsess, $order, $user_array['mobile_no']);
				$arr = [];
				CcavenueResponse::create([
					'slug' => 'emitra_response',
					'meta_data' => json_encode($tranData),
				]);
				if ($tranData['TRANSACTIONSTATUS'] == 'SUCCESS') {
					$arr['tracking_id'] = $tranData['TRANSACTIONID'];
					$arr['order_bank_ref_no'] = $tranData['RECEIPTNO'];
					Parent::putAppointmentDataApp($order, '', $arr);
				}
				$parameters = ["status" => 8, 'return_url' => @$getsess['decryptData']['RETURNURL'], 'msg' => $tranData['MSG'], 'tracking_id' => @$arr['tracking_id']];
				return $parameters;
			} else {
				$appointment_order = AppointmentOrder::create([
					'type'	 => 1,
					'service_charge_meta' =>  json_encode($service_charge_meta),
					'service_charge' =>  $service_charge,
					'order_subtotal' =>  $order_subtotal,
					'order_total' =>  $fee,
					'order_status' =>  0,
					'app_date' => $start_date,
					'doc_id' =>  $docData->user_id,
					'order_from' => 0,
					'order_by' => $user_array['order_by'],
					'coupon_id' => $data['coupon_id'],
					'coupon_discount' => $user_array['coupon_discount'],
					'hg_miniApp' => $user_array['hg_miniApp'],
					'meta_data' => json_encode($user_array),
					'appt_type' => @$user_array['appoint_type'],
					
				]);

				if ($data['isPaytmTab'] == "true") {
					$txnToken = $this->appointmentCheckoutPaytm($appointment_order->id, $user_array['order_by'], $fee);
					$parameters = [
						"status" => 3,
						'tid' => base64_encode(strtotime("now")),
						'order_id' => base64_encode($appointment_order->id),
						'order_by' => base64_encode($user_array['order_by']),
						'amount' => base64_encode($fee),
						'txnToken' => base64_encode($txnToken),
						'MID' => base64_encode("MiniAp78932858151828"),
					];
					return $parameters;
				} else {
					$parameters = [
						"status" => 1,
						'tid' => base64_encode(strtotime("now")),
						'order_id' => base64_encode($appointment_order->id),
						'order_by' => base64_encode($user_array['order_by']),
						'amount' => base64_encode($fee),
						'merchant_param1' => base64_encode("Health Gennie Appointment"),
					];
					return $parameters;
				}
			}
		}
	}

	public function bookConsult(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			// pr($data);
			$validator = Validator::make($data, [
				'p_id' => 'required',
				'patient_name' => 'required|max:50',
				'gender' => 'required',
				'age' => 'required|numeric',
				'mobile_no' => 'required|numeric',
				'total_fee' => 'required',
				'consultation_fees' => 'required',
			]);
			if ($validator->fails()) {
				$errors = $validator->errors()->all();
				$parameters = ["status" => 4, 'errors' => $errors];
				return $parameters;
			}

			$docData = Doctors::where(['id' => base64_decode($data['doctor'])])->first();

			$user_array = array();
			$user_array['order_by'] = $data['order_by'];
			$user_array['doc_id']  = $docData->id;
			$user_array['doc_name'] = $docData->first_name . " " . $docData->last_name;
			$user_array['p_id'] = $data['p_id'];
			$user_array['visit_type'] = null;
			$user_array['blood_group'] = null;
			$user_array['consultation_fees'] = base64_decode($data['consultation_fees']);
			$user_array['finalConsultaionFee'] = base64_decode($data['consultation_fees']);
			$user_array['appointment_date'] = $data['date'];
			$user_array['time'] = date("H:i:s", $data['time']);
			$user_array['slot_duration'] = $docData->slot_duration;
			$user_array['onCallStatus'] = base64_decode($data['onCallStatus']);
			$user_array['isFirstTeleAppointment'] = null;
			$user_array['service_charge'] = base64_decode($data['service_charge']);
			$user_array['is_subscribed'] = $data['is_subscribed'];
			$user_array['gender'] = $data['gender'];
			$user_array['patient_name'] = $data['patient_name'];
			$user_array['dob'] = $data['dob'];
			$user_array['mobile_no'] = $data['mobile_no'];
			$user_array['other_mobile_no'] = $data['other_mobile_no'];
			$user_array['otherPatient'] = $data['otherPatient'];
			$user_array['coupon_id'] = null;
			$user_array['isPaytmTab'] = $data['isPaytmTab'];
			$user_array['coupon_discount'] =  null;
			$user_array['isDirectAppt'] = "0";
			$user_array['is_peak'] = "0";
			$user_array['call_type'] = "1";
			$user_array['referral_code'] = null;
			$user_array['organization'] = $data['organization'];


			$increment_time = $docData->slot_duration * 60;
			$date = date("Y-m-d", strtotime($data['date']));
			$time = date("H:i:s", $data['time']);
			$start_date = date("Y-m-d H:i:s", strtotime($date . " " . $time));
			$end_date = date('Y-m-d H:i:s', strtotime($date . " " . $time) + $increment_time);

			$fromD = date('Y-m-d H:i:s');
			$toD = date('Y-m-d H:i:s', strtotime($fromD) - 300);
			$order_exists = AppointmentOrder::where(['doc_id' => $docData->user_id, 'order_status' => 0])->where('app_date', 'like', '%' . $start_date . '%')->where('created_at', '<=', $fromD)->where('created_at', '>=', $toD)->get();
			if ($order_exists->count() > 0) {
				return ["status" => 2];
			}
			$fee = base64_decode($data['total_fee']);
			$charge =  getSetting("service_charge_rupee")[0];
			$tax =  getSetting("tax_in_percent")[0];
			$gst =  getSetting("gst")[0];
			$service_charge_meta = 	["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];
			$service_charge = (isset($data['service_charge']) ? base64_decode($data['service_charge']) : 0);
			// $order_subtotal = $fee - $service_charge;
			$order_subtotal = $user_array['consultation_fees'];

			/***Free Appointment section***/
			if (base64_decode($data['isDirect']) == '1') {
				$user_array['isDirectAppt'] = '1';
				$user_array['doc_id'] = getSetting("direct_appt_doc_id")[0];
				$increment_time = $docData->slot_duration * 60;
				$user_array['appointment_date'] = date("Y-m-d");
				$start_date = date("Y-m-d H:i:s");
				$user_array['time'] = checkAvailableSlot($start_date, $docData->user_id, $increment_time);

				//for instant doc
				// if($user_array['is_peak'] != "1") {
				/*if(date('N') != 5) {
							//$user_array['appointment_date'] = date("Y-m-d");
							$user_array['appointment_date'] = date('Y-m-d');
							$nexdateTime = date('Y-m-d H:i:s', strtotime($user_array['appointment_date']." ".'10:00:00'));
							$user_array['time'] = checkAvailableSlot($nexdateTime,$docData->user_id,$increment_time);
							$finalDateTime =  date('Y-m-d H:i:s',strtotime($user_array['appointment_date'].' '.$user_array['time']));
							$start_date = $finalDateTime;
						}*/
				/*else{
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
						}*/
				// }

				// for instant doc end
				$charge =  0;
				$tax =  0;
				$gst =  0;
				$service_charge_meta = 	["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];
				// $fee = getSetting("direct_tele_appt_fee")[0];
				$user_array['consultation_fees'] = $fee;
				$user_array['finalConsultaionFee'] = $fee;
				$order_subtotal = $fee;

				$order = AppointmentOrder::create([
					'type'	 => 0,
					'service_charge_meta' =>  json_encode($service_charge_meta),
					'service_charge' =>  $service_charge,
					'order_subtotal' =>  $order_subtotal,
					'order_total' =>  $fee,
					'order_status' =>  0,
					'app_date' => $start_date,
					'doc_id' =>  $docData->user_id,
					'order_from' => 0,
					'order_by' => $user_array['order_by'],
					'coupon_id' => $user_array['coupon_id'],
					'coupon_discount' => $user_array['coupon_discount'],
					'meta_data' => json_encode($user_array),
				]);
				$appointment_id = Parent::putAppointmentDataApp($order, '', '');
				return ["status" => 5];

				/*$appointment_order = AppointmentOrder::create([
						  'type'	 => 1,
						  'service_charge_meta' =>  json_encode($service_charge_meta),
						  'service_charge' =>  $service_charge,
						  'order_subtotal' =>  $order_subtotal,
						  'order_total' =>  $fee,
						  'order_status' =>  0,
						  'app_date' => $start_date,
						  'doc_id' =>  $docData->user_id,
						  'order_from' => 0,
						  'order_by' => $user_array['order_by'],
						  'coupon_id' => $user_array['coupon_id'],
						  'coupon_discount' => $user_array['coupon_discount'],
						  'meta_data' => json_encode($user_array),
					]);
					$parameters = [
						"status"=> 1,
						'tid' => base64_encode(strtotime("now")),
						'order_id' => base64_encode($appointment_order->id),
						'order_by' => base64_encode($user_array['order_by']),
						'amount' => base64_encode($fee),
						'merchant_param1' => base64_encode("Health Gennie Appointment"),
					];
					return $parameters;*/
			}
			/***END section***/
		}
	}

	public function appointmentCheckoutPaytm($order_id, $order_by, $amount)
	{
		$parameters = [];
		$parameters["MID"] = "MiniAp78932858151828";
		$parameters["ORDER_ID"] = $order_id;
		$parameters["CUST_ID"] = $order_by;
		$parameters["TXN_AMOUNT"] = 1;
		//$parameters["CALLBACK_URL"] = url('paytmresponse'); 
		$paytmParams = array();
		$paytmParams["body"] = array(
			"requestType"   => "Payment",
			"mid"           => $parameters["MID"],
			"websiteName"   => "WEBPROD",
			"orderId"       => $parameters["ORDER_ID"],
			//"callbackUrl"   => url('paytmresponse'),
			"txnAmount"     => array(
				"value"     => $parameters["TXN_AMOUNT"],
				"currency"  => "INR",
			),
			"userInfo"      => array(
				"custId"    => $parameters["CUST_ID"],
			),
		);
		$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), "oS%zlWJKYh#GqL5P");
		$paytmParams["head"] = array(
			"signature"	=> $checksum
		);
		$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
		/* for Staging */
		//$url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=".$parameters["MID"]."&orderId=".$parameters["ORDER_ID"];
		/* for Production */
		$url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=" . $parameters["MID"] . "&orderId=" . $parameters["ORDER_ID"];
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
		$response = curl_exec($ch);
		$response = json_decode($response, true);

		$txnToken = "";
		if (isset($response['body']['resultInfo']) && $response['body']['resultInfo']['resultStatus'] == "S") {
			$txnToken = $response['body']['txnToken'];
		}
		return $txnToken;
	}

	public function appointmentCheckout(Request $request)
	{
		$data = $request->all();
		$user = User::where("id", base64_decode($data['order_by']))->first();
		$mbl = isset($user->mobile_no) ? $user->mobile_no : '0000000000';
		$email = !empty($user->email)   ? $user->email : 'test@mailinator.com';
		$parameters["order"] = base64_decode($data['order_id']);
		$parameters["amount"] = 1;
		$parameters["user"] = base64_decode($data['order_by']);
		$parameters["mobile_number"] = $mbl;
		$parameters["email"] = $email;
		$parameters["callback_url"] = url('paytmresponse');


		$payment = PaytmWallet::with('receive');
		$payment->prepare($parameters);
		return $payment->receive();
	}

	public function showFeedbackForm(Request $request)
	{
		
		$doc_id = $request->id;
		$appointment_id = $request->appointment_id;
		$doctor = Doctors::where(['user_id' => $doc_id])->first();
		if (!empty($doctor->profile_pic)) {
			$image_url = getPath("public/doctor/ProfilePics/" . $doctor->profile_pic);
			$doctor->profile_pic = $image_url;
		} else {
			$doctor->profile_pic = null;
		}
		return view($this->getView('doctors.ajaxpages.feedback-popup'), ['doctor' => $doctor, 'appointment_id' => $appointment_id]);
	}


	public function saveFeedbackForm(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			if (isset($data['closeModal']) && $data['closeModal'] == 1) {
				Session::put('closeFeedbackModal', '1');
				return 0;
			}
			$suggestions = null;
			if (isset($data['suggestions'])) {
				$suggestions = implode(',', $data['suggestions']);
			}
	
			$patient = PatientFeedback::create([
				'user_id' => $data['user_id'],
				'doc_id' =>  $data['doc_id'],
				'appointment_id' =>  $data['appointment_id'],
				'rating' => (isset($data['rating']) ? $data['rating'] : null),
				'recommendation' => (isset($data['recommendation']) ? $data['recommendation'] : null),
				'visit_type' => (isset($data['visit_type']) ? $data['visit_type'] : null),
				'waiting_time' => (isset($data['waiting_time']) ? $data['waiting_time'] : null),
				'suggestions' =>  $suggestions,
				'experience' =>  $data['experience'],
				'publish_status' => (isset($data['publish_status']) ? $data['publish_status'] : null),
			]);
			$user = \Auth::user();
			if (!empty($user) && !empty($user->mobile_no)) {
				$app_link = "www.healthgennie.com/download";
				$message = urlencode("Dear " . $user->first_name . ", Thanks for your valuable feedback Team Health Gennie");
				$this->sendSMS($user->mobile_no, $message, '1707161588020533679');
			}
			Session::flash('message', "Thanks for your feedback");
			return 1;
		}
	}

	public function putLocalty(Request $request)
	{
		$docs = Doctors::select(['id', 'locality_name', 'city_id', 'state_id', 'country_id'])->get();
		foreach ($docs as $doc) {
			if ($doc->locality_name != "") {
				$locality_id = "";
				$city_l = CityLocalities::where('name', 'LIKE', $doc->locality_name)->first();
				if (!empty($city_l)) {
					$locality_id = $city_l->id;
				} else {
					$localitiy =  CityLocalities::create([
						'name' => $doc->locality_name,
						'city_id' => $doc->city_id,
						'state_id' => $doc->state_id,
						'country_id' => $doc->country_id,
						'status' => 1,
						'top_status' => 1,
					]);
					$locality_id = $localitiy->id;
				}
				Doctors::where('id', $doc->id)->update(array(
					'locality_id' => $locality_id
				));
			}
		}
		return 'Successfully Updated..';
	}

	public function clean($string)
	{
		$string = str_replace(' ', '-', strtolower(trim($string))); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}

	public function searchUserByMobile(Request $request)
	{
		$request->mobile;
		$users = [];
		if (!empty($request->mobile)) {
			$users = User::where('mobile_no', $request->mobile)->orderBy('parent_id')->get();
			foreach ($users as $user) {
				$user['dob'] = date('d-m-Y', $user->dob);
			}
		}
		return $users;
	}
	public function teleCamp(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$docData = Doctors::where(['id' => $data['doctor']])->first();
			// pr($data);
			$user_array = array();
			$user_array['order_by'] = $data['order_by'];
			$user_array['doc_id']  = $docData->id;
			$user_array['doc_name'] = $docData->first_name . " " . $docData->last_name;
			$user_array['p_id'] = $data['p_id'];
			$user_array['visit_type'] = null;
			$user_array['blood_group'] = null;
			$user_array['consultation_fees'] = null;
			$user_array['appointment_date'] = $data['date'];
			$user_array['time'] = date("H:i:s", $data['time']);
			$user_array['slot_duration'] = $docData->slot_duration;
			$user_array['conType'] = $data['conType'];
			$user_array['onCallStatus'] = 1;
			$user_array['isFirstTeleAppointment'] = null;
			$user_array['service_charge'] = base64_decode($data['service_charge']);
			$user_array['is_subscribed'] = $data['is_subscribed'];
			$user_array['gender'] = $data['gender'];
			$user_array['patient_name'] = $data['patient_name'];
			$user_array['dob'] = $data['dob'];
			$user_array['mobile_no'] = $data['mobile_no'];
			$user_array['otherPatient'] = $data['otherPatient'];
			$user_array['coupon_id'] = '';
			$user_array['coupon_discount'] =  '';
			$user_array['call_type'] = 1;
			// $user_array['referral_code'] = $data['referral_code'];

			if (empty($data['p_id'])) {
				$first_name = trim(strtok($data['patient_name'], ' '));
				$last_name = trim(strstr($data['patient_name'], ' '));
				$user = User::create([
					'mobile_no' =>  $data['mobile_no'],
					'first_name' =>  $first_name,
					'last_name' =>  $last_name,
					'parent_id' => 0,
					'dob' => strtotime($data['dob']),
					'gender' => $data['gender'],
					'status' =>  1,
					'device_type' =>  3,
				]);

				$user_array['p_id'] = $user->id;
				if (empty($data['order_by'])) {
					$user_array['order_by'] = $user->id;
				}
			}
			$increment_time = $docData->slot_duration * 60;
			$date = date("Y-m-d", strtotime($data['date']));
			$time = date("H:i:s", $data['time']);
			$start_date = date("Y-m-d H:i:s", strtotime($date . " " . $time));
			$end_date = date('Y-m-d H:i:s', strtotime($date . " " . $time) + $increment_time);


			$fee = base64_decode($data['total_fee']);
			$charge =  getSetting("service_charge_rupee")[0];
			$tax =  getSetting("tax_in_percent")[0];
			$gst =  getSetting("gst")[0];
			$service_charge_meta = 	["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];
			$service_charge = (isset($data['service_charge']) ? base64_decode($data['service_charge']) : 0);
			$order_subtotal = $fee - $service_charge;
			$order = AppointmentOrder::create([
				'type'	 => 2,
				'service_charge_meta' =>  json_encode($service_charge_meta),
				'service_charge' =>  $service_charge,
				'order_subtotal' =>  $order_subtotal,
				'order_total' =>  $fee,
				'order_status' =>  0,
				'app_date' => $start_date,
				'doc_id' =>  $docData->user_id,
				'order_from' => 0,
				'order_by' => $user_array['order_by'],
				'meta_data' => json_encode($user_array),
			]);
			Parent::putAppointmentDataApp($order, '', $order);
			Session::flash('message', "Appointment Created Successfully..");
			return 1;
		}
		$email = ehrUser::select("id")->where('email', getSetting("doctor_email")[0])->first();
		$doctor = Doctors::where(['user_id' => $email->id])->first();
		if (!empty($doctor->profile_pic)) {
			$image_url = getPath("public/doctor/ProfilePics/" . $doctor->profile_pic);
			$doctor->profile_pic = $image_url;
		} else {
			$doctor->profile_pic = null;
		}
		return view($this->getView('appointments.tele-camp'), ['doctor' => $doctor]);
	}
	public function doitTrans($sess, $order, $mobile = null)
	{
		$orderId = $order->id;
		$getsess = $sess;
		$time = (string) strtotime("now");
		$checkArr = array(
			'SSOID' => $getsess['decryptData']['SSOID'],
			'REQUESTID' => (string)$orderId,
			'REQTIMESTAMP' => $time,
			'SSOTOKEN' => $getsess['decryptData']['SSOTOKEN']
		);
		//echo json_encode($checkArr);

		$postdata1 = http_build_query(
			array(
				'toBeCheckSumString' => json_encode($checkArr)
			)
		);

		$opts1 = array(
			'http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata1
			)
		);

		$context1  = stream_context_create($opts1);
		$checksum = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum', false, $context1);
		//$checksum = json_decode($result1, TRUE);

		//for payment
		// echo $checksum."<br>"; die;
		$paymentArr = array(
			'MERCHANTCODE' => 'FITKID2022',
			'REQUESTID' => (string)$orderId,
			'REQTIMESTAMP' => $time,
			'SERVICEID' => "8992",
			'SUBSERVICEID' => '',
			'REVENUEHEAD' =>  "3565-" . $order->order_total . "|3564-20.00",
			'CONSUMERKEY' => (string)$orderId,
			'CONSUMERNAME' => $mobile . $orderId,
			'COMMTYPE' => "3",
			'SSOID' => $getsess['decryptData']['SSOID'],
			'OFFICECODE' => 'FITKIDHEALTH',
			'SSOTOKEN' => $getsess['decryptData']['SSOTOKEN'],
			'CHECKSUM' => $checksum
		);
		//print_r($paymentArr);

		$postdata2 = http_build_query(
			array(
				'toBeEncrypt' => json_encode($paymentArr)
			)
		);

		$opts2 = array(
			'http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata2
			)
		);

		$context2  = stream_context_create($opts2);
		$encconvert = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESEncryption', false, $context2);
		// pr($encconvert);

		$postdata3 = http_build_query(
			array(
				'encData' => $encconvert
			)
		);

		$opts4 = array(
			'http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata3
			)
		);

		$context3  = stream_context_create($opts4);
		$pay = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/backtobackTransactionWithEncryptionA', false, $context3);
		//die;
		$postdataA = http_build_query(
			array(
				'toBeDecrypt' => $pay
			)
		);

		$optsA = array(
			'http' =>
			array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdataA
			)
		);

		$contextA  = stream_context_create($optsA);
		$resultA = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESDecryption', false, $contextA);
		$encDataA = json_decode($resultA, TRUE);
		return $encDataA;
	}

	public function razorpayPayment(Request $request)
	{
		$razorpayKey = "rzp_test_uCWW4fIrP9ys6C";
		$razorpaySecret = "9hcgBMZWz7gX64mxqR2yLbPC";

		// Decode base64 parameters
		$order_id = base64_decode($request->order_id);
		$amount = base64_decode($request->amount);
		$order_by = base64_decode($request->order_by);
		Log::info('order_id', [$order_id, 'dd', $amount, 'ss', $order_by]);

		try {
			// Initialize Razorpay API
			$api = new Api('rzp_test_uCWW4fIrP9ys6C', '9hcgBMZWz7gX64mxqR2yLbPC');
			Log::info('$api', [$api]);

			// Create a new order
			$order = $api->payment->fetch(array('receipt' => '123', 'amount' => 1000, 'currency' => 'INR', 'notes' => array('key1' => 'value3', 'key2' => 'value2')));
			// $api->order->create(array('receipt' => '123', 'amount' => 100, 'currency' => 'INR', 'notes'=> array('key1'=> 'value3','key2'=> 'value2')));
			Log::info('order', [$order]);

			// Return order details
			return response()->json([
				'success' => true,
				'order_id' => $order['id'],
				'razorpayKey' => $razorpayKey,
				'amount' => $amount,
			]);
		} catch (\Exception $e) {
			// Handle any errors
			return response()->json([
				'success' => false,
				'message' => 'Failed to create Razorpay order: ' . $e->getMessage(),
			], 500);
		}
	}


	public function verifyPayment(Request $request)
	{
		Log::info('aaaaaaaaaaaa', [$request->all()]);
		$input = $request->all();
		$razorpayKey = "rzp_test_uCWW4fIrP9ys6C";
		$razorpaySecret = "9hcgBMZWz7gX64mxqR2yLbPC";

		$api = new Api($razorpayKey, $razorpaySecret);

		try {
			// Verify the signature




			$response = $api->payment->fetch($input['razorpay_payment_id'])->capture(array('amount' => $input['amount']));
			Log::info('response', [$response]);




			return response()->json(['success' => true, 'message' => 'Payment successful!']);
		} catch (\Exception $e) {
			Log::error($e->getMessage());

			return response()->json(['success' => false, 'message' => 'Payment verification failed!']);
		}
	}


	public function paymentOrderCreate(Request $request)
	{
		// Validate the incoming request
         $data=$request->all();

		Log::info('asdsadasadsad', [$request->all()]);
 
		// Razorpay API credentials
		$keyId = 'rzp_test_uCWW4fIrP9ys6C';
		$keySecret = '9hcgBMZWz7gX64mxqR2yLbPC';
		$authHeader = base64_encode("$keyId:$keySecret");
		$amount = base64_decode($request->amount);
		Log::info('============', [$amount]);
		$receipt = base64_decode($request->receipt);
		$walletAmt = base64_decode($request->wallet_amt);
		$order_by = base64_decode($request->order_by);
		// Prepare the data for Razorpay Order API
	
		$postData = [
			'amount' => $amount * 100, // Convert to paise
			'currency' => $request->currency,
			'receipt' => $receipt,
			'notes' => [
				'notes_key_1' => 'Tea, Earl Grey, Hot',
				'notes_key_2' => 'Tea, Earl Grey… decaf.',
			],
		];

		try {
			// Make the curl request to Razorpay
			$response = Http::withHeaders([
				'Authorization' => 'Basic ' . $authHeader,
				'Content-Type' => 'application/json',
			])->post('https://api.razorpay.com/v1/orders', $postData);
			Log::info('$response', [$response]);

			// Parse and return the response
			if ($response->successful()) {
			
				return response()->json([
					'success' => true,
					'data' => $response->json(),
				]);
			} else {
				return response()->json([
					'success' => false,
					'message' => $response->json(),
				], $response->status());
			}
		} catch (\Exception $e) {
			return response()->json([
				'success' => false,
				'message' => $e->getMessage(),
			], 500);
		}
	}

	// Subtract availWalletAmount

	public function fetchRazorpayPayments(Request $request)
{
    // Validate incoming request
    $request->validate([
        'order_id' => 'required|string',
    ]);


    // Razorpay API credentials
    $keyId = 'rzp_test_uCWW4fIrP9ys6C';
    $keySecret = '9hcgBMZWz7gX64mxqR2yLbPC';
    $authHeader = base64_encode("$keyId:$keySecret");

    $orderId = $request->order_id;

    try {
        // Make the curl request to Razorpay
        $response = Http::withHeaders([
            'Authorization' => 'Basic ' . $authHeader,
        ])->get("https://api.razorpay.com/v1/orders/{$orderId}/payments");
		Log::info(' $response->json()',  [$response->json()]);

        // Return the response
        if ($response->successful()) {
			
			if(!empty($request->walletAmt)){
				availWalletAmount(base64_decode($request->orderBy),7,base64_decode($request->walletAmt));
			}

            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response->json(),
            ], $response->status());
        }
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage(),
        ], 500);
    }
}
}
