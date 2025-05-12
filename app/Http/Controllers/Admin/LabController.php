<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\LabPrice;
use App\Models\NewLabCompanyPrice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\LabPincode;
use App\Models\Doctors;
use App\Models\LabCollection;
use App\Models\LabCompany;
use App\Models\OrganizationMaster;
use Response;
use PaytmWallet;
use App\Models\DefaultLabs;
/**ehr db models */
use App\Imports\Laborder;
use App\Models\ehr\User as ehrUser;
use App\Models\ehr\PracticeDetails;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
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
use App\Models\UsersLabordersAddress;
use App\Models\LabOrders;
use App\Models\LabReports;
use App\Models\LabPackage;
use App\Models\ThyrocareLab;
use App\Models\ApptLink;
use App\Models\Exports\QueriesExport;
use Illuminate\Support\Facades\Request as Input;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
class LabController extends Controller {
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
//  public function __construct() {
// 	if(!Session::has('API_KEY')) {
// 		$this->setSessionAPIKey();
// 	}
//  }
//  public function setSessionAPIKey() {

// 	try{
// 	 if(!Session::has('API_KEY')) {
// 		Session::forget('API_KEY');
// 		Session::put('API_KEY', getThyrocareKey_Mobile()['API_KEY']);
	
// 		Session::put('dsa_mobile', getThyrocareKey_Mobile()['dsa_mobile']);
// 		Session::save();
// 	 }

// 	}catch(Exception $e){

// 		return $e->getMessage();

// 	}  

//  }
 public function labOrders(Request $request) {

	try{
	if ($request->isMethod('post')) {
		$params = array();
		 if (!empty($request->input('start_date'))) {
             $params['start_date'] = base64_encode($request->input('start_date'));
         }
		 if (!empty($request->input('end_date'))) {
             $params['end_date'] = base64_encode($request->input('end_date'));
         }
		if (!empty($request->input('filter'))) {
		 $params['filter'] = base64_encode($request->input('filter'));
		}
		if (!empty($request->input('page_no'))) {
		 $params['page_no'] = base64_encode($request->input('page_no'));
		}
		if (!empty($request->input('pay_type'))) {
					$params['pay_type'] = base64_encode($request->input('pay_type'));
					
		}
		if (!empty($request->input('ref_orderId'))) {
					$params['ref_orderId'] = base64_encode($request->input('ref_orderId'));
			}
		if (!empty($request->input('order_status'))) {
				 $params['order_status'] = base64_encode($request->input('order_status'));
		 }
		 if(!empty($request->input('order_by'))) {
				$params['order_by'] = base64_encode($request->input('order_by'));
		}
		if($request->input('order_type') != "") {
		 $params['order_type'] = base64_encode($request->input('order_type'));
		}
		if ($request->input('status')!= "") {
				 $params['status'] = base64_encode($request->input('status'));
		 }
		if ($request->input('file_type')!= "") {
				 $params['file_type'] = base64_encode($request->input('file_type'));
		 }
		 if ($request->input('filter_by')!= "") {
			$params['filter_by'] = base64_encode($request->input('filter_by'));
	     }
		 if ($request->input('payment_mode') != "") {
             $params['payment_mode'] = base64_encode($request->input('payment_mode'));
         }
		 if ($request->input('organization') != "") {
			$params['organization'] = base64_encode($request->input('organization'));
		}
		return redirect()->route('admin.labOrders',$params)->withInput();
		}
		else {
			$organization=OrganizationMaster::where('delete_status','1')->get();
			$query = LabOrders::query()->with(['LabOrderTxn','LabOrderedItems','LabReports','user','UsersLabordersAddress']);
			if(!empty($request->input('filter_by'))) {
				$filter_by = base64_decode($request->input('filter_by'));
				if($filter_by == 1) {
					if(!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
						if(!empty($request->input('start_date'))) {
							$start_date = strtotime(base64_decode($request->input('start_date')));
							$query->whereRaw('appt_date >= ?', [$start_date]);
						}
						if(!empty($request->input('end_date')))	{
							$end_date = strtotime(base64_decode($request->input('end_date'))." 23:59:59");
							$query->whereRaw('appt_date <= ?', [$end_date]);
						}
					}
				}
				else if($filter_by != 2) {
					if(!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
						if(!empty($request->input('start_date'))) {
							$start_date = date('Y-m-d',strtotime(base64_decode($request->input('start_date'))));
							$query->whereRaw('date(created_at) >= ?', [$start_date]);
						}
						if(!empty($request->input('end_date')))	{
							$end_date = date('Y-m-d',strtotime(base64_decode($request->input('end_date'))));
							$query->whereRaw('date(created_at) <= ?', [$end_date]);
						}
					}
				}
			}
			else {
				if(!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
					if(!empty($request->input('start_date'))) {
						$start_date = date('Y-m-d',strtotime(base64_decode($request->input('start_date'))));
						$query->whereRaw('date(created_at) >= ?', [$start_date]);
					}
					if(!empty($request->input('end_date')))	{
						$end_date = date('Y-m-d',strtotime(base64_decode($request->input('end_date'))));
						$query->whereRaw('date(created_at) <= ?', [$end_date]);
					}
				}
			}
			// pr($query->toSql());
			if($request->input('order_type')!="") {
				$query->where('type', base64_decode($request->input('order_type')));
			}
			if($request->input('organization')!="") {
				$organizationsearch = base64_decode($request->input('organization'));
					$query->where('org_id', '=', $organizationsearch);
		
			}
			// $query->whereHas('user.OrganizationMaster', function ($query) use ($organizationsearch) {	
			// 	$query->where('id', '=', $organizationsearch);	
			// });

			if(!empty($request->input('filter'))) {
				$filter = base64_decode($request->input('filter'));
				if ($filter == 1) {
					$query->whereIn('order_status', array('YET TO CONFIRM', 'YET TO ASSIGN'));
				}
				else if ($filter == 3) {
					$query->where('order_status', 'DONE');
				}
				else if ($filter == 4) {
					$query->where('order_status', 'CANCELLED');
				}
			}
			if(!empty($request->input('pay_type'))) {
				$pay_type = base64_decode($request->input('pay_type'));
				$query->where('pay_type', $pay_type);
			}

			// if(!empty($request->input('date'))) {
				// $date = base64_decode($request->input('date'));
				// if($date==0){
					// $query->orderBy('created_at', 'desc');
				// }else{
					// $query->orderBy('appt_date', 'desc');
				// }
				
				// }else{
					
				// }

			
				if(!empty($request->input('ref_orderId'))) {
					$ref_orderId = base64_decode($request->input('ref_orderId'));
					$query->where('orderId', 'like', '%'.$ref_orderId.'%');
				}

				if(!empty($request->input('order_by'))) {
					$order_by = base64_decode($request->input('order_by'));
					if(is_numeric($order_by)){
						$query->orWhereHas('user',function ($q)use($order_by){
							$q->where('mobile_no','like','%'.$order_by.'%');
						});
					}
					else{
						$query->where('order_by', 'like', '%'.$order_by.'%');
					}
				}
			
				if(!empty($request->input('order_status'))) {
					$order_status = base64_decode($request->input('order_status'));
					$query->where('order_status', 'like', '%'.$order_status.'%');
				}
				if(!empty($request->input('status'))) {
					$status = base64_decode($request->input('status'));
					$query->where('status',$status);
				}
				if($request->input('payment_mode')  != '') {
					$payment_mode = base64_decode($request->input('payment_mode'));
					$query->where('payment_mode_type',$payment_mode);
				}
			    $page = 25;
				if(!empty($request->input('page_no'))) {
					$page = base64_decode($request->input('page_no'));
				}
				$query->where("user_id",'!=',NULL)->where('delete_status', 1);
			
				$allCmpny = getLabComGrp();
				if(base64_decode($request->input('file_type')) == "excel") {
				$orders = $query->orderBy("id","DESC")->get(); 
				
				if((!empty($request->input('start_date')) || !empty($request->input('end_date'))) && !empty($request->input('filter_by'))) {
					$filter_by = base64_decode($request->input('filter_by'));
					if($filter_by == 2) {
						$newOrders = [];
						$start_date = date('Y-m-d',strtotime(base64_decode($request->input('start_date'))));
						$end_date = date('Y-m-d',strtotime(base64_decode($request->input('end_date'))));
						if($orders->count()>0) {
							foreach($orders as $raw){
								if(!empty($raw->LabOrderTxn->trans_date)) {
									$trans_date = date('Y-m-d',strtotime($raw->LabOrderTxn->trans_date));
									if($trans_date >= $start_date && $trans_date <= $end_date){
										$newOrders[] =  $raw;
									}
								}
							}
						}
						$orders = $newOrders;
					}
				}
				 $ordersDataArray[] = array('Sr. No.','Order From','Assigned Company','HG OrderNo','Order Number','Product','Schedule ','Pay Type','Payment Mode','Payable Amount ','Payment Status','Order Status','Order By','Order By mobile_no','Sale By','Organization','Transaction Date','Created At','Address');
				 foreach($orders as $i => $ord) {
					
					$labType = ""; 
					$payment_mode = "";
					if($ord->payment_mode_type == "1") {$payment_mode = "Online Payment";}
					 elseif($ord->payment_mode_type == "2"){ $payment_mode = "Cheque"; }
					 elseif($ord->payment_mode_type == "3"){ $payment_mode = "Cash"; }
					 elseif($ord->payment_mode_type == "4"){ $payment_mode = "Admin Online";}
					 elseif($ord->payment_mode_type == "5"){ $payment_mode = "Free";}
					 elseif($ord->payment_mode_type == "6"){ $payment_mode = "Payment Link";}
					 elseif($ord->payment_mode_type == "7"){ $payment_mode = "Bank/NEFT/RTGS/IMPS";}
					 elseif($ord->payment_mode_type == "8"){ $payment_mode = "Credit to CCL";}
					 elseif($ord->payment_mode_type == "9"){ $payment_mode = "Credit to RELIABLE";}
					 elseif($ord->payment_mode_type == "10"){ $payment_mode = "Credit to LIVING ROOT";}
				   if ($ord->status == 0) {
					 $status = "pending";
				   }
				   elseif ($ord->status == 1) {
					$status = "completed";
				   }
				   elseif ($ord->status == 2) {
					 $status = "cancelled";
				   }
				   else {
					 $status = "failure transaction";
				   }
				   $addedBy = "User";
				   if($ord->added_by != 0){ $addedBy = getNameByLoginId($ord->added_by);}
				   $meta_data = json_decode($ord->meta_data);
				   
				   if($ord->lab_type==0){$labType = "Free";}
				   $type = ($ord->type == 0) ? 2 : $ord->type;
					$ordersDataArray[] = array(
						 $i+1,
						 $allCmpny[$type],
						 @$ord->LabCompany->title,
						 $ord->orderId,
						 $ord->ref_orderId,
						 $ord->product,
						 date('d M Y h:i A',$ord->appt_date),
						 $ord->pay_type,
						 $payment_mode,
						 $ord->payable_amt,
						 $status."  ".$labType,
						 $ord->order_status,
						 $ord->order_by,
						 @$meta_data->mobile,
						 $addedBy,
						 @$ord->user->OrganizationMaster->title,
						 (isset($ord->LabOrderTxn->trans_date)) ? date('Y-m-d',strtotime(@$ord->LabOrderTxn->trans_date)) : '',
						 $ord->created_at,
						 @$ord->user->address,
						 
					  );
				 }
				return Excel::download(new QueriesExport($ordersDataArray), 'laborder.xlsx');
			}
			$query->orderBy('id', 'desc');	
			if((!empty($request->input('start_date')) || !empty($request->input('end_date'))) && base64_decode($request->input('filter_by')) == 2) {
				$orders = $query->get();
				$newOrders = [];
				$start_date = date('Y-m-d',strtotime(base64_decode($request->input('start_date'))));
				$end_date = date('Y-m-d',strtotime(base64_decode($request->input('end_date'))));
				if($orders->count()>0) {
					foreach($orders as $raw){
						if(!empty($raw->LabOrderTxn->trans_date)) {
							$trans_date = date('Y-m-d',strtotime($raw->LabOrderTxn->trans_date));
							if($trans_date >= $start_date && $trans_date <= $end_date){
								$newOrders[] =  $raw;
							}
						}
					}
				}
				$orders = $newOrders;
				$input = Input::all();
				if (isset($input['page']) && !empty($input['page'])) { $currentPage = $input['page']; } else { $currentPage = 1; }
				$offset = ($currentPage * $page) - $page;
				$itemsForCurrentPage = array_slice($orders, $offset, $page, false);
				$orders =  new Paginator($itemsForCurrentPage, count($orders), $page,Paginator::resolveCurrentPage(), array('path' => Paginator::resolveCurrentPath()));
			}
			else{
				$orders = $query->paginate($page);
			}
			if(count($orders)>0) {
				$APiData =getThyrocareKey_Mobile();
				$API_KEY = $APiData['API_KEY'];
				$dsa_mobile = $APiData['dsa_mobile'];
			
				foreach($orders as $order) {
					if($order->type == 0 && $order->order_status != "CANCELLED" && $order->order_status != "DONE" && date('Y-m-d',strtotime($order->created_at)) == date('Y-m-d')) {
						$orderId = $order->orderId;
						$meta_data = json_decode($order->meta_data,true);
						$mobile_no = $meta_data['mobile'];
						$post_data = ["ApiKey"=>$API_KEY,"OrderNo"=>$order->ref_orderId];
						$response_data = getResponseByCurl($post_data,"https://velso.thyrocare.cloud/api/OrderSummary/OrderSummary");
						if(!empty($response_data) && isset($response_data['orderMaster'][0])) {
							$is_free_appt = 0;
							if($response_data['orderMaster'][0]['status'] == "REPORTED" || $response_data['orderMaster'][0]['status'] == "DONE") {
								if($order->pay_type != "Prepaid" && $order->is_free_appt == "0" && $order->order_status != "REPORTED" && $order->order_status != "DONE") {
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
									"order_status" => trim($response_data['orderMaster'][0]['status'])
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
									$lead_id = @$post_meta['ORDERRESPONSE']['PostOrderDataResponse'][0]['LEAD_ID'];
									$post_data = ["Apikey"=>$API_KEY,"Displaytype"=>"GETREPORTS","Value"=>$lead_id,"Reporttype"=>"PDF","Mobile"=>$user_mobile];
									$reportData = getResponseByCurl($post_data,"https://b2capi.thyrocare.com/APIs/order.svc/{APIKEY}/GETREPORTS/{VALUE}/{REPORTTYPE}/{MOBILE}/Myreport");
									if(!empty($reportData)) {
										if($reportData['RES_ID'] == "RES0000") {
											$url_downloadPdf = $reportData['URL'];
											LabReports::create([
											  'order_id' => $orderId,
											  'user_id' => $order->user_id,
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
					}
				}
			}
		}
		return view('admin.lab_order.order-list',compact('orders','organization'));

	}catch(Exception $e){

		return $e->getMessage();

	}  
	}

	public function cancelOrderFunc($orderId=null, $cancel_reason= null) {

		try{
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

	}catch(Exception $e){

		return $e->getMessage();

	}  
	}
	public function viewOrderDetails(Request $request) {

		try{
		if($request->isMethod('post')) {
			$data = $request->all();
			

			
			$order = LabOrders::with('LabOrderedItems')->where('id', $data['orderId'])->first();
		
	
			$orderAPI = null;
			if ($order !== null && $order->type == 0) {
				$APiData =getThyrocareKey_Mobile();
				$API_KEY = $APiData['API_KEY'];
			
				$post_data = array(
					'ApiKey' => $API_KEY,
					'OrderNo' => $order->ref_orderId,
				);
				$orderAPI = getResponseByCurl($post_data,"https://velso.thyrocare.cloud/api/OrderSummary/OrderSummary");
				if(!empty($orderAPI['orderMaster'])) {
				
					LabOrders::where(["id"=>$order->id])->update([
						"order_status" => trim($orderAPI['orderMaster'][0]['status']),
						"payment_mode_type" => trim($orderAPI['orderMaster'][0]['status'])
						
					]);
					$order->order_status = $orderAPI['orderMaster'][0]['status'];
				}
			}
			$coupanDetails = null;
			if (!empty($order->coupon_id)) {
				$coupanDetails = Coupons::where('id',$order->coupon_id)->first();
			}
			return view('admin.lab_order.view-order',compact('order','orderAPI','coupanDetails'));
		}

	}catch(Exception $e){

		return $e->getMessage();

	}  
	}
	public function deleteOrder(Request $request) {
		try{
		if ($request->isMethod('post')) {
			$data = $request->all();
			$order = LabOrders::where('orderId', $data['orderId'])->update(['delete_status' => 0]);
			return 1;
		}

	}catch(Exception $e){

		return $e->getMessage();

	}  
	}
	public function downloadLabBill(Request $request,$id) {
		try{
			$id = base64_decode($id);
			$order = LabOrders::with('LabOrderedItems','user')->where('id', $id)->first();
			$pdf = PDF::loadView('lab.DownloadLabBillReceiptPDF',compact('order'));
			return $pdf->download('pdfviewlabbill.pdf');
		}catch(Exception $e){
			return $e->getMessage();
		}
	}

	public function getLabsPackage(Request $request, $cmp_id) {
		$cmp_id = base64_decode($cmp_id);
		$labCollectiondata=[];
	
		$groups = ThyrocarePackageGroup::where(['delete_status'=>1])->orderBy('sequence','ASC')->get();
		if($cmp_id != 2) {

			$packages = LabPackage::with(["LabCompany","DefaultLabs"])->where(['company_id'=>$cmp_id])->where(['delete_status'=>1])->get();
			$labcollection = LabCollection::with("DefaultLabs")->where(['company_id'=>$cmp_id])->where('delete_status', '=', '1')->get();

			return response()->json(['package'=>$packages,'cmp_id'=>$cmp_id,'labcollection'=>$labcollection]);
		}
		else{
			$packages = getThyrocareData("ALL");
		}

		 return response()->json(['package'=>$packages,'cmp_id'=>$cmp_id]);
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
					$countCoupon = AppointmentOrder::select('id')->where('coupon_id',$query->id)->where('order_by',$data['order_by'])->where('order_status',1)->count();
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

   public function checkPincodeAvailability(Request $request) {
	if($request->isMethod('post')) {
	   $data = $request->all();
	   if($request->company_id != 2){
	
		   return LabPincode::where(['company_id'=>$request->company_id,'pincode'=>$data['pincode']])->count() > 0 ? 1 : 0;
	   }
	   else{
       
		// $API_KEY = Session::get('API_KEY');
		$APiData =getThyrocareKey_Mobile();
	
		$postdata = array(
		   'ApiKey' => $APiData['API_KEY'],
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
			if($request->company_id != 2) {
				$labTimings =  LabCompany::where(['id'=>$request->company_id])->first();
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
					$currentDate = date('Y-m-d'); // Current date
					$currentTime = strtotime(date("H:i")); // Current time in seconds
					$nextTwoHours = $currentTime + (2 * 3600); // 2 hours from now

					if (count($time_slot) > 0) {
					    foreach ($time_slot as $k => $val) {
						$gg = $val + ($labTimings->slot_duration * 60);
						$valFormatted = date("H:i", $val) . ' - ' . date("H:i", $gg);
						
						// Show next 2-hour slots only if the requested date is today
						if ($date == $currentDate) {
						    if ($val >= $currentTime && $val <= $nextTwoHours) {
							$slot_array[] = ['id' => $k, 'slot' => $valFormatted, 'slotMasterId' => $k];
						    }
						} else {
						    // Show all slots for future dates
						    $slot_array[] = ['id' => $k, 'slot' => $valFormatted, 'slotMasterId' => $k];
						}
					    }
					}


					return ['lSlotDataRes'=>$slot_array];
				}
			}
			else{
				$APiData =getThyrocareKey_Mobile();
				$API_KEY = $APiData['API_KEY'];
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
			
			// if(Auth::user() != null){
			//   $packages = getLabCart();
			// }
			// else{
			//   $packages = Session::get("CartPackages");
			// }
			// $products=DB::table('thyrocare_labs')->whereIn('id',[1,2,3])->get();
	       
			$packages = ThyrocareLab::whereIn('id',$data['packageIds'])->get()->toarray();
	
			// $report_type = 'N';
			foreach($packages as $key => $value) {
				if ($value['type'] == 'OFFER') {
					$product_name[] = $value['testNames'];
					$product_price[] = $value['rate']['b2C'];
				}
				elseif ($value['type'] == 'PROFILE') {
					$product_name[] = $value['testNames'];
					$product_price[] = $value['rate']['b2C'];
				}
				else {
					$product_price[] = $value['rate']['b2C'];
					if($value['code'] == 'HVA' || $value['code'] == 'SEEL' || $value['code'] == 'E22' || $value['code'] == 'BTHAL' || $value['code'] == 'CUA' || $value['code'] == 'ELEMENTS'
					|| $value['code'] == 'H3' || $value['code'] == 'H5' || $value['code'] == 'H6' || $value['code'] == 'MA' || $value['code'] == 'BEAP') {
						$product_name[] = $value['testNames'];
					}
					else{
						$product_name[] = $value['testNames'];
					}
				}
			}
			$product_name = implode(",",$product_name);
			$product_price = implode(",",$product_price);
		
			// $product_type = implode(",",$product_type);
			//dd($product_name);
			if(count($packages) > 0) {
				$APiData =getThyrocareKey_Mobile();
				$API_KEY = $APiData['API_KEY'];
				$dsa_mobile = $APiData['dsa_mobile'];
				$postdata = array(
					'ApiKey' => $API_KEY,
					'Products' => $product_name,
					'Rates' => $product_price,
					'ClientType' => 'PUBLIC',
					'Mobile' => $dsa_mobile,
					'BenCount' => '1',
					'Report' => '1',
					'Discount' => '',
					'Coupon' => '',
				);

				// dd($postdata);
			
				$response = getResponseByCurl($postdata,"https://velso.thyrocare.cloud/api/CartMaster/DSAViewCartDTL");
				
				if(!empty($response)){
					return $response;
				}
			}
		}
	 }



	 public function createLabOrder(Request $request) {
		
		if($request->isMethod('post')) {
			$data = $request->all();
		// dd($data);
			$validator = Validator::make($data, [
				'name'   => 'required|max:100',
				'gender'   => 'required|max:50',
				'email' => 'required|email|max:255',
				'mobile' => 'required|numeric',
				// 'address_id'   => 'required|max:50',
				'appt_date'   => 'required|max:50',
				'appt_time'   => 'required|max:50',
				'total_amount'   => 'required',
				
			]);

			if($validator->fails()) {
				return Response::json(array(
					'success' => false,
					'errors' => $validator->getMessageBag()
			
				), 400);
			}
			else {
				User::where('id', $data['user_id'])->update(array('organization' => $data['organization_id'],'address'=>$data['address'],'zipcode'=>$data['pincode']));
				if($request->lab_order != 2){
					
					return $this->createCustomLabOrder($data);
				}
				else{
					$appt_date = date("Y-m-d", strtotime($data['appt_date']));
					$appt_time = date("h:i:s A", strtotime($data['appt_time']));
					$appt_date_time = $appt_date.' '.$appt_time;
					$user_id = $data['user_id'];
					if($data['coupon_code'] == "HGSUBSCRIBED") {
						$prod_code = (isset($data['prod_code'])) ? $data['prod_code'] : null;
						$packages = getSubscriptionLabData($prod_code);
					}
					else{

                        foreach($data['lab'] as $lab){

							$explData=explode("_",$lab);

							$ProductId[]=$explData[1];


						}
						
						$packages = ThyrocareLab::whereIn('id',$ProductId)->get()->toarray();
						
					}
					// $final_products = $data['final_products'];
					// $final_products = explode(",",$final_products);
					
					$product_name = [];
					foreach($packages as $key => $value) {
			
						if ($value['type'] == 'OFFER') {
							$product_name[] = $value['testNames'];
							$product_price[] = $value['rate']['b2C'];
							$code[] = $value['code'];
						}
						elseif ($value['type'] == 'PROFILE') {
							$product_name[] = $value['testNames'];
							$product_price[] = $value['rate']['b2C'];

							$code[] = $value['code'];
						}
						else {
							$product_price[] = $value['rate']['b2C'];
							if($value['code'] == 'HVA' || $value['code'] == 'SEEL' || $value['code'] == 'E22' || $value['code'] == 'BTHAL' || $value['code'] == 'CUA' || $value['code'] == 'ELEMENTS'
							|| $value['code'] == 'H3' || $value['code'] == 'H5' || $value['code'] == 'H6' || $value['code'] == 'MA' || $value['code'] == 'BEAP') {
								$product_name[] = $value['testNames'];
							}
							else{
								$product_name[] = $value['testNames'];
								$product_price[] = $value['rate']['b2C'];
								$code[] = $value['code'];
							}
						}
					}

					$product_name = implode(",",$product_name);
					$product_price = implode(",",$product_price);
					$report_code = implode(",",$code);
				
					$address = UsersLaborderAddresses::Where(['user_id' => $user_id,'id' => $data['address_id']])->first();
				  
					$lab = LabOrders::create();
					$orderId = $lab->id."LAB".rand(10,100);
					$created_at = $data['created_at']." ".date("H:i:s");
					LabOrders::where(["id"=>$lab->id])->update(["orderId"=>$orderId,'created_at'=>$created_at,'updated_at'=>$created_at]);
					$APiData =getThyrocareKey_Mobile();
					$API_KEY = $APiData['API_KEY'];
					$dsa_mobile = $APiData['dsa_mobile'];
				
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
					$user_array['api_key'] = $API_KEY ;
					$user_array['orderId'] = $orderId;
					$user_array['user_id'] = $user_id;
					if($address){
						$user_array['address'] = $address->address.' '.$address->landmark.' '.$address->locality.' '.$address->pincode;
					}else{
						$user_array['address'] = $data['address'].' '.$data['pincode'];	
					}
					$pay_type=$data['pay_type'];
					$user_array['mobile'] = $data['mobile'];
					$user_array['email'] = $data['email'];
					$user_array['Gender'] = $data['gender'];
					$user_array['age'] = $data['age'];
					$user_array['service_type'] = 'H';
					if($address){
						$user_array['address'] = $address->address.' '.$address->landmark.' '.$address->locality.' '.$address->pincode;
					}else{
						$user_array['pincode'] = $data['pincode'];	
					}
					// $user_array['pincode'] = $address->pincode;
					$user_array['address_id'] = $data['address_id'];
					if($data['pay_type']==3){
						$data['pay_type']='Prepaid';
					}
					if($data['pay_type']==5){
						$data['paymenttype']='5';
						$data['pay_type']='Prepaid';
					}
					if($data['pay_type']==6){
						$data['pay_link'] = '6';
						$data['pay_type']='Prepaid';
					}
					
					if($data['pay_type']==2){
						$data['pay_type']='Prepaid';
					}
					
					if($data['pay_type']==7){
						$data['pay_type']='Prepaid';
						$user_array['tracking_id'] = $data['tracking_id'];
					}
					
					if($data['pay_type']==8){
						$data['pay_type']='Prepaid';
					}
					
					if($data['pay_type']==9){
						$data['pay_type']='Prepaid';
					}
					
					if($data['pay_type']==10){
						$data['pay_type']='Prepaid';
					}

					if($data['pay_type']==4){
						$data['pay_type']='Prepaid';
						$user_array['tracking_id'] = $data['tracking_id'];
					}else{
						$user_array['tracking_id'] = '';
					}

					// $user_array['login_id'] => Session::get('id');
                     $user_array['created_by'] = Session::get('id');
					$user_array['pay_type'] = $data['pay_type'];
					$user_array['bencount'] = "1";
					$user_array['bendataxml'] = $bendataxml;
					$user_array['coupon_id'] = $data['coupon_id'];
					$user_array['order_by'] = $data['name'];
					$user_array['rate'] = $data['total_amount'];
					$user_array['hc'] = 0;
					$user_array['reports'] = $data['report_type'];
					$user_array['ref_code'] = "9414061829";
					$user_array['total_amt'] = $data['total_amount'];
					$user_array['discount_amt'] = $data['discount_amt'];
					$user_array['coupon_amt'] =  $data['coupon_amt'];
					if($data['payable_amt']){
						$user_array['payable_amt'] = $data['payable_amt'];	
					}else{
						$user_array['payable_amt'] = $data['total_amount'];
					}
					
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
					$order=LabOrders::where(["orderId"=>$user_array['orderId']])->update([
						'created_by' => Session::get('id'),
						'user_id' => $user_array['user_id'],
					    'order_status' => $user_array['order_status'],
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
						'added_by' => $data['added_by'],
						'payment_mode_type' => $pay_type,
						'status' => 0
					]);

					
					$orderData=LabOrders::where(["id"=>$lab->id])->first();

					// pr($user_array);
					
						if(empty($user_array['report_code'])){
							$user_array['report_code'] = '';
						}
				
						
						// dd($user_array['product']);
						$order_array = array(
							'ApiKey' => $user_array['api_key'],
							'OrderId' => 'TEST',
							'Email' => $user_array['email'],
							'Gender' => $user_array['Gender'],
							'Address' => $user_array['address'],
							'Margin' => $user_array['Margin'],
							'Pincode' => $user_array['pincode'],
							'Product' => $user_array['product'],
							'Mobile' => $user_array['mobile'],
							'ServiceType' => $user_array['service_type'],
							'OrderBy' => $user_array['order_by'],
							'Rate' =>0,
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

						//  dd($order_array);
					
						
						$output = getResponseByCurl($order_array,"https://velso.thyrocare.cloud/api/BookingMaster/DSABooking");
				
						 if(!empty($output)  && $output['respId'] == 'RES02012') {

							$lab_id = LabOrders::select("id")->where(["orderId"=>$user_array['orderId']])->first();
							if(isset($data['pay_link']) && $data['pay_link'] == '6'){
							LabOrders::where(["orderId"=>$user_array['orderId']])->update([
								'ref_orderId' => $output['refOrderId'],
								'post_order_meta' => json_encode($output),
								'order_status' => $output['status'],
								'is_free_appt'=>1,
								'status'=>0,
							]);
							}else{

								LabOrders::where(["orderId"=>$user_array['orderId']])->update([
									'ref_orderId' => $output['refOrderId'],
									'post_order_meta' => json_encode($output),
									'order_status' => $output['status'],
									'is_free_appt'=>1,
									'status'=>1,
								]);
								
							}

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
							$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.$user_array['product'].') booking is confirmed with Healthgennie on '.$appt_date.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
							$this->sendSMS($user_array['mobile'],$message,'1707165122333414122');

							$message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.$user_array['product'].') with Thyrocare lab on '.$appt_date.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
							$this->sendSMS(implode(",",getSetting("support_contact_numbers")),$message,'1707165122295538821');
                 
							// dd($data);
							LabOrderTxn::create([
								'order_id' => $user_array['orderId'],
								'tran_mode'=> $data['pay_type'],
								'payed_amount'=>$data['total_amount'],
								'cheque_no'=>$data['cheque_no'],
								'cheque_payee_name'=>$data['cheque_payee_name'],
								'cheque_bank_name'=>$data['cheque_bank_name'],
								'cheque_date'=>$data['cheque_date'],
								'tran_status' => "Success",
								'currency' => "INR",
								'tracking_id'=>$user_array['tracking_id'],
								'trans_date' => data['trans_date']
							]);

						

                            // dd($LabOrderTxn->toArray());

							if(isset($data['pay_link']) && $data['pay_link'] == '6'){

								$lnk = route('admin.labPayment',[base64_encode($lab->id)]);
								$links = ApptLink::create([
									'type' => 4,
									'user_id' => $user_array['user_id'],
									'link' => $lnk,
									'order_id' => $lab->id,
									'createBy' => Session::get('id'),
									'meta_data' => json_encode($orderData),
								]);
								return ['type'=>2,'data'=>$links];
						    }
							
							if(isset($data['paymenttype']) && $data['paymenttype'] == '5'){
								LabOrders::where(["orderId"=>$user_array['orderId']])->update([
									'lab_type' => 0,
								]);
						    }
							// return json_encode($output);
							// User::where('id',$user_array['user_id'])->update(['email'=>$data['email']]);
							return ['status'=>true,'data'=>$user_array];
						}
						else{
							return ["status"=>0,'output'=>$output];
						}
						// if($user_array['coupon_code'] == "HGCash" || $user_array['coupon_code'] == "HGSUBSCRIBED") {
							// if($user_array['coupon_code'] == "HGSUBSCRIBED") {
							// 	PlanPeriods::where('id', $user_array['plan_id'])->update(array(
							// 	   'lab_pkg_remaining' => 0,
							// 	));
							// }
						
						// }
						// LabCart::where(['user_id' => $user_array['user_id']])->delete();
					
					
				}
			}
		}
	}


	public function labPaymentforlink(Request $request,$lab_id) {

		try{
		if(!empty($lab_id)) {
			$order = LabOrders::FindOrFail(base64_decode($lab_id));
		
			if(!empty($order)){
				$parameters = [];
				// $parameters["MID"] = "yNnDQV03999999736874";
				// $parameters["MID"] = "fiBzPH32318843731373";
				// $parameters["ORDER_ID"] = base64_decode($orderId);
				// $parameters["CUST_ID"] = @$appOrder->order_by;
				// $parameters["TXN_AMOUNT"] = $appOrder->order_total;
				// $parameters["CALLBACK_URL"] = url('paytmresponse');
				// $order = Indipay::gateway('Paytm')->prepare($parameters);
				// return Indipay::process($order);
				$mbl = @User::where("id",$order->user_id)->first()->mobile_no;
				$parameters["order"] = $order->orderId;
				$parameters["amount"] = '1';
				$parameters["user"] = @$order->user_id;
				$parameters["mobile_number"] = $mbl;
				$parameters["email"] = 'test';
				$parameters["callback_url"] = url('paytmresponse');
				$payment = PaytmWallet::with('receive');
				$payment->prepare($parameters);
				return $payment->receive();
			} else return abort('404');
		}
		else return abort('404');

		}catch(Exception $e){

			return $e->getMessage();

		}
	}


	public function createCustomLabOrder($data) {


		$ProductId=[];
		$ProductIdcol=[];
		$user_id = $data['user_id'];
		foreach($data['lab'] as $lab){

			$explData=explode("_",$lab);
			if($explData[2]=='labpackage'){
				$ProductId[]=$explData[1];	
			}else{
				$ProductIdcol[]=$explData[1];
			}
		

		}
				
		if(count($ProductId)>0){
			$packages = LabPackage::whereIn('id',$ProductId)->get();
		}
		if(count($ProductIdcol)>0){
			$labcollection = LabCollection::with("DefaultLabs")->whereIn('id',$ProductIdcol)->get()->toArray();
		}
        $payType=$data['pay_type'];
		if($data['pay_type']==3){
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==5){
			$data['paymenttype']='5';
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==2){
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==7){
			$data['pay_type']='Prepaid';
			$user_array['tracking_id'] = $data['tracking_id'];
		}
		
		if($data['pay_type']==8){
			$data['pay_type']='Prepaid';
		}
		
		if($data['pay_type']==9){
			$data['pay_type']='Prepaid';
		}
		
		if($data['pay_type']==10){
			$data['pay_type']='Prepaid';
		}

		if($data['pay_type']==6){
			$data['pay_link']='6';
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==4){
			$data['pay_type']='Prepaid';
			$user_array['tracking_id'] = $data['tracking_id'];
		}else{
			$user_array['tracking_id'] = '';
		}
	
		
		// $address = UsersLaborderAddresses::Where(['user_id' => $user_id,'id' => $data['address_id']])->first();
		$lab = LabOrders::create();
		$orderId = $lab->id."LAB".rand(10,100);
		LabOrders::where(["id"=>$lab->id])->update(["orderId"=>$orderId]);
		$appt_date_d = date("d-m-Y", strtotime($data['appt_date']));
		$appt_date = date("Y-m-d", strtotime($data['appt_date']));
		$appt_time = date("h:i:s A", strtotime($data['appt_time']));
		$appt_date_time = $appt_date.' '.$appt_time;
		$user_array = array();
		$user_array['created_by'] = Session::get('id');
		$user_array['coupon_code'] = $data['coupon_code'];
		$user_array['api_key'] = NULL;
		$user_array['orderId'] = $orderId;
		$user_array['user_id'] = $user_id;
		$user_array['address'] = $data['address'].' '.$data['pincode'];
		$user_array['mobile'] = $data['mobile'];
		$user_array['email'] = $data['email'];
		$user_array['Gender'] = $data['gender'];
		$user_array['age'] = $data['age'];
		$user_array['service_type'] = 'H';
		$user_array['pincode'] = $data['pincode'];
		$user_array['address_id'] = $data['address_id'];
		$user_array['pay_type'] = $data['pay_type'];
		$user_array['bencount'] = "1";
		$user_array['bendataxml'] = NULL;
		$user_array['coupon_id'] = $data['coupon_id'];
		$user_array['order_by'] = $data['name'];
		$user_array['rate'] = $data['total_amount'];
		$user_array['hc'] = 0;
		$user_array['reports'] = isset($data['report_type']) ? $data['report_type'] : 'no';
		$user_array['ref_code'] = "9414061829";
		$user_array['total_amt'] = $data['total_amount'];
		$user_array['discount_amt'] = $data['discount_amt'];
		$user_array['coupon_amt'] = $data['coupon_amt'];
		if($data['payable_amt']){
			$user_array['payable_amt'] = $data['payable_amt'];
		}else{
			$user_array['payable_amt'] = $data['total_amount'];
		}
		
		$user_array['appt_date'] = $appt_date_time;
		$user_array['status'] = $data['status'];
		$user_array['order_status'] = $data['order_status'];
		$user_array['Margin'] = $data['Margin'];
		$user_array['service_charge'] = $data['service_charge'];
		$user_array['product'] = null;
		$user_array['items'] = '32423432';
		$user_array['report_code'] = null;
		$user_array['plan_id'] = (isset($data['plan_id'])) ? $data['plan_id'] : null;
		$user_array['organization_id'] = (isset($data['organization_id'])) ? $data['organization_id'] : null;
		$meta_data = json_encode($user_array);

		if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])) {
			$appt_date = strtotime($user_array['appt_date']);
		}
		$created_at = $data['created_at']." ".date("H:i:s");
		LabOrders::where(["orderId"=>$user_array['orderId']])->update([
			'type' => $data['company_ids'],
			'created_by' => Session::get('id'),
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
			'order_status' => $user_array['order_status'],
			'added_by' => $data['added_by'],
			'payment_mode_type' => $payType,
			'created_at' => $created_at,
			'updated_at' => $created_at,
			'status' => 1,
			'org_id'=>$user_array['organization_id']
		]);
		$product_name = [];
	
		$orderData=LabOrders::where(["id"=>$lab->id])->first();
        
		if(isset($labcollection) && count($labcollection) > 0) {
			foreach($labcollection as $itms) {
			
				// dd($itm);
				// if($itm['lab_cart_type'] == 'package') {
					// if(isset($itm['labs']) && count($itm['labs'])>0){
						// foreach($itm['labs'] as $raw) {
							LabOrderedItems::create([
								'package_id' =>$itms['id'],
								'order_id' => $lab->id,
								// 'user_lab_id' => $raw['id'],
								'product_name' =>$itms['default_labs']['title'],
								'cost' => $itms['offer_rate'],
								'discount_amt' =>  $itms['cost'],
								'item_type' => "CUSTOM",
							]);
						// }
					// }
					$product_name[] = $itms['default_labs']['title'];
				// }
				// else{
				// 	LabOrderedItems::create([
				// 		'order_id' => $lab->id,
				// 		'user_lab_id' => $itm['id'],
				// 		'product_name' => $itm['DefaultLabs']['title'],
				// 		'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
				// 		'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
				// 		'item_type' => "CUSTOM",
				// 	]);
				// 	$product_name[] = $itm['DefaultLabs']['title'];
				// }
			}
		}

		
		if(isset($packages) && count($packages) > 0) {
			foreach($packages as $itm) {
				// dd($itm);
				// if($itm['lab_cart_type'] == 'package') {
					// if(isset($itm['labs']) && count($itm['labs'])>0){
						// foreach($itm['labs'] as $raw) {
							LabOrderedItems::create([
								'package_id' =>$itm->id,
								'order_id' => $lab->id,
								// 'user_lab_id' => $raw['id'],
								'product_name' => $itm->title,
								'cost' => $itm->price,
								'discount_amt' =>  $itm->discount_price,
								'item_type' => "CUSTOM",
							]);
						// }
					// }
					$product_name[] = $itm->title;
				// }
				// else{
				// 	LabOrderedItems::create([
				// 		'order_id' => $lab->id,
				// 		'user_lab_id' => $itm['id'],
				// 		'product_name' => $itm['DefaultLabs']['title'],
				// 		'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
				// 		'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
				// 		'item_type' => "CUSTOM",
				// 	]);
				// 	$product_name[] = $itm['DefaultLabs']['title'];
				// }
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
		

			if(isset($data['pay_link']) && $data['pay_link'] == '6'){
				LabOrders::where(["orderId"=>$user_array['orderId']])->update([
				'status'=>0,
				]);
								
				$lnk = route('admin.labPayment',[base64_encode($lab->id)]);
				$links = ApptLink::create([
					'type' => 3,
					'user_id' => $user_array['user_id'],
					'link' => $lnk,
					'order_id' => $lab->id,
					'createBy' => Session::get('id'),
					'meta_data' => json_encode($orderData),
				]);
				$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.implode(",",$product_name).') booking is confirmed with Healthgennie on '.$appt_date_d.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
		        $this->sendSMS($user_array['mobile'],$message,'1707165122333414122');
				return ['type'=>2,'data'=>$links];
			}

			if(isset($data['paymenttype']) && $data['paymenttype'] == '5'){
				LabOrders::where(["orderId"=>$user_array['orderId']])->update([
					'lab_type' => 0,
				
				]);	
			}
			if(isset($payType) && ($payType == '4' ||  $payType == '3' ||  $payType == '7')) {
				LabOrderTxn::create([
					'order_id' => $user_array['orderId'],
					'tran_mode'=> 'Prepaid',
					'payed_amount'=>$data['total_amount'],
					'currency' => "INR",
					'tracking_id' => $data['tracking_id'],
					'trans_date' => $data['trans_date']
				]);
			}
			
			return ['status'=>true,'data'=>$user_array];
		}
		if($user_array['coupon_code'] == "HGCash" || $user_array['coupon_code'] == "HGSUBSCRIBED") {
			LabOrderTxn::create([
				'order_id' => $user_array['orderId'],
				'tran_mode'=> "Cash",
				'payed_amount'=>$user_array['total_amount'],
				'tran_status' => "Success",
				'currency' => "INR",
				'trans_date' => date('d-m-Y')
			]);
		}

		
		$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.implode(",",$product_name).') booking is confirmed with Healthgennie on '.$appt_date_d.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
		$this->sendSMS($user_array['mobile'],$message,'1707165122333414122');

		$message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.implode(",",$product_name).') with Reliable lab on '.$appt_date_d.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
		$this->sendSMS(implode(",",getSetting("support_contact_numbers")),$message,'1707165122295538821');
	
		// LabCart::where(['user_id' => $user_array['user_id']])->delete();
		
		
		
		return ['status'=>true,'data'=>$user_array];
	}


	public function chnagepayStatus(Request $request){


     
		$id=explode('_',$request->orderId);
		

		$updateStatus=LabOrders::where(["id"=>$id[1]])->update([
			'status' =>$id[0],
		
		]);	

		return $updateStatus;



	}

	public function getDefLabs(Request $request) {
		$title = $request->searchText;
		$labs = DefaultLabs::select('title','cost','discount','id')->where('title', 'like', '%'.$title.'%')->where('delete_status',1)->limit(10)->get()->toArray();
		 return $labs;
	 }
		public function createLabdefaultOrder(Request $request) {

		$data=$request->all();
		
		$validator = Validator::make($data, [
			'name'   => 'required|max:100',
			'gender'   => 'required|max:50',
			'age'   => 'required|max:200',
			'email' => 'required|email|max:255',
			'mobile' => 'required|numeric',
			"labs"    => "required",
			"labs.*"  => "required",
			'appt_date'   => 'required|max:50',
					
		]);
		if($validator->fails()) {
			return Response::json(array(
				'success' => false,
				'errors' => $validator->getMessageBag()
		
			), 400);
		}

		$packages = DefaultLabs::whereIn('id',$request['labs'])->get();
		
		if($data['pay_type']==3){
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==5){
			$data['paymenttype']='5';
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==2){
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==7){
			$data['pay_type']='Prepaid';
			$user_array['tracking_id'] = $data['tracking_id'];
		}
		if($data['pay_type']==8){
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==9){
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==10){
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==6){
			$data['pay_link']='6';
			$data['pay_type']='Prepaid';
		}
		if($data['pay_type']==4){
			$data['pay_type']='Prepaid';
			$user_array['tracking_id'] = $data['tracking_id'];
		}else{
			$user_array['tracking_id'] = '';
		}
	
				// $address = UsersLaborderAddresses::Where(['user_id' => $user_id,'id' => $data['address_id']])->first();
		$lab = LabOrders::create();
		$orderId = $lab->id."LAB".rand(10,100);
		LabOrders::where(["id"=>$lab->id])->update(["orderId"=>$orderId]);
		$appt_date_d = date("d-m-Y", strtotime($data['appt_date']));
		$appt_date = date("Y-m-d", strtotime($data['appt_date']));
	
		$appt_date_time = $appt_date;
		$user_array = array();
		$user_array['coupon_code'] = $data['coupon_code'];
		$user_array['api_key'] = NULL;
		$user_array['orderId'] = $orderId;
		$user_array['user_id'] = $data['user_id'];;
		$user_array['address'] = $data['address'].' '.$data['pincode'];
		$user_array['mobile'] = $data['mobile'];
		$user_array['email'] = $data['email'];
		$user_array['Gender'] = $data['gender'];
		$user_array['age'] = $data['age'];
		$user_array['service_type'] = 'H';
		$user_array['pincode'] = $data['pincode'];
		$user_array['address_id'] = $data['address_id'];
		$user_array['created_by'] = Session::get('id');
		$user_array['pay_type'] = $data['pay_type'];
		$user_array['bencount'] = "1";
		$user_array['bendataxml'] = NULL;
		$user_array['coupon_id'] = $data['coupon_id'];
		$user_array['order_by'] = $data['name'];
		$user_array['rate'] = $data['total_amount'];
		$user_array['hc'] = 0;
		$user_array['reports'] = isset($data['report_type']) ? $data['report_type'] : 'no';
		$user_array['ref_code'] = "9414061829";
		$user_array['total_amt'] = $data['total_amount'];
		$user_array['discount_amt'] = $data['discount_amt'];
		$user_array['coupon_amt'] = $data['coupon_amt'];
		$user_array['organization_id'] = (isset($data['organization_id'])) ? $data['organization_id'] : null;

		// if($data['service_charge']){
		// 	if($data['service_charge']>$data['payable_amt']){
		// 		$data['payable_amt']=$data['service_charge']-$data['payable_amt'];
		// 	}else{
		// 		$data['payable_amt']=$data['payable_amt']-$data['service_charge'];
		// 	}
			
		// }
		if($data['payable_amt']){
			$user_array['payable_amt'] = $data['payable_amt'];
		}else{
			$user_array['payable_amt'] = $data['total_amount'];
		}
		$user_array['appt_date'] = $appt_date_time;
		$user_array['status'] = $data['status'];
		$user_array['order_status'] = $data['order_status'];
		$user_array['Margin'] = $data['Margin'];
		$user_array['service_charge'] = $data['service_charge'];
		$user_array['product'] = null;
		$user_array['items'] = '32423432';
		$user_array['report_code'] = null;
		$user_array['plan_id'] = (isset($data['plan_id'])) ? $data['plan_id'] : null;
		
		$meta_data = json_encode($user_array);

		if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])){
			$appt_date = strtotime($user_array['appt_date']);
		}
		LabOrders::where(["orderId"=>$user_array['orderId']])->update([
			'type' => null,
			'user_id' => $user_array['user_id'],
			'address_id' => $user_array['address_id'],
			'product' => $user_array['product'],
			'created_by' => Session::get('id'),
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
			'order_status' => $user_array['order_status'],
			'service_charge' => $user_array['service_charge'],
			'status' => 1,
			'org_id'=>$user_array['organization_id']
		]);
		$product_name = [];
	
		$orderData=LabOrders::where(["id"=>$lab->id])->first();
   
		if(isset($packages) && count($packages) > 0) {
			foreach($packages as $itm) {
				
				// if($itm['lab_cart_type'] == 'package') {
					// if(isset($itm['labs']) && count($itm['labs'])>0){
						// foreach($itm['labs'] as $raw) {
							LabOrderedItems::create([
								'package_id' =>$itm->id,
								'order_id' => $lab->id,
								// 'user_lab_id' => $raw['id'],
								'product_name' => $itm->title,
								'cost' => $itm->cost,
								'discount_amt' =>  $itm->discount,
								'item_type' => "CUSTOM",
							]);
						// }
					// }
					$product_name[] = $itm->title;
				// }
				// else{
				// 	LabOrderedItems::create([
				// 		'order_id' => $lab->id,
				// 		'user_lab_id' => $itm['id'],
				// 		'product_name' => $itm['DefaultLabs']['title'],
				// 		'cost' => (!empty($itm['offer_rate'])) ? $itm['offer_rate'] : $itm['cost'],
				// 		'discount_amt' => (!empty($itm['offer_rate'])) ? $itm['cost'] - $itm['offer_rate'] : 0,
				// 		'item_type' => "CUSTOM",
				// 	]);
				// 	$product_name[] = $itm['DefaultLabs']['title'];
				// }
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
		

			if(isset($data['pay_link']) && $data['pay_link'] == '6'){
				LabOrders::where(["orderId"=>$user_array['orderId']])->update([
				'status'=>0,
				]);
								
				$lnk = route('admin.labPayment',[base64_encode($lab->id)]);
				$links = ApptLink::create([
					'type' => 3,
					'user_id' => $user_array['user_id'],
					'link' => $lnk,
					'order_id' => $lab->id,
					'createBy' => Session::get('id'),
					'meta_data' => json_encode($orderData),
				]);
				$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.implode(",",$product_name).') booking is confirmed with Healthgennie on '.$appt_date_d.' at '.$appt_date.'.Please be available at your location at the given time. Thanks Team Health Gennie');
		        $this->sendSMS($user_array['mobile'],$message,'1707165122333414122');
				return ['type'=>2,'data'=>$links];
			}

			if(isset($data['paymenttype']) && $data['paymenttype'] == '5'){
				LabOrders::where(["orderId"=>$user_array['orderId']])->update([
					'lab_type' => 0,
				
				]);	
			}
			
			return ['status'=>true,'data'=>$user_array];
		}
		if($user_array['coupon_code'] == "HGCash" || $user_array['coupon_code'] == "HGSUBSCRIBED") {
			LabOrderTxn::create([
				'order_id' => $user_array['orderId'],
				'tran_mode'=> "Cash",
				'payed_amount'=>$user_array['total_amount'],
				'tran_status' => "Success",
				'currency' => "INR",
				'trans_date' => date('d-m-Y')
			]);
		}

		
		$message = urlencode('Dear '.$user_array['order_by'].', Your Lab Test ('.implode(",",$product_name).') booking is confirmed with Healthgennie on '.$appt_date_d.' at '.$appt_time.'.Please be available at your location at the given time. Thanks Team Health Gennie');
		$this->sendSMS($user_array['mobile'],$message,'1707165122333414122');

		$message = urlencode('This patient('.$user_array['order_by'].') has booked a lab test ('.implode(",",$product_name).') with Reliable lab on '.$appt_date_d.' at '.$appt_time.'. Patient Mobile : '.$user_array['mobile'].' Thanks Team Health Gennie');
		$this->sendSMS(implode(",",getSetting("support_contact_numbers")),$message,'1707165122295538821');
	
		// LabCart::where(['user_id' => $user_array['user_id']])->delete();
		
		return ['status'=>true,'data'=>$user_array];
	}
	 public function changeCompanyStatus(Request $request){

		 $Arrdata=explode("_",$request->company_type);
		$daTa=LabOrders::where(["id"=>$Arrdata[1]])->update([
			"company_id" => $Arrdata[0]
		]);

		return $daTa;

	 }


	 
	 function labOrderImport(Request $request){
		$extensions = array("xls","xlsx","csv","ods");
		
		$fileextension = $request->file('laborder_file')->extension();
		 if(in_array($fileextension,$extensions)){
			
		 }else{
			Session::flash('error', "File type should be in xls,xlsx,csv,ods");
		    return back();
		 }

		Excel::import(new Laborder, $request->file('laborder_file'));

		Session::flash('message', "Data Imported successfully");
		return back();
		

	}
    public function updateNote(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $id = base64_decode($data['id']);
            $labNote = $data['note'];
            LabOrders::where('id',$id)->update([
                'remark' => $labNote,
            ]);
            return response()->json(1);
        }
    }
    public function addNewLabPrice(Request $request)
    {
        $companyId = $request->input('company_id');
        $labId = $request->input('lab_id');
        $amount = $request->input('amount');
        $query = LabPrice::where('company_id', $companyId)->where('lab_id', $labId)->first();;

        if($query) {
//            dd(12);
            $labPrice = $query->update(['amount' => $amount]);
        } else {
//            dd(1);
            LabPrice::create([
                "company_id" => $companyId,
                "lab_id" => $labId,
                "amount" => $amount
            ]);
        }
        return 1;

    }

    public function LabCompanyPrice(Request $request)
    {
        if ($request->isMethod('post')) {
            $params = [];

            if (!empty($request->input('company_id'))) {
                $params['company_id'] = base64_encode($request->input('company_id'));
            }

            if (!empty($request->input('lab_id'))) {
                $params['lab_id'] = base64_encode($request->input('lab_id'));
            }
            if (!empty($request->input('location'))) {
                $params['location'] = base64_encode($request->input('location'));
            }
            return redirect()->route('admin.LabCompanyPrice', $params)->withInput();
        } else {
//            dd(123);
            $lab = LabPrice::with(['company', 'defaultLab'])->orderByDesc('id');
            $perPage = 15;
            $location = base64_decode($request->input('location'));
//            dd($location);
            $companyId = base64_decode($request->input('company_id'));
            $labId = base64_decode($request->input('lab_id'));
            if($request->input('company_id')  != '') {
                $lab->where('company_id',$companyId);
            }
            if($request->input('lab_id')  != '') {
                $lab->where('lab_id',$labId);
            }
            if($request->input('location')  != '') {
                $lab->whereHas('company', function ($query) use ($location) {
                    $query->where('title', 'like', "%$location%");
                });
            }
            $lab = $lab->paginate($perPage);
            return view('admin.labCompanyPrice.index', compact('lab'));
        }
//        dd($request->all());
    }

    public function updateLabCompanyPrice(Request $request)
    {
        $id = $request->id;
        $lab = LabPrice::with(['company', 'defaultLab'])->Where( 'id', '=', $id)->first();
        if ($request->isMethod('post')) {
            $request->all();
            $labPrice = $request->input('amount');
            LabPrice::where('id',$id)->update([
                'amount' => $labPrice,
            ]);
            Session::flash('message', "Lab Price Updated Successfully");
        }
        return view('admin.labCompanyPrice.edit',compact('lab'));


    }

    public function uploadLabPrice(Request $request)
    {

        $extensions = array("xls","xlsx","csv","ods");

        $fileextension = $request->file('lab_price')->extension();
        if(in_array($fileextension,$extensions)){

        }else{
            Session::flash('error', "File type should be in xls,xlsx,csv,ods");
            return back();
        }

        Excel::import(new NewLabCompanyPrice, $request->file('lab_price'));

        Session::flash('message', "Data Imported successfully");
        return back();
    }


}
