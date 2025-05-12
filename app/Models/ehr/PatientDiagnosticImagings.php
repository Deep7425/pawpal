<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientDiagnosticImagings extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_diagnostic_imagings';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
        'patient_id','appointment_id','lab_id', 'title', 'instructions', 'di_finding', 'comments','file','di_status','status','delete_status','added_by','order_id'
    ];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	 public function patient()
 {
   return $this->belongsTo('App\Models\ehr\Patients', 'patient_id');
 }
 public function Appointments()
 {
     return $this->belongsTo('App\Models\ehr\Appointments', 'appointment_id');
 }
 
 public function radiologyMaster()
 {
   return $this->belongsTo('App\Models\ehr\RadiologyMaster', 'lab_id');

 }
  public function RadiologyOrders()
  {
    return $this->belongsTo('App\Models\ehr\RadiologyOrders', 'order_id');
  }
}
?>
