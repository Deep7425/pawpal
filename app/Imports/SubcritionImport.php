<?php

namespace App\Imports;

use App\Models\User;
use App\Models\UsersSubscriptions;
use Maatwebsite\Excel\Concerns\ToModel;
use App\Models\UserSubscriptionsTxn;
use App\Models\UserSubscribedPlans;
use App\Models\Plans;
use App\Models\ApptLink;
use App\Models\PlanPeriods;
use App\Models\ReferralMaster;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class SubcritionImport implements WithHeadingRow, ToCollection, WithChunkReading
{
  use RemembersRowNumber;
  /**
   * @param array $row
   *
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function collection(Collection $rows)
  {


    foreach ($rows as $k => $labdata) {



      $userDAta = User::where('mobile_no', $labdata['mobile'])->select('id')->where('parent_id', 0)->first();


      //  $labdata['total']=$Total;
      $labdata['user_id'] = (isset($userDAta->id)) ? $userDAta->id : null;

      // dd(34);
      $this->Createsubcription($labdata, $k);
    }
    //   Session::flash('message', "Data Imported successfully");
    // 		      return back();

  }


  public function Createsubcription($data, $roWcount) {
    if (empty($data['plan_id'])) {
        Session::flash('message', "Please Provide Plan Id");
        return back();
    }

    if ($roWcount == 0) {
        $planData = getPlanDetails($data['plan_id']);
        if (!empty($data['referral_code'])) {
            $couponData = $this->ApplyReferralCodeAdmin($data);
            Session::put('referral_user_id', $couponData['referral_user_id']);
            Session::put('coupon_discount', $couponData['coupon_discount']);
        }
        Session::put('discountPrice', $planData->discount_price);
        Session::put('price', $planData->price);
    }

    if (!empty($data['referral_code'])) {
        $data['order_total'] = Session::get('price') - Session::get('coupon_discount') - Session::get('discountPrice');  
        $data['referral_user_id'] = Session::get('referral_user_id') ?? '';
        $data['coupon_discount'] = Session::get('coupon_discount') ?? '';
    } else {
        $data['order_total'] = Session::get('price') - Session::get('discountPrice');
        $data['referral_user_id'] = '';
        $data['coupon_discount'] = '';
    }

    // **Set Trial Start Date from `subcribetime` if provided, else use current date**
    $subcribedate = !empty($data['subcribetime']) ? Carbon::parse($data['subcribetime'])->format('Y-m-d H:i:s') : now();

    $subscription = UsersSubscriptions::create([
        'login_id' => Session::get('id'),
        'user_id' => $data['user_id'],
        'payment_mode' => $data['payment_mode'],
        'ref_code' => $data['referral_user_id'],
        'created_at' => $subcribedate,
        'coupon_discount' => $data['coupon_discount'],
        'order_subtotal' => $data['order_total'],
        'order_total' => $data['order_total'],
        'order_status' => ($data['payment_mode'] == '6') ? 0 : 1,
        'added_by' => $data['sale_by'],
        'remark' => $data['remarks'],
        'organization_id' => $data['corporate'],
        'meta_data' => json_encode($data),
    ]);

    $subs_id = $subscription->id;
    $plan = Plans::find($data['plan_id']);

    $subscribedPlan = new UserSubscribedPlans;
    $subscribedPlan->plan_id = $plan->id;
    $subscribedPlan->plan_price = $plan->price;
    $subscribedPlan->discount_price = $plan->discount_price;
    $subscribedPlan->plan_duration_type = $plan->plan_duration_type;
    $subscribedPlan->plan_duration = $plan->plan_duration;
    $subscribedPlan->appointment_cnt = $plan->appointment_cnt;
    $subscribedPlan->lab_pkg = $plan->lab_pkg;
    $subscribedPlan->meta_data = json_encode($plan);
    $subscription->UserSubscribedPlans()->save($subscribedPlan);

    // **Calculate Trial Period End Date based on Plan Duration**
    $duration_in_days = match ($plan->plan_duration_type) {
        "d" => $plan->plan_duration,
        "m" => $plan->plan_duration * 30,
        "y" => $plan->plan_duration * 366,
        default => 0
    };

    $end_date = Carbon::parse($subcribedate)->addDays($duration_in_days)->format('Y-m-d H:i:s');

    PlanPeriods::create([
        'subscription_id' => $subs_id,
        'subscribed_plan_id' => $subscribedPlan->id,
        'user_plan_id' => $data['plan_id'],
        'user_id' => $data['user_id'],
        'start_trail' => $subcribedate,
        'end_trail' => $end_date,
        'remaining_appointment' => $plan->appointment_cnt,
        'specialist_appointment_cnt' => $plan->specialist_appointment_cnt,
        'lab_pkg_remaining' => 0,
        'status' => 1
    ]);

    $tran_mode = match ($data['payment_mode']) {
        '2' => "Cheque",
        '3' => "Cash",
        '4' => "Online Payment",
        '5' => "Free",
        default => "Unknown"
    };

    UserSubscriptionsTxn::create([
        'subscription_id' => $subs_id,
        'tracking_id' => $data['tracking_id'] ?? null,
        'tran_mode' => $tran_mode,
        'currency' => "INR",
        'payed_amount' => $data['order_total'],
        'tran_status' => "Success",
        'trans_date' => date('d-m-Y')
    ]);
}



  function ApplyReferralCodeAdmin($data)
  {

    // $data = $request->all();
    $success = 0;
    $res = ["success" => $success, "referral_user_id" => "", "coupon_discount" => ""];

    $refData = ReferralMaster::where('code', $data['referral_code'])->where(['status' => 1, 'delete_status' => 1])->first();
    if (!empty($refData)) {
      $dt = date('Y-m-d');
      if ($refData->code_last_date < $dt) {
        $success = 0;
      }
      if (!empty($refData->plan_ids)) {
        $plan_ids = explode(",", $refData->plan_ids);
        if (in_array($data['plan_id'], $plan_ids)) {
          $dis = getDiscount($refData->referral_discount_type, $refData->referral_discount, $data['plan_id']);
          $res['type'] =  2;
          $res['referral_user_id'] =  $refData->id;
          $res['coupon_discount'] =  $dis;
          $success = 1;
        }
      } else {
        $dis = getDiscount($refData->referral_discount_type, $refData->referral_discount, $data['plan_id']);
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

  public function batchSize(): int
  {
    return 100;
  }

  public function chunkSize(): int
  {
    return 100;
  }
}

