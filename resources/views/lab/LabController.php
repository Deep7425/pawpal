<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\UserDetails;
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
use App\Models\LabPackage;
use App\Models\ThyrocareLab;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\MedicinePrescriptions;
use App\Models\LabPincode;
use App\Models\LabCompany;
use App\Models\LabRequests;
use App\Models\Plans as userPlan;
use Mail;
use File;
use Session;
use Auth;
use PaytmWallet;
use Softon\Indipay\Facades\Indipay;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
class LabController extends Controller {

		public function __construct(){
			if(!Session::has('lab_company_type')) { 
				$labK = setLabTypeSession();
				if(!empty($labK)){ 
					Session::put('lab_company_type',$labK);
					Session::save();
				}
			}
			// if(!Session::has('API_KEY')){
				$this->setSessionAPIKey();
			// }
		}

		public function setSessionAPIKey(){
			 // if(!Session::has('API_KEY')) {
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
					$response = json_decode($response,true);
					
					if(!empty($response)) {
						Session::forget('API_KEY');
						Session::put('API_KEY', $response['apiKey']);
						Session::put('dsa_mobile', $response['mobile']);
						Session::save();
					}
					// pr(Session::get('API_KEY'));
			 // }
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
			if(Auth::user() == null){
				Session::put('loginFrom', '1');
			}
			Session::forget('search_from_lab');
			$groups = ThyrocarePackageGroup::where(['delete_status'=>1, 'status' => 1])->orderBy('sequence','ASC')->get();
			$plans  = userPlan::Where(["delete_status"=>'1'])->whereIn("id",array(29,30,34))->orderBy('price', 'asc')->get();
			return view($this->getView('lab.lab-dashboard'),['groups'=>$groups,'plans'=>$plans]);
	 	}

		public function allPackages(Request $request, $type){
			$groups = ThyrocarePackageGroup::where(['delete_status'=>1, 'status' => 1])->orderBy('sequence','ASC')->get();
			 return view($this->getView('lab.all-packages'),['type'=>$type,'groups'=>$groups]);
		}
		public function showLabsPackage(Request $request, $cmp_id) {
			$cmp_id = base64_decode($cmp_id);
			$groups = ThyrocarePackageGroup::where(['delete_status'=>1, 'status' => 1])->orderBy('sequence','ASC')->get();
			if($cmp_id != 2) {
				$packages = LabPackage::with(["LabCompany","DefaultLabs"])->where(['company_id'=>$cmp_id])->where(['status'=>1,'delete_status'=>1])->orderBy('id','desc')->get();
			}
			else{
				$packages = getThyrocareData("OFFER");
			}
			 return view($this->getView('lab.company-packages'),['packages'=>$packages,'cmp_id'=>$cmp_id]);
		 }

