<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Coupons;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as Input;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
//use Illuminate\Mail\Mailer;
class CouponController extends Controller {

	//coupons master section start
	   public function couponMaster(Request $request)
	   {
	     $search = '';
	     $getMyPractice =  Auth::id();
	     if ($request->isMethod('post')) {
	       $params = array();
	         if (!empty($request->input('search'))) {
	             $params['search'] = base64_encode($request->input('search'));
	         }
			//  ($params['search']);
			if (!empty($request->input('page_no'))) {
				$params['page_no'] = base64_encode($request->input('page_no'));
		    }
	         return redirect()->route('admin.couponMaster',$params)->withInput();
	       }
	       else
	       {
				$filters = array();
				$search = base64_decode($request->input('search'));
				$page = base64_decode($request->input('page_no'));
	         	$query = Coupons::Where("delete_status",'1');
					 if (Session::get('id') != 1) {
					 		$query->Where("added_by",Session::get('id'));
					 }
					 $coupons = $query->orderBy('id', 'desc')->paginate(25);
	         if ($request->input('search')  != '') {
	           $query = Coupons::where('coupon_title','like','%'.$search.'%')->orWhere('coupon_code','like','%'.$search.'%');
							 if (Session::get('id') != 1) {
							 		$query->Where("added_by",Session::get('id'));
							 }
						 $coupons = $query->Where("delete_status",'1')->orderBy('id', 'desc')->paginate(25);
	         }
			 $page = 25;
			 if(!empty($request->input('page_no'))){
				$page = base64_decode($request->input('page_no'));
			}
		 $coupons = $query->orderBy('id', 'desc')->paginate($page);
	       }
	       return view('admin.coupon_master.coupon-master',compact('coupons'));
	   }

	   public function couponMasterAdd(Request $request)
	   {
	       return view('admin.coupon_master.add-coupon');
	   }
	   public function addCouponMaster(Request $request) {
	       if ($request->isMethod('post')) {
	        $data = $request->all();
	        $coupon =  Coupons::Where('coupon_code',$data['coupon_code'])->Where(['status'=>1,'delete_status'=>1])->get();
	        if(count($coupon)>0){
	          return 2;
	        }else {
	          $user =  Coupons::create([
	                'type' => $data['type'],
					'login_id' => Session::get('id'),
	                'coupon_title' => $data['coupon_title'],
	                'coupon_sub_type' => $data['coupon_sub_type'],
	                'coupon_discount_type' => $data['coupon_discount_type'],
	                'coupon_discount' => $data['coupon_discount'],
	                'coupon_code' => $data['coupon_code'],
	                // 'plan_type' => $data['plan_type'],
	                'coupon_duration_type' => $data['coupon_duration_type'],
	                'coupon_duration' => $data['coupon_duration'],
	                'coupon_last_date' => date('Y-m-d', strtotime($data['coupon_last_date'])),
									'other_text' => $data['other_text'],
									  'apply_type' => $data['apply_type'],
									  'is_show' => $data['is_show'],
									  'max_uses' => $data['max_uses'],
									  'term_conditions' => $data['term_conditions'],
	                'added_by' => Session::get('id'),
	          ]);
	          Session::flash('message', "Coupon Added Successfully");
	          return 1;
	        }

	     }
	     // return redirect()->route('couponMaster');
	   }
	   public function editCoupons($id)
	   {
	       $coupon = Coupons::Where( 'id', '=', base64_decode($id))->first();
	       return view('admin.coupon_master.edit-coupon',compact('coupon'));
	   }
	   public function updateCouponsMaster(Request $request)
	   {
	     if ($request->isMethod('post')) {
	         $data = $request->all();
			 // pr($data);
	         $id = $data['id'];
	         $coupon =  Coupons::Where('coupon_code',$data['coupon_code'])->Where(['status'=>1,'delete_status'=>1])->Where('id','!=',$id)->first();
	         if(!empty($coupon)){
	           return 2;
	         }else {
	         $user =  Coupons::where('id', $id)->update(array(
			   'type' => $data['type'],
	           'coupon_title' => $data['coupon_title'],
	           'coupon_sub_type' => $data['coupon_sub_type'],
	           'coupon_discount_type' => $data['coupon_discount_type'],
	           'coupon_discount' => $data['coupon_discount'],
	           'coupon_code' => $data['coupon_code'],
	           // 'plan_type' => $data['plan_type'],
	           'coupon_duration_type' => $data['coupon_duration_type'],
	           'coupon_duration' => $data['coupon_duration'],
	           'coupon_last_date' => date('Y-m-d', strtotime($data['coupon_last_date'])),
	           'other_text' => $data['other_text'],
	           'apply_type' => $data['apply_type'],
	           'is_show' => $data['is_show'],
	           'max_uses' => $data['max_uses'],
	           'term_conditions' => $data['term_conditions'],
	         ));
	          Session::flash('message', "Coupon Updated Successfully");
	          return 1;
	        }
	       }
	     // return redirect()->route('couponMaster');
	   }
	   public function deleteCouponMaster($id)
	   {
	     Coupons::where('id', base64_decode($id))->update(array('delete_status' => '0'));
	     Session::flash('message', "Coupon Deleted Successfully");
	     return redirect()->route('admin.couponMaster');
	   }
	   public function updateCouponStatus(Request $request) {
	     if($request->isMethod('post')) {
	        $data = $request->all();
	        if($data['status'] == 0){
	          Coupons::where('id',$data['id'])->update(['status' => 1]);
	          return 1;
	        }
	        else{
	          Coupons::where('id',$data['id'])->update(['status' => 0]);
	          return 2;
	        }
	     }
	   }




}
