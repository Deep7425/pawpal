<?php

namespace App\Models\ehr;

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
	protected $connection = 'mysql_ehr';  
    protected $table = 'lab_orders';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['patient_id','order_by','doctor_type','component_data','lab_name','file_name','comment','status','review_status','practice_id','delete_status','order_status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
    public function patient()
    {
        return $this->belongsTo('App\Models\ehr\Patients', 'patient_id');
    }
    public function PatientLabs()
    {
        return $this->hasMany('App\Models\ehr\PatientLabs', 'order_id')->where('delete_status',1);
    }
    public function user()
    {
        return $this->belongsTo('App\Models\ehr\User', 'order_by');
    }
    public function LabTransactions()
    {
        return $this->hasOne('App\Models\ehr\LabTransactions', 'order_id');
    }
    public function ReferralsMaster()
    {
        return $this->belongsTo('App\Models\ehr\ReferralsMaster', 'order_by');
    }
    public function LabOrderedItems()
    {
        return $this->hasMany('App\Models\ehr\LabOrderedItems', 'order_id');
    }
    public function RefundPatientLabBills()
    {
        return $this->hasMany('App\Models\ehr\RefundPatientLabBills', 'order_id')->orderBy('balance_amount', 'asc');
    }
    public function PatientRemainingBillLab()
    {
        return $this->hasMany('App\Models\ehr\PatientRemainingBillLab', 'order_id');
    }
}
?>
