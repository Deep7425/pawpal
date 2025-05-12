<?php

namespace App\Http\Controllers\Mental;

use App\Http\Controllers\Controller;
use App\Models\Admin\Symptoms;
use App\Models\AssesmentAnswer;
use App\Models\ehr\Appointments;
use App\Models\LabOrders;
use App\Models\MhCommonAudio;
use App\Models\MhCommonSheet;
use App\Models\MhJournal;
use App\Models\MhJournalThought;
use App\Models\MhMood;
use App\Models\MhProgramMatrix;
use App\Models\MhQuesRange;
use App\Models\MhSheetData;
use App\Models\MhTracker;
use App\Models\MhWeeklyProgram;
use App\Models\MhWeeklyTask;
use App\Models\MhWpFeedback;
use App\Models\NewsFeeds;
use App\Models\Doctors;
use App\Models\Plans;
use App\Models\PreAssesmentAnswer;
use App\Models\PreAssessmentQues;
use App\Models\Quizquestion;
use App\Models\User;
use App\Models\VhtOrder;
use App\Models\Settings;
use App\Models\UsersSubscriptions;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;

class MentalHealthController extends Controller
{
    public function showSympHighlight(Request $request)
    {

        $user_array = [];
        $user_array['slug'] = $request->input('slug', 'default-slug');
        $user_array['lng'] = $request->input('lng', 'en');
        $user_array['user_id'] = $request->input('user_id', 0);
        $symptom = ($user_array['lng'] == "hi") ? 'symptom_hindi as symptom' : 'symptom';
        $description = ($user_array['lng'] == "hi") ? 'description_hindi as description' : 'description';
        $treatment = ($user_array['lng'] == "hi") ? 'treatment_hindi as treatment' : 'treatment';
        $cause = ($user_array['lng'] == "hi") ? 'cause_hindi as cause' : 'cause';
        $strategy = ($user_array['lng'] == "hi") ? 'strategy_hindi as strategy' : 'strategy';
        $assess_program = ($user_array['lng'] == "hi") ? 'assess_program_hindi as assess_program' : 'assess_program';
        $symp_details = ($user_array['lng'] == "hi") ? 'symp_details_hindi as symp_details' : 'symp_details';
        $pointerSymp = Symptoms::select('id', 'icon', $symptom, $description, $treatment, $cause, $strategy, $assess_program, $symp_details)
            ->with(['SymptomsSpeciality', 'SymptomTags'])
            ->where('mh_status', 1)
            ->where('status', 1)
            ->get();
        if ($pointerSymp->count() > 0) {
            foreach ($pointerSymp as $raw) {
                $raw['is_quiz_done'] = 0;

                $assessment = AssesmentAnswer::where(['user_id' => $user_array['user_id'], 'symp_id' => $raw->id])
                    ->orderBy('created_at', 'ASC')
                    ->count();

                if ($assessment > 0) {
                    $raw['is_quiz_done'] = 1;
                }
            }
        }
        if (!empty($user_array['lng'])) {
            $page = DB::table('pages')
                ->where(['status' => 1, 'slug' => $user_array['slug']])
                ->where('lng', $user_array['lng'])
                ->first();
        } else {
            $page = DB::table('pages')
                ->where(['status' => 1, 'slug' => $user_array['slug']])
                ->where('lng', 'en')
                ->first();
        }
        $mArr = ['pointerSymp' => $pointerSymp, 'page' => $page];
        return view('mental.symptom-higehlight', $mArr);
    }
    public function fetchAssesmentQues(Request $request)
    {

        if (Auth::user() == null) {
            Session::put('loginFrom', '10');
            Session::put('symp_id', $request->input('symp_id'));
            return redirect()->route('login');
        }


        $sympId = $request->input('symp_id');
        $symptom = Symptoms::where('id', $sympId)->first();

        $ques = Quizquestion::select('id', 'question as ques', 'optionA', 'optionB', 'optionC', 'optionD', 'optionE', 'optionF', 'optionA_val', 'optionB_val', 'optionC_val', 'optionD_val', 'optionE_val', 'optionF_val')->where('symptom_id', $sympId)->get();

        return view('mental.assessment-ques', compact('ques', 'sympId'));
    }
    public function viewAssesmentQues($slug)
    {
     
        $symptom = Symptoms::where('slug', $slug)->first();
        $sympId = $symptom->id;
     
        return view('mental.symptom-details', compact('sympId', 'symptom'));
    }
    public function fetchPreAssesmentQues(Request $request)
    {
        $ques = PreAssessmentQues::select('id', 'question as ques', 'optionA', 'optionB', 'optionC', 'optionD', 'optionE')->get();
        return view('mental.pre-assessment', compact('ques'));
    }
    public function saveAssesment(Request $request)
    {

        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['symp_id'] = $request->input('symp_id');
        $user_array['quesArr'] = $request->input('quesArr');
        $user_array['lng'] = $request->input('lng');

        $quesArr = $user_array['quesArr'];
        $totScore = 0;
        if (count($quesArr) > 0) {
            foreach ($quesArr as $raw) {
                $totScore += $raw['ques_val'];
            }
        }

        $result = $this->calculateResult($quesArr, $user_array['symp_id']);
        $res = AssesmentAnswer::create([
            'user_id' => $user_array['user_id'],
            'symp_id' => $user_array['symp_id'],
            'total_score' => $result['score'],
            'mental_status' => $result['category'],
            'suggestion' => $result['suggestion'],
            'meta_data' => json_encode($user_array['quesArr']),
            'score_data' => count($result['scoreData']) > 0 ? json_encode($result['scoreData']) : NULL
        ]);
        $type =  'type';
        $category = 'category';
        $suggestion = 'suggestion';
        if (!empty($res->suggestion)) {
            $res['suggestion_id'] = $res->suggestion;
            $qRangeData = MhQuesRange::with("MhResultType")->select('id', 'category_id', $suggestion)->where('id', $res->suggestion)->first();
            $res->suggestion = $qRangeData->suggestion;
            $res->mental_status = $qRangeData->MhResultType->title;
        }
        if (!empty($res->score_data)) {
            $scoreData = json_decode($res->score_data, true);
            $newData = [];
            if (count($scoreData) > 0) {
                foreach ($scoreData as $raw) {
                    $qRange = MhQuesRange::select('id', $type, $category, $suggestion)->where('id', $raw['rawId'])->first();
                    $raw['suggestion'] = $qRange->suggestion;
                    $raw['category'] = $qRange->category;
                    $raw['type'] = $qRange->type;
                    $newData[] = $raw;
                }
            }
            $res->score_data = $newData;
        }
        return $this->sendResponse($res, 'Saved Successfully', true);
    }

