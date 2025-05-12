<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Imports\SymptomImport;
use App\Models\Admin\QuizQuestion;
use Illuminate\Support\Facades\DB;
// use App\Models\Admin\Admin;
use App\Models\User;
use App\Models\NonHgDoctors;
use App\Models\NewsFeeds;
use App\Models\Admin\Symptoms;
use App\Models\Admin\SymptomTags;
use App\Models\Admin\SymptomsSpeciality;
use App\Models\AssesmentAnswer;
use App\Models\AssessmentOverview;
use App\Models\MhQuesRange;
use App\Models\MhWeeklyProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\MhMood;
use App\Models\MhTracker;
use App\Models\MhJournal;
use App\Models\MhWpFeedback;
use App\Models\MhCommonSheet;
use App\Models\MhSheetData;
use App\Models\SheetData;

use App\Models\PreAssesmentAnswer;
use App\Models\MhProgramMatrix;

//use Illuminate\Mail\Mailer;
class SymptomController extends Controller {
    
    
	
		
	public function SymptomsMaster(Request $request) {
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
          if ($request->input('mh_status') != "") {
            $params['mh_status'] = base64_encode($request->input('mh_status'));
    	 }
         return redirect()->route('symptoms.SymptomsMaster',$params)->withInput();
		}
		else {
         // $symptoms = SpecialitySymptoms::where('delete_status', '=', '1')->orderBy('id', 'desc')->paginate(10);
			// if ($request->input('search')  != '') {
			   $search = base64_decode($request->input('search'));
			   $spaciality = base64_decode($request->input('spaciality'));
			    $mh_status = base64_decode($request->input('mh_status'));
			   $query = Symptoms::with("SymptomsSpeciality.Speciality")->where('delete_status', '=', '1');
			   $page = 25;
				if(!empty($request->input('page_no'))){
					$page = base64_decode($request->input('page_no')); 
				}
				if(!empty($search)){
					$query->where('symptom','like','%'.$search.'%');
				}
				if($spaciality){ 
					$query->whereHas('SymptomsSpeciality', function($q)  use ($spaciality) {$q->Where(['speciality_id'=>$spaciality]);});
				}
				if ($mh_status !== null) {
					if ($mh_status === '0') {
						$query->where('mh_status', '=', 0);
					} elseif ($mh_status === '1') {
						$query->where('mh_status', '=', 1);
					}
				}
			    $symptoms = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('admin.manage_symptoms.symptoms-master',compact('symptoms'));
	}

