<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
// use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\VhtOrder;
use App\Models\HealthProcessPw;
use App\Models\GeneralFeedback;
use App\Models\NonHgDoctors;
use App\Models\Doctors;
use App\Models\SubcribedEmail;
use App\Models\ManageSponsored;
use Illuminate\Http\Request;
use App\Models\ehr\Plans;
use App\Models\Plans as UserPlan;
use App\Models\ehr\User as ehrUser;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\DoctorsInfo;
use App\Models\ehr\StaffsInfo;
use App\Models\ehr\RoleUser;
use App\Models\ehr\ManageTrailPeriods;
use App\Models\ehr\JobApplications;
use App\Models\ehr\clinicalNotePermissions;
use App\Models\ehr\UserPermissions;
use App\Models\ehr\OpdTimings;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\PatientFeedback;
use App\Models\DoctorSlug;
use App\Models\Contact;
use App\Models\Admin\Enquiry;
use App\Models\Support;
use App\Models\UsersSubscriptions;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\CityLocalities;
use App\Models\Exports\DoctorExport;
use App\Models\Exports\QueriesExport;
use App\Models\DocQrcodeApply;
use App\Models\Campaigns;
use App\Models\ReminderUserNotificatios;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\State;
use App\Models\City;
use App\Models\ehr\Appointments;
use App\Models\UsersOTP;
use App\Models\CovidHelp;
use App\Models\OrganizationMaster;
use App\Models\DoctorDocuments;
use App\Models\DoctorData;
use App\Models\VaccinationDrive;
use App\Models\RunnersLead;
use App\Models\ehr\AppointmentOrder;
use App\Models\ehr\Patients;
use App\Models\ApptLink;
use App\Models\MedicineOrders;
use App\Models\UsersLaborderAddresses;
use App\Models\HandleQueries;
use App\Models\UsersOnlineData;
use App\Models\Corporate;
use App\Imports\UsersOnlineDataImport;
use App\Imports\UsersImport;
use App\Models\DefaultLabs;
use App\Models\ManageDiabetesRecords;
use App\Models\ManageWeightRecords;
use App\Models\ManageBpRecords;
use App\Models\ehr\ModuleSetting;
use App\Models\LabPackage;
use App\Models\UserNotifications;
use App\Models\Admin\Symptoms;
use App\Models\NotificationUserId;
use App\Models\LabOrders;
use App\Models\Filteryear;
use App\Models\BulkExportCSV;
use App\Http\Controllers\PaytmChecksum;
use App\Jobs\ExportPatientsJob;
use App\Models\NotificationSchedule;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;


use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Models\Settings;
use Exception;
use Softon\Indipay\Facades\Indipay;
use Storage;
use PaytmWallet;
//use Illuminate\Mail\Mailer;
class HomeController extends Controller
{

	public function getDoctorData()
	{
		return Doctors::limit(10)->get();
	}
	public function login(Request $request)
	{
		if ($request->isMethod('post')) {
			$rules = array(
				'email' => 'required',
				'password' => 'required',
			);
			$validator = Validator::make(Input::all(), $rules);
			if ($validator->fails()) {
				foreach ($validator->messages()->getMessages() as $field_name => $messages) {
					if (!isset($firstError))
						$firstError = $messages[0];
					$error[$field_name] = $messages[0];
				}
				return redirect("/admin")->with('error_msg', $firstError)->send();
			} else {
				$userdata = array(
					'email' => Input::get('email'),
				);
				// attempt to do the login
				if ($user = DB::table("admins")->where($userdata)->first()) {
					if ($user->status == '1') {
						if (Hash::check(Input::get('password'), $user->password)) {
							Session::put('id', $user->id);
							Session::put('userdata', $user);
							if (!empty(Input::get('device_token'))) {
								$device_tokens = (!empty($user->device_tokens)) ? json_decode($user->device_tokens) : [];
								array_push($device_tokens, Input::get('device_token'));
								$device_tokens = array_unique($device_tokens);
								DB::table("admins")->where(["id" => $user->id])->update([
									'device_token' => Input::get('device_token'),
									'device_tokens' => json_encode($device_tokens)
								]);
							}
							return redirect("admin/home")->with('success_msg', "Successfully Login")->send();
						} else {

							return redirect("admin/")->with('error_msg', "Please enter Valid credentials")->send();
						}
					} else {
						return redirect("admin/")->with('error_msg', "Your account is not Active")->send();
					}
				} else {
					return redirect("admin/")->with('error_msg', "Please enter Valid username")->send();
				}
			}
		}
		return view("admin/Home/login");
	}


	

	public function forgotPassword(Request  $request)
	{
		if ($request->isMethod('post')) {
			$email = $request->input('email');
			$userData = Admin::where('email', $email)->first();
			if ($userData) {
				$token = Str::random(60);
				$fromEmail = 'noreply@healthgennie.com';
				$subject  = 'Reset Your Password';

				$user = $userData->update(['token' => $token, 'expire_at' => now()->addMinutes(30)]);
				$datas = array(
					'to' => $userData->email,
					'from' => $fromEmail,
					'mailTitle' => 'Reset Password Link',
					'subject' => $subject,
					'token' => $token
				);
				try {
					Mail::send('emails.reset-mail', $datas, function ($message) use ($datas) {
						$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
					});
				} catch (\Exception $e) {
				}
				Session::flash('successMsg', 'Reset Link Send On Your Email');

				//            return view('emails.reset-mail', compact('datas'));
				return redirect("/admin")->send();
			} else {
				return redirect()->route('admin.password')->withInput();
			}
		}
		return view('admin.Home.reset-password');
	}

	public function updatePassword(Request $request)
	{
		$email = base64_decode($request->key);
		$token = $request->token;

		// Validate the token and check if it has expired
		$admin = Admin::where('email', $email)
			->where('token', $token)
			->where('expire_at', '>=', now())
			->first();
		if ($admin) {
			return view('admin.Home.change-password', compact('admin'));
		} else {
			// Invalid or expired token
			return redirect()->back()->with('error', 'Invalid or expired token');
		}
	}
	public function credentialUpdate(Request $request)
	{
		$id = $request->id;
		$password = Hash::make($request->input('password'));
		Admin::where('id', $id)->update(['password' => $password]);
		//Session::flash('Your password has been changed');
		//return redirect()->route("admin.login");
		return 1;
	}



	public function Home(Request $request)
	{
		$admin = Session::get('userdata');

		// Use cache to store dashboard data for faster loading
		$cacheKey = 'admin_dashboard_' . auth()->id();
		$cacheDuration = 60; // Cache for 60 minutes

		// Check if data is in cache
		if (Cache::has($cacheKey)) {
			$dashboardData = Cache::get($cacheKey);
			return view('admin.index', $dashboardData);
		}

		$filteryear = Filteryear::first();
		$year = $filteryear->year;

		$currentMonth = date("m");
		$currentYear = $year;

		// Prepare date ranges for queries
		$startDate = Carbon::create($currentYear, 1, 1)->startOfMonth();
		$endDate = Carbon::create($currentYear, 12, 31)->endOfMonth();

		// Arrays to store data
		$labels = [];
		for ($i = 1; $i <= 12; $i++) {
			$labels[] = date('F', mktime(0, 0, 0, $i, 1, $currentYear));
		}
		
		// Use query builders with proper indexing
		$subscriptionsByMonth = $this->getMonthlyCountData(new UsersSubscriptions(), $startDate, $endDate);
		$appointmentsByMonth = $this->getMonthlyCountData(new Appointments(), $startDate, $endDate);
		$labOrdersByMonth = $this->getMonthlyCountData(new LabOrders(), $startDate, $endDate);
		$usersByMonth = $this->getMonthlyCountData(new User(), $startDate, $endDate);
		
		// This query is complex, so optimize it separately
		$doctorsByMonth = $this->getMonthlyDoctorData($startDate, $endDate);

		// Create data arrays for view
		$data = [
			'labels' => $labels,
			'data' => $this->formatDataByMonth($subscriptionsByMonth, $currentYear),
		];
		
		$dataAppt = [
			'labels' => $labels,
			'data' => $this->formatDataByMonth($appointmentsByMonth, $currentYear),
		];
		
		$dataLabOrder = [
			'labels' => $labels,
			'data' => $this->formatDataByMonth($labOrdersByMonth, $currentYear),
		];
		
		$dataUser = [
			'labels' => $labels,
			'data' => $this->formatDataByMonth($usersByMonth, $currentYear),
		];
		
		$dataDoctor = [
			'labels' => $labels,
			'data' => $this->formatDataByMonth($doctorsByMonth, $currentYear),
		];

		$dashboardData = compact('admin', 'data', 'dataAppt', 'dataLabOrder', 'dataUser', 'dataDoctor', 'year');
		
		// Cache the result
		Cache::put($cacheKey, $dashboardData, $cacheDuration);

		return view('admin.index', $dashboardData);
	}

	/**
	 * Get monthly count data for a given model
	 */
	private function getMonthlyCountData($model, $startDate, $endDate)
	{
		return $model->select(
			DB::raw('MONTH(created_at) as month'),
			DB::raw('COUNT(*) as count')
		)
		->whereBetween('created_at', [$startDate, $endDate])
		->groupBy(DB::raw('MONTH(created_at)'))
		->pluck('count', 'month')
		->toArray();
	}

	/**
	 * Get monthly doctor data
	 */
	private function getMonthlyDoctorData($startDate, $endDate)
	{
		return (new Doctors)->select(
			DB::raw('MONTH(created_at) as month'),
			DB::raw('COUNT(*) as count')
		)
		->where([
			"status" => 1, 
			"delete_status" => 1, 
			"hg_doctor" => 1, 
			"claim_status" => 1, 
			"varify_status" => 1
		])
		->where("oncall_status", "!=", 0)
		->whereBetween('created_at', [$startDate, $endDate])
		->groupBy(DB::raw('MONTH(created_at)'))
		->pluck('count', 'month')
		->toArray();
	}

	/**
	 * Format monthly data into an array with 12 months
	 */
	private function formatDataByMonth($monthlyData, $year)
	{
		$formattedData = [];
		for ($month = 1; $month <= 12; $month++) {
			$formattedData[] = $monthlyData[$month] ?? 0;
		}
		return $formattedData;
	}


	public function updateYear(Request $request)
	{
		if ($request->isMethod('post')) {

			$data = $request->all();
			$FilterYear = Filteryear::first();
			if (!empty($FilterYear)) {
				Filteryear::where('id', 1)->update(array(
					'year' => $data['year']
				));
			} else {
				Filteryear::create([
					'year' => $data['year']
				]);
			}

			return 1;
		}
	}

