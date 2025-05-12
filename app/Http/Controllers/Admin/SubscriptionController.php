<?php

namespace App\Http\Controllers\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Admin\Admin;
use Illuminate\Support\Facades\Validator;
use App\Models\Plans;
use App\Models\PlanPeriods;
use App\Models\UserSubscribedPlans;
use App\Models\UsersSubscriptions;
use App\Models\OrganizationMaster;
use App\Models\UserSubscriptionsTxn;
use App\Models\ehr\Appointments;
use App\Models\ReferralMaster;
use App\Http\Controllers\PaytmChecksum;
use App\Models\Exports\SubscriptionExport;
use App\Imports\SubcritionImport;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Models\EmailTemplate;
use App\Models\Templates;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\ehr\AppointmentOrder;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use App\Models\ApptLink;
use App\Models\DailyReport;
use App\Models\Exports\CommonExport;
use App\Models\Exports\DepositExport;
use App\Models\Exports\SubscriptionCashbackExport;
use App\Models\ReferralCashback;
use App\Models\SubAmtDeposit;
use Illuminate\Support\Facades\Http;
use Razorpay\Api\Api;

class SubscriptionController extends Controller
{
    /*
     * Create a new controller instance.
     *
     * @return void
     */
   public function planMaster(Request $request)  {
     $search = '';
     if ($request->isMethod('post')) {
       $params = array();
         if (!empty($request->input('search'))) {
             $params['search'] = base64_encode($request->input('search'));
         }
		 if (!empty($request->input('page_no'))) {
             $params['page_no'] = base64_encode($request->input('page_no'));
         }
         return redirect()->route('plans.planMaster',$params)->withInput();
       }
       else {
         $filters = array();
         $plans = Plans::Where("delete_status",'1')->orderBy('id', 'desc')->paginate(25);
		 $page = 25;
		 if(!empty($request->input('page_no'))) {
			$page = base64_decode($request->input('page_no'));
		 }
         if ($request->input('search')  != '') {
           $search = base64_decode($request->input('search'));
           $plans = Plans::where('plan_title','like','%'.$search.'%')->Where("delete_status",'1')->orderBy('id', 'desc')->paginate($page);
         }
       }
       return view('admin.subscription.planMaster',compact('plans'));
   }

   public function planMasterAdd(Request $request){
         if ($request->isMethod('post')) {
          $data = $request->all();
          $user =  Plans::create([
          'plan_title' => $data['plan_title'],
          'slug' => $data['slug'],
          'price' => $data['price'],
          'discount_price' => $data['discount_price'],
          'plan_duration_type' => $data['plan_duration_type'],
          'plan_duration' => $data['plan_duration'],
          'appointment_cnt' => $data['appointment_cnt'],
          'specialist_appointment_cnt' => $data['specialist_appointment_cnt'],
          'counseling_session' => $data['counseling_session'],
           'max_appointment_fee' => $data['max_appointment_fee'],
          'lab_pkg' => $data['lab_pkg'],
          'lab_pkg_title' => $data['lab_pkg_title'],
          'max_patient_count' => $data['max_patient_count'],
          'content' => $data['content'],
          'is_best' => $data['is_best'],
          'type' => $data['type'],
          ]);
          Session::flash('message', "Plan Added Successfully");
          return 1;
       }
	   else{
		    return view('admin.subscription.planMasterAdd');
	   }
   }
   public function editPlans(Request $request) {
	   $data = $request->all();
       $plan = Plans::Where( 'id', '=', $data['id'])->first();
       return view('admin.subscription.planMasterEdit',compact('plan'));
   }
   public function updatePlansMaster(Request $request) {
     if ($request->isMethod('post')) {
         $data = $request->all();
         $id = $data['id'];
         $user =  Plans::where('id', $id)->update(array(
           'plan_title' => $data['plan_title'],
           'slug' => $data['slug'],
           'price' => $data['price'],
           'discount_price' => $data['discount_price'],
           'plan_duration_type' => $data['plan_duration_type'],
           'plan_duration' => $data['plan_duration'],
           'max_appointment_fee' => $data['max_appointment_fee'],
           'appointment_cnt' => $data['appointment_cnt'],
		   'specialist_appointment_cnt' => $data['specialist_appointment_cnt'],
           'lab_pkg' => $data['lab_pkg'],
           'lab_pkg_title' => $data['lab_pkg_title'],
           'max_patient_count' => $data['max_patient_count'],
           'content' => $data['content'],
           'is_best' => $data['is_best'],
		   'type' => $data['type'],
         ));
        Session::flash('message', "Plan Updated Successfully");
        return 1;
       }
   }
   public function deletePlanMaster(Request $request) {
	  $data = $request->all();
     Plans::where('id',$data['id'])->update(array('delete_status' => '0'));
     Session::flash('message', "Plan Deleted Successfully");
     return 1;
   }
   public function updatePlanStatus(Request $request) {
     if($request->isMethod('post')) {
        $data = $request->all();
        if($data['status'] == 0){
          Plans::where('id',$data['id'])->update(['status' => 1]);
          return 1;
        }
        else{
          Plans::where('id',$data['id'])->update(['status' => 0]);
          return 2;
        }
     }
   }

     public function subscriptionMaster(Request $request)
{
    if ($request->isMethod('post')) {
        $params = collect($request->except('_token'))->filter()->mapWithKeys(function ($value, $key) {
            return [$key => base64_encode($value)];
        })->toArray();
        return redirect()->route('subscription.subscriptionMaster', $params)->withInput();
    }

    $page = $request->filled('page_no') ? (int)base64_decode($request->page_no) : 25;
    $fileType = $request->filled('file_type') ? base64_decode($request->file_type) : null;

    $query = UsersSubscriptions::query()
        ->with(['PlanPeriods.Plans', 'UserSubscribedPlans.PlanPeriods', 'User', 'ReferralMaster'])
        ->whereNotNull('id');

    // Apply filters using when()
    $query->when($request->filled('type'), function ($q) use ($request) {
        $q->where('order_status', base64_decode($request->type));
    })->when($request->filled('payment_mode'), function ($q) use ($request) {
        $q->where('payment_mode', base64_decode($request->payment_mode));
    })->when($request->filled('plan_id'), function ($q) use ($request) {
        $planId = base64_decode($request->plan_id);
        $pIds = getPlanIdToSlug($planId);
        $q->whereHas('PlanPeriods', fn($subQ) => $subQ->whereIn('user_plan_id', $pIds));
    })->when($request->filled('search'), function ($q) use ($request) {
        $search = base64_decode($request->search);
        $q->where('plan_title', 'like', "%$search%");
    })->when($request->filled('organization_id'), function ($q) use ($request) {
        $q->where('organization_id', base64_decode($request->organization_id));
    })->when($request->filled('login_id'), function ($q) use ($request) {
        $q->where('login_id', base64_decode($request->login_id));
    })->when($request->filled('code'), function ($q) use ($request) {
        $code = base64_decode($request->code);
        if ($code === 'blank') {
            $q->whereNull('ref_code');
        } elseif ($code === 'emitra') {
            $q->where('hg_miniApp', 2);
        } else {
            $q->where('ref_code', $code)->where('hg_miniApp', '!=', 2);
        }
    })->when($request->filled('name'), function ($q) use ($request) {
        $name = base64_decode($request->name);
        $q->whereHas('User', function ($userQ) use ($name) {
            $userQ->where(function ($innerQ) use ($name) {
                $innerQ->where(DB::raw("CONCAT(IFNULL(first_name,''),' ',IFNULL(last_name,''))"), 'like', "%$name%")
                    ->orWhere('mobile_no', $name);
            });
        });
    })->when($request->filled('status'), function ($q) use ($request) {
        $status = base64_decode($request->status);
        $q->whereHas('PlanPeriods', fn($pQ) => $pQ->where('status', $status));
    });

    // Date and Time filtering
    if ($request->filled('start_date') || $request->filled('end_date')) {
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', date('Y-m-d', strtotime(base64_decode($request->start_date))));
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', date('Y-m-d', strtotime(base64_decode($request->end_date))));
        }
    }



    if ($request->filled('start_time') || $request->filled('end_time')) {
        if ($request->filled('start_time')) {
            $query->whereTime('created_at', '>=', date('H:i:s', strtotime(base64_decode($request->start_time))));
        }
        if ($request->filled('end_time')) {
            $query->whereTime('created_at', '<=', date('H:i:s', strtotime(base64_decode($request->end_time))));
        }
    }

    // Export logic
    if ($fileType === 'excel') {
        $subsData = $query->orderByDesc('id');
        return $this->exportToExcel($subsData);

    }

    if ($request->filled('location')) {
		$location = base64_decode($request->input('location'));
    
		$tempQuery = clone $query;
	
		$tempQuery->whereRaw("JSON_VALID(meta_data)")
				  ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(meta_data, '$.pw_location')) = ?", [$location]);
	
