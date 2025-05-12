<?php
namespace App\Http\Controllers\API23MAR2023;

use App\Http\Controllers\API23MAR2023\APIBaseController as APIBaseController;
use Illuminate\Http\Request;
use Validator;
use App\Models\Users;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Request as Input;
use App\Models\AssesmentAnswer;
use App\Models\PreAssessmentQues;
use App\Models\PreAssesmentAnswer;
use App\Models\MhQuesRange;
use App\Models\AssessmentOverview;
use App\Models\MhWeeklyProgram;
use App\Models\MhProgramMatrix;
use App\Models\MhMood;
use App\Models\MhWeeklyTask;
use App\Models\MhJournal;
use App\Models\MhCommonAudio;
use App\Models\Admin\Symptoms;
use App\Models\Quizquestion;
use App\Models\User;
use App\Models\ehr\AppointmentOrder;
use App\Models\ehr\Appointments;
use App\Models\MhTracker;
use App\Models\MhCommonSheet;
use App\Models\MhSheetData;
use App\Models\MhWpFeedback;
use App\Models\UsersSubscriptions;
use App\Models\MhJournalThought;
use App\Models\Plans;
use App\Models\UserPrescription;
use App\Models\NewsFeeds;
use App\Models\LabOrders;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use Hash;
use DB;
use URL;
use File;
use Mail;
use PDF;

class MentalHealthController extends APIBaseController {
   	function searchSymptoms(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['search_key'] = $data->get('search_key');
		$user_array['lng'] = $data->get('lng');
		$user_array['user_id'] = $data->get('user_id');
		$symptoms = [];
		$success = false;
		if($user_array['search_key'] != '') {
			$search_key = trim(strtolower($user_array['search_key']));			
			if($user_array['lng'] == "hi") {
				$symptomps_query = Symptoms::with(['SymptomsSpeciality','SymptomTags'])->Where(['status'=>1])->Where('symptom_hindi', 'like', '%'.$search_key.'%');
				$symptomps_query->OrWhereHas("SymptomTags",function($qry) use($search_key) {
						$qry->Where('text','like','%'.$search_key.'%');
				});
				$symptoms = $symptomps_query->limit(20)->get();
			}
			else{
				$symptomps_query = Symptoms::with(['SymptomsSpeciality','SymptomTags'])->Where(['status'=>1])->Where('symptom', 'like', '%'.$search_key.'%');
				$symptomps_query->OrWhereHas("SymptomTags",function($qry) use($search_key) {
						$qry->Where('text','like','%'.$search_key.'%');
				});
				$symptoms = $symptomps_query->limit(20)->get();
			}
			if(count($symptoms) > 0) {
				$success = true;
			}
			return $this->sendResponse($symptoms, 'Symptoms get Successfully.',$success);
		}
	}

	public function showSympHighlight(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['slug'] = $data->get('slug');
		$user_array['lng'] = $data->get('lng');
		$user_array['user_id'] = $data->get('user_id');

		$symptom = (($user_array['lng'] == "hi") ? 'symptom_hindi as symptom' : 'symptom');
		$description = (($user_array['lng'] == "hi") ? 'description_hindi as description' : 'description');
		$treatment = (($user_array['lng'] == "hi") ? 'treatment_hindi as treatment' : 'treatment');
		$cause = (($user_array['lng'] == "hi") ? 'cause_hindi as cause' : 'cause');
		$strategy = (($user_array['lng'] == "hi") ? 'strategy_hindi as strategy' : 'strategy');
		$assess_program = (($user_array['lng'] == "hi") ? 'assess_program_hindi as assess_program' : 'assess_program');
		$symp_details = (($user_array['lng'] == "hi") ? 'symp_details_hindi as symp_details' : 'symp_details');
		$pointerSymp = Symptoms::select('id','icon',$symptom,$description,$treatment,$cause,$strategy,$assess_program,$symp_details)->with(['SymptomsSpeciality','SymptomTags'])->where('mh_status',1)->Where(['status'=>1])->get();

		if($pointerSymp->count()>0) {
			foreach($pointerSymp as $raw) {
				$raw['is_quiz_done'] = 0;
				// if($raw->MhWeeklyProgram->count()>0) {
				// 	foreach($raw->MhWeeklyProgram as $col) {
				// 		$weeklyPArr = [];
				// 		if($col->AssessmentOverview->count() > 0) {
				// 			foreach($col->AssessmentOverview as $line) {
				// 				$line['audio_file'] = getPath("public/mh-audio-files/".$line->audio_file);
				// 			}
				// 		}
				// 		$col['icon'] = getPath("public/mh-weekly-icons/".$col->icon);
				// 	}
				// }
				$assessment =  AssesmentAnswer::where(['user_id'=>$user_array['user_id'],'symp_id'=>$raw->id])->orderBy('created_at','ASC')->count();
				if($assessment > 0) {
					$raw['is_quiz_done'] = 1;
				}
			}
		}
		if(!empty($user_array['lng'])) {
			$page = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug']])->where("lng",$user_array['lng'])->first();
		}
		else {
			$page = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug']])->where("lng","en")->first();
		}
		$mArr = ['pointerSymp'=>$pointerSymp,'page'=>$page];
		return $this->sendResponse($mArr, '',true);
    }

	public function fetchAssesmentQues(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		$user_array['symp_id'] = $data->get('symp_id');
		if($user_array['lng'] == 'en'){
			$ques = Quizquestion::select('id','question as ques','optionA','optionB','optionC','optionD','optionE','optionF','optionA_val','optionB_val','optionC_val','optionD_val','optionE_val','optionF_val')->where('symptom_id',$user_array['symp_id'])->get();
		}
		else{
			$ques = Quizquestion::select('id','question_hindi as ques','optionA_hindi as optionA','optionB_hindi as optionB','optionC_hindi as optionC','optionD_hindi as optionD','optionE_hindi as optionE','optionF_hindi as optionF','optionA_val','optionB_val','optionC_val','optionD_val','optionE_val','optionF_val')->where('symptom_id',$user_array['symp_id'])->get();
		}
		return $this->sendResponse($ques, '',true);
    }

	public function fetchPreAssesmentQues(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		if($user_array['lng'] == 'en'){
			$ques = PreAssessmentQues::select('id','question as ques','optionA','optionB','optionC','optionD','optionE')->get();
		}
		else{
			$ques = PreAssessmentQues::select('id','question_hindi as ques','optionA_hindi as optionA','optionB_hindi as optionB','optionC_hindi as optionC','optionD_hindi as optionD','optionE_hindi as optionE')->get();
		}
		return $this->sendResponse($ques, '',true);
    }

