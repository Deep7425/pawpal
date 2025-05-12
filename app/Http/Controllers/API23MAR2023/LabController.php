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
use App\Models\NewsFeeds;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\PpSliders;
use App\Models\Doctors;
use App\Models\NonHgDoctors;
use App\Models\Speciality;
use App\Models\User;
use App\Models\UserDetails;
use App\Models\ThyrocarePackageGroup;
use App\Models\UsersLaborderAddresses;
use App\Models\ratingReviews;
use App\Models\SearchResults;
use App\Models\CityLocalities;
use App\Models\Admin\SymptomsSpeciality;
use App\Models\Admin\Symptoms;
use App\Models\Admin\SymptomTags;
use App\Models\OutSideAppointments;
use App\Models\ehr\Appointments;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\RoleUser;
use App\Models\ehr\Patients;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\DoctorsInfo;
use App\Models\ehr\PracticeDocuments;
use App\Models\ehr\PatientRagistrationNumbers;

use App\Models\LabCart;
use App\Models\Coupons;
use App\Models\LabOrderTxn;
use App\Models\LabOrderedItems;
use App\Models\LabOrders;
use App\Models\LabReports;
use App\Models\PlanPeriods;
use App\Models\CampData;
use App\Models\UserSubscribedPlans;
use App\Models\LabCollection;
use App\Models\LabRequests;
use App\Models\DefaultLabs;
use App\Models\LabPackage;
use App\Models\LabCompany;
use App\Models\ThyrocareLab;
use App\Models\LabPincode;
use PaytmWallet;
use Softon\Indipay\Facades\Indipay;
class LabController extends APIBaseController {

	public function thyrocarelogin(Request $request) {
		return $this->sendResponse('', '',true);
		 $postdata = array(
				'username' => '9414061829',
				'password' => '256EE3',
				'portalType' => '',
				'userType' => 'dsa',
				'facebookId' => 'string',
				'mobile' => 'string',
				);
                $post_data = json_encode($postdata);
		$url = "https://velso.thyrocare.cloud/api/Login/Login";
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$response = curl_exec($ch);
				//$LoginData = json_decode($response,true);
		//$LoginData = @file_get_contents("https://www.thyrocare.com/APIS/common.svc/9414061829/256EE3/portalorders/DSA/login");
		// $LoginData = file_get_contents("https://www.thyrocare.com/API_BETA/common.svc/7738943013/123456789/portalorders/DSA/login");
		if($response){
			$LoginData = json_decode($response);
			return $this->sendResponse($LoginData, '',true);
		}
		else{
			return $this->sendError('Api Does not execute');
		}
    }
	
