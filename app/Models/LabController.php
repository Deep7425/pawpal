<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Doctors;
/**ehr db models */
use App\Models\ehr\User as ehrUser;
use App\Models\ehr\PracticeDetails;
use App\Models\ehr\DoctorsInfo;
use App\Models\ehr\StaffsInfo;
use App\Models\ehr\RoleUser;
use App\Models\ehr\OpdTimings;
use App\Models\ehr\Plans;
use App\Models\ehr\CityLocalities;
use App\Models\ehr\ManageTrailPeriods;
use App\Models\ehr\PatientRagistrationNumbers;
use App\Models\ehr\Patients;
use App\Models\ehr\EmailTemplate;
use App\Models\ehr\Appointments;
use App\Models\ehr\PracticeDocuments;
use App\Models\ehr\AppointmentOrder;
use App\Models\Admin\SymptomsSpeciality;
use App\Models\ThyrocarePackageGroup;
use App\Models\UsersLaborderAddresses;

use App\Models\Admin\Symptoms;
use App\Models\Admin\SymptomTags;
use App\Models\OtpPracticeDetails;
use App\Models\Speciality;
use App\Models\PatientFeedback;
use App\Models\Coupons;
use App\Models\LabOrderTxn;
use App\Models\LabOrderedItems;
use App\Models\LabOrders;
use App\Models\LabReports;
use App\Models\LabCart;
use App\Models\PlanPeriods;
use App\Models\CampData;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
class LabController extends Controller
{

		public function __construct()
		{
			if(!Session::has('API_KEY'))
			{
				$this->setSessionAPIKey();
			}
		}

		public function setSessionAPIKey(){
			 if(!Session::has('API_KEY')) {
				Session::put('API_KEY', "WFDBtPSian4GS9rQ@ySDy0APVhdWE4cNf0unh8fpQNYcKLWvXyFzEw==");
				Session::save();
				// $LoginData = file_get_contents("https://www.thyrocare.com/APIS/common.svc/7738943013/123456789/portalorders/DSA/login");
				 // $LoginData = @file_get_contents("https://www.thyrocare.com/APIS/common.svc/9414061829/256EE3/portalorders/DSA/login");
				 // if(!empty($LoginData)){
					// $LoginData = json_decode($LoginData);
					// Session::put('API_KEY', $LoginData->API_KEY);
					// Session::put('API_KEY', "WFDBtPSian4GS9rQ@ySDy0APVhdWE4cNf0unh8fpQNYcKLWvXyFzEw==");
					// Session::save();
				 // }
			 }
		 }
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

		public function LabDashboard(Request $request){

			//  if(empty(session()->get('API_KEY')) || session()->get('API_KEY') == null)
 			// {
 			// 	$LoginData = file_get_contents("https://www.thyrocare.com/APIS/common.svc/7738943013/123456789/portalorders/DSA/login");
 			// 	$LoginData = json_decode($LoginData);
 			// 	Session::put('API_KEY', $LoginData->API_KEY);
 			// 	Session::save();
 			// }

			if(Auth::user() == null){
				Session::put('loginFrom', '1');
			}
			Session::forget('search_from_lab');
			$groups = ThyrocarePackageGroup::where(['delete_status'=>1, 'status' => 1])->orderBy('sequence','ASC')->get();
			return view($this->getView('lab.lab-dashboard'),['groups'=>$groups]);
	 	}

		public function allPackages(Request $request, $type){
			$groups = ThyrocarePackageGroup::where(['delete_status'=>1, 'status' => 1])->orderBy('sequence','ASC')->get();
			 return view($this->getView('lab.all-packages'),['type'=>$type,'groups'=>$groups]);
		 }


		public function LabProfile($id)
    {
        $id = base64_decode($id);
        $product = File::get(public_path('thyrocare-data/Profile.txt'));
				$product = json_decode($product);
				$offers = getThyrocareData("OFFER");
		    $i = 0;
			if(count($offers) > 0){
				foreach($offers as $subKey => $subArray){
				   if($subArray->name == $product[$i]->name ){
					  unset($product[$i]);
				   }
				  $i++;
				}
			}


				$items = array();
				if(count($product) > 0){
					foreach($product as $structs){
						//pr($struct);
								if ($id == $structs->diseaseGroup) {
									//dd("ravi");
							array_push($items, $structs);
						}
							// foreach($structs->childs as $struct){
							// 	//pr($struct);
							// 			if ($id == $struct->groupName) {
							// 				//dd("ravi");
							//         array_push($items, $structs);
							// 				break;
							//     }
							// }
					}
				}
		return view($this->getView('lab.lab-profile'),['items'=>$items]);
    }
	
	public function availPackDetails($code) { 
		$code = base64_decode($code);
		$item = "";
		$all_product = File::get(public_path('thyrocare-data/All.txt'));
		if(!empty($this->findPackageData($all_product,$code))) {
			$item = $this->findPackageData($all_product,$code);
		}
		return view($this->getView('lab.avail-lab-details'),['item'=>$item]);
	}
	
