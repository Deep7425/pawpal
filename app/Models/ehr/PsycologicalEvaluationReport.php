<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PsycologicalEvaluationReport extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $table = 'psycological_evaluation_report';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['appointment_id','pId','status', 'address' ,'marital_status', 'added_by', 'education' ,'purpose','recommendation','test_summary','test_findings','test_behaviour','test_administered','pre_diagnosis','mental_status_examination','	medications','treatment_history','cheif_complaints','family_history','socio_demographic_details','reliability_and_adequacy','source_of_information','created_at','updated_at']; 
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */
	 
	public function patient() {
		return $this->belongsTo('App\Models\Patients', 'pId');
	}
}
?>