    public function addSymptoms(Request $request){
		if($request->isMethod('post')) {
            $data = $request->all();
			$symptomTags = $data['tags'];
			$spaciality_id = [];
          	$symtomps_exists_array = [];
			if(!empty($data['spaciality_id'])) {
				foreach($data['spaciality_id'] as $spc) {
					$symtomps_exists = Symptoms::with("SymptomsSpeciality")->where('symptom' ,'like', $data['symptom'])->whereHas("SymptomsSpeciality",function($q) use($spc) {$q->Where(['speciality_id'=>$spc]);})->count();
					if($symtomps_exists > 0) {
						$symtomps_exists_array[] = ['id'=>$spc,'name'=>getSpecialityData($spc)];
					}
					else{
						$spaciality_id[] = $spc;
					}
				}
			}
			if(count($spaciality_id) > 0) {
				$symptoms = Symptoms::create([
					'symptom' => $data['symptom'],
					'symptom_hindi' => $data['symptom_hindi'],
					'description' => $data['description'],
					'description_hindi' => $data['description_hindi'],
					'disease' => $data['disease'],
					'treatment' => $data['treatment'],
					'treatment_hindi' => $data['treatment_hindi'],
					'cause' => $data['cause'],
					'cause_hindi' => $data['cause_hindi'],
					'status' => $data['status'],
				]); 
				foreach($spaciality_id as $spc) {
					SymptomsSpeciality::create([
						'symptoms_id' => $symptoms->id,
						'speciality_id' => $spc
					]);		
				}
			
			if (!is_null($symptomTags) && is_array($symptomTags) && count($symptomTags) > 0) {
					foreach(json_decode($symptomTags) as $tag) {
						SymptomTags::create([
							'symptoms_id' => $symptoms->id,
							'text' => $tag
						]);		
					}
				}
			}
			Session::flash('message', "Speciality Symptoms Added Successfully");
			if(count($symtomps_exists_array)>0){
				return ["spaciality"=>$symtomps_exists_array,"symptom"=>$data['symptom']];
			}
			else return 1;
		}
		return view('admin.manage_symptoms.add-symptoms');
	}
	public function editSymptoms(Request $request) {
        $id = $request->id;
        $symptom = Symptoms::with(["SymptomsSpeciality","SymptomTags"])->where('delete_status', '=', '1')->Where( 'id', '=', $id)->first();
		$spaciality_id = [];
		if(count($symptom->SymptomsSpeciality) > 0){
			foreach($symptom->SymptomsSpeciality as $val){
				$spaciality_id[] = $val->speciality_id;
			}
		}
        return view('admin.manage_symptoms.edit-symptoms',compact('symptom','spaciality_id'));
    }
	public function updateSymptoms(Request $request){
		// session_unset('message');
        if($request->isMethod('post')) {
			$data = $request->all(); 
			$symptomTags = $data['tags'];
			$spaciality_id = [];
          	$symtomps_exists_array = [];
			if(!empty($data['spaciality_id'])) {
				foreach($data['spaciality_id'] as $spc) {
					$symtomps_exists = Symptoms::with("SymptomsSpeciality")->where('symptom' ,'like', $data['symptom'])->where('id','!=', $data['id'])->whereHas("SymptomsSpeciality",function($q) use($spc) {$q->Where(['speciality_id'=>$spc]);})->count();
					if($symtomps_exists > 0) {
						$symtomps_exists_array[] = ['id'=>$spc,'name'=>getSpecialityData($spc)];
					}
					else{
						$spaciality_id[] = $spc;
					}
				}
			}
			if(count($spaciality_id) > 0) {
				$symptoms = Symptoms::where('id', $data['id'])->update(array(
					'symptom' => $data['symptom'],
					'symptom_hindi' => $data['symptom_hindi'],
					'description' => $data['description'],
					'description_web' => $data['description_web'],
					'treatment_web' => $data['treatment_web'],
					'cause_web' => $data['cause_web'],
					'symp_details_web' => $data['symp_details_web'],
					'description_hindi' => $data['description_hindi'],
					'disease' => $data['disease'],
					'treatment' => $data['treatment'],
					'treatment_hindi' => $data['treatment_hindi'],
					'cause' => $data['cause'],
					'cause_hindi' => $data['cause_hindi'],
					'status' => $data['status'],
				)); 
				SymptomsSpeciality::where('symptoms_id',$data['id'])->delete();
				foreach($spaciality_id as $spc) {
					SymptomsSpeciality::create([
						'symptoms_id' => $data['id'],
						'speciality_id' => $spc
					]);	
				}
				if ($symptomTags !== null && count($symptomTags) > 0) {
					SymptomTags::where('symptoms_id',$data['id'])->delete();	
					foreach(json_decode($symptomTags) as $tag) {
						SymptomTags::create([
							'symptoms_id' => $data['id'],
							'text' => $tag
						]);		
					}
				}
			}
			if(count($symtomps_exists_array)>0){
				return ["spaciality"=>$symtomps_exists_array,"symptom"=>$data['symptom']];
			}
			else return 1;
		}
		return 2;
	}
	public function deleteSymptoms(Request $request) {
		session_unset();
		$id = $request->id; 
		Symptoms::where('id', $id)->delete();
		SymptomsSpeciality::where('symptoms_id', $id)->delete();
		SymptomTags::where('symptoms_id', $id)->delete();
		Session::flash('message', "Speciality Symptoms Deleted Successfully");
		return 1;
    }
	public function viewSymptomsQuestion(Request $request)
    {
		$id = $request->id;
	
        $symptomQues = QuizQuestion::with('SymptomsName')->where('symptom_id', $id )->where('status', 1)->get();

        return view('admin.manage_symptoms.symptomQuestionView', compact('symptomQues'));
    }

