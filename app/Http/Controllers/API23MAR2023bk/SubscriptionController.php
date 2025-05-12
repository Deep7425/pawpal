<?php
namespace App\Http\Controllers\API23MAR2023;

use App\Http\Controllers\API23MAR2023\APIBaseController as APIBaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Hash;
use DB;
use URL;
use File;
use Mail;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Models\Appointments;
use App\Models\Plans;
use App\Models\PlanPeriods;
use App\Models\UserSubscribedPlans;
use App\Models\UsersSubscriptions;
use App\Models\UserSubscriptionsTxn;
use App\Models\Doctors;
use App\Models\User;
use App\Models\Pages;
use App\Models\ReferralMaster;
use App\Models\UserDetails;
use Softon\Indipay\Facades\Indipay;
use App\Models\UserReferral;
use PDF;
use PaytmWallet;
class SubscriptionController extends APIBaseController {
	
	public function getSubscriptionPlans(Request $request) {
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
		else {
			$response = [];
			$plan_id = 0;
			if(!empty($user_array['user_id'])) {
				$result = UsersSubscriptions::with("PlanPeriods")->where('user_id',$user_array['user_id'])->where('order_status','1')->whereHas('PlanPeriods', function($q){
				  $q->Where('status', 1);
				})->first();
				if(!empty($result)){
					$meta_data = json_decode($result->meta_data);
					$plan_id = @$meta_data->plan_id;
				}
			}
			if($plan_id > 0) {
				if($user_array['lng'] == "hi") {
					$plans = Plans::Where(["delete_status"=>'1','status'=>1])->whereIn("type",array(3,4))->where('id','!=',46)->orderBy('price', 'ASC')->get();
					$response["upper_content"] = Pages::where(['slug'=>'subscription-plan-page-content-upperapp'])->where("lng",$user_array['lng'])->first();
					$response["bottom_content"] = Pages::where(['slug'=>'subscription-plan-page-content-bottomapp'])->where("lng",$user_array['lng'])->first();
				}
				else{
					$plans = Plans::Where(["delete_status"=>'1','status'=>1])->whereIn("type",array(1,2))->where('id','!=',45)->orderBy('price', 'ASC')->get();
					$response["upper_content"] = Pages::where(['slug'=>'subscription-plan-page-content-upperapp'])->first();
					$response["bottom_content"] = Pages::where(['slug'=>'subscription-plan-page-content-bottomapp'])->first();
				}
				if($plans->count() >0){
					foreach($plans as $raw) {
						if($plan_id == $raw->id){
							$raw['is_sub'] = true;
						}
						else {
							$raw['is_sub'] = false;
						}
					}
				}
				$response["plans"] = $plans;
				$amount = @UserDetails::select('wallet_amount')->where(['user_id'=>$user_array['user_id']])->first()->wallet_amount;
				$response["wallet_amount"] = $amount;
				$response["avail_limit"] = getSetting("reward_subs_avail_limit")[0];
			}
			else {
				if($user_array['lng'] == "hi") {
					$plans = Plans::Where(["delete_status"=>'1','status'=>1])->whereIn("type",array(3,4))->where('id','!=',49)->orderBy('price', 'ASC')->get();
					$response["upper_content"] = Pages::where(['slug'=>'subscription-plan-page-content-upperapp'])->where("lng",$user_array['lng'])->first();
					$response["bottom_content"] = Pages::where(['slug'=>'subscription-plan-page-content-bottomapp'])->where("lng",$user_array['lng'])->first();
				}
				else{
					$plans = Plans::Where(["delete_status"=>'1','status'=>1])->whereIn("type",array(1,2))->where('id','!=',48)->orderBy('price', 'ASC')->get();
					$response["upper_content"] = Pages::where(['slug'=>'subscription-plan-page-content-upperapp'])->first();
					$response["bottom_content"] = Pages::where(['slug'=>'subscription-plan-page-content-bottomapp'])->first();
				}
				$response["plans"] = $plans;
				$amount = @UserDetails::select('wallet_amount')->where(['user_id'=>$user_array['user_id']])->first()->wallet_amount;
				$response["wallet_amount"] = $amount;
				$response["avail_limit"] = getSetting("reward_subs_avail_limit")[0];
			}
			return $this->sendResponse($response,'',true);
		}
	}
	