	public function findPackageData($lab_array,$code) {
		$thyProductsArray = [];
		$lab_array = json_decode($lab_array);
		$item = "";
		$testProducts = @$lab_array->MASTERS->TESTS;
		$profileProducts = @$lab_array->MASTERS->PROFILE;
		$offerProducts = @$lab_array->MASTERS->OFFER;
		
		if(count($testProducts) > 0){
			foreach($testProducts as $struct){
				$thyProductsArray[] = $struct;
			}
		}
		if(count($profileProducts) > 0){
			foreach($profileProducts as $struct){
				$thyProductsArray[] = $struct;
			}
		}
		if(count($offerProducts) > 0){
			$ofr_arr = File::get(public_path('thyrocare-data/Offer.txt'));
			$arr2 = json_decode($ofr_arr);
			$offerProducts = @array_merge($offerProducts,$arr2);
			if(count($offerProducts)>0){
				foreach($offerProducts as $struct){
					$thyProductsArray[] = $struct;
				}
			}
		}
		foreach($thyProductsArray as $product) {
			if ($product->code == $code) {
				$item = $product;
				break;
			}
		}
		return $item;
	}

		public function LabDetails($id, $type) {
				$id = base64_decode($id);
				$type = base64_decode($type);
				$item = "";
				Session::put('search_from_lab', $id);
				$all_product = File::get(public_path('thyrocare-data/All.txt'));
			
				$offer_product = File::get(public_path('thyrocare-data/Offer.txt'));

				$profile_product = File::get(public_path('thyrocare-data/Profile.txt'));

				$test_product = File::get(public_path('thyrocare-data/Tests.txt'));

				if(!empty($this->findLabData($all_product,$id))) {
					$item = $this->findLabData($all_product,$id);
				}
				return view($this->getView('lab.lab-details'),['item'=>$item,'id'=>$id]);
		}

		public function findLabData($lab_array,$id) {
			$thyProductsArray = [];
			$lab_array = json_decode($lab_array);
			$item = "";
			$testProducts = @$lab_array->MASTERS->TESTS;
			$profileProducts = @$lab_array->MASTERS->PROFILE;
			$offerProducts = @$lab_array->MASTERS->OFFER;
			
			if(count($testProducts) > 0){
				foreach($testProducts as $struct){
					$thyProductsArray[] = $struct;
				}
			}
			if(count($profileProducts) > 0){
				foreach($profileProducts as $struct){
					$thyProductsArray[] = $struct;
				}
			}
			if(count($offerProducts) > 0){
				$ofr_arr = File::get(public_path('thyrocare-data/Offer.txt'));
				$arr2 = json_decode($ofr_arr);
				$offerProducts = @array_merge($offerProducts,$arr2);
				if(count($offerProducts)>0){
					foreach($offerProducts as $struct){
						$thyProductsArray[] = $struct;
					}
				}
			}
			foreach($thyProductsArray as $product) {
				if ($product->name == $id || $product->aliasName == $id) {
					$item = $product;
					break;
				}
			}
			return $item;
		}
		
		public function LabProfileDetails($id) {
        $id = base64_decode($id);
        $product = File::get(public_path('thyrocare-data/Profile.txt'));
		$product = json_decode($product);
		foreach($product as $struct) {
			if ($id == $struct->name) {
					$item = $struct;
					break;
			}
		}
		return view($this->getView('lab.lab-profile-details'),['item'=>$item]);
    }
	public function LabCart(Request $request) {
		if(Auth::user() == null){
			Session::put('loginFrom', '1');
			return redirect()->route('login');
		}

		$user_id = Auth::user()->id;
		$addresses =  UsersLaborderAddresses::orderBy('label_type', 'ASC')->Where('user_id', $user_id)->get();
		$user =  User::Where('id', $user_id)->first();
		return view($this->getView('lab.lab-cart'),['addresses'=>$addresses,'user'=>$user]);
    }
	public function AvailLabCart(Request $request) {
		$code = "";
		$plan_id = "";
		if(!empty($request->code)){
			$code = base64_decode($request->code);
		}
		if(!empty($request->plan_id)){
			$plan_id = base64_decode($request->plan_id);
		}
		if(Auth::user() == null){
			Session::put('loginFrom', '1');
			return redirect()->route('login');
		}
		$user_id = Auth::user()->id;
		$addresses =  UsersLaborderAddresses::orderBy('label_type', 'ASC')->Where('user_id', $user_id)->get();
		$user =  User::Where('id', $user_id)->first();
		$plan = PlanPeriods::Where('id', $plan_id)->first();
	  return view($this->getView('lab.avail-lab-cart'),['addresses'=>$addresses,'user'=>$user,'code'=>$code,'plan_id'=>$plan_id,'plan'=>$plan]);
    }
	