	public function quizQuestions(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('symptom'))) {
			$params['symptom'] = base64_encode($request->input('symptom'));
		}
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('quizQuestions',$params)->withInput();
		}
		else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$symptom = base64_decode($request->input('symptom'));
			$query = QuizQuestion::with('SymptomsName');
			if(!empty($search)) {
				$query->where('question', 'like', '%'.$search.'%');
			}
			if (!empty($symptom)) {
				$query->whereHas('SymptomsName', function($q) use ($symptom) {
					$q->where('symptom', 'like', '%' . $symptom . '%');
				});
			}
			$page = 25;
			if(!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$quizQuetions = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('admin.manage_symptoms.quiz-questions-master',compact('quizQuetions'));
	}
	
	public function editQuizQuestions(Request $request) {
        $id = $request->id;
        $quizQuetions = QuizQuestion::with('SymptomsName')->Where( 'id', '=', $id)->first();
		// dd($quizQuetions);
		$symptoms = Symptoms::Where('mh_status', 1)->get();
        return view('admin.manage_symptoms.edit-quiz-questions',compact('quizQuetions','symptoms'));
    }
	
	public function updateQuizQuestions(Request $request) {
        if($request->isMethod('post')) {
			$data = $request->all();
			$quizQ_already = QuizQuestion::Where('question', '=', $data['question'])->where('id','!=',$data['id'])->first();
			if(!empty($quizQ_already)) {
				return 2;
			}
			else {
				QuizQuestion::where('id', $data['id'])->update(array(
					'symptom_id' => $data['symptom_id'],
					'question' => $data['question'],
					'optionA' => $data['optionA'],
					'optionB' => $data['optionB'],
					'optionC' => $data['optionC'],
					'optionD' => $data['optionD'],
					'optionE' => $data['optionE'],
					'optionF' => $data['optionF'],
					'question_hindi' => $data['question_hindi'],
					'optionA_hindi' => $data['optionA_hindi'],
					'optionB_hindi' => $data['optionB_hindi'],
					'optionC_hindi' => $data['optionC_hindi'],
					'optionD_hindi' => $data['optionD_hindi'],
					'optionE_hindi' => $data['optionE_hindi'],
					'optionF_hindi' => $data['optionF_hindi'],
					'optionA_val' => $data['optionA_val'],
					'optionB_val' => $data['optionB_val'],
					'optionC_val' => $data['optionC_val'],
					'optionD_val' => $data['optionD_val'],
					'optionE_val' => $data['optionE_val'],
					'optionF_val' => $data['optionF_val'],
					'status' => $data['status']
				));
				Session::flash('message', "Quiz Question Updated Successfully");
				return 1;
			}
		}
		return 2;
	}

	public function addQuizQuestions(Request $request){
		if($request->isMethod('post')) {
            $data = $request->all();
			$quizQ_already = QuizQuestion::Where('question', '=', $data['question'])->first();
			
			if(!empty($quizQ_already)) {
				return 2;
			}
			else {
				$fdjfjd = QuizQuestion::create([
					'symptom_id' => $data['symptom_id'],
					'question' => $data['question'],
					'optionA' => $data['optionA'],
					'optionB' => $data['optionB'],
					'optionC' => $data['optionC'],
					'optionD' => $data['optionD'],
					'optionE' => $data['optionE'],
					'optionF' => $data['optionF'],
					'question_hindi' => $data['question_hindi'],
					'optionA_hindi' => $data['optionA_hindi'],
					'optionB_hindi' => $data['optionB_hindi'],
					'optionC_hindi' => $data['optionC_hindi'],
					'optionD_hindi' => $data['optionD_hindi'],
					'optionE_hindi' => $data['optionE_hindi'],
					'optionF_hindi' => $data['optionF_hindi'],
					'optionA_val' => $data['optionA_val'],
					'optionB_val' => $data['optionB_val'],
					'optionC_val' => $data['optionC_val'],
					'optionD_val' => $data['optionD_val'],
					'optionE_val' => $data['optionE_val'],
					'optionF_val' => $data['optionF_val'],
				]);
				// dd($fdjfjd);
				// Session::flash('message', "Quiz Question Added Successfully");
				return response()->json(['status'=> 1, 'msg' => "Quiz Question Added Successfully"]);
			}
		}
		$symptoms = Symptoms::Where('mh_status', 1)->Where('delete_status', 1)->get();
		return view('admin.manage_symptoms.add-quiz-questions', compact('symptoms'));
	}
	public function updateStatus(Request $request) {
		$quizQuestion = QuizQuestion::find($request->id);
		if ($quizQuestion) {
			$quizQuestion->status = $request->status;
			$quizQuestion->save();
			return response()->json(['success' => true, 'status' => $quizQuestion->status]);
		} else {
			return response()->json(['success' => false, 'message' => 'Quiz question not found']);
		}
	}

	public function getAssesmentChallenge(Request $request) {
		$search = '';
		if ($request->isMethod('post')) {
		$params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('getAssesmentChallenge',$params)->withInput();
		}
		else {
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query =  AssesmentAnswer::with(['userDetails']);
			if (!empty($search)) {
				$query->whereHas('userDetails', function($q) use ($search) {
					$q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $search . '%');
				});
			}
			$page = 10;
			if(!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$assesmentChallenges = $query->orderBy('id', 'desc')->paginate($page);
		}
		return view('admin.manage_symptoms.assessment-challenges',compact('assesmentChallenges'));
	}
	public function assesAnsView(Request $request)
    {
		$data = $request->all();
        $assessmentAnswers = AssesmentAnswer::where('id',$data['id'])->first();
        return view('admin.manage_symptoms.assessmentAnsView', compact('assessmentAnswers'));
    }
	function symptomExcelImport(Request $request){
		$extensions = array("xls","xlsx","csv");
		$datass = $request->all();
		$result = array($request->file('select_file')->getClientOriginalExtension());
		if(in_array($result[0],$extensions)){
			Excel::import(new SymptomImport,$request->file('select_file'));
			Session::flash('message', "Excel Data Imported successfully.");
			return redirect('quiz-question/excelImport');
		}
		else{
			Session::flash('message', "The select file must be a xls, xlsx and csv");
			return redirect('admin/quiz-questions')->with('error', 'The select file must be a xls, xlsx..');
		}
	}
	public function assesOverview(Request $request) {
		$search = '';
		$page = 25; // Default number of items per page
	
		if ($request->isMethod('post')) {
			$params = array();
			if (!empty($request->input('search'))) {
				$params['search'] = base64_encode($request->input('search'));
			}
			if (!empty($request->input('symptom_id'))) {
				$params['symptom_id'] = base64_encode($request->input('symptom_id'));
			}
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
			}
			return redirect()->route('assesOverview', $params)->withInput();
		} else {
			$filters = array();
			if ($request->input('search')) {
				$search = base64_decode($request->input('search'));
			}
			if ($request->input('symptom_id')) {
				$symptom_id = base64_decode($request->input('symptom_id'));
			}
			if ($request->input('page_no')) {
				$page = base64_decode($request->input('page_no'));
			}
			$query = MhWeeklyProgram::with('AssessmentOverview','SymptomsName');
			// $query = Symptoms::whereIn('id', $symptomIds)->with('SymptomsSpeciality');
	
			// Apply search filter if present
			if (!empty($search)) {
				$query->where('name', 'like', '%' . $search . '%');
			}
			if (!empty($symptom_id)) {
				$query->whereHas('SymptomsName', function($q) use ($symptom_id) {
					$q->where('id', $symptom_id);
				});
			}
	
			// Paginate the results
			$assesOverviews = $query->orderBy('id', 'desc')->paginate($page);
		}
		$symptoms = Symptoms::where('mh_status', 1)->where('delete_status', 1)->get();
		return view('admin.manage_symptoms.assessmentOverviews', compact('assesOverviews','symptoms'));
	}
	

	public function updateOverviewStatus(Request $request) {
		$assesOverview = AssessmentOverview::find($request->id);
		if ($assesOverview) {
			$assesOverview->status = $request->status;
			$assesOverview->save();
			return response()->json(['success' => true, 'status' => $assesOverview->status]);
		} else {
			return response()->json(['success' => false, 'message' => 'Quiz question not found']);
		}
	}


	public function addAssesOverview(Request $request) {
		if($request->isMethod('post')) {
			$data = $request->all();
			$validator = Validator::make($data, [
				'symptom_id' => 'required|exists:symptoms,id',
				'weekly_program' => 'required|string|max:255',
				'weekly_program_hindi' => 'required|string|max:255',
				'description' => 'required|string',
				'description_hindi' => 'required|string',
				// 'icon' => 'required|file|mimes:jpeg,png,jpg,gif,svg|max:2048',
				// 's_type' => 'required|in:1,2,3',
				// 'title.*' => 'required|string|max:255',
				// 'title_hindi.*' => 'required|string|max:255',
				// 'audio_file.*' => 'required|file|mimes:mp3,wav|max:2048',
				// 'program.*' => 'required|string',
				// 'program_hindi' => 'required|string',
			]);

			if ($validator->fails()) {
				return response()->json(['errors' => $validator->errors()], 422);
			}
			$iconFileName = null;
			if($request->hasFile('icon')) {
				$iconFile = $request->file('icon');
				$iconFileName = uniqid() . '_' . $iconFile->getClientOriginalName();
				$iconFile->move(public_path('mh-weekly-icons'), $iconFileName);
			}

			$mhWeeklyProgram = MhWeeklyProgram::create([
				'symp_id' => $data['symptom_id'],
				's_type' => $data['s_type'],
				'week_type' => $data['week_type'],
				'title' => $data['weekly_program'],
				'title_hindi' => $data['weekly_program_hindi'],
				'description' => $data['description'],
				'description_hindi' => $data['description_hindi'],
				'icon' => $iconFileName,
			]);

			$weeklyProgramId = $mhWeeklyProgram->id;
			$symptom_id = $data['symptom_id'];
			foreach ($data['program'] as $index => $program) {
				if ($request->hasFile('audio_file.' . $index)) {
					$audioFile = $request->file('audio_file.' . $index);
					$audioFileName = uniqid() . '_' . $audioFile->getClientOriginalName();
					$audioFile->move(public_path('mh-audio-files'), $audioFileName);
					AssessmentOverview::create([
						'weekly_program_id' => $weeklyProgramId,
						'symptom_id' => $symptom_id,
						'program' => $program,
						'title' => $data['title'][$index],
						'title_hindi' => $data['title_hindi'][$index],
						'program_hindi' => $data['program_hindi'][$index],
						'audio_file' => $audioFileName,
					]);
				}
			}
			Session::flash('message', "Assessment Programs Added Successfully");
			return response()->json(1);
		}
    $symptoms = Symptoms::where('mh_status', 1)->where('delete_status', 1)->get();
    return view('admin.manage_symptoms.add-assesmentProgram', compact('symptoms'));
}


