<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DietPlan extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
     protected $connection = 'mysql_ehr';
    protected $table = 'diet_plan';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['appointment_id','patient_id','doc_id','no_of_meal','meal_plan_id','meal_plan_time','menu','menu_exchange','ingredient','added_by','status','delete_status'];

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
	public function MealPlanMaster()
	{
		return $this->belongsTo('App\Models\ehr\MealPlanMaster', 'meal_plan_id');
	}
	public function PatientDietPlanFile()
	{
		return $this->belongsTo('App\Models\ehr\PatientDietPlanFile', 'appointment_id');
	}

}
?>
