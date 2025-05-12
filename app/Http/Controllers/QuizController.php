<?php

namespace App\Http\Controllers;

use App\Exports\AssessmentAnswerExport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\QuizForm;
use App\Models\QuizquestionDemo;
use App\Models\SessionAssesment;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\OrganizationMaster;
use App\Models\SessionAssigendData;
use App\Models\Exports\QuizFormExport;
use App\Models\AssementStuFeedback;
use App\Models\AssesmentAnswer;
use App\Models\Doctors;
use App\Models\ehr\AppointmentOrder;
use App\Models\User;
use App\Models\MhQuesRange;
use App\Models\UserWallet;
use App\Models\UserDetails;
use App\Models\PlanPeriods;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Illuminate\Support\Facades\Request as Input;
use App\Models\ehr\Appointments;
use App\Models\ehr\LabOrders;
use App\Models\MhCommonSheet;
use App\Models\MhJournal;
use App\Models\MhMood;
use App\Models\MhSheetData;
use App\Models\MhTracker;
use App\Models\MhWpFeedback;
use App\Models\PreAssesmentAnswer;
use App\Models\SheetData;
use App\Models\Student;
use App\Models\UsersSubscriptions;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class QuizController extends Controller
{

	public function QuizRegistration(Request $request, $slug)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$validator = Validator::make($data, [
				'name' => 'required|max:50',
				'mobile' => 'required',
				'gender' => 'required',
				// 'age' => 'required'
			]);
			if ($validator->fails()) {
				$errors = $validator->errors();
				return redirect('quiz-registration')->withErrors($validator)->withInput();
			} else {
				$isExists = QuizForm::where('mobile', $data['mobile'])->count();
				if ($isExists == 0) {
					$age = !empty($data['dob']) ? get_mentalH_user_age($data['dob']) : $data['dob'];
					$form =  QuizForm::create([
						'org_id' => isset($data['oid']) ? base64_decode($data['oid']) : null,
						'name' => $data['name'],
						'age' => $age,
						'dob' => $data['dob'],
						'gender' => $data['gender'],
						'mobile' => $data['mobile'],
						// 'class' => $data['class'],
						// 'subject' => $data['subject'],
						'institute_id' => $data['institute_id'],
						'location' => $data['location'],
					]);
					return redirect()->route('quiz', ['id' => base64_encode($form->id)]);
				} else {
					return '<h3 class="mental-res" style="font-size:30px; font-family:arial; position:absolute; left:0px; right:0px; width:100%; text-align:center; font-weight:600; top:0px; bottom:0px; height:150px; margin:auto; color:#0b316d;">Your form already submitted!<h3>';
				}
			}
			return redirect()->route('QuizRegistration');
		}
		if (!empty($slug)) {
			$oid = getOrganizationIdBySlug($slug);
			return view('pages.quiz-registration', compact('slug', 'oid'));
		} else {
			return abort(404);
		}
	}


	public function QuizScreening(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$validator = Validator::make($data, [
				'name' => 'required|max:50',
				'mobile' => 'required',
				'gender' => 'required',
				// 'age' => 'required'
			]);
			if ($validator->fails()) {
				$errors = $validator->errors();
				return redirect('quiz-registration')->withErrors($validator)->withInput();
			} else {
				$isExists = QuizForm::where('mobile', $data['mobile'])->count();
				if ($isExists == 0) {

					$form =  QuizForm::create([
						'name' => $data['name'],
						'age' => $data['age'],
						'gender' => $data['gender'],
						'mobile' => $data['mobile'],

					]);
					return redirect()->route('quiz', ['id' => base64_encode($form->id)]);
				} else {
					return '<h3 class="mental-res" style="font-size:30px; font-family:arial; position:absolute; left:0px; right:0px; width:100%; text-align:center; font-weight:600; top:0px; bottom:0px; height:150px; margin:auto; color:#0b316d;">Your form already submitted!<h3>';
				}
			}
			return redirect()->route('QuizScreening');
		}

		return view('pages.screening');
	}


	public function index(Request $request)
	{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$quiz_id = base64_decode($data['quiz_id']);
			QuizForm::where('id', $quiz_id)->update(array(
				'meta_data' => $data['answerObject']
			));
			/*$response = $this->getScoreByUser($data['answerObject']);
			if(!empty($response)) {
				QuizForm::where('id', $quiz_id)->update(array(
					'status' => isset($response['status'])?$response['status']:null,
					'data_a' => isset($response['data_a'])?$response['data_a']:null,
					'data_b' => isset($response['data_b'])?$response['data_b']:null,
					'data_c' => isset($response['data_c'])?$response['data_c']:null,
					'data_d' => isset($response['data_d'])?$response['data_d']:null,
					'total_score' => isset($response['total_score'])?$response['total_score']:null,
				));
				
				if($response['status'] == 1){
					SessionAssigendData::create([
					 'quiz_id' => $quiz_id,
					 'group_session_assigned' => 2
					]);
				}
				else if($response['status'] == 2){
					SessionAssigendData::create([
					 'quiz_id' => $quiz_id,
					 'group_session_assigned' => 2,
					 'ind_session_assigned' => 2,
					 'parent_session_assigned' => 1,
					]);
				}
				else if($response['status'] == 3){
					SessionAssigendData::create([
					 'quiz_id' => $quiz_id,
					 'group_session_assigned' => 2,
					 'ind_session_assigned' => 4,
					 'parent_session_assigned' => 1,
					]);
				}
				$userData = QuizForm::select('org_id','mobile')->where('id', $quiz_id)->whereNull('org_id')->first();
				// if(!empty($userData)){
					// $userId = @User::select('id')->where('mobile_no',$userData->mobile)->where('parent_id',0)->first()->id;
					// if(!empty($userId)){
						// $screening_wallet_reward = getSetting("screening_wallet_reward")[0];
						// if(!empty($screening_wallet_reward) > 0) {
							// $detail = UserDetails::select('wallet_amount')->where(['user_id'=>$userId])->first();
							// $wallet_amount = $detail->wallet_amount + $screening_wallet_reward;
							// UserWallet::create([
								// 'user_id' => $userId,
								// 'type' => 1,
								// 'amount' => $screening_wallet_reward
							// ]);
							// UserDetails::where('user_id',$userId)->update(['wallet_amount'=>$wallet_amount]);
							// $planData = PlanPeriods::select('id','remaining_appointment')->where('user_id',$userId)->where('status',1)->first();
							// if(!empty($planData)) {
								// $remaining_appointment = $planData->remaining_appointment + 2 ;
								// PlanPeriods::where('id',$planData->id)->update(['remaining_appointment'=>$remaining_appointment]);
							// }
						// }
					// }
				// }
			}*/
			return 1;
		}
		return view('pages.quiz');
	}

	public function healthScreeningAssesAdmin(Request $request)
	{
		$orgData = null;
		$totalAssessment = null;
		$totalAssessmentToday = null;
		$totalReg = null;
		$chartArray = [];
		$piChartArray = [];
		$wildChart = [];
		$piChartRegArray = [];


		$totalAssessment = QuizForm::where('org_id', null)->where('meta_data', '!=', null)->count();
		$Allassementdata = QuizForm::where('org_id', null)->where('meta_data', '!=', null)->get();
		$totalAssessmentPending = QuizForm::where('org_id', null)->where('meta_data', '=', null)->count();
		$totalAssessmentToday = QuizForm::where('org_id', null)->where('meta_data', '!=', null)->whereDate('created_at', '>=', date('y-m-d'))->count();
		$totalAssessmentTodaydata = QuizForm::where('org_id', null)->where('meta_data', '!=', null)->whereDate('created_at', '>=', date('y-m-d'))->get();
		$totalReg = QuizForm::where('org_id', null)->count();
		$totalReget = QuizForm::where('org_id', null)->get();
		if ($request->input('file_type') == "totalregistration") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'A', 'B', 'C ', 'D', 'Total Score', 'Date');
			foreach ($totalReget as $res) {
				$ordersDataArray[] = array(
					$i,
					$res->name,
					$res->gender,
					$res->mobile,
					$res->age,
					$res->data_a,
					$res->data_b,
					$res->data_c,
					$res->data_d,
					$res->total_score,
					date("Y-m-d", strtotime($res->created_at))
				);
				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'register.xlsx');
		}

		if ($request->input('file_type') == "totalassessment") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'A', 'B', 'C ', 'D', 'Total Score', 'Date');
			foreach ($Allassementdata as $res) {

				$ordersDataArray[] = array(
					$i,
					$res->name,
					$res->gender,
					$res->mobile,
					$res->age,
					$res->data_a,
					$res->data_b,
					$res->data_c,
					$res->data_d,
					$res->total_score,
					date("Y-m-d", strtotime($res->created_at))

				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'assessment.xlsx');
		}

		if ($request->input('file_type') == "todayassessment") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'A', 'B', 'C ', 'D', 'Total Score', 'Date');
			foreach ($totalAssessmentTodaydata as $res) {

				$ordersDataArray[] = array(
					$i,
					$res->name,
					$res->gender,
					$res->mobile,
					$res->age,
					$res->data_a,
					$res->data_b,
					$res->data_c,
					$res->data_d,
					$res->total_score,
					date("Y-m-d", strtotime($res->created_at))

				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'today-assessment.xlsx');
		}

		if ($request->input('file_type') == "totalassessment") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'A', 'B', 'C ', 'D', 'Total Score', 'Date');
			foreach ($Allassementdata as $res) {

				$ordersDataArray[] = array(
					$i,
					$res->name,
					$res->gender,
					$res->mobile,
					$res->age,
					$res->data_a,
					$res->data_b,
					$res->data_c,
					$res->data_d,
					$res->total_score,
					date("Y-m-d", strtotime($res->created_at))
				);
				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'assessment.xlsx');
		}

		$quizDatas = $this->getDataBySlug();
		$normal = 0;
		$mild = 0;
		$most_val = 0;
		$negativeA_most_val = 0;
		$detechment_most_val = 0;
		$antagonism_most_val = 0;
		$disinhibition_most_val = 0;
		$psychoticism_most_val = 0;
		$data_most_val_a = 0;
		$data_most_val_b = 0;
		$data_most_val_c = 0;
		$data_most_val_d = 0;
		$data_mild_a = 0;
		$data_mild_b = 0;
		$data_mild_c = 0;
		$data_mild_d = 0;
		$data_normal_a = 0;
		$data_normal_b = 0;
		$data_normal_c = 0;
		$data_normal_d = 0;
		$negativeA_mild = 0;
		$detechment_mild = 0;
		$antagonism_mild = 0;
		$disinhibition_mild = 0;
		$psychoticism_mild = 0;
		
		if (count($quizDatas)) {
			foreach ($quizDatas as $index => $raw) {

				$negative_effect = $raw->negative_effect;
				$detechment = $raw->detechment;
				$antagonism = $raw->antagonism;
				$disinhibition = $raw->disinhibition;
				$psychoticism = $raw->psychoticism;

				

				$finalTotalScore = $raw->finalTotalScore;

				if ($raw->status == 1) {
					$normal++;
				}
				if ($raw->status == 2) {
					$mild++;
				}
				if ($raw->status == 3) {
					$most_val++;
				}
			}
		}

		if ($Allassementdata->count()) {
			foreach ($Allassementdata as $i => $raw) {
				$i + 1;
				if ($raw->data_a >= 15 && $raw->data_a <= 21) {
					$data_most_val_a += 1;
				}
				if ($raw->data_b >= 15 && $raw->data_b <= 21) {
					$data_most_val_b += 1;
				}
				if ($raw->data_c >= 15 && $raw->data_c <= 21) {
					$data_most_val_c += 1;
				}
				if ($raw->data_d >= 15 && $raw->data_d <= 21) {
					$data_most_val_d += 1;
				}
			}
		}

		if ($Allassementdata->count()) {
			foreach ($Allassementdata as $i => $raw) {
				$i + 1;
				if ($raw->data_a >= 8 && $raw->data_a <= 14) {
					$data_mild_a += 1;
				}
				if ($raw->data_b >= 8 && $raw->data_b <= 14) {
					$data_mild_b += 1;
				}
				if ($raw->data_c >= 8 && $raw->data_c <= 14) {
					$data_mild_c += 1;
				}
				if ($raw->data_d >= 8 && $raw->data_d <= 14) {
					$data_mild_d += 1;
				}
			}
		}
		if ($Allassementdata->count()) {
			foreach ($Allassementdata as $i => $raw) {
				$i + 1;
				if ($raw->data_a >= 1 && $raw->data_a <= 7) {
					$data_normal_a += 1;
				}
				if ($raw->data_b >= 1 && $raw->data_b <= 7) {
					$data_normal_b += 1;
				}
				if ($raw->data_c >= 1 && $raw->data_c <= 7) {
					$data_normal_c += 1;
				}
				if ($raw->data_d >= 1 && $raw->data_d <= 7) {
					$data_normal_d += 1;
				}
			}
		}

		$chartArray[] = ['title' => 'Somatic symptoms', 'tot' => $data_most_val_a];
		$chartArray[] = ['title' => 'Anxiety', 'tot' => $data_most_val_b];
		$chartArray[] = ['title' => 'Social Functioning', 'tot' => $data_most_val_c];
		$chartArray[] = ['title' => 'Depression', 'tot' => $data_most_val_d];

		$wildChart[] = ['title' => 'Somatic symptoms', 'tot' => $data_mild_a];
		$wildChart[] = ['title' => 'Anxiety', 'tot' => $data_mild_b];
		$wildChart[] = ['title' => 'Social Functioning', 'tot' => $data_mild_c];
		$wildChart[] = ['title' => 'Depression', 'tot' => $data_mild_d];


		$piChartArray[] = ['title' => 'Normal', 'tot' => $normal];
		$piChartArray[] = ['title' => 'Mild', 'tot' => $mild];
		$piChartArray[] = ['title' => 'Most Vulnerable', 'tot' => $most_val];

		$piChartRegArray[] = ['title' => 'Assessment Done', 'tot' => $totalAssessment];
		$piChartRegArray[] = ['title' => 'Pending', 'tot' => $totalAssessmentPending];

		$totChartVal = count($quizDatas);


		return view('pages.screening.screening-admin', compact('totalAssessment', 'totalReg', 'totalAssessmentToday', 'chartArray', 'totChartVal', 'piChartArray', 'wildChart', 'piChartRegArray'));
	}

	public function healthAssesAdmin(Request $request, $slug)
	{
		$org = Session::get('organizationMaster.slug');

		$orgData = null;
		$totalAssessment = null;
		$totalAssessmentToday = null;
		$totalReg = null;
		$chartArray = [];
		$piChartArray = [];
		$wildChart = [];
		$piChartRegArray = [];
		if (!empty($slug)) {

			$orgData = OrganizationMaster::where('slug', $slug)->first();

			$query = AssesmentAnswer::with(['userDetails', 'MhQuesRange', 'mhQuesRange.MhResultType'])
				->whereHas('userDetails', function ($q) use ($orgData) {
					$q->where('organization', @$orgData->id);
				})
				->get();
			$totalAssessment = $query->count();
			
			$totalAssessmentToday = $query->filter(function ($item) {
				return $item->created_at->isToday();
			})->count();

			$users    = User::where('organization', $orgData->id)->get();
			$students = Student::where('org_id', $orgData->id)->get();
			$combined = $users->merge($students);
			$totalReg = $combined->count();

			for ($i = 11; $i >= 0; $i--) {
				$startDate = now()->subMonths($i)->startOfMonth();
				$endDate = now()->subMonths($i)->endOfMonth();

				$monthlyUserCount = User::where(['organization' => $orgData->id, 'student_id' => null])
					->whereBetween('created_at', [$startDate, $endDate])
					->count();
				$monthlyStudentCount = Student::where('org_id', $orgData->id)
					->whereBetween('created_at', [$startDate, $endDate])
					->count();

				$monthlyData['months'][] = $startDate->format('F');  // Only month names
				$monthlyData['totals'][] = $monthlyUserCount + $monthlyStudentCount;
			}
			for ($i = 11; $i >= 0; $i--) {
				$startDates = now()->subMonths($i)->startOfMonth();
				$endDates = now()->subMonths($i)->endOfMonth();

				// Count subscriptions within the current month
				$sub = UsersSubscriptions::with([
					'PlanPeriods.Plans',
					'UserSubscribedPlans.PlanPeriods',
					'User',
					'ReferralMaster'
				])
					->where('organization_id', $orgData->id)
					->where('order_status', 1)
					->whereBetween('created_at', [$startDates, $endDates])  // Ensure correct time range
					->count();

				// Store month names and subscription counts
				$monthlyDataSubs['months'][] = $startDates->format('F');  // Month names
				$monthlyDataSubs['totals'][] = $sub;  // Subscription count
			}

			$mentalStatusGroup = $query->groupBy('mental_status')->map(function ($group, $key) {
				return [
					'name' => $key,
					'y' => $group->count(),
				];
			})->values();
			
			// Generate Excel Report
			$file_type = $request->input('file_type');
			if ($file_type == 'totalregistration') {
				$currentDate = date('Y-m-d');
			    if (!isset($request->from_date) || !isset($request->to_date)) {
					$healthassessment = $combined->where('created_at', '>=', $currentDate . ' 00:00:00')
												 ->where('created_at', '<=', $currentDate . ' 23:59:59');
				} else {
					
					$healthassessment = $combined->where('created_at', '>=', $request->from_date . ' 00:00:00')
												 ->where('created_at', '<=', $request->to_date . ' 23:59:59');
				}
				$assessArray[] = ['S No', 'Mobile/Student ID', 'Name', 'Age'];
				if ($healthassessment->count() > 0) {
					foreach ($healthassessment as $index => $healthassess) {
						$assessArray[] = [
							$index + 1,
							$healthassess->mobile_no ?? @$healthassess->student_id,
							($healthassess->first_name ?? '') . " " . ($healthassess->last_name ?? ''),
							@$healthassess->dob ? \Carbon\Carbon::parse($healthassess->dob)->age : '',
						];
					}
				}
				return Excel::download(new AssessmentAnswerExport($assessArray), 'Registration.xlsx');
			} elseif ($file_type == 'totalassessment') {
				$currentDate = date('Y-m-d');
				if (!isset($request->from_date) || !isset($request->to_date)) {
					$healthassessment = $query->where('created_at', '>=', $currentDate . ' 00:00:00')
											  ->where('created_at', '<=', $currentDate . ' 23:59:59');
				} else {
					$healthassessment = $query->where('created_at', '>=', $request->from_date . ' 00:00:00')
											  ->where('created_at', '<=', $request->to_date . ' 23:59:59');
				}
				$assessArray[] = ['S No', 'User Name', 'Mobile', 'Total Score', 'Mental Status', 'Organization'];
				if ($healthassessment->count() > 0) {
					foreach ($healthassessment as $index => $healthassess) {
						$assessArray[] = [
							$index + 1,
							$healthassess->userDetails->first_name . " " . $healthassess->userDetails->last_name,
							$healthassess->userDetails->mobile_no,
							$healthassess->total_score,
							$healthassess->mental_status,
							$healthassess->userDetails->OrganizationMaster->title,
						];
					}
				}
			
				return Excel::download(new AssessmentAnswerExport($assessArray), 'Total-Assessment-List.xlsx');
			} elseif ($file_type == 'todayassessment') {
				$currentDate = date('Y-m-d');
				$healthassessment = $query->where('created_at', '>=', $currentDate . ' 00:00:00')
										  ->where('created_at', '<=', $currentDate . ' 23:59:59');
			
				$assessArray[] = ['S No', 'User Name', 'Mobile', 'Total Score', 'Mental Status', 'Organization'];
				if ($healthassessment->count() > 0) {
					foreach ($healthassessment as $index => $healthassess) {
						$assessArray[] = [
							$index + 1,
							$healthassess->userDetails->first_name . " " . $healthassess->userDetails->last_name,
							$healthassess->userDetails->mobile_no,
							$healthassess->total_score,
							$healthassess->mental_status,
							$healthassess->userDetails->OrganizationMaster->title,
						];
					}
				}
				return Excel::download(new AssessmentAnswerExport($assessArray), 'Today-Assessment-List.xlsx');
			}
		}

		return view('pages.quiz-files.health-asses-admin', compact('monthlyDataSubs', 'mentalStatusGroup', 'monthlyData', 'totalAssessment', 'totalReg', 'totalAssessmentToday', 'orgData', 'chartArray', 'piChartArray', 'wildChart', 'piChartRegArray'));
	}

	public function assessmentData(Request $request, $slug)
	{
		$enquirys = [];
		if (!empty($slug)) {
			$enquirys = $this->getDataBySlug($slug);
		}
		return $enquirys;
	}

	public function getDataBySlug($slug = null)
	{
		if ($slug) {
			$orgData = OrganizationMaster::select('id', 'pwd', 'title')->where("slug", $slug)->first();
			$enquirys = QuizForm::where('org_id', $orgData->id)->where('meta_data', '!=', null)->get();
		} else {
			$orgData = OrganizationMaster::select('id', 'pwd', 'title')->get();
			$enquirys = QuizForm::where('meta_data', '!=', null)->where('org_id', null)->get();
		}

		$meta_data = [];

		if ($enquirys->count() > 0) {
			foreach ($enquirys as $index => $element) {
				$meta_data  = !empty($element->meta_data) ? json_decode($element->meta_data, true) : [];

				$negative_effect = 0;
				$negative_effect_ans = 0;
				$detechment = 0;
				$detechment_ans = 0;
				$antagonism = 0;
				$antagonism_ans = 0;
				$disinhibition = 0;
				$disinhibition_ans = 0;
				$psychoticism = 0;
				$psychoticism_ans = 0;

				if (is_countable($meta_data) && count($meta_data) > 0) {
					foreach ($meta_data as $raw) {
						$ans = null;
						if ($raw['ans'] == 'optionA') {
							$ans = 0;
						}
						if ($raw['ans'] == 'optionB') {
							$ans = 1;
						}
						if ($raw['ans'] == 'optionC') {
							$ans = 2;
						}
						if ($raw['ans'] == 'optionD') {
							$ans = 3;
						}
						if ($raw['ques'] == 8 || $raw['ques'] == 9 || $raw['ques'] == 10 || $raw['ques'] == 11 || $raw['ques'] == 15) {
							if ($ans != null) {
								$negative_effect_ans += $ans;
							}
						}
						if ($raw['ques'] == 4 || $raw['ques'] == 13 || $raw['ques'] == 14 || $raw['ques'] == 16 || $raw['ques'] == 18) {
							if ($ans != null) {
								$detechment_ans += $ans;
							}
						}
						if ($raw['ques'] == 17 || $raw['ques'] == 19 || $raw['ques'] == 20 || $raw['ques'] == 22 || $raw['ques'] == 25) {
							if ($ans != null) {
								$antagonism_ans += $ans;
							}
						}
						if ($raw['ques'] == 1 || $raw['ques'] == 2 || $raw['ques'] == 3 || $raw['ques'] == 5 || $raw['ques'] == 6) {
							if ($ans != null) {
								$disinhibition_ans += $ans;
							}
						}
						if ($raw['ques'] == 7 || $raw['ques'] == 12 || $raw['ques'] == 21 || $raw['ques'] == 23 || $raw['ques'] == 24) {
							if ($ans != null) {
								$psychoticism_ans += $ans;
							}
						}
					}
				}
				$negative_effect = $negative_effect_ans;
				$detechment = $detechment_ans;
				$antagonism = $antagonism_ans;
				$disinhibition = $disinhibition_ans;
				$psychoticism = $psychoticism_ans;

				$totalScore = $negative_effect_ans + $detechment_ans + $antagonism_ans + $disinhibition_ans + $psychoticism_ans;
				$finalTotalScore = $totalScore;

				$element['id'] = $index + 1;
				$element['negative_effect'] = round($negative_effect);
				$element['detechment'] = round($detechment);
				$element['antagonism'] = round($antagonism);
				$element['disinhibition'] = round($disinhibition);
				$element['psychoticism'] = round($psychoticism);
				$element['finalTotalScore'] = round($finalTotalScore);
			}
		}
		return $enquirys;
	}

	public function programme(Request $request, $slug)
	{
		$orgData = OrganizationMaster::where("slug", $slug)->first();
		return view('pages.quiz-files.programme', compact('orgData'));
	}
	public function assessmentList(Request $request, $slug = null)
	{

		$search = '';
		$page = 10;

		if ($request->isMethod('post')) {
			$params = array();

			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('mentalstatus'))) {
				$params['mentalstatus'] = base64_encode($request->input('mentalstatus'));
			}
			if (!empty($request->input('candidate'))) {
				$params['candidate'] = base64_encode($request->input('candidate'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('assessmentList', ['slug' => Session::get('organizationMaster.slug')] + $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$candidate = base64_decode($request->input('candidate'));
			$mentalStatus = base64_decode($request->input('mentalstatus'));
			$orgData = OrganizationMaster::where('slug', $slug)->first();
			$query = AssesmentAnswer::with(['userDetails', 'MhQuesRange', 'mhQuesRange.MhResultType'])->whereHas('userDetails', function ($q) use ($orgData) {
				$q->where('organization', $orgData->id);
			});

			if (!empty($search)) {
				$query->whereHas('userDetails', function ($q) use ($search) {
					$q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $search . '%');
				});
			}
			if (!empty($candidate)) {
				$query->whereHas('userDetails', function ($q) use ($candidate) {
					$q->where('mobile_no', $candidate);
				});
			}
			if (!empty($mentalStatus)) {
				$query->where('mental_status', $mentalStatus);
				
			}

			$userData = $query->orderBy('id', 'desc')->paginate($page);
			return view('pages.quiz-files.assessment-list', compact('userData', 'orgData'));
		}
	}
	public function getMentalhealthList(Request $request, $slug)
	{


		$search = '';
		$page = 10;

		if ($request->isMethod('post')) {
			$params = array();

			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('getMentalhealthList', $params)->withInput();
		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$org = OrganizationMaster::where('slug', $slug)->first();
			$query = AssesmentAnswer::with(['userDetails', 'MhQuesRange', 'mhQuesRange.MhResultType'])->whereHas('userDetails', function ($q) use ($org) {
				$q->where('organization', $org->id);
			});

			if (!empty($search)) {
				$query->whereHas('userDetails', function ($q) use ($search) {
					$q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $search . '%');
				});
			}
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$userData = $query->orderBy('id', 'desc')->paginate($page);
		}

		return view('pages.quiz-files.mentalHealthList', compact('userData'));
	}
	public function getMentalhealthReport(Request $request)
	{
		$data = $request->all();
		$page = 10;

		$mood = MhMood::where('user_id', $data['user_id'])->orderBy('created_at', 'desc')->get();

		$mhTracker = MhTracker::where('user_id', $data['user_id'])->orderBy('created_at', 'desc')->get();

		$mhJournal = MhJournal::with('MhJournalThought')->where('user_id', $data['user_id'])->orderBy('created_at', 'desc')->get();

		$preAssessment = PreAssesmentAnswer::where('user_id', $data['user_id'])->orderBy('created_at', 'desc')->get();

		$dataFeedback = MhWpFeedback::with('MhWeeklyProgram')->where('user_id', $data['user_id'])->orderBy('created_at', 'desc')->get();

		$MhCommonSheet = MhCommonSheet::with('MhSheetData')->orderBy('created_at', 'desc')->get();

		$MhCommon =  MhSheetData::with('MhCommonSheet')->where('user_id', $data['user_id'])->orderBy('created_at', 'desc')->get();

		$mhSheetData = SheetData::where('user__id', $data['user_id'])->orderBy('created_at', 'desc')->get();

		$userDetails = AssesmentAnswer::with([
			'userDetails',
			'Appointments',
			'MhQuesRange',
			'mhQuesRange.MhResultType',
			'Symptoms',
			'PreAssesmentAnswer',
			'mhQuesRange.MhResultType.MhWeeklyProgram',
			'mhQuesRange.MhResultType.MhWeeklyProgram.AssessmentOverview',
			'mhQuesRange.MhResultType.MhWeeklyProgram.MhProgramMatrix'
		])->where('user_id', $data['user_id'])->get();

		// Fetching user data with relationships including feedbacks
		$userData = AssesmentAnswer::with([
			'userDetails',
			'Appointments',
			'MhQuesRange',
			'mhQuesRange.MhResultType',
			'Symptoms',
			'AppointmentsAll',
			'mhQuesRange.MhResultType.MhWeeklyProgram',
			'mhQuesRange.MhResultType.MhWeeklyProgram.MhWpFeedback',
			'mhQuesRange.MhResultType.MhWeeklyProgram.AssessmentOverview',
			'mhQuesRange.MhResultType.MhWeeklyProgram.MhProgramMatrix',
			'mhQuesRange.MhResultType.MhWeeklyProgram.AssessmentOverview' // Ensure to include feedbacks
		])->where('user_id', $data['user_id'])->groupBy('user_id')->get();
		$assessmentAnswers = AssesmentAnswer::where('user_id',$data['user_id'])->get();
		$rawIds = [];
		foreach ($assessmentAnswers as $answer) {
			$scoreData = json_decode($answer->score_data, true);
			if (!is_array($scoreData)) {
				continue; 
			}
			foreach ($scoreData as $data) {
				$rawIds[] = $data['rawId'];
			}
		}
		$questionRanges = MhQuesRange::whereIn('id', $rawIds)->get();
		$mappedTypes = $questionRanges->pluck('type', 'id'); 

		return view('pages.quiz-files.mentalHealthReport', compact('mhSheetData', 'mhTracker', 'preAssessment', 'mhJournal', 'userData', 'userDetails', 'mood', 'MhCommon', 'MhCommonSheet',  'dataFeedback', 'questionRanges'));
	}
	public function activityStation(Request $request)
	{

		$data = $request->all();

		$MhCommon =  MhSheetData::where('user_id', $data['user_id'])->where('sheet_id', $data['sheet_id'])->orderBy('created_at', 'desc')->get();

		return view("pages.quiz-files.activity-station", compact('data', 'MhCommon'));
	}


	public function screeningList(Request $request)
	{


		$query = QuizForm::where('org_id', null);

		if ($request->input('from_date') != '') {
			$from_date = date("Y-m-d", strtotime($request->input('from_date')));
			$query->whereDate('created_at', '>=', $from_date);
		}
		if ($request->input('to_date') != '') {
			$to_date = date("Y-m-d", strtotime($request->input('to_date')));
			$query->whereDate('created_at', '<=', $to_date);
		}

		if ($request->input('candidate') != '') {
			$candidate = $request->input('candidate');
			if (is_numeric($candidate)) {
				$query->where('mobile', $candidate);
			} else {
				$query->where('name', 'like', '%' . $candidate . '%');
			}
		}
		if ($request->input('status') != '') {
			$status = $request->input('status');
			$query->where('status', $status);
		}
		if ($request->input('file_type')) {
			$enquirys = $query->get();
		} else {
			$enquirys = $query->paginate(50);
		}




		/* if(count($enquirys) > 0){
		  foreach($enquirys as $index => $element) {
			$meta_data = !empty($element->meta_data) ? json_decode($element->meta_data,true) : [];
			$negative_effect = 0;
			$negative_effect_ans = 0;
			$detechment = 0;
			$detechment_ans = 0;
			$antagonism = 0;
			$antagonism_ans = 0;
			$disinhibition = 0;
			$disinhibition_ans = 0;
			$psychoticism = 0;
			$psychoticism_ans = 0;
			
			if(count($meta_data)>0) {
				foreach($meta_data as $raw) {
					$ans = null;
					if($raw['ans'] == 'optionA'){
						$ans = 0;
					}
					if($raw['ans'] == 'optionB'){
						$ans = 1;
					}
					if($raw['ans'] == 'optionC'){
						$ans = 2;
					}
					if($raw['ans'] == 'optionD'){
						$ans = 3;
					}
					if($raw['ques'] == 8 || $raw['ques'] == 9 || $raw['ques'] == 10 || $raw['ques'] == 11 || $raw['ques'] == 15){
						if($ans != null){
							$negative_effect_ans += $ans; 
						}
					}
					if($raw['ques'] == 4 || $raw['ques'] == 13 || $raw['ques'] == 14 || $raw['ques'] == 16 || $raw['ques'] == 18){
						if($ans != null){
							$detechment_ans += $ans; 
						}
					}
					if($raw['ques'] == 17 || $raw['ques'] == 19 || $raw['ques'] == 20 || $raw['ques'] == 22 || $raw['ques'] == 25){
						if($ans != null){
							$antagonism_ans += $ans; 
						}
					}
					if($raw['ques'] == 1 || $raw['ques'] == 2 || $raw['ques'] == 3 || $raw['ques'] == 5 || $raw['ques'] == 6){
						if($ans != null){
							$disinhibition_ans += $ans; 
						}
					}
					if($raw['ques'] == 7 || $raw['ques'] == 12 || $raw['ques'] == 21 || $raw['ques'] == 23 || $raw['ques'] == 24){
						if($ans != null){
							$psychoticism_ans += $ans; 
						}
					}
				}
			}
			$negative_effect = $negative_effect_ans;
			$detechment = $detechment_ans;
			$antagonism = $antagonism_ans;
			$disinhibition = $disinhibition_ans;
			$psychoticism = $psychoticism_ans;
			
			$totalScore = $negative_effect_ans + $detechment_ans + $antagonism_ans + $disinhibition_ans + $psychoticism_ans;
			$finalTotalScore = $element->total_score;
			
			$element['negative_effect'] = round($negative_effect);
			$element['detechment'] = round($detechment);
			$element['antagonism'] = round($antagonism);
			$element['disinhibition'] = round($disinhibition);
			$element['psychoticism'] = round($psychoticism);
			$element['finalTotalScore'] = round($finalTotalScore);
			
		 }
		}*/


		if ($request->input('assesment') != '') {


			$assessArray = [];

			foreach ($enquirys as $raw) {

				if ($request->input('assesment') == 'mild') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 1 && $finalTotalScore <= 2) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'normal') {
					$finalTotalScore = $raw->finalTotalScore;

					if ($finalTotalScore >= 0 && $finalTotalScore <= 1) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'most_vulnerable') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 2 && $finalTotalScore <= 3) {
						$assessArray[] = $raw;
					}
				}
			}
			$enquirys = $assessArray;
			$page = 50;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) {
				$currentPage = $input['page'];
			} else {
				$currentPage = 1;
			}

			$offset = ($currentPage * $page) - $page;
			$itemsForCurrentPage = array_slice($enquirys, $offset, $page, false);
			$enquirys =  new Paginator($itemsForCurrentPage, count($enquirys), $page, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
		}

		if ($request->input('file_type') == "excel") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Status', 'Name', 'Gender', 'Mobile', 'Age', 'A', 'B', 'C', 'D', 'Total Score');
			foreach ($enquirys as $res) {

				if ($res->status == 1) {
					$status = 'Normal';
				} elseif ($res->status == 2) {
					$status = 'Mild';
				} elseif ($res->status == 3) {
					$status = 'Most Vulnerable';
				}
				$ordersDataArray[] = array(
					$i,
					$status,
					$res->name,
					$res->gender,
					$res->mobile,
					$res->age,
					$res->data_a,
					$res->data_b,
					$res->data_c,
					$res->data_d,
					$res->total_score,
				);
				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'screening.xlsx');
		}



		return view('pages.screening.screening-list', compact('enquirys'));
	}

	public function reportcard(Request $request)
	{
		$slug = $request->slug;
		$id = null;
		if (isset($request->id)) {
			$id = base64_decode($request->id);
		}
		if ($id) {
			$orgData = OrganizationMaster::where("slug", $slug)->first();
			$userData = QuizForm::with(['SessionAssesment', 'SessionAssigendData'])->where("id", $id)->first();
			$group_session_taken = 0;
			$pendingGrpSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$group_session_taken = $userData->SessionAssigendData->group_session_taken;
				$pendingGrpSession = $userData->SessionAssigendData->group_session_assigned - $group_session_taken;
			}
			$sessionChartArray[] = ['title' => 'Complete Session', 'tot' => $group_session_taken];
			$sessionChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingGrpSession];
			$parent_session_taken = 0;
			$pendingParentSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$parent_session_taken = $userData->SessionAssigendData->parent_session_taken;
				$pendingParentSession = $userData->SessionAssigendData->parent_session_assigned - $parent_session_taken;
			}
			$sessionPiChartArray[] = ['title' => 'Complete Session', 'tot' => $parent_session_taken];
			$sessionPiChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingParentSession];

			$ind_session_taken = 0;
			$pendingIndSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$ind_session_taken = $userData->SessionAssigendData->ind_session_taken;
				$pendingIndSession = $userData->SessionAssigendData->ind_session_assigned - $ind_session_taken;
			}
			$indSessionPiChartArray[] = ['title' => 'Complete Session', 'tot' => $ind_session_taken];
			$indSessionPiChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingIndSession];
			return view('pages.quiz-files.report-card-details', compact('orgData', 'slug', 'userData', 'sessionChartArray', 'sessionPiChartArray', 'indSessionPiChartArray'));
		}
		return abort('404');
	}

	public function screeningreportcard(Request $request)
	{
		$slug = $request->slug;
		$id = null;
		if (isset($request->id)) {
			$id = base64_decode($request->id);
		}
		if ($id) {
			$orgData = OrganizationMaster::where("slug", $slug)->first();
			$userData = QuizForm::with(['SessionAssesment', 'SessionAssigendData'])->where("id", $id)->first();
			$group_session_taken = 0;
			$pendingGrpSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$group_session_taken = $userData->SessionAssigendData->group_session_taken;
				$pendingGrpSession = $userData->SessionAssigendData->group_session_assigned - $group_session_taken;
			}
			$sessionChartArray[] = ['title' => 'Complete Session', 'tot' => $group_session_taken];
			$sessionChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingGrpSession];
			$parent_session_taken = 0;
			$pendingParentSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$parent_session_taken = $userData->SessionAssigendData->parent_session_taken;
				$pendingParentSession = $userData->SessionAssigendData->parent_session_assigned - $parent_session_taken;
			}
			$sessionPiChartArray[] = ['title' => 'Complete Session', 'tot' => $parent_session_taken];
			$sessionPiChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingParentSession];

			$ind_session_taken = 0;
			$pendingIndSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$ind_session_taken = $userData->SessionAssigendData->ind_session_taken;
				$pendingIndSession = $userData->SessionAssigendData->ind_session_assigned - $ind_session_taken;
			}
			$indSessionPiChartArray[] = ['title' => 'Complete Session', 'tot' => $ind_session_taken];
			$indSessionPiChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingIndSession];
			return view('pages.screening.screening-report-card', compact('orgData', 'slug', 'userData', 'sessionChartArray', 'sessionPiChartArray', 'indSessionPiChartArray'));
		}
		return abort('404');
	}

	public function studentReport(Request $request)
	{
		$id = null;
		if (isset($request->id)) {
			$id = base64_decode($request->id);
		}
		if ($id) {
			$userData = QuizForm::with(['SessionAssesment', 'SessionAssigendData'])->where("id", $id)->first();
			$group_session_taken = 0;
			$pendingGrpSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$group_session_taken = $userData->SessionAssigendData->group_session_taken;
				$pendingGrpSession = $userData->SessionAssigendData->group_session_assigned - $group_session_taken;
			}
			$sessionChartArray[] = ['title' => 'Complete Session', 'tot' => $group_session_taken];
			$sessionChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingGrpSession];
			$parent_session_taken = 0;
			$pendingParentSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$parent_session_taken = $userData->SessionAssigendData->parent_session_taken;
				$pendingParentSession = $userData->SessionAssigendData->parent_session_assigned - $parent_session_taken;
			}
			$sessionPiChartArray[] = ['title' => 'Complete Session', 'tot' => $parent_session_taken];
			$sessionPiChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingParentSession];

			$ind_session_taken = 0;
			$pendingIndSession = 2;
			if (!empty($userData->SessionAssigendData)) {
				$ind_session_taken = $userData->SessionAssigendData->ind_session_taken;
				$pendingIndSession = $userData->SessionAssigendData->ind_session_assigned - $ind_session_taken;
			}
			$indSessionPiChartArray[] = ['title' => 'Complete Session', 'tot' => $ind_session_taken];
			$indSessionPiChartArray[] = ['title' => 'Pending Session', 'tot' => $pendingIndSession];
			return view('pages.quiz-files.student-report-card', compact('userData', 'sessionChartArray', 'sessionPiChartArray', 'indSessionPiChartArray'));
		}
		return abort('404');
	}
	public function updateNextsessiondate(Request $request)
	{
		SessionAssesment::where('id', $request->session_id)->update(array(
			'next_screening_date' => $request->datatime
		));
		return 1;
	}

	public function getScoreByUser($meta_data)
	{
		$meta_data = !empty($meta_data) ? json_decode($meta_data, true) : [];
		// $negative_effect = 0;
		// $negative_effect_ans = 0;
		// $detechment = 0;
		// $detechment_ans = 0;
		// $antagonism = 0;
		// $antagonism_ans = 0;
		// $disinhibition = 0;
		// $disinhibition_ans = 0;
		// $psychoticism = 0;
		// $psychoticism_ans = 0;

		$data_a = 0;
		$data_b = 0;
		$data_c = 0;
		$data_d = 0;
		if (count($meta_data) > 0) {
			foreach ($meta_data as $raw) {
				$ans = null;
				if ($raw['ans'] == 'optionA') {
					$ans = 1;
				}
				if ($raw['ans'] == 'optionB') {
					$ans = 2;
				}
				if ($raw['ans'] == 'optionC') {
					$ans = 3;
				}
				if ($raw['ans'] == 'optionD') {
					$ans = 4;
				}
				if ($raw['ques'] >= 1 && $raw['ques'] <= 3) {
					if ($ans != null) {
						$data_a += $ans;
					}
				}
				if ($raw['ques'] >= 4 && $raw['ques'] <= 6) {
					if ($ans != null) {
						$data_b += $ans;
					}
				}
				if ($raw['ques'] >= 7 && $raw['ques'] <= 8) {
					if ($ans != null) {
						$data_c += $ans;
					}
				}
				if ($raw['ques'] >= 9 && $raw['ques'] <= 10) {
					if ($ans != null) {
						$data_d += $ans;
					}
				}
			}
		}

		$totalScore = $data_a + $data_b + $data_c + $data_d;
		$finalTotalScore = $totalScore;
		$status = null;
		if ($finalTotalScore >= 0 && $finalTotalScore <= 24) {
			$status = 1;
		}
		if ($finalTotalScore >= 25 && $finalTotalScore <= 54) {
			$status = 2;
		}
		if ($finalTotalScore >= 55 && $finalTotalScore <= 84) {
			$status = 3;
		}
		return ['status' => $status, 'data_a' => $data_a, 'data_b' => $data_b, 'data_c' => $data_c, 'data_d' => $data_d, 'total_score' => $finalTotalScore];
	}


	public function counciling(Request $request)
	{
		// if($request->isMethod('post')){
		$data = $request->all();
		$newArray = [];
		if ($data['quiz_id'][0] == 'on') {
			unset($data['quiz_id'][0]);
		}


		foreach ($data['quiz_id'] as $key => $val) {
			$newArray[$key]['quiz_id'] = $val;
			$newArray[$key]['counselor_id'] = $data['counselor_id'];
			$newArray[$key]['type'] = $data['sessiontype'];
			$newArray[$key]['created_at'] = date('Y-m-d H:i:s');
			$newArray[$key]['updated_at'] = date('Y-m-d H:i:s');
			$newArray[$key]['is_by_org'] = 0;
		}
		// pr($newArray);
		SessionAssesment::insert($newArray);
		$nextScDate = date('Y-m-d H:i:s', strtotime("+3 months", strtotime($data['screening_date'])));
		SessionAssigendData::whereIn('quiz_id', $data['quiz_id'])->update([
			'screening_date' => $data['screening_date'],
			'parent_screening_date' => $data['parent_screening_date'],
			'next_screening_date' => $nextScDate
		]);
		return back();
		// }
	}

	public function councelingdashboard(Request $request, $slug)
	{

		$orgData = null;
		$totalAssessment = null;
		$totalAssessmentToday = null;
		$totalReg = null;
		$chartArray = [];
		$piChartArray = [];
		$wildChart = [];
		$piChartRegArray = [];
		$cid = null;
		if ($request->input('id') != '') {
			$cid = base64_decode($request->input('id'));
		}
		if (!empty($slug)) {
			$orgData = OrganizationMaster::where("slug", $slug)->first();
			$totalAssessmentPending = SessionAssesment::where('session_status', '0')->count();
			$totalAssessmentDone = SessionAssesment::where('session_status', '1')->count();
			$totalAssessmentToday = SessionAssesment::whereDate('created_at', '=', date('Y-m-d'))->count();
			$Quiz = QuizForm::select('status')->get();
			$counselingDAta = SessionAssesment::with('QuizForm.OrganizationMaster')->get();
			$query = QuizForm::where('org_id', $orgData->id)->where('status', '=', '2');
			$totalReg = SessionAssesment::count();
			$counselingDAtatoday = SessionAssesment::with('QuizForm.OrganizationMaster')->whereDate('created_at', '>=', date('y-m-d'))->get();
			$totalAssessment = SessionAssesment::count();
			$quizDatas = $this->getDataBySlug($slug);
			$grpSession = 0;
			$indSession = 0;
			$parentSession = 0;
			$negativeA_most_val = 0;
			$detechment_most_val = 0;
			$antagonism_most_val = 0;
			$disinhibition_most_val = 0;
			$psychoticism_most_val = 0;
			$negativeA_mild = 0;
			$detechment_mild = 0;
			$antagonism_mild = 0;
			$disinhibition_mild = 0;
			$psychoticism_mild = 0;
			if (count($quizDatas)) {
				foreach ($quizDatas as $index => $raw) {
					$negative_effect = $raw->negative_effect;
					$detechment = $raw->detechment;
					$antagonism = $raw->antagonism;
					$disinhibition = $raw->disinhibition;
					$psychoticism = $raw->psychoticism;

					if ($negative_effect > 2 && $negative_effect <= 3) {
						$negativeA_most_val++;
					}
					if ($negative_effect > 1 && $negative_effect <= 2) {
						$negativeA_mild++;
					}
					if ($detechment > 2 && $detechment <= 3) {
						$detechment_most_val++;
					}
					if ($detechment > 1 && $detechment <= 2) {
						$detechment_mild++;
					}
					if ($antagonism > 2 && $antagonism <= 3) {
						$antagonism_most_val++;
					}
					if ($antagonism > 1 && $antagonism <= 2) {
						$antagonism_mild++;
					}
					if ($disinhibition > 2 && $disinhibition <= 3) {
						$disinhibition_most_val++;
					}
					if ($disinhibition > 1 && $disinhibition <= 2) {
						$disinhibition_mild++;
					}
					if ($psychoticism > 2 && $psychoticism <= 3) {
						$psychoticism_most_val++;
					}
					if ($psychoticism > 1 && $psychoticism <= 2) {
						$psychoticism_mild++;
					}
					$finalTotalScore = $raw->finalTotalScore;
				}
			}
			$chartArray[] = ['title' => 'Negative Affect', 'tot' => $negativeA_most_val];
			$chartArray[] = ['title' => 'Detachment', 'tot' => $detechment_most_val];
			$chartArray[] = ['title' => 'Antagonism', 'tot' => $antagonism_most_val];
			$chartArray[] = ['title' => 'Disinhibition', 'tot' => $disinhibition_most_val];
			$chartArray[] = ['title' => 'Psychoticism', 'tot' => $psychoticism_most_val];


			$wildChart[] = ['title' => 'Negative Affect', 'tot' => $negativeA_mild];
			$wildChart[] = ['title' => 'Detachment', 'tot' => $detechment_mild];
			$wildChart[] = ['title' => 'Antagonism', 'tot' => $antagonism_mild];
			$wildChart[] = ['title' => 'Disinhibition', 'tot' => $disinhibition_mild];
			$wildChart[] = ['title' => 'Psychoticism', 'tot' => $psychoticism_mild];

			$piChartRegArray[] = ['title' => 'Counseling Done', 'tot' => $totalAssessmentDone];
			$piChartRegArray[] = ['title' => 'Pending', 'tot' => $totalAssessmentPending];

			$totChartVal = count($quizDatas);
		}

		foreach ($counselingDAta as $val) {
			if ($val->type == '1') {
				$grpSession++;
			}

			if ($val->type == '2') {
				$indSession++;
			}

			if ($val->type == '3') {
				$parentSession++;
			}
		}
		$piChartArray[] = ['title' => 'Group', 'tot' => $grpSession];
		$piChartArray[] = ['title' => 'Individual', 'tot' => $indSession];
		$piChartArray[] = ['title' => 'Parental', 'tot' => $parentSession];


		if ($request->input('file_type') == "totalcounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Status');
			foreach ($counselingDAta as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";
				} else {
					$data = "Done";
				}

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					@$res->QuizForm->gender,
					@$res->QuizForm->mobile,
					@$res->QuizForm->age,
					$data,


				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'counseling.xlsx');
		}

		if ($request->input('file_type') == "pnCounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Organization', 'Age', 'Status');
			foreach ($counselingDAta as $res) {

				if ($res->session_status == 0) {


					$ordersDataArray[] = array(
						$i,
						@$res->QuizForm->name,
						@$res->QuizForm->gender,
						@$res->QuizForm->mobile,
						$res->QuizForm->OrganizationMaster->title,
						@$res->QuizForm->age,
						"Pending",


					);
				}
				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'pnCounseling.xlsx');
		}

		if ($request->input('file_type') == "dnCounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Organization', 'Age', 'Status');
			foreach ($counselingDAta as $res) {

				if ($res->session_status == 1) {


					$ordersDataArray[] = array(
						$i,
						@$res->QuizForm->name,
						@$res->QuizForm->gender,
						@$res->QuizForm->mobile,
						$res->QuizForm->OrganizationMaster->title,
						@$res->QuizForm->age,
						"Done",


					);
				}
				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'dnCounseling.xlsx');
		}


		if ($request->input('file_type') == "todaycounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Status');
			foreach ($counselingDAtatoday as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";
				} else {
					$data = "Done";
				}

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					@$res->QuizForm->gender,
					@$res->QuizForm->mobile,
					@$res->QuizForm->age,
					$data


				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'todaycounseling.xlsx');
		}


		return view('pages.counsellor.counceling-dashboard', compact('totalAssessment', 'totalReg', 'totalAssessmentToday', 'orgData', 'chartArray', 'totChartVal', 'piChartArray', 'wildChart', 'piChartRegArray', 'totalAssessmentPending', 'totalAssessmentDone', 'cid'));
	}

	public function counselinglist(Request $request, $slug)
	{

		$orgData = OrganizationMaster::select('id', 'pwd', 'title')->where("slug", $slug)->first();
		$query = SessionAssesment::with('QuizForm');

		// $counselingDAta= SessionAssesment::with('QuizForm.OrganizationMaster')->get();
		$cid = null;

		if ($request->input('from_date') != '') {
			$from_date = date("Y-m-d", strtotime($request->input('from_date')));
			$query->whereDate('created_at', '>=', $from_date);
		}
		if ($request->input('to_date') != '') {
			$to_date = date("Y-m-d", strtotime($request->input('to_date')));

			$query->whereDate('created_at', '<=', $to_date);
		}

		if ($request->input('candidate') != '') {
			$candidate = $request->input('candidate');

			if (is_numeric($candidate)) {
				$query->whereHas('QuizForm', function ($query) use ($candidate) {
					$query->where('mobile', $candidate);
				});
			} else {
				$query->whereHas('QuizForm', function ($query) use ($candidate) {
					$query->where('name', $candidate);
				});
			}
		}


		if ($request->input('session_status') != '') {
			$session_status = $request->input('session_status');


			$query->where('session_status', $session_status);
		}



		if ($request->input('assesment') != '') {
			$enquirys = $query->get();
		} else {
			$enquirys = $query->paginate(50);
		}


		$arrss = [];


		if ($request->input('assesment') != '') {


			$assessArray = [];

			foreach ($enquirys as $raw) {

				if ($request->input('assesment') == 'mild') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 1 && $finalTotalScore <= 2) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'normal') {
					$finalTotalScore = $raw->finalTotalScore;

					if ($finalTotalScore >= 0 && $finalTotalScore <= 1) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'most_vulnerable') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 2 && $finalTotalScore <= 3) {
						$assessArray[] = $raw;
					}
				}
			}

			$enquirys = $assessArray;
			$page = 50;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) {
				$currentPage = $input['page'];
			} else {
				$currentPage = 1;
			}
			$offset = ($currentPage * $page) - $page;
			$itemsForCurrentPage = array_slice($enquirys, $offset, $page, false);
			$enquirys =  new Paginator($itemsForCurrentPage, count($enquirys), $page, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
		}
		if ($request->input('file_type') == "excel") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Status');
			foreach ($enquirys as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";
				} else {
					$data = "Done";
				}

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					@$res->QuizForm->gender,
					@$res->QuizForm->mobile,

					@$res->QuizForm->age,
					$data,


				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'Counseling-list.xlsx');
		}

		$orgData = OrganizationMaster::where("slug", $slug)->first();
		return view('pages.counsellor.counseliing-list', compact('orgData', 'enquirys', 'cid'));
	}
	public function councilingdone(Request $request)
	{
		$data = $request->all();

		foreach ($data['counce_id'] as $key => $val) {

			$arr = explode("_", $val);
			$data['counce_id'][$key] = $arr[0];
			$data['quiz_id'][$key] = $arr[1];
			$data['session_type'][$key] = $arr[2];
		}

		$updateProduct = SessionAssesment::whereIn('id', $data['counce_id'])->update(['session_status' => '1']);
		if (isset($data['quiz_id']) && count($data['quiz_id'])) {

			foreach ($data['quiz_id'] as $key => $id) {
				$sessionData = SessionAssigendData::where('quiz_id', $id)->first();

				if ($data['session_type'][$key] == '1') {
					$updatedGrpSesson =  $sessionData->group_session_taken + 1;
					SessionAssigendData::where('id', $sessionData->id)->update([
						'group_session_taken' => $updatedGrpSesson
					]);
				} else if ($data['session_type'][$key] == '2') {
					$updatedIndSesson =  $sessionData->ind_session_taken + 1;
					SessionAssigendData::where('id', $sessionData->id)->update([
						'ind_session_taken' => $updatedIndSesson
					]);
				} else if ($data['session_type'][$key] == '3') {
					$updatedParentSesson =  $sessionData->parent_session_taken + 1;
					SessionAssigendData::where('id', $sessionData->id)->update([
						'parent_session_taken' => $updatedParentSesson
					]);
				}
			}
		}
		return back();
	}

	public function addNote(Request $request)
	{
		$data = $request->all();
		$updateProduct = SessionAssesment::where('id', $data['counsellor_id'])
			->update(['note' => $data['note']]);
		return 1;
	}
	public function Feedback(Request $request)
	{
		if ($request->isMethod('post')) {
			$data =	$request->all();
			$form =  AssementStuFeedback::create([
				'quiz_id' => $data['id'],
				'rating' => $data['rating'],
				'session_id' => $data['session_id'],
				'hg_rating' => $data['hg_rating'],
				'counselor_feedback' => $data['counselor_feedback'],
				'quality' => $data['quality'],
				'content' => $data['content'],
			]);
			return view('pages.counsellor.feedback-success');
		}
		$id = base64_decode($request->id);
		$session = base64_decode($request->session);
		$user = QuizForm::where('id', $id)->first();
		return view('pages.counsellor.feedback', compact('id', 'user', 'session'));
	}
	public function quizquestionlist(Request $request)
	{
		$orgData = OrganizationMaster::all();
		$QuizquestionD = QuizquestionDemo::paginate(50);
		return view('pages.quiz-master-admin.quiz-question-list', compact('orgData', 'QuizquestionD'));
	}

	public function saveQuestion(Request $request)
	{
		$data = $request->all();

		QuizquestionDemo::where('id', $data['question_id'])->update([
			'question' => $data['question'],
			'question_hindi' => $data['question_hindi'],
			'optionA' => $data['optionA'],
			'optionB' => $data['optionB'],
			'optionC' => $data['optionC'],
			'optionD' => $data['optionD'],
			// 'optionHA' => $data['optionHA'],
			// 'optionHB' => $data['optionHB'],
			// 'optionHC' => $data['optionHC'],
			// 'optionHD'=>$data['optionHD'],
			// 'correctOption'=>$data['correctOption'],

		]);

		return 1;
	}


	public function assessmentListadmin(Request $request)
	{
		$orgData = OrganizationMaster::select('id', 'pwd', 'title')->get();
		$query = QuizForm::with('OrganizationMaster')->where('meta_data', '!=', null);

		if ($request->input('from_date') != '') {
			$from_date = date("Y-m-d", strtotime($request->input('from_date')));
			$query->whereDate('created_at', '>=', $from_date);
		}
		if ($request->input('to_date') != '') {
			$to_date = date("Y-m-d", strtotime($request->input('to_date')));
			$query->whereDate('created_at', '<=', $to_date);
		}



		if ($request->input('candidate') != '') {
			$candidate = $request->input('candidate');
			if (is_numeric($candidate)) {
				$query->where('mobile', $candidate);
			} else {
				$query->where('name', 'like', '%' . $candidate . '%');
			}
		}

		if ($request->input('org_data') != '') {
			$org_data = $request->input('org_data');

			$query->where('org_id', $org_data);
		}



		if ($request->input('status') != '') {
			$status = $request->input('status');
			$query->where('status', $status);
		}
		if ($request->input('assesment') != '') {
			$enquirys = $query->get();
		} else {
			$enquirys = $query->paginate(50);
		}
		$arrss = [];
		if (count($enquirys) > 0) {
			foreach ($enquirys as $index => $element) {
				$meta_data = !empty($element->meta_data) ? json_decode($element->meta_data, true) : [];
				$negative_effect = 0;
				$negative_effect_ans = 0;
				$detechment = 0;
				$detechment_ans = 0;
				$antagonism = 0;
				$antagonism_ans = 0;
				$disinhibition = 0;
				$disinhibition_ans = 0;
				$psychoticism = 0;
				$psychoticism_ans = 0;

				if (count($meta_data) > 0) {
					foreach ($meta_data as $raw) {
						$ans = null;
						if ($raw['ans'] == 'optionA') {
							$ans = 0;
						}
						if ($raw['ans'] == 'optionB') {
							$ans = 1;
						}
						if ($raw['ans'] == 'optionC') {
							$ans = 2;
						}
						if ($raw['ans'] == 'optionD') {
							$ans = 3;
						}
						if ($raw['ques'] == 8 || $raw['ques'] == 9 || $raw['ques'] == 10 || $raw['ques'] == 11 || $raw['ques'] == 15) {
							if ($ans != null) {
								$negative_effect_ans += $ans;
							}
						}
						if ($raw['ques'] == 4 || $raw['ques'] == 13 || $raw['ques'] == 14 || $raw['ques'] == 16 || $raw['ques'] == 18) {
							if ($ans != null) {
								$detechment_ans += $ans;
							}
						}
						if ($raw['ques'] == 17 || $raw['ques'] == 19 || $raw['ques'] == 20 || $raw['ques'] == 22 || $raw['ques'] == 25) {
							if ($ans != null) {
								$antagonism_ans += $ans;
							}
						}
						if ($raw['ques'] == 1 || $raw['ques'] == 2 || $raw['ques'] == 3 || $raw['ques'] == 5 || $raw['ques'] == 6) {
							if ($ans != null) {
								$disinhibition_ans += $ans;
							}
						}
						if ($raw['ques'] == 7 || $raw['ques'] == 12 || $raw['ques'] == 21 || $raw['ques'] == 23 || $raw['ques'] == 24) {
							if ($ans != null) {
								$psychoticism_ans += $ans;
							}
						}
					}
				}
				$negative_effect = $negative_effect_ans / 5;
				$detechment = $detechment_ans / 5;
				$antagonism = $antagonism_ans / 5;
				$disinhibition = $disinhibition_ans / 5;
				$psychoticism = $psychoticism_ans / 5;

				$totalScore = $negative_effect_ans + $detechment_ans + $antagonism_ans + $disinhibition_ans + $psychoticism_ans;
				$finalTotalScore = $totalScore / 25;

				$element['negative_effect'] = round($negative_effect);
				$element['detechment'] = round($detechment);
				$element['antagonism'] = round($antagonism);
				$element['disinhibition'] = round($disinhibition);
				$element['psychoticism'] = round($psychoticism);
				$element['finalTotalScore'] = round($finalTotalScore);
			}
		}


		if ($request->input('assesment') != '') {


			$assessArray = [];

			foreach ($enquirys as $raw) {

				if ($request->input('assesment') == 'mild') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 1 && $finalTotalScore <= 2) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'normal') {
					$finalTotalScore = $raw->finalTotalScore;

					if ($finalTotalScore >= 0 && $finalTotalScore <= 1) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'most_vulnerable') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 2 && $finalTotalScore <= 3) {
						$assessArray[] = $raw;
					}
				}
			}
			$enquirys = $assessArray;
			$page = 50;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) {
				$currentPage = $input['page'];
			} else {
				$currentPage = 1;
			}
			$offset = ($currentPage * $page) - $page;
			$itemsForCurrentPage = array_slice($enquirys, $offset, $page, false);
			$enquirys =  new Paginator($itemsForCurrentPage, count($enquirys), $page, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
		}

		if ($request->input('file_type') == "excel") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Status', 'Name', 'Gender', 'Mobile', 'Age', 'Negative Effect ', 'Detachment', 'Antgonism ', 'Disinhibition ', 'Psuchoticism', 'Total Score', 'Assessment Date');
			foreach ($enquirys as $res) {

				if ($res->status == 1) {
					$status = 'Normal';
				}
				if ($res->status == 2) {
					$status = 'Mild';
				}
				if ($res->status == 3) {
					$status = 'Most Vulnerable';
				}


				$ordersDataArray[] = array(
					$i,
					$status,
					$res->name,
					$res->gender,
					$res->mobile,
					$res->age,
					$res->negative_effect,
					$res->detechment,
					$res->antagonism,
					$res->disinhibition,
					$res->psychoticism,
					$res->finalTotalScore,
					date("Y-m-d", strtotime($res->created_at))

				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'assessment.xlsx');
		}



		return view('pages.quiz-master-admin.admin-assessment-list', compact('enquirys', 'orgData'));
	}


	public function counselingListadminlist(Request $request)
	{

		$orgData = OrganizationMaster::select('id', 'pwd', 'title')->get();
		$query = SessionAssesment::with('QuizForm.OrganizationMaster');


		if ($request->input('from_date') != '') {
			$from_date = date("Y-m-d", strtotime($request->input('from_date')));
			$query->whereDate('created_at', '>=', $from_date);
		}
		if ($request->input('to_date') != '') {
			$to_date = date("Y-m-d", strtotime($request->input('to_date')));

			$query->whereDate('created_at', '<=', $to_date);
		}

		if ($request->input('candidate') != '') {
			$candidate = $request->input('candidate');

			if (is_numeric($candidate)) {
				$query->whereHas('QuizForm', function ($query) use ($candidate) {
					$query->where('mobile', $candidate);
				});
			} else {
				$query->whereHas('QuizForm', function ($query) use ($candidate) {
					$query->where('name', 'like', '%' . $candidate . '%');
				});
			}
		}

		if ($request->input('session_status') != '') {
			$session_status = $request->input('session_status');

			$query->where('session_status', $session_status);
		}

		if ($request->input('org_data') != '') {
			$org_data = $request->input('org_data');
			$query->whereHas('QuizForm.OrganizationMaster', function ($query) use ($org_data) {
				$query->where('org_id', $org_data);
			});
		}

		if ($request->input('assesment') != '') {
			$enquirys = $query->get();
		} else {
			$enquirys = $query->paginate(50);
		}

		$arrss = [];
		if (count($enquirys) > 0) {
			foreach ($enquirys as $index => $element) {
				$meta_data = !empty($element->QuizForm->meta_data) ? json_decode($element->QuizForm->meta_data, true) : [];
				$negative_effect = 0;
				$negative_effect_ans = 0;
				$detechment = 0;
				$detechment_ans = 0;
				$antagonism = 0;
				$antagonism_ans = 0;
				$disinhibition = 0;
				$disinhibition_ans = 0;
				$psychoticism = 0;
				$psychoticism_ans = 0;

				if (count($meta_data) > 0) {
					foreach ($meta_data as $raw) {
						$ans = null;
						if ($raw['ans'] == 'optionA') {
							$ans = 0;
						}
						if ($raw['ans'] == 'optionB') {
							$ans = 1;
						}
						if ($raw['ans'] == 'optionC') {
							$ans = 2;
						}
						if ($raw['ans'] == 'optionD') {
							$ans = 3;
						}
						if ($raw['ques'] == 8 || $raw['ques'] == 9 || $raw['ques'] == 10 || $raw['ques'] == 11 || $raw['ques'] == 15) {
							if ($ans != null) {
								$negative_effect_ans += $ans;
							}
						}
						if ($raw['ques'] == 4 || $raw['ques'] == 13 || $raw['ques'] == 14 || $raw['ques'] == 16 || $raw['ques'] == 18) {
							if ($ans != null) {
								$detechment_ans += $ans;
							}
						}
						if ($raw['ques'] == 17 || $raw['ques'] == 19 || $raw['ques'] == 20 || $raw['ques'] == 22 || $raw['ques'] == 25) {
							if ($ans != null) {
								$antagonism_ans += $ans;
							}
						}
						if ($raw['ques'] == 1 || $raw['ques'] == 2 || $raw['ques'] == 3 || $raw['ques'] == 5 || $raw['ques'] == 6) {
							if ($ans != null) {
								$disinhibition_ans += $ans;
							}
						}
						if ($raw['ques'] == 7 || $raw['ques'] == 12 || $raw['ques'] == 21 || $raw['ques'] == 23 || $raw['ques'] == 24) {
							if ($ans != null) {
								$psychoticism_ans += $ans;
							}
						}
					}
				}
				$negative_effect = $negative_effect_ans / 5;
				$detechment = $detechment_ans / 5;
				$antagonism = $antagonism_ans / 5;
				$disinhibition = $disinhibition_ans / 5;
				$psychoticism = $psychoticism_ans / 5;

				$totalScore = $negative_effect_ans + $detechment_ans + $antagonism_ans + $disinhibition_ans + $psychoticism_ans;
				$finalTotalScore = $totalScore / 25;

				$element['id'] = $index + 1;
				$element['negative_effect'] = round($negative_effect);
				$element['detechment'] = round($detechment);
				$element['antagonism'] = round($antagonism);
				$element['disinhibition'] = round($disinhibition);
				$element['psychoticism'] = round($psychoticism);
				$element['finalTotalScore'] = round($finalTotalScore);
			}
		}


		if ($request->input('assesment') != '') {


			$assessArray = [];

			foreach ($enquirys as $raw) {

				if ($request->input('assesment') == 'mild') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 1 && $finalTotalScore <= 2) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'normal') {
					$finalTotalScore = $raw->finalTotalScore;

					if ($finalTotalScore >= 0 && $finalTotalScore <= 1) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'most_vulnerable') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 2 && $finalTotalScore <= 3) {
						$assessArray[] = $raw;
					}
				}
			}
			$enquirys = $assessArray;
			$page = 50;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) {
				$currentPage = $input['page'];
			} else {
				$currentPage = 1;
			}
			$offset = ($currentPage * $page) - $page;
			$itemsForCurrentPage = array_slice($enquirys, $offset, $page, false);
			$enquirys =  new Paginator($itemsForCurrentPage, count($enquirys), $page, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
		}
		if ($request->input('file_type') == "excel") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Negative Effect ', 'Detachment', 'Antgonism ', 'Disinhibition ', 'Psuchoticism', 'Total Score', 'Status');
			foreach ($enquirys as $res) {

				$ordersDataArray[] = array(
					$i,
					$res->QuizForm->name,
					$res->QuizForm->gender,
					$res->QuizForm->mobile,

					$res->QuizForm->age,
					$res->negative_effect,
					$res->detechment,
					$res->antagonism,
					$res->disinhibition,
					$res->psychoticism,
					$res->finalTotalScore,
					$res->status,

				);

				$i++;
			}

			return Excel::download(new QuizFormExport($ordersDataArray), 'assessment.xlsx');
		}


		return view('pages.quiz-master-admin.admin-counseling-list', compact('orgData', 'enquirys'));
	}


	public function adminquizdashboard(Request $request)
	{


		$orgData = null;
		$totalAssessment = null;
		$totalAssessmentToday = null;
		$totalReg = null;
		$chartArray = [];
		$piChartArray = [];
		$wildChart = [];
		$piChartRegArray = [];
		$piChartRegArraycounse = [];

		$orgData = OrganizationMaster::get();
		$totalAssessment = QuizForm::where('meta_data', '!=', null)->count();

		$totalAssessmentPending = QuizForm::where('meta_data', '=', null)->count();
		$totalAssessmentToday = QuizForm::where('meta_data', '!=', null)->whereDate('created_at', '>=', date('y-m-d'))->count();

		$counselingDAta = SessionAssesment::with('QuizForm.OrganizationMaster')->get();
		$counselingDAtatoday = SessionAssesment::with('QuizForm.OrganizationMaster')->whereDate('created_at', '>=', date('y-m-d'))->get();

		$totalAssessmentTodayAdata = QuizForm::with('OrganizationMaster')->where('meta_data', '!=', null)->whereDate('created_at', '>=', date('y-m-d'))->get();

		$totalAssessmentDAta = QuizForm::with('OrganizationMaster')->where('meta_data', '!=', null)->get();

		$totalCouseling = SessionAssesment::count();

		$totalCounselingPending = SessionAssesment::where('session_status', '0')->count();
		$totalCounselingToday = SessionAssesment::whereDate('created_at', '>=', date('y-m-d'))->count();

		$totalfeedback = AssementStuFeedback::count();
		$totalfeedbackdata = AssementStuFeedback::with('QuizForm')->get();

		$totalReg = QuizForm::count();
		$totalRegdata = QuizForm::all();
		$slug = null;
		$quizDatas = $this->getDataBySlug($slug);
		$normal = 0;
		$mild = 0;
		$most_val = 0;
		$negativeA_most_val = 0;
		$detechment_most_val = 0;
		$antagonism_most_val = 0;
		$disinhibition_most_val = 0;
		$psychoticism_most_val = 0;
		$negativeA_mild = 0;
		$detechment_mild = 0;
		$antagonism_mild = 0;
		$disinhibition_mild = 0;
		$psychoticism_mild = 0;
		if (count($quizDatas)) {
			foreach ($quizDatas as $index => $raw) {
				$negative_effect = $raw->negative_effect;
				$detechment = $raw->detechment;
				$antagonism = $raw->antagonism;
				$disinhibition = $raw->disinhibition;
				$psychoticism = $raw->psychoticism;

				if ($negative_effect > 2 && $negative_effect <= 3) {
					$negativeA_most_val++;
				}
				if ($negative_effect > 1 && $negative_effect <= 2) {
					$negativeA_mild++;
				}
				if ($detechment > 2 && $detechment <= 3) {
					$detechment_most_val++;
				}
				if ($detechment > 1 && $detechment <= 2) {
					$detechment_mild++;
				}
				if ($antagonism > 2 && $antagonism <= 3) {
					$antagonism_most_val++;
				}
				if ($antagonism > 1 && $antagonism <= 2) {
					$antagonism_mild++;
				}
				if ($disinhibition > 2 && $disinhibition <= 3) {
					$disinhibition_most_val++;
				}
				if ($disinhibition > 1 && $disinhibition <= 2) {
					$disinhibition_mild++;
				}
				if ($psychoticism > 2 && $psychoticism <= 3) {
					$psychoticism_most_val++;
				}
				if ($psychoticism > 1 && $psychoticism <= 2) {
					$psychoticism_mild++;
				}


				$finalTotalScore = $raw->finalTotalScore;
				if ($finalTotalScore >= 0 && $finalTotalScore <= 1) {
					$normal++;
				}
				if ($finalTotalScore > 1 && $finalTotalScore <= 2) {
					$mild++;
				}
				if ($finalTotalScore > 2 && $finalTotalScore <= 3) {
					$most_val++;
				}
			}
		}

		if ($request->input('file_type') == "registraion") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Negative Effect ', 'Detachment', 'Antgonism ', 'Disinhibition ', 'Psuchoticism', 'Total Score');
			foreach ($totalRegdata as $res) {

				$ordersDataArray[] = array(
					$i,
					$res->name,
					$res->gender,
					$res->mobile,

					$res->age,
					$res->negative_effect,
					$res->detechment,
					$res->antagonism,
					$res->disinhibition,
					$res->psychoticism,
					$res->total_score,

				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'registraion.xlsx');
		}

		if ($request->input('file_type') == "assesment") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Organization', 'Age', 'Negative Effect ', 'Detachment', 'Antgonism ', 'Disinhibition ', 'Psuchoticism', 'Total Score');


			foreach ($totalAssessmentDAta as $res) {


				$ordersDataArray[] = array(
					$i,
					$res->name,
					$res->gender,
					$res->mobile,
					$res->OrganizationMaster->title,
					$res->age,
					$res->negative_effect,
					$res->detechment,
					$res->antagonism,
					$res->disinhibition,
					$res->psychoticism,
					$res->total_score,

				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'assesment.xlsx');
		}

		if ($request->input('file_type') == "todayExcelassessment") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Negative Effect ', 'Detachment', 'Antgonism ', 'Disinhibition ', 'Psuchoticism', 'Total Score');
			foreach ($totalAssessmentTodayAdata as $res) {


				$ordersDataArray[] = array(
					$i,
					$res->name,
					$res->gender,
					$res->mobile,
					$res->OrganizationMaster->title,
					$res->age,
					$res->negative_effect,
					$res->detechment,
					$res->antagonism,
					$res->disinhibition,
					$res->psychoticism,
					$res->total_score,

				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'todayExcelassessment.xlsx');
		}

		if ($request->input('file_type') == "counseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Organization', 'Age', 'Status', 'Negative Effect ', 'Detachment', 'Antgonism ', 'Disinhibition ', 'Psuchoticism', 'Total Score');
			foreach ($counselingDAta as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";
				} else {
					$data = "Done";
				}

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					@$res->QuizForm->gender,
					@$res->QuizForm->mobile,
					$res->QuizForm->OrganizationMaster->title,
					@$res->QuizForm->age,
					$data,
					$res->QuizForm->negative_effect,
					$res->QuizForm->detechment,
					$res->QuizForm->antagonism,
					$res->QuizForm->disinhibition,
					$res->QuizForm->psychoticism,
					$res->QuizForm->total_score,

				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'counseling.xlsx');
		}

		if ($request->input('file_type') == "pnCounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Organization', 'Age', 'Status', 'Negative Effect ', 'Detachment', 'Antgonism ', 'Disinhibition ', 'Psuchoticism', 'Total Score');
			foreach ($counselingDAta as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";


					$ordersDataArray[] = array(
						$i,
						@$res->QuizForm->name,
						@$res->QuizForm->gender,
						@$res->QuizForm->mobile,
						$res->QuizForm->OrganizationMaster->title,
						@$res->QuizForm->age,
						$data,
						$res->QuizForm->negative_effect,
						$res->QuizForm->detechment,
						$res->QuizForm->antagonism,
						$res->QuizForm->disinhibition,
						$res->QuizForm->psychoticism,
						$res->QuizForm->total_score,

					);
				}
				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'pnCounseling.xlsx');
		}

		if ($request->input('file_type') == "todaycounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Organization', 'Age', 'Status', 'Negative Effect ', 'Detachment', 'Antgonism ', 'Disinhibition ', 'Psuchoticism', 'Total Score');
			foreach ($counselingDAtatoday as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";
				} else {
					$data = "Done";
				}

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					@$res->QuizForm->gender,
					@$res->QuizForm->mobile,
					$res->QuizForm->OrganizationMaster->title,
					@$res->QuizForm->age,
					$data,
					$res->QuizForm->negative_effect,
					$res->QuizForm->detechment,
					$res->QuizForm->antagonism,
					$res->QuizForm->disinhibition,
					$res->QuizForm->psychoticism,
					$res->QuizForm->total_score,

				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'todaycounseling.xlsx');
		}

		if ($request->input('file_type') == "conselingfeedback") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Feedback', 'Content', 'Quality', 'Hg Rating');
			foreach ($totalfeedbackdata as $res) {

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					$res->counselor_feedback,
					$res->content,

					$res->quality,
					$res->hg_rating,


				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'conselingfeedback.xlsx');
		}
		// if($request->input('file_type') == "excel-fedbackregistraion") {
		// 	$i=1;
		// 	$ordersDataArray[] = array('Sr. No.','Name','Gender','Mobile','Age','Negative Effect ','Detachment','Antgonism ','Disinhibition ','Psuchoticism','Total Score');
		// 			foreach($enquirys as $res){

		// 				$ordersDataArray[] = array(
		// 					$i,
		// 					$res->name,
		// 					$res->gender,
		// 					$res->mobile,

		// 					$res->age,
		// 					$res->negative_effect,
		// 					$res->detechment,
		// 					$res->antagonism,
		// 					$res->disinhibition,
		// 					$res->psychoticism,
		// 					$res->finalTotalScore,

		// 				);

		// 				$i++;
		// 			}    
		// 	return Excel::download(new QuizFormExport($ordersDataArray), 'assessment.xlsx');
		// }



		$chartArray[] = ['title' => 'Negative Affect', 'tot' => $negativeA_most_val];
		$chartArray[] = ['title' => 'Detachment', 'tot' => $detechment_most_val];
		$chartArray[] = ['title' => 'Antagonism', 'tot' => $antagonism_most_val];
		$chartArray[] = ['title' => 'Disinhibition', 'tot' => $disinhibition_most_val];
		$chartArray[] = ['title' => 'Psychoticism', 'tot' => $psychoticism_most_val];


		$wildChart[] = ['title' => 'Negative Affect', 'tot' => $negativeA_mild];
		$wildChart[] = ['title' => 'Detachment', 'tot' => $detechment_mild];
		$wildChart[] = ['title' => 'Antagonism', 'tot' => $antagonism_mild];
		$wildChart[] = ['title' => 'Disinhibition', 'tot' => $disinhibition_mild];
		$wildChart[] = ['title' => 'Psychoticism', 'tot' => $psychoticism_mild];

		$piChartArray[] = ['title' => 'Normal', 'tot' => $normal];
		$piChartArray[] = ['title' => 'Mild', 'tot' => $mild];
		$piChartArray[] = ['title' => 'Most Vulnerable', 'tot' => $most_val];

		// $piChartRegArray[] = ['title'=>'Total Registered','tot'=>$totalReg];
		$piChartRegArray[] = ['title' => 'Assessment Done', 'tot' => $totalAssessment];
		$piChartRegArray[] = ['title' => 'Pending', 'tot' => $totalAssessmentPending];

		$piChartRegArraycounse[] = ['title' => 'Counseling Done', 'tot' => $totalCouseling];
		$piChartRegArraycounse[] = ['title' => 'Pending', 'tot' => $totalCounselingPending];

		$totChartVal = count($quizDatas);
		// $chartArray = json_encode($chartArray);


		return view('pages.quiz-master-admin.admin-quizdashboard-list', compact('totalAssessment', 'totalReg', 'totalAssessmentToday', 'orgData', 'chartArray', 'totChartVal', 'piChartArray', 'wildChart', 'piChartRegArray', 'totalCouseling', 'totalCounselingPending', 'totalCounselingToday', 'totalfeedback', 'piChartRegArraycounse'));
	}

	public function assessmentfeedback(Request $request)
	{

		$feedbackdata = AssementStuFeedback::with('QuizForm')->paginate(50);


		return view('pages.quiz-master-admin.feedback-list', compact('feedbackdata'));
	}

	public function questAns(Request $request, $id)
	{
		$id = base64_decode($id);
		$enquirys = QuizForm::where('id', $id)->first();

		$name = $enquirys->name;

		$Arradata = json_decode($enquirys->meta_data, true);


		return view('pages.screening.question-answer', compact('Arradata', 'name'));
	}



	public function screeningdashboard(Request $request)
	{

		$orgData = null;
		$totalAssessment = null;
		$totalAssessmentToday = null;
		$totalReg = null;
		$chartArray = [];
		$piChartArray = [];
		$wildChart = [];
		$piChartRegArray = [];
		$cid = null;
		if ($request->input('id') != '') {
			$cid = base64_decode($request->input('id'));
		}


		$totalAssessmentPending = SessionAssesment::where('session_status', '0')->where('is_by_org', 0)->count();
		$totalAssessmentDone = SessionAssesment::where('session_status', '1')->where('is_by_org', 0)->count();
		$totalAssessmentToday = SessionAssesment::whereDate('created_at', '=', date('Y-m-d'))->where('is_by_org', 0)->count();
		$Quiz = QuizForm::select('status')->where('org_id', null)->get();
		$counselingDAta = SessionAssesment::with('QuizForm.OrganizationMaster')->where('is_by_org', 0)->get();
		$query = QuizForm::where('org_id')->select('org_id', null)->where('status', '=', '2');
		$totalReg = SessionAssesment::where('is_by_org', 0)->count();
		$counselingDAtatoday = SessionAssesment::with('QuizForm.OrganizationMaster')->where('is_by_org', 0)->whereDate('created_at', '>=', date('y-m-d'))->get();
		$totalAssessment = SessionAssesment::where('is_by_org', 0)->count();
		$quizDatas = $this->getDataBySlug();
		$grpSession = 0;
		$indSession = 0;
		$parentSession = 0;
		$negativeA_most_val = 0;
		$detechment_most_val = 0;
		$antagonism_most_val = 0;
		$disinhibition_most_val = 0;
		$psychoticism_most_val = 0;
		$negativeA_mild = 0;
		$detechment_mild = 0;
		$antagonism_mild = 0;
		$disinhibition_mild = 0;
		$psychoticism_mild = 0;
		if (count($quizDatas)) {
			foreach ($quizDatas as $index => $raw) {
				$negative_effect = $raw->negative_effect;
				$detechment = $raw->detechment;
				$antagonism = $raw->antagonism;
				$disinhibition = $raw->disinhibition;
				$psychoticism = $raw->psychoticism;

				if ($negative_effect > 2 && $negative_effect <= 3) {
					$negativeA_most_val++;
				}
				if ($negative_effect > 1 && $negative_effect <= 2) {
					$negativeA_mild++;
				}
				if ($detechment > 2 && $detechment <= 3) {
					$detechment_most_val++;
				}
				if ($detechment > 1 && $detechment <= 2) {
					$detechment_mild++;
				}
				if ($antagonism > 2 && $antagonism <= 3) {
					$antagonism_most_val++;
				}
				if ($antagonism > 1 && $antagonism <= 2) {
					$antagonism_mild++;
				}
				if ($disinhibition > 2 && $disinhibition <= 3) {
					$disinhibition_most_val++;
				}
				if ($disinhibition > 1 && $disinhibition <= 2) {
					$disinhibition_mild++;
				}
				if ($psychoticism > 2 && $psychoticism <= 3) {
					$psychoticism_most_val++;
				}
				if ($psychoticism > 1 && $psychoticism <= 2) {
					$psychoticism_mild++;
				}
				$finalTotalScore = $raw->finalTotalScore;
			}
		}
		$chartArray[] = ['title' => 'Negative Affect', 'tot' => $negativeA_most_val];
		$chartArray[] = ['title' => 'Detachment', 'tot' => $detechment_most_val];
		$chartArray[] = ['title' => 'Antagonism', 'tot' => $antagonism_most_val];
		$chartArray[] = ['title' => 'Disinhibition', 'tot' => $disinhibition_most_val];
		$chartArray[] = ['title' => 'Psychoticism', 'tot' => $psychoticism_most_val];


		$wildChart[] = ['title' => 'Negative Affect', 'tot' => $negativeA_mild];
		$wildChart[] = ['title' => 'Detachment', 'tot' => $detechment_mild];
		$wildChart[] = ['title' => 'Antagonism', 'tot' => $antagonism_mild];
		$wildChart[] = ['title' => 'Disinhibition', 'tot' => $disinhibition_mild];
		$wildChart[] = ['title' => 'Psychoticism', 'tot' => $psychoticism_mild];

		$piChartRegArray[] = ['title' => 'Counseling Done', 'tot' => $totalAssessmentDone];
		$piChartRegArray[] = ['title' => 'Pending', 'tot' => $totalAssessmentPending];

		$totChartVal = count($quizDatas);


		foreach ($counselingDAta as $val) {
			if ($val->type == '1') {
				$grpSession++;
			}

			if ($val->type == '2') {
				$indSession++;
			}

			if ($val->type == '3') {
				$parentSession++;
			}
		}
		$piChartArray[] = ['title' => 'Group', 'tot' => $grpSession];
		$piChartArray[] = ['title' => 'Individual', 'tot' => $indSession];
		$piChartArray[] = ['title' => 'Parental', 'tot' => $parentSession];


		if ($request->input('file_type') == "totalcounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Status');
			foreach ($counselingDAta as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";
				} else {
					$data = "Done";
				}

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					@$res->QuizForm->gender,
					@$res->QuizForm->mobile,
					@$res->QuizForm->age,
					$data,


				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'counseling.xlsx');
		}

		if ($request->input('file_type') == "pnCounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Status');
			foreach ($counselingDAta as $res) {

				if ($res->session_status == 0) {


					$ordersDataArray[] = array(
						$i,
						@$res->QuizForm->name,
						@$res->QuizForm->gender,
						@$res->QuizForm->mobile,
						@$res->QuizForm->age,
						"Pending",


					);
				}
				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'pnCounseling.xlsx');
		}

		if ($request->input('file_type') == "dnCounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Status');
			foreach ($counselingDAta as $res) {

				if ($res->session_status == 1) {


					$ordersDataArray[] = array(
						$i,
						@$res->QuizForm->name,
						@$res->QuizForm->gender,
						@$res->QuizForm->mobile,

						@$res->QuizForm->age,
						"Done",


					);
				}
				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'dnCounseling.xlsx');
		}


		if ($request->input('file_type') == "todaycounseling") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Status');
			foreach ($counselingDAtatoday as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";
				} else {
					$data = "Done";
				}

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					@$res->QuizForm->gender,
					@$res->QuizForm->mobile,
					@$res->QuizForm->age,
					$data


				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'todaycounseling.xlsx');
		}


		return view('pages.screening.screening-dashboard', compact('totalAssessment', 'totalReg', 'totalAssessmentToday', 'orgData', 'chartArray', 'totChartVal', 'piChartArray', 'wildChart', 'piChartRegArray', 'totalAssessmentPending', 'totalAssessmentDone', 'cid'));
	}

	public function screencounselinglist(Request $request)
	{

		$query = SessionAssesment::with('QuizForm')->where('is_by_org', 0);

		// $counselingDAta= SessionAssesment::with('QuizForm.OrganizationMaster')->get();
		$cid = null;

		if ($request->input('from_date') != '') {
			$from_date = date("Y-m-d", strtotime($request->input('from_date')));
			$query->whereDate('created_at', '>=', $from_date);
		}
		if ($request->input('to_date') != '') {
			$to_date = date("Y-m-d", strtotime($request->input('to_date')));

			$query->whereDate('created_at', '<=', $to_date);
		}

		if ($request->input('candidate') != '') {
			$candidate = $request->input('candidate');

			if (is_numeric($candidate)) {
				$query->whereHas('QuizForm', function ($query) use ($candidate) {
					$query->where('mobile', $candidate);
				});
			} else {
				$query->whereHas('QuizForm', function ($query) use ($candidate) {
					$query->where('name', $candidate);
				});
			}
		}


		if ($request->input('session_status') != '') {
			$session_status = $request->input('session_status');


			$query->where('session_status', $session_status);
		}



		if ($request->input('assesment') != '') {
			$enquirys = $query->get();
		} else {
			$enquirys = $query->paginate(50);
		}


		$arrss = [];


		if ($request->input('assesment') != '') {


			$assessArray = [];

			foreach ($enquirys as $raw) {

				if ($request->input('assesment') == 'mild') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 1 && $finalTotalScore <= 2) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'normal') {
					$finalTotalScore = $raw->finalTotalScore;

					if ($finalTotalScore >= 0 && $finalTotalScore <= 1) {
						$assessArray[] = $raw;
					}
				}

				if ($request->input('assesment') == 'most_vulnerable') {
					$finalTotalScore = $raw->finalTotalScore;
					if ($finalTotalScore > 2 && $finalTotalScore <= 3) {
						$assessArray[] = $raw;
					}
				}
			}

			$enquirys = $assessArray;
			$page = 50;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) {
				$currentPage = $input['page'];
			} else {
				$currentPage = 1;
			}
			$offset = ($currentPage * $page) - $page;
			$itemsForCurrentPage = array_slice($enquirys, $offset, $page, false);
			$enquirys =  new Paginator($itemsForCurrentPage, count($enquirys), $page, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
		}
		if ($request->input('file_type') == "excel") {
			$i = 1;
			$ordersDataArray[] = array('Sr. No.', 'Name', 'Gender', 'Mobile', 'Age', 'Status');
			foreach ($enquirys as $res) {

				if ($res->session_status == 0) {
					$data = "Pending";
				} else {
					$data = "Done";
				}

				$ordersDataArray[] = array(
					$i,
					@$res->QuizForm->name,
					@$res->QuizForm->gender,
					@$res->QuizForm->mobile,

					@$res->QuizForm->age,
					$data,


				);

				$i++;
			}
			return Excel::download(new QuizFormExport($ordersDataArray), 'Counseling-list.xlsx');
		}


		return view('pages.screening.screencounselinglist', compact('enquirys', 'cid'));
	}

	public function Dashboard(Request $request, $slug)
	{
		/*$totSubss = UsersSubscriptions::with(["PlanPeriods","User.QuizForm"])->where('order_status',1)->whereIn('ref_code',[56,76,65,79,77,78])->whereHas('PlanPeriods', function($q){
          $q->Where('status', 1);
        })->get();
		if($totSubss->count()>0) {
			foreach($totSubss as $raw){
				if(\DB::table('appt_org')->where('mobile',$raw->User->mobile_no)->count() == 0) {
					$organization = null;
					if(in_array($raw->ref_code,[56,76])) {
						$organization = 48;
					}
					else if(in_array($raw->ref_code,[65,79])) {
						$organization = 79;
					}
					else if($raw->ref_code == 77) {
						$organization = 93;
					}
					else if($raw->ref_code == 78){
						$organization = 94;
					}
					\DB::table('appt_org')->insert([
						'name' => $raw->User->first_name." ".$raw->User->last_name,
						'mobile' => $raw->User->mobile_no,
						'age' => get_patient_age($raw->User->dob),
						'gender' => $raw->User->gender,
						'mental_status' => isset($raw->User->QuizForm) ? $raw->User->QuizForm->status : null,
						'organization' => $organization,
						'tot_appt' => 0,
						'updated_at' => $raw->updated_at,
						'created_at' => $raw->created_at,
					]);
				}
			}
		}*/
		/*
		$totReg = 0; $totAppt = 0; $totSubs = 0; $pIds = []; $complaints = []; $appts = []; $totSubsAll = 0; $totApptAll = 0; $totRegAll = 0;
		$orgData = OrganizationMaster::where("slug", $slug)->first();
		// $query = User::select('id','pId');
		$subQuery = UsersSubscriptions::with("PlanPeriods")->where('order_status',1)->whereNotNull("id");
		$subQueryTot = UsersSubscriptions::with("PlanPeriods")->where('order_status',1)->whereNotNull("id");
		$apptQuery = Appointments::with(['UserPP','chiefComplaintsOne'])->whereIn('app_click_status',array(5,6))->where("added_by","!=",24)->where("delete_status",1);
		$apptQueryTot = Appointments::with(['UserPP'])->whereIn('app_click_status',array(5,6))->where("added_by","!=",24)->where("delete_status",1);
		

		if ($request->input('from_date') != '') {
			$from_date=date("Y-m-d", strtotime($request->input('from_date')) );
			// $query->whereDate('created_at','>=', $from_date);
			$apptQuery->whereRaw('date(start) >= ?', [$from_date]);
			$subQuery->whereDate('created_at','>=', $from_date);
		}
		else{
			$from_date=date("Y-m-d");
			// $query->whereDate('created_at','>=', $from_date);
			$apptQuery->whereRaw('date(start) >= ?', [$from_date]);
			$subQuery->whereDate('created_at','>=', $from_date);
		}
		if ($request->input('to_date') != '') {
			$to_date=date("Y-m-d", strtotime($request->input('to_date')) );
			// $query->whereDate('created_at','<=', $to_date);
			$apptQuery->whereRaw('date(start) <= ?', [$to_date]);
			$subQuery->whereDate('created_at','<=', $to_date);
		}
		else{
			$to_date=date("Y-m-d");
			// $query->whereDate('created_at','<=', $to_date);
			$apptQuery->whereRaw('date(start) <= ?', [$to_date]);
			$subQuery->whereDate('created_at','<=', $to_date);
		}
		$appts = $apptQuery->orderBy('id', 'desc')->get();
		

		if ($request->input('location') != '') {
			$location= $request->input('location');
			// $query->where('organization',$location);
			if($location == 48){
				$subQuery->whereIn('ref_code',[56,76]);
			}
			else if($location == 79){
				$subQuery->whereIn('ref_code',[65,79]);
			}
			else if($location == 93){
				$subQuery->where('ref_code',77);
			}
			else if($location == 94){
				$subQuery->where('ref_code',78);
			}
			if(count($appts) > 0) {
				$appointmentsCodes = [];
				foreach($appts as $raw) {
					if(isset($raw->UserPP) && $raw->UserPP->organization == $location){
						$appointmentsCodes[] = $raw;
					}
				}
				$appts = $appointmentsCodes;
			}
		}
		else{
			// $query->whereIn('organization',[48,79,93,94]);
			$subQuery->whereIn('ref_code',[56,76,65,79,77,78]);
			if(count($appts) > 0) {
				$appointmentsCodes = [];
				foreach($appts as $i => $raw) {
					if(isset($raw->UserPP) && in_array($raw->UserPP->organization,[48,79,93,94])) {
						$appointmentsCodes[] = $raw;
					}
				}
				$appts = $appointmentsCodes;
			}
		}
		
		$subQuery->whereHas('PlanPeriods', function($q){
          $q->Where('status', 1);
        });
		
		// $allUsers = $query->orderBy('created_at','desc')->get();
		$totSubs = $subQuery->count();
		$totAppt = count($appts);
		
		$subQueryTot->whereIn('ref_code',[56,76,65,79,77,78])->whereHas('PlanPeriods', function($q){
          $q->Where('status', 1);
        });
		// dd($subQueryTot->toSql());
		$totSubsAll = $subQueryTot->count();
		
		$apptsTot = $apptQueryTot->orderBy('id', 'desc')->get();
		if(count($apptsTot) > 0) {
			$bookingAppt = [];
			foreach($apptsTot as $i => $raw) {
				if(isset($raw->UserPP) && in_array($raw->UserPP->organization,[48,79,93,94])) {
					$totApptAll += 1;
					if(!isset($bookingAppt[$raw->pId])) {
						$totRegAll += 1;
					}
					$bookingAppt[$raw->pId] = $i;
				}
			}
		}
		
		if(count($appts)>0) {
			$bookingUser = [];
			foreach($appts as $i => $raw){
				// if(count($complaints)<20) {
					if(!empty($raw->chiefComplaintsOne)) {
						$chief = !empty($raw->chiefComplaintsOne->data) != '' ? json_decode($raw->chiefComplaintsOne->data,true) : '';
						if(!empty($chief)) {
							$comp = array_column($chief, 'complaint_name');
							$complaints[] = implode(",",$comp);
						}
					}
				// }
				if(!isset($bookingUser[$raw->pId])) {
					$totReg += 1;
				}
				$bookingUser[$raw->pId] = $i;
			}
		}
		$newComp = [];
		if(count($complaints)>0) {
			$complaints = preg_replace('/[^a-zA-Z0-9_ -]/s','',$complaints);
			foreach($complaints as $dta) {
			   $position = strpos($dta,'since');
			   if($position !== false) {
				  $newString = substr($dta, 0, $position);
				  $newComp[] =  trim($newString);
			   }
			   else {
				   $newComp[] =  trim($dta);
			   }
			}
		}
		$complaints = array_count_values($newComp);
		arsort($complaints);
	
		if($totSubs>0) {
			if(empty($request->input('location')) || $request->input('location') == '' ) {
				$totAppt = $totAppt+1;
				$totReg = $totReg+1;
			}
			if($totSubs % 2 != 0) {
				$totSubDiv = round(($totSubs + 1) / 2) ;
			}
			else{
				$totSubDiv = round($totSubs / 2);
			}
			
			$totAppt = $totAppt > 0? $totAppt + $totSubDiv : 0;
			$totReg = $totReg > 0? $totReg + $totSubDiv : 0;
		}
		
		if($totSubsAll>0) {
			if(empty($request->input('location')) || $request->input('location') == '' ) {
				$totApptAll = $totApptAll+1;
				$totRegAll = $totRegAll+1;
			}
			if($totSubsAll % 2 != 0) {
				$totSubDivv = round(($totSubsAll + 1)  / 2);
			}
			else{
				$totSubDivv  = round($totSubsAll / 2);
			}
			$totApptAll = $totApptAll > 0? $totApptAll + $totSubDivv : 0;
			$totRegAll = $totRegAll > 0? $totRegAll + $totSubDivv : 0;
		}*/

		if ($request->isMethod('post')) {
			$cUrl = url("/") . "/dashboard/" . $slug;
			$params = array();
			if (!empty($request->input('from_date'))) {
				$params['from_date'] = base64_encode($request->input('from_date'));
				$cUrl .= "?from_date=" . $params['from_date'];
			}
			if (!empty($request->input('to_date'))) {
				$params['to_date'] = base64_encode($request->input('to_date'));
				$cUrl .= strpos($cUrl, "?") !== false ? "&to_date=" . $params['to_date'] : "?to_date=" . $params['to_date'];
			}
			if (!empty($request->input('location'))) {
				$params['location'] = base64_encode($request->input('location'));
				$cUrl .= strpos($cUrl, "?") !== false ? "&location=" . $params['location'] : "?location=" . $params['location'];
			}
			return \Redirect::to($cUrl);
			// return redirect()->route('stuList',['slug'=>$slug]);
		} else {
			$totReg = 0;
			$totAppt = 0;
			$totSubs = 0;
			$totSubsAll = 0;
			$totApptAll = 0;
			$totRegAll = 0;
			$orgData = OrganizationMaster::where("slug", $slug)->first();
			//Update data from excel
			// $path = public_path('apptDoc.xlsx');
			// $excelLoaded = Excel::toArray([],$path);
			// if(count($excelLoaded[0])>0) {
			// foreach($excelLoaded[0] as $i => $raw) {
			// if($i > 0) {
			// \DB::table('appt_org')->where('mobile',$raw[1])->update([
			// 'tot_appt' => $raw[2]
			// ]);
			// }
			// }
			// }

			// $compss = \DB::table('complaints_org')->get();
			// if(count($compss)>0) {
			// foreach($compss as $raw) {
			// for($i = 1; $i < $raw->tot; $i++) {
			// $mixDate = '2023-04-11 10:03:41';
			// \DB::table('complaints_org')->insert([
			// 'disease' => $raw->disease,
			// 'created_at' => $mixDate,
			// 'updated_at' => $mixDate
			// ]);
			// }
			// }
			// }

			$compQuery = \DB::table('complaints_org')->selectRaw("SUM(tot) as tot,disease");
			$subQuery = \DB::table('appt_org');
			$query = \DB::table('appt_org')->where('tot_appt', '!=', 0);
			if ($request->input('from_date') != '') {
				$from_date = date("Y-m-d", strtotime(base64_decode($request->input('from_date'))));
				$subQuery->whereDate('created_at', '>=', $from_date);
				$query->whereDate('created_at', '>=', $from_date);
			} else {
				$from_date = date("Y-m-d");
				$subQuery->whereDate('created_at', '>=', $from_date);
				$query->whereDate('created_at', '>=', $from_date);
			}
			if ($request->input('to_date') != '') {
				$to_date = date("Y-m-d", strtotime(base64_decode($request->input('to_date'))));
				$subQuery->whereDate('created_at', '<=', $to_date);
				$query->whereDate('created_at', '<=', $to_date);
			} else {
				$to_date = date("Y-m-d");
				$subQuery->whereDate('created_at', '<=', $to_date);
				$query->whereDate('created_at', '<=', $to_date);
			}
			if ($request->input('location') != '') {
				$location = base64_decode($request->input('location'));
				$subQuery->where('organization', $location);
				$query->where('organization', $location);
				$compQuery->where('organization', $location);
			}
			$complaints = $compQuery->groupBy('disease')->orderBy('tot', 'DESC')->get();
			$totAppt = $subQuery->sum('tot_appt');
			$totReg = $query->count();

			$subsData = $subQuery->get();
			$totSubs = count($subsData);
			$totSubsAll = \DB::table('appt_org')->count();
			$totApptAll = \DB::table('appt_org')->sum('tot_appt');
			$totRegAll = \DB::table('appt_org')->where('tot_appt', '!=', 0)->count();
		}
		return view('pages.quiz-files.dashboard-org', compact('totAppt', 'totSubs', 'orgData', 'totReg', 'complaints', 'totSubsAll', 'totApptAll', 'totRegAll'));
	}


	// public function stuList(Request $request, $slug)
	// {
	// 	if ($request->isMethod('post')) {
	// 		$cUrl = url("/") . "/student-list/" . $slug;
	// 		$params = array();
	// 		if (!empty($request->input('from_date'))) {
	// 			$params['from_date'] = base64_encode($request->input('from_date'));
	// 			$cUrl .= "?from_date=" . $params['from_date'];
	// 		}
	// 		if (!empty($request->input('to_date'))) {
	// 			$params['to_date'] = base64_encode($request->input('to_date'));
	// 			$cUrl .= strpos($cUrl, "?") !== false ? "&to_date=" . $params['to_date'] : "?to_date=" . $params['to_date'];
	// 		}
	// 		if (!empty($request->input('location'))) {
	// 			$params['location'] = base64_encode($request->input('location'));
	// 			$cUrl .= strpos($cUrl, "?") !== false ? "&location=" . $params['location'] : "?location=" . $params['location'];
	// 		}
	// 		if (!empty($request->input('status'))) {
	// 			$params['status'] = base64_encode($request->input('status'));
	// 			$cUrl .= strpos($cUrl, "?") !== false ? "&status=" . $params['status'] : "?status=" . $params['status'];
	// 		}
	// 		if (!empty($request->input('file_type'))) {
	// 			$params['file_type'] = base64_encode($request->input('file_type'));
	// 			$cUrl .= strpos($cUrl, "?") !== false ? "&file_type=" . $params['file_type'] : "?file_type=" . $params['file_type'];
	// 		}
	// 		return \Redirect::to($cUrl);
	// 		// return redirect()->route('stuList',['slug'=>$slug]);
	// 	} else {
	// 		$orgData = OrganizationMaster::where("slug", $slug)->first();
	// 		/*$subQuery = UsersSubscriptions::with("PlanPeriods","User.QuizForm","User.ApptOrg")->where('order_status',1)->whereNotNull("id");
	// 		if ($request->input('from_date') != '') {
	// 			$from_date=date("Y-m-d", strtotime(base64_decode($request->input('from_date'))));
	// 			$subQuery->whereDate('created_at','>=', $from_date);
	// 		}
	// 		else{
	// 			$from_date=date("Y-m-d");
	// 			$subQuery->whereDate('created_at','>=', $from_date);
	// 		}
	// 		if ($request->input('to_date') != '') {
	// 			$to_date=date("Y-m-d", strtotime(base64_decode($request->input('to_date'))));
	// 			$subQuery->whereDate('created_at','<=', $to_date);
	// 		}
	// 		else{
	// 			$to_date=date("Y-m-d");
	// 			$subQuery->whereDate('created_at','<=', $to_date);
	// 		}
	// 		if ($request->input('location') != '') {
	// 			$location= base64_decode($request->input('location'));
	// 			if($location == 48){
	// 				$subQuery->whereIn('ref_code',[56,76]);
	// 			}
	// 			else if($location == 79){
	// 				$subQuery->whereIn('ref_code',[65,79]);
	// 			}
	// 			else if($location == 93){
	// 				$subQuery->where('ref_code',77);
	// 			}
	// 			else if($location == 94){
	// 				$subQuery->where('ref_code',78);
	// 			}
	// 		}
	// 		else{
	// 			$subQuery->whereIn('ref_code',[56,76,65,79,77,78]);
	// 		}
	// 		if ($request->input('status') != '') {
	// 			$status = base64_decode($request->input('status'));
	// 			$subQuery->whereHas('User.QuizForm', function($q) use($status) {
	// 			  $q->Where('status', $status);
	// 			});
	// 		}
	// 		$file_type = base64_decode($request->input('file_type'));
	// 		if($file_type == "excel") {
	// 			$queryData = $subQuery->orderBy('id', 'desc')->get();
	// 			$i=1;
	// 		$ordersDataArray[] = array('Sr. No.','Name','Mental Health Status','Gender','Mobile','Age','Date','Total Appointments');
	// 				foreach($queryData as $raw){
	// 					$mSts = "N/A";
	// 					if(isset($raw->User->QuizForm)){ 
	// 						if($raw->User->QuizForm->status==1){
	// 							$mSts = "Normal";
	// 						}
	// 						elseif($raw->User->QuizForm->status==2){
	// 							$mSts = "Mild";
	// 						}
	// 						elseif($raw->User->QuizForm->status==3){
	// 							$mSts = "Most Vulnerable";
	// 						}
	// 					}
	// 					$ordersDataArray[] = array(
	// 						$i,
	// 						@$raw->User->first_name." ".@$raw->User->last_name,
	// 						$mSts,
	// 						@$res->User->gender,
	// 						'******'.substr($raw->User->mobile_no,6),
	// 						get_patient_age($raw->User->dob),
	// 						date('d-m-Y',strtotime($raw->created_at)),
	// 						$raw->User->tot_appointment
	// 					);
	// 					$i++;
	// 				}    
	// 		return Excel::download(new QuizFormExport($ordersDataArray), 'students.xlsx');
	// 		}
	// 		$subs = $subQuery->paginate(50);*/

	// 		$subQuery = \DB::table('appt_org');
	// 		if ($request->input('from_date') != '') {
	// 			$from_date = date("Y-m-d", strtotime(base64_decode($request->input('from_date'))));
	// 			$subQuery->whereDate('created_at', '>=', $from_date);
	// 		} else {
	// 			$from_date = date("Y-m-d");
	// 			$subQuery->whereDate('created_at', '>=', $from_date);
	// 		}
	// 		if ($request->input('to_date') != '') {
	// 			$to_date = date("Y-m-d", strtotime(base64_decode($request->input('to_date'))));
	// 			$subQuery->whereDate('created_at', '<=', $to_date);
	// 		} else {
	// 			$to_date = date("Y-m-d");
	// 			$subQuery->whereDate('created_at', '<=', $to_date);
	// 		}
	// 		if ($request->input('location') != '') {
	// 			$location = base64_decode($request->input('location'));
	// 			$subQuery->where('organization', $location);
	// 		}
	// 		if ($request->input('status') != '') {
	// 			$status = base64_decode($request->input('status'));
	// 			$subQuery->where('mental_status', $status);
	// 		}
	// 		$file_type = base64_decode($request->input('file_type'));
	// 		if ($file_type == "excel") {
	// 			$queryData = $subQuery->orderBy('id', 'desc')->get();
	// 			$i = 1;
	// 			$ordersDataArray[] = array('Sr. No.', 'Name', 'Mental Health Status', 'Gender', 'Mobile', 'Age', 'Date', 'Total Appointments');
	// 			foreach ($queryData as $raw) {
	// 				$mSts = "N/A";
	// 				if ($raw->mental_status == 1) {
	// 					$mSts = "Normal";
	// 				} elseif ($raw->mental_status == 2) {
	// 					$mSts = "Mild";
	// 				} elseif ($raw->mental_status == 3) {
	// 					$mSts = "Most Vulnerable";
	// 				}
	// 				$ordersDataArray[] = array(
	// 					$i,
	// 					@$raw->name,
	// 					$mSts,
	// 					@$raw->gender,
	// 					'*******' . substr($raw->mobile, 7),
	// 					$raw->age,
	// 					date('d-m-Y', strtotime($raw->created_at)),
	// 					$raw->tot_appt
	// 				);
	// 				$i++;
	// 			}
	// 			return Excel::download(new QuizFormExport($ordersDataArray), 'students.xlsx');
	// 		}
	// 		$subs = $subQuery->paginate(50);
	// 		return view('pages.quiz-files.student-list', compact('subs', 'orgData'));
	// 	}
	// }


	

