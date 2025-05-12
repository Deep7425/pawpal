<?php
namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Patients;
use App\Models\Appointments;
use App\Models\Plans;
use App\Models\PlanPeriods;
use App\Models\UserSubscribedPlans;
use App\Models\UsersSubscriptions;
use App\Models\UserSubscriptionsTxn;
use App\Models\Doctors;
use App\Models\ReferralMaster;
use App\Models\Plans as userPlan;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Models\EmailTemplate;
use App\Models\Templates;
use App\Models\Pages;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Database\QueryException;
class SubscriptionController extends Controller
{
    /*
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    function apply_coupon(Request $request) {

	try{	
       $code = $request->searchText;
       //return $code;
       $dt = date('Y-m-d');
       $query =  Coupons::select(['id','coupon_price','other_text','coupon_code'])->where('coupon_code',$code)->whereDate('coupon_last_date','>=', $dt)->where('status','1')->first();
       if($query) {
         $arr = array('coupon_id'=>$query->id,'coupon_rate'=>$query->coupon_price,'other_text'=>$query->other_text,'coupon_code'=>$query->coupon_code);
         return $arr;
       }
       else {
         return 3;
       }

	}catch(Exception $e){

		return $e->getMessage();

	}


    }
	public function checkOutUserPlanPaytm(Request $request) {

		try{
			

		if($request->isMethod('post')){
			$data = $request->all();
			$user_id = Auth::id();
			$orderId = "SUBS"."1";
			$userSubs = UsersSubscriptions::orderBy("id","DESC")->first();
			if(!empty($userSubs)){
				$sid = $userSubs->id + 1;
				$orderId = "SUBS".$sid;
			}
			$data['user_id'] = $user_id;
			$subscription =  UsersSubscriptions::create([
				 'user_id' => $user_id,
				 'order_id' => $orderId,
				 'payment_mode' => 1,
				 'ref_code' => $data['referral_user_id'],
				 'coupon_id' => null,
				 'tax' => $data['tax'],
				 'order_subtotal' => $data['order_subtotal'],
				 'order_total' => $data['order_total'],
				 'coupon_discount' => $data['coupon_discount'],
				 'meta_data' => json_encode($data),
			]);
			$txnToken = $this->planCheckoutPaytm($orderId,$user_id,$data['order_total']);
			$parameters = [
				"status"=> 1,
				'tid' => base64_encode(strtotime("now")),
				'order_id' => base64_encode($orderId),
				'order_by' => base64_encode($user_id),
				'amount' => base64_encode($data['order_total']),
				'txnToken' => base64_encode($txnToken),
				'MID' => base64_encode("MiniAp78932858151828"),
			];
			return $parameters;
		}
	
	   }catch(Exception $e){

		return $e->getMessage();

	   }
    }
	
	public function checkOutUserPlan(Request $request) {
		if($request->isMethod('post')){
			$data = $request->all();
			$user = Auth::user();
			$orderId = "SUBS"."1";
			$userSubs = UsersSubscriptions::orderBy("id","DESC")->first();
			if(!empty($userSubs)){
				$sid = $userSubs->id + 1;
				$orderId = "SUBS".$sid;
			}
			$user_id = $user->id;
			if(isset($data['isAppt']) && $data['isAppt'] == '1') {
				$patientInfo = [];
				$patientInfo['order_by'] = $user_id;
				$patientInfo['p_id'] = $user_id;
				$patientInfo['gender'] = $user->gender;
				$patientInfo['patient_name'] = $user->first_name." ".$user->last_name;
				$patientInfo['dob'] = $data['dob'];
				$patientInfo['dob_type'] = $data['dob_type'];
				$patientInfo['mobile_no'] = $user->mobile_no;
				$patientInfo['other_mobile_no'] = $user->other_mobile_no;
				$patientInfo['otherPatient'] = 0;
				$patientInfo['coupon_id'] = null;
				$patientInfo['coupon_discount'] = null;
				$data['patientInfo'] = $patientInfo;
			}
			$data['hg_miniApp'] = 0;
			if(Session::get('wltSts') != null) {
				$data['hg_miniApp'] = 1;
			}else if(Session::get('lanEmitraData') != null){
				$data['hg_miniApp'] = 2;
			}
			$data['user_id'] = $user_id;
			$subscription =  UsersSubscriptions::create([
				 'user_id' => $user_id,
				 'order_id' => $orderId,
				 'payment_mode' => 1,
				 'ref_code' => $data['referral_user_id'],
				 'coupon_id' => null,
				 'tax' => $data['tax'],
				 'order_subtotal' => $data['order_subtotal'],
				 'order_total' => $data['order_total'],
				 'coupon_discount' => $data['coupon_discount'],
				 'hg_miniApp' => $data['hg_miniApp'],
				 'meta_data' => json_encode($data),
			]);
			if($data['hg_miniApp'] == 1) {
				$parameters = [
					"status"=> 2,
					'tid' => base64_encode(strtotime("now")),
					'order_id' => base64_encode($orderId),
					'order_by' => base64_encode($user_id),
					'amount' => base64_encode($subscription->order_total),
					'MID' => base64_encode("MiniAp78932858151828"),
				];
				return $parameters;
			} else if($data['hg_miniApp'] == 2) {
                
				$getsess = Session::get('lanEmitraData');
                $time = (string) strtotime("now");
				$checkArr = array(
				'SSOID' => $getsess['decryptData']['SSOID'],
				'REQUESTID' => $orderId,
				'REQTIMESTAMP' => $time,
				'SSOTOKEN' => $getsess['decryptData']['SSOTOKEN']
				);
				//echo json_encode($checkArr);

				$postdata1 = http_build_query(
				array(
				'toBeCheckSumString' => json_encode($checkArr)
				)
				);

				$opts1 = array('http' =>
				array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata1
				)
				);

				$context1  = stream_context_create($opts1);
				//for test
				$checksum = file_get_contents('http://emitrauat.rajasthan.gov.in/webServicesRepositoryUat/emitraMD5Checksum', false, $context1);
				//$checksum = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraMD5Checksum', false, $context1);
				//$checksum = json_decode($result1, TRUE);
				
				//for payment
				//echo $checksum."<br>";
				$myplan = $data['plan_id'];
				$commision = 0;
				if($myplan == 21){
                   $commision = 225;
				}else if($myplan == 7){
                   $commision = 90;
                }else if($myplan == 4){
                   $commision = 150;
				}else if($myplan == 11){
                   $commision = 375;
				}
				$paymentArr = array(
				'MERCHANTCODE' => 'FITKID2022',
				'REQUESTID' => $orderId,
				'REQTIMESTAMP' => $time,
				'SERVICEID' => "8992",
				'SUBSERVICEID' => '',
				'REVENUEHEAD' =>  "3565-".$data['order_total']."|3564-".$commision,
				'CONSUMERKEY' => $orderId,
				'CONSUMERNAME' => $user->mobile_no.$orderId,
				'COMMTYPE' => "3",
				'SSOID' => $getsess['decryptData']['SSOID'],
				'OFFICECODE' => 'FITKIDHEALTH',
				'SSOTOKEN' => $getsess['decryptData']['SSOTOKEN'],
				'CHECKSUM' => $checksum
				);
				//print_r($paymentArr);
				
		        $postdata2 = http_build_query(
				array(
				'toBeEncrypt' => json_encode($paymentArr)
				)
				);

				$opts2 = array('http' =>
				array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata2
				)
				);

				$context2  = stream_context_create($opts2);
				//for test
				$encconvert = file_get_contents('http://emitrauat.rajasthan.gov.in/webServicesRepositoryUat/emitraAESEncryption', false, $context2);
				//$encconvert = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESEncryption', false, $context2);
				//pr($encconvert);

                $postdata3 = http_build_query(
				array(
				'encData' => $encconvert
				)
				);

				$opts4 = array('http' =>
				array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdata3
				)
				);

				$context3  = stream_context_create($opts4);
				//for test
				 $pay = file_get_contents('http://emitrauat.rajasthan.gov.in/webServicesRepositoryUat/backtobackTransactionWithEncryptionA', false, $context3);
				//$pay = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/backtobackTransactionWithEncryptionA', false, $context3);
				//die;
				$postdataA = http_build_query(
				array(
				'toBeDecrypt' => $pay
				)
				);

				$optsA = array('http' =>
				array(
				'method'  => 'POST',
				'header'  => 'Content-Type: application/x-www-form-urlencoded',
				'content' => $postdataA
				)
				);

				$contextA  = stream_context_create($optsA);
				//for test
				$resultA = file_get_contents('http://emitrauat.rajasthan.gov.in/webServicesRepositoryUat/emitraAESDecryption', false, $contextA);
				//$resultA = file_get_contents('https://emitraapp.rajasthan.gov.in/webServicesRepository/emitraAESDecryption', false, $contextA);
				$encDataA = json_decode($resultA, TRUE);
				//print($encDataA);
				//echo $responseb = json_decode($pay,true);
				//pr($encDataA);
                   $res = $this->doitSubs($encDataA);
                   if($res == 'Done'){
                      // echo $getsess['decryptData']['RETURNURL'];
						$parameters = [
						"status"=> 3,
						'return_url' => $getsess['decryptData']['RETURNURL'],
						'order_id' => base64_encode($orderId),
						'msg'=>$encDataA['MSG']
						//'order_by' => base64_encode($user_id),
						//'amount' => base64_encode($subscription->order_total),
						//'MID' => base64_encode("MiniAp78932858151828"),
						];
						return $parameters;
                   }else{
                        //echo $getsess['decryptData']['RETURNURL'];
						$parameters = [
						"status"=> 4,
						'return_url' => $getsess['decryptData']['RETURNURL'],
						'order_id' => base64_encode($orderId),
						'msg'=>$encDataA['MSG']
						//'order_by' => base64_encode($user_id),
						//'amount' => base64_encode($subscription->order_total),
						//'MID' => base64_encode("MiniAp78932858151828"),
						];
						return $parameters;
                   }
			}
			else{
				if($data['walletDiscountAmount']){
					// availWalletAmount($user_id,7,$data['walletDiscountAmount']);
					$walletAmt = $data['walletDiscountAmount'];
				}else{
					$walletAmt = 0;
				}
				
				$parameters = [
					"status"=> 1,
					'tid' => base64_encode(strtotime("now")),
					'order_id' => base64_encode($orderId),
					'order_by' => base64_encode($user_id),
					'amount' => base64_encode($subscription->order_total),
					'MID' => base64_encode("MiniAp78932858151828"),
					'wallet_amt' => base64_encode($walletAmt),
				];

				

				return $parameters;
			}
		}
		else{
			$plan_id = base64_decode($request->id);
			$tp = base64_decode($request->tp);
			$plan = Plans::where('id', $plan_id)->first();
			return view($this->getView('users.subscription.checkout_plan'),['plan'=>$plan,'tp'=>$tp]);
		}
    }
	public function planCheckoutLive(Request $request) {

	try{	

		$data = $request->all();
		/*$parameters = [];
		$parameters["MID"] = "yNnDQV03999999736874"; 
		$parameters["ORDER_ID"] = base64_decode($data['order_id']); 
		$parameters["CUST_ID"] = base64_decode($data['order_by']); 
		$parameters["TXN_AMOUNT"] = base64_decode($data['amount']); 
		$parameters["CALLBACK_URL"] = url('paytmresponse'); 
		$order = Indipay::gateway('Paytm')->prepare($parameters);
		return Indipay::process($order);*/ 
		