		if ($tempQuery->count() > 0) {
			$query->whereRaw("JSON_VALID(meta_data)")
				  ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(meta_data, '$.pw_location')) = ?", [$location]);
		} else {
			$query->whereHas('admin', function($q) use ($location) {
				$q->where('city', $location);
			});
		}
	}

    $subscriptions = $query->orderByDesc('id')->paginate($page);
    return view('admin.subscription.subscriptionMaster', compact('subscriptions'));

}


private function exportToExcel($query) {
    Artisan::call('queue:work', [
        '--queue' => 'bulkExportCSV,default',
        '--stop-when-empty' => true
    ]);

    $subsData = $query;

    $resourceNamespace = 'App\Http\Resources\SubscriptionResource';
        $columns = ['Sr. No.', 'Sale By', 'Order ID', 'User Name', 'Mobile', 'Payment Mode', 'Plan Type', 'Plan Actual rate', 'Discount offer', 'Tax', 'Payble Amount', 'Ref Code', 'Corportae name', 'Order Status', 'Subscription Date', 'Subs Time', 'Total Done Appointment', 'Remark'];

        $data = "subscription";

        \BulkExportCSV::build($subsData, $resourceNamespace, $columns , $data);

        $filename = \BulkExportCSV::download($subsData, $resource_namespace, $columns , $data);

    return response()->json([
        'status' => 'success',
        'message' => 'Excel Generated Successfully!',
        'file' => $filename,
        'url' => asset('public/storage/exportCSV/' . $filename) // optional: full download URL
    ]);
}




public function editRefCode(Request $request){
    if ($request->isMethod('post')) {
        $data = $request->all();
        return view('admin.subscription.editrefcode' , compact('data'));
    }
}
public function updateRefCode(Request $request){
    if ($request->isMethod('post')) {
        $data = $request->all();
        $userDataOld = ReferralMaster::where('id' , $data['referral_old'])->first();
        $userDataNew = ReferralMaster::where('id' , $data['code'])->first();
        $referralDisTypeOld =  @$userDataOld->referral_discount_type;
        $referralDisOld     = @$userDataOld->referral_discount;
        $referralDisTypeNew = @$userDataNew->referral_discount_type;
        $referralDisNew = @$userDataNew->referral_discount;
        // if($referralDisTypeOld ==  $referralDisTypeNew  &&  $referralDisOld ==  $referralDisNew  ){
            $userSubscription = UsersSubscriptions::where('id',$data['id'])->update(['ref_code'=> $data['code']]);
            return 1;
        // }else{
            // return 2;
        // }
    }
}

