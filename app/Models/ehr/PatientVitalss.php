<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientVitalss extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_vitals';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id','patient_id','heightCm','weight','bmi','bp_systolic','bp_diastolic','pulse_rate','temprature','head_circumference','notes','status','delete_status','added_by'
    ];
	public function Appointment(){
		return $this->belongsTo('App\Models\ehr\Appointments', 'appointment_id');
	}
    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	
}
?>
