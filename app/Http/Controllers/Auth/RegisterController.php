<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Models\ehr\EmailTemplate;
use Illuminate\Support\Facades\Mail;
class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'first_name' => $data['first_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
	
	public function register(Request $request) { 
		die; // Currently off registeration
        // Check validation
        $data = $request->all();

        $validatorArray1 = array(
        	'mobile_no' => 'required|digits:10',
			'full_name' => 'required',
			'mobile_no' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|max:10'
        );
        $validatorArray2 = array();
        if (!empty($data['email'])) {
        	 $validatorArray2 = array(
	        	'email' => 'required|email|max:255',
				'password' => 'required|string|min:6|max:20',
				'cPassword' => 'same:password',
	        );
        }
        
        $validatorArray = array_merge($validatorArray1,$validatorArray2);
        $validator = Validator::make($request->all(), $validatorArray);
		if($validator->fails()) {
			$errors = $validator->errors();
			return redirect('register')->withErrors($validator)->withInput();
        }
		else{
			$query = User::where('mobile_no',$request->get('mobile_no'));
			if (!empty($request->get('email'))) {
				$query->orWhere('email','=',$request->get('email'));
			}
			$user = $query->where('parent_id',0)->first();
			if(!empty($user) && !empty($user)) {
				//$this->sendOTP($user->id);
				
				return ["status"=>0,"user_id"=>$user->id];
			}
			else{
				if(!empty($data['full_name'])){
					$name = explode(" ",$data['full_name']);
					$first_name = $name[0];
					$last_name = (isset($name[1]) ? $name[1] : ' ');
				}
				$password = null;
				if(!empty($data['email'])){
					$password = bcrypt($request->get('password'));
				}
				$user = User::create([
				   'first_name' => ucfirst($first_name),
				   'last_name' => $last_name,
				   'email' =>  $request->get('email'),
				   'mobile_no' =>  $request->get('mobile_no'),
				   'device_type' =>  3,
				   'urls' =>  json_encode(getEhrFullUrls()),
				   'password' => $password,
				   'parent_id' => 0,
				   'status' =>  1,
				   'login_type' =>  2,
				]);
				$this->sendOTP($user->id);
				$to = $request->get('email');
				if(!empty($to)) {
					$EmailTemplate = EmailTemplate::where('slug','accountactivationByOtp')->first();
					if($EmailTemplate) {
						$body = $EmailTemplate->description;
						$mailMessage = str_replace(array('{{username}}', '{{otp}}'),array("Sir ",$user->otp),$body);
						$datas = array('to' =>$to,'from' => 'info@healthgennie.com','mailTitle'=>$EmailTemplate->title,'content'=>$mailMessage,'subject'=>$EmailTemplate->subject);
						try{
						Mail::send('emails.all', $datas, function( $message ) use ($datas)
						{
						   $message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
						});
						}
						catch(\Exception $e){
							  // Never reached
						}
					}	
				}
				return ["status"=>1,"user_id"=>$user->id];
			}
			return redirect()->route('driveDashboard');
		}
    }
	
	public function sendOTP($id) {
		$otp = rand(100000,999999);
		if(!empty($id)) {
			User::where('id',$id)->update(['otp'=>$otp]);
		}
		else{
			User::where('id',$id)->update(['otp'=>$otp]);
		}
		$user = User::where('id',$id)->first();
		if(!empty($user->mobile_no)) {
		    $message = urlencode("Your Health Gennie OTP is ".$otp." Creating Healthy Generations Thanks Team Health Gennie");
		    $this->sendSMS($user->mobile_no,$message,'1707161587911566049');
		}
		return $user->id;
	}
}
