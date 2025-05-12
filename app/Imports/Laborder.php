<?php

namespace App\Imports;
use Illuminate\Support\Facades\Session;
use App\Models\LabOrders;
use App\Models\LabOrderTxn;
use App\Models\ApptLink;
use App\Models\LabPackage;
use App\Models\Coupons;
use App\Models\User;
use App\Models\LabOrderedItems;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
class Laborder implements WithHeadingRow,ToCollection
{
    public function collection(Collection $rows)    
    {
	      $x='|';
			foreach($rows as $k=> $labdata) {  
				$coupanDiscountAmount=0; 
				  
                   $Total=0;
                   $Totalprice=0;
				 
                        $labdata['package_name'] = (int) filter_var($labdata['package_name'], FILTER_SANITIZE_NUMBER_INT);
                    
                        $labdata['name'] = str_replace('"', '', $labdata['name']);
                      
						$packData= $this->getLabsPackageByCompID($labdata);
                      
                    if($packData['cmp_id']==2){
						Session::flash('error', "Not Allowed for thyrocare");
						return back();
					}
				    if(count($packData['package'])>0)  {
					foreach($packData['package'] as $res){
                                 
						$Totalprice+=$res->discount_price;

					  }
					}
                 
						  if($labdata['coupon_code']){
							$couponData= $this->getCoupondiscount($labdata['coupon_code']);
						
                            if($couponData['status']==1){
							if($couponData['coupon_discount_type'] && $couponData['coupon_discount_type']=='1'){
								 
								$Total = $Totalprice - $couponData['coupon_rate'];
							}else{
								$coupanDiscountAmount = $Totalprice * $couponData['coupon_rate'] / 100;
								
							    $Total=$Totalprice-$coupanDiscountAmount;
								
							}
						   }
						   $Total=$Totalprice;
						  }else{
						      $Total=$Totalprice;
					
						  }

                     
                          $first_name = trim(strtok($labdata['name'], ' '));
                          $last_name = trim(strstr($labdata['name'], ' '));
						 
                          
						  $userDAta=User::where('mobile_no', $labdata['mobile'])->where('parent_id', 0)->first();
                         
                          if(empty($userDAta)) {
                          $userDAta = new User();
                          $userDAta->first_name = $first_name;
                          $userDAta->last_name = $last_name;
                          $userDAta->mobile_no = $labdata['mobile'];
                          $userDAta->gender = $labdata['gender'];
                          $userDAta->email = $labdata['email'];
                          $userDAta->organization = $labdata['organization'];
                          
                          $userDAta->login_type = 2;
                          $userDAta->device_type = 3;
                          $userDAta->created_at = date('Y-m-d h:i:s');
                          $userDAta->updated_at = date('Y-m-d h:i:s');
                          $userDAta->save();
                          $userid = $userDAta->id;
                          createUsersReferralCode($userid);
                          }else{
                            $userid=$userDAta->id;
                          }

						  $labdata['coupon_rate']=(isset($couponData['coupon_rate'])) ? $couponData['coupon_rate'] : null;
						  $labdata['coupon_code']=(isset($couponData['coupon_code'])) ? $couponData['coupon_code'] : null;
						  $labdata['total_price']=$Totalprice;
						  $labdata['coupon_discount_type']=(isset($data['coupon_discount_type'])) ? $data['coupon_discount_type'] : null;
						  $labdata['package_name']=$labdata['package_name'];
						  $labdata['total_price_count']=$Totalprice;
						  $labdata['total']=$Total;
						  $labdata['user_id']=(isset($userDAta->id)) ? $userDAta->id : null;
						  $labdata['23']=0;
						  
						  $getLabres= $this->createLaborderforcsv($labdata,$userDAta);	
				
				
			}
			  Session::flash('message', "Data Imported successfully");
					      return back();
		
    }

