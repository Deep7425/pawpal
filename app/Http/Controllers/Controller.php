<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Admin\Symptoms;
use App\Models\Doctors;
use App\Models\Student;
use App\Models\State;
use App\Models\City;
use App\Models\LabOrders;
use App\Models\LabOrderTxn;
use App\Models\ehr\CityLocalities;
use App\Models\Speciality;
use App\Models\MedicineDetails;
use App\Models\ehr\SubscriptionsTxn;
use App\Models\ehr\ManageTrailPeriods;
use App\Models\ehr\SubscribedPlans;
use App\Models\ehr\PracticesSubscriptions;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\User as ehrUser;
use App\Models\LabCart;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\Appointments;
use App\Models\UserSubscriptionsTxn;
use App\Models\ehr\Plans;
use App\Models\Plans as userPlan;
use App\Models\User;
use App\Models\PlanPeriods;
use App\Models\UserSubscribedPlans;
use App\Models\UsersSubscriptions;
use App\Models\ManageUsersNotifications;
use App\Models\ehr\AppointmentTxn;
use App\Models\ehr\AppointmentOrder;
use App\Models\CcavenueResponse;
use App\Models\EnquiryForm;
use App\Http\Controllers\PaytmChecksum;
use App\Models\UserCashback;
use App\Models\ReferralCashback;
use App\Models\ehr\RoleUser;
use App\Models\ehr\Patients;
use App\Models\ehr\PatientRagistrationNumbers;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Support\Facades\Validator;
use App\Models\UsersDonation;
use App\Models\UserDonationTxn;
use App\Models\ApptLink;
use App\Models\MedicineOrders;
use App\Models\MedicineOrderedItems;
use App\Models\MedicineTxn;
use App\Models\UserPrescription;
use App\Models\OrganizationPayment;
use App\Models\DefaultLabs;
use App\Models\LabCollection;
use App\Models\ThyrocareLab;
use App\Models\LabPackage;
use App\Models\UserDetails;
use App\Models\Pages;
use App\Http\Controllers\PDFHF;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Exception;
use Google\Client;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class Controller extends BaseController
{
	private $client;
	private $accessToken;
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	public $notificationKey = 'AAAAKfEmcIY:APA91bFnCFD66QXU6DDdOkZ_dVGyCltf72teyb0hi5ifstB27TbIIQACNMhUDwcTx9TZLUPFzRqideyjAI1AlWWYmpS9FQl71AdkeJhHbicnrwTJA2DKMaOyNteels-sxWtMfsPOgHAP';

	protected function getView($view)
	{
		if (request()->segment(1) == 'amp') {
			if (view()->exists("amp." . $view)) {
				$view =  "amp." . $view;
			} else {
				abort(404);
			}
		}
		return $view;
	}

	public function sendSMS($mobile, $message, $tempId = null) {
		try {
			$url = "http://bulksms.smsdigital.in/api/SmsApi/SendSingleApi?UserID=healthgennie&Password=hgennie123&SenderID=GENNIE&Phno=".$mobile."&Msg=".$message."&EntityID=1701159306593960064&TemplateID=".$tempId;
			// Use @ to suppress errors from file_get_contents
			$response = @file_get_contents($url);
			// If the request fails, ignore the error
			if ($response !== false) {
				$json = json_decode($response, true);
				// Check if JSON response indicates success
				if ($json) {
					return 1; // SMS sent successfully
				}
			}
			// If SMS sending fails, do nothing (ignore the failure)
			return 0; // Optional return, could be omitted if you don't care about the result
		} catch (Exception $e) {
			// Log error if needed, but ignore the failure for SMS sending
			error_log("SMS sending failed: " . $e->getMessage());
			return 0; // Optional return, could be omitted
		}
	}

	public function getUpdateStateList($id)
	{
		try {
			$states = State::where('country_id', $id)->get();
			return $states;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function getUpdateCityList($id)
	{
		try {
			$cities = City::where('state_id', $id)->get();
			return $cities;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public	function getSpecialityList()
	{

		try {
			return getTopDocSpeciality();
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public	function getCurrentLocality(Request $request)
	{
		try {
			$city_name = '';
			$state_name = '';
			$city_id = '';
			$state_id = '';
			$locality_area = '';
			$city_data = '';
			$state_data = '';
			$localites = [];
			$query = CityLocalities::with(["State", "City"])->where('status', 1)->where('country_id', '101');
			if (!empty($request->city_name) || !empty($request->state_name)) {
				$city_name = $request->city_name;
				$state_name = $request->state_name;

				if (!empty($state_name)) {
					$state_id = State::select('id')->where('name', 'like', '%' . $state_name . '%')->where('country_id', '101')->first();
				}
				if (!empty($city_name)) {
					$query_city = City::with(["State"])->where('name', 'like', '%' . $city_name . '%');
					if (!empty($state_id)) {
						$query_city->where(["state_id" => $state_id->id]);
					}
					$city_id = $query_city->whereHas('State', function ($qr) {
						$qr->Where(['country_id' => '101']);
					})->first();
				}
				if (!empty($city_id)) {
					$query->where('city_id', $city_id->id);
				}
				if (!empty($state_id)) {
					$query->where('state_id', $state_id->id);
				}
			}

			if (!empty($request->locality_area)) {
				$locality_area = $request->locality_area;
				$query->where('name', 'like', '%' . $locality_area . '%');
				$city_data = City::with(["State"])->where('name', 'like', '%' . $locality_area . '%')->whereHas('State', function ($qrr) {
					$qrr->Where(['country_id' => '101']);
				})->get();
			}

			$locality = $query->orderBy('top_status', 'DESC')->limit(12)->get();
			if (count($locality) > 0) {
				$localites['locality'] = $locality;
			} else {
				$localites['locality'] = [];
			}
			if (!empty($city_data) && count($city_data) > 0) {
				$localites['city'] = $city_data;
			} else {
				$localites['city'] = [];
			}

			if (!empty($state_data)) {
				//$localites['state'] = $state_data;
			} else {
				$localites['state'] = [];
			}
			return $localites;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function is_connected()
	{



		try {
			$connected = @fsockopen("www.google.com", 80);
			if ($connected) {
				$is_conn = true; //action when connected
				fclose($connected);
			} else {
				$is_conn = false; //action in connection failure
			}
			return $is_conn;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function getStateList(Request $request)
	{
		//echo 'hii';die;
		try {
			$id = $request->input('id');
			//die;
			$states = State::where('country_id', $id)->get();

			return $states;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function getUpdateLocalityList($id)
	{
		try {
			$localities = CityLocalities::where('city_id', $id)->get();
			return $localities;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function getLocalityList(Request $request)
	{
		try {
			$id = $request->input('id');
			$localities = CityLocalities::where('city_id', $id)->get();
			return $localities;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function getCityList(Request $request)
	{
		try {
			$id = $request->input('id');
			$cities = City::where('state_id', $id)->get();
			return $cities;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function getDataByLatLng($data, $minLat, $maxLon, $distance_km)
	{
		try {

			$flag = 1;
			$difference = $distance_km + 10;
			while ($flag < 10) {
				$minLat = round($minLat - rad2deg($difference / 6371), 7);
				$maxLon = $this->getMaxLongitude($minLat, $maxLon, $difference);
				$data->orWhere('lat', '>=', $minLat)->where('lng', '<=', $maxLon)->orWhereNull('lat');
				if ($data->count() == 0) {
					$difference = $difference + 10;
					$flag += 1;
				} else {
					$flag = $flag + 100;
				}
			}

			return $data;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	function searchDoctorsWeb(Request $request)
	{

		try {
			$user_array = array();
			$user_array['search_key'] = $request->search_key;
			$user_array['address'] = $request->address;
			$user_array['locality_id'] = $request->locality_id;
			$user_array['city_id'] = $request->city_id;
			$user_array['state_id'] = $request->state_id;
			$user_array['lat'] = $request->lat;
			$user_array['lng'] = $request->lng;
			return	$this->fetchDocData($user_array);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function fetchDocDataOld($user_array)
	{

		try {
			$docs_array = [];
			$success = false;
			if ($user_array['search_key'] != '') {
				/** For Doctor Name wise**/
				$search_key = $user_array['search_key'];

				//$search_key = trim(str_replace('dr','',strtolower($search_key)));
				$firstChar = trim(strtolower(substr($search_key, 0, 3)));
				if ($firstChar == "dr." || $firstChar == "dr") {
					$search_key = trim(str_replace($firstChar, '', strtolower($search_key)));
				}
				$search_key = cleanForSql($search_key);
				$dotor_data = Doctors::with(["DoctorSlug"])->Where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->where("oncall_status", "!=", 0);
				/** Clinic name wise**/
				$doctor_clinic = Doctors::with(["DoctorSlug"])->select(["id", "user_id", "clinic_name", "practice_id", "clinic_image", "practice_type"])->Where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->where("oncall_status", "!=", 0);

				if (!empty($user_array['locality_id'])) {
					//$dotor_data->where('locality_id',$user_array['locality_id']);
					//$doctor_clinic->where('locality_id',$user_array['locality_id']);
				}
				if (!empty($user_array['city_id'])) {
					// $dotor_data->where('city_id',$user_array['city_id']);
					// $doctor_clinic->where('city_id',$user_array['city_id']);
				}
				if (!empty($user_array['state_id'])) {
					// $dotor_data->where('state_id',$user_array['state_id']);
					//$doctor_clinic->where('state_id',$user_array['state_id']);
				}

				/** For Doctor Name **/
				$doc_data_by_name = $dotor_data->where(DB::raw('concat(first_name," ",IFNULL(last_name,""))'), 'like', '%' . $search_key . '%')->orderBy('hg_doctor', 'DESC')->limit(5)->get();
				if ($doc_data_by_name->count() > 0) {
					$docs_array["Doctors"] = bindDocData($doc_data_by_name);
					$success = true;
				} else {
					$docs_array["Doctors"] = [];
				}

				$clinic_array = [];
				$hospital_array = [];
				/** Clinic name **/
				$doc_data_by_clinic_name = $doctor_clinic->where('clinic_name', 'like', '%' . $search_key . '%')->groupBy('practice_id')->orderBy('hg_doctor', 'DESC')->limit(5)->get();
				if ($doc_data_by_clinic_name->count() > 0) {
					foreach ($doc_data_by_clinic_name as $val) {
						if (!empty($val->clinic_image)) {
							$image_url = getEhrUrl() . "/public/doctor/" . $val->clinic_image;
							if (does_url_exists($image_url)) {
								$val['clinic_image'] = $image_url;
							} else {
								$val['clinic_image'] = null;
							}
						} else {
							$val['clinic_image'] = null;
						}
						$val['doc_rating'] = 0;
						if (isset($val->DoctorRatingReviews)) {
							if (count($val->DoctorRatingReviews) > 0) {
								$rating_val = 0;
								$rating_count = 0;
								foreach ($val->DoctorRatingReviews as $rating) {
									$rating_val += $rating->rating;
									$rating_count++;
								}
								if ($rating_val > 0) {
									$rating_val = round($rating_val / $rating_count, 1);
								}
								$val['doc_rating'] = $rating_val;
							}
						}
						if ($val->practice_type == 2) {
							$hospital_array[] = $val;
						} else {
							$clinic_array[] = $val;
						}
					}
					$docs_array["Clinic"] = $clinic_array;
					$docs_array["Hospital"] = $hospital_array;
					$success = true;
				} else {
					$docs_array["Clinic"] = [];
					$docs_array["Hospital"] = [];
				}

				/** Specialty name **/

				$sptArr = [];
				$doc_data_by_spaciality = Speciality::where(DB::raw('concat(specialities," ",IFNULL(spaciality,""))'), 'like', '%' . $search_key . '%')->whereNotIn('id', [203])->limit(5)->get();

				if (count($doc_data_by_spaciality) > 0) {
					foreach ($doc_data_by_spaciality as $value) {
						if (!empty($value->speciality_icon)) {
							$value['speciality_icon'] = url("/") . "/public/speciality-icon/" . $value->speciality_icon;
						}
					}
					$sptArr = $doc_data_by_spaciality;
					$success = true;
				}

				$docBySptTags = Speciality::where('tags', 'like', '%' . $search_key . '%')->whereNotIn('id', [203])->limit(5)->get();
				if (count($docBySptTags) > 0) {
					foreach ($docBySptTags as $value) {
						if (!empty($value->speciality_icon)) {
							$value['speciality_icon'] = url("/") . "/public/speciality-icon/" . $value->speciality_icon;
						}
						if (!empty($value->tags)) {
							$tags = explode(",", $value->tags);
							$value['specialities'] = $value->specialities . " ($tags[0])";
						}
					}
					$success = true;
					$sptArr = $doc_data_by_spaciality->merge($docBySptTags);
				}
				$docs_array["Speciality"] = $sptArr;

				$symptomps_query = Symptoms::with(['SymptomsSpeciality', 'SymptomTags'])->Where(['status' => 1])->Where('symptom', 'like', '%' . $search_key . '%');
				$symptomps_query->OrWhereHas("SymptomTags", function ($qry) use ($search_key) {
					$qry->Where('text', 'like', '%' . $search_key . '%');
				});
				$doc_data_by_symptomps = $symptomps_query->limit(5)->get();
				if (count($doc_data_by_symptomps) > 0) {
					$docs_array["symptoms"] = $doc_data_by_symptomps;
					$success = true;
				} else {
					$docs_array["symptoms"] = [];
				}
			}
			return $docs_array;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function fetchDocData($user_array)
	{
		try {
			$docs_array = [];
			$success = false;
			if ($user_array['search_key'] != '') {
				/** For Doctor Name wise**/
				$search_key = $user_array['search_key'];
				//$search_key = trim(str_replace('dr','',strtolower($search_key)));
				$firstChar = trim(strtolower(substr($search_key, 0, 3)));
				if ($firstChar == "dr." || $firstChar == "dr") {
					$search_key = trim(str_replace($firstChar, '', strtolower($search_key)));
				}
				// $search_key = cleanForSql($search_key);
				$dotor_data = Doctors::with(["DoctorSlug"])->Where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->where("oncall_status", "!=", 0);
				/** Clinic name wise**/
				$doctor_clinic = Doctors::with(["DoctorSlug"])->select(["id", "user_id", "clinic_name", "practice_id", "clinic_image", "practice_type"])->Where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->where("oncall_status", "!=", 0);
				if (!empty($user_array['locality_id'])) {
					//$dotor_data->where('locality_id',$user_array['locality_id']);
					//$doctor_clinic->where('locality_id',$user_array['locality_id']);
				}
				if (!empty($user_array['city_id'])) {
					// $dotor_data->where('city_id',$user_array['city_id']);
					// $doctor_clinic->where('city_id',$user_array['city_id']);
				}
				if (!empty($user_array['state_id'])) {
					// $dotor_data->where('state_id',$user_array['state_id']);
					//$doctor_clinic->where('state_id',$user_array['state_id']);
				}
				/** For Doctor Name **/
				// $doc_data_by_name = $dotor_data->whereRaw('match (first_name, last_name) against ("classic" in boolean mode)', [$search_key])->orderBy('hg_doctor','DESC')->limit(5)->get();

				$explodName = explode(" ", $search_key);
				$search_key2 = preg_replace('/[^A-Za-z0-9\-]/', '', $search_key);
				if (strlen($search_key2) == 2) {
					$search_key = preg_replace('/[^A-Za-z0-9\-]/', '', $search_key);
					$search_key = wordwrap($search_key, 1, " ", true);
					$explodName = explode(" ", $search_key);
					// $doc_data_by_name = $dotor_data->where(\DB::raw("INSERT(REPLACE(LEFT(TRIM(REPLACE(CONCAT(first_name, ' ', IFNULL(last_name,'')), '.', '')), 3), ' ', ''), 2, 0, ' ')"), 'LIKE', $search_key.'%')->orderBy('hg_doctor','DESC')->limit(5)->get();

					$doc_data_by_name = $dotor_data->where(function ($q) use ($search_key, $explodName) {
						$q->where(DB::raw("INSERT(LEFT(REPLACE(REPLACE(CONCAT(first_name, ' ', IFNULL(last_name,'')), '.', ''), ' ', ''),2), 2, 0, ' ')"), 'LIKE', $search_key . '%')
							->orWhere(function ($q) use ($search_key, $explodName) {
								$q->Where(DB::raw("LEFT(first_name, 1)"), '=', $explodName[0])
									->Where(DB::raw("LEFT(IFNULL(last_name,''), 1)"), '=', $explodName[1]);
							});
					})->orderBy('hg_doctor', 'DESC')->limit(5)->get();
				} else {
					$dotor_data->where(function ($q) use ($search_key) {
						$q->where(DB::raw('concat(first_name," ",IFNULL(last_name,""))'), 'like', '%' . $search_key . '%')
							->orWhere('first_name', 'SOUNDS LIKE', '%' . $search_key);
					});
					if (isset($explodName[0]) && isset($explodName[1])) {
						$dotor_data->orWhere(function ($q) use ($search_key, $explodName) {
							$q->Where(\DB::raw("LEFT(first_name, 1)"), '=', $explodName[0])
								->Where(\DB::raw("IFNULL(last_name,'')"), 'like', '%' . $explodName[1] . '%');
						});
					}
					$doc_data_by_name = $dotor_data->limit(5)->get();
					// dd($doc_data_by_name)
				}
				if ($doc_data_by_name->count() > 0) {
					$docs_array["Doctors"] = bindDocData($doc_data_by_name);
					$success = true;
				} else {
					$docs_array["Doctors"] = [];
				}
				$clinic_array = [];
				$hospital_array = [];
				/** Clinic name **/
				$doc_data_by_clinic_name = $doctor_clinic->where('clinic_name', 'like', '%' . $search_key . '%')->groupBy('practice_id')->orderBy('hg_doctor', 'DESC')->limit(5)->get();
				if ($doc_data_by_clinic_name->count() > 0) {
					foreach ($doc_data_by_clinic_name as $val) {
						if (!empty($val->clinic_image)) {
							$image_url = getEhrUrl() . "/public/doctor/" . $val->clinic_image;
							if (does_url_exists($image_url)) {
								$val['clinic_image'] = $image_url;
							} else {
								$val['clinic_image'] = null;
							}
						} else {
							$val['clinic_image'] = null;
						}
						$val['doc_rating'] = 0;
						if (isset($val->DoctorRatingReviews)) {
							if (count($val->DoctorRatingReviews) > 0) {
								$rating_val = 0;
								$rating_count = 0;
								foreach ($val->DoctorRatingReviews as $rating) {
									$rating_val += $rating->rating;
									$rating_count++;
								}
								if ($rating_val > 0) {
									$rating_val = round($rating_val / $rating_count, 1);
								}
								$val['doc_rating'] = $rating_val;
							}
						}
						if ($val->practice_type == 2) {
							$hospital_array[] = $val;
						} else {
							$clinic_array[] = $val;
						}
					}
					$docs_array["Clinic"] = $clinic_array;
					$docs_array["Hospital"] = $hospital_array;
					$success = true;
				} else {
					$docs_array["Clinic"] = [];
					$docs_array["Hospital"] = [];
				}
				/** Specialty name **/
				$sptArr = [];
				$doc_data_by_spaciality = Speciality::where(DB::raw('concat(specialities," ",IFNULL(spaciality,""))'), 'like', '%' . $search_key . '%')->whereNotIn('id', [203])->limit(5)->get();
				if (count($doc_data_by_spaciality) > 0) {
					foreach ($doc_data_by_spaciality as $value) {
						if (!empty($value->speciality_icon)) {
							$value['speciality_icon'] = url("/") . "/public/speciality-icon/" . $value->speciality_icon;
						}
					}
					$sptArr = $doc_data_by_spaciality;
					$success = true;
				}
				$docBySptTags = Speciality::where('tags', 'like', '%' . $search_key . '%')->whereNotIn('id', [203])->limit(5)->get();
				if (count($docBySptTags) > 0) {
					foreach ($docBySptTags as $value) {
						if (!empty($value->speciality_icon)) {
							$value['speciality_icon'] = url("/") . "/public/speciality-icon/" . $value->speciality_icon;
						}
						if (!empty($value->tags)) {
							$tags = explode(",", $value->tags);
							$value['specialities'] = $value->specialities . " ($tags[0])";
						}
					}
					$success = true;
					$sptArr = $doc_data_by_spaciality->merge($docBySptTags);
				}
				$docs_array["Speciality"] = $sptArr;
				$symptomps_query = Symptoms::with(['SymptomsSpeciality', 'SymptomTags'])->Where(['status' => 1])->Where('symptom', 'like', '%' . $search_key . '%');
				$symptomps_query->OrWhereHas("SymptomTags", function ($qry) use ($search_key) {
					$qry->Where('text', 'like', '%' . $search_key . '%');
				});
				$doc_data_by_symptomps = $symptomps_query->limit(5)->get();
				if (count($doc_data_by_symptomps) > 0) {

					$docs_array["symptoms"] = $doc_data_by_symptomps;
					Log::info('doc_symptom11', [$docs_array["symptoms"]]);
					$success = true;
				} else {
					$docs_array["symptoms"] = [];
				}
			}
			return $docs_array;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}


	public function getMaxLongitude($lat, $lng, $distance)
	{

		try {
			$R = 6371; //constant earth radius. You can add precision here if you wish
			$maxLon = round($lng + rad2deg(asin($distance / $R) / cos(deg2rad($lat))), 7);
			return $maxLon;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function pn($apikey = null, $regId = null, $message = null, $title = null, $subtitle = null, $tickerText = null, $page = null)
	{

		try {
			$id = [$regId];
			$result = $this->androidNotificationSend($apikey, $id, $message, $title, $subtitle, $tickerText, $page);
			return $result;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function androidNotificationSend($apikey = null, $registrationIds = [], $message = null, $title = null, $subtitle = null, $tickerText = null, $page = null)
	{

		try {

			if (count($registrationIds) > 0 && $message != '' && $message != null && $title != '' && $title != null && $subtitle != '' && $subtitle != null) {
				$api_key = $apikey;
				$msg = array(
					'body'  => $message,
					'title'  => $title,
					//'android_channel_id' => 'healthgennie',
					'vibrate' => 1,
					'lights' => 1,
					//'sound' => 'ringtone',
					// 'badge'=>7,
					// 'ongoing'=> true,
					'visibility' => 1,
					//'soundname'=> 'kaps',
					'image' => "https://doc.healthgennie.com/img/web/health_gennie_logo.png",
					'subtitle' => $subtitle,
					'tickerText' => $tickerText,
					'foreground' => 1,
					'priority' => 10,

					// 'click_action' => "FCM_PLUGIN_ACTIVITY",
					"icon" => "notification_icon",
					"color" => '#14bef0',
					"forceShow" => 1,
					"pushNotification" => true

				);

				$fields = array(
					'registration_ids'  => $registrationIds,
					// "content_available"=> true,
					'notification'   => $msg,
					'priority' => 'high',
					'data'   => array(
						'page'  => $page
					)
				);
				$headers = array(
					'Authorization: key=' . $api_key,
					'Content-Type: application/json'
				);
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
				$result = curl_exec($ch);
				curl_close($ch);
				return strip_tags($result);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function myMedicineReminderNotification(Request $request)
	{

		try {

			$daynumber = getNumberByDay(date('D'));
			$today = date('Y-m-d');
			$time = date('H:i');
			$medicines = MedicineDetails::with("user")->where(['status' => 1])->whereRaw("find_in_set('" . $daynumber . "',medicine_details.days)")
				->whereDate('start', '<=', $today)
				->whereDate('end', '>=', $today)
				->where('time', '=', $time)
				->orderBy("id", "DESC")->get();

			if (count($medicines) > 0) {
				foreach ($medicines as $med) {
					if (!empty($med->user)) {
						$title = 'Medicine Reminder';
						$subtitle = 'Today Your Medicine';
						$tickerText = 'This is text';
						$message = $med->med_name . " - " . date("h:i A", strtotime($med->time));
						$fcm_token = $med->user->fcm_token;
						$device_type = $med->user->device_type;

						if ($device_type == 1 && !empty($fcm_token)) {
							$notifyres = $this->pn($this->notificationKey, $fcm_token, $message, $title, $subtitle, $tickerText, 'medicine_reminder');
						} else if ($device_type == 2 && !empty($fcm_token)) {
							$iosnotify = $this->iosNotificationSend($fcm_token, $message, $title, $tickerText, 'medicine_reminder');
							//pr($iosnotify);
						}
					}
				}
			}

			die;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function iosNotificationSend($deviceId = null, $message = null, $title = null, $tickerText = null, $page = null)
	{


		try {

			$token = $deviceId;
			$body = $message;

			$notification = array('title' => $title, 'text' => $body, 'tickerText' => $tickerText, 'content-available' => 1, 'foreground' => false);
			$arrayToSend = array('to' => $token, 'notification' => $notification, 'priority' => 'high', 'data'   => array('page'  => $page));
			$headers = array();
			$headers[] = 'Content-Type: application/json';
			if ($page == "video" || $page == "appointmentpp") {
				$headers[] = 'Authorization: key= AAAAIC4y15c:APA91bEL_YYIe4KZOcC-_HogaAC80aabtIxTDBGYMExAdzAVcyEqQhEvfdrDuxE8mFe7bgrE44l3SdIpDNyOZvbonOuhfSV91Z0AtQa8R_YYNajL9YpF62Xc0AWuwOqaZTuiabxqtdCA';
			} else {
				$headers[] = 'Authorization: key= AAAAKfEmcIY:APA91bFnCFD66QXU6DDdOkZ_dVGyCltf72teyb0hi5ifstB27TbIIQACNMhUDwcTx9TZLUPFzRqideyjAI1AlWWYmpS9FQl71AdkeJhHbicnrwTJA2DKMaOyNteels-sxWtMfsPOgHAP';
			}
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrayToSend));
			$result = curl_exec($ch);

			curl_close($ch);
			return strip_tags($result);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function paymentResponse(Request $request)
	{

		try {

			$response = Indipay::gateway('CCAvenue')->response($request);
			CcavenueResponse::create([
				'slug' => $response['merchant_param1'],
				'meta_data' => json_encode($response),
			]);
			if ($response['merchant_param1'] == "HealthGennie Lab Order") {
				LabOrderTxn::create([
					'order_id' => $response['order_id'],
					'tracking_id' => $response['tracking_id'],
					'bank_ref_no' => $response['bank_ref_no'],
					'tran_mode' => $response['payment_mode'],
					'card_name' => $response['card_name'],
					'currency' => $response['currency'],
					'payed_amount' => $response['amount'],
					'tran_status' => $response['order_status'],
					'trans_date' => $response['trans_date']
				]);
				if ($response['order_status'] == 'Success') {
					LabOrders::where(["orderId" => $response['order_id']])->update([
						'status' => 1,
					]);

					return Redirect::to('https://www.healthgennie.com/lab-order/success?order_id=' . base64_encode($response['order_id']));
				} else {
					LabOrders::where(["orderId" => $response['order_id']])->update([
						'status' => 3,
					]);
					return Redirect::to('https://www.healthgennie.com/lab-order/cancel?order_id=' . base64_encode($response['order_id']));
				}
			} else if ($response['merchant_param3'] == "Gennie Plan") {
				UserSubscriptionsTxn::create([
					'subscription_id' => $response['order_id'],
					'tracking_id' => $response['tracking_id'],
					'bank_ref_no' => $response['bank_ref_no'],
					'tran_mode' => $response['payment_mode'],
					'card_name' => $response['card_name'],
					'currency' => $response['currency'],
					'payed_amount' => $response['amount'],
					'tran_status' => $response['order_status'],
					'trans_date' => $response['trans_date']
				]);
				if ($response['order_status'] == 'Success') {
					UsersSubscriptions::where(["id" => $response['order_id']])->update([
						'order_status' => 1,
					]);
					PlanPeriods::where('subscription_id', $response['order_id'])->update(array(
						'status' => 1,
					));
					$this->sendUserSubscriptionMail($response['order_id'], 1, "success");
					return Redirect::to('https://www.healthgennie.com/plan/success?order_id=' . base64_encode($response['tracking_id']));
				} else {
					UsersSubscriptions::where(["id" => $response['order_id']])->update([
						'order_status' => 3,
					]);
					return Redirect::to('https://www.healthgennie.com/plan/cancel?order_id=' . base64_encode($response['order_id']));
				}
			} else if ($response['merchant_param1'] == "Health Gennie Appointment") {
				if ($response['order_status'] == 'Success') {
					$order = AppointmentOrder::where(["id" => $response['order_id'], 'order_status' => 0])->first();
					if (!empty($order->meta_data)) {
						$this->putAppointmentDataApp($order, $response);
					}
					return Redirect::to('https://www.healthgennie.com/appointment/success?order_id=' . base64_encode($response['tracking_id']));
				} else {
					return Redirect::to('https://www.healthgennie.com/appointment/cancel?order_id=' . base64_encode($response['order_id']));
				}
			} else {
				$subscription =  SubscriptionsTxn::create([
					'subscription_id' => $response['order_id'],
					'tracking_id' => $response['tracking_id'],
					'bank_ref_no' => $response['bank_ref_no'],
					'tran_mode' => $response['payment_mode'],
					'card_name' => $response['card_name'],
					'currency' => $response['currency'],
					'payed_amount' => $response['amount'],
					'tran_status' => $response['order_status'],
					'trans_date' => $response['trans_date']
				]);
				$params = array();
				if (!empty($response['order_id'])) {
					$params['order_id'] = base64_encode($response['order_id']);
				}
				$msg = '';
				if ($response['order_status'] == 'Success') {
					PracticesSubscriptions::where('id', $response['order_id'])->update(array(
						'order_status' => 1,
					));
					ManageTrailPeriods::where('subscription_id', $response['order_id'])->update(array(
						'status' => 1,
					));
					$checkfreeTrail = ManageTrailPeriods::where(['user_id' => $response['merchant_param2'], 'user_plan_id' => 5])->first();
					if (!empty($checkfreeTrail) && $checkfreeTrail->status == 1) {
						ManageTrailPeriods::where('id', $checkfreeTrail->id)->update(array(
							'status' => 0,
						));
					}
					$this->sendPracticeSubscriptionMail($response['order_id'], 1, "success");
					$this->sendOwnerMailForSubscription($response['order_id'], 1, "success");
					$msg = 'Payment Successfully Done.';
				} else {
					PracticesSubscriptions::where('id', $response['order_id'])->update(array(
						'order_status' => 3,
					));
					$msg = (!empty($response['failure_message']) || $response['failure_message'] != "") ? $response['failure_message'] : "Your Transaction has been failed for the healthgennie subscription.";

					$this->sendPracticeSubscriptionMail($response['order_id'], 3, $msg);
					$this->sendOwnerMailForSubscription($response['order_id'], 3, $msg);
				}
				return Redirect::to('https://www.healthgennie.com/subcription-success?pid=' . base64_encode($response['merchant_param2']));
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

public function paytmResponse(Request $request)
	{
		try {
		 session()->regenerate(); 
			
			$response = $request->all();
		
			Log::info('$response',[$response]);
			if (substr($response['ORDERID'], 0, 4) == "SUBS") {
				Log::info('in1');
				CcavenueResponse::create([
					'slug' => 'plan_subscription',
					'meta_data' => json_encode($response),
				]);
				
				// if($response['clientId'] == "Gennie_Plan"){
				$orderData = UsersSubscriptions::select(["id", "meta_data"])->where(["order_id" => $response['ORDERID']])->first();
				$orderID = $orderData->id;
				UserSubscriptionsTxn::create([
					'subscription_id' => $orderID,
					'tracking_id' => @$response['TXNID'],
					'bank_ref_no' => @$response['BANKTXNID'],
					'tran_mode' => @$response['PAYMENTMODE'],
					'card_name' => @$response['BANKNAME'],
					'currency' => @$response['CURRENCY'],
					'payed_amount' => @$response['TXNAMOUNT'],
					'tran_status' => @$response['STATUS'],
					'trans_date' => @$response['TXNDATE']
				]);
				if ($response['STATUS'] == 'TXN_SUCCESS') {
					$meta_data = json_decode($orderData->meta_data, true);
					$plan = userPlan::where('id', $meta_data['plan_id'])->first();
					$subscribedPlan = new UserSubscribedPlans;
					$subscribedPlan->subscription_id = $orderID;
					$subscribedPlan->plan_id = $plan->id;
					$subscribedPlan->plan_price = $plan->price;
					$subscribedPlan->discount_price = $plan->discount_price;
					$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
					$subscribedPlan->plan_duration = $plan->plan_duration;
					$subscribedPlan->appointment_cnt = $plan->appointment_cnt;
					$subscribedPlan->lab_pkg = $plan->lab_pkg;
					$subscribedPlan->meta_data = json_encode($plan);
					$subscribedPlan->save();

					//for the plan trail period
					$duration_type = $plan->plan_duration_type;
					if ($duration_type == "d") {
						$duration_in_days = $plan->plan_duration;
					} elseif ($duration_type == "m") {
						$duration_in_days = (30 * $plan->plan_duration);
					} elseif ($duration_type == "y") {
						$duration_in_days = (366 * $plan->plan_duration);
					}
					$end_date = date('Y-m-d', strtotime($subscribedPlan->created_at . '+' . $duration_in_days . ' days'));
					$PlanPeriods =  PlanPeriods::create([
						'subscription_id' => $orderID,
						'subscribed_plan_id' => $subscribedPlan->id,
						'user_plan_id' => $meta_data['plan_id'],
						'user_id' => $meta_data['user_id'],
						'start_trail' => date('Y-m-d'),
						'end_trail' => $end_date,
						'remaining_appointment' => $plan->appointment_cnt,
						'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
						'lab_pkg_remaining' => 0,
						'status' => 1
					]);
					UsersSubscriptions::where(["id" => $orderID])->update([
						'order_status' => 1,
					]);
					updateWallet($meta_data['user_id'], 4, 'subscription_reward');
					availWalletAmount($meta_data['user_id'], 7, @$meta_data['availWalletAmt']);
					ApptLink::where(["user_id" => $meta_data['user_id'], 'order_id' => $response['ORDERID']])->update(['status' => 1]);
					if (!empty($meta_data['coupon_code']) && checkRefCodeIsExist($meta_data['coupon_code']) == false) {
						$this->walletCashbackSubs($meta_data, $orderID, $meta_data['plan_id']);
					}
					$this->sendUserSubscriptionMail($orderID, 1, "success");
					if (isset($meta_data['patientInfo']) && !empty($meta_data['patientInfo'])) {
						$patientInfo = @$meta_data['patientInfo'];
						$this->addApptForPlan($patientInfo);
					}
					$url = url("/") . '/plan/success?order_id=' . base64_encode(@$response['TXNID']);
					return Redirect::to($url);
				} else {
					UsersSubscriptions::where(["id" => $orderID])->update([
						'order_status' => 3,
					]);
					ApptLink::where(['order_id' => $response['ORDERID']])->update(['status' => 2]);
					$url = url("/") . '/plan/cancel?order_id=' . base64_encode(@$response['TXNID']);
					return Redirect::to($url);
				}
			
			} else if (substr($response['ORDERID'], 0, 3) == "MED") {
				Log::info('in2');
				CcavenueResponse::create([
					'slug' => 'medicine_orderd',
					'meta_data' => json_encode($response),
				]);
				$orderData = MedicineOrders::select(["id", "delivery_date", "meta_data", "user_id"])->where(["order_id" => $response['ORDERID']])->first();
				$orderID = $orderData->id;
				MedicineTxn::create([
					'type' => 1,
					'order_id' => $orderData->id,
					'tracking_id' => @$response['TXNID'],
					'bank_ref_no' => @$response['BANKTXNID'],
					'tran_mode' => @$response['PAYMENTMODE'],
					'card_name' => @$response['BANKNAME'],
					'currency' => @$response['CURRENCY'],
					'payed_amount' => @$response['TXNAMOUNT'],
					'tran_status' => @$response['STATUS'],
					'trans_date' => @$response['TXNDATE']
				]);
				if ($response['STATUS'] == 'TXN_SUCCESS') {
					$meta_data = json_decode($orderData->meta_data, true);
					if (count($meta_data['meds']) > 0) {
						foreach ($meta_data['meds'] as $raw) {
							// $medDetail = MedicineDetails::where('id',$raw->medicine_id)->first();
							$item = new MedicineOrderedItems;
							$item->medicine_id = @$raw['medicine_id'];
							$item->order_id = $orderData->id;
							$item->qty = @$raw['qty'];
							$item->cost = @$raw['medicine_details']['price'];
							// $item->discount_amt = $raw->discount_amt;
							// $item->discount_type = $raw->discount_type;
							// $item->tax = $raw->tax;
							// $item->tax_amt = $raw->tax_amt;
							$item->total_amount = @$raw['medicine_details']['price'];
							$item->save();
						}
					}
					MedicineOrders::where(["id" => $orderData->id])->update([
						'type' => 2,
						'status' => 1,
					]);
					$this->sendMedicineOrderMail($orderID, $orderData->delivery_date);
					ApptLink::where(["type" => 2, "user_id" => $orderData->user_id, 'order_id' => $response['ORDERID']])->update(['status' => 1]);
					$url = url("/") . '/medicine/success?order_id=' . base64_encode(@$response['TXNID']);
					return Redirect::to($url);
				} else {
					MedicineOrders::where(["id" => $orderID])->update([
						'status' => 3,
					]);
					$url = url("/") . '/medicine/cancel?order_id=' . base64_encode(@$response['TXNID']);
					return Redirect::to($url);
				}
			} else if (substr($response['ORDERID'], 0, 3) == "DON") {
				CcavenueResponse::create([
					'slug' => 'donation',
					'meta_data' => json_encode($response),
				]);
				$orderData = UsersDonation::select(["id", "meta_data"])->where(["order_id" => $response['ORDERID']])->first();
				$orderID = $orderData->id;
				UserDonationTxn::create([
					'donation_id' => $orderID,
					'tracking_id' => @$response['TXNID'],
					'bank_ref_no' => @$response['BANKTXNID'],
					'tran_mode' => @$response['PAYMENTMODE'],
					'card_name' => @$response['BANKNAME'],
					'currency' => @$response['CURRENCY'],
					'payed_amount' => @$response['TXNAMOUNT'],
					'tran_status' => @$response['STATUS'],
					'trans_date' => @$response['TXNDATE']
				]);
				if ($response['STATUS'] == 'TXN_SUCCESS') {
					UsersDonation::where(["id" => $orderID])->update([
						'order_status' => 1,
					]);
					$url = url("/") . '/donation/success?order_id=' . base64_encode(@$response['TXNID']);
					return Redirect::to($url);
				} else {
					UsersDonation::where(["id" => $orderID])->update([
						'order_status' => 3,
					]);
					$url = url("/") . '/donation/cancel?order_id=' . base64_encode(@$response['TXNID']);
					return Redirect::to($url);
				}
			} else if (strpos($response['ORDERID'], 'LAB') !== false) {
				Log::info('in3');

				CcavenueResponse::create([
					'slug' => 'lab_order',
					'meta_data' => json_encode($response),
				]);
				$lab = LabOrders::select(["id", "user_id", "meta_data", "type"])->where(["orderId" => $response['ORDERID']])->first();

				$response['order_id'] = $lab->id;
				LabOrderTxn::create([
					'order_id' => $response['order_id'],
					'tracking_id' => @$response['TXNID'],
					'bank_ref_no' => @$response['BANKTXNID'],
					'tran_mode' => @$response['PAYMENTMODE'],
					'card_name' => @$response['BANKNAME'],
					'currency' => @$response['CURRENCY'],
					'payed_amount' => @$response['TXNAMOUNT'],
					'tran_status' => @$response['STATUS'],
					'trans_date' => (isset($response['TXNDATE']) ? $response['TXNDATE'] : date("Y-m-d h:i:s")),
				]);
				if ($response['STATUS'] == 'TXN_SUCCESS') {
					$meta_data = json_decode($lab->meta_data);
					if ($lab->type == 0 && $lab->type != null) {

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, "https://velso.thyrocare.cloud/api/BookingMaster/DSABooking");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POST, true);
						$order_array = array(
							'ApiKey' => $meta_data->api_key,
							'OrderId' => $meta_data->orderId,
							'Address' => $meta_data->address,
							'Pincode' => $meta_data->pincode,
							'Product' => $meta_data->product,
							'Mobile' => $meta_data->mobile,
							'Email' => $meta_data->email,
							'Gender' => $meta_data->Gender,
							'ServiceType' => $meta_data->service_type,
							'OrderBy' => $meta_data->order_by,
							'Rate' => $meta_data->rate,
							'HC' => $meta_data->hc,
							'ApptDate' => $meta_data->appt_date,
							'Reports' => $meta_data->reports,
							'RefCode' => $meta_data->ref_code,
							'PayType' => $meta_data->pay_type,
							'BenCount' => $meta_data->bencount,
							'BenDataXML' => $meta_data->bendataxml,
							'ReportCode' => $meta_data->report_code,
							'Passon' => $meta_data->Passon,
							'Remarks' => ""
						);
						$order_data = json_encode($order_array);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $order_data);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$output = curl_exec($ch);
						curl_close($ch);
						$output = json_decode($output, true);
						if (!empty($output) && $output['respId'] == 'RES02012') {
							LabOrders::where(["orderId" => $response['order_id']])->update([
								'status' => 1,
								'is_free_appt' => 1,
								'order_status' => $output['status'],
								'post_order_meta' => json_encode($output),
								'ref_orderId' => $output['refOrderId'],
							]);

							LabCart::where(['user_id' => $lab->user_id])->delete();
							Session::forget('CartPackages');
							$appt_date = date("d-m-Y", strtotime($meta_data->appt_date));
							$appt_time = date("h:i A", strtotime($meta_data->appt_date));
							$message = urlencode('Dear ' . $meta_data->order_by . ', Your Lab Test (' . $meta_data->product . ') booking is confirmed with Healthgennie on ' . $appt_date . ' at ' . $appt_time . '.Please be available at your location at the given time. Thanks Team Health Gennie');
							$this->sendSMS($meta_data->mobile, $message, '1707165122333414122');

							$message = urlencode('This patient(' . $meta_data->order_by . ') has booked a lab test (' . $meta_data->product . ') with Thyrocare lab on ' . $appt_date . ' at ' . $appt_time . '. Patient Mobile : ' . $meta_data->mobile . ' Thanks Team Health Gennie');
							$this->sendSMS(8905557252, $message, '1707165122295538821');

							updateWallet($lab->user_id, 3, 'lab_reward');
							availWalletAmount($lab->user_id, 5, @$meta_data->availWalletAmt);
							return Redirect::to('https://www.healthgennie.com/lab-order/success?order_id=' . base64_encode($response['order_id']));
						} else {
							LabOrders::where(["orderId" => $response['order_id']])->update([
								'status' => 2,
								'post_order_meta' => json_encode($output),
							]);
							return Redirect::to('https://www.healthgennie.com/lab-order/cancel?order_id=' . base64_encode($response['order_id']));
						}
					} else if ($lab->type == null) {

						LabOrders::where(["orderId" => $response['order_id']])->update([
							'status' => 1,
							'is_free_appt' => 1,
							'order_status' => 'YET TO CONFIRM',
						]);

						LabCart::where(['user_id' => $lab->user_id])->delete();
						Session::forget('CartPackages');
						$appt_date = date("d-m-Y", strtotime($meta_data->appt_date));
						$appt_time = date("h:i A", strtotime($meta_data->appt_date));
						$message = urlencode('Dear ' . $meta_data->order_by . ', Your Lab Test (' . $meta_data->product . ') booking is confirmed with Healthgennie on ' . $appt_date . ' at ' . $appt_time . '.Please be available at your location at the given time. Thanks Team Health Gennie');
						$this->sendSMS($meta_data->mobile, $message, '1707165122333414122');
						return Redirect::to('https://www.healthgennie.com/lab-order/success?order_id=' . base64_encode($response['order_id']));
					} else {
						LabOrders::where(["orderId" => $response['order_id']])->update([
							'status' => 1,
							'is_free_appt' => 1,
							'order_status' => 'YET TO CONFIRM',
						]);

						LabCart::where(['user_id' => $lab->user_id])->delete();
						Session::forget('CartPackages');
						$appt_date = date("d-m-Y", strtotime($meta_data->appt_date));
						$appt_time = date("h:i A", strtotime($meta_data->appt_date));
						$message = urlencode('Dear ' . $meta_data->order_by . ', Your Lab Test (' . $meta_data->product . ') booking is confirmed with Healthgennie on ' . $appt_date . ' at ' . $appt_time . '.Please be available at your location at the given time. Thanks Team Health Gennie');
						$this->sendSMS($meta_data->mobile, $message, '1707165122333414122');

						$message = urlencode('This patient(' . $meta_data->order_by . ') has booked a lab test (' . $meta_data->product . ') with Reliable lab on ' . $appt_date . ' at ' . $appt_time . '. Patient Mobile : ' . $meta_data->mobile . ' Thanks Team Health Gennie');
						$this->sendSMS(8905557252, $message, '1707165122295538821');
						updateWallet($lab->user_id, 3, 'lab_reward');
						availWalletAmount($lab->user_id, 5, @$meta_data->availWalletAmt);
						return Redirect::to('https://www.healthgennie.com/lab-order/success?order_id=' . base64_encode($response['order_id']));
					}
				} else {
					LabOrders::where(["orderId" => $response['order_id']])->update([
						'status' => 2,
					]);
					return Redirect::to('https://www.healthgennie.com/lab-order/cancel?order_id=' . base64_encode($response['order_id']));
				}
			} else {
				Log::info('in4');
				CcavenueResponse::create([
					'slug' => 'appointment',
					'meta_data' => json_encode($response),
				]);
				$TXNID = @$response['TXNID'];
				if ($response['STATUS'] == 'TXN_SUCCESS') {
					$order = AppointmentOrder::where(["id" => $response['ORDERID'], 'order_status' => 0])->first();
					if (!empty($order->meta_data)) {
						$this->putAppointmentDataApp($order, $response);
					}
					$meta_data = json_decode($order->meta_data, true);
					if ($meta_data['onCallStatus'] == "1") {
						updateWallet($order->order_by, 2, 'appt_reward');
						availWalletAmount($order->order_by, 6, @$meta_data['availWalletAmt']);
					}
					$url = url("/") . '/appointment/success?order_id=' . base64_encode($TXNID);
					return Redirect::to($url);
				} else {
					$order = AppointmentOrder::where(["id" => $response['ORDERID']])->first();
					$meta_data = json_decode($order->meta_data);
					if ($response['STATUS'] == 'PENDING') {
						if (!empty($meta_data->mobile_no)) {
							$message = urlencode("Dear " . $meta_data->patient_name . ", " . $response['RESPMSG'] . " \nThanks, Team Health Gennie");
							$this->sendSMS($meta_data->mobile_no, $message);
						}
					} else {
						if ($response['RESPCODE'] == '810' || $response['RESPCODE'] == '141') {
							AppointmentOrder::where(["id" => $order->id])->update([
								"order_status" => 2,
							]);
							if (!empty($meta_data->mobile_no)) {
								if ($meta_data->onCallStatus == "1") {
									$message = urlencode("Dear " . ucfirst($meta_data->patient_name) . ", Please make the payment to confirm the tele consultation appointment with Dr. " . $meta_data->doc_name . ".\nThanks Team Health Gennie.");
									$this->sendSMS($meta_data->mobile_no, $message, '1707161588002047180');
								} else {
									$message = urlencode("Dear " . ucfirst($meta_data->patient_name) . ", Please make the payment to confirm the in clinic consultation appointment with Dr. " . $meta_data->doc_name . ".\nThanks Team Health Gennie.");
									$this->sendSMS($meta_data->mobile_no, $message);
								}
							}
						} else {
							AppointmentOrder::where(["id" => $order->id])->update([
								"order_status" => 3,
							]);
							if (!empty($meta_data->mobile_no)) {
								$message = urlencode($response['RESPMSG'] . " \nThanks, Team Health Gennie");
								$this->sendSMS($meta_data->mobile_no, $message);
							}
						}
					}
					ApptLink::where(['order_id' => $order->id])->update(['status' => 2]);
					$url = url("/") . '/appointment/cancel?order_id=' . base64_encode($TXNID);
					return Redirect::to($url);
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function addApptForPlan($patientInfo)
	{
		try {
			$user_array['order_by']   = $patientInfo['order_by'];
			$user_array['doc_id']   =  getSetting("direct_appt_doc_id")[0];
			$docData = Doctors::select(["user_id", "consultation_fees", "oncall_fee", "slot_duration", "first_name", "last_name"])->where(['id' => $user_array['doc_id']])->first();
			$user_array['doc_name']   = $docData->first_name . " " . $docData->last_name;
			$user_array['p_id']   = $patientInfo['p_id'];
			$user_array['visit_type'] = 1;
			$user_array['blood_group'] = NULL;
			$user_array['consultation_fees'] = getSetting("direct_tele_appt_fee")[0];
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
			$user_array['dob'] = get_patient_dobByAge($patientInfo['dob'], $patientInfo['dob_type']);
			$user_array['mobile_no'] = $patientInfo['mobile_no'];
			$user_array['other_mobile_no'] = $patientInfo['other_mobile_no'];
			$user_array['otherPatient'] = $patientInfo['otherPatient'];
			$user_array['coupon_id'] = $patientInfo['coupon_id'];
			$user_array['coupon_discount'] = $patientInfo['coupon_discount'];
			$user_array['call_type'] = 1;
			$user_array['referral_code'] = null;
			$user_array['is_peak'] = 0;
			$user_array['finalConsultaionFee'] = $user_array['consultation_fees'];
			$charge =  0;
			$tax =  0;
			$gst =  0;
			$service_charge_meta = 	["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];

			$order = AppointmentOrder::create([
				'type'	 => 0,
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
			$this->putAppointmentDataApp($order, '', '');
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function walletCashbackSubs($meta_data, $orderID, $planId)
	{
		// if($planId == 7 || $planId == 8){
		// $amount = 50;
		// }else{

		// }
		try {
			$amount = getSetting("referral_amount")[0];
			$user = User::where('id', $meta_data['user_id'])->first();
			$order_id = time();
			$mobile = $meta_data['coupon_code'];
			$paytmParams = array();
			$paytmParams["subwalletGuid"]      = "77128966-fb92-4fc3-a27f-52474285d3fa";
			$paytmParams["orderId"]            = $order_id;
			$paytmParams["beneficiaryPhoneNo"] = $mobile;
			$paytmParams["amount"]             = $amount;
			$paytmParams["maxQueueDays"]       = 0;
			$paytmParams["disburseToNewUser"]  = false;
			$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
			$checksum = PaytmChecksum::generateSignature($post_data, "OJ0vuq8N&t3aAR7y");
			$x_mid      = "FITKID61350170158252";
			$x_checksum = $checksum;
			$url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/wallet/gratification";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$response = json_decode($response, true);
			$cashBack = ReferralCashback::create([
				'order_id' => $order_id,
				'referral_id' => $meta_data['referral_user_id'],
				'referred_id' => $meta_data['user_id'],
				'meta_data' =>  json_encode($response),
				'paytm_status' => $response["statusCode"],
			]);
			UsersSubscriptions::where(["id" => $orderID])->update([
				'coupon_id' => $cashBack->id,
			]);
			sleep(5);
			$this->walletPaytmDisburseStatusSubs($order_id);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function walletPaytmDisburseStatusSubs($order_id)
	{

		try {

			$paytmParams = array();
			$paytmParams["orderId"] = $order_id;
			$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
			$checksum = PaytmChecksum::generateSignature($post_data, "OJ0vuq8N&t3aAR7y");
			$x_mid      = "FITKID61350170158252";
			$x_checksum = $checksum;
			$url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/query";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response = curl_exec($ch);
			$response = json_decode($response, true);
			$wStatus = 0;
			if ($response['status'] == "SUCCESS") {
				$wStatus = 1;
			}
			ReferralCashback::where('order_id', $order_id)->update([
				'meta_data' =>  json_encode($response),
				'status' =>  $wStatus,
				'paytm_status' => $response["statusCode"],
			]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function paytmResponseMIniAppPlan(Request $request)
	{
		try {
			$response = json_decode($request->data, true);
			//pr();die;
			CcavenueResponse::create([
				'slug' => 'user_subscription_paytm',
				'meta_data' => json_encode($response),
			]);
			$orderData = UsersSubscriptions::select(["id", "meta_data"])->where(["order_id" => $response['ORDERID']])->first();
			$orderID = $orderData->id;
			UserSubscriptionsTxn::create([
				'subscription_id' => $orderID,
				'tracking_id' => @$response['TXNID'],
				'bank_ref_no' => @$response['BANKTXNID'],
				'tran_mode' => @$response['PAYMENTMODE'],
				'card_name' => @$response['BANKNAME'],
				'currency' => @$response['CURRENCY'],
				'payed_amount' => @$response['TXNAMOUNT'],
				'tran_status' => @$response['STATUS'],
				'trans_date' => @$response['TXNDATE']
			]);
			if ($response['STATUS'] == 'TXN_SUCCESS') {
				$meta_data = json_decode($orderData->meta_data, true);

				$plan = userPlan::where('id', $meta_data['plan_id'])->first();
				$subscribedPlan = new UserSubscribedPlans;
				$subscribedPlan->subscription_id = $orderID;
				$subscribedPlan->plan_id = $plan->id;
				$subscribedPlan->plan_price = $plan->price;
				$subscribedPlan->discount_price = $plan->discount_price;
				$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
				$subscribedPlan->plan_duration = $plan->plan_duration;
				$subscribedPlan->appointment_cnt = $plan->appointment_cnt;
				$subscribedPlan->lab_pkg = $plan->lab_pkg;
				$subscribedPlan->meta_data = json_encode($plan);
				$subscribedPlan->save();

				//for the plan trail period
				$duration_type = $plan->plan_duration_type;
				if ($duration_type == "d") {
					$duration_in_days = $plan->plan_duration;
				} elseif ($duration_type == "m") {
					$duration_in_days = (30 * $plan->plan_duration);
				} elseif ($duration_type == "y") {
					$duration_in_days = (366 * $plan->plan_duration);
				}
				$end_date = date('Y-m-d', strtotime($subscribedPlan->created_at . '+' . $duration_in_days . ' days'));
				$PlanPeriods =  PlanPeriods::create([
					'subscription_id' => $orderID,
					'subscribed_plan_id' => $subscribedPlan->id,
					'user_plan_id' => $meta_data['plan_id'],
					'user_id' => $meta_data['user_id'],
					'start_trail' => date('Y-m-d'),
					'end_trail' => $end_date,
					'remaining_appointment' => $plan->appointment_cnt,
					'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
					'lab_pkg_remaining' => 0,
					'status' => 1
				]);
				UsersSubscriptions::where(["id" => $orderID])->update([
					'order_status' => 1,
				]);
				if (checkRefCodeIsExist($meta_data['coupon_code']) == false) {
					$this->walletCashbackSubs($meta_data, $orderID, $meta_data['plan_id']);
				}
				$this->sendUserSubscriptionMail($orderID, 1, "success");
				$url = url("/") . '/plan/success?order_id=' . base64_encode(@$response['TXNID']);
				// return \Redirect::to($url);
				return $url;
			} else {
				return url("/") . '/plan/cancel?order_id=' . base64_encode($TXNID);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function paytmResponseMIniApp(Request $request)
	{

		try {

			//$response = Indipay::gateway('Paytm')->response($request);
			$response = json_decode($request->data, true);
			//pr();die;
			CcavenueResponse::create([
				'slug' => 'paytm_appointment',
				'meta_data' => json_encode($response),
			]);
			$TXNID = @$response['TXNID'];
			if ($response['STATUS'] == 'TXN_SUCCESS') {
				$order = AppointmentOrder::where(["id" => $response['ORDERID'], 'order_status' => 0])->first();
				if (!empty($order->meta_data)) {
					$this->putAppointmentDataApp($order, $response);
				}
				//return \Redirect::to('https://www.healthgennie.com/appointment/success?order_id='.base64_encode($TXNID));
				return 'https://www.healthgennie.com/appointment/success?order_id=' . base64_encode($TXNID);
			} else {
				$order = AppointmentOrder::where(["id" => $response['ORDERID']])->first();
				$meta_data = json_decode($order->meta_data);
				if ($response['STATUS'] == 'PENDING') {
					if (!empty($meta_data->mobile_no)) {
						$message = urlencode("Dear " . $meta_data->patient_name . ", " . $response['RESPMSG'] . " \nThanks, Team Health Gennie");
						$this->sendSMS($meta_data->mobile_no, $message);
					}
				} else {
					if ($response['RESPCODE'] == '810' || $response['RESPCODE'] == '141') {
						AppointmentOrder::where(["id" => $order->id])->update([
							"order_status" => 2,
						]);
						if (!empty($meta_data->mobile_no)) {
							$message = urlencode("Dear " . ucfirst($meta_data->patient_name) . ", Please make the payment to confirm the tele consultation appointment with Dr. " . $meta_data->doc_name . ".\nThanks Team Health Gennie.");
							$this->sendSMS($meta_data->mobile_no, $message, '1707161588002047180');
						}
					} else {
						AppointmentOrder::where(["id" => $order->id])->update([
							"order_status" => 3,
						]);
						if (!empty($meta_data->mobile_no)) {
							$message = urlencode($response['RESPMSG'] . " \nThanks, Team Health Gennie");
							$this->sendSMS($meta_data->mobile_no, $message);
						}
					}
				}
				return 'https://www.healthgennie.com/appointment/cancel?order_id=' . base64_encode($TXNID);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function putAppointmentDataStudent($order, $response = null, $campRes = null)
	{

		Log::info('order===============', [$order]);
		Log::info('response===============', [$response]);


		$meta_data = json_decode($order->meta_data, true);


		$docData = Doctors::where(['id' => $meta_data['doc_id']])->first();
		$increment_time = $meta_data['slot_duration'] * 60;
		$date = date("Y-m-d", strtotime($meta_data['appointment_date']));
		$time = date("H:i:s", strtotime($meta_data['time']));
		$start_date = date("Y-m-d H:i:s", strtotime($date . " " . $time));
		$end_date = date('Y-m-d H:i:s', strtotime($date . " " . $time) + $increment_time);

		$patient =  User::where(['id' => $meta_data['p_id']])->first();
		$Students =  Student::where(['student_id' => $meta_data['student_id']])->first();
		$practice =  RoleUser::select(['user_id', 'role_id', 'practice_id'])->where(['user_id' => $docData->user_id])->first();

		$patient_exists = "";
		if ($meta_data['otherPatient'] == 0) {
			if ($patient->parent_id == 0) {
				$patient_exists = Patients::where(['id' => $patient->pId, 'parent_id' => 0])->first();
			} else {
				$patient_exists = Patients::where(['id' => $patient->pId])->first();
			}
		}
		$first_name = trim(strtok($meta_data['patient_name'], ' '));
		$last_name = trim(strstr($meta_data['patient_name'], ' '));
		$type = null;
		$check_in = null;
		$current_status = 1;
		if ($meta_data['otherPatient'] == 0 && !empty($patient_exists)) {

			$practices_id = [];
			array_push($practices_id, $practice->practice_id);
			$practices = $patient_exists->practices_id;
			if (!empty($practices)) {
				$practices_id = explode(',', $practices);
				if (!in_array($practice->practice_id, $practices_id)) {
					array_push($practices_id, $practice->practice_id);
				}
			}
			$reg_exist = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'pid' => $patient_exists->id])->first();

			if (!empty($reg_exist)) {
				$reg_no = $reg_exist->reg_no;
			} else {
				$last_reg_no = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'status' => 1])->max('reg_no');
				$reg_no = 1;
				if (!empty($last_reg_no)) {
					$reg_no = $last_reg_no + 1;
				}
				$patient_registration_number = PatientRagistrationNumbers::create([
					'pid' => $patient_exists->id,
					'reg_no' =>  $reg_no,
					'status' =>  1,
					'added_by' => $practice->practice_id,
				]);
			}
			$pnumber = $patient_exists->patient_number;
			if (empty($pnumber)) {
				$pnumber =  getUniqueIdPatient('P');
				$write_file = @file_get_contents(getEhrUrl() . "/fileWriteByUrl?p_num=" . $pnumber . "&practice_id=" . $practice->practice_id);
			}


			Patients::where('id', $patient_exists->id)->update(array(
				'practices_id' => implode(',', $practices_id),
				'added_by' => $practice->practice_id,
				'patient_number' => $pnumber,
				'first_name' =>  $first_name,
				'last_name' =>  $last_name,
				'dob' => strtotime($meta_data['dob']),
				'gender' =>  $meta_data['gender'],
				'student_id' =>  @$meta_data['student_id'],
				'org_id' => @$meta_data['org_id']
			));

			// 
			$patient_number1 = User::where('pId', $patient_exists->id)->where('patient_number', $pnumber)->first();
			$patient_number2 = User::where('patient_number', $pnumber)->get();


			if ($patient_number1) {
				User::where('pId', $patient_exists->id)->update(array(
					'practices_id' => implode(',', $practices_id),
					'added_by' => $practice->practice_id,
					'patient_number' => $pnumber,
					'reg_no' => $reg_no,
					'first_name' =>  $first_name,
					'last_name' =>  @$last_name,
					'dob' => strtotime($meta_data['dob']),
					'profile_status' => 1,
					'gender' =>  $meta_data['gender'],
					'other_mobile_no' => @$meta_data['other_mobile_no'],
				));
			} else {
				User::where('pId', $patient_exists->id)->update(array(
					'practices_id' => implode(',', $practices_id),
					'added_by' => $practice->practice_id,
					'reg_no' => $reg_no,
					'first_name' =>  $first_name,
					'last_name' =>  @$last_name,
					'dob' => strtotime($meta_data['dob']),
					'profile_status' => 1,
					'gender' =>  $meta_data['gender'],
					'other_mobile_no' => @$meta_data['other_mobile_no'],
				));
			}



			$consultation_fees = $docData->consultation_fees;
			if ($meta_data['onCallStatus'] == "1") {
				$type = 3;
				$check_in = strtotime(date("Y-m-d H:i:s"));
				$current_status = 2;
				if (isset($meta_data['isDirectAppt']) && $meta_data['isDirectAppt'] == '1') {
					$consultation_fees = getSetting("direct_tele_appt_fee")[0];
				} else {
					$consultation_fees = $docData->oncall_fee;
				}
			}
			if (isset($meta_data['finalConsultaionFee']) && !empty($meta_data['finalConsultaionFee'])) {
				$consultation_fees = $meta_data['finalConsultaionFee'];
			}
			$app_click_status = 5;
			if ($order->order_from == 0) {
				$app_click_status = 6;
			}
			$visit_type = 1;
			if (isset($meta_data['isfollowup']) && !empty($meta_data['isfollowup'])) {
				$visit_type = 6;
			}

			$appointment = Appointments::create([
				'doc_id' =>  $docData->user_id,
				'pId' =>   $patient_exists->id,
				'visit_type' =>  $visit_type,
				'type' =>  $type,
				'blood_group' =>  $meta_data['blood_group'],
				'consultation_fees' =>  $consultation_fees,
				'start' =>  $start_date,
				'end' =>    $end_date,
				'status' =>  1,
				'billable_status' =>  1,
				'delete_status' =>  1,
				'appointment_confirmation' => 1,
				'check_in' => $check_in,
				'current_status' => $current_status,
				// 'call_type' => $meta_data['call_type'],
				'app_click_status' =>  $app_click_status,
				'added_by' => $practice->practice_id
			]);
			AppointmentOrder::where(["id" => $order->id])->update([
				"order_status" => 1,
				'patient_id' => $patient_exists->id,
				'appointment_id' =>  $appointment->id,
			]);
		} else if ($meta_data['otherPatient'] == 0 && empty($patient_exists)) {

			

			$pnumber =  getUniqueIdPatient('P');
			$ehr_patient = Patients::create([
				'patient_number' => $pnumber,
				'first_name' =>  $first_name,
				'last_name' =>  $last_name,
				'dob' => strtotime($meta_data['dob']),
				'gender' =>  $meta_data['gender'],
				'student_id' =>  $meta_data['student_id'],
				'org_id' => $meta_data['org_id'],
				'email' =>  $patient->email,
				'country_id' =>  $patient->country_id,
				'state_id' =>  $patient->state_id,
				'city_id' =>  $patient->city_id,
				'address' =>  $patient->address,
				'zipcode' =>  $patient->zipcode,
				'aadhar_no' =>  $patient->aadhar_no,
				'image' => $patient->image,
				'status' =>  1,
				'added_by' => $practice->practice_id,
				'practices_id' => $practice->practice_id,
				'parent_id' => 0,
			]);
			$reg_exist = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'pid' => $ehr_patient->id])->first();
			if (!empty($reg_exist)) {
				$reg_no = $reg_exist->reg_no;
			} else {
				$last_reg_no = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'status' => 1])->max('reg_no');
				$reg_no = 1;
				if (!empty($last_reg_no)) {
					$reg_no = $last_reg_no + 1;
				}
				$patient_registration_number = PatientRagistrationNumbers::create([
					'pid' => $ehr_patient->id,
					'reg_no' =>  $reg_no,
					'status' =>  1,
					'added_by' => $practice->practice_id,
				]);
			}
			User::where('id', $meta_data['p_id'])->update([
				'patient_number' => $pnumber,
				'first_name' =>  $first_name,
				'last_name' =>  $last_name,
				'dob' => strtotime($meta_data['dob']),
				'gender' =>  $meta_data['gender'],
				'pId' => $ehr_patient->id,
				'reg_no' => $reg_no,
				'added_by' => $practice->practice_id,
				'profile_status' => 1,
				'practices_id' => $practice->practice_id,
				
			]);
			$write_file = @file_get_contents(getEhrUrl() . "/fileWriteByUrl?p_num=" . $pnumber . "&practice_id=" . $practice->practice_id);

			$consultation_fees = $docData->consultation_fees;
			if ($meta_data['onCallStatus'] == "1") {
				$type = 3;
				$check_in = strtotime(date("Y-m-d H:i:s"));
				$current_status = 2;
				if (isset($meta_data['isDirectAppt']) && $meta_data['isDirectAppt'] == '1') {
					$consultation_fees = getSetting("direct_tele_appt_fee")[0];
				} else {
					$consultation_fees = $docData->oncall_fee;
				}
			}
			if (isset($meta_data['finalConsultaionFee']) && !empty($meta_data['finalConsultaionFee'])) {
				$consultation_fees = $meta_data['finalConsultaionFee'];
			}
			$app_click_status = 5;
			if ($order->order_from == 0) {
				$app_click_status = 6;
			}
			$visit_type = 1;
			if (isset($meta_data['isfollowup']) && !empty($meta_data['isfollowup'])) {
				$visit_type = 6;
			}
			$appointment = Appointments::create([
				'doc_id' =>  $docData->user_id,
				'pId' =>   $ehr_patient->id,
				'visit_type' =>  $visit_type,
				'type' =>  $type,
				'blood_group' =>  $meta_data['blood_group'],
				'consultation_fees' =>  $consultation_fees,
				'start' =>  $start_date,
				'end' =>    $end_date,
				'status' =>  1,
				'billable_status' =>  1,
				'delete_status' =>  1,
				'appointment_confirmation' =>  1,
				'check_in' => $check_in,
				'current_status' => $current_status,
				// 'call_type' => $meta_data['call_type'],
				'app_click_status' =>  $app_click_status,
				'added_by' => $practice->practice_id
			]);
			AppointmentOrder::where(["id" => $order->id])->update([
				"order_status" => 1,
				'patient_id' => $ehr_patient->id,
				'appointment_id' =>  $appointment->id,
			]);
		} 
		
		if (!empty($response)) {
			Log::info('ffffffffffffffffffff', [$response]);
			Log::info('appppppppppppppppppp', [$appointment]);
			Log::info('apppppppXX', [$order]);

			if (isset($response['acquirer_data']['upi_transaction_id']) && $order->id) {
				$APPOINT = AppointmentTxn::create([
					'order_id' => $order->id,
					'appointment_id' => @$appointment->id,
					'tracking_id' => @$response['acquirer_data']['upi_transaction_id'],
					'bank_ref_no' => @$response['acquirer_data']['bank_transaction_id'],
					'tran_mode' => @$response['method'],
					'card_name' => @$response['bank'],
					'currency' => @$response['currency'],
					'payed_amount' => @$response['amount'],
					'tran_status' => @$response['status'],
					'trans_date' => @$response['created_at']
				]);
			} else {
				$APPOINT = AppointmentTxn::create([
					'order_id' => $order->id,
					'appointment_id' => @$appointment->id,
					'tracking_id' => @$order->razorpay_order_id,
					'bank_ref_no' => '',
					'tran_mode' => @$response['method'] ?? '',
					'card_name' => @$response['bank'] ?? '',
					'currency' => @$response['currency'],
					'payed_amount' => @$response['amount'] / 100,
					'tran_status' => 'success',
					'trans_date' => @$response['created_at']
				]);
			}
		} else if (!empty($campRes)) {
			$tran_mode = "online";
			if ($order->type == "0") {
				$tran_mode = "free";
			} else if ($order->type == "2") {
				$tran_mode = "cash";
			}

			AppointmentTxn::create([
				'order_id' => $order->id,
				'tracking_id' => (isset($meta_data['tracking_id']) && $meta_data['tracking_id'] != "") ? @$meta_data['tracking_id'] : @$campRes['tracking_id'],
				'appointment_id' => $appointment->id,
				'bank_ref_no' => @$campRes['order_bank_ref_no'],
				'tran_mode' => $tran_mode,
				'currency' => 'INR',
				'payed_amount' => $order->order_total,
				'tran_status' => 'success',
				'trans_date' => date('Y-m-d H:i:s'),
				'received_by' => @$meta_data['receivedBy']
			]);
		}
		$dt = date('Y-m-d');
		$plan_data =  PlanPeriods::whereDate('start_trail', '<=', $dt)->whereDate('end_trail', '>=', $dt)->where(['user_id' => $meta_data['order_by'], 'status' => 1])->where('remaining_appointment', '>', 0)->first();
		if ($visit_type == 1 && !empty($appointment->id) && !empty($plan_data) && $meta_data['isDirectAppt'] == 1) {
			if (!empty($plan_data->appointment_ids)) {
				$appointment_ids = explode(",", $plan_data->appointment_ids);
				array_push($appointment_ids, $appointment->id);
				$appointment_ids =  implode(',', $appointment_ids);
			} else {
				$appointment_ids = $appointment->id;
			}
			$remaining_appointment_count =  $plan_data->remaining_appointment;
			PlanPeriods::where('id', $plan_data->id)->update(array('remaining_appointment' => ($remaining_appointment_count - 1), 'appointment_ids' => $appointment_ids));
		}
		ApptLink::where(["user_id" => $meta_data['p_id'], 'order_id' => $order->id])->update(['status' => 1]);
		$this->sendUserAppointmentMail($appointment->id, $order->order_from, $type, $meta_data, $order->type);
		return $appointment->id;
	}

	public function putAppointmentDataApp($order, $response = null, $campRes = null)
	{

		Log::info('order===============', [$order]);	
		Log::info('response===============', [$response]);	
		

		 $meta_data = json_decode($order->meta_data, true);
         
	

		$docData = Doctors::where(['id' => $meta_data['doc_id']])->first();
		$increment_time = $meta_data['slot_duration'] * 60;
		$date = date("Y-m-d", strtotime($meta_data['appointment_date']));
		$time = date("H:i:s", strtotime($meta_data['time']));
		$start_date = date("Y-m-d H:i:s", strtotime($date . " " . $time));
		$end_date = date('Y-m-d H:i:s', strtotime($date . " " . $time) + $increment_time);

		$patient =  User::where(['id' => $meta_data['p_id']])->first();
		$practice =  RoleUser::select(['user_id', 'role_id', 'practice_id'])->where(['user_id' => $docData->user_id])->first();

		$patient_exists = "";
		if ($meta_data['otherPatient'] == 0) {
			if ($patient->parent_id == 0) {
				$patient_exists = Patients::where(['mobile_no' => $patient->mobile_no, 'parent_id' => 0])->first();
			} else {
				$patient_exists = Patients::where(['id' => $patient->pId])->first();
			}
		}
		$first_name = trim(strtok($meta_data['patient_name'], ' '));
		$last_name = trim(strstr($meta_data['patient_name'], ' '));
		$type = null;
		$check_in = null;
		$current_status = 1;
		if ($meta_data['otherPatient'] == 0 && !empty($patient_exists)) {

			$practices_id = [];
			array_push($practices_id, $practice->practice_id);
			$practices = $patient_exists->practices_id;
			if (!empty($practices)) {
				$practices_id = explode(',', $practices);
				if (!in_array($practice->practice_id, $practices_id)) {
					array_push($practices_id, $practice->practice_id);
				}
			}
			$reg_exist = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'pid' => $patient_exists->id])->first();

			if (!empty($reg_exist)) {
				$reg_no = $reg_exist->reg_no;
			} else {
				$last_reg_no = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'status' => 1])->max('reg_no');
				$reg_no = 1;
				if (!empty($last_reg_no)) {
					$reg_no = $last_reg_no + 1;
				}
				$patient_registration_number = PatientRagistrationNumbers::create([
					'pid' => $patient_exists->id,
					'reg_no' =>  $reg_no,
					'status' =>  1,
					'added_by' => $practice->practice_id,
				]);
			}
			$pnumber = $patient_exists->patient_number;
			if (empty($pnumber)) {
				$pnumber =  getUniqueIdPatient('P');
				$write_file = @file_get_contents(getEhrUrl() . "/fileWriteByUrl?p_num=" . $pnumber . "&practice_id=" . $practice->practice_id);
			}

$slug = Session::get('organizationMaster.slug');
				$org_id = null;
				if (Session::has('organizationMaster')) {
					$org_id = Session::get('organizationMaster.id'); // Assuming 'id' is stored
				}
			Patients::where('id', $patient_exists->id)->update(array(
				'practices_id' => implode(',', $practices_id),
				'added_by' => $practice->practice_id,
				'patient_number' => $pnumber,
				'first_name' =>  $first_name,
				'last_name' =>  $last_name,
				'dob' => strtotime($meta_data['dob']),
				'gender' =>  $meta_data['gender'],
				'other_mobile_no' =>  @$meta_data['other_mobile_no'],
				'org_id' => $org_id,
			));

			// 
			$patient_number1 = User::where('pId', $patient_exists->id)->where('patient_number', $pnumber)->first();
			$patient_number2 = User::where('patient_number', $pnumber)->get();


			if ($patient_number1) {
				User::where('pId', $patient_exists->id)->update(array(
					'practices_id' => implode(',', $practices_id),
					'added_by' => $practice->practice_id,
					'patient_number' => $pnumber,
					'reg_no' => $reg_no,
					'first_name' =>  $first_name,
					'last_name' =>  @$last_name,
					'dob' => strtotime($meta_data['dob']),
					'profile_status' => 1,
					'gender' =>  $meta_data['gender'],
					'other_mobile_no' => @$meta_data['other_mobile_no'],
				));
			} else {
				User::where('pId', $patient_exists->id)->update(array(
					'practices_id' => implode(',', $practices_id),
					'added_by' => $practice->practice_id,
					'reg_no' => $reg_no,
					'first_name' =>  $first_name,
					'last_name' =>  @$last_name,
					'dob' => strtotime($meta_data['dob']),
					'profile_status' => 1,
					'gender' =>  $meta_data['gender'],
					'other_mobile_no' => @$meta_data['other_mobile_no'],
				));
			}



			$consultation_fees = $docData->consultation_fees;
			if ($meta_data['onCallStatus'] == "1") {
				$type = 3;
				$check_in = strtotime(date("Y-m-d H:i:s"));
				$current_status = 2;
				if (isset($meta_data['isDirectAppt']) && $meta_data['isDirectAppt'] == '1') {
					$consultation_fees = getSetting("direct_tele_appt_fee")[0];
				} else {
					$consultation_fees = $docData->oncall_fee;
				}
			}
			if (isset($meta_data['finalConsultaionFee']) && !empty($meta_data['finalConsultaionFee'])) {
				$consultation_fees = $meta_data['finalConsultaionFee'];
			}
			$app_click_status = 5;
			if ($order->order_from == 0) {
				$app_click_status = 6;
			}
			$visit_type = 1;
			if (isset($meta_data['isfollowup']) && !empty($meta_data['isfollowup'])) {
				$visit_type = 6;
			}

			$appointment = Appointments::create([
				'doc_id' =>  $docData->user_id,
				'pId' =>   $patient_exists->id,
				'visit_type' =>  $visit_type,
				'type' =>  $type,
				'blood_group' =>  $meta_data['blood_group'],
				'consultation_fees' =>  $consultation_fees,
				'start' =>  $start_date,
				'end' =>    $end_date,
				'status' =>  1,
				'billable_status' =>  1,
				'delete_status' =>  1,
				'appointment_confirmation' => 1,
				'check_in' => $check_in,
				'current_status' => $current_status,
				// 'call_type' => $meta_data['call_type'],
				'app_click_status' =>  $app_click_status,
				'added_by' => $practice->practice_id
			]);
			AppointmentOrder::where(["id" => $order->id])->update([
				"order_status" => 1,
				'patient_id' => $patient_exists->id,
				'appointment_id' =>  $appointment->id,
			]);
		} else if ($meta_data['otherPatient'] == 0 && empty($patient_exists)) {



			$pnumber =  getUniqueIdPatient('P');
			$slug = Session::get('organizationMaster.slug');
			$org_id = null;
			if (Session::has('organizationMaster')) {
				$org_id = Session::get('organizationMaster.id'); // Assuming 'id' is stored
			}
			$ehr_patient = Patients::create([
				'patient_number' => $pnumber,
				'first_name' =>  $first_name,
				'last_name' =>  $last_name,
				'dob' => strtotime($meta_data['dob']),
				'gender' =>  $meta_data['gender'],
				'mobile_no' =>  $patient->mobile_no,
				'other_mobile_no' =>  @$meta_data['other_mobile_no'],
				'email' =>  $patient->email,
				'country_id' =>  $patient->country_id,
				'state_id' =>  $patient->state_id,
				'city_id' =>  $patient->city_id,
				'address' =>  $patient->address,
				'zipcode' =>  $patient->zipcode,
				'aadhar_no' =>  $patient->aadhar_no,
				'image' => $patient->image,
				'status' =>  1,
				'added_by' => $practice->practice_id,
				'practices_id' => $practice->practice_id,
				'parent_id' => 0,
				'org_id' => $org_id,
			]);
			$reg_exist = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'pid' => $ehr_patient->id])->first();
			if (!empty($reg_exist)) {
				$reg_no = $reg_exist->reg_no;
			} else {
				$last_reg_no = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'status' => 1])->max('reg_no');
				$reg_no = 1;
				if (!empty($last_reg_no)) {
					$reg_no = $last_reg_no + 1;
				}
				$patient_registration_number = PatientRagistrationNumbers::create([
					'pid' => $ehr_patient->id,
					'reg_no' =>  $reg_no,
					'status' =>  1,
					'added_by' => $practice->practice_id,
				]);
			}
			User::where('id', $meta_data['p_id'])->update([
				'patient_number' => $pnumber,
				'first_name' =>  $first_name,
				'last_name' =>  $last_name,
				'dob' => strtotime($meta_data['dob']),
				'gender' =>  $meta_data['gender'],
				'pId' => $ehr_patient->id,
				'reg_no' => $reg_no,
				'added_by' => $practice->practice_id,
				'profile_status' => 1,
				'practices_id' => $practice->practice_id,
				'other_mobile_no' =>  @$meta_data['other_mobile_no'],
			]);
			$write_file = @file_get_contents(getEhrUrl() . "/fileWriteByUrl?p_num=" . $pnumber . "&practice_id=" . $practice->practice_id);

			$consultation_fees = $docData->consultation_fees;
			if ($meta_data['onCallStatus'] == "1") {
				$type = 3;
				$check_in = strtotime(date("Y-m-d H:i:s"));
				$current_status = 2;
				if (isset($meta_data['isDirectAppt']) && $meta_data['isDirectAppt'] == '1') {
					$consultation_fees = getSetting("direct_tele_appt_fee")[0];
				} else {
					$consultation_fees = $docData->oncall_fee;
				}
			}
			if (isset($meta_data['finalConsultaionFee']) && !empty($meta_data['finalConsultaionFee'])) {
				$consultation_fees = $meta_data['finalConsultaionFee'];
			}
			$app_click_status = 5;
			if ($order->order_from == 0) {
				$app_click_status = 6;
			}
			$visit_type = 1;
			if (isset($meta_data['isfollowup']) && !empty($meta_data['isfollowup'])) {
				$visit_type = 6;
			}
			$appointment = Appointments::create([
				'doc_id' =>  $docData->user_id,
				'pId' =>   $ehr_patient->id,
				'visit_type' =>  $visit_type,
				'type' =>  $type,
				'blood_group' =>  $meta_data['blood_group'],
				'consultation_fees' =>  $consultation_fees,
				'start' =>  $start_date,
				'end' =>    $end_date,
				'status' =>  1,
				'billable_status' =>  1,
				'delete_status' =>  1,
				'appointment_confirmation' =>  1,
				'check_in' => $check_in,
				'current_status' => $current_status,
				// 'call_type' => $meta_data['call_type'],
				'app_click_status' =>  $app_click_status,
				'added_by' => $practice->practice_id
			]);
			AppointmentOrder::where(["id" => $order->id])->update([
				"order_status" => 1,
				'patient_id' => $ehr_patient->id,
				'appointment_id' =>  $appointment->id,
			]);
		} else if ($meta_data['otherPatient'] == 1) {


			$patient_exists = Patients::where(['mobile_no' => $patient->mobile_no, 'parent_id' => 0])->first();
			if (!empty($patient_exists)) {
				$parent_id	= $patient_exists->id;
			} else {
				$slug = Session::get('organizationMaster.slug');
				$org_id = null;
				if (Session::has('organizationMaster')) {
					$org_id = Session::get('organizationMaster.id'); // Assuming 'id' is stored
				}
				$parent_register = Patients::create([
					//'patient_number' => $pnumber,
					'first_name' =>  $patient->first_name,
					'last_name' =>  $patient->last_name,
					'dob' => $patient->dob,
					'gender' =>  $patient->gender,
					'mobile_no' =>  $patient->mobile_no,
					'email' =>  $patient->email,
					'country_id' =>  $patient->country_id,
					'state_id' =>  $patient->state_id,
					'city_id' =>  $patient->city_id,
					'address' =>  $patient->address,
					'zipcode' =>  $patient->zipcode,
					'aadhar_no' =>  $patient->aadhar_no,
					'image' => $patient->image,
					'status' =>  1,
					//'added_by' => $practice->practice_id,
					//'practices_id' => $practice->practice_id,
					'parent_id' => 0,
					'other_mobile_no' =>  @$meta_data['other_mobile_no'],
					'org_id' => $org_id,
				]);
				User::where('id', $meta_data['p_id'])->update([
					'pId' => $parent_register->id,
				]);
				$parent_id = $parent_register->id;
			}
			$pnumber =  getUniqueIdPatient('P');
			$slug = Session::get('organizationMaster.slug');
				$org_id = null;
				if (Session::has('organizationMaster')) {
					$org_id = Session::get('organizationMaster.id'); // Assuming 'id' is stored
				}
			$ehr_patient = Patients::create([
				'patient_number' => $pnumber,
				'first_name' =>  $first_name,
				'last_name' =>  $last_name,
				'dob' => strtotime($meta_data['dob']),
				'gender' =>  $meta_data['gender'],
				'mobile_no' =>  $patient->mobile_no,
				'country_id' =>  $patient->country_id,
				'state_id' =>  $patient->state_id,
				'city_id' =>  $patient->city_id,
				'address' =>  $patient->address,
				'zipcode' =>  $patient->zipcode,
				'status' =>  1,
				'added_by' => $practice->practice_id,
				'practices_id' => $practice->practice_id,
				'parent_id' => $parent_id,
				'other_mobile_no' =>  @$meta_data['other_mobile_no'],
				'org_id' => $org_id,
			]);
			$last_reg_no = PatientRagistrationNumbers::where(['added_by' => $practice->practice_id, 'status' => 1])->max('reg_no');
			$reg_no = 1;
			if (!empty($last_reg_no)) {
				$reg_no = $last_reg_no + 1;
			}
			$patient_registration_number = PatientRagistrationNumbers::create([
				'pid' => $ehr_patient->id,
				'reg_no' =>  $reg_no,
				'status' =>  1,
				'added_by' => $practice->practice_id,
			]);
			$login_type = 2;
			$user = User::select("login_type")->where('id', $meta_data['p_id'])->first();
			if (!empty($user)) {
				$login_type = $user->login_type;
			}
			$newUser = User::create([
				'pId' => $ehr_patient->id,
				'reg_no' => $reg_no,
				'patient_number' => $pnumber,
				'first_name' =>  $first_name,
				'last_name' =>  $last_name,
				'dob' => strtotime($meta_data['dob']),
				'gender' =>  $meta_data['gender'],
				'mobile_no' =>  $patient->mobile_no,
				'country_id' =>  $patient->country_id,
				'state_id' =>  $patient->state_id,
				'city_id' =>  $patient->city_id,
				'address' =>  $patient->address,
				'zipcode' =>  $patient->zipcode,
				'status' =>  1,
				'profile_status' =>  1,
				'login_type' =>  $login_type,
				'device_type' =>  $patient->device_type,
				'added_by' => $practice->practice_id,
				'practices_id' => $practice->practice_id,
				'parent_id' => $parent_id,
				'other_mobile_no' =>  @$meta_data['other_mobile_no'],
			]);
			createUsersReferralCode($newUser->id);
			$write_file = @file_get_contents(getEhrUrl() . "/fileWriteByUrl?p_num=" . $pnumber . "&practice_id=" . $practice->practice_id);
			$consultation_fees = $docData->consultation_fees;
			if ($meta_data['onCallStatus'] == "1") {
				$type = 3;
				$check_in = strtotime(date("Y-m-d H:i:s"));
				$current_status = 2;
				if (isset($meta_data['isDirectAppt']) && $meta_data['isDirectAppt'] == '1') {
					$consultation_fees = getSetting("direct_tele_appt_fee")[0];
				} else {
					$consultation_fees = $docData->oncall_fee;
				}
			}
			if (isset($meta_data['finalConsultaionFee']) && !empty($meta_data['finalConsultaionFee'])) {
				$consultation_fees = $meta_data['finalConsultaionFee'];
			}
			$app_click_status = 5;
			if ($order->order_from == 0) {
				$app_click_status = 6;
			}
			$visit_type = 1;
			if (isset($meta_data['isfollowup']) && !empty($meta_data['isfollowup'])) {
				$visit_type = 6;
			}
			$appointment = Appointments::create([
				'doc_id' =>  $docData->user_id,
				'pId' =>   $ehr_patient->id,
				'visit_type' =>  $visit_type,
				'type' =>  $type,
				'blood_group' =>  $meta_data['blood_group'],
				'consultation_fees' =>  $consultation_fees,
				'start' =>  $start_date,
				'end' =>    $end_date,
				'status' =>  1,
				'billable_status' =>  1,
				'delete_status' =>  1,
				'appointment_confirmation' =>  1,
				'check_in' => $check_in,
				'current_status' => $current_status,
				// 'call_type' => $meta_data['call_type'],
				'app_click_status' =>  $app_click_status,
				'added_by' => $practice->practice_id
			]);
			AppointmentOrder::where(["id" => $order->id])->update([
				"order_status" => 1,
				'patient_id' => $ehr_patient->id,
				'appointment_id' =>  $appointment->id,
			]);
		}
		if (!empty($response)) {
			Log::info('ffffffffffffffffffff', [$response]);
			Log::info('appppppppppppppppppp', [$appointment]);
			Log::info('apppppppXX', [$order]);

			if (isset($response['acquirer_data']['upi_transaction_id']) && $order->id) {
				$APPOINT = AppointmentTxn::create([
					'order_id' => $order->id,
					'appointment_id' => @$appointment->id,
					'tracking_id' => @$response['acquirer_data']['upi_transaction_id'] ,
					'bank_ref_no' => @$response['acquirer_data']['bank_transaction_id'],
					'tran_mode' => @$response['method'],
					'card_name' => @$response['bank'],
					'currency' => @$response['currency'],
					'payed_amount' => @$response['amount'],
					'tran_status' => @$response['status'],
					'trans_date' => @$response['created_at']
				]);
			}else{
				$APPOINT = AppointmentTxn::create([
					'order_id' => $order->id,
					'appointment_id' => @$appointment->id,
					'tracking_id' => @$meta_data['razorpay_order_id'],
					'bank_ref_no' => '',
					'tran_mode' => @$response['method'] ?? '',
					'card_name' => @$response['bank'] ?? '',
					'currency' => @$response['currency'],
					'payed_amount' => @$response['amount'] / 100,
					'tran_status' =>'sucess',
					'trans_date' => @$response['created_at']
				]);
			}

		
			
		} else if (!empty($campRes)) {
			$tran_mode = "online";
			if ($order->type == "0") {
				$tran_mode = "free";
			} else if ($order->type == "2") {
				$tran_mode = "cash";
			}

			AppointmentTxn::create([
				'order_id' => $order->id,
				'tracking_id' => (isset($meta_data['tracking_id']) && $meta_data['tracking_id'] != "") ? @$meta_data['tracking_id'] : @$campRes['tracking_id'],
				'appointment_id' => $appointment->id,
				'bank_ref_no' => @$campRes['order_bank_ref_no'],
				'tran_mode' => $tran_mode,
				'currency' => 'INR',
				'payed_amount' => $order->order_total,
				'tran_status' => 'success',
				'trans_date' => date('Y-m-d H:i:s'),
				'received_by' => @$meta_data['receivedBy']
			]);
		}
		$dt = date('Y-m-d');
		$plan_data =  PlanPeriods::whereDate('start_trail', '<=', $dt)->whereDate('end_trail', '>=', $dt)->where(['user_id' => $meta_data['order_by'], 'status' => 1])->where('remaining_appointment', '>', 0)->first();
		if ($visit_type == 1 && !empty($appointment->id) && !empty($plan_data) && $meta_data['isDirectAppt'] == 1) {
			if (!empty($plan_data->appointment_ids)) {
				$appointment_ids = explode(",", $plan_data->appointment_ids);
				array_push($appointment_ids, $appointment->id);
				$appointment_ids =  implode(',', $appointment_ids);
			} else {
				$appointment_ids = $appointment->id;
			}
			$remaining_appointment_count =  $plan_data->remaining_appointment;
			PlanPeriods::where('id', $plan_data->id)->update(array('remaining_appointment' => ($remaining_appointment_count - 1), 'appointment_ids' => $appointment_ids));
		}
		ApptLink::where(["user_id" => $meta_data['p_id'], 'order_id' => $order->id])->update(['status' => 1]);
		$this->sendUserAppointmentMail($appointment->id, $order->order_from, $type, $meta_data, $order->type);
		return $appointment->id;
	}

	public function paymentcancel(Request $request)
	{


		try {
			$response = Indipay::gateway('CCAvenue')->response($request);
			CcavenueResponse::create([
				'slug' => $response['merchant_param1'],
				'meta_data' => json_encode($response),
			]);
			if ($response['merchant_param1'] == "HealthGennie Lab Order") {
				$lab = LabOrders::select(["id", "ref_orderId"])->where(["orderId" => $response['order_id']])->first();
				if (!empty($lab)) {
					$ch_app = curl_init();
					curl_setopt($ch_app, CURLOPT_URL, "https://www.thyrocare.com/APIS/ORDER.svc/cancelledorder");
					curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch_app, CURLOPT_POST, true);
					$order_array = array(
						'OrderNo' => $lab->ref_orderId,
						'VisitId' => $lab->ref_orderId,
						'Status' => 2,
					);
					$order_data = json_encode($order_array);
					curl_setopt($ch_app, CURLOPT_POSTFIELDS, $order_data);
					curl_setopt($ch_app, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
					curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, true);
					$app_output = curl_exec($ch_app);
					curl_close($ch_app);
					LabOrders::where(["id" => $lab->id])->update([
						"order_status" => "CANCELLED",
						'Status' => 2,
					]);
				}
				return Redirect::to('https://www.healthgennie.com/lab-order/cancel?order_id=' . base64_encode($response['order_id']));
			} else if ($response['merchant_param3'] == "Gennie Plan") {
				UsersSubscriptions::where(["id" => $response['order_id']])->update([
					'order_status' => 2,
				]);
				return Redirect::to('https://www.healthgennie.com/plan/cancel?order_id=' . base64_encode($response['order_id']));
			} else if ($response['merchant_param1'] == "Health Gennie Appointment") {
				$order = AppointmentOrder::where(["id" => $response['order_id']])->first();
				AppointmentOrder::where(["id" => $response['order_id']])->update([
					"order_status" => 2,
				]);
				if (!empty($order->meta_data)) {
					if ($order->order_from == "1") {
						$meta_data = json_decode($order->meta_data, true);
						$patient =  User::where(['id' => $meta_data['p_id']])->first();
						$docName = $meta_data['doc_name'];
						if (!empty($patient->mobile_no)) {
							$message = urlencode("Dear " . ucfirst($patient->first_name) . " " . $patient->last_name . ", Please make the payment to confirm the tele consultation appointment with Dr " . $docName . ".\nThanks Team Health Gennie.");
							$this->sendSMS($patient->mobile_no, $message, '1707161588002047180');
						}
					} else {
						$meta_data = json_decode($order->meta_data, true);
						$docData = Doctors::select(["first_name", "last_name"])->where(['id' => $meta_data['doctor']])->first();
						if (!empty($meta_data['mobile_no'])) {
							$message = urlencode("Dear " . ucfirst($meta_data['first_name']) . " " . $meta_data['last_name'] . ", Please make the payment to confirm the tele consultation appointment with Dr " . $docData->first_name . " " . $docData->last_name . ".\nThanks Team Health Gennie.");
							$this->sendSMS($meta_data['mobile_no'], $message, '1707161588002047180');
						}
					}
				}
				return Redirect::to('https://www.healthgennie.com/appointment/cancel?order_id=' . base64_encode($response['order_id']));
			} else {
				PracticesSubscriptions::where('id', $response['order_id'])->update(array(
					'order_status' => 2,
				));
			}
			return redirect()->route('successSubscripton');
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function paymentcancelMiniProgram(Request $request)
	{

		try {
			AppointmentOrder::where(["id" => $request->id])->update([
				"order_status" => 2
			]);
			return 1;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function paymentcancelMiniProgramPlan(Request $request)
	{

		try {

			UsersSubscriptions::where(["id" => $request->id])->update([
				"order_status" => 2
			]);
			return 1;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function sendUserAppointmentMail($appointment_id, $from, $type, $meta_data = null, $orderType = 0)
	{

		try {

			if ($this->is_connected() == 1) {
				if ($type == 3) {
					$appointment =  Appointments::where('id', $appointment_id)->first();
					$consultation_fees = $appointment->consultation_fees;
					$fees_type = "";
					if ($appointment->AppointmentOrder->type == '0') {
						$consultation_fees = '<strike>' . getSetting("tele_main_price")[0] . '</strike>';
						$fees_type = "FREE";
					}
					$docData = Doctors::where(['user_id' => $appointment->doc_id])->first();
					$docData["appointment_type"] = $appointment->type;
					$docName = "Dr. " . ucfirst($docData->first_name) . " " . $docData->last_name;
					$patientname = $appointment->patient->first_name . ' ' . $appointment->patient->last_name;
					$appointDate = date('d-m-Y', strtotime($appointment->start));
					$appointtime = date('h:i A', strtotime($appointment->start));

					if (!empty($docData->mobile_no)) {
						$message = urlencode("Dear " . $docName . ", " . $patientname . " has booked a tele consultation with you on " . $appointDate . " and " . $appointtime . " Patient contact number is " . $appointment->Patient->mobile_no . " please call the patient at the appointment time and send the digital prescription from Health Gennie app Thanks Team Health Gennie");
						$this->sendSMS($docData->mobile_no, $message, '1707161735128760937');
					}


					if (!empty($appointment->Patient->mobile_no)) {

						if ($orderType == 0) {
							if ($from == 0 || $meta_data['doc_id'] == 49188) {
								$app_link = "www.healthgennie.com/download";
								$message = urlencode("Dear " . ucfirst($appointment->Patient->first_name) . " " . $appointment->Patient->last_name . ", Thanks for booking an appointment with Health Gennie. A doctor will be assigned to you shortly.For Better Experience Download Health Gennie App " . $app_link . " Thanks Team Health Gennie");
								$this->sendSMS($appointment->Patient->mobile_no, $message, '1707161735294162018');
							} else {
								$message = urlencode("Dear " . ucfirst($appointment->Patient->first_name) . " " . $appointment->Patient->last_name . ", Thanks for booking an appointment with Health Gennie. A doctor will be assigned to you shortly \n Thanks Team Health Gennie");
								$this->sendSMS($appointment->Patient->mobile_no, $message, '1707161587996474225');
							}
						} else {
							if ($meta_data['doc_id'] == 49188) {
								$app_link = "www.healthgennie.com/download";
								$message = urlencode("Dear " . ucfirst($appointment->Patient->first_name) . " " . $appointment->Patient->last_name . ", Thanks for booking an appointment with Health Gennie. A doctor will be assigned to you shortly.For Better Experience Download Health Gennie App " . $app_link . " Thanks Team Health Gennie");
								$this->sendSMS($appointment->Patient->mobile_no, $message, '1707161735294162018');
							} else {
								if (!empty($appointment->Patient->email)) {
									$EmailTemplate = EmailTemplate::where('slug', 'teleconsultpatientappointment')->first();
									$to = $appointment->Patient->email;
									if ($EmailTemplate && !empty($to)) {
										$body = $EmailTemplate->description;
										$tbl = '<table style="width: 100%;" cellpadding="0" cellspacing="0"><tbody><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Appointment Dr.</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Dr. ' . @$docData->first_name . " " . @$docData->last_name . '</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Date and Time</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">' . date('d-m-Y, h:i:sa', strtotime($appointment->start)) . '</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Payment for Consultations</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">₹ ' . $consultation_fees . " " . $fees_type . '</td></tr><tr><td colspan="2" style="font-size: 13px; color:#333; padding:10px 0px 10px;">If you wish to reschedule or cancel your appointment, please contact to our help line number.</td></tr></tbody></table>';

										$mailMessage = str_replace(
											array('{{pat_name}}', '{{clinic_name}}', '{{clinic_phone}}', '{{appointmenttable}}'),
											array($patientname, $docData->clinic_name, $docData->mobile, $tbl),
											$body
										);
										$to_docname = '';
										$datas = array('to' => $to, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'practiceData' => $docData, 'content' => $mailMessage, 'subject' => $EmailTemplate->subject);
										try {
											Mail::send('emails.mailtempPractice', $datas, function ($message) use ($datas) {
												$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
											});
										} catch (\Exception $e) {
											// Never reached
										}
									}
								}
								$message = urlencode("Dear " . ucfirst($appointment->Patient->first_name) . " " . $appointment->Patient->last_name . " , Your Tele consultation with Dr. " . $appointment->User->DoctorInfo->first_name . " " . $appointment->User->DoctorInfo->last_name . " on " . $appointDate . " and " . $appointtime . " has been confirmed. Please keep the Health Gennie app open at the time of consultation. Thanks Team Health Gennie");
								$this->sendSMS($appointment->Patient->mobile_no, $message, '1707161587979652683');
							}
						}
						$admin_msg = urlencode("This patient(" . $patientname . ") of tele consultaion appointment with " . $docName . " on " . $appointDate . " at " . $appointtime . " Doctor Mobile : " . $docData->mobile_no . " Patient Mobile : " . $appointment->Patient->mobile_no . " Thanks Team Health Gennie");
						$this->sendSMS(implode(",", getSetting("support_contact_numbers")), $admin_msg, '1707161735123037290');
					}
					$EmailTemplate = EmailTemplate::where('slug', 'teleconsultappointmentmailadmin')->first();
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
				} else {
					$appointment =  Appointments::where('id', $appointment_id)->first();
					$docData = Doctors::where(['user_id' => $appointment->doc_id])->first();
					$docName = "Dr. " . ucfirst($docData->first_name) . " " . $docData->last_name;
					$patientname = $appointment->patient->first_name . ' ' . $appointment->patient->last_name;
					$appointDate = date('d-m-Y', strtotime($appointment->start));
					$appointtime = date('h:i A', strtotime($appointment->start));

					$EmailTemplate = EmailTemplate::where('slug', 'patientappointment')->first();
					if ($EmailTemplate && !empty($to)) {
						$body = $EmailTemplate->description;
						$tbl = '<table style="width: 100%;" cellpadding="0" cellspacing="0"><tbody><tr><td colspan="2" style="color:#189ad4; font-size: 15px;font-weight:500; padding: 15px 0px 0px;">Dear ' . $pat_name . '</td></tr><tr><td colspan="2" style="color:#333; font-size: 13px;font-weight:500; padding: 4px 0px 15px;">if you wish to reschedule or cancel your appointment, please call us at <br> ' . $practiceData->mobile . '</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Appointment Dr.</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Dr. ' . @$appointment->User->DoctorInfo->first_name . " " . @$appointment->User->DoctorInfo->last_name . '</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Date and Time</td><td style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">' . date('d-m-Y, h:i:sa', strtotime($appointment->start)) . '</td></tr><tr><td width="130" style="border:1px solid #ccc;font-size: 13px; color:#189ad4; padding: 5px 10px;">Address</td><td style="border:1px solid #ccc; font-size: 13px; color:#189ad4; padding: 5px 10px;">' . $practiceData->address_1 . ', ' . $practiceData->address_2 . ',' . getCityName($practiceData->city_id) . ',' . getStateName($practiceData->state_id) . ',' . getCountrieName($practiceData->country_id) . ',' . $practiceData->zipcode . '</td></tr><tr><td colspan="2" style="font-size: 13px; color:#333; padding:10px 0px 10px;">if you wish to reschedule or cancel your appointment,please call us at ' . $practiceData->mobile . '</td></tr><tr><td colspan="2" style="font-size: 13px; color:#333; padding:10px 0px 10px;"><strong>Thanks <br>' . $practiceData->clinic_name . '</strong></td></tr></tbody></table>';

						$mailMessage = str_replace(
							array('{{pat_name}}', '{{clinic_name}}', '{{clinic_phone}}', '{{appointmenttable}}'),
							array($pat_name, $practiceData->clinic_name, $practiceData->mobile, $tbl),
							$body
						);
						$to_docname = '';
						$datas = array('to' => $to, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'practiceData' => $practiceData, 'content' => $mailMessage, 'subject' => $EmailTemplate->subject);
						if (isset($meta_data['isPaytmTab']) && $meta_data['isPaytmTab'] == 'true') {
							try {
								Mail::send('emails.mailtempPracticePaytm', $datas, function ($message) use ($datas) {
									$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
								});
							} catch (\Exception $e) {
								// Never reached
							}
						} else {
							try {
								Mail::send('emails.mailtempPractice', $datas, function ($message) use ($datas) {
									$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
								});
							} catch (\Exception $e) {
								// Never reached
							}
						}
					}
					if (!empty($appointment->Patient->mobile_no)) {
						$appointDate = date('d-m-Y', strtotime($appointment->start));
						$appointtime = date('h:i A', strtotime($appointment->start));

						$app_link = "www.healthgennie.com/download";
						$message = urlencode("Dear " . ucfirst($appointment->Patient->first_name) . " " . $appointment->Patient->last_name . " Your appointment with Dr. " . $appointment->User->DoctorInfo->first_name . " " . $appointment->User->DoctorInfo->last_name . " on " . $appointDate . " at " . $appointtime . " has been confirmed by Dr. " . $appointment->User->DoctorInfo->first_name . " " . $appointment->User->DoctorInfo->last_name . ". Please visit the clinic 15 mins before your appointment time at clinic address. For Better Experience Download Health Gennie App " . $app_link . " Thanks Team Health Gennie");
						$this->sendSMS($appointment->Patient->mobile_no, $message, '1707161735128760937');
					}
					$admin_msg = urlencode("This patient(" . $patientname . ") of In clinic appointment with " . $docName . " on " . $appointDate . " at " . $appointtime . ".\nDoctor Mobile : " . $docData->mobile_no . ".\nPatient Mobile : " . $appointment->Patient->mobile_no . ".\n Thanks Team Health Gennie");
					$this->sendSMS(implode(",", getSetting("support_contact_numbers")), $admin_msg, '1707161587991219876');
				}
				$this->notificationDoctorForAppointment($appointment);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function notificationDoctorForAppointment($appointment = null)
	{

		try {

			$notifyres = "";
			$notificationKeyDoc = "AAAAIC4y15c:APA91bEL_YYIe4KZOcC-_HogaAC80aabtIxTDBGYMExAdzAVcyEqQhEvfdrDuxE8mFe7bgrE44l3SdIpDNyOZvbonOuhfSV91Z0AtQa8R_YYNajL9YpF62Xc0AWuwOqaZTuiabxqtdCA";
			$title = 'New Appointment';
			$subtitle = 'New Appointment';
			$tickerText = 'text here...';
			$fcm_token = $appointment->User->fcm_token;
			$device_id = $appointment->User->device_id;
			$appointDate = date('d-m-Y', strtotime($appointment->start));
			$appointtime = date('h:i A', strtotime($appointment->start));
			$notify_message = "Dear " . ucfirst($appointment->User->DoctorInfo->first_name) . " " . $appointment->User->DoctorInfo->last_name . ", You have appointment to patient name " . ucfirst($appointment->Patient->first_name) . " " . $appointment->Patient->last_name . " on " . $appointDate . " at " . $appointtime;
			if ($device_id == 1 && !empty($fcm_token)) {
				$notifyres = $this->pn($notificationKeyDoc, $fcm_token, $notify_message, $title, $subtitle, $tickerText, 'appointmentpp');
			} else if ($device_id == 2 && !empty($fcm_token)) {
				$notifyres = $this->iosNotificationSend($fcm_token, $notify_message, $title, 'appointmentpp');
			}
			return true;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}


	public  function successSubscripton(Request $request)
	{

		try {

			$practiceId = base64_decode($request->pid);
			if (!empty($practiceId)) {
				$doc_data = Doctors::where(["user_id" => $practiceId, "practice_id" => $practiceId])->first();
				$trail_details =  PlanPeriods::with(['user'])->where(['status' => 1])->get();
				$pass  = trim(substr($doc_data->first_name, 0, 3)) . substr($doc_data->mobile_no, -4) . rand(000, 999);
				Doctors::where('practice_id', $practiceId)->update(array(
					'hg_doctor' => 1,
					'varify_status' => 1,
					'password' => bcrypt($pass),
					'created_at' => date('Y-m-d h:i:s'),
				));
				ehrUser::where('id', $doc_data->practice_id)->update(array(
					'varify_status' => 1,
					'password' => bcrypt($pass),
				));

				$username = ucfirst($doc_data->first_name) . " " . $doc_data->last_name;
				$to = $doc_data->email;
				if (!empty($doc_data->mobile_no)) {
					$message = urlencode("Dear Dr. " . $username . ",\nCongratulation! Your profile has been verified successfully with Health Gennie.Your 14 day trial for Health Gennie software starts from today..Please check your email for login credentials.If you have any questions, please call us at " . getSetting("helpline_number")[0]);
					$this->sendSMS($doc_data->mobile_no, $message, '1707161587969096964');
				}
				$practiceData =  PracticeDetails::where(['user_id' => $practiceId])->first();
				$EmailTemplate = EmailTemplate::where('slug', 'doctorregbackend')->first();
				if ($this->is_connected() == 1) {
					if ($EmailTemplate) {
						$body = $EmailTemplate->description;
						$password = $pass;
						$mailMessage = str_replace(
							array('{{username}}', '{{clinic_name}}', '{{password}}', '{{to}}'),
							array($username, $practiceData->clinic_name, $password, $to),
							$body
						);
						$datas = array('to' => $to, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'practiceData' => $practiceData, 'content' => $mailMessage, 'subject' => $EmailTemplate->subject);
						try {
							Mail::send('emails.mailtempPractice', $datas, function ($message) use ($datas) {
								$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
							});
						} catch (\Exception $e) {
							// Never reached
						}
					}
				}
				$trail_detail_history =  ManageTrailPeriods::with(['Plans'])->where(['user_id' => $practiceId, 'user_plan_id' => 5])->first();
				$practiceDetails =  ManageTrailPeriods::with(['SubscribedPlans', 'Plans'])->where(['user_id' => $practiceId])->orderBy('id', 'desc')->get();
				$practicesSubscriptions = PracticesSubscriptions::Where('user_id', $practiceId)->orderBy('id', 'desc')->paginate(10);
				return view($this->getView('subscription.success'), ['practiceDetails' => $practiceDetails, 'trail_details' => $trail_details, 'trail_detail_history' => $trail_detail_history, 'practicesSubscriptions' => $practicesSubscriptions]);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function viewBill(Request $request)
	{

		try {
			$bid = $request->bid;
			$PracticesSubscriptions = PracticesSubscriptions::Where('id', $bid)->first();
			//echo "<pre>";print_r($patientBill);die;
			return view($this->getView('subscription.viewBill'), ['PracticesSubscriptions' => $PracticesSubscriptions]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function getClinics(Request $request)
	{

		try {

			$clinic = $request->searchText;
			$docs = Doctors::select(["id", "practice_id", "clinic_name", "practice_type", "clinic_speciality", "city_id", "locality_id", "clinic_image", "clinic_mobile", "clinic_email", "website", "address_1", "country_id", "state_id", "zipcode"])->where('clinic_name', 'like', '%' . $clinic . '%')->where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->whereNotNull('member_id')->whereNotNull('practice_id')->groupBy('practice_id')->get();

			$data = array();
			foreach ($docs as $value) {
				//$data[] = array("id"=>$doc->id,"practice_id"=>$doc->practice_id,"clinic_name"=>$doc->clinic_name);
				if (!empty($value->clinic_image)) {
					$image_url = getEhrUrl() . "/public/doctor/" . $value->clinic_image;
					if (does_url_exists($image_url)) {
						$value['clinic_image_url'] = $image_url;
					} else {
						$value['clinic_image_url'] = null;
					}
				} else {
					$value['clinic_image_url'] = null;
				}

				if (!empty($value->city_id)) {
					$value['city_id'] = array("id" => $value->city_id, "name" => getCityName($value->city_id));
				}
				if (!empty($value->locality_id)) {
					$value['locality_id'] = array("id" => $value->locality_id, "name" => getLocalityName($value->locality_id));
				}
			}

			//dd($docs);

			return $docs;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}


	public function checkoutPlan(Request $request)
	{

		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$doc_id = $data['doc_id'];
				if (!empty($doc_id)) {
					$doc_data = Doctors::Where(['id' => $doc_id])->first();
					$practiceId = $doc_data->practice_id;
					$subscription =  PracticesSubscriptions::create([
						'user_id' => $practiceId,
						// 'tran_id' =>  strtotime("now"),
						'payment_mode' => 1,
						'coupon_id' => null,
						'tax' => $data['tax'],
						'order_subtotal' => $data['order_subtotal'],
						'order_total' => $data['order_total'],
					]);
					$meta = [];
					$plan = Plans::where('id', $data['plan_id'])->first();
					$meta = ['plan_title' => $plan->plan_title, 'core_modules' => $plan->core_modules, 'other_text' => $plan->other_text];
					$subscribedPlan = new SubscribedPlans;
					$subscribedPlan->plan_id = $plan->id;
					$subscribedPlan->plan_type = $plan->plan_type;
					$subscribedPlan->plan_price = $plan->plan_price;
					$subscribedPlan->discount_price = $plan->discount_price;
					$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
					$subscribedPlan->plan_duration = $plan->plan_duration;
					$subscribedPlan->promotional_sms_limit = $plan->promotional_sms_limit;
					$subscribedPlan->meta_data = json_encode($meta);
					$subscription->SubscribedPlans()->save($subscribedPlan);

					//for the plan trail period
					$duration_type = $plan->plan_duration_type;
					if ($duration_type == "d") {
						$duration_in_days = $plan->plan_duration;
					} elseif ($duration_type == "m") {
						$duration_in_days = (30 * $plan->plan_duration);
					} elseif ($duration_type == "y") {
						$duration_in_days = (366 * $plan->plan_duration);
					}
					$end_date = date('Y-m-d H:i:s', strtotime($subscribedPlan->created_at . '+' . $duration_in_days . ' days'));
					$ManageTrailPeriods =  ManageTrailPeriods::create([
						'subscription_id' => $subscription->id,
						'subscribed_plan_id' => $subscribedPlan->id,
						'user_plan_id' => $data['plan_id'],
						'user_id' => $practiceId,
						'start_trail' => $subscribedPlan->created_at,
						'end_trail' => $end_date,
						'remaining_sms' => $plan->promotional_sms_limit,
						'status' => 0
					]);
					//end for the plan trail period
					// echo "<pre>"; print_r($subscription);die;

					$parameters = [
						'tid' => strtotime("now"),
						'order_id' => $subscription->id,
						'amount' => $subscription->order_total,
						'merchant_param1' => "WEB",
						'merchant_param2' => $practiceId,
					];

					// gateway = CCAvenue / others
					$order = Indipay::gateway('CCAvenue')->prepare($parameters);
					return Indipay::process($order);
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function sendPracticeSubscriptionMail($order_id, $status, $msg)
	{

		try {

			$PracticesSubscriptions =  PracticesSubscriptions::where('id', $order_id)->first();
			if (!empty($PracticesSubscriptions)) {
				$user = ehrUser::with(["practiceDetails", "doctorInfo"])->where("id", $PracticesSubscriptions->user_id)->first();
				if ($status == 1) {
					$today_date = date('d-m-Y');
				} else {
					$today_date = $msg;
				}

				$to = $user->email;
				$username = $user->doctorInfo->first_name . " " . $user->doctorInfo->last_name;
				if ($this->is_connected() == 1) {
					if ($status == 1) {
						$EmailTemplate = EmailTemplate::where('slug', 'healthgenniesubscription')->first();
					} else {
						$EmailTemplate = EmailTemplate::where('slug', 'healthgenniesubscriptionfailure')->first();
					}

					if ($EmailTemplate && !empty($to)) {
						$body = $EmailTemplate->description;
						$tbl  = "<table border='1' cellspacing='0' cellpadding='0' width='100%'>";
						$tbl .= "<thead><tr>";
						$tbl .= "<th>Plan Name</th><th>Plan Start Date</th><th>Plan End Date</th><th>Duration</th><th>SMS Limit</th><th>Plan Amount</th></tr></thead>";
						$tbl .= '<tbody>';
						if (!empty($PracticesSubscriptions)) {
							foreach ($PracticesSubscriptions->SubscribedPlans as $SubscribedPlan) {
								$type = "";
								if ($SubscribedPlan->plan_duration_type == "d") {
									$type = "Day";
								} elseif ($SubscribedPlan->plan_duration_type == "m") {
									$type = "Month";
								} elseif ($SubscribedPlan->plan_duration_type == "y") {
									$type =  "Year";
								}
								$tbl .= "<tr style='text-align:center;'><td>" . $SubscribedPlan->Plans->plan_title . "</td><td>" . date('d-m-Y', strtotime($SubscribedPlan->ManageTrailPeriods->start_trail)) . "</td><td>" . date('d-m-Y', strtotime($SubscribedPlan->ManageTrailPeriods->end_trail)) . "</td><td>" . $SubscribedPlan->plan_duration . " " . $type . "</td><td>" . $SubscribedPlan->promotional_sms_limit . "</td><td style='text-align:right;'>" . ($SubscribedPlan->plan_price - $SubscribedPlan->discount_price) . "</td></tr>";
							}
							$tbl .= "<tr><td style='text-align:right;' colspan='5' >CGST(9%)</td><td style='text-align:right;'>" . ($PracticesSubscriptions->tax / 2) . "</td></tr>";
							$tbl .= "<tr><td style='text-align:right;'colspan='5' >SGST(9%)</td><td style='text-align:right;'>" . ($PracticesSubscriptions->tax / 2) . "</td></tr>";
							$tbl .= "<tr><td style='text-align:right;' colspan='5'>Total</td><td style='text-align:right;'>" . $PracticesSubscriptions->order_total . "</td></tr>";

							if ($PracticesSubscriptions->coupon_discount != '') {
								$tbl .= "<tr><td style='text-align:right;' colspan='5' >Coupon Discount</td><td style='text-align:right;'>" . $PracticesSubscriptions->coupon_discount . "</td></tr>";
							}
							$tbl .= "<tr><td style='text-align:right;' colspan='5'><strong>Payed Total</stron></td><td style='text-align:right;'><strong>" . $PracticesSubscriptions->SubscriptionsTxn->payed_amount . "</strong></td></tr>";
						}
						$tbl .= "</tbody></table>";
						$mailMessage = str_replace(
							array('{{name}}', '{{date}}', '{{subscribetable}}'),
							array($username, $today_date, $tbl),
							$body
						);

						$datas = array('to' => $to, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'subject' => $EmailTemplate->subject);
						if ($status == 1) {
							try {
								Mail::send('emails.subscriptionPractice', $datas, function ($message) use ($datas) {
									$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
								});
							} catch (\Exception $e) {
								// Never reached
							}

							if (!empty($user->practiceDetails->mobile)) {
								foreach ($PracticesSubscriptions->SubscribedPlans as $SubscribedPlan) {
									if ($SubscribedPlan->plan_type == 1) {
										$message = urlencode("Dear " . ucfirst($username) . ",Thank you for subscribing Health Gennie. Your subscription is valid until (" . date('d-m-Y', strtotime($SubscribedPlan->ManageTrailPeriods->end_trail)) . ")");
										$this->sendSMS($user->practiceDetails->mobile, $message, '1707161587915325103');
										$messagecamp = urlencode("Dear " . ucfirst($username) . ", Congratulations. " . $SubscribedPlan->promotional_sms_limit . " free campaign SMS has been added into your Health Gennie account");
										$this->sendSMS($user->practiceDetails->mobile, $messagecamp, '1707161526826981709');
									} else {
										$messageaddon = urlencode("Dear " . ucfirst($username) . ", " . $SubscribedPlan->Plans->plan_title . " Add-on has been successfully added to your Health Gennie account.");
										$this->sendSMS($user->practiceDetails->mobile, $messageaddon, '1707161526818780951');
									}
								}
							}
						} else {
							try {
								Mail::send('emails.subscriptionPractice', $datas, function ($message) use ($datas) {
									$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
								});
							} catch (\Exception $e) {
								// Never reached
							}

							if (!empty($user->practiceDetails->mobile)) {
								$message = urlencode("Dear " . ucfirst($username) . ", Your Transaction has been failed for the healthgennie subscription.\nPlease try again.");
								$this->sendSMS($user->practiceDetails->mobile, $message, '1707161526812763299');
							}
						}
					}
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function sendUserSubscriptionMail($order_id, $status, $msg)
	{

		try {
			$usersSubscriptions =  UsersSubscriptions::where('id', $order_id)->first();
			if (!empty($usersSubscriptions)) {
				$user = User::where("id", $usersSubscriptions->user_id)->first();
				if ($status == 1) {
					$today_date = date('d-m-Y');
				} else {
					$today_date = $msg;
				}
				$to = $user->email;
				$username = "User";
				if (!empty($user->first_name)) {
					$username = $user->first_name . " " . $user->last_name;
				}
				if ($this->is_connected() == 1) {
					if (!empty($user->mobile_no)) {
						$messagecamp = urlencode("Dear " . ucfirst($username) . ", Congratulations. Your plan has been subscribed successfully with Health Gennie. Your Subscription is active now Thanks Team Health Gennie");
						$this->sendSMS($user->mobile_no, $messagecamp, '1707161587964331729');
					}
					$EmailTemplate = EmailTemplate::where('slug', 'healthgennieplansubscription')->first();
					if ($EmailTemplate && !empty($to)) {
						$body = $EmailTemplate->description;
						$tbl  = "<table border='1' cellspacing='0' cellpadding='0' width='100%'>";
						$tbl .= "<thead><tr>";
						$tbl .= "<th>Plan Name</th><th>Plan Start Date</th><th>Plan End Date</th><th>Duration</th><th>SMS Limit</th><th>Plan Amount</th></tr></thead>";
						$tbl .= '<tbody>';
						if (!empty($usersSubscriptions)) {
							foreach ($usersSubscriptions->UserSubscribedPlans as $SubscribedPlan) {
								$type = "";
								if ($SubscribedPlan->plan_duration_type == "d") {
									$type = "Day";
								} elseif ($SubscribedPlan->plan_duration_type == "m") {
									$type = "Month";
								} elseif ($SubscribedPlan->plan_duration_type == "y") {
									$type =  "Year";
								}
								$tbl .= "<tr style='text-align:center;'><td>" . $SubscribedPlan->Plans->plan_title . "</td><td>" . date('d-m-Y', strtotime($SubscribedPlan->PlanPeriods->start_trail)) . "</td><td>" . date('d-m-Y', strtotime($SubscribedPlan->PlanPeriods->end_trail)) . "</td><td>" . $SubscribedPlan->plan_duration . " " . $type . "</td><td>" . $SubscribedPlan->appointment_cnt . "</td><td style='text-align:right;'>" . ($SubscribedPlan->plan_price - $SubscribedPlan->discount_price) . "</td></tr>";
							}
							$tbl .= "<tr><td style='text-align:right;' colspan='5' >CGST(9%)</td><td style='text-align:right;'>" . ($usersSubscriptions->tax / 2) . "</td></tr>";
							$tbl .= "<tr><td style='text-align:right;'colspan='5' >SGST(9%)</td><td style='text-align:right;'>" . ($usersSubscriptions->tax / 2) . "</td></tr>";
							$tbl .= "<tr><td style='text-align:right;' colspan='5'>Total</td><td style='text-align:right;'>" . $usersSubscriptions->order_total . "</td></tr>";

							if ($usersSubscriptions->coupon_discount != '') {
								$tbl .= "<tr><td style='text-align:right;' colspan='5' >Coupon Discount</td><td style='text-align:right;'>" . $usersSubscriptions->coupon_discount . "</td></tr>";
							}
							$tbl .= "<tr><td style='text-align:right;' colspan='5'><strong>Payed Total</stron></td><td style='text-align:right;'><strong>" . $usersSubscriptions->UserSubscriptionsTxn->payed_amount . "</strong></td></tr>";
						}
						$tbl .= "</tbody></table>";
						$mailMessage = str_replace(
							array('{{name}}', '{{date}}', '{{subscribetable}}'),
							array($username, $today_date, $tbl),
							$body
						);

						$datas = array('to' => $to, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'subject' => $EmailTemplate->subject);
						try {
							Mail::send('emails.subscriptionPractice', $datas, function ($message) use ($datas) {
								$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
							});
						} catch (\Exception $e) {
							// Never reached
						}
					}
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function sendMedicineOrderMail($order_id, $date)
	{
		try {
			$order =  MedicineOrders::where('id', $order_id)->first();
			if (!empty($order)) {
				$user = User::where("id", $order->user_id)->first();
				$to = $user->email;
				$username = $user->first_name . " " . $user->last_name;
				if ($this->is_connected() == 1) {
					if (!empty($user->mobile_no)) {
					}
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function sendOwnerMailForSubscription($order_id, $status, $msg)
	{

		try {

			if ($this->is_connected() == 1) {

				$PracticesSubscriptions =  PracticesSubscriptions::where('id', $order_id)->first();
				if (!empty($PracticesSubscriptions)) {
					$user = ehrUser::with("practiceDetails")->where("id", $PracticesSubscriptions->user_id)->first();
					$today_date = date('d-m-Y');
					$mobile = $user->practiceDetails->mobile;
					$username = $user->practiceDetails->first_name . " " . $user->practiceDetails->last_name;

					if ($status == 1) {
						$msg = " has been subscribed on healthgennie.";
					} else {
						$msg = " Transaction has been failed.";
					}
					$EmailTemplate = EmailTemplate::where('slug', 'healthgenniesubscriptionownermail')->first();
					if ($EmailTemplate) {
						$body = $EmailTemplate->description;

						$tbl  = "<table border='1' cellspacing='0' cellpadding='0' width='100%'>";
						$tbl .= "<thead><tr>";
						$tbl .= "<th>Plan Name</th><th>Plan Start Date</th><th>Plan End Date</th><th>Duration</th><th>SMS Limit</th><th>Plan Amount</th></tr></thead>";
						$tbl .= '<tbody>';
						if (!empty($PracticesSubscriptions)) {
							foreach ($PracticesSubscriptions->SubscribedPlans as $SubscribedPlan) {
								$type = "";
								if ($SubscribedPlan->plan_duration_type == "d") {
									$type = "Day";
								} elseif ($SubscribedPlan->plan_duration_type == "m") {
									$type = "Month";
								} elseif ($SubscribedPlan->plan_duration_type == "y") {
									$type =  "Year";
								}
								$tbl .= "<tr style='text-align:center;'><td>" . $SubscribedPlan->Plans->plan_title . "</td><td>" . date('d-m-Y', strtotime($SubscribedPlan->ManageTrailPeriods->start_trail)) . "</td><td>" . date('d-m-Y', strtotime($SubscribedPlan->ManageTrailPeriods->end_trail)) . "</td><td>" . $SubscribedPlan->plan_duration . " " . $type . "</td><td>" . $SubscribedPlan->promotional_sms_limit . "</td><td style='text-align:right;'>" . ($SubscribedPlan->plan_price - $SubscribedPlan->discount_price) . "</td></tr>";
							}
							$tbl .= "<tr><td style='text-align:right;' colspan='5' >CGST(9%)</td><td style='text-align:right;'>" . ($PracticesSubscriptions->tax / 2) . "</td></tr>";
							$tbl .= "<tr><td style='text-align:right;'colspan='5' >SGST(9%)</td><td style='text-align:right;'>" . ($PracticesSubscriptions->tax / 2) . "</td></tr>";
							$tbl .= "<tr><td style='text-align:right;' colspan='5'>Total</td><td style='text-align:right;'>" . $PracticesSubscriptions->order_total . "</td></tr>";

							if ($PracticesSubscriptions->coupon_discount != '') {
								$tbl .= "<tr><td style='text-align:right;' colspan='5' >Coupon Discount</td><td style='text-align:right;'>" . $PracticesSubscriptions->coupon_discount . "</td></tr>";
							}
							$tbl .= "<tr><td style='text-align:right;' colspan='5'><strong>Payed Total</stron></td><td style='text-align:right;'><strong>" . $PracticesSubscriptions->SubscriptionsTxn->payed_amount . "</strong></td></tr>";
						}
						$tbl .= "</tbody></table>";

						$mailMessage = str_replace(
							array('{{id}}', '{{name}}', '{{email}}', '{{mobile}}', '{{msg}}', '{{subscribetable}}'),
							array($user->id, $username, $user->email, $mobile, $msg, $tbl),
							$body
						);
						$datas = array('to' => "info@healthgennie.com", 'from' => $user->email, 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'subject' => $EmailTemplate->subject);
						try {
							Mail::send('emails.all', $datas, function ($message) use ($datas) {
								$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
							});
						} catch (\Exception $e) {
							// Never reached
						}
					}
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function writeThyrocareData(Request $request)
	{

		try {
			$postdata = array(
				'username' => '9414061829',
				'password' => '256EE3',
				'portalType' => '',
				'userType' => 'dsa',
				'facebookId' => 'string',
				'mobile' => 'string',
			);
			$post_data = json_encode($postdata);

			$url = "https://velso.thyrocare.cloud/api/Login/Login";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			$response = curl_exec($ch);
			$LoginData = json_decode($response, true);
			//pr($LoginData);
			if ($LoginData) {
				//$LoginData = json_decode($LoginData);
				$api_key = $LoginData['apiKey'];
				$urlGetproduct = "https://velso.thyrocare.cloud/api/productsmaster/Products";
				$chAll = curl_init($urlGetproduct);
				curl_setopt($chAll, CURLOPT_POST, 1);
				curl_setopt($chAll, CURLOPT_POSTFIELDS, json_encode(array('ApiKey' => $api_key, 'ProductType' => 'ALL')));
				curl_setopt($chAll, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($chAll, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$thyrocate_data_all = curl_exec($chAll);
				//$thyrocate_data_all = json_decode($responseAll,true);
				if (!empty($thyrocate_data_all)) {
					File::put(public_path('thyrocare-data/All.txt'), $thyrocate_data_all);
				}
				$chOffer = curl_init($urlGetproduct);
				curl_setopt($chOffer, CURLOPT_POST, 1);
				curl_setopt($chOffer, CURLOPT_POSTFIELDS, json_encode(array('ApiKey' => $api_key, 'ProductType' => 'OFFER')));
				curl_setopt($chOffer, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($chOffer, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$thyrocate_data_offer = curl_exec($chOffer);

				//$thyrocate_data_offer = json_decode($responseOffer,true);
				//pr($thyrocate_data_offer);
				$productCode = ['PROJ1022893', 'PROJ1022897', 'PROJ1023946', 'PROJ1022900', 'PROJ1022901', 'PROJ1022903', 'PROJ1022904', 'PROJ1022905', 'PROJ1022906', 'PROJ1022907', 'PROJ1022908', 'PROJ1022909', 'PROJ1022916', 'PROJ1022921', 'PROJ1022923'];
				if (!empty($thyrocate_data_offer)) {
					$thyrocate_data_offer = json_decode($thyrocate_data_offer, true);
					//pr($thyrocate_data_offer['master']['offer']);
					$offArr = array();
					foreach ($thyrocate_data_offer['master']['offer'] as $offer) {
						if (in_array($offer['code'], $productCode)) {
							$offArr[] =  $offer;
						}
					}
					//$thyrocate_data_offer = json_encode($thyrocate_data_offer['master']['offer']);
					File::put(public_path('thyrocare-data/Offer.txt'), json_encode($offArr));
				}
				$chPROFILE = curl_init($urlGetproduct);
				curl_setopt($chPROFILE, CURLOPT_POST, 1);
				curl_setopt($chPROFILE, CURLOPT_POSTFIELDS, json_encode(array('ApiKey' => $api_key, 'ProductType' => 'PROFILE')));
				curl_setopt($chPROFILE, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($chPROFILE, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$responsePROFILE = curl_exec($chPROFILE);
				//$thyrocate_data_profile = json_decode($responsePROFILE,true);
				if (!empty($responsePROFILE)) {
					$thyrocate_data_profile = json_decode($responsePROFILE, true);
					$thyrocate_data_profile = json_encode($thyrocate_data_profile['master']['profile']);
					File::put(public_path('thyrocare-data/Profile.txt'), $thyrocate_data_profile);
				}
				$chTest = curl_init($urlGetproduct);
				curl_setopt($chTest, CURLOPT_POST, 1);
				curl_setopt($chTest, CURLOPT_POSTFIELDS, json_encode(array('ApiKey' => $api_key, 'ProductType' => 'TEST')));
				curl_setopt($chTest, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($chTest, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$responseTest = curl_exec($chTest);
				//$thyrocate_data_tests = json_decode($responseTest,true);
				if (!empty($responseTest)) {
					$thyrocate_data_tests = json_decode($responseTest, true);
					$thyrocate_data_tests = json_encode($thyrocate_data_tests['master']['tests']);
					File::put(public_path('thyrocare-data/Tests.txt'), $thyrocate_data_tests);
				}
				$this->insertToDbThyData();
				return 1;
			}
			return 2;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}


	public function getthyrocareLab($type, $limit = null)
	{

		try {
			if ($type == "ALL") {
				$products = ThyrocareLab::limit($limit)->get()->toArray();
				// $product = File::get(public_path('thyrocare-data/All.txt'));
			} else {
				$products = ThyrocareLab::where(['type' => $type])->limit($limit)->get()->toArray();
			}
			return $products;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function getFavLabList() {
		$labsData = LabCollection::with("DefaultLabs")->where('company_id',3)->where(['delete_status'=>1,'status'=>1])->distinct()->limit(7)->get();
		
		$thyProducts = [];
		if($labsData->count()>0) {
			foreach($labsData as $raw){
				$thyProducts[] = ["image_location"=>null,"name"=>@$raw->DefaultLabs->title,"common_name"=>@$raw->DefaultLabs->title];
			}
		}
		// $thyProductsTest = $this->getthyrocareLab("PROFILE",7);
		// if(count($thyProductsTest) > 0){
			// foreach($thyProductsTest as $product){
				// $img_url = $product['imageLocation'];
				// if(!empty($img_url)) {
					  // if(does_url_exists($img_url)) {
						 // $product['imageLocation'] = $img_url;
					  // }
					  // else{
						  // $product['imageLocation'] = null;
					  // }
				// }
				// else{
					// $product['imageLocation'] = null;
				// }
				// $thyProducts[] = ["image_location"=>$product['imageLocation'],"name"=>$product["name"],"common_name"=>$product["common_name"]];
			// }
		// }
		return $thyProducts;
	}

	function searchLabWeb(Request $request)
	{

		try {
			$user_array = array();
			$user_array['search_key'] = $request->search_key;
			return	$this->fetchLabDataNew($user_array);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function fetchLabDataNew($user_array)
	{

		try {
			$search = $user_array['search_key'];

			$allArr = [];
			$thyrocareData = ThyrocareLab::where('common_name', 'like', '%' . $search . '%')->where(["type" => "test"])->limit(30)->get()->toArray();


			$c_labs = LabCollection::with("DefaultLabs", "LabCompany")->where('delete_status', '=', '1')->where('status' , '=' , '1')->whereHas("DefaultLabs", function ($q) use ($search) {
				$q->where(DB::raw('concat(default_labs.title," ",IFNULL(default_labs.short_name,""))'), 'like', '%' . $search . '%')->where('status' , '=' , '1');
			})->limit(30)->get()->toArray();
			$product = LabPackage::where('title', 'like', '%' . $search . '%')->where('delete_status', 1)->limit(30)->get()->toArray();
			if (count($c_labs) > 0) {
				$allArr = array_unique(array_merge($thyrocareData, $c_labs), SORT_REGULAR);
			} else {
				$allArr = $thyrocareData;
			}
			$allArr = array_unique(array_merge($allArr, $product), SORT_REGULAR);

			return $allArr;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	

	public function fetchLabData($user_array) {
		$lab_array = [];
		if($user_array['search_key'] != '') {
			$thyProducts = $this->getthyrocareLab("ALL");
			$search_key = $user_array['search_key'];
			/*$testProducts = @$thyProducts['master']['tests'];
			if(count($testProducts) > 0){
				foreach($testProducts as $prod){
					$thyProductsArray[] = $prod;
				}
			}
			$profileProducts = @$thyProducts['master']['profile'];
			if(count($profileProducts) > 0){
				foreach($profileProducts as $prod){
					$thyProductsArray[] = $prod;
				}
			}
			$offerProducts = @$thyProducts['master']['offer'];
			if(count($offerProducts) > 0){
				foreach($offerProducts as $prod){
					$thyProductsArray[] = $prod;
				}
			}*/
			$profile_array = [];
			$test_array = [] ;
			$offer_array = [];
			$thyProductsArray = $thyProducts;
			if(count($thyProductsArray)>0 ){
				foreach($thyProductsArray as $product) {
					if (strpos(strtolower($product['name']),strtolower($search_key)) !== FALSE || strpos(strtolower($product['common_name']),strtolower($search_key)) !== FALSE) {
						$img_url = $product['imageLocation'];
						if(!empty($img_url)) {
							  if(does_url_exists($img_url)) {
								 $product['imageLocation'] = $img_url;
							  }
							  else{
								  $product['imageLocation'] = null;
							  }
						}
						else{
							$product['imageLocation'] = null;
						}
						if($product['type'] == "PROFILE") {
							$profile_array[] = ["image_location"=>$product['imageLocation'],"name"=>$product["name"]];
						}
						if($product['type'] == "TEST") {
							$test_array[] = ["image_location"=>$product['imageLocation'],"name"=>$product["name"]];
						}
						if($product['type'] == "OFFER") {
							$offer_array[] = ["image_location"=>$product['imageLocation'],"name"=>$product["name"]];
						}
					}
				}
			}
			if(count($profile_array) > 0){
				$lab_array["PROFILE"] = $profile_array;
			}
			else{
				$lab_array["PROFILE"] = [];
			}
			if(count($test_array) > 0){
				$lab_array["TESTS"] = $test_array;
			}
			else{
				$lab_array["TESTS"] = [];
			}
			if(count($offer_array) > 0){
				$lab_array["OFFER"] = $offer_array;
			}
			else{
				$lab_array["OFFER"] = [];
			}
			return $lab_array;
		}
	}

	//for Web/app plan success
	public function planOrderSuccess(Request $request)
	{

		try {
			return view($this->getView('users.subscription.success'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	//for Web/app plan success
	public function planOrderCancel(Request $request)
	{

		try {
			return view($this->getView('users.subscription.cancel'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	//for Web/app plan success
	public function appointmentOrderSuccess(Request $request)
	{

		try {
			return view($this->getView('appointments.success'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function appointmentAdminOrderSuccess(Request $request)
	{

		try {
			return view($this->getView('admin.appointments.success'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	//for Web/app plan success
	public function appointmentOrderCancel(Request $request)
	{
		try {
			return view($this->getView('appointments.cancel'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	//for Web/app donation success
	public function donationOrderSuccess(Request $request)
	{
		return view($this->getView('pages.donation-success'));
	}
	//for Web/app donation success
	public function donationOrderCancel(Request $request)
	{
		return view($this->getView('pages.donation-cancel'));
	}
	//for Web/app medicine success
	public function medicineSuccess(Request $request)
	{
		return view($this->getView('pages.medicine-success'));
	}
	//for Web/app medicine success
	public function medicineCancel(Request $request)
	{
		return view($this->getView('pages.medicine-cancel'));
	}
	public function driveDashboard(Request $request)
	{

		$user = Auth::user();
		if ($user != null && $user->profile_status != '1') {
			// return redirect()->route('profile',['id'=>base64_encode($user->id)]);
			// return redirect()->route('userAppointment');
			if (Session::get('loginFrom') == '3') {
				Session::forget('loginFrom');
				$appData = Session::get('appDoctorData');
				return redirect()->route('doctor.bookSlot', $appData)->withInput();
			} else if (Session::get('loginFrom') == '7') {
				Session::forget('loginFrom');
				$planId = Session::get('planId');
				return redirect()->route('checkOutUserPlan', ['id' => $planId])->withInput();
			} else {
				$plans = userPlan::Where(["delete_status" => '1', 'status' => 1])->whereNotIn('id', [48, 11])->whereIn("type", array(1, 2))->orderBy('price', 'asc')->get();
				$upper_content = Pages::where(['slug' => 'subscription-plan-page-content-upperapp'])->first();
				$bottom_content = Pages::where(['slug' => 'subscription-plan-page-content-bottomapp'])->first();
				return view('users.dashboard', compact('plans', 'upper_content', 'bottom_content'));
			}
		}
		//login from cart
		else if ($user != null &&  Session::get('loginFrom') == '1') {
			Session::forget('loginFrom');
			return redirect()->route('LabCart');
		}
		//login from Medicines
		elseif ($user != null &&  Session::get('loginFrom') == '2') {
			Session::forget('loginFrom');
			echo '<script>window.open("' . route('oneMgOpen') . '","_blank")</script>';
			return view($this->getView('home'));
		} elseif ($user != null && Session::get('loginFrom') == '3') {
			Session::forget('loginFrom');
			$appData = Session::get('appDoctorData');
			return redirect()->route('doctor.bookSlot', $appData)->withInput();
		} elseif ($user != null && Session::get('loginFrom') == '4') {
			Session::forget('loginFrom');
			$blogData = Session::get('hgBlogData');
			return \Redirect::to($blogData);
		} elseif ($user != null && Session::get('loginFrom') == '5') {
			Session::forget('loginFrom');
			$feedData = Session::get('feedDoctorData');
			Session::forget('feedDoctorData');
			return \Redirect::to($feedData);
		} elseif ($user != null && Session::get('loginFrom') == '7') {
			Session::forget('loginFrom');
			$planId = Session::get('planId');
			return redirect()->route('checkOutUserPlan', ['id' => $planId])->withInput();
		} elseif ($user != null && Session::get('loginFrom') == '8') {
			Session::forget('loginFrom');
			return redirect()->route('allAssessmentScore');
		} elseif ($user != null && Session::get('loginFrom') == '9') {
			Session::forget('loginFrom');
			return redirect()->route('fetchAssessmentMetrix');
		} elseif ($user != null && Session::get('loginFrom') == '10') {
			Session::forget('loginFrom');
			$sympID = Session::get('symp_id');
			return redirect()->route('fetchAssesmentQues', ['symp_id' => $sympID])->withInput();
		} elseif ($user != null && Session::get('loginFromConsult') == '1') {
			// pr(Session::get('loginFrom'));
			Session::forget('loginFrom');
			Session::forget('loginFromConsult');
			// $appData = Session::get('appDoctorData');
			return redirect()->route('onlineConsult')->withInput();
		} elseif ($user != null && Session::get('loginFrom') == '11') {
			Session::forget('loginFrom');
			return redirect()->route('LabDashboard');
		} elseif ($user != null && Session::get('loginFrom') == '12') {
			Session::forget('loginFrom');
			return redirect()->route('slotBook');
		} elseif ($user != null && Session::get('loginFrom') == '13') {
			Session::forget('loginFrom');
			return redirect()->route('labOrderDetails');
		} elseif ($user != null && Session::get('loginFrom') == '14') {
			Session::forget('loginFrom');
			return redirect()->route('labOrders');
		} elseif ($user != null && Session::get('loginFrom') == '15') {
			Session::forget('loginFrom');
			return redirect()->route('LabDetails');
		} elseif ($user != null && Session::get('loginFrom') == '15') {
			Session::forget('loginFrom');
			return redirect()->route('getDoctorInfo');
		} 
		elseif($user!= null && Session::get('loginFrom') == '17') {
			Session::forget('loginFrom');
			return redirect()->route('voiceAssessment')->withInput();
		}
		elseif($user!= null && Session::get('loginFrom') == '19') {
			Session::forget('loginFrom');
			return redirect()->route('unlimitedPlan')->withInput();
		}
		
		else {

			$dt = date('Y-m-d');
			$is_subscribed = PlanPeriods::select('id')->whereDate('start_trail', '<=', $dt)->whereDate('end_trail', '>=', $dt)->where('user_id', Auth::id())->where('remaining_appointment', '!=', '0')->where('status', '1')->count();

			if (!is_null($user) && $is_subscribed > 0) {

				$plans = userPlan::Where(["delete_status" => '1', 'status' => 1])->whereNotIn('id', [45, 11])->whereIn("type", array(1, 2))->orderBy('price', 'asc')->get();

				if (count($plans) > 0) {
					foreach ($plans as $plan) {
						$plan['pkg_data'] = availPackDetails($plan->lab_pkg);
					}
				}
			} else {
				$plans = userPlan::Where(["delete_status" => '1', 'status' => 1])->whereNotIn('id', [48, 11])->whereIn("type", array(1, 2))->orderBy('price', 'asc')->get();

				if (count($plans) > 0) {
					foreach ($plans as $plan) {
						$plan['pkg_data'] = availPackDetails($plan->lab_pkg);
					}
				}
			}

			return view($this->getView('users.dashboard'), ['plans' => $plans]);
		}
	}
	public function unlimitedPlan(Request $request)
	{
		$plans = userPlan::Where(["delete_status" => '1'])->whereIn("id", array(29, 30, 34))->orderBy('price', 'asc')->get();
		if (count($plans) > 0) {
			foreach ($plans as $plan) {
				$plan['pkg_data'] = availGenniePackDetails($plan->lab_pkg);
			}
		}
		$from = base64_decode($request->from);
		Session::put('orderFrom', $from);
		Session::save();
		return view($this->getView('users.unlimited-plan'), ['plans' => $plans]);
	}

	public function choosePlan(Request $request)
	{
		if(Auth::user() == null) {
			\Log::info('in29jan' );
			Session::put('loginFrom', '7');
			Session::put('planId', $request->id);
			Session::save();
			return redirect()->route('login');
		} else {
			return redirect()->route('checkOutUserPlan', ['id' => $request->id, 'tp' => $request->tp])->withInput();
		}
	}
	public function planDetails(Request $request)
	{
		if (Auth::user() == null) {
			Session::put('loginFrom', '19');
			return redirect()->route('login');
		}

		$plan_id = base64_decode($request->id);
		$tp = base64_decode($request->tp);
		$plan = userPlan::where('id', $plan_id)->first();
		return view($this->getView('users.subscription.checkout_plan'), ['plan' => $plan, 'tp' => $tp]);

	}
	public function sitemap(Request $request)
	{
		return response()->view('sitemap')->header('Content-Type', 'text/xml');
	}
	public function autoUserSubscriptionExpired(Request $request)
	{
		$dt = date('Y-m-d', strtotime("-1 days"));
		$subs = PlanPeriods::with("user")->whereDate('end_trail', '=', $dt)->where(['status' => 1])->get();
		foreach ($subs as $raw) {
			$msg = 'Your health gennie elite subscription has been expired.';
			PlanPeriods::where('id', $raw->id)->update(array(
				'status' => 0,
			));
			if (!empty($raw->user->mobile_no)) {
				$message = urlencode($msg);
				$this->sendSMS($raw->user->mobile_no, $message, 1707161804136576354);
			}
		}
		echo "Success";
		die;
	}

	public function reminderForUserSubscriptionExpired(Request $request)
	{
		$dt = date('Y-m-d');
		$newDate15 = date('Y-m-d', strtotime("+15 days"));
		$newDate7 = date('Y-m-d', strtotime('+7 days'));
		$newDate2 = date('Y-m-d', strtotime('+2 days'));
		$newDate1 = date('Y-m-d', strtotime('+1 days'));
		// pr($newDate1);
		$trail_details =  PlanPeriods::with(['user'])->where(['status' => 1])->get();
		if (count($trail_details) > 0) {
			foreach ($trail_details as $trail_detail) {
				if ($newDate15 == date('Y-m-d', strtotime($trail_detail->end_trail))) {
					$msg = 'Your Health Gennie Subscription will expire in 15 days. Please subscribe to continue using Health Gennie.';
					ManageUsersNotifications::create([
						'notification' => $msg,
						'practice_id' =>  $trail_detail->user->id,
						'view_status' => 0,
					]);
					if (!empty($trail_detail->user->mobile_no)) {
						$message = urlencode($msg);
						$this->sendSMS($trail_detail->user->mobile_no, $message, '1707161526806979276');
					}
				}
				if ($newDate7 == date('Y-m-d', strtotime($trail_detail->end_trail))) {
					$msg = 'Your Health Gennie Subscription will expire in 7 days. Please subscribe to continue using Health Gennie.';
					ManageUsersNotifications::create([
						'notification' => $msg,
						'practice_id' =>  $trail_detail->user->id,
						'view_status' => 0,
					]);
					if (!empty($trail_detail->user->mobile_no)) {
						$message = urlencode($msg);
						$this->sendSMS($trail_detail->user->mobile_no, $message, '1707161526806979276');
					}
				}
				if ($newDate2 == date('Y-m-d', strtotime($trail_detail->end_trail))) {
					$msg = 'Your Health Gennie Subscription will expire in 2 days. Please subscribe to continue using Health Gennie.';
					ManageUsersNotifications::create([
						'notification' => $msg,
						'practice_id' =>  $trail_detail->user->id,
						'view_status' => 0,
					]);
					if (!empty($trail_detail->user->mobile_no)) {
						$message = urlencode($msg);
						$this->sendSMS($trail_detail->user->mobile_no, $message, '1707161526806979276');
					}
				}
				if ($newDate1 == date('Y-m-d', strtotime($trail_detail->end_trail))) {
					$msg = 'Your Health Gennie Subscription will expire tomorrow. Please subscribe to continue using Health Gennie.';
					ManageUsersNotifications::create([
						'notification' => $msg,
						'practice_id' =>  $trail_detail->user->id,
						'view_status' => 0,
					]);
					if (!empty($trail_detail->user->mobile_no)) {
						$message = urlencode($msg);
						$this->sendSMS($trail_detail->user->mobile_no, $message, '1707161526806979276');
					}
				}
				if ($dt == date('Y-m-d', strtotime($trail_detail->end_trail))) {
					$msg = 'Your Health Gennie Subscription will expire today. Please subscribe to continue using Health Gennie';
					ManageUsersNotifications::create([
						'notification' => $msg,
						'practice_id' =>  $trail_detail->user->id,
						'view_status' => 0,
					]);
					if (!empty($trail_detail->user->mobile_no)) {
						$message = urlencode($msg);
						$this->sendSMS($trail_detail->user->mobile_no, $message, '1707161526806979276');
					}
				}
			}
		}
		die;
	}
	public function loginFeedback(Request $request)
	{
		if (Auth::user() == null) {
			$data = $request->all();
			Session::put('loginFrom', '5');
			Session::put('feedDoctorData', $data['url']);
			Session::save();
			return redirect()->route('login');
		}
	}
	public function checkNotify()
	{
		$user = User::select(["fcm_token", "device_type"])->where(["id" => 1, 'status' => 1])->first();
		$subtitle = "Notify";
		$tickerText = 'text here...';
		$fcm_token = $user->fcm_token;
		$device_type = $user->device_type;
		$res = $this->pn($this->notificationKey, $fcm_token, "Test", "Notify", $subtitle, $tickerText, 'notifications');
		pr($res);
	}
	public function AppointmentFileWriteByUrlPP($patient_number, $appointment_id, $fileName)
	{
		if (!empty($fileName)) {
			$file = file_get_contents("https://doc.healthgennie.com/uploads/PatientDocuments/" . $patient_number . '/appointments/' . $appointment_id . '/' . $fileName);
			if (!empty($file)) {
				$filepath = base_path() . "/public/PatientDocuments/" . $patient_number . "/appointments/" . $appointment_id . '/';
				if (!is_dir($filepath)) {
					File::makeDirectory($filepath, $mode = 0777, true, true);
				}
				file_put_contents($filepath . $fileName, $file);
			}
			return 1;
		}
	}
	public function updateSlugForDoctor(Request $request)
	{
		echo "successsssss";
		die;
	}
	public function clean($string)
	{
		$string = str_replace(' ', '-', strtolower(trim($string))); // Replaces all spaces with hyphens.
		return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}


	public function checkAppointmentOrderStatus(Request $request)
	{
		$orders = AppointmentOrder::where(["order_status" => "0", "type" => "1"])->whereDate('created_at', Carbon::today())->get();
		// $orders = AppointmentOrder::where(["order_status"=>"0","type"=>"1"])->get();
		// $orders = AppointmentOrder::where(["id"=>4253])->get();
		if (count($orders) > 0) {
			foreach ($orders as $order) {
				$meta_data = json_decode($order['meta_data'], true);
				$mid = "yNnDQV03999999736874";
				$merchent_key = "&!VbTpsYcd6nvvQS";
				if (isset($meta_data['isPaytmTab']) && $meta_data['isPaytmTab'] == "true") {
					$mid = "MiniAp78932858151828";
					$merchent_key = "oS%zlWJKYh#GqL5P";
				}
				$paytmParams["body"] = array(
					"mid" => $mid,
					"orderId" => $order->id,
				);
				$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchent_key);
				$paytmParams["head"] = array(
					"signature"	=> $checksum
				);
				// $url = "https://securegw-stage.paytm.in/v3/order/status";
				$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

				/* for Production */
				$url = "https://securegw.paytm.in/v3/order/status";
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$response = curl_exec($ch);
				$response = json_decode($response, true);

				if ($response['body']['resultInfo']['resultCode'] == "01" && $response['body']['resultInfo']['resultStatus'] == "TXN_SUCCESS") {
					$resArr = [
						'ORDERID' => @$response['body']['orderId'],
						'TXNID' => @$response['body']['txnId'],
						'BANKTXNID' => @$response['body']['bankTxnId'],
						'PAYMENTMODE' => @$response['body']['paymentMode'],
						'BANKNAME' => @$response['body']['bankName'],
						'CURRENCY' => 'INR',
						'TXNAMOUNT' => @$response['body']['txnAmount'],
						'status' => 'success',
						'TXNDATE' => @$response['body']['txnDate']
					];
					$isExist = AppointmentOrder::select("id")->where(["id" => $order->id, 'order_status' => 0])->count();
					if ($isExist > 0) {
						$this->putAppointmentDataApp($order, $resArr, '');
					}
				}
			}
		}
		
		echo "success";
		die;
	}
	//ADD NEW ENCRYPT Function
	public function encrypt($plainText, $key)
	{
		$key = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		$encryptedText = bin2hex($openMode);
		return $encryptedText;
	}

	public function decrypt($encryptedText, $key)
	{
		$key = $this->hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText = $this->hextobin($encryptedText);
		$decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
		return $decryptedText;
	}
	//********** Hexadecimal to Binary function for php 4.0 version ********
	public function hextobin($hexString)
	{
		$length = strlen($hexString);
		$binString = "";
		$count = 0;
		while ($count < $length) {
			$subString = trim(substr($hexString, $count, 2));
			if (strlen($subString) == 2) {
				$packedString = pack("H*", $subString);
				if ($count == 0) {
					$binString = $packedString;
				} else {
					$binString .= $packedString;
				}
			}
			$count += 2;
		}
		return $binString;
	}

	public function notifyForCall(Request $request)
	{
		$message = 'Please join meeting';
		$title = 'Please join meeting';
		$subtitle = 'Video call';
		$tickerText = 'text here...';
		$today_date = date("Y-m-d");
		$current_time = date("H:i");
		$appointments = Appointments::where(["delete_status" => 1, "appointment_confirmation" => 1])->whereDate('start', 'like', $today_date)->get();

		if (count($appointments) > 0) {
			foreach ($appointments as $key => $appt) {
				$FiveMinBeforeTime = date('H:i', strtotime($appt->start . " -5 minutes"));
				$user = User::select(['fcm_token', 'device_type'])->where(['pId' => $appt->pId])->first();
				$fcm_token = $user->fcm_token;
				$device_type = $user->device_type;
				if ($current_time == $FiveMinBeforeTime) {
					if ($device_type == 1 && !empty($fcm_token)) {
						$notifyres = $this->pn($this->notificationKey, $fcm_token, $message, $title, $subtitle, $tickerText, 'notifications');
					} else if ($device_type == 2 && !empty($fcm_token)) {
						$iosnotify = $this->iosNotificationSend($fcm_token, $message, $title, 'notifications');
					}
				}
			}
		}
		die;
	}

	public function loginUserByPaytmData(Request $request)
	{
		if ($request->isMethod('post')) {
			$ress = null;
			$response = null;
			$user = null;
			$success = 0;
			$content = "";
			$code = $request->code;
			$paytmParams = array();
			$paytmParams["grant_type"] = "authorization_code";
			$paytmParams["scope"] = "basic";
			$paytmParams["code"] = $code; //"10adff55-cfc7-47de-b914-a0d0b3a85200";
			$paytmParams["client_id"] = "merchant-health-gennie-prod";

			$post_data = http_build_query($paytmParams) . "</br>";
			$auth = "Basic " . base64_encode("merchant-health-gennie-prod" . ":" . "lmhqj0GgD6RTiYlToQma1lgoz6uxSg4B");

			//$url = "https://accounts-uat.paytm.com/oauth2/v2/token";

			$url = "https://accounts.paytm.com/oauth2/v2/token";


			$ch = curl_init();
			curl_setopt_array($ch, array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_CUSTOMREQUEST => "POST",
				CURLOPT_POSTFIELDS => $post_data,
				CURLOPT_HTTPHEADER => array(
					"content-type: application/x-www-form-urlencoded",
					"Authorization: " . $auth
				),
			));
			$response = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);
			$response = json_decode($response, true);
			if (isset($response['access_token']) && !empty($response['access_token'])) {
				$access_token = $response['access_token'];
				//$auth = "Basic " . base64_encode("merchant-health-gennie-uat". ":" ."U4vIuakMdShfetKMX0DADFnCrKwCII5d");
				$url = "https://accounts.paytm.com/v2/user?fetch_strategy=profile_info,phone_number,email";
				// $url = "https://accounts.paytm.com/oauth2/v2/token";
				//$chl = curl_init();
				$chl = curl_init($url);
				curl_setopt($chl, CURLOPT_POST, false);
				curl_setopt($chl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($chl, CURLOPT_HTTPHEADER, array("verification_type: oauth_token", "data: " . $access_token, "Authorization:" . $auth));
				$ress = curl_exec($chl);
				// print_r($ress);
				$ress = json_decode($ress, true);
				$first_name = null;
				$last_name = null;
				if (isset($ress['profileInfo']['displayName']) && !empty($ress['profileInfo']['displayName'])) {
					$first_name = trim(strtok($ress['profileInfo']['displayName'], ' '));
					$last_name = trim(strstr($ress['profileInfo']['displayName'], ' '));
				}
				if (isset($ress['phoneInfo']['phoneNumber']) && !empty($ress['phoneInfo']['phoneNumber'])) {
					$user = User::where('mobile_no', $ress['phoneInfo']['phoneNumber'])->where('parent_id', 0)->first();
					if (!empty($user)) {
						User::where('id', $user->id)->update(array(
							'first_name' => $first_name,
							'last_name' => $last_name,
							'email' => (isset($ress['email']) ? $ress['email'] : null),
						));
					} else {
						$user = User::create([
							'mobile_no' => (isset($ress['phoneInfo']['phoneNumber']) ? $ress['phoneInfo']['phoneNumber'] : null),
							'email' => (isset($ress['email']) ? $ress['email'] : null),
							'first_name' =>  $first_name,
							'last_name' =>  $last_name,
							'parent_id' => 0,
							'status' =>  1,
							'device_type' =>  3,
							'login_type' =>  3,
						]);
						createUsersReferralCode($user->id);
					}
					Auth::login($user);
					$success = 1;
					$image_url = url("/") . "/img/avatar_2x.png";
					if (!empty($user->image)) {
						$image_url = getEhrUrl() . "/public/patients_pics/" . $user->image;
						if (does_url_exists($image_url)) {
							$image_url = $image_url;
						}
					}
					$name = "";
					if (!empty($user->first_name)) {
						$name = $user->first_name . " " . $user->last_name;
					} else {
						$name = $user->mobile_no;
					}
					$profile_url = url("/") . "/elite";
					$logout_url = url("/") . "/logout";
					$content = '<a class="dropdown-toggle paytm-login" role="button" data-toggle="dropdown" href="javascript:void(0);"><img  class="top-user-img" src="' . $image_url . '"/><span class="user-nametop">' . $name . '</span><span class="caret"></span></a><ul id="g-account-menu" class="dropdown-menu" role="menu">';
					// echo $content;die;
					if (Auth::id() != null && checkUserSubcriptionStatus(Auth::id())) {
						$content .=	'<div class="elite-member"><div class="bg">&nbsp;</div><div class="text">Elite</div></div>';
					}
					$content .=	'<li><a href="' . $profile_url . '"><img width="50" height="50" src="' . $image_url . '"/>' . $name . ' </a></li></ul>';
				}
			}
			return ["parent_response" => $response, 'response' => $ress, "user" => $user, "success" => $success, "content" => $content];
		}
		return "get method not allowed..";
	}
	public function enquiryFromSubmit(Request $request)
	{
		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$validator = Validator::make($data, [
					'name' => 'required|max:255',
					'email' => 'nullable|email|max:255',
					'mobile' => 'required|numeric',
				]);
				if ($validator->fails()) {
					$errors = $validator->errors();
					return ['status' => 3, 'errors' => $errors];
				}
				$user = EnquiryForm::create([
					'name' => ucfirst($data['name']),
					'mobile' => $data['mobile'],
					'email' => $data['email'] ?? '',
					'req_from' => isset($data['req_from']) ? $data['req_from'] : 0,
				]);
				Session::flash('message', "Examination Added Successfully");
				
				return ['status' => 1];
			}
			return ['status' => 2];
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}

	public function checkPaytmRewardStatus(Request $request)
	{

		try {
			$users = UserCashback::where(['status' => 0, 'paytm_status' => 'DE_002'])->whereDate('created_at', Carbon::today())->get();
			// dd($users);
			if (count($users) > 0) {
				foreach ($users as $user) {
					$order_id = $user->order_id;
					$paytmParams = array();
					$paytmParams["orderId"] = $order_id;
					$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
					$checksum = PaytmChecksum::generateSignature($post_data, "OJ0vuq8N&t3aAR7y");
					// $checksum = PaytmChecksum::generateSignature($post_data, "J7IeK&JZ6LwrfmBv");
					// $x_mid      = "FITKID54692936504563";
					$x_mid      = "FITKID61350170158252";
					$x_checksum = $checksum;
					/* for Staging */
					// $url = "https://staging-dashboard.paytm.com/bpay/api/v1/disburse/order/query";
					/* for Production */

					$url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/query";
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_POST, true);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					$response = curl_exec($ch);
					$response = json_decode($response, true);
					if ($response['status'] == "SUCCESS") {
						UserCashback::where('user_id', $user->id)->update([
							'meta_data' =>  json_encode($response),
							'status' =>  1,
							'paytm_status' => $response["statusCode"],
						]);
						User::where('id', $user->user_id)->update(['is_cashBack' => 1]);
						$userMobile = User::select("mobile_no")->where('id', $user->user_id)->first();
						$cashback = $response["result"]['amount'];
						if (!empty($userMobile->mobile_no) && !empty($userMobile->mobile_no)) {
							$message = urlencode("Congratulations ! You have earn a reward of Rs " . $cashback . "/- in your paytm wallet. Stay Healthy with Health Gennie Thanks Team Health Gennie");
							$this->sendSMS($userMobile->mobile_no, $message, '1707161587959478930');
						}
					}
				}
			}
			echo "Done";
			die;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function checkSubscriptionStatus(Request $request)
	{

		try {
			$users = UsersSubscriptions::where(['order_status' => 0])->whereDate('created_at', Carbon::today())->get();
			// dd($users);
			if (count($users) > 0) {
				foreach ($users as $order) {
					$orderID = $order->id;
					$order_id = $order->order_id;
					$meta_data = json_decode($order['meta_data'], true);
					$mid = "yNnDQV03999999736874";
					$merchent_key = "&!VbTpsYcd6nvvQS";

					$paytmParams["body"] = array(
						"mid" => $mid,
						"orderId" => $order_id,
					);
					$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchent_key);
					$paytmParams["head"] = array(
						"signature"	=> $checksum
					);
					// $url = "https://securegw-stage.paytm.in/v3/order/status";
					$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);

					/* for Production */
					$url = "https://securegw.paytm.in/v3/order/status";
					$ch = curl_init($url);
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					$response = curl_exec($ch);
					$response = json_decode($response, true);
					// pr($response);
					if ($response['body']['resultInfo']['resultCode'] == "01" && $response['body']['resultInfo']['resultStatus'] == "TXN_SUCCESS") {
						$subsIds[] = $orderID;
						$resArr = [
							'ORDERID' => @$response['body']['orderId'],
							'TXNID' => @$response['body']['txnId'],
							'BANKTXNID' => @$response['body']['bankTxnId'],
							'PAYMENTMODE' => @$response['body']['paymentMode'],
							'BANKNAME' => @$response['body']['bankName'],
							'CURRENCY' => 'INR',
							'TXNAMOUNT' => @$response['body']['txnAmount'],
							'status' => 'success',
							'TXNDATE' => @$response['body']['txnDate']
						];
						UserSubscriptionsTxn::create([
							'subscription_id' => $orderID,
							'tracking_id' => @$resArr['TXNID'],
							'bank_ref_no' => @$resArr['BANKTXNID'],
							'tran_mode' => @$resArr['PAYMENTMODE'],
							'card_name' => @$resArr['BANKNAME'],
							'currency' => @$resArr['CURRENCY'],
							'payed_amount' => @$resArr['TXNAMOUNT'],
							'tran_status' => @$resArr['STATUS'],
							'trans_date' => @$resArr['TXNDATE']
						]);
						$plan = userPlan::where('id', $meta_data['plan_id'])->first();
						$subscribedPlan = new UserSubscribedPlans;
						$subscribedPlan->subscription_id = $orderID;
						$subscribedPlan->plan_id = $plan->id;
						$subscribedPlan->plan_price = $plan->price;
						$subscribedPlan->discount_price = $plan->discount_price;
						$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
						$subscribedPlan->plan_duration = $plan->plan_duration;
						$subscribedPlan->appointment_cnt = $plan->appointment_cnt;
						$subscribedPlan->lab_pkg = $plan->lab_pkg;
						$subscribedPlan->meta_data = json_encode($plan);
						$subscribedPlan->save();

						//for the plan trail period
						$duration_type = $plan->plan_duration_type;
						if ($duration_type == "d") {
							$duration_in_days = $plan->plan_duration;
						} elseif ($duration_type == "m") {
							$duration_in_days = (30 * $plan->plan_duration);
						} elseif ($duration_type == "y") {
							$duration_in_days = (366 * $plan->plan_duration);
						}
						$end_date = date('Y-m-d', strtotime($subscribedPlan->created_at . '+' . $duration_in_days . ' days'));
						$PlanPeriods =  PlanPeriods::create([
							'subscription_id' => $orderID,
							'subscribed_plan_id' => $subscribedPlan->id,
							'user_plan_id' => $meta_data['plan_id'],
							'user_id' => $meta_data['user_id'],
							'start_trail' => date('Y-m-d'),
							'end_trail' => $end_date,
							'remaining_appointment' => $plan->appointment_cnt,
							'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
							'lab_pkg_remaining' => 0,
							'status' => 1
						]);
						UsersSubscriptions::where(["id" => $orderID])->update([
							'order_status' => 1,
						]);
						// if(!empty($meta_data['referral_user_id']) && !empty($meta_data['coupon_code'])){
						// if($meta_data['coupon_code'] != 'gennie21' &&  strtolower($meta_data['coupon_code']) != 'allen' && strtolower($meta_data['coupon_code']) != 'kota' && strcasecmp("gennie50",$meta_data['coupon_code']) != 0 && strcasecmp("AG4256",$meta_data['coupon_code']) != 0){

						// }
						// }
						if (checkRefCodeIsExist($meta_data['coupon_code']) == false) {
							$this->walletCashbackSubs($meta_data, $orderID, $meta_data['plan_id']);
						}
						$this->sendUserSubscriptionMail($orderID, 1, "success");
					}
				}
			}
			echo "Done";
			die;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function shp(Request $request, $appId)
	{

		try {
			$appId = base64_decode($appId);
			header("Content-type:application/pdf");
			header("Content-Disposition:attachment;filename=clinicalNotePrint.pdf");
			$presDta = UserPrescription::select(["prescription", "type", "patient_number"])->where(['appointment_id' => $appId])->orderBy("id", "DESC")->first();
			if (!empty($presDta->prescription) && $presDta->type == "1") {
				$this->writeClinicNoteFile($presDta->patient_number, $presDta->prescription);
				$preUrl = getPath("uploads/PatientDocuments/" . $presDta->patient_number . "/misc/clinicalNotePrint.pdf");
				readfile($preUrl);
			} else return '';
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function writeClinicNoteFile($patient_number, $prescription)
	{
		try {
			$docPath = 'uploads/PatientDocuments/' . $patient_number . '/misc/';
			$output = PDF::loadHTML($prescription)->output();
			if ($output != null) {
				Storage::disk('s3')->put($docPath . 'clinicalNotePrint.pdf', $output, 'public');
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function rad($appId)
	{

		try {
			header("Location: https://docs.google.com/gview?url=" . url("/") . "/shp/" . $appId); /* Redirect browser */
			exit();
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function getPaytmOrders()
	{
		try {
			$mid = "yNnDQV03999999736874";
			$merchent_key = "&!VbTpsYcd6nvvQS";
			$paytmParams["body"] = array(
				"mid"           => $mid,
				"fromDate"   => "2021-08-01",
				"toDate"       => "2021-08-15",
				"orderSearchType"   => "TRANSACTION",
				"orderSearchStatus"   => "SUCCESS",
				"pageNumber"   => 1,
				"pageSize"   => 50,
				"searchConditions"     => array(
					// "searchKey"     => "VAN_ID",
					// "searchValue"  => "PYI3831611899004",
				),
			);
			$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchent_key);
			$paytmParams["head"] = array(
				"signature"	=> $checksum,
				"tokenType"    => $checksum,
				"requestTimestamp"     => ""
			);
			$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
			/* for Production */
			$url = "https://securegw-stage.paytm.in/merchant-passbook/search/list/order/v2";
			$ch = curl_init($url);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
			$response = curl_exec($ch);
			$response = json_decode($response, true);
			pr($response);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function helpIndiaLogin(Request $request)
	{
		try {
			if (!empty($request->mobile)) {
				$mobile = $request->mobile;
				$user = User::where('mobile_no', $mobile)->where('parent_id', 0)->first();
				if (empty($user)) {
					$user = User::create([
						'mobile_no' =>  $mobile,
						'parent_id' => 0,
						'status' =>  1,
						'device_type' =>  3,
						'login_type' =>  2,
						'organization' =>  9,
					]);
					createUsersReferralCode($user->id);
				}
				Auth::login($user);
				Session::put('wltSts', 1);
				Session::save();
				return redirect()->route('login');
			} else {
				return redirect()->route('login');
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function helpIndPay(Request $request)
	{
		try {

			$data = $request->all();
			if (substr($data['merchantTxnId'], 0, 4) == "SUBS") {
				$orderData = UsersSubscriptions::select(["id", "meta_data", "order_total"])->where(["order_id" => $data['merchantTxnId']])->first();
				$orderID = $orderData->id;
				if ($data['status'] == 'SUCCESS') {
					$trackingId = rand(10000, 1000000);
					UserSubscriptionsTxn::create([
						'subscription_id' => $orderID,
						'tracking_id' => $trackingId,
						'bank_ref_no' => '',
						'tran_mode' => 'WALLET',
						'card_name' => 'WALLET',
						'currency' => 'INR',
						'payed_amount' => $orderData->order_total,
						'tran_status' => 'TXN_SUCCESS',
						'trans_date' => date('Y-m-d H:i:s')
					]);
					$meta_data = json_decode($orderData->meta_data, true);
					$plan = userPlan::where('id', $meta_data['plan_id'])->first();
					$subscribedPlan = new UserSubscribedPlans;
					$subscribedPlan->subscription_id = $orderID;
					$subscribedPlan->plan_id = $plan->id;
					$subscribedPlan->plan_price = $plan->price;
					$subscribedPlan->discount_price = $plan->discount_price;
					$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
					$subscribedPlan->plan_duration = $plan->plan_duration;
					$subscribedPlan->appointment_cnt = $plan->appointment_cnt;
					$subscribedPlan->lab_pkg = $plan->lab_pkg;
					$subscribedPlan->meta_data = json_encode($plan);
					$subscribedPlan->save();

					//for the plan trail period
					$duration_type = $plan->plan_duration_type;
					if ($duration_type == "d") {
						$duration_in_days = $plan->plan_duration;
					} elseif ($duration_type == "m") {
						$duration_in_days = (30 * $plan->plan_duration);
					} elseif ($duration_type == "y") {
						$duration_in_days = (366 * $plan->plan_duration);
					}
					$end_date = date('Y-m-d', strtotime($subscribedPlan->created_at . '+' . $duration_in_days . ' days'));
					$PlanPeriods =  PlanPeriods::create([
						'subscription_id' => $orderID,
						'subscribed_plan_id' => $subscribedPlan->id,
						'user_plan_id' => $meta_data['plan_id'],
						'user_id' => $meta_data['user_id'],
						'start_trail' => date('Y-m-d'),
						'end_trail' => $end_date,
						'remaining_appointment' => $plan->appointment_cnt,
						'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
						'lab_pkg_remaining' => 0,
						'status' => 1
					]);
					UsersSubscriptions::where(["id" => $orderID])->update([
						'order_status' => 1,
					]);
					ApptLink::where(["user_id" => $meta_data['user_id'], 'order_id' => $data['merchantTxnId']])->update(['status' => 1]);
					if (!empty($meta_data['coupon_code']) && checkRefCodeIsExist($meta_data['coupon_code']) == false) {
						$this->walletCashbackSubs($meta_data, $orderID, $meta_data['plan_id']);
					}
					$this->sendUserSubscriptionMail($orderID, 1, "success");
					if (isset($meta_data['patientInfo']) && !empty($meta_data['patientInfo'])) {
						$patientInfo = @$meta_data['patientInfo'];
						$this->addApptForPlan($patientInfo);
					}
					$orgPay = OrganizationPayment::where(['organization_id' => 9])->where('remaining_amount', '!=', '0')->orderBy('id', 'asc')->first();
					$actAmt = $orgPay->remaining_amount - $orderData->order_total;
					OrganizationPayment::where(['id' => $orgPay->id])->update(['remaining_amount' => $actAmt]);
					$url = url("/") . '/plan/success?order_id=' . base64_encode(@$data['merchantTxnId']);
					return Redirect::to($url);
				} else {
					UsersSubscriptions::where(["id" => $orderID])->update([
						'order_status' => 3,
					]);
					ApptLink::where(['order_id' => $data['merchantTxnId']])->update(['status' => 2]);
					$url = url("/") . '/plan/cancel?order_id=' . base64_encode(@$data['merchantTxnId']);
					return Redirect::to($url);
				}
			} else {
				if ($data['status'] == 'SUCCESS') {
					$order = AppointmentOrder::where(["id" => $data['merchantTxnId'], 'order_status' => 0])->first();
					if (!empty($order->meta_data)) {
						$this->putAppointmentDataApp($order, '', $order);
					}
					$orgPay = OrganizationPayment::where(['organization_id' => 9])->where('remaining_amount', '!=', '0')->orderBy('id', 'asc')->first();
					$actAmt = $orgPay->remaining_amount - $order->order_total;
					OrganizationPayment::where(['id' => $orgPay->id])->update(['remaining_amount' => $actAmt]);
					return Redirect::to('https://www.healthgennie.com/appointment/success?order_id=' . base64_encode($data['merchantTxnId']));
				} else {
					return Redirect::to('https://www.healthgennie.com/appointment/cancel?order_id=' . base64_encode($data['merchantTxnId']));
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function doitLandPage(Request $request)
	{


		try {


			$data = $request->all();
			//pr($data); 
			//if(Session::get('lanEmitraData') != null){
			//Session::forget('lanEmitraData');
			$postdata = http_build_query(
				array(
					'toBeDecrypt' => $data['encData']
				)
			);

			$opts = array(
				'http' =>
				array(
					'method'  => 'POST',
					'header'  => 'Content-Type: application/x-www-form-urlencoded',
					'content' => $postdata
				)
			);

			$context  = stream_context_create($opts);
			//for test
			//$result = file_get_contents('http://emitrauat.rajasthan.gov.in/webServicesRepositoryUat/emitraAESDecryption', false, $context);

			$result = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESDecryption', false, $context);
			$encData = json_decode($result, TRUE);
			$data['decryptData'] = $encData;
			//pr($data); 

			$plans = userPlan::Where(["delete_status" => '1', 'status' => 1])->whereIn("type", array(1, 2))->orderBy('price', 'asc')->get();
			if (count($plans) > 0) {
				$newPlans = array();
				foreach ($plans as $plan) {
					$plan['pkg_data'] = availPackDetails($plan->price - $plan->discount_price);
				}

				foreach ($plans as $plan) {
					$price = $plan->price - $plan->discount_price;
					$newPlans[] = array('id' => $plan->id, 'price' => $price, 'data' => $plan);
				}
				//pr($newPlans);
				$price = array_column($newPlans, 'price');
				array_multisort($price, SORT_ASC, $newPlans);
				// pr($newPlans);
				$finalPlans = array();
				foreach ($newPlans as $plan) {
					$finalPlans[] = $plan['data'];
				}
				//pr($finalPlans);

			}
			Session::put('lanEmitraData', $data);
			Session::save();
			//pr($result1);
			return view($this->getView('pages.doit-land'), ['plans' => $finalPlans]);
			//}


		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function getLabs(Request $request)
	{


		try {

			$title = $request->searchText;
			$query = DefaultLabs::select(['id', 'title', 'short_name'])->where(DB::raw('CONCAT_WS(title," ",short_name," ")'), 'like', '%' . $title . '%');
			if (!empty($request->company_id)) {
				$query->where('company_id', $request->company_id);
			}
			$labs = $query->where(['status' => 1, 'delete_status' => 1])->get();
			return $labs;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function getLabByCompany(Request $request)
	{

		try {
			$search = strtolower($request->searchText);
			$query = LabCollection::with("DefaultLabs", "LabCompany")->where('delete_status', '=', '1');
			if (!empty($search)) {
				$query->whereHas("DefaultLabs", function ($q) use ($search) {
					$q->where(DB::raw('concat(default_labs.title," ",IFNULL(default_labs.short_name,""))'), 'like', '%' . $search . '%');
				});
			}

			if (!empty($request->company_id)) {
				$query->where('company_id', $request->company_id);
			}
			$labs = $query->orderBy('id', 'desc')->get();
		
			return $labs;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function insertToDbThyData()
	{


		try {

			$products = File::get(public_path('thyrocare-data/All.txt'));
			$products = json_decode($products, true);
			if (isset($products['master'])) {
				if (isset($products['master']['profile']) && count($products['master']['profile']) > 0) {
					foreach ($products['master']['profile'] as $raw) {
						if ($this->checkThisLabIsExists($raw['name'], $raw['code'])) {
							$this->updateThyrocareLab($raw);
						} else {
							$this->createThyrocareLab($raw);
						}
					}
				}
				if (isset($products['master']['tests']) && count($products['master']['tests']) > 0) {
					foreach ($products['master']['tests'] as $raw) {
						if ($this->checkThisLabIsExists($raw['name'], $raw['code'])) {
							$this->updateThyrocareLab($raw);
						} else {
							$this->createThyrocareLab($raw);
						}
					}
				}
			}
			$offer_products = File::get(public_path('thyrocare-data/Offer.txt'));
			$offer_products = json_decode($offer_products, true);
			if (count($offer_products) > 0) {
				foreach ($offer_products as $raw) {
					if ($this->checkThisLabIsExists($raw['name'], $raw['code'])) {
						$this->updateThyrocareLab($raw);
					} else {
						$this->createThyrocareLab($raw);
					}
				}
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function updateThyrocareLab($raw)
	{

		try {


			ThyrocareLab::where(['name' => $raw['name'], 'code' => $raw['code']])->update([
				// 'name'=>$raw['name'],
				// 'code'=>$raw['code'],
				'aliasName' => $raw['aliasName'],
				'type' => $raw['type'],
				'childs' => json_encode($raw['childs']),
				'rate' => json_encode($raw['rate']),
				'testCount' => $raw['testCount'],
				'benMin' => $raw['benMin'],
				'benMultiple' => $raw['benMultiple'],
				'benMax' => $raw['benMax'],
				'payType' => $raw['payType'],
				'serum' => $raw['serum'],
				'edta' => $raw['edta'],
				'urine' => $raw['urine'],
				'fluoride' => $raw['fluoride'],
				'fasting' => $raw['fasting'],
				'new' => $raw['new'],
				'diseaseGroup' => $raw['diseaseGroup'],
				'units' => $raw['units'],
				'volume' => $raw['volume'],
				'normalVal' => $raw['normalVal'],
				'groupName' => $raw['groupName'],
				'margin' => $raw['margin'],
				'hc' => $raw['hc'],
				'specimenType' => $raw['specimenType'],
				'testNames' => $raw['testNames'],
				'additionalTests' => $raw['additionalTests'],
				'imageLocation' => $raw['imageLocation'],
				'imageMaster' => json_encode($raw['imageMaster']),
				'validTo' => $raw['validTo'],
				'hcrInclude' => $raw['hcrInclude'],
				'ownPkg' => $raw['ownPkg'],
				'bookedCount' => $raw['bookedCount'],
				'barcodes' => $raw['barcodes'],
				'category' => $raw['category'],
			]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function createThyrocareLab($raw)
	{

		try {


			ThyrocareLab::create([
				'name' => $raw['name'],
				'common_name' => $raw['name'],
				'code' => $raw['code'],
				'aliasName' => $raw['aliasName'],
				'type' => $raw['type'],
				'childs' => json_encode($raw['childs']),
				'rate' => json_encode($raw['rate']),
				'testCount' => $raw['testCount'],
				'benMin' => $raw['benMin'],
				'benMultiple' => $raw['benMultiple'],
				'benMax' => $raw['benMax'],
				'payType' => $raw['payType'],
				'serum' => $raw['serum'],
				'edta' => $raw['edta'],
				'urine' => $raw['urine'],
				'fluoride' => $raw['fluoride'],
				'fasting' => $raw['fasting'],
				'new' => $raw['new'],
				'diseaseGroup' => $raw['diseaseGroup'],
				'units' => $raw['units'],
				'volume' => $raw['volume'],
				'normalVal' => $raw['normalVal'],
				'groupName' => $raw['groupName'],
				'margin' => $raw['margin'],
				'hc' => $raw['hc'],
				'specimenType' => $raw['specimenType'],
				'testNames' => $raw['testNames'],
				'additionalTests' => $raw['additionalTests'],
				'imageLocation' => $raw['imageLocation'],
				'imageMaster' => json_encode($raw['imageMaster']),
				'validTo' => $raw['validTo'],
				'hcrInclude' => $raw['hcrInclude'],
				'ownPkg' => $raw['ownPkg'],
				'bookedCount' => $raw['bookedCount'],
				'barcodes' => $raw['barcodes'],
				'category' => $raw['category'],
			]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function checkThisLabIsExists($name, $code)
	{

		try {
			return ThyrocareLab::where(['name' => $name, 'code' => $code])->count() > 0 ? true : false;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function createOrderFromAdmin(Request $request)
	{
		try {

			if (!empty($request->id)) {
				$id = base64_decode($request->id);
				$user = User::where('id', $id)->where('parent_id', 0)->first();
				if (!empty($user)) {
					Auth::login($user);
					Session::put('LabCAdmin', 1);
					Session::save();
					return redirect()->route('LabDashboard');
				}
				return redirect()->route('login');
			} else {
				return redirect()->route('login');
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function updateOrganizationData()
	{
		$users = User::select('id', 'mobile_no', 'organization')->where('parent_id', 0)->whereNotNull('organization')->get();
		if ($users->count() > 0) {
			foreach ($users as $raw) {
				User::where(['mobile_no' => $raw->mobile_no])->where('parent_id', '!=', 0)->update([
					'organization' => $raw->organization,
				]);
			}
		}
		return "Success";
	}
	public function updateUsersReferralCode()
	{
		$users = User::select('id')->where('parent_id', 0)->get();
		if ($users->count() > 0) {
			foreach ($users as $raw) {
				if (UserDetails::select("id")->where('user_id', $raw->id)->count() == 0) {
					// $referral_code = $this->getRefCode();
					// if($this->refCodeExist($referral_code) > 0) {
					$referral_code = $this->getUniqueRefCode($this->getRefCode());
					// }
					UserDetails::create([
						'user_id' => $raw->id,
						'referral_code' => $referral_code,
					]);
				}
			}
		}
		return "Success";
	}
	public function getRefCode()
	{
		return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 10, 6);
	}
	public function refCodeExist($refCode)
	{
		return UserDetails::select("id")->where('referral_code', $refCode)->count();
	}
	public function getUniqueRefCode($referral_code)
	{
		if ($this->refCodeExist($referral_code) > 0) {
			return $this->getUniqueRefCode($this->getRefCode());
		} else {
			return $referral_code;
		}
	}
	public function paymentVerificationByEmitra()
	{

		$time = (string) strtotime("now");
		// $checkArr = array(
		// 'REQTIMESTAMP' => $time,
		// );
		$checkArr = array(
			'SSOID' => "KUMAWAT.AKASH1",
			'REQUESTID' => "SUBS14159",
			'REQTIMESTAMP' => $time,
			'SSOTOKEN' => "dGxwNWxLVWxxZnI3ZjV3cHhtU3BnOUxhSTYrS2NLTTN0ZXJ4WXVuN1lqaytRRnRkQTh2ckNRQUJ6L2pQY3RCNG1LTWNObzhhT3ZwTEZaUFFGM3U4cWIrTDJJOTRjOTBESlVlTW1Nb0VTQVZjYTBvMzVVa2hBSXliN3I4eG1HUWIvb0h4NWs1azMvZGhOU1BSbldONnVWR3FueHVDRmp3djhuUk1OdWhkdkNhWXpvNXZ3eTRzZ0hoakhMMVBQcnRO"
		);
		$postdata1 = http_build_query(
			array(
				'toBeCheckSumString' => json_encode($checkArr)
			)
		);
		$opts1 = array('http' =>
		array(
			'method'  => 'POST',
			'header'  => 'Content-Type: application/x-www-form-urlencoded',
			'content' => $postdata1
		));
		$context1  = stream_context_create($opts1);
		$checksum = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum', false, $context1);

		$paymentArr = array(
			'MERCHANTCODE' => 'FITKID2022',
			'REQUESTID' => "SUBS14159",
			'SERVICEID' => "8992",
			'SSOTOKEN' => "0",
			'CHECKSUM' => $checksum
		);
		$postdata2 = http_build_query(array('toBeEncrypt' => json_encode($paymentArr)));
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


		$resultA = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/getTokenVerifyNewProcessByRequestIdWithEncryption', false, $context3);

		$postdataA = http_build_query(
			array(
				'toBeDecrypt' => $resultA
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
		$response = json_decode($resultA, TRUE);
		dd($response);
	}
	public function healthAsses(Request $request)
	{
		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$isExists = DB::table('mental_health_aases')->where('mobile', $data['mobile'])->count();
				if ($isExists == 0) {
					DB::table('mental_health_aases')->insert([
						'name' => $data['name'],
						'gender' => $data['gender'],
						'mobile' => $data['mobile'],
						'age' => $data['age'],
						'ques_1' => isset($data['ques_1']) ? $data['ques_1'] : null,
						'ques_2' => isset($data['ques_2']) ? $data['ques_2'] : null,
						'ques_3' => isset($data['ques_3']) ? $data['ques_3'] : null,
						'ques_4' => isset($data['ques_4']) ? $data['ques_4'] : null,
						'ques_5' => isset($data['ques_5']) ? $data['ques_5'] : null,
						'ques_6' => isset($data['ques_6']) ? $data['ques_6'] : null,
						'ques_7' => isset($data['ques_7']) ? $data['ques_7'] : null,
						'ques_8' => isset($data['ques_8']) ? $data['ques_8'] : null,
						'ques_9' => isset($data['ques_9']) ? $data['ques_9'] : null,
						'ques_10' => isset($data['ques_10']) ? $data['ques_10'] : null,
						'ques_11' => isset($data['ques_11']) ? $data['ques_11'] : null,
						'ques_12' => isset($data['ques_12']) ? $data['ques_12'] : null,
						'ques_13' => isset($data['ques_13']) ? $data['ques_13'] : null,
						'ques_14' => isset($data['ques_14']) ? $data['ques_14'] : null,
						'ques_15' => isset($data['ques_15']) ? $data['ques_15'] : null,
						'ques_16' => isset($data['ques_16']) ? $data['ques_16'] : null,
						'ques_17' => isset($data['ques_17']) ? $data['ques_17'] : null,
						'ques_18' => isset($data['ques_18']) ? $data['ques_18'] : null,
						'ques_19' => isset($data['ques_19']) ? $data['ques_19'] : null,
						'ques_20' => isset($data['ques_20']) ? $data['ques_20'] : null,
						'ques_21' => isset($data['ques_21']) ? $data['ques_21'] : null,
						'ques_22' => isset($data['ques_22']) ? $data['ques_22'] : null,
						'ques_23' => isset($data['ques_23']) ? $data['ques_23'] : null,
						'ques_24' => isset($data['ques_24']) ? $data['ques_24'] : null,
						'ques_25' => isset($data['ques_25']) ? $data['ques_25'] : null,
						'ques_26' => isset($data['ques_26']) ? $data['ques_26'] : null,
					]);
					return 'Your Form submitted successfully for Medical Health Assessment.';
				}
				return 'Your Form already submitted!';
			} else {
				return view('pages.medical-form');
			}
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function subscriptionAds(Request $request)
	{
		return view('pages.subscription-ad');
	}
	public function changePdfHeader(Request $request)
	{
		// try{
		if ($request->isMethod('post')) {
			// dd($request->all());
			$data = $request->all();
			if (isset($data['pdf_doc']) && $request->hasFile('pdf_doc')) {
				// $pdfDoc = $request->file('pdf_doc');
				// pr();
				$this->getPdf($_FILES["pdf_doc"]["tmp_name"]);
			}
		} else {
			return view('pages.pdf-page');
		}
		// }
		// catch(Exception $e){
		// return $e->getMessage();
		// }
	}

	public function getPdf($path)
	{
		$pdf = new PDFHF();
		$pdf->AliasNbPages();
		// $pdf->AddPage();
		// $pdf->SetFont('Times','',12);
		$pagecount = $pdf->setSourceFile($path);
		if ($pagecount > 0) {
			for ($i = 1; $i <= $pagecount; $i++) {
				$tplId = $pdf->importPage($i);
				$pdf->AddPage();
				$pdf->useTemplate($tplId);
			}
		}
		$pdf->Output("labTest.pdf", "D");
		// $pdf->Output();
		exit;
	}

	public function downloadsubrecAdmin(Request $request, $id)
	{
		$id = base64_decode($id);
		$subscription = UsersSubscriptions::with(['User', 'UserSubscribedPlans.Plans', 'UserSubscriptionsTxn', 'PlanPeriods'])->where('order_id', $id)->first();
		$pdf = Pdf::loadView('subscription.DownloadSubsReceiptPDF', compact('subscription'));
		return $pdf->download('pdfviewforSubscription.pdf');
	}
	public function sendOtpToDevice($fcm_token, $otp)
	{
		Log::info('$fcm_token', [$fcm_token]);
		// Get OAuth 2.0 Bearer token
		// $bearerToken = $this->getBearerToken();
		$pathToServiceAccount = storage_path('pplivev10-firebase-adminsdk-w02bb-80fa4017ff.json');
		$this->client = new Client();
		$this->client->setAuthConfig($pathToServiceAccount);
		$this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
		// Generate OAuth2 access token
		$this->accessToken = $this->client->fetchAccessTokenWithAssertion()['access_token'];

		// The OTP to be sent
		// $otp = $otp; // Generate a 6-digit OTP

		// Notification payload
		$notification = [
			'title' => 'HealthGennie Login OTP',
			'body'  => 'Your OTP is: ' . $otp . '. It is valid for 3 minutes.',
			'image' => 'https://doc.healthgennie.com/img/web/health_gennie_logo.png'  // Add image URL here

		];
		Log::info('$this->accessToken', [$this->accessToken]);
		Log::info(' $notification', [$notification]);

		// Data payload (ensure all values are strings)
		$data = [
			'otp' => (string)$otp,
		];

		// FCM API URL for HTTP v1
		$fcmUrl = 'https://fcm.googleapis.com/v1/projects/pplivev10/messages:send';

		// Prepare the payload for FCM
		$fcmNotification = [
			'message' => [
				'token' => $fcm_token, // The FCM token of the device
				'notification' => $notification,
				'data' => $data,
			],
		];

		// Headers for FCM request
		$headers = [
			"Authorization: Bearer $this->accessToken",
			'Content-Type: application/json',
		];

		// Initialize CURL to send the request to FCM
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $fcmUrl);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

		// Execute CURL and get the result
		$result = curl_exec($ch);
		curl_close($ch);

		if ($result === FALSE) {
			die('FCM Send Error: ' . curl_error($ch));
		}

		// Log the result (optional)
		Log::info('FCM response: ' . $result);

		// Return the generated OTP
		return $otp;
	}

	protected function loadMoreData($query, $perPage)
	{
		return $query->paginate($perPage);
	}

	function razorpaySubscriptionPlan(Request $request)
{
    $data = $request->all();
	Log::info('$data', [$data]);
    $orderId = base64_decode($data['order_id']);
    Log::info('Decoded Order ID:', [$orderId]);

	if (substr($orderId, 0, 4) == "SUBS") {
		Log::info('ffffffff');
		CcavenueResponse::create([
			'slug' => 'plan_subscription',
			'meta_data' => json_encode($data),
		]);
		
		$orderData = UsersSubscriptions::select(["id", "meta_data"])->where(["order_id" =>  $orderId])->first();
		$orderID = $orderData->id;
		$paymentItem = $data['response']['data']['items'][0] ?? null;
		
		Log::info('$orderData ', [$orderData ]);
		Log::info('$$paymentItem  ', [$paymentItem  ]);

		$userSubscription = UserSubscriptionsTxn::create([
			'subscription_id' => $orderID,
			'tracking_id' => @$paymentItem['acquirer_data']['upi_transaction_id'],
			'bank_ref_no' => @$paymentItem['acquirer_data']['bank_transaction_id'], 
			'tran_mode' => @$paymentItem['method'],
			'card_name' => @$paymentItem['card'],
			'currency' => @$paymentItem['currency'],
			'payed_amount' => @$paymentItem['amount'],
			'tran_status' => @$paymentItem['status'],
			'trans_date' => @$paymentItem['created_at']
		]);
		Log::info('ssssssssssssssssssssssssssssssssssss', [$userSubscription]);
		if ($paymentItem['status'] == 'captured') {
			Log::info('fffffffffffffffffffffffffffffffff');
			$meta_data = json_decode($orderData->meta_data, true);
			$plan = userPlan::where('id', $meta_data['plan_id'])->first();
			$subscribedPlan = new UserSubscribedPlans;
			$subscribedPlan->subscription_id = $orderID;
			$subscribedPlan->plan_id = $plan->id;
			$subscribedPlan->plan_price = $plan->price;
			$subscribedPlan->discount_price = $plan->discount_price;
			$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
			$subscribedPlan->plan_duration = $plan->plan_duration;
			$subscribedPlan->appointment_cnt = $plan->appointment_cnt;
			$subscribedPlan->lab_pkg = $plan->lab_pkg;
			$subscribedPlan->meta_data = json_encode($plan);
			$subscribedPlan->save();
			Log::info('==========opppp=================pp=======', [$plan]);

			//for the plan trail period
			$duration_type = $plan->plan_duration_type;
			if ($duration_type == "d") {
				$duration_in_days = $plan->plan_duration;
			} elseif ($duration_type == "m") {
				$duration_in_days = (30 * $plan->plan_duration);
			} elseif ($duration_type == "y") {
				$duration_in_days = (366 * $plan->plan_duration);
			}
			// Log::info('==========opppp=================pp=========================');
			$end_date = date('Y-m-d', strtotime($subscribedPlan->created_at . '+' . $duration_in_days . ' days'));
			Log::info("============", [$end_date]);
			Log::info("date", [date('Y-m-d')]);
			$PlanPeriods =  PlanPeriods::create([
				'subscription_id' => $orderID,
				'subscribed_plan_id' => $subscribedPlan->id,
				'user_plan_id' => $meta_data['plan_id'],
				'user_id' => $meta_data['user_id'],
				'start_trail' => date('Y-m-d'),
				'end_trail' => $end_date,
				'remaining_appointment' => $plan->appointment_cnt,
				'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
				'lab_pkg_remaining' => 0,
				'status' => 1
			]);
			Log::info('=====================', [$PlanPeriods]);
			UsersSubscriptions::where(["id" => $orderID])->update([
				'order_status' => 1,
			]);
			updateWallet($meta_data['user_id'], 4, 'subscription_reward');
			availWalletAmount($meta_data['user_id'], 7, @$meta_data['availWalletAmt']);
			ApptLink::where(["user_id" => $meta_data['user_id'], 'order_id' => $data['order_id']])->update(['status' => 1]);
			if (!empty($meta_data['coupon_code']) && checkRefCodeIsExist($meta_data['coupon_code']) == false) {
				$this->walletCashbackSubs($meta_data, $orderID, $meta_data['plan_id']);
			}
			// $this->sendUserSubscriptionMail($orderID, 1, "success");
			if (isset($meta_data['patientInfo']) && !empty($meta_data['patientInfo'])) {
				$patientInfo = @$meta_data['patientInfo'];
				$this->addApptForPlan($patientInfo);
			}
			$url = url("/") . '/plan/success?order_id=' . base64_encode(@$data['acquirer_data']['upi_transaction_id']);
			return Redirect::to($url);
		} else {
			UsersSubscriptions::where(["id" => $orderID])->update([
				'order_status' => 3,
			]);
			ApptLink::where(['order_id' => $data['order_id']])->update(['status' => 2]);
			$url = url("/") . '/plan/cancel?order_id=' . base64_encode(@$data['acquirer_data']['upi_transaction_id']);
			return Redirect::to($url);
		}
	} 
	elseif (isset($data['order_type']) && $data['order_type'] == 'LAB') {
		Log::info('innnn');

		CcavenueResponse::create([
			'slug' => 'lab_order',
			'meta_data' => json_encode($data),
		]);
		$lab = LabOrders::select(["id", "user_id", "meta_data", "type"])->where(["orderId" =>  $orderId])->first();
		Log::info('innnn', [$lab]);

		$labId = $lab->id;
		$paymentItem = $data['response']['data']['items'][0] ?? null;
		$lab12 = LabOrderTxn::create([
			'order_id' => $lab->id,
			'tracking_id' => @$paymentItem['acquirer_data']['upi_transaction_id'],
			'bank_ref_no' => @$paymentItem['acquirer_data']['bank_transaction_id'], 
			'tran_mode' => @$paymentItem['method'],
			'card_name' => @$paymentItem['card'],
			'currency' => @$paymentItem['currency'],
			'payed_amount' => @$paymentItem['amount'],
			'tran_status' => @$paymentItem['status'],
			'trans_date' =>  date("Y-m-d h:i:s"),
		]);
		Log::info('$lab12', [$lab12]);
		if ($paymentItem['status'] == 'captured') {
			$meta_data = json_decode($lab->meta_data);
			if ($lab->type == null) {

				LabOrders::where(["orderId" => $orderId])->update([
					'status' => 1,
					'is_free_appt' => 1,
					'order_status' => 'YET TO CONFIRM',
				]);

				LabCart::where(['user_id' => $lab->user_id])->delete();
				Session::forget('CartPackages');
				$appt_date = date("d-m-Y", strtotime($meta_data->appt_date));
				$appt_time = date("h:i A", strtotime($meta_data->appt_date));
				$message = urlencode('Dear ' . $meta_data->order_by . ', Your Lab Test (' . $meta_data->product . ') booking is confirmed with Healthgennie on ' . $appt_date . ' at ' . $appt_time . '.Please be available at your location at the given time. Thanks Team Health Gennie');
				$this->sendSMS($meta_data->mobile, $message, '1707165122333414122');
				return Redirect::to('https://www.healthgennie.com/lab-order/success?order_id=' . base64_encode($orderId));
			} else {
				LabOrders::where(["orderId" => $orderId])->update([
					'status' => 1,
					'is_free_appt' => 1,
					'order_status' => 'YET TO CONFIRM',
				]);

				LabCart::where(['user_id' => $lab->user_id])->delete();
				Session::forget('CartPackages');
				$appt_date = date("d-m-Y", strtotime($meta_data->appt_date));
				$appt_time = date("h:i A", strtotime($meta_data->appt_date));
				$message = urlencode('Dear ' . $meta_data->order_by . ', Your Lab Test (' . $meta_data->product . ') booking is confirmed with Healthgennie on ' . $appt_date . ' at ' . $appt_time . '.Please be available at your location at the given time. Thanks Team Health Gennie');
				$this->sendSMS($meta_data->mobile, $message, '1707165122333414122');

				$message = urlencode('This patient(' . $meta_data->order_by . ') has booked a lab test (' . $meta_data->product . ') with Reliable lab on ' . $appt_date . ' at ' . $appt_time . '. Patient Mobile : ' . $meta_data->mobile . ' Thanks Team Health Gennie');
				$this->sendSMS(8905557252, $message, '1707165122295538821');
				$updateWallet= updateWallet($lab->user_id, 3, 'lab_reward');
				Log::info('updateWallet', [$updateWallet]);
				$availWalletAmount = availWalletAmount($lab->user_id, 5, @$meta_data->availWalletAmt);
				Log::info('availWalletAmount', [$availWalletAmount]);
				return Redirect::to(url('/lab-order/success') . '?order_id=' . base64_encode($orderId));

			}
		} else {
			LabOrders::where(["orderId" => $orderId])->update([
				'status' => 2,
			]);
			return Redirect::to('https://www.healthgennie.com/lab-order/cancel?order_id=' . base64_encode($orderId));
		}
	}
	 else {

	
    Log::info('Request Data:', [$data]);

    // Fetch the Appointment Order
    $order = AppointmentOrder::where('id', $orderId)->first();

    if (!$order) {
        Log::error('Order not found for ID:', [$orderId]);
        return response()->json(['error' => 'Order not found'], 404);
    }

    $meta_data = json_decode($order->meta_data);

    // Check if the status is 'captured'
    $paymentItem = $data['response']['data']['items'][0] ?? null;
	Log::info('paymentItem', [$paymentItem]);

	if ($paymentItem['status'] === 'captured') {
		$order = AppointmentOrder::where(["id" => $order->id, 'order_status' => 0])->first();
		Log::info('order22JAN', [$order]);
		if (!empty($order->meta_data)) {
			$this->putAppointmentDataApp($order, $paymentItem);
		}
		//return \Redirect::to('https://www.healthgennie.com/appointment/success?order_id='.base64_encode($TXNID));
		$redirectUrl =  url("/") . '/appointment/success?order_id=' . (base64_encode($paymentItem['acquirer_data']['upi_transaction_id']));
		return Redirect::to($redirectUrl);

	} else {
		$order = AppointmentOrder::where(["id" => $order->id, 'order_status' => 0])->first();
		$meta_data = json_decode($order->meta_data);
		if ($paymentItem['status'] === 'created') {
			if (!empty($meta_data->mobile_no)) {}
		} else {
				AppointmentOrder::where(["id" => $order->id])->update([
					"order_status" => 3,
				]);
				
		}
		$redirectUrl =  url("/") . '/appointment/success?order_id=' . ($paymentItem['acquirer_data']['upi_transaction_id']);
		return Redirect::to($redirectUrl);
}

}
}




public function razorpayResponse(Request $request)
{
    $data = $request->all();
    // Log the response for debugging
    \Log::info('Razorpay Response:', $data);
     
	
    if (!isset($data['orderId'])) {
        return response()->json(['status' => 'error', 'message' => 'Invalid Order ID'], 400);
    }

    $orderId = $data['orderId']; // Extract the order ID from the request

    $appOrder = AppointmentOrder::select(["order_by", "order_total"])
        ->where(["id" => base64_decode($orderId)])
        ->where('order_status', '0')
        ->first();

    if (!empty($appOrder)) {
        // Update the order status to '1' (completed)

       AppointmentOrder::where(["id" => base64_decode($orderId)])->update([
            'order_status' => '1',
            'razorpay_order_id' =>  $data['razorpay_order_id'],
        ]);
		$order =  $appOrder = AppointmentOrder::where(["id" => base64_decode($orderId)])
        ->where('order_status', '1')
        ->first();
      
		$trackingId = $data['razorpay_order_id'];

		$response =  $data;

		// dd($order);
		$this->putAppointmentDataApp($order, $response, $order);

		ApptLink::where(["id" => base64_decode($orderId)])->update([
		'status' => 1
		]);

		$redirectUrl =  url("/") . '/appointment/admin/success?order_id=' . base64_encode($data['razorpay_order_id']);
		return Redirect::to($redirectUrl);
		

        return response()->json([
            'status' => 'success',
            'message' => 'Payment successful and order updated.',
            'data' => [
                'orderId' => $orderId,
                'amount' => $appOrder->order_total,
                'currency' => 'INR', // Assuming the currency is always INR
                'userId' => $appOrder->order_by
            ]
        ]);
    } else {
        return response()->json(['status' => 'error', 'message' => 'Order not found or already processed'], 404);
    }
}
}