	public function saveAssesment(Request $request) {
		$data = $request->all();
  		$user_array=array();
		$user_array['user_id'] = @$data['user_id'];
		$user_array['symp_id'] = @$data['symp_id'];
		$user_array['quesArr'] = @$data['quesArr'];
		$user_array['lng'] = @$data['lng'];
		
		$quesArr = $user_array['quesArr'];
		$totScore = 0;
		if(count($quesArr)>0) {
			foreach($quesArr as $raw) {
				$totScore += $raw['ques_val'];
			}
		}
		$result = $this->calculateResult($quesArr,$user_array['symp_id']);
		$res = AssesmentAnswer::create([
			'user_id' => $user_array['user_id'],
			'symp_id' => $user_array['symp_id'],
			'total_score' => $result['score'],
			'mental_status' => $result['category'],
			'suggestion' => $result['suggestion'],
			'meta_data' => json_encode($user_array['quesArr']),
			'score_data' => count($result['scoreData']) > 0 ? json_encode($result['scoreData']) : NULL
		]);
		$type = (($user_array['lng'] == "hi") ? 'type_hindi as type' : 'type');
		$category = (($user_array['lng'] == "hi") ? 'category_hindi as category' : 'category');
		$suggestion = (($user_array['lng'] == "hi") ? 'suggestion_hindi as suggestion' : 'suggestion');
		if(!empty($res->suggestion)) {
			$res['suggestion_id'] = $res->suggestion;
			$qRangeData = MhQuesRange::with("MhResultType")->select('id','category_id',$suggestion)->where('id',$res->suggestion)->first();
			$res->suggestion = $qRangeData->suggestion;
			$res->mental_status = $user_array['lng'] == "hi" ? $qRangeData->MhResultType->title_hindi : $qRangeData->MhResultType->title;
		}
		if(!empty($res->score_data)) {
			$scoreData = json_decode($res->score_data,true);
			$newData = [];
			if(count($scoreData)>0) {
				foreach($scoreData as $raw) {
					$qRange = MhQuesRange::select('id',$type,$category,$suggestion)->where('id',$raw['rawId'])->first();
					$raw['suggestion'] = $qRange->suggestion;
					$raw['category'] = $qRange->category;
					$raw['type'] = $qRange->type;
					$newData[] = $raw;
				}
			}
			$res->score_data = $newData;
		}
		return $this->sendResponse($res, 'Saved Successfully',true);
    }
	public function calculateResult($quesArr,$sympId) {
		$score = 0; $totScoreA = NULL; $totScoreB = NULL; $totScoreC = NULL; $totScoreD = NULL; $totScoreE = NULL; $totScoreF = NULL;
		if(count($quesArr)>0) {
			foreach($quesArr as $raw) {
				if(in_array($raw['ques_id'],[105,106,107,108,109,110,111,112])) {
					$totScoreA += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[113,114,115,116,117,118,119])) {
					$totScoreB += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[120,121,122,123])) {
					$totScoreC += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[124,125,126])) {
					$totScoreD += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[127,128,129,130,131])) {
					$totScoreE += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[132,133,134,135,136])) {
					$totScoreF += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[94,96,97,99,103])) {
					$totScoreA += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[85,86,98,102,104])) {
					$totScoreB += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[90,92,93])) {
					$totScoreC += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[91,95])) {
					$totScoreD += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[89,100,101])) {
					$totScoreE += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[87,88])) {
					$totScoreF += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[65,70,75,80])){
					$totScoreA += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[66,71,76,81])){
					$totScoreB += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[67,72,77,82])){
					$totScoreC += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[68,73,78,83])){
					$totScoreD += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[69,74,79,84])){
					$totScoreE += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[6,9,13,14])) {
					$totScoreA += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[4,5,10,11,12,15,16,17])) {
					$totScoreB += $raw['ques_val'];
				}
				else if(in_array($raw['ques_id'],[1,2,3,7,8,18])) {
					$totScoreC += $raw['ques_val'];
				}
				else{
					$score += $raw['ques_val'];
				}
			}
		}
		if($sympId == 1217 || $sympId ==  1219 || $sympId ==  1220 || $sympId == 1221) {
			$score = 0;
			$score = $totScoreA + $totScoreB + $totScoreC + $totScoreD + $totScoreE + $totScoreF;
		}
		$rangeData = MhQuesRange::where('symp_id',$sympId)->get();
		$cat = null; $rangeId = null; $scoreData = [];
		foreach($rangeData as $raw) {
			if($raw->ques_type == 1) {
				if($score >= $raw->min_score && $score <= $raw->max_score) {
					$cat = $raw->category;
					$rangeId = $raw->id;
				}
			}
			else if($raw->ques_type == 2) {
				if(!empty($totScoreA) && $raw->score_type == 1) {
					if($totScoreA >= $raw->min_score && $totScoreA <= $raw->max_score) {
						$scoreData[] = ['rawId'=>$raw->id,'score'=>$totScoreA];
					}
				}
				if(!empty($totScoreB) && $raw->score_type == 2) {
					if($totScoreB >= $raw->min_score && $totScoreB <= $raw->max_score) {
						$scoreData[] = ['rawId'=>$raw->id,'score'=>$totScoreB];
					}
				}
				if(!empty($totScoreC) && $raw->score_type == 3) {
					if($totScoreC >= $raw->min_score && $totScoreC <= $raw->max_score) {
						$scoreData[] = ['rawId'=>$raw->id,'score'=>$totScoreC];
					}
				}
				if(!empty($totScoreD) && $raw->score_type == 4){
					if($totScoreD >= $raw->min_score && $totScoreD <= $raw->max_score) {
						$scoreData[] = ['rawId'=>$raw->id,'score'=>$totScoreD];
					}
				}
				if(!empty($totScoreE) && $raw->score_type == 5){
					if($totScoreE >= $raw->min_score && $totScoreE <= $raw->max_score) {
						$scoreData[] = ['rawId'=>$raw->id,'score'=>$totScoreE];
					}
				}
				if(!empty($totScoreF) && $raw->score_type == 6){
					if($totScoreF >= $raw->min_score && $totScoreF <= $raw->max_score) {
						$scoreData[] = ['rawId'=>$raw->id,'score'=>$totScoreF];
					}
				}
			}
		}
		return ['score'=>$score,'category'=>$cat,'suggestion'=>$rangeId,'scoreData'=>$scoreData];
	}
	
	public function savePreAssesment(Request $request) {
		$data = $request->all();
  		$user_array=array();
		$user_array['user_id'] = @$data['user_id'];
		$user_array['quesArr'] = @$data['quesArr'];
		$user_array['lng'] = @$data['lng'];

		$quesArr = 	$user_array['quesArr'];
		$tot_score = 0;
		if(count($quesArr)>0) {
			foreach($quesArr as $raw) {
				$tot_score += $raw['ques_val'];
				// if(in_array($raw['ques_id'],[3,5,10,13,16,17,21])){
				// 	$depres_score += $raw['ques_val'];
				// }
				// else if(in_array($raw['ques_id'],[2,4,7,9,15,19,20])){
				// 	$anxiety_score += $raw['ques_val'];
				// }
				// else if(in_array($raw['ques_id'],[1,6,8,11,12,14,18])){
				// 	$stress_score += $raw['ques_val'];
				// }
			}
		}
		$res = PreAssesmentAnswer::create([
			'user_id' => $user_array['user_id'],
			'meta_data' => json_encode($user_array['quesArr']),
			'total_score' => $tot_score,
		]);
		if($res->total_score >= 30 && $res->total_score <= 50) {
			$res['distress_level'] = "Severe";
			$res['distress_description'] = getTermsBySLug('very-high-psychological-distress',$user_array['lng']);
		}
		else if($res->total_score >= 25 && $res->total_score <= 29) {
			$res['distress_level'] = "Mild";
			$res['distress_description'] = getTermsBySLug('high-psychological-distress',$user_array['lng']);
		}
		else if($res->total_score >= 20 && $res->total_score <= 24) {
			$res['distress_level'] = "Moderate";
			$res['distress_description'] = getTermsBySLug('moderate-psychological-distress',$user_array['lng']);
		}
		else if($res->total_score >= 10 && $res->total_score <= 19) {
			$res['distress_level'] = "Normal";
			$res['distress_description'] = getTermsBySLug('low-psychological-distress',$user_array['lng']);
		}
		/*$depress_level = ""; $anxiety_level = ""; $stress_level = "";
		$depress_description = ""; $anxiety_description = ""; $stress_description = "";
		if($res->depression_score >= 0 && $res->depression_score <= 9){
			$depress_level = 'Normal';
		}
		else if($res->depression_score >= 10 && $res->depression_score <= 13){
			$depress_level = 'Mild';
			$depress_description = getTermsBySLug('mild-depression-recommended-intervention',$user_array['lng']);
		}
		else if($res->depression_score >= 14 && $res->depression_score <= 20){
			$depress_level = 'Moderate';
			$depress_description = getTermsBySLug('moderate-depression-recommended-intervention',$user_array['lng']);
		}
		else if($res->depression_score >= 21 && $res->depression_score <= 27){
			$depress_level = 'Severe';
			$depress_description = getTermsBySLug('severe-depression-recommended-intervention',$user_array['lng']);
		}
		else if($res->depression_score >= 28) {
			$depress_level = 'Extremely severe';
			$depress_description = getTermsBySLug('extremely-severe-depression-recommended-intervention',$user_array['lng']);
		}

		if($res->anxiety_score >= 0 && $res->anxiety_score <= 7){
			$anxiety_level = 'Normal';
			
		}
		else if($res->anxiety_score >= 8 && $res->anxiety_score <= 9){
			$anxiety_level = 'Mild';
			$anxiety_description = getTermsBySLug('mild-anxiety-recommended-intervention',$user_array['lng']);
		}
		else if($res->anxiety_score >= 10 && $res->anxiety_score <= 14){
			$anxiety_level = 'Moderate';
			$anxiety_description = getTermsBySLug('moderate-anxiety-recommended-intervention',$user_array['lng']);
		}
		else if($res->anxiety_score >= 15 && $res->anxiety_score <= 19){
			$anxiety_level = 'Severe';
			$anxiety_description = getTermsBySLug('severe-anxiety-recommended-intervention',$user_array['lng']);
		}
		else if($res->anxiety_score >= 20) {
			$anxiety_level = 'Extremely severe';
			$anxiety_description = getTermsBySLug('extremely-severe-anxiety-recommended-intervention',$user_array['lng']);
		}

		if($res->stress_score >= 0 && $res->stress_score <= 14){
			$stress_level = 'Normal';
		}
		else if($res->stress_score >= 15 && $res->stress_score <= 18){
			$stress_level = 'Mild';
			$stress_description = getTermsBySLug('mild-stress-recommended-intervention',$user_array['lng']);
		}
		else if($res->stress_score >= 19 && $res->stress_score <= 25){
			$stress_level = 'Moderate';
			$stress_description = getTermsBySLug('moderate-stress-recommended-intervention',$user_array['lng']);
		}
		else if($res->stress_score >= 26 && $res->stress_score <= 33){
			$stress_level = 'Severe';
			$stress_description = getTermsBySLug('severe-stress-recommended-intervention',$user_array['lng']);
		}
		else if($res->stress_score >= 34) {
			$stress_level = 'Extremely severe';
			$stress_description = getTermsBySLug('extremely-severe-stress-recommended-intervention',$user_array['lng']);
		}
		$res['depress_level'] = $depress_level;
		$res['anxiety_level'] = $anxiety_level;
		$res['stress_level'] = $stress_level;

		$res['depress_description'] = $depress_description;
		$res['anxiety_description'] = $anxiety_description;
		$res['stress_description'] = $stress_description;*/
		return $this->sendResponse($res, 'Saved Successfully',true);
    }

	public function saveAssesmentExp(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['assm_id'] = $data->get('assm_id');
		$user_array['feedback'] = $data->get('feedback');
		$user_array['feed_msg'] = $data->get('feed_msg');

		AssesmentAnswer::where('id',$user_array['assm_id'])->update([
			'feedback' => !empty($user_array['feedback']) ? $user_array['feedback'] : NULL,
			'feed_msg' => $user_array['feed_msg']
		]);
		return $this->sendResponse('', 'Saved Successfully',true);
    }

	public function fetchSymptomById(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['sym_id'] = $data->get('sym_id');

		$symp = Symptoms::where('id',$user_array['sym_id'])->first();
		return $this->sendResponse($symp, 'Saved Successfully',true);
    }

	
	/*public function fetchPreAssessment(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['lng'] = $data->get('lng') !== NULL ? $data->get('lng') : 'en';
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$assessmentRecords = PreAssesmentAnswer::where('user_id',$user_array['user_id'])->orderBy('created_at','ASC')->get();
			
			if(count($assessmentRecords)>0) {
				foreach($assessmentRecords as $res) {
					$level = NULL;
					if($res->total_score >= 30 && $res->total_score <= 50){
						$level = "Likely to have a severe disorder";
					}
					else if($res->total_score >= 25 && $res->total_score <= 29){
						$level = "Likely to have a mild disorder";
					}
					else if($res->total_score >= 20 && $res->total_score <= 24){
						$level = "Likely to have a moderate disorder";
					}
					else if($res->total_score >= 10 && $res->total_score <= 19){
						$level = "Likely to have a severe disorder";
					}
					$res['distress_level'] = $level;
					$res['distress_description'] = getTermsBySLug('mild-depression-recommended-intervention',$user_array['lng']);
					/*$depress_level = ""; $anxiety_level = ""; $stress_level = "";
					$depress_description = ""; $anxiety_description = ""; $stress_description = "";

					if($res->depression_score >= 0 && $res->depression_score <= 9){
						$depress_level = 'Normal';
					}
					else if($res->depression_score >= 10 && $res->depression_score <= 13){
						$depress_level = 'Mild';
						$depress_description = getTermsBySLug('mild-depression-recommended-intervention',$user_array['lng']);
					}
					else if($res->depression_score >= 14 && $res->depression_score <= 20){
						$depress_level = 'Moderate';
						$depress_description = getTermsBySLug('moderate-depression-recommended-intervention',$user_array['lng']);
					}
					else if($res->depression_score >= 21 && $res->depression_score <= 27){
						$depress_level = 'Severe';
						$depress_description = getTermsBySLug('severe-depression-recommended-intervention',$user_array['lng']);
					}
					else if($res->depression_score >= 28) {
						$depress_level = 'Extremely severe';
						$depress_description = getTermsBySLug('extremely-severe-depression-recommended-intervention',$user_array['lng']);
					}

					if($res->anxiety_score >= 0 && $res->anxiety_score <= 7){
						$anxiety_level = 'Normal';
					}
					else if($res->anxiety_score >= 8 && $res->anxiety_score <= 9){
						$anxiety_level = 'Mild';
						$anxiety_description = getTermsBySLug('mild-anxiety-recommended-intervention',$user_array['lng']);
					}
					else if($res->anxiety_score >= 10 && $res->anxiety_score <= 14){
						$anxiety_level = 'Moderate';
						$anxiety_description = getTermsBySLug('moderate-anxiety-recommended-intervention',$user_array['lng']);
					}
					else if($res->anxiety_score >= 15 && $res->anxiety_score <= 19){
						$anxiety_level = 'Severe';
						$anxiety_description = getTermsBySLug('severe-anxiety-recommended-intervention',$user_array['lng']);
					}
					else if($res->anxiety_score >= 20) {
						$anxiety_level = 'Extremely severe';
						$anxiety_description = getTermsBySLug('extremely-severe-anxiety-recommended-intervention',$user_array['lng']);
					}

					if($res->stress_score >= 0 && $res->stress_score <= 14){
						$stress_level = 'Normal';
					}
					else if($res->stress_score >= 15 && $res->stress_score <= 18){
						$stress_level = 'Mild';
						$stress_description = getTermsBySLug('mild-stress-recommended-intervention',$user_array['lng']);
					}
					else if($res->stress_score >= 19 && $res->stress_score <= 25){
						$stress_level = 'Moderate';
						$stress_description = getTermsBySLug('moderate-stress-recommended-intervention',$user_array['lng']);
					}
					else if($res->stress_score >= 26 && $res->stress_score <= 33){
						$stress_level = 'Severe';
						$stress_description = getTermsBySLug('severe-stress-recommended-intervention',$user_array['lng']);
					}
					else if($res->stress_score >= 34) {
						$stress_level = 'Extremely severe';
						$stress_description = getTermsBySLug('extremely-severe-stress-recommended-intervention',$user_array['lng']);
					}
					$res['depress_level'] = $depress_level;
					$res['anxiety_level'] = $anxiety_level;
					$res['stress_level'] = $stress_level;

					$res['depress_description'] = $depress_description;
					$res['anxiety_description'] = $anxiety_description;
					$res['stress_description'] = $stress_description;*/
				/*}
			}
			return $this->sendResponse($assessmentRecords, 'Fetch Successfully',true);
		}
    }*/

	public function fetchMainAssessment(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['symp_id'] = $data->get('symp_id');
		$user_array['lng'] = $data->get('lng');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'symp_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$mainAssessments = AssesmentAnswer::where('user_id',$user_array['user_id'])->where('symp_id',$user_array['symp_id'])->get();
			if(count($mainAssessments)) {
				foreach($mainAssessments as $res) {
					$type = (($user_array['lng'] == "hi") ? 'type_hindi as type' : 'type');
					$category = (($user_array['lng'] == "hi") ? 'category_hindi as type' : 'category');
					$suggestion = (($user_array['lng'] == "hi") ? 'suggestion_hindi as type' : 'suggestion');
					if(!empty($res->suggestion)) {
						$qRangeData = MhQuesRange::with("MhResultType")->select('id','category_id',$category,$suggestion)->where('id',$res->suggestion)->first();
						$res->suggestion = $qRangeData->suggestion;
						$res->mental_status = $user_array['lng'] == "hi" ? $qRangeData->MhResultType->title_hindi : $qRangeData->MhResultType->title;
					}
					if(!empty($res->score_data)) {
						$scoreData = json_decode($res->score_data,true);
						$newData = [];
						if(count($scoreData)>0) {
							foreach($scoreData as $raw) {
								$qRange = MhQuesRange::select('id',$type,$category,$suggestion)->where('id',$raw['rawId'])->first();
								$raw['suggestion'] = $qRange->suggestion;
								$raw['category'] = $qRange->category;
								$raw['type'] = $qRange->type;
								$newData[] = $raw;
							}
						}
						$res->score_data = $newData;
					}
				}
			}
			return $this->sendResponse($mainAssessments, 'Fetch Successfully',true);
		}
	}

	public function fetchMhMatrix(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['lng'] = $data->get('lng');
		$user_array['slug'] = $data->get('slug');
		$user_array['symp_id'] = $data->get('symp_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'lng' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$user_id = $user_array['user_id'];
			$mainAssessments = AssesmentAnswer::with('MhQuesRange.MhResultType')->where('user_id',$user_array['user_id'])->orderBy('symp_id','ASC')->get();
			if($mainAssessments->count()>0) {
				foreach($mainAssessments as $raw) {
					// if($raw->MhWeeklyProgram->count()>0) {
						$weekCount = 0;
						/*foreach($raw->MhWeeklyProgram as $col) {
							$isComplete = 0;
							if(count($col->AssessmentOverview) > 0) {
								foreach($col->AssessmentOverview as $line) {
									if(!empty($line->audio_file)){
										$line['audio_file_url'] = getPath("public/mh-audio-files/".$line->audio_file);
									}
									else{
										$line['audio_file_url'] = null;
									}
									// if($user_array['lng'] == "en") {
									// 	$line['audio_file_url'] = getPath("public/mh-audio-files/".$line->audio_file);
									// }
									// else {
									// 	$line['audio_file_url'] = getPath("public/mh-audio-files/".$line->audio_file_hindi);
									// }

									$matrixArr = null;
								if($line->MhProgramMatrix->count()>0){
									foreach($line->MhProgramMatrix as $matrix) {
										if($user_array['user_id'] == $matrix->user_id) {
											$matrixArr = $matrix;
										}
									}
								}
								
								unset($line->MhProgramMatrix);
								unset($line['MhProgramMatrix']);
								$line['mh_program_matrix'] = $matrixArr;
	
								if(!empty($matrixArr) && $matrixArr->program_status == 2) {
									$isComplete = 1; 
								}
								else {
									$isComplete = 0;
								}
								}

								$wpFeedbackArr = null;
								if(count($col->MhWpFeedback)>0) {
									foreach($col->MhWpFeedback as $feed){
										if($feed->user_id == $user_id){
											$wpFeedbackArr = $feed;
										}
									}
								}
								unset($col->MhWpFeedback);
								$col['mh_wp_feedback'] = $wpFeedbackArr;
							}
							$col['icon_url'] = getPath("public/mh-weekly-icons/".$col->icon);
							if(isset($raw->MhQuesRange) && $raw->MhQuesRange->category_id == $col->s_type) {
								$weeklyPArr[] = $col;
							}
							if($isComplete == 1) {
								$weekCount += 1;
							}
						}
					}*/
					$weeklyPArr = 0;
					if(isset($raw->MhQuesRange) && !empty($raw->MhQuesRange->MhResultType)) {
						$raw['mental_status'] = $user_array['lng'] == 'en' ? @$raw->MhQuesRange->MhResultType->title : @$raw->MhQuesRange->MhResultType->title_hindi;
						$weeklyPrograms = MhWeeklyProgram::with("AssessmentOverview.MhProgramMatrix")->where("s_type",$raw->MhQuesRange->category_id)->get();
						$weeklyPArr = $weeklyPrograms->count();
						if($weeklyPArr > 0){
							foreach($weeklyPrograms as $wkp) {
							$readAssesCount = 0;
							if(count($wkp->AssessmentOverview) > 0) {
								foreach($wkp->AssessmentOverview as $aso) {
									if($aso->MhProgramMatrix->count()>0){
										foreach($aso->MhProgramMatrix as $matrix) {
											if($user_array['user_id'] == $matrix->user_id) {
												if($matrix->program_status == 2) {
													$readAssesCount += 1; 
												}
											}
										}
									}
								}
							}
								if(count($wkp->AssessmentOverview) == $readAssesCount){
									$weekCount += 1; 
								}
							}
						}
					}
					if($user_array['lng'] == 'hi') {
						$raw['program_title'] = $this->setTitleDescProgramHindi($raw->symp_id,$weeklyPArr,$weekCount)['title'];
						$raw['program_desc'] = $this->setTitleDescProgramHindi($raw->symp_id,$weeklyPArr,$weekCount)['desc'];
						$raw['icon'] = $this->setTitleDescProgramHindi($raw->symp_id,$weeklyPArr,$weekCount)['icon'];
					}
					else{
						$raw['program_title'] = $this->setTitleDescProgram($raw->symp_id,$weeklyPArr,$weekCount)['title'];
						$raw['program_desc'] = $this->setTitleDescProgram($raw->symp_id,$weeklyPArr,$weekCount)['desc'];
						$raw['icon'] = $this->setTitleDescProgram($raw->symp_id,$weeklyPArr,$weekCount)['icon'];
					}
				
					if($raw->symp_id == 1220) {
						$content = DB::table('pages')->where(["status"=>1,'slug'=>'decision-making-low-score'])->where("lng",$user_array['lng'])->first();
					}
					if($raw->symp_id == 1223) {
						$content = DB::table('pages')->where(["status"=>1,'slug'=>'self-steem-high'])->where("lng",$user_array['lng'])->first();
					}
					if($raw->symp_id == 1222) {
						$content = DB::table('pages')->where(["status"=>1,'slug'=>'adjustment-issue'])->where("lng",$user_array['lng'])->first();
					}
					if($raw->symp_id == 1218) {
						$content = DB::table('pages')->where(["status"=>1,'slug'=>'poor-focus-high-score'])->where("lng",$user_array['lng'])->first();
					}
					$raw['mh_normal'] = null;
					if(isset($content) && !empty($content) && $weeklyPArr == 0) {
						$raw['mh_normal'] = $content->description;
					}					
				}
			}
			return $this->sendResponse($mainAssessments, 'Fetch Successfully',true);
		}
	}
	
	function setTitleDescProgram($id,$totWeek,$completeWeekCount) {
		$titleDesc = [];
		$titleDesc['desc'] = "Reminder - You still need to start your weekly program. Let's begin your journey towards growth and well-being.";
		$titleDesc['title'] = NULL;
		$titleDesc['icon'] = NULL;
		if($id == 440) {
			$titleDesc['title'] = $totWeek."-week program for anxiety";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "Congratulations, you completed the ".$completeWeekCount."-week program. Now, move towards the new week.";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/anxiety.png");
		}
		else if($id == 1217){
			$titleDesc['title'] = $totWeek."-week program for academic stress";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "Congratulations, you completed the ".$completeWeekCount."-week program. Now, move towards the new week.";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/academic-stress.png");
		}
		else if($id == 1218){
			$titleDesc['title'] = $totWeek."-week program for poor concentration and focus";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "Congratulations, you completed the ".$completeWeekCount."-week program. Now, move towards the new week.";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/poor-concentration.png");
		}
		else if($id == 1219){
			$titleDesc['title'] = $totWeek."-week program for relationship issues";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "Congratulations, you completed the ".$completeWeekCount."-week program. Now, move towards the new week.";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/relation-issue.png");
		}
		else if($id == 1220){
			$titleDesc['title'] = $totWeek."-week program for decision making";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "Congratulations, you completed the ".$completeWeekCount."-week program. Now, move towards the new week.";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/decision-making.png");
		}
		else if($id == 1221){
			$titleDesc['title'] = $totWeek."-week program for addiction";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "Congratulations, you completed the ".$completeWeekCount."-week program. Now, move towards the new week.";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/addiction.png");
		}
		else if($id == 1222){
			$titleDesc['title'] = $totWeek."-week program for adjustment issues";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "Congratulations, you completed the ".$completeWeekCount."-week program. Now, move towards the new week.";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/adjustment-issue.png");
		}
		else if($id == 1223){
			$titleDesc['title'] = $totWeek."-week program self-esteem";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "Congratulations, you completed the ".$completeWeekCount."-week program. Now, move towards the new week.";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/self-esteem.png");
		}
		return $titleDesc;
	}

	public function setTitleDescProgramHindi($id,$totWeek,$completeWeekCount) {
		$titleDesc = [];
		$titleDesc['desc'] = "रिमाइंडर - आपको अभी भी अपना साप्ताहिक कार्यक्रम शुरू करने की आवश्यकता है। आइए विकास और खुशहाली की दिशा में अपनी यात्रा शुरू करें।";
		$titleDesc['title'] = NULL;
		$titleDesc['icon'] = NULL;
		if($id == 440) {
			$titleDesc['title'] = "चिंता के लिए ".$totWeek." सप्ताह का कार्यक्रम";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "बधाई हो, आपने ".$completeWeekCount." सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/anxiety.png");
		}
		else if($id == 1217){
			$titleDesc['title'] = "शैक्षणिक तनाव के लिए ".$totWeek." सप्ताह का कार्यक्रम";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "बधाई हो, आपने ".$completeWeekCount." सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/academic-stress.png");
		}
		else if($id == 1218){
			$titleDesc['title'] = "खराब एकाग्रता और फोकस के लिए ".$totWeek." सप्ताह का कार्यक्रम";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "बधाई हो, आपने ".$completeWeekCount." सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/poor-concentration.png");
		}
		else if($id == 1219){
			$titleDesc['title'] = "रिश्ते के मुद्दों के लिए ".$totWeek." सप्ताह का कार्यक्रम";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "बधाई हो, आपने ".$completeWeekCount." सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/relation-issue.png");
		}
		else if($id == 1220){
			$titleDesc['title'] = "निर्णय लेने के लिए ".$totWeek."5 सप्ताह का कार्यक्रम";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "बधाई हो, आपने ".$completeWeekCount." सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/decision-making.png");
		}
		else if($id == 1221){
			$titleDesc['title'] = "व्यसन के लिए ".$totWeek." सप्ताह का कार्यक्रम";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "बधाई हो, आपने ".$completeWeekCount." सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/addiction.png");
		}
		else if($id == 1222){
			$titleDesc['title'] = "समायोजन मुद्दों के लिए ".$totWeek." सप्ताह का कार्यक्रम";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "बधाई हो, आपने ".$completeWeekCount." सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/adjustment-issue.png");
		}
		else if($id == 1223){
			$titleDesc['title'] = $totWeek." सप्ताह का कार्यक्रम आत्मसम्मान";
			if($completeWeekCount > 0) {
				$titleDesc['desc'] = "बधाई हो, आपने ".$completeWeekCount." सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
			}
			$titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/self-esteem.png");
		}
		return $titleDesc;
	}

	public function fetchAssessmentRecord(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['lng'] = $data->get('lng') !== NULL ? $data->get('lng') : 'en';
		$user_array['slug'] = $data->get('slug');
		$user_array['symp_id'] = $data->get('symp_id');
		$page = DB::table('pages')
		->where('status', 1)
		->whereIn('slug', [
			'decision-making-low-score',
			'self-steem-high',
			'adjustment-issue',
			'poor-focus-high-score'
		])
		->where('lng', $user_array['lng'])
		->first();

		$validator = Validator::make($user_array, [
			'user_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$assessmentRecords = PreAssesmentAnswer::where('user_id',$user_array['user_id'])->orderBy('created_at','ASC')->get();
			if(count($assessmentRecords)>0) {
				foreach($assessmentRecords as $res) {
					if($res->total_score >= 30 && $res->total_score <= 50) {
						$res['distress_level'] = "Severe";
						$res['distress_description'] = getTermsBySLug('very-high-psychological-distress',$user_array['lng']);
					}
					else if($res->total_score >= 25 && $res->total_score <= 29) {
						$res['distress_level'] = "Mild";
						$res['distress_description'] = getTermsBySLug('high-psychological-distress',$user_array['lng']);
					}
					else if($res->total_score >= 20 && $res->total_score <= 24) {
						$res['distress_level'] = "Moderate";
						$res['distress_description'] = getTermsBySLug('moderate-psychological-distress',$user_array['lng']);
					}
					else if($res->total_score >= 10 && $res->total_score <= 19) {
						$res['distress_level'] = "Normal";
						$res['distress_description'] = getTermsBySLug('low-psychological-distress',$user_array['lng']);
					}
					
				}
			}
			$mainAssessments = AssesmentAnswer::with(['MhQuesRange.MhResultType',"Symptoms"])->where('user_id',$user_array['user_id'])->get();
			$newArr = [];
			if(count($mainAssessments)) {
				foreach($mainAssessments as $res) {
					$type = (($user_array['lng'] == "hi") ? 'type_hindi as type' : 'type');
					$category = (($user_array['lng'] == "hi") ? 'category_hindi as category' : 'category');
					$suggestion = (($user_array['lng'] == "hi") ? 'suggestion_hindi as suggestion' : 'suggestion');
					if(!empty($res->suggestion)) {
						$qRangeData = MhQuesRange::select('id',$category,$suggestion)->where('id',$res->suggestion)->first();
						$res->suggestion = $qRangeData->suggestion;
						$res->mental_status = $qRangeData->category;
					}
					if(!empty($res->score_data)) {
						$scoreData = json_decode($res->score_data,true);
						$newData = [];
						if(count($scoreData)>0) {
							foreach($scoreData as $raw) {
								$qRange = MhQuesRange::select('id',$type,$category,$suggestion)->where('id',$raw['rawId'])->first();
								$raw['suggestion'] = $qRange->suggestion;
								$raw['category'] = $qRange->category;
								$raw['type'] = $qRange->type;
								$newData[] = $raw;
							}
						}
						$res->score_data = $newData;
					}
					$weeklyPArr = 0;
					if(isset($res->MhQuesRange) && !empty($res->MhQuesRange->MhResultType)) {
						$weeklyPArr = MhWeeklyProgram::where("s_type",$res->MhQuesRange->category_id)->count();
					}
					/*if($res->MhWeeklyProgram->count()>0) {
						foreach($res->MhWeeklyProgram as $col) {
							if(count($col->AssessmentOverview) > 0) {
								foreach($col->AssessmentOverview as $line) {
									if(!empty($line->audio_file)){
										$line['audio_file_url'] = getPath("public/mh-audio-files/".$line->audio_file);
									}
									else{
										$line['audio_file_url'] = null;
									}
									
									$matrixArr = null;
									if($line->MhProgramMatrix->count()>0){
										foreach($line->MhProgramMatrix as $matrix) {
											if($user_array['user_id'] == $matrix->user_id) {
												$matrixArr = $matrix;
											}
										}
									}
									unset($line->MhProgramMatrix);
									unset($line['MhProgramMatrix']);
									$line['mh_program_matrix'] = $matrixArr;
								}
							}
							$wpFeedbackArr = null;
							if(count($col->MhWpFeedback)>0) {
								foreach($col->MhWpFeedback as $feed){
									if($feed->user_id == $user_array['user_id']){
										$wpFeedbackArr = $feed;
									}
								}
							}
							unset($col->MhWpFeedback);
							$col['mh_wp_feedback'] = $wpFeedbackArr;

							$col['icon'] = getPath("public/mh-weekly-icons/".$col->icon);
							if(isset($res->MhQuesRange) && $res->MhQuesRange->category_id == $col->s_type) {
								$weeklyPArr[] = $col;
							}
						}
					}*/
					

					if($res->symp_id == 1220) {
						$content = DB::table('pages')->where(["status"=>1,'slug'=>'decision-making-low-score'])->where("lng",$user_array['lng'])->first();
					}
					if($res->symp_id == 1223) {
						$content = DB::table('pages')->where(["status"=>1,'slug'=>'self-steem-high'])->where("lng",$user_array['lng'])->first();
					}
					if($res->symp_id == 1222) {
						$content = DB::table('pages')->where(["status"=>1,'slug'=>'adjustment-issue'])->where("lng",$user_array['lng'])->first();
					}
					if($res->symp_id == 1218) {
						$content = DB::table('pages')->where(["status"=>1,'slug'=>'poor-focus-high-score'])->where("lng",$user_array['lng'])->first();
					}
					$res['mh_normal'] = null;
					if(isset($content) && !empty($content) && $weeklyPArr == 0) {
						$res['mh_normal'] = $content->description;
					}
					if($user_array['lng'] == "hi"){
						if(isset($newArr[$res->Symptoms->symptom_hindi])) {
							$newArr[$res->Symptoms->symptom_hindi][] = $res;
						}
						else{
							$newArr[$res->Symptoms->symptom_hindi] = [$res];
						}
					}
					else{
						if(isset($newArr[$res->Symptoms->symptom])) {
							$newArr[$res->Symptoms->symptom][] = $res;
						}
						else{
							$newArr[$res->Symptoms->symptom] = [$res];
						}
					}
				}
			}
			$arr = ['pre_assessment_records'=>$assessmentRecords,'main_assessment_records'=>$newArr];
			return $this->sendResponse($arr, 'Fetch Successfully',true);
    	}
	}

	public function fetchSession(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['pid'] = $data->get('pid');
		$user_array['appt_type'] = $data->get('appt_type');
		$validator = Validator::make($user_array,[
			// 'pid' => 'required',
			'appt_type' => 'required',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$p_ids = User::select("pId")->where(["parent_id"=>$user_array['pid']])->pluck("pId")->toArray();
			array_push($p_ids,$user_array['pid']);
			$orders = Appointments::with(['Doctors.docSpeciality','Doctors.DoctorData','Doctors.getCityName','Doctors.getStateName','practiceDetails','AppointmentOrder','NotifyUserSms','PatientFeedback','patient'])->whereIn('pID',$p_ids)->where('delete_status',1)->where('doc_id','!=',2219)->orderBy('start','desc')->paginate(12);
			$success = false;
			$newArr = [];
			if($orders->count()>0) {
				$success = true;
				foreach($orders as $appt) {
					$followup_count = isset($appt->Doctors->DoctorData) ? $appt->Doctors->DoctorData->followup_count : null;
					$followUp = followupExist($appt->start,$followup_count,$appt->id,$appt->doc_id,$appt->pId);
					$appt['is_followup'] = $followUp['success'];
					$appt['followupDone'] = $followUp['flag'];
					$appt['prescription'] = null;
					$presDta = UserPrescription::where(['appointment_id'=>$appt->id])->count();
					if($appt->visit_status == 1 && $presDta > 0){
						$appt['prescription'] = 1;
					}
					
					$appt['consultation_fees'] = getSetting("tele_main_price")[0];
					if(!empty($appt->Doctors) && $appt->Doctors->docSpeciality) {
						$appt['doc_speciality'] = array("id"=>$appt->Doctors->docSpeciality->id,"spaciality"=>$appt->Doctors->docSpeciality->spaciality,"spaciality_hindi"=> $appt->Doctors->docSpeciality->spaciality_hindi);
					}
					else{
						$appt['doc_speciality'] = array("id"=>"","spaciality"=>"","spaciality_hindi"=>"");
					}
					$appt['doc_pic'] = null;
					if(!empty($appt->Doctors)) {
						$appt['doc_pic'] = getPath("public/doctor/ProfilePics/".$appt->Doctors->profile_pic);
					}
					if(!empty($appt->PatientFeedback)){
						$appt['feedback_done'] = 1;
					}
					else{
						$appt['feedback_done'] = 0;
					}
					$appt['apt_type'] = 0;
					if(!empty($appt->AppointmentOrder)) {
						if($user_array['appt_type'] == 1) {
							$metaData = json_decode($appt->AppointmentOrder->meta_data,true);
							if(isset($metaData['_from']) && $metaData['_from'] == 1) {
								$newArr[] = $appt;
							}
						}
						else if($user_array['appt_type'] == 2) {
							$metaData = json_decode($appt->AppointmentOrder->meta_data,true);
							$appt['apt_type'] = isset($metaData['_from']) ? $metaData['_from'] : 0;
						}
					}
					$appt['appointment_confirmation'] = 0;
				}
			}
			if($user_array['appt_type'] == 1) {
				$perPage = 10;
				$input = Input::all();
				if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }

				$offset = ($currentPage * $perPage) - $perPage;
				$itemsForCurrentPage = array_slice($newArr, $offset, $perPage, false);
				$newArr =  new Paginator($itemsForCurrentPage, count($newArr), $perPage,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
				$orders = $newArr;
			}
			return $this->sendResponse($orders, 'User Session.',$success);
		}
	}
	public function updateReadSession(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['program_id'] = $data->get('program_id');
		$user_array['symp_id'] = $data->get('symp_id');
		$user_array['program_status'] = $data->get('program_status');

		$validator = Validator::make($user_array,[
			'user_id' => 'required',
			'program_id' => 'required',
			'symp_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$matrix = MhProgramMatrix::where('program_id',$user_array['program_id'])->where('user_id',$user_array['user_id'])->orderBy('id','DESC')->first();
			if(!empty($matrix)) {
				$dfsd = MhProgramMatrix::where('id',$matrix->id)->update([
					'program_status' => $user_array['program_status']
				]);
				$matrix['program_status'] = $user_array['program_status'];
			}
			else{
				$matrix = MhProgramMatrix::create([
					'user_id' => $user_array['user_id'],
					'program_id' => $user_array['program_id'],
					'symp_id' => $user_array['symp_id'],
					'program_status' => $user_array['program_status'],
				]);
			}
			return $this->sendResponse($matrix, 'Saved Successfully',true);
		}
    }
	public function fetchOverviewContent(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['lng'] = $data->get('lng');
		$user_array['symp_id'] = $data->get('symp_id');
		$user_array['assess_id'] = $data->get('assess_id');
		$user_array['suggestionId'] = $data->get('suggestion');

		$validator = Validator::make($user_array,[
			'user_id' => 'required',
			'lng' => 'required',
			'symp_id' => 'required',
			// 'assess_id' => 'required',
			// 'suggestionId' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$page = null;
			if(!empty($user_array['lng'])) {
				$page = DB::table('pages')->select('description')->where(["status"=>1,'slug'=>'mh-week-after-quiz'])->where("lng",$user_array['lng'])->first();
			}
			if(empty($page)) {
				$page = DB::table('pages')->select('description')->where(["status"=>1,'slug'=>'mh-week-after-quiz'])->where("lng","en")->first();
			}
			if(!empty($user_array['suggestionId'])) {
				$qRange = MhQuesRange::with('MhResultType.MhWeeklyProgram.AssessmentOverview')->select('id','category_id')->where('id',$user_array['suggestionId'])->first();
			}
			else {
				$assessmen = AssesmentAnswer::where(['user_id'=>$user_array['user_id'],'symp_id'=>$user_array['symp_id']])->orderBy('created_at','DESC')->first();
				$qRange = MhQuesRange::with('MhResultType.MhWeeklyProgram.AssessmentOverview')->select('id','category_id')->where('id',$assessmen->id)->first();
			}
			$programId = 1;
			if(!empty($qRange)){
				if($qRange->MhResultType->MhWeeklyProgram->count()>0) {
					$programId = $qRange->MhResultType->MhWeeklyProgram[0]->AssessmentOverview[0]->id;
				}
			}
			
			$totalWeek = isset($qRange->MhResultType) ? $qRange->MhResultType->MhWeeklyProgram->count() : 0; 
			if($user_array['lng'] == 'hi'){
				$upperContent = $this->collectionWeekStartProgramHindi($user_array['symp_id'],$totalWeek);
			}
			else{
				$upperContent = $this->collectionWeekStartProgram($user_array['symp_id'],$totalWeek);
			}
			$res = ['overview-content'=>$upperContent." ".$page->description,'program_id'=>$programId];
			return $this->sendResponse($res, 'Fetch content Successfully',true);
		}
    }

	function collectionWeekStartProgramHindi($rsTypeId,$weekCount) {
		$content = NULL;
		switch($rsTypeId) { 
			case 440 : 
				$content = "<h1>चिंता को कम करें</h1><p class='week-mh'>.$weekCount.- चिंता से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br> वाणी का ".$weekCount."-सप्ताह कार्यक्रम चिंता प्रबंधन के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है, जैसे कि सचेतनता और तनाव प्रबंधन रणनीतियाँ।<p>";
			break;	

			case 1217 : 
				$content = "<h1>शैक्षणिक तनाव</h1><p class='week-mh'>.$weekCount.-शैक्षणिक तनाव से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br >वाणी का .$weekCount.-सप्ताह कार्यक्रम अकादमिक तनाव के प्रबंधन के लिए विभिन्न तरीकों को सिखाने, राहत और आशा का मार्ग प्रदान करने के लिए डिज़ाइन किया गया है।<p>";
			break;
			
			case 1218 : 
				$content = "<h1>खराब फोकस और एकाग्रता</h1><p class='week-mh'>.$weekCount.- खराब फोकस और एकाग्रता से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</ मजबूत><br>वाणी का ".$weekCount."-सप्ताह कार्यक्रम खराब फोकस और एकाग्रता में सुधार के लिए विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है।<p>";
			break;

			case 1219 : 
				$content = "<h1>रिश्ते के मुद्दे</h1><p class='week-mh'>.$weekCount.''-रिश्ते की समस्याओं से निपटने के लिए सप्ताह का रोडमैप</p><p>वाणी का .$weekCount.''-सप्ताह कार्यक्रम है रिश्तों को स्वस्थ और बेहतर रिश्तों में बदलने के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है। <p>";
			break;

			case 1220 : 
				$content = "<h1>निर्णय लेना</h1><p class='week-mh'>.$weekCount.-निर्णय लेने से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br >वाणी का ".$weekCount."-सप्ताह कार्यक्रम जीवन में प्रभावी निर्णय लेने के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है।<p>";
			break;

			case 1221 :
				$content = "<h1>व्यसन</h1><p class='week-mh'>.$weekCount.-व्यसन से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br>वाणी का ".$weekCount."-सप्ताह कार्यक्रम इंटरनेट की लत से निपटने के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है। <p>";
			break;

			case 1222 : 
				$content = "<h1>समायोजन मुद्दे</h1><p class='week-mh'>.$weekCount.-समायोजन मुद्दों से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br >वाणी का ".$weekCount."-सप्ताह कार्यक्रम नई जगहों और नए लोगों के साथ तालमेल बिठाने के विभिन्न तरीके सिखाने के लिए डिज़ाइन किया गया है।<p>";
			break;

			case 1223 : 
				$content = "<h1>आत्म-सम्मान</h1><p class='week-mh'>.$weekCount.'-आत्म-सम्मान से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong> <br>वाणी का ".$weekCount."-सप्ताह कार्यक्रम आत्म-सम्मान बढ़ाने के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है।<p>";
			break;

		default :
		$content = null;
		}
		return $content;
	}

	function collectionWeekStartProgram($rsTypeId,$weekCount) {
		$content = NULL;
		switch($rsTypeId) { 
			case 440 : 
				$content = "<h1>Ease Anxiety</h1><p class='week-mh'>".$weekCount."-week Roadmap to Cope Anxiety</p><p><strong>Program Overview</strong><br>Vaani's ".$weekCount."-week program is designed to teach various methods for managing anxiety, such as mindfulness and stress management strategies.<p>";
			break;	

			case 1217 : 
				$content = "<h1>Academic stress</h1><p class='week-mh'>".$weekCount."-week Roadmap to Cope Academic stress</p><p><strong>Program Overview</strong><br>Vaani's ".$weekCount."-week program is designed to teach various methods for managing academic stress, offering a path to relief and hope.<p>";
			break;
			
			case 1218 : 
				$content = "<h1>Poor focus and Concentration</h1><p class='week-mh'>".$weekCount."-week Roadmap to Cope Poor focus and Concentration</p><p><strong>Program Overview</strong><br>Vaani's ".$weekCount."-week program is designed to teach various methods for improving Poor focus and Concentration.<p>";
			break;

			case 1219 : 
				$content = "<h1>Relationship issues</h1><p class='week-mh'>".$weekCount."-week Roadmap to Cope Relationship issues</p><p>Vaani's ".$weekCount."-week program is designed to teach various methods of transforming relationships into healthy and better ones.<p>";
			break;

			case 1220 : 
				$content = "<h1>Decision Making</h1><p class='week-mh'>".$weekCount."-week Roadmap to Cope Decision Making</p><p><strong>Program Overview</strong><br>Vaani's ".$weekCount."-week program is designed to teach various methods for making effective decisions in life.<p>";
			break;

			case 1221 :
				$content = "<h1>Addiction</h1><p class='week-mh'>".$weekCount."-week Roadmap to Cope Addiction</p><p><strong>Program Overview</strong><br>Vaani's ".$weekCount."-week program is designed to teach various methods for dealing with internet addiction.<p>";
			break;

			case 1222 : 
				$content = "<h1>Adjustment Issues</h1><p class='week-mh'>".$weekCount."-week Roadmap to Cope Adjustment Issues</p><p><strong>Program Overview</strong><br>Vaani's ".$weekCount."-week program is designed to teach various methods for adjusting to new places and with new people.<p>";
			break;

			case 1223 : 
				$content = "<h1>Self-Esteem</h1><p class='week-mh'>".$weekCount."-week Roadmap to Cope Self-Esteem</p><p><strong>Program Overview</strong><br>Vaani's ".$weekCount."-week program is designed to teach various methods to boost self-esteem.<p>";
			break;

		default :
		$content = null;
		}
		return $content;
	}

	public function fetchWeeksResult(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['symp_id'] = $data->get('symp_id');
		$user_array['lng'] = $data->get('lng');


		$user_array['lng'] = $data->get('lng');
		$user_array['assess_id'] = $data->get('assess_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'lng' => 'required',
			// 'symp_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			if(!empty($user_array['assess_id'])) {
				$raw = AssesmentAnswer::with('MhWeeklyProgram.AssessmentOverview.MhProgramMatrix','MhQuesRange','MhWeeklyProgram.MhWpFeedback')->where('id',$user_array['assess_id'])->first();
			}
			else{
				$raw = AssesmentAnswer::with('MhWeeklyProgram.AssessmentOverview.MhProgramMatrix','MhQuesRange','MhWeeklyProgram.MhWpFeedback')->where('user_id',$user_array['user_id'])->where('symp_id',$user_array['symp_id'])->orderBy('created_at','DESC')->first();
			}
			$weeklyPArr = [];
			if(!empty($raw)) {
				$isData = MhProgramMatrix::where('user_id',$user_array['user_id'])->where('symp_id',$user_array['symp_id'])->count();
				if($raw->MhWeeklyProgram->count()>0) {
					foreach($raw->MhWeeklyProgram as $col) {
						if(count($col->AssessmentOverview) > 0) {
							foreach($col->AssessmentOverview as $line) {
								if(!empty($line->audio_file)){
									$line['audio_file_url'] = getPath("public/mh-audio-files/".$line->audio_file);
								}
								else{
									$line['audio_file_url'] = null;
								}
							}
							
							// mhProgramMatrix
							$matrixArr = null;
							if($line->MhProgramMatrix->count()>0){
								foreach($line->MhProgramMatrix as $matrix) {
									if($user_array['user_id'] == $matrix->user_id) {
										$matrixArr = $matrix;
									}
								}
							}
							unset($line->MhProgramMatrix);
							unset($line['MhProgramMatrix']);
							$line['mh_program_matrix'] = $matrixArr;
						}
						$wpFeedbackArr = null;
						if(count($col->MhWpFeedback)>0) {
							foreach($col->MhWpFeedback as $feed){
								if($feed->user_id == $user_array['user_id']){
									$wpFeedbackArr = $feed;
								}
							}
						}
						unset($col->MhWpFeedback);
						$col['mh_wp_feedback'] = $wpFeedbackArr;


						$col['icon_url'] = getPath("public/mh-weekly-icons/".$col->icon);
						if($raw->MhQuesRange->category_id == $col->s_type) {
							$weeklyPArr[] = $col;
						}
					}
				}
				

				if($raw->symp_id == 1220) {
					$content = DB::table('pages')->where(["status"=>1,'slug'=>'decision-making-low-score'])->where("lng",$user_array['lng'])->first();
				}
				if($raw->symp_id == 1223) {
					$content = DB::table('pages')->where(["status"=>1,'slug'=>'self-steem-high'])->where("lng",$user_array['lng'])->first();
				}
				if($raw->symp_id == 1222) {
					$content = DB::table('pages')->where(["status"=>1,'slug'=>'adjustment-issue'])->where("lng",$user_array['lng'])->first();
				}
				if($raw->symp_id == 1218) {
					$content = DB::table('pages')->where(["status"=>1,'slug'=>'poor-focus-high-score'])->where("lng",$user_array['lng'])->first();
				}
				$raw['mh_weekly_program_data'] = $weeklyPArr; 
				if(isset($content) && !empty($content) && count($weeklyPArr) == 0) {
					$raw['mh_normal'] = $content->description;
				}
				$raw['is_program_start'] = $isData > 0 ? 1 : 0; 
			}
			return $this->sendResponse($raw, 'Fetch Successfully',true);
		}
	}
	public function checkweekShow(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$assessment =  AssesmentAnswer::where(['user_id'=>$user_array['user_id']])->count();
		$isStrt = 0;
		if($assessment > 0){
			$isStrt = 1;
		}
		return $this->sendResponse($isStrt, 'Fetch Weeks Successfully',true);
	}
	public function insertMood(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['ques_1'] = $data->get('ques_1');
		$user_array['ques_2'] = $data->get('ques_2');
		$user_array['message'] = $data->get('message');
		$user_array['mood'] = $data->get('mood');
		$user_array['lng'] = $data->get('lng');
		$user_array['id'] = $data->get('id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'mood' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {	
			if(!empty($user_array['id'])) {
				MhMood::where('id',$user_array['id'])->update([
					'mood' => $user_array['mood'],
					'ques_1' => $user_array['ques_1'],
					'ques_2' => $user_array['ques_2'],
					'message' => $user_array['message']
				]);
				$res = MhMood::where('id',$user_array['id'])->first();
			}
			else{
				$res = MhMood::create([
					'user_id' => $user_array['user_id'],
					'ques_1' => $user_array['ques_1'],
					'ques_2' => $user_array['ques_2'],
					'message' => $user_array['message'],
					'mood' => $user_array['mood']
				]);
			}
			return $this->sendResponse($res, 'Submit Successfully',true);
		}
	}

	public function moodHistory(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$res = MhMood::where(['user_id' => $user_array['user_id']])->paginate(10);
			return $this->sendResponse($res, 'Fetch Successfully',true);
		}
	}

	public function pushWeeklyProgram(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['mhp_mid'] = $data->get('mhp_mid');
		$user_array['program_id'] = $data->get('program_id');
		$user_array['task_value'] = $data->get('task_value');
		$user_array['sheet_title'] = $data->get('sheet_title');
		$user_array['id'] = $data->get('id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'program_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			if(empty($user_array['mhp_mid'])){
				$matrix = MhProgramMatrix::select('id')->where('user_id',$user_array['user_id'])->where('program_id',$user_array['program_id'])->first();
				$user_array['mhp_mid'] = $matrix->id;
			}
			if(!empty($user_array['id'])){
				MhWeeklyTask::where('id',$user_array['id'])->update([
					'task_value' => json_encode($user_array['task_value']),
				]);
				$res = MhWeeklyTask::where('id',$user_array['id'])->first();
			}
			else{
				$res = MhWeeklyTask::create([
					'user_id' => $user_array['user_id'],
					'program_id' => $user_array['program_id'],
					'mhp_mid' => $user_array['mhp_mid'],
					'sheet_title' => $user_array['sheet_title'],
					'task_value' => json_encode($user_array['task_value']),
				]);
			}
			return $this->sendResponse($res, 'Submit Successfully',true);
		}
	}

	public function insertWeeklyFeedback(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['week_id'] = $data->get('week_id');
		$user_array['program_id'] = $data->get('program_id');
		$user_array['ques_1'] = $data->get('ques_1');
		$user_array['ques_2'] = $data->get('ques_2');
		$user_array['ques_3'] = $data->get('ques_3');
		$user_array['ques_4'] = $data->get('ques_4');
		
		if($data->get('ques_1') == "No"){
			$user_array['ques_1'] = 0;
		}
		if($data->get('ques_1') == "Yes"){
			$user_array['ques_1'] = 1;
		}
        if($data->get('ques_2') == "No"){
			$user_array['ques_2'] = 0;
		}
        if($data->get('ques_2') == "Yes"){
			$user_array['ques_2'] = 1;
		}
		if($data->get('ques_3') == "Not Satisfied"){
			$user_array['ques_3'] = 0;
		}
        if($data->get('ques_3') == "Satisfied"){
			$user_array['ques_3'] = 1;
		}
		if($data->get('ques_4') == "no"){
			$user_array['ques_4'] = 0;
		}
        if($data->get('ques_4') == "yes"){
			$user_array['ques_4'] = 1;
		}
	
		$user_array['comment'] = $data->get('comment');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'week_id' => 'required',
			'program_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$res = MhWpFeedback::create([
				'user_id' => $user_array['user_id'],
				'program_id' => $user_array['program_id'],
				'week_id' => $user_array['week_id'],
				'ques_1' => $user_array['ques_1'],
				'ques_2' => $user_array['ques_2'],
				'ques_3' => $user_array['ques_3'],
				'ques_4' => $user_array['ques_4'],
				'comment' => $user_array['comment'],
			]);
			return $this->sendResponse($res, 'Feedback Submitted Successfully',true);
		}
	}

	public function insertJournal(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['meta'] = $data->get('meta');
		$user_array['id'] = $data->get('id');
		$user_array['thought_id'] = $data->get('thought_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'meta' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$res = null;
			if(!empty($user_array['id'])) {
				MhJournal::where('id',$user_array['id'])->update([
					'meta' => $user_array['meta'],
					'thought_id' => $user_array['thought_id']
				]);
			}
			else {
				$res = MhJournal::create([
					'user_id' => $user_array['user_id'],
					'thought_id' => $user_array['thought_id'],
					'meta' => $user_array['meta']
				]);
			}
			return $this->sendResponse($res, 'Journal Submitted Successfully',true);
		}
	}

	public function journalHistory(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$res = MhJournal::with('MhJournalThought')->where(['user_id' => $user_array['user_id']])->paginate(10);
			return $this->sendResponse($res, 'Journal Fetch Successfully',true);
		}
	}

	public function commonAudio(Request $request) {	
		$data=Input::json();
  		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		$validator = Validator::make($user_array, [
			'lng' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$audio_file = (($user_array['lng'] == "hi") ? 'audio_file_hindi as audio_file' : 'audio_file');
			$title = (($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title');
			$res = MhCommonAudio::select('id',$title,'tot_listeners',$audio_file)->get();
			if($res->count()>0) {
				foreach($res as $raw) {
					$filePath = public_path("mh-audio-files/common/".$raw->audio_file);
					$raw['audio_url'] = getPath("public/mh-audio-files/common/".$raw->audio_file);
					$raw['tot_listeners'] = formatNumber($raw['tot_listeners']);
				}
			}
			return $this->sendResponse($res, 'Audios Fetch Successfully',true);
		}
	}
	public function updateAudioListen(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['audio_id'] = $data->get('audio_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'audio_id' => 'required'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$audio = MhCommonAudio::where('id',$user_array['audio_id'])->first();
			MhCommonAudio::where('id',$user_array['audio_id'])->update([
				'tot_listeners' => $audio->tot_listeners + 1
			]);
			return $this->sendResponse(null, 'Updated Successfully',true);
		}
	}

	public function fetchMentalDashboard(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['slug'] = $data->get('slug');
		$user_array['lng'] = $data->get('lng');
		$user_array['user_id'] = $data->get('user_id');

		$symptom = (($user_array['lng'] == "hi") ? 'symptom_hindi as symptom' : 'symptom');
		$description = (($user_array['lng'] == "hi") ? 'description_hindi as description' : 'description');
		$treatment = (($user_array['lng'] == "hi") ? 'treatment_hindi as treatment' : 'treatment');
		$cause = (($user_array['lng'] == "hi") ? 'cause_hindi as cause' : 'cause');
		$strategy = (($user_array['lng'] == "hi") ? 'strategy_hindi as strategy' : 'strategy');
		$assess_program = (($user_array['lng'] == "hi") ? 'assess_program_hindi as assess_program' : 'assess_program');
		$symp_details = (($user_array['lng'] == "hi") ? 'symp_details_hindi as symp_details' : 'symp_details');
		$pointerSymp = Symptoms::select('id','icon',$symptom,$description,$treatment,$cause,$strategy,$assess_program,$symp_details)->where('mh_status',1)->Where(['status'=>1])->get();
		$isData = 0;
		if($pointerSymp->count()>0){
			foreach($pointerSymp as $sym){
				$sym['icon_url'] = url("/")."/public/symptom-icons/".$sym->icon;
			}
		}
		$user_id = $user_array['user_id'];
		$raw = AssesmentAnswer::with([
			'MhWeeklyProgram.AssessmentOverview.MhProgramMatrix',
			'MhWeeklyProgram.MhResultType',
			'MhWeeklyProgram.MhWpFeedback',
			'MhQuesRange'
		])->where('user_id', $user_array['user_id'])
		->orderBy('created_at', 'DESC')
		->first();
		
		$weeklyPArr = [];
		if(!empty($raw)) {
			// $isData = MhProgramMatrix::where('user_id',$user_array['user_id'])->count();
			// if($isData > 0) {
				if($raw->MhWeeklyProgram->count()>0) {
					foreach($raw->MhWeeklyProgram as $col) {
						$isComplete = 0;
						if(count($col->AssessmentOverview) > 0) {
							foreach($col->AssessmentOverview as $line) {
								if(!empty($line->audio_file)){
									$line['audio_file_url'] = getPath("public/mh-audio-files/".$line->audio_file);
								}
								else{
									$line['audio_file_url'] = null;
								}
								$matrixArr = null;
								if($line->MhProgramMatrix->count()>0){
									foreach($line->MhProgramMatrix as $matrix) {
										if($user_id == $matrix->user_id) {
											$matrixArr = $matrix;
										}
									}
								}
								unset($line->MhProgramMatrix);
								unset($line['MhProgramMatrix']);
								$line['mh_program_matrix'] = $matrixArr;
							}
						}
						$wpFeedbackArr = null;
						if(count($col->MhWpFeedback)>0) {
							foreach($col->MhWpFeedback as $feed){
								if($feed->user_id == $user_id){
									$wpFeedbackArr = $feed;
								}
							}
						}
						unset($col->MhWpFeedback);
						$col['mh_wp_feedback'] = $wpFeedbackArr;
						$col['icon_url'] = getPath("public/mh-weekly-icons/".$col->icon);
						if(isset($raw->MhQuesRange) && $raw->MhQuesRange->category_id == $col->s_type) {
							$weeklyPArr[] = $col;
						}
					}
				}
			// }
			if($raw->symp_id == 1220) {
				$content = DB::table('pages')->where(["status"=>1,'slug'=>'decision-making-low-score'])->where("lng",$user_array['lng'])->first();
			}
			if($raw->symp_id == 1223) {
				$content = DB::table('pages')->where(["status"=>1,'slug'=>'self-steem-high'])->where("lng",$user_array['lng'])->first();
			}
			if($raw->symp_id == 1222) {
				$content = DB::table('pages')->where(["status"=>1,'slug'=>'adjustment-issue'])->where("lng",$user_array['lng'])->first();
			}
			if($raw->symp_id == 1218) {
				$content = DB::table('pages')->where(["status"=>1,'slug'=>'poor-focus-high-score'])->where("lng",$user_array['lng'])->first();
			}
			if(isset($content) && !empty($content) && count($weeklyPArr) == 0) {
				$raw['mh_normal'] = $content->description;
			}
		}

		if(!empty($user_array['lng'])) {
			$page = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug']])->where("lng",$user_array['lng'])->first();
		}
		else {
			$page = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug']])->where("lng","en")->first();
		}

		$audio_file = (($user_array['lng'] == "hi") ? 'audio_file_hindi as audio_file' : 'audio_file');
		$title = (($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title');
		$audios = MhCommonAudio::select('id','tot_listeners',$title,$audio_file)->get();
		if($audios->count()>0) {
			foreach($audios as $aud) {
				$filePath = public_path("mh-audio-files/common/".$aud->audio_file);
				$aud['audio_url'] = getPath("public/mh-audio-files/common/".$aud->audio_file);
				$aud['tot_listeners'] = formatNumber($aud['tot_listeners']);
			}
		}
		$isJournal = MhJournal::select('id')->where(['user_id' => $user_array['user_id']])->count();
		$journalData = $isJournal > 0 ? true : false;
		$tMood = MhMood::where(['user_id' => $user_array['user_id']])->whereDate('created_at',date('Y-m-d'))->first();
		$result = UsersSubscriptions::with("UserSubscribedPlans","PlanPeriods")->where('user_id',$user_array['user_id'])->where('order_status','1')->whereHas('PlanPeriods', function($q) {
			$q->Where('status', 1);
		})->count();
		$isSubs = $result > 0 ? true : false;
		$title = (($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title');
		$thought = MhJournalThought::inRandomOrder()->select('id',$title)->first();
		$dtxt = $user_array['lng'] == "hi" ? '<h2><strong>मन मित्र</strong><span>कल्याण और विकास का मार्ग</span></h2>' : '<h2><strong>Mann Mitra:</strong><span>Pathway to Wellness and Growth</span></h2>';

		$mArr = ['pointerSymp'=>$pointerSymp,'page'=>$page,'audios'=>$audios,'weekly_programs'=>$weeklyPArr,'is_journal'=>$journalData,'today_mood'=>$tMood,'is_subscribed'=>$isSubs,'journal_thought'=>@$thought,'dtxt'=>$dtxt];
		return $this->sendResponse($mArr, '',true);
    }

	public function mhTracker(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['c_date'] = $data->get('c_date');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'c_date' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			if(empty($user_array['c_date'])) {
				$user_array['c_date'] = date('Y-m-d');
			}
			$res = MhTracker::where('user_id',$user_array['user_id'])->whereDate('s_date',date('Y-m-d',strtotime($user_array['c_date'])))->first();
			return $this->sendResponse($res, 'Tracker Fetch Successfully',true);
		}
	}

	public function updateMhTracker(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['s_date'] = $data->get('s_date');
		$user_array['sleep_cycle'] = $data->get('sleep_cycle');
		$user_array['exercise'] = $data->get('exercise');
		$user_array['energy_level'] = $data->get('energy_level');
		$user_array['id'] = $data->get('id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			// 's_date' => 'required',
			// 'sleep_cycle' => 'required',
			// 'exercise' => 'required',
			// 'energy_level' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			if(!empty($user_array['id'])) {
				MhTracker::where('id',$user_array['id'])->update([
					'sleep_cycle' => $user_array['sleep_cycle'],
					'exercise' => $user_array['exercise'],
					'energy_level' => $user_array['energy_level'],
				]);
				$res = MhTracker::where('id',$user_array['id'])->first();
			}
			else {
				if(empty($user_array['s_date'])){
					$user_array['s_date'] = date('Y-m-d');
				}
				$res = MhTracker::create([
					'user_id' => $user_array['user_id'],
					's_date' => $user_array['s_date'],
					'sleep_cycle' => $user_array['sleep_cycle'],
					'exercise' => $user_array['exercise'],
					'energy_level' => $user_array['energy_level'],
				]);
			}
			return $this->sendResponse($res, 'Mh Tracker Updated Successfully',true);
		}
	}



	public function commonSheet(Request $request) {
		$data = Input::json();
		  $user_array = array();
		$user_array['lng'] = $data->get('lng');
		$user_array['user_id'] = $data->get('user_id');
	
		$validator = Validator::make($user_array, [
			'lng' => 'required'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		} else {
			$title = ($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title';
			$desc = ($user_array['lng'] == "hi") ? 'description_hindi as description' : 'description';
			$res = MhCommonSheet::select('id', $title, $desc, 'sheet_name', 'symp_id' , 'icon')
				->with('Symptoms')
				->where('status', 1)
				->get();
	
			$iconBasePath = url('/').'/public/mh-weekly-icons/common-icons/';
	
			if($res->count() > 0) {
				foreach($res as $sheet) {
					$sheet['icon_url'] = $iconBasePath . $sheet->icon;
					$isData = MhSheetData::where(['user_id'=>$user_array['user_id'],'sheet_id'=>$sheet->id])->count();
					$sheet['is_data_exist'] = $isData > 0 ? true : false;
				}
			}
			return $this->sendResponse($res, 'Submit Successfully', true);
		}
	}
	


	public function insertSheetData(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['sheet_id'] = $data->get('sheet_id');
		$user_array['sheet_value'] = $data->get('sheet_value');
		$user_array['id'] = $data->get('id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'sheet_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {	
			if(!empty($user_array['id'])){
				MhSheetData::where('id',$user_array['id'])->update([
					'sheet_value' => json_encode($user_array['sheet_value'])
				]);
			}
			else{
				MhSheetData::create([
					'user_id' => $user_array['user_id'],
					'sheet_id' => $user_array['sheet_id'],
					'sheet_value' => json_encode($user_array['sheet_value']),
				]);
			}
			return $this->sendResponse('', 'Submit Successfully',true);
		}
	}

	public function deleteJournal(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['id'] = $data->get('id');
		$validator = Validator::make($user_array, [
			'id' => 'required'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			MhJournal::where('id',$user_array['id'])->delete();
			return $this->sendResponse('', 'Deleted Successfully',true);
		}
	}

	public function randomPrompt(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		$validator = Validator::make($user_array, [
			'lng' => 'required'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$title = (($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title');
			$thought = MhJournalThought::inRandomOrder()->select('id',$title)->first();
			return $this->sendResponse($thought, 'Fetch Successfully',true);
		}
	}
	
	public function getMentalHealthPlan(Request $request) {
		$data = Input::json();
		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		$user_array['user_id'] = $data->get('user_id');
		$validator = Validator::make($user_array, [
			// 'lng' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$response = [];
			if($user_array['lng'] == "hi") {
				$plans = Plans::whereIn('id',[46])->orderBy('price', 'ASC')->get();
			}
			else{
				$plans = Plans::whereIn('id',[45])->orderBy('price', 'ASC')->get();
			}
			$result = UsersSubscriptions::with("UserSubscribedPlans","PlanPeriods")->where('user_id',$user_array['user_id'])->where('order_status','1')->whereHas('PlanPeriods', function($q) {
			$q->Where('status', 1);
			})->count();
			if($result > 0){
				foreach($plans as $raw) {
					$raw->price = 7500;
					$raw->discount_price = 5000;
				}
			}			
			return $this->sendResponse($plans,'',true);
		}
	}
	
	public function fetchCommonSheetsByUserId(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['sheet_id'] = $data->get('sheet_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'sheet_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {	
			$sheets = MhSheetData::where(['user_id'=>$user_array['user_id'],'sheet_id'=>$user_array['sheet_id']])->orderBy("id","DESC")->get();
			if(count($sheets) > 0) {
				foreach($sheets as $raw) {
					$raw->sheet_value = json_decode($raw->sheet_value);
				}
			}
			return $this->sendResponse($sheets, 'Fetch Successfully',true);
		}
	}
	
	public function fetchMHSlider(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['lng'] = $data->get('lng');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'lng' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			if($user_array['lng'] == 'hi') {
				$arr[] = ['title' => 'इंटेलिजेंट मूड ट्रैकिंग और भावना सुधार','icon' => url("/")."/css/img/screen-informative-1.png"];
				$arr[] = ['title' => 'मानसिक स्वास्थ्य जर्नल और सेल्फ रिफ्लेक्शन','icon' => url("/")."/css/img/screen-informative-2.png"];
				$arr[] = ['title' => 'माइंडफुल रेसौर्सेस जो आपको खुश करते हैं','icon' => url("/")."/css/img/screen-informative-3.png"];
				$arr[] = ['title' => 'सामान्य चिंताओं पर लेखआर्टिकल','icon' => url("/")."/css/img/screen-informative-4.png"];
				$arr[] = ['title' => 'पर्सनलाइज़ योर मेन्टल हेल्थ स्टेट-साप्ताहिक प्रोग्राम','icon' => url("/")."/css/img/screen-informative-5.png"];
			}
			else{
				$arr[] = ['title' => 'Intelligent Mood Tracking & Emotion Improvement','icon' => url("/")."/css/img/screen-informative-1.png"];
				$arr[] = ['title' => 'Mental Health Journal & Self-Reflection','icon' => url("/")."/css/img/screen-informative-2.png"];
				$arr[] = ['title' => 'Mindful Resources That Make You Happy','icon' => url("/")."/css/img/screen-informative-3.png"];
				$arr[] = ['title' => 'Articles On Common Concerns','icon' => url("/")."/css/img/screen-informative-4.png"];
				$arr[] = ['title' => 'Personalize Your Mental Health State-Weekly Program','icon' => url("/")."/css/img/screen-informative-5.png"];
			}
			return $this->sendResponse($arr, 'Fetch Successfully',true);
		}
	}
	
	public function fetchJournalUrl(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['journal_id'] = $data->get('journal_id');
			
			$validator = Validator::make($user_array, [
				'journal_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$jd = MhJournal::where(['id'=>$user_array['journal_id']])->orderBy("id","Desc")->first();
				
				$jdata = view('pages.pdfFiles.journalPDF',compact('jd'))->render();

				$output = PDF::loadHTML($jdata)->output();
				file_put_contents(public_path()."/htmltopdfview.pdf", $output);
				$pdf_url = 	url("/")."/public/htmltopdfview.pdf?.".time();
				return $this->sendResponse($pdf_url,'',true);	
			}
		}
	}

	public function fetchArticle(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['_from'] = $data->get('_from');
			$user_array['doc_id'] = $data->get('doc_id');
			
			$validator = Validator::make($user_array, [
				
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$query = NewsFeeds::whereRaw("find_in_set('1',news_feeds.type)")->where('is_mh',$user_array['_from'])->whereDate('publish_date', '<=', date("Y-m-d"));
				if(!empty($user_array['doc_id'])) {
					$query->where('doctor_id',$user_array['doc_id']);
				}
				$nfs = $query->orderBy('show_date', 'DESC')->paginate(10);
				if($nfs->count() > 0) {
					foreach($nfs as $raw) {
						$raw['image'] = url("/")."/public/newsFeedFiles/".$raw['image'];
					}
				}
				return $this->sendResponse($nfs,'Article Fetch Successfully',true);	
			}
		}
	}
	
	public function fetchConsData(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['mobile_no'] = $data->get('mobile_no');
			$user_array['pId'] = $data->get('pId');
			$user_array['user_id'] = $data->get('user_id');
			$user_array['slug'] = $data->get('slug');
			$user_array['lng'] = $data->get('lng');
			$user_array['is_subscribed'] = $data->get('is_subscribed');
			
			$validator = Validator::make($user_array, [
				'mobile_no'   => 'required',
				'user_id'   => 'required',
				'slug'   => 'required',
				'lng'   => 'required',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				if($user_array['is_subscribed'] == 1) {
					if($user_array['lng'] == "hi") {
						$plans = Plans::whereIn('id',[46])->orderBy('price', 'ASC')->get();
					}
					else{
						$plans = Plans::whereIn('id',[45])->orderBy('price', 'ASC')->get();
					}
				}
				else{
					if($user_array['lng'] == "hi") {
						$plans = Plans::whereIn('id',[49])->orderBy('price', 'ASC')->get();
					}
					else{
						$plans = Plans::whereIn('id',[48])->orderBy('price', 'ASC')->get();
					}
				}
				/*if($user_array['lng'] == "hi") {
					if($user_array['is_subscribed'] != 1){
						$plans = Plans::whereIn('id',[46])->orderBy('price', 'ASC')->get();
					}
					else{
						$plans = Plans::whereIn('id',[42,46])->orderBy('price', 'ASC')->get();
					}
				}
				else{
					if($user_array['is_subscribed'] != 1) {
						$plans = Plans::whereIn('id',[45])->orderBy('price', 'ASC')->get();
					}
					else{
						$plans = Plans::whereIn('id',[41,45])->orderBy('price', 'ASC')->get();
					}
				}
				if($user_array['is_subscribed'] == 1) {
					foreach($plans as $raw) {
						$raw->price = 7500;
						$raw->discount_price = 5000;
					}
				}*/
				$page = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug']])->where("lng",$user_array['lng'])->first();
				$page2 = DB::table('pages')->where(["status"=>1,'slug'=>'counselling-page-data'])->where("lng",$user_array['lng'])->first();
				$response = [];

				$isFreeAppt = false;
				$isFirstFreeAppt = false;
				$main_price = 1000;
				$fee = 1000;
				$available = 0;

				if(!empty($user_array['mobile_no'])) {
					$p_ids = User::select("pId")->where(["mobile_no"=>$user_array['mobile_no']])->pluck("pId")->toArray();
					$appointment = Appointments::whereIn('pID',$p_ids)->where(["delete_status"=>1,"appointment_confirmation"=>1,"type"=>3])->count();
					if($appointment == 0) {
						$isFirstFreeAppt = true;
						$isFreeAppt = true;
					}
					else {
						$isFirstFreeAppt = false;
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
				if($user_array['is_subscribed'] == 1) {
					$isFreeAppt = true;
				}
				$arr = ["fee"=>$fee,"main_price"=>$main_price,"available"=>$available,"isFreeAppt"=>$isFreeAppt,"isFirstFreeAppt"=>$isFirstFreeAppt,'plans'=>$plans, 'content'=>$page,'content2'=>$page2];
				return $this->sendResponse($arr,'Data Fetch Successfully',true);	
			}
		}
	}
}
