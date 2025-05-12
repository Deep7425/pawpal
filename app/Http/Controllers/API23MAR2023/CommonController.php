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
use App\Models\Pages;
use App\Models\NewsFeeds;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\PpSliders;
use App\Models\Doctors;
use App\Models\Speciality;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\OutSideAppointments;
use App\Models\foodPreferenceMaster;
use App\Models\smokingHabitsMaster;
use App\Models\occupationMaster;
use App\Models\alcoholConsumptionMaster;
use App\Models\activityLevelMaster;
use App\Models\ehr\Appointments;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\RoleUser;
use App\Models\ehr\Patients;
use App\Models\ehr\VdoRecord;
use App\Models\ehr\PracticeDocuments;
use App\Models\ehr\PatientRagistrationNumbers;
use App\Models\StepsDetails;
use App\Models\MedicineDetails;
use App\Models\ManageDiabetesRecords;
use App\Models\ManageWeightRecords;
use App\Models\ManageBpRecords;
use App\Models\MedicineHits;
use App\Models\Coupons;
use App\Models\AdsHits;
use App\Models\SearchResults;
use App\Models\Admin\BannerMaster;
use App\Models\ehr\CityLocalities;
use App\Models\Support;
use App\Models\WaitingTimeMaster;
use App\Models\ComplimentsMaster;
use App\Models\OrganizationMaster;
use App\Models\Admin\AdMaster;
use App\Models\ehr\NotifyUserSms;
use App\Models\UsersSubscriptions;
use Auth;
use App\Http\Controllers\PaytmChecksum;
use App\Models\UserCashback;
use App\Models\ReferralCashback;
use App\Models\ManageTemperatureRecords;
use PDF;
use App\Models\HealthQuestion;
use App\Models\ReferralMaster;
use App\Models\UserWallet;
use App\Models\Admin\Symptoms;
use App\Models\ApptFeedbackStatus;
use App\Models\UserNotifications;
use App\Models\NotificationUserId;
use App\Models\Settings;
use App\Models\VhtOrder;
use App\Http\Controllers\AgoraToken\RtcTokenBuilder;
class CommonController extends APIBaseController {
	
	public function getFoodPreferenceMaster(Request $request) {
		$food_pref = foodPreferenceMaster::orderBy("id","ASC")->get();
		return $this->sendResponse($food_pref, '',$success = true);
    }
	
	public function getSmokingHabitsMaster(Request $request) {
		$food_pref = smokingHabitsMaster::orderBy("id","ASC")->get();
		return $this->sendResponse($food_pref, '',$success = true);
    }
	
	public function getOccupationMaster(Request $request) {
		$food_pref = occupationMaster::orderBy("id","ASC")->get();
		return $this->sendResponse($food_pref, '',$success = true);
    }
	
	public function getAlcoholConsumptionMaster(Request $request) {
		$food_pref = alcoholConsumptionMaster::orderBy("id","ASC")->get();
		return $this->sendResponse($food_pref, '',$success = true);
    }
	public function getOfferBannersOld(Request $request) {
		$data = Input::json();  
		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		
		$validator = Validator::make($user_array, [
			// 'lng'   => 'required|max:50',
		]);
		if($validator->fails()) {
			return $this->sendError($validator->errors());
		}
		else {
			if($user_array['lng'] == "hi"){
				$banners = BannerMaster::where('status','1')->whereIn("type",array(3,4))->orderBy("id","DESC")->get();
			}
			else{
				$banners = BannerMaster::where('status','1')->whereIn("type",array(1,2))->orderBy("id","DESC")->get();
			}
			if(count($banners) > 0 ){
				foreach($banners as $value) {
					if(!empty($value->image)) {  
						$image_url = url("/")."/public/offerBannerFiles/".$value->image;
						$value['image'] = $image_url;
					}
					else{
						$value['image'] = null;
					}
				}
			}
			return $this->sendResponse($banners, '',true);
		}
    }
	
	public function getOfferBanners(Request $request) { 
		$data = Input::json();  
		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		$user_array['cashbackOrderId'] = $data->get('cashbackOrderId');
		$user_array['user_id'] = $data->get('user_id');
		$user_array['type'] = $data->get('type');
		$validator = Validator::make($user_array, [
			// 'lng'   => 'required|max:50',
		]);
		if($validator->fails()) {
			return $this->sendError($validator->errors());
		}
		else {
			if($user_array['type'] == "med"){
				if($user_array['lng'] == "hi") {
					$banners = BannerMaster::where('status','1')->where("type",6)->orderBy("id","ASC")->get();
				}
				else{
					$banners = BannerMaster::where('status','1')->where("type",5)->orderBy("id","ASC")->get();
				}
			}
			else{
				if($user_array['lng'] == "hi") {
					$banners = BannerMaster::where('status','1')->whereIn("type",array(3,4))->orderBy("id","ASC")->get();
				}
				else{
					$banners = BannerMaster::where('status','1')->whereIn("type",array(1,2))->orderBy("id","ASC")->get();
				}
			}
			if(count($banners) > 0 ){
				foreach($banners as $value) {
					if(!empty($value->image)) {
						$image_url = url("/")."/public/offerBannerFiles/".$value->image;
						$value['image'] = $image_url;
					}
					else{
						$value['image'] = null;
					}
				}
			}
			// if($user_array['user_id']){
				// $checkUser = User::where(['id'=>$user_array['user_id']])->first();
				//print_r($checkUser->is_cashBack);pr($checkUser);
				// if($checkUser->is_cashBack == 0) {
                   // $cashbackAmt = $this->walletPaytmDisburseStatus($user_array['cashbackOrderId'],$user_array['user_id'],$checkUser->mobile_no);
				   // saveUserActivity($request, 'getOfferBanners', 'banners', $user_array['user_id']);
                   // return $this->sendResponse($banners, $cashbackAmt,true);
				// }
			// }else{
				// saveUserActivity($request, 'getOfferBanners', 'banners', '');
                return $this->sendResponse($banners, 'no',true);
			// }
		}
    }

    public function walletPaytmDisburseStatus($order_id,$userId,$mobile_no) {
		// $order_id = "HGCB13586"; 
		 sleep(7);
        $paytmParams = array();
	    $paytmParams["orderId"] = $order_id;
		$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
		$checksum = PaytmChecksum::generateSignature($post_data, "OJ0vuq8N&t3aAR7y");
		// $checksum = PaytmChecksum::generateSignature($post_data, "J7IeK&JZ6LwrfmBv");
		// $x_mid      = "FITKID54692936504563";
		$x_mid      = "FITKID61350170158252";
		$x_checksum = $checksum;
		/* for Staging */
        // $url = "https://staging-dashboard.paytm.com/bpay/api/v1/disburse/order/query";
        /* for Production */
        
        $url = "https://dashboard.paytm.com/bpay/api/v1/disburse/order/query";
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json", "x-mid: " . $x_mid, "x-checksum: " . $x_checksum)); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		$response = curl_exec($ch);
		$response = json_decode($response,true);
        // sleep(2);
		 //pr($response);
        $cashback = 0;
		if($response['status'] == "SUCCESS") {  //echo "kaps";
		    UserCashback::where('user_id',$userId)->update([
			   'meta_data' =>  json_encode($response),
			   'status' =>  1,
			   'paytm_status' => $response["statusCode"],
			]);
			User::where('id',$userId)->update(['is_cashBack'=>1]); 
			$cashback = $response["result"]['amount'];
			if(!empty($mobile_no)) {
			  $message = urlencode("Congratulations ! You have earn a reward of Rs ".$cashback."/- in your paytm wallet. Stay Healthy with Health Gennie Thanks Team Health Gennie");
			  $this->sendSMS($mobile_no,$message,'1707161587959478930');
			}
		}
		else{
			UserCashback::where('user_id',$userId)->update([
			   'meta_data' =>  json_encode($response),
			   'paytm_status' => $response["statusCode"],
			]);
		}
		return $cashback;
		//pr($response);
	}
	
