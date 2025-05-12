<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class RadiologyOrders extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $table = 'radiology_orders';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['patient_id','order_by','doctor_type','component_data','status','practice_id','delete_status','order_status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function patient()
	{
		return $this->belongsTo('App\Models\ehr\Patients', 'patient_id');
	}
	public function PatientDiagnosticImagings()
	{
		return $this->hasMany('App\Models\ehr\PatientDiagnosticImagings', 'order_id')->where('delete_status',1);
	}
	public function user()
	{
		return $this->belongsTo('App\Models\ehr\User', 'order_by');
	}
	public function RadiologyTransactions()
	{
		return $this->hasOne('App\Models\ehr\RadiologyTransactions', 'order_id');
	}
	public function ReferralsMaster()
	{
		return $this->belongsTo('App\Models\ehr\ReferralsMaster', 'order_by');
	}
	public function RadiologyOrderedItems()
	{
		return $this->hasMany('App\Models\ehr\RadiologyOrderedItems', 'order_id');
	}
  public function RefundPatientRadiologyBills()
  {
    return $this->hasMany('App\Models\ehr\RefundPatientRadiologyBills', 'order_id')->orderBy('balance_amount', 'asc');
  }
  public function PatientRemainingBillRadiology()
  {
      return $this->hasMany('App\Models\ehr\PatientRemainingBillRadiology', 'order_id');
  }
}
?>
