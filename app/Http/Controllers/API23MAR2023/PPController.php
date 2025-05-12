<?php
namespace App\Http\Controllers\API23MAR2023;

use App\Http\Controllers\API23MAR2023\APIBaseController as APIBaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Validator;
use App\Models\Users;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Hash;
use DB;
use URL;
use File;
use Mail;

class PPController extends APIBaseController {
    public function plogin(Request $request) {
    	$user = Users::Where(['mobile_no'=>9509416659])->first(); 
        
        $data=Input::json();
		$user_array=array();
        $user_array['mobile_no']=$data->get('mobile_no');
        $user_array['fcm_token'] = $data->get('fcm_token');
        $user_array['device_type'] = $data->get('device_type');
        $user_array['password'] = $data->get('password');
		$validator = Validator::make($user_array, [
            'mobile_no' => 'required',
        ]);
		if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else{
			$device_type = 3;
			if($user_array['device_type'] == "Android") {
				$device_type = 1;
			}
			else if($user_array['device_type'] == "iOS") {
				$device_type = 2;
			}
			if(!empty($user_array['mobile_no'])){
				$user = Users::Where(['mobile_no'=>$user_array['mobile_no']])->first();
				if(!empty($user)){
					$user['user_status'] = "0";
					if(isset($user_array['password'])){
						$validCredentials = Hash::check($user_array['password'], $user->getAuthPassword());
						if($validCredentials){
							if ($user->status != 1) {
								return $this->sendError('User not active.');
							}
							else{
								Users::where('id',$user->id)->update(['is_login'=>'1','fcm_token'=>$user_array['fcm_token'],'device_type'=>$device_type]);
								$user['user_status'] = "2";
								return $this->sendResponse($user, 'Posts retrieved successfully.');
							}
						}
						else{
							return $this->sendError('Incorrect password.');
						}
					}
					else{
						$user['user_status'] = "1";		// User Already Exists
						$user['mobile_no'] = $user_array['mobile_no'];		
						return $this->sendResponse($user, 'User Already Exists');
					}
				}
				else{
					$user['user_status'] = "3";
					$user['mobile_no'] = $user_array['mobile_no'];	
					return $this->sendResponse($user, 'User does not exists');
				}
			}
			else{
				return $this->sendError('Mobile Number is required.');
			}
        }
    }
	
	public function addUser(Request $request) {
        $data = Input::json();
        $user_array=array();
        $user_array['email']        =$data->get('email');
        $user_array['name']        =$data->get('name');
        $user_array['password']     =$data->get('password');
        $user_array['dob']    =$data->get('dob');
        $user_array['mobile_no']    =$data->get('mobile_no');
        $user_array['device_type'] =$data->get('device_type');
        $user_array['fcm_token']    =$data->get('fcm_token');
		$otp = rand(100000,999999);
		
        $validator = Validator::make($user_array, [
            'name'        => 'required',
            'mobile_no'         => 'required|numeric',
            'password'          => 'required|string|min:6|max:20'
          ],[
            'name.required'   		=> 'Name field cannot be empty',
            'mobile_no.required'    => 'Mobile No. field cannot be empty',
            'password.required'     => 'Password field cannot be empty',
          ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else {
			$device_type = 3;
			if($user_array['device_type'] == "Android"){
				$device_type = 1;
			}
			else if($user_array['device_type'] == "iOS"){
				$device_type = 2;
			}
			$user = Users::create([
			   'name' =>  $user_array['name'],
			   'mobile_no' =>  $user_array['mobile_no'],
			   'email' =>  $user_array['email'],
			   'fcm_token' =>  $user_array['fcm_token'],
			   'device_type' =>  $device_type,
			   'password' => bcrypt($user_array['password']),
			   'otp' =>  $otp,
			   'status' =>  1,
			]);
					
			if(!empty($user_array['mobile_no'])) {
			  $message = urlencode("Use ".$user->otp." to sign-up in to ".$user_array['name'].", as patient-portal account verification code Thanks Team Health Gennie");
			  $this->sendSMS($user_array['mobile_no'],$message,'1707161588059648800');
			}
			$to = $user_array['email'];
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
			$user['is_forgot'] = 0;
			return $this->sendResponse($user, 'User Registered successfully.');
		}
    }
	
	public function resendOtp(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['id']=$data->get('id');
		$user_array['is_forgot']=$data->get('is_forgot');
	   
		$validator = Validator::make($user_array, [
			'id' => 'required'
		]);

		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$user = Users::where(['id'=>$user_array['id']])->first();
			if(!empty($user)) {
				$otp = rand(100000,999999);	
				Users::where('id',$user->id)->update(['otp'=>$otp]);    
				if(!empty($user->mobile_no)) {
				   // $message = urlencode("Use ".$otp." as HealthGennie account security code.");
				   $message = urlencode("Use ".$otp." to sign-up in to ".$user->name.", as patient-portal account verification code Thanks Team Health Gennie");
				   $this->sendSMS($user->mobile_no,$message,'1707161588059648800');
				}
				$user['otp'] = $otp;
				$user['is_forgot'] = $user_array['is_forgot'];
				return $this->sendResponse($user, 'OTP send successfully.');
			}
			else{
				 return $this->sendError('User not activated');
			}
		}
	}
	
	public function otpVerified(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['id'] = $data->get('id');
		$user_array['otp'] = $data->get('otp');
		$user_array['is_forgot'] = $data->get('is_forgot');
		$validator = Validator::make($user_array, [
			'id'                => 'required|max:255',
			],[
			'id.required'   => 'Id field cannot be empty',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$user = Users::where('id',$user_array['id'])->first();
			if(!empty($user)) {
				if($user_array['otp'] == $user->otp) {
					$user->status = 1;
					$user->save();
					$user['is_forgot'] = $user_array['is_forgot'];
					return $this->sendResponse($user, 'Otp Verified Successfully.');
				}
				else{
					return $this->sendError('Invalid or Incorrect OTP');
				}
			}
			else{
				return $this->sendError('User does not exist');
			}
		}
	}

	public function ChangePassword(Request $request) {
        $data=Input::json();
        $password_array=array();
        $password_array['id']=$data->get('id');
        $password_array['new_password']=$data->get('new_password');

        $validator = Validator::make($password_array, [
			'id'           => 'required',
            'new_password' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else{
           $user =  Users::where('id', $password_array['id'])->first();
		   if(!empty($user)){
                $user->password = Hash::make($password_array['new_password']);
                $user->save();
                return $this->sendResponse($user, 'Password Changed Successfully.');
		   }
		   else{
				return $this->sendError('user does not exist');
		   }
        }
    }
	public function forgot(Request $request) {
        $data=Input::json();
        $password_array=array();
        $password_array['mobile_no']=$data->get('mobile_no');
        
        $validator = Validator::make($password_array, [
			'mobile_no'            => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else{
           $user =  Users::where('mobile_no', $password_array['mobile_no'])->first();
		   if(!empty($user)){
				$otp = rand(100000,999999);	
				Users::where('id',$user->id)->update(['otp'=>$otp]);    
				if(!empty($user->mobile_no)) {
				   $message = urlencode("Use ".$otp." to forgot password in to ".$user->name.", as patient-portal account verification code Thanks Team Health Gennie");
				   $this->sendSMS($user->mobile_no,$message,'1707161588064068016');
				}
				$user['otp'] = $otp;
				$user['is_forgot'] = 1;
				return $this->sendResponse($user, 'OTP send successfully.');
		   }
		   else{
				return $this->sendError('user does not exist');
		   }
        }
    }
}
