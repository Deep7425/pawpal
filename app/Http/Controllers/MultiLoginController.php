<?php

namespace App\Http\Controllers;

use App\Constants\AppConstants;
use App\Models\OrganizationMaster;
use App\Models\Student;
use App\Models\User;
use App\Models\UserSecurityQuestion;
use App\Models\UsersOTP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class MultiLoginController extends Controller
{
   
    public function multiLoginShowLoginForm()
    {
       
        if (auth()->check()) {
            return redirect('/');
        }
        return view('multi-login');
    }

    /**Institute  Login*/
    public function checkInstitute(Request $request)
    {

        $data = $request->all();

        $organization = OrganizationMaster::where('title', $data['organization'])->get();

        if ($organization->count() > 0) {
            return 1;
        } else {
            return 2;
        }
    }
    
    public function updatePassword(Request $request)
{
    if ($request->isMethod('post')) {
        $data = $request->all();
        Log::info('data', [$data]);

        // Update the password
        User::where('id', $data['user_id'])->update(['password' => Hash::make($data['password'])]);

        // Retrieve the updated user record
        $user = User::find($data['user_id']);
        Log::info('$user', [$user]);

        // Log in the user
        Auth::login($user);

        return response()->json(['status' => 1]);
    }
}


    public function loginInstitue(Request $request)
    {

        if ($request->isMethod('post')) {

            $data = $request->all();

            $organizationMaster = OrganizationMaster::where('institute_id', $data['institute_id'])
                ->where('pwd', $data['password'])->first();

            if (!empty($organizationMaster)) {
                $request->session()->put('organizationMaster', $organizationMaster);

                //                $request->session()->flash('organizationMaster', $organizationMaster);

                return ['status' => 1];
            } else {
                return ['status' => 2];
            }
        }
    }
   

    public function verifySecurityQuestion(Request $request) {
        // dd($request->all());
        $userId = $request->input('user_id');
        $device_token = $request->input('device_token');
        $inputData = $request->all();  
        $usersOtp = UsersOTP::where('id', $userId)->first();
        $users = User::where('student_id', 3)->first();
        if(empty($users) ) {
            return 3;
        }

        $userSecurityQuestions = UserSecurityQuestion::where('user_id', $users->id)->get();
        
        // If the user has security questions stored
        if (!$userSecurityQuestions->isEmpty()) {
            foreach ($userSecurityQuestions as $index => $userSecurityQuestion) {
                $questionKey = "question" . ($index + 1);
                $answerKey = "answer" . ($index + 1);
                
                // Check if the question and answer are present in the request
                if ($request->input($questionKey) != $userSecurityQuestion->question_id || 
                    $request->input($answerKey) != $userSecurityQuestion->answer) {
                    return 2; // Return 2 if any answer doesn't match
                } else {
                    $usersOtp->update(['fcm_token'=> $device_token]);
                }
            }
        } else {
            // If no security questions exist, add them
            for ($i = 1; $i <= 5; $i++) {
                $questionId = $request->input("question$i");
                $answer = $request->input("answer$i");
                // Create a new entry for each security question and answer
                UserSecurityQuestion::create([
                    'user_id' => $userId,           
                    'question_id' => $questionId, 
                    'answer' => $answer,
                ]);
            }
        }
    // dd($usersOtp->mobile_no);
        $user = User::where(['parent_id' => 0, 'student_id' => $users->student_id])->first();
        // dd($user);
        Auth::login($user);
    
        return 1; 
    }

   
    
}
