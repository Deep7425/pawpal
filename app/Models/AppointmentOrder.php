<?php
namespace App\Models\ehr;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class AppointmentOrder extends Authenticatable {
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $fillable = ['login_id' , 'name' , 'type' , 'patient_id','appointment_id','coupon_id','coupon_discount','service_charge_meta','service_charge','order_subtotal','order_total','order_status','app_date','doc_id','order_by','switch_apt','rating','order_from','referral_code','hg_miniApp','meta_data'];

    protected $table = 'appointment_order';
	
	public function AppointmentTxn(){
		return $this->hasOne('App\Models\ehr\AppointmentTxn', 'order_id');
	}
	public function Appointments(){
		return $this->belongsTo('App\Models\ehr\Appointments', 'appointment_id');
	}
	public function PlanPeriods() {
		return $this->hasMany('App\Models\PlanPeriods','user_id','order_by');
	}

	public function admin(){
        return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
    }
}