public function editAssesOverview(Request $request)
{
    $id = $request->id;
    $sympId = $request->sympId;
    $symptoms = Symptoms::where('mh_status', 1)->get();
    $assessmentPrograms = MhWeeklyProgram::with('AssessmentOverview')->where('id',$id)->first();
    $symptomsId = $assessmentPrograms->pluck('symp_id')->unique();
    return view('admin.manage_symptoms.edit-assesmentProgram', compact('assessmentPrograms', 'symptoms', 'symptomsId', 'sympId'));
}
public function updateAssesProgram(Request $request)
{
    $data = $request->all();
    $mhWeeklyProgramId = $data['id'];
    $mhWeeklyProgram = MhWeeklyProgram::find($mhWeeklyProgramId);

    if ($mhWeeklyProgram) {
        // Update the existing MhWeeklyProgram
        $updateData = [
            'symp_id' => $data['symptom_id'],
            'week_type' => $data['week_type'],
            'title' => $data['weekly_program'],
			's_type' => $data['s_type'],
            'title_hindi' => $data['weekly_program_hindi'],
            'description' => $data['description'],
            'description_hindi' => $data['description_hindi'],
        ];

        if ($request->hasFile('icon')) {
            $iconFile = $request->file('icon');
            $iconFileName = uniqid() . '.' . $iconFile->getClientOriginalExtension();
            $iconFile->move(public_path('mh-weekly-icons/'), $iconFileName);

            // Add the new icon filename to the update array
            $updateData['icon'] = $iconFileName;

            // Optionally, delete the old icon file if exists
            if ($mhWeeklyProgram->icon) {
                $oldIconPath = public_path('mh-weekly-icons/' . $mhWeeklyProgram->icon);
                if (file_exists($oldIconPath)) {
                    unlink($oldIconPath);
                }
            }
        }
        $mhWeeklyProgram->update($updateData);
    } else {
        return response()->json(['status' => 'error', 'message' => 'MhWeeklyProgram not found'], 404);
    }

    // Step 2: Iterate and update or create AssessmentOverview records
    foreach ($data['program'] as $index => $program) {
        	$assessmentId = $data['oid'][$index] ?? null;
            $assessmentData = [
                'symptom_id' => $data['symptom_id'],
                'title' => $data['title'][$index] ?? null,
                'title_hindi' => $data['title_hindi'][$index] ?? null,
                'program' => $data['program'][$index] ?? null,
                'program_hindi' => $data['program_hindi'][$index] ?? null,
                'weekly_program_id' => $mhWeeklyProgramId,
            ];
            $assessment = AssessmentOverview::where('id',$assessmentId)->first();
            if ($assessment) {
                // Update existing assessment
                if ($request->hasFile('audio_file.' . $index)) {
                    $audioFile = $request->file('audio_file.' . $index);
                    $originalFileName = $audioFile->getClientOriginalName();
                    $timestampedFileName = uniqid() . '_' . $originalFileName;
                    $audioFile->move(public_path('mh-audio-files'), $timestampedFileName);
                    // Delete old audio file if exists
                    $existingAudioFilePath = public_path('mh-audio-files/' . $assessment->audio_file);
                    if (file_exists($existingAudioFilePath)) {
                        unlink($existingAudioFilePath);
                    }
                    // Update audio file in data array
                    $assessmentData['audio_file'] = $timestampedFileName;
                }
                $assessment->update(array_filter($assessmentData)); // Filter out null values
            } else {
                // Create new assessment if it does not exist
                if ($request->hasFile('audio_file.' . $index)) {
                    $audioFile = $request->file('audio_file.' . $index);
                    $originalFileName = $audioFile->getClientOriginalName();
                    $timestampedFileName = uniqid() . '_' . $originalFileName;
                    $audioFile->move(public_path('mh-audio-files'), $timestampedFileName);
                    $assessmentData['audio_file'] = $timestampedFileName;
                }
                $assessmentCreate = AssessmentOverview::create(array_filter($assessmentData)); // Filter out null values
                if (!$assessmentCreate) {
                    return response()->json(['status' => 'error', 'message' => 'Assessment creation failed'], 500);
                }
            }
    }

    return response()->json(['status' => 'success', 'message' => 'Data updated successfully'], 200);
}







	
	public function viewAssessmentProgram(Request $request)
	{
		$id = $request->id;
		
		$assessProgramViews = MhWeeklyProgram::with(['AssessmentOverview' => function ($query) {
			$query->orderBy('id', 'ASC');
		}])
		->where('id', $id)->first();
		return view('admin.manage_symptoms.assessmentProgramView', compact('assessProgramViews'));
	}

	public function mentalHealthQues(Request $request)

   {	$user = Session::get('userdata');
	
	$MentalQuesData = MhQuesRange::with('Symptoms')->where('added_by' , $user->id)->where('ques_type' , [1, 2] )->groupBy('symp_id')->get();
	
	return view('admin.manage_symptoms.mental-health-ques' , compact('MentalQuesData'));

	}


	public function editMentalHealthQues(Request $request, $id = null)
{
    if ($id === null) {
        return redirect()->back()->with('error', 'ID is required');
    }
    $MhQuesRange = MhQuesRange::with(["Symptoms"])->where('symp_id', '=', $id)->get();
    return view('admin.manage_symptoms.view-mhq-edit', compact('MhQuesRange'));
}