		function CartUpdate(Request $request) {
			if($request->isMethod('post')){
			$data = $request->all();
			// pr($data);
			$action_type = $data['action_type'];
			
			if($action_type=="add_item") {
				$packages[] = json_decode($data['product_array'], true);
				if(Auth::user() != null){
					$user_id = Auth::user()->id;
					$offer_exists = "";
					if($packages[0]['type'] == "OFFER") {
						$offer_exists = LabCart::where(['user_id'=>$user_id,'product_type'=>"OFFER"])->first();
						if(isset($data['replace_itm']) && $data['replace_itm'] == '1') {
							LabCart::where(['user_id' => $user_id, 'product_type'=>"OFFER"])->delete();
							$offer_exists = "";
						}
					}
					if(!empty($offer_exists)) {
						return 3;
					}
					else {
						$alreadyAdded = LabCart::where(['user_id' => $user_id, 'product_name' => $packages[0]['name'], 'product_code' => $packages[0]['code']])->delete();
						$LabCart = LabCart::create([
							'user_id' => $user_id,
							'product_name' => $packages[0]['name'],
							'product_code' => $packages[0]['code'],
							'product_type' => $packages[0]['type'],
						]);
					}
				}
				else {
					if(session()->has('CartPackages')) {
						$new_packages = json_decode($data['product_array'], true);
						$offer_exists = "";
						if($new_packages['type'] == "OFFER") {
							$old_packages = Session::get('CartPackages');
							foreach($old_packages as $subKey => $subArray) {
								if($subArray['type'] == "OFFER") {
									$offer_exists = 1;
									if(isset($data['replace_itm']) && $data['replace_itm'] == '1') {
										$offer_exists = "";
										unset($old_packages[$subKey]);
										Session::put('CartPackages', $old_packages);
										Session::save();
									}
									break;
								}
							}
						}
						if(!empty($offer_exists)) {
							return 3;
						}
						unset($new_packages['childs']);
						Session::push('CartPackages', $new_packages);
						Session::save();
					}
					else{
						unset($packages[0]['childs']);
						Session::put('CartPackages', $packages);
						Session::save();
					}
				}
				return 1;
			}
			else if($action_type=="remove_item") {
				if(Auth::user() != null){
					$user_id = Auth::user()->id;
					LabCart::where(['user_id' => $user_id, 'product_name' => $data['product_array'][0]['pname'], 'product_code' => $data['product_array'][0]['pcode']])->delete();
				}
				else {
					$old_packages = Session::get('CartPackages');
					foreach($old_packages as $subKey => $subArray){
						 if($subArray['name'] ==$data['product_array'][0]['pname'] || $subArray['code'] == $data['product_array'][0]['pcode']){
								unset($old_packages[$subKey]);
						 }
					}

					Session::put('CartPackages', $old_packages);
					Session::save();
				}
			 return 2;
			}
		}
		return 0;
	}
	 public function createLaborderAddresses(Request $request) {
			if($request->isMethod('post')) {
					$data = $request->all();

					$user_id = Auth::user()->id;
					$addresses =  UsersLaborderAddresses::Where(['user_id' => $user_id, 'label_type' => $data['label_type']])->first();

					if ((count($addresses) > 0) && ($data['label_type'] == 1 || $data['label_type'] == 2)) {
						UsersLaborderAddresses::Where(['user_id' => $user_id, 'label_type' => $data['label_type']])->update([
						'locality'   =>  $data['locality'],
						'pincode'    =>  $data['pincode'],
						'address'    =>  $data['address'],
						'landmark'   =>  $data['landmark'],
						'label_type' =>  $data['label_type'],
						'label_name' =>  $data['label_name'],
						 ]);

					 $address = UsersLaborderAddresses::Where(['user_id' => $user_id, 'label_type' => $data['label_type']])->first();
					}
					else{

						$address =  UsersLaborderAddresses::create([
 						'user_id'   =>  $user_id,
 	 					'locality'   =>  $data['locality'],
 	 					'pincode'    =>  $data['pincode'],
 	 					'address'    =>  $data['address'],
 	 					'landmark'   =>  $data['landmark'],
 						'label_type' =>  $data['label_type'],
 	 					'label_name' =>  $data['label_name'],
 						 ]);
					}
					 return $address;
			 }
		}
		function ApplyCoupon(Request $request) {
			 if($request->isMethod('post')) {
				 $data = $request->all();
				 $validator = Validator::make($data, [
	 				'couponcode' => 'required'
	 			 ]);
	 			if($validator->fails()) {
	 				$errors = $validator->errors();
	 				return $errors->messages()['couponcode'];
	 			}
				 $dt = date('Y-m-d');
				$query =  Coupons::select(['id','coupon_discount','other_text','coupon_code','apply_type','coupon_discount_type'])->where("coupon_code",$data['couponcode'])->whereDate('coupon_last_date','>=', $dt)->where('status','1')->first();//
				 //return $query;
				 // pr(base64_decode($data['onCallStatus']));
				 if(strtolower($data['couponcode']) == "gennie50"){
					 if(base64_decode($data['consultation_fees']) > '500' && base64_decode($data['onCallStatus']) == '1'){
						  return ['status'=>'0','msg'=>'Coupon code only applicable for ₹ 500 or below ₹ 500  doctor consultation fee.'];
					 }
					 else if(base64_decode($data['onCallStatus']) == '2'){
						 return ['status'=>'0','msg'=>'Coupon code only applicable for tele consultation appointments.'];
					 }
				 }
				
				 
				 if($query) {
					 if(base64_decode($data['isDirect']) == '0' && strtolower($data['couponcode']) == "freehg"){
						 return ['status'=>'0','msg'=>'Coupon Code Not Matched.'];
					 }
					 else if(base64_decode($data['isDirect']) == '1' && strtolower($data['couponcode']) == "freehg"){
						 $countCoupon = AppointmentOrder::where('coupon_id',$query->id)->where('order_by',$data['order_by'])->where('order_status',1)->count();
						 if($countCoupon > 0){
							return ['status'=>'0','msg'=>'Coupon Code Is Already Used.'];
						 }
					 }
					 $arr = array('status'=>'1','coupon_id'=>$query->id,'coupon_rate'=>$query->coupon_discount,'other_text'=>$query->other_text,'coupon_code'=>$query->coupon_code,'apply_type'=>$query->apply_type,'coupon_discount_type'=>$query->coupon_discount_type);
					 return $arr;
				 }
				 else {
					 return ['status'=>'0','msg'=>'Coupon Code Not Matched.'];
				 }
			}

		}