public function totInsSubs(Request $request) {
    $start_date = $request->start_date;
    $end_date = $request->end_date;
       // $query = UsersSubscriptions::with(["PlanPeriods.Plans","OrganizationMaster"])->whereHas('PlanPeriods', function($q)  {
             // $q->Where('status', 1);
           // })->where('order_status',1)
        // if(!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
           // if(!empty($request->input('start_date'))) {
               // $start_date = date('Y-m-d',strtotime(base64_decode($request->input('start_date'))));
               // $query->whereRaw('date(created_at) >= ?', [$start_date]);
           // }
           // if(!empty($request->input('end_date')))	{
               // $end_date = date('Y-m-d',strtotime(base64_decode($request->input('end_date'))));
               // $query->whereRaw('date(created_at) <= ?', [$end_date]);
           // }
       // }
       // $subscriptions = $query->groupBy('organization_id')->get();
       
       $organizations = OrganizationMaster::withCount(['subscriptions as total_subscriptions' => function ($query) use ($start_date,$end_date) {
       $query->where('order_status', 1)
           ->whereHas('PlanPeriods', function ($q) {
               $q->where('status', 1);
           });
       // Optional date filters
       if (!empty($start_date)) {
           $start_date = date('Y-m-d', strtotime($start_date));
           $query->whereDate('created_at', '>=', $start_date);
       }
       if (!empty($end_date)) {
           $end_date = date('Y-m-d', strtotime($end_date));
           $query->whereDate('created_at', '<=', $end_date);
       }
       }])->orderByDesc('total_subscriptions')->get();
    return view('admin.Patients.subscription.totInsSubs',compact('organizations'));
 }

  public function viewSubscription(Request $request) {
		 $id = base64_decode($request->id);
		 $UsersSubscriptions = UsersSubscriptions::Where('user_id', $id)->get();
		 $user = User::where("id",$id)->first();
		 // dd($UsersSubscriptions);
		 return view('admin.Patients.subscription.viewSubscription',compact('UsersSubscriptions','user'));
  }

   public function viewPlan(Request $request) {
		 $id = $request->id;
		 $UsersSubscriptions = UsersSubscriptions::Where('id', $id)->first();
		 return view('admin.Patients.subscription.viewPlan',compact('UsersSubscriptions'));
  }

  public function newSubscription(Request $request){
         if ($request->isMethod('post')) {
			$data = $request->all();
			// dd($data);
			$orderId = "SUBS"."1";
				$userSubs = UsersSubscriptions::orderBy("id","DESC")->first();
				if(!empty($userSubs)){
					$sid = $userSubs->id + 1;
					$orderId = "SUBS".$sid;
				}
				if($data['subcribetime']){
					$subcribedate = date("Y-m-d",strtotime($data['subcribedate']))." ".date("H:i:s",strtotime($data['subcribetime']));
				}else{
					$subcribedate = date("Y-m-d",strtotime($data['subcribedate']))." ".date("H:i:s");
				}
			
			  $subscription =  UsersSubscriptions::create([
				'login_id' => Session::get('id'),
				 'user_id' => $data['user_id'],
				 'order_id' => $orderId,
				 'payment_mode' => $data['payment_mode'],
				 'ref_code' => $data['referral_user_id'],
				 // 'coupon_id' => null,
				 // 'tax' => $data['tax'],
				 'created_at' => $subcribedate,
				 'coupon_discount' => $data['coupon_discount'],
				 'order_subtotal' => $data['order_total'],
				 'order_total' => $data['order_total'],
				 'order_status' => ($data['payment_mode'] == '6') ? 0 : 1,
				 'added_by' => $data['added_by'],
				 'remark' => $data['remark'],
				 'organization_id' => $data['organization_id'],
				 'meta_data' => json_encode($data),
			  ]);
				if($data['payment_mode'] == '6'){
				$lnk = route('subsPayment',[base64_encode($subscription->id)]);
				$links = ApptLink::create([
					'type' => 3,
					'user_id' => $subscription->user_id,
					'link' => $lnk,
					'order_id' => $orderId,
					'createBy' => 0,
					'meta_data' => json_encode($subscription),
				]);
				return ['type'=>2,'data'=>$links];
		}else {
			$subs_id = @$subscription->id;
  			$plan = Plans::where('id',$data['plan_id'])->first();
  			$subscribedPlan = new UserSubscribedPlans;
  			$subscribedPlan->plan_id = $plan->id;
  			$subscribedPlan->plan_price = $plan->price;
  			$subscribedPlan->discount_price =  $plan->discount_price;
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
  			$end_date = date('Y-m-d H:i:s', strtotime($subcribedate.'+'.$duration_in_days.' days'));
  			$PlanPeriods =  PlanPeriods::create([
  			   'subscription_id' => $subs_id,
  			   'subscribed_plan_id' => $subscribedPlan->id,
  			   'user_plan_id' => $data['plan_id'],
  			   'user_id' => $data['user_id'],
  			   'start_trail' => $subcribedate,
  			   'end_trail'=> $end_date,
  			   'remaining_appointment' => $plan->appointment_cnt,
  			   'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
  			   'lab_pkg_remaining' => 0,
  			   'status' => 1
  			]);
  			if($data['payment_mode'] == '2'){
  				$tran_mode = "Cheque";
  			}
  			else if($data['payment_mode'] == '3'){
  				$tran_mode = "Cash";
  			}
  			else if($data['payment_mode'] == '4'){
  				$tran_mode = "Online Payment";
  			}else if($data['payment_mode'] == '5'){
  				$tran_mode = "Free";
  			}

  			// $tracking_id = rand(100000000000,999999999999);
  			// $trackingIdExist =  UserSubscriptionsTxn::where("tracking_id",$tracking_id)->count();
  			// if($trackingIdExist > 0){
  				// $tracking_id = $tracking_id+1;
  			// }
  			$tracking_id = null;
  			if(isset($data['tracking_id'])){
  				$tracking_id = $data['tracking_id'];
  			}
  			UserSubscriptionsTxn::create([
  				'subscription_id' => $subs_id,
  				'tracking_id' => $tracking_id,
  				'tran_mode'=> $tran_mode,
  				'currency'=> "INR",
  				'payed_amount'=>$data['order_total'],
  				'cheque_no'=>$data['cheque_no'],
  				'cheque_payee_name'=>$data['cheque_payee_name'],
  				'cheque_bank_name'=>$data['cheque_bank_name'],
  				'cheque_date'=>$data['cheque_date'],
  				'tran_status' => "Success",
  				'trans_date' => date('d-m-Y')
  			]);
  			Session::flash('message', "Subscription Created Successfully");
        return ['type'=>1,'data'=>$data['user_id']];
      }

       }
	   else{
		    $id = base64_decode($request->id);
			$user = User::where("id",$id)->first();
		    return view('admin.Patients.subscription.newSubscription',compact('user'));
	   }
   }

    function ApplyReferralCodeAdmin(Request $request) {
		 if($request->isMethod('post')) {
			$data = $request->all();
			$success = 0;
			$res = ["success"=>$success,"referral_user_id"=>"","coupon_discount"=>""];
			// $checkCode = User::select('id')->where(['mobile_no'=> $data['ref_code'],"parent_id"=>0])->where('id', '!=', $data['user_id'])->first();
			// if(!empty($checkCode)) {
				// $success = 1;
				// $res['referral_user_id'] =  $checkCode->id;
				// $res['coupon_discount'] =  getSetting("referred_amount")[0];
			// }
			// else {
				$refData = ReferralMaster::where('code',$data['ref_code'])->where(['status'=>1,'delete_status'=>1])->first();
				if(!empty($refData)){
					$dt = date('Y-m-d');
					if($refData->code_last_date < $dt){
						$success = 0;
					}
					if(!empty($refData->plan_ids)) {
						$plan_ids = explode(",",$refData->plan_ids);
						if(in_array($data['plan_id'],$plan_ids)) {
							$dis = getDiscount($refData->referral_discount_type,$refData->referral_discount,$data['plan_id']);
							$res['type'] =  2;
							$res['referral_user_id'] =  $refData->id;
							$res['coupon_discount'] =  $dis;
							$success = 1;
						}
					}
					else{
						$dis = getDiscount($refData->referral_discount_type,$refData->referral_discount,$data['plan_id']);
						$res['type'] =  2;
						$res['referral_user_id'] =  $refData->id;
						$res['coupon_discount'] =  $dis;
						$success = 1;
					}
				}
			// }
			$res['success'] =  $success;
			return $res;
		}
     }

	public function changePlanPeriodStatus(Request $request) {
		if($request->isMethod('post')) {
			$data = $request->all();
			if($data['s_type'] == 1) {
				if($data['status'] == 0) {
					PlanPeriods::where('subscription_id',$data['id'])->update(['status' => 1]);
					return 1;
				}
				else {
					PlanPeriods::where('subscription_id',$data['id'])->update(['status' => 0]);
					return 2;
				}
			}
			else {
				$sts = $data['status'] == 1  ? 2 : 1;
				UsersSubscriptions::where('id',$data['id'])->update(['order_status' => $sts]);
				return 1;

			}
		}
    }
    
    public function instantSubs(Request $request) {
		if($request->isMethod('post')) {
			$data = $request->all();
			\Session::put('admin_org_id', $data['organization']);
			\Session::put('admin_ref_code', $data['ref_code']);
			$user = User::where(['mobile_no'=>trim($data['mobile_no']),'parent_id'=>0])->first();
			
			$first_name = trim(strtok($data['user_name'], ' '));
			$last_name = trim(strstr($data['user_name'], ' '));

			if(empty($user)) {
				   $first_name = trim(strtok($data['user_name'], ' '));
				   $last_name = trim(strstr($data['user_name'], ' '));
				   $user = User::create([
					   'first_name' => ucfirst($first_name),
					   'last_name' => $last_name,
					   'mobile_no' => $data['mobile_no'],
					   'profession_type' => 2,
					   'organization' => $data['organization'],
					   'login_type' => 2,
					   'device_type' => 3,
					   'urls' => $data['payment_type'] == 'rej' ? json_encode($data) : null,
					   'register_by' => Session::get('id'),
				   ]);
				   createUsersReferralCode($user->id);
			   }
			else{
				if($user && (is_null($user->first_name) || is_null($user->last_name))) {
					User::where('mobile_no', trim($data['mobile_no']))->update([
						'first_name' => ucfirst($first_name),
						'last_name'  => $last_name,
						'profession_type' => 2,
						'organization' => $data['organization'],
						'login_type' => 2,
						'device_type' => 3,
						'urls' => $data['payment_type'] == 'rej' ? json_encode($data) : null,
						'register_by' => Session::get('id'),
					]);
				}
			}

			   //else{
				   //$isSubs = UsersSubscriptions::with(["PlanPeriods.Plans"])->whereNotNull("id")->where('user_id',$user->id)->where('order_status',1)->whereHas('PlanPeriods', function($q) {$q->Where('status', 1);})->count();
				   //if($isSubs > 0){
				   //	return ['type'=>3];
				   //}
			   //}
				if($data['payment_type'] == 'rej') {
					return ['type'=>4];
				}
				else{
				   $orderId = "SUBS"."1";
				   $userSubs = UsersSubscriptions::orderBy("id","DESC")->first();
					   
				   if(!empty($userSubs)){
					   $sid = $userSubs->id + 1;
					   $orderId = "SUBS".$sid;
				   }
				   if(Session::get('id') == 252){
					   $plan = Plans::where('id',4)->first();
				   }
				   else{
					$plan = Plans::where('id',7)->first();
				   }
				   $coupon_discount = 0;
				   $refData = ReferralMaster::where('id',$data['ref_code'])->where(['status'=>1,'delete_status'=>1])->first();
				   if(!empty($refData)){
					   if(!empty($refData->plan_ids)) {
						   $plan_ids = explode(",",$refData->plan_ids);
						   if(in_array($plan->id,$plan_ids)) {
							   $coupon_discount = getDiscount($refData->referral_discount_type,$refData->referral_discount,$plan->id);
						   }
					   }
					   else{
						   $coupon_discount = getDiscount($refData->referral_discount_type,$refData->referral_discount,$plan->id);
					   }
				   }
				   $actualPice = $plan->price - $plan->discount_price;
				   $order_total = $actualPice - $coupon_discount;
				   if($user->mobile_no == 7691079774){
					   $order_total = 1;
				   }
				   $subArray = $data;
				   $subArray['user_id'] = $user->id;
				   $subArray['plan_id'] = $plan->id;
				   $subscription =  UsersSubscriptions::create([
					   'login_id' => Session::get('id'),
						'user_id' => $user->id,
						'order_id' => $orderId,
						'payment_mode' => $data['payment_type'] == 'cash' ? 3 : 1,
						'ref_code' => $data['ref_code'],
						'coupon_discount' => $coupon_discount,
						'order_subtotal' => $order_total,
						'order_total' => $order_total,
						'order_status' => $data['payment_type'] == 'cash' ? 1 : 0,
						'added_by' => Session::get('id'),
						// 'remark' => $data['remark'],
						'organization_id' => $data['organization'],
						'meta_data' => json_encode($subArray),
				   ]);
				   if($data['payment_type'] == 'online' || $data['payment_type'] == 'bank') {
					   $mid = "yNnDQV03999999736874";
					   $merchent_key = "&!VbTpsYcd6nvvQS";
					   $paytmParams["body"] = array(
						   "mid"           => $mid,
						   "orderId"       => $subscription->order_id,
						   "amount"        => $subscription->order_total,
						   "businessType"  => "UPI_QR_CODE",
						   "posId"         => time()
					   );
					   $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchent_key);
					   $paytmParams["head"] = array(
						   "clientId"	=> 'C12',
						   "version"	=> 'v1',
						   "signature"	=> $checksum
					   );
					   $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
					   /* for Production */
					   $url = "https://securegw.paytm.in/paymentservices/qr/create";
					   $ch = curl_init($url);
					   curl_setopt($ch, CURLOPT_POST, 1);
					   curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
					   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					   curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					   $response = curl_exec($ch);
					   $response = json_decode($response,true);
					   $qr_code = "";
					   $ptmGoid = "";
					   if($response['body']['resultInfo']['resultCode'] == "QR_0001" && $response['body']['resultInfo']['resultStatus'] == "SUCCESS") {
						   $qr_code = $response['body']['image'];
						   // $qr_data = $response['body']["qrData"];
						   // if(isset($qr_data)){
							   // $qrExData = explode("&",$qr_data);
							   // if(isset($qrExData[3])){
								   // $ptmGoid = substr($qrExData[3],3);
								   // UsersSubscriptions::where(["id"=>$subscription->id])->update([
									   // 'paytm_goid' => $ptmGoid,
								   // ]);
							   // }
						   // }
					   }
					   // $lnk = route('subsPayment',[base64_encode($subscription->id)]);
					   // $link = ApptLink::create([
						   // 'type' => 3,
						   // 'user_id' => $user->id,
						   // 'link' => $lnk,
						   // 'order_id' => $orderId,
						   // 'createBy' => Session::get('id'),
						   // 'meta_data' => json_encode($subscription),
					   // ]);
					   return ['type'=>2,'orderId'=>$orderId,'qr_code'=>$qr_code];
				   }
				   else {
					   $subs_id = @$subscription->id;
					   $subscribedPlan = new UserSubscribedPlans;
					   $subscribedPlan->plan_id = $plan->id;
					   $subscribedPlan->plan_price = $plan->price;
					   $subscribedPlan->discount_price =  $plan->discount_price;
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
					   $end_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').'+'.$duration_in_days.' days'));
					   $PlanPeriods =  PlanPeriods::create([
						  'subscription_id' => $subs_id,
						  'subscribed_plan_id' => $subscribedPlan->id,
						  'user_plan_id' => $plan->id,
						  'user_id' => $user->id,
						  'start_trail' => date('Y-m-d H:i:s'),
						  'end_trail'=> $end_date,
						  'remaining_appointment' => $plan->appointment_cnt,
						  'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
						  'lab_pkg_remaining' => 0,
						  'status' => 1
					   ]);
					   UserSubscriptionsTxn::create([
						   'subscription_id' => $subs_id,
						   'tran_mode'=> 'Cash',
						   'currency'=> "INR",
						   'payed_amount'=>$order_total,
						   'tran_status' => "Success",
						   'trans_date' => date('d-m-Y')
					   ]);
				   $this->sendMessageToUser($subscription);
				   $currentDate = date('Y-m-d');							
				   $chkExist = DailyReport::where('added_by',Session::get('id'))->whereDate('created_at', $currentDate)->first();	   
				   if($chkExist) {
						DailyReport::where('added_by',Session::get('id'))->whereDate('created_at', $currentDate)->update([
							'actual_sub_cash' => $chkExist->actual_sub_cash + 1
						]);
					}
					else{
						 DailyReport::create([
							'actual_sub_cash' => 1,
							'added_by' => Session::get('id'),
							'created_at'=>$currentDate." ".date('H:i:s')
						]);
				   }
				   Session::flash('message', "Subscription Created Successfully");
				   return ['type'=>1];
				 }
			}
		}
		else{
			$onlineSubs = UsersSubscriptions::with(["PlanPeriods.Plans","UserSubscribedPlans.PlanPeriods","User","ReferralMaster"])->whereNotNull("id")->where('order_status',1)->whereHas('PlanPeriods', function($q) {
				 $q->Where('status', 1);
			})->where('added_by',Session::get('id'))->where('payment_mode',1)->whereRaw('date(created_at) >= ?', [date('Y-m-d')])->whereRaw('date(created_at) <= ?', [date('Y-m-d')])->orderBy('id', 'desc')->count();
			
			$cashSubs = UsersSubscriptions::with(["PlanPeriods.Plans","UserSubscribedPlans.PlanPeriods","User","ReferralMaster"])->whereNotNull("id")->where('order_status',1)->whereHas('PlanPeriods', function($q) {
				 $q->Where('status', 1);
			})->where('added_by',Session::get('id'))->where('payment_mode',3)->whereRaw('date(created_at) >= ?', [date('Y-m-d')])->whereRaw('date(created_at) <= ?', [date('Y-m-d')])->orderBy('id', 'desc')->count();
			
			$OrganizationList =  OrganizationMaster::whereIn('id',[14,91,74,49,48,13,4,55,101,108])->orderBy('id', 'desc')->get();
			$orgQuery = ReferralMaster::whereIn('subscription_org_id',[14,91,74,49,48,13,4,55,101,273])->where('delete_status',1);
			if(Session::get("admin_org_id")!= null) {
			   $orgQuery->where('subscription_org_id',Session::get("admin_org_id"));
			}
			$refCodeData = $orgQuery->get();
			return view('admin.subscription.instant-subscription',compact('OrganizationList','onlineSubs','cashSubs','refCodeData'));
		}
	}

    public function getReferralCodes(Request $request) {
        $organizationId = $request->input('organization_id');
        $referralCodes = ReferralMaster::where('subscription_org_id', $organizationId)->where('delete_status',1)->get();
        return response()->json(['referralCodes' => $referralCodes]);
    }

	function instantSubsOld(Request $request) {
        if($request->isMethod('post')) {
            $data = $request->all();
            Session::put('admin_org_id', $data['organization']);
            Session::put('admin_ref_code', $data['ref_code']);
            $user = User::where(['mobile_no'=>trim($data['mobile_no']),'parent_id'=>0])->first();
            if(empty($user)) {
                $first_name = trim(strtok($data['user_name'], ' '));
                $last_name = trim(strstr($data['user_name'], ' '));
                $user = User::create([
                    'first_name' => ucfirst($first_name),
                    'last_name' => $last_name,
                    'mobile_no' => $data['mobile_no'],
                    'profession_type' => 2,
                    'organization' => $data['organization'],
                    'login_type' => 2,
                    'device_type' => 3,
                    'register_by' => Session::get('id'),
                ]);
                createUsersReferralCode($user->id);
            }
            //else{
            //$isSubs = UsersSubscriptions::with(["PlanPeriods.Plans"])->whereNotNull("id")->where('user_id',$user->id)->where('order_status',1)->whereHas('PlanPeriods', function($q) {$q->Where('status', 1);})->count();
            //if($isSubs > 0){
            //	return ['type'=>3];
            //}
            //}

            $orderId = "SUBS"."1";
            $userSubs = UsersSubscriptions::orderBy("id","DESC")->first();
            if(!empty($userSubs)){
                $sid = $userSubs->id + 1;
                $orderId = "SUBS".$sid;
            }
            $plan = Plans::where('id',7)->first();
            $coupon_discount = 0;
            $refData = ReferralMaster::where('id',$data['ref_code'])->where(['status'=>1,'delete_status'=>1])->first();
            if(!empty($refData)){
                if(!empty($refData->plan_ids)) {
                    $plan_ids = explode(",",$refData->plan_ids);
                    if(in_array($plan->id,$plan_ids)) {
                        $coupon_discount = getDiscount($refData->referral_discount_type,$refData->referral_discount,$plan->id);
                    }
                }
                else{
                    $coupon_discount = getDiscount($refData->referral_discount_type,$refData->referral_discount,$plan->id);
                }
            }
            $actualPice = $plan->price - $plan->discount_price;
            $order_total = $actualPice - $coupon_discount;
            // $order_total = 1;
            $subArray = $data;
            $subArray['user_id'] = $user->id;
            $subArray['plan_id'] = $plan->id;
            $subscription =  UsersSubscriptions::create([
                'login_id' => Session::get('id'),
                'user_id' => $user->id,
                'order_id' => $orderId,
                'payment_mode' => $data['payment_type'] == 'cash' ? 3 : 1,
                'ref_code' => $data['ref_code'],
                'coupon_discount' => $coupon_discount,
                'order_subtotal' => $order_total,
                'order_total' => $order_total,
                'order_status' => $data['payment_type'] == 'cash' ? 1 : 0,
                'added_by' => Session::get('id'),
                // 'remark' => $data['remark'],
                'organization_id' => $data['organization'],
                'meta_data' => json_encode($subArray),
            ]);
            if($data['payment_type'] == 'online') {
                $mid = "yNnDQV03999999736874";
                $merchent_key = "&!VbTpsYcd6nvvQS";
                $paytmParams["body"] = array(
                    "mid"           => $mid,
                    "orderId"       => $subscription->order_id,
                    "amount"        => $subscription->order_total,
                    "businessType"  => "UPI_QR_CODE",
                    "posId"         => time()
                );
                $checksum = PaytmChecksum::generateSignature(json_encode($paytmParams["body"], JSON_UNESCAPED_SLASHES), $merchent_key);
                $paytmParams["head"] = array(
                    "clientId"	=> 'C11',
                    "version"	=> 'v1',
                    "signature"	=> $checksum
                );
                $post_data = json_encode($paytmParams, JSON_UNESCAPED_SLASHES);
                /* for Production */
                $url = "https://securegw.paytm.in/paymentservices/qr/create";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                $response = curl_exec($ch);
                $response = json_decode($response,true);
                $qr_code = "";
                if($response['body']['resultInfo']['resultCode'] == "QR_0001" && $response['body']['resultInfo']['resultStatus'] == "SUCCESS") {
                    $qr_code = $response['body']['image'];
                }
                // $lnk = route('subsPayment',[base64_encode($subscription->id)]);
                // $link = ApptLink::create([
                // 'type' => 3,
                // 'user_id' => $user->id,
                // 'link' => $lnk,
                // 'order_id' => $orderId,
                // 'createBy' => Session::get('id'),
                // 'meta_data' => json_encode($subscription),
                // ]);
                return ['type'=>2,'orderId'=>$orderId,'qr_code'=>$qr_code];
            }
            else {
                $subs_id = @$subscription->id;
                $subscribedPlan = new UserSubscribedPlans;
                $subscribedPlan->plan_id = $plan->id;
                $subscribedPlan->plan_price = $plan->price;
                $subscribedPlan->discount_price =  $plan->discount_price;
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
                $end_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s').'+'.$duration_in_days.' days'));
                $PlanPeriods =  PlanPeriods::create([
                    'subscription_id' => $subs_id,
                    'subscribed_plan_id' => $subscribedPlan->id,
                    'user_plan_id' => $plan->id,
                    'user_id' => $user->id,
                    'start_trail' => date('Y-m-d H:i:s'),
                    'end_trail'=> $end_date,
                    'remaining_appointment' => $plan->appointment_cnt,
                    'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
                    'lab_pkg_remaining' => 0,
                    'status' => 1
                ]);
                UserSubscriptionsTxn::create([
                    'subscription_id' => $subs_id,
                    'tran_mode'=> 'Cash',
                    'currency'=> "INR",
                    'payed_amount'=>$order_total,
                    'tran_status' => "Success",
                    'trans_date' => date('d-m-Y')
                ]);
                $this->sendMessageToUser($subscription);
                Session::flash('message', "Subscription Created Successfully");
                return ['type'=>1];
            }
        }
        else{
            $onlineSubs = UsersSubscriptions::with(["PlanPeriods.Plans","UserSubscribedPlans.PlanPeriods","User","ReferralMaster"])->whereNotNull("id")->where('order_status',1)->whereHas('PlanPeriods', function($q) {
                $q->Where('status', 1);
            })->where('added_by',Session::get('id'))->where('payment_mode',1)->whereRaw('date(created_at) >= ?', [date('Y-m-d')])->whereRaw('date(created_at) <= ?', [date('Y-m-d')])->orderBy('id', 'desc')->count();

            $cashSubs = UsersSubscriptions::with(["PlanPeriods.Plans","UserSubscribedPlans.PlanPeriods","User","ReferralMaster"])->whereNotNull("id")->where('order_status',1)->whereHas('PlanPeriods', function($q) {
                $q->Where('status', 1);
            })->where('added_by',Session::get('id'))->where('payment_mode',3)->whereRaw('date(created_at) >= ?', [date('Y-m-d')])->whereRaw('date(created_at) <= ?', [date('Y-m-d')])->orderBy('id', 'desc')->count();

            $OrganizationList =  OrganizationMaster::whereIn('id',[91,74,49,48,13,4,55])->orderBy('id', 'desc')->get();
            $orgQuery = ReferralMaster::whereIn('org_id',[91,74,49,48,13,4,55]);
            if(Session::get("admin_org_id")!= null) {
                $orgQuery->where('org_id',Session::get("admin_org_id"));
            }
            $refCodeData = $orgQuery->get();
            return view('admin.subscription.instant-subscription',compact('OrganizationList','onlineSubs','cashSubs','refCodeData'));
        }
    }



    public function instantSubsReport(Request $request) 
    {
        if($request->isMethod('post'))
         {
            $params = array();
            if (!empty($request->input('search'))) {
                $params['search'] = base64_encode($request->input('search'));
            }
            if (!empty($request->input('page_no'))) {
                $params['page_no'] = base64_encode($request->input('page_no'));
            }
            return redirect()->route('admin.instantSubsReport',$params)->withInput();
        }
        else 
        {
            $page = 25;
            if(!empty($request->input('page_no')))
             {
                $page = base64_decode($request->input('page_no'));
             }

            $instReport =  DB::table('instant_subs_report')->where('added_by',Session::get('id'))->orderBy('id', 'desc')->paginate($page);
            $adminData = DB::table('admins')->where('id',Session::get('id'))->first();
            $adminList = Admin::where('delete_status', 1)->where('status',1)->get();
            return view('admin.subscription.instant-subscription-report',compact('instReport','adminData','adminList'));
        }
    }
    public function instantSubsReportAdmin(Request $request) {
        if ($request->isMethod('post')) {
            $params = array();
            if (!empty($request->input('start_date'))) 
            {
                $params['start_date'] = base64_encode($request->input('start_date'));
            }
            if (!empty($request->input('end_date'))) 
            {
                $params['end_date'] = base64_encode($request->input('end_date'));
            }
            if (!empty($request->input('page_no'))) 
            {
                $params['page_no'] = base64_encode($request->input('page_no'));
            }
            if (!empty($request->input('city'))) {
                $params['city'] = base64_encode($request->input('city'));
            }
            if (!empty($request->input('file_type'))) 
            {
                $params['file_type'] = base64_encode($request->input('file_type'));
            }
            if (!empty($request->input('added_by'))) 
            {
                $params['added_by'] = base64_encode($request->input('added_by'));
            }
            return redirect()->route('admin.instantSubsReportAdmin',$params)->withInput();
        }
        else {
            $page = 25;
            if(!empty($request->input('page_no'))) {
                $page = base64_decode($request->input('page_no'));
            }
            $query = DailyReport::with("Admin");
             
            $result = DB::table('user_subscriptions')
            ->join('instant_subs_report', 'user_subscriptions.added_by', '=', 'instant_subs_report.added_by')
            ->select('user_subscriptions.added_by', DB::raw('COUNT(*) as occurrence_count'))
            ->where('user_subscriptions.payment_mode', 4)
            ->groupBy('user_subscriptions.added_by')
            ->get();
            
            if(!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
                if(!empty($request->input('start_date'))) {
                    $start_date = date('Y-m-d',strtotime(base64_decode($request->input('start_date'))));
                    $query->whereRaw('date(created_at) >= ?', [$start_date]);
                }
                if(!empty($request->input('end_date')))	{
                    $end_date = date('Y-m-d',strtotime(base64_decode($request->input('end_date'))));
                    $query->whereRaw('date(created_at) <= ?', [$end_date]);
                }
                

                   $sumPlanCash = $query->sum('plan_cash');
                    $sumPlanOnline = $query->sum('plan_online');
               
            }

            if($request->input('added_by')  != '') {
                $added_by = base64_decode($request->input('added_by'));
                $query->where('added_by',$added_by);
            }
            if($request->input('city')  != '') {
                $city = base64_decode($request->input('city'));
                $query->whereHas('Admin', function($q)  use ($city) {$q->where('city',$city);});
            }
            $file_type = base64_decode($request->input('file_type'));
            
                    $sumPlanCash = $query->sum('plan_cash');
                    $sumPlanOnline = $query->sum('plan_online');

            if($file_type == "excel") {
                $depositData = $query->orderBy('id', 'desc')->get();
                $instArray = array();
                foreach($depositData as $i => $element) {
                    $instArray[] = array(
                        $i+1,
                        $element->Admin->name,
                        $element->Admin->city,
                        $element->total_students,
                        $element->plan_online,
                        $element->plan_cash,
                        $element->amount,
                        date('d-m-Y',strtotime($element->created_at)),
                        date('H:i',strtotime($element->created_at)),
                    );
                }
                return Excel::download(new CommonExport($instArray), 'subs.xlsx');
            }
            $instReport = $query->orderBy('id', 
            
            'desc')->paginate($page);
            $instReportAll =  $query->orderBy('actual_sub_cash', 'desc')->orderBy('actual_sub_online', 'desc')->get();
            // $instReport = $subscription->orderBy('id', 'desc')->paginate($page);
            return view('admin.subscription.instant-subscription-report-admin',compact('instReport' ,'result' , 'sumPlanCash' , 'sumPlanOnline', 'instReportAll' ));
        }
    }


	   public function subcriptionImport(Request $request){
			
		if($request->subcription_plan){
		
			$extensions = array("xls","xlsx","csv","ods");
				
			$fileextension = $request->file('subcription_plan')->extension();
			if(in_array($fileextension,$extensions)){
				
			}else{
				Session::flash('error', "File type should be in xls,xlsx,csv,ods");
				return back();
			}

			    Excel::import(new SubcritionImport,request()->file('subcription_plan'));

				Session::flash('message', "Data Imported successfully");
				return back();

		}else{
			Session::flash('error', "Please Upload File");
				return back();
		}
		

				
	   }
	 
	   public function addSubNote(Request $request) {
        if($request->isMethod('post')) {

            $data = $request->all();
            $id = base64_decode($data['id']);
            UsersSubscriptions::where('id', $id)->update(array('remark' => $data['note']));
            return 1;
        }
    }
      public function depositeRemark(Request $request){
        if($request->isMethod('post')){
            if(isset($request->remark) && !empty($request->remark)){
                $update = SubAmtDeposit::where('id',$request->id)->update(['remark'=>$request->remark]);
                if($update){
                    return json_encode(array('status'=>true, 'msg'=>'remark added successfully'));
                }
            }else{
                return json_encode(array('status'=>false,'msg'=>'remark is required'));
            }
        }
    }


	public function depositAmt(Request $request) {
        if($request->isMethod('post')) {
            $data = $request->all();
            $fileName = null;
            if($request->hasFile('slip')) {
                $filepath = 'public/instant-subs-slip/';
                $slip = $request->file('slip');
                $fileName = strtolower(str_replace(" ","",$slip->getClientOriginalName()));
                if(!\Storage::disk('s3')->exists($filepath)) {
                    \Storage::disk('s3')->makeDirectory($filepath);
                }
                \Storage::disk('s3')->put($filepath.$fileName, file_get_contents($slip), 'public');
            }
            DB::table('subs_amt_deposit')->insert([
                'amount' => $data['amount'],
                'slip' => $fileName,
                'added_by' => Session::get('id'),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            return 1;
        }
    }

	public function depositReqAdmin(Request $request) {
        if ($request->isMethod('post')) {
            $params = array();
            if (!empty($request->input('start_date'))) {
                $params['start_date'] = base64_encode($request->input('start_date'));
            }
            if (!empty($request->input('end_date'))) {
                $params['end_date'] = base64_encode($request->input('end_date'));
            }
            if ($request->input('status') != "") {
                $params['status'] = base64_encode($request->input('status'));
            }
            if (!empty($request->input('page_no'))) {
                $params['page_no'] = base64_encode($request->input('page_no'));
            }
            if (!empty($request->input('city'))) {
                $params['city'] = base64_encode($request->input('city'));
            }
            if (!empty($request->input('file_type'))) {
                $params['file_type'] = base64_encode($request->input('file_type'));
            }
            if (!empty($request->input('added_by'))) {
                $params['added_by'] = base64_encode($request->input('added_by'));
            }
            return redirect()->route('admin.depositReqAdmin',$params)->withInput();
        }
        else {
            $page = 25;
            if(!empty($request->input('page_no'))) {
                $page = base64_decode($request->input('page_no'));
            }
            $query =  SubAmtDeposit::with("Admin");
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
            if($request->input('status')  != '') {
                $status = base64_decode($request->input('status'));
                $query->where('status',$status);
            }
            if($request->input('added_by')  != '') {
                $added_by = base64_decode($request->input('added_by'));
                $query->where('added_by',$added_by);
            }
            if($request->input('city')  != '') {
                $city = base64_decode($request->input('city'));
                $query->whereHas('Admin', function($q)  use ($city) {$q->where('city',$city);});
            }
            $file_type = base64_decode($request->input('file_type'));
            if($file_type == "excel") {
                $depositData = $query->orderBy('id', 'desc')->get();
                $DepArray = array();
                foreach($depositData as $i => $element) {
                    $sts = "";
                    if($element->status == "0") {$sts = "Pending";}
                    elseif($element->status == "1") {$sts = "Success";}
                    elseif($element->status == "2")  {$sts = "Invalid";}

                    $DepArray[] = array(
                        $i+1,
                        $element->Admin->name,
                        $element->Admin->city,
                        $element->amount,
                        $element->Admin->subs_amount,
                        $sts,
                        date('d-m-Y',strtotime($element->created_at)),
                        date('H:i',strtotime($element->created_at)),
                    );
                }
                return Excel::download(new DepositExport($DepArray), 'deposits.xlsx');
            }
            $deposits = $query->orderBy('id', 'desc')->paginate($page);
            return view('admin.subscription.deposit-subscription-report-admin',compact('deposits'));
        }
    }
    public function sendMessageToUser($orderData) 
    {
		$user = User::select('first_name','last_name','mobile_no')->where("id",$orderData->user_id)->first();
		if(!empty($user)) {
			  $username = "User";
			  if(!empty($user->first_name)){
				$username = $user->first_name." ".$user->last_name;
			  }
			$messagecamp = urlencode("Dear ".ucfirst($username).", Your Health Gennie plan has been activated. Please click the link below to download the app https://www.healthgennie.com/app-guide .Thanks Team Health Gennie");
			$this->sendSMS($user->mobile_no,$messagecamp,'1707170305526963735');
			
			$tmpName = "new_subscription";
			$post_data = ['parameters'=>[['name'=>'name','value'=>$username]],'template_name'=>$tmpName,'broadcast_name'=>'Subscription'];
			
			$curl = curl_init(); 
			curl_setopt_array($curl, array(
			  CURLOPT_URL => "https://live-server-2748.wati.io/api/v1/sendTemplateMessage?whatsappNumber=91".$user->mobile_no,
			  CURLOPT_RETURNTRANSFER => true,
			  CURLOPT_ENCODING => "",
			  CURLOPT_MAXREDIRS => 10,
			  CURLOPT_TIMEOUT => 0,
			  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			  CURLOPT_CUSTOMREQUEST => "POST",
			  CURLOPT_POSTFIELDS => json_encode($post_data),
			  CURLOPT_HTTPHEADER => array(
				"Authorization: Bearer eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJqdGkiOiJhNmQxMWVlNy1mOGVjLTQzMjItODYwYy0zZDA3OGZjNzM1OTgiLCJ1bmlxdWVfbmFtZSI6InN1ZGhhbnNodS5nQGhlYWx0aGdlbm5pZS5jb20iLCJuYW1laWQiOiJzdWRoYW5zaHUuZ0BoZWFsdGhnZW5uaWUuY29tIiwiZW1haWwiOiJzdWRoYW5zaHUuZ0BoZWFsdGhnZW5uaWUuY29tIiwiYXV0aF90aW1lIjoiMTIvMjAvMjAyMyAwNzoxNTo1NiIsImRiX25hbWUiOiIyNzQ4IiwiaHR0cDovL3NjaGVtYXMubWljcm9zb2Z0LmNvbS93cy8yMDA4LzA2L2lkZW50aXR5L2NsYWltcy9yb2xlIjoiQURNSU5JU1RSQVRPUiIsImV4cCI6MjUzNDAyMzAwODAwLCJpc3MiOiJDbGFyZV9BSSIsImF1ZCI6IkNsYXJlX0FJIn0.7-PZC6RxUURiZ8esAyS1awxnivz3dkLylpCNedbJsCk",
				"Content-Type: application/json-patch+json"
			  ),
			));
			$response = curl_exec($curl);
			curl_close($curl);
			$response = json_decode($response,true);
			if(isset($response['result']) && $response['result'] == 'success') {
				return 1;
			}
		 }
	 }

	
    public function depositReq(Request $request) {
        if ($request->isMethod('post')) {
            $params = array();
            if (!empty($request->input('search'))) {
                $params['search'] = base64_encode($request->input('search'));
            }
            if (!empty($request->input('page_no'))) {
                $params['page_no'] = base64_encode($request->input('page_no'));
            }
            return redirect()->route('admin.depositReq',$params)->withInput();
        }
        else {
            $page = 25;
            if(!empty($request->input('page_no'))) {
                $page = base64_decode($request->input('page_no'));
            }
            $deposits =  SubAmtDeposit::with("Admin")->where('added_by',Session::get('id'))->orderBy('id', 'desc')->paginate($page);
            return view('admin.subscription.deposit-subscription-report',compact('deposits'));
        }
    }
    public function updateDepositReqSts(Request $request) {
        $data = $request->all();
        if($data['status'] == 1) {
            $deps = DB::table('subs_amt_deposit')->where('id',$data['id'])->first();
            $adminData = DB::table('admins')->where('id',Session::get('id'))->first();
            $lastAmt = $adminData->subs_amount - $deps->amount;
            DB::table('admins')->where('id',$deps->added_by)->update([
                'subs_amount' => $lastAmt
            ]);
        }
        $deposits =  DB::table('subs_amt_deposit')->where('id',$data['id'])->update([
            'status' => $data['status']
        ]);
        return 1;
    }
    public function subscriptionByLocation(Request $request)
    {
        if ($request->isMethod('post')) {
            $params = [];
            $filterParams = ['search', 'start_date', 'end_date', 'page_no', 'organization_id', 'location', 'sub_location'];

            foreach ($filterParams as $param) {
                if (!empty($request->input($param))) {
                    $params[$param] = base64_encode($request->input($param));
                }
            }
            return redirect()->route('admin.subscriptionByLocation', $params)->withInput();
        } else {
            // Query subscriptions
            $subscriptionCounts = UsersSubscriptions::select('referral_master.location', 'user_subscriptions.organization_id', 'referral_master.sub_location', DB::raw('COUNT(*) as total_subscriptions'))
                ->join('referral_master', 'user_subscriptions.ref_code', '=', 'referral_master.code')
                ->groupBy('referral_master.location', 'referral_master.sub_location', 'user_subscriptions.organization_id')->orderByDesc('total_subscriptions');;
            // Apply filters
            if ($request->has('location')) {
                $location = base64_decode($request->input('location'));
                $subscriptionCounts->where('referral_master.location', $location);
            }

            if ($request->has('sub_location')) {
                $subLocation = base64_decode($request->input('sub_location'));
                $subscriptionCounts->where('referral_master.sub_location', $subLocation);
            }

            if ($request->has('organization_id')) {
                $organizationId = base64_decode($request->input('organization_id'));
                $subscriptionCounts->where('user_subscriptions.organization_id', $organizationId);
            }

            if (!empty($request->input('start_date'))) {
                $startDate = date('Y-m-d', strtotime(base64_decode($request->input('start_date'))));
                $subscriptionCounts->whereDate('user_subscriptions.created_at', '>=', $startDate);
            }

            if (!empty($request->input('end_date'))) {
                $endDate = date('Y-m-d', strtotime(base64_decode($request->input('end_date'))));
                $subscriptionCounts->whereDate('user_subscriptions.created_at', '<=', $endDate);
            }

            $subscriptionCounts = $subscriptionCounts->get();

        }

        return view('admin.subscription.subscription-by-location', compact('subscriptionCounts'));
    }

    public function subscriptionCashback(Request $request)
    {
        $search = '';
        if ($request->isMethod('post')) {
            $params = array();
            if (!empty($request->input('search'))) {
                $params['search'] = base64_encode($request->input('search'));
            }
            if (!empty($request->input('file_type'))) {
                $params['file_type'] = base64_encode($request->input('file_type'));
            }
            if ($request->input('name') != "") {
                $params['name'] = base64_encode($request->input('name'));
            }
            if ($request->input('status') != "") {
                $params['status'] = base64_encode($request->input('status'));
            }
            if (!empty($request->input('page_no'))) {
                $params['page_no'] = base64_encode($request->input('page_no'));
            }
            if (!empty($request->input('from_date'))) {
                $params['from_date'] = base64_encode($request->input('from_date'));
            }
            if (!empty($request->input('to_date'))) {
                $params['to_date'] = base64_encode($request->input('to_date'));
            }
            if (!empty($request->input('organization_id'))) {
                $params['organization_id'] = base64_encode($request->input('organization_id'));
            }

            return redirect()->route('subscription.cashback',$params)->withInput();
        } else {
            $query = ReferralCashback::with(['ReferralUser', 'ReferredUser.UsersSubscriptions']);
            $page = 25;
            $file_type = base64_decode($request->input('file_type'));
            if(!empty($request->input('page_no'))) {
                $page = base64_decode($request->input('page_no'));
            }
            if($request->input('status')  != '') {
                $status = base64_decode($request->input('status'));
                $query->where('status', $status);
            }
            if($request->input('organization_id')  != '') {
                $organization_id = base64_decode($request->input('organization_id'));
                $query->where('org_id',$organization_id);
            }
            if (!empty($request->input('from_date'))) {
                $startDate = date('Y-m-d', strtotime(base64_decode($request->input('from_date'))));
                $query->whereDate('created_at', '>=', $startDate);
            }
            if (!empty($request->input('to_date'))) {
                $endDate = date('Y-m-d', strtotime(base64_decode($request->input('to_date'))));
                $query->whereDate('created_at', '<=', $endDate);
            }
            if ($file_type == "excel") {
                $subscription = $query->orderBy('id', 'desc')->get();
                $instArray = array();

                foreach ($subscription as $i => $element) {
                    $og_data = [];

                    foreach (getOrganizations() as $raw) {
                        if (!empty($element->org_id)) {
                            $raw->id = $element->org_id;
                        } elseif (!empty($element->other_org)) {
                            $raw->id = $element->other_org;
                        } else {
                            $raw->id = 'N/A'; // Assign 'N/A' to the id field if no org_id or other_org
                        }

                        $og_data['title'] = $raw->title; // Assuming raw->title is set somewhere
                    }

                    $instArray[] = array(
                        $i + 1,
                        @$element->order_id,
                        @$element->ReferralUser->first_name . ' ' .@$element->ReferralUser->last_name,
                        @$element->ReferralUser->mobile_no,
                        @$element->type ? 'subscribe' : 'unsubscribe', // Use name "subscribe" or "unsubscribe"
                        @$element->ReferredUser->first_name . ' ' . @$element->ReferredUser->last_name,
                        @$element->ReferredUser->mobile_no,
                        @$element->ReferredUser->UsersSubscriptions->order_status ? 'subscribe' : 'unsubscribe', // Use name "subscribe" or "unsubscribe"
                        @$element->status ? 'Success' : 'Pending', // Convert status to "Success" or "Pending"
                        @$og_data['title'] ?? 'N/A', // Using collected title from $og_data or 'N/A' if not set
                        date('d-m-Y', strtotime($element->created_at)),
                    );
                }

                return Excel::download(new SubscriptionCashbackExport($instArray), 'Cashback.xlsx');
            }

            if ($request->input('name') != '') {
                $name = base64_decode($request->input('name'));

                $query->where(function ($query) use ($name) {
                    $query->whereHas('ReferralUser', function ($q) use ($name) {
                        $q->where('first_name', 'like', '%' . $name . '%')
                            ->orWhere('last_name', 'like', '%' . $name . '%')
                            ->orWhere('mobile_no', 'like', '%' . $name . '%');
                    });
                    $query->orWhereHas('ReferredUser', function ($q) use ($name) {
                        $q->where('first_name', 'like', '%' . $name . '%')
                            ->orWhere('last_name', 'like', '%' . $name . '%')
                            ->orWhere('mobile_no', 'like', '%' . $name . '%');
                    });
                });
            }



            $cashback = $query->orderBy('id', 'desc')->paginate($page);


        }

        return view('admin.subscription.subscription-cashback-index', compact('cashback'));
    }
    public function subscriptionCashbackStatus(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status');
        $record = ReferralCashback::find($id);
        if (!empty($record))
        {
            $record->update(['status' => $status]);
        } else {
            return 'Something Went wrong';
        }
    }
    public function createContact(Request $request)
    {
        $data = $request->all();
        if ($data['payment_type'] == 'upi')
        {
            $unique = 'HG' . rand(0000, 1111);
            $name = $request->input('mobile_no');
            $contact = $request->input('mobile_no');
            $apiKey = env('RAZORPAY_KEY_ID');
            $apiSecret = env('RAZORPAY_KEY_SECRET');
            $data = [
                'name' => $name ?? 'Hgadd',
                'email' => 'test@healthgennie.com',
                'contact' => $contact,
                'type' => 'vendor',
                'reference_id' => $unique,
                'notes' => [
                    'notes_key_1' => 'Refer Friends & Get Rewards'
                ]
            ];

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.razorpay.com/v1/contacts');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($apiKey . ':' . $apiSecret)
            ]);

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'Curl error: ' . curl_error($ch);
            }

            curl_close($ch);
            $responseData = json_decode($response, true);
            // Create the fund account using the contact ID
            $fundAccount = $this->createFundAccount($responseData['id'], $request->input('upi'));

            // Check for errors in fund account creation
            if (isset($fundAccount['error'])) {
                return response()->json(['error' => $fundAccount['error']], 400);
            }

            // Initiate payout using the fund account ID
            $payoutResponse = $this->initiatePayout($fundAccount['id']);

            // Check for errors in payout initiation
            if (isset($payoutResponse['error'])) {
                return response()->json(['error' => $payoutResponse['error']], 400);
            }

            // Update the record status
            $id = $request->input('id');
            $record = ReferralCashback::find($id);
            if (!empty($record))
            {
                $record->update(['status' => 1]);
            } else {
                return response()->json(['error' => 'Something went wrong'], 400);
            }

            return 1;
        }
        elseif ($data['payment_type'] == 'phonepe') {
            return response()->json(['message' => 'In-progress']);
        }
        else {
            return response()->json(['error' => 'Invalid payment type'], 400);
        }
    }

    private function createFundAccount($contactId, $upiAddress)
    {
        $apiKey = env('RAZORPAY_KEY_ID');
        $apiSecret = env('RAZORPAY_KEY_SECRET');
        $api = new Api($apiKey, $apiSecret);

        $data = [
            'account_type' => 'vpa',
            'contact_id' => $contactId,
            'vpa' => [
                'address' => $upiAddress
            ]
        ];

        try {
            $fundAccount = $api->fundAccount->create($data);
            return $fundAccount->toArray();
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    private function initiatePayout($fundAccountId)
    {
        $response = Http::withBasicAuth(env('RAZORPAY_KEY_ID'), env('RAZORPAY_KEY_SECRET'))
            ->post('https://api.razorpay.com/v1/payouts', [
                'account_number' => 2323230060679317,
                'fund_account_id' => $fundAccountId,
                'amount' => 1000, // its deduct in paise
                'currency' => 'INR',
                'mode' => 'UPI',
                'purpose'=> "refund",
                // Add any other necessary attributes
            ]);
        if ($response->successful()) {
            //Logic for status update

            return 1;
        } else {
            // Payout initiation failed
            return response()->json(['success' => false, 'error' => 'Failed to initiate payout'], $response->status());
        }
    }


    public function depositSubscriptionLadger(Request $request) {
        if ($request->isMethod('post')) {
            $params = array();
            if (!empty($request->input('start_date'))) {
                $params['start_date'] = base64_encode($request->input('start_date'));
            }
            if (!empty($request->input('end_date'))) {
                $params['end_date'] = base64_encode($request->input('end_date'));
            }
            if ($request->input('status') != "") {
                $params['status'] = base64_encode($request->input('status'));
            }
            if (!empty($request->input('page_no'))) {
                $params['page_no'] = base64_encode($request->input('page_no'));
            }
            if (!empty($request->input('city'))) {
                $params['city'] = base64_encode($request->input('city'));
            }
            if (!empty($request->input('file_type'))) {
                $params['file_type'] = base64_encode($request->input('file_type'));
            }
            if (!empty($request->input('added_by'))) {
                $params['added_by'] = base64_encode($request->input('added_by'));
            }
            return redirect()->route('admin.depositSubscriptionLadger',$params)->withInput();
        }
        else {
            $page = 25;
			$loginid=Session::get('id');
			$admin = DB::table('admins')->where(['status'=>1, 'delete_status'=>1])->get();
			foreach($admin as $key =>$user){
				$existdeposit = SubAmtDeposit::where('added_by', $user->id)->whereDate('created_at', date('Y-m-d'))->first();
				if(!$existdeposit){
					$lastRec = SubAmtDeposit::where('added_by', $user->id)->orderBy('id','desc')->first();
					if($lastRec){
						DB::table('subs_amt_deposit')->insert([
							'opening_amount' => $lastRec->closing_amount,
							'sale_amount' => 0,
							'amount' => 0,
							'closing_amount' => $lastRec->closing_amount,
							'login_id'=>$user->id,
							'added_by' => $user->id,
							'created_at' => date('Y-m-d H:i:s'),
							'updated_at' => date('Y-m-d H:i:s'),
						]);
					}				
					
				}
			}
            if(!empty($request->input('page_no'))) {
                $page = base64_decode($request->input('page_no'));
            }
            $query =  SubAmtDeposit::where('status','!=',2)->with("Admin");
			$query->whereHas('Admin', function($q)  use ( $loginid) {$q->whereNotNull('manager_id'); $q->where('manager_id',$loginid);}); 

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
            
            if($request->input('added_by')  != '') {
                $added_by = base64_decode($request->input('added_by'));
                $query->where('added_by',$added_by);
            }
            if($request->input('city')  != '') {
                $city = base64_decode($request->input('city'));
                $query->whereHas('Admin', function($q)  use ($city) {$q->where('city',$city);});
            }
            $file_type = base64_decode($request->input('file_type'));
            
            $depositsTotal = $query->orderBy('id', 'desc')->get();
			$deposits = $query->orderBy('id', 'desc')->paginate($page);
			if($deposits->count() == 0){
				
				$query1 =  SubAmtDeposit::where('status','!=',2)->with("Admin");
				$query1->whereHas('Admin', function($q) { $q->whereNull('manager_id');}); 

				if(!empty($request->input('start_date')) || !empty($request->input('end_date'))) {
					if(!empty($request->input('start_date'))) {
						$start_date = date('Y-m-d',strtotime(base64_decode($request->input('start_date'))));
						$query1->whereRaw('date(created_at) >= ?', [$start_date]);
					}
					if(!empty($request->input('end_date')))	{
						$end_date = date('Y-m-d',strtotime(base64_decode($request->input('end_date'))));
						$query1->whereRaw('date(created_at) <= ?', [$end_date]);
					}
				}
				
				if($request->input('added_by')  != '') {
					$added_by = base64_decode($request->input('added_by'));
					$query1->where('added_by',$added_by);
				}
				if($request->input('city')  != '') {
					$city = base64_decode($request->input('city'));
					$query1->whereHas('Admin', function($q)  use ($city) {$q->where('city',$city);});
				}
				$file_type = base64_decode($request->input('file_type'));
				if($file_type == "excel") {
					$depositData = $query1->orderBy('id', 'desc')->get();
					
					$totalOpening = $totalSale = $totalDeposit = $totalClosing = 0;
					foreach($depositData as $val){
						$totalOpening += $val->opening_amount;
						$totalSale += $val->sale_amount;
						$totalDeposit += $val->amount;
						$totalClosing += $val->closing_amount;
					}
					$DepArray[] = ['Sno.','Name', 'Location', 'Opening Amount('.$totalOpening.')', 'Sale Amount('.$totalSale.')', 'Deposit Amount('.$totalDeposit.')', 'Closing Amount('.$totalClosing.')', 'Date'];
					foreach($depositData as $i => $element) {
						$sts = "";
						if($element->status == "0") {$sts = "Pending";}
						elseif($element->status == "1") {$sts = "Success";}
						elseif($element->status == "2")  {$sts = "Invalid";}

						$DepArray[] = array(
							$i+1,
							$element->Admin->name,
							$element->Admin->city,
							(isset($element->opening_amount) && !empty($element->opening_amount))?$element->opening_amount:0,
							(isset($element->sale_amount) && !empty($element->sale_amount))?$element->sale_amount:0,
							(isset($element->amount) && !empty($element->amount))?$element->amount:0,
							(isset($element->closing_amount) && !empty($element->closing_amount))?$element->closing_amount:0,
							date('d-m-Y',strtotime($element->created_at))
						);
					}
					return Excel::download(new DefaultExport($DepArray), 'deposits-ladger.xlsx');
				}
				$depositsTotal = $query1->orderBy('id', 'desc')->get();
				$deposits = $query1->orderBy('id', 'desc')->paginate($page);
			}else{
				if($file_type == "excel") {
				
					$depositData = $query->orderBy('id', 'desc')->get();
					$totalOpening = $totalSale = $totalDeposit = $totalClosing = 0;
					foreach($depositData as $val){
						$totalOpening += $val->opening_amount;
						$totalSale += $val->sale_amount;
						$totalDeposit += $val->amount;
						$totalClosing += $val->closing_amount;
					}
					$DepArray[] = ['Sno.','Name', 'Location', 'Opening Amount('.$totalOpening.')', 'Sale Amount('.$totalSale.')', 'Deposit Amount('.$totalDeposit.')', 'Closing Amount('.$totalClosing.')', 'Date'];
					foreach($depositData as $i => $element) {
						$sts = "";
						if($element->status == "0") {$sts = "Pending";}
						elseif($element->status == "1") {$sts = "Success";}
						elseif($element->status == "2")  {$sts = "Invalid";}
	
						$DepArray[] = array(
							$i+1,
							$element->Admin->name,
							$element->Admin->city,
							(isset($element->opening_amount) && !empty($element->opening_amount))?$element->opening_amount:0,
							(isset($element->sale_amount) && !empty($element->sale_amount))?$element->sale_amount:0,
							(isset($element->amount) && !empty($element->amount))?$element->amount:0,
							(isset($element->closing_amount) && !empty($element->closing_amount))?$element->closing_amount:0,
							date('d-m-Y',strtotime($element->created_at))
						);
					}
					return Excel::download(new DefaultExport($DepArray), 'deposits-ladger.xlsx');
				}
			}
            return view('admin.subscription.deposit-subscription-ladger-admin',compact('deposits','depositsTotal'));
			
        }
    }

    public function insertSubsAmount(Request $request) {
        if($request->isMethod('post')) {
			
            $data = $request->all();

            $date = date('Y-m-d', strtotime($request->input('date')));
			$chkRec = DB::table('instant_subs_report')
			->where('added_by', Session::get('id'))
			->whereDate('date', $data['date'])->first();

			$adminData = DB::table('admins')->where('id',Session::get('id'))->first();
			if($chkRec){
				$lastRec = DB::table('instant_subs_report')->where('id', $chkRec->id)->first();
				$adminData->subs_amount = $adminData->subs_amount-$lastRec->amount;
				DB::table('instant_subs_report')
				->where('added_by', Session::get('id'))
				->whereDate('created_at', $data['created_at'])->update([
					'plan_online' => $data['plan_online'],
					'off_today' => isset($data['off_today']) ? $data['off_today'] : 0,
					'total_students' => $data['total_students'],
					'plan_cash' => $data['plan_cash'],
					'amount' => $data['amount'],
					'date' => $date,
				]);
			}else{
				$adminData->subs_amount = $adminData->subs_amount;
				DB::table('instant_subs_report')->insert([
				'plan_online' => $data['plan_online'],
				'off_today' => isset($data['off_today']) ? $data['off_today'] : 0,
				'total_students' => $data['total_students'],
				'plan_cash' => $data['plan_cash'],
				'amount' => $data['amount'],
				'added_by' => Session::get('id'),
				'date' => $date,
			]);
			}
			
			
			$lastAmt = $adminData->subs_amount + $data['amount'];
			DB::table('admins')->where('id',Session::get('id'))->update([
				'subs_amount' => $lastAmt
			]);
            return 1;
        }
    }


}
