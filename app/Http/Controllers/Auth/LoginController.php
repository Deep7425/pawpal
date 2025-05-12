<?php

namespace App\Http\Controllers\Auth;

use App\Constants\AppConstants;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UsersOTP;
use App\Models\LabCart;
use App\Models\OrganizationMaster;
use App\Models\SecurityQuestion;
use App\Models\Student;
use App\Models\UserSecurityQuestion;
use Google\Service\Logging\Resource\Logs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\MockObject\Builder\Stub;

class LoginController extends Controller
{

    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $redirectAfterLogout = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function showLoginForm()
    {
        return view('auth.login');;
    }
   
    public function login(Request $request)
    {
        $data = $request->all();

        if ($data['type'] == 'email') {
            $user = UsersOTP::select(['id', 'expiry_date', 'email'])->Where(['email' => $data['value']])->first();

            if (!empty($user)) {
                $this->sendOTP($user->id, 'email');
                return ["status" => 1, "type" => "email", "user_id" => $user->id, "msg" => "Login successfully"];
            } else {
                $otp = rand(100000, 999999);

                $currentDate = date('Y-m-d H:i:s');
                $expiry_date = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($currentDate)));
                $user = UsersOTP::create([
                    'email' =>  $data['value'],
                    'expiry_date' =>  $expiry_date,
                    'device_type' =>  3,
                    'otp' =>  $otp,
                    'type' => AppConstants::TYPE_EMAIL_OTP,
                    'fcm_token' => @$data['device_token'],
                ]);
                if (!empty($user->email)) {
                    $app_link = "www.healthgennie.com/download";
                    $message =  urlencode("Your Health Gennie OTP is " . $otp . "\nThis otp is valid for 60 seconds Thanks Team Health Gennie");
                    if ($otp != 987654) {
                               $this->sendSMS($user->mobile_no,$message,'1707165547064979677');
                    }
                    return ["status" => 1, "type" => "email", "user_id" => $user->id, "msg" => "Login successfully"];
                }
            }
        } elseif ($data['type'] == 'mobile') {
            $user = UsersOTP::select(['id', 'expiry_date', 'mobile_no'])->Where(['mobile_no' => $data['value']])->first();
           Log::info('==============================o============', [$user]);
            if (!empty($user)) {
                $this->sendOTP($user->id, 'mobile_no');
                return ["status" => 1, "type" => "mobile", "user_id" => $user->id, "msg" => "Login successfully"];
            } else {
                $otp = rand(100000, 999999);
                //$otp = 111111;

                $currentDate = date('Y-m-d H:i:s');
                $expiry_date = date('Y-m-d H:i:s', strtotime('+3 minutes', strtotime($currentDate)));
                $user = UsersOTP::create([
                    'mobile_no' =>  $data['value'],
                    'expiry_date' =>  $expiry_date,
                    'device_type' =>  3,
                    'otp' =>  $otp,
                    'type' => AppConstants::TYPE_MOBILE_OTP,
                    'fcm_token' => @$data['device_token'],
                ]);
                if (!empty($user->mobile_no)) {
                    $app_link = "www.healthgennie.com/download";
                    $message =  urlencode("Your Health Gennie OTP is " . $otp . "\nThis otp is valid for 60 seconds Thanks Team Health Gennie");
                    if ($otp != 987654) {
                                                        $this->sendSMS($user->mobile_no,$message,'1707165547064979677');
                    }
                    return ["status" => 1, "type" => "mobile", "user_id" => $user->id, "msg" => "Login successfully"];
                }
            }
        } elseif ($data['type'] == 'nameOrStudentId') {
            $students = Student::where(['student_id' => $data['value']])->first();
            $otp = rand(100000, 999999);
            $currentDate = date('Y-m-d H:i:s');
            $expiry_date = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($currentDate)));
            $userOtp = UsersOTP::where(['type' => AppConstants::TYPE_STUDENT_ID_OTP, 'user_id' => $students->id])->first();
            if (empty($userOtp)) {
                $userOtp = UsersOTP::create([
                    'type' => AppConstants::TYPE_STUDENT_ID_OTP,
                    'expiry_date' => $expiry_date,
                    'device_type' => 3,
                    'otp' => $otp,
                    'fcm_token' => $data['device_token'],
                    'user_id' => $students->id
                ]);
                $this->sendOtpToDevice($userOtp->fcm_token, $otp);
            } elseif (!empty($userOtp)) {
                $this->sendOtpToDevice($userOtp->fcm_token, $otp);
                if (is_null($userOtp->fcm_token)) {

                    $userOtp->update(['otp' => $otp, 'fcm_token' => $data['device_token'], 'student_id' => $userOtp->student_id, 'expiry_date' => $expiry_date]);
                } else {
                    $userOtp->update(['otp' => $otp, 'expiry_date' => $expiry_date]);
                }
            }

            if (!empty($userOtp)) {
                Log::info('$student->id', [$students->id]);
                Log::info('$p->id', [$userOtp]);
                return ["status" => 12, "type" => "nameOrStudentId", 'student_id' => $userOtp->user_id, 'user_id' => $userOtp->id, "msg" => "Login successfully"];
            } else {
                return ['status' => 2, "msg" => "Somthing Went Wrong!"];
            }
        }
    }

    public function sendOTP($id, $type)
    {
        Log::info('typeeeeeeeeeeeeeeee', [$type]);

        $otp = rand(100000, 999999);
        //$otp = 111111;
        Log::info('hhhh', [$otp]);
        $currentDate = date('Y-m-d H:i:s');
        $expiry_date = date('Y-m-d H:i:s', strtotime('+8 minutes', strtotime($currentDate)));
        UsersOTP::where('id', $id)->update(['otp' => $otp, 'expiry_date' =>  $expiry_date, 'type' => $type]);

        $user = UsersOTP::where('id', $id)->first();
        if ($user->type == 'student_id') {
            $this->sendOtpToDevice($user->fcm_token, $otp);
        }

        if ($type == 'email') {
            $fromEmail = 'noreply@healthgennie.com';
            $subject = 'Login Request OTP(One Time Password)';
            $datas = array(
                'to' => $user->email,
                'from' => $fromEmail,
                'mailTitle' => 'Verification code',
                'subject' => $subject,
                'otp' => $otp,

            );

            // try {
            Mail::send('emails.otp-send-mail', $datas, function ($message) use ($datas) {
                $message->to($datas['to'])->from($datas['from'])->subject($datas['subject']);
            });
            // } catch (\Exception $e) {
            // }
        }
        if(!empty($user->mobile_no)) {
			   $app_link = "www.healthgennie.com/download";
			   $message =  urlencode("Your Health Gennie OTP is ".$otp."\nThis otp is valid for 60 seconds Thanks Team Health Gennie");
			   $this->sendSMS($user->mobile_no,$message,'1707165547064979677');
			}
        return $user->id;
    }
    public function sendUserOtp(Request $request)
    {

        if (!empty($request->user_id)) {
            Log::info('=================sendUserOTp', [$request->user_id, $request->type]);
            $this->sendOTP($request->user_id, $request->type);
            return 1;
        } else {
            return false;
        }
    }

    public function confirmOtp(Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();
            Log::info('data=--=', [$data]);
            $validator = Validator::make($data, [
                'otp' => 'required|numeric|min:6'
            ]);
            if ($validator->fails()) {
                $errors = $validator->errors();
                return $errors->messages()['otp'];
            }
            $userOTP = UsersOTP::where('id', $request->get('user_id'))->first();
            Log::info('userOTP', [$userOTP]);
            $currentDate = date('Y-m-d H:i:s');
            if ($data['otp'] == $userOTP->otp) {
                if ($currentDate <= $userOTP->expiry_date) {
                   

                    if (!empty($userOTP->id && $data['stType'] == 'email')) {
                        $user = User::where(['email' => $userOTP->email, 'parent_id' => 0])->first();
                        Log::info('user in email', [$user]);
                        if (empty($user)) {
                            $user = User::create([
                                'email' =>  $userOTP->email,
                                'password' => @$userOTP->password,
                                'parent_id' => 0,
                                'status' =>  1,
                                'device_type' =>  3,
                                'login_type' =>  2,
                                'is_login' => 1,
                                'notification_status' => 1,
                                'fcm_token' => @$data['device_token']

                            ]);
                            createUsersReferralCode($user->id);
                        } else {
                            User::Where(['id' => $user->id])->update(['fcm_token' => @$data['device_token'] ]);
                            
                        }
                    } elseif (!empty($userOTP->id && $userOTP->type == AppConstants::TYPE_STUDENT_ID_OTP)) {
                        $user = User::where(['student_id' => $userOTP->user_id, 'parent_id' => 0])->first();
                        Log::info('user in student id', [$user]);


                        if (empty($user)) {
                            $user = User::create([
                                'student_id' =>  $userOTP->user_id,
                                'parent_id' => 0,
                                'status' =>  1,
                                'device_type' =>  3,
                                'login_type' =>  2,
                                'is_login' => 1,
                                'notification_status' => 1,

                            ]);

                            createUsersReferralCode($user->id);
                        }
                        $userSecurityQuestion = UserSecurityQuestion::where('user_id', $user->id)->get();
                        if ($userSecurityQuestion->isEmpty()) {
                            return ['status' => 1, 'user_id' => $user->id, "msg" => 'Please fill security question'];
                            // return ['status' => 7, 'user_id' => $user->id, "msg" => 'Please fill security question'];
                        }
                    } elseif (!empty($userOTP->id && $data['stType'] == 'mobile')) {
                        Log::info('iiiiiiiiiiiiiiiiii-');

                        $user = User::where(['mobile_no' => $userOTP->mobile_no, 'parent_id' => 0])->first();
                        Log::info('user in mobile number', [$user]);
                        if (empty($user)) {
                            $user = User::create([
                                'mobile_no' =>  $userOTP->mobile_no,
                                'password' => @$userOTP->password,
                                'parent_id' => 0,
                                'status' =>  1,
                                'device_type' =>  3,
                                'login_type' =>  2,
                                'fcm_token' => @$data['device_token'],
                                'is_login' => 1,
                                'notification_status' => 1,

                            ]);
                            createUsersReferralCode($user->id);
                        } else {
                            User::Where(['id' => $user->id])->update(['fcm_token' => @$data['device_token'] ]);
                           
                           
                        }
                    }
                    if ($userOTP->type === AppConstants::TYPE_STUDENT_ID_OTP) {
                        UsersOTP::Where(['id' => $userOTP->id])->update(['expiry_date' => $currentDate]);
                    } else {
                        UsersOTP::Where(['id' => $userOTP->id])->update(['user_id' => $user->id, 'expiry_date' => $currentDate, 'email' => @$user->email]);
                    }
                    Auth::login($user);
                    Session::put('profile_status', $user->profile_status);
                    if (empty($orgId)) {
                        $message =  urlencode("Welcome to Health Gennie family. Now you can take care of your family's health from the comfort of your home. Book FREE doctor consultation now. Download Health Gennie app from www.healthgennie.com/download or call 8929920932 and WhatsApp 8690006254 Thanks Team Health Gennie");
                        $sdfdf = $this->sendSMS($user->mobile_no, $message, 1707168957379732805);
                    }
                    if (Session::has('CartPackages')) {
                        $packages = Session::get('CartPackages');
                        if (Session::get("lab_company_type") == 0) {
                            LabCart::where(['user_id' => $user->id])->where('type', '!=', 0)->delete();
                        } else {
                            LabCart::where(['user_id' => $user->id, 'type' => 0])->delete();
                        }
                        foreach ($packages as $key => $value) {
                            if (isset($value['DefaultLabs'])) {
                                $alreadyAdded = LabCart::where(['user_id' => $user->id, 'product_name' => $value['DefaultLabs']['title'], 'product_code' => $value['id']])->delete();
                                if ($value['lab_cart_type'] == 'package') {
                                    $lab_cart_type = "OFFER";
                                } else {
                                    $lab_cart_type = "TEST";
                                }
                                LabCart::create([
                                    'type' => $value['lab_company']['id'],
                                    'user_id' => $user->id,
                                    'product_name' => $value['DefaultLabs']['title'],
                                    'product_code' => $value['id'],
                                    'product_type' => $lab_cart_type,
                                ]);
                            }
                        }
                        Session::forget('CartPackages');
                    }
                    // if($data['stType'] != 'nameOrStudentId') {
                    //     if (empty($user->password)) {
                    //         return [
                    //             'status' => 4,
                    //             'user_id' => $user->id
                    //         ];
                    //     }
                    // }

                    return 1;
                } else {
                    return 3;
                }
            } else {
                return 2;
            }
        }
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function verifyLogin(Request $request)
    {
        $data = $request->all();
        try {
        $request->validate([
            'user_identifier' => 'required',
            'password' => 'required|string|min:6',
        ]);
        
        if (!is_numeric($data['user_identifier'])) {
            $user = User::where('email', $data['user_identifier'])->where('parent_id', 0)->first();
            if ($user && Hash::check($data['password'], $user->password)) {
                Auth::login($user);
                return 1;
            } else {
                return 2;
            }
        } elseif (is_numeric($data['user_identifier'])) {
            $user = User::where('mobile_no', $data['user_identifier'])->where('parent_id', 0)->first();
           
            Log::info('user',[ $data['password']]);
            

            if ($user && Hash::check($data['password'], $user->password)) {

                Auth::login($user);
                return 1;
            } else {
                return 2;
            }
        } else {
            return 3;
        }
    } catch(\Illuminate\Validation\ValidationException $e) {
        return response()->json(['status' => 'error', 'code' => 4, 'errors' => $e->errors()], 422);
        }
    }
    public function checkUser(Request $request)
    {
        $data = $request->all();
        if (isset($data['type'])) {
            if (isset($data['checkPassword'])) {
          

                if ($data['type'] == 'mobile') {
                    $user = User::where('mobile_no', $data['value'])->where('parent_id', 0)->first();
                    return response()->json($user ? ['status' => 1, 'type' => 'mobile', '==='=>$user] : ['status' => 2, 'type' => 'mobile', '==='=>$user]);
                } elseif ($data['type'] == 'email') {
                    $user = User::where('email', $data['value'])->where('parent_id', 0)->first();
                    return response()->json($user ? ['status' => 1, 'type' => 'email', '==='=>$user] : ['status' => 2, 'type' => 'email', '==='=>$user]);
                }
            } elseif ($data['type'] == 'nameOrStudentId') {
                // Look up the student using the provided student ID
                $student = Student::where('student_id', $data['value'])->first();

                if ($student) {
                    // Find the associated user by student ID
                    $user = User::where('student_id', $student->id)->first();

                    if ($user) {

                        return ['status' => 1, 'type' => 'nameOrStudentId'];
                    } else {
                        return ['status' => 1, 'type' => 'nameOrStudentId', 'credential' => 'user Not Found'];
                    }
                }

                // Status 2: Student/User not found, cannot fetch data
                return ['status' => 2, 'type' => 'nameOrStudentId'];
            } elseif ($data['type'] == 'email') {
            
                $user = User::where(['email' => $data['value'], 'parent_id' => 0])->first();
                if ($user) {
                    $status = empty($user->password) ? 3 : 1;
                    $response = ['status' => $status, 'type' => 'email'];

                    // Add user_id only if status is 3
                    if ($status == 3) {
                        $response['user_id'] = $user->id;
                    }
                    

                    return response()->json($response);
                } else {
                    return response()->json(['status' => 2, 'type' => 'email']);
                }
            } elseif ($data['type'] == 'mobile') {
                $user = User::where(['mobile_no' => $data['value'], 'parent_id' => 0])->first();
                if ($user) {
                    $status = empty($user->password) ? 3 : 1;
                    $response = ['status' => $status, 'type' => 'mobile'];

                    // Add user_id only if status is 3
                    if ($status == 3) {
                        $response['user_id'] = $user->id;
                    }

                    return response()->json($response);
                } else {
                    return response()->json(['status' => 2, 'type' => 'mobile']);
                }
            } else {
                return response()->json(['status' => 4]);
            }
        }
    }
 

    public function createNewUser(Request $request)
    {

        $data = $request->all();


        if (!empty($data['type'] == 'email')) {
            $otp = rand(100000, 999999);
            $currentDate = date('Y-m-d H:i:s');
            $expiry_date = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($currentDate)));
            $user = UsersOTP::create([
                'email' =>  $data['user_identifier'],
                'expiry_date' =>  $expiry_date,
                'device_type' =>  3,
                'otp' =>  $otp,
                'type' => AppConstants::TYPE_EMAIL_OTP,
                'fcm_token' => @$data['device_token'],
                'password' => Hash::make($data['password'])
            ]);
            return ["status" => 1, "user_id" => $user->id, "type" => "email", "msg" => "OTP send successfully"];
        } elseif (!empty($data['type'] == 'mobile')) {
            $otp = rand(100000, 999999);
            $currentDate = date('Y-m-d H:i:s');
            $expiry_date = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($currentDate)));
            $user = UsersOTP::create([
                'mobile_no' =>  $data['user_identifier'],
                'expiry_date' =>  $expiry_date,
                'device_type' =>  3,
                'otp' =>  $otp,
                'type' => AppConstants::TYPE_MOBILE_OTP,
                'fcm_token' => @$data['device_token'],
                'password' => Hash::make($data['password'])
            ]);
            Log::info('$user=============', [$user]);
            return ["status" => 1, "user_id" => $user->id, "type" => "mobile", "msg" => "OTP send successfully"];
        }
    }

    public function verifyOrganizationLogin(Request $request)
    {
        $data = $request->all();

        $organization = OrganizationMaster::where('slug', $data['institute_id'])->first();

        if ($organization) {
            if ($organization->pwd === $data['password']) {
                $request->session()->put('organizationMaster', $organization);
                return response()->json(['status' => 1, 'message' => 'Login successful', 'slug' => $data['institute_id']]);
            } else {
                return response()->json(['status' => 2, 'message' => 'Password does not match']);
            }
        } else {
            return response()->json(['status' => 0, 'message' => 'Organization title does not match']);
        }
    }
    public function generatePasswords(Request $request)
    {
        $data = $request->all();
        Log::info('Request Data:', [$data]);
    
        // Find the student based on the identifier
        $student = Student::where('student_id', $data['user_identifier'])->first();
        if (!$student) {
            return response()->json([
                'status' => 0,
                'message' => 'Invalid student identifier.',
            ]);
        }
        Log::info('Student Data:', [$student]);
    
        // Check if logintype2 is set to 2 (Login scenario)
        if (isset($data['logintype2']) && $data['logintype2'] == 2) {
            $user = User::where('student_id', $student->id)->first();
    
            if ($user && Hash::check($data['password'], $user->password)) {
                Auth::login($user);
    
                return response()->json([
                    'status' => 1,
                    'message' => 'Login successful.',
                ]);
            }
    
            return response()->json([
                'status' => 0,
                'message' => 'Invalid credentials.',
            ]);
        }
    
        // Else, create a new user
        $user = User::updateOrCreate(
            ['student_id' => $student->id],
            [
                'password' => Hash::make($data['password']),
                'parent_id' => 0,
                'status' => 1,
                'device_type' => 3,
                'login_type' => 2,
                'fcm_token' => $data['device_token'] ?? null,
                'is_login' => 1,
                'notification_status' => 1,
            ]
        );
    
        Log::info('Created/Updated User:', $user->toArray());
    
        // Authenticate the user
        Auth::login($user);
        Log::info('User logged in:', [$user->id]);
    
        // Return a success response
        return response()->json([
            'status' => 1,
            'message' => 'Password successfully generated.',
        ]);
    }
    
    public function logoutOrganization(Request $request)
    {
        $request->session()->forget('organizationMaster');
        $request->session()->flush();
        return 1;
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



    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }
}