	public function getthyrocareData(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['type']=$data->get('type');
		$user_array['limit']=$data->get('limit');
		$validator = Validator::make($user_array, [
			'type' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			// if($user_array['type'] == "thyroSearchData") {
				// $product = ThyrocareLab::limit($user_array['limit'])->get();
			// }
			// else{
				$product = ThyrocareLab::where(['type'=>$user_array['type']])->limit($user_array['limit'])->get();
			// }
			
			/*if($user_array['type'] == "ALL") {
				$product = File::get(public_path('thyrocare-data/All.txt'));
				$product = (array) json_decode($product);
			}
			else if($user_array['type'] == "OFFER") {
				$product = File::get(public_path('thyrocare-data/Offer.txt'));
				$product = (array) json_decode($product);
				$rate = array();
				foreach($product as $key => $row) {
					$rate[$key] = $row->rate->offerRate;
				}
				array_multisort($rate, SORT_ASC, $product);
			}
			else if($user_array['type'] == "PROFILE") {
				$product = File::get(public_path('thyrocare-data/Profile.txt'));
				$product = (array) json_decode($product);
			}
			else if($user_array['type'] == "TESTS") {
				$product = File::get(public_path('thyrocare-data/Tests.txt'));
				$product = (array) json_decode($product);
			}*/
			if($user_array['type'] == "thyroSearchData") {
				// $allArr = [];
				// $allData = File::get(public_path('thyrocare-data/All.txt'));
				// $allData = (array) json_decode($allData);
				
				// $allArr['OFFER'] = $allData['master']->offer;
				// $allArr['TESTS'] = $allData['master']->tests;
				// $allArr['POP_TESTS'] = getPopularTest();
				
				$allArr['OFFER'] =  ThyrocareLab::where(['type'=>"OFFER"])->get();
				$allArr['TESTS'] =  ThyrocareLab::where(['type'=>"TEST"])->get();
				$allArr['POP_TESTS'] = getPopularTest();
				$product = $allArr;
			}
			if(!empty($user_array['limit'])) {
				if($user_array['type'] == "thyroSearchData") {
					$product = array_slice($product, 0, $user_array['limit']);
				}
				else{
					$product = array_slice($product->toArray(), 0, $user_array['limit']);
				}
			}
			return $this->sendResponse($product, '',true);
		}
	}
	
	
		public function getThyrocarePackageGroup(Request $request) {
			$groups = ThyrocarePackageGroup::where(['delete_status'=>1, 'status'=>1])->orderBy("sequence","ASC")->get();
			if(count($groups) > 0) {
				$profile_product = File::get(public_path('thyrocare-data/Profile.txt'));
				$profile_product = json_decode($profile_product);
				
				foreach($groups as $key => $group) {
					if(!empty($group->image)) {
						$group['image'] = url('/')."/public/thyrocarePackageFiles/".$group->image;
					}
					$items = array();
					// pr($profile_product);
					if(count($profile_product) > 0) {
						foreach($profile_product as $structs) {
							if ($group->group_name == trim($structs->groupName)) {
								$items[] = $structs;
							}
						}
						$group['group_package'] = $items;
					}
					else{
						$group['group_package'] = $items;
					}
				}
			}
			return $this->sendResponse($groups, '.',$success = true);
		}

	public function createLaborderAddresses(Request $request) {
			if($request->isMethod('post')) {
				$data = Input::json();
				$user_array=array();
				$address_array['user_id']     = $data->get('user_id');
				$address_array['locality']    = $data->get('locality');
				$address_array['pincode']     = $data->get('pincode');
				$address_array['address']     = $data->get('address');
				$address_array['landmark']    = $data->get('landmark');
				$address_array['label_type']  = $data->get('label_type');
				$address_array['label_name']  = $data->get('label_name');
				$address_array['deliverTo']   = $data->get('deliverTo');
				$address_array['deliverToMobile']    = $data->get('deliverToMobile');

			$validator = Validator::make($address_array, [
				'user_id'   =>  'required',
				'locality'      =>  'required',
				'pincode'     =>  'required',
				'address'   =>  'required',
				'landmark'   =>  'required',
				'label_type'   =>  'required',
			]);
			if($validator->fails()){
				return $this->sendError($validator->errors());
			}
			else{
				$address =  UsersLaborderAddresses::Where(['user_id' => $data->get('user_id'), 'label_type' => $data->get('label_type')])->first();
				if(!empty($address) && ($data->get('label_type') == 1 || $data->get('label_type') == 2)) {
					UsersLaborderAddresses::Where(['user_id' => $data->get('user_id'), 'label_type' => $data->get('label_type')])->update($address_array);
					$addresses = UsersLaborderAddresses::Where(['user_id' => $data->get('user_id'), 'label_type' => $data->get('label_type')])->first();
				}
				else {
					$addresses =  UsersLaborderAddresses::create($address_array);
				}
				 return $this->sendResponse($addresses,'Your request successfully submitted.',true);
		   }
		}
	 }

		public function getLaborderAddresses(Request $request) {
			if($request->isMethod('post')) {
				$data = Input::json();
                $address = UsersLaborderAddresses::where(['user_id'=>$data->get('user_id')])->orderBy('label_type', 'ASC')->get();
				return $this->sendResponse($address, 'Addresses List Found',$success = true);
			}
		}

		public function deleteLaborderAddress(Request $request) {
			if($request->isMethod('post')) {
				$data = Input::json();
				$address_array=array();
				$address_array['id'] = $data->get('id');
				$validator = Validator::make($address_array, [
					'id'   => 'required',
				]);
				if($validator->fails()) {
					return $this->sendError($validator->errors());
				}
				else {
					$success = true;
					$query = UsersLaborderAddresses::where(['id'=>$address_array['id']])->delete();
					return $this->sendResponse($query, 'Address Deleted Successfully.',$success);
				}
			}
		}
		
			
		public function checkCouponCode(Request $request) {
			 if($request->isMethod('post')) {
				$data = Input::json();
				$user_array=array();
				$user_array['coupon_code'] = $data->get('coupon_code');
				
				$validator = Validator::make($user_array, [
					'coupon_code'   => 'required',
				]);
				if($validator->fails()) {
					return $this->sendResponse('','Coupon Code Is Required',false);
				}
				else {
					 $success = false;
					 $dt = strtotime(date('Y-m-d'));
					 $coupon_data =  Coupons::where('coupon_code',$user_array['coupon_code'])->where(['type'=>1])->where('delete_status',1)->first();
					 if(!empty($coupon_data)){
						 if($coupon_data->coupon_code == '9125615357' || $coupon_data->coupon_code == '9118998982' || $coupon_data->coupon_code == '9608994002' || $coupon_data->coupon_code == '7691079774') {
							$lab = LabOrders::where(["coupon_id"=>$coupon_data->id])->where('status',1)->first();
							if(empty($lab)){
								$user = User::select("id")->where(["mobile_no"=>$coupon_data->coupon_code,'parent_id'=>0])->first();
								if(!empty($user)){
									$cartData = LabCart::where(['user_id' => $user->id])->get();
									if($cartData->count() == 1) {
										if($cartData[0]->product_code == '76') {
											return $this->sendResponse($coupon_data, 'Coupon Applied Successfully.',true);
										}
										else{
											return $this->sendResponse('','Coupon Code is not applicable.',false);
										}
									}
									else{
										return $this->sendResponse('','Coupon Code is not applicable.',false);
									}
								}
							}
							else{
								return $this->sendResponse('','Coupon Code already redeemed, try another one.',false);
							}
						 }
						 else{
							 if($coupon_data->status != '1') {
								return $this->sendResponse('','Coupon Code does Not Active',false);
							 }
							 else if(strtotime($coupon_data->coupon_last_date) < $dt){
								 return $this->sendResponse('','Coupon Code Is Expired',false);
							 }
							 else{
								 return $this->sendResponse($coupon_data, 'Coupon Applied Successfully.',true);
							 }
						 }
					 }
					 else{
						 return $this->sendResponse('','Coupon Code Not Matched.',false);
					 }
				}
			}
		}
		
		public function getUniqueOrderId(Request $request) {
			 if($request->isMethod('post')) {
				$data = Input::json();
				 $success = false;
				 $lab = LabOrders::create();
				 $number = $lab->id."LAB".rand(10,100);
				 LabOrders::where(["id"=>$lab->id])->update(["orderId"=>$number]);
				 if(!empty($lab)) {
					$success = true;
					return $this->sendResponse($number, '',$success);
				 }
			}
		}
		
	
		public function createLabOrder(Request $request) {
			if($request->isMethod('post')) {
				$data = Input::json();
				$user_array=array();
				$user_array['api_key'] = $data->get('apiKey');
				$user_array['orderId'] = $data->get('OrderId');
				$user_array['user_id'] = $data->get('user_id');
				$user_array['address'] = $data->get('Address');
				$user_array['mobile'] = $data->get('Mobile');
				$user_array['email'] = $data->get('Email');
				$user_array['service_type'] = $data->get('ServiceType');
				$user_array['pincode'] = $data->get('Pincode');
				$user_array['address_id'] = $data->get('address_id');
				$user_array['pay_type'] = $data->get('PayType');
				$user_array['bencount'] = $data->get('BenCount');
				$user_array['bendataxml'] = $data->get('BenDataXML');
				$user_array['coupon_id'] = $data->get('coupon_id');
				$user_array['order_by'] = $data->get('OrderBy');
				$user_array['rate'] = $data->get('Rate');
				$user_array['hc'] = $data->get('HC');
				$user_array['reports'] = $data->get('Reports');
				$user_array['ref_code'] = $data->get('RefCode');
				$user_array['total_amt'] = $data->get('total_amt');
				$user_array['discount_amt'] = $data->get('discount_amt');
				$user_array['payable_amt'] = $data->get('payable_amt');
				$user_array['appt_date'] = $data->get('ApptDate');
				$user_array['status'] = $data->get('status');
				$user_array['order_status'] = $data->get('order_status');
				$user_array['product'] = $data->get('Product');
				$user_array['coupon_amt'] = $data->get('coupon_amt');
				$user_array['items'] = $data->get('items');
				$user_array['report_code'] = $data->get('ReportCode');
				$user_array['Gender'] = $data->get('Gender');
				$user_array['Margin'] = (String) $data->get('Margin');
				$user_array['Passon'] = $data->get('Passon');
				$user_array['Remarks'] = $data->get('Remarks');
				$user_array['availWalletAmt'] = $data->get('availWalletAmt');

			  $validator = Validator::make($user_array, [
				// 'api_key'   =>  'required',
				'orderId'   =>  'required',
				'user_id'   =>  'required',
				'address_id'      =>  'required',
				'pay_type'      =>  'required',
				'coupon_id'      =>  'required',
				'order_by'      =>  'required',
				'reports'      =>  'required',
				'total_amt'      =>  'required',
				'discount_amt'      =>  'required',
				'payable_amt'      =>  'required',
				'appt_date'      =>  'required',
				'product'      =>  'required',
				'items'      =>  'required',
			  ]);
			  if($validator->fails()){
				return $this->sendError($validator->errors());
			  }
			  else{
				    $appt_date = null;
					$success = false;
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, "https://velso.thyrocare.cloud/api/BookingMaster/DSABooking");
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($ch, CURLOPT_POST, true);
					if(empty($user_array['report_code'])){
						$user_array['report_code'] = '';
					}
					$order_array = array(
						'ApiKey' => $user_array['api_key'],
						'OrderId' => $user_array['orderId'],
						'Email' => $user_array['email'],
						'Gender' => $user_array['Gender'],
						'Address' => $user_array['address'],
						'Margin' => $user_array['Margin'],
						'Pincode' => $user_array['pincode'],
						'Product' => $user_array['product'],
						'Mobile' => $user_array['mobile'],
						'ServiceType' => $user_array['service_type'],
						'OrderBy' => $user_array['order_by'],
						'Rate' => $user_array['rate'],
						'HC' => $user_array['hc'],
						'ApptDate' => $user_array['appt_date'],
						'Reports' => $user_array['reports'],
						'RefCode' => $user_array['ref_code'],
						'PayType' => $user_array['pay_type'],
						'BenCount' => $user_array['bencount'],
						'BenDataXML' => $user_array['bendataxml'],
						'ReportCode' => $user_array['report_code'],
						'Passon' => $user_array['Passon'],
						'Remarks' => '');
					    $order_data = json_encode($order_array);
						// print_r($order_data);die;
						curl_setopt($ch, CURLOPT_POSTFIELDS, $order_data);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$output = curl_exec($ch);
						curl_close($ch);
						$output = json_decode($output,true);
					//pr($output);
					if($output) {
						if($output['respId'] == 'RES02012') {
							if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])) {
								$appt_date = strtotime($user_array['appt_date']);
							}
							$meta_data = json_encode($user_array);
							LabOrders::where(["orderId"=>$user_array['orderId']])->update([
								'user_id' => $user_array['user_id'],
								'product' => $user_array['product'],
								'address_id' => $user_array['address_id'],
								'pay_type' => $user_array['pay_type'],
								'coupon_id' => $user_array['coupon_id'],
								'order_by' => $user_array['order_by'],
								'order_type' => 1,
								'report_type' => $user_array['reports'],
								'total_amt' => $user_array['total_amt'],
								'discount_amt' => $user_array['discount_amt'],
								'coupon_amt' => $user_array['coupon_amt'],
								'payable_amt' => $user_array['payable_amt'],
								'meta_data' => $meta_data,
								'appt_date' => $appt_date,
								'ref_orderId' => $output['refOrderId'],
								'post_order_meta' => json_encode($output),
								'order_status' => $output['status'],
								'payment_mode_type' => 3,
								'is_free_appt' => 1,
								'status' => 1
							]);
							$lab_id = LabOrders::select("id")->where(["orderId"=>$user_array['orderId']])->first();
							if(count($user_array['items']) > 0){
								foreach($user_array['items'] as $itm){
									$items = LabOrderedItems::create([
										'order_id' => $lab_id->id,
										'product_name' => $itm['name'],
										'cost' => @$itm['rate']['b2C'],
										'discount_amt' => @$itm['rate']['offerRate'],
										'margin' => $itm['margin'],
										'item_type' => $itm['type'],
									]);
								}
							}
							
							/*$ch_app = curl_init();
							curl_setopt($ch_app, CURLOPT_URL, "https://www.thyrocare.com/APIS/ORDER.svc/FixAppointment");
							curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch_app, CURLOPT_POST, true);
							$order_array = array(
								'api_key' => $user_array['api_key'],
								'VisitId' => $user_array['orderId'],
								'Pincode' => $user_array['pincode'],
								'AppointmentDate' => $user_array['appt_date'],
							);
							$order_data = json_encode($order_array);
							curl_setopt($ch_app, CURLOPT_POSTFIELDS, $order_data);
							curl_setopt($ch_app, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
							curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, true);
							$app_output = curl_exec($ch_app);
							curl_close($ch_app);
							$app_output = json_decode($app_output);
							$output['payable_amt'] = $user_array['payable_amt'];
							$output['REPORT_HARD_COPY'] = $user_array['reports'];
							$output['coupon_code'] = "";*/
							$appt_date = date("d-m-Y", strtotime($user_array['appt_date']));
							$appt_time = date("h:i A", strtotime($user_array['appt_date']));
							$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.$user_array['product'].') booking is confirmed with Healthgennie on '.$appt_date.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
							$this->sendSMS($user_array['mobile'],$message,'1707165122333414122');
							
							//$message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.$user_array['product'].') with Thyrocare lab on '.$appt_date.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
							// $this->sendSMS(8905557252,$message,'1707165122295538821');
							// $this->sendSMS(9414430699,$message,'1707165122295538821');
							$success = true;
							LabCart::where(['user_id' => $user_array['user_id']])->delete();
							// updateWallet($user_array['user_id'],3,'lab_reward');
							availWalletAmount($user_array['user_id'],5,$user_array['availWalletAmt']);
							return $this->sendResponse($output,'Lab create Successfully.',$success);
						}
						else{
							return $this->sendResponse($output,'Lab does not created.',$success);
						}
					}
					else{
						return $this->sendResponse($output,'Not Execute',$success);
					}
				}
			}
		}
		public function createLabOrderOnline(Request $request) {
			if($request->isMethod('post')) {
				$data = Input::json();
				$user_array=array();
				$user_array['api_key'] = $data->get('apiKey');
                $user_array['orderId'] = $data->get('OrderId');
                $user_array['user_id'] = $data->get('user_id');
                $user_array['plan_periods_id'] = $data->get('plan_periods_id');
                $user_array['address'] = $data->get('Address');
                $user_array['mobile'] = $data->get('Mobile');
                $user_array['email'] = $data->get('Email');
                $user_array['service_type'] = $data->get('ServiceType');
                $user_array['pincode'] = $data->get('Pincode');
                $user_array['address_id'] = $data->get('address_id');
                $user_array['pay_type'] = $data->get('PayType');
                $user_array['bencount'] = $data->get('BenCount');
                $user_array['bendataxml'] = $data->get('BenDataXML');
                $user_array['coupon_id'] = $data->get('coupon_id');
                $user_array['coupon_code'] = $data->get('coupon_code');
                $user_array['order_by'] = $data->get('OrderBy');
                $user_array['rate'] = (float) $data->get('Rate');
                $user_array['hc'] = $data->get('HC');
                $user_array['reports'] = $data->get('Reports');
                $user_array['ref_code'] = $data->get('RefCode');
                $user_array['total_amt'] = $data->get('total_amt');
                $user_array['discount_amt'] = $data->get('discount_amt');
                $user_array['payable_amt'] = $data->get('payable_amt');
                $user_array['appt_date'] = $data->get('ApptDate');
                $user_array['status'] = $data->get('status');
                $user_array['order_status'] = $data->get('order_status');
                $user_array['product'] = $data->get('Product');
                $user_array['items'] = $data->get('items');
                $user_array['coupon_amt'] = $data->get('coupon_amt');
                $user_array['report_code'] = $data->get('ReportCode');
                $user_array['Gender'] = $data->get('Gender');
                $user_array['Margin'] = (String) $data->get('Margin');
                $user_array['Passon'] = $data->get('Passon');
                $user_array['Remarks'] = $data->get('Remarks');
                $user_array['availWalletAmt'] = $data->get('availWalletAmt');

			  $validator = Validator::make($user_array, [
				// 'api_key'   =>  'required',
				'orderId'   =>  'required',
				'user_id'   =>  'required',
				'address_id'      =>  'required',
				'pay_type'      =>  'required',
				//'coupon_id'      =>  'required',
				'order_by'      =>  'required',
				'reports'      =>  'required',
				'total_amt'      =>  'required',
				'discount_amt'      =>  'required',
				'payable_amt'      =>  'required',
				'appt_date'      =>  'required',
				'product'      =>  'required',
				'items'      =>  'required',
			  ]);
			  if($validator->fails()){
				return $this->sendError($validator->errors());
			  }
			  else{
				    $output = ""; $res = $user_array['orderId'];
					$appt_date = null;
					$success = false;
					if($user_array['coupon_code'] == "HGSUBSCRIBED") {
						$coupon_data =  Coupons::select("id")->where('coupon_code',$user_array['coupon_code'])->first();
						$user_array['coupon_id'] = $coupon_data->id;
					}
					if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])) {
						$appt_date = strtotime($user_array['appt_date']);
					}
					if(empty($user_array['report_code'])){
						$user_array['report_code'] = '';
					}
					$meta_data = json_encode($user_array);
					LabOrders::where(["orderId"=>$user_array['orderId']])->update([
						'user_id' => $user_array['user_id'],
						'product' => $user_array['product'],
						'address_id' => $user_array['address_id'],
						'pay_type' => $user_array['pay_type'],
						'coupon_id' => $user_array['coupon_id'],
						'order_by' => $user_array['order_by'],
						'order_type' => 1,
						'report_type' => $user_array['reports'],
						'total_amt' => $user_array['total_amt'],
						'discount_amt' => $user_array['discount_amt'],
						'coupon_amt' => $user_array['coupon_amt'],
						'payable_amt' => $user_array['payable_amt'],
						'meta_data' => $meta_data,
						'appt_date' => $appt_date,
						'plan_id' => $user_array['plan_periods_id'],
						'payment_mode_type' => 1,
						'status' => 0
					]);
					$lab_id = LabOrders::select("id")->where(["orderId"=>$user_array['orderId']])->first();
					if(count($user_array['items']) > 0) {
						foreach($user_array['items'] as $itm){
							$items = LabOrderedItems::create([
								'order_id' => $lab_id->id,
								'product_name' => $itm['name'],
								'cost' => @$itm['rate']['b2C'],
								'discount_amt' => @$itm['rate']['offerRate'],
								'margin' => $itm['margin'],
								'item_type' => $itm['type'],
							]);
						}
					}
					if($user_array['coupon_code'] == "HGCash") {
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, "https://velso.thyrocare.cloud/api/BookingMaster/DSABooking");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POST, true);
						$order_array = array(
							'ApiKey' => $user_array['api_key'],
							'OrderId' => $user_array['orderId'],
							'Address' => $user_array['address'],
							'Pincode' => $user_array['pincode'],
							'Product' => $user_array['product'],
							'Mobile' => $user_array['mobile'],
							'Email' => $user_array['email'],
							'Gender' => $user_array['Gender'],
							'ServiceType' => $user_array['service_type'],
							'OrderBy' => $user_array['order_by'],
							'Rate' => $user_array['rate'],
							'HC' => $user_array['hc'],
							'ApptDate' => $user_array['appt_date'],
							'Reports' => $user_array['reports'],
							'RefCode' => $user_array['ref_code'],
							'PayType' => $user_array['pay_type'],
							'BenCount' => $user_array['bencount'],
							'BenDataXML' => $user_array['bendataxml'],
							'ReportCode' =>$user_array['report_code'],
							'Passon' =>$user_array['Passon'],
							'Remarks' => ""
						);
						$order_data = json_encode($order_array);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $order_data);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$output = curl_exec($ch);
						curl_close($ch);
						$output = json_decode($output,true);
						if(!empty($output) && $output['respId'] == 'RES02012') {
							$success = true;
							$output['payable_amt'] = $user_array['payable_amt'];
							$output['reportHardCopy'] = $user_array['reports'];
							LabOrders::where(["orderId"=>$user_array['orderId']])->update([
								'status'=>1,
								'is_free_appt' => 1,
								'order_status' => $output['status'],
								'post_order_meta' => json_encode($output),
								'ref_orderId' => $output['refOrderId'],
							]);
							LabOrderTxn::create([
								'order_id' => $user_array['orderId'],
								'tran_mode'=> "Cash",
								'payed_amount'=>$user_array['payable_amt'],
								'tran_status' => "Success",
								'currency' => "INR",
								'trans_date' => date('d-m-Y')
							]);
							LabCart::where(['user_id' => $user_array['user_id']])->delete();
							$output['coupon_code'] = "";
							$appt_date = date("d-m-Y", strtotime($user_array['appt_date']));
							$appt_time = date("h:i A", strtotime($user_array['appt_date']));
							$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.$user_array['product'].') booking is confirmed with Healthgennie on '.$appt_date.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
							$this->sendSMS($user_array['mobile'],$message,'1707165122333414122');
							
							// $message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.$user_array['product'].') with Thyrocare lab on '.$appt_date.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
							// $this->sendSMS(8905557252,$message,'1707165122295538821');
							// $this->sendSMS(9414430699,$message,'1707165122295538821');
							// updateWallet($user_array['user_id'],3,'lab_reward');
							availWalletAmount($user_array['user_id'],5,$user_array['availWalletAmt']);
							/*$ch_app = curl_init();
							curl_setopt($ch_app, CURLOPT_URL, "https://www.thyrocare.com/APIS/ORDER.svc/FixAppointment");
							curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch_app, CURLOPT_POST, true);
							$forder_array = array(
								'api_key' => $user_array['api_key'],
								'VisitId' => $user_array['orderId'],
								'Pincode' => $user_array['pincode'],
								'AppointmentDate' => $user_array['appt_date'],
							);
							$forder_data = json_encode($forder_array);
							curl_setopt($ch_app, CURLOPT_POSTFIELDS, $forder_data);
							curl_setopt($ch_app, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
							curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, true);
							$app_output = curl_exec($ch_app);
							curl_close($ch_app);
							$app_output = json_decode($app_output);*/
						}
					}
					else if($user_array['coupon_code'] == "HGSUBSCRIBED") {
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, "https://velso.thyrocare.cloud/api/BookingMaster/DSABooking");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
						curl_setopt($ch, CURLOPT_POST, true);
						$order_array = array(
							'ApiKey' => $user_array['api_key'],
							'OrderId' => $user_array['orderId'],
							'Address' => $user_array['address'],
							'Pincode' => $user_array['pincode'],
							'Product' => $user_array['product'],
							'Mobile' => $user_array['mobile'],
							'Email' => $user_array['email'],
							'Gender' => $user_array['Gender'],
							'ServiceType' => $user_array['service_type'],
							'OrderBy' => $user_array['order_by'],
							'Rate' => $user_array['rate'],
							'HC' => $user_array['hc'],
							'ApptDate' => $user_array['appt_date'],
							'Reports' => $user_array['reports'],
							'RefCode' => $user_array['ref_code'],
							'PayType' => $user_array['pay_type'],
							'BenCount' => $user_array['bencount'],
							'BenDataXML' => $user_array['bendataxml'],
							'ReportCode' =>$user_array['report_code'],
							'Passon' =>$user_array['Passon'],
							'Remarks' => ""
						);
						$order_data = json_encode($order_array);
						curl_setopt($ch, CURLOPT_POSTFIELDS, $order_data);
						curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
						$output = curl_exec($ch);
						curl_close($ch);
						$output = json_decode($output,true);
						if(!empty($output) && $output['respId'] == 'RES02012') {
							$success = true;
							LabOrders::where(["orderId"=>$user_array['orderId']])->update([
								'status'=>1,
								'is_free_appt' => 1,
								'order_status' => $output['status'],
								'post_order_meta' => json_encode($output),
								'ref_orderId' => $output['refOrderId'],
							]);
							LabOrderTxn::create([
								'order_id' => $user_array['orderId'],
								'tran_mode'=> "Cash",
								'payed_amount'=>$user_array['payable_amt'],
								'tran_status' => "Success",
								'currency' => "INR",
								'trans_date' => date('d-m-Y')
							]);
							$output['coupon_code'] = $user_array['coupon_code'];
							LabCart::where(['user_id' => $user_array['user_id']])->delete();
							PlanPeriods::where('id', $user_array['plan_periods_id'])->update(array(
							   'lab_pkg_remaining' => 0,
							));
							$output['PlanPeriods'] = PlanPeriods::with("UserSubscribedPlans")->where("id",$user_array['plan_periods_id'])->first();
							$appt_date = date("d-m-Y", strtotime($user_array['appt_date']));
							$appt_time = date("h:i A", strtotime($user_array['appt_date']));
							$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.$user_array['product'].') booking is confirmed with Healthgennie on '.$appt_date.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
							$this->sendSMS($user_array['mobile'],$message,'1707165122333414122');
							
							//$message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.$user_array['product'].') with Thyrocare lab on '.$appt_date.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
							//$this->sendSMS(8905557252,$message,'1707165122295538821');
							//$this->sendSMS(9414430699,$message,'1707165122295538821');
							// updateWallet($user_array['user_id'],3,'lab_reward');
							availWalletAmount($user_array['user_id'],5,$user_array['availWalletAmt']);
						}
					}
					else{
						$res = [];
						$res['ORDER_NO'] = $user_array['orderId'];
					}
					$success = true;
					return $this->sendResponse($res,'Lab create Successfully.',$success);
				}
			}
		}
		
		function labCheckout(Request $request) {
              $data = $request->all();
              $params =  json_decode(base64_decode($data['params']));
			  // pr($params);
              $user_array = array();
              $user_array['orderId'] = $params->orderId;
              $user_array['api_key'] = $params->api_key;
              $user_array['pincode'] = $params->pincode;
              $user_array['appt_date'] = $params->appt_date;
              $user_array['payable_amt'] = $params->payable_amt;

              $validator = Validator::make($user_array, [
                'orderId'   =>  'required',
                // 'api_key'   =>  'required',
                // 'pincode'   =>  'required',
                // 'appt_date'   =>  'required',
                'payable_amt'   =>  'required',
              ]);
              if($validator->fails()){
					return $this->sendError($validator->errors());
              }
              else {
				$lab =  LabOrders::where(["orderId"=>$user_array['orderId']])->first();
				// $parameters = [
				   // 'tid' => strtotime("now"),
				   // 'order_id' => $user_array['orderId'],
				   // 'amount' => $user_array['payable_amt'],
				   // 'amount' => 1,
				   // 'merchant_param1' => "HealthGennie Lab Order",
				   // 'merchant_param2' => $user_array['api_key'],
				   // 'merchant_param3' => $user_array['pincode'],
				   // 'merchant_param4' => $user_array['appt_date'],
				// ];
			   // gateway = CCAvenue / others
				// $order = Indipay::gateway('CCAvenue')->prepare($parameters);
				// return Indipay::process($order);
				
				/*$parameters["MID"] = "yNnDQV03999999736874";
				// $parameters["MID"] = "fiBzPH32318843731373"; 			
				$parameters["ORDER_ID"] = $lab->orderId; 
				$parameters["CUST_ID"] = @$lab->order_by; 
				//$parameters["TXN_AMOUNT"] = 1; 
				 $parameters["TXN_AMOUNT"] = $user_array['payable_amt']; 
				$parameters["CALLBACK_URL"] = url('paytmresponse'); 
				$order = Indipay::gateway('Paytm')->prepare($parameters);
				return Indipay::process($order);*/
				
				
				$user = User::where("id",$lab->user_id)->first();
				$mbl = isset($user->mobile_no) ? $user->mobile_no : '0000000000';
				$email = !empty($user->email)   ? $user->email : 'test@mailinator.com';
				$parameters["order"] = $lab->orderId;
				$parameters["amount"] = $user_array['payable_amt'];
				$parameters["amount"] = 1;
				// $parameters["amount"] = 1;
				$parameters["user"] = $lab->order_by;
				$parameters["mobile_number"] = $mbl;
				$parameters["email"] = $email;
				$parameters["callback_url"] = url('paytmresponse');
				$payment = PaytmWallet::with('receive');
				$payment->prepare($parameters);
				return $payment->receive();
			}
		}
		public function getMyLabOrderData(Request $request) {
			$data = Input::json();
			$user_array=array();
			$user_array['order_id'] = $data->get('order_id');

			  $validator = Validator::make($user_array, [
				'order_id'   =>  'required',
			  ]);
			  if($validator->fails()){
					return $this->sendError($validator->errors());
			  }
			  else {
				   $success = false;
				   $lab = LabOrders::select(["post_order_meta","meta_data","type","coupon_id","payable_amt","order_status"])->where(["id"=>$user_array['order_id']])->first();
				   if(!empty($lab)){
				   	   $meta =  json_decode($lab->post_order_meta,true);
				   	   if($lab->type != 0){
                       $meta =  json_decode($lab->meta_data,true);
				   	   }
					   $post_order_meta = $meta;
					   $post_order_meta['coupon_code'] = '';
					   $post_order_meta['payable_amt'] = $lab->payable_amt;
					   $post_order_meta['type'] = $lab->type;
					   $post_order_meta['order_status'] = $lab->order_status;
					   $success = true;
					   return $this->sendResponse($post_order_meta,'Lab data fecth successfully.',$success);
				   }
				   else{
					   return $this->sendResponse($lab,'Lab not fecthed.',$success);
				   }
			  }
		}
		public function getMyLabOrders(Request $request) {
				$data = Input::json();
				$user_array=array();
				$user_array['user_id'] = $data->get('user_id');
				$user_array['api_key'] = $data->get('api_key');

			  $validator = Validator::make($user_array, [
				'user_id'   =>  'required',
				// 'api_key'   =>  'required',
			  ]);
			  if($validator->fails()){
                return $this->sendError($validator->errors());
              }
              else {
				  $labs = LabOrders::with(["LabOrderTxn","Coupons","PlanPeriods.UserSubscribedPlans","LabCompany"])->where(["user_id"=>$user_array['user_id']])->orderBy("id","DESC")->where(["delete_status"=>"1","status"=>1])->whereNotNull("order_status")->get();
				    $success = false;
				    if(count($labs)>0) {
					   foreach($labs as $lab) {
						   $orderId = $lab->orderId;
						   if($lab->type == 0) {
							$user_data = json_decode($lab->meta_data,true);
							$post_meta = json_decode($lab->post_order_meta,true);
							$user_mobile = $user_data['mobile'];
							$post_data = array(
							'ApiKey' => $user_array['api_key'],
							'OrderNo' => $lab->ref_orderId,
							);
							$response_data = getResponseByCurl($post_data,"https://velso.thyrocare.cloud/api/OrderSummary/OrderSummary");
							$lab['thy_order_data'] = $response_data;
							if(!empty($response_data) && isset($response_data['orderMaster'][0])) {
								$status = 0;
								$is_free_appt = 0;
								if($response_data['orderMaster'][0]['status'] == "REPORTED" || $response_data['orderMaster'][0]['status'] == "DONE") {
									if($lab->pay_type != "PREPAID" && $lab->is_free_appt == "0"){
										LabOrders::where(["id"=>$lab->id])->update([
											"order_status" => trim($response_data['orderMaster'][0]['status']),
											"status" => 1,
											"is_free_appt" => 1,
										]);
									}
									else{
										LabOrders::where(["id"=>$lab->id])->update([
											"order_status" => trim($response_data['orderMaster'][0]['status']),
											"status" => 1,
										]);
									}
								}
								else{
									LabOrders::where(["id"=>$lab->id])->update([
										"order_status" => trim($response_data['orderMaster'][0]['status']),
									]);
								}
								$lab->order_status = trim($response_data['orderMaster'][0]['status']);
								if($lab->pay_type == "PREPAID" && $lab->user_id != null && $lab->ref_orderId != null && $lab->status != 1 && $lab->order_status != "CANCELLED") {
									// $this->cancelOrderFunc($lab->ref_orderId,"Payment Not Completed");
									// $lab->order_status = "CANCELLED";
								}
								if($response_data['orderMaster'][0]['status'] == "REPORTED" || $response_data['orderMaster'][0]['status'] == "DONE") {
									$report = LabReports::where(["order_id"=>$orderId])->first();
									if(empty($report)) {
										$user_data = json_decode($lab->meta_data,true);
										$user_mobile = $user_data['mobile'];
										$lead_id = @$post_meta['ORDERRESPONSE']['PostOrderDataResponse'][0]['LEAD_ID'];
										$post_data = ["Apikey"=>$API_KEY,"Displaytype"=>"GETREPORTS","Value"=>$lead_id,"Reporttype"=>"PDF","Mobile"=>$user_mobile];
										$reportData = getResponseByCurl($post_data,"https://b2capi.thyrocare.com/APIs/order.svc/{APIKEY}/GETREPORTS/{VALUE}/{REPORTTYPE}/{MOBILE}/Myreport");
										if(!empty($reportData)) {
											if($reportData['RES_ID'] == "RES0000") {
												$url_downloadPdf = $reportData['URL'];
												LabReports::create([
												  'order_id' => $orderId,
												  'user_id' => $lab->user_id,
												  'report_pdf_name' => $url_downloadPdf,
												]);
											}
										 }
									}
									else{
										LabReports::where("id",$report->id)->update([
										  'report_pdf_name' => $response_data['benMaster'][0]['url'],
										]);
									}
								}
							}
						$lab['is_camp'] = 0;
					   }
					   else{
						   $lab['is_camp'] = 0;
					   }
					   // if($lab->type == 4 || $lab->type == 1) {
						   // $meta_data = json_decode($lab->meta_data,true);
						   // $meta_data['items'] = [['lab_company'=>LabCompany::where('id',$lab->type)->first()]];
						   // $lab['meta_data'] = json_encode($meta_data);
					   // }
					   // $cmpId = $lab->type;
					   // if($lab->type == 0) {
						   // $cmpId = 2;
					   // }
					   // $lab['lab_company'] = LabCompany::where('id',$cmpId)->first();
					   $lab['lab_reports'] = getLabReportById($orderId);
					  }
					  $success = true;
				  }
				 /* $camp_data = CampData::with("user")->where("user_id",$user_array['user_id'])->get();
				  if(count($camp_data) > 0) {
					  foreach($camp_data as $ddt){
                        $postdata = array(
							'ApiKey' => $user_array['api_key'],
							'OrderNo' => $ddt->thy_ref_order_no
							);
							 $post_data = json_encode($postdata);
							$url = "https://velso.thyrocare.cloud/api/OrderSummary/OrderSummary";
							$ch = curl_init($url);
							curl_setopt($ch, CURLOPT_POST, 1);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
							$output = curl_exec($ch);
							curl_close($ch); 
							$output = json_decode($output, true);


						$ddt['thy_order_data'] = $output;
						$ddt['is_camp'] = 1;  
						$ddt['report_url'] = null;
						
						$lead_id = @$output['leadHistoryMaster'][0]['appointOn'][0]['leadId'];
						
						$reportFile = "https://b2capi.thyrocare.com/APIs/order.svc/".$login->API_KEY."/GETREPORTS/".$lead_id."/pdf/".$ddt->user->mobile_no."/Myreport";
						
						// CURL for report
						$ch_r = curl_init();
						curl_setopt($ch_r, CURLOPT_URL, $reportFile);
						curl_setopt($ch_r,CURLOPT_RETURNTRANSFER,true);
						$reportOutput = curl_exec($ch_r);
						curl_close($ch_r); 
						$reportOutput = json_decode($reportOutput, true);
						if(!empty($reportOutput)){
							$ddt['report_url'] = $reportOutput['URL'];
						}
					  }
					  
					  if(count($labs) > 0 ) {
							// $labs = array_merge($labs,$camp_data);
							$labs = $labs->merge($camp_data);
							$labs = $labs->all();
					   }
					   else{
						   $labs = $camp_data;
					   }
					$success = true;
				  }*/
				  return $this->sendResponse($labs,'',$success);
			  }
		}
		
		public function getMyLabReports(Request $request) {
				$data = Input::json();
				$user_array=array();
				$user_array['user_id'] = $data->get('user_id');

			  $validator = Validator::make($user_array, [
				'user_id'   =>  'required',
			  ]);
			  if($validator->fails()){
                return $this->sendError($validator->errors());
              }
              else {
				  $reports = LabReports::where(["user_id"=>$user_array['user_id']])->orderBy("id","DESC")->get();
				  $success = false;
				  if(count($reports)>0) {
					  $success = true;
				  }
				  return $this->sendResponse($reports,'',$success);
			  }
		}
		
		public function cancelLabOrder(Request $request) {
			$data = Input::json();
			$user_array=array();
			$user_array['orderId'] = $data->get('orderId');

		  $validator = Validator::make($user_array, [
			'orderId'   =>  'required',
		  ]);
		  if($validator->fails()){
			return $this->sendError($validator->errors());
		  }
		  else {
			  $app_output = $this->cancelOrderFunc($user_array['orderId'],'Cancel By User');
			  return $this->sendResponse($app_output,'Lab Test Cancelled Successfully',true);
		  }
		}
		
		public function cancelOrderFunc($orderId=null, $cancel_reason= null){
			$ch_app = curl_init();
			curl_setopt($ch_app, CURLOPT_URL, "https://www.thyrocare.com/APIS/ORDER.svc/cancelledorder");
			curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch_app, CURLOPT_POST, true);
			$order_array = array(
				'OrderNo' => $orderId,
				'VisitId' => $orderId,
				'Status' =>  2,
			);
			$order_data = json_encode($order_array);
			curl_setopt($ch_app, CURLOPT_POSTFIELDS, $order_data);
			curl_setopt($ch_app, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, true);
			$app_output = curl_exec($ch_app);
			curl_close($ch_app);
			$app_output = json_decode($app_output);
			
			LabOrders::where(["ref_orderId"=>$orderId])->update([
				"order_status" => "CANCELLED",
				'is_free_appt' => 0,
			]);
			return $app_output;
		}
		public function getLabCartData(Request $request) {
				$data = Input::json();
				$user_array=array();
				$user_array['user_id'] = $data->get('user_id');

			  $validator = Validator::make($user_array, [
				'user_id'   =>  'required',
			  ]);
			  if($validator->fails()){
                return $this->sendError($validator->errors());
              }
              else {
				  $labCart = getLabCartNew($user_array['user_id']);
				  $success = false;
				  if(count($labCart)>0) {
					  $success = true;
				  }
				  return $this->sendResponse($labCart,'',$success);
			  }
		}
		
		public function addLabCartData(Request $request) {
			$data = json_decode($request->getContent(), true); // Parse JSON input into an array
			$user_array = [];
			$user_array['user_id'] = $data['user_id'] ?? null;
			$user_array['products'] = $data['products'] ?? [];

			  $validator = Validator::make($user_array, [
				'user_id'   =>  'required',
			  ]);
			  if($validator->fails()){
                return $this->sendError($validator->errors());
              }
            else {
				if(count($user_array['products']) > 0 ) {
					//LabCart::where(['user_id'=>$user_array['user_id']])->delete();
					foreach($user_array['products'] as $product) {
						if($product['type'] == "OFFER" && $product['compid']== '0') {
							LabCart::where(['user_id'=>$user_array['user_id'],'product_type' => $product['type']])->delete();
						}
						$lab_exists = LabCart::where(['user_id'=>$user_array['user_id'],'product_name' => $product['name'], 'product_code' => $product['code']])->count();
						if($lab_exists == 0) {
							$LabCart = LabCart::create([
								'user_id' => $user_array['user_id'],
								
								'type' => ($product['compid']!= "")?$product['compid']:0,
								'product_name' => $product['name'],
								'product_code' => $product['code'],
								'product_type' => $product['type'],
							]);
						}
					}
				}
				return $this->sendResponse('','Updated Successfully',true);
			}
		}
		
		public function deleteLabCartData(Request $request) {
				$data = json_decode($request->getContent(), true);
				$user_array=array();			
				$user_array['user_id'] = $data['user_id'];
				$user_array['product_name'] = $data['product_name'] ?? null;
				$user_array['product_code'] = $data['product_code'];
				$user_array['lab_cart_type'] = @$data['lab_cart_type'];

			  $validator = Validator::make($user_array, [
				'user_id'   =>  'required',
			  ]);
			  if($validator->fails()){
                return $this->sendError($validator->errors());
              }
            else {
				if($user_array['lab_cart_type'] == "package") {
					LabCart::where(['id' => $user_array['product_code']])->delete();
				}
				else{
					LabCart::where(['user_id' => $user_array['user_id'], 'product_code' => $user_array['product_code']])->delete();
				}
				return $this->sendResponse('','Deleted successfully',true);
			}
		}
		
		public function GetAppointmentSlots(Request $request) {
			$data = Input::json();
			$user_array=array();
			$user_array['ApiKey'] = $data->get('ApiKey');
			$user_array['Pincode'] = $data->get('Pincode');
			$user_array['Date'] = $data->get('Date');
			  $validator = Validator::make($user_array, [
				'ApiKey'   =>  'required',
				'Pincode'   =>  'required',
				'Date'   =>  'required',
			  ]);
			  if($validator->fails()){
                return $this->sendError($validator->errors());
              }
            else {
				$postdata = array(
				'ApiKey' => $user_array['ApiKey'],
				'Pincode' => $user_array['Pincode'],
				'Date' => $user_array['Date'],
				);
				$post_data = json_encode($postdata);
				$url = "https://velso.thyrocare.cloud/api/TechsoApi/GetAppointmentSlots";
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$response = curl_exec($ch);
				if($response){
					$resData = json_decode($response);
					return $this->sendResponse($resData, '',true);
				}
				else{
					return $this->sendError('Api Does not execute');
				}
			}
		}
		
		public function PincodeAvailability(Request $request) {
			$data = Input::json();
			$user_array=array();
			$user_array['ApiKey'] = $data->get('ApiKey');
			$user_array['Pincode'] = $data->get('Pincode');
			  $validator = Validator::make($user_array, [
				'ApiKey'   =>  'required',
				'Pincode'   =>  'required',
			  ]);
			  if($validator->fails()){
                return $this->sendError($validator->errors());
              }
            else {
				$postdata = array(
				'ApiKey' => $user_array['ApiKey'],
				'Pincode' => $user_array['Pincode'],
				);
				$post_data = json_encode($postdata);
				$url = "https://velso.thyrocare.cloud/api/TechsoApi/PincodeAvailability";
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$response = curl_exec($ch);
				if($response){
					$resData = json_decode($response);
					return $this->sendResponse($resData, '',true);
				}
				else{
					return $this->sendError('Api Does not execute');
				}
			}
		}

		public function ViewCart(Request $request) {
			$data = Input::json();
			$user_array=array();
			$user_array['ApiKey'] = $data->get('key');
			$user_array['product'] = $data->get('product');
			  $validator = Validator::make($user_array, [
				'ApiKey'   =>  'required',
				//'Pincode'   =>  'required',
			  ]);
			  if($validator->fails()){
                return $this->sendError($validator->errors());
              }
            else {
				$postdata = array(
				'ApiKey' => $user_array['ApiKey'],
				'Products' => implode(',',$user_array['product']),
				'Rates' =>  $data->get('product_price'),
				'ClientType' => 'PUBLIC',
				'Mobile' =>  $data->get('mobile_no'),
				'BenCount' =>  $data->get('ben_count'),
				'Report' =>  $data->get('report_type'),
				'Discount' => '',
				'Coupon' => '',
				);
				$post_data = json_encode($postdata);
				$url = "https://velso.thyrocare.cloud/api/CartMaster/DSAViewCartDTL";
				$ch = curl_init($url);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
				$response = curl_exec($ch);
				if($response){
					$resData = json_decode($response);
					return $this->sendResponse($resData, '',true);
				}
				else{
					return $this->sendError('Api Does not execute');
				}
			}
		}
		
		public function getLabByName(Request $request) {
			$data = Input::json();
			$user_array=array();
			$user_array['lab_name'] = $data->get('lab_name');
			$validator = Validator::make($user_array, [
				'lab_name'   =>  'required',
			]);
			if($validator->fails()){
                return $this->sendError($validator->errors());
            }
            else {
				 $query = LabCollection::with("DefaultLabs","LabCompany")->where('status', '=', '1')->where('delete_status', '=', '1');
				 $search = $user_array['lab_name'];
			     $query->whereHas("DefaultLabs",function($q) use($search) {
					$q->where(DB::raw('concat(default_labs.title," ",IFNULL(default_labs.short_name,""))') , 'like', '%'.$search.'%');
			     });
				 $labs = $query->orderBy('id', 'desc')->get();
				 return $this->sendResponse($labs,'',true);
			}
		}
		public function createCustomLabOrder(Request $request) {
			if($request->isMethod('post')) {
				$data = Input::json()->all();
				$user_array=array();
				$user_array['orderId'] = @$data['OrderId'];
				$user_array['user_id'] = @$data['user_id'];
				$user_array['address'] = @$data['Address'];
				$user_array['mobile'] = @$data['Mobile'];
				$user_array['email'] = @$data['Email'];
				// $user_array['service_type'] = $data->get('ServiceType');
				$user_array['pincode'] = @$data['Pincode'];
				$user_array['address_id'] = @$data['address_id'];
				$user_array['pay_type'] = @$data['PayType'];
				// $user_array['bencount'] = $data->get('BenCount');
				// $user_array['bendataxml'] = $data->get('BenDataXML');
				$user_array['coupon_id'] = @$data['coupon_id'];
				$user_array['order_by'] = @$data['OrderBy'];
				$user_array['rate'] = @$data['Rate'];
				// $user_array['hc'] = $data->get('HC');
				$user_array['reports'] = @$data['Reports'];
				$user_array['ref_code'] = @$data['RefCode'];
				$user_array['total_amt'] = @$data['total_amt'];
				$user_array['discount_amt'] = @$data['discount_amt'];
				$user_array['payable_amt'] = @$data['payable_amt'];
				$user_array['appt_date'] = @$data['ApptDate'];
				$user_array['status'] = @$data['status'];
				$user_array['order_status'] = @$data['order_status'];
				$user_array['product'] = @$data['Product'];
				$user_array['coupon_amt'] = @$data['coupon_amt'];
				$user_array['items'] = @$data['items'];
				$user_array['report_code'] = @$data['ReportCode'];
				$user_array['Gender'] = @$data['Gender'];
				$user_array['age'] = @$data['age'];
				$user_array['cmpId'] = @$data['cmpId'];
				$user_array['availWalletAmt'] = @$data['availWalletAmt'];
				// $user_array['Margin'] = (String) $data->get('Margin');
				// $user_array['Passon'] = $data->get('Passon');
				// $user_array['Remarks'] = $data->get('Remarks');

			  $validator = Validator::make($user_array, [
				// 'api_key'   =>  'required',
				// 'orderId'   =>  'required',
				'user_id'   =>  'required',
				'address_id'      =>  'required',
				'pay_type'      =>  'required',
				// 'coupon_id'      =>  'required',
				'order_by'      =>  'required',
				'reports'      =>  'required',
				'total_amt'      =>  'required',
				'discount_amt'      =>  'required',
				'payable_amt'      =>  'required',
				// 'appt_date'      =>  'required',
				'product'      =>  'required',
				'items'      =>  'required',
			  ]);
			  if($validator->fails()){
				return $this->sendError($validator->errors());
			  }
			  else{
				    $appt_date = null;
					$success = false;
					if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])) {
						$appt_date = strtotime($user_array['appt_date']);
					}
					// pr($user_array);
					$meta_data = json_encode($user_array);
					LabOrders::where(["orderId"=>$user_array['orderId']])->update([
						'user_id' => $user_array['user_id'],
						'product' => $user_array['product'],
						'address_id' => $user_array['address_id'],
						'pay_type' => $user_array['pay_type'],
						'coupon_id' => $user_array['coupon_id'],
						'order_by' => $user_array['order_by'],
						'type' => !empty($user_array['cmpId']) ? $user_array['cmpId'] : 1,
						'order_type' => 1,
						'report_type' => $user_array['reports'],
						'total_amt' => $user_array['total_amt'],
						'discount_amt' => $user_array['discount_amt'],
						'coupon_amt' => $user_array['coupon_amt'],
						'payable_amt' => $user_array['payable_amt'],
						'meta_data' => $meta_data,
						'appt_date' => $appt_date,
						'status' => 1,
						'is_free_appt' => 1,
						'payment_mode_type' => 3,
						'order_status' => 'YET TO CONFIRM',
					]);
					$lab_id = LabOrders::where(["orderId"=>$user_array['orderId']])->first();
					// if(count($user_array['items']) > 0){
						// foreach($user_array['items'] as $itm){
							// $items = LabOrderedItems::create([
								// 'order_id' => $lab_id->id,
								// 'user_lab_id' => $itm['lab_id'],
								// 'product_name' => $itm['default_labs']['title'],
								// 'cost' => @$itm['cost'],
								// 'discount_amt' => @$itm['offer_rate'],
								// 'item_type' => "CUSTOM",
							// ]);
						// }
					// }
					if(count($user_array['items']) > 0) {
						foreach($user_array['items'] as $itm) {
							if($itm['lab_cart_type'] == 'package') {
								$items = LabOrderedItems::create([
									'package_id' => $itm['id'],
									'order_id' => $lab_id->id,
									// 'user_lab_id' => $raw['id'],
									'product_name' => $itm['default_labs']['title'],
									'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
									'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
									'item_type' => "CUSTOM",
								]);
								$product_name[] = $itm['title'];
							}
							else{
								$items = LabOrderedItems::create([
									'order_id' => $lab_id->id,
									'user_lab_id' => $itm['id'],
									'product_name' => $itm['default_labs']['title'],
									'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
									'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
									'item_type' => "CUSTOM",
								]);
								$product_name[] = $itm['default_labs']['title'];
							}
						}
					}
					// updateWallet($user_array['user_id'],3,'lab_reward');
					availWalletAmount($user_array['user_id'],5,$user_array['availWalletAmt']);
					$appt_date = date("d-m-Y", strtotime($user_array['appt_date']));
					$appt_time = date("h:i A", strtotime($user_array['appt_date']));
					$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.$user_array['product'].') booking is confirmed with Healthgennie on '.$appt_date.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
					$this->sendSMS($user_array['mobile'],$message,'1707165122333414122');
					
					// $message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.$user_array['product'].') with Reliable lab on '.$appt_date.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
					// $this->sendSMS(8905557252,$message,'1707165122295538821');
					// $this->sendSMS(9414430699,$message,'1707165122295538821');
					// $this->sendSMS(8690006254,$message,'1707165122295538821');
					$success = true;
					LabCart::where(['user_id' => $user_array['user_id']])->delete();
					return $this->sendResponse($lab_id,'Lab create Successfully.',$success);
				}
			}
		}
		public function createCustomLabOrderOnline(Request $request) {
			if($request->isMethod('post')) {
				$data = Input::json()->all();
				$user_array=array();
				$user_array['orderId'] = @$data['OrderId'];
				$user_array['user_id'] = @$data['user_id'];
				$user_array['address'] = @$data['Address'];
				$user_array['mobile'] = @$data['Mobile'];
				$user_array['email'] = @$data['Email'];
				// $user_array['service_type'] = $data->get('ServiceType');
				$user_array['pincode'] = @$data['Pincode'];
				$user_array['address_id'] = @$data['address_id'];
				$user_array['pay_type'] = @$data['PayType'];
				// $user_array['bencount'] = $data->get('BenCount');
				// $user_array['bendataxml'] = $data->get('BenDataXML');
				$user_array['coupon_id'] = @$data['coupon_id'];
				$user_array['order_by'] = @$data['OrderBy'];
				$user_array['rate'] = @$data['Rate'];
				// $user_array['hc'] = $data->get('HC');
				$user_array['reports'] = @$data['Reports'];
				$user_array['ref_code'] = @$data['RefCode'];
				$user_array['total_amt'] = @$data['total_amt'];
				$user_array['discount_amt'] = @$data['discount_amt'];
				$user_array['payable_amt'] = @$data['payable_amt'];
				$user_array['appt_date'] = @$data['ApptDate'];
				$user_array['status'] = @$data['status'];
				$user_array['order_status'] = @$data['order_status'];
				$user_array['product'] = @$data['Product'];
				$user_array['coupon_amt'] = @$data['coupon_amt'];
				$user_array['items'] = @$data['items'];
				$user_array['report_code'] = @$data['ReportCode'];
				$user_array['Gender'] = @$data['Gender'];
				$user_array['age'] = @$data['age'];
				$user_array['availWalletAmt'] = @$data['availWalletAmt'];
				$user_array['cmpId'] = @$data['cmpId'];
				// $user_array['Margin'] = (String) $data->get('Margin');
				// $user_array['Passon'] = $data->get('Passon');
				// $user_array['Remarks'] = $data->get('Remarks');

			  $validator = Validator::make($user_array, [
				// 'api_key'   =>  'required',
				// 'orderId'   =>  'required',
				'user_id'   =>  'required',
				'address_id'      =>  'required',
				'pay_type'      =>  'required',
				// 'coupon_id'      =>  'required',
				'order_by'      =>  'required',
				'reports'      =>  'required',
				'total_amt'      =>  'required',
				'discount_amt'      =>  'required',
				'payable_amt'      =>  'required',
				// 'appt_date'      =>  'required',
				'product'      =>  'required',
				'items'      =>  'required',
			  ]);
			  if($validator->fails()){
				return $this->sendError($validator->errors());
			  }
			  else{
				    $output = ""; $res = $user_array['orderId'];
					$appt_date = null;
					$success = false;
					// if($user_array['coupon_code'] == "HGSUBSCRIBED") {
						// $coupon_data =  Coupons::select("id")->where('coupon_code',$user_array['coupon_code'])->first();
						// $user_array['coupon_id'] = $coupon_data->id;
					// }
					if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])) {
						$appt_date = strtotime($user_array['appt_date']);
					}
					$meta_data = json_encode($user_array);
					LabOrders::where(["orderId"=>$user_array['orderId']])->update([
						'user_id' => $user_array['user_id'],
						'product' => $user_array['product'],
						'address_id' => $user_array['address_id'],
						'pay_type' => $user_array['pay_type'],
						'coupon_id' => $user_array['coupon_id'],
						'order_by' => $user_array['order_by'],
						'type' => !empty($user_array['cmpId']) ? $user_array['cmpId'] : 1,
						'order_type' => 1,
						'report_type' => $user_array['reports'],
						'total_amt' => $user_array['total_amt'],
						'discount_amt' => $user_array['discount_amt'],
						'coupon_amt' => $user_array['coupon_amt'],
						'payable_amt' => $user_array['payable_amt'],
						'meta_data' => $meta_data,
						'appt_date' => $appt_date,
						'payment_mode_type' => 1,
						// 'status' => 0,
						// 'order_status' => 'YET TO CONFIRM',
					]);
					$lab_id = LabOrders::select("id")->where(["orderId"=>$user_array['orderId']])->first();
					// if(count($user_array['items']) > 0) {
						// foreach($user_array['items'] as $itm) {
							// $items = LabOrderedItems::create([
								// 'order_id' => $lab_id->id,
								// 'user_lab_id' => $itm['lab_id'],
								// 'product_name' => $itm['default_labs']['title'],
								// 'cost' => @$itm['cost'],
								// 'discount_amt' => @$itm['offer_rate'],
								// 'margin' => $itm['margin'],
								// 'item_type' => "CUSTOM",
							// ]);
						// }
					// }
					if(count($user_array['items']) > 0) {
						foreach($user_array['items'] as $itm) {
							if($itm['lab_cart_type'] == 'package') {
								$items = LabOrderedItems::create([
									'package_id' => $itm['id'],
									'order_id' => $lab_id->id,
									// 'user_lab_id' => $raw['id'],
									'product_name' => $itm['default_labs']['title'],
									'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
									'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
									'item_type' => "CUSTOM",
								]);
								$product_name[] = $itm['title'];
							}
							else{
								$items = LabOrderedItems::create([
									'order_id' => $lab_id->id,
									'user_lab_id' => $itm['id'],
									'product_name' => $itm['default_labs']['title'],
									'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
									'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
									'item_type' => "CUSTOM",
								]);
								$product_name[] = $itm['default_labs']['title'];
							}
						}
					}
					$success = true;
					return $this->sendResponse($res,'Lab create Successfully.',$success);
				}
			}
		}

    public function labRequestViaPrescription(Request $request) {
    	if($request->isMethod('post')) {
			$data = $request->all();
			$validator = Validator::make($data, [
				'user_id' => 'required',
				'pres_id' => 'required'
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$res = LabRequests::create(['user_id'=>$data['user_id'],'mobile_no'=>$data['mobile_no'],'pres_id'=>$data['pres_id']]);
				$message = urlencode('Dear '.getUserName($data['user_id']).', Your lab test request has been received by Health Gennie. Our team will contact you for the details shortly. Thanks Team Health Gennie');
				$this->sendSMS($data['mobile_no'],$message,'1707165122309302900');
				 
				$req_date = date("Y-m-d", strtotime($res->created_at));
				$req_time = date("h:i:s A", strtotime($res->created_at));
				// $message = urlencode('This patient '.getUserName($data['user_id']).' has request a lab test on '.$req_date.' at '.$req_time.'. Patient Mobile: '.$data['mobile_no'].'. Thanks, Team Health Gennie');
				// $this->sendSMS(implode(",",getSetting("support_contact_numbers")),$message,'1707165268637387346');
				// $this->sendSMS(8690006254,$message,'1707165268637387346');
				return $this->sendResponse($res , 'Prescription Image Uploaded Successfully.',true);
			}
		 }
	}
	public function getDefLabByName(Request $request) {
		$data = Input::json();
		$user_array=array();
		$user_array['search'] = $data->get('search');
		$validator = Validator::make($user_array, [
			'search'   =>  'required',
		]);
		if($validator->fails()){
			return $this->sendError($validator->errors());
		}
		else {
			$labs = DefaultLabs::with('LabCollection')->where('status', '=', '1')->where('delete_status', '=', '1')->where(DB::raw('concat(default_labs.title," ",IFNULL(default_labs.short_name,""))') , 'like', '%'.$user_array['search'].'%')
			->get();
			$arr = [];
			if(count($labs)>0){
				foreach($labs as $raw){
					if(!empty($raw->LabCollection)){
						$arr[] = $raw;
					}
				}
			}
			return $this->sendResponse($arr,'',true);
		}
	}
	public function getLabsByIds(Request $request) {
		$data = Input::json()->all();
		$user_array=array();
		$user_array['ids'] = @$data['ids'];
		$validator = Validator::make($user_array, [
			'ids'   =>  'required',
		]);
		if($validator->fails()){
			return $this->sendError($validator->errors());
		}
		else {
			 $comapanies = getLabCompanies();
			 $ids = $user_array['ids'];
			 $idsT = [];
			 if(count($ids)>0){
				 foreach($ids as $raw){
					$idsT[] =  $raw['title'];
				 }
			 }
			 foreach($comapanies as $raw) {
				$raw['icon_url'] = url("/")."/public/others/company_logos/".$raw->icon;
				$raw['labs'] = $this->getLabDataByComapny($raw->id,$idsT);
			 }
			 return $this->sendResponse($comapanies,'',true);
		}
	}
	
	public function getLabDataByComapny($comapny,$ids){
		if($comapny == 2) {
			$products = [];
			$cartProducts = [];
			// $Allproduct = File::get(public_path('thyrocare-data/All.txt'));
			// $Allproduct = json_decode($Allproduct,true);
			// $arr1 = $Allproduct['master']['offer'];

			// $ofr_arr = File::get(public_path('thyrocare-data/Offer.txt'));
			// $arr2 = json_decode($ofr_arr, true);
			// $products = array_unique(@array_merge($arr1,$arr2),SORT_REGULAR);
			$products = getAllThyrocareData();
			foreach($ids as $id){
				//$id = getTestNameByLabName($id);
				if(count($products) > 0) {
					foreach ($products as $key => $product) {
					  if (!empty($cartProducts)) {
						if ($product['common_name'] == $id && array_search($product['common_name'], array_column($cartProducts, 'common_name')) === false) {
						    array_push($cartProducts, $product);
						}
					  }
					  else {
						if ($product['common_name'] == $id) {
						  $cartProducts[] =  $product;
						}
					  }
				   }
				}
			}
			$labs = $cartProducts;
		}
		else {
		 $query = LabCollection::with("DefaultLabs")->where(['company_id'=>$comapny,'status'=> '1','delete_status'=> '1']);
		 $query->whereHas("DefaultLabs",function($q) use($ids) {
			$q->whereIn('title',$ids);
		 });
		 $labs = $query->orderBy('id', 'desc')->get();
		}
		return $labs;
	}
	public function getLabCompanies(Request $request) {
		$cmpny = LabCompany::where('status','1')->get();
		if($cmpny->count() > 0) {
			foreach($cmpny as $raw) {
				$raw['icon'] = url("/")."/public/others/company_logos/".$raw->icon;
			}
		}
		return $this->sendResponse($cmpny, '',true);
	}
	public function getLabPackage(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['comp_id']=$data->get('comp_id');
		$validator = Validator::make($user_array, [
			'comp_id' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			if($user_array['comp_id'] == "2") {
				$product = getThyrocareData("OFFER");
				// $product = File::get(public_path('thyrocare-data/Offer.txt'));
				// $product = (array) json_decode($product);
				// $rate = array();
				// foreach($product as $key => $row) {
					// $rate[$key] = $row->rate->offerRate;
				// }
				// array_multisort($rate, SORT_ASC, $product);
			}
			else {
				$product = LabPackage::with('DefaultLabs')->where('company_id',$user_array['comp_id'])->where(['status'=>1,'delete_status'=>1])->orderBy('id','desc')->get();
				if($product->count()>0) {
					foreach($product as $raw){
						$raw['image'] = url("/")."/public/lab-package-icon/".$raw->image;
					}
				}
			}
			return $this->sendResponse($product, '',true);
		}
	}
	public function checkLabPinCode(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['comp_id']=$data->get('comp_id');
		$user_array['pincode']=$data->get('pincode');
		$validator = Validator::make($user_array, [
			'comp_id' => 'required',
			'pincode' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$res = LabPincode::where(['company_id'=>$user_array['comp_id'],'pincode'=>$user_array['pincode']])->count() > 0 ? true : false;
			return $this->sendResponse('', '',$res);
		}
	}

	public function getLabTestSlots(Request $request){
        $data=Input::json();
  		$user_array=array();
		$user_array['comp_id'] = $data->get('comp_id');
		$user_array['date'] = $data->get('date');
		//$user_array['type'] = $data->get('type');
		$success = false;
		$opd_timings = array();
		$current_date = $user_array['date'];
		//$nameOfDay = date('N', strtotime($current_date));
		
		//if($nameOfDay == "7"){
		//	$nameOfDay = "0";
		//}
		$selected_date = date('d-m-Y', strtotime($current_date));
        $labTimings =  LabCompany::where(['id'=>$user_array['comp_id']])->first();

		if(!empty($labTimings)) {
			$opd_time = array();
			$increment = 900;
			if(!empty($labTimings->slot_duration)){
				$increment = $labTimings->slot_duration*60;
			}
			$time_slot = array();
			if(!empty($labTimings->start_time)){
				$startTime = strtotime($labTimings->start_time);
				while($startTime <= strtotime($labTimings->end_time)) {
				$time_slot[] = $startTime;
				$startTime += $increment;
				}	
			}
			$from = "";
			$slot_array = array();
			// $arr = [];
			if(count($time_slot)>0){
				foreach($time_slot as $k=>$val){
					//$from = date('Y-m-d H:i:s',strtotime($selected_date." ".date("h:i A",$val)));
				    if(strtotime($selected_date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
					    $gg = $val;
						$gg += $labTimings->slot_duration*60;
						$val = date("H:i",$val).' - '.date("H:i",$gg);
						$slot_array[] = $val;
					    }				
				}
				/*foreach($time_slot as $k=>$val){
					$from = date('Y-m-d H:i:s',strtotime($selected_date." ".date("h:i A",$val)));
					if($val < strtotime('12:00')) {
						if(strtotime($selected_date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
							    $gg = $val;
							   $gg += $labTimings->slot_duration;
								$val = array("time"=>date("h:i A",$val).'-'.date("h:i A",$time_slot[$k+1]),"book"=>"0");
							
						}
						else{
							$gg = $val;
							$gg += $labTimings->slot_duration*60;
							$val = array("time"=>date("h:i A",$val).'-'.date("h:i A",$gg),"book"=>"1");
						}
						$slot_array["M"][] = $val;
					}
					if($val >= strtotime('12:00') && $val < strtotime('16:00')) {
						if(strtotime($selected_date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
							
								$val = array("time"=>date("h:i A",$val),"book"=>"0");
							
						}
						else{
							$val = array("time"=>date("h:i A",$val),"book"=>"1");
						}
						$slot_array["A"][] = $val;
					}
					if($val >= strtotime('16:00') && $val < strtotime('24:00')) {
						if(strtotime($selected_date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
							    $val = array("time"=>date("h:i A",$val),"book"=>"0");
						}
						else{
							$val = array("time"=>date("h:i A",$val),"book"=>"1");
						}
						$slot_array["E"][] = $val;
					}
				}*/ 
			}
			//pr($slot_array);
			//$doctor['timing_slots'] = $slot_array;
			return $this->sendResponse($slot_array,'Lab slots Available.',true);
		}
		else{
			return $this->sendError('Slots does not exist');
		}
	}
	public function getLabPackageById(Request $request) {
		$data=Input::json();
		$user_array=array();
		$user_array['pack_id']=$data->get('pack_id');
		$validator = Validator::make($user_array, [
			'pack_id' => 'required'
		]);
		if($validator->fails()){
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$product = LabPackage::where('id',$user_array['pack_id'])->where('delete_status',1)->first();
			if(!empty($product)) {
				$product['image'] = url("/")."/public/lab-package-icon/".$product->image;
			}
			return $this->sendResponse($product, '',true);
		}
	}
	public function fetchLabDetails(Request $request) {
		$data = Input::json()->all();
		$user_array=array();
		$user_array['user_id']= @$data['user_id'];
		$user_array['labs']= $data['labs'];
		$validator = Validator::make($user_array, [
			'user_id' => 'required',
			'labs' => 'required'
		]);
		if($validator->fails()) {
			return $this->sendError('Validation Error.', $validator->errors());
		}
		else{
			$labs = [];
			if(count($user_array['labs']) > 0){
				foreach($user_array['labs'] as $itm) {
					 $title = $itm['title'];
					 $query = LabCollection::with("DefaultLabs","LabCompany")->where('company_id',3)->where('status', '=', '1')->where('delete_status', '=', '1');
					 $query->whereHas("DefaultLabs",function($q) use($title) {
						$q->where(DB::raw('concat(default_labs.title," ",IFNULL(default_labs.short_name,""))') , 'like', '%'.$title.'%');
					 });
					 $lab = $query->orderBy('id', 'desc')->first();	
					if(!empty($lab)){
						$labs[] = $lab;
					}
				}
			}
			return $this->sendResponse($labs, 'Data Fetched',true);
		}
	}
}
