<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Session;
use App\Models\ehr\Appointments;
use Illuminate\Support\Facades\Validator;
use App\Models\ehr\RoleUser;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\Plans;
use App\Models\ehr\PracticesSubscriptions;
use App\Models\ehr\ManageTrailPeriods;
use App\Models\ehr\SubscribedPlans;
use App\Models\Contact;
use App\Models\Pages;
use App\Models\City;
use App\Models\State;
use App\Models\SubcribedEmail;
use App\Models\Doctors;
use App\Models\ehr\User as ehrUser;
use App\Models\ehr\DoctorsInfo;
use App\Models\ehr\clinicalNotePermissions;
use App\Models\ehr\OpdTimings;
use App\Models\AppLinkSend;
use App\Models\DonatePlasma;
use App\Models\OxygenSuppliers;
use App\Models\VaccinationDrive;
use App\Models\RunnersLead;
use App\Models\ReferralCashback;
use App\Models\UsersSubscriptions;
use App\Models\Corporate;
use App\Models\ehr\JobCategory;
use App\Models\ehr\Jobs;
use App\Models\UserWallet;
use App\Models\ehr\JobApplications;
use App\Models\PatientFeedback;
use Illuminate\Support\Facades\Hash;

use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Softon\Indipay\Facades\Indipay;
use App\Models\AuMarathonReg;
use App\Models\SalesTeam;
use App\Models\Coupons;
use App\Models\MedicalStoreDetails;
use App\Models\SheetData;
use App\Models\SheetTemplate;
use Illuminate\Support\Facades\Input;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Storage;
use Exception;
use Illuminate\Support\Facades\Session;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
	/** 
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	// public function __construct()
	// {
	//     $this->middleware('auth');
	// }

	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function index()
	{

		try {

			return view($this->getView('home'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function appointmentConfirm(Request $request)
	{


		try {
			$appointment = '';
			if (!empty($request->id)) {
				$appointment_id = base64_decode($request->id);
				$appointment = Appointments::with(['User.DoctorInfo', 'Patient'])->where('id', $appointment_id)->first();
			}
			if ($request->isMethod('post')) {
				if (!empty($appointment)) {
					Appointments::where('id', $appointment_id)->update(array(
						'appointment_confirmation' => 1,
						'status' => 1,
					));
					$practice =  RoleUser::select(['user_id', 'role_id', 'practice_id'])->where(['user_id' => $appointment->doc_id])->first();
					$practiceData =  PracticeDetails::where(['user_id' => $practice->practice_id])->first();
					$to = $appointment->Patient->email;
					$pat_name = ucfirst($appointment->Patient->first_name) . " " . ucfirst($appointment->Patient->last_name);

					if (Parent::is_connected() == 1) {
						if (!empty($appointment->Patient->email)) {
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
							$app_link = "https://www.healthgennie.com/download";
							$appointDate = date('d-m-Y', strtotime($appointment->start));
							$appointtime = date('h:i A', strtotime($appointment->start));
							$message = urlencode("Dear " . ucfirst($appointment->Patient->first_name) . " " . $appointment->Patient->last_name . " Your appointment with Dr. " . $appointment->User->DoctorInfo->first_name . " " . $appointment->User->DoctorInfo->last_name . " on  " . $appointDate . " at " . $appointtime . " has been confirmed by Dr. " . $appointment->User->DoctorInfo->first_name . " " . $appointment->User->DoctorInfo->last_name . ". Please visit the clinic 15 mins before your appointment time at clinic address. For Better Experience Download Health Gennie App" . $app_link . " Thanks Team Health Gennie");
							$this->sendSMS($appointment->Patient->mobile_no, $message, '1707161735128760937');
						}
					}
					return 1;
				}
			}
			return view($this->getView('doctors.appointment-confirmation'), ['appointment' => $appointment]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function appointmentCancel(Request $request)
	{

		try {
			if (!empty($request->id)) {
				$appointment_id = base64_decode($request->id);
				$appointmentData = Appointments::with(['User.DoctorInfo', 'Patient'])->where('id', $appointment_id)->first();
				if (!empty($appointmentData)) {
					Appointments::where('id', $appointment_id)->update(array(
						'status' => 0,
						'appointment_confirmation' => 1,
						'cancel_reason' => "cancelbydoctor",
					));
					$practice =  RoleUser::select(['user_id', 'role_id', 'practice_id'])->where(['user_id' => $appointmentData->doc_id])->first();
					$practiceData =  PracticeDetails::where(['user_id' => $practice->practice_id])->first();
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
						if (!empty($appointmentData->Patient->mobile_no)) {
							$app_link = "www.healthgennie.com/download";
							$message = urlencode("Dear " . $pName . ", Due to the unavailability of Dr., Your request for appointment with Dr. " . $doctorname . " has not been approved. Please select an alternate time or book appointment with another Doctor \nFor Better Experience Download Health Gennie App\n" . $app_link . "\nThanks Team Health Gennie.");
							$this->sendSMS($appointmentData->Patient->mobile_no, $message, '1707161588025544243');
						}
						$docName = "Dr. " . ucfirst($appointmentData->User->DoctorInfo->first_name) . " " . $appointmentData->User->DoctorInfo->last_name;
						$patientname = $appointmentData->Patient->first_name . ' ' . $appointmentData->Patient->last_name;
						$appointDate = date('d-m-Y', strtotime($appointmentData->start));
						$appointtime = date('h:i A', strtotime($appointmentData->start));
						if (!empty($appointmentData->User->email)) {
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
						}
						if (!empty($appointmentData->User->DoctorInfo->mobile)) {
							$message = urlencode("Dear " . $docName . ", Appointment of " . $patientname . ", with you on " . $appointDate . " at " . $appointtime . " has been cancelled Thanks Team Health Gennie");
							$this->sendSMS($appointmentData->User->DoctorInfo->mobile, $message, '1707161587827747448');
						}
					}
					return 1;
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function aboutUs()
	{

		try {

			return view($this->getView('pages.about'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function contactUs(Request $request)
	{

		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
		
				$validator = Validator::make($data, [
					'interest_in' => 'required|max:255',
					'name' => 'required|max:50',
					'email' => 'required|email|max:255',
					'mobile' => 'required|numeric',
					'subject' => 'required|max:50',
					'message' => 'required|max:255'
				]);
				if ($validator->fails()) {
					$errors = $validator->errors();
					return redirect('contact-us')->withErrors($validator)->withInput();
				} else {
					$contact =  Contact::create([
						'interest_in' => $data['interest_in'],
						'name' => $data['name'],
						'email' => $data['email'],
						'mobile' => $data['mobile'],
						'subject' => $data['subject'],
						'message' => $data['message'],
						'status' => 1
					]);
					$from = $contact->email;
					$EmailTemplate = EmailTemplate::where('slug', 'webcontactus')->first();

					if (Parent::is_connected() == 1) {
						if ($EmailTemplate) {
							$body = $EmailTemplate->description;
							array($body);
							$username = ucfirst($contact->name);
							$message = $data['message'];
							$mailMessage = str_replace(array('{{username}}', '{{email}}', '{{contact_no}}', '{{comment}}'), array($username, $from, $contact->mobile, $message), $body);
							$datas = array('to' => 'info@healthgennie.com', 'from' => $from, 'mailTitle' => $EmailTemplate->title, 'content' => $mailMessage, 'subject' => $EmailTemplate->subject);
							try {

								Mail::send('emails.all', $datas, function ($message) use ($datas) {
									$message->to($datas['to'])->from($datas['from'], 'HealthGennie')->subject($datas['subject']);
								});
							} catch (\Exception $e) {

								Log::info('email template', [$e]);
								// Never reached
							}
						}
					}
					Session::flash('message', "Thanks For Your Contact! We will contact soon");
				}
				return redirect()->route('contactUs');
			}
			return view($this->getView('pages.contact'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function subcribedEmail(Request $request)
	{

		try {
			$data = $request->all();
			$validator = Validator::make($data, [
				'email' => 'required|email|max:255'
			]);
			if ($validator->fails()) {
				$errors = $validator->errors();
				return $errors->messages()['email'];
			}
			SubcribedEmail::create(['email' => $request->email]);
			$EmailTemplate = EmailTemplate::where('slug', 'subscribed_email')->first();
			\Log::info('$$datas', [$EmailTemplate]);
			if ($EmailTemplate) {
				$body = $EmailTemplate->description;
				$datas = array('to' => $request->email, 'from' => 'noreply@healthgennie.com', 'mailTitle' => $EmailTemplate->title, 'content' => $body, 'subject' => $EmailTemplate->subject);
				try {
				\Log::info('$$datas', [$datas]);
					Mail::send('emails.subscribedEmail', $datas, function ($message) use ($datas) {
						$message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
					});
				} catch (\Exception $e) {
					\Log::info('$esubscribed', [$e]);
				}
			}
			Session::flash('message', "Thanks For Your Subcription.");
			return 1;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function viewMailTemplate(Request $request)
	{

		try {
			return view($this->getView('emails.subscribedEmail'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function carrerUs()
	{

		try {

			$data = JobCategory::where('status', 1)->where('delete_status', 0)->get();
			// dd($data);

			// return view($this->getView('pages.career' ,['data'=>$data] ));
			return view('pages.career', compact('data'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	// public function carrerDetail($id = null)
	// {      $decodedId = base64_decode($id);
	// 	if ($decodedId) {
	// 	  $jobData = Jobs::where('cat_id', $decodedId)->where('status', 1)->where('delete_status', 0)->get();
	// 	  $data = JobCategory::where('id' , $decodedId)->where('status' , 1 )->where('delete_status' , 0)->first();
	// 	} 
	// 	return view('pages.carrerDetail', compact('jobData' , 'data'));
	// }

	public function carrerDetail(Request $request, $id = null)
	{
		$decodedId = $id ? base64_decode($id) : null;
		$cityId = $request->input('city');

		$data = $request->all();

		$query = Jobs::query();

		if ($cityId) {
			$query->where('city_id', $cityId);
		}
		$jobData = $query->where('status', 1)->where('delete_status', 0)->get();


		$data = JobCategory::where('id', $decodedId)->where('status', 1)->where('delete_status', 0)->first();


		return view('pages.carrerDetail', compact('jobData', 'data'));
	}


	public function getCities($stateId)
	{
		$cities = City::where('state_id', $stateId)->get();
		return response()->json($cities);
	}

	public function getState($countryId)
	{
		$states = State::where('country_id', $countryId)->get(['id', 'name']);
		return response()->json($states);
	}


	public function jobApply($id = null)
	{

		$decodedId = base64_decode($id);
		if ($decodedId) {
			$jobData = Jobs::where('id', $decodedId)->where('status', 1)->where('delete_status', 0)->first();
			$data = JobCategory::where('id', $decodedId)->where('status', 1)->where('delete_status', 0)->first();
		}	
		return view('pages.jobApply', compact('jobData', 'data'));
	}	


	public function applyForJob(Request $request)
{
    $data = $request->all();
    try {
        if ($request->hasFile('resume')) {
            $resume = $request->file('resume');
            $originalName = str_replace(" ", "", $resume->getClientOriginalName());
            $fileName = time() . '_' . $originalName;
            $resume->move(public_path("resumes"), $fileName);
        }


        $jobData = Jobs::where('id', $data['job_id'])
            ->where('status', 1)
            ->where('delete_status', 0)
            ->first();

        if (!$jobData) {
            return redirect()->back()->withErrors('The job you are applying for is no longer available.');
        }

        if (!empty($data['first_name']) && !empty($data['last_name'])) {
            $jobApplication = new JobApplications();

			$jobApplication->first_name = $request->input('first_name');
            $jobApplication->last_name = $request->input('last_name');
            $jobApplication->email = $request->input('email');
            $jobApplication->city = $request->input('city');
            $jobApplication->experience = $request->input('experience');
            $jobApplication->state = $request->input('state');
            $jobApplication->country_id = $request->input('country');
            $jobApplication->qualification = $request->input('qualification');
            $jobApplication->address = $request->input('address');
            $jobApplication->phone = $request->input('phone');
            $jobApplication->file_data = $fileName ?? null;
            $jobApplication->position = $jobData->title;
            $jobApplication->save();
        }

		Log::info("Applying for job: ", $data);

		Log::info("Job application saved successfully", ['id' => $jobApplication->id]);
	
		Log::info($jobApplication);

        return redirect()->back()->with('success', 'Your application has been submitted successfully!');
    } catch (\Exception $e) {
        return redirect()->back()->withErrors('There was an error submitting your application: ' . $e->getMessage());
    }
}

	public function helpMe()
	{
		try {
			return view($this->getView('pages.help'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function privacyPolicy()
	{

		try {

			$page = Pages::where(["slug" => "privacy_policy"])->first();
			return view($this->getView('pages.privacy-policy'), ['page' => $page]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function cancelationPolicy(){
		try {
			$page = Pages::where(["slug" => "cancelation-policy"])->first();
			return view($this->getView('pages.cancelation-policy'), ['page' => $page]);
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
	public function termsConditions()
	{

		try {
			$page = Pages::where(["slug" => "terms_conditions"])->first();
			return view($this->getView('pages.terms-conditions'), ['page' => $page]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function claimTermsConditions()
	{

		try {
			$page = Pages::where(["slug" => "claim_terms_conditions"])->first();
			return view($this->getView('pages.terms-conditions'), ['page' => $page]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function oneMgOpen(Request $request)
	{

		try {

			if (Auth::user() == null) {
				Session::put('loginFrom', '2');
				return redirect()->route('login');
			}
			$user = Auth::user();
			if ($user) {
				header('Location: http://bit.ly/2vMrOls');
				exit;
			} else {
				return view($this->getView('home'));
			}
			/* $ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.1mg.com/webservices/merchants/generate-merchant-hash");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, true);
		$user = Auth::user();
		if($user){
			$user_id = $user->id;
			$email = $user->email;
			$name = $user->first_name." ".$user->last_name;
			$data = array(
				'api_key' => 'cadc8dff-c1a8-4bf9-9a1f-cbb1d8ea8ed9',
				'user_id' => $user_id,
				'email' => $email,
				'name' => $name,
				'redirect_url' => 'https://www.1mg.com/',
				'source' => 'health_gennie'
			);

			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			$output = curl_exec($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			$hash = json_decode($output)->hash;
			header('Location: https://www.1mg.com?_source=health_gennie&merchant_token='.$hash);
			exit;
		}*/
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function hgOffers(Request $request)
	{

		try {
			/*$id = Session::get('offer_doc_id');
		$doctor = "";
		if(!empty($id)){
			$doctor = Doctors::with(["docSpeciality","DoctorRatingReviews"])->Where(['id'=>$id])->first();
		}
		return view($this->getView('subscription.health-gennie-offers'),['doctor'=>$doctor]);*/
			// $refers = ReferralCashback::select('referral_id','referred_id',DB::raw('count(referred_id) as total_ref'))->where(['status'=>1,'paytm_status'=>'DE_001'])->groupBy('referral_id')->orderBy('total_ref','DESC')->get();
			// $t_countRef = 0;
			// if(count($refers)){
			// foreach($refers as $raw){
			// $t_countRef += $raw->total_ref;
			// }
			// }
			if ($request->isMethod('post')) {
				$params = array();
				if (!empty($request->input('fname'))) {
					$params['fname'] = base64_encode($request->input('fname'));
				}
				return redirect()->route('hgOffers', $params)->withInput();
			} else {
				$query = UsersSubscriptions::with(['User', 'PlanPeriods', 'UserReferral' => function ($q) {
					$q->select('referral_id', 'referred_id', DB::raw('count(referred_id) as total_ref'))->where(['status' => 1, 'paytm_status' => 'DE_001'])->groupBy('referral_id');
				}]);
				if (!empty($request->input('fname'))) {
					$fname = base64_decode($request->input('fname'));
					$query->whereHas('User', function ($q) use ($fname) {
						$q->where(DB::raw('concat(IFNULL(first_name,"")," ",IFNULL(last_name,"")," ",IFNULL(mobile_no,""))'), 'like', '%' . $fname . '%');
					});
				}
				$subs =  $query->whereHas('PlanPeriods', function ($q) {
					$q->Where('status', 1);
				})->where(["order_status" => 1])->where('user_id', '!=', '1')->groupBy('user_id')->get()->toArray();
				if (count($subs) > 0) {
					$subsData = [];
					foreach ($subs as $key => $raw) {
						if (!empty($raw['user_referral'])) {
							$raw['total_ref'] = $raw['user_referral']['total_ref'];
						} else {
							$raw['total_ref'] = 0;
						}
						$subsData[] = $raw;
					}
					$tref = array();
					foreach ($subsData as $key => $raw) {
						if (!empty($raw['total_ref'])) {
							$tref[$key] = $raw['total_ref'];
						} else {
							$tref[$key] = 0;
						}
					}
					array_multisort($tref, SORT_DESC, $subsData);
					$subs = $subsData;
				}
				// $perPage = 10;
				// $input = Input::all();
				// if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }

				// $offset = ($currentPage * $perPage) - $perPage;
				// $itemsForCurrentPage = array_slice($subs, $offset, $perPage, false);
				// $subs =  new Paginator($itemsForCurrentPage, count($subs), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				return view($this->getView('pages.offers'), ['subs' => $subs]);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function hgOffersPlans(Request $request)
	{



		try {

			if (!empty($request->doc_id)) {

				$doc_id = base64_decode($request->doc_id);
				$doctor = Doctors::where('delete_status', '=', '1')->Where('id', '=', $doc_id)->first();
				return view($this->getView('subscription.health-gennie-plans'), ['doc_id' => $doc_id, 'doctor' => $doctor]);
			} else {
				// dd(222);
				return view($this->getView('home'));
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function checkOut(Request $request)
	{


		try {

			if ($request->isMethod('post')) {
				$data = $request->all();
				$doc_id = $data['doc_id'];
			} else {
				if (!empty($request->id)) {
					$doc_id = base64_decode($request->doc_id);
					$product_id = base64_decode($request->id);

					$doc_data = Doctors::Where(['id' => $doc_id])->first();
					if (empty($doc_data->user_id)) {
						$pass  = trim(substr($doc_data->first_name, 0, 3)) . substr($doc_data->mobile_no, -4) . rand(000, 999);

						$urls = json_encode(getEhrFullUrls());
						if (!empty($doc_data->clinic_image)) {
							$this->uploadClinicImgFileBy($doc_data->clinic_image);
						}
						if (!empty($doc_data->profile_pic)) {
							$this->uploadDoctorFileBy($doc_data->profile_pic);
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
						$userDetails->consultation_discount  = $doc_data->consultation_discount;
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

						clinicalNotePermissions::insert(['user_id' => $user->id, 'practice_id' => $user->id, 'modules_access' => "1,2,3,4,6,7,8,9,11,12,13,14,15,16", 'created_at' => date("Y-m-d h:i:s"), 'updated_at' => date("Y-m-d h:i:s")]);

						OpdTimings::create(['user_id' => $user->id, 'practice_id' => $user->id, 'schedule' => $doc_data->opd_timings]);

						Doctors::where('id', $doc_id)->update(array(
							//'hg_doctor' => 1,
							//'varify_status' => 1,
							'password' => bcrypt($pass),
							'created_at' => date('Y-m-d h:i:s'),
							'user_id' => $user->id,
							'member_id' => $user->member_id,
							'practice_id' => $practice_id,
							'claim_status' => 1,
						));

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
							'user_id' => $user->id,
							'user_plan_id' => $plans->id,
							'start_trail' => $create_date,
							'end_trail' => $end_date,
							'remaining_sms' => $plans->promotional_sms_limit
						]);
					}
					$plan = Plans::where('id', $product_id)->first();
					return view($this->getView('subscription.checkout_plan'), ['plan' => $plan, 'doc_id' => $doc_id]);
				} else {
					Session::flash('message', "You didn't select any plan");
					return redirect()->route('hgOffersPlans');
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public  function varifyAfterSubcription(Request $request)
	{

		try {

			$id = $request->id;
			if (!empty($id)) {
				$doc_data = Doctors::where(["id" => $id])->first();
				$pass = $doc_data->first_name . "" . $doc_data->reg_no;
				Doctors::where('id', $id)->update(array(
					'hg_doctor' => 1,
					'varify_status' => 1,
					'password' => bcrypt($pass),
					'created_at' => date('Y-m-d h:i:s'),
				));
				ehrUser::where('id', $doc_data->user_id)->update(array(
					'varify_status' => 1,
					'password' => bcrypt($pass),
				));
				if (!empty($doc_data->mobile_no)) {
					$username = ucfirst($doc_data->first_name) . " " . $doc_data->last_name;
					$to = $doc_data->email;
					$message = urlencode("Congratulation! Dear Dr. " . $username . ", Your profile verified successfully with HealthGennie.Your Subcription start from now.Please use this credential for login Email : " . $to . " Password : " . $pass . " Thanks,If any query please call at +91-8929920932 Thanks Team Health Gennie");
					$this->sendSMS($doc_data->mobile_no, $message, '1707161735186568608');
				}
				return 1;
			}
			return 2;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function getUniqueId($str)
	{

		try {
			$num = 1;
			// $users =  DB::select('SELECT MAX(CAST((SUBSTRING(member_id,4)) as UNSIGNED)) as total FROM healthgennieEhr.users');
			$users =  ehrUser::select(DB::raw('MAX(CAST((SUBSTRING(member_id,4)) as UNSIGNED)) as total'))->pluck('total');
			// return $users[0];
			if (!empty($users)) {
				$num = $users[0] + $num;
			}
			return $str . $num;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function uploadDoctorFileBy($fileName, $old_image = null)
	{
		// @file_get_contents(getEhrUrl()."/doctorFileWriteByUrl?fileName=".$fileName."&old_profile_pic=".$old_image);
	}

	public function uploadClinicImgFileBy($fileName, $old_image = null)
	{
		// @file_get_contents(getEhrUrl()."/clinicFileWriteByUrl?fileName=".$fileName."&old_profile_pic=".$old_image);
	}

	public function SendAppLink(Request $request)
	{


		try {

			if ($request->isMethod('post')) {
				$data = $request->all();
				$validator = Validator::make($data, [
					'mobile_no' => 'required|min:10'
				]);
				if ($validator->fails()) {
					$errors = $validator->errors();
					return $errors->messages()['mobile_no'];
				}
				$mobile_no = trim(str_replace(" ", "", $data['mobile_no']));
				AppLinkSend::create(["mobile_no" => $mobile_no]);
				$app_link = "www.healthgennie.com/download";
				$message = urlencode("Click on below link to download Health Gennie App " . $app_link . " Thanks Team Health Gennie");
				$this->sendSMS($mobile_no, $message, '1707161588035056619');
				return 1;
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function healthgenniePatientApp(Request $request)
	{

		try {
			$iPod    = stripos($_SERVER['HTTP_USER_AGENT'], "iPod");
			$iPhone  = stripos($_SERVER['HTTP_USER_AGENT'], "iPhone");
			$iPad    = stripos($_SERVER['HTTP_USER_AGENT'], "iPad");
			$Android = stripos($_SERVER['HTTP_USER_AGENT'], "Android");
			$webOS   = stripos($_SERVER['HTTP_USER_AGENT'], "webOS");

			//do something with this information
			if ($iPod || $iPhone || $iPad) {
				sleep(1);
				header('Location: https://apps.apple.com/in/app/health-gennie-care-at-home/id1492557472');
				exit;
			} else {
				sleep(1);
				header('Location: https://play.google.com/store/apps/details?id=io.Hgpp.app&hl=en');
				exit;
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function qrCodeIndex(Request $request, $action, $slug)
	{

		try {
			if ($action == "clinic") {
				$doc_id = getClinicIdBySlug($slug);
			} else {
				$doc_id = getDoctorIdBySlug($slug);
			}
			if (!empty($doc_id) && count($doc_id) > 0) {
				$user = Doctors::Where(['id' => $doc_id])->first();
				if (!empty($user)) {
					if ($action == "clinic") {
						$user['url'] = url("/") . "/" . $user->getCityName->slug . "/clinic/" . $user->DoctorSlug->clinic_name_slug;
					} else {
						$user['url'] = url("/") . "/" . $user->getCityName->slug . "/doctor/" . $user->DoctorSlug->name_slug;
					}
					if ($request->file_type == "pdf") {
						$pdf = PDF::loadView('admin.qrCode.printqrCode', compact('user'))->setOption('page-width', '1152')->setOption('page-height', '1728');
						return $pdf->download('doctor-qr-code.pdf');
					}
					return view($this->getView('admin.qrCode.qrCodeIndex'), ['user' => $user]);
				} else {
					return abort(404);
				}
			} else {
				return abort(404);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function printqrCode(Request $request)
	{
		try {
			$id = $request->input('id');
			$action = $request->input('action');
			$logo = "https://www.healthgennie.com/img/health-gennie-logo23.png";
			$user = Doctors::Where(['id' => $id])->first();
			if ($action == "clinic") {
				$user['url'] = url("/") . "/" . $user->getCityName->slug . "/clinic/" . $user->DoctorSlug->clinic_name_slug;
			} else {
				$user['url'] = url("/") . "/" . $user->getCityName->slug . "/doctor/" . $user->DoctorSlug->name_slug;
			}
			if ($request->input('qRtype') == '1') {
				return view($this->getView('admin.qrCode.printqrCodeEnglish'), ['user' => $user, 'action' => $action, 'logo' => $logo]);
			} else {
				return view($this->getView('admin.qrCode.printqrCodeHindi'), ['user' => $user, 'action' => $action, 'logo' => $logo]);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function downloadQrCode(Request $request)
	{

		try {
			$id = $request->input('id');
			$user = Doctors::Where(['id' => $id])->first();
			$user['url'] = url("/") . "/" . $user->getCityName->slug . "/doctor/" . $user->DoctorSlug->name_slug;
			$pdf = PDF::loadView('admin.qrCode.printqrCode', compact('user'));
			return $pdf->download('doctor-qr-code.pdf');
			/*$docPath = public_path().'/doctor/'.$user->id.'/qrcode/';
		if(!is_dir($docPath)){
			 File::makeDirectory($docPath, $mode = 0777, true, true);
		}
		if(!file_exists($docPath.'qrCodePrint.pdf')) {
			File::copy(public_path().'/htmltopdfview.pdf', $docPath.'qrCodePrint.pdf');
			File::makeDirectory($docPath.'qrCodePrint.pdf', $mode = 0777, true, true);
		}
		$output = PDF::loadHTML($html)->output();
		file_put_contents(public_path()."/doctor/".$user->id."/qrcode/qrCodePrint.pdf", $output);
		return 1;*/
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function qrCodeApp(Request $request)
	{
		try {
			$url = "https://www.healthgennie.com/download";
			return view($this->getView('admin.qrCode.qrCodeApp'), ['url' => $url]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function AuMarathonReg(Request $request)
	{


		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$already = AuMarathonReg::Where('mobile_no', 'LIKE', "%{$data['mobile_no']}%")->first();

				$validatedData = Validator::make($data, [
					'name' => 'required|max:50',
					'email' => 'required|max:50',
					'mobile_no' => 'required|min:10',
					'dob' => 'required',
					'gender' => 'required',
					't_shirt_size' => 'required'
				]);
				if ($validatedData->fails()) {
					$errors = $validatedData->errors();
					return array('status' => 0, 'error' => $errors);
				} else {
					if (empty($already)) {
						$registration =  AuMarathonReg::create([
							'name' => $data['name'],
							'email' => $data['email'],
							'mobile_no' => $data['mobile_no'],
							'dob' => date("Y-m-d", strtotime($data['dob'])),
							'gender' => $data['gender'],
							't_shirt_size' => $data['t_shirt_size']
						]);
						if (!empty($data['mobile_no'])) {
							$mobile_no = trim($data['mobile_no']);
							$message = urlencode("Thank you for registering for AU bank Jaipur Marathon Dream run category. Please collect your kit from Diggi Palace , Jaipur on 31st Jan 2020 from 10 am to 7 PM. Download healthgennie app from healthgennie.com/download to show at the time of kit collection.\nTeam Health Gennie.");
							$this->sendSMS($mobile_no, $message);
						}
						saveUserActivity($request, 'AuMarathonReg', 'au_marathon_reg', @$registration->id);
						return 1;
					} else {
						return 0;
					}
				}
			}
			// return view('pages.au-marathon-registration');
			return view($this->getView('pages.au-marathon-registration'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function generateCode(Request $request)
	{
		try {
			return view($this->getView('pages.generate-code'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function generateCouponCode(Request $request)
	{

		try {

			if ($request->isMethod('post')) {
				$data = $request->all();
				$validator = Validator::make($data, [
					'name' => 'required|max:50',
					'owner_name' => 'required|max:50',
					'address' => 'required|max:255',
					'mobile' => 'required|numeric',
					'interest_in' => 'required|max:50',
				]);
				if ($validator->fails()) {
					$errors = $validator->errors();
					return redirect('generateCode')->withErrors($validator)->withInput();
				} else {
					$fileName = null;
					if (isset($data['document']) && $request->hasFile('document')) {
						$images = $request->file('document');
						$fileName = str_replace(" ", "", $images->getClientOriginalName());
						$filepath = 'public/medicalStoreFiles/';
						// if(!is_dir($filepath)){
						// File::makeDirectory($filepath, $mode = 0777, true, true);
						// }
						Storage::disk('s3')->makeDirectory($filepath);
						// $request->file('document')->move($filepath, $fileName);
						Storage::disk('s3')->put($filepath . $fileName, file_get_contents($images), 'public');
					}
					$user = SalesTeam::Where('id', $data['interest_in'])->first();
					// $name = explode(" ",$user->name);
					$cop = Coupons::select("id")->orderBy("id", "DESC")->first();
					$cop_id = 1;
					if (!empty($cop)) {
						$cop_id = $cop->id + 1;
					}
					// $rand = rand(100,999);
					$coupon_code = "GENNIE" . '' . $cop_id;
					$coupon = Coupons::create([
						'type' => 2,
						'coupon_title' => 'Extra Discount',
						'coupon_discount' => 5,
						'coupon_code' => strtoupper($coupon_code),
						'coupon_duration_type' => 'y',
						'coupon_duration' => 1,
						'coupon_last_date' => date('Y-m-d', strtotime('+1 year', strtotime(date('Y-m-d')))),
						'generated_by' => $data['interest_in'],
					]);

					MedicalStoreDetails::create([
						'name' => $data['name'],
						'address' => $data['address'],
						'owner_name' => $data['owner_name'],
						'mobile' => $data['mobile'],
						'document' => $fileName,
						'acc_no' => $data['acc_no'],
						'acc_name' => $data['acc_name'],
						'ifsc_no' => $data['ifsc_no'],
						'bank_name' => $data['bank_name'],
						'paytm_no' => $data['paytm_no'],
						'country_id' => $data['country_id'],
						'state_id' => $data['state_id'],
						'city_id' => $data['city_id'],
						'coupon_id' => $coupon->id,
						'generated_by' => $data['interest_in'],
					]);
					$message = urlencode("Dear " . $user->name . ", Please use this coupon code to get extra 5% discount.{#var#}\n" . $coupon_code);
					$this->sendSMS($user->mobile, $message, '1707161735181382094');
					Session::flash('message', "Coupon code generated successfully. " . $coupon->coupon_code);
					return redirect()->route('generateCode');
					// return array('status'=>1, 'coupon' => $coupon_code);
				}
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}




	public function homeService(Request $request)
	{
		try {
			return view($this->getView('pages.home-service'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function meal(Request $request)
	{
		try {
			return view($this->getView('pages.meal'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function plasama(Request $request)
	{
		try {
			if ($request->isMethod('post')) {
				$params = array();
				if (!empty($request->input('state'))) {
					$params['state'] = base64_encode($request->input('state'));
				}
				if (!empty($request->input('city'))) {
					$params['city'] = base64_encode($request->input('city'));
				}
				return redirect()->route('plasama', $params)->withInput();
			} else {
				$query = DonatePlasma::where('status', 1);
				if (!empty($request->input('state'))) {
					$state = base64_decode($request->input('state'));
					$query->where('state', $state);
				}
				if (!empty($request->input('city'))) {
					$city = base64_decode($request->input('city'));
					$query->where('city', $city);
				}
				$plasama = $query->get();
				// $subs = UsersSubscriptions::with(['User'])->
				// whereHas('UserReferral', function($q) {
				// $q->select('referral_id','referred_id',DB::raw('count(referred_id) as total_ref'))->where(['status'=>1,'paytm_status'=>'DE_001'])->groupBy('referral_id');
				// })
				// where(["order_status"=>1])->groupBy('user_id')->get();
				// dd($subs);
				return view($this->getView('pages.plasama'), ['plasama' => $plasama]);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function donatePlasma(Request $request)
	{

		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$validator = Validator::make($data, [
					'blood_group' => 'required',
					'name' => 'required',
					'mobile' => 'required|min:10',
					'state' => 'required',
					'city' => 'required',
					// 'message' => 'required',
				]);
				if ($validator->fails()) {
					$errors = $validator->errors();
					return redirect('donate-plasma')->withErrors($validator)->withInput();
				} else {
					$registration =  DonatePlasma::create([
						'blood_group' => $data['blood_group'],
						'name' => $data['name'],
						'mobile' => $data['mobile'],
						'state' => $data['state'],
						'city' => $data['city'],
						// 'message' => $data['message'],
					]);
					Session::flash('message', "Thanks For Your Interest! We will contact soon");
				}
			}
			return view($this->getView('pages.donate-plasma'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function getCityListByName(Request $request)
	{

		try {
			$id = $request->input('id');
			$cities = OxygenSuppliers::select("city")->where('state', $id)->groupBy("city")->get();
			return $cities;
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function oxygenAvailablity(Request $request)
	{

		try {
			if ($request->isMethod('post')) {
				$params = array();
				if (!empty($request->input('state'))) {
					$params['state'] = base64_encode($request->input('state'));
				}
				if (!empty($request->input('city'))) {
					$params['city'] = base64_encode($request->input('city'));
				}
				return redirect()->route('oxygenAvailablity', $params)->withInput();
			} else {
				$query = OxygenSuppliers::where('status', 1);
				$cityData = OxygenSuppliers::select("city");
				if (!empty($request->input('state'))) {
					$state = base64_decode($request->input('state'));
					$query->where('state', $state);
					$cityData->where('state', $state);
				}
				if (!empty($request->input('city'))) {
					$city = base64_decode($request->input('city'));
					$query->where('city', $city);
				}
				$oxygen = $query->get();
				$states = OxygenSuppliers::select("state")->groupBy("state")->get();
				$cities = $cityData->groupBy("city")->get();
				return view($this->getView('pages.oxygen-availablity'), ['oxygen' => $oxygen, 'states' => $states, 'cities' => $cities]);
			}
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function vaccinationDrive(Request $request)
	{

		try {
			return abort(404);
			if ($request->isMethod('post')) {
				$data = $request->all();


				$validatedData = Validator::make($data, [
					'name' => 'required|max:50',
					'mobile_no' => 'required|min:10',
					'dose_type' => 'required',
					'persons' => 'required',
					'address' => 'required||max:255'
				]);
				if ($validatedData->fails()) {
					$errors = $validatedData->errors();
					return array('status' => 0, 'error' => $errors);
				} else {
					$registration =  VaccinationDrive::create([
						'name' => $data['name'],
						'mobile_no' => $data['mobile_no'],
						'dose_type' => $data['dose_type'],
						'persons' => $data['persons'],
						'preferred_date' => date("Y-m-d", strtotime($data['preferred_date'])),
						'address' => $data['address']
					]);

					if (!empty($data['mobile_no'])) {
						$mobile_no = trim($data['mobile_no']);
						$message = urlencode("Your request for Vaccination has been registered successfully. One of our team members will call you for date and time. For any questions please call 9414430699. Thanks. Team Health Gennie.");
						$this->sendSMS($mobile_no, $message, '1707162702747842759');
					}
					saveUserActivity($request, 'VaccinationDrive', 'vaccination_drive', @$registration->id);
					return 1;
				}
			}
			// return view('pages.au-marathon-registration');
			return view($this->getView('pages.vaccination-drive'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function runnersLead(Request $request)
	{

		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$validatedData = Validator::make($data, [
					'name' => 'required|max:50',
					'mobile_no' => 'required|min:10',
					'address' => 'required',
					'app_download' => 'required',
					'appointment' => 'required',
					'plan_sold' => 'required',
					'created_by' => 'required'
				]);
				if ($validatedData->fails()) {
					$errors = $validatedData->errors();
					return array('status' => 0, 'error' => $errors);
				} else {
					$registration =  RunnersLead::create([
						'name' => $data['name'],
						'mobile_no' => $data['mobile_no'],
						'address' => $data['address'],
						'app_download' => $data['app_download'],
						'appointment' => $data['appointment'],
						'plan_sold' => $data['plan_sold'],
						'created_by' => $data['created_by']
					]);
					saveUserActivity($request, 'RunnersLead', 'runner', @$registration->id);
					$count = RunnersLead::where('created_by', $data['created_by'])->count();
					return $count;
				}
			}
			$sales = SalesTeam::all();
			// return view('pages.au-marathon-registration');
			return view($this->getView('pages.runners-lead'), ['sales' => $sales]);
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function partners(Request $request)
	{

		try {
			return view($this->getView('pages.partners'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}
	public function corporate(Request $request)
	{

		try {
			if ($request->isMethod('post')) {
				$data = $request->all();
				$validator = Validator::make($data, [
					'name' => 'required',
					'mobile' => 'required|min:10',
					'org_name' => 'required',
					'org_size' => 'required',
				]);
				if ($validator->fails()) {
					$errors = $validator->errors();
					return redirect('corporate')->withErrors($validator)->withInput();
				} else {
					$qry_from = 2;
					if (!empty($data['qry_from'])) {
						if (strpos($data['qry_from'], '?home') !== false) {
							$qry_from = 1;
						}
					}
					Corporate::create([
						'name' => $data['name'],
						'mobile' => $data['mobile'],
						'email' => $data['email'],
						'org_name' => $data['org_name'],
						'org_size' => $data['org_size'],
						'qry_from' => $qry_from,
					]);
					Session::flash('message', "Thanks For Your Interest! We will contact soon");
				}
			}
			return view($this->getView('pages.corporate'));
		} catch (Exception $e) {

			return $e->getMessage();
		}
	}

	public function patientFeedback(Request $request)
	{

		$RandId = $request->id;
		return view('pages.patient_feedback_for_doctor', compact('RandId'));
	}

	public function patientFeedbacksave(Request $request)
	{

		$data = $request->all();

		$this->validate($request, [
			'recommendation' => 'required',
			'waiting_time' => 'required',
			'visit_type' => 'required',
			'suggestions' => 'required',
			'rating' => 'required',
			'experience' => 'required',
			'randomid' => 'required'
		]);

		PatientFeedback::where('random_no', $request->randomid)->update(array(
			'recommendation' => $request->recommendation,
			'waiting_time' => $request->waiting_time,
			'visit_type' => $request->visit_type,
			'suggestions' => json_encode($request->suggestions, JSON_FORCE_OBJECT),
			'rating' => $request->rating,
			'experience' => $request->experience,
			'publish_status' => $request->publish_status,
			'delete_status' => 1,
			'resource' => 1
		));

		Session::flash('message', "Thanks For Your Feedback");
		return redirect()->back();
	}
	/**Mental Health Sheet*/
	public function worksheets(Request $request)
	{
		$sheet_id = $request->input('sheet_id');
		$user_id = $request->input('user_id');
		$lng = $request->input('lng');

		if ($request->isMethod('post')) {
			// Collect all input fields except the specified ones
			$data = $request->except(['_token', 'sheet_id', 'user_id', 'lng']);
			$canvasData = [];
			// Handle canvas data dynamically
			// Loop through the request and capture canvas data
			foreach ($request->all() as $key => $value) {
				if (strpos($key, 'canvas') === 0) {
					$canvasData[$key] = $value;
				}
			}
			// Now, check if canvas data exists and handle it
			if (!empty($canvasData)) {
				// Handle canvas data by encoding it to JSON
				$sheet_value = json_encode($canvasData, JSON_UNESCAPED_SLASHES);
			} else {
				// Handle non-canvas data (for other form fields)
				$data = $request->except(['_token', 'sheet_id', 'user_id', 'lng']);
				$sheet_value = json_encode($data, JSON_UNESCAPED_SLASHES);
			}
			// Update or create the record with the new data
			SheetData::updateOrCreate(
				['sheet_temp_id' => $sheet_id, 'user__id' => $user_id], // Adjusted to use correct fields
				['meta_data' => $sheet_value]
			);

			// Redirect back with a success message
			return redirect()->back()->with('success', 'Worksheet saved successfully!');
		}
		// Retrieve existing sheet data from the database
		$existingSheet = SheetData::where('sheet_temp_id', $sheet_id)
			->where('user__id', $user_id)
			->first();
		if ($existingSheet) {
			$existingData = json_decode($existingSheet->meta_data, true) ?? [];
			$sheetData = array_merge($this->getDynamicFields($existingData), $existingData);  // Merge dynamic fields with existing data
		} else {
			$sheetData = $this->getDynamicFields();  // Use only dynamic fields if no existing data
		}

		$sheetTemplate = SheetTemplate::where('sheet_id', $sheet_id)->first();
		$htmlTemplate = $sheetTemplate ? $sheetTemplate->html_module : '';

		$htmlTemplate = $this->replacePlaceholdersWithSheetData($htmlTemplate, $sheetData);

		return view('worksheet', compact('sheet_id', 'user_id', 'lng', 'sheetData', 'htmlTemplate'));
	}

	private function getDynamicFields($existingData = [])
	{
		$dynamicFields = [];
		$fields = !empty($existingData) ? array_keys($existingData) : [];

		foreach ($fields as $field) {
			if (strpos($field, 'canvas') === 0) {
				$dynamicFields[$field] = $existingData[$field] ?? '';
			} else {
				$dynamicFields[$field] = '';
			}
		}
		if (isset($existingData['canvas_data'])) {
			$dynamicFields['canvas_data'] = $existingData['canvas_data'];
		}
		return $dynamicFields;
	}
	private function replacePlaceholdersWithSheetData($htmlTemplate, $sheetData)
	{
		// Load HTML content as a DOM document to manipulate input fields directly
		$dom = new \DOMDocument();
		libxml_use_internal_errors(true); // Ignore errors due to malformed HTML
		$dom->loadHTML($htmlTemplate);
		libxml_clear_errors();

		// Handle <input> elements
		$inputElements = $dom->getElementsByTagName('input');
		foreach ($inputElements as $input) {
			// Ensure the node is an element
			if ($input instanceof \DOMElement) {
				$name = $input->getAttribute('name');
				$type = $input->getAttribute('type');
				$value = $input->getAttribute('value');

				if (isset($sheetData[$name])) {
					$dataValue = $sheetData[$name];

					if ($type === 'radio') {
						// Handle radio buttons
						if ($value === $dataValue) {
							$input->setAttribute('checked', 'checked');
						} else {
							$input->removeAttribute('checked');
						}
					} elseif ($type === 'checkbox') {
						// Handle checkboxes
						if ($dataValue === true || $dataValue === 'on' || $dataValue === 'yes') {
							$input->setAttribute('checked', 'checked');
						} else {
							$input->removeAttribute('checked');
						}
					} else {
						// Handle other input types
						$input->setAttribute('value', htmlspecialchars($dataValue, ENT_QUOTES, 'UTF-8'));
					}
				}
			}
		}
		// Handle <textarea> elements
		$textareaElements = $dom->getElementsByTagName('textarea');
		foreach ($textareaElements as $textarea) {
			// Ensure the node is an element
			if ($textarea instanceof \DOMElement) {
				$name = $textarea->getAttribute('name');

				if (isset($sheetData[$name])) {
					$value = htmlspecialchars($sheetData[$name], ENT_QUOTES, 'UTF-8');
					// Set the value of the textarea
					$textarea->nodeValue = $value;
				}
			}
		}
		$buttonElements = $dom->getElementsByTagName('button');
		foreach ($buttonElements as $button) {
			// Ensure the node is an element
			if ($button instanceof \DOMElement) {
				$name = $button->getAttribute('name');
				$class = $button->getAttribute('class');

				if (isset($sheetData[$name])) {
					$value = $sheetData[$name];
					$isTrueButton = strpos($class, 'true-btn') !== false;
					$isFalseButton = strpos($class, 'false-btn') !== false;

					if ($value === 'TRUE') {
						// TRUE button should be active
						if ($isTrueButton) {
							$button->setAttribute('class', $class . ' active');
						} else {
							// Ensure FALSE button does not have 'active' class
							if ($isFalseButton) {
								$button->setAttribute('class', str_replace(' active', '', $class));
							}
						}
					} else if ($value === 'FALSE') {
						// FALSE button should be active
						if ($isFalseButton) {
							$button->setAttribute('class', $class . ' active');
						} else {
							// Ensure TRUE button does not have 'active' class
							if ($isTrueButton) {
								$button->setAttribute('class', str_replace(' active', '', $class));
							}
						}
					}
				}
			}
		}
		foreach ($sheetData as $key => $value) {
			if (strpos($key, 'canvas') === 0 && !empty($value)) {
				$canvasElement = $dom->getElementById($key);
				if ($canvasElement) {
					// Add the base64 data as a custom attribute
					$canvasElement->setAttribute('data-image', $value);
				}
			}
		}
		// Save the modified HTML and return it
		$htmlTemplate = $dom->saveHTML();
		return $htmlTemplate;
	}
	public function WKDone(Request $request)
	{
		return view('pages.worksheet-success');
	}
	public function clearWorksheet(Request $request)
	{
		$sheet_id = $request->input('sheet_id');
		$user_id = $request->input('user_id');
		$existingSheet = SheetData::where('sheet_temp_id', $sheet_id)
			->where('user__id', $user_id)
			->first();
		if ($existingSheet) {
			// $existingSheet->delete();
			$existingSheet->meta_data = [];
			$existingSheet->save();
			// Log::info("Worksheet data deleted successfully.");
		} else {
			// Log::info("No existing worksheet data found.");
		}
		return response()->json(['success' => true, 'message' => 'Worksheet data cleared successfully!']);
	}
	public function modifyHtml()
	{
		// Path to the HTML file
		$inputFilePath = public_path('new_sheet-10sep.html');  // Adjust the path as needed
		$outputFilePath = public_path('new_sheet-10sep_modified.html');

		// Load the HTML content from the file
		$htmlContent = file_get_contents($inputFilePath);

		if (!$htmlContent) {
			return response()->json(['error' => 'Unable to read HTML content'], 500);
		}

		// Load HTML content as a DOM document
		$dom = new \DOMDocument();
		libxml_use_internal_errors(true); // Ignore errors due to malformed HTML
		$dom->loadHTML($htmlContent);
		libxml_clear_errors();

		// Get all input elements and add name attributes
		$inputElements = $dom->getElementsByTagName('input');
		foreach ($inputElements as $index => $input) {
			if ($input instanceof \DOMElement) {
				// Generate a unique name for each input element
				$input->setAttribute('name', 'input_' . ($index + 1));
			}
		}

		// Get all textarea elements and add name attributes
		$textareaElements = $dom->getElementsByTagName('textarea');
		foreach ($textareaElements as $index => $textarea) {
			if ($textarea instanceof \DOMElement) {
				// Generate a unique name for each textarea element
				$textarea->setAttribute('name', 'textarea_' . ($index + 1));
			}
		}

		// Save the modified HTML and return it
		$modifiedHtmlContent = $dom->saveHTML();

		// Save the modified HTML back to a new file
		file_put_contents($outputFilePath, $modifiedHtmlContent);

		return response()->json(['success' => 'Name attributes added successfully!', 'file' => $outputFilePath]);
	}

	public function Support(Request $request)
	{

		return view('pages.support');
	}


	// In your Controller (e.g., ModalController.php)
	public function setModalSession(Request $request)
	{
		// Set a session for 10 seconds
		session(['inquiryModalClosed' => now()->addSeconds(10)]);

		return response()->json(['status' => 'success', 'message' => 'Session set for 10 seconds']);
	}

	public function checkModalSession()
	{
		// Check if the session exists and has expired
		if (session('inquiryModalClosed') && now()->lessThanOrEqualTo(session('inquiryModalClosed'))) {
			return response()->json(['status' => 'show', 'message' => 'Modal session active']);
		} else {
			return response()->json(['status' => 'hide', 'message' => 'Modal session expired']);
		}
	}

	public function expertHg()
	{
		return view('pages.expert');
	}


	public function walletList(Request $request)
	{

		$user = \Auth::user();
		$walletHistory = UserWallet::where(['user_id' => $user->id])->orderBy("created_at", "DESC")->get();
		return view($this->getView('appointments.wallet-history'), ['walletHistory' => $walletHistory]);
	}

	public function closeSession(Request $request)
	{
		Session::put('popUpSession', '1');
		return response()->json(['message' => 'Session value set to 1']);
	}

	/**
	 * Applies PKCS5 padding to the input data.
	 */
	function pkcs5Padding($data, $blockSize)
	{
		$pad = $blockSize - (strlen($data) % $blockSize);

		// dd(trim($data . str_repeat(chr($pad), $pad)));
		return trim($data . str_repeat(chr($pad), $pad));
	}
	/**
	 * AES encryption in ECB mode without IV, and encodes the output in Base64.
	 */
	function aesEncryptBase64ECB($key, $data)
	{
		// Ensure the key is exactly 16 bytes (AES-128)
		if (strlen($key) !== 16) {
			throw new Exception("Key must be 16 bytes for AES-128.");
		}
		// Apply PKCS5 padding
		$paddedData = $this->pkcs5Padding($data, 16);
		// Encrypt using AES-128-ECB
		$encryptedData = openssl_encrypt($paddedData, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
		if ($encryptedData === false) {
			throw new Exception("Encryption failed.");
		}
		// Encode to Base64
		return base64_encode($encryptedData);
	}

	/**
	 * Computes the SSO signature using AES encryption and MD5 hashing.
	 */
	function computeSSOSignature($key, $data)
	{
		if (empty($key)) {
			throw new Exception("Invalid key.");
		}
		if (empty($data)) {
			throw new Exception("Invalid payload.");
		}
		// Encrypt and then hash
		$cipherText = $this->aesEncryptBase64ECB($key, $data);
		return md5($cipherText);
	}

	public function videoLanding(Request $request)
	{
		try {
			$microtime = microtime(true); // Get current time in seconds with microseconds
			$milliseconds = round($microtime * 1000); // Convert to milliseconds
			// live key IK2D86BTMRQI9JUE
			// $key = 'IK2D86BTMRQI9JUE'; // 16-byte encryption key
			//testing key
			$key = 'X99BVHNW9PLF3KME'; // 16-byte encryption key

			// Login-Free Parameter Definition
			
			$extOrderId = "health-gennie-vht-" . str_pad(1 + 1, 8, '0', STR_PAD_LEFT); 
			// $extOrderId = "health-gennie-ext-01012025";
			$orgId = "YdE7c5f6";
			$thirdKey = "health-gennie-01012025";
			$timestamp = $milliseconds;

			// Concatenate parameters
			$data = $extOrderId . $orgId . $thirdKey . $milliseconds;
			$signature = $this->computeSSOSignature($key, $data);

			// Construct URL
			// $url = 'https://health.voicehealthtech.com/api/auth/fast-login';
			$url = 'https://health.voicehealthtech.com/ou/api/auth/fast-login';
			$url .= "?org-id={$orgId}&third-key={$thirdKey}&ext-order-id={$extOrderId}&timestamp={$timestamp}&signature={$signature}";
			dd($url);



			// Check if the response is not empty
			// if (!$response) {
			// 	throw new Exception("No response from the API.");
			// }

			// // Return the response as an HTML Blade view
			// return view('video-landing')->with('htmlContent', $response);
		} catch (Exception $e) {
			return response()->json([
				'success' => false,
				'error' => $e->getMessage(),
			], 500);
		}
	}
	public function students(Request $request) {
		return view('pages.students');
	}
	public function enquiryFromOrganization(Request $request)
	{
		$data = $request->all();
		Corporate::create([
		'name' => $data['name'], 
		'mobile' => $data['phone'],
		'email'=>$data['email'],
		'org_name'=> $data['organisation'],
		'org_size'=> $data['employees'],
		'status' => 1,
		'qry_from' => 1,
		]);
		return ['status' => 1];
	}
}