    public function calculateResult($quesArr, $sympId)
    {

        $score = 0;
        $totScoreA = NULL;
        $totScoreB = NULL;
        $totScoreC = NULL;
        $totScoreD = NULL;
        $totScoreE = NULL;
        $totScoreF = NULL;
        if (count($quesArr) > 0) {
            foreach ($quesArr as $raw) {
                // dd($raw['ques_id']);
                if (in_array($raw['ques_id'], [105, 106, 107, 108, 109, 110, 111, 112])) {
                    $totScoreA += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [113, 114, 115, 116, 117, 118, 119])) {
                    $totScoreB += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [120, 121, 122, 123])) {
                    $totScoreC += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [124, 125, 126])) {
                    $totScoreD += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [127, 128, 129, 130, 131])) {
                    $totScoreE += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [132, 133, 134, 135, 136])) {
                    $totScoreF += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [94, 96, 97, 99, 103])) {
                    $totScoreA += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [85, 86, 98, 102, 104])) {
                    $totScoreB += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [90, 92, 93])) {
                    $totScoreC += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [91, 95])) {
                    $totScoreD += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [89, 100, 101])) {
                    $totScoreE += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [87, 88])) {
                    $totScoreF += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [65, 70, 75, 80])) {

                    $totScoreA += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [66, 71, 76, 81])) {
                    $totScoreB += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [67, 72, 77, 82])) {
                    $totScoreC += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [68, 73, 78, 83])) {
                    $totScoreD += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [69, 74, 79, 84])) {
                    $totScoreE += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [6, 9, 13, 14])) {
                    $totScoreA += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [4, 5, 10, 11, 12, 15, 16, 17])) {
                    $totScoreB += $raw['ques_val'];
                } else if (in_array($raw['ques_id'], [1, 2, 3, 7, 8, 18])) {
                    $totScoreC += $raw['ques_val'];
                } else {
                    $score += $raw['ques_val'];
                }
            }
        }
        if ($sympId == 1217 || $sympId ==  1219 || $sympId ==  1220 || $sympId == 1221) {
            $score = 0;
            $score = $totScoreA + $totScoreB + $totScoreC + $totScoreD + $totScoreE + $totScoreF;
        }
        $rangeData = MhQuesRange::where('symp_id', $sympId)->get();

        $cat = null;
        $rangeId = null;
        $scoreData = [];
        foreach ($rangeData as $raw) {
            if ($raw->ques_type == 1) {
                if ($score >= $raw->min_score && $score <= $raw->max_score) {
                    $cat = $raw->category;
                    $rangeId = $raw->id;
                }
            } else if ($raw->ques_type == 2) {
                if (!empty($totScoreA) && $raw->score_type == 1) {
                    if ($totScoreA >= $raw->min_score && $totScoreA <= $raw->max_score) {
                        $scoreData[] = ['rawId' => $raw->id, 'score' => $totScoreA];
                    }
                }
                if (!empty($totScoreB) && $raw->score_type == 2) {
                    if ($totScoreB >= $raw->min_score && $totScoreB <= $raw->max_score) {
                        $scoreData[] = ['rawId' => $raw->id, 'score' => $totScoreB];
                    }
                }
                if (!empty($totScoreC) && $raw->score_type == 3) {
                    if ($totScoreC >= $raw->min_score && $totScoreC <= $raw->max_score) {
                        $scoreData[] = ['rawId' => $raw->id, 'score' => $totScoreC];
                    }
                }
                if (!empty($totScoreD) && $raw->score_type == 4) {
                    if ($totScoreD >= $raw->min_score && $totScoreD <= $raw->max_score) {
                        $scoreData[] = ['rawId' => $raw->id, 'score' => $totScoreD];
                    }
                }
                if (!empty($totScoreE) && $raw->score_type == 5) {
                    if ($totScoreE >= $raw->min_score && $totScoreE <= $raw->max_score) {
                        $scoreData[] = ['rawId' => $raw->id, 'score' => $totScoreE];
                    }
                }
                if (!empty($totScoreF) && $raw->score_type == 6) {
                    if ($totScoreF >= $raw->min_score && $totScoreF <= $raw->max_score) {
                        $scoreData[] = ['rawId' => $raw->id, 'score' => $totScoreF];
                    }
                }
            }
        }
        return ['score' => $score, 'category' => $cat, 'suggestion' => $rangeId, 'scoreData' => $scoreData];
    }

    public function savePreAssesment(Request $request)
    {
        // 
        $user_array = [];

        $user_array['user_id'] = Auth::id();
        $user_array['quesArr'] = $request->input('questionArrayselections');
        $user_array['lng'] =  'en';

        $quesArr =     $user_array['quesArr'];
        $tot_score = 0;
        if (count($quesArr) > 0) {
            foreach ($quesArr as $raw) {
                $tot_score += $raw['ques_val'];
            }
        }
        $res = PreAssesmentAnswer::create([
            'user_id' => $user_array['user_id'],
            'meta_data' => json_encode($user_array['quesArr']),
            'total_score' => $tot_score,
        ]);
        if ($res->total_score >= 30 && $res->total_score <= 50) {
            $res['distress_level'] = "Severe";
            $res['distress_description'] = getTermsBySLug('very-high-psychological-distress', $user_array['lng']);
        } else if ($res->total_score >= 25 && $res->total_score <= 29) {
            $res['distress_level'] = "Mild";
            $res['distress_description'] = getTermsBySLug('high-psychological-distress', $user_array['lng']);
        } else if ($res->total_score >= 20 && $res->total_score <= 24) {
            $res['distress_level'] = "Moderate";
            $res['distress_description'] = getTermsBySLug('moderate-psychological-distress', $user_array['lng']);
        } else if ($res->total_score >= 10 && $res->total_score <= 19) {
            $res['distress_level'] = "Normal";
            $res['distress_description'] = getTermsBySLug('low-psychological-distress', $user_array['lng']);
        }
        // return ['status' => 1, 'data_response' => $res];
        return response()->json(['status' => 1, 'redirect_url' => route('mental.assessmentResult', ['score' => $res->total_score])]);
        // return $this->sendResponse($res, 'Saved Successfully',true);
    }



    public function assessmentQuesResult($score)
    {

        $res = AssesmentAnswer::where('id', $score)->first();
        $qRangeData = MhQuesRange::with("MhResultType")->where('id', $res->suggestion)->first();
        $res->suggestion = @$qRangeData->suggestion;
        $res->mental_status = @$qRangeData->MhResultType->title;


        return view('mental.assessment-ques-result', compact('res'));
    }



    public function assessmentResult($score)
    {
        $qry = Doctors::with(["docSpeciality", "DoctorRatingReviews"])->where(["delete_status" => 1, 'status' => 1, 'varify_status' => 1])->whereNotNull('speciality')->where("oncall_status", "!=", 0);
        $qry->limit(4)->get();
        return view('mental.assessmentResult', compact('score'));
    }

    public function saveAssesmentExp(Request $request)
    {
        $user_array = [];
        $user_array['assm_id'] = $request->input('assm_id');
        $user_array['feedback'] = $request->input('feedback');
        $user_array['feed_msg'] = $request->input('feed_msg');

        AssesmentAnswer::where('id', $user_array['assm_id'])->update([
            'feedback' => !empty($user_array['feedback']) ? $user_array['feedback'] : NULL,
            'feed_msg' => $user_array['feed_msg']
        ]);
        return $this->sendResponse('', 'Saved Successfully', true);
    }

    public function fetchSymptomById(Request $request)
    {

        $user_array = [];
        $user_array['sym_id'] = $request->input('sym_id');

        $symp = Symptoms::where('id', $user_array['sym_id'])->first();
        return $this->sendResponse($symp, 'Saved Successfully', true);
    }

    public function fetchMainAssessment(Request $request)
    {

        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['symp_id'] = $request->input('symp_id');
        $user_array['lng'] =     $request->input('lng');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'symp_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $mainAssessments = AssesmentAnswer::where('user_id', $user_array['user_id'])->where('symp_id', $user_array['symp_id'])->get();
            if (count($mainAssessments)) {
                foreach ($mainAssessments as $res) {
                    $type = (($user_array['lng'] == "hi") ? 'type_hindi as type' : 'type');
                    $category = (($user_array['lng'] == "hi") ? 'category_hindi as type' : 'category');
                    $suggestion = (($user_array['lng'] == "hi") ? 'suggestion_hindi as type' : 'suggestion');
                    if (!empty($res->suggestion)) {
                        $qRangeData = MhQuesRange::with("MhResultType")->select('id', 'category_id', $category, $suggestion)->where('id', $res->suggestion)->first();
                        $res->suggestion = $qRangeData->suggestion;
                        $res->mental_status = $user_array['lng'] == "hi" ? $qRangeData->MhResultType->title_hindi : $qRangeData->MhResultType->title;
                    }
                    if (!empty($res->score_data)) {
                        $scoreData = json_decode($res->score_data, true);
                        $newData = [];
                        if (count($scoreData) > 0) {
                            foreach ($scoreData as $raw) {
                                $qRange = MhQuesRange::select('id', $type, $category, $suggestion)->where('id', $raw['rawId'])->first();
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
            return $this->sendResponse($mainAssessments, 'Fetch Successfully', true);
        }
    }

    public function fetchAssessmentMetrix(Request $request)
    {
        if (Auth::user() == null) {
            Session::put('loginFrom', '9');
            return redirect()->route('login');
        }


        $auth = Auth::id();
        $user_array = [];
        $user_array['user_id'] = $auth;
        $user_array['lng']     = 'en';
        $user_array['slug']    = $request->input('slug');
        $user_array['symp_id'] = $request->input('symp_id');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'lng' => 'required',
        ]);

        $user_id = $user_array['user_id'];
        $mainAssessments = AssesmentAnswer::with('MhQuesRange.MhResultType')->where('user_id', $user_array['user_id'])->orderBy('symp_id', 'ASC')->get();
        if ($mainAssessments->count() > 0) {
            foreach ($mainAssessments as $raw) {
                $weekCount = 0;
                $weeklyPArr = 0;
                if (isset($raw->MhQuesRange) && !empty($raw->MhQuesRange->MhResultType)) {
                    $raw['mental_status'] = $user_array['lng'] == 'en' ? @$raw->MhQuesRange->MhResultType->title : @$raw->MhQuesRange->MhResultType->title_hindi;
                    $weeklyPrograms = MhWeeklyProgram::with("AssessmentOverview.MhProgramMatrix")->where("s_type", $raw->MhQuesRange->category_id)->get();
                    $weeklyPArr = $weeklyPrograms->count();
                    if ($weeklyPArr > 0) {
                        foreach ($weeklyPrograms as $wkp) {
                            $readAssesCount = 0;
                            if (count($wkp->AssessmentOverview) > 0) {
                                foreach ($wkp->AssessmentOverview as $aso) {
                                    if ($aso->MhProgramMatrix->count() > 0) {
                                        foreach ($aso->MhProgramMatrix as $matrix) {
                                            if ($user_array['user_id'] == $matrix->user_id) {
                                                if ($matrix->program_status == 2) {
                                                    $readAssesCount += 1;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            if (count($wkp->AssessmentOverview) == $readAssesCount) {
                                $weekCount += 1;
                            }
                        }
                    }
                }
                if ($user_array['lng'] == 'hi') {
                    $raw['program_title'] = $this->setTitleDescProgramHindi($raw->symp_id, $weeklyPArr, $weekCount)['title'];
                    $raw['program_desc'] = $this->setTitleDescProgramHindi($raw->symp_id, $weeklyPArr, $weekCount)['desc'];
                    $raw['icon'] = $this->setTitleDescProgramHindi($raw->symp_id, $weeklyPArr, $weekCount)['icon'];
                } else {
                    $raw['program_title'] = $this->setTitleDescProgram($raw->symp_id, $weeklyPArr, $weekCount)['title'];
                    $raw['program_desc'] = $this->setTitleDescProgram($raw->symp_id, $weeklyPArr, $weekCount)['desc'];
                    $raw['icon'] = $this->setTitleDescProgram($raw->symp_id, $weeklyPArr, $weekCount)['icon'];
                }

                if ($raw->symp_id == 1220) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'decision-making-low-score'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1223) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'self-steem-high'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1222) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'adjustment-issue'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1218) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'poor-focus-high-score'])->where("lng", $user_array['lng'])->first();
                }
                $raw['mh_normal'] = null;
                if (isset($content) && !empty($content) && $weeklyPArr == 0) {
                    $raw['mh_normal'] = $content->description;
                }
            }

           
        }
        return view('mental.enroll-metrix', compact('mainAssessments'));
    }

    function setTitleDescProgram($id, $totWeek, $completeWeekCount)
    {
        $titleDesc = [];
        $titleDesc['desc'] = "Reminder - You still need to start your weekly program. Let's begin your journey towards growth and well-being.";
        $titleDesc['title'] = NULL;
        $titleDesc['icon'] = NULL;
        if ($id == 440) {
            $titleDesc['title'] = $totWeek . "-week program for anxiety";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "Congratulations, you completed the " . $completeWeekCount . "-week program. Now, move towards the new week.";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/anxiety.png");
        } else if ($id == 1217) {
            $titleDesc['title'] = $totWeek . "-week program for academic stress";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "Congratulations, you completed the " . $completeWeekCount . "-week program. Now, move towards the new week.";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/academic-stress.png");
        } else if ($id == 1218) {
            $titleDesc['title'] = $totWeek . "-week program for poor concentration and focus";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "Congratulations, you completed the " . $completeWeekCount . "-week program. Now, move towards the new week.";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/poor-concentration.png");
        } else if ($id == 1219) {
            $titleDesc['title'] = $totWeek . "-week program for relationship issues";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "Congratulations, you completed the " . $completeWeekCount . "-week program. Now, move towards the new week.";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/relation-issue.png");
        } else if ($id == 1220) {
            $titleDesc['title'] = $totWeek . "-week program for decision making";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "Congratulations, you completed the " . $completeWeekCount . "-week program. Now, move towards the new week.";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/decision-making.png");
        } else if ($id == 1221) {
            $titleDesc['title'] = $totWeek . "-week program for addiction";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "Congratulations, you completed the " . $completeWeekCount . "-week program. Now, move towards the new week.";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/addiction.png");
        } else if ($id == 1222) {
            $titleDesc['title'] = $totWeek . "-week program for adjustment issues";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "Congratulations, you completed the " . $completeWeekCount . "-week program. Now, move towards the new week.";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/adjustment-issue.png");
        } else if ($id == 1223) {
            $titleDesc['title'] = $totWeek . "-week program self-esteem";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "Congratulations, you completed the " . $completeWeekCount . "-week program. Now, move towards the new week.";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/self-esteem.png");
        }
        return $titleDesc;
    }
    public function setTitleDescProgramHindi($id, $totWeek, $completeWeekCount)
    {
        $titleDesc = [];
        $titleDesc['desc'] = "रिमाइंडर - आपको अभी भी अपना साप्ताहिक कार्यक्रम शुरू करने की आवश्यकता है। आइए विकास और खुशहाली की दिशा में अपनी यात्रा शुरू करें।";
        $titleDesc['title'] = NULL;
        $titleDesc['icon'] = NULL;
        if ($id == 440) {
            $titleDesc['title'] = "चिंता के लिए " . $totWeek . " सप्ताह का कार्यक्रम";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "बधाई हो, आपने " . $completeWeekCount . " सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/anxiety.png");
        } else if ($id == 1217) {
            $titleDesc['title'] = "शैक्षणिक तनाव के लिए " . $totWeek . " सप्ताह का कार्यक्रम";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "बधाई हो, आपने " . $completeWeekCount . " सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/academic-stress.png");
        } else if ($id == 1218) {
            $titleDesc['title'] = "खराब एकाग्रता और फोकस के लिए " . $totWeek . " सप्ताह का कार्यक्रम";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "बधाई हो, आपने " . $completeWeekCount . " सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/poor-concentration.png");
        } else if ($id == 1219) {
            $titleDesc['title'] = "रिश्ते के मुद्दों के लिए " . $totWeek . " सप्ताह का कार्यक्रम";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "बधाई हो, आपने " . $completeWeekCount . " सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/relation-issue.png");
        } else if ($id == 1220) {
            $titleDesc['title'] = "निर्णय लेने के लिए " . $totWeek . "5 सप्ताह का कार्यक्रम";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "बधाई हो, आपने " . $completeWeekCount . " सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/decision-making.png");
        } else if ($id == 1221) {
            $titleDesc['title'] = "व्यसन के लिए " . $totWeek . " सप्ताह का कार्यक्रम";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "बधाई हो, आपने " . $completeWeekCount . " सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/addiction.png");
        } else if ($id == 1222) {
            $titleDesc['title'] = "समायोजन मुद्दों के लिए " . $totWeek . " सप्ताह का कार्यक्रम";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "बधाई हो, आपने " . $completeWeekCount . " सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/adjustment-issue.png");
        } else if ($id == 1223) {
            $titleDesc['title'] = $totWeek . " सप्ताह का कार्यक्रम आत्मसम्मान";
            if ($completeWeekCount > 0) {
                $titleDesc['desc'] = "बधाई हो, आपने " . $completeWeekCount . " सप्ताह का कार्यक्रम पूरा कर लिया। अब, नए सप्ताह की ओर बढ़ें।";
            }
            $titleDesc['icon'] = getPath("public/mh-weekly-icons/week-start/self-esteem.png");
        }
        return $titleDesc;
    }
    public function fetchAssessmentRecord(Request $request)
    {

        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['lng']     = $request->input('lng') !== NULL ? $request->input('lng') : 'en';
        $user_array['slug']    = $request->input('slug');
        $user_array['symp_id'] = $request->input('symp_id');
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
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $assessmentRecords = PreAssesmentAnswer::where('user_id', $user_array['user_id'])->orderBy('created_at', 'ASC')->get();
            if (count($assessmentRecords) > 0) {
                foreach ($assessmentRecords as $res) {
                    if ($res->total_score >= 30 && $res->total_score <= 50) {
                        $res['distress_level'] = "Severe";
                        $res['distress_description'] = getTermsBySLug('very-high-psychological-distress', $user_array['lng']);
                    } else if ($res->total_score >= 25 && $res->total_score <= 29) {
                        $res['distress_level'] = "Mild";
                        $res['distress_description'] = getTermsBySLug('high-psychological-distress', $user_array['lng']);
                    } else if ($res->total_score >= 20 && $res->total_score <= 24) {
                        $res['distress_level'] = "Moderate";
                        $res['distress_description'] = getTermsBySLug('moderate-psychological-distress', $user_array['lng']);
                    } else if ($res->total_score >= 10 && $res->total_score <= 19) {
                        $res['distress_level'] = "Normal";
                        $res['distress_description'] = getTermsBySLug('low-psychological-distress', $user_array['lng']);
                    }
                }
            }
            $mainAssessments = AssesmentAnswer::with(['MhQuesRange.MhResultType', "Symptoms"])->where('user_id', $user_array['user_id'])->get();
            $newArr = [];
            if (count($mainAssessments)) {
                foreach ($mainAssessments as $res) {
                    $type = (($user_array['lng'] == "hi") ? 'type_hindi as type' : 'type');
                    $category = (($user_array['lng'] == "hi") ? 'category_hindi as category' : 'category');
                    $suggestion = (($user_array['lng'] == "hi") ? 'suggestion_hindi as suggestion' : 'suggestion');
                    if (!empty($res->suggestion)) {
                        $qRangeData = MhQuesRange::select('id', $category, $suggestion)->where('id', $res->suggestion)->first();
                        $res->suggestion = $qRangeData->suggestion;
                        $res->mental_status = $qRangeData->category;
                    }
                    if (!empty($res->score_data)) {
                        $scoreData = json_decode($res->score_data, true);
                        $newData = [];
                        if (count($scoreData) > 0) {
                            foreach ($scoreData as $raw) {
                                $qRange = MhQuesRange::select('id', $type, $category, $suggestion)->where('id', $raw['rawId'])->first();
                                $raw['suggestion'] = $qRange->suggestion;
                                $raw['category'] = $qRange->category;
                                $raw['type'] = $qRange->type;
                                $newData[] = $raw;
                            }
                        }
                        $res->score_data = $newData;
                    }
                    $weeklyPArr = 0;
                    if (isset($res->MhQuesRange) && !empty($res->MhQuesRange->MhResultType)) {
                        $weeklyPArr = MhWeeklyProgram::where("s_type", $res->MhQuesRange->category_id)->count();
                    }
                    if ($res->symp_id == 1220) {
                        $content = DB::table('pages')->where(["status" => 1, 'slug' => 'decision-making-low-score'])->where("lng", $user_array['lng'])->first();
                    }
                    if ($res->symp_id == 1223) {
                        $content = DB::table('pages')->where(["status" => 1, 'slug' => 'self-steem-high'])->where("lng", $user_array['lng'])->first();
                    }
                    if ($res->symp_id == 1222) {
                        $content = DB::table('pages')->where(["status" => 1, 'slug' => 'adjustment-issue'])->where("lng", $user_array['lng'])->first();
                    }
                    if ($res->symp_id == 1218) {
                        $content = DB::table('pages')->where(["status" => 1, 'slug' => 'poor-focus-high-score'])->where("lng", $user_array['lng'])->first();
                    }
                    $res['mh_normal'] = null;
                    if (isset($content) && !empty($content) && $weeklyPArr == 0) {
                        $res['mh_normal'] = $content->description;
                    }
                    if ($user_array['lng'] == "hi") {
                        if (isset($newArr[$res->Symptoms->symptom_hindi])) {
                            $newArr[$res->Symptoms->symptom_hindi][] = $res;
                        } else {
                            $newArr[$res->Symptoms->symptom_hindi] = [$res];
                        }
                    } else {
                        if (isset($newArr[$res->Symptoms->symptom])) {
                            $newArr[$res->Symptoms->symptom][] = $res;
                        } else {
                            $newArr[$res->Symptoms->symptom] = [$res];
                        }
                    }
                }
            }
            $arr = ['pre_assessment_records' => $assessmentRecords, 'main_assessment_records' => $newArr];
            return $this->sendResponse($arr, 'Fetch Successfully', true);
        }
    }
    public function fetchSession(Request $request)
    {
        $user_array = [];
        $user_array['pid'] = $request->input('pid');
        $user_array['appt_type'] = $request->input('appt_type');

        $validator = Validator::make($user_array, [
            'appt_type' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $p_ids = User::select("pId")->where(["parent_id" => $user_array['pid']])->pluck("pId")->toArray();
        array_push($p_ids, $user_array['pid']);

        $ordersQuery = Appointments::with([
            'Doctors.docSpeciality',
            'Doctors.DoctorData',
            'Doctors.getCityName',
            'Doctors.getStateName',
            'practiceDetails',
            'AppointmentOrder',
            'NotifyUserSms',
            'PatientFeedback',
            'patient'
        ])
            ->whereIn('pID', $p_ids)
            ->where('delete_status', 1)
            ->where('doc_id', '!=', 2219)
            ->orderBy('start', 'desc');

        // Handle appointment type specific logic
        if ($user_array['appt_type'] == 1) {
            $orders = $ordersQuery->get(); // Get all orders
            $newArr = [];

            foreach ($orders as $appt) {
                $followup_count = isset($appt->Doctors->DoctorData) ? $appt->Doctors->DoctorData->followup_count : null;
                $followUp = followupExist($appt->start, $followup_count, $appt->id, $appt->doc_id, $appt->pId);
                $appt['is_followup'] = $followUp['success'];
                $appt['followupDone'] = $followUp['flag'];
                $appt['prescription'] = null;

                $presDta = UsersSubscriptions::where(['appointment_id' => $appt->id])->count();
                if ($appt->visit_status == 1 && $presDta > 0) {
                    $appt['prescription'] = 1;
                }

                $appt['consultation_fees'] = getSetting("tele_main_price")[0];

                if (!empty($appt->Doctors) && $appt->Doctors->docSpeciality) {
                    $appt['doc_speciality'] = [
                        "id" => $appt->Doctors->docSpeciality->id,
                        "spaciality" => $appt->Doctors->docSpeciality->spaciality,
                        "spaciality_hindi" => $appt->Doctors->docSpeciality->spaciality_hindi
                    ];
                } else {
                    $appt['doc_speciality'] = [
                        "id" => "",
                        "spaciality" => "",
                        "spaciality_hindi" => ""
                    ];
                }

                $appt['doc_pic'] = !empty($appt->Doctors) ? getPath("public/doctor/ProfilePics/" . $appt->Doctors->profile_pic) : null;
                $appt['apt_type'] = 0;

                if (!empty($appt->AppointmentOrder)) {
                    $metaData = json_decode($appt->AppointmentOrder->meta_data, true);
                    if (isset($metaData['_from'])) {
                        if ($user_array['appt_type'] == 1 && $metaData['_from'] == 1) {
                            $newArr[] = $appt;
                        } else if ($user_array['appt_type'] == 2) {
                            $appt['apt_type'] = $metaData['_from'];
                        }
                    }
                }
            }

            // Manually paginate the results
            $perPage = 10;
            $currentPage = $request->input('page', 1);

            $offset = ($currentPage * $perPage) - $perPage;
            $itemsForCurrentPage = array_slice($newArr, $offset, $perPage, false);
            $orders = new Paginator($itemsForCurrentPage, count($newArr), $perPage, $currentPage, ['path' => Paginator::resolveCurrentPath()]);
        } else {
            // Use built-in pagination if not using custom logic
            $orders = $ordersQuery->paginate(12);
        }

        return view('your-view-name', ['orders' => $orders]);
    }

    public function updateReadSession(Request $request)
    {

        $auth = Auth::id();



        $user_array = [];
        $user_array['user_id'] = $auth;
        $user_array['program_id'] = $request->input('id');
        $user_array['symp_id'] = $request->input('symp_id');
        $user_array['program_status'] = $request->input('program_status');

        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'program_id' => 'required',
            'symp_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $matrix = MhProgramMatrix::where('program_id', $user_array['program_id'])->where('user_id', $user_array['user_id'])->orderBy('id', 'DESC')->first();
            if (!empty($matrix)) {
                $dfsd = MhProgramMatrix::where('id', $matrix->id)->update([
                    'program_status' => $user_array['program_status']
                ]);
                $matrix['program_status'] = $user_array['program_status'];
            } else {
                $matrix = MhProgramMatrix::create([
                    'user_id' => $user_array['user_id'],
                    'program_id' => $user_array['program_id'],
                    'symp_id' => $user_array['symp_id'],
                    'program_status' => $user_array['program_status'],
                ]);
            }
            return response()->json([
                'status' => 'success',
                'message' => 'Program status updated successfully',
                'data' => $matrix
            ]);
        }
    }
    public function fetchOverviewContent(Request $request)
    {

        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['lng'] = $request->input('lng');
        $user_array['symp_id'] = $request->input('symp_id');
        $user_array['assess_id'] = $request->input('assess_id');
        $user_array['suggestionId'] = $request->input('suggestion');

        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'lng' => 'required',
            'symp_id' => 'required',
            // 'assess_id' => 'required',
            // 'suggestionId' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $page = null;
            if (!empty($user_array['lng'])) {
                $page = DB::table('pages')->select('description')->where(["status" => 1, 'slug' => 'mh-week-after-quiz'])->where("lng", $user_array['lng'])->first();
            }
            if (empty($page)) {
                $page = DB::table('pages')->select('description')->where(["status" => 1, 'slug' => 'mh-week-after-quiz'])->where("lng", "en")->first();
            }
            if (!empty($user_array['suggestionId'])) {
                $qRange = MhQuesRange::with('MhResultType.MhWeeklyProgram.AssessmentOverview')->select('id', 'category_id')->where('id', $user_array['suggestionId'])->first();
            } else {
                $assessmen = AssesmentAnswer::where(['user_id' => $user_array['user_id'], 'symp_id' => $user_array['symp_id']])->orderBy('created_at', 'DESC')->first();
                $qRange = MhQuesRange::with('MhResultType.MhWeeklyProgram.AssessmentOverview')->select('id', 'category_id')->where('id', $assessmen->id)->first();
            }
            $programId = 1;
            if (!empty($qRange)) {
                if ($qRange->MhResultType->MhWeeklyProgram->count() > 0) {
                    $programId = $qRange->MhResultType->MhWeeklyProgram[0]->AssessmentOverview[0]->id;
                }
            }

            $totalWeek = isset($qRange->MhResultType) ? $qRange->MhResultType->MhWeeklyProgram->count() : 0;
            if ($user_array['lng'] == 'hi') {
                $upperContent = $this->collectionWeekStartProgramHindi($user_array['symp_id'], $totalWeek);
            } else {
                $upperContent = $this->collectionWeekStartProgram($user_array['symp_id'], $totalWeek);
            }
            $res = ['overview-content' => $upperContent . " " . $page->description, 'program_id' => $programId];
            return $this->sendResponse($res, 'Fetch content Successfully', true);
        }
    }

    function collectionWeekStartProgramHindi($rsTypeId, $weekCount)
    {
        $content = NULL;
        switch ($rsTypeId) {
            case 440:
                $content = "<h1>चिंता को कम करें</h1><p class='week-mh'>.$weekCount.- चिंता से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br> वाणी का " . $weekCount . "-सप्ताह कार्यक्रम चिंता प्रबंधन के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है, जैसे कि सचेतनता और तनाव प्रबंधन रणनीतियाँ।<p>";
                break;

            case 1217:
                $content = "<h1>शैक्षणिक तनाव</h1><p class='week-mh'>.$weekCount.-शैक्षणिक तनाव से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br >वाणी का .$weekCount.-सप्ताह कार्यक्रम अकादमिक तनाव के प्रबंधन के लिए विभिन्न तरीकों को सिखाने, राहत और आशा का मार्ग प्रदान करने के लिए डिज़ाइन किया गया है।<p>";
                break;

            case 1218:
                $content = "<h1>खराब फोकस और एकाग्रता</h1><p class='week-mh'>.$weekCount.- खराब फोकस और एकाग्रता से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</ मजबूत><br>वाणी का " . $weekCount . "-सप्ताह कार्यक्रम खराब फोकस और एकाग्रता में सुधार के लिए विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है।<p>";
                break;

            case 1219:
                $content = "<h1>रिश्ते के मुद्दे</h1><p class='week-mh'>.$weekCount.''-रिश्ते की समस्याओं से निपटने के लिए सप्ताह का रोडमैप</p><p>वाणी का .$weekCount.''-सप्ताह कार्यक्रम है रिश्तों को स्वस्थ और बेहतर रिश्तों में बदलने के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है। <p>";
                break;

            case 1220:
                $content = "<h1>निर्णय लेना</h1><p class='week-mh'>.$weekCount.-निर्णय लेने से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br >वाणी का " . $weekCount . "-सप्ताह कार्यक्रम जीवन में प्रभावी निर्णय लेने के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है।<p>";
                break;

            case 1221:
                $content = "<h1>व्यसन</h1><p class='week-mh'>.$weekCount.-व्यसन से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br>वाणी का " . $weekCount . "-सप्ताह कार्यक्रम इंटरनेट की लत से निपटने के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है। <p>";
                break;

            case 1222:
                $content = "<h1>समायोजन मुद्दे</h1><p class='week-mh'>.$weekCount.-समायोजन मुद्दों से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong><br >वाणी का " . $weekCount . "-सप्ताह कार्यक्रम नई जगहों और नए लोगों के साथ तालमेल बिठाने के विभिन्न तरीके सिखाने के लिए डिज़ाइन किया गया है।<p>";
                break;

            case 1223:
                $content = "<h1>आत्म-सम्मान</h1><p class='week-mh'>.$weekCount.'-आत्म-सम्मान से निपटने के लिए सप्ताह का रोडमैप</p><p><strong>कार्यक्रम अवलोकन</strong> <br>वाणी का " . $weekCount . "-सप्ताह कार्यक्रम आत्म-सम्मान बढ़ाने के विभिन्न तरीकों को सिखाने के लिए डिज़ाइन किया गया है।<p>";
                break;

            default:
                $content = null;
        }
        return $content;
    }

    function collectionWeekStartProgram($rsTypeId, $weekCount)
    {
        $content = NULL;
        switch ($rsTypeId) {
            case 440:
                $content = "<h1>Ease Anxiety</h1><p class='week-mh'>" . $weekCount . "-week Roadmap to Cope Anxiety</p><p><strong>Program Overview</strong><br>Vaani's " . $weekCount . "-week program is designed to teach various methods for managing anxiety, such as mindfulness and stress management strategies.<p>";
                break;

            case 1217:
                $content = "<h1>Academic stress</h1><p class='week-mh'>" . $weekCount . "-week Roadmap to Cope Academic stress</p><p><strong>Program Overview</strong><br>Vaani's " . $weekCount . "-week program is designed to teach various methods for managing academic stress, offering a path to relief and hope.<p>";
                break;

            case 1218:
                $content = "<h1>Poor focus and Concentration</h1><p class='week-mh'>" . $weekCount . "-week Roadmap to Cope Poor focus and Concentration</p><p><strong>Program Overview</strong><br>Vaani's " . $weekCount . "-week program is designed to teach various methods for improving Poor focus and Concentration.<p>";
                break;

            case 1219:
                $content = "<h1>Relationship issues</h1><p class='week-mh'>" . $weekCount . "-week Roadmap to Cope Relationship issues</p><p>Vaani's " . $weekCount . "-week program is designed to teach various methods of transforming relationships into healthy and better ones.<p>";
                break;

            case 1220:
                $content = "<h1>Decision Making</h1><p class='week-mh'>" . $weekCount . "-week Roadmap to Cope Decision Making</p><p><strong>Program Overview</strong><br>Vaani's " . $weekCount . "-week program is designed to teach various methods for making effective decisions in life.<p>";
                break;

            case 1221:
                $content = "<h1>Addiction</h1><p class='week-mh'>" . $weekCount . "-week Roadmap to Cope Addiction</p><p><strong>Program Overview</strong><br>Vaani's " . $weekCount . "-week program is designed to teach various methods for dealing with internet addiction.<p>";
                break;

            case 1222:
                $content = "<h1>Adjustment Issues</h1><p class='week-mh'>" . $weekCount . "-week Roadmap to Cope Adjustment Issues</p><p><strong>Program Overview</strong><br>Vaani's " . $weekCount . "-week program is designed to teach various methods for adjusting to new places and with new people.<p>";
                break;

            case 1223:
                $content = "<h1>Self-Esteem</h1><p class='week-mh'>" . $weekCount . "-week Roadmap to Cope Self-Esteem</p><p><strong>Program Overview</strong><br>Vaani's " . $weekCount . "-week program is designed to teach various methods to boost self-esteem.<p>";
                break;

            default:
                $content = null;
        }
        return $content;
    }
    public function fetchWeeklyPrograms(Request $request)
    {

        $params = json_decode(base64_decode($request->query('params')), true);
        Log::info('params', [$params]);
        if (is_array($params)) {
            $user_array = [
                'user_id' => $params['user_id'] ?? null,
                'symp_id' => $params['symp_id'] ?? null,
                'assess_id' => $params['assess_id'] ?? null,
                'lng' => 'en',
            ];

            $result = UsersSubscriptions::with("UserSubscribedPlans", "PlanPeriods")->where('user_id', $user_array['user_id'])->where('order_status', '1')->whereHas('PlanPeriods', function ($q) {
                $q->Where('status', 1);
            })->count();
            $isSubs = $result > 0 ? true : false;
            if (!empty($user_array['assess_id'])) {
                $raw = AssesmentAnswer::with('MhWeeklyProgram.AssessmentOverview.MhProgramMatrix', 'MhQuesRange', 'MhWeeklyProgram.MhWpFeedback')->where('id', $user_array['assess_id'])->first();
            } else {
                $raw = AssesmentAnswer::with('MhWeeklyProgram.AssessmentOverview.MhProgramMatrix', 'MhQuesRange', 'MhWeeklyProgram.MhWpFeedback')->where('user_id', $user_array['user_id'])->where('symp_id', $user_array['symp_id'])->orderBy('created_at', 'DESC')->first();
            }
            $weeklyPArr = [];
            if (!empty($raw)) {
                $isData = MhProgramMatrix::where('user_id', $user_array['user_id'])->where('symp_id', $user_array['symp_id'])->count();
                if ($raw->MhWeeklyProgram->count() > 0) {
                    foreach ($raw->MhWeeklyProgram as $col) {
                        if (count($col->AssessmentOverview) > 0) {
                            foreach ($col->AssessmentOverview as $line) {
                                if (!empty($line->audio_file)) {
                                    $line['audio_file_url'] = getPath("public/mh-audio-files/" . $line->audio_file);
                                } else {
                                    $line['audio_file_url'] = null;
                                }
                            }

                            // mhProgramMatrix
                            $matrixArr = null;
                            if ($line->MhProgramMatrix->count() > 0) {
                                foreach ($line->MhProgramMatrix as $matrix) {
                                    if ($user_array['user_id'] === $matrix->user_id) {
                                        $matrixArr = $matrix;
                                    }
                                }
                            }
                            unset($line->MhProgramMatrix);
                            unset($line['MhProgramMatrix']);
                            $line['mh_program_matrix'] = $matrixArr;
                        }
                        $wpFeedbackArr = null;
                        if (count($col->MhWpFeedback) > 0) {
                            foreach ($col->MhWpFeedback as $feed) {
                                if ($feed->user_id == $user_array['user_id']) {
                                    $wpFeedbackArr = $feed;
                                }
                            }
                        }
                        unset($col->MhWpFeedback);
                        $col['mh_wp_feedback'] = $wpFeedbackArr;


                        $col['icon_url'] = getPath("public/mh-weekly-icons/" . $col->icon);
                        if ($raw->MhQuesRange->category_id == $col->s_type) {
                            $weeklyPArr[] = $col;
                        }
                    }
                }


                if ($raw->symp_id == 1220) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'decision-making-low-score'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1223) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'self-steem-high'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1222) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'adjustment-issue'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1218) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'poor-focus-high-score'])->where("lng", $user_array['lng'])->first();
                }
                $raw['mh_weekly_program_data'] = $weeklyPArr;
                if (isset($content) && !empty($content) && count($weeklyPArr) == 0) {
                    $raw['mh_normal'] = $content->description;
                }
                $raw['is_program_start'] = $isData > 0 ? 1 : 0;
            }

            return view('mental.weekly-program', compact('raw', 'isSubs'));
        }
    }
    public function fetchWeeksResult(Request $request)
    {

        $params = json_decode(base64_decode($request->query('params')), true);
        Log::info('sparams', [$params]);
        $auth = Auth::id();
        if (is_array($params)) {
            $user_array = [
                'id' => $params['sid'] ?? null,
                'user_id' => $auth,
                'symp_id' => $params['symp_id'] ?? null,
                'assess_id' => $params['assess_id'] ?? null,
                'lng' => 'en',
            ];

            if (!empty($user_array['assess_id'])) {

                $raw = AssesmentAnswer::with([
                    'MhWeeklyPrograms.AssessmentOverview.MhProgramMatrix',
                    'MhQuesRange',
                    'MhWeeklyProgram.MhWpFeedback'
                ])
                    ->where('symp_id', $user_array['symp_id'])
                    ->whereHas('MhWeeklyProgram', function ($query) use ($user_array) {
                        $query->where('id', $user_array['id']);
                    })
                    ->first();
            } else {
                $raw = AssesmentAnswer::with('MhQuesRange', 'MhWeeklyProgram.MhWpFeedback', 'MhWeeklyPrograms.AssessmentOverview.MhProgramMatrix')->where('user_id', $user_array['user_id'])->where('symp_id', $user_array['symp_id'])->orderBy('created_at', 'DESC')->first();
            }

            $weeklyPArr = [];
            if (!empty($raw)) {
                $isData = MhProgramMatrix::where('user_id', $user_array['user_id'])->where('symp_id', $user_array['symp_id'])->count();
                if ($raw->MhWeeklyProgram->count() > 0) {
                    foreach ($raw->MhWeeklyProgram as $col) {
                        if (count($col->AssessmentOverview) > 0) {
                            foreach ($col->AssessmentOverview as $line) {
                                if (!empty($line->audio_file)) {
                                    $line['audio_file_url'] = getPath("public/mh-audio-files/" . $line->audio_file);
                                } else {
                                    $line['audio_file_url'] = null;
                                }
                            }

                            // mhProgramMatrix
                            $matrixArr = null;
                            if ($line->MhProgramMatrix->count() > 0) {
                                foreach ($line->MhProgramMatrix as $matrix) {
                                    if ($user_array['user_id'] === $matrix->user_id) {
                                        $matrixArr = $matrix;
                                    }
                                }
                            }
                            unset($line->MhProgramMatrix);
                            unset($line['MhProgramMatrix']);
                            $line['mh_program_matrix'] = $matrixArr;
                        }
                        $wpFeedbackArr = null;
                        if (count($col->MhWpFeedback) > 0) {
                            foreach ($col->MhWpFeedback as $feed) {
                                if ($feed->user_id == $user_array['user_id']) {
                                    $wpFeedbackArr = $feed;
                                }
                            }
                        }
                        unset($col->MhWpFeedback);
                        $col['mh_wp_feedback'] = $wpFeedbackArr;


                        $col['icon_url'] = getPath("public/mh-weekly-icons/" . $col->icon);
                        if ($raw->MhQuesRange->category_id == $col->s_type) {
                            $weeklyPArr[] = $col;
                        }
                    }
                }


                if ($raw->symp_id == 1220) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'decision-making-low-score'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1223) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'self-steem-high'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1222) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'adjustment-issue'])->where("lng", $user_array['lng'])->first();
                }
                if ($raw->symp_id == 1218) {
                    $content = DB::table('pages')->where(["status" => 1, 'slug' => 'poor-focus-high-score'])->where("lng", $user_array['lng'])->first();
                }
                $raw['mh_weekly_program_data'] = $weeklyPArr;
                if (isset($content) && !empty($content) && count($weeklyPArr) == 0) {
                    $raw['mh_normal'] = $content->description;
                }
                $raw['is_program_start'] = $isData > 0 ? 1 : 0;
            }
            $ids = $user_array['id'];


            return view('mental.weekly-program-result', compact('raw', 'ids'));
        }
    }
    public function checkweekShow(Request $request)
    {
        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $assessment =  AssesmentAnswer::where(['user_id' => $user_array['user_id']])->count();
        $isStrt = 0;
        if ($assessment > 0) {
            $isStrt = 1;
        }
        return $this->sendResponse($isStrt, 'Fetch Weeks Successfully', true);
    }
    public function insertMood(Request $request)
    {
        $userID = \Auth::id();
    
        // Step 1: Gather and Validate Input
        $user_array = $request->only(['user_id', 'ip', 'ques_1', 'ques_2', 'message', 'selectedMood', 'lng', 'id']);
        $user_array['mood'] = $user_array['selectedMood']; // Assign selectedMood to mood field
    
        // Step 2: Validation for Required Fields
        $validator = Validator::make($user_array, [
            'mood' => 'required',
        ]);
        Log::info('Validation Result', [$validator]);
    
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
    
        // Step 3: Check for an existing record based on login status and IP
        if ($userID) {
            Log::info('userID', [$userID]);
            $existingRecord = MhMood::where('ip', $user_array['ip'])
                ->where(function ($query) use ($userID) {
                    $query->whereNull('user_id') // Check for a null `user_id`
                        ->orWhere('user_id', $userID); // Or a matching `user_id`
                })->latest()->first(); // Get the latest record
            Log::info('Existing Record', [$existingRecord]);
        } else {
            $existingRecord = MhMood::where('ip', $user_array['ip'])->latest()->first();
            Log::info('Existing Record (No User)', [$existingRecord]);
        }
    
        // Step 4: Check Date and Decide to Create or Update
        $currentDate = now()->toDateString(); // Get the current date
    
        if ($existingRecord && $existingRecord->created_at->toDateString() === $currentDate) {
            // Update the existing record
            $existingRecord->update([
                'user_id'  => $existingRecord->user_id ?? $userID, // Add `user_id` if it's currently empty
                'mood'     => $user_array['mood'],
                'ques_1'   => $user_array['ques_1'],
                'ques_2'   => $user_array['ques_2'],
                'message'  => $user_array['message'],
            ]);
    
            Log::info('Record Updated', ['id' => $existingRecord->id]);
        } else {
            // Create a new record
            $newRecord = MhMood::create([
                'user_id'  => $userID,  // Use authenticated user ID if available
                'ip'       => $user_array['ip'],
                'ques_1'   => $user_array['ques_1'],
                'ques_2'   => $user_array['ques_2'],
                'message'  => $user_array['message'],
                'mood'     => $user_array['mood'],
            ]);
    
            Log::info('New Record Created', ['id' => $newRecord->id]);
        }
    
        // Return a success response
        return response()->json(['success' => true, 'message' => 'Mood inserted successfully.']);
    }
    



    public function moodHistory(Request $request)
    {
        $user_array = [];
        $user_array['ip'] = $request->input('ip');
        $user_array['user_id'] = $request->input('user_id');
        $validator = Validator::make($user_array, [
            'user_id' => 'nullable'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $currentDate = Carbon::now()->format('Y-m-d');
            $res = MhMood::where(['ip' => $user_array['ip']])->whereDate('created_at', $currentDate)->first();
            if (($res)) {
                Log::info('res', [$res]);

                return ['status' => 1, 'data' => $res];
            } else {
                return ['status' => 2];
            }
        }
    }

    public function pushWeeklyProgram(Request $request)
    {
        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['mhp_mid'] = $request->input('mhp_mid');
        $user_array['program_id'] = $request->input('program_id');
        $user_array['task_value'] = $request->input('task_value');
        $user_array['sheet_title'] = $request->input('sheet_title');
        $user_array['id'] = $request->input('id');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'program_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            if (empty($user_array['mhp_mid'])) {
                $matrix = MhProgramMatrix::select('id')->where('user_id', $user_array['user_id'])->where('program_id', $user_array['program_id'])->first();
                $user_array['mhp_mid'] = $matrix->id;
            }
            if (!empty($user_array['id'])) {
                MhWeeklyTask::where('id', $user_array['id'])->update([
                    'task_value' => json_encode($user_array['task_value']),
                ]);
                $res = MhWeeklyTask::where('id', $user_array['id'])->first();
            } else {
                $res = MhWeeklyTask::create([
                    'user_id' => $user_array['user_id'],
                    'program_id' => $user_array['program_id'],
                    'mhp_mid' => $user_array['mhp_mid'],
                    'sheet_title' => $user_array['sheet_title'],
                    'task_value' => json_encode($user_array['task_value']),
                ]);
            }
            return $this->sendResponse($res, 'Submit Successfully', true);
        }
    }
    public function insertWeeklyFeedback(Request $request)
    {
        $user_id = Auth::id();


        $user_array['user_id'] = $user_id;
        $user_array['week_id'] = $request->input('week_id');
        $user_array['program_id'] = $request->input('program_id');
        $user_array['ques_1'] = $request->input('ques_1');
        $user_array['ques_2'] = $request->input('ques_2');
        $user_array['ques_3'] = $request->input('ques_3');
        $user_array['ques_4'] = $request->input('ques_4');

        if ($request->input('ques_1') == "No") {
            $user_array['ques_1'] = 0;
        }
        if ($request->input('ques_1') == "Yes") {
            $user_array['ques_1'] = 1;
        }
        if ($request->input('ques_2') == "No") {
            $user_array['ques_2'] = 0;
        }
        if ($request->input('ques_2') == "Yes") {
            $user_array['ques_2'] = 1;
        }
        if ($request->input('ques_3') == "Not Satisfied") {
            $user_array['ques_3'] = 0;
        }
        if ($request->input('ques_3') == "Satisfied") {
            $user_array['ques_3'] = 1;
        }
        if ($request->input('ques_4') == "no") {
            $user_array['ques_4'] = 0;
        }
        if ($request->input('ques_4') == "yes") {
            $user_array['ques_4'] = 1;
        }

        $user_array['comment'] = $request->input('comment');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'week_id' => 'required',
            'program_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
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
            return ['status' => 'success'];
        }
    }

    public function insertJournal(Request $request)
    {

        $user_array['user_id'] = $request->input('user_id');
        $user_array['meta'] = $request->input('meta');
        $user_array['id'] = $request->input('id');
        $user_array['thought_id'] = $request->input('thought_id');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'meta' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $res = null;
            if (!empty($user_array['id'])) {
                MhJournal::where('id', $user_array['id'])->update([
                    'meta' => $user_array['meta'],
                    'thought_id' => $user_array['thought_id']
                ]);
            } else {
                $res = MhJournal::create([
                    'user_id' => $user_array['user_id'],
                    'thought_id' => $user_array['thought_id'],
                    'meta' => $user_array['meta']
                ]);
            }
            return $this->sendResponse($res, 'Journal Submitted Successfully', true);
        }
    }

    public function journalHistory(Request $request)
    {

        $user_array['user_id'] = $request->input('user_id');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $res = MhJournal::with('MhJournalThought')->where(['user_id' => $user_array['user_id']])->get();
            return $this->sendResponse($res, 'Journal Fetch Successfully', true);
        }
    }

    public function commonAudio(Request $request)
    {

        $user_array['lng'] = $request->input('lng');
        $validator = Validator::make($user_array, [
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $audio_file = (($user_array['lng'] == "hi") ? 'audio_file_hindi as audio_file' : 'audio_file');
            $title = (($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title');
            $res = MhCommonAudio::select('id', $title, 'tot_listeners', $audio_file)->get();
            if ($res->count() > 0) {
                foreach ($res as $raw) {
                    $filePath = public_path("mh-audio-files/common/" . $raw->audio_file);
                    $raw['audio_url'] = getPath("public/mh-audio-files/common/" . $raw->audio_file);
                    $raw['tot_listeners'] = formatNumber($raw['tot_listeners']);
                }
            }
            return $this->sendResponse($res, 'Audios Fetch Successfully', true);
        }
    }
    public function updateAudioListen(Request $request)
    {

        $user_array['user_id'] = $request->input('user_id');
        $user_array['audio_id'] = $request->input('audio_id');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'audio_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $audio = MhCommonAudio::where('id', $user_array['audio_id'])->first();
            MhCommonAudio::where('id', $user_array['audio_id'])->update([
                'tot_listeners' => $audio->tot_listeners + 1
            ]);
            return $this->sendResponse(null, 'Updated Successfully', true);
        }
    }

    public function fetchMentalDashboard(Request $request)
    {
        $user_array = [];
        $user_array['slug'] = $request->input('slug');
        $user_array['lng'] = $request->input('lng');
        $user_array['user_id'] = $request->input('user_id');


        $symptom = (($user_array['lng'] == "hi") ? 'symptom_hindi as symptom' : 'symptom');
        $description = (($user_array['lng'] == "hi") ? 'description_hindi as description' : 'description');
        $treatment = (($user_array['lng'] == "hi") ? 'treatment_hindi as treatment' : 'treatment');
        $cause = (($user_array['lng'] == "hi") ? 'cause_hindi as cause' : 'cause');
        $strategy = (($user_array['lng'] == "hi") ? 'strategy_hindi as strategy' : 'strategy');
        $assess_program = (($user_array['lng'] == "hi") ? 'assess_program_hindi as assess_program' : 'assess_program');
        $symp_details = (($user_array['lng'] == "hi") ? 'symp_details_hindi as symp_details' : 'symp_details');
        $pointerSymp = Symptoms::select('id', 'icon', $symptom, $description, $treatment, $cause, $strategy, $assess_program, $symp_details)->where('mh_status', 1)->Where(['status' => 1])->get();
        $isData = 0;
        if ($pointerSymp->count() > 0) {
            foreach ($pointerSymp as $sym) {
                $sym['icon_url'] = url("/") . "/public/symptom-icons/" . $sym->icon;
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
        if (!empty($raw)) {
            // $isData = MhProgramMatrix::where('user_id',$user_array['user_id'])->count();
            // if($isData > 0) {
            if ($raw->MhWeeklyProgram->count() > 0) {
                foreach ($raw->MhWeeklyProgram as $col) {
                    $isComplete = 0;
                    if (count($col->AssessmentOverview) > 0) {
                        foreach ($col->AssessmentOverview as $line) {
                            if (!empty($line->audio_file)) {
                                $line['audio_file_url'] = getPath("public/mh-audio-files/" . $line->audio_file);
                            } else {
                                $line['audio_file_url'] = null;
                            }
                            $matrixArr = null;
                            if ($line->MhProgramMatrix->count() > 0) {
                                foreach ($line->MhProgramMatrix as $matrix) {
                                    if ($user_id == $matrix->user_id) {
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
                    if (count($col->MhWpFeedback) > 0) {
                        foreach ($col->MhWpFeedback as $feed) {
                            if ($feed->user_id == $user_id) {
                                $wpFeedbackArr = $feed;
                            }
                        }
                    }
                    unset($col->MhWpFeedback);
                    $col['mh_wp_feedback'] = $wpFeedbackArr;
                    $col['icon_url'] = getPath("public/mh-weekly-icons/" . $col->icon);
                    if (isset($raw->MhQuesRange) && $raw->MhQuesRange->category_id == $col->s_type) {
                        $weeklyPArr[] = $col;
                    }
                }
            }
            // }
            if ($raw->symp_id == 1220) {
                $content = DB::table('pages')->where(["status" => 1, 'slug' => 'decision-making-low-score'])->where("lng", $user_array['lng'])->first();
            }
            if ($raw->symp_id == 1223) {
                $content = DB::table('pages')->where(["status" => 1, 'slug' => 'self-steem-high'])->where("lng", $user_array['lng'])->first();
            }
            if ($raw->symp_id == 1222) {
                $content = DB::table('pages')->where(["status" => 1, 'slug' => 'adjustment-issue'])->where("lng", $user_array['lng'])->first();
            }
            if ($raw->symp_id == 1218) {
                $content = DB::table('pages')->where(["status" => 1, 'slug' => 'poor-focus-high-score'])->where("lng", $user_array['lng'])->first();
            }
            if (isset($content) && !empty($content) && count($weeklyPArr) == 0) {
                $raw['mh_normal'] = $content->description;
            }
        }

        if (!empty($user_array['lng'])) {
            $page = DB::table('pages')->where(["status" => 1, 'slug' => $user_array['slug']])->where("lng", $user_array['lng'])->first();
        } else {
            $page = DB::table('pages')->where(["status" => 1, 'slug' => $user_array['slug']])->where("lng", "en")->first();
        }

        $audio_file = (($user_array['lng'] == "hi") ? 'audio_file_hindi as audio_file' : 'audio_file');
        $title = (($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title');
        $audios = MhCommonAudio::select('id', 'tot_listeners', $title, $audio_file)->get();
        if ($audios->count() > 0) {
            foreach ($audios as $aud) {
                $filePath = public_path("mh-audio-files/common/" . $aud->audio_file);
                $aud['audio_url'] = getPath("public/mh-audio-files/common/" . $aud->audio_file);
                $aud['tot_listeners'] = formatNumber($aud['tot_listeners']);
            }
        }
        $isJournal = MhJournal::select('id')->where(['user_id' => $user_array['user_id']])->count();
        $journalData = $isJournal > 0 ? true : false;
        $tMood = MhMood::where(['user_id' => $user_array['user_id']])->whereDate('created_at', date('Y-m-d'))->first();
        $result = UsersSubscriptions::with("UserSubscribedPlans", "PlanPeriods")->where('user_id', $user_array['user_id'])->where('order_status', '1')->whereHas('PlanPeriods', function ($q) {
            $q->Where('status', 1);
        })->count();
        $isSubs = $result > 0 ? true : false;
        $title = (($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title');
        $thought = MhJournalThought::inRandomOrder()->select('id', $title)->first();
        $mArr = ['pointerSymp' => $pointerSymp, 'page' => $page, 'audios' => $audios, 'weekly_programs' => $weeklyPArr, 'is_journal' => $journalData, 'today_mood' => $tMood, 'is_subscribed' => $isSubs, 'journal_thought' => @$thought];
        return $this->sendResponse($mArr, '', true);
    }

    public function mhTracker(Request $request)
    {
        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['c_date'] = $request->input('c_date');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'c_date' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            if (empty($user_array['c_date'])) {
                $user_array['c_date'] = date('Y-m-d');
            }
            $res = MhTracker::where('user_id', $user_array['user_id'])->whereDate('s_date', date('Y-m-d', strtotime($user_array['c_date'])))->first();
            return $this->sendResponse($res, 'Tracker Fetch Successfully', true);
        }
    }

    public function updateMhTracker(Request $request)
    {
        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['s_date'] = $request->input('s_date');
        $user_array['sleep_cycle'] = $request->input('sleep_cycle');
        $user_array['exercise'] = $request->input('exercise');
        $user_array['energy_level'] = $request->input('energy_level');
        $user_array['id'] = $request->input('id');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            // 's_date' => 'required',
            // 'sleep_cycle' => 'required',
            // 'exercise' => 'required',
            // 'energy_level' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            if (!empty($user_array['id'])) {
                MhTracker::where('id', $user_array['id'])->update([
                    'sleep_cycle' => $user_array['sleep_cycle'],
                    'exercise' => $user_array['exercise'],
                    'energy_level' => $user_array['energy_level'],
                ]);
                $res = MhTracker::where('id', $user_array['id'])->first();
            } else {
                if (empty($user_array['s_date'])) {
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
            return $this->sendResponse($res, 'Mh Tracker Updated Successfully', true);
        }
    }



    public function commonSheet(Request $request)
    {

        $user_array = array();
        $user_array['lng'] = $request->input('lng');
        $user_array['user_id'] = $request->input('user_id');

        $validator = Validator::make($user_array, [
            'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $title = ($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title';
            $desc = ($user_array['lng'] == "hi") ? 'description_hindi as description' : 'description';
            $res = MhCommonSheet::select('id', $title, $desc, 'sheet_name', 'symp_id', 'icon')
                ->with('Symptoms')
                ->where('status', 1)
                ->get();

            $iconBasePath = url('/') . '/public/mh-weekly-icons/common-icons/';

            if ($res->count() > 0) {
                foreach ($res as $sheet) {
                    $sheet['icon_url'] = $iconBasePath . $sheet->icon;
                    $isData = MhSheetData::where(['user_id' => $user_array['user_id'], 'sheet_id' => $sheet->id])->count();
                    $sheet['is_data_exist'] = $isData > 0 ? true : false;
                }
            }
            return $this->sendResponse($res, 'Submit Successfully', true);
        }
    }



    public function insertSheetData(Request $request)
    {
        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['sheet_id'] = $request->input('sheet_id');
        $user_array['sheet_value'] = $request->input('sheet_value');
        $user_array['id'] = $request->input('id');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'sheet_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            if (!empty($user_array['id'])) {
                MhSheetData::where('id', $user_array['id'])->update([
                    'sheet_value' => json_encode($user_array['sheet_value'])
                ]);
            } else {
                MhSheetData::create([
                    'user_id' => $user_array['user_id'],
                    'sheet_id' => $user_array['sheet_id'],
                    'sheet_value' => json_encode($user_array['sheet_value']),
                ]);
            }
            return $this->sendResponse('', 'Submit Successfully', true);
        }
    }

    public function deleteJournal(Request $request)
    {
        $user_array = [];
        $user_array['id'] = $request->input('id');
        $validator = Validator::make($user_array, [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            MhJournal::where('id', $user_array['id'])->delete();
            return $this->sendResponse('', 'Deleted Successfully', true);
        }
    }

    public function randomPrompt(Request $request)
    {
        $user_array = [];
        $user_array['lng'] = $request->input('lng');
        $validator = Validator::make($user_array, [
            'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $title = (($user_array['lng'] == "hi") ? 'title_hindi as title' : 'title');
            $thought = MhJournalThought::inRandomOrder()->select('id', $title)->first();
            return $this->sendResponse($thought, 'Fetch Successfully', true);
        }
    }

    public function getMentalHealthPlan(Request $request)
    {

        $user_array = [];
        $user_array['lng'] = $request->input('lng');
        $user_array['user_id'] = $request->input('user_id');
        $validator = Validator::make($user_array, [
            // 'lng' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $response = [];
            if ($user_array['lng'] == "hi") {
                $plans = Plans::whereIn('id', [42, 44, 46])->orderBy('price', 'ASC')->get();
            } else {
                $plans = Plans::whereIn('id', [41, 43, 45])->orderBy('price', 'ASC')->get();
            }
            return $this->sendResponse($plans, '', true);
        }
    }

    public function fetchCommonSheetsByUserId(Request $request)
    {
        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['sheet_id'] = $request->input('sheet_id');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'sheet_id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $sheets = MhSheetData::where(['user_id' => $user_array['user_id'], 'sheet_id' => $user_array['sheet_id']])->orderBy("id", "DESC")->get();
            if (count($sheets) > 0) {
                foreach ($sheets as $raw) {
                    $raw->sheet_value = json_decode($raw->sheet_value);
                }
            }
            return $this->sendResponse($sheets, 'Fetch Successfully', true);
        }
    }

    public function fetchMHSlider(Request $request)
    {
        $user_array = [];
        $user_array['user_id'] = $request->input('user_id');
        $user_array['lng'] = $request->input('lng');
        $validator = Validator::make($user_array, [
            'user_id' => 'required',
            'lng' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            if ($user_array['lng'] == 'hi') {
                $arr[] = ['title' => 'इंटेलिजेंट मूड ट्रैकिंग और भावना सुधार', 'icon' => url("/") . "/css/img/screen-informative-1.png"];
                $arr[] = ['title' => 'मानसिक स्वास्थ्य जर्नल और सेल्फ रिफ्लेक्शन', 'icon' => url("/") . "/css/img/screen-informative-2.png"];
                $arr[] = ['title' => 'माइंडफुल रेसौर्सेस जो आपको खुश करते हैं', 'icon' => url("/") . "/css/img/screen-informative-3.png"];
                $arr[] = ['title' => 'सामान्य चिंताओं पर लेखआर्टिकल', 'icon' => url("/") . "/css/img/screen-informative-4.png"];
                $arr[] = ['title' => 'पर्सनलाइज़ योर मेन्टल हेल्थ स्टेट-साप्ताहिक प्रोग्राम', 'icon' => url("/") . "/css/img/screen-informative-5.png"];
            } else {
                $arr[] = ['title' => 'Intelligent Mood Tracking & Emotion Improvement', 'icon' => url("/") . "/css/img/screen-informative-1.png"];
                $arr[] = ['title' => 'Mental Health Journal & Self-Reflection', 'icon' => url("/") . "/css/img/screen-informative-2.png"];
                $arr[] = ['title' => 'Mindful Resources That Make You Happy', 'icon' => url("/") . "/css/img/screen-informative-3.png"];
                $arr[] = ['title' => 'Articles On Common Concerns', 'icon' => url("/") . "/css/img/screen-informative-4.png"];
                $arr[] = ['title' => 'Personalize Your Mental Health State-Weekly Program', 'icon' => url("/") . "/css/img/screen-informative-5.png"];
            }
            return $this->sendResponse($arr, 'Fetch Successfully', true);
        }
    }

    public function fetchJournalUrl(Request $request)
    {
        if ($request->isMethod('post')) {

            $user_array = [];
            $user_array['journal_id'] = $request->input('journal_id');

            $validator = Validator::make($user_array, [
                'journal_id'   => 'required|max:50',
            ]);
            if ($validator->fails()) {
                return $this->sendError($validator->errors());
            } else {
                $jd = MhJournal::where(['id' => $user_array['journal_id']])->orderBy("id", "Desc")->first();

                $jdata = view('pages.pdfFiles.journalPDF', compact('jd'))->render();
                $output = Pdf::loadHTML($jdata)->output();
                file_put_contents(public_path() . "/htmltopdfview.pdf", $output);
                $pdf_url =     url("/") . "/public/htmltopdfview.pdf?." . time();
                return $this->sendResponse($pdf_url, '', true);
            }
        }
    }

    public function fetchArticle(Request $request)
    {
        if ($request->isMethod('post')) {

            $user_array = [];

            $validator = Validator::make($user_array, []);
            if ($validator->fails()) {
                return $this->sendError($validator->errors());
            } else {
                $nfs = NewsFeeds::whereRaw("find_in_set('1',news_feeds.type)")->paginate(10);
                if ($nfs->count() > 0) {
                    foreach ($nfs as $raw) {
                        $raw['image'] = url("/") . "/public/newsFeedFiles/" . $raw['image'];
                    }
                }
                return $this->sendResponse($nfs, 'Article Fetch Successfully', true);
            }
        }
    }

    public function fetchConsData(Request $request) {
        $user_array = [];
        $user_array['mobile_no'] = $request->input('mobile_no');
        $user_array['pId'] = $request->input('pId');
        $user_array['user_id'] = $request->input('user_id');
        $user_array['slug'] = $request->input('slug');
        $user_array['lng'] = $request->input('lng');
        $user_array['is_subscribed'] = $request->input('is_subscribed');

        $validator = Validator::make($user_array, [
            // 'mobile_no'   => 'required',
            'user_id'   => 'required',
            'slug'   => 'required',

        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors());
        } else {
            $plans = Plans::whereIn('id', [41, 43, 45])->orderBy('price', 'ASC')->get();
            $page = DB::table('pages')->where(["status" => 1, 'slug' => $user_array['slug']])->first();
            $response = [];
            $isFreeAppt = false;
            $main_price = 1000;
            $fee = 1000;
            $available = 0;

            if (!empty($user_array['mobile_no'])) {
                $p_ids = User::select("pId")->where(["mobile_no" => $user_array['mobile_no']])->pluck("pId")->toArray();
                $appointment = Appointments::whereIn('pID', $p_ids)->where(["delete_status" => 1, "appointment_confirmation" => 1, "type" => 3])->count();
                if ($appointment == 0) {
                    $isFreeAppt = true;
                } else if ($appointment > 0 && $user_array['is_subscribed'] == 1) {
                    $isFreeAppt = true;
                } else {
                    $isFreeAppt = false;
                }
            }

            $times = getSetting("direct_appt_schedule_time");
            if (isset($times[0])) {
                $time = explode("-", $times[0]);
                if ($time[0] <= date("H") &&  date("H") < $time[1]) {
                    $available = 1;
                }
            }
            if (isset($times[1])) {
                $days = explode("-", $times[1]);
                if (count($days) > 0) {
                    if (!in_array(date('N'), $days)) {
                        $available = 0;
                    }
                }
            }
            $lab = LabOrders::select("is_free_appt")->where(["user_id" => $user_array['user_id'], "status" => 1, "is_free_appt" => 1])->first();
            if (!empty($lab)) {
                $isFreeAppt = true;
            }
            $arr = ["fee" => $fee, "main_price" => $main_price, "available" => $available, "isFreeAppt" => $isFreeAppt, 'plans' => $plans, 'content' => $page];

            return view('mental.mental-plan', compact('arr'));
        }
    }

    public function fetchSymptom(Request $request)
    {
        $symptom =  'symptom';
        $description = 'description';
        $treatment =  'treatment';
        $cause = 'cause';
        $strategy =  'strategy';
        $assess_program = 'assess_program';
        $symp_details = 'symp_details';

        $pointerSymp = Symptoms::select('id', 'icon', $symptom, $description, $treatment, $cause, $strategy, $assess_program, $symp_details)->where('mh_status', 1)->Where(['status' => 1])->get();
        if ($pointerSymp->count() > 0) {
            foreach ($pointerSymp as $sym) {
                $sym['icon_url'] = url("/") . "/public/symptom-icons/" . $sym->icon;
            }
        }
        return response()->json($pointerSymp);
    }
    public function saveAssesmentWeb(Request $request)
    {
        $userId = Auth::id();
        if ($userId == null) {
            return redirect()->route('login');
        }
        $data = $request->all();

        // dd($data['questionArrayselections']);
        $user_array = array();
        $user_array['user_id'] = $userId;
        $user_array['symp_id'] = $data['symp_id'];
        $user_array['quesArr'] = $data['questionArrayselections'];

        $quesArr = $user_array['quesArr'];
        $totScore = 0;
        if (count($quesArr) > 0) {
            foreach ($quesArr as $raw) {
                $totScore += $raw['ques_val'];
            }
        }

        $result = $this->calculateResult($quesArr, $user_array['symp_id']);

        $res = AssesmentAnswer::create([
            'user_id' => $user_array['user_id'],
            'symp_id' => $user_array['symp_id'],
            'total_score' => $result['score'],
            'mental_status' => $result['category'],
            'suggestion' => $result['suggestion'],
            'meta_data' => json_encode($user_array['quesArr']),
            'score_data' => count($result['scoreData']) > 0 ? json_encode($result['scoreData']) : NULL
        ]);
        $category = 'category';
        $suggestion =  'suggestion';

        if (!empty($res->score_data)) {
            $scoreData = json_decode($res->score_data, true);
            $newData = [];
            if (count($scoreData) > 0) {
                foreach ($scoreData as $raw) {
                    $qRange = MhQuesRange::select('id', $category, $suggestion)->where('id', $raw['rawId'])->first();
                    $raw['suggestion'] = $qRange->suggestion;
                    $raw['category'] = $qRange->category;

                    $newData[] = $raw;
                }
            }
            $res->score_data = $newData;
        }

        return response()->json(['status' => 1, 'redirect_url' => route('mental.assessmentQuesResult', ['id' => $res->id])]);
    }
    public function institute(Request $request)
    {
        return view('pages.institute');
    }
    protected function sendError($error, $errorMessages = [], $code = 400)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
    public function viewMoodHistory(Request $request)
    {
        $userID = \Auth::id();
		$userIp = $_SERVER['REMOTE_ADDR'];
		// $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, "https://api.ipify.org?format=json");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // $response = curl_exec($ch);

        // curl_close($ch);
        // $userIp = json_decode($response, true)['ip'] ?? null;
        if ($userIp && $userID) {
            $mhMood = MhMood::where('user_id', $userID)->where('ip', $userIp)->get();
        } else {
            $mhMood =  MhMood::where('ip', $userIp)->get();
        }
        return view('mental.viewMoodHistory', compact('mhMood'));
    }



    public function allAssessmentScore(Request $request)
    {

        if (Auth::user() == null) {
            Session::put('loginFrom', '8');
            return redirect()->route('login');
        }

        $user_array = [
            'user_id' => \Auth::id(),
            'lng' => 'en',
        ];

        if ($user_array['user_id']) {
            $validator = Validator::make($user_array, [
                'user_id' => 'required',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            try {
                // Fetch PreAssessment Records
                $assessmentRecords = PreAssesmentAnswer::where('user_id', $user_array['user_id'])
                    ->orderBy('created_at', 'ASC')
                    ->get();

                if (count($assessmentRecords) > 0) {
                    foreach ($assessmentRecords as $res) {
                        if ($res->total_score >= 30 && $res->total_score <= 50) {
                            $res['distress_level'] = "Severe";
                            $res['distress_description'] = getTermsBySLug('very-high-psychological-distress', $user_array['lng']);
                        } elseif ($res->total_score >= 25 && $res->total_score <= 29) {
                            $res['distress_level'] = "Mild";
                            $res['distress_description'] = getTermsBySLug('high-psychological-distress', $user_array['lng']);
                        } elseif ($res->total_score >= 20 && $res->total_score <= 24) {
                            $res['distress_level'] = "Moderate";
                            $res['distress_description'] = getTermsBySLug('moderate-psychological-distress', $user_array['lng']);
                        } elseif ($res->total_score >= 10 && $res->total_score <= 19) {
                            $res['distress_level'] = "Normal";
                            $res['distress_description'] = getTermsBySLug('low-psychological-distress', $user_array['lng']);
                        }
                    }
                }

                // Fetch Main Assessment Records with Relationships
                $mainAssessments = AssesmentAnswer::with(['MhQuesRange.MhResultType', 'Symptoms'])
                    ->where('user_id', $user_array['user_id'])
                    ->get();

                $newArr = [];
                if (count($mainAssessments) > 0) {
                    foreach ($mainAssessments as $res) {
                        $type = $user_array['lng'] === "hi" ? 'type_hindi as type' : 'type';
                        $category = $user_array['lng'] === "hi" ? 'category_hindi as category' : 'category';
                        $suggestion = $user_array['lng'] === "hi" ? 'suggestion_hindi as suggestion' : 'suggestion';

                        if (!empty($res->suggestion)) {
                            $qRangeData = MhQuesRange::select('id', $category, $suggestion)
                                ->where('id', $res->suggestion)
                                ->first();

                            if ($qRangeData) {
                                $res->suggestion = $qRangeData->suggestion;
                                $res->mental_status = $qRangeData->category;
                            }
                        }

                        if (!empty($res->score_data)) {
                            $scoreData = json_decode($res->score_data, true);
                            $newData = [];
                            foreach ($scoreData as $raw) {
                                $qRange = MhQuesRange::select('id', $type, $category, $suggestion)
                                    ->where('id', $raw['rawId'])
                                    ->first();

                                if ($qRange) {
                                    $raw['suggestion'] = $qRange->suggestion;
                                    $raw['category'] = $qRange->category;
                                    $raw['type'] = $qRange->type;
                                    $newData[] = $raw;
                                }
                            }
                            $res->score_data = $newData;
                        }

                        $weeklyPArr = 0;
                        if (isset($res->MhQuesRange) && !empty($res->MhQuesRange->MhResultType)) {
                            $weeklyPArr = MhWeeklyProgram::where("s_type", $res->MhQuesRange->category_id)->count();
                        }

                        // Define Content Based on Symptoms
                        $content = null;
                        switch ($res->symp_id) {
                            case 1220:
                                $content = DB::table('pages')->where(["status" => 1, 'slug' => 'decision-making-low-score', "lng" => $user_array['lng']])->first();
                                break;
                            case 1223:
                                $content = DB::table('pages')->where(["status" => 1, 'slug' => 'self-steem-high', "lng" => $user_array['lng']])->first();
                                break;
                            case 1222:
                                $content = DB::table('pages')->where(["status" => 1, 'slug' => 'adjustment-issue', "lng" => $user_array['lng']])->first();
                                break;
                            case 1218:
                                $content = DB::table('pages')->where(["status" => 1, 'slug' => 'poor-focus-high-score', "lng" => $user_array['lng']])->first();
                                break;
                        }

                        $res['mh_normal'] = $content && $weeklyPArr == 0 ? $content->description : null;

                        // Organize Records by Symptom
                        $symptomKey = $user_array['lng'] === "hi" ? $res->Symptoms->symptom_hindi : $res->Symptoms->symptom;
                        if (isset($newArr[$symptomKey])) {
                            $newArr[$symptomKey][] = $res;
                        } else {
                            $newArr[$symptomKey] = [$res];
                        }
                    }
                }

                return view('mental.score', [
                    'pre_assessment_records' => $assessmentRecords,
                    'main_assessment_records' => $newArr
                ]);
            } catch (\Exception $e) {
                // Log the error and return a friendly message
                \Log::error("Error in allAssessmentScore: " . $e->getMessage());
            }
        } else {
            return view('mental.score', [
                'pre_assessment_records' => null,
                'main_assessment_records' => null
            ]);
        }
    }



    //   Deepak start

    public function weeklyprogramHistory(Request $request)
    {
        return view('mental.weeklyProgramHistory');
    }

    // Deepak end


    public function symptomList(Request $request)
    {
        try {
            $query = Symptoms::where('status', 1)->where('delete_status', 1);

            // Check if a search term is provided
            if ($request->has('search') && $request->search != '') {
                $searchTerm = $request->search;
                $query->where('symptom', 'LIKE', '%' . $searchTerm . '%');
            }
            $symps = $query->limit(16)->get();
            return view('doctors.smptoms_list', compact('symps'));
        } catch (\Exception $e) {
            // Log the error and return a friendly message or redirect
            \Log::error("Error in symptomList: " . $e->getMessage());
        }
    }


    public function symptomDetails($symptom)
    {
        Log::info('gff', [$symptom]);
        try {
            $decodedId = $symptom;
            $symps = Symptoms::with(['SymptomsSpeciality'])->where('symptom', $decodedId)->where('status', 1)->where('delete_status', 1)
                ->first();

            if (!$symps) {
                return redirect()->back()->with('error', 'Symptom not found or may have been deleted.');
            }
            return view('symptom-details', compact('symps'));
        } catch (\Exception $e) {
            \Log::error("Error in symptomDetails: " . $e->getMessage());
        }
    }

    public function getVHTScore($title) {
		$score = 5;
		if($title == "Mild"){
			$score = 15;
		}
		else if($title == "Moderate"){
			$score = 25;
		}
		else if($title == "Severe"){
			$score = 35;
		}
		else if($title == "Moderately Severe"){
			$score = 45;
		}
		return $score;
	}
    
    public function voiceAssessment(Request $request)
    {
        if (Auth::user() == null) {
            Session::put('loginFrom', '17');
           return redirect()->route('login');
        }
    	

        $user_id = Auth::id();
        $lng = $request->input('lng', 'en'); // default to 'en' if not provided
    
        // Basic validation
        $validator = Validator::make([
            'user_id' => $user_id,
            'lng'     => $lng,
        ], [
            'user_id' => 'required',
            'lng'     => 'required',
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        // Get last voice assessment order
        $vhtOrder = VhtOrder::where("user_id", $user_id)
            ->whereNotNull("vh_meta_data")
            ->where("delete_status", 0)
            ->orderBy("created_at", "DESC")
            ->first();
    
        if (!empty($vhtOrder)) {
            $vhtData = json_decode($vhtOrder->vh_meta_data, true);
            $vhtData['score'] = $this->getVHTScore($vhtData['result']);
            $vhtOrder->vh_meta_data = $vhtData;
        }
    
        // Get privacy policy page
        $pnmh = DB::table('pages')
            ->where([
                "status" => 1,
                "slug" => "privacy-notice-ai-based-mantel",
                "lng" => $lng
            ])
            ->first();
    
        // Get past voice assessment orders
        $vhtOrders = VhtOrder::where("user_id", $user_id)
            ->whereNotNull("vh_meta_data")
            ->where("delete_status", 0)
            ->orderBy("created_at", "DESC")
            ->get();
    
        $scoringArr = [];
        if ($vhtOrders->count() > 0) {
            $scoringArr[] = [
                "score" => 0,
                "result" => "No significant abnormality",
                "date" => $vhtOrders[0]['created_at']
            ];
    
            foreach ($vhtOrders as $raw) {
                $vhtData = json_decode($raw->vh_meta_data, true);
                $score = $this->getVHTScore($vhtData['result']);
                $scoringArr[] = [
                    "score" => $score,
                    "result" => $vhtData['result'],
                    "date" => $raw->created_at
                ];
            }
        }
    
        // Video settings
        $settingData = Settings::whereIn('key', ["is_video_app_show", "vdo_name"])
            ->pluck("value", "key");
    
        $isVdoAppShow = $settingData["is_video_app_show"] ?? 0;
        $vdo_url = null;
        if ($isVdoAppShow == 1 && !empty($settingData["vdo_name"])) {
            $vdo_url = getPath("public/uploads/ads_video/" . $settingData["vdo_name"]);
        }
    
        // dd($vhtOrder);
        // dd($pnmh);
        // dd($scoringArr);
        // dd($vdo_url);
        // Return the web view with compacted variables
        return view('mental.voice-assessment', compact(
            'vhtOrder',
            'pnmh',
            'scoringArr',
            'vdo_url'
        ));
    
    }

    public function aiprivacyPolicy(Request $request){

        $pnmh = DB::table('pages')
        ->where([
            "status" => 1,
            "slug" => "privacy-notice-ai-based-mantel",
            "lng" => "en",
        ])->first();

 
        return view('mental.ai-privacy-policy' , compact('pnmh'));

    }


    public function vhtOrders(Request $request)
    {
        $user_id = Auth::id(); // Get logged-in user's ID
    
        if (!$user_id) {
            return redirect()->route('login')->withErrors('Please login to continue.');
        }
    
        // Get VHT Orders for the authenticated user
        $vhtOrders = VhtOrder::where("user_id", $user_id)
            ->whereNotNull("vh_meta_data")
            ->where("delete_status", 0)
            ->orderBy("created_at", "DESC")
            ->get();
    
        if ($vhtOrders->count() > 0) {
            foreach ($vhtOrders as $order) {
                $vhtData = json_decode($order->vh_meta_data, true);
    
                // Clean and enhance HTML content
                $vhtData['details'] = preg_replace(
                    '/<b>.*?<\/b>/',
                    '',
                    $this->addClassInElements($vhtData['details']),
                    1
                );
    
                $vhtData['suggestion'] = $this->addClassInElements($vhtData['suggestion']);
                $vhtData['score'] = $this->getVHTScore($vhtData['result']);
    
                $order->vh_meta_data = $vhtData;
            }
        }
    
        return view('mental.vht-orders', compact('vhtOrders'));
    }
    
	public function addClassInElements($htmlContent){
        // Match all <p> tags
        preg_match_all('/<p>(.*?)<\/p>/', $htmlContent, $matches);
        // Generate a modified version of HTML
        $modifiedHtml = $htmlContent;
        foreach ($matches[0] as $index => $pTag) {
            $newClass = 'paragraph-' . ($index + 1); // Dynamic class
            $newPTag = '<p class="' . $newClass . '">' . $matches[1][$index] . '</p>';
            $modifiedHtml = str_replace($pTag, $newPTag, $modifiedHtml);
        }
        return $modifiedHtml;
}

    public function createVhtOrder(Request $request)
{
    $user_id = Auth::id();

    // Basic validation
    $validator = Validator::make([
        'user_id' => $user_id,
    ], [
        'user_id' => 'required',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator);
    }

    // Time setup
    $microtime = microtime(true);
    $milliseconds = round($microtime * 1000);

    $key = 'VR685C5RB6M27DVT'; // 16-byte encryption key

    // Get last VHT order for auto increment keys
    $order = VhtOrder::select('third_key', 'ext_order_id')->orderBy('id', 'DESC')->first();
    $third_key = !empty($order->third_key) ? preg_replace('/\D/', '', $order->third_key) : 0;
    $ext_order_id = !empty($order->ext_order_id) ? preg_replace('/\D/', '', $order->ext_order_id) : 0;

    // Login-Free Parameter Definition
    $extOrderId = "health-gennie-vht-" . str_pad(1 + $ext_order_id, 9, '0', STR_PAD_LEFT);
    $orgId = "YdE7c5f6";
    $thirdKey = "health-gennie-" . str_pad(1 + $third_key, 9, '0', STR_PAD_LEFT);
    $timestamp = $milliseconds;
    $language = "en-US";

    // Prepare signature
    $params = $extOrderId . $language . $orgId . $thirdKey . $milliseconds;
    $signature = $this->computeSSOSignature($key, $params);

    // Save the order
    VhtOrder::create([
        'user_id' => $user_id,
        'order_from' => 1,
        'third_key' => $thirdKey,
        'ext_order_id' => $extOrderId,
        'timestamp' => $milliseconds,
        'signature' => $signature,
        'org_id' => $orgId,
    ]);

    // Construct the fast login URL
    $url = 'https://healtho-in.wondertech.ai/api/auth/fast-login';
    $url .= "?org-id={$orgId}&third-key={$thirdKey}&ext-order-id={$extOrderId}&timestamp={$timestamp}&signature={$signature}&language={$language}";

    // Option 1: Redirect directly
    return redirect()->away($url);

    // Option 2 (if you want to show a view with the link):
    // return view('mental.redirect-voice', compact('url'));
}

function computeSSOSignature($key, $data)
{
    if (empty($key)) {
        throw new \Exception("Invalid key.");
    }
    if (empty($data)) {
        throw new \Exception("Invalid payload.");
    }
    // Encrypt and then hash
    $cipherText = $this->aesEncryptBase64ECB($key, $data);
    return md5($cipherText);
}
function aesEncryptBase64ECB($key, $data)
{
    // Ensure the key is exactly 16 bytes (AES-128)
    if (strlen($key) !== 16) {
        throw new \Exception("Key must be 16 bytes for AES-128.");
    }
    // Apply PKCS5 padding
    $paddedData = $this->pkcs5Padding($data, 16);
    // Encrypt using AES-128-ECB
    $encryptedData = openssl_encrypt($paddedData, 'AES-128-ECB', $key, OPENSSL_RAW_DATA);
    if ($encryptedData === false) {
        throw new \Exception("Encryption failed.");
    }
    // Encode to Base64
    return base64_encode($encryptedData);
}

function pkcs5Padding($data, $blockSize)
{ return trim($data);
    $pad = $blockSize - (strlen($data) % $blockSize);

    // dd(trim($data . str_repeat(chr($pad), $pad)));
    return trim($data . str_repeat(chr($pad), $pad));
}

}