		$user = User::where("id",base64_decode($data['order_by']))->first();
		$mbl = isset($user->mobile_no) ? $user->mobile_no : '0000000000';
		$email = !empty($user->email)   ? $user->email : 'test@mailinator.com';
		$parameters["order"] = base64_decode($data['order_id']);
		$parameters["amount"] = 1;
		$parameters["user"] = base64_decode($data['order_by']);
		$parameters["mobile_number"] = $mbl;
		$parameters["email"] = $email;
		$parameters["callback_url"] = url('paytmresponse');
		$payment = PaytmWallet::with('receive');
		$payment->prepare($parameters);
		return $payment->receive();

		}catch(Exception $e){

			return $e->getMessage();

		}
	}
	public function planCheckoutPaytm($order_id,$order_by,$amount) {
      
		try{

		$parameters = [];
		$parameters["MID"] = "MiniAp78932858151828"; 
		$parameters["ORDER_ID"] = $order_id; 
		$parameters["CUST_ID"] = $order_by; 
		$parameters["TXN_AMOUNT"] = $amount; 
		//$parameters["CALLBACK_URL"] = url('paytmresponse'); 
		$paytmParams = array();
		$paytmParams["body"] = array(
			"requestType"   => "Payment",
			"mid"           => $parameters["MID"],
			"websiteName"   => "WEBPROD",
			"orderId"       => $parameters["ORDER_ID"],
			//"callbackUrl"   => url('paytmresponse'),
			"txnAmount"     => array(
				"value"     => $parameters["TXN_AMOUNT"],
				"currency"  => "INR",
			),
			"userInfo"      => array(
				"custId"    => $parameters["CUST_ID"],
			),
		);
		$checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), "oS%zlWJKYh#GqL5P");
		$paytmParams["head"] = array(
			"signature"	=> $checksum
		);
		$post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
		/* for Staging */
		//$url = "https://securegw-stage.paytm.in/theia/api/v1/initiateTransaction?mid=".$parameters["MID"]."&orderId=".$parameters["ORDER_ID"];
		/* for Production */
		$url = "https://securegw.paytm.in/theia/api/v1/initiateTransaction?mid=".$parameters["MID"]."&orderId=".$parameters["ORDER_ID"];
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json")); 
		$response = curl_exec($ch);
		$response = json_decode($response,true);
		
		$txnToken = "";
		if(isset($response['body']['resultInfo']) && $response['body']['resultInfo']['resultStatus'] == "S"){
			$txnToken = $response['body']['txnToken'];
		}
		return $txnToken;

		}catch(Exception $e){

			return $e->getMessage();

		}
	}
    public function subscriptionPlans(Request $request) {

		try{

		 return view($this->getView('users.index'));

		}catch(Exception $e){

			return $e->getMessage();

		}
    }
	
	 public function drive(Request $request)  {
		try{
		 $user = Auth::user();
  		if($user!= null && $user->profile_status != '1') {
  			// return redirect()->route('profile',['id'=>base64_encode($user->id)]);
  			// return redirect()->route('userAppointment');
			if(Session::get('loginFrom') == '3') {
				Session::forget('loginFrom');
				$appData = Session::get('appDoctorData');
				return redirect()->route('doctor.bookSlot',$appData)->withInput();
			}
			else if(Session::get('loginFrom') == '7') {
				Session::forget('loginFrom');
				$planId = Session::get('planId');
				return redirect()->route('checkOutUserPlan',['id'=>$planId])->withInput();
			}
			else{
				$plans = Plans::Where(["delete_status"=>'1','status'=>1])->whereIn("type",array(1,2))->orderBy('price', 'asc')->get();
				$upper_content = Pages::where(['slug'=>'subscription-plan-page-content-upperapp'])->first();
				$bottom_content= Pages::where(['slug'=>'subscription-plan-page-content-bottomapp'])->first();
				return view('users.index',compact('plans','upper_content','bottom_content'));				
			}
  		}
		//login from cart
		else if($user!= null &&  Session::get('loginFrom') == '1') {
			Session::forget('loginFrom');
			 return redirect()->route('LabCart');
		}
		//login from Medicines
		elseif ($user!= null &&  Session::get('loginFrom') == '2') {
			Session::forget('loginFrom');
			echo '<script>window.open("'.route('oneMgOpen').'","_blank")</script>';
			return view($this->getView('home'));
		}
		elseif($user!= null && Session::get('loginFrom') == '3') {
			Session::forget('loginFrom');
			$appData = Session::get('appDoctorData');
			return redirect()->route('doctor.bookSlot',$appData)->withInput();
		}
		elseif($user!= null && Session::get('loginFrom') == '4') {
			Session::forget('loginFrom');
			$blogData = Session::get('hgBlogData');
			return \Redirect::to($blogData);
		}
		elseif($user!= null && Session::get('loginFrom') == '5') {
			Session::forget('loginFrom');
			$feedData = Session::get('feedDoctorData');
			Session::forget('feedDoctorData');
			return \Redirect::to($feedData);
		}
		elseif($user!= null && Session::get('loginFrom') == '7') {
			Session::forget('loginFrom');
			$planId = Session::get('planId');
			return redirect()->route('checkOutUserPlan',['id'=>$planId])->withInput();
		}
		elseif($user!= null && Session::get('loginFrom') == '8') {
			Session::forget('loginFrom');
			return redirect()->route('allAssessmentScore' );
		}
		elseif($user!= null && Session::get('loginFrom') == '9') {
			Session::forget('loginFrom');
			return redirect()->route('fetchAssessmentMetrix' );
		}
		elseif($user!= null && Session::get('loginFrom') == '10') {
			Session::forget('loginFrom');
			$sympID = Session::get('symp_id');
			return redirect()->route('fetchAssesmentQues' ,['symp_id' =>$sympID])->withInput();
		}
		elseif($user!= null && Session::get('loginFrom') == '17') {
			Session::forget('loginFrom');
			return redirect()->route('voiceAssessment')->withInput();
		}
		elseif($user!= null && Session::get('loginFromConsult') == '1') {
			// pr(Session::get('loginFrom'));
			Session::forget('loginFrom');
			Session::forget('loginFromConsult');
			// $appData = Session::get('appDoctorData');
			return redirect()->route('onlineConsult')->withInput();
		}
		elseif($user!= null && Session::get('loginFrom') == '11') {
			Session::forget('loginFrom');
			return redirect()->route('LabDashboard' );
		}
		elseif($user!= null && Session::get('loginFrom') == '12') {
			Session::forget('loginFrom');
			return redirect()->route('slotBook' );
		}
		elseif($user!= null && Session::get('loginFrom') == '13') {
			Session::forget('loginFrom');
			return redirect()->route('labOrderDetails' );
		}
		elseif($user!= null && Session::get('loginFrom') == '14') {
			Session::forget('loginFrom');
			return redirect()->route('labOrders' );
		}
		elseif($user!= null && Session::get('loginFrom') == '15') {
			Session::forget('loginFrom');
			return redirect()->route('LabDetails' );
		}
		elseif($user!= null && Session::get('loginFrom') == '15') {
			Session::forget('loginFrom');
			return redirect()->route('getDoctorInfo' );
		}
		else{
			//$plans = Plans::Where(["status"=>1,"delete_status"=>'1'])->orderBy('price', 'desc')->get();
			$plans = Plans::Where(["delete_status"=>'1','status'=>1])->whereIn("type",array(1,2))->orderBy('price', 'asc')->get();
				$upper_content = Pages::where(['slug'=>'subscription-plan-page-content-upperapp'])->first();
				$bottom_content= Pages::where(['slug'=>'subscription-plan-page-content-bottomapp'])->first();
			if(count($plans) > 0){
				//foreach($plans as $plan){
				//	$plan['pkg_data'] = availPackDetails($plan->lab_pkg);
				//}
			}
			 return view('users.index',compact('plans','upper_content','bottom_content'));
			//return redirect()->route('userAppointment');
		}

		}catch(Exception $e){

			return $e->getMessage();

		}

	 } 
	 public function mySubscriptions(Request $request) {

		try{
		 $user_id = Auth::id();
		 $UsersSubscriptions = UsersSubscriptions::with('UserSubscribedPlans.Plans')->Where('user_id', $user_id)->Where('order_status', 1)->orderBy('id','desc')->get();
		 return view($this->getView('users.subscription.mySubscriptions'),['UsersSubscriptions'=>$UsersSubscriptions]);
		
		}catch(Exception $e){

			return $e->getMessage();

		}
	 }
	 public function viewSubscription(Request $request, $id) {
		try{
		$id = base64_decode($id);
		
		$UsersSubscriptions = UsersSubscriptions::Where('id', $id)->first();
		
	    return view($this->getView('users.subscription.viewSubscription'),['UsersSubscriptions'=>$UsersSubscriptions]);
	}catch(Exception $e){

		return $e->getMessage();

	}
	 }
	 function ApplyReferralCode(Request $request) {

		try{
		 if($request->isMethod('post')) {
			 $data = $request->all();
			 $checkCode = User::select(['id','mobile_no'])->where(['mobile_no'=> $data['ref_code'],"parent_id"=>0])->where('id', '!=', Auth::id())->first();
			 $success = 0;
			 $res = ["success"=>$success,"referral_user_id"=>"","coupon_discount"=>""];
			 if(!empty($checkCode)) {
				$success = 1;
				$res['referral_user_id'] =  $checkCode->id;
				$res['ref_code'] =  $checkCode->mobile_no;
				$res['success'] =  $success;
				$res['coupon_discount'] =  getSetting("referred_amount")[0];
			}
			else {
				$refData = ReferralMaster::where('code',strtoupper($data['ref_code']))->where(['status'=>1,'delete_status'=>1])->first();
				if(!empty($refData)){
					$dt = date('Y-m-d');
					if($refData->code_last_date < $dt){
						$res['success'] =  0;
						return $res;
					}
					if(!empty($refData->plan_ids)) {
						$plan_ids = explode(",",$refData->plan_ids);
						if(in_array($data['plan_id'],$plan_ids)) {
							$dis = getDiscount($refData->referral_discount_type,$refData->referral_discount,$data['plan_id']);
							$res['type'] =  2;
							$res['ref_code'] =  $refData->code;
							$res['referral_user_id'] =  $refData->id;
							$res['coupon_discount'] =  $dis;
							$res['success'] =  1;
						}
					}
					else{
						$dis = getDiscount($refData->referral_discount_type,$refData->referral_discount,$data['plan_id']);
						$res['type'] =  2;
						$res['ref_code'] =  $refData->code;
						$res['referral_user_id'] =  $refData->id;
						$res['coupon_discount'] =  $dis;
						$res['success'] =  1;
					}
				}
			}
			return $res;
		}

	}catch(Exception $e){

		return $e->getMessage();

	}

     }

    public function doitSubs($data) {

		try{
		//$data = $request->all();
			$orderData = UsersSubscriptions::select(["id","meta_data","order_total"])->where(["order_id"=>$data['REQUESTID']])->first();
		    $orderID = $orderData->id;
			if($data['TRANSACTIONSTATUS'] == 'SUCCESS') {
				$trackingId = rand(10000,1000000);
				UserSubscriptionsTxn::create([
					'subscription_id' => $orderID,
					'tracking_id'=> $data['TRANSACTIONID'],
					'bank_ref_no'=> '',
					'tran_mode'=> 'WALLET',
					'card_name'=> 'WALLET',
					'currency'=> 'INR',
					'payed_amount'=> $orderData->order_total,
					'tran_status' => 'TXN_SUCCESS',
					'trans_date' => date('Y-m-d H:i:s')
				]);
				$meta_data = json_decode($orderData->meta_data,true);
				$plan = userPlan::where('id',$meta_data['plan_id'])->first();
				$subscribedPlan = new UserSubscribedPlans;
				$subscribedPlan->subscription_id = $orderID;
				$subscribedPlan->plan_id = $plan->id;
				$subscribedPlan->plan_price = $plan->price;
				$subscribedPlan->discount_price = $plan->discount_price;
				$subscribedPlan->plan_duration_type = $plan->plan_duration_type;
				$subscribedPlan->plan_duration = $plan->plan_duration;
				$subscribedPlan->appointment_cnt = $plan->appointment_cnt;
				$subscribedPlan->lab_pkg = $plan->lab_pkg;
				$subscribedPlan->meta_data = json_encode($plan);
				$subscribedPlan->save();

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
				   'subscription_id' => $orderID,
				   'subscribed_plan_id' => $subscribedPlan->id,
				   'user_plan_id' => $meta_data['plan_id'],
				   'user_id' => $meta_data['user_id'],
				   'start_trail' => date('Y-m-d'),
				   'end_trail'=> $end_date,
				   'remaining_appointment' => $plan->appointment_cnt,
				   'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
				   'lab_pkg_remaining' => 0,
				   'status' => 1
				]);
				UsersSubscriptions::where(["id"=>$orderID])->update([
					'order_status' => 1,
				]);
				//ApptLink::where(["user_id"=>$meta_data['user_id'],'order_id'=>$data['merchantTxnId']])->update(['status'=>1]);
				
				$this->sendUserSubscriptionMail($orderID,1,"success");
				if(isset($meta_data['patientInfo']) && !empty($meta_data['patientInfo'])) {
					$patientInfo = @$meta_data['patientInfo'];
					$this->addApptForPlan($patientInfo);
				}
				//$orgPay = OrganizationPayment::where(['organization_id'=>9])->where('remaining_amount','!=','0')->orderBy('id', 'asc')->first();
				//$actAmt = $orgPay->remaining_amount - $orderData->order_total;
				//OrganizationPayment::where(['id'=>$orgPay->id])->update(['remaining_amount'=>$actAmt]);
				//$url = url("/").'/plan/success?order_id='.base64_encode(@$data['merchantTxnId']);
				return 'Done';

			}
			else{
				//echo "<br>fail".$orderID;
				
				UsersSubscriptions::where(["id"=>$orderID])->update([
					'order_status' => 3,
				]);
				//die;
				//ApptLink::where(['order_id'=>$data['merchantTxnId']])->update(['status'=>2]);
				//$url = url("/").'/plan/cancel?order_id='.base64_encode(@$data['merchantTxnId']);
				return "Fail";
			}

		}catch(Exception $e){

			return $e->getMessage();

		}
	}
	public function downloadsubrec(Request $request,$id) {

		try{

		$id = base64_decode($id);
		$subscription = UsersSubscriptions::with(['User','UserSubscribedPlans.Plans','UserSubscriptionsTxn','PlanPeriods'])->where('order_id',$id)->first();
		$pdf = PDF::loadView('subscription.DownloadSubsReceiptPDF',compact('subscription'));
		return $pdf->download('pdfviewforSubscription.pdf');

		}catch(Exception $e){

			return $e->getMessage();

		}
	}

function Applywalletcoupon(Request $request) {
		if($request->isMethod('post')) {
			$data = $request->all();
			$validator = Validator::make($data, [
				'walletCode' => 'required'
			 ]);
			if($validator->fails()) {
				$errors = $validator->errors();
				return $errors->messages()['walletCode'];
			}
		   $query =  UserDetails::select(['id','referral_code','wallet_amount'])->where("referral_code",$data['walletCode'])->first();//
			if($query) {
				$arr = array('status'=>'1','userdetails_id'=>$query->id,'wallet_amount'=>$query->wallet_amount,'referral_code'=>$query->referral_code,'msg'=>'Wallet Code Applied Successfully');
				return $arr;
			}
			else {
				return ['status'=>'0','msg'=>'Wallet Code Not Matched.'];
			}
	   }

   }
}