		public function deletelaborderAddress(Request $request) {

			 if($request->isMethod('post')) {
					 $data = $request->all();
					 	$user_id = Auth::user()->id;
						UsersLaborderAddresses::Where(['user_id' => $user_id,'id' => $data['id']])->delete();
						return 1;
				}
		 }

		// public function checkPincodeAvailability(Request $request) {

		// 	 if($request->isMethod('post')) {
		// 			 $data = $request->all();
		// 			 $API_KEY = Session::get('API_KEY');
		// 			 $response = @file_get_contents("https://www.thyrocare.com/APIs/order.svc/".$API_KEY."/".$data['pincode']."/PincodeAvailability");
		// 			if(!empty($response)){
		// 			 $responseData = json_decode($response, true);
		// 			 if ($responseData['status'] == 'Y') {
		// 				 return 1;
		// 			 }
		// 			 else{
		// 				 return 0;
		// 			 }
		// 		  }

		// 		}
		//  }

		 public function GetAppointmentSlots(Request $request) {

				if($request->isMethod('post')) {
						$data = $request->all();
						$date = date('Y-m-d', strtotime($data['schedule_date']));
						$pincode = $data['pincode'];
						$url = "https://www.thyrocare.com/apis/ORDER.svc/".$pincode."/".$date."/GetAppointmentSlots";
						$url = str_replace(" ", '%20', $url);
						$response = @file_get_contents($url);
						$responseData = json_decode($response, true);
						if (count($responseData) > 0) {
							return $responseData;
						}
						else{
							return 0;
						}
				 }
			}

			public function ViewCartAPI(Request $request) {

				if($request->isMethod('post')) {
						$data = $request->all();
				    if(Auth::user() != null){
				      $packages = getLabCart();
				    }
				    else{
				      $packages = Session::get("CartPackages");
				    }
					$report_type = $data['report_type'];

					
					foreach ($packages as $key => $value) { 
						if ($value['type'] == 'OFFER') {
							$product_name[] = $value['code'];
							$product_price[] = $value['rate']['b2c'];
						}
						elseif ($value['type'] == 'PROFILE') {
							$product_name[] = $value['name'];
							$product_price[] = $value['rate']['b2c'];
						}
						else {
							$product_price[] = $value['rate']['b2c'];
							if($value['code'] == 'HVA' || $value['code'] == 'SEEL' || $value['code'] == 'E22' || $value['code'] == 'BTHAL' || $value['code'] == 'CUA' || $value['code'] == 'ELEMENTS'
					    	|| $value['code'] == 'H3' || $value['code'] == 'H5' || $value['code'] == 'H6' || $value['code'] == 'MA' || $value['code'] == 'BEAP') {
								$product_name[] = $value['name'];
							}
							else{
								$product_name[] = $value['code'];
							}  
						}
					}

					$product_name = implode(",",$product_name);
					$product_price = implode(",",$product_price);

					if (count($packages) > 0) {
						$API_KEY = Session::get('API_KEY');
						$url = "https://www.thyrocare.com/apis/order.svc/".$API_KEY."/".$product_name."/".$product_price."/NSA/9414061829/1/".$report_type."/0/ViewCart";
						
						$url = str_replace(" ", '%20', $url);
						$response = @file_get_contents($url);
						$responseData = json_decode($response, true);
						if(!empty($response)){
							return $responseData;
						}
					}

				}
			 }
			 
			 public function AvailViewCartAPI(Request $request) {

				if($request->isMethod('post')) {
						$data = $request->all();
						$code = $data['code'];
						$packages = getSubscriptionLabData($code);
						$report_type = $data['report_type'];

					
					foreach ($packages as $key => $value) { 
						if ($value['type'] == 'OFFER') {
							$product_name[] = $value['code'];
							$product_price[] = $value['rate']['b2c'];
						}
						elseif ($value['type'] == 'PROFILE') {
							$product_name[] = $value['name'];
							$product_price[] = $value['rate']['b2c'];
						}
						else {
							$product_price[] = $value['rate']['b2c'];
							if($value['code'] == 'HVA' || $value['code'] == 'SEEL' || $value['code'] == 'E22' || $value['code'] == 'BTHAL' || $value['code'] == 'CUA' || $value['code'] == 'ELEMENTS'
					    	|| $value['code'] == 'H3' || $value['code'] == 'H5' || $value['code'] == 'H6' || $value['code'] == 'MA' || $value['code'] == 'BEAP') {
								$product_name[] = $value['name'];
							}
							else{
								$product_name[] = $value['code'];
							}  
						}
					}

					$product_name = implode(",",$product_name);
					$product_price = implode(",",$product_price);

					if (count($packages) > 0) {
						$API_KEY = Session::get('API_KEY');
						$url = "https://www.thyrocare.com/apis/order.svc/".$API_KEY."/".$product_name."/".$product_price."/NSA/9414061829/1/".$report_type."/0/ViewCart";
						
						$url = str_replace(" ", '%20', $url);
						$response = @file_get_contents($url);
						$responseData = json_decode($response, true);
						if(!empty($response)){
							return $responseData;
						}
					}

				}
			 }

