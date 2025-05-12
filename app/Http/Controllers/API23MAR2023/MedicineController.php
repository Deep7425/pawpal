<?php
namespace App\Http\Controllers\API23MAR2023;
use App\Http\Controllers\API23MAR2023\APIBaseController as APIBaseController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;
// use Illuminate\Support\Facades\Input;
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
use Softon\Indipay\Facades\Indipay;
use Carbon\Carbon;
use App\Models\MedicineProductDetails;
use App\Models\MedicinePrescriptions;
use App\Models\MedicineOrders;
use App\Models\MedicineOrderedItems;
use App\Models\MedicineTxn;
use App\Models\UsersLaborderAddresses;
use App\Models\MedicineCart;
use App\Models\Coupons;
use PDF;
use PaytmWallet;
class MedicineController extends APIBaseController {

	function searchMedicine(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['search_key'] = $data->get('search_key');
		$user_array['user_id'] = $data->get('user_id');
		$medicines = [];
		$success = false;
		if($user_array['search_key'] != '') {
			/** For Doctor Name wise**/
			$medicine = MedicineProductDetails::Where(['delete_status'=>1,'status'=>1]);
			$search_key = $user_array['search_key'];
			$medicine->where(function ($q) use ($search_key) {
				$q->where(DB::raw('concat(name," ",IFNULL(composition_name,""))'), 'like', '%'.$search_key.'%')
				->orWhere('name', 'SOUNDS LIKE', '%'.$search_key);
			});
			$medicines = $medicine->orderBy("id","DESC")->limit(30)->get();
			if(count($medicines) > 0 ) {
				$success = true;
			}
		}
		return $this->sendResponse($medicines, 'Medicine get Successfully.',$success);
	}
	public function uploadPrescriptionImage(Request $request) {

    	if($request->isMethod('post')) {
			$data = $request->all();
			$validator = Validator::make($data, [
				'user_id' => 'required',
				'document' => 'required'
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$fileName = null;
				if($request->hasFile('document')){
					$images = $request->file('document');
					$fileName = str_replace(" ","",$images->getClientOriginalName());
					storeFileAwsBucket("public/lab-req-prescription/", $fileName, file_get_contents($images));
				}
				MedicinePrescriptions::create(['user_id'=>$data['user_id'],'prescription'=>$fileName]);
				$pres = $this->getPres($data['user_id']);
				return $this->sendResponse($pres, 'Prescription Image Uploaded Successfully.',true);
			}
		 }
	}
	function deletePrescriptionImage(Request $request) {
		$data=Input::json();
  		$user_array=array();
		$user_array['id'] = $data->get('id');
		// $user_array['prescription'] = $data->get('prescription');
		// $pres = public_path().'/medicine-files/'.$user_array['prescription'];
		// if(file_exists($pres)) {
			// File::delete($pres);
		// }
		MedicinePrescriptions::where(['id'=>$user_array['id']])->update(['delete_status'=>0]);
		return $this->sendResponse('', 'Prescription Image Deleted Successfully.',true);
	}
	public function createMedicineOrderOnline(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();
			$user_array=array();
            // $params =  json_decode(base64_decode($data['params']));
			// $user_array['user_id'] = $params->user_id;
			// $user_array['tax'] = $params->tax;
			// $user_array['order_subtotal'] = $params->order_subtotal;
			// $user_array['order_total'] = $params->order_total;
			// $user_array['medicine_id'] = $params->medicine_id;
			// $user_array['coupon_id'] = @$params->coupon_id;
			// $user_array['coupon_discount'] = @$params->coupon_discount;
			// $user_array['coupon_code'] = @$params->coupon_code;

			$user_array['user_id'] = $data->get('user_id');
			$user_array['address_id'] = $data->get('address_id');
			$user_array['coupon_id'] = $data->get('coupon_id');
			$user_array['order_by'] = $data->get('order_by');
			$user_array['order_subtotal'] = $data->get('order_subtotal');
			$user_array['order_total'] = $data->get('order_total');
			$user_array['coupon_discount'] = $data->get('coupon_discount');
			// $user_array['appt_date'] = $data->get('appt_date');
			$user_array['tax'] = $data->get('tax');
			$user_array['medicine_items'] = $data->get('medicine_items');

		  $validator = Validator::make($user_array, [
			'user_id'   =>  'required',
			'order_subtotal'      =>  'required',
			'order_total'      =>  'required',
			'medicine_items'      =>  'required',
		  ]);
		  if($validator->fails()){
				return $this->sendError($validator->errors());
		  }
		  else{
				$success = false;
				$orderId = "MED1";
				$med = MedicineOrders::orderBy("id","DESC")->first();
				if(!empty($med)){
					$sid = $med->id + 1;
					$orderId = "MED".$sid;
				}
				$order =  MedicineOrders::create([
					 'type' => 1,
					 'order_id' => $orderId,
					 'user_id' => $user_array['user_id'],
					 'address_id' => $user_array['address_id'],
					 'payment_mode' => 1,
					 'coupon_id' => $user_array['coupon_id'],
					 'coupon_discount' => $user_array['coupon_discount'],
					 'tax' => $user_array['tax'],
					 'order_subtotal' => $user_array['order_subtotal'],
					 'order_total' => $user_array['order_total'],
					 'status' => 0,
					 // 'appt_date' => $user_array['appt_date'],
					 'order_by' => $user_array['user_id'],
					 'meta_data' => json_encode($user_array),
				]);
				// $parameters = [];
				// $parameters["MID"] = "fiBzPH32318843731373";
				// $parameters["MID"] = "yNnDQV03999999736874";
				// $parameters["ORDER_ID"] = $orderId;
				// $parameters["CUST_ID"] = $user_array['user_id'];
				// $parameters["TXN_AMOUNT"] = $order->order_total;
				// $parameters["CALLBACK_URL"] = url('paytmresponse');
				// $order = Indipay::gateway('Paytm')->prepare($parameters);
				// return Indipay::process($order);
				
				$parameters = [];
				$user = User::where("id",$order->order_by)->first();
				$mbl = isset($user->mobile_no) ? $user->mobile_no : '0000000000';
				$email = !empty($user->email)   ? $user->email : 'test@mailinator.com';
				$parameters["order"] = $orderId;
				$parameters["amount"] = $order->order_total;
				$parameters["user"] = $user_array['user_id'];
				$parameters["mobile_number"] = $mbl;
				$parameters["email"] = $email;
				$parameters["callback_url"] = url('paytmresponse');
				$payment = PaytmWallet::with('receive');
				$payment->prepare($parameters);
				return $payment->receive();
			}
		}
	}
	public function getMedPrescription(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();
			$pres = [];
			$pres['uploadFromApp'] = $this->getPres($data->get('user_id'));
			$pres['byDoc'] = null;
			
			$order = MedicineOrders::where(['user_id'=>$data->get('user_id')])->where('delete_status',1)->orderBy('id','DESC')->first();
			if(!empty($order)) {
				$meta_data = json_decode($order->meta_data,true);
				if($meta_data['pres_type'] == '2' && $order->appId) {
					$pres['byDoc'] = getPresFile($order->appId);
				}
			}
			return $this->sendResponse($pres, 'Prescription get Successfully',$success = true);
		}
	}
	public function getPres($user_id){
		$pres = MedicinePrescriptions::where(['user_id'=>$user_id])->where('delete_status',1)->orderBy('id','DESC')->get();
		if(count($pres)>0){
			foreach($pres as $raw) {
				$raw['presUrl'] = getPath("public/lab-req-prescription/".$raw->prescription);
				$file_ext = explode('.', $raw->prescription);
				$file_ext_count = count($file_ext);
				$cnt = $file_ext_count - 1;
				$file_extension = $file_ext[$cnt];
				$raw['file_ext'] = 	$file_extension	;	
			}
		}
		return $pres;
	}
	public function getMedOrder(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();
			$orders = MedicineOrders::with(['MedicineOrderedItems.MedicineProductDetails','MedicineTxn','User','UsersLaborderAddresses'])->where(['user_id'=>$data->get('user_id')])->where('delete_status',1)->orderBy('id','DESC')->get();
			return $this->sendResponse($orders, 'Order get Successfully',$success = true);
		}
	}
	public function viewInvoice(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();
			$user_array=array();
			$user_array['id'] = $data->get('id');
			$validator = Validator::make($user_array, [
				'id' => 'required|max:50',
			]);
			if($validator->fails()){
				return $this->sendError('Validation Error.', $validator->errors());
			}
			else{
				$orders = MedicineOrders::with(['MedicineOrderedItems.MedicineProductDetails','MedicineTxn','User'])->where(['id'=>$user_array['id']])->where('delete_status',1)->first();
				$medData = view('medicine.receiptPDF',compact('orders'))->render();
				$output = PDF::loadHTML($medData)->output();
				file_put_contents(public_path()."/pdfviewforMedicine.pdf", $output);
				$pdf_url = 	url("/")."/public/pdfviewforMedicine.pdf";
				return $this->sendResponse($pdf_url,'',true);
			}
		}
	}
	public function updateMedCart(Request $request) {
			$data = Input::json();
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['medicines'] = $data->get('medicines');
			$user_array['type'] = $data->get('type');
			$validator = Validator::make($user_array, [
			'user_id'   =>  'required',
			]);
			if($validator->fails()){
				return $this->sendError($validator->errors());
			}
			else {
				if($user_array['type'] == "reorder") {
					if(count($user_array['medicines']) > 0 ) {
						foreach($user_array['medicines'] as $medicine) {
							$medicine_exists = MedicineCart::where(['user_id'=>$user_array['user_id'],'medicine_id' => $medicine['medicine_id']])->first();
							if(!empty($medicine_exists)) {
								$qty = $medicine_exists->qty + $medicine['qty'];
								MedicineCart::where("id",$medicine_exists->id)->update([
									'qty' => $qty
								]);		
							}
							else{
								MedicineCart::create([
									'user_id' => $user_array['user_id'],
									'medicine_id' => $medicine['medicine_id'],
									'qty' => $medicine['qty']
								]);
							}
						}
					}
				}
				else{
					MedicineCart::where(['user_id'=>$user_array['user_id']])->delete();
					if(count($user_array['medicines'])>0){
						foreach($user_array['medicines'] as $medicine) {
							$medicine_exists = MedicineCart::where(['user_id'=>$user_array['user_id'],'medicine_id' => $medicine['medicine_id']])->count();
							if($medicine_exists == 0) {
								MedicineCart::create([
									'user_id' => $user_array['user_id'],
									'medicine_id' => $medicine['medicine_id'],
									'qty' => $medicine['qty']
								]);
							}
						}
					}
				}
			return $this->sendResponse('','Updated Successfully',true);
		}
	}
	public function getMedCart(Request $request) {
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
				$medicines = MedicineCart::with('MedicineProductDetails')->where(['user_id'=>$user_array['user_id']])->get();
				$arr['medCart'] = $medicines;
				$arr['delivery_charge'] = $charge = getSetting("delivery_charge")[0];
				return $this->sendResponse($arr,'Updated Successfully',true);
			}
	}

	public function deleteMedCart(Request $request) {
			$data = Input::json();
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['medicine_id'] = $data->get('medicine_id');

			$validator = Validator::make($user_array, [
			'user_id'   =>  'required',
			]);
			if($validator->fails()){
							return $this->sendError($validator->errors());
						}
					else {
			MedicineCart::where(['user_id' => $user_array['user_id'], 'medicine_id' => $user_array['medicine_id']])->delete();
			return $this->sendResponse('','Deleted successfully',true);
		}
	}
	public function updateMedQty(Request $request) {
		$data = Input::json();
		$user_array=array();
		$user_array['user_id'] = $data->get('user_id');
		$user_array['id'] = $data->get('id');
		$user_array['qty'] = $data->get('qty');
		$validator = Validator::make($user_array, [
			'user_id'   =>  'required',
			'id'   =>  'required',
			'qty'   =>  'required',
		]);
		if($validator->fails()){
				return $this->sendError($validator->errors());
		}
		else {
			MedicineCart::where(['id'=>$user_array['id']])->update(['qty'=>$user_array['qty']]);
			$medicines = MedicineCart::with('MedicineProductDetails')->where(['user_id'=>$user_array['user_id']])->get();
			return $this->sendResponse($medicines,'Updated Successfully',true);
		}
	}
	public function createMedicineOrder(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();
			$user_array=array();
			$user_array['user_id'] = $data->get('user_id');
			$user_array['address_id'] = $data->get('address_id');
			// $user_array['coupon_id'] = $data->get('coupon_id');
			$user_array['order_by'] = $data->get('user_id');
			// $user_array['order_subtotal'] = $data->get('order_subtotal');
			// $user_array['order_total'] = $data->get('order_total');
			// $user_array['coupon_discount'] = $data->get('coupon_discount');
			// $user_array['appt_date'] = $data->get('appt_date');
			// $user_array['tax'] = $data->get('tax');
			$user_array['meds'] = $data->get('order');
			$order = $user_array['meds'];
			$user_array['pres_type'] = $data->get('pres_type');
			$user_array['meds'] = $order['cartItems'];
			$user_array['order_subtotal'] = $order['subtotal'];
			$user_array['order_total'] = $order['totpay'];
			$user_array['coupon_amt'] = $order['coupon_amt'];
			$user_array['coupon_id'] = $order['coupon_id'];
			$user_array['delivery_charge'] = $order['delivery_charge'];
			
		  $validator = Validator::make($user_array, [
			'user_id'   =>  'required',
			'order_subtotal'      =>  'required',
			'order_total'      =>  'required',
			'meds'      =>  'required',
		  ]);
		  if($validator->fails()){
				return $this->sendError($validator->errors());
		  }
		  else{
				$success = false;
				$orderId = "MED1";
				$med = MedicineOrders::orderBy("id","DESC")->first();
				if(!empty($med)){
					$sid = $med->id + 1;
					$orderId = "MED".$sid;
				}
				$order =  MedicineOrders::create([
					 'type' => 0,
					 'order_id' => $orderId,
					 'user_id' => $user_array['user_id'],
					 'pres_type' => $user_array['pres_type'],
					 'address_id' => $user_array['address_id'],
					 'payment_mode' => 1,
					 'coupon_id' => $user_array['coupon_id'],
					 'coupon_discount' => $user_array['coupon_amt'],
					 // 'tax' => $user_array['tax'],
					 'order_subtotal' => $user_array['order_subtotal'],
					 'order_total' => $user_array['order_total'],
					 'status' => 0,
					 // 'appt_date' => $user_array['appt_date'],
					 'order_by' => $user_array['user_id'],
					 'delivery_charge' => $user_array['delivery_charge'],
					 'meta_data' => json_encode($user_array),
				]);
				MedicineCart::where(['user_id'=>$user_array['user_id']])->delete();
				return $this->sendResponse($order,'Order Created successfully',true);
			}
		}
	}
	
	public function checkMedCouponCode(Request $request) {
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
				 $dt = date('Y-m-d');
				 $coupon_data =  Coupons::where('coupon_code',$user_array['coupon_code'])->where(['type'=>3])->first();
				 if(!empty($coupon_data)){
					 if($coupon_data->status != '1') {
						return $this->sendResponse('','Coupon Code does Not Active',false);
					 }
					 else if($coupon_data->coupon_last_date < $dt){
						 return $this->sendResponse('','Coupon Code Is Expired',false);
					 }
					 else{
						 $success = true;
						 return $this->sendResponse($coupon_data, 'Coupon Applied Successfully.',$success);
					 }
				 }
				 else{
					 return $this->sendResponse('','Coupon Code Not Matched.',false);
				 }
			}
		}
	}
	
	public function vieworderPres(Request $request) {
		if($request->isMethod('post')) {
			$data = Input::json();
			$order = MedicineOrders::with(['MedicineOrderedItems.MedicineProductDetails','MedicineTxn','User','UsersLaborderAddresses'])->where(['id'=>$data->get('order_id')])->first();
			if(!empty($order)) {
				$meta_data = json_decode($order->meta_data,true);
				if($meta_data['pres_type'] == '2' && $order->appId) {
					$order['prescription'] = getPresFile($order->appId);
				}
				else{
					$order['prescription'] = $this->getPres($data->get('user_id'));
				}
			}
			return $this->sendResponse($order, 'Order get Successfully',$success = true);
		}
	}
	public function cancelOrder(Request $request) {
		$data = Input::json();
		$user_array=array();
		$user_array['order_id'] = $data->get('order_id');
		$user_array['cancel_reason'] = $data->get('cancel_reason');
		$validator = Validator::make($user_array, [
			'order_id'   =>  'required',
		]);
		if($validator->fails()){
			return $this->sendError($validator->errors());
		}
		else {
			MedicineOrders::where(['id'=>$user_array['order_id']])->update([
				"order_status" => 4,
				"cancel_reason" => $user_array['cancel_reason']
			]);
			$order = MedicineOrders::with(['MedicineOrderedItems.MedicineProductDetails','MedicineTxn','User','UsersLaborderAddresses'])->where(['id'=>$user_array['order_id']])->get();
			return $this->sendResponse($order,'Order Cancelled Successfully',true);
		}
	}
}
