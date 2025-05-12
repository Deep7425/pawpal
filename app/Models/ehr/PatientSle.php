<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientSle extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $table = 'patient_sle';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id','ipd_id','patient_id','notes','sle_eye','sle_id','status','delete_status','added_by'
    ];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

	public function Sle(){
	  return $this->belongsTo('App\Models\ehr\SleMaster', 'sle_id');
	}
	public function Appointment(){
		return $this->belongsTo('App\Models\ehr\Appointments', 'appointment_id');
	}
 }
  ?>