			// public function getUniqueOrderId(Request $request) {
			// 	 if($request->isMethod('post')) {
			// 		$data = Input::json();
			// 		 $success = false;
			// 		 $lab = LabOrders::create();
			// 		 $number = $lab->id."HG".rand(10,100);
			// 		 LabOrders::where(["id"=>$lab->id])->update(["orderId"=>$number]);
			// 		 if(!empty($lab)) {
			// 			$success = true;
			// 			return $this->sendResponse($number, '',$success);
			// 		 }
			// 	}
			// }


			public function createLabOrder(Request $request) {
				if($request->isMethod('post')) {
					$data = $request->all();
					$validator = Validator::make($data, [
						'name'   => 'required|max:100',
					 	'gender'   => 'required|max:50',
						'age'   => 'required|max:200',
						'email' => 'required|email|max:255',
						'mobile' => 'required|numeric',
						'address_id'   => 'required|max:50',
						'appt_date'   => 'required|max:50',
						'appt_time'   => 'required|max:50',
						'total_amount'   => 'required',
						'payable_amt'   => 'required',
					]);
					if($validator->fails()) {
						return 4;
					}
					else {
						$appt_date = date("Y-m-d", strtotime($data['appt_date']));
						$appt_time = date("h:i:s A", strtotime($data['appt_time']));
						$appt_date_time = $appt_date.' '.$appt_time;
						$user_id = Auth::user()->id;
						if($data['coupon_code'] == "HGSUBSCRIBED") {
							$prod_code = (isset($data['prod_code'])) ? $data['prod_code'] : null;
							$packages = getSubscriptionLabData($prod_code);
						}
						else{
							$packages = getLabCart();
						}
						$final_products = $data['final_products'];
						$final_products = explode(",",$final_products);
						
						$product_name = [];
						$code = [];
						foreach($packages as $key => $value) {
							if(in_array($value['name'],$final_products)) {
								if ($value['type'] == 'OFFER') {
									if($value['ownpkg'] == "Y") {
										$product_name[] = $value['testnames'];
										$product_price[] = $value['rate']['b2c'];
									}
									else{
										$product_name[] = $value['name'];
										$product_price[] = $value['rate']['b2c'];
									}
									$code[] = $value['code'];
								}
								else if($value['type'] == 'PROFILE') {
									$product_name[] = $value['name'];
									$product_price[] = $value['rate']['b2c'];
								}
								else{
									$product_name[] = $value['name'];
									$product_price[] = $value['rate']['b2c'];
								}
							}
						}
						$product_name = implode(",",$product_name);
						$product_price = implode(",",$product_price);
						$report_code = implode(",",$code);
						$address = UsersLaborderAddresses::Where(['user_id' => $user_id,'id' => $data['address_id']])->first();
						$lab = LabOrders::create();
						$orderId = $lab->id."LAB".rand(10,100);
						LabOrders::where(["id"=>$lab->id])->update(["orderId"=>$orderId]);
						$API_KEY = Session::get('API_KEY');
						$appt_date = date("Y-m-d", strtotime($data['appt_date']));
						$appt_time = date("h:i:s A", strtotime($data['appt_time']));
						$appt_date_time = $appt_date.' '.$appt_time;
						$bendataxml = "<NewDataSet><Ben_details><Name>".$data['name']."</Name><Age>".$data['age']."</Age><Gender>".$data['gender']."</Gender></Ben_details></NewDataSet>";

						if($data['coupon_code'] == "HGSUBSCRIBED") {
							$coupon_data =  Coupons::select("id")->where('coupon_code',$data['coupon_code'])->first();
							$data['coupon_id'] = $coupon_data->id;
						}
						$user_array = array();
						$user_array['coupon_code'] = $data['coupon_code'];
						$user_array['api_key'] = Session::get('API_KEY');
						$user_array['orderId'] = $orderId;
						$user_array['user_id'] = $user_id;
						$user_array['address'] = $address->address.' '.$address->landmark.' '.$address->locality.' '.$address->pincode;
						$user_array['mobile'] = $data['mobile'];
						$user_array['email'] = $data['email'];
						$user_array['service_type'] = 'H';
						$user_array['pincode'] = $address->pincode;
						$user_array['address_id'] = $data['address_id'];
						$user_array['pay_type'] = $data['pay_type'];
						$user_array['bencount'] = "1";
						$user_array['bendataxml'] = $bendataxml;
						$user_array['coupon_id'] = $data['coupon_id'];
						$user_array['order_by'] = $data['name'];
						$user_array['rate'] = base64_decode($data['payable_amt']);
						$user_array['hc'] = 0;
						$user_array['reports'] = $data['report_type'];
						$user_array['ref_code'] = "9414061829";
						$user_array['total_amt'] = base64_decode($data['total_amount']);
						$user_array['discount_amt'] = base64_decode($data['discount_amt']);
						$user_array['coupon_amt'] = base64_decode($data['coupon_amt']);
						$user_array['payable_amt'] = base64_decode($data['payable_amt']);
						$user_array['appt_date'] = $appt_date_time;
						$user_array['status'] = $data['status'];
						$user_array['order_status'] = $data['order_status'];
						$user_array['product'] = $product_name;
						$user_array['items'] = $packages;
						$user_array['report_code'] = $report_code;
						$user_array['plan_id'] = (isset($data['plan_id'])) ? $data['plan_id'] : null;
						
						if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])) {
							$appt_date = strtotime($user_array['appt_date']);
						}
						$meta_data = json_encode($user_array);
						LabOrders::where(["orderId"=>$user_array['orderId']])->update([
							'user_id' => $user_array['user_id'],
							'address_id' => $user_array['address_id'],
							'product' => $user_array['product'],
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
							'plan_id' => $user_array['plan_id'],
							'status' => 0
						]);
						
