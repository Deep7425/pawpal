<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UsersSubscriptions extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $fillable = ['user_id', 'login_id' , 'name' , 'order_id','payment_mode','coupon_id','coupon_discount','tax','order_subtotal','order_total','order_status','ref_code','hg_miniApp','meta_data','added_by','remark','organization_id','order_from','subcribedate','created_at','razorpay_order_id'];

    protected $table = 'user_subscriptions';
    //public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed


     */


    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
	public function UserReferral()
    {
        return $this->belongsTo('App\Models\ReferralCashback','user_id','referral_id');
    }
	public function ReferralMaster()
    {
        return $this->belongsTo('App\Models\ReferralMaster','ref_code');
    }
    public function UserSubscribedPlans()
    {
        return $this->hasMany('App\Models\UserSubscribedPlans','subscription_id');
    }
    public function UserSubscriptionsTxn()
    {
        return $this->hasOne('App\Models\UserSubscriptionsTxn','subscription_id');
    }
	public function PlanPeriods()
    {
        return $this->hasOne('App\Models\PlanPeriods','subscription_id');
    }
    public function isSubComplete()
    {
      $UsersSubscriptions = UsersSubscriptions::where('user_id', $this->user_id)->where('order_status', 1)->count();
      $dt = date('Y-m-d');
      $PlanPeriods =  PlanPeriods::whereDate('start_trail','<=', $dt)->whereDate('end_trail','>=', $dt)->where(['user_id'=>$this->user_id,'status'=>1])->count();
      if($UsersSubscriptions == 1 && $PlanPeriods == 1){
        return true;
      }else {
        return false;
      }
    }

    
    public function admin()
    {
        return $this->belongsTo('App\Models\Admin\Admin', 'login_id', 'id');
    }
    public function OrganizationMaster()
    {
        return $this->belongsTo('App\Models\OrganizationMaster','organization_id');
    }

}