	public  function doctorsList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('speciality_id'))) {
				$params['speciality_id'] = base64_encode($request->input('speciality_id'));
			}
			if (!empty($request->input('grp_speciality'))) {
				$params['grp_speciality'] = base64_encode($request->input('grp_speciality'));
			}
			if (!empty($request->input('status_type'))) {
				$params['status_type'] = base64_encode($request->input('status_type'));
			}
			if (!empty($request->input('filter'))) {
				$params['filter'] = base64_encode($request->input('filter'));
			}
			if (!empty($request->input('state_id'))) {
				$params['state_id'] = base64_encode($request->input('state_id'));
			}
			if (!empty($request->input('city_id'))) {
				$params['city_id'] = base64_encode($request->input('city_id'));
			}
			if (!empty($request->input('locality_id'))) {
				$params['locality_id'] = base64_encode($request->input('locality_id'));
			}

			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}
			if (!empty($request->input('facility'))) {
				$params['facility'] = base64_encode($request->input('facility'));
			}
			if ($request->input('oncall_status') != "") {
				$params['oncall_status'] = base64_encode($request->input('oncall_status'));
			}
			if ($request->input('is_live') != "") {
				$params['is_live'] = base64_encode($request->input('is_live'));
			}
			return redirect()->route('admin.doctorsList', $params)->withInput();
		} else {
			$search = base64_decode($request->input('search'));
			$speciality_id = base64_decode($request->input('speciality_id'));
			$grp_speciality = base64_decode($request->input('grp_speciality'));
			$state_id = base64_decode($request->input('state_id'));
			$city_id = base64_decode($request->input('city_id'));
			$locality_id = base64_decode($request->input('locality_id'));
			$status_type = base64_decode($request->input('status_type'));
			$facility = base64_decode($request->input('facility'));
			$oncall_status = base64_decode($request->input('oncall_status'));
			$is_live = base64_decode($request->input('is_live'));
			$filter = base64_decode($request->input('filter'));
			$file_type = base64_decode($request->input('file_type'));
			$query = Doctors::with("docSpeciality")->where(["delete_status" => 1, "hg_doctor" => 1, "claim_status" => 1, "varify_status" => 1]);

			if (!empty($search)) {
				$query->where(function($q) use ($search) {
					$q->where(DB::raw('concat(first_name, " ", last_name, " ", IFNULL(clinic_name, ""), " ", mobile_no)'), 'like', '%' . $search . '%')
					  ->orWhere('mobile_no', 'like', '%' . $search . '%')
					  ->orWhere('clinic_name', 'like', '%' . $search . '%');
				});
				// $query->where(DB::raw('concat(first_name collate utf8mb4_unicode_ci, " ", last_name collate utf8mb4_unicode_ci, " ", mobile_no collate utf8mb4_unicode_ci, " ", IFNULL(clinic_name collate utf8mb4_unicode_ci, ""))'), 'like', '%' . $search . '%');
			}
			if (!empty($grp_speciality)) {
				$grp_speciality = DB::table('doctor_specialities')->select("id")->where(["group_id" => $grp_speciality])->get();
				$s_ids = [];
				foreach ($grp_speciality as $ids) {
					$s_ids[] = $ids->id;
				}
				$query->whereIn('speciality', $s_ids);
			}
			if (!empty($speciality_id)) {
				$query->where('speciality', $speciality_id);
			}
			if (!empty($state_id)) {
				$query->where('state_id', $state_id);
			}
			if (!empty($city_id)) {
				$query->where('city_id', $city_id);
			}
			if (!empty($locality_id)) {
				$query->where('locality_id', $locality_id);
			}
			if (!empty($oncall_status)) {
				if ($oncall_status == 1) {
					$query->where('oncall_status', '=', '1');
				} else if ($oncall_status == 2) {
					$query->where('oncall_status', '=', '2');
				} else if ($oncall_status == 3) {
					$query->whereIn('oncall_status', ['1,2', '2,1']);
				}
			}
			if (!empty($is_live)) {
				if ($is_live == 1) {
					$query->where("oncall_status", "!=", 0);
				} else if ($is_live == 0) {
					$query->where("oncall_status", "=", 0);
				}
			}
			if (!empty($facility)) {
				$query->orderBy('clinic_name', 'ASC');
			}

			if (!empty($filter)) {
				if ($filter == 1) {
					$user_id = checSubscribeDoc(1);
					$query->whereIn('user_id', $user_id);
				} elseif ($filter == 2) {
					$user_id = checSubscribeDoc(2);
					$query->whereIn('user_id', $user_id);
				} elseif ($filter == 3) {
					$user_id = checSubscribeDoc(3);
					$query->whereIn('user_id', $user_id);
				} elseif ($filter == 4) {
					$query->where('claim_profile_web', 1);
				} elseif ($filter == 5) {
					$user_id = checSubscribeDoc(4);
					$query->where('varify_status', 1)->whereNotIn('user_id', $user_id);
				}
			}

			if ($file_type == "excel") {
				$doctors = $query->orderBy("created_at", "DESC")->get();
				//$doctorArray[] = array('Sr. No.','Doc Id','Name','Registartion Number','Email','Clinic Email','Mobile','Clinic Mobile','Gender','Speciality','Clnic Name','Address','State','City','Locality','Zipcode','Consultation Fee','Experience','Date');
				foreach ($doctors as $i => $doc) {
					$typee = "";
					if (substr($doc->member_id, 0, 3) == 'Pra') {
						if ($doc->practice_type == '1') {
							$typee = "Practice(Clinic)";
						} else {
							$typee = "Practice(Hospital)";
						}
					} elseif (substr($doc->member_id, 0, 3) == 'Doc') {
						$typee = "Doctor";
					}
					$doctorArray[] = array(
						$i + 1,
						$doc->id,
						$typee,
						@$doc->first_name . " " . @$doc->last_name,
						@$doc->reg_no,
						@$doc->email,
						@$doc->clinic_email,
						@$doc->mobile_no,
						@$doc->clinic_mobile,
						@$doc->gender,
						@$doc->docSpeciality->specialities,
						@$doc->qualification,
						@$doc->docSpeciality->SpecialityGroup->group_name,
						@$doc->clinic_name,
						@$doc->address_1,
						getStateName(@$doc->state_id),
						getCityName(@$doc->city_id),
						getLocalityName(@$doc->locality_id),
						@$doc->zipcode,
						@$doc->consultation_fees,
						($doc->experience != null) ? @$doc->experience . " years" : "",
						($doc->opd_timings != null) ? @$doc->opd_timings : "",
						$doc->oncall_fee,
						(!empty($doc->oncall_status)) ? "Yes" : "No",
						($doc->updated_at != null) ? date("d-m-Y h:i A", strtotime($doc->updated_at)) : "",
						($doc->created_at != null) ? date("d-m-Y h:i A", strtotime($doc->created_at)) : "",
					);
				}
				return Excel::download(new DoctorExport($doctorArray), 'doctors.xlsx');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$doctors = 	$query->orderBy("created_at", "DESC")->paginate($page);
			$appliedQRCode = json_decode(DocQrcodeApply::select('doc_id')->pluck('doc_id'));
		}
		return view('admin.Doctors.doctors-list', compact('doctors', 'appliedQRCode'));
	}
	public  function nonHgDoctorsList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('speciality_id'))) {
				$params['speciality_id'] = base64_encode($request->input('speciality_id'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('state_id'))) {
				$params['state_id'] = base64_encode($request->input('state_id'));
			}
			if (!empty($request->input('city_id'))) {
				$params['city_id'] = base64_encode($request->input('city_id'));
			}
			if (!empty($request->input('find_by'))) {
				$params['find_by'] = base64_encode($request->input('find_by'));
			}
			if (!empty($request->input('mobile'))) {
				$params['mobile'] = base64_encode($request->input('mobile'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}
			return redirect()->route('admin.nonHgDoctorsList', $params)->withInput();
		} else {
			$search = base64_decode($request->input('search'));
			$speciality_id = base64_decode($request->input('speciality_id'));
			$file_type = base64_decode($request->input('file_type'));
			$query = Doctors::with("docSpeciality")->where(["delete_status" => 1, "hg_doctor" => 0]);
			if (!empty($search)) {
				// $query->where(DB::raw('concat(first_name," ",IFNULL(last_name,""))'), 'like', '%'.$search.'%');
				$query->where(DB::raw('concat(first_name collate utf8mb4_unicode_ci, " ", last_name collate utf8mb4_unicode_ci, " ", IFNULL(clinic_name collate utf8mb4_unicode_ci, ""))'), 'like', '%' . $search . '%');

				// $query->where(DB::raw('concat(first_name," ",last_name," ",IFNULL(clinic_name,""))') , 'like', '%'.$search.'%');
			}
			if (!empty($request->input('find_by'))) {
				$query->where('email', 'like', '%' . base64_decode($request->input('find_by')) . '%');
			}
			if (!empty($request->input('mobile'))) {
				$query->where('mobile_no', 'like', '%' . base64_decode($request->input('mobile')) . '%');
			}
			if (!empty($request->input('state_id'))) {
				$state_id = base64_decode($request->input('state_id'));
				$query->where('state_id', $state_id);
			}
			if (!empty($request->input('city_id'))) {
				$city_id = base64_decode($request->input('city_id'));
				$query->where('city_id', $city_id);
			}

			if ($speciality_id) {
				$query->where('speciality', $speciality_id);
			}
			if ($file_type == "excel") {
				$doctors = $query->orderBy("created_at", "DESC")->get();
				foreach ($doctors as $i => $doc) {
					$doctorArray[] = array(
						$i + 1,
						$doc->id,
						'',
						@$doc->first_name . " " . @$doc->last_name,
						@$doc->reg_no,
						@$doc->email,
						@$doc->clinic_email,
						@$doc->mobile_no,
						@$doc->clinic_mobile,
						@$doc->gender,
						@$doc->docSpeciality->specialities,
						@$doc->qualification,
						@$doc->docSpeciality->SpecialityGroup->group_name,
						@$doc->clinic_name,
						@$doc->address_1,
						getStateName(@$doc->state_id),
						getCityName(@$doc->city_id),
						getLocalityName(@$doc->locality_id),
						@$doc->zipcode,
						@$doc->consultation_fees,
						($doc->experience != null) ? @$doc->experience . " years" : "",
						($doc->opd_timings != null) ? @$doc->opd_timings : "",
						($doc->profile_pic != null) ? "Yes" : "No",
						($doc->updated_at != null) ? date("d-m-Y h:i A", strtotime($doc->updated_at)) : "",
					);
				}
				return Excel::download(new DoctorExport($doctorArray), 'doctors.xlsx');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$doctors = 	$query->orderBy("created_at", "DESC")->paginate($page);
		}
		return view('admin.Doctors.non-hg-doctors-list', compact('doctors'));
	}

	public  function claimDoctorsList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('speciality_id'))) {
				$params['speciality_id'] = base64_encode($request->input('speciality_id'));
			}
			if (!empty($request->input('status_type'))) {
				$params['status_type'] = base64_encode($request->input('status_type'));
			}
			if (!empty($request->input('filter'))) {
				$params['filter'] = base64_encode($request->input('filter'));
			}
			if (!empty($request->input('state_id'))) {
				$params['state_id'] = base64_encode($request->input('state_id'));
			}
			if (!empty($request->input('city_id'))) {
				$params['city_id'] = base64_encode($request->input('city_id'));
			}
			if (!empty($request->input('locality_id'))) {
				$params['locality_id'] = base64_encode($request->input('locality_id'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.claimDoctorsList', $params)->withInput();
		} else {
			$search = base64_decode($request->input('search'));
			$speciality_id = base64_decode($request->input('speciality_id'));
			$state_id = base64_decode($request->input('state_id'));
			$city_id = base64_decode($request->input('city_id'));
			$locality_id = base64_decode($request->input('locality_id'));
			$file_type = base64_decode($request->input('file_type'));
			$query = Doctors::with("docSpeciality")->where(["delete_status" => 1, "claim_status" => 1, "varify_status" => 0]);
			if (!empty($search)) {
				$query->where(DB::raw('concat(first_name," ",last_name)'), 'like', '%' . $search . '%');
			}
			if ($speciality_id) {
				$query->where('speciality', $speciality_id);
			}
			if (!empty($state_id)) {
				$query->where('state_id', $state_id);
			}
			if (!empty($city_id)) {
				$query->where('city_id', $city_id);
			}
			if (!empty($locality_id)) {
				$query->where('locality_id', $locality_id);
			}
			if ($file_type == "excel") {
				$doctors = $query->orderBy("created_at", "DESC")->get();

				/*****FOR DATA*****/
				/*$allCity = DoctorsInfo::select("city_id")->groupBy("city_id")->whereNotNull("city_id")->whereNotNull("user_id")->where('city_id','!=',0)->where('country_id',101)->pluck('city_id');

				if(count($allCity)>0){
					foreach($allCity as $cty){
						$allDoc = DoctorsInfo::with(['user'=>function($q) {$q->where(["status"=>1,"delete_status"=>1])->where('id','!=',24);}])->select("user_id")->whereNotNull("city_id")->where('city_id','!=',0)->whereNotNull("user_id")->where('city_id',$cty)->where(["claim_status"=>1,"hg_doctor"=>1])->where('user_id','!=',24)->get();
						$count = 0;
						if(count($allDoc)>0){
							foreach($allDoc as $doc){
								$chkAppt = Appointments::select("id")->where('doc_id',$doc->user_id)->where("delete_status",1)->count();
								if($chkAppt > 0){
									$count++;
								}
							}
						}
						$doctorArray[] = array(
							 $cty,
							 getCityName($cty),
							 $count,
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
							 '',
						  );
					}
				}*/

				// dd($allDoc);
				/****END****/
				foreach ($doctors as $i => $doc) {
					$typee = "";
					if (substr($doc->member_id, 0, 3) == 'Pra') {
						if ($doc->practice_type == '1') {
							$typee = "Practice(Clinic)";
						} else {
							$typee = "Practice(Hospital)";
						}
					} elseif (substr($doc->member_id, 0, 3) == 'Doc') {
						$typee = "Doctor";
					}
					$doctorArray[] = array(
						$i + 1,
						$doc->id,
						$typee,
						@$doc->first_name . " " . @$doc->last_name,
						@$doc->reg_no,
						@$doc->email,
						@$doc->clinic_email,
						@$doc->mobile_no,
						@$doc->clinic_mobile,
						@$doc->gender,
						@$doc->docSpeciality->specialities,
						@$doc->qualification,
						@$doc->docSpeciality->SpecialityGroup->group_name,
						@$doc->clinic_name,
						@$doc->address_1,
						getStateName(@$doc->state_id),
						getCityName(@$doc->city_id),
						getLocalityName(@$doc->locality_id),
						@$doc->zipcode,
						@$doc->consultation_fees,
						($doc->experience != null) ? @$doc->experience . " years" : "",
						($doc->opd_timings != null) ? @$doc->opd_timings : "",
						($doc->profile_pic != null) ? "Yes" : "No",
						($doc->updated_at != null) ? date("d-m-Y h:i A", strtotime($doc->updated_at)) : "",
					);
				}
				return Excel::download(new DoctorExport($doctorArray), 'doctors.xlsx');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$doctors = 	$query->orderBy("created_at", "DESC")->paginate($page);
		}
		$type = 1;
		return view('admin.Doctors.claim-doctors-list', compact('doctors', 'type'));
	}
	public  function nonClaimDoctorsList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('spaciality'))) {
				$params['spaciality'] = base64_encode($request->input('spaciality'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.nonClaimDoctorsList', $params)->withInput();
		} else {
			$search = base64_decode($request->input('search'));
			$spaciality = base64_decode($request->input('spaciality'));
			$query = Doctors::with("docSpeciality")->where(["claim_status" => 0, "varify_status" => 0, "hg_doctor" => 1]);
			if (!empty($search)) {
				$query->where(DB::raw('concat(first_name," ",last_name)'), 'like', '%' . $search . '%');
			}
			if ($spaciality) {
				$query->whereHas('docSpeciality', function ($q)  use ($spaciality) {
					$q->Where('specialities', 'like', '%' . $spaciality . '%');
				});
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$doctors = 	$query->orderBy("created_at", "DESC")->paginate($page);
		}
		$type = 2;
		return view('admin.Doctors.claim-doctors-list', compact('doctors', 'type'));
	}
	public  function varifyClaimDoctor(Request $request)
	{
		$id = $request->id;
		if (!empty($id)) {
			$doc_data = Doctors::where(["id" => $id])->first();
			$userId = $doc_data->user_id;
			$memberId = $doc_data->member_id;
			$practiceId = $doc_data->practice_id;
			$pass  = trim(substr($doc_data->first_name, 0, 3)) . substr($doc_data->mobile_no, -4) . rand(000, 999);
			if (empty($doc_data->user_id)) {
				$urls = json_encode(getEhrFullUrls());
				if (!empty($doc_data->clinic_image)) {
					$this->uploadClinicImgFileBy($doc_data->clinic_image);
				}
				if (!empty($doc_data->profile_pic)) {
					$file_exist = public_path() . "/doctorImage/" . $doc_data->profile_pic;
					if (file_exists($file_exist)) {
						$this->uploadDoctorFileBy($doc_data->profile_pic);
					}
				}
				if (!empty($doc_data->practice_id)) {
					$mem_id = $this->getUniqueId('Doc');
				} else {
					$mem_id = $this->getUniqueId('Pra');
				}
				$user =  ehrUser::create([
					'email' => $doc_data->email,
					'mobile_no' => $doc_data->mobile_no,
					'device_id' => 3,
					'reg_device_type' => 4,
					'member_id' => $mem_id,
					'profile_status' => 1,
					'varify_status' => 1,
					'password' => bcrypt($pass),
				]);

				if (empty($doc_data->practice_id)) {
					$practiceDetails    = new PracticeDetails;
					$practiceDetails->clinic_name = $doc_data->clinic_name;
					$practiceDetails->address_1 = $doc_data->address_1;
					$practiceDetails->mobile = $doc_data->clinic_mobile;
					$practiceDetails->email = $doc_data->clinic_email;
					$practiceDetails->practice_type = $doc_data->practice_type;
					$practiceDetails->city_id = $doc_data->city_id;
					$practiceDetails->locality_id = $doc_data->locality_id;
					$practiceDetails->state_id = $doc_data->state_id;
					$practiceDetails->country_id = $doc_data->country_id;
					$practiceDetails->zipcode = $doc_data->zipcode;
					$practiceDetails->website = $doc_data->website;
					$practiceDetails->specialization = $doc_data->clinic_speciality;
					$practiceDetails->logo = $doc_data->clinic_image;
					$practiceDetails->slot_duration =  $doc_data->slot_duration;
					$practiceDetails->my_visits = '{"1":{"id":"1","amount":"' . $doc_data->consultation_fees . '"},"2":{"id":"4","amount":""}}';
					$user->practiceDetails()->save($practiceDetails);
				}

				$userDetails    = new DoctorsInfo;
				$userDetails->first_name  = ucfirst($doc_data->first_name);
				$userDetails->last_name  = $doc_data->last_name;
				$userDetails->mobile  = $doc_data->mobile_no;
				$userDetails->gender  = $doc_data->gender;
				$userDetails->reg_no  = $doc_data->reg_no;
				$userDetails->reg_year  = $doc_data->reg_year;
				$userDetails->reg_council  = $doc_data->reg_council;
				$userDetails->last_obtained_degree  = $doc_data->last_obtained_degree;
				$userDetails->degree_year  = $doc_data->degree_year;
				$userDetails->university  = $doc_data->university;
				// $userDetails->consultation_discount  = $doc_data->consultation_discount;
				$userDetails->speciality  = $doc_data->speciality;
				$userDetails->address_1  = $doc_data->address_1;
				$userDetails->city_id  = $doc_data->city_id;
				$userDetails->locality_id  = $doc_data->locality_id;
				$userDetails->state_id  = $doc_data->state_id;
				$userDetails->country_id  = $doc_data->country_id;
				$userDetails->zipcode  = $doc_data->zipcode;
				$userDetails->profile_pic  = $doc_data->profile_pic;
				$userDetails->educations  = $doc_data->qualification;
				$userDetails->experience  = $doc_data->experience;
				$userDetails->content  = $doc_data->content;
				$userDetails->consultation_fee  = $doc_data->consultation_fees;
				$userDetails->ref_code  = $doc_data->ref_code;
				$userDetails->oncall_status  = $doc_data->oncall_status;
				$userDetails->oncall_fee  = $doc_data->oncall_fee;
				$userDetails->acc_no  = $doc_data->acc_no;
				$userDetails->acc_name  = $doc_data->acc_name;
				$userDetails->ifsc_no  = $doc_data->ifsc_no;
				$userDetails->bank_name  = $doc_data->bank_name;
				$userDetails->paytm_no  = $doc_data->paytm_no;
				$userDetails->hg_doctor  = 1;
				$userDetails->claim_status  = 1;
				$user->doctorInfo()->save($userDetails);

				if (!empty($doc_data->practice_id)) {
					$role =  RoleUser::create([
						'user_id' => $user->id,
						'role_id' => 3,
						'practice_id' => $doc_data->practice_id
					]);
					$practice_id = $doc_data->practice_id;
				} else {
					$role_type = array(2, 3);
					foreach ($role_type as $key => $val) {
						$role    = new RoleUser;
						$role->role_id = $val;
						$role->practice_id = $user->id;
						$user->RoleUser()->save($role);
					}
					$practice_id = $user->id;
				}

				clinicalNotePermissions::insert(['user_id' => $user->id, 'practice_id' => $practice_id, 'modules_access' => "1,2,3,4,6,7,8,9,11,12,13,14,15,16", 'created_at' => date("Y-m-d h:i:s"), 'updated_at' => date("Y-m-d h:i:s")]);

				OpdTimings::create(['user_id' => $user->id, 'practice_id' => $practice_id, 'schedule' => $doc_data->opd_timings]);

				$userId = $user->id;
				$memberId = $user->member_id;
				$practiceId = $practice_id;
				if (UserPermissions::where(['user_id' => $practiceId, 'practice_id' => $practiceId])->count() == 0) {
					$moduleAccess = ModuleSetting::select('id')->pluck('id')->toArray();
					$moduleAccess = (isset($moduleAccess) ? implode(',', $moduleAccess) : null);
					UserPermissions::insert(['user_id' => $practiceId, 'practice_id' => $practiceId, 'modules_access' => "1,2,3,4,5,6,7", 'settings_access' => $moduleAccess]);
				}
			}
			if (!empty($doc_data->user_id)) {
				$docSlug = getDoctorSlug($doc_data, '1');
				$clinicSlug = getClinicSlug($doc_data, '1');
				DoctorSlug::where('doc_id', $id)->update(array(
					'practice_id' => $practiceId,
					'name_slug' => strtolower($docSlug),
					'clinic_name_slug' => strtolower($clinicSlug),
					"city_id" => $doc_data->city_id
				));
			}
			$feedback = PatientFeedback::where('doc_id', $doc_data->id)->first();
			if (empty($feedback)) {
				PatientFeedback::create([
					'doc_id' =>  $doc_data->id,
					'rating' =>  4,
					'publish_admin' =>  1,
					'doc_type' => 1
				]);
			} else {
				PatientFeedback::where('doc_id', $doc_data->id)->update(array('rating' => 4, 'doc_type' => 1));
			}

			$varified_user = ehrUser::where('id', $doc_data->user_id)->where('varify_status', 0)->count();
			if ($varified_user > 0) {
				$user =  ehrUser::where('id', $doc_data->user_id)->update(array(
					'varify_status' => 1,
					'password' => bcrypt($pass),
				));
			}
			$name = substr(str_shuffle(str_replace(' ', '', $doc_data->first_name)), 0, 2);
			if (!empty($doc_data->mobile_no)) {
				$mobile = substr(str_shuffle($doc_data->mobile_no), 0, 2);
			} else {
				$mobile = rand(10, 100);
			}
			$timestamp = time();
			$time = substr(str_shuffle($timestamp), 0, 2);
			$ref_code =  "HG" . strtoupper($name) . '' . $mobile . '' . $time;
			Doctors::where('id', $id)->update(array(
				'hg_doctor' => 1,
				'varify_status' => 1,
				'ref_code' => $ref_code,
				'password' => bcrypt($pass),
				'created_at' => date('Y-m-d H:i:s'),
				'user_id' => $userId,
				'member_id' => $memberId,
				'practice_id' => $practiceId,
				'login_id' => Session::get('id'),
			));

			DoctorSlug::where('doc_id', $id)->update(array(
				'practice_id' => $practiceId,
			));
			$ehrDoc = Doctors::select("user_id")->where(["id" => $id])->first();
			if (!empty($ehrDoc->user_id)) {
				DoctorsInfo::where('user_id', $ehrDoc->user_id)->update(array(
					'ref_code' => $ref_code,
				));
			}
			if (empty($doc_data->practice_id)) {
				$plans = Plans::Where("id", 5)->first();
				$duration_type = $plans->plan_duration_type;
				if ($duration_type == "d") {
					$duration_in_days = $plans->plan_duration;
				} elseif ($duration_type == "m") {
					$duration_in_days = (30 * $plans->plan_duration);
				} elseif ($duration_type == "y") {
					$duration_in_days = (365 * $plans->plan_duration);
				}
				$create_date = date('Y-m-d H:i:s');
				$end_date = date('Y-m-d H:i:s', strtotime($create_date . '+' . $duration_in_days . ' days'));
				$ManageTrailPeriods =  ManageTrailPeriods::create([
					'user_id' => $userId,
					'user_plan_id' => $plans->id,
					'start_trail' => $create_date,
					'end_trail' => $end_date,
					'remaining_sms' => $plans->promotional_sms_limit
				]);
			}

			$username = ucfirst($doc_data->first_name) . " " . $doc_data->last_name;
			$to = $doc_data->email;
			if (!empty($doc_data->mobile_no)) {
				$message = urlencode("Dear Dr. " . $username . ",Congratulation! Your profile has been verified successfully with Health Gennie.Your 14 day trial for Health Gennie software starts from today..Please check your email for login credentials.If you have any questions, please call us at 8929920932 Thanks Team Health Gennie");
				$this->sendSMS($doc_data->mobile_no, $message, '1707161587969096964');
			}
			$practiceData =  PracticeDetails::where(['user_id' => $practiceId])->first();

			$EmailTemplate = EmailTemplate::where('slug', 'doctorregbackend')->first();
			if (parent::is_connected() == 1) {
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
			return 1;
		}
		return 2;
	}
	public  function addDoctor(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$fileName = null;
			$clincFileName = null;
			if ($request->hasFile('profile_pic')) {
				$image  = $request->file('profile_pic');
				$fullName = str_replace(" ", "", $image->getClientOriginalName());
				$onlyName = explode('.', $fullName);
				if (is_array($onlyName)) {
					$fileName = $onlyName[0] . time() . "." . $onlyName[1];
				} else {
					$fileName = $onlyName . time();
				}
				$request->file('profile_pic')->move(public_path() . "/doctorImage", $fileName);
				if (!empty($data['doc_claim_type']) && !empty($fileName)) {
					$this->uploadDoctorFileBy($fileName);
				}
			}

			if ($request->hasFile('clinic_image')) {
				$image  = $request->file('clinic_image');
				$fullName = str_replace(" ", "", $image->getClientOriginalName());
				$onlyName = explode('.', $fullName);
				if (is_array($onlyName)) {
					$clincFileName = $onlyName[0] . time() . "." . $onlyName[1];
				} else {
					$clincFileName = $onlyName . time();
				}
				$request->file('clinic_image')->move(public_path() . "/clinic", $clincFileName);
				if (!empty($data['doc_claim_type']) && !empty($clincFileName)) {
					$this->uploadClinicImgFileBy($clincFileName);
				}
			}
			$schedule = "";
			if (isset($data['schedule']) && count($data['schedule']) > 0) {
				$schedule = json_encode($data['schedule']);
			}
			$doctor_data = Doctors::create([
				'login_id' => Session::get('id'),
				'name' => $data['name'],
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				'gender' =>  isset($data['gender']) ? $data['gender'] : "",
				'mobile_no' => $data['mobile_no'],
				'email' => $data['email'],
				'speciality' => $data['speciality'],
				'clinic_speciality' => $data['clinic_speciality'],
				'experience' => $data['experience'],
				'qualification' => $data['qualification'],
				'reg_no' => isset($data['reg_no']) ? $data['reg_no'] : null,
				'reg_year' => isset($data['reg_year']) ? $data['reg_year'] : null,
				'reg_council' =>  isset($data['reg_council']) ? $data['reg_council'] : null,
				'last_obtained_degree' => isset($data['last_obtained_degree']) ? $data['last_obtained_degree'] : null,
				'degree_year' => isset($data['degree_year']) ? $data['degree_year'] : null,
				'university' => isset($data['university']) ? $data['university'] : null,
				'content' => $data['content'],
				'clinic_name' => $data['clinic_name'],
				'clinic_mobile' => $data['clinic_mobile'],
				'clinic_email' => $data['clinic_email'],
				'practice_type' =>  isset($data['practice_type']) ? $data['practice_type'] : "",
				'recommend' => $data['recommend'],
				'website' => isset($data['website']) ? $data['website'] : "",
				'note' => $data['note'],
				'address_1' => $data['address_1'],
				'country_id' => $data['country_id'],
				'state_id' => $data['state_id'],
				'locality_id' => isset($data['locality_id']) ? $data['locality_id'] : null,
				'city_id' => $data['city_id'],
				'zipcode' => $data['zipcode'],
				'servtel_api_key' => $data['servtel_api_key'],
				'consultation_fees' => $data['consultation_fees'],
				// 'consultation_discount' => $data['consultation_discount'],
				'oncall_status' => (isset($data['oncall_status']) ? implode(',', $data['oncall_status']) : null),
				'oncall_fee' => (isset($data['oncall_fee']) ? $data['oncall_fee'] : null),
				'acc_name' => (isset($data['acc_name']) ? $data['acc_name'] : null),
				'acc_no' => (isset($data['acc_no']) ? $data['acc_no'] : null),
				'ifsc_no' => (isset($data['ifsc_no']) ? $data['ifsc_no'] : null),
				'bank_name' => (isset($data['bank_name']) ? $data['bank_name'] : null),
				'paytm_no' => (isset($data['paytm_no']) ? $data['paytm_no'] : null),
				'profile_pic' => $fileName,
				'clinic_image' => $clincFileName,
				'opd_timings' => $schedule,
				'slot_duration' => (isset($data['slot_duration']) ? $data['slot_duration'] : '5'),
				'claim_status' => 1,
				'claim_profile_web' => 1,
				'profile_status' => 1,
				'my_visits' => '{"1":{"id":"1","amount":"' . $data['consultation_fees'] . '"},"2":{"id":"4","amount":""}}',
				'practice_id' => (isset($data['clinic_id']) ? $data['clinic_id'] : null),
			]);
			$doclng = (isset($data['languages'])) > 0 ? implode(',', $data['languages']) : NULL;
			// if(!empty($data['followup_count'])){
			// DoctorData::create(['doc_id'=>$doctor_data->id,'user_id'=>$doctor_data->user_id,'followup_count'=>$data['followup_count']]);
			// }
			$alternate_address = ((isset($data['alternate_address']) && count(json_encode($data['alternate_address'])) > 0) ? json_encode($data['alternate_address']) : NULL);
			// if(!empty($data['followup_count']) || !empty($data['plan_consult_fee']) || isset($data['alternate_address'])){
			DoctorData::create(['doc_id' => $doctor_data->id, 'user_id' => $doctor_data->user_id, 'followup_count' => $data['followup_count'], 'alternate_address' => $alternate_address, 'languages' => $doclng]);
			// }
			if (!empty($data['clinic_id'])) {
				$slug = DoctorSlug::where('practice_id', $data['clinic_id'])->first();
				$clinicSlug = $slug->clinic_name_slug;
			} else {
				$clinicSlug = getClinicSlug($doctor_data, '1');
			}
			$docSlug = getDoctorSlug($doctor_data, '1');
			DoctorSlug::create(['doc_id' => $doctor_data->id, 'name_slug' => strtolower($docSlug), 'clinic_name_slug' => strtolower($clinicSlug), "city_id" => $doctor_data->city_id]);
			return 1;
		}
		return view('admin.Doctors.add-doctor');
	}
	public function get_lat_long($address)
	{
		$address = str_replace(" ", "+", $address);
		$lat = 0;
		$long = 0;
		if (parent::is_connected() == 1) {
			$json = file_get_contents("https://maps.google.com/maps/api/geocode/json?address=urlencode($address)&sensor=false&key=AIzaSyDrzKrcKQqGvZQjuMZtDQy3MHOpNjPmjnU");
			$json = json_decode($json);
			// pr($json->['results']);
			$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
			$long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
			return $lat . ',' . $long;
		}
		return $lat . ',' . $long;
	}
	public function editDoctor(Request $request)
	{
		
		$id = $request->id;
		$type = $request->type;
		$doctor = Doctors::where('delete_status', '=', '1')->Where('id', '=', $id)->first();
		
	
		$doctorInfo = DoctorsInfo::Where('user_id', '=', $doctor->user_id)->first();
		
		return view('admin.Doctors.edit-doctor', compact('doctor', 'type', 'doctorInfo'));
	}

	// public function updateDoctor(Request $request)
	// {
		// if ($request->isMethod('post')) {
			// $data = $request->all();
			// $fileName = null;
			// $clincFileName = null;
			// if ($request->hasFile('profile_pic')) {
				// $image  = $request->file('profile_pic');
				// $fullName = str_replace(" ", "", $image->getClientOriginalName());
				// $onlyName = explode('.', $fullName);
				// if (is_array($onlyName)) {
					// $fileName = $onlyName[0] . time() . "." . $onlyName[1];
				// } else {
					// $fileName = $onlyName . time();
				// }
				// $docPath = "public/doctor/ProfilePics/";
				// //   if(!Storage::disk('s3')->exists($docPath)){
				// // 	 Storage::disk('s3')->makeDirectory($docPath);
// 
				// //   }
				// //   Storage::disk('s3')->put($docPath.$fileName, file_get_contents($image),'public');
				// $request->file('profile_pic')->move(public_path() . "/doctorImage", $fileName);
				// if (isset($data['old_profile_pic']) && !empty($data['old_profile_pic'])) {
					// $oldFilename = public_path() . "/doctorImage/" . $data['old_profile_pic'];
					// // if(Storage::disk('s3')->exists($docPath.$data['old_profile_pic'])) {
					// // 	Storage::disk('s3')->delete($docPath.$data['old_profile_pic']);
					// // }
					// // if(file_exists($oldFilename)){
					// // File::delete($oldFilename);
					// // }
				// }
				// // if(!empty($data['doc_claim_type']) && !empty($fileName) ) {
				// // $this->uploadDoctorFileBy($fileName);
				// // }
			// } else {
				// $fileName = isset($data['old_profile_pic']) ? $data['old_profile_pic'] : "";
			// }
// 
			// if ($request->hasFile('doctor_signature')) {
				// $images  = $request->file('doctor_signature');
				// $fullName = str_replace(" ", "", $images->getClientOriginalName());
				// $onlyName = explode('.', $fullName);
				// if (is_array($onlyName)) {
					// $signatureFileName = $onlyName[0] . time() . "." . $onlyName[1];
				// } else {
					// $signatureFileName = $onlyName . time();
				// }
				// $docPath = "public/doctor/signature/";
				// // if(!Storage::disk('s3')->exists($docPath)){
				// //    Storage::disk('s3')->makeDirectory($docPath);
				// // }
				// // Storage::disk('s3')->put($docPath.$signatureFileName, file_get_contents($images),'public');
				// $request->file('clinic_image')->move(public_path() . "/clinic", $clincFileName);
				// if (isset($data['old_signature_image']) && !empty($data['old_signature_image'])) {
					// $filename = $docPath . $data['old_signature_image'];
					// if (Storage::disk('s3')->exists($filename)) {
						// Storage::disk('s3')->delete($filename);
					// }
				// }
			// } else {
				// $signatureFileName = isset($data['old_signature_image']) ? $data['old_signature_image'] : "";
			// }
// 
// 
			// if ($request->hasFile('clinic_image')) {
				// $image  = $request->file('clinic_image');
				// $fullName = str_replace(" ", "", $image->getClientOriginalName());
				// $onlyName = explode('.', $fullName);
				// if (is_array($onlyName)) {
					// $clincFileName = $onlyName[0] . time() . "." . $onlyName[1];
				// } else {
					// $clincFileName = $onlyName . time();
				// }
				// $docPath = "public/doctor/";
				// //   if(!Storage::disk('s3')->exists($docPath)){
				// // 	 Storage::disk('s3')->makeDirectory($docPath);
				// //   }
				// //   Storage::disk('s3')->put($docPath.$clincFileName, file_get_contents($image),'public');
				// $request->file('clinic_image')->move(public_path() . "/clinic", $clincFileName);
				// if (isset($data['old_clinic_image']) && !empty($data['old_clinic_image'])) {
					// $filename = $docPath . $data['old_clinic_image'];
					// //   if(Storage::disk('s3')->exists($filename)) {
					// // 	 Storage::disk('s3')->delete($filename);
					// //   }
					// $oldClinicFilename = public_path() . "/clinic/" . $data['old_clinic_image'];
					// if (file_exists($oldClinicFilename)) {
						// File::delete($oldClinicFilename);
					// }
				// }
				// if (!empty($data['doc_claim_type']) && !empty($clincFileName)) {
					// $this->uploadClinicImgFileBy($clincFileName);
				// }
			// } else {
				// $clincFileName = isset($data['old_clinic_image']) ? $data['old_clinic_image'] : "";
			// }
			// $schedule = "";
			// if (isset($data['schedule']) && count($data['schedule']) > 0) {
				// $schedule = json_encode($data['schedule']);
			// }
			// Doctors::where('id', $data['id'])->update(array(
				// 'doc_type' => $data['doc_type'],
				// 'name' => $data['name'],
				// 'first_name' => $data['first_name'],
				// 'last_name' => $data['last_name'],
				// 'email' => $data['email'],
				// 'clinic_mobile' => $data['clinic_mobile'],
				// 'user_id' => $data['user_id'],
				// 'clinic_email' => $data['clinic_email'],
				// 'reg_no' => isset($data['reg_no']) ? $data['reg_no'] : null,
				// 'reg_year' => isset($data['reg_year']) ? $data['reg_year'] : null,
				// 'reg_council' =>  isset($data['reg_council']) ? $data['reg_council'] : null,
				// 'last_obtained_degree' => isset($data['last_obtained_degree']) ? $data['last_obtained_degree'] : null,
				// 'degree_year' => isset($data['degree_year']) ? $data['degree_year'] : null,
				// 'university' => isset($data['university']) ? $data['university'] : null,
				// 'mobile_no' => $data['mobile_no'],
				// 'gender' => isset($data['gender']) ? $data['gender'] : "",
				// 'speciality' => implode(',', $data['speciality']),
				// 'clinic_speciality' => $data['clinic_speciality'],
				// 'address_1' => $data['address_1'],
				// 'country_id' => $data['country_id'],
				// 'state_id' => $data['state_id'],
				// 'city_id' => $data['city_id'],
				// 'locality_id' => $data['locality_id'],
				// 'zipcode' => $data['zipcode'],
				// // 'my_visits' => $data['my_visits'],
				// 'consultation_fees' => $data['consultation_fees'],
				// 'fees_show' => $data['fees_show'],
				// 'oncall_status' => (isset($data['oncall_status']) ? implode(',', $data['oncall_status']) : null),
				// 'oncall_fee' => $data['oncall_fee'],
				// 'convenience_fee' => $data['convenience_fee'],
				// 'acc_no' => (isset($data['acc_no']) ? $data['acc_no'] : null),
				// 'acc_name' => (isset($data['acc_name']) ? $data['acc_name'] : null),
				// 'ifsc_no' => (isset($data['ifsc_no']) ? $data['ifsc_no'] : null),
				// 'bank_name' => (isset($data['bank_name']) ? $data['bank_name'] : null),
				// 'paytm_no' => (isset($data['paytm_no']) ? $data['paytm_no'] : null),
				// 'clinic_name' => $data['clinic_name'],
				// 'experience' => $data['experience'],
				// 'qualification' => $data['qualification'],
				// 'recommend' => $data['recommend'],
				// 'slot_duration' => (isset($data['slot_duration']) ? $data['slot_duration'] : '5'),
				// 'profile_pic' => $fileName,
				// 'clinic_image' => $clincFileName,
				// 'opd_timings' => $schedule,
				// 'content' => $data['content'],
				// 'note' => $data['note'],
				// 'doctor_signature' => $signatureFileName,
				// 'servtel_api_key' => $data['servtel_api_key'],
				// 'hg_interested' => isset($data['hg_interested']) ? $data['hg_interested'] : 0,
				// // 'consultation_discount' => isset($data['consultation_discount']) ? $data['consultation_discount'] : null,
			// ));
			// $doctor_data = Doctors::find($data['id']);
			// $docData = DoctorData::select("id")->where(["doc_id" => $data['id']])->first();
			// $alternate_address = (isset($data['alternate_address'])) ? json_encode($data['alternate_address']) : NULL;
			// $doc_lng = (isset($data['languages'])) > 0 ? implode(',', $data['languages']) : NULL;
			// if (empty($docData)) {
				// if (!empty($data['followup_count']) || !empty($data['plan_consult_fee']) || (isset($data['alternate_address']))) {
					// DoctorData::create([
						// 'doc_id' => $data['id'],
						// 'user_id' => $data['user_id'],
						// 'followup_count' => $data['followup_count'],
						// 'plan_consult_fee' => $data['plan_consult_fee'],
						// 'alternate_address' => $alternate_address,
						// 'languages' => $doc_lng,
					// ]);
				// }
			// } else {
				// DoctorData::where(['id' => $docData->id])->update([
					// 'user_id' => $data['user_id'],
					// 'followup_count' => $data['followup_count'],
					// 'plan_consult_fee' => $data['plan_consult_fee'],
					// 'alternate_address' => $alternate_address,
					// 'languages' => $doc_lng,
				// ]);
			// }
			// if (!empty($data['doc_claim_type'])) {
				// $doc_data = Doctors::select("member_id")->where('id', $data['id'])->first();
				// $user = ehrUser::select("id")->where('member_id', $doc_data->member_id)->first();
				// $practice =  RoleUser::select(['user_id', 'role_id', 'practice_id'])->where(['user_id' => $user->id])->first();
				// PracticeDetails::where('user_id', $user->id)->update(array(
					// 'clinic_name' => ucfirst($data['clinic_name']),
					// 'email' => $data['clinic_email'],
					// 'mobile' => $data['clinic_mobile'],
					// // 'address_1' => $data['address_1'],
					// // 'city_id' => $data['city_id'],
					// // 'state_id' => $data['state_id'],
					// // 'country_id' => $data['country_id'],
					// // 'zipcode' => $data['zipcode'],
					// 'specialization' => $data['clinic_speciality'],
					// 'slot_duration' => (isset($data['slot_duration']) ? $data['slot_duration'] : '5'),
					// 'logo' => $clincFileName,
					// //'my_visits' => '{"1":{"id":"1","amount":"'.$data['consultation_fees'].'"},"2":{"id":"4","amount":""}}',
				// ));
				// DoctorsInfo::where('user_id', $user->id)->update(array(
					// 'first_name' => ucfirst($data['first_name']),
					// 'last_name' => $data['last_name'],
					// 'mobile' => $data['mobile_no'],
					// 'gender' => $data['gender'],
					// 'reg_no' => $data['reg_no'],
					// 'reg_year' => isset($data['reg_year']) ? $data['reg_year'] : null,
					// 'reg_council' =>  isset($data['reg_council']) ? $data['reg_council'] : null,
					// 'last_obtained_degree' => isset($data['last_obtained_degree']) ? $data['last_obtained_degree'] : null,
					// 'degree_year' => isset($data['degree_year']) ? $data['degree_year'] : null,
					// 'university' => isset($data['university']) ? $data['university'] : null,
					// 'speciality' => implode(',', $data['speciality']),
					// 'address_1' => $data['address_1'],
					// 'city_id' => $data['city_id'],
					// 'state_id' => $data['state_id'],
					// 'country_id' => $data['country_id'],
					// 'zipcode' => $data['zipcode'],
					// 'consultation_fee' => $data['consultation_fees'],
					// // 'consultation_discount' => isset($data['consultation_discount']) ? $data['consultation_discount'] : null,
					// 'oncall_status' => (isset($data['oncall_status']) ? implode(',', $data['oncall_status']) : null),
					// 'oncall_fee' => $data['oncall_fee'],
					// 'acc_no' => (isset($data['acc_no']) ? $data['acc_no'] : null),
					// 'acc_name' => (isset($data['acc_name']) ? $data['acc_name'] : null),
					// 'ifsc_no' => (isset($data['ifsc_no']) ? $data['ifsc_no'] : null),
					// 'bank_name' => (isset($data['bank_name']) ? $data['bank_name'] : null),
					// 'paytm_no' => (isset($data['paytm_no']) ? $data['paytm_no'] : null),
					// 'experience' => $data['experience'],
					// 'content' => $data['content'],
					// 'servtel_api_key' => $data['servtel_api_key'],
					// 'profile_pic' => $fileName,
					// 'doctor_sign' => $signatureFileName,
					// 'sign_view' => (isset($data['sign_view']) ? $data['sign_view'] : 0),
					// 'educations'  => ($data['qualification'] != "") ? $data['qualification'] : "",
				// ));
				// OpdTimings::where('user_id', $user->id)->update(['schedule' => $schedule]);
				// if (isset($data['is_subcribed_user']) && $data['is_subcribed_user'] == '1') {
					// $values = Settings::where('key', "specialist_doctor_user_ids")->first();
					// if (!empty($values->value)) {
						// $values = explode(",", $values->value);
						// if (!in_array($user->id, $values)) {
							// array_push($values, $user->id);
							// Settings::where('key', "specialist_doctor_user_ids")->update(['value' => implode(",", $values)]);
						// }
					// }
				// } else {
					// $values = Settings::where('key', "specialist_doctor_user_ids")->first();
					// if (!empty($values->value)) {
						// $values = explode(",", $values->value);
						// if (in_array($user->id, $values)) {
							// $values = array_diff($values, [$user->id]);
							// Settings::where('key', "specialist_doctor_user_ids")->update(['value' => implode(",", $values)]);
						// }
					// }
				// }
			// } else {
				// if (isset($data['is_complete']) && $data['is_complete'] == "1") {
					// if ($data['type'] == "non_hg") {
						// Doctors::where('id', $data['id'])->update(array(
							// 'email' => $data['email'],
							// 'claim_status' => 1,
							// 'claim_profile_web' => 1,
							// 'profile_status' => 1,
						// ));
					// } else if ($data['type'] == "claim") {
						// Doctors::where('id', $data['id'])->update(array('email' => $data['email']));
						// // ehrUser::where('member_id', $doc_data->member_id)->update(array('email' => $data['email']));
					// }
				// } else {
					// if ($data['type'] == "non_hg" || $data['type'] == "claim") {
						// Doctors::where('id', $data['id'])->update(array('email' => $data['email']));
					// }
				// }
			// }
			// return 1;
		// }
	// }
	
	
	public function updateDoctor(Request $request) {
		if ($request->isMethod('post')) {
			$data = $request->all();
			$fileName = null;
			$clincFileName = null;
			if($request->hasFile('profile_pic')) {
                  $image  = $request->file('profile_pic');
                  $fullName = str_replace(" ","",$image->getClientOriginalName());
                  $onlyName = explode('.',$fullName);
                  if(is_array($onlyName)){
                    $fileName = $onlyName[0].time().".".$onlyName[1];
                  }
                  else{
                    $fileName = $onlyName.time();
                  }
				  $docPath = "public/doctor/ProfilePics/";
				  Storage::disk('s3')->put($docPath.$fileName, file_get_contents($image));
				  if(isset($data['old_profile_pic']) && !empty($data['old_profile_pic'])) {
					if(Storage::disk('s3')->exists($docPath.$data['old_profile_pic'])) {
						Storage::disk('s3')->delete($docPath.$data['old_profile_pic']);
					}
				  }
			}
			else{
                $fileName = isset($data['old_profile_pic']) ? $data['old_profile_pic'] : "";
            }

			if($request->hasFile('doctor_signature')) {
				$images  = $request->file('doctor_signature');
				$fullName = str_replace(" ","",$images->getClientOriginalName());
				$onlyName = explode('.',$fullName);
				if(is_array($onlyName)){
				  $signatureFileName = $onlyName[0].time().".".$onlyName[1];
				}
				else{
				  $signatureFileName = $onlyName.time();
				}
				$docPath = "public/doctor/signature/";
				if(!Storage::disk('s3')->exists($docPath)){
				   Storage::disk('s3')->makeDirectory($docPath);
				}
				Storage::disk('s3')->put($docPath.$signatureFileName, file_get_contents($images));
				if(isset($data['old_signature_image']) && !empty($data['old_signature_image'])){
					$filename = $docPath.$data['old_signature_image'];
					if(Storage::disk('s3')->exists($filename)) {
					   Storage::disk('s3')->delete($filename);
					}
				}
		  }
		  else{
			  $signatureFileName = isset($data['old_signature_image']) ? $data['old_signature_image'] : "";
		  }
			if($request->hasFile('clinic_image')) {
                  $image  = $request->file('clinic_image');
                  $fullName = str_replace(" ","",$image->getClientOriginalName());
                  $onlyName = explode('.',$fullName);
                  if(is_array($onlyName)){
                    $clincFileName = $onlyName[0].time().".".$onlyName[1];
                  }
                  else{
                    $clincFileName = $onlyName.time();
                  }
				  $docPath = "public/doctor/";
				  if(!Storage::disk('s3')->exists($docPath)){
					 Storage::disk('s3')->makeDirectory($docPath);
				  }
				  Storage::disk('s3')->put($docPath.$clincFileName, file_get_contents($image));
				  if(isset($data['old_clinic_image']) && !empty($data['old_clinic_image'])){
					  $filename = $docPath.$data['old_clinic_image'];
					  if(Storage::disk('s3')->exists($filename)) {
						 Storage::disk('s3')->delete($filename);
					  }
				  }
            }
			else{
                $clincFileName = isset($data['old_clinic_image']) ? $data['old_clinic_image'] : "";
            }
			$schedule = "";
			if(isset($data['schedule']) && count($data['schedule']) > 0) {
				$schedule = json_encode($data['schedule']);
			}
			Doctors::where('id', $data['id'])->update(array(
			    'doc_type' => $data['doc_type'],
				'name' => $data['name'],
				'first_name' => $data['first_name'],
				'last_name' => $data['last_name'],
				// 'email' => $data['email'],
				'clinic_mobile' => $data['clinic_mobile'],
				'user_id' => $data['user_id'],
				'clinic_email' => $data['clinic_email'],
				'reg_no' => isset($data['reg_no']) ? $data['reg_no'] : null,
				'reg_year' => isset($data['reg_year']) ? $data['reg_year'] : null,
				'reg_council' =>  isset($data['reg_council']) ? $data['reg_council'] : null,
				'last_obtained_degree' => isset($data['last_obtained_degree']) ? $data['last_obtained_degree'] : null,
				'degree_year' => isset($data['degree_year']) ? $data['degree_year'] : null,
				'university' => isset($data['university']) ? $data['university'] : null,
				'mobile_no' => $data['mobile_no'],
				'gender' => isset($data['gender']) ? $data['gender'] : "",
				'speciality' => implode(',',$data['speciality']),
				'clinic_speciality' => $data['clinic_speciality'],
				'address_1' => $data['address_1'],
				'country_id' => $data['country_id'],
				'state_id' => $data['state_id'],
				'city_id' => $data['city_id'],
				'locality_id' => $data['locality_id'],
				'zipcode' => $data['zipcode'],
				// 'my_visits' => $data['my_visits'],
				'consultation_fees' => $data['consultation_fees'],
				'fees_show' => $data['fees_show'],
				'oncall_status' => (isset($data['oncall_status']) ? implode(',',$data['oncall_status']) : null),
				'oncall_fee' => $data['oncall_fee'],
				'convenience_fee' => $data['convenience_fee'],
				'acc_no' => (isset($data['acc_no']) ? $data['acc_no'] : null),
				'acc_name' => (isset($data['acc_name']) ? $data['acc_name'] : null),
				'ifsc_no' => (isset($data['ifsc_no']) ? $data['ifsc_no'] : null),
				'bank_name' => (isset($data['bank_name']) ? $data['bank_name'] : null),
				'paytm_no' => (isset($data['paytm_no']) ? $data['paytm_no'] : null),
				'clinic_name' => $data['clinic_name'],
				'experience' => $data['experience'],
				'qualification' => $data['qualification'],
				'recommend' => $data['recommend'],
				'slot_duration' => (isset($data['slot_duration']) ? $data['slot_duration'] : '5'),
				'profile_pic' => $fileName,
				'clinic_image' => $clincFileName,
				'opd_timings' => $schedule,
				'content' => $data['content'],
				'note' => $data['note'],
				'doctor_signature' => $signatureFileName,
				'servtel_api_key' => $data['servtel_api_key'],
				'hg_interested' => isset($data['hg_interested']) ? $data['hg_interested'] : 0,
				// 'consultation_discount' => isset($data['consultation_discount']) ? $data['consultation_discount'] : null,
			));
			$doctor_data = Doctors::find($data['id']);
			$docData = DoctorData::select("id")->where(["doc_id"=>$data['id']])->first();
			$alternate_address = (isset($data['alternate_address'])) ? json_encode($data['alternate_address']) : NULL;
			$doc_lng = (isset($data['language'])) > 0 ? implode(',', $data['language']) : NULL;
			if(empty($docData)) {
				if(!empty($data['followup_count']) || !empty($data['plan_consult_fee']) || (isset($data['alternate_address']))){
					DoctorData::create([
						'doc_id'=>$data['id'],
						'user_id'=>$data['user_id'],
						'followup_count'=>$data['followup_count'],
						'plan_consult_fee'=>$data['plan_consult_fee'],
						'alternate_address'=> $alternate_address,
						'language' => $doc_lng,
					]);
				}
			}
			else{
				DoctorData::where(['id'=>$docData->id])->update([
					'user_id'=>$data['user_id'],
					'followup_count'=>$data['followup_count'],
					'plan_consult_fee'=>$data['plan_consult_fee'],
					'alternate_address'=>$alternate_address,
					'language' => $doc_lng,
				]);
			}
			if(!empty($data['doc_claim_type'])) {
				$doc_data = Doctors::select("member_id")->where('id', $data['id'])->first();
				$user = ehrUser::select("id")->where('member_id', $doc_data->member_id)->first();
				$practice =  RoleUser::select(['user_id','role_id','practice_id'])->where(['user_id'=>$user->id])->first();
				PracticeDetails::where('user_id', $user->id)->update(array(
					'clinic_name' => ucfirst($data['clinic_name']),
					'email' => $data['clinic_email'],
					'mobile' => $data['clinic_mobile'],
					// 'address_1' => $data['address_1'],
					// 'city_id' => $data['city_id'],
					// 'state_id' => $data['state_id'],
					// 'country_id' => $data['country_id'],
					// 'zipcode' => $data['zipcode'],
					'specialization' => $data['clinic_speciality'],
					'slot_duration' => (isset($data['slot_duration']) ? $data['slot_duration'] : '5'),
					'logo' => $clincFileName,
					//'my_visits' => '{"1":{"id":"1","amount":"'.$data['consultation_fees'].'"},"2":{"id":"4","amount":""}}',
				));
				DoctorsInfo::where('user_id', $user->id)->update(array(
					 'first_name' => ucfirst($data['first_name']),
					 'last_name' => $data['last_name'],
					 'mobile' => $data['mobile_no'],
					 'gender' => $data['gender'],
					 'reg_no' => isset($data['reg_no']) ? $data['reg_no'] : null,
					 'reg_year' => isset($data['reg_year']) ? $data['reg_year'] : null,
					 'reg_council' =>  isset($data['reg_council']) ? $data['reg_council'] : null,
					 'last_obtained_degree' => isset($data['last_obtained_degree']) ? $data['last_obtained_degree'] : null,
					 'degree_year' => isset($data['degree_year']) ? $data['degree_year'] : null,
					 'university' => isset($data['university']) ? $data['university'] : null,
					 'speciality' => implode(',',$data['speciality']),
					 'address_1' => $data['address_1'],
					 'city_id' => $data['city_id'],
					 'state_id' => $data['state_id'],
					 'country_id' => $data['country_id'],
					 'zipcode' => $data['zipcode'],
					 'consultation_fee' => $data['consultation_fees'],
					 // 'consultation_discount' => isset($data['consultation_discount']) ? $data['consultation_discount'] : null,
					 'oncall_status' => (isset($data['oncall_status']) ? implode(',',$data['oncall_status']) : null),
	 				 'oncall_fee' => $data['oncall_fee'],
					 'acc_no' => (isset($data['acc_no']) ? $data['acc_no'] : null),
					 'acc_name' => (isset($data['acc_name']) ? $data['acc_name'] : null),
					 'ifsc_no' => (isset($data['ifsc_no']) ? $data['ifsc_no'] : null),
					 'bank_name' => (isset($data['bank_name']) ? $data['bank_name'] : null),
					 'paytm_no' => (isset($data['paytm_no']) ? $data['paytm_no'] : null),
					 'experience' => $data['experience'],
					 'content' => $data['content'],
					 'servtel_api_key' => $data['servtel_api_key'],
					 'profile_pic' => $fileName,
					 'doctor_sign' => $signatureFileName,
					 'sign_view' => (isset($data['sign_view']) ? $data['sign_view'] : 0),
					 'educations'  => ($data['qualification']!="")? $data['qualification'] : "",
				));
				OpdTimings::where('user_id', $user->id)->update(['schedule' => $schedule]);
				if(isset($data['is_subcribed_user']) && $data['is_subcribed_user'] == '1') {
					$values = Settings::where('key',"specialist_doctor_user_ids")->first();
					if(!empty($values->value)) {
						$values = explode(",",$values->value);
						if(!in_array($user->id,$values)){
							array_push($values,$user->id);
							Settings::where('key',"specialist_doctor_user_ids")->update(['value' => implode(",",$values)]);
						}
					}
				}
				else{
					$values = Settings::where('key',"specialist_doctor_user_ids")->first();
					if(!empty($values->value)) {
						$values = explode(",",$values->value);
						if(in_array($user->id,$values)){
							$values = array_diff($values,[$user->id]);
							Settings::where('key',"specialist_doctor_user_ids")->update(['value' => implode(",",$values)]);
						}
					}
				}
			}
			else{
				if(isset($data['is_complete']) && $data['is_complete'] == "1") {
					if($data['type'] == "non_hg") {
						Doctors::where('id', $data['id'])->update(array(
							'email' => $data['email'],
							'claim_status' => 1,
							'claim_profile_web' => 1,
							'profile_status' => 1,
						));
					}
					else if($data['type'] == "claim"){
						Doctors::where('id', $data['id'])->update(array('email' => $data['email']));
						// ehrUser::where('member_id', $doc_data->member_id)->update(array('email' => $data['email']));
					}
				}
				else{
					if($data['type'] == "non_hg" || $data['type'] == "claim") {
						Doctors::where('id', $data['id'])->update(array('email' => $data['email']));
					}
				}
			}
			return 1;
		}
	}
	
	
	
	public function changeDoctorStatus(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			// dd($data);
			if ($data['status'] == '1') {
				Doctors::where('id', $data['id'])->update(array('status' => '0'));
			} else {
				Doctors::where('id', $data['id'])->update(array('status' => '1'));
			}
			return 1;
		}
	}
	public  function loadHeaderData(Request $request)
	{
		return view('layouts.admin.partials.top-nav');
	}

	public  function patientList(Request $request)
	{
		Log::info('Patient list function called', ['request' => $request->all()]);
		
		if ($request->isMethod('post')) {
			// Handle POST request - encode parameters and redirect
			$params = $this->encodePatientSearchParams($request);
			return redirect()->route('admin.patientList', $params)->withInput();
		}

		// Build base query with optimized eager loading
		$query = User::with([
			'OrganizationMaster', 
			'student', 
			'getCityName', 
			'State', 
			'UsersSubscriptions'
		])
		->select('id', 'student_id', 'email', 'pId', 'device_type', 'login_type', 
			'first_name', 'last_name', 'gender', 'mobile_no', 'other_mobile_no', 
			'address', 'city_id', 'state_id', 'organization', 'location_meta', 
			'created_at', 'note', 'call_status', 'urls')
		->where(['parent_id' => 0]);

		// Apply filters directly in the query
		$this->applyPatientQueryFilters($query, $request);

		// Get pagination size
		$perPage = !empty($request->input('page_no')) ? base64_decode($request->input('page_no')) : 25;
		
		// Check if export is requested
		$user_type = base64_decode($request->input('user_type'));
		if (base64_decode($request->input('file_type')) == "excel") {
			return $this->exportPatientsToExcel($query);
    }


		// Get paginated results for normal view
		$patients = $query->orderBy('id', 'DESC')->paginate($perPage);
		
		// Get additional data for the view
		$fileName = $this->getLatestExportedFileName();
		$recentJobData = BulkExportCSV::where('export_status', "Completed")->latest()->first();
		
		// Get other data needed for the view
		$OrganizationList = OrganizationMaster::select('id', 'title')
			->where("delete_status", 1)
			->orderBy('id', 'desc')
			->get();
			
		$practices = Doctors::with("docSpeciality")
			->select('oncall_fee', 'consultation_fees', 'first_name', 'last_name', 'email', 'user_id', 'id')
			->where([
				"delete_status" => 1, 
				"hg_doctor" => 1, 
				"claim_status" => 1, 
				"varify_status" => 1
			])
			->orderBy("id", "ASC")
			->get();
			
		$plans = UserPlan::where(["delete_status" => 1, 'status' => 1])
			->orderBy('id', 'desc')
			->whereIn('type', ['1', '2'])
			->get();
			
		$labpackages = LabPackage::where('company_id', 3)
			->where(['delete_status' => 1, 'status' => 1])
			->get();
			
		$symptomslist = Symptoms::where(['delete_status' => 1])->get();
		
		// Get all patient IDs for JavaScript operations
		$AllPatientsIds = $patients->pluck('id')->toArray();

		return view('admin.Patients.patient-list', compact(
			'patients', 
			'AllPatientsIds', 
			'OrganizationList', 
			'practices', 
			'plans', 
			'labpackages', 
			'symptomslist', 
			'recentJobData', 
			'fileName'
		));
	}

	// Helper method to encode search parameters
	private function encodePatientSearchParams(Request $request) {
		$params = [];
		$fields = [
			'search', 'filter_by', 'search_value', 'state_id', 'city_id', 'user_type',
			'reg_type', 'start_date', 'end_date', 'page_no', 'file_type', 
			'is_status', 'organization_type', 'ref_code'
		];

		foreach ($fields as $field) {
			if ($request->filled($field)) {
				$params[$field] = base64_encode($request->input($field));
			} elseif (isset($request[$field]) && $field == 'is_status' || $field == 'organization_type') {
				// Handle empty string values for these specific fields
				$params[$field] = base64_encode($request->input($field));
			}
		}
		return $params;
	}

	// Helper method to apply filters to query
	private function applyPatientQueryFilters($query, Request $request) {
		// Search filter
		if ($search = base64_decode($request->input('search'))) {
			$query->where(DB::raw('concat(IFNULL(first_name,"")," ",IFNULL(last_name,"")," ",IFNULL(mobile_no,""))'), 'like', '%' . $search . '%');
		}

		// Filter by specific field
		if ($filter_by = base64_decode($request->input('filter_by'))) {
			$search_value = base64_decode($request->input('search_value'));
			if ($filter_by == '1') {
				$query->where('mobile_no', $search_value);
			} elseif ($filter_by == '2') {
				$query->where('email', $search_value);
			} elseif ($filter_by == '3') {
				$query->whereIn('student_id', function ($subquery) use ($search_value) {
					$subquery->select('id')
							->from('students')
							->where('student_id', 'LIKE', '%' . $search_value . '%');
				});
			}
		}

		// State filter
		if ($state_id = base64_decode($request->input('state_id'))) {
			$query->where(function ($qry) use ($state_id) {
				$qry->where('state_id', $state_id);
				$state_name = getStateName($state_id);
				if (!empty($state_name)) {
					$qry->orWhere('location_meta', 'LIKE', '%' . $state_name . '%');
				}
			});
		}

		// City filter
		if ($city_id = base64_decode($request->input('city_id'))) {
			$query->where(function ($qry) use ($city_id) {
				$qry->where('city_id', $city_id);
				$city_name = getCityName($city_id);
				if (!empty($city_name)) {
					$qry->orWhere('location_meta', 'LIKE', '%' . $city_name . '%');
				}
			});
		}

		// Date range filter
		$this->applyPatientDateFilters($query, $request);

		// Call status filter
		if ($request->input('is_status') !== null && $request->input('is_status') != "") {
			$is_status = base64_decode($request->input('is_status'));
			$query->where('call_status', $is_status);
		}

		// User type filter
		if ($user_type = base64_decode($request->input('user_type'))) {
			$this->applyUserTypeFilter($query, $user_type, $request);
		}

		// Registration type filter
		if ($reg_type = base64_decode($request->input('reg_type'))) {
			if ($reg_type != 4) {
				$query->where(['device_type' => $reg_type]);
			}
		}

		// Organization type filter
		if ($request->filled('organization_type')) {
			$organization_type = base64_decode($request->input('organization_type'));
			if ($organization_type == "blank") {
				$organization_type = null;
			}
			$query->where(['organization' => $organization_type]);
		}

		// Referral code filter
		if ($ref_code = base64_decode($request->input('ref_code'))) {
			$query->whereJsonContains('urls->ref_code', $ref_code);
		}
	}

	// Helper method for date filters
	private function applyPatientDateFilters($query, Request $request) {
		if (!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
			if (!empty($request->input('start_date'))) {
				$start_date = date('Y-m-d', strtotime(base64_decode($request->input('start_date'))));
				$query->whereRaw('date(created_at) >= ?', [$start_date]);
			}
			if (!empty($request->input('end_date'))) {
				$end_date = date('Y-m-d', strtotime(base64_decode($request->input('end_date'))));
				$query->whereRaw('date(created_at) <= ?', [$end_date]);
			}
		}
	}

	// Helper method for user type filters
	private function applyUserTypeFilter($query, $user_type, Request $request) {
		if ($user_type == 1) {
			$UsersSubscriptions = UsersSubscriptions::select('user_id')
				->where('order_status', 1)
				->groupBy('user_id')
				->pluck('user_id');
			$query->whereIn('id', $UsersSubscriptions);
		} else if ($user_type == 2) {
			if (!empty($request->input('reg_type'))) {
				if (base64_decode($request->input('reg_type')) == 4) {
					$query->whereIn('login_type', [3]);
				} else {
					$query->whereIn('login_type', [2]);
				}
			} else {
				$query->whereIn('login_type', [2, 3]);
			}
		} else if ($user_type == 3) {
			$query->where('login_type', 1);
		} else if ($user_type == 4) {
			$query->whereNull('pId');
		} else if ($user_type == 5) {
			$query->whereIn('login_type', [2, 3])->whereNotNull('pId');
		} else if ($user_type == 6 && !empty($request->input('ref_code'))) {
			$ref_code = base64_decode($request->input('ref_code'));
			$query->whereJsonContains('urls->ref_code', $ref_code);
		}
	}

	// Helper method to export to Excel
	private function exportPatientsToExcel($query) {
		Artisan::call('queue:work', [
			'--queue' => 'bulkExportCSV,default',
			'--stop-when-empty' => true
		]);

		$resource_namespace = 'App\Http\Resources\UserResource';
		$columns = ['Sr No', 'Appointment', 'Subscribed', 'Organization', 'Registered Type', 
			'Name', 'Gender', 'Age', 'Mobile', 'Email', 'Address', 'City', 'State', 
			'Location', 'Note', 'Date'];

		$data = "patient";
		$bulkExportCSV = \BulkExportCSV::build($query, $resource_namespace, $columns , $data);

		$filename = \BulkExportCSV::download($query, $resource_namespace, $columns , $data);

		return response()->json([
			'status' => 'success',
			'message' => 'Excel Generated Successfully!',
			'file' => $filename,
			'url' => asset('public/storage/exportCSV/' . $filename) // optional: full download URL
		]);
	}

	// Helper method to get latest exported file name
	private function getLatestExportedFileName() {
		$directoryPath = public_path('storage/exportCSV');

		if (!is_dir($directoryPath)) {
			mkdir($directoryPath, 0777, true);
		}

		$files = scandir($directoryPath);
		if ($files !== false && count($files) > 2) {
			$files = array_diff($files, ['.', '..']);
			usort($files, function ($a, $b) use ($directoryPath) {
				return filemtime($directoryPath . '/' . $b) - filemtime($directoryPath . '/' . $a);
			});
			return $files ? $files[0] : null;
		}

		return null;
	}





	//Logout
	public function logout()
	{
		Session::forget('id');
		Session::forget('userdata');

		return redirect('/admin');
	}


	public function feedbackPatAll(Request $request)
	{
		$search = '';
		//dd($request->all());
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('start_date'))) {
				$params['start_date'] = base64_encode($request->input('start_date'));
			}
			if (!empty($request->input('end_date'))) {
				$params['end_date'] = base64_encode($request->input('end_date'));
			}
			if (!empty($request->input('doc_id'))) {
				$params['doc_id'] = base64_encode($request->input('doc_id'));
			}
			if (!empty($request->input('app_type'))) {
				$params['app_type'] = base64_encode($request->input('app_type'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = $request->input('file_type');
			}
			if (!empty($request->input('filter_by'))) {
				$params['filter_by'] = base64_encode($request->input('filter_by'));
			}

			return redirect()->route('admin.feedbackPatAll', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = PatientFeedback::with(['Doctors' , 'HandleQueries'])->whereNotNull("user_id");
			$query2 = PatientFeedback::with(['Doctors'])->whereNotNull("user_id");


			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}

			if (!empty($request->input('doc_id'))) {
				$doc_id = base64_decode($request->input('doc_id'));
				$query->where('doc_id', $doc_id);
			}

			if (!empty($request->input('filter_by'))) {
				$filter_by = base64_decode($request->input('filter_by'));
				$query->whereHas('HandleQueries', function ($q) use ($filter_by) {
					$q->where('r_from' , 3)->where('type', $filter_by);
				});
			}

			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			if ($request->input('file_type') == "excel") {
				$feedbacks = $query->orderBy('id', 'desc')->get();
				$enqDataArray[] = array('Sr. No.', 'Doctor Name', 'User Name', 'User Mobile', 'Visit Type', 'Recommedation', 'Waiting Time', 'Complement', 'Experirence');

				foreach ($feedbacks as $i => $enq) {
					$DocName = '';
					$varFollow = '';
					$Rating = '';
					$d = '';
					$ab = '';
					if (!empty($enq->rating)) {
						$Rating = $enq->rating;
					}
					if ($enq->recommendation == 1) {
						$ab = 'Less than 5 min';
					}
					if ($enq->recommendation == 2) {
						$ab = '5 min to 10 min';
					}
					if ($enq->recommendation == 3) {
						$ab = '10 min to 30 min';
					}
					if ($enq->recommendation == 4) {
						$ab = '30 min to 1 hour';
					}
					if ($enq->recommendation == 5) {
						$ab = 'More than 1 hour';
					}
					if ($enq->recommendation == 1) {
						$d = 'Yes';
					} else {
						$d = 'No';
					}
					if (!empty($enq->user_id)) {
						$DocName = getUserName($enq->user_id);
					}
					if ($enq->visit_type == '1') {
						$varFollow = 'Consultation';
					}
					if ($enq->visit_type == '2') {
						$varFollow = 'Procedure';
					}
					if ($enq->visit_type == '3') {
						$varFollow = 'Follow up';
					}

					$enqDataArray[] = array(
						$i + 1,
						@$enq->Doctors->first_name . " " . @$enq->Doctors->last_name,
						$DocName,
						@$enq->User->mobile_no,
						$varFollow,
						$Rating,
						$d,
						$ab,
						$enq->experience,

					);
				}
				return Excel::download(new QueriesExport($enqDataArray), 'feedback.xlsx');
			}
			$feedbacks = $query->orderBy('id', 'desc')->paginate($page);
			$doctors = $query2->orderBy('id', 'desc')->groupBy('doc_id')->get();
		}
		//$practices = Doctors::select(["id","first_name","last_name"])->where(['status'=>1])->orderBy("id","DESC")->get();

		return view('admin.feedbacks.feedback', compact('feedbacks', 'doctors'));
	}

	public function changeFeedbackStatus(Request $request)
	{
		$data = $request->all();
		if ($data['type'] == '1') {
			if ($data['status'] == '1') {
				PatientFeedback::where('id', $data['id'])->update(array('publish_admin' => '0'));
			} else {
				PatientFeedback::where('id', $data['id'])->update(array('publish_admin' => '1'));
			}
			return 1;
		} elseif ($data['type'] == '2') {
			if ($data['status'] == '1') {
				PatientFeedback::where('id', $data['id'])->update(array('status' => '0'));
			} else {
				PatientFeedback::where('id', $data['id'])->update(array('status' => '1'));
			}
			return 1;
		}
	}

	public function viewFeedback(Request $request)
	{
		$data = $request->all();
		$feedback = PatientFeedback::where('id', $data['id'])->first();
		return view('admin.feedbacks.view-feedback', compact('feedback'));
	}

	public function supportPatAll(Request $request)
	{
		// dd($request->all());
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('user_id'))) {
				$params['user_id'] = base64_encode($request->input('user_id'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('filter_by'))) {
				$params['filter_by'] = base64_encode($request->input('filter_by'));
			}
			return redirect()->route('admin.supportPatAll', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			// dd($search);
			$query = Support::with(['User']);
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}

			if (!empty($request->input('user_id'))) {
				$user_id = base64_decode($request->input('user_id'));
				$query->where('user_id', $user_id);
			}
			if (!empty($request->input('filter_by'))) {
				$filter_by = base64_decode($request->input('filter_by'));
				$query->whereHas('HandleQueries', function ($q) use ($filter_by) {
					$q->where('r_from' , 1)->where('type', $filter_by);
				});
			}

			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			// dd($query);
			$supports = $query->orderBy('id', 'desc')->paginate($page);
		}
		// $users = User::select(["id","first_name","last_name"])->where(['status'=>1])->orderBy("id","ASC")->get();

		return view('admin.feedbacks.support', compact('supports'));
	}
	public function viewSupport(Request $request)
	{
		$data = $request->all();
		$support = Support::where('id', $data['id'])->first();
		return view('admin.feedbacks.view-support', compact('support'));
	}
	public function subcribedAll(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}

			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.subcribedAll', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = SubcribedEmail::orderBy('id', 'desc');
			if (!empty($search)) {
				$query->where('email', 'like', '%' . $search . '%');
			}

			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$subscribes = $query->paginate($page);
		}

		return view('admin.feedbacks.subscribes', compact('subscribes'));
	}


	public function contactQuery(Request $request)
	{	
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('interest_in'))) {
				$params['interest_in'] = base64_encode($request->input('interest_in'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('filter_by'))) {
				$params['filter_by'] = base64_encode($request->input('filter_by'));
			}
			return redirect()->route('admin.contactQuery', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = Contact::with("HandleQueries")->where(["delete_status" => 1])->orderBy('id', 'desc');

			if (!empty($request->input('interest_in'))) {
				$interest_in = base64_decode($request->input('interest_in'));
				$query->where('interest_in', $interest_in);
			}
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}

			if (!empty($request->input('filter_by'))) {
				$filter_by = base64_decode($request->input('filter_by'));
				$query->whereHas('HandleQueries', function ($q) use ($filter_by) {
					$q->where('r_from' , 4)->where('type', $filter_by);
				});
			}

			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$contacts = $query->paginate($page);
		}



		return view('admin.feedbacks.contact', compact('contacts'));
	}

	public function enquiryQuery(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('start_date'))) {
				$params['start_date'] = base64_encode($request->input('start_date'));
			}
			if (!empty($request->input('end_date'))) {
				$params['end_date'] = base64_encode($request->input('end_date'));
			}

			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}
			if (!empty($request->input('filter_by'))) {
				$params['filter_by'] = base64_encode($request->input('filter_by'));
			}
			return redirect()->route('admin.enquiryQuery', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = Enquiry::with('User.UsersSubscriptions' , 'HandleQueries')->orderBy('id', 'desc');
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}
			if (!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
				if (!empty($request->input('start_date'))) {
					$start_date = date('Y-m-d', strtotime(base64_decode($request->input('start_date'))));
					$query->whereRaw('date(created_at) >= ?', [$start_date]);
				}
				if (!empty($request->input('end_date'))) {
					$end_date = date('Y-m-d', strtotime(base64_decode($request->input('end_date'))));
					$query->whereRaw('date(created_at) <= ?', [$end_date]);
				}
			}
			if (base64_decode($request->input('file_type')) == "excel") {
				$enqData = $query->orderBy("updated_at", "DESC")->get();
				$enqDataArray[] = array('Sr. No.', 'Name', 'Mobile', 'Email', 'City', 'From', 'Is Subscribed', 'Tot Appt', 'Date');

				foreach ($enqData as $i => $enq) {
					if ($enq->req_from == 1) {
						$var = 'Ad';
					} else {
						$var = 'HealthGennie';
					}

					$enqDataArray[] = array(
						$i + 1,
						$enq->name,
						$enq->mobile,
						$enq->email,
						$enq->city,
						$var,
						(isset($enq->User->UsersSubscriptions) && !empty($enq->User->UsersSubscriptions)) ? 'Yes' : 'No',
						(isset($enq->User) && !empty($enq->User)) ? $enq->User->tot_appointment : 0,
						($enq->updated_at != null) ? date("d-m-Y H:i", strtotime($enq->updated_at)) : "",
					);
				}
				return Excel::download(new QueriesExport($enqDataArray), 'enqueries.xlsx');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			if (!empty($request->input('filter_by'))) {
				$filter_by = base64_decode($request->input('filter_by'));
				$query->whereHas('HandleQueries', function ($q) use ($filter_by) {
					$q->where('r_from' , 1)->where('type', $filter_by);
				});
			}

			$enquirys = $query->paginate($page);
		}
		return view('admin.feedbacks.popupEnquiry', compact('enquirys'));
	}

	public function enquiryStatus(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			Enquiry::where('id', $data['id'])->update(array(
				'status' => 1,
			));
			return 1;
		}
	}

	public function corporateLeads(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('start_date'))) {
				$params['start_date'] = base64_encode($request->input('start_date'));
			}
			if (!empty($request->input('end_date'))) {
				$params['end_date'] = base64_encode($request->input('end_date'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}
			if (!empty($request->input('filter_by'))) {
				$params['filter_by'] = base64_encode($request->input('filter_by'));
			}
			return redirect()->route('admin.corporateLeads', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = Corporate::with('HandleQueries')->orderBy('id', 'desc');
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}
			if (!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
				if (!empty($request->input('start_date'))) {
					$start_date = date('Y-m-d', strtotime(base64_decode($request->input('start_date'))));
					$query->whereRaw('date(created_at) >= ?', [$start_date]);
				}
				if (!empty($request->input('end_date'))) {
					$end_date = date('Y-m-d', strtotime(base64_decode($request->input('end_date'))));
					$query->whereRaw('date(created_at) <= ?', [$end_date]);
				}
			}
			if (base64_decode($request->input('file_type')) == "excel") {
				$enqData = $query->orderBy("updated_at", "DESC")->get();
				$enqDataArray[] = array('Sr. No.', 'Name', 'Mobile', 'Email', 'Date');
				foreach ($enqData as $i => $enq) {
					$enqDataArray[] = array(
						$i + 1,
						$enq->name,
						$enq->mobile,
						$enq->email,
						($enq->updated_at != null) ? date("d-m-Y h:i A", strtotime($enq->updated_at)) : "",
					);
				}
				return Excel::download(new QueriesExport($enqDataArray), 'leads.xlsx');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}

			if (!empty($request->input('filter_by'))) {
				$filter_by = base64_decode($request->input('filter_by'));
				$query->whereHas('HandleQueries', function ($q) use ($filter_by) {
					$q->where('r_from' , 1)->where('type', $filter_by);
				});
			}

			$leads = $query->paginate($page);
		}
		return view('admin.feedbacks.corporateLeads', compact('leads'));
	}

	public function viewContact(Request $request)
	{
		$data = $request->all();
		if ($data['action'] == 2) {
			// action 2 for delete data
			Contact::where('id', $data['id'])->update(['delete_status' => 0]);
			Session::flash('successMsg', "Deleted Successfully");
			return 1;
		} else {
			// action 1 for view data
			$contact = Contact::where('id', $data['id'])->first();
			return view('admin.feedbacks.view-contact', compact('contact'));
		}
	}


	public  function doctorsListForLocality(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('type'))) {
				$params['type'] = base64_encode($request->input('type'));
			}
			if (!empty($request->input('state_id'))) {
				$params['state_id'] = base64_encode($request->input('state_id'));
			}
			if (!empty($request->input('city_id'))) {
				$params['city_id'] = base64_encode($request->input('city_id'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.doctorsListForLocality', $params)->withInput();
		} else {

			$search = base64_decode($request->input('search'));
			$type = base64_decode($request->input('type'));
			$query = Doctors::where(["delete_status" => 1, "status" => 1, "address_status" => 1])->whereNull('locality_id')->where("web_id", '!=', '0');
			if (!empty($search)) {
				$query->where(DB::raw('concat(first_name," ",last_name)'), 'like', '%' . $search . '%');
			}
			if (!empty($request->input('state_id'))) {
				$state_id = base64_decode($request->input('state_id'));
				$query->where('state_id', $state_id);
			}
			if (!empty($request->input('city_id'))) {
				$city_id = base64_decode($request->input('city_id'));
				$query->where('city_id', $city_id);
			}
			if (!empty($locality_id)) {
				$query->Where('locality_id', $locality_id);
			}
			if (!empty($type)) {
				if ($type == 1) {
					$query->Where('address_1', '')->orWhere('address_1', NULL);
				} else if ($type == 2) {
					$query->Where('address_1', '!=', '');
				}
			}

			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$doctors = 	$query->orderBy("updated_at", "DESC")->paginate($page);
		}
		return view('admin.Doctors.locality-docs-list', compact('doctors'));
	}

	public function localityAssign(Request $request)
	{
		$locality_id = $request->locality_id;
		$doc_id = $request->doc_id;
		if (!empty($locality_id) && !empty($doc_id)) {
			Doctors::where('id', $doc_id)->update(["locality_id" => $locality_id]);
			return 1;
		}
		return 0;
	}

	public function localityDoctorUpdateNotFOund(Request $request)
	{
		$doc_id = $request->doc_id;
		if (!empty($doc_id)) {
			Doctors::where('id', $doc_id)->update(["address_status" => 0]);
			return 1;
		}
		return 0;
	}

	public function updateDocAddress(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			Doctors::where('id', $data['doc_id'])->update(array(
				'address_1' => $data['address_1'],
				'city_id' => $data['city_id'],
				'locality_id' => $data['locality_id'],
				'state_id' => $data['state_id'],
				'country_id' => 101,
				'zipcode' => $data['zipcode'],
			));
			return 1;
		}
	}

	public  function sponsoredDoctor(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('user_id'))) {
				$params['user_id'] = base64_encode($request->input('user_id'));
			}
			if (!empty($request->input('package_id'))) {
				$params['package_id'] = base64_encode($request->input('package_id'));
			}
			if (!empty($request->input('status'))) {
				$params['status'] = base64_encode($request->input('status'));
			}

			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}

			return redirect()->route('admin.sponsoredDoctor', $params)->withInput();
		} else {
			$search = base64_decode($request->input('search'));
			$user_id = base64_decode($request->input('user_id'));
			$package_id = base64_decode($request->input('package_id'));
			$status = base64_decode($request->input('status'));
			// dd($status);
			$query = ManageSponsored::with('Doctors')->orderBy("id",  'DESC');
			if (!empty($search)) {
				$query->whereHas('Doctors', function ($q)  use ($search) {
					$q->Where('clinic_name', 'like', '%' . $search . '%');
				});
			}
			if (!empty($user_id)) {
				$query->Where('user_id', $user_id);
			}
			if (!empty($package_id)) {
				$query->Where('package_id', $package_id);
			}
			if (!empty($status)) {
				$query->Where('status', $status);
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$doctors = 	$query->paginate($page);
		}
		return view('admin.Doctors.sponsor-doctors-list', compact('doctors'));
	}

	public function sponsorDoc($action = null, $id = null)
	{

		if (isset($id)) {
			$id = base64_decode($id);
			$sponsor = ManageSponsored::with('Doctors')->where('id', '=', $id)->first();
			return view('admin.Doctors.sponsor-doctor', compact('sponsor'));
		} else {
			return view('admin.Doctors.sponsor-doctor');
		}
	}

	public function doctorSponsorship(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
	
			if (\DateTime::createFromFormat('Y-m-d', $data['start_date']) !== false) {
				$start_date = $data['start_date'];
			} else {
				$start_date = \DateTime::createFromFormat('m/d/Y', $data['start_date'])->format('Y-m-d');
			}
	
			if (\DateTime::createFromFormat('Y-m-d', $data['end_date']) !== false) {
				$end_date = $data['end_date'];
			} else {
				$end_date = \DateTime::createFromFormat('m/d/Y', $data['end_date'])->format('Y-m-d');
			}
			//$state_ids = implode( ',', $data['state_id'] );
			//$city_ids = implode( ',', $data['city_id'] );
			// pr($data);
		
			if (!empty($data['sponsorship_id'])) {
				ManageSponsored::where('id', $data['sponsorship_id'])->update(array(
					'user_id' => $data['id'],
					'package_id' => $data['package_id'],
					'start_date' => $start_date,
					'end_date' => $end_date,
					'state_ids' => $data['state_id'],
					'city_ids' => $data['city_id'],
				));
			} else {
				$ManageSponsored =  ManageSponsored::create([
					'user_id' => $data['id'],
					'package_id' => $data['package_id'],
					'start_date' => $start_date,
					'end_date' => $end_date,
					'state_ids' => $data['state_id'],
					'city_ids' => $data['city_id'],
				]);
			}
			Doctors::where('user_id', $data['id'])->update(array('sponsored_status' => 1));
			return 1;
		}
	}

	public function changeSponsorStatus(Request $request)
	{
		$data = $request->all();
		if ($data['status'] == '1') {
			ManageSponsored::where('id', $data['id'])->update(array('status' => '0'));
		} else {
			ManageSponsored::where('id', $data['id'])->update(array('status' => '1'));
		}

		return 1;
	}

	public function getUniqueId($str)
	{
		$num = 1;
		// $users =  DB::select('SELECT MAX(CAST((SUBSTRING(member_id,4)) as UNSIGNED)) as total FROM healthgennieEhr.users');
		$users =  ehrUser::select(DB::raw('MAX(CAST((SUBSTRING(member_id,4)) as UNSIGNED)) as total'))->pluck('total');
		// return $users[0];
		if (!empty($users)) {
			$num = $users[0] + $num;
		}
		return $str . $num;
	}

	public function uploadDoctorFileBy($fileName, $old_image = null)
	{
		// file_get_contents(getEhrUrl()."/doctorFileWriteByUrl?fileName=".$fileName."&old_profile_pic=".$old_image);
	}

	public function uploadClinicImgFileBy($fileName, $old_image = null)
	{
		// file_get_contents(getEhrUrl()."/clinicFileWriteByUrl?fileName=".$fileName."&old_profile_pic=".$old_image);
	}

	public function deleteDoctorInfo(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			Doctors::where('id', $data['id'])->update(array(
				'delete_status' => 0,
			));
			return 1;
		}
	}


	public function otpList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.otpList', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = \DB::table("otp_practice_details");
			if (!empty($search)) {
				$query->where('mobile_no', 'like', '%' . $search . '%');
			}

			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$otps = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('admin.feedbacks.otp_list', compact('otps'));
	}

	public function appliedQRCode(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$count = DocQrcodeApply::where('doc_id', $data['id'])->count();
			if ($count == 0) {
				DocQrcodeApply::create(array('doc_id' => $data['id']));
			} else {
				$count = DocQrcodeApply::where('doc_id', $data['id'])->delete();
			}
			return 1;
		}
	}

	public  function notificationMaster(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.notificationMaster', $params)->withInput();
		} else {
			$search = base64_decode($request->input('search'));
			$query = ReminderUserNotificatios::where("delete_status", 1);
			if (!empty($search)) {
				$query->where('module_slug', 'like', '%' . $search . '%');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$notifications = $query->orderBy("id", "DESC")->paginate($page);
		}
		return view('admin.Patients.notificationMaster', compact('notifications'));
	}
	public function newNotification(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			// dd($data);
			$a = ReminderUserNotificatios::create([
				"module_slug" => $data["module_slug"],
				"notification" => $data["notification"]
			]);
			$this->myNotificationReminder($data["notification"], $data["module_slug"]);
			return 1;
		}
	}

	public function myNotificationReminder($message, $title)
	{
		$users = DB::table('users')
			->select('fcm_token', 'device_type')
			->where('status', 1)
			->get();
		if (count($users) > 0) {
			foreach ($users as $user) {
				$subtitle = $title;
				$tickerText = 'text here...';
				$fcm_token = $user->fcm_token;
				$device_type = $user->device_type;
				if ($device_type == 1 && !empty($fcm_token)) {
					$notifyres = Parent::pn($this->notificationKey, $fcm_token, $message, $title, $subtitle, $tickerText, 'notifications');
				} else if ($device_type == 2 && !empty($fcm_token)) {
					$iosnotify = Parent::iosNotificationSend($fcm_token, $message, $title, 'notifications');
				}
			}
			return 1;
		}
	}
	public function setDocPosition(Request $request)
	{
		$data = $request->all();
		$count = Doctors::Where('position', '=', $data['pos'])->count();
		if ($count > 0) {
			Doctors::whereNotNull('position')->update(['position' => DB::raw('position + 1')]);
		}
		$doctor = Doctors::Where('id', '=', $data['id'])->update(['position' => $data['pos']]);
		return 1;
	}

	public function sendSupportReply(Request $request)
	{
		$data = $request->all();
		if (!empty($data['user_id'])) {
			$user = User::select(["fcm_token", "device_type"])->where(['id' => $data['user_id']])->first();
			if (!empty($user)) {
				$title = "Health Gennie Support..";
				$subtitle = $title;
				$tickerText = 'text here...';
				$fcm_token = $user->fcm_token;
				$device_type = $user->device_type;
				if ($device_type == 1 && !empty($fcm_token)) {
					$notifyres = Parent::pn($this->notificationKey, $fcm_token, $data['msg'], $title, $subtitle, $tickerText, 'notifications');
				} else if ($device_type == 2 && !empty($fcm_token)) {
					$iosnotify = Parent::iosNotificationSend($fcm_token, $data['msg'], $title, 'notifications');
				}
			}
		}
		if (!empty($data['mobile_no'])) {
			$message =  urlencode($data['msg']);
			$this->sendSMS($data['mobile_no'], $message);
		}
		return 1;
	}



	public function sendUserBulkSms(Request $request)
	{
		ini_set('max_execution_time', 100000);
		$data = $request->all();
		if (strpos($data['msg'], "#user") !== false) {
			$notification_route = $data['notification_route'];
			$nType = $data['nType'];
			$isExistN = UserNotifications::select('id')->where('title', $data['subject'])->count();
			if ($isExistN == 0) {
				$base64Icon = null;
				if ($request->hasFile('notification_icon')) {
					$path = $request->file('notification_icon')->getRealPath();
					$base64Icon = file_get_contents($path);
				}
				$metaData = ['nType' => $data['nType'], 'user_id' => $data['user_id'], 'plan_id' => $data['plan_id'], 'lab_id' => $data['lab_id'], 'symptom_id' => $data['symptom_id']];
				$msgNew = str_replace("#user", "user", $data['msg']);
				UserNotifications::create([
					'type' => 1,
					'title' => $data['subject'],
					'message' => $msgNew,
					'route' => $notification_route,
					'icon' => $base64Icon,
					'meta_data' => json_encode($metaData),
					'created_by' => Session::get('userdata')->id,
				]);
			}
			$notificationIcon = false;
			if ($request->hasFile('notification_icon')) {
				$filepath = base_path() . "/img/";
				$request->file('notification_icon')->move($filepath, 'notificationIcon.png');
				$notificationIcon = true;
			}
			if (!empty($data['ids']) || !empty($data['Allids'])) {
				if (isset($data['smsFor']) && $data['smsFor'] == 1) {
					$ids = json_decode($data['Allids']);
				} else {
					$ids = json_decode($data['ids']);
				}

				$user_id = $data['user_id'];
				$plan_id = $data['plan_id'];
				$lab_id = $data['lab_id'];
				$symptom_id = $data['symptom_id'];
				$pageIds = array('user_id' => $user_id, 'plan_id' => $plan_id, 'lab_id' => $lab_id, 'symptom_id' => $symptom_id);
				$total_success_users = [];
				$total_fail = 0;
				$total_success = 0;
				$fail_users = array();
				$mobileNos = array();
				$fcmTokenAndroid = array();
				$fcmTokenIos = array();
				if (in_array(2, $data['smsType'])) {
					$title = $data['subject'];
					$subtitle = $title;
					$tickerText = 'Health Gennie...';
					foreach (array_chunk($ids, 1000) as $i => $item) {
						$NewArray = [];
						$users = User::select(["first_name", "last_name", "fcm_token", "device_type"])->whereIn('id', $item)->where('parent_id', 0)->where('notification_status', 1)->whereNotNull('fcm_token')->groupBy('fcm_token')->get();
						if ($users->count() > 0) {
							foreach ($users as $raw) {
								$msgg = str_replace("#user", $raw->first_name . " " . $raw->last_name, $data['msg']);
								if ($raw->device_type == 1 || $raw->device_type == 3) {
									$notifyres = $this->sendNotification($this->notificationKey, [$raw->fcm_token], $msgg, $title, $subtitle, $tickerText, $notification_route, $pageIds, $nType, $notificationIcon);
								} else {
									$iosnotify = $this->sendNotificationIos([$raw->fcm_token], $msgg, $title, 'text', $notification_route, $pageIds, $nType, $notificationIcon);
								}
							}
						}
					}
				}
				return 1;
			}
		} else {
			$notification_route = $data['notification_route'];
			$nType = $data['nType'];
			$isExistN = UserNotifications::select('id')->where('title', $data['subject'])->count();
			if ($isExistN == 0) {
				$base64Icon = null;
				if ($request->hasFile('notification_icon')) {
					$path = $request->file('notification_icon')->getRealPath();
					$base64Icon = file_get_contents($path);
				}
				$metaData = ['nType' => $data['nType'], 'user_id' => $data['user_id'], 'plan_id' => $data['plan_id'], 'lab_id' => $data['lab_id'], 'symptom_id' => $data['symptom_id']];
				UserNotifications::create([
					'title' => $data['subject'],
					'message' => $data['msg'],
					'route' => $notification_route,
					'icon' => $base64Icon,
					'meta_data' => json_encode($metaData),
					'created_by' => Session::get('userdata')->id,
				]);
			}
			$notificationIcon = false;
			if ($request->hasFile('notification_icon')) {
				$filepath = base_path() . "/img/";
				$request->file('notification_icon')->move($filepath, 'notificationIcon.png');
				$notificationIcon = true;
			}
			if (!empty($data['ids']) || !empty($data['Allids'])) {
				if (isset($data['smsFor']) && $data['smsFor'] == 1) {
					$ids = json_decode($data['Allids']);
				} else {
					$ids = json_decode($data['ids']);
				}

				$user_id = $data['user_id'];
				$plan_id = $data['plan_id'];
				$lab_id = $data['lab_id'];
				$symptom_id = $data['symptom_id'];
				$pageIds = array('user_id' => $user_id, 'plan_id' => $plan_id, 'lab_id' => $lab_id, 'symptom_id' => $symptom_id);
				$total_success_users = [];
				$total_fail = 0;
				$total_success = 0;
				$fail_users = array();
				$mobileNos = array();
				$fcmTokenAndroid = array();
				$fcmTokenIos = array();

				foreach (array_chunk($ids, 1000) as $i => $item) {
					$NewArray = [];
					$users = User::select(["fcm_token", "device_type"])->whereIn('id', $item)->where('parent_id', 0)->where('notification_status', 1)->whereNotNull('fcm_token')->groupBy('fcm_token')->get();
					if ($users->count() > 0) {
						foreach ($users as $raw) {
							if ($raw->device_type == 1 || $raw->device_type == 3) {
								$fcmTokenAndroid[] =  $raw->fcm_token;
							} else {
								$fcmTokenIos[] =  $raw->fcm_token;
							}
						}
					}
				}
				if (in_array(2, $data['smsType'])) {
					$title = $data['subject'];
					$subtitle = $title;
					$tickerText = 'Health Gennie...';
					foreach (array_chunk($fcmTokenAndroid, 100) as $i => $token) {
						$notifyres = $this->sendNotification($this->notificationKey, $token, $data['msg'], $title, $subtitle, $tickerText, $notification_route, $pageIds, $nType, $notificationIcon);
					}
					foreach (array_chunk($fcmTokenIos, 100) as $i => $token) {
						$iosnotify = $this->sendNotificationIos($token, $data['msg'], $title, 'text', $notification_route, $pageIds, $nType, $notificationIcon);
					}
					$msg_len = 1;
					if (strlen($data['msg']) > 160) {
						$msg_len = 2;
					}
				}
				return 1;
			}
		}
	}

	public function sendNotificationIos($deviceId = null, $message = null, $title = null, $tickerText = null, $page = null, $pageIds = [], $nType = 1, $notificationIcon = false)
	{
		$token = implode($deviceId);
		$notification = array(
			'title' => $title,
			'text' => $message,
			'image' => $notificationIcon == true ? "https://www.healthgennie.com/img/notificationIcon.png" : null,
			'tickerText' => $tickerText,
			'content-available' => 1,
			'foreground' => false
		);
		$arrayToSend = array(
			'to' => $token,
			'notification' => $notification,
			'priority' => 'high',
			'data' => array(
				'page'  => $page,
				'type' => $nType,
				'user_id' => @$pageIds['user_id'],
				'plan_id' => @$pageIds['plan_id'],
				'lab_id' => @$pageIds['lab_id'],
				'symptom_id' => @$pageIds['symptom_id'],
			)
		);
		$headers = array(
			'Content-Type: application/json',
			'Authorization: key= AAAAKfEmcIY:APA91bFnCFD66QXU6DDdOkZ_dVGyCltf72teyb0hi5ifstB27TbIIQACNMhUDwcTx9TZLUPFzRqideyjAI1AlWWYmpS9FQl71AdkeJhHbicnrwTJA2DKMaOyNteels-sxWtMfsPOgHAP'
		);
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
	}

	// public function sendUserBulkSms(Request $request) {
	// 	$data = $request->all();
	// 	if(!empty($data['ids']) || !empty($data['Allids'])){
	// 		if (isset($data['smsFor']) && $data['smsFor'] == 1) {
	// 			$ids = json_decode($data['Allids']);
	// 		}
	// 		else {
	// 			$ids = json_decode($data['ids']);
	// 		}
	// 		$notification_route = $data['notification_route'];
	// 		$user_id = $data['user_id'];
	// 		$plan_id = $data['plan_id'];
	// 		$lab_id = $data['lab_id'];
	// 		$symptom_id = $data['symptom_id'];

	// 		$pageIds = array('user_id'=>$user_id,'plan_id'=>$plan_id,'lab_id'=>$lab_id,'symptom_id'=>$symptom_id);
	// 		$users = User::select(["fcm_token","device_type","mobile_no","id"])->whereIn('id',$ids)->groupBy('fcm_token')->get();
	// 		$total_success_users = [];
	// 		$total_fail = 0;
	// 		$total_success = 0;
	// 		$fail_users=array();

	// 		if (count($users)>0) {
	// 			$mobileNos = array();
	// 			foreach ($users as $key => $user) {
	// 				if(!empty($user->mobile_no) && is_numeric($user->mobile_no) && strlen($user->mobile_no) >= 10) {
	// 					$mobileNos[]= trim(str_replace(" ", "", $user->mobile_no));
	// 					$total_success++;
	// 					$total_success_users[] = $user->id;
	// 				}
	// 				else {
	// 					$total_fail++;
	// 					$fail_users[] = $user->id;
	// 				}
	// 				if(in_array(2,$data['smsType'])) {
	// 					$title = $data['subject'];
	// 					$subtitle = $title;
	// 					$tickerText = 'Health Gennie...';
	// 					$fcm_token = $user->fcm_token;
	// 					$device_type = $user->device_type;
	// 					if($device_type == 1 && !empty($fcm_token)) {
	// 						$notifyres = Parent::pn($this->notificationKey,$fcm_token,$data['msg'],$title,$subtitle,$tickerText,$notification_route,$pageIds);
	// 					}
	// 					else if($device_type == 2 && !empty($fcm_token)) {
	// 						$iosnotify = Parent::iosNotificationSend($fcm_token,$data['msg'],$title,$notification_route);
	// 					}
	// 				}
	// 			}
	// 		}

	// 		if(in_array(1,$data['smsType'])) {
	// 			$message = urlencode($data['msg']);
	// 			$mobilesChunk = array_chunk($mobileNos, 500, true);
	// 			$responceCode = array();
	// 			foreach ($mobilesChunk as  $chunk) {
	// 				// $responceCode[] = 1;
	// 				 $responceCode[] = $this->sendSMS(implode(',',$chunk),$message);
	// 			}
	// 		}
	// 		$msg_len = 1;
	// 		if(strlen($data['msg']) > 160){
	// 		  $msg_len = 2;
	// 		}
	// 		$campaign_sms = Campaigns::create([
	// 			'campaign_type'   =>  (isset($data['smsType']) ? implode(',',$data['smsType']) : null),
	// 			'to_users'        =>  count($ids)  > 0 ? implode(",",$ids) : null,
	// 			'tot_recipients'  =>   count($ids),
	// 			'subject'         =>   $data['subject'],
	// 			'message'         =>   $data['msg'],
	// 			'sender_id'       =>   0,
	// 			'tot_success'     =>   $total_success,
	// 			'tot_success_users' => (count($total_success_users) > 0) ? implode(",",$total_success_users) : null,
	// 			'tot_fail'        =>   count($fail_users),
	// 			'fail_users'      =>   (count($fail_users) > 0) ? implode(",",$fail_users) : null,
	// 			'type'     =>  1,
	// 			'msg_length_cnt'  =>   $msg_len
	// 		]);
	// 		return 1;
	// 	}
	// 	return 2;
	// }
	public function addNote(Request $request)
	{
		if ($request->isMethod('post')) {

			$data = $request->all();
			if (isset($data["note_type"]) && $data["note_type"] == '2') {
				$id = base64_decode($data['id']);
				Appointments::where('id', $id)->update(array('note' => $data['note']));
				return 1;
			} else if (isset($data["note_type"]) && $data["note_type"] == '3') {
				$id = base64_decode($data['id']);
				Doctors::where('id', $id)->update(array('admin_note' => $data['note']));
				return 1;
			} else if (isset($data["note_type"]) && $data["note_type"] == '4') {
				$id = base64_decode($data['id']);
				Support::where('id', $id)->update(array('note' => $data['note']));
				return 1;
			} else {
				$id = base64_decode($data['id']);
				User::where('id', $id)->update(array('note' => $data['note']));
				return 1;
			}
		}
	}

	public function updateCallStatus(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			// pr(date("Y-m-d H:i:s",strtotime($data['followupDate'])));
			if ($data['status'] == 1) {
				User::where('id', $data['user_id'])->update(['call_status' => 1, 'call_sts_data' => date("Y-m-d H:i:s")]);
				return  ['status' => 1, 'stsDate' => date("d-m-Y")];
			} else if ($data['status'] == 3) {
				User::where('id', $data['user_id'])->update(['call_status' => $data['status'], 'followup_date' => date("Y-m-d H:i:s", strtotime($data['followupDate']))]);
				return  ['status' => 3, 'stsDate' => ''];
			} else {
				User::where('id', $data['user_id'])->update(['call_status' => $data['status']]);
				return  ['status' => 2, 'stsDate' => ''];
			}
		}
	}


	public function userOtpList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('start_date'))) {
				$params['start_date'] = base64_encode($request->input('start_date'));
			}
			if (!empty($request->input('end_date'))) {
				$params['end_date'] = base64_encode($request->input('end_date'));
			}
			return redirect()->route('admin.userOtpList', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = UsersOTP::select(["otp","email", "mobile_no", "created_at", "updated_at"])->whereNotNull("otp");
			if (!empty($search)) {
				$query->where('mobile_no', 'like', '%' . $search . '%');
			}
			if (!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
				if (!empty($request->input('start_date'))) {
					$start_date = date('Y-m-d', strtotime(base64_decode($request->input('start_date'))));
					$query->whereRaw('date(updated_at) >= ?', [$start_date]);
				}
				if (!empty($request->input('end_date'))) {
					$end_date = date('Y-m-d', strtotime(base64_decode($request->input('end_date'))));
					$query->whereRaw('date(updated_at) <= ?', [$end_date]);
				}
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$otps = $query->orderBy('updated_at', 'desc')->paginate($page);
		}
		return view('admin.feedbacks.user_otp_list', compact('otps'));
	}

	public  function liveDoctorsList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('speciality_id'))) {
				$params['speciality_id'] = base64_encode($request->input('speciality_id'));
			}
			if (!empty($request->input('grp_speciality'))) {
				$params['grp_speciality'] = base64_encode($request->input('grp_speciality'));
			}
			if (!empty($request->input('status_type'))) {
				$params['status_type'] = base64_encode($request->input('status_type'));
			}
			if (!empty($request->input('filter'))) {
				$params['filter'] = base64_encode($request->input('filter'));
			}
			if (!empty($request->input('state_id'))) {
				$params['state_id'] = base64_encode($request->input('state_id'));
			}
			if (!empty($request->input('city_id'))) {
				$params['city_id'] = base64_encode($request->input('city_id'));
			}
			if (!empty($request->input('locality_id'))) {
				$params['locality_id'] = base64_encode($request->input('locality_id'));
			}

			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}
			if (!empty($request->input('facility'))) {
				$params['facility'] = base64_encode($request->input('facility'));
			}
			if ($request->input('oncall_status') != "") {
				$params['oncall_status'] = base64_encode($request->input('oncall_status'));
			}
			if (!empty($request->input('languages'))) {
				$params['languages'] = base64_encode($request->input('languages'));
			}
			return redirect()->route('admin.liveDoctorsList', $params)->withInput();
		} else {
			$search = base64_decode($request->input('search'));
			$speciality_id = base64_decode($request->input('speciality_id'));
			$grp_speciality = base64_decode($request->input('grp_speciality'));
			$state_id = base64_decode($request->input('state_id'));
			$city_id = base64_decode($request->input('city_id'));
			$locality_id = base64_decode($request->input('locality_id'));
			$status_type = base64_decode($request->input('status_type'));
			$facility = base64_decode($request->input('facility'));
			$oncall_status = base64_decode($request->input('oncall_status'));
			$filter = base64_decode($request->input('filter'));
			$file_type = base64_decode($request->input('file_type'));
			$doclang = base64_decode($request->input('languages'));
			$query = Doctors::with("docSpeciality", 'DoctorsInfo', 'DoctorData')->where(["status" => 1, "delete_status" => 1, "hg_doctor" => 1, "claim_status" => 1, "varify_status" => 1])->where("oncall_status", "!=", 0);
			if (!empty($search)) {
				// $query->where(DB::raw('concat(first_name collate utf8mb4_unicode_ci, " ", last_name collate utf8mb4_unicode_ci, " ", mobile_no collate utf8mb4_unicode_ci, " ", IFNULL(clinic_name collate utf8mb4_unicode_ci, ""))'), 'like', '%' . $search . '%');
				$query->where(function($query) use ($search) {
					$query->where(DB::raw('concat(IFNULL(first_name,"")," ",IFNULL(last_name,"")," ",IFNULL(mobile_no,""))') , 'like', '%'.$search.'%')->orWhere('clinic_name', 'like', '%' . $search . '%');
				});

		

			}

			if (!empty($grp_speciality)) {
				$grp_speciality = DB::table('doctor_specialities')->select("id")->where(["group_id" => $grp_speciality])->get();
				$s_ids = [];
				foreach ($grp_speciality as $ids) {
					$s_ids[] = $ids->id;
				}
				$query->whereIn('speciality', $s_ids);
			}
			if (!empty($speciality_id)) {
				$query->where('speciality', $speciality_id);
			}
			if (!empty($state_id)) {
				$query->where('state_id', $state_id);
			}
			if (!empty($city_id)) {
				$query->where('city_id', $city_id);
			}
			if (!empty($locality_id)) {
				$query->where('locality_id', $locality_id);
			}
			if (!empty($doclang)) {
				$query->whereHas('DoctorData', function ($q) use ($doclang) {
					$q->whereRaw('FIND_IN_SET(?, languages)', [$doclang]);
				});
			}
			if (!empty($status_type)) {
				if ($status_type == 1) {
					$query->where('status', 1);
				} else if ($status_type == 2) {
					$query->where('status', 0);
				}
			}
			if (!empty($oncall_status)) {
				if ($oncall_status == 1) {
					// $query->whereRaw("find_in_set('1',doctors.oncall_status)");
					// $query->where('oncall_status',1);
					$query->where('oncall_status', '=', '1');
				} else if ($oncall_status == 2) {
					// $query->whereRaw("find_in_set('2',doctors.oncall_status)");
					// $query->where('oncall_status',0);
					$query->where('oncall_status', '=', '2');
				} else if ($oncall_status == 3) {
					// $query->whereRaw("find_in_set('2',doctors.oncall_status)");
					// $query->where('oncall_status',0);
					$query->whereIn('oncall_status', ['1,2', '2,1']);
					// $query->where('oncall_status', 'LIKE', '%1,2%');
					// $query->orWhere('oncall_status', 'LIKE', '%2,1%');

					// $query->where(function ($query) use($city_id) {
					// $query->where('location_meta', 'LIKE', '%'.$city_id.'%');
					// });
				}
			}
			if (!empty($facility)) {
				$query->orderBy('clinic_name', 'ASC');
			}

			if (!empty($filter)) {
				if ($filter == 1) {
					$user_id = checSubscribeDoc(1);
					$query->whereIn('user_id', $user_id);
				} elseif ($filter == 2) {
					$user_id = checSubscribeDoc(2);
					$query->whereIn('user_id', $user_id);
				} elseif ($filter == 3) {
					$user_id = checSubscribeDoc(3);
					$query->whereIn('user_id', $user_id);
				} elseif ($filter == 4) {
					$query->where('claim_profile_web', 1);
				} elseif ($filter == 5) {
					$user_id = checSubscribeDoc(4);
					$query->where('varify_status', 1)->whereNotIn('user_id', $user_id);
				} elseif ($filter == 6) {
					$query->whereIn('user_id', getSetting("specialist_doctor_user_ids"));
				}
			}

			if ($file_type == "excel") {
				$doctors = $query->orderBy("created_at", "DESC")->get();
				//$doctorArray[] = array('Sr. No.','Doc Id','Name','Registartion Number','Email','Clinic Email','Mobile','Clinic Mobile','Gender','Speciality','Clnic Name','Address','State','City','Locality','Zipcode','Consultation Fee','Experience','Date');

				foreach ($doctors as $i => $doc) {
					$typee = "";
					// if(substr($doc->member_id,0,3) =='Pra'){
					// if($doc->practice_type == '1'){
					// $typee = "Practice(Clinic)";
					// }
					// else{
					// $typee = "Practice(Hospital)";
					// }
					// }
					// elseif(substr($doc->member_id,0,3) =='Doc') {
					// $typee = "Doctor";
					// }
					$types = [];
					if (!empty($doc->oncall_status)) {
						$types = explode(',', $doc->oncall_status);
					}
					if (in_array(1, $types) && in_array(2, $types)) {
						$typee = "BOTH";
					} elseif (in_array(2, $types)) {
						$typee = "In-clinic";
					} elseif (in_array(1, $types)) {
						$typee = "Tele";
					}
					$doctorArray[] = array(
						$i + 1,
						$doc->id,
						$typee,
						@$doc->first_name . " " . @$doc->last_name,
						@$doc->reg_no,
						@$doc->email,
						@$doc->clinic_email,
						@$doc->mobile_no,
						@$doc->clinic_mobile,
						@$doc->gender,
						@$doc->docSpeciality->specialities,
						@$doc->qualification,
						@$doc->docSpeciality->SpecialityGroup->group_name,
						@$doc->clinic_name,
						@$doc->address_1,
						getStateName(@$doc->state_id),
						getCityName(@$doc->city_id),
						getLocalityName(@$doc->locality_id),
						@$doc->zipcode,
						@$doc->consultation_fees,
						($doc->experience != null) ? @$doc->experience . " years" : "",
						(!empty(@$doc->DoctorsInfo->doctor_sign)) ? "Yes" : "No",
						($doc->opd_timings != null) ? @$doc->opd_timings : "",
						$doc->oncall_fee,
						(!empty($doc->oncall_status)) ? "Yes" : "No",
						($doc->updated_at != null) ? date("d-m-Y h:i A", strtotime($doc->updated_at)) : "",
						($doc->created_at != null) ? date("d-m-Y h:i A", strtotime($doc->created_at)) : "",
					);
				}
				return Excel::download(new DoctorExport($doctorArray), 'doctors.xlsx');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$doctors = 	$query->orderBy("created_at", "DESC")->paginate($page);
			$appliedQRCode = json_decode(DocQrcodeApply::select('doc_id')->pluck('doc_id'));
		}
		return view('admin.Doctors.live-doctors-list', compact('doctors', 'appliedQRCode'));
	}
	public function addPatients(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$no_exists = User::where(['mobile_no' => trim($data['mobile_no'])])->count();
			if ($no_exists > 0) {
				// Session::flash('error', "Mobile Number already exist.");
				return 2;
			} else {

				$first_name = trim(strtok($data['name'], ' '));
				$last_name = trim(strstr($data['name'], ' '));
				$user = User::create([
					'first_name' => ucfirst($first_name),
					'last_name' => $last_name,
					'mobile_no' => $data['mobile_no'],
					'gender' => (isset($data['gender']) ? $data['gender'] : null),
					'dob' => (isset($data['dob']) ? strtotime($data['dob']) : null),
					'profession_type' => $data['profession_type'],
					'organization' => $data['organization'],
					'login_type' => 2,
					'device_type' => 3,
					'register_by' => Session::get('userdata')->id,
				]);
				createUsersReferralCode($user->id);
				Session::flash('message', "User Added Successfully");
				return 1;
			}
		}
		$OrganizationList =  OrganizationMaster::select('id', 'title')->where("delete_status", 1)->orderBy('id', 'desc')->get();
		return view('admin.Patients.add-patients', compact('OrganizationList'));
	}
	public function addUser(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$no_exists = User::where(['mobile_no' => trim($data['mobile_no'])])->count();
			if ($no_exists > 0) {
				// Session::flash('error', "Mobile Number already exist.");
				return 2;
			} else {
				$first_name = trim(strtok($data['name'], ' '));
				$last_name = trim(strstr($data['name'], ' '));
				User::create([
					'first_name' => ucfirst($first_name),
					'last_name' => $last_name,
					'mobile_no' => $data['mobile_no'],
					'profession_type' => $data['profession_type'],
					'organization' => $data['organization'],
					'login_type' => 2,
					'device_type' => 3,
					'register_by' => Session::get('userdata')->id,
				]);
				Session::flash('message', "User Added Successfully");
				return 1;
			}
		}
		$OrganizationList =  OrganizationMaster::select('id', 'title')->where("delete_status", 1)->orderBy('id', 'desc')->get();
		return view('admin.Patients.add-patients', compact('OrganizationList'));
	}
	public function editUser(Request $request, $id)
	{
		$id = base64_decode($id);
		if ($request->isMethod('post')) {
			$data = $request->all();
			$user = User::find($id);
			$old_number = $user->mobile_no;
			$old_email = $user->email;
			$errors = [];
			if ($old_email != $data['email']) {
				$email_exists = User::where('email', 'like', '%' . $data['email'] . '%')->count();
				if ($email_exists > 0) {
					return 2;
				}
			};
			$first_name = trim(strtok($data['name'], ' '));
			$last_name = trim(strstr($data['name'], ' '));
			User::where('id', $id)->update(array(
				'first_name' => ucfirst($first_name),
				'last_name' => $last_name,
				'address' => $data['address'],
				'email' => $data['email'],
				'gender' => (isset($data['gender']) ? $data['gender'] : null),
				'dob' => (isset($data['dob']) ? strtotime($data['dob']) : null),
				'city_id' => $data['city_id'],
				'state_id' => $data['state_id'],
				'country_id' => $data['country_id'],
				'zipcode' => $data['zipcode'],
				'profession_type' => $data['profession_type'],
				'organization' => $data['organization']
			));
			if (!empty($user->patient_number)) {
				Patients::where('patient_number', $user->patient_number)->update(array(
					'first_name' => ucfirst($first_name),
					'last_name' => $last_name,
					'address' => $data['address'],
					'email' => $data['email'],
					'gender' => (isset($data['gender']) ? $data['gender'] : null),
					'dob' => (isset($data['dob']) ? strtotime($data['dob']) : null),
					'city_id' => $data['city_id'],
					'state_id' => $data['state_id'],
					'country_id' => $data['country_id'],
					'zipcode' => $data['zipcode']
				));
			}
			Session::flash('message', "User Added Successfully");
			return 1;
		}
		// dd($id);
		$user = User::find($id);
		$OrganizationList =  OrganizationMaster::select('id', 'title')->where("delete_status", 1)->orderBy('id', 'desc')->get();
		return view('admin.Patients.edit-patients', compact('OrganizationList', 'user'));
	}
	public function addUserAddress(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$userArray = array(
				'locality'   =>  $data['locality'],
				'pincode'    =>  $data['pincode'],
				'address'    =>  $data['address'],
				'landmark'   =>  $data['landmark'],
				'label_type' =>  $data['label_type'],
				'label_name' =>  $data['label_name']
			);
			$user_id = base64_decode($data['user_id']);

			$addresses =  UsersLaborderAddresses::Where(['user_id' => $user_id, 'label_type' => $data['label_type']])->first();

			if ((!empty($addresses) > 0) && ($data['label_type'] == 1 || $data['label_type'] == 2)) {
				UsersLaborderAddresses::Where(['user_id' => $user_id, 'label_type' => $data['label_type']])->update($userArray);
				$address = UsersLaborderAddresses::Where(['user_id' => $user_id, 'label_type' => $data['label_type']])->first();
			} else {
				$userArray['user_id'] = $user_id;
				$address =  UsersLaborderAddresses::create($userArray);
			}
			return $address;
		}
	}

	public  function corporateUsers(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('state_id'))) {
				$params['state_id'] = base64_encode($request->input('state_id'));
			}
			if (!empty($request->input('city_id'))) {
				$params['city_id'] = base64_encode($request->input('city_id'));
			}
			if (!empty($request->input('user_type'))) {
				$params['user_type'] = base64_encode($request->input('user_type'));
			}
			if (!empty($request->input('reg_type'))) {
				$params['reg_type'] = base64_encode($request->input('reg_type'));
			}
			if (!empty($request->input('start_date'))) {
				$params['start_date'] = base64_encode($request->input('start_date'));
			}
			if (!empty($request->input('end_date'))) {
				$params['end_date'] = base64_encode($request->input('end_date'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}
			if ($request->input('is_status') != "") {
				$params['is_status'] = base64_encode($request->input('is_status'));
			}
			return redirect()->route('admin.corporateUsers', $params)->withInput();
		} else {
			$UsersSubscriptions = UsersSubscriptions::select('user_id')->where('order_status', 1)->groupBy('user_id')->pluck('user_id');
			$search = base64_decode($request->input('search'));
			$query = User::select('*');
			if (!empty($search)) {
				$query->where(DB::raw('concat(first_name," ",last_name," ",mobile_no)'), 'like', '%' . $search . '%');
			}
			if (!empty($request->input('state_id'))) {
				$state_id = base64_decode($request->input('state_id'));
				$query->where('state_id', $state_id);
			}
			if (!empty($request->input('city_id'))) {
				$city_id = base64_decode($request->input('city_id'));
				$query->where(function ($qry) use ($city_id) {
					$qry->where('city_id', $city_id);
					$qry->orWhere('location_meta', 'LIKE', '%' . $city_id . '%');
				});
			}
			if (!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
				if (!empty($request->input('start_date'))) {
					$start_date = date('Y-m-d', strtotime(base64_decode($request->input('start_date'))));
					$query->whereRaw('date(created_at) >= ?', [$start_date]);
				}
				if (!empty($request->input('end_date'))) {
					$end_date = date('Y-m-d', strtotime(base64_decode($request->input('end_date'))));
					$query->whereRaw('date(created_at) <= ?', [$end_date]);
				}
			}
			if (!empty($request->input('is_reward'))) {
				$is_reward = base64_decode($request->input('is_reward'));
				if ($is_reward == '1') {
					$query->whereHas('UserCashback', function ($q) {
						$q->where(['status' => '1', 'paytm_status' => 'DE_001']);
					});
				} else if ($is_reward == '2') {
					$query->whereHas('UserCashback', function ($q) {
						$q->where('status', '2');
					});
				}
			}
			if ($request->input('is_status') != "") {
				$is_status = base64_decode($request->input('is_status'));
				$query->where('call_status', $is_status);
			}
			$user_type = base64_decode($request->input('user_type'));
			if (!empty($request->input('user_type'))) {

				if ($user_type == 1) {
					$query->whereIn('id', $UsersSubscriptions);
				} else if ($user_type == 2) {
					if (!empty($request->input('reg_type'))) {
						if (base64_decode($request->input('reg_type')) == 4) {
							$query->whereIn('login_type', [3]);
						} else {
							$query->whereIn('login_type', [2]);
						}
					} else {
						$query->whereIn('login_type', [2, 3]);
					}
				} else if ($user_type == 3) {
					$query->where('login_type', 1);
				} else if ($user_type == 4) {
					$query->whereNull('pId');
				} else if ($user_type == 5) {
					$query->whereIn('login_type', [2, 3])->whereNotNull('pId');
				}
			}
			if (!empty($request->input('reg_type'))) {
				$reg_type = base64_decode($request->input('reg_type'));
				if ($reg_type == 4) {
					// $query->where(['device_type'=>3]);
					// $query2->where(['device_type'=>3]);
				} else {
					$query->where(['device_type' => $reg_type]);
				}
			}
			$query->where(['organization' => 4]);
			$query->where(['parent_id' => 0]);
			if (base64_decode($request->input('file_type')) == "excel") {
				$patData = $query->orderBy("id", "DESC")->get();
				$patDataArr = [];
				foreach ($patData as $i => $element) {
					if ($user_type == '5') {
						if (!empty($element->pId) && getTotalAppointmentByUser($element->pId) > 0) {
							$patDataArr[] = $element;
						}
					} else {
						$patDataArr[] = $element;
					}
				}
				$patData = $patDataArr;
				$patDataArray[] = array(
					'Sr. No.',
					'Appointment',
					'Registered Type',
					'Name',
					'Gender',
					'Age',
					'Mobile',
					'Email',
					'Address',
					'City',
					'State',
					'Note',
					'Date'
				);
				foreach ($patData as $i => $pat) {
					if ($pat->device_type == "1") {
						$dType = "Android";
					} else if ($pat->device_type == "2") {
						$dType = "IOS";
					} else if ($pat->device_type == "3") {
						if ($pat->login_type == "3") {
							$dType = "PAYTM";
						} else {
							$dType = "WEB";
						}
					}
					$patDataArray[] = array(
						$i + 1,
						(!empty($pat->pId)) ? getTotalAppointmentByUser($pat->pId) : 0,
						$dType,
						$pat->first_name . " " . $pat->last_name,
						$pat->gender,
						get_patient_age($pat->dob),
						$pat->mobile_no,
						$pat->email,
						$pat->address,
						getCityName($pat->city_id),
						getStateName($pat->state_id),
						$pat->note,
						($pat->updated_at != null) ? date("d-m-Y h:i A", strtotime($pat->updated_at)) : "",
					);
				}
				return Excel::download(new QueriesExport($patDataArray), 'patients.xlsx');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$patients = $query->orderBy("id", "DESC")->get();
			$patArr = [];
			$patArrId = [];
			// $appt = 0;
			foreach ($patients as $i => $element) {
				if ($user_type == '5') {
					if (!empty($element->pId) && getTotalAppointmentByUser($element->pId) > 0) {
						$patArr[] = $element;
						$patArrId[] = $element->id;
						// $appt += getTotalAppointmentByUser($element->pId);
					}
				} else {
					$patArr[] = $element;
					$patArrId[] = $element->id;
				}
			}
			$patients = $patArr;
			// pr($appt);
			$perPage = 25;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) {
				$currentPage = $input['page'];
			} else {
				$currentPage = 1;
			}
			$offset = ($currentPage * $page) - $page;
			$itemsForCurrentPage = array_slice($patients, $offset, $page, false);
			$patients =  new Paginator($itemsForCurrentPage, count($patients), $page, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
			$AllPatientsIds = $patArrId;
		}
		$OrganizationList =  OrganizationMaster::select('id', 'title')->where("delete_status", 1)->orderBy('id', 'desc')->get();
		return view('admin.Patients.corporate-users-list', compact('patients', 'UsersSubscriptions', 'AllPatientsIds', 'OrganizationList'));
	}

	public function covidHelpList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('start_date'))) {
				$params['start_date'] = base64_encode($request->input('start_date'));
			}
			if (!empty($request->input('end_date'))) {
				$params['end_date'] = base64_encode($request->input('end_date'));
			}
			return redirect()->route('admin.covidHelpList', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = CovidHelp::select(["helper_no", "target_no", "created_at", "updated_at"]);
			if (!empty($search)) {
				$query->where(DB::raw('concat(helper_no," ",target_no)'), 'like', '%' . $search . '%');
			}
			if (!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
				if (!empty($request->input('start_date'))) {
					$start_date = date('Y-m-d', strtotime(base64_decode($request->input('start_date'))));
					$query->whereRaw('date(created_at) >= ?', [$start_date]);
				}
				if (!empty($request->input('end_date'))) {
					$end_date = date('Y-m-d', strtotime(base64_decode($request->input('end_date'))));
					$query->whereRaw('date(created_at) <= ?', [$end_date]);
				}
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$covidhelp = $query->orderBy('created_at', 'desc')->paginate($page);
		}
		return view('admin.feedbacks.covid_help_list', compact('covidhelp'));
	}
	public function uploadDoctorDocuments(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			if (isset($data['doc_id'])) {
				if ($request->hasFile('file')) {
					// $user = Doctors::select('first_name','last_name')->Where('id', $data['id'])->first();
					// $count = UserImage::Where(['user_id'=>$data['user_id'],'type'=>$data['type']])->count();
					// $count = $count + 1;
					// $userName = @$user->first_name." ".$user->last_name;
					$image  = $request->file('file');
					$fullName = str_replace(" ", "", $image->getClientOriginalName());
					$onlyName = explode('.', $fullName);
					// $fName = explode(' ',$userName)[0];
					// $arrayType = array('1' => 'pro','2' => 'dis-cert','3' => 'horoscope','4' => 'aadhar','5' => 'election');
					// $tt = $arrayType[$data['type']];
					$fileName = time() . "." . $onlyName[1];
					$filepath = public_path() . "/doctorDocuments/";
					$request->file('file')->move($filepath, $fileName);
					$files = DoctorDocuments::create([
						'doc_id' => $data['doc_id'],
						'user_id' => @$data['user_id'],
						'type' => $data['type'],
						'file_name' => $fileName
					]);
					$files->file_name = url("/") . "/public/doctorDocuments/" . $files->file_name;
					$files->type = $files->type;
					$files['type_name'] = getDoctorDocumentType($files->type);
					return $files;
				}
			}
		}
	}

	public function deleteFile(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$id = $request->id;
			$file =  DoctorDocuments::select('file_name')->Where('id', '=', $id)->first();
			$filename = public_path() . '/doctorDocuments/' . @$file->file_name;
			if (file_exists($filename)) {
				File::delete($filename);
			}
			DoctorDocuments::Where('id', '=', $id)->delete();
			Session::flash('successMsg', "Status Successfully");
			return 1;
		}
	}
	public function vaccinationDrive(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.vaccinationDrive', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$t_status = base64_decode($request->input('t_status'));
			$query = VaccinationDrive::orderBy('id', 'desc');
			if (!empty($search)) {
				$query->where(DB::raw('concat(name," ",IFNULL(mobile_no,""))'), 'like', '%' . $search . '%');
			}
			if ($t_status != "") {
				$query->where('t_status', $t_status);
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$registrations = $query->paginate($page);
		}
		return view('admin.feedbacks.vaccination-drive', compact('registrations'));
	}
	public function modifyVaccDriveStatus(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			//change status
			if ($data['type'] == 'changeStatus') {
				if ($data['status'] == '1') {
					VaccinationDrive::where('id', $data['id'])->update(array('status' => '0'));
				} else {
					VaccinationDrive::where('id', $data['id'])->update(array('status' => '1'));
				}
			}
			//add note
			elseif ($data['type'] == 'addNote') {
				VaccinationDrive::where('id', base64_decode($data['id']))->update(array('note' => $data['note']));
			}

			return 1;
		}
	}
	public function runnersLeads(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('from_date'))) {
				$params['from_date'] = base64_encode($request->input('from_date'));
			}
			if (!empty($request->input('to_date'))) {
				$params['to_date'] = base64_encode($request->input('to_date'));
			}
			if (!empty($request->input('app_download'))) {
				$params['app_download'] = base64_encode($request->input('app_download'));
			}
			if (!empty($request->input('appointment'))) {
				$params['appointment'] = base64_encode($request->input('appointment'));
			}
			if (!empty($request->input('plan_sold'))) {
				$params['plan_sold'] = base64_encode($request->input('plan_sold'));
			}
			if (!empty($request->input('created_by'))) {
				$params['created_by'] = base64_encode($request->input('created_by'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.runnersLeads', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$app_download = base64_decode($request->input('app_download'));
			$appointment = base64_decode($request->input('appointment'));
			$plan_sold = base64_decode($request->input('plan_sold'));
			$created_by = base64_decode($request->input('created_by'));
			$from_date = base64_decode($request->input('from_date'));
			$to_date = base64_decode($request->input('to_date'));
			$query = RunnersLead::with('SalesTeam')->orderBy('created_at', 'desc');
			if ($request->input('from_date') != '') {
				$query->whereDate('created_at', '>=', $from_date);
			}
			if ($request->input('to_date') != '') {
				$query->whereDate('created_at', '<=', $to_date);
			}
			if (!empty($search)) {
				$query->where(DB::raw('concat(name," ",IFNULL(mobile_no,""))'), 'like', '%' . $search . '%');
			}
			if (!empty($request->input('app_download'))) {
				$query->where('app_download', $app_download);
			}
			if (!empty($request->input('appointment'))) {
				$query->where('appointment', $appointment);
			}
			if (!empty($request->input('plan_sold'))) {
				$query->where('plan_sold', $plan_sold);
			}
			if (!empty($request->input('created_by'))) {
				$query->whereHas('SalesTeam', function ($q)  use ($created_by) {
					$q->Where('id', $created_by);
				});
			}
			if (!empty($search)) {
				$query->where(DB::raw('concat(name," ",IFNULL(mobile_no,""))'), 'like', '%' . $search . '%');
			}

			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$registrations = $query->paginate($page);
		}
		return view('admin.feedbacks.runners-lead', compact('registrations'));
	}
	public function crtAppt(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$data = $request->all();
			$user = User::where('id', base64_decode($data['pId']))->first();
			$user_array['order_by']   = $user->id;
			$user_array['doc_id']   =  getSetting("direct_appt_doc_id")[0];
			$docData = Doctors::select(["user_id", "consultation_fees", "oncall_fee", "slot_duration", "first_name", "last_name"])->where(['id' => $user_array['doc_id']])->first();
			$user_array['doc_name']   = $docData->first_name . " " . $docData->last_name;
			$user_array['p_id']   = $user->id;
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
			$user_array['gender'] = $user->gender;
			$user_array['patient_name'] = $user->first_name . " " . $user->last_name;
			$user_array['dob'] = date('d-m-Y', $user->dob);
			$user_array['mobile_no'] = $user->mobile_no;
			$user_array['other_mobile_no'] = $user->other_mobile_no;
			$user_array['otherPatient'] = 0;
			$user_array['coupon_id'] = null;
			$user_array['coupon_discount'] = null;
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
				'order_from' => 2,
				'order_by' => $user_array['order_by'],
				'coupon_id' => $user_array['coupon_id'],
				'coupon_discount' => $user_array['coupon_discount'],
				'meta_data' => json_encode($user_array),
			]);
			$appId = $this->putAppointmentDataApp($order, '', '');
			if (isset($data['from']) && $data['from'] == 2) {
				MedicineOrders::where(["order_id" => $data['orderId']])->update(['appId' => $appId]);
			}
			return 1;
		}
	}
	public function createApptLnk(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			// dd($data);
			$user = User::where('id', base64_decode($data['pId']))->first();
			$user_array['order_by']   = $user->id;

			$docData = Doctors::select(["id", "user_id", "consultation_fees", "oncall_fee", "slot_duration", "first_name", "last_name", "convenience_fee"])->where(['user_id' => $data['doc_id']])->first();
			$user_array['doc_id'] =  $docData->id;
			$user_array['slot_duration'] = checkOpdTimeById($user_array['doc_id'], $data['appstart_date'], $data['time'], $docData->slot_duration);
			$increment_time = $user_array['slot_duration'] * 60;
			$date = date("Y-m-d", strtotime($data['appstart_date']));
			$time = date("H:i:s", $data['time']);
			$start_date = date("Y-m-d H:i:s", strtotime($date . " " . $time));
			$end_date = date('Y-m-d H:i:s', strtotime($date . " " . $time) + $increment_time);
			if (!empty($docData->convenience_fee)) {
				$charge = $docData->convenience_fee;
			} else {
				$charge = getSetting("service_charge_rupee")[0];
			}
			$tax =  getSetting("tax_in_percent")[0];
			$gst =  getSetting("gst")[0];
			$service_charge_meta = 	["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];
			$service_charge = $charge;
			if (base64_decode($data['app_type']) == "1") {
				$consultation_fees = $docData->oncall_fee;
				$onCallStatus = 1;
				$order_total = $consultation_fees + $service_charge;
			} else if (base64_decode($data['app_type']) == "2") {
				$consultation_fees = $docData->consultation_fees;
				$onCallStatus = 2;
				$order_total = $service_charge;
			}
			$isDirectAppt = 0;
			if ($docData->id == 49188) {
				$order_total = getSetting("direct_tele_appt_fee")[0];
				$isDirectAppt = 1;
			}
			if (isset($data['appttype']) && $data['appttype'] == '1') {
				$isDirectAppt = 0;
			}
			$order_subtotal = $consultation_fees;
			$finalConsultaionFee = $consultation_fees + $service_charge;
			$payment_mode = $data['payment_mode'];
			if (empty($data['payment_mode'])) {
				$payment_mode = 1;
			}
			if (!empty($data['conFee'])) {
				$service_charge = 0;
				$consultation_fees = $data['conFee'];
				$order_total = $data['conFee'];
				$order_subtotal = $data['conFee'];
			}
	
			$user_array['doc_name']   = $docData->first_name . " " . $docData->last_name;
			$user_array['p_id']   = $user->id;
			$user_array['visit_type'] = 1;
			$user_array['blood_group'] = NULL;
			$user_array['consultation_fees'] = $consultation_fees;
			$user_array['appointment_date'] = $start_date;
			$increment_time = $docData->slot_duration * 60;
			$user_array['time'] = $time;
			$user_array['slot_duration'] = $docData->slot_duration;
			$user_array['onCallStatus'] = $onCallStatus;
			$user_array['isFirstTeleAppointment'] = 1;
			$user_array['isDirectAppt'] = $isDirectAppt;
			$user_array['service_charge'] = $service_charge;
			$user_array['is_subscribed'] = 0;
			$user_array['gender'] = $user->gender;
			$user_array['patient_name'] = $user->first_name . " " . $user->last_name;
			$user_array['dob'] = date('d-m-Y', $user->dob);
			$user_array['mobile_no'] = $user->mobile_no;
			$user_array['other_mobile_no'] = $user->other_mobile_no;
			$user_array['otherPatient'] = 0;
			$user_array['coupon_id'] = null;
			$user_array['coupon_discount'] = null;
			$user_array['call_type'] = 1;
			$user_array['referral_code'] = null;
			$user_array['is_peak'] = 0;
			$user_array['finalConsultaionFee'] = $consultation_fees;
			$user_array['apptBy'] = base64_decode($data['apptBy']);
			$user_array['receivedBy'] = $data['receivedBy'];
			$user_array['order_total'] = $order_total;
			$user_array['payment_mode'] = $payment_mode;
			$user_array['tracking_id'] = @$data['tracking_id'];
			$service_charge_meta = 	["service_charge_rupee" => $charge, "tax_in_percent" => $tax, "gst" => $gst];
        
	
		

			$order = AppointmentOrder::create([
				'type'	 => $payment_mode,
				'service_charge_meta' =>  json_encode($service_charge_meta),
				'service_charge' =>  $service_charge,
				'order_subtotal' => str_replace(',', '', $order_subtotal),
				'order_total' =>  str_replace(',', '', $order_total),
				'order_status' =>  0,
				'app_date' => $start_date,
				'doc_id' =>  $docData->user_id,
				'order_from' => 2,
				'order_by' => $user_array['order_by'],
				'coupon_id' => $user_array['coupon_id'],
				'coupon_discount' => $user_array['coupon_discount'],
				'meta_data' => json_encode($user_array),
			]);

	

			if (isset($data['appttype']) && $data['appttype'] == '2') {
				Parent::putAppointmentDataApp($order, '', $order);
				return ['status' => 2];
			} else {
				$lnk = route('apptPayment', [base64_encode($order->id)]);
				ApptLink::create([
					'user_id' => $user_array['order_by'],
					'link' => $lnk,
					'order_id' => $order->id,
					'createBy' => $user_array['apptBy'],
					'meta_data' => json_encode($user_array),
				]);
				$name = $user_array['patient_name'];
				$tmpName = "payment_link_v2";
				$post_data = ['parameters' => [['name' => 'user', 'value' => $name], ['name' => 'link', 'value' => $lnk]], 'template_name' => $tmpName, 'broadcast_name' => 'Payment'];
				sendWhatAppMsg($post_data, $user->mobile_no);
				$payLnk = "healthgennie.com/pay/" . base64_encode($order->id);
				$message = urlencode("Dear " . $name . ", Your appointment request received successfully, Please use below link to pay fee for appointment confirmation. \n" . $payLnk . " \nThanks Team Health Gennie.");
				$this->sendSMS($user->mobile_no, $message, '1707163047105925473');
				return ['status' => 1, 'link' => $lnk];
			}
		}
	}
	// public function apptPayment(Request $request, $orderId)
	// {
	// 	if (!empty($orderId)) {
	// 		$appOrder = AppointmentOrder::select(["order_by", "order_total"])->where(["id" => base64_decode($orderId)])->where('order_status', '0')->first();
	// 		if (!empty($appOrder)) {
	// 			$parameters = [];
	// 			// $parameters["MID"] = "yNnDQV03999999736874";
	// 			// $parameters["MID"] = "fiBzPH32318843731373";
	// 			// $parameters["ORDER_ID"] = base64_decode($orderId);
	// 			// $parameters["CUST_ID"] = @$appOrder->order_by;
	// 			// $parameters["TXN_AMOUNT"] = $appOrder->order_total;
	// 			// $parameters["CALLBACK_URL"] = url('paytmresponse');
	// 			// $order = Indipay::gateway('Paytm')->prepare($parameters);
	// 			// return Indipay::process($order);
	// 			$mbl = @User::where("id", $appOrder->order_by)->first()->mobile_no;
	// 			\Log::info('$mbl', [$mbl]);
	// 			\Log::info('$appOrder', [$appOrder]);
	// 			$parameters["order"] = base64_decode($orderId);
	// 			$parameters["amount"] = $appOrder->order_total;
	// 			$parameters["user"] = @$appOrder->order_by;
	// 			$parameters["mobile_number"] = $mbl;
	// 			$parameters["email"] = 'test';
	// 			$parameters["callback_url"] = url('paytmresponse');
	// 			$payment = PaytmWallet::with('receive');
	// 			$payment->prepare($parameters);
	// 			return $payment->receive();
	// 		} else return abort('404');
	// 	} else return abort('404');
	// }



	public function apptPayment(Request $request, $orderId)
	{
		if (!empty($orderId)) {
			$appOrder = AppointmentOrder::select(["order_by", "order_total"])
				->where(["id" => base64_decode($orderId)])
				->where('order_status', '0')
				->first();
	
			// if (!empty($appOrder)) {
			// 	$apiKey = env('RAZORPAY_KEY_ID'); 
			// 	$apiSecret = env('RAZORPAY_KEY_SECRET'); 
			// 	$api = new \Razorpay\Api\Api($apiKey, $apiSecret);
	
			// 	$mbl = @User::where("id", $appOrder->order_by)->first()->mobile_no;
	
			// 	\Log::info('$mbl', [$mbl]);
			// 	\Log::info('$appOrder', [$appOrder]);
	
			// 	try {
			// 		// Create a Razorpay order
			// 		$razorpayOrder = $api->order->create([
			// 			'receipt'         => base64_decode($orderId),
			// 			'amount'          => $appOrder->order_total * 100,
			// 			'currency'        => 'INR',
			// 			'notes'           => [
			// 				'user_id' => $appOrder->order_by,
			// 			]
			// 		]);
	
			// 		// Generate and return the Razorpay checkout form
			// 		$checkoutForm = "
			// 		<!DOCTYPE html>
			// 		<html lang='en'>
			// 		<head>
			// 			<meta charset='UTF-8'>
			// 			<meta name='viewport' content='width=device-width, initial-scale=1.0'>
			// 			<title>Payment</title>
			// 			<script src='https://checkout.razorpay.com/v1/checkout.js'></script>
			// 		</head>
			// 		<body>
			// 			<script>
			// 				const options = {
			// 					key: '{$apiKey}',
			// 					amount: '{$razorpayOrder->amount}',
			// 					currency: '{$razorpayOrder->currency}',
			// 					name: 'Health Gennie',
			// 					description: 'Order Payment',
			// 					order_id: '{$razorpayOrder->id}',
			// 					handler: function (response) {
			// 						const paymentData = {
			// 							razorpay_payment_id: response.razorpay_payment_id,
			// 							razorpay_order_id: response.razorpay_order_id,
			// 							razorpay_signature: response.razorpay_signature,
			// 							amount: '{$razorpayOrder->amount}',
			// 							currency: '{$razorpayOrder->currency}',
			// 							orderId: '{$orderId}',
			// 						};
	
			// 						console.log('Payment successful:', paymentData);
	
			// 						// Redirect with payment data
			// 						window.location.href = '" . url('razorpayresponse') . "?' + new URLSearchParams(paymentData).toString();
			// 					},
			// 					prefill: {
			// 						name: 'User {$appOrder->order_by}',
			// 						email: 'user@example.com',
			// 						contact: '{$mbl}'
			// 					},
			// 					theme: {
			// 						color: '#3399cc'
			// 					}
			// 				};
			// 				const rzp = new Razorpay(options);
			// 				rzp.open();
			// 			</script>
			// 		</body>
			// 		</html>";
	
			// 		return response($checkoutForm);
	
			// 	} catch (\Exception $e) {
			// 		\Log::error('Razorpay error: ' . $e->getMessage());
			// 		return abort(500, 'Payment gateway error.');
			// 	}
			// } else {
			// 	return abort(404, 'Order not found');
			// }
		} else {
			return abort(404, 'Invalid Order ID');
		}
	}
	



	public function subsPayment(Request $request, $sub_id)
	{
		if (!empty($sub_id)) {
			$order = UsersSubscriptions::FindOrFail(base64_decode($sub_id));
			if (!empty($order)) {
				$parameters = [];
				// $parameters["MID"] = "yNnDQV03999999736874";
				// $parameters["MID"] = "fiBzPH32318843731373";
				// $parameters["ORDER_ID"] = base64_decode($orderId);
				// $parameters["CUST_ID"] = @$appOrder->order_by;
				// $parameters["TXN_AMOUNT"] = $appOrder->order_total;
				// $parameters["CALLBACK_URL"] = url('paytmresponse');
				// $order = Indipay::gateway('Paytm')->prepare($parameters);
				// return Indipay::process($order);

				// $mbl = @User::where("id",$order->user_id)->first()->mobile_no;
				// $parameters["order"] = $order->order_id;
				// $parameters["amount"] = $order->order_total;
				// $parameters["user"] = @$order->user_id;
				// $parameters["mobile_number"] = $mbl;
				// $parameters["email"] = 'test';
				// $parameters["callback_url"] = url('paytmresponse');
				// $parameters["enablePaymentMode"] = ["mode" => "UPI_QR_CODE"];
				// $payment = PaytmWallet::with('receive');
				// $payment->prepare($parameters);
				// return $payment->receive();

				$mid = "yNnDQV03999999736874";
				$merchent_key = "&!VbTpsYcd6nvvQS";
				$paytmParams["body"] = array(
					"mid"           => $mid,
					"orderId"       => $order->order_id,
					"amount"        => $order->order_total,
					"businessType"  => "UPI_QR_CODE",
					"posId"         => time()
				);
				$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchent_key);
				$paytmParams["head"] = array(
					"clientId"	=> 'C11',
					"version"	=> 'v1',
					"signature"	=> $checksum
				);
				$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
				/* for Production */
				$url = "https://securegw.paytm.in/paymentservices/qr/create";
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$response = curl_exec($ch);
				$response = json_decode($response, true);
				dd($response);
				// if($response['body']['resultInfo']['resultCode'] == "01" && $response['body']['resultInfo']['resultStatus'] == "TXN_SUCCESS") {
				// $resArr = ['ORDERID' => @$response['body']['orderId'],
				// 'TXNID'=> @$response['body']['txnId'],
				// 'BANKTXNID'=> @$response['body']['bankTxnId'],
				// 'PAYMENTMODE'=> @$response['body']['paymentMode'],
				// 'BANKNAME'=> @$response['body']['bankName'],
				// 'CURRENCY'=> 'INR',
				// 'TXNAMOUNT'=> @$response['body']['txnAmount'],
				// 'status' => 'success',
				// 'TXNDATE' => @$response['body']['txnDate']
				// ]; 
				// $isExist = AppointmentOrder::select("id")->where(["id"=>$order->id,'order_status'=>0])->count();
				// if($isExist > 0){
				// $this->putAppointmentDataApp($order,$resArr,'');
				// }
				// }
			} else return abort('404');
		} else return abort('404');
	}
	public function loadLinks(Request $request)
	{
		$data = $request->all();
		$userId = base64_decode($data['userId']);
		// $typ = 1;
		// if(isset($data['typ']) && $data['typ'] == '2'){
		// 	$typ = 2;
		// }
		$query = ApptLink::where(["user_id" => $userId]);
		if (isset($data['orderId'])) {
			$query->where(['order_id' => $data['orderId']]);
		}
		$links = $query->orderBy('id', 'desc')->get();
		// if(count($links)>0){
		// foreach($links as $raw){
		// $raw->status
		// }
		// }
		return ['links' => $links];
	}
	public function getAllChild(Request $request)
	{
		$data = $request->all();
		$pid = base64_decode($data['pid']);
		$users = User::where("parent_id", $pid)->get();
		return view('admin.Patients.child-users', compact('users'));
	}
	public function manageSupportSystem(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$pkey = base64_decode($data['pkey']);
			$r_from = $data['r_from'];
			$typ = $data['typ'];
			HandleQueries::create([
				'r_from' => $r_from,
				'table_id' => $pkey,
				'type' => $typ,
				'note' => $data['note'],
				'followUpDate' => ($data['followUpDate'] != "")  ? date("Y-m-d", strtotime($data['followUpDate'])) : null,
				'createBy' => Session::get('id'),
			]);
			return 1;
		}
	}
	public function showHandleQueries(Request $request)
	{
		$data = $request->all();
		$pkey = base64_decode($data['pkey']);
		$r_from = $data['r_from'];
		$queries = HandleQueries::where(['table_id' => $pkey, 'r_from' => $r_from])->get();
		return view('admin.feedbacks.view-handle-queries', compact('queries', 'pkey', 'r_from'));
	}
	public function userDataList(Request $request)
	{
		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('start_date'))) {
				$params['start_date'] = base64_encode($request->input('start_date'));
			}
			if (!empty($request->input('end_date'))) {
				$params['end_date'] = base64_encode($request->input('end_date'));
			}
			if (!empty($request->input('data_type'))) {
				$params['data_type'] = base64_encode($request->input('data_type'));
			}
			if (!empty($request->input('type'))) {
				$params['type'] = base64_encode($request->input('type'));
			}
			if (!empty($request->input('bpst'))) {
				$params['bpst'] = base64_encode($request->input('bpst'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}

			if (!empty($request->input('mobile'))) {
				$params['mobile'] = base64_encode($request->input('mobile'));
			}


			return redirect()->route('admin.userDataList', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$data_type = base64_decode($request->input('data_type'));
			$type = base64_decode($request->input('type'));
			$start_date = base64_decode($request->input('start_date'));
			$end_date = base64_decode($request->input('end_date'));
			$mobile = base64_decode($request->input('mobile'));

			$query = UsersOnlineData::orderBy('id', 'desc');
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}

			if (!empty($mobile)) {
				$query->where('mobile', '=', $mobile);
			}

			if (!empty($request->input('data_type'))) {
				$query->where('data_type', $data_type);
			}
			if (!empty($request->input('type'))) {
				$query->where('type', $type);
			}
			if (!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
				if (!empty($request->input('start_date'))) {
					$start_date = date('Y-m-d', strtotime(base64_decode($request->input('start_date'))));
					$query->whereRaw('date(created_at) >= ?', [$start_date]);
				}
				if (!empty($request->input('end_date'))) {
					$end_date = date('Y-m-d', strtotime(base64_decode($request->input('end_date'))));
					$query->whereRaw('date(created_at) <= ?', [$end_date]);
				}
			}
			if (!empty($request->input('bpst'))) {
				$bpst = base64_decode($request->input('bpst'));
				if ($bpst == '1') {
					$query->where('bp_s', '>', 120);
				} else if ($bpst == '2') {
					$query->where('bp_d', '<', 80);
				} else if ($bpst == '3') {
					$query->where('sugar', '>', 140);
				}
			}
			if (base64_decode($request->input('file_type')) == "excel") {
				$enqData = $query->orderBy("updated_at", "DESC")->get();
				$enqDataArray[] = array('Sr. No.', 'Data Type', 'Name', 'Mobile', 'Email', 'Title', 'Type', 'Company Name', 'Url', 'BP Systolic', 'BP Diastolic', 'Sugar', 'Dob', 'Gender', 'Organization', 'created At');
				foreach ($enqData as $i => $enq) {
					$enqDataArray[] = array(
						$i + 1,
						$enq->data_type,
						$enq->name,
						$enq->mobile,
						$enq->email,
						$enq->title,
						$enq->type,
						$enq->com_name,
						$enq->url,
						$enq->bp_s,
						$enq->bp_d,
						$enq->sugar,
						$enq->dob != null ? date("d-m-Y", $enq->dob) : "",
						$enq->gender,
						@$enq->OrganizationMaster->title,
						date('d-m-Y', strtotime($enq->created_at)),
					);
				}
				return Excel::download(new QueriesExport($enqDataArray), 'users.xlsx');
			}
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$enquirys = $query->paginate($page);
		}
		return view('admin.common.userData', compact('enquirys'));
	}
	function userDataExcelImport(Request $request)
	{
		$extensions = array("xls", "xlsx", "csv");
		$datass = $request->all();
		$result = array($request->file('select_file')->getClientOriginalExtension());
		if (in_array($result[0], $extensions)) {
			// $path = $request->file('select_file')->getRealPath();
			// $data = Excel::import($path)->get();
			Excel::import(new UsersOnlineDataImport, $request->file('select_file'));
			/*if($data->count() > 0){
		   foreach($data as $key => $value){
				// $mobile_no = preg_replace("/[^a-zA-Z0-9]/", "", $value['mobileno']);
				// if(strlen($mobile_no) == 12 && substr($mobile_no, 0, 2) == "91") {
					// $mobile_no   = substr($mobile_no, 2, 10);
				// }
				// $patient = UsersOnlineData::where('mobile_no',  'like', '%'.$mobile_no.'%')->count();
				if($value->filter()->isNotEmpty()){
				 $insert_data[] = array(
				  'name'  => $value['name'],
				  'title'   => $value['title'],
				  'type'   => $value['type'],
				  'mobile' => $value['mobile'],
				  'email'   => $value['email'],
				  'url'   => $value['url']
				);
			}
		}
		 if(!empty($insert_data)){
		 $arrayChunk = array_chunk($insert_data, 500, true);
		 foreach ($arrayChunk as  $chunk) {
		   UsersOnlineData::insert($chunk);
		 }
	     }
		 }*/
			Session::flash('message', "Excel Data Imported successfully.");
			return redirect('admin/user-data-list');
		} else {
			Session::flash('message', "The select file must be a xls, xlsx and csv");
			return redirect('admin/user-data-list')->with('error', 'The select file must be a xls, xlsx..');
		}
	}
	function userExcelImport(Request $request)
	{
		$extensions = array("xls", "xlsx", "csv");
		$datass = $request->all();
		$result = array($request->file('select_file')->getClientOriginalExtension());
		if (in_array($result[0], $extensions)) {
			Excel::import(new UsersImport, $request->file('select_file'));
			Session::flash('message', "Excel Data Imported successfully.");
			return redirect('admin/patients');
		} else {
			Session::flash('message', "The select file must be a xls, xlsx and csv");
			return redirect('admin/patients')->with('error', 'The select file must be a xls, xlsx..');
		}
	}
	public function regPrimaryUsers(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			if (!empty($data['id'])) {
				$ids = json_decode($data['id']);
				$allUsers = UsersOnlineData::whereIn('id', $ids)->get();
				if ($allUsers->count() > 0) {
					foreach ($allUsers as $raw) {
						$isExist = User::select('id')->where(['parent_id' => 0, 'mobile_no' => $raw->mobile])->first();
						if (empty($isExist)) {
							$first_name = trim(strtok($raw->name, ' '));
							$last_name = trim(strstr($raw->name, ' '));
							$user = new User();
							$user->first_name = $first_name;
							$user->last_name = $last_name;
							$user->mobile_no = $raw->mobile;
							$user->dob = $raw->dob;
							$user->gender = $raw->gender;
							$user->login_type = 2;
							$user->device_type = 3;
							$user->organization = $raw->organization_id;
							$user->created_at = $raw->created_at;
							$user->updated_at = $raw->updated_at;
							$user->save();
							$userId = $user->id;
						} else {
							$userId = $isExist->id;
						}
						$bpRecord = ManageBpRecords::where('user_id', $userId)->count();
						if ($bpRecord == 0) {
							ManageBpRecords::create([
								'user_id' => $userId,
								'bp_systolic' => $raw->bp_s,
								'bp_diastolic' => $raw->bp_d,
								'weight' => $raw->weight,
								'date' => date('Y-m-d', strtotime($raw->created_at)),
								'time' => date('h:i', strtotime($raw->created_at)),
							]);
						}
						$diabRecord = ManageWeightRecords::where('user_id', $userId)->count();
						if ($diabRecord == 0) {
							$weightData = ManageWeightRecords::create([
								'user_id' =>  $userId,
								'date' => date('Y-m-d', strtotime($raw->created_at)),
								'time' => date('h:i', strtotime($raw->created_at)),
								'weight' => $raw->weight,
							]);
						}
						$weightRecord = ManageDiabetesRecords::where('user_id', $userId)->count();
						if ($weightRecord == 0) {
							$diaData = ManageDiabetesRecords::create([
								'user_id' => $userId,
								'sugar_level' => $raw->sugar,
								'date' => date('Y-m-d', strtotime($raw->created_at)),
								'time' => date('h:i', strtotime($raw->created_at)),
							]);
						}
						createUsersReferralCode($userId);
					}
				}
			}
			return 1;
		}
	}
	public function newUserData(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$no_exists = UsersOnlineData::where(['mobile' => trim($data['mobile'])])->count();
			if ($no_exists > 0) {
				return 2;
			} else {
				UsersOnlineData::create([
					'data_type' => 'Users',
					'name' => $data['name'],
					'dob' => (isset($data['dob']) ? strtotime($data['dob']) : null),
					'gender' => $data['gender'],
					// 'title' => $data['title'],
					'type' => 'Park Camp',
					'email' => null,
					'mobile' => $data['mobile'],
					'bp_s' => $data['bp_s'],
					'bp_d' => $data['bp_d'],
					'sugar' => $data['sugar'],
					'other' => $data['other'],
				]);
				$message = urlencode("Dear " . $data['name'] . ", Thank you using Health Gennie for your health care need. You can download health gennie app from https://healthgennie.com/download. Get FREE Dr consultation and upto 40% on lab test with Free home sample collection. For more details call us at 8929920932. Thanks, Team Health Gennie.");
				$this->sendSMS($data['mobile'], $message, '1707165822189298965');
				Session::flash('message', "User Added Successfully");
				return 1;
			}
		}
		return view('admin.common.add-data');
	}

	public function makeLabOrder(Request $request)
	{

		try {
			$data = $request->all();

			$pId = base64_decode($data['id']);

			$user = User::where('id', $pId)->first();
			$user['age'] = null;
			if (!empty($user->dob)) {
				$user['age'] = get_patient_age_year($user->dob);
			}
			$addresses =  UsersLaborderAddresses::orderBy('label_type', 'ASC')->Where('user_id', $pId)->first();

			return view('admin.Patients.create-lab-order', compact('addresses', 'user'));
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function newLabOrderFromAdmin(Request $request)
	{

		try {
			$data = $request->all();

			$pId = base64_decode($data['id']);

			$user = User::where('id', $pId)->first();

			$addresses =  UsersLaborderAddresses::orderBy('label_type', 'ASC')->Where('user_id', $pId)->first();
			$labs = DefaultLabs::select('title', 'cost', 'discount', 'id')->where('delete_status', 1)->limit(5)->get();
			return view('admin.Patients.new-lab-order-admin', compact('addresses', 'user', 'labs'));
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}


	public function notificationClick(Request $request)
	{
		$notifications = userNotifications::with(['NotificationUserId'])->orderBy('id', 'DESC')->paginate(10);
		return view('admin.Patients.notificationclick', compact('notifications'));
	}

	public function viewUsers(Request $request)
	{

		$notification_id = $request->input('notification_id');

		$userIdsInNotifications = NotificationUserId::where('notification_id', $notification_id)->pluck('user_id')->all();

		$users = User::whereIn('id', $userIdsInNotifications)
			->select('first_name', 'last_name', 'mobile_no')
			->get();

		return view('admin.Patients.viewNotificationUsers', compact('users'));
	}

	// ----------map-----------

	public function HgMap(Request $request)
	{

		// dd("ww");
		return view("admin.map.hgmap");
	}

	public function scheduleNotification(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$params = array();

			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('from_date'))) {
				$params['from_date'] = base64_encode($request->input('from_date'));
			}
			if (!empty($request->input('to_date'))) {
				$params['to_date'] = base64_encode($request->input('to_date'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}

			return redirect()->route('admin.scheduleNotification', $params)->withInput();
		} else {
			$search = !empty($request->input('search')) ? base64_decode($request->input('search')) : null;
			$from_date = !empty($request->input('from_date')) ? base64_decode($request->input('from_date')) : null;
			$to_date = !empty($request->input('to_date')) ? base64_decode($request->input('to_date')) : null;
			$page = !empty($request->input('page_no')) ? base64_decode($request->input('page_no')) : 25;

			$query = NotificationSchedule::with(["admin"])->orderBy('id', 'desc');

			if (!empty($search)) {
				$query->where('mobile_no', 'like', '%' . $search . '%');
			}
			if (!empty($from_date) && !empty($to_date)) {
				$query->where(function ($query) use ($from_date, $to_date) {
					$query->whereDate('from_date', [$from_date, $to_date])
						->orWhereDate('to_date', [$from_date, $to_date])
						->orWhere(function ($query) use ($from_date, $to_date) {
							$query->whereDate('from_date', '<=', $from_date)
								->whereDate('to_date', '>=', $to_date);
						});
				});
			}

			$userNotifications = $query->paginate($page);
			$practices = Doctors::with("docSpeciality")->select('oncall_fee', 'consultation_fees', 'first_name', 'last_name', 'email', 'user_id', 'id')->where(["delete_status" => 1, "hg_doctor" => 1, "claim_status" => 1, "varify_status" => 1])->orderBy("id", "ASC")->get();
			$plans = UserPlan::where(["delete_status" => 1, 'status' => 1])->orderBy('id', 'desc')->whereIn('type', ['1', '2'])->get();
			$labpackages = LabPackage::where('company_id', 3)->where(['delete_status' => 1, 'status' => 1])->get();
			$symptomslist = Symptoms::where(['delete_status' => 1])->get();

			return view('admin.Patients.user-notification-send', compact('userNotifications', 'practices', 'plans', 'labpackages', 'symptomslist'));
		}
	}

	public function setSchedular(Request $request)
	{
		$data = $request->all();
		$base64Icon = null;
		$fromDate = Carbon::parse($data['from_date']);
		$toDate = Carbon::parse($data['to_date']);
		$start_date = Carbon::parse($data['start_date']);
		$end_date = Carbon::parse($data['end_date']);
		$sendUserNotificationCreate = NotificationSchedule::create([
			'title' => $data['subject'],
			'content' => $data["msg"],
			'n_type' => $data["nType"],
			'from_date' => $fromDate,
			'to_date' => $toDate,
			'user_id' => Session::get('userdata')->id,
			'status' => 1,
			'start_date' => $start_date,
			'end_date' => $end_date,
			'notification_type' => $data['notification_type'],
			'schedule_type' => $data['schedule_type']
		]);
		if ($data['notification_route'] == 0 && $data['nType'] == 2) {
			$sendUserNotificationCreate->application_page =  $data["urlInput"];
		} else {
			$sendUserNotificationCreate->application_page =  $data["notification_route"];
		}
		if ($request->hasFile('notification_icon')) {
			$path = $request->file('notification_icon')->getRealPath();
			$base64Icon = file_get_contents($path);

			$image  = $request->file('notification_icon');
			$fullName = str_replace(" ", "", $image->getClientOriginalName());
			$onlyName = explode('.', $fullName);
			if (is_array($onlyName)) {
				$fileName = $onlyName[0] . time() . "." . $onlyName[1];
			} else {
				$fileName = $onlyName . time();
			}
			$filepath = public_path() . "/notification-icons/";
			$request->file('notification_icon')->move($filepath, $fileName);
			$sendUserNotificationCreate->image = $fileName;
			// $this->compress($fileName, $filepath);
		}
		if (!empty($data['user_id'])) {
			$sendUserNotificationCreate->type_id = $data['user_id'];
		} elseif (!empty($data['plan_id'])) {
			$sendUserNotificationCreate->type_id = $data['plan_id'];
		} elseif (!empty($data['lab_id'])) {
			$sendUserNotificationCreate->type_id = $data['lab_id'];
		} elseif (!empty($data['symptom_id'])) {
			$sendUserNotificationCreate->type_id = $data['symptom_id'];
		}
		$sendUserNotificationCreate->save();
		$metaData = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		$user = UserNotifications::create([
			'type' => 1,
			'title' => $data['subject'],
			'message' => $data["msg"],
			//            'route' => $data["notification_route"],
			'icon' => $base64Icon,
			'meta_data' => $metaData,
			'created_by' => Session::get('userdata')->id,
		]);
		if ($data['notification_route'] == 0) {
			$user->route =  $data["urlInput"];
		} else {
			$user->route =  $data["notification_route"];
		}
		$user->save();
		return 1;
	}
	public function notificationStatusUpdate(Request $request)
	{
		
		if ($request->isMethod('post')) {

			$status = $request->status;
			// dd($status);
			$id = $request->id;
			if ($status == 1) {
				NotificationSchedule::Where('id', '=', $id)->update(['status' => 0]);
			} else {
				NotificationSchedule::Where('id', '=', $id)->update(['status' => 1]);
			}
		}
		return 1;
	}

	// public function bulkExportCsv(Request $request)
	// {
	// 	$query = User::query();
	// 	$resource_namespace = 'App\Http\Resources\UserResource';
	// 	$columns = ['First Name', 'Last Name', 'Contact Number'];
	// 	$bulkExportCSV = \BulkExportCSV::build($query, $resource_namespace, $columns);

	// 	return response()->json([
	// 		'message' => 'CSV export initiated successfully!',
	// 		'export_status' => 'InProgress',
	// 		'details' => 'You will be notified once the export is complete.'
	// 	]);
	// }

	public function jobApplication(Request $request){
         
		$page = 25;
		$ApplicationsData = jobApplications::where('status' , 1)->where('delete_status' , 0)->orderBy('id', 'desc')->paginate($page);

		return view('admin.application_list.jobApplication' ,compact('ApplicationsData'));
	   }


	   public function healthProcessPw(Request $request)
	   {
		   if ($request->isMethod('post')) {
			   $params = [];
   
			   if ($request->filled('type')) {
				   $params['type'] = base64_encode($request->input('type'));
			   }
			   if ($request->filled('page_no')) {
				   $params['page_no'] = base64_encode($request->input('page_no'));
			   }
			   return redirect()->route('admin.healthProcessPw', $params)->withInput();
		   }
	   
		   $type = $request->filled('type') ? base64_decode($request->type) : null;
		   $perPage = $request->filled('page_no') ? (int) base64_decode($request->page_no) : 10;
	   
		   $query = HealthProcessPw::with('OrganizationMaster')->orderBy('created_at', 'desc');
	   
		   if (!empty($type)) {
			   $query->where('type', $type);
		   }
	   
		   $data = $query->paginate($perPage);
	   
		   return view('admin.feedbacks.health-process-pw', compact('data', 'type', 'perPage'));
	   }

	   public function VHTlist(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
			$params = [];
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('admin.VHTlist', $params)->withInput();
		} else {
			$filters = [];
			$search = base64_decode($request->input('search', ''));
			$query = VhtOrder::with(['user'])->where('delete_status', 0)->orderBy('id', 'desc');
			
	
			// Search functionality
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}
	
			$page = $request->input('page_no') ? base64_decode($request->input('page_no')) : 25;
			$leads = $query->paginate($page);
	
	
			// Return to the view
			return view('admin.feedbacks.vht-order-list', compact('leads'));
		}
	}

	public function generalFeedbackList(Request $request){
		
		if($request->isMethod('post')) {
		$params = array();
		if (!empty($request->input('page_no'))) {
			$params['page_no'] = base64_encode($request->input('page_no'));
		}
		return redirect()->route('admin.generalFeedbackList',$params)->withInput();

	}else{

		$page = 25;
		if(!empty($request->input('page_no'))) {
			$page = base64_decode($request->input('page_no'));
		}

		$data = GeneralFeedback::orderBy('created_at' , 'desc')->paginate($page);

	}
	
		return view('admin.feedbacks.generalFeedbackList' ,compact('data'));

	}

	public function viewSymptomsQuestionPW(Request $request){

		$data = $request->all();
		$viewData = HealthProcessPw::where('id' , $data['id'])->orderBy('created_at' ,'desc')->first();
	
		return view('admin.feedbacks.view-hppw' , compact('viewData'));
	}

}