						if ($user_array['pay_type'] == "Postpaid" || $user_array['coupon_code'] == "HGCash" || $user_array['coupon_code'] == "HGSUBSCRIBED") {
							$order_array = array(
								'api_key' => $user_array['api_key'],
								'orderid' => $user_array['orderId'],
								'address' => $user_array['address'],
								'pincode' => $user_array['pincode'],
								'product' => $user_array['product'],
								'mobile' => $user_array['mobile'],
								'email' => $user_array['email'],
								'service_type' => $user_array['service_type'],
								'order_by' => $user_array['order_by'],
								'rate' => $user_array['rate'],
								'hc' => $user_array['hc'],
								'appt_date' => $user_array['appt_date'],
								'reports' => $user_array['reports'],
								'ref_code' => $user_array['ref_code'],
								'pay_type' => $user_array['pay_type'],
								'bencount' => $user_array['bencount'],
								'bendataxml' => $user_array['bendataxml'],
								'report_code' => $report_code,
							);
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, "https://www.thyrocare.com/APIs/ORDER.svc/Postorderdata");
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
							curl_setopt($ch, CURLOPT_POST, true);

							$order_data = json_encode($order_array);
							curl_setopt($ch, CURLOPT_POSTFIELDS, $order_data);
							curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
							curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
							$output = curl_exec($ch);
							curl_close($ch);
							$output = json_decode($output,true);
							