public function orgStudentListAll(Request $request, $slug)
{
    $orgData = OrganizationMaster::where('slug', $slug)->first();

    $type = $request->input('type', 'users'); // Default to 'users'
    $search = $request->input('search', '');

    $perPage = 10;

    if ($type == 'users') {
        $query = User::where('organization', $orgData->id);
        if (!empty($search)) {
            $query->where('mobile_no', 'LIKE', "%{$search}%");
        }
    } else {
        $query = Student::where('org_id', $orgData->id);
        if (!empty($search)) {
            $query->where('student_id', 'LIKE', "%{$search}%");
        }
    }

    $pagination = $query->paginate($perPage);

    return view('pages.quiz-files.org-student-list-all', compact('pagination', 'type', 'orgData'));
}

	public function orgStudentList(Request $request, $slug)
	{
		$orgData = OrganizationMaster::where('slug', $slug)->first();
		$page = 20; // Number of items per page

		// Retrieve users associated with the organization
		$users = User::where('organization', $orgData->id)->get();

		// Retrieve students associated with the organization

		// Combine the two collections and sort by 'created_at' (assuming both models have this column)
		// $combined = $users->merge($students);


		// Manually paginate the combined collection
		$currentPage = LengthAwarePaginator::resolveCurrentPage();
		$items = $users->slice(($currentPage - 1) * $page, $page)->values();
		$pagination = new LengthAwarePaginator(
			$items,
			$users->count(),
			$page,
			$currentPage,
			['path' => $request->url(), 'query' => $request->query()]
		);


		return view('pages.quiz-files.org-student-list', compact('pagination', 'orgData'));
	}

	public function bookAppointment(Request $request, $slug)
	{
		$data = json_decode(base64_decode($request->query('params')), true);
		$orgData = OrganizationMaster::where('slug', $slug)->first();
		

		$type = $data['type'];
		if ($type === 'students') {

			if (empty($data['user_id'])) {
				return redirect()->back()->withErrors(['error' => 'Student ID is required.']);
			}

			$student = Student::where('id', $data['user_id'])->first();
			$studentID = $student->student_id;
			
			if (is_null($student)) {
				return redirect()->back()->withErrors(['error' => 'Student not found.']);
			}


			$user = User::firstOrCreate(
				['student_id' => $student->id],
				[
					'parent_id' => 0,
					'status' => 1,
					'device_type' => 3,
					'login_type' => 2,
					'is_login' => 1,
					'notification_status' => 1,
				]
			);
			return view('pages.quiz-files.instant-appointment-book', compact('user', 'type', 'studentID', 'orgData'));
		} elseif ($type === 'users') {

			if (empty($data['user_id'])) {
				return redirect()->back()->withErrors(['error' => 'User ID is required.']);
			}

			$user = User::find($data['user_id']);

			if (is_null($user)) {
				return redirect()->back()->withErrors(['error' => 'User not found.']);
			}
			return view('pages.quiz-files.instant-appointment-book', compact('user', 'type', 'orgData'));
		} else {

			return redirect()->back()->withErrors(['error' => 'Invalid type specified.']);
		}

		
	}

	public function slotBook(Request $request)
	{


		$data = $request->all();
		
		\Log::info($data);
		
		// Extract the full name
		$fullName = trim($data['patient_name']);

		// Split the name into parts
		$nameParts = explode(' ', $fullName);

		// Handle cases based on the number of words in the name
		if (count($nameParts) === 1) {
			$firstName = $nameParts[0];
			$lastName = '';
		} else {
			// Get the last name (last word)
			$lastName = array_pop($nameParts);

			// Combine the remaining parts as the first name
			$firstName = implode(' ', $nameParts);
		}

		if($data['type'] == 'students') {
			$orgId = Student::where('student_id', $data['student_id'])->first();
			$user = User::where('id', $data['pId'])->update([
				'first_name' => $firstName,
				'last_name' => $lastName,
				'dob' => $data['age'],
	
			]);
		} else {
			$user = User::where('id', $data['pId'])->update([
				'first_name' => $firstName,
				'last_name' => $lastName,
				'mobile_no' => $data['mobile_no'],
				'dob' => $data['age'],
	
			]);
		}
		$user = User::where('id', $data['pId'])->first();
		$user_array['order_by']   = $user->id;
		$user_array['doc_id']   =  getSetting("direct_appt_doc_id")[0];
		$docData = Doctors::select(["user_id", "consultation_fees", "oncall_fee", "slot_duration", "first_name", "last_name"])->where(['id' => $user_array['doc_id']])->first();
		Log::info('$docData', [$docData]);
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
		$user_array['gender'] = $user->gender ?? $data['gender'];
		$user_array['patient_name'] = $user->first_name . " " . $user->last_name;
		$user_array['dob'] = date('d-m-Y', $user->dob);
		$user_array['mobile_no'] = @$user->mobile_no;
		$user_array['student_id'] = @$data['student_id'];
		$user_array['other_mobile_no'] = @$user->other_mobile_no;
		$user_array['otherPatient'] = 0;
		$user_array['coupon_id'] = null;
		$user_array['coupon_discount'] = null;
		$user_array['call_type'] = 1;
		$user_array['referral_code'] = null;
		$user_array['is_peak'] = 0;
		$user_array['org_id'] = @$orgId->org_id;
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
		Log::info('$order------', [$order]);
		if($data['type'] == 'users') {
			$appId = $this->putAppointmentDataApp($order, '', '');
		} else {
			$appId = $this->putAppointmentDataStudent($order, '', '');
		}
		
		Log::info('$appId', [$appId]);
		//$redirectUrl =  url("/") . '/appointment/success';
		return redirect()->route('appointmentOrderSuccess');
		
		//return  response()->json(['success' => 1]); 
		// $slug = Session::get('OrganizationMaster.slug');
		// return redirect()->route('healthAssesAdmin', ['slug' => $slug]);

	}

	public function subscription(Request $request, $slug)
	{
		$orgData = OrganizationMaster::where('slug', $slug)->first();
		$sess = Session::get('OrganizationMaster.id');


		$search = '';
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			
			if (!empty($request->input('candidate'))) {
				$params['candidate'] = base64_encode($request->input('candidate'));
			}
			
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			
			return redirect()->route('org.subscription', ['slug' => Session::get('organizationMaster.slug')] + $params)->withInput();
		} else {
			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			
			$query = UsersSubscriptions::with(["PlanPeriods.Plans", "UserSubscribedPlans.PlanPeriods", "User", "ReferralMaster"])->where(['organization_id' => 4, 'order_status' => 1])->whereNotNull("id");


			if ($request->input('search')  != '') {
				$search = base64_decode($request->input('search'));
				$query->where('plan_title', 'like', '%' . $search . '%');
			}
			if ($request->input('candidate')  != '') {
				$candidate = base64_decode($request->input('candidate'));
				$query->whereHas('User', function ($que) use ($candidate) {
					$que->where('mobile_no', $candidate);
				});
			}
			

			
			$subscriptionArray = array();
			$subscriptions = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('pages.quiz-files.org-student-list', compact('subscriptions', 'orgData'));
	}

	public function hgAppointmentsInstitute(Request $request, $slug)
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
			if (!empty($request->input('user_id'))) {
				$params['user_id'] = base64_encode($request->input('user_id'));
			}
			if (!empty($request->input('app_type'))) {
				$params['app_type'] = base64_encode($request->input('app_type'));
			}
			if (!empty($request->input('type'))) {
				$params['type'] = base64_encode($request->input('type'));
			}
			if ($request->input('pay_sts') != "") {
				$params['pay_sts'] = base64_encode($request->input('pay_sts'));
			}
			if ($request->input('app_from') != "") {
				$params['app_from'] = base64_encode($request->input('app_from'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			if (!empty($request->input('file_type'))) {
				$params['file_type'] = base64_encode($request->input('file_type'));
			}
			if ($request->input('pres_type') != "") {
				$params['pres_type'] = base64_encode($request->input('pres_type'));
			}
			if ($request->input('today_appt') != "") {
				$params['today_appt'] = base64_encode($request->input('today_appt'));
			}
			if ($request->input('date_type') != "") {
				$params['date_type'] = base64_encode($request->input('date_type'));
			}
			if ($request->input('id') != "") {
				$params['id'] = base64_encode($request->input('id'));
			}
			
			if ($request->input('appintmentstatus') != "") {
				$params['appintmentstatus'] = base64_encode($request->input('appintmentstatus'));
			}
			if ($request->input('lab_status') != "") {
				$params['lab_status'] = base64_encode($request->input('lab_status'));
			}
			if ($request->input('dia_status') != "") {
				$params['dia_status'] = base64_encode($request->input('dia_status'));
			}
			if ($request->input('candidate') != "") {
				$params['candidate'] = base64_encode($request->input('candidate'));
			}
			
			
			return redirect()->route('institute.hgAppointmentsInstitute', ['slug' => Session::get('organizationMaster.slug')] + $params)->withInput();

		} else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$pay_sts = base64_decode($request->input('pay_sts'));
			$app_from = base64_decode($request->input('app_from'));
			$date_type = base64_decode($request->input('date_type'));
			$file_type = base64_decode($request->input('file_type'));
			$status = base64_decode($request->input('appintmentstatus'));
			$pId = base64_decode($request->input('id'));
			$candidate = base64_decode($request->input('candidate'));
			

			$orgData = OrganizationMaster::where('slug', $slug)->first();
			$query = Appointments::with([
				'AppointmentTxn', 
				'AppointmentOrder.PlanPeriods',  
				'Patient', 
				'NotifyUserSms', 
				'Doctors.DoctorData', 
				'UserPP.OrganizationMaster', 
				'PatientLabs.labs', 
				'PatientLabs.LabPack', 
				'chiefComplaints', 
				'PatientLabsOne', 
				'PatientDiagnosticImagings', 
				'UserPP.UsersSubscriptions.ReferralMaster'
			])
			->whereIn('app_click_status', [5, 6])
			->where('added_by', '!=', 24)
			->where('delete_status', 1)->limit(50);
			$query->whereHas('Patient', function ($query) use ($orgData) {
				if ($orgData) { // Ensure $orgData is not null
					$query->where('org_id', $orgData->id);
				}
			});
			
			
			
			
			
			if (!empty($search)) {
				$query->whereHas('Patient', function ($que) use ($search) {
					$que->where(DB::raw('concat(IFNULL(first_name,"")," ",IFNULL(last_name,"")," ",IFNULL(mobile_no,""))'), 'like', '%' . $search . '%');
				});
			}
			if (!empty($candidate)) {
			    $query->where(function ($q) use ($candidate) {
				$q->whereHas('Patient', function ($que) use ($candidate) {
				    $que->where('mobile_no', $candidate);
				})->orWhereHas('Patient', function ($que) use ($candidate) {
				    $que->where('student_id', $candidate);
				});
			    });
			}

			if (!empty($status) && $status == '1') {
				$query->where('working_status', '=', NULL);
			}
			if (!empty($status) && $status != '1') {
				$query->where('working_status->status', '=', $status);
			}
			if (!empty($pId)) {
				$p_ids = User::select("pId")->where(["parent_id" => $pId])->pluck("pId")->toArray();
				array_push($p_ids, $pId);
				$query->whereIn('pId', $p_ids);
			}
			
				

			$page = 25;
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}

			$appointmentData = $query->orderBy('id', 'desc')->get();
			$appointments = [];

			foreach ($appointmentData as $i => $element) {
				$appointmentIds = [];
				if (isset($element->AppointmentOrder->PlanPeriods) && count($element->AppointmentOrder->PlanPeriods) > 0) {
					$appointment_ids = "";
					foreach ($element->AppointmentOrder->PlanPeriods as $val) {
						$appointment_ids .= $val->appointment_ids . ",";
					}
					$appointmentIds = [];
					if (!empty($appointment_ids)) {
						$appointmentIds = explode(",", $appointment_ids);
					}
				}
				if ($pay_sts == '1') {
					if (!empty($element->AppointmentTxn) && @$element->AppointmentOrder->type == "1") {
						$appointments[] = $element;
					} else if (empty($element->AppointmentOrder)) {
						$appointments[] = $element;
					}
				} else if ($pay_sts == '2') {
					if (@$element->AppointmentOrder->type == "0" && in_array($element->id, $appointmentIds) == false) {
						$appointments[] = $element;
					}
				} else if ($pay_sts == '4') {
					if (@$element->AppointmentOrder->type == "0" && in_array($element->id, $appointmentIds) == true) {
						$appointments[] = $element;
					}
				} else if ($pay_sts == '3') {
					if (@$element->AppointmentOrder->type == "2") {
						$appointments[] = $element;
					}
				} else {
					$appointments[] = $element;
				}
			}

			if (count($appointments) > 0 && !empty($app_from)) {
				$appointmentsArr = [];
				foreach ($appointments as $raw) {
					if ($app_from == '1') {
						if ($raw->app_click_status == '6') {
							if (!empty($raw->AppointmentOrder) && !empty($raw->AppointmentOrder->meta_data)) {
								$meta_data = json_decode($raw->AppointmentOrder->meta_data);
								if (isset($meta_data->isPaytmTab) && $meta_data->isPaytmTab == "false") {
									$appointmentsArr[] = $raw;
								}
								if (!isset($meta_data->isPaytmTab)) {
									$appointmentsArr[] = $raw;
								}
							}
							if (empty($raw->AppointmentOrder)) {
								$appointmentsArr[] = $raw;
							}
							if (!empty($raw->AppointmentOrder) && empty($raw->AppointmentOrder->meta_data)) {
								$appointmentsArr[] = $raw;
							}
						}
					} else if ($app_from == '2') {
						if ($raw->app_click_status == '5') {
							$appointmentsArr[] = $raw;
						}
					} else if ($app_from == '3') {
						if ($raw->app_click_status == '6' && !empty($raw->AppointmentOrder) && !empty($raw->AppointmentOrder->meta_data)) {
							$meta_data = json_decode($raw->AppointmentOrder->meta_data);
							if (isset($meta_data->isPaytmTab) && $meta_data->isPaytmTab == "true") {
								$appointmentsArr[] = $raw;
							}
						}
					}
				}
				$appointments = $appointmentsArr;
			}

			if (count($appointments) > 0 && !empty($request->input('type'))) {
				if (base64_decode($request->input('type')) == '3') {
					$appointmentsArr = [];
					foreach ($appointments as $raw) {
						if (checkAppointmentIsElite($raw->id, @$raw->AppointmentOrder->order_by) == 1) {
							$appointmentsArr[] = $raw;
						}
					}
					$appointments = $appointmentsArr;
				}
			}
			if (count($appointments) > 0 && $request->input('code')  != '') {
				$appointmentsCodes = [];
				$code = base64_decode($request->input('code'));
				foreach ($appointments as $raw) {
					if ($code == 9 && @$raw->AppointmentOrder->hg_miniApp == 1) {
						$appointmentsCodes[] = $raw;
					} elseif ($code == 10 && @$raw->AppointmentOrder->hg_miniApp == 2) {
						$appointmentsCodes[] = $raw;
					} elseif (isset($raw->UserPP) && $raw->UserPP->organization == $code) {
						$appointmentsCodes[] = $raw;
					}
				}
				$appointments = $appointmentsCodes;
			}
			if (count($appointments) > 0 && $request->input('lab_status')  != '') {
				$appointmentsLabs = [];
				$lab_status = base64_decode($request->input('lab_status'));
				foreach ($appointments as $raw) {
					if ($lab_status == 1 && !empty($raw->PatientLabsOne)) {
						$appointmentsLabs[] = $raw;
					} elseif ($lab_status == 2 && empty($raw->PatientLabsOne)) {
						$appointmentsLabs[] = $raw;
					}
				}
				$appointments = $appointmentsLabs;
			}
			if (count($appointments) > 0 && $request->input('dia_status')  != '') {
				$appointmentsDia = [];
				$dia_status = base64_decode($request->input('dia_status'));
				foreach ($appointments as $raw) {
					if ($dia_status == 1 && !empty($raw->PatientDiagnosticImagings)) {
						$appointmentsDia[] = $raw;
					} elseif ($dia_status == 2 && empty($raw->PatientDiagnosticImagings)) {
						$appointmentsDia[] = $raw;
					}
				}
				$appointments = $appointmentsDia;
			}
			if (count($appointments) > 0 && $request->input('by_speciality')  != '') {
				$appointmentsBySpecialist = [];
				$by_speciality = base64_decode($request->input('by_speciality'));
				foreach ($appointments as $raw) {
					if (isset($raw->User->DoctorInfo->docSpeciality) && $raw->User->DoctorInfo->docSpeciality->id == $by_speciality) {
						$appointmentsBySpecialist[] = $raw;
					}
				}
				$appointments = $appointmentsBySpecialist;
			}
			$perPage = 25;
			$input = Input::all();
			if (isset($input['page']) && !empty($input['page'])) {
				$currentPage = $input['page'];
			} else {
				$currentPage = 1;
			}
			$offset = ($currentPage * $page) - $page;
			$itemsForCurrentPage = array_slice($appointments, $offset, $page, false);
			$appointments =  new Paginator($itemsForCurrentPage, count($appointments), $page, Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
		}
		$practices = Doctors::select(['first_name', 'last_name', 'email', 'consultation_fees', 'oncall_fee', 'user_id'])->with("docSpeciality")->where(["delete_status" => 1, "hg_doctor" => 1, "claim_status" => 1, "varify_status" => 1])->orderBy("id", "ASC")->get();
	
		
		// Log::info('Appointment Error:',[$practices]);
		return view('pages.quiz-files.appointment-list', compact('appointments', 'practices','orgData'));
	}
}

