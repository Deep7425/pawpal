<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class LabOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'lab_orders';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['created_by' , 'name' , 'orderId','user_id','address_id','product','pay_type','type','report_type','coupon_id','order_by','order_type','total_amt','discount_amt','coupon_amt','payable_amt','meta_data','appt_date','status','delete_status','order_status','cancel_reason','plan_id','lab_type','service_charge','payment_mode_type','order_from','company_id','added_by'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public function LabOrderTxn()
    {
        return $this->hasOne('App\Models\LabOrderTxn', 'order_id');
    }
    public function LabOrderedItems()
    {
        return $this->hasMany('App\Models\LabOrderedItems', 'order_id');
    }
	public function LabReports()
    {
        return $this->hasOne('App\Models\LabReports', 'order_id');
    }
	 public function Coupons()
    {
        return $this->belongsTo('App\Models\Coupons', 'coupon_id');
    }
	 public function PlanPeriods()
    {
        return $this->belongsTo('App\Models\PlanPeriods', 'plan_id');
    }
    public function UsersLabordersAddress()
    {
        return $this->belongsTo('App\Models\UsersLaborderAddresses', 'user_id');
    }
	public function LabCompany()
    {
        return $this->belongsTo('App\Models\LabCompany', 'company_id');
    }
    public function admin(){
        return $this->belongsTo(Admin\Admin::class, 'created_by', 'id');
    }
}
?>