		public function LabProfile($id){
			$id = base64_decode($id);
			$items = ThyrocareLab::whereIn('type',['PROFILE','POP'])->where('diseaseGroup',$id)->get();
			/*$product = File::get(public_path('thyrocare-data/Profile.txt'));
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
			}*/
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

		public function LabDetails($id, $type=null) {
			$id = base64_decode($id);
			$type = base64_decode($type);
			$cmpId = Session::get("lab_company_type");
			// pr($cmpId);
			$other_item = [];
			if($type == "PACKAGE") {
				$item = LabPackage::with(["LabCompany","DefaultLabs"])->whereHas('LabCompany',function($q) use($cmpId){
					if(!empty($cmpId)){$q->where('id',$cmpId);}
				})->where(['title'=>$id])->first();	
			}
			else if($type == "CUSTOM"){
				$item = ThyrocareLab::where(['name'=>$id])->orWhere(['common_name'=>$id])->first();
				$other_item = getOtherLab($id);
			}
			else{
				$item = ThyrocareLab::where(['common_name'=>$id])->first();
				$other_item = getOtherLab($id);
			}
			return view($this->getView('lab.lab-details'),['item'=>$item,'id'=>$id,'other_item'=>$other_item,'type'=>$type]);
		}

		public function findLabData($lab_array,$id) {
			$thyProductsArray = [];
			$lab_array = json_decode($lab_array);
			$item = "";
			$testProducts = @$lab_array->master->tests;
			$profileProducts = @$lab_array->master->profile;
			$offerProducts = @$lab_array->master->offer;
			
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
				if (strtolower($product->name) == strtolower($id) || strtolower($product->aliasName) == strtolower($id)) {
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
			// dd($data);
			$action_type = $data['action_type'];
			if($action_type=="add_item") {
				$lab_type = @$data['lab_type'];
				$lab_company = @$data['lab_company_type'];
				$packages = json_decode($data['product_array'], true);
				if(Auth::user() != null){
					$user_id = Auth::user()->id;
					if(isset($data['replace_itm']) && $data['replace_itm'] == '2') {
						LabCart::where(['user_id' => $user_id])->delete();
					}
					if($lab_type != 0) {
						if(isset($packages['labs'])) {
							LabCart::where(['user_id' => $user_id, 'product_name' => $packages['title'], 'product_code' => $packages['id']])->delete();
							$LabCart = LabCart::create([
								'type' => $packages['company_id'],
								'user_id' => $user_id,
								'product_name' => $packages['title'],
								'product_code' => $packages['id'],
								'product_type' => "OFFER",
							]);
							Session::put('lab_company_type', $packages['company_id']);
							Session::save();
						}
						else{
							LabCart::where(['user_id' => $user_id, 'product_name' => $packages['default_labs']['title'], 'product_code' => $packages['default_labs']['id']])->delete();
							$LabCart = LabCart::create([
								'type' => $packages['company_id'],
								'user_id' => $user_id,
								'product_name' => $packages['default_labs']['title'],
								'product_code' => $packages['default_labs']['id'],
								'product_type' => "TEST",
							]);
							Session::put('lab_company_type', $packages['company_id']);
							Session::save();
						}
					}
					else{
						$offer_exists = "";
						if($packages['type'] == "OFFER") {
							$offer_exists = LabCart::where(['user_id'=>$user_id,'product_type'=>"OFFER"])->first();
							if(isset($data['replace_itm']) && $data['replace_itm'] == '1') {
								LabCart::where(['user_id' => $user_id, 'product_type'=>"OFFER"])->delete();
								$offer_exists = "";
							}
						}
						if(!empty($offer_exists)) {
							return ['status'=>3,'lab_company_type'=>Session::get("lab_company_type")];
						}
						else {
							$alreadyAdded = LabCart::where(['user_id' => $user_id, 'product_name' => $packages['name'], 'product_code' => $packages['code']])->delete();
							LabCart::create([
								'user_id' => $user_id,
								'product_name' => $packages['name'],
								'product_code' => $packages['code'],
								'product_type' => $packages['type'],
							]);
							Session::put('lab_company_type',0);
							Session::save();
						}
					}
				}
				else {
					$new_packages = json_decode($data['product_array'], true);
					if(isset($data['replace_itm']) && $data['replace_itm'] == '2') {
						Session::forget('CartPackages');
					}
					if(isset($new_packages['type']) && $new_packages['type'] == "OFFER" && $lab_type == 0) {
						$old_packages = Session::get('CartPackages');
						if(isset($old_packages)) {
							foreach($old_packages as $subKey => $subArray) {
								if($subArray['type'] == "OFFER") {
									$offer_exists = 1;
									if(isset($data['replace_itm']) && $data['replace_itm'] == '1'){
										$offer_exists = "";
										unset($old_packages[$subKey]);
										Session::put('CartPackages', $old_packages);
										Session::put('lab_company_type',0);
										Session::save();
									}
									break;
								}
							}
						}
					}
					if(!empty($offer_exists)) {
						return ['status'=>3,'lab_company_type'=>Session::get("lab_company_type")];
					}
					$this->setLabCartData($lab_type,$data['replace_itm'],$new_packages,$lab_company);
					/*if(session()->has('CartPackages')) {
						$new_packages = json_decode($data['product_array'], true);
						$offer_exists = "";
						if(isset($data['replace_itm']) && $data['replace_itm'] == '2') {
							Session::forget('CartPackages');
						}
						if($lab_type != 0) {
							if(isset($new_packages['default_labs'])){
								$new_packages['DefaultLabs'] = $new_packages['default_labs'];
							}
							$arr[] = $new_packages;
							Session::put('CartPackages', $arr);
							Session::put('lab_company_type', 1);
							Session::save();
						}
						else{
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
							if(isset($new_packages['default_labs'])){
								$new_packages['DefaultLabs'] = $new_packages['default_labs'];
							}
							$arr = $new_packages;
							Session::push('CartPackages', $arr);
							Session::put('lab_company_type', 0);
							Session::save();
						}
					}*/
					// else{
						// unset($packages['childs']);
						// if($lab_type != 0) {
							// if(isset($packages['default_labs'])){
								// $packages['DefaultLabs'] = $packages['default_labs'];
							// }
							// Session::put('lab_company_type', 1);
							// Session::save();
						// }
						// else{
							// Session::put('lab_company_type', 0);
							// Session::save();
						// }
						// $arr[] = $packages;
						// Session::put('CartPackages', $arr);
						// Session::save();
					// }
				}
				return ['status'=>1,'lab_company_type'=>Session::get("lab_company_type")];
			}
			else if($action_type == "remove_item") {
				if(Auth::user() != null) {
					$user_id = Auth::user()->id;
					LabCart::where(['user_id' => $user_id, 'product_name' => $data['product_array'][0]['pname'], 'product_code' => $data['product_array'][0]['pcode']])->delete();
					$isCartEmpty = LabCart::where(['user_id' => $user_id])->count() > 0 ? false : true;
					if($isCartEmpty) { Session::forget('lab_company_type'); }
					return ['status'=>1,'lab_company_type'=>Session::get("lab_company_type")];
				}
				else {
					$lab_company_type = Session::get("lab_company_type");
					$old_packages = Session::get('CartPackages');
					if($lab_company_type != 0){
						foreach($old_packages as $subKey => $subArray) {
						 if($subArray['DefaultLabs']['title'] == $data['product_array'][0]['pname']){
							unset($old_packages[$subKey]);
						 }
						}
					}
					else{
						foreach($old_packages as $subKey => $subArray) {
						 if($subArray['name'] ==$data['product_array'][0]['pname'] || $subArray['code'] == $data['product_array'][0]['pcode']){
							unset($old_packages[$subKey]);
						 }
						}
					}
					Session::put('CartPackages', $old_packages);
					Session::save();
					$isCartEmpty = true;
					return ['status'=>1,'lab_company_type'=>$lab_company_type];
				}
			}
		}
		return ['status'=>0,'lab_company_type'=>Session::get("lab_company_type")];
	}
	public function setLabCartData($lab_type,$replace_itm,$new_packages,$lab_company = null){
		if($replace_itm == '2') {
			Session::forget('CartPackages');
		}
		$old_packages = Session::get('CartPackages');
		
		if($lab_type != 0) {
			if(isset($new_packages['cost'])) {
				$new_packages['DefaultLabs'] = $new_packages['default_labs'];
				$new_packages['lab_cart_type'] = 'custom';
			}
			else if(isset($new_packages['price'])) {
				$new_packages['DefaultLabs']['id'] = $new_packages['id'];
				$new_packages['DefaultLabs']['title'] = $new_packages['title'];
				$new_packages['cost'] = $new_packages['price'];
				$new_packages['offer_rate'] = $new_packages['discount_price'];
				$new_packages['lab_cart_type'] = 'package'; 
			}
			Session::put('lab_company_type', $lab_company);
		}
		else{
			$new_packages['lab_cart_type'] = 'thy';
			unset($new_packages['childs']);
			Session::put('lab_company_type', 0);
		}
		// dd($new_packages);
		if(!empty($old_packages)) {
			array_push($old_packages,$new_packages);
			Session::put('CartPackages', $old_packages);
			Session::save();
		}
		else{
			$old_packages[] = $new_packages;
			Session::put('CartPackages', $old_packages);
			Session::save();
		}
	}
	
	public function createLaborderAddresses(Request $request) {
			if($request->isMethod('post')) {
					$data = $request->all();

					$user_id = Auth::user()->id;
					$addresses =  UsersLaborderAddresses::Where(['user_id' => $user_id, 'label_type' => $data['label_type']])->first();

					if((!empty($addresses)) && ($data['label_type'] == 1 || $data['label_type'] == 2)) {
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


		public function deletelaborderAddress(Request $request) {

			 if($request->isMethod('post')) {
					 $data = $request->all();
					 	$user_id = Auth::user()->id;
						UsersLaborderAddresses::Where(['user_id' => $user_id,'id' => $data['id']])->delete();
						return 1;
				}
		 }

		public function checkPincodeAvailability(Request $request) {
		 if($request->isMethod('post')) {
			$data = $request->all();
			if(Session::get("lab_company_type") != 0){
				return LabPincode::where(['company_id'=>Session::get("lab_company_type"),'pincode'=>$data['pincode']])->count() > 0 ? 1 : 0;
			}
			else{
			 $API_KEY = Session::get('API_KEY');
			 $postdata = array(
				'ApiKey' => $API_KEY,
				'Pincode' => $data['pincode']
			  );
			  $response = getResponseByCurl($postdata,"https://velso.thyrocare.cloud/api/TechsoApi/PincodeAvailability");
			  if(!empty($response)){
				 if ($response['status'] == 'Y') {
					 return 1;
				 }
				 else{
					 return 0;
				 }
			  }
			}
		   }
		 }

			 public function GetAppointmentSlots(Request $request) {
				if($request->isMethod('post')) {
					$data = $request->all();
					$date = date('Y-m-d', strtotime($data['schedule_date']));
					if(Session::get("lab_company_type") != 0) {
						$labTimings =  LabCompany::where(['id'=>Session::get("lab_company_type")])->first();
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
							$slot_array = array();
							if(count($time_slot)>0) {
								foreach($time_slot as $k=>$val){
									// if(strtotime($date." ".date("h:i A",$val)) > strtotime(date("Y-m-d h:i A"))){
										$gg = $val;
										$gg += $labTimings->slot_duration*60;
										$val = date("H:i",$val).' - '.date("H:i",$gg);
										$slot_array[] = ['id'=>$k,'slot'=>$val,'slotMasterId'=>$k];
									// }
								}
							}
							return ['lSlotDataRes'=>$slot_array];
						}
					}
					else{
						$API_KEY = Session::get('API_KEY');
						$postdata = array(
							'ApiKey' => $API_KEY,
							'Pincode' => $data['pincode'],
							'Date' => $date
						);
						$response = getResponseByCurl($postdata,"https://velso.thyrocare.cloud/api/TechsoApi/GetAppointmentSlots");
						if(!empty($response)) {
							return $response;
						}
						else{
							return 0;
						}
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
					// dd($packages);
					$report_type = $data['report_type'];
					foreach($packages as $key => $value) { 
						if ($value['type'] == 'OFFER') {
							$product_name[] = $value['code'];
							$product_price[] = $value['rate']['b2C'];
						}
						elseif ($value['type'] == 'PROFILE') {
							$product_name[] = $value['name'];
							$product_price[] = $value['rate']['b2C'];
						}
						else {
							$product_price[] = $value['rate']['b2C'];
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
					if(count($packages) > 0) {
						$API_KEY = Session::get('API_KEY');
						// pr($API_KEY);
						$postdata = array(
							'ApiKey' => $API_KEY,
							'Products' => $product_name,
							'Rates' => $product_price,
							'ClientType' => 'PUBLIC',
							'Mobile' => Session::get('dsa_mobile'),
							'BenCount' => '1',
							'Report' => $report_type,
							'Discount' => '',
							'Coupon' => '',
						);
						// pr($postdata);
						$response = getResponseByCurl($postdata,"https://velso.thyrocare.cloud/api/CartMaster/DSAViewCartDTL");
						if(!empty($response)){
							return $response;
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
							$product_price[] = $value['rate']['b2C'];
						}
						elseif ($value['type'] == 'PROFILE') {
							$product_name[] = $value['name'];
							$product_price[] = $value['rate']['b2C'];
						}
						else {
							$product_price[] = $value['rate']['b2C'];
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


			public function createCustomLabOrder($data) {
				$user_id = Auth::user()->id;
				$packages = getLabCart();
				$address = UsersLaborderAddresses::Where(['user_id' => $user_id,'id' => $data['address_id']])->first();
				$lab = LabOrders::create();
				$orderId = $lab->id."LAB".rand(10,100);
				LabOrders::where(["id"=>$lab->id])->update(["orderId"=>$orderId]);
				$appt_date_d = date("d-m-Y", strtotime($data['appt_date']));
				$appt_date = date("Y-m-d", strtotime($data['appt_date']));
				$appt_time = date("h:i:s A", strtotime($data['appt_time']));
				$appt_date_time = $appt_date.' '.$appt_time;
				$user_array = array();
				$user_array['coupon_code'] = $data['coupon_code'];
				$user_array['api_key'] = NULL;
				$user_array['orderId'] = $orderId;
				$user_array['user_id'] = $user_id;
				$user_array['address'] = $address->address.' '.$address->landmark.' '.$address->locality.' '.$address->pincode;
				$user_array['mobile'] = $data['mobile'];
				$user_array['email'] = $data['email'];
				$user_array['Gender'] = $data['gender'];
				$user_array['age'] = $data['age'];
				$user_array['service_type'] = 'H';
				$user_array['pincode'] = $address->pincode;
				$user_array['address_id'] = $data['address_id'];
				$user_array['pay_type'] = $data['pay_type'];
				$user_array['bencount'] = "1";
				$user_array['bendataxml'] = NULL;
				$user_array['coupon_id'] = $data['coupon_id'];
				$user_array['order_by'] = $data['name'];
				$user_array['rate'] = (int) base64_decode($data['payable_amt']);
				$user_array['hc'] = 0;
				$user_array['reports'] = isset($data['report_type']) ? $data['report_type'] : 'no';
				$user_array['ref_code'] = "9414061829";
				$user_array['total_amt'] = base64_decode($data['total_amount']);
				$user_array['discount_amt'] = base64_decode($data['discount_amt']);
				$user_array['coupon_amt'] = base64_decode($data['coupon_amt']);
				$user_array['payable_amt'] = base64_decode($data['payable_amt']);
				$user_array['appt_date'] = $appt_date_time;
				$user_array['status'] = $data['status'];
				$user_array['order_status'] = $data['order_status'];
				$user_array['Margin'] = $data['Margin'];
				$user_array['service_charge'] = base64_decode($data['service_charge']);
				$user_array['product'] = null;
				$user_array['items'] = $packages;
				$user_array['report_code'] = null;
				$user_array['plan_id'] = (isset($data['plan_id'])) ? $data['plan_id'] : null;
				$meta_data = json_encode($user_array);
				
				if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])) {
					$appt_date = strtotime($user_array['appt_date']);
				}
				LabOrders::where(["orderId"=>$user_array['orderId']])->update([
					'type' => Session::get("lab_company_type") !== NULL ? Session::get("lab_company_type") : 1,
					'user_id' => $user_array['user_id'],
					'address_id' => $user_array['address_id'],
					'product' => $user_array['product'],
					'pay_type' => $user_array['pay_type'],
					'coupon_id' => $user_array['coupon_id'],
					'order_by' => $user_array['order_by'],
					'order_type' => 0,
					'report_type' => $user_array['reports'],
					'total_amt' => $user_array['total_amt'],
					'discount_amt' => $user_array['discount_amt'],
					'coupon_amt' => $user_array['coupon_amt'],
					'payable_amt' => $user_array['payable_amt'],
					'meta_data' => $meta_data,
					'appt_date' => $appt_date,
					'plan_id' => $user_array['plan_id'],
					'payment_mode_type' => 3,
					'status' => 0
				]);
				$product_name = [];
				if(count($packages) > 0) {
					foreach($packages as $itm) {
						if($itm['lab_cart_type'] == 'package') {
							// if(isset($itm['labs']) && count($itm['labs'])>0){
								// foreach($itm['labs'] as $raw) {
									LabOrderedItems::create([
										'package_id' => $itm['id'],
										'order_id' => $lab->id,
										// 'user_lab_id' => $raw['id'],
										'product_name' => $itm['DefaultLabs']['title'],
										'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
										'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
										'item_type' => "CUSTOM",
									]);
								// }
							// }
							$product_name[] = $itm['title'];
						}
						else{
							LabOrderedItems::create([
								'order_id' => $lab->id,
								'user_lab_id' => $itm['id'],
								'product_name' => $itm['DefaultLabs']['title'],
								'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
								'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
								'item_type' => "CUSTOM",
							]);
							$product_name[] = $itm['DefaultLabs']['title'];
						}
					}
				}
				LabOrders::where(["orderId"=>$user_array['orderId']])->update([
					'product' => count($product_name) > 0 ? implode(",",$product_name) : null
				]);
				if($user_array['pay_type'] == "Postpaid" || $user_array['coupon_code'] == "HGCash" || $user_array['coupon_code'] == "HGSUBSCRIBED") {
					LabOrders::where(["orderId"=>$user_array['orderId']])->update([
						'order_status' => 'YET TO ASSIGN',
						'is_free_appt'=>1,
						'status'=>1,
					]);
				}
				else {
					return $this->labCheckout($user_array['orderId'], $user_array['api_key'], $user_array['pincode'], $user_array['appt_date'], $user_array['payable_amt'],$user_array['order_by']);
				}
				if($user_array['coupon_code'] == "HGCash" || $user_array['coupon_code'] == "HGSUBSCRIBED") {
					LabOrderTxn::create([
						'order_id' => $user_array['orderId'],
						'tran_mode'=> "Cash",
						'payed_amount'=>$user_array['payable_amt'],
						'tran_status' => "Success",
						'currency' => "INR",
						'trans_date' => date('d-m-Y')
					]);
				}
				$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.implode(",",$product_name).') booking is confirmed with Healthgennie on '.$appt_date_d.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
				// $this->sendSMS($user_array['mobile'],$message,'1707165122333414122');
				
				$message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.implode(",",$product_name).') with Reliable lab on '.$appt_date_d.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
				// $this->sendSMS(implode(",",getSetting("support_contact_numbers")),$message,'1707165122295538821');
				LabCart::where(['user_id' => $user_array['user_id']])->delete();
				return ['msg'=>'Lab Created Successfully'];
			}
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
						// 'appt_date'   => 'required|max:50',
						// 'appt_time'   => 'required|max:50',
						'total_amount'   => 'required',
						'payable_amt'   => 'required',
					]);
					if($validator->fails()) {
						return 4;
					}
					else {
						if(Session::get("lab_company_type") != 0){
							return $this->createCustomLabOrder($data);
						}
						else{
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
							$testNames = [];
							foreach($packages as $key => $value) {
								if(in_array($value['name'],$final_products)) {
									if ($value['type'] == 'OFFER') {
										$product_name[] = $value['name'];
										$testNames[] = $value['testNames'];
										$product_price[] = $value['rate']['b2C'];
										$code[] = $value['code'];
									}
									else if($value['type'] == 'PROFILE') {
										$product_name[] = $value['name'];
										$testNames[] = $value['testNames'];
										$product_price[] = $value['rate']['b2C'];
									}
									else{
										$product_name[] = $value['name'];
										$testNames[] = $value['testNames'];
										$product_price[] = $value['rate']['b2C'];
									}
								}
							}
							$testNames = implode(",",$testNames);
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
							$user_array['availWalletAmt'] = (int) base64_decode($data['wallet_amount']);
							$user_array['api_key'] = Session::get('API_KEY');
							$user_array['orderId'] = $orderId;
							$user_array['user_id'] = $user_id;
							$user_array['address'] = $address->address.' '.$address->landmark.' '.$address->locality.' '.$address->pincode;
							$user_array['mobile'] = $data['mobile'];
							$user_array['email'] = $data['email'];
							$user_array['Gender'] = $data['gender'];
							$user_array['age'] = $data['age'];
							$user_array['service_type'] = 'H';
							$user_array['pincode'] = $address->pincode;
							$user_array['address_id'] = $data['address_id'];
							$user_array['pay_type'] = $data['pay_type'];
							$user_array['bencount'] = "1";
							$user_array['bendataxml'] = $bendataxml;
							$user_array['coupon_id'] = $data['coupon_id'];
							$user_array['order_by'] = $data['name'];
							$user_array['rate'] = (int) base64_decode($data['payable_amt']);
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
							$user_array['Margin'] = $data['Margin'];
							$user_array['service_charge'] = $data['service_charge'];
							$user_array['product'] = $product_name;
							$user_array['items'] = $packages;
							$user_array['report_code'] = $report_code;
							$user_array['Passon'] = 0;
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
								'order_type' => 0,
								'report_type' => $user_array['reports'],
								'total_amt' => $user_array['total_amt'],
								'discount_amt' => $user_array['discount_amt'],
								'coupon_amt' => $user_array['coupon_amt'],
								'payable_amt' => $user_array['payable_amt'],
								'meta_data' => $meta_data,
								'appt_date' => $appt_date,
								'plan_id' => $user_array['plan_id'],
								'payment_mode_type' => 1,
								'status' => 0
							]);
							// pr($user_array);
							if ($user_array['pay_type'] == "Postpaid" || $user_array['coupon_code'] == "HGCash" || $user_array['coupon_code'] == "HGSUBSCRIBED") {
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
									'Product' => $testNames,
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
									'Passon' => 0,
									'Remarks' => ''
								);
								$output = getResponseByCurl($order_array,"https://velso.thyrocare.cloud/api/BookingMaster/DSABooking");
								// dd($output);
								if(!empty($output)  && $output['respId'] == 'RES02012') {
									$lab_id = LabOrders::select("id")->where(["orderId"=>$user_array['orderId']])->first();
									LabOrders::where(["orderId"=>$user_array['orderId']])->update([
										'ref_orderId' => $output['refOrderId'],
										'post_order_meta' => json_encode($output),
										'order_status' => $output['status'],
										'is_free_appt'=>1,
										'status'=>1,
										'payment_mode_type' => 3,
									]);
									if(count($packages) > 0) {
										foreach($packages as $itm) {
											$items = LabOrderedItems::create([
												'order_id' => $lab_id->id,
												'product_name' => $itm['name'],
												'cost' => $itm['rate']['b2C'],
												'discount_amt' => $itm['rate']['offerRate'],
												'margin' => $itm['margin'],
												'item_type' => $itm['type'],
											]);
										}
									}
									availWalletAmount($user_array['user_id'],5,$user_array['availWalletAmt']);
									
									$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.$user_array['product'].') booking is confirmed with Healthgennie on '.$appt_date.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
									$this->sendSMS($user_array['mobile'],$message,'1707165122333414122');
									
									$message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.$user_array['product'].') with Thyrocare lab on '.$appt_date.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
									$this->sendSMS(implode(",",getSetting("support_contact_numbers")),$message,'1707165122295538821');
								
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
								LabCart::where(['user_id' => $user_array['user_id']])->delete();
							    availWalletAmount($user_array['user_id'],5,$user_array['availWalletAmt']);
								return json_encode($output);
							}
							else {
								dd(323);
								return $this->labCheckout($user_array['orderId'], $user_array['api_key'], $user_array['pincode'], $user_array['appt_date'], $user_array['payable_amt'],$user_array['order_by']);
							}
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
				
				
				/*$parameters["MID"] = "yNnDQV03999999736874";		
				// $parameters["MID"] = "fiBzPH32318843731373";		
				$parameters["ORDER_ID"] = base64_decode($data['order_id']); 
				$parameters["CUST_ID"] =  base64_decode($data['order_by']);
				$parameters["TXN_AMOUNT"] = base64_decode($data['amount']); 
				// $parameters["TXN_AMOUNT"] = 1; 
				$parameters["CALLBACK_URL"] = url('paytmresponse'); 
				$order = Indipay::gateway('Paytm')->prepare($parameters);
				return Indipay::process($order);*/
				
				$user = User::where("id",base64_decode($data['order_by']))->first();
				$mbl = isset($user->mobile_no) ? $user->mobile_no : '0000000000';
				$email = !empty($user->email)   ? $user->email : 'test@mailinator.com';
				$parameters["order"] = base64_decode($data['order_id']);
				$parameters["amount"] = base64_decode($data['amount']);
				$parameters["user"] = base64_decode($data['order_by']);
				$parameters["mobile_number"] = $mbl;
				$parameters["email"] = $email;
				$parameters["callback_url"] = url('paytmresponse');
				$payment = PaytmWallet::with('receive');
				$payment->prepare($parameters);
				return $payment->receive();
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
						$post_data = ["ApiKey"=>$API_KEY,"OrderNo"=>$order->ref_orderId];
						$response_data = getResponseByCurl($post_data,"https://velso.thyrocare.cloud/api/OrderSummary/OrderSummary");
						if(!empty($response_data)){
							if(!empty($response_data) && isset($response_data['orderMaster'][0])) {
								if($response_data['orderMaster'][0]['status'] == "REPORTED" || $response_data['orderMaster'][0]['status'] == "DONE") {
									if($order->pay_type != "Prepaid" && $order->is_free_appt == "0"){
										LabOrders::where(["id"=>$order->id])->update([
											"order_status" => trim($response_data['orderMaster'][0]['status']),
											"status" => 1,
											"is_free_appt" => 1,
										]);
									}
									else{
										LabOrders::where(["id"=>$order->id])->update([
											"order_status" => trim($response_data['orderMaster'][0]['status']),
											"status" => 1,
										]);
									}
								}
								else{
									LabOrders::where(["id"=>$order->id])->update([
										"order_status" => trim($response_data['orderMaster'][0]['status']),
									]);
								}
								$order->order_status = $response_data['orderMaster'][0]['status'];
								if($order->pay_type == "Prepaid" && $order->user_id != null && $order->ref_orderId != null && $order->status != 1) {
									// $this->cancelOrderFunc($orderId,"Payment Not Completed");
									// $order->order_status = "CANCELLED";
								}
								if($response_data['orderMaster'][0]['status'] == "REPORTED" || $response_data['orderMaster'][0]['status'] == "DONE") {
									$report = LabReports::where(["order_id"=>$orderId])->first();
									if(empty($report)) {
									  $user_data = json_decode($order->meta_data,true);
									  $user_mobile = $user_data['mobile'];
									  $post_meta = json_decode($order->post_order_meta,true);
									  $lead_id = @$post_meta['ORDERRESPONSE']['PostOrderDataResponse'][0]['LEAD_ID'];
									  $post_data = ["Apikey"=>$API_KEY,"Displaytype"=>"GETREPORTS","Value"=>$lead_id,"Reporttype"=>"PDF","Mobile"=>$user_mobile];
									  $reportData = getResponseByCurl($post_data,"https://b2capi.thyrocare.com/APIs/order.svc/{APIKEY}/GETREPORTS/{VALUE}/{REPORTTYPE}/{MOBILE}/Myreport");
									  
									  // $url_myReport = "https://www.thyrocare.com/APIS/order.svc/".$API_KEY."/GETREPORTS/".$orderId."/xml/".$user_mobile."/Myreport";
									  // $url_myReportPdf = "https://www.thyrocare.com/APIS/order.svc/".$API_KEY."/GETREPORTS/".$lead_id."/pdf/".$user_mobile."/Myreport";

									  // $reportData = @file_get_contents($url_myReport);
									  // $reportData = json_decode($reportData,true);
									  $fName = "";$url_downloadPdf = "";
									  if(!empty($reportData)) {
										if($reportData['RES_ID'] == "RES0000") {
										  $url_download = $reportData['URL'];
										  // $patPath = base_path()."/uploads/userDocuments/".$order->user_id;
										  // if(!is_dir($patPath)){
											 // File::makeDirectory($patPath, $mode = 0777, true, true);
										  // }
										  // $fName = $order->user_id."".$order->orderId."".time().".pdf";
										  // file_put_contents($patPath."/".$fName,file_get_contents($url_download));
										  $items = LabReports::create([
											  'order_id' => $orderId,
											  'user_id' => $order->user_id,
											  'report_xml_name' => $fName,
											  'report_pdf_name' => $url_download,
											]);
										}
									  }

									  // $reportDataPdf = @file_get_contents($url_myReportPdf);
									  // $reportDataPdf = json_decode($reportDataPdf,true);
									  // if(!empty($reportDataPdf)) {
										// if($reportDataPdf['RESPONSE'] == "SUCCESS") {
										  // $url_downloadPdf = $reportDataPdf['URL'];
										// }
									  // }
									  // if(!empty(($fName))) {
										
									  // }
									}
								}
							}
						}
					}
					// dd($orders);
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
		return view($this->getView('lab.lab-order-details'),['order'=>$order,'coupanDetails'=>$coupanDetails]);
	}
	public function uploadPrescription(Request $request) {
		 try{
		 if ($request->isMethod('post')) { 
			 if(Auth::user()) {
				 $data = $request->all(); 
				 $fileName = null;
				 if($request->hasFile('document')) {
					  $document  = $request->file('document');
					  $fullName = str_replace(" ","",$document->getClientOriginalName());
					  $onlyName = explode('.',$fullName);
					  if(is_array($onlyName)){
						$fileName = $onlyName[0].time().".".$onlyName[1];
					  }
					  else{
						$fileName = $onlyName.time();
					  }
					  $request->file('document')->move(public_path("/medicine-files"), $fileName);
				 }
				 $med = MedicinePrescriptions::create(['user_id'=>Auth::user()->id,'prescription'=>$fileName]);
				 $labReq = LabRequests::create(['user_id'=>Auth::user()->id,'mobile_no'=>Auth::user()->mobile_no,'pres_id'=>$med->id]);
				 $message = urlencode('Dear '.Auth::user()->first_name.' '.Auth::user()->last_name.', Your lab test request has been received by Health Gennie. Our team will contact you for the details shortly. Thanks Team Health Gennie');
				 $this->sendSMS(Auth::user()->mobile_no,$message,'1707165122309302900');
				 
				 $req_date = date("Y-m-d", strtotime($labReq->created_at));
				 $req_time = date("h:i:s A", strtotime($labReq->created_at));
				 $message = urlencode('This patient '.Auth::user()->first_name.' '.Auth::user()->last_name.' has request a lab test on '.$req_date.' at '.$req_time.'. Patient Mobile: '.Auth::user()->mobile_no.'. Thanks, Team Health Gennie');
				 $this->sendSMS(implode(",",getSetting("support_contact_numbers")),$message,'1707165268637387346');
			 }
			// return redirect()->route('admin.labOrders')->withInput();
			Session::flash('message','File Upload Successfully');
			return redirect()->route('LabDashboard');
			// return redirect()->back(); //If you want to go back
		   }
		}
		catch(\Exception $e){
			throw new \Exception($e->getMessage());
		}
		return redirect()->back();
    }
	public function getPrescriptions(Request $request) {
		$pres = [];
		if(Auth::user()){
			$pres = MedicinePrescriptions::where(['user_id'=>Auth::user()->id])->where('delete_status',1)->orderBy('id','DESC')->get();
			if(count($pres)>0){
				foreach($pres as $raw) {
					$raw['presUrl'] = url("/")."/public/medicine-files/".$raw->prescription;
					$file_nm = $raw->prescription;
					$file_ext = explode('.', $file_nm);
					$file_ext_count = count($file_ext);
					$cnt = $file_ext_count - 1;
					$file_extension = $file_ext[$cnt];
					$raw['file_ext'] = $file_extension;
				}
			}
		}		
		return view($this->getView('lab.prescriptions'),['pres'=>$pres]);
	}
	public function removePrescription(Request $request) {
		 try{
		 if ($request->isMethod('post')) { 
			 if(Auth::user()) {
				 $data = $request->all(); 
				 // $fileName = null;
				 // if(isset($data['fileName'])) {
					// $oldFilename = public_path()."/medicine-files/".$data['fileName'];
					// if(file_exists($oldFilename)) {
					   // File::delete($oldFilename);
					// }
				 // }
				 MedicinePrescriptions::where(['id'=>$data['rawId']])->update(['delete_status'=>0]);
			 }
			return 1; //If you want to go back
		   }
		}
		catch(\Exception $e){
			throw new \Exception($e->getMessage());
		}
		return redirect()->back();
	}
}