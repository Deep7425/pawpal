<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Nutritionalinfo extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
     protected $connection = 'mysql_ehr';
    protected $table = 'nutritional_info';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['appointment_id','patient_id','doc_id','medical_concern','eating_habits','disease','disease_option','medical_treatment','medical_treatment_option','allergy','physical_activity','work_schedule_from','work_schedule_to','life_style','body_type','energy_calories','protein','fat','calcium','added_by','status','delete_status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
   	public function user()
 	{
 		return $this->belongsTo('App\Models\ehr\User', 'added_by');
 	}
	public function patient()
	{
		return $this->belongsTo('App\Models\ehr\Patients', 'patient_id');
	}
}
?>