	public function getMySubscription(Request $request)  {
		$data = Input::json();
		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');

		$validator = Validator::make($user_array, [
			'user_id' => 'required'
		]);

		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$UsersSubscriptions = UsersSubscriptions::with('UserSubscribedPlans.Plans','UserSubscriptionsTxn','PlanPeriods')->Where('user_id', $user_array['user_id'])->where('order_status',1)->orderBy('id', 'desc')->get();
			return $this->sendResponse($UsersSubscriptions,'',true);
		}
	 }
	 
	
	
	public function subscriptionPayOld(Request $request) {
		
			$data = $request->all();
            $params =  json_decode(base64_decode($data['params']));
			
			$user_array=array();
			$user_array['user_id'] = $params->user_id;
			$user_array['tax'] = $params->tax;
			$user_array['order_subtotal'] = $params->order_subtotal;
			$user_array['order_total'] = $params->order_total;
			$user_array['plan_id'] = $params->plan_id;
			$user_array['ref_code'] = @$params->ref_code;

		  $validator = Validator::make($user_array, [
			'user_id'   =>  'required',
			'order_subtotal'      =>  'required',
			'order_total'      =>  'required',
			'plan_id'      =>  'required',
		  ]);
		  if($validator->fails()){
			return $this->sendError($validator->errors());
		  }
		  else{
				$success = false;
				$subscription =  UsersSubscriptions::create([
					 'user_id' => $user_array['user_id'],
					 'payment_mode' => 1,
					 'ref_code' => $user_array['ref_code'],
					 'coupon_id' => null,
					 'tax' => $user_array['tax'],
					 'order_subtotal' => $user_array['order_subtotal'],
					 'order_total' => $user_array['order_total'],
				]);
				$plan = Plans::where('id',$user_array['plan_id'])->first();
				$subscribedPlan = new UserSubscribedPlans;
				$subscribedPlan->plan_id = $plan->id;
				$subscribedPlan->plan_price = $plan->price;
				$subscribedPlan->discount_price = $plan->discount_price;
				$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
				$subscribedPlan->plan_duration = $plan->plan_duration;
				$subscribedPlan->appointment_cnt = $plan->appointment_cnt;
				$subscribedPlan->lab_pkg = $plan->lab_pkg;
				$subscribedPlan->meta_data = json_encode($plan);
				$subscription->UserSubscribedPlans()->save($subscribedPlan);	
						
				//for the plan trail period
				$duration_type = $plan->plan_duration_type;
				if($duration_type=="d") {
				  $duration_in_days = $plan->plan_duration;
				}
				elseif ($duration_type=="m") {
				  $duration_in_days = (30*$plan->plan_duration);
				}
				elseif ($duration_type=="y") {
				  $duration_in_days = (366*$plan->plan_duration);
				}
				$end_date = date('Y-m-d H:i:s', strtotime($subscribedPlan->created_at.'+'.$duration_in_days.' days'));
				$PlanPeriods =  PlanPeriods::create([
				   'subscription_id' => $subscription->id,
				   'subscribed_plan_id' => $subscribedPlan->id,
				   'user_plan_id' => $user_array['plan_id'],
				   'user_id' => $user_array['user_id'],
				   'start_trail' => $subscribedPlan->created_at,
				   'end_trail'=> $end_date,
				   'remaining_appointment' => $plan->appointment_cnt,
				   'lab_pkg_remaining' => 1,
				   'status' => 0
				]);		
				
				 $parameters = [
				   'tid' => strtotime("now"),
				   'order_id' => $subscribedPlan->id,
				   'amount' => $subscription->order_total,
					// 'amount' => 1,
				   'merchant_param1' => $plan->plan_title,
				   'merchant_param2' => $user_array['user_id'],
				   'merchant_param3' => "Gennie Plan",
				  ];
				  // gateway = CCAvenue / others
				  $order = Indipay::gateway('CCAvenue')->prepare($parameters);
				  return Indipay::process($order);
				  //return $this->sendResponse($output,'Lab create Successfully.',$success);
			}
	}
	public function subscriptionPay(Request $request) {
			$data = $request->all();
            $params =  json_decode(base64_decode($data['params']));
			$user_array=array();
			$user_array['user_id'] = $params->user_id;
			$user_array['tax'] = $params->tax;
			$user_array['order_subtotal'] = $params->order_subtotal;
			$user_array['order_total'] = $params->order_total;
			$user_array['plan_id'] = $params->plan_id;
			$user_array['coupon_id'] = @$params->coupon_id;
			$user_array['coupon_discount'] = @$params->coupon_discount;
			$user_array['referral_user_id'] = @$params->referral_user_id;
			$user_array['coupon_code'] = @$params->coupon_code;
			$user_array['patientInfo'] = @$params->patientInfo;
			$user_array['availWalletAmt'] = @$params->availWalletAmt;
			$user_array['ref_code'] = @$params->coupon_code;

		  $validator = Validator::make($user_array, [
			'user_id'   =>  'required',
			'order_subtotal'      =>  'required',
			'order_total'      =>  'required',
			'plan_id'      =>  'required',
		  ]);
		  if($validator->fails()){
			return $this->sendError($validator->errors());
		  }
		  else{
				$success = false;
				$orderId = "SUBS"."1";
				$userSubs = UsersSubscriptions::orderBy("id","DESC")->first();
				if(!empty($userSubs)){
					$sid = $userSubs->id + 1;
					$orderId = "SUBS".$sid;
				}
				$subscription =  UsersSubscriptions::create([
					 'user_id' => $user_array['user_id'],
					 'order_id' => $orderId,
					 'payment_mode' => 1,
					 'ref_code' => $user_array['referral_user_id'],
					 'coupon_id' => $user_array['coupon_id'],
					 'tax' => $user_array['tax'],
					 'order_subtotal' => $user_array['order_subtotal'],
					 'order_total' => $user_array['order_total'],
					 'coupon_discount' => $user_array['coupon_discount'],
					 'meta_data' => json_encode($user_array),
				]);
				/*$plan = Plans::where('id',$user_array['plan_id'])->first();
				$subscribedPlan = new UserSubscribedPlans;
				$subscribedPlan->plan_id = $plan->id;
				$subscribedPlan->plan_price = $plan->price;
				$subscribedPlan->discount_price = $plan->discount_price;
				$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
				$subscribedPlan->plan_duration = $plan->plan_duration;
				$subscribedPlan->appointment_cnt = $plan->appointment_cnt;
				$subscribedPlan->lab_pkg = $plan->lab_pkg;
				$subscribedPlan->meta_data = json_encode($plan);
				$subscription->UserSubscribedPlans()->save($subscribedPlan);	
						
				//for the plan trail period
				$duration_type = $plan->plan_duration_type;
				if($duration_type=="d") {
				  $duration_in_days = $plan->plan_duration;
				}
				elseif ($duration_type=="m") {
				  $duration_in_days = (30*$plan->plan_duration);
				}
				elseif ($duration_type=="y") {
				  $duration_in_days = (366*$plan->plan_duration);
				}
				$end_date = date('Y-m-d', strtotime($subscribedPlan->created_at.'+'.$duration_in_days.' days'));
				$PlanPeriods =  PlanPeriods::create([
				   'subscription_id' => $subscription->id,
				   'subscribed_plan_id' => $subscribedPlan->id,
				   'user_plan_id' => $user_array['plan_id'],
				   'user_id' => $user_array['user_id'],
				   'start_trail' => date('Y-m-d'),
				   'end_trail'=> $end_date,
				   'remaining_appointment' => $plan->appointment_cnt,
				   'lab_pkg_remaining' => 0,
				   'status' => 0
				]);	*/	
				// $user = User::where("id",$user_array['user_id'])->first();
				/* $parameters = [
				   'tid' => strtotime("now"),
				   'order_id' => $subscribedPlan->id,
				   'amount' => $subscription->order_total,
					// 'amount' => 1,
				   'merchant_param1' => $plan->plan_title,
				   'merchant_param2' => $user_array['user_id'],
				   'merchant_param3' => "Gennie Plan",
				  ];*/
				  // gateway = CCAvenue / others
				  // $order = Indipay::gateway('CCAvenue')->prepare($parameters);
				  // return Indipay::process($order);
				  //return $this->sendResponse($output,'Lab create Successfully.',$success);
				  
				/*$parameters = [];
				// $parameters["MID"] = "fiBzPH32318843731373"; 
				$parameters["MID"] = "yNnDQV03999999736874"; 
				$parameters["ORDER_ID"] = $orderId; 
				$parameters["CUST_ID"] = $user_array['user_id']; 
				$parameters["TXN_AMOUNT"] = $subscription->order_total; 
				$parameters["CALLBACK_URL"] = url('paytmresponse'); 
				$order = Indipay::gateway('Paytm')->prepare($parameters);
				return Indipay::process($order);  */
				
				$user = User::where("id",$user_array['user_id'])->first();
				$mbl = isset($user->mobile_no) ? $user->mobile_no : '0000000000';
				$email = !empty($user->email)   ? $user->email : 'test@mailinator.com';
				$parameters["order"] = $orderId;
				$parameters["amount"] = $subscription->order_total;
				if($user->mobile_no == 7691079774){
					$parameters["amount"] = 1;
				}
				$parameters["user"] = $user_array['user_id'];
				$parameters["mobile_number"] = $mbl;
				$parameters["email"] = $email;
				$parameters["callback_url"] = url('paytmresponse');
				$payment = PaytmWallet::with('receive');
				$payment->prepare($parameters);
				return $payment->receive();
			}
	}
		
	public function checkMySubscription(Request $request)  {
		$data = Input::json();
		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');

		$validator = Validator::make($user_array, [
			'user_id' => 'required'
		]);

		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$user['is_subscribed'] = 0;
			$user['max_appointment_fee'] = 0;
			$dt = date('Y-m-d');
			$is_subscribed = PlanPeriods::select('subscription_id')->whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where('user_id', $user_array['user_id'])->where('remaining_appointment', '!=', '0')->where('status', '1')->first();
			if (!empty($is_subscribed)) {
				$UserSubscribedPlans = UserSubscribedPlans::select('meta_data')->where('subscription_id', $is_subscribed->subscription_id)->first();
				// $user['is_subscribed'] = 1;
				$plan_meta = json_decode($UserSubscribedPlans->meta_data);
				$max_fee = @$plan_meta->max_appointment_fee;
				$user['max_appointment_fee'] = $max_fee;
			}
			return $this->sendResponse($user,'',true);
		}
	 }
	 
	 public function checkRefCode(Request $request) {
		$data = Input::json();
		$user_array=array();
		$user_array['ref_code'] = $data->get('ref_code');
		$user_array['lng'] = $data->get('lng');
		$user_array['user_id'] = (int)$data->get('user_id');
		$validator = Validator::make($user_array, [
			'ref_code' => 'required',
			'user_id' => 'required',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$success = false;
			if($user_array['lng'] == "hi") {
				$msg = "रेफरल कोड मेल नहीं खाता है";	
			}
			else{
				$msg = "Referral code does not matched";
			}
			$res = ["referral_user_id"=>"","coupon_discount"=>"","type"=>""];
			$userWithReferralCode = UserReferral::where('referralCode', $user_array['ref_code'])->first();
			$refUserId = getUserIdByRefCode($user_array['ref_code'],$user_array['user_id']);
			if(!empty($userWithReferralCode) || checkActiveSubs($refUserId) > 0) {
				$success = true;
				if($user_array['lng'] == "hi") {
					$msg = "रेफरल कोड का मिलान हुआ";
				}
				else{
					$msg = "Referral code matched";	
				}
				$res['referral_user_id'] =  !empty($userWithReferralCode) ? $userWithReferralCode->id : null;
				$res['type'] =  1;
				$res['coupon_discount'] =  499;
			}
			else {
				$refData = ReferralMaster::where('code',strtoupper($user_array['ref_code']))->where(['status'=>1,'delete_status'=>1])->first();
				if(!empty($refData)){
					$dt = date('Y-m-d');
					if($refData->code_last_date < $dt){
						$success = false;
						if($user_array['lng'] == "hi") {
							$msg = "रेफरल कोड समाप्त हो गया है";
						}
						else{
							$msg = "Referral code Is Expired";	
						}
						return $this->sendResponse($res,$msg,$success);
					}
					if(!empty($refData->plan_ids)) {
						$plan_ids = explode(",",$refData->plan_ids);
						$planId = $data->get('plan_id');
						if(in_array($data->get('plan_id'),$plan_ids)) {
							if(strtoupper($user_array['ref_code']) == 'JANMASHTAMI'){
								if($planId == 11 || $planId == 12){
									$dis = 500;
								}
								else if($planId == 4 || $planId == 5){
									$dis = 300;
								}
								else if($planId == 7 || $planId == 8){
									$dis = 200;
								}
							}
							else{
								$dis = getDiscount($refData->referral_discount_type,$refData->referral_discount,$data->get('plan_id'));
							}
							$res['type'] =  2;
							$res['referral_user_id'] =  $refData->id;
							$res['coupon_discount'] =  round($dis);
							$success = true;
							if($user_array['lng'] == "hi") {
								$msg = "रेफरल कोड का मिलान हुआ";
							}
							else{
								$msg = "Referral code matched";	
							}
						}
					}
					else{
						$dis = getDiscount($refData->referral_discount_type,$refData->referral_discount,$data->get('plan_id'));
						$res['type'] =  2;
						$res['referral_user_id'] =  $refData->id;
						$res['coupon_discount'] =  round($dis);
						$success = true;
						if($user_array['lng'] == "hi") {
							$msg = "रेफरल कोड का मिलान हुआ";
						}
						else{
							$msg = "Referral code matched";	
						}
					}
				}
			}
			return $this->sendResponse($res,$msg,$success);
		}
	 }
	 public function downloadSubscriptionReceipt(Request $request){
			$data=Input::json();
			$user_array=array();
			$user_array['order_id'] = $data->get('order_id');
			$user_array['lng'] = $data->get('lng');
			$validator = Validator::make($user_array, [
				'order_id' => 'required|max:50',
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$subscription = UsersSubscriptions::with(['User','UserSubscribedPlans.Plans','UserSubscriptionsTxn','PlanPeriods'])->where('id',$user_array['order_id'])->first();
				if($user_array['lng'] == "hi"){
					$appointmentData = view('subscription.DownloadSubsReceiptPDFHindi',compact('subscription'))->render();
				}
				else{
					$appointmentData = view('subscription.DownloadSubsReceiptPDF',compact('subscription'))->render();
				}
				$output = PDF::loadHTML($appointmentData)->output();
				file_put_contents(public_path()."/pdfviewforSubscription.pdf", $output);
				$pdf_url = 	url("/")."/public/pdfviewforSubscription.pdf?".time();
				return $this->sendResponse($pdf_url,'',true);
			}
		}
	public function getOffersPlans(Request $request) {
		$data = Input::json();
		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		$validator = Validator::make($user_array, [
			// 'lng' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$response = [];
			if($user_array['lng'] == "hi") {
				$plans = Plans::whereIn("id",array(31,32,33))->orderBy('price', 'ASC')->get();
			}
			else{
				$plans = Plans::whereIn("id",array(29,30,34))->orderBy('price', 'ASC')->get();
			}
			$response["upper_content"] = Pages::where(['slug'=>'offer-plan-app'])->where("lng",$user_array['lng'])->first();
			$response["bottom_content"] = Pages::where(['slug'=>'subscription-plan-page-content-bottomapp'])->where("lng",$user_array['lng'])->first();
			$response["plans"] = $plans;
			return $this->sendResponse($response,'',true);
		}
	}
	
	public function getPlanDetails(Request $request) {
		$data = Input::json();
		$user_array=array();
		$user_array['plan_id'] = $data->get('plan_id');
		$validator = Validator::make($user_array, [
			'plan_id' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$plan = Plans::Where(["delete_status"=>'1','status'=>1])->where("id",$user_array['plan_id'])->first();
			return $this->sendResponse($plan,'',true);
		}
	}
}