    function getCoupondiscount($coupon) {
        $dt = date('Y-m-d');
       $query =  Coupons::select(['id','coupon_discount','other_text','coupon_code','apply_type','coupon_discount_type'])->where("coupon_code",$coupon)->whereDate('coupon_last_date','>=', $dt)->where('status','1')->first();//
        
        if($query) {
        
            $arr = array('status'=>'1','coupon_id'=>$query->id,'coupon_rate'=>$query->coupon_discount,'other_text'=>$query->other_text,'coupon_code'=>$query->coupon_code,'apply_type'=>$query->apply_type,'coupon_discount_type'=>$query->coupon_discount_type);
            return $arr;
        }
        else {
            return ['status'=>'0','msg'=>'Coupon Code Not Matched.'];
        }
  
}



public function getLabsPackageByCompID($data) {
  
        $orderpackages[]=$data['lab_package'];
 
  
    $labCollectiondata=[];

    if($data['package_name'] && $data['package_name'] != 2) {

        $packages = LabPackage::with(["LabCompany","DefaultLabs"])->where(['company_id'=>$data['package_name']])->where(['delete_status'=>1])->whereIn('id',$orderpackages)->get();
       
        return ['package'=>$packages,'cmp_id'=>$data['package_name']];
     
    }
    else{
        $packages = getThyrocareData("ALL");
    }

     return ['package'=>array(),'cmp_id'=>$data['package_name']];
 }


 
public function createLaborderforcsv($data,$userData) {

    $ProductId=[];
  
        $ProductId[]=$data['lab_package'];
 
 
    if(count($ProductId)>0){
        $packages = LabPackage::whereIn('id',$ProductId)->get();
    }

    $payType=$data['payment_mode'];
   
    if($data['payment_mode']==3){
        $data['pay_type']='Prepaid';
    }
    if($data['payment_mode']==5){
        $data['paymenttype']='5';
        $data['pay_type']='Prepaid';
    }
    if($data['payment_mode']==2){
        $data['pay_type']='Prepaid';
    }
    if($data['payment_mode']==6){
        $data['pay_link']='6';
        $data['pay_type']='Prepaid';
    }
    if($data['payment_mode']==4){
        $data['pay_type']='Prepaid';
        $user_array['tracking_id'] = $data['tracking_id_for_online_mode_only'];
    }else{
        $user_array['tracking_id'] = '';
    }

   if($data['date']){
    $data['date'] = str_replace('"', '', $data['date']);
   }
  
    $lab = LabOrders::create();
    $orderId = $lab->id."LAB".rand(10,100);
    LabOrders::where(["id"=>$lab->id])->update(["orderId"=>$orderId]);
    $appt_date_d = date("d-m-Y", strtotime($data['date']));
    // $appt_date = date("Y-m-d", strtotime($data[6]));
    $appt_date =  $appt_date_d;
    $appt_time = date("h:i:s A");
    $appt_date_time = $appt_date.' '.$appt_time;
    $user_array = array();
    $user_array['user_id'] = (isset($userData->id)) ? $userData->id : null;
    $user_array['address'] = $data['address'].' '.$data['pincode'];
    $user_array['address_id'] = (isset($data['plan_id'])) ? $data['plan_id'] : null;
    $user_array['bencount'] = "1";
    $user_array['address'] = $data['address'].' '.$data['pincode'];
    $user_array['mobile'] = $data['mobile'];
    $user_array['email'] = $data['email'];
    $user_array['Gender'] = (isset($data['gender'])) ? $data['gender']: '';
    $user_array['age'] = $data['age'];
    $user_array['bendataxml'] = NULL;
    $user_array['coupon_id'] = $data['coupon_code'];
    $user_array['discount_amt'] = (isset($data['discount_amt'])) ? $data['discount_amt'] : null;
    if($data['total']){
        $user_array['payable_amt'] = $data['total'];
    }else{
        $user_array['payable_amt'] = $data['total_price_count'];
    }
    $user_array['appt_date'] = $appt_date_time;
    $user_array['order_status'] = 'YET TO ASSIGN';
    $user_array['product'] = null;
    $user_array['plan_id'] = (isset($data['plan_id'])) ? $data['plan_id'] : null;
    if(isset($user_array['appt_date']) && !empty($user_array['appt_date'])) {
        $appt_date = strtotime($user_array['appt_date']);
    }
 
    $created_at=
    $created_at = date('Y-m-d h:i:s');
    LabOrders::where(["orderId"=>$orderId])->update([
        'type' => $data['package_name'],
        'user_id' => $user_array['user_id'],
        'address_id' => $user_array['address_id'],
        'product' => $user_array['product'],
        'pay_type' => $data['pay_type'],
        'coupon_id' => $user_array['coupon_id'],
        'order_by' => $data['name'],
        'order_type' => 0,
        'report_type' => 'no',
        'total_amt' => $data['total_price_count'],
        'discount_amt' => $user_array['discount_amt'],
        'coupon_amt' => $data['coupon_rate'],
        'payable_amt' => $user_array['payable_amt'],
        'meta_data' => json_encode($user_array),
        'appt_date' => $appt_date,
        'plan_id' => $user_array['plan_id'],
        'order_status' => $user_array['order_status'],
        'added_by' => $data['sale_by'],
        'payment_mode_type' => $payType,
        'created_at' => $created_at,
        'updated_at' => $created_at,
        'status' => 1,
        'org_id' => 1,
    ]);
    $product_name = [];

    $orderData=LabOrders::where(["id"=>$lab->id])->first();
 
    
    if(isset($packages) && count($packages) > 0) {
        foreach($packages as $itm) {
          
                        LabOrderedItems::create([
                            'package_id' =>$itm->id,
                            'order_id' => $lab->id,
                            // 'user_lab_id' => $raw['id'],
                            'product_name' => $itm->title,
                            'cost' => $itm->price,
                            'discount_amt' =>  $itm->discount_price,
                            'item_type' => "CUSTOM",
                        ]);
                
                $product_name[] = $itm->title;
           
        }
    }

    LabOrders::where(["orderId"=>$orderId])->update([
        'product' => count($product_name) > 0 ? implode(",",$product_name) : null
    ]);
 
        if(isset($payType) && $payType == '4'){
        
            LabOrderTxn::create([
                'order_id' => $lab->id,
                'tran_mode'=> 'Prepaid',
                'payed_amount'=>$user_array['payable_amt'],
                'currency' => "INR",
                'tracking_id'=>$data['tracking_id_for_online_mode_only'],
                'trans_date' => trim($data['transaction_date'])
            ]);
        }
    
    return ['status'=>true,'data'=>$user_array];
}

}