	public function fetchPopupStatus(Request $request) {
		$data = Input::json();  
		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');

		$is_wmh_pop_show = getSetting("is_wmh_pop_show")[0];
		$refText = getSetting("refer_text_app")[0];
		$ref = ReferralCashback::select("id")->where("referred_id",$user_array['user_id'])->count();
		if($ref > 0) {
			$is_wmh_pop_show = 0;
		}
		if($is_wmh_pop_show == 1) {
			$datee = date('Y-m-d', strtotime('-3 days'));
			$user = User::select('id')->where("id",$user_array['user_id'])->where("created_at",'>=',$datee)->first();
			if(!empty($user)) {
				$is_wmh_pop_show = 1;
			}
			else{
				$is_wmh_pop_show = 0;
			}
			if(!empty($user) && checkActiveSubs($user->id) > 0) {
				$is_wmh_pop_show = 0;
			}
		}
		$response = ["wmh_popup"=>$is_wmh_pop_show,"refer_text_app"=>$refText];
		return $this->sendResponse($response, '',true);
	}
	public function getAds(Request $request) {
		$data = Input::json();  
		$user_array=array();
		$user_array['lng'] = $data->get('lng');
		$user_array['slug'] = $data->get('slug');
		$user_array['slug2'] = $data->get('slug2');
		$user_array['user_id'] = $data->get('user_id');
		$user_array['p_id'] = $data->get('p_id');
		
		$validator = Validator::make($user_array, [
			// 'lng'   => 'required|max:50',
		]);
		if($validator->fails()) {
			return $this->sendError($validator->errors());
		}
		else {
			$success = false;
			$banners = [];
			$date = date("Y-m-d");
			$isSubs = false;
			if(count($banners) > 0 ){
				foreach($banners as $value) {
					if(!empty($value->image)) { 
						$image_url = url("/")."/public/adBannerFiles/".$value->image;
						$value['image'] = $image_url;
					}
					else{
						$value['image'] = null;
					}
				}
				$success = true;
			}
			$page = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug']])->where("lng",$user_array['lng'])->first();
			$page1 = DB::table('pages')->where(["status"=>1,'slug'=>$user_array['slug2']])->where("lng",$user_array['lng'])->first();
			$dashpop = null;
			$pages = [$page,$page1];
			$walletDetails = UserDetails::select('wallet_amount','referral_code')->where(['user_id'=>$user_array['user_id']])->first();
			$unIds = UserNotifications::select(['id'])->where('delete_status',1)->pluck('id')->sort();
			$snIds = NotificationUserId::select(['notification_id'])->where('user_id',$user_array['user_id'])->pluck('notification_id')->sort();
			$readAllN = 0;
			if(count($unIds) == count($snIds)) {
				$readAllN = 1;
			}
			$settingData = Settings::whereIn('key',["ads_enable","is_wmh_pop_show","is_enable_maintenance","maintenance_hi","maintenance_eng","is_mh_enable","refer_text_app","is_video_app_show","vdo_name","is_chat_enable"])->pluck("value","key");
			
			if($settingData["ads_enable"] == "on") {
			if($user_array['lng'] == "hi"){
				$banners = AdMaster::where('status','1')->where("type",2)->whereRaw('date(expiry_date) >= ?', [$date])->orderBy("id","DESC")->get();
			}
			else{
				$banners = AdMaster::where('status','1')->where("type",1)->whereRaw('date(expiry_date) >= ?', [$date])->orderBy("id","DESC")->get();
			}}
			$is_wmh_pop_show = $settingData["is_wmh_pop_show"];
			$is_chat_enable = $settingData["is_chat_enable"];
			$ref = ReferralCashback::select("id")->where("referred_id",$user_array['user_id'])->count();
			if($ref > 0) {
				$is_wmh_pop_show = 0;
			}
			if($is_wmh_pop_show == 1) {
				$datee = date('Y-m-d', strtotime('-3 days'));
				$user = User::select('id')->where("id",$user_array['user_id'])->where("created_at",'>=',$datee)->first();
				if(!empty($user)) {
					$is_wmh_pop_show = 1;
				}
				else{
					$is_wmh_pop_show = 0;
				}
				if(!empty($user) && checkActiveSubs($user->id) > 0) {
					$is_wmh_pop_show = 0;
				}
			}
			$isVdoReq = null;
			if(!empty($user_array['p_id'])) {
				$vdoReq = NotifyUserSms::where(['patient_id'=>$user_array['p_id']])->where('status','0')->whereDate('created_at',date('Y-m-d'))->first();
				if(!empty($vdoReq)) {
					$isVdoReq = $vdoReq;
				}
			}
			$is_enable_maintenance = $settingData["is_enable_maintenance"];
			$mntTxt = '';
			if($is_enable_maintenance){
				if($user_array['lng'] == "hi"){
					$mntTxt = $settingData["maintenance_hi"];
				}
				else{
					$mntTxt = $settingData["maintenance_eng"];
				}
			}
			$is_mh_enable = $settingData["is_mh_enable"];
			$refText = $settingData["refer_text_app"];
			$subscription_data = null;
			$result = UsersSubscriptions::with("UserSubscribedPlans","PlanPeriods")->where('user_id',$user_array['user_id'])->where('order_status','1')->orderBy("created_at","DESC")->first();
		
			if(!empty($result) && $result->UserSubscribedPlans->isNotEmpty()) {
				  foreach($result->UserSubscribedPlans as $plan) {
					if(isset($plan['discount_price']) && $plan['discount_price'] == 1401) {
						$plan['discount_price'] = 1001;
					}
				  }
				// if(!empty($result->PlanPeriods) && $result->PlanPeriods->status == 1) {
				// $isSubs = true;
				// $subscription_data = $result;
				// $expiry_date = $result->PlanPeriods->end_trail;
				// $current_date = date("Y-m-d");
				// $reminder_date = date("Y-m-d", strtotime($expiry_date." -30 days"));
				// if($current_date >= $reminder_date && $current_date <= $expiry_date) {
					// $mntTxt = "⏳ Your Health Plan is Expiring Soon! Don’t let it slip — renew for just ₹1 per day (₹365/year). Use code: HGRENEW for an exclusive discount! Act fast, limited time only! ⏰ 👇 Renew Now 👇";
				// }
				// }
				// else if(!empty($result->PlanPeriods) && $result->PlanPeriods->status == 0) {
					// $expiry_date = $result->PlanPeriods->end_trial;
					// $current_date = date("Y-m-d");
					// if($current_date >= $expiry_date) {
						// $mntTxt = "⏳ Oops! Your Health Plan Expired! Renew now for just ₹1/day (₹365/year). Use code: HGRENEW to unlock your discount! Hurry, limited time only! ⏰ 👇 Renew Now 👇";
					// }	
				// }
			}
				
			
			$vhtOrder = VhtOrder::where("user_id",$user_array['user_id'])->whereNotNull("vh_meta_data")->where("delete_status",0)->orderBy("created_at","DESC")->first();
			$is_test_done = 0;
			if(!empty($vhtOrder) && $vhtOrder->is_view == 1) {
				$is_test_done = 1;
			}
			saveUserActivity($request, 'getAds', 'users', $user_array['user_id']);
			$response = ["ads"=>$banners,"banner"=>$pages,"walletDetails"=>$walletDetails,"notificationIcon"=>$readAllN,"wmh_popup"=>$is_wmh_pop_show,"dashboard_popup"=>$dashpop,"is_vdo_req"=>$isVdoReq,"refer_text_app"=>$refText,'is_mh_enable'=>$is_mh_enable,'mntTxt'=>$mntTxt,'is_subscribed'=>$isSubs,"subscription_data"=>$result,"reminder_date"=>@$reminder_date,"is_chat_enable"=>$is_chat_enable,"is_test_done"=>$is_test_done];
			return $this->sendResponse($response, '',true);
		}
    }
	public function getTotalSteps(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['date'] = $data->get('date');
			$user_array['type'] = $data->get('type');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = false;
				if($user_array['type'] == 'todayCnt'){
					$success = true;
					$date = date('Y-m-d',strtotime($user_array['date']));
					// $date = '2019-07-16';
					$cnt = StepsDetails::select(["step_count","calories"])->where(['user_id'=>$user_array['user_id'],'status'=>1])->where("date",$date)->selectRaw('sum(calories) as tot_cal, sum(step_count) as total_step')->first();
					$health_trackers["total_step"] = $cnt->total_step;
					$health_trackers["total_calories"] = $cnt->tot_cal;
					return $this->sendResponse($health_trackers, '',$success);

				}
				else{
					$date = date('Y-m-d',strtotime($user_array['date']));
					$query = StepsDetails::where(['user_id'=>$user_array['user_id'],'status'=>1])->where("date",$date)->orderBy("date","DESC")->get();
					if(count($query)>0){
					    $success = true;
					}
					$health_trackers= $query;
					return $this->sendResponse($health_trackers, '',$success);
				}
				
			}
		}
	}
	
	public function updateMedicineDetails(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$user_array['user_id'] = $data->get('user_id');
			$user_array['med_name'] = $data->get('med_name');
			$user_array['start'] = $data->get('start');
			$user_array['end'] = $data->get('end');
			$user_array['days'] = $data->get('days');
			$user_array['time'] = $data->get('time');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				if(!empty($user_array['id'])) {
					MedicineDetails::where('id',$user_array['id'])->update(array(
					  'med_name' => $user_array['med_name'],
					  'start' => $user_array['start'],
					  'end' => $user_array['end'],
					  'days' => $user_array['days'],
					  'time' => $user_array['time'],
					));
				}
				else{
					MedicineDetails::create([
					  'user_id' => $user_array['user_id'],
					  'med_name' => $user_array['med_name'],
					  'start' => $user_array['start'],
					  'end' => $user_array['end'],
					  'days' => $user_array['days'],
					  'time' => $user_array['time'],
					]);
				}
				return $this->sendResponse('', 'Medicine Reminder Generated Successfully.',true);
			}
		}
	}
	
	public function getMedicineReminderList(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = false;
				//$date = date('Y-m-d',strtotime($user_array['date']));
				$query = MedicineDetails::where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","DESC")->get();
				if(count($query)>0){
					$success = true;
				}
				$medicine= $query;
				return $this->sendResponse($medicine, '',$success);	
			}
		}
	}
	
	public function deleteMedicineReminder(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$validator = Validator::make($user_array, [
				'id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = true;
				$query = MedicineDetails::where(['id'=>$user_array['id']])->delete();
				return $this->sendResponse($query, 'Medicine Reminder Deleted Successfully.',$success);	
			}
		}
	}
	
	public function updateBpRecordDetails(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json()->all();  
			$user_array=array();
			$user_array['id'] = @$data['id'];
			$user_array['user_id'] = @$data['user_id'];
			$user_array['order_id'] = @$data['order_id'];
			$user_array['bp_systolic'] = @$data['bp_systolic'];
			$user_array['bp_diastolic'] = @$data['bp_diastolic'];
			$user_array['pulse_rate'] = @$data['pulse_rate'];
			$user_array['weight'] = @$data['weight'];
			$user_array['notes'] = @$data['notes'];
			$user_array['date'] = @$data['date'];
			$user_array['time'] = @$data['time'];
			$user_array['ques_meta'] = @$data['ques_meta'];
			$user_array['result'] = @$data['result'];
			$user_array['result_note'] = @$data['result_note'];
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$ques_meta = null;
				if(!empty($user_array['id'])) {
					if(!empty($user_array['ques_meta'])) {
					    $ques_meta = json_encode($user_array['ques_meta']);
						ManageBpRecords::where('id',$user_array['id'])->update(array(
							'ques_meta' => $ques_meta,
							'result' => $user_array['result'],
							'order_id' => $user_array['order_id'],
							'result_note' => $user_array['result_note']
						));
						$id = $user_array['id'];
				    }
					else{
				    	ManageBpRecords::where('id',$user_array['id'])->update(array(
						  'bp_systolic' => $user_array['bp_systolic'],
						  'bp_diastolic' => $user_array['bp_diastolic'],
						  'pulse_rate' => $user_array['pulse_rate'],
						  'weight' => $user_array['weight'],
						  'date' => $user_array['date'],
						  'time' => $user_array['time'],
						  'notes' => $user_array['notes'],
						));
						$id = $user_array['id'];
				    }
				}
				else{
					$bpRecord = ManageBpRecords::create([
					  'user_id' => $user_array['user_id'],
					  'bp_systolic' => $user_array['bp_systolic'],
					  'bp_diastolic' => $user_array['bp_diastolic'],
					  'pulse_rate' => $user_array['pulse_rate'],
					  'weight' => $user_array['weight'],
					  'date' => $user_array['date'],
					  'time' => $user_array['time'],
					  'notes' => $user_array['notes'],
					]);
					$id = $bpRecord->id;
				}
				return $this->sendResponse($id, 'Bp Record Generated Successfully.',true);
			}
		}
	}
	
	public function deleteBpRecord(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$validator = Validator::make($user_array, [
				'id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = true;
				$query = ManageBpRecords::where(['id'=>$user_array['id']])->delete();
				return $this->sendResponse($query, 'Bp Record Deleted Successfully.',$success);	
			}
		}
	}
	
	public function bpRecordList(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = false;
				//$date = date('Y-m-d',strtotime($user_array['date']));
				$query = ManageBpRecords::with('Speciality')->where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","DESC")->get();
				if(count($query)>0){
					$success = true;
				}
				$lang = 1;
				if($user_array['lng'] == 'hi'){
					$lang = 2;
				}
				$myArr= [];
				if($success) {
					$arrDisplay = [];
					foreach ($query as $row ){
						if(!empty($row->Speciality) && !empty($row->Speciality->speciality_icon)){
							$row->Speciality['icon'] = url("/")."/public/speciality-icon/".@$row->Speciality->speciality_icon;
						}
						$final_result_en = null;
						$final_result_hi = null;
						if(checkTextHindi($row->result_note)) {
							if(!empty($row->getHinQuestion) && !empty($row->getHinQuestion->meta_data)){
								$res = $this->getFinalResult($row->getHinQuestion->meta_data,$row->result_note);
								$final_result_hi = $res['result'];
								if(!empty($row->getEnQuestion) && $row->getEnQuestion->meta_data){
									$meta_data = json_decode($row->getEnQuestion->meta_data,true);
									$final_result_en = (isset($meta_data[$res['key']]))?$meta_data[$res['key']]['note']:null;
								}
							}
						}
						else{
							if(!empty($row->getEnQuestion) && !empty($row->getEnQuestion->meta_data)){
								$res = $this->getFinalResult($row->getEnQuestion->meta_data,$row->result_note);
								$final_result_en = $res['result'];
								if(!empty($row->getHinQuestion) && $row->getHinQuestion->meta_data){
									$meta_data = json_decode($row->getHinQuestion->meta_data,true);
									$final_result_hi = (isset($meta_data[$res['key']]))?$meta_data[$res['key']]['note']:null;
								}
							}
						}
						if($lang == 1){
							$row['final_result'] = $final_result_en;
						}
						else{
							$row['final_result'] = $final_result_hi;
						}
						$m = date('M Y',strtotime($row->date));
						$arrDisplay[$m][] = $row;
					}
					foreach ($arrDisplay as $key => $value) {
						$myArr[] = array('date'=>$key,'value'=> $value);
					}
				}
				return $this->sendResponse($myArr, '',$success);	
			}
		}
	}
	
	
	public function updateDiabetesRecordDetails(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json()->all();  
			$user_array=array();
			$user_array['id'] = @$data['id'];
			$user_array['user_id'] = @$data['user_id'];
			$user_array['order_id'] = @$data['order_id'];
			$user_array['sugar_level'] = @$data['sugar_level'];
			$user_array['test_id'] = @$data['test_id'];
			$user_array['date'] = @$data['date'];
			$user_array['time'] = @$data['time'];
			$user_array['notes'] = @$data['notes'];
			$user_array['ques_meta'] = @$data['ques_meta'];
			$user_array['result'] = @$data['result'];
			$user_array['result_note'] = @$data['result_note'];
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				if(!empty($user_array['id'])) {
					if(!empty($user_array['ques_meta'])){
					    $ques_meta = json_encode($user_array['ques_meta']);
						ManageDiabetesRecords::where('id',$user_array['id'])->update(array(
						  'ques_meta' => $ques_meta,
						  'order_id' => $user_array['order_id'],
						  'result' => $user_array['result'],
						  'result_note' => $user_array['result_note']
						));
						$id = $user_array['id'];
				    }else{
						ManageDiabetesRecords::where('id',$user_array['id'])->update(array(
						'sugar_level' => $user_array['sugar_level'],
						'test_id' => $user_array['test_id'],
						'date' => $user_array['date'],
						'time' => $user_array['time'],
						'notes' => $user_array['notes'],
						));
						$id = $user_array['id'];
				    }
				}
				else{
					$diaData = ManageDiabetesRecords::create([
					  'user_id' => $user_array['user_id'],
					  'sugar_level' => $user_array['sugar_level'],
					  'test_id' => $user_array['test_id'],
					  'date' => $user_array['date'],
					  'time' => $user_array['time'],
					  'notes' => $user_array['notes']
					]);
					$id = $diaData->id;
				}
				return $this->sendResponse($id, 'Diabetes Record Generated Successfully.',true);
			}
		}
	}
	
	
	public function deleteDiabetesRecord(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$validator = Validator::make($user_array, [
				'id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = true;
				$query = ManageDiabetesRecords::where(['id'=>$user_array['id']])->delete();
				return $this->sendResponse($query, 'Diabetes Record Deleted Successfully.',$success);	
			}
		}
	}
	
	public function diabetesRecordList(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = false;
				$query = ManageDiabetesRecords::with('Speciality')->where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","Desc")->get();
				if(count($query)>0){
					$success = true;
				}
				$lang = 1;
				if($user_array['lng'] == 'hi'){
					$lang = 2;
				}
				$myArr= [];
				if($success) {
					$arrDisplay = [];
					foreach ($query as $row ){
						if(!empty($row->Speciality) && !empty($row->Speciality->speciality_icon)){
							$row->Speciality['icon'] = url("/")."/public/speciality-icon/".@$row->Speciality->speciality_icon;
						}
						$final_result_en = null;
						$final_result_hi = null;
						if(checkTextHindi($row->result_note)) {
							if(!empty($row->getHinQuestion) && !empty($row->getHinQuestion->meta_data)){
								$res = $this->getFinalResult($row->getHinQuestion->meta_data,$row->result_note);
								$final_result_hi = $res['result'];
								if(!empty($row->getEnQuestion) && $row->getEnQuestion->meta_data){
									$meta_data = json_decode($row->getEnQuestion->meta_data,true);
									$final_result_en = (isset($meta_data[$res['key']]))?$meta_data[$res['key']]['note']:null;
								}
							}
						}
						else{
							if(!empty($row->getEnQuestion) && !empty($row->getEnQuestion->meta_data)){
								$res = $this->getFinalResult($row->getEnQuestion->meta_data,$row->result_note);
								$final_result_en = $res['result'];
								if(!empty($row->getHinQuestion) && $row->getHinQuestion->meta_data){
									$meta_data = json_decode($row->getHinQuestion->meta_data,true);
									$final_result_hi = (isset($meta_data[$res['key']]))?$meta_data[$res['key']]['note']:null;
								}
							}
						}
						if($lang == 1){
							$row['final_result'] = $final_result_en;
						}
						else{
							$row['final_result'] = $final_result_hi;
						}
						$m = date('M Y',strtotime($row->date));
						$arrDisplay[$m][] = $row;
					}
					foreach ($arrDisplay as $key => $value) {
						$myArr[] = array('date'=>$key,'value'=> $value);
					}
				}
				return $this->sendResponse($myArr, '',$success);	
			}
		}
	}
	public function saveSearchResults(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['result'] = $data->get('result');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$results = SearchResults::where(["user_id"=>$user_array['user_id'],"type"=>1])->first();
				if(!empty($results)) {
					$result_data = $results->result.",".$user_array['result'];
					SearchResults::where('id',$results->id)->update(array(
					  'result' => $result_data,
					));
				}
				else{
					SearchResults::create([
					  'user_id' => $user_array['user_id'],
					  'result' => $user_array['result'],
					  "type"=>1,
					]);
				}
				return $this->sendResponse('', 'Result save successfully.',true);
			}
		}
	}
	
	public function usersBuymedicineHits(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				MedicineHits::create([
				  'user_id' => $user_array['user_id']
				]);
				return $this->sendResponse('', 'Hits save successfully.',true);
			}
		}
	}
	
	public function usersAdsHits(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['ads_id'] = $data->get('ads_id');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
				'ads_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				AdsHits::create([
				  'user_id' => $user_array['user_id'],
				  'ads_id' => $user_array['ads_id'],
				]);
				return $this->sendResponse('', 'Hits save successfully.',true);
			}
		}
	}
	
	public function getLocalitiesByCity(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['city_id'] = $data->get('city_id');
			
			$validator = Validator::make($user_array, [
				'city_id'   => 'max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$city_id = $user_array['city_id'];
				$cities = CityLocalities::with("City")->select(['id','name','city_id'])->where('city_id',$city_id)->orderBy('top_status','DESC')->limit(12)->get();
				$success = false;
				if(count($cities) > 0) {
					$success = true;
				}
				return $this->sendResponse($cities, '',$success);
			}
		}
	}
	
	public function getLocalitiesbySearch(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['city_id'] = $data->get('city_id');
			$user_array['city_name'] = $data->get('locality');
			
			$validator = Validator::make($user_array, [
				'city_name'   => 'max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$arr = [];
				$city_name = $user_array['city_name'];
				$cities = [];
				if(!empty($city_name)){
					$cities = City::with("State")->select(['id','name','state_id'])->Where('name', 'like', '%'.$city_name.'%')->limit(10)->get();
				}
				$query = CityLocalities::with("City")->select(['id','name','city_id'])->where('status',1);
				if(!empty($user_array['city_id']) && $user_array['city_id'] != "0" ) {
					$query->Where('city_id',$user_array['city_id']);
				}
				if(!empty($city_name)) {
					$query->Where('name', 'like', '%'.$city_name.'%');
				}
				$localities = $query->orderBy('top_status','DESC')->limit(12)->get();
				$success = false;
				
				if(count($cities) > 0 || count($localities) > 0) {
					$success = true;
				}
				$arr['city'] = $cities;
				$arr['locality'] = $localities;
				return $this->sendResponse($arr, '',$success);
			}
		}
	}
	
	public function getcityIdByLocality(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['city'] = $data->get('city');
			$user_array['state_name'] = $data->get('state_name');
			$user_array['locality'] = $data->get('locality');
			
			$validator = Validator::make($user_array, [
				'city'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$ids = []; $success = false;
				$ids['city_id'] = null;
				$ids['locality_id'] = null;
				$city = $user_array['city'];
				$state_name = $user_array['state_name'];
				$locality = $user_array['locality'];
				$qry = City::with("State")->select(['id','state_id'])->Where('name', 'like', $city);
						if(!empty($state_name)) {
							$qry->whereHas('State', function($q)  use ($state_name) {$q->Where(['name'=>$state_name]);});
						}
				$cities	=  $qry->first();
				
				if(!empty($cities)) {
					$ids['city_id'] = $cities->id;
					$success = true;
				}
				if(!empty($locality)) {
					$query = CityLocalities::select(['id'])->Where('name', 'like', $locality);
					if($ids['city_id'] != null){
						$query->Where('city_id',$ids['city_id']);
					}
					$localities = $query->first();
					if(!empty($localities)) {
						$ids['locality_id'] = $localities->id;
						$success = true;
					}
				}
				return $this->sendResponse($ids, '',$success);
			}
		}
	}
	
	    public function support(Request $request) {
        if($request->isMethod('post')) {
            $data = Input::json();
            $user_array=array();
            $user_array['user_id'] = $data->get('user_id');
            $user_array['name'] = $data->get('name');
            $user_array['email'] = $data->get('email');
            $user_array['message'] = $data->get('message');
            $user_array['subject'] = $data->get('subject');
            $user_array['lng'] = $data->get('lng');

          $validator = Validator::make($user_array, [
            'user_id'   =>  'required|max:50',
            //'name'      =>  'required',
            //'email'     =>  'required',
            'message'   =>  'required',
            //'subject'   =>  'required',
          ]);
          if($validator->fails()){
            return $this->sendError('Enter required details');
          }
          else{
			  $user = User::where('id',$user_array['user_id'])->first();
			  $name = @$user->first_name." ".@$user->last_name;
              $tocken_no = strtoupper(substr($name,0,2).substr($user_array['message'],0,2)).rand(00000,99999).time();
				
			   $support =  Support::create([
                'user_id' => $user_array['user_id'],
                'tocken' => $tocken_no,
                'name' => $name,
                'email' => $user->email,
                'message' => $user_array['message'],
                // 'subject' => $user_array['subject'],
                'type' => 0,
              ]);
				if(!empty($support->email)){
				  $email = ($support->email!="")?$support->email:"";
				  $name = ($support->name!="")?ucfirst($support->name):"Guest(".$support->email.")";
				  $sub = ($support->subject!="")?$support->subject:"";
				  $msg = ($support->message!="")?$support->message:"";
				  $EmailTemplate = EmailTemplate::where('slug','support')->first();
				   if($EmailTemplate)  {
					  $to = ($email!="")?$email:"noreply@healthgennie.com";
					  $body = $EmailTemplate->description;
					  $mailMessage = str_replace(array('{{name}}','{{email}}','{{sub}}','{{msg}}'),
					  array($name,$email,$sub,$msg),$body);
					  $datas = array('to' =>"info@healthgennie.com",'from' => $to,'mailTitle'=>$EmailTemplate->title,'practiceData'=>'','content'=>$mailMessage,'subject'=>$EmailTemplate->subject);
					  try{
					  Mail::send( 'emails.all', $datas, function( $message ) use ($datas){
						  $message->to( $datas['to'] )->from( $datas['from'])->subject($datas['subject']);
					  });
					  }
					  catch(\Exception $e)
					  {
						 // Never reached
					  }	
					  }
			    }
				if($user_array['lng'] == "hi") {
					return $this->sendResponse('','आपका अनुरोध सफलतापूर्वक दर्ज हो गया है।',true);	
				}
				else{
					return $this->sendResponse('','Your request successfully submitted.',true);
				}
         }
      }
    }
	
	public function getComplimentsData(Request $request) {
		$data = ComplimentsMaster::orderBy("id","ASC")->get();
		return $this->sendResponse($data, '',$success = true);
    }
	
	public function getWaitingTimeData(Request $request) {
		$data = WaitingTimeMaster::orderBy("id","ASC")->get();
		return $this->sendResponse($data, '',$success = true);
    }
	
	public function getOrganizations(Request $request){
		$OrganizationList = OrganizationMaster::select('id','title')->where('is_app_show',1)->where("delete_status", 1)->orderBy('id', 'desc')->get();
		return $this->sendResponse($OrganizationList, '',true);
	}
	
	public function getReferLinkMsg(Request $request){
		$res = [];
		$res["download_link"] = getSetting("refer_link")[0];
		$res["refer_msg"] = getSetting("refer_msg")[0];
		$res["refer_subject"] = getSetting("refer_subject")[0];
		return $this->sendResponse($res, '',true);
	}
	
	public function getBlogCount(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['id'] = $data->get('id');
			
			$validator = Validator::make($user_array, [
				'id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = true;
				$blog = NewsFeeds::where(['id'=>$user_array['id']])->first();
				$ttl = $blog->blog_count + 1;
				NewsFeeds::where('id', $user_array['id'])->update(array(
					'blog_count' => $ttl
				));
				return $this->sendResponse($ttl, '',$success);
			}
		}
	}
	
	public function getStaticPage(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();
			$user_array=array();
			$user_array['slug'] = $data->get('slug');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'lng'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = true;
				$blog = Pages::where(['slug'=>$user_array['slug']])->where("lng",$user_array['lng'])->first();
				return $this->sendResponse($blog, '',$success);
			}
		}
	}
	
	
	public function appointmentCheckoutDetails(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['doc_id'] = $data->get('doc_id');
			$user_array['onCallStatus'] = $data->get('onCallStatus');
			$user_array['is_subscribed'] = $data->get('is_subscribed');
			$user_array['lng'] = $data->get('lng');
			$user_array['is_peak'] = $data->get('is_peak');
			$user_array['user_id'] = $data->get('user_id');
			$user_array['is_pscyco'] = $data->get('is_pscyco');
			
			$validator = Validator::make($user_array, [
				'doc_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$res = [];
				$terms = "";
				$success = true;
				$doctor = Doctors::select(["oncall_fee","consultation_fees","consultation_discount","convenience_fee"])->where(['id'=>$user_array['doc_id']])->first();
				$consultation_fees = 0;
				if($user_array['is_subscribed']==1 || $user_array['onCallStatus']==1) {
					if($user_array['is_subscribed']==1){
						$terms = getTermsBySLug("terms-conditions-elite-appointment",$user_array['lng']);
					}
					else{
						$terms = getTermsBySLug("terms-conditions-tele-appointment",$user_array['lng']);
					}
					if(!empty($doctor)){
						$consultation_fees = $doctor->oncall_fee;
						if(!empty($doctor->convenience_fee)){
							$charge = $doctor->convenience_fee;
						}
						else{
							$charge = getSetting("service_charge_rupee")[0];
						}
					}
					else if($user_array['doc_id'] == 0){
						$consultation_fees = getSetting("direct_tele_appt_fee")[0];
						$charge = 0;
					}
				}
				elseif($user_array['onCallStatus']==2) {
					$consultation_fees = $doctor->consultation_fees;
					$terms = getTermsBySLug("term-conditions-appointment",$user_array['lng']);
					if(!empty($doctor->convenience_fee)){
						$charge = $doctor->convenience_fee;
					}
					else{
						$charge = getSetting("inclinic-service-charge")[0];
					}
				}
				if($user_array['is_peak'] == "1"){
					if(!empty($doctor->convenience_fee)){
						$charge = $doctor->convenience_fee;
					}
					else{
						$charge = getSetting("service_charge_rupee")[0];
					}
					$consultation_fees = getSetting("peak_hour_price")[0];
				}
				if($user_array['is_pscyco'] == "1") {
					$consultation_fees = 1000;
				}
				$res["convFee"] = $charge;
				$res["oncall_fee"] = $consultation_fees;
				$res["terms"] = $terms;
				$amount = @UserDetails::select('wallet_amount')->where(['user_id'=>$user_array['user_id']])->first()->wallet_amount;
				$res["wallet_amount"] = $amount;
				$res["avail_limit"] = getSetting("reward_appt_avail_limit")[0];
				return $this->sendResponse($res, '',$success);
			}
		}
	}
	public function getPatients(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['mobile_no'] = $data->get('mobile_no');
			$user_array['isDirectAppt'] = $data->get('isDirectAppt');
			
			$validator = Validator::make($user_array, [
				'mobile_no'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = true;
				// $user = User::where(['mobile_no'=>$user_array['mobile_no']])->where("parent_id",0)->first();
				// if(!empty($user)){
					// checkPatientCount($user->id);
				// }
				$max_patient_count = 0;
				$users = User::where(['mobile_no'=>$user_array['mobile_no']])->orderBy("parent_id")->get();
				$k = 0;
				if(count($users)>0){
					foreach($users as $i=> $user){
						$user["dob_type"] = 0;
						if(!empty($user->dob)) {
							$user["dob_type"] = get_patient_age_api($user->dob)[1];
							$user["dob"] = get_patient_age_api($user->dob)[0];
						}
						if($user->parent_id == 0) {
							$max_patient_count = checkPatientCount($user->id);
						}
						else{
							$k = $k+1;
						}
					}
				}
				$sts = "yes";
				if($user_array['isDirectAppt'] == 1) {
					// $sts = "yes";
					if($max_patient_count > 0 && $max_patient_count <= $k){
						$sts = "no";
					}
				}
				return $this->sendResponse($users, $sts,$success);
			}
		}
	}
	
	public function getTopSpecialities(Request $request) {
		$success = true;
		$ids = [1,49,5,7,131,60,20,25];
		$ids_ordered = implode(',', $ids);
		$specialities = Speciality::whereIn('id',$ids)->orderByRaw(DB::raw("FIELD(id, $ids_ordered)"))->get();
		if(count($specialities) > 0){
			foreach($specialities as $spa){
				if(!empty($spa->speciality_icon)) {
					$spa->speciality_icon = url("/")."/public/speciality-icon/".$spa->speciality_icon;
				}
				else{
					$spa->speciality_icon = null;
				}
			}
		}
		return $this->sendResponse($specialities, '',$success);
	}
	
	public function updateWeightDetails(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json()->all();  
			$user_array=array();
			$user_array['id'] = @$data['id'];
			$user_array['user_id'] = @$data['user_id'];
			$user_array['order_id'] = @$data['order_id'];
			$user_array['date'] = @$data['date'];
			$user_array['time'] = @$data['time'];
			$user_array['weight'] = @$data['weight'];
			$user_array['height'] = @$data['height'];
			$user_array['bmi'] = @$data['bmi'];
			$user_array['ques_meta'] = @$data['ques_meta'];
			$user_array['result'] = @$data['result'];
			$user_array['result_note'] = @$data['result_note'];
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$ques_meta = null;
				if(!empty($user_array['id'])) {
					if(!empty($user_array['ques_meta'])){
					    $ques_meta = json_encode($user_array['ques_meta']);
						ManageWeightRecords::where('id',$user_array['id'])->update(array(
						'ques_meta' => $ques_meta,
						'result' => $user_array['result'],
						'order_id' => $user_array['order_id'],
					    'result_note' => $user_array['result_note']
						));
						$id = $user_array['id'];
				    }
					else{
				    	ManageWeightRecords::where('id',$user_array['id'])->update(array(
						  'date' => $user_array['date'],
						  'ques_meta' => null,
						  'result' => null,
						  'result_note' => null,
						  'time' => $user_array['time'],
						  'weight' => $user_array['weight'],
						  'height' => $user_array['height'],
						  'bmi' => $user_array['bmi'],
						));
						$id = $user_array['id'];
				    }
				}
				else{
					$weightData = ManageWeightRecords::create([
					  'user_id' => $user_array['user_id'],
					  'date' => $user_array['date'],
					  'time' => $user_array['time'],
					  'weight' => $user_array['weight'],
					  'height' => $user_array['height'],
					  'bmi' => $user_array['bmi'],
					  'ques_meta' => $ques_meta,
					]);
					$id = $weightData->id;
				}
				return $this->sendResponse($id, 'Weight Record Generated Successfully.',true);
			}
		}
	}
	
	
	public function deleteWeightRecord(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$validator = Validator::make($user_array, [
				'id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = true;
				$query = ManageWeightRecords::where(['id'=>$user_array['id']])->delete();
				return $this->sendResponse($query, 'Weight Record Deleted Successfully.',$success);	
			}
		}
	}
	
	public function weightList(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = false;
				$query = ManageWeightRecords::with('Speciality')->where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","Desc")->get();
				if(count($query)>0){
					$success = true;
				}
				$lang = 1;
				if($user_array['lng'] == 'hi'){
					$lang = 2;
				}
				$myArr= [];
				if($success) {
					$arrDisplay = [];
					foreach($query as $row ){
						if(!empty($row->Speciality) && !empty($row->Speciality->speciality_icon)){
							$row->Speciality['icon'] = url("/")."/public/speciality-icon/".@$row->Speciality->speciality_icon;
						}
						$final_result_en = null;
						$final_result_hi = null;
						if(checkTextHindi($row->result_note)) {
							if(!empty($row->getHinQuestion) && !empty($row->getHinQuestion->meta_data)){
								$res = $this->getFinalResult($row->getHinQuestion->meta_data,$row->result_note);
								$final_result_hi = $res['result'];
								if(!empty($row->getEnQuestion) && $row->getEnQuestion->meta_data){
									$meta_data = json_decode($row->getEnQuestion->meta_data,true);
									$final_result_en = (isset($meta_data[$res['key']]))?$meta_data[$res['key']]['note']:null;
								}
							}
						}
						else{
							if(!empty($row->getEnQuestion) && !empty($row->getEnQuestion->meta_data)){
								$res = $this->getFinalResult($row->getEnQuestion->meta_data,$row->result_note);
								$final_result_en = $res['result'];
								if(!empty($row->getHinQuestion) && $row->getHinQuestion->meta_data){
									$meta_data = json_decode($row->getHinQuestion->meta_data,true);
									$final_result_hi = (isset($meta_data[$res['key']]))?$meta_data[$res['key']]['note']:null;
								}
							}
						}
						if($lang == 1){
							$row['final_result'] = $final_result_en;
						}
						else{
							$row['final_result'] = $final_result_hi;
						}
						$m = date('M Y',strtotime($row->date));
						$arrDisplay[$m][] = $row;
					}
					foreach($arrDisplay as $key => $value) {
						$myArr[] = array('date'=>$key,'value'=> $value);
					}
				}
				return $this->sendResponse($myArr, '',$success);	
			}
		}
	}
	
	public function loginUserByPaytmData(Request $request) {
		if ($request->isMethod('post')) {
			$ress = null; $response = null; $user = null; $success = 0;
			$code = $request->code;
			$paytmParams = array();
			$paytmParams["grant_type"] = "authorization_code";
			$paytmParams["scope"] = "basic";
			$paytmParams["code"] = $code; //"10adff55-cfc7-47de-b914-a0d0b3a85200";
			$paytmParams["client_id"] = "merchant-health-gennie-uat";
			
			$post_data = http_build_query($paytmParams)."</br>";
			$auth = "Basic " . base64_encode("merchant-health-gennie-uat". ":" ."U4vIuakMdShfetKMX0DADFnCrKwCII5d");

			$url = "https://accounts-uat.paytm.com/oauth2/v2/token";

			// $url = "https://accounts.paytm.com/oauth2/v2/token";
			
			
			$ch = curl_init();
			curl_setopt_array($ch, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_CUSTOMREQUEST => "POST",
			CURLOPT_POSTFIELDS => $post_data,
			CURLOPT_HTTPHEADER => array(
			"content-type: application/x-www-form-urlencoded", "Authorization: " . $auth
			),
			));
			$response = curl_exec($ch);
			$err = curl_error($ch);
			curl_close($ch);
			$response = json_decode($response,true);
			if(isset($response['access_token']) && !empty($response['access_token'])){
				$access_token = $response['access_token'];
				//$auth = "Basic " . base64_encode("merchant-health-gennie-uat". ":" ."U4vIuakMdShfetKMX0DADFnCrKwCII5d");
				$url = "https://accounts-uat.paytm.com/v2/user?fetch_strategy=profile_info,phone_number,email";
				// $url = "https://accounts.paytm.com/oauth2/v2/token";
				//$chl = curl_init();
				$chl = curl_init($url);
				curl_setopt($chl, CURLOPT_POST, false);
				curl_setopt($chl, CURLOPT_RETURNTRANSFER, true); 
				curl_setopt($chl, CURLOPT_HTTPHEADER, array("verification_type: oauth_token", "data: ".$access_token,"Authorization:".$auth)); 
				$ress = curl_exec($chl);
				// print_r($ress);
				$ress = json_decode($ress,true);
				$first_name = null; $last_name=null;
				if(isset($ress['profileInfo']['displayName']) && !empty($ress['profileInfo']['displayName'])){
					$first_name = trim(strtok($ress['profileInfo']['displayName'], ' '));
					$last_name = trim(strstr($ress['profileInfo']['displayName'], ' '));
				}
				if(isset($ress['phoneInfo']['phoneNumber']) && !empty($ress['phoneInfo']['phoneNumber'])){
					$user = User::where('mobile_no',$ress['phoneInfo']['phoneNumber'])->where('parent_id',0)->first();
					if(!empty($user)){
						User::where('id', $user->id)->update(array(
						  'first_name' => $first_name,
						  'last_name' => $last_name,
						  'email' =>  (isset($ress['email'])?$ress['email']:null),
						));
					}
					else{
						$user = User::create([
						   'mobile_no' =>  (isset($ress['phoneInfo']['phoneNumber'])?$ress['phoneInfo']['phoneNumber']:null),
						   'email' =>  (isset($ress['email'])?$ress['email']:null),
						   'first_name' =>  $first_name,
						   'last_name' =>  $last_name,
						   'parent_id' => 0,
						   'status' =>  1,
						   'device_type' =>  3,
						]);	
					}
					Auth::login($user);
					$success = 1;
				}
			}
			return ["parent_response"=>$response,'response'=>$ress,"user"=>$user,"success"=>$success];
		}
		return "get method not allowed..";
	}
	
	public function getCashcack(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$userCashBack = UserCashback::where(['user_id'=>$user_array['user_id']])->whereIn("paytm_status",["DE_001"])->orderBy("created_at","DESC")->get()->toArray();
				
				$referralCashback = ReferralCashback::where(['referred_id'=>$user_array['user_id']])->whereIn("paytm_status",["DE_001"])->orderBy("created_at","DESC")->get()->toArray();
				$response = @array_merge($userCashBack,$referralCashback);
				// $response['referralCashback'] = $referralCashback;
				// $response['userCashBack'] = $userCashBack;
				$success = true;
				return $this->sendResponse($response,'',$success);	
			}
		}
	}
	public function getweightListPdf(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$weight = ManageWeightRecords::where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","Desc")->get();
				
				if($user_array['lng'] == "hi"){
					$weightData = view('pages.pdfFiles.weightHinPDF',compact('weight'))->render();
				}
				else{
					$weightData = view('pages.pdfFiles.weightPDF',compact('weight'))->render();
				}
				$output = PDF::loadHTML($weightData)->output();
				file_put_contents(public_path()."/htmltopdfview.pdf", $output);
				$pdf_url = 	url("/")."/public/htmltopdfview.pdf?.".time();
				return $this->sendResponse($pdf_url,'',true);	
			}
		}
	}
	
	public function getDiabetesListPdf(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$lng = $user_array['lng'];
				$diabetes = ManageDiabetesRecords::where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","Desc")->get();
				if($user_array['lng'] == "hi"){
					$diabetesData = view('pages.pdfFiles.diabetesHinPDF',compact('diabetes','lng'))->render();
				}
				else{
					$diabetesData = view('pages.pdfFiles.diabetesPDF',compact('diabetes','lng'))->render();
				}
				$output = PDF::loadHTML($diabetesData)->output();
				file_put_contents(public_path()."/htmltopdfview.pdf", $output);
				$pdf_url = 	url("/")."/public/htmltopdfview.pdf?".time();
				return $this->sendResponse($pdf_url,'',true);	
			}
		}
	}
	
	public function getBpListPdf(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$bp = ManageBpRecords::where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","DESC")->get();
				if($user_array['lng'] == "hi"){
					$bpData = view('pages.pdfFiles.bpHinPDF',compact('bp'))->render();
				}
				else{
					$bpData = view('pages.pdfFiles.bpPDF',compact('bp'))->render();
				}
				$output = PDF::loadHTML($bpData)->output();
				file_put_contents(public_path()."/htmltopdfview.pdf", $output);
				$pdf_url = 	url("/")."/public/htmltopdfview.pdf?".time();
				return $this->sendResponse($pdf_url,'',true);	
			}
		}
	}
	
	public function getMedicineListPdf(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$medicine = MedicineDetails::where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","DESC")->get();
				if($user_array['lng'] == "hi"){
					$medicineData = view('pages.pdfFiles.medicineHinPDF',compact('medicine'))->render();
				}
				else{
					$medicineData = view('pages.pdfFiles.medicinePDF',compact('medicine'))->render();
				}
				$output = PDF::loadHTML($medicineData)->output();
				file_put_contents(public_path()."/htmltopdfview.pdf", $output);
				$pdf_url = 	url("/")."/public/htmltopdfview.pdf?".time();
				return $this->sendResponse($pdf_url,'',true);	
			}
		}
	}
	
	public function updateTempDetails(Request $request) {
		  if($request->isMethod('post')) {
			$data = Input::json()->all();  
			$user_array=array();
			$user_array['id'] = @$data['id'];
			$user_array['user_id'] = @$data['user_id'];
			$user_array['order_id'] = @$data['order_id'];
			$user_array['temp'] = @$data['temp'];
			$user_array['temp_type'] = @$data['temp_type'];
			$user_array['date'] = @$data['date'];
			$user_array['time'] = @$data['time'];
			$user_array['ques_meta'] = @$data['ques_meta'];
			$user_array['result'] = @$data['result'];
			$user_array['result_note'] = @$data['result_note'];
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50'
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$ques_meta = null;
				if(!empty($user_array['id'])) {
					if(!empty($user_array['ques_meta'])) {
					    $ques_meta = json_encode($user_array['ques_meta']);
						ManageTemperatureRecords::where('id',$user_array['id'])->update(array(
							'ques_meta' => $ques_meta,
							'order_id' => $user_array['order_id'],
							'result' => $user_array['result'],
							'result_note' => $user_array['result_note']
						));
						$id = $user_array['id'];
				    }
					else{
				    	ManageTemperatureRecords::where('id',$user_array['id'])->update(array(
						  'temp' => $user_array['temp'],
						  'temp_type' => $user_array['temp_type'],
						  'date' => $user_array['date'],
						  'time' => $user_array['time'],
						));
						$id = $user_array['id'];
				    }
				}
				else{
					$tempData = ManageTemperatureRecords::create([
					  'user_id' => $user_array['user_id'],
					  'temp_type' => $user_array['temp_type'],
					  'temp' => $user_array['temp'],
					  'date' => $user_array['date'],
					  'time' => $user_array['time'],
					]);
					$id = $tempData->id;
				}
				return $this->sendResponse($id, 'Temprature Record Generated Successfully.',true);
			}
		}
	}
	public function deleteTempRecord(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$validator = Validator::make($user_array, [
				'id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = true;
				$query = ManageTemperatureRecords::where(['id'=>$user_array['id']])->delete();
				return $this->sendResponse($query, 'Temprature Record Deleted Successfully.',$success);	
			}
		}
	}
	public function tempList(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$success = false;
				$query = ManageTemperatureRecords::with(['Speciality','getHinQuestion','getEnQuestion'])->where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","Desc")->get();
				if(count($query)>0){
					$success = true;
				}
				$lang = 1;
				if($user_array['lng'] == 'hi'){
					$lang = 2;
				}
				$myArr= [];
				if($success) {
					$arrDisplay = [];
					foreach ($query as $row ){
						if(!empty($row->Speciality) && !empty($row->Speciality->speciality_icon)){
							$row->Speciality['icon'] = url("/")."/public/speciality-icon/".@$row->Speciality->speciality_icon;
						}
						$final_result_en = null;
						$final_result_hi = null;
						if(checkTextHindi($row->result_note)) {
							if(!empty($row->getHinQuestion) && !empty($row->getHinQuestion->meta_data)){
								$res = $this->getFinalResult($row->getHinQuestion->meta_data,$row->result_note);
								$final_result_hi = $res['result'];
								if(!empty($row->getEnQuestion) && $row->getEnQuestion->meta_data){
									$meta_data = json_decode($row->getEnQuestion->meta_data,true);
									$final_result_en = (isset($meta_data[$res['key']]))?$meta_data[$res['key']]['note']:null;
								}
							}
						}
						else{
							if(!empty($row->getEnQuestion) && !empty($row->getEnQuestion->meta_data)){
								$res = $this->getFinalResult($row->getEnQuestion->meta_data,$row->result_note);
								$final_result_en = $res['result'];
								if(!empty($row->getHinQuestion) && $row->getHinQuestion->meta_data){
									$meta_data = json_decode($row->getHinQuestion->meta_data,true);
									$final_result_hi = (isset($meta_data[$res['key']]))?$meta_data[$res['key']]['note']:null;
								}
							}
						}
						if($lang == 1){
							$row['final_result'] = $final_result_en;
						}
						else{
							$row['final_result'] = $final_result_hi;
						}
						$m = date('M Y',strtotime($row->date));
						$arrDisplay[$m][] = $row;
					}
					foreach ($arrDisplay as $key => $value) {
						$myArr[] = array('date'=>$key,'value'=> $value);
					}
				}
				return $this->sendResponse($myArr, '',$success);	
			}
		}
	}
	public function getFinalResult($meta_data,$result_note,$result = null) {
		$meta_data = json_decode($meta_data);
		$key = null;
		if(isset($meta_data)){
			foreach($meta_data as $k => $meta){
				if(strcasecmp($meta->answer,$result_note) == 0){
					$result = $meta->note;
					$key = $k;
				}
			}
		}
		return ['result'=>$result,'key'=>$key];		
	}
	public function getFinalResultOld($meta_data,$result_note,$result = null) {
		if(count($meta_data)>0){
			foreach($meta_data as $meta){
				if(strcasecmp($meta->answer,$result_note) == 0){
					$result = $meta->note;
				}
			}
		}
		return $result;		
	}
	public function gettempListPdf(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required|max:50',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$temp = ManageTemperatureRecords::where(['user_id'=>$user_array['user_id'],'status'=>1])->orderBy("id","DESC")->get();
				if($user_array['lng'] == "hi"){
					$tempData = view('pages.pdfFiles.tempHinPDF',compact('temp'))->render();
				}
				else{
					$tempData = view('pages.pdfFiles.tempPDF',compact('temp'))->render();
				}
				
				$output = PDF::loadHTML($tempData)->output();
				file_put_contents(public_path()."/htmltopdfview.pdf", $output);
				$pdf_url = 	url("/")."/public/htmltopdfview.pdf?".time();
				return $this->sendResponse($pdf_url,'',true);	
			}
		}
	}
	
	public function checkFcmToken(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$checkUser = User::where(['id'=>$user_array['user_id']])->first();
			$token = 0;
			if($checkUser->fcm_token){
				 $token = 1;
			}
			return $token;	
			
		}
	}
	
	public function updateFcmToken(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['fcm_token'] = $data->get('fcm_token');
			User::where(['id'=>$user_array['user_id']])->update(array('fcm_token' => $user_array['fcm_token']));
			return $this->sendResponse('', 'FCM Updated Successfully.',true);
		}
	}
	
	public function updateUserNotifyStatus(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['p_id'] = $data->get('p_id');
			$user_array['call_status'] = $data->get('call_status');
			$nData = NotifyUserSms::where(["patient_id"=>$user_array['p_id']])->orderBy('id','DESC')->first();
			if(!empty($nData)) {
				NotifyUserSms::where(['id'=>$nData->id])->update(array('status' => '1','call_status' => $user_array['call_status']));
			}
			return $this->sendResponse('', 'status Updated Successfully.',true);
		}
	}
	public function getCouponCodeLists(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$dt = date('Y-m-d');
			$user_array['type'] = $data->get('type');
			$coupons = Coupons::where(['type'=>$user_array['type']])->where('coupon_last_date','>',$dt)->where(['is_show'=>'1','status' => '1','delete_status'=>'1'])->orderBy("id","desc")->get();
			return $this->sendResponse($coupons, 'Data get Successfully.',true);
		}
	}
	public function getQuesByType(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$user_array['lng'] = $data->get('lng');
			$user_array['type'] = $data->get('type');
			$user_array['level_type'] = $data->get('level_type');
			$success = false;
			$lang = 1;
			if($user_array['lng'] == 'hi'){
				$lang = 2;
			}
			if($user_array['id'] != "") {
				$ques = HealthQuestion::where(['id'=>$user_array['id'],'lang'=>$lang,'type'=>$user_array['type'],'delete_status'=>1])->orderBy('order_id','ASC')->first();
			}
			else{
				$qry = HealthQuestion::where(['lang'=>$lang,'type'=>$user_array['type'],'delete_status'=>1]);
				if(!empty($user_array['level_type'])) {
					$level_type = strtolower($user_array['level_type']);
					$qry->where('level_type',$level_type);
				}
				$ques = $qry->orderBy('order_id','ASC')->first();
			}
			if(!empty($ques)){
				if(!empty($ques->meta_data)){
					$meta_data = json_decode($ques->meta_data);
					$metaVal = [];
					foreach($meta_data as $val){
						$metaVal[] = $val;
					}
					$ques['question'] = $metaVal;
				}
				$success = true;
			}
			return $this->sendResponse($ques, 'Data get Successfully.',$success);
		}
	}
	public function getReferCodeLists(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$dt = date('Y-m-d');
			$refs = ReferralMaster::where('code_last_date','>',$dt)->where(['is_show'=>'1','status' => '1','delete_status'=>'1'])->orderBy("id","desc")->get();
			return $this->sendResponse($refs, 'Data get Successfully.',true);
		}
	}
	public function getRewards(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			
			$validator = Validator::make($user_array, [
				'user_id'   => 'required',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$walletDetails = UserDetails::select('wallet_amount','referral_code')->where(['user_id'=>$user_array['user_id']])->first();
				$walletHistory = UserWallet::where(['user_id'=>$user_array['user_id']])->orderBy("created_at","DESC")->get();
				$success = true;
				$response = ['walletDetails'=>$walletDetails,'walletHistory'=>$walletHistory];
				return $this->sendResponse($response,'',$success);	
			}
		}
	}
	public function sendInviteSms(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['mobile_no'] = $data->get('mobile_no');
			$user_array['ref_code'] = $data->get('ref_code');
			$validator = Validator::make($user_array, [
				'mobile_no'   => 'required',
				// 'ref_code'   => 'required',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$app_link = "www.healthgennie.com/download";
				$direct_referred_reward = getSetting("direct_referred_reward")[0];
				$message = urlencode("Hi User, Congratulations! We are happy to share that you have earned Rs. ".$direct_referred_reward." cash in your Health Gennie wallet. You have been referred by our happy customer to use referral code: ".$user_array['ref_code'].". You can avail the coupon in wallet option of the app. Download your shortcut to better health with Health Gennie app. Get it now www.healthgennie.com/download. Thanks Team Health Gennie.");
				
				// $message = urlencode("Hi Dear, I just got HG cash ".$direct_referred_reward." reward from Health Gennie. You can also get it if you just sign up. Download the Health Gennie app and start managing your health in a better way. Use Code : ".$user_array['ref_code']." Get it now https://bit.ly/3Bqpcs7 \nThanks Team Health Gennie.");
				
				$mobile = str_replace(" ", "",$user_array['mobile_no']);
				$this->sendSMS($mobile,$message,'1707169045567999288');
				return $this->sendResponse('', 'Invitation send Successfully.',true);
			}
		}
	}
	public function getRefPageData(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['lng'] = $data->get('lng');
			$user_array['slug'] = $data->get('slug');
			
			$validator = Validator::make($user_array, [
				'user_id' => 'required',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				if(!empty($user_array['lng'])) {
					$pages = DB::table('pages')->whereIn('slug',['refer-content-app','refer-up-to','terms-conditions-earninvite'])->where(["status"=>1])->where("lng",$user_array['lng'])->get();
					$refer_up_to = null; $refer_content_app = null; $terms_conditions_earninvite = null;
					if($pages->count()>0){
						foreach($pages as $raw){
							if($raw->slug == 'refer-up-to'){
								$refer_up_to = $raw->description;
							}
							else if($raw->slug == 'refer-content-app'){
								$refer_content_app = $raw->description;
							}
							else if($raw->slug == 'terms-conditions-earninvite'){
								$terms_conditions_earninvite = $raw->description;
							}
						}
					}
				}
				$walletDetails = UserDetails::select('wallet_amount','referral_code')->where(['user_id'=>$user_array['user_id']])->first();
				$refer_msg_app = getSetting("refer_msg_app")[0];
				$res = ['refer_up_to'=>$refer_up_to,'refer_content_app'=>$refer_content_app,'terms_conditions_earninvite'=>$terms_conditions_earninvite,'walletDetails'=>$walletDetails,'refer_msg_app'=>$refer_msg_app];
				return $this->sendResponse($res, 'Data Get Successfully.',true);
			}
		}
	}
	public function registerReferred(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['ref_code'] = $data->get('ref_code');
			$validator = Validator::make($user_array, [
				'user_id' => 'required',
				'ref_code' => 'required',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$refUserId = getUserIdByRefCode($user_array['ref_code'],$user_array['user_id']);
				if(!empty($refUserId)) {
					$direct_referred_reward = getSetting("direct_referred_reward")[0];
					if(!empty($direct_referred_reward) > 0) {
						$detail = UserDetails::select('wallet_amount')->where(['user_id'=>$user_array['user_id']])->first();
						$wallet_amount = $detail->wallet_amount + $direct_referred_reward;
						UserWallet::create([
							'user_id' => $user_array['user_id'],
							'type' => 1,
							'amount' => $direct_referred_reward
						]);
						UserDetails::where('user_id',$user_array['user_id'])->update(['referred_id'=>$refUserId,'wallet_amount'=>$wallet_amount]);
					}
					$direct_referral_reward = getSetting("direct_referral_reward")[0];
					if(!empty($direct_referral_reward) > 0) {
						$referralUserDetail = UserDetails::select('wallet_amount')->where(['user_id'=>$refUserId])->first();
						$referral_wallet_amount = $referralUserDetail->wallet_amount + $direct_referral_reward;
						UserWallet::create([
							'user_id' => $refUserId,
							'type' => 1,
							'amount' => $direct_referral_reward
						]);
						UserDetails::where('user_id',$refUserId)->update(['wallet_amount'=>$referral_wallet_amount]);
					}
					return $this->sendResponse('', 'You have received Rs.50 in HG Wallet. Enjoy your newfound wealth!💸✨',true);
				}
				else return $this->sendResponse('', 'Referred Code Does\'t matched.',false);
			}
		}
	}
	public function getWalletDetails(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['user_id']=$data->get('user_id');
		$user_array['type']=$data->get('type');
		$validator = Validator::make($user_array, [
			'user_id' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$amount = @UserDetails::select('wallet_amount')->where(['user_id'=>$user_array['user_id']])->first()->wallet_amount;
			$res["wallet_amount"] = $amount;
			if($user_array['type'] == 2) {
				$res["avail_limit"] = getSetting("reward_labs_avail_limit")[0];
			}
			else if($user_array['type'] == 3) {
				$res["avail_limit"] = getSetting("reward_subs_avail_limit")[0];
			}
			return $this->sendResponse($res, '',true);
		}
	}
	public function applyWalletAmt(Request $request) {
		$data=Input::json()->all();
		$user_array=array();
		$user_array['user_id']=@$data['user_id'];
		$user_array['type']=@$data['type'];
		$user_array['appt_type']= @$data['appt_type'];
		$user_array['isFirstTeleAppointment']= @$data['isFirstTeleAppointment'];
		$user_array['lng']=@$data['lng'];
		$user_array['wallet_amount']=@$data['amount'];
		$user_array['plan_id']=@$data['plan_id'];
		$user_array['meta_data']=@$data['meta_data'];
		$validator = Validator::make($user_array, [
			'user_id' => 'required'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$msg = null; $availAmount = 0; $isApplicable = 0;
			if($user_array['type'] == 1) {
				$avail_limit = getSetting("reward_appt_avail_limit")[0];
				if($user_array['wallet_amount'] > $avail_limit) {
					$availAmount = (float) $avail_limit;
				}
				else{
					$availAmount = (float) $user_array['wallet_amount'];
				}
				if($user_array['appt_type'] == 1) {
					$isApplicable = 1;
				}
				else{
					$isApplicable = 0;
				}
				if($user_array['isFirstTeleAppointment'] == 1 && $user_array['meta_data']['isDirectAppt'] == 1) {
					$isApplicable = 0;
				}
				if($isApplicable == 1){
				if($user_array['lng'] == "hi") {
					$msg = "हेल्थ जिनि कैश ₹".$availAmount." का डिस्काउंट लागु होता हे ";
				}
				else {
					$msg = "Health Gennie Cash ₹".$availAmount." applied on this order";
				}}
				else{
					if($user_array['lng'] == "hi") {
						$msg = "हेल्थ जिनि कैश का डिस्काउंट लागु नहीं होता हे ";
					}
					else {
						$msg = "Health Gennie Cash not applied on this order";
					}
				}
			}
			else if($user_array['type'] == 2) {
				$avail_limit = getSetting("reward_subs_avail_limit")[0];
				if($user_array['wallet_amount'] > $avail_limit) {
					$availAmount =(float)  $avail_limit;
				}
				else{
					$availAmount = (float)$user_array['wallet_amount'];
				}
				$isApplicable = 1;
				if($user_array['plan_id'] == 7 || $user_array['plan_id'] == 8) {
					$isApplicable = 0;
				}
				if($isApplicable == 1) {
				if($user_array['lng'] == "hi") {
					$msg = "हेल्थ जिनि कैश ₹".$availAmount." का डिस्काउंट लागु होता हे ";
				}
				else {
					$msg = "Health Gennie Cash ₹".$availAmount." applied on this order";
				}}
				else{
					if($user_array['lng'] == "hi") {
						$msg = "हेल्थ जिनि कैश का डिस्काउंट लागु नहीं होता हे ";
					}
					else {
						$msg = "Health Gennie Cash not applied on this order";
					}
				}
			}
			else if($user_array['type'] == 3) {
				$avail_limit = getSetting("reward_labs_avail_limit")[0];
				if($user_array['wallet_amount'] > $avail_limit) {
					$availAmount = (float)$avail_limit;
				}
				else{
					$availAmount = (float)$user_array['wallet_amount'];
				}
				if($user_array['lng'] == "hi") {
					$msg = "हेल्थ जिनि कैश ₹".$availAmount." का डिस्काउंट लागु होता हे ";
				}
				else {
					$msg = "Health Gennie Cash ₹".$availAmount." applied on this order";
				}
				$isApplicable = 1;
			}
			$res = ['availAmount'=>$availAmount,'msg'=>$msg,'isApplicable'=>$isApplicable];
			return $this->sendResponse($res, '',true);
		}
	}
	public function getSymtomData(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['symptom_id']=$data->get('symptom_id');
		$validator = Validator::make($user_array, [
			'symptom_id' => 'required'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else {
			$symptom = Symptoms::with(['SymptomsSpeciality','SymptomTags'])->Where('id',$user_array['symptom_id'])->first();
			return $this->sendResponse($symptom, '',true);
		}
	}
	public function updateAppFeedStatus(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['appt_id']=$data->get('appt_id');
		$user_array['user_id']=$data->get('user_id');
		$user_array['doc_id']=$data->get('doc_id');
		$user_array['status']=$data->get('status');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'appt_id' => 'required',
			'doc_id' => 'required',
			'status' => 'required',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$is_feedback = ApptFeedbackStatus::where(['user_id'=>$user_array['user_id'],'appt_id'=>$user_array['appt_id']])->count();
			if($is_feedback == 0) {
				ApptFeedbackStatus::create([
					'appt_id' => $user_array['appt_id'],
					'user_id' => $user_array['user_id'],
					'doc_id' => $user_array['doc_id'],
					'status' => $user_array['status'],
				]);
			}
			return $this->sendResponse('', 'Thanks',true);
		}
	}
		
	public function fetchNotification(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$notifications = UserNotifications::select(['title','message','id','route','meta_data','icon','created_at'])->with("NotificationUserId")->orderBy('created_at','DESC')->where('delete_status',1)->paginate(10);
			if($notifications->count() > 0 ) {
				foreach($notifications as $raw) {
					if(!empty($raw->icon)) {
						$raw['icon'] = 'data:image/png;base64,'.base64_encode($raw->icon);
					}
					else{
						$raw['icon'] = url("/")."/images/notification-dummy-icon.png";
					}
					$raw['active'] = "1";
					if($raw->NotificationUserId->count()>0) {
						foreach($raw->NotificationUserId as $notf){
							if($notf->user_id == $user_array['user_id'])  {
								$raw['active'] = "0";
								break;
							}
						}
					}
				}
			}
			return $this->sendResponse($notifications,'Fetch notification successfully',true);
		}
	}
	
	public function updateNotificationData(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['notification_id']=$data->get('notification_id');
		$user_array['user_id']=$data->get('user_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'notification_id' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$isUserNotifications = NotificationUserId::select("id")->where(['user_id'=>$user_array['user_id'],'notification_id'=>$user_array['notification_id']])->count();
			if($isUserNotifications == 0) {
				NotificationUserId::create([
					'notification_id' => $user_array['notification_id'],
					'user_id' => $user_array['user_id'],
				]);
			}
			return $this->sendResponse('', 'Thanks',true);
		}
	}
	public function updateUserNotificationSetting(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['status']=$data->get('status');
		$user_array['user_id']=$data->get('user_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'status' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			User::where('id',$user_array['user_id'])->update(['notification_status'=>$user_array['status']]);
			
			if($user_array['status'] == 1){
				return $this->sendResponse('', 'Thanks for setting notifications stay connected',true);
			}
			else{
				return $this->sendResponse('', 'Thank you for considering notifications. To stay connected and receive timely updates, please enable notification settings. We appreciate your engagement!',true);
			}
		}
	}
	
	public function saveClickWMH(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['type']=$data->get('type');
		$user_array['user_id']=$data->get('user_id');
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'type' => 'required',
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			DB::table('wmh_clicks')->insert([
				'user_id' => $user_array['user_id'],
				'type' => $user_array['type'],
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			]);
			return $this->sendResponse('', 'Thanks',true);
		}
	}
	public function accessToken(Request $request) {
        $data = Input::json();
        $user_array = array();
        $user_array['appId'] = $data->get('appId');
        $user_array['appCertificate'] = $data->get('appCertificate');
        $user_array['channelName'] = $data->get('channelName');
        $user_array['uid'] = $data->get('uid');
        $user_array['p_id'] = $data->get('p_id');

        $validator = Validator::make($user_array, [
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else{
			// Need to set environment variable AGORA_APP_ID
			$appId = $user_array['appId'];
			// Need to set environment variable AGORA_APP_CERTIFICATE
			$appCertificate = $user_array['appCertificate'];

			$channelName = $user_array['channelName'];
			$uid = $user_array['uid'];
			$role = RtcTokenBuilder::RoleAttendee;
			$expireTimeInSeconds = 3600;
			$currentTimestamp = (new \DateTime("now", new \DateTimeZone('UTC')))->getTimestamp();
			$privilegeExpiredTs = $currentTimestamp + $expireTimeInSeconds;
			$token = RtcTokenBuilder::buildTokenWithUid($appId, $appCertificate, $channelName, $uid, $role, $privilegeExpiredTs);
			Patients::where('id',$user_array['p_id'])->update(['agora_uid'=>$user_array['uid'],'agora_token'=>$token]);
            return $this->sendResponse($token, '',true);
        }
    }
	public function checkVdoCallStatus(Request $request) {
        $data = Input::json();
        $user_array = array();
        $user_array['p_id'] = $data->get('p_id');
        $user_array['uid'] = $data->get('uid');
        $validator = Validator::make($user_array, [
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        else{
			$nData = NotifyUserSms::where(["patient_id"=>$user_array['p_id']])->orderBy('id','DESC')->first();
			if(!empty($nData)) {
				$doc = Doctors::select('first_name','last_name')->where('user_id',$nData->doc_id)->first();
				$docName = $doc->first_name." ".$doc->last_name;
				$nData['doc_name'] = $docName;
			}
			$vdoData = VdoRecord::where('uid',$user_array['uid'])->first();
			$endCall = !empty($vdoData->end_call) ? $vdoData->end_call : null;
			$nDatas = ['nData'=>$nData,'endCall'=>$endCall];
            return $this->sendResponse($nDatas, '',true);
        }
    }
	public function refCodeVerification(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();  
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['org_id'] = $data->get('org_id');
			$user_array['ref_code'] = $data->get('ref_code');
			$user_array['other_org'] = $data->get('other_org');
			$validator = Validator::make($user_array, [
				'user_id' => 'required',
				'ref_code' => 'required',
			]);
			if($validator->fails()) {
				return $this->sendError($validator->errors());
			}
			else {
				$order_id = time();
				$refUserId = getUserIdByRefCode($user_array['ref_code'],$user_array['user_id']);
				if(!empty($refUserId)) {
					//send rs 10 cashback to the sender
					// add 50 hg cash in wallet
					$detail = UserDetails::select('wallet_amount')->where(['user_id'=>$user_array['user_id']])->first();
					$wallet_amount = $detail->wallet_amount + 50;
					UserWallet::create([
						'user_id' => $user_array['user_id'],
						'type' => 1,
						'amount' => 50
					]);
					UserDetails::where('user_id',$user_array['user_id'])->update(['referred_id'=>$refUserId,'wallet_amount'=>$wallet_amount]);
					$isRef = ReferralCashback::where('referred_id',$user_array['user_id'])->count();
					if($isRef == 0) { 
						if(checkActiveSubs($refUserId) > 0) {
							ReferralCashback::create([
								'type' => 1,
								'order_id' => $order_id,
								'referral_id' => $refUserId,
								'referred_id' => $user_array['user_id'],
								'org_id' => $user_array['org_id'] != 'other' ? $user_array['org_id'] : null,
								'other_org' =>  $user_array['other_org'],
								'status' =>  0,
								'meta_data' =>  null,
								'paytm_status' => null,
							]);
						}
						else{
							ReferralCashback::create([
								'type' => 0,
								'order_id' => $order_id,
								'referral_id' => $refUserId,
								'referred_id' => $user_array['user_id'],
								'org_id' => $user_array['org_id'] != 'other' ? $user_array['org_id'] : null,
								'other_org' =>  $user_array['other_org'],
								'status' =>  0,
								'meta_data' =>  null,
								'paytm_status' => null,
							]);
						}
					}
					if($user_array['org_id'] != 'other') {
						User::where('id', $user_array['user_id'])->update(array(
						  'organization' => $user_array['org_id'],
						));
					}
					return $this->sendResponse('', 'Cashback Redeem Successfully.',true);
				}
				else return $this->sendResponse('', 'Referred Code Does\'t matched.',false);
			}
		}
	}
}