							if(!empty($output)  && $output['RES_ID'] == 'RES0000') {
								$lab_id = LabOrders::select("id")->where(["orderId"=>$user_array['orderId']])->first();
								LabOrders::where(["orderId"=>$user_array['orderId']])->update([
									'ref_orderId' => $output['REF_ORDERID'],
									'post_order_meta' => json_encode($output),
									'order_status' => $output['STATUS'],
									'status'=>1,
								]);
								if(count($packages) > 0){
									foreach($packages as $itm){
										$items = LabOrderedItems::create([
											'order_id' => $lab_id->id,
											'product_name' => $itm['name'],
											'cost' => $itm['rate']['b2c'],
											'discount_amt' => $itm['rate']['offer_rate'],
											'margin' => $itm['margin'],
											'item_type' => $itm['type'],
										]);
									}
								}
							}
							else{
								return ["status"=>0,'output'=>$output];
							}
							if($user_array['coupon_code'] == "HGCash" || $user_array['coupon_code'] == "HGSUBSCRIBED") {
								if($user_array['coupon_code'] == "HGSUBSCRIBED") {
									PlanPeriods::where('id', $user_array['plan_id'])->update(array(
									   'lab_pkg_remaining' => 0,
									));
								}
								LabOrderTxn::create([
									'order_id' => $user_array['orderId'],
									'tran_mode'=> "Cash",
									'payed_amount'=>$user_array['payable_amt'],
									'tran_status' => "Success",
									'currency' => "INR",
									'trans_date' => date('d-m-Y')
								]);
							}
							$ch_app = curl_init();
							curl_setopt($ch_app, CURLOPT_URL, "https://www.thyrocare.com/APIs/ORDER.svc/FixAppointment");
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
							LabCart::where(['user_id' => $user_id])->delete();
							Session::forget('CartPackages');
							return json_encode($output);
						}
						else {
							return $this->labCheckout($user_array['orderId'], $user_array['api_key'], $user_array['pincode'], $user_array['appt_date'], $user_array['payable_amt'],$user_array['order_by']);
						}
					}
				}
			}

			function labCheckout($orderId, $api_key, $pincode, $appt_date, $payable_amt,$order_by) {
				$parameters = [
					"status"=> 1,
					'tid' => base64_encode(strtotime("now")),
					'order_id' => base64_encode($orderId),
					'amount' => base64_encode($payable_amt),
					'order_by' => base64_encode($order_by),
					// 'amount' => base64_encode(1),
					'merchant_param1' => base64_encode("HealthGennie Lab Order"),
					'merchant_param2' => base64_encode($api_key),
					'merchant_param3' => base64_encode($pincode),
					'merchant_param4' => base64_encode($appt_date),
				];
				return $parameters;
			}

			public function labCheckoutOrder(Request $request) {
				$data = $request->all();
				// $parameters = [
					// 'tid' => base64_decode($data['tid']),
					// 'order_id' => base64_decode($data['order_id']),
					// 'amount' => base64_decode($data['amount']),
					// 'merchant_param1' => base64_decode($data['merchant_param1']),
					// 'merchant_param2' => base64_decode($data['merchant_param2']),
					// 'merchant_param3' => base64_decode($data['merchant_param3']),
					// 'merchant_param4' => base64_decode($data['merchant_param4']),
				// ];
				// $order = Indipay::gateway('CCAvenue')->prepare($parameters);
				// return Indipay::process($order);
				
				
				$parameters["MID"] = "yNnDQV03999999736874";		
				$parameters["ORDER_ID"] = base64_decode($data['order_id']); 
				$parameters["CUST_ID"] =  base64_decode($data['order_by']);
				$parameters["TXN_AMOUNT"] = base64_decode($data['amount']); 
				$parameters["CALLBACK_URL"] = url('paytmresponse'); 
				$order = Indipay::gateway('Paytm')->prepare($parameters);
				return Indipay::process($order);
			}
		//for APP
		public function orderSuccess(Request $request) {
			return view($this->getView('lab.labOrder_complete'));
		}
		//for APP
		public function orderCancel(Request $request) {
			return view($this->getView('lab.labOrder_cancel'));
		}
		//for Web
		public function cancelOrder(Request $request) {
			$data = $request->all();
			return $this->cancelOrderFunc($data['orderId'],$data['cancel_reason']);
		}
		
		public function cancelOrderFunc($orderId=null, $cancel_reason= null) {
			$order = LabOrders::where(["orderId"=>$orderId])->first();
			if (!empty($order)) {
				$ch_app = curl_init();
				curl_setopt($ch_app, CURLOPT_URL, "https://www.thyrocare.com/APIs/ORDER.svc/cancelledorder");
				curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch_app, CURLOPT_POST, true);
				$order_array = array(
					'OrderNo' => $order->ref_orderId,
					'VisitId' => $order->ref_orderId,
					'Status' => 2,
				);
				$order_data = json_encode($order_array);
				curl_setopt($ch_app, CURLOPT_POSTFIELDS, $order_data);
				curl_setopt($ch_app, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt($ch_app, CURLOPT_RETURNTRANSFER, true);
				$app_output = curl_exec($ch_app);
				curl_close($ch_app);
				$output = json_decode($app_output,true);
				
				if($output['RESPONSE']) {
					$response = json_decode($output['RESPONSE'],true);
					if($response['Response'] == "SUCCESS") {
						LabOrders::where(["ref_orderId"=>$order->ref_orderId])->update([
							'order_status' => 'CANCELLED',
							'cancel_reason' => $cancel_reason,
							'is_free_appt' => 0,
						]);
						return ["status"=>1,'output'=>$output];
					}
					else {
						return ["status"=>0,'output'=>$output];
					}
				}
				else {
					return ["status"=>0,'output'=>$output];
				}
			}
		}
		
		public function labOrders(Request $request, $filter=null) {
			
			
			
			if(Auth::user() == null){
				Session::put('loginFrom', '1');
				return redirect()->route('login');
			}
			if($request->isMethod('post')) {
				$user_id = Auth::user()->id;
				$API_KEY = Session::get('API_KEY');
				$data = $request->all();
				$query = LabOrders::with('LabOrderedItems')->where(["user_id" => $user_id]);
				if ($data['filter'] == 1) {
					$query->whereIn('order_status', array('YET TO CONFIRM', 'YET TO ASSIGN', 'Y'));
				}
				else if ($data['filter'] == 3) {
					$query->whereNotIn('order_status',array('YET TO CONFIRM', 'YET TO ASSIGN', 'Y' , 'CANCELLED'));
				}
				else if ($data['filter'] == 4) {
					$query->where('order_status', 'CANCELLED');
				}
				if ($data['lastId'] != null) {
					$orders = $query->where('id', '<', $data['lastId'])->orderBy('id', 'DESC')->paginate(1);
				}
				else {
					$orders = $query->orderBy('id', 'DESC')->paginate(1);
				}

				if(count($orders)>0){
					foreach($orders as $order) {
						$order['is_camp'] = 0;
						$orderId = $order->orderId;
						$meta_data = json_decode($order->meta_data,true);
						$mobile_no = $meta_data['mobile'];
						$url = "https://www.thyrocare.com/APIS/order.svc/".$API_KEY."/".$order->ref_orderId."/".$mobile_no."/all/OrderSummary";

						$url = str_replace(" ", '%20', $url);
						$response = @file_get_contents($url);
						if(!empty($response)){
							$response_data = json_decode($response, true);
							if(!empty($response_data) && isset($response_data['ORDER_MASTER'][0])) {
								
								if($response_data['ORDER_MASTER'][0]['STATUS'] == "REPORTED" || $response_data['ORDER_MASTER'][0]['STATUS'] == "DONE") {
									if($order->pay_type != "Prepaid" && $order->is_free_appt == "0"){
										LabOrders::where(["id"=>$order->id])->update([
											"order_status" => trim($response_data['ORDER_MASTER'][0]['STATUS']),
											"status" => 1,
											"is_free_appt" => 1,
										]);
									}
									else{
										LabOrders::where(["id"=>$order->id])->update([
											"order_status" => trim($response_data['ORDER_MASTER'][0]['STATUS']),
											"status" => 1,
										]);
									}
								}
								else{
									LabOrders::where(["id"=>$order->id])->update([
										"order_status" => trim($response_data['ORDER_MASTER'][0]['STATUS']),
									]);
								}
								
								$order->order_status = $response_data['ORDER_MASTER'][0]['STATUS'];

								if($order->pay_type == "Prepaid" && $order->user_id != null && $order->ref_orderId != null && $order->status != 1) {
									$this->cancelOrderFunc($orderId,"Payment Not Completed");
									$order->order_status = "CANCELLED";
								}
								if($response_data['ORDER_MASTER'][0]['STATUS'] == "REPORTED" || $response_data['ORDER_MASTER'][0]['STATUS'] == "DONE") {
									$report = LabReports::where(["order_id"=>$orderId])->first();
									if(empty($report)) {
									  $user_data = json_decode($order->meta_data,true);
									  $user_mobile = $user_data['mobile'];
									  $post_meta = json_decode($order->post_order_meta,true);
									  $lead_id = @$post_meta['ORDERRESPONSE']['PostOrderDataResponse'][0]['LEAD_ID'];
									  $url_myReport = "https://www.thyrocare.com/APIS/order.svc/".$API_KEY."/GETREPORTS/".$orderId."/xml/".$user_mobile."/Myreport";
									  $url_myReportPdf = "https://www.thyrocare.com/APIS/order.svc/".$API_KEY."/GETREPORTS/".$lead_id."/pdf/".$user_mobile."/Myreport";

									  $reportData = @file_get_contents($url_myReport);
									  $reportData = json_decode($reportData,true);
									  $fName = "";$url_downloadPdf = "";
									  if(!empty($reportData)) {
										if($reportData['RESPONSE'] == "SUCCESS") {
										  $url_download = $reportData['URL'];
										  $patPath = base_path()."/uploads/userDocuments/".$order->user_id;
										  if(!is_dir($patPath)){
											 File::makeDirectory($patPath, $mode = 0777, true, true);
										  }
										  $fName = $order->user_id."".$order->orderId."".time().".xml";
										  file_put_contents($patPath."/".$fName,file_get_contents($url_download));
										}
									  }

									  $reportDataPdf = @file_get_contents($url_myReportPdf);
									  $reportDataPdf = json_decode($reportDataPdf,true);
									  if(!empty($reportDataPdf)) {
										if($reportDataPdf['RESPONSE'] == "SUCCESS") {
										  $url_downloadPdf = $reportDataPdf['URL'];
										}
									  }
									  if(!empty(($fName))) {
										$items = LabReports::create([
										  'order_id' => $orderId,
										  'user_id' => $order->user_id,
										  'report_xml_name' => $fName,
										  'report_pdf_name' => $url_downloadPdf,
										]);
									  }
									}
								}
							}
						}
					}
					return view($this->getView('lab.load-lab-orders'),['orders'=>$orders]);
				}

				$campQuery = CampData::with("user")->where("user_id",$user_id);
				if($data['filter'] == 1 ||  $data['filter'] == 4){
					$campQuery->where("user_id",null);
				}
				if ($data['lastId'] != null) {
					$orderss = $campQuery->where('id', '<', $data['lastId'])->orderBy('id', 'DESC')->paginate(1);
				}
				else{
					$orderss = $campQuery->orderBy('id', 'DESC')->paginate(1);
				}
				if(!empty($orderss)) {
					foreach($orderss as $ord) {
						$apiUrl = "https://www.thyrocare.com/APIS/order.svc/".$API_KEY."/".$ord->thy_ref_order_no."/".$ord->user->mobile_no."/all/OrderSummary";
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $apiUrl);
						curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
						$output = curl_exec($ch);
						curl_close($ch);
						$output = json_decode($output);
						$ord['thy_order_data'] = $output;
						$ord['is_camp'] = 1;
						$ord['report_url'] = null;

						$lead_idss = @$output->LEADHISORY_MASTER[0]->APPOINT_ON[0]->LEAD_ID;
						$reportFile = "https://www.thyrocare.com/APIS/order.svc/".$API_KEY."/GETREPORTS/".$lead_idss."/pdf/".$ord->user->mobile_no."/Myreport";

						$ch_r = curl_init();
						curl_setopt($ch_r, CURLOPT_URL, $reportFile);
						curl_setopt($ch_r,CURLOPT_RETURNTRANSFER,true);
						$reportOutput = curl_exec($ch_r);
						curl_close($ch_r);
						$reportOutput = json_decode($reportOutput, true);
						if(!empty($reportOutput)){
							$ord['report_url'] = $reportOutput['URL'];
						}
					}
					return view($this->getView('lab.load-lab-order-camp'),['orderss'=>$orderss]);
				}
			}
			else{
				$filter = base64_decode($filter);
				return view($this->getView('lab.lab-orders'),['filter'=>$filter]);
			}
		}
		
		public function labOrderDetails(Request $request, $orderid) {
			if(Auth::user() == null){
				Session::put('loginFrom', '1');
				return redirect()->route('login');
			}
			$orderid = base64_decode($orderid);
			$user_id = Auth::user()->id;
			$order = LabOrders::where(["orderId" => $orderid])->first();
			$coupanDetails = "";
			if (!empty($order->coupon_id)) {
				$coupanDetails = Coupons::where('id',$order->coupon_id)->first();
			}
			return view($this->getView('lab.lab-order-details'),['order'=>$order,'orderAPI'=>$orderAPI,'coupanDetails'=>$coupanDetails]);
		}

}