public function finalEditMHQ(Request $request)
{
	$id = $request->id;
    if ($id === null) {
        return redirect()->back()->with('error', 'ID is required');
    }

    $MhQuesRange = MhQuesRange::with(["Symptoms"])->where('id', '=', $id)->first();
    return view('admin.manage_symptoms.edit-mhq', compact('MhQuesRange'));
}


public function updateMentalHealthQues(Request $request){
	if($request->isMethod('post')) {
		$data = $request->all();
		// dd($data);
	
		if(isset($data['id']) && $data['id'] != null) {
		
			$MhQuesRange = MhQuesRange::where('id', $data['id'])->update(array(

				'suggestion' => $data['suggestion'],
				'suggestion_hindi' => $data['suggestion_hindi'],
				'min_score' => $data['min_score'],
				'max_score' => $data['max_score'],
				'category' => $data['category'],
				'category_hindi' => $data['category_hindi'],
				'type' => $data['type'],
				'type_hindi' => $data['type_hindi'],
			)); 
			
		}
	return 1;
	}
	return 2;
}


	public function viewMentalHealthQues(Request $request)
	{      
		$symp_id = $request->symp_id;
		$MhQuesRange = MhQuesRange::with(["Symptoms"])->where( 'symp_id', '=', $symp_id)->get();
		return view('admin.manage_symptoms.view-mhq', compact('MhQuesRange'));

	}

	
	public function sheetDisplay(Request $request)
	{
		$page = 25;
		if (!empty($request->input('page_no'))) {
			$page = intval(base64_decode($request->input('page_no')));
		}
		$query = DB::table('sheet_template')->orderBy('sheet_id', 'asc');
		if (!empty($request->input('module_id'))) {
			$module_id = intval($request->input('module_id'));
			$query->where('sheet_id', $module_id);
		}
		$data = $query->paginate($page);
		$dataAll = DB::table('sheet_template')->orderBy('sheet_id', 'asc')->get();
		return view("admin.manage_symptoms.sheetDisplay", compact('data', 'dataAll'));
	}
	
		
		public function editSheet(Request $request){
			
			$sheet_id = $request->all();
			$data = DB::table('sheet_template')->where('sheet_id' , $sheet_id)->orderBy('sheet_id', 'asc')->first();
			return view("admin.manage_symptoms.edit-sheet" ,compact('data'));
	
		}
		public function updateMentalHealthSheet(Request $request){
			$data = $request->all();
			$symptoms = DB::table('sheet_template')->where('id', $data['sheet_id'])->update(array(
				'html_module' => $data['html_module'],
			));
		return 1;
		}
	
	
		
	public function getMentalhealthList(Request $request)
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
		}
		else 
		{
			$filters = array();
			$search = base64_decode($request->input('search'));
			$query = AssesmentAnswer::with(['userDetails', 'MhQuesRange', 'mhQuesRange.MhResultType', 'PreAssesmentAnswer' ]);
	
	
		


			if (!empty($search)) {
				$query->whereHas('userDetails', function($q) use ($search) {
					$q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', '%' . $search . '%');
				});
			}
			if (!empty($request->input('page_no'))) {
				$page = base64_decode($request->input('page_no'));
			}
			$userData = $query->groupBy('user_id')->orderBy('id', 'desc')->paginate($page);
	
		}
	
		return view('admin.manage_symptoms.mentalHealthList', compact('userData'));
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
	
		$userData = AssesmentAnswer::with([
			'userDetails',
			'Appointments',
			'AppointmentsAll',
			'MhQuesRange',
			'mhQuesRange.MhResultType',
			'Symptoms',
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

	return view('admin.manage_symptoms.mentalHealthReport', compact('mhSheetData', 'mhTracker','preAssessment' , 'mhJournal', 'userData', 'questionRanges', 'userDetails', 'mood' ,'MhCommon' , 'MhCommonSheet' ,  'dataFeedback'));
	}
	

}
