<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientLabs extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_labs';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['patient_id','appointment_id','pack_status','pack_id','lab_id','lab_name','instructions','lr_unit','lr_range','result_type','result','comment','file_name','status','delete_status','added_by','order_id'];
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */
	public function PatientSubLabs()
  {
      return $this->hasMany('App\Models\ehr\PatientSubLabs', 'parent_id');
  }
  public function patient()
  {
      return $this->belongsTo('App\Models\ehr\Patients', 'patient_id');
  }
  public function Appointments()
  {
      return $this->belongsTo('App\Models\ehr\Appointments', 'appointment_id');
  }

  public function labs()
  {
      return $this->belongsTo('App\Models\ehr\Labs', 'lab_id');
  }
   public function LabPack()
  {
      return $this->belongsTo('App\Models\ehr\LabPack', 'pack_id');
  }
  public function LabTransactions()
  {
      return $this->hasOne('App\Models\ehr\LabTransactions', 'order_id');
  }
  public function LabOrders()
  {
      return $this->belongsTo('App\Models\ehr\LabOrders', 'order_id');
  }
}
?>
