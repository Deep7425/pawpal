<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientAdvice extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $table = 'patient_advice';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = ['appointment_id','pId','status','data','added_by']; 
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */
	 
	public function patient()
	{
		return $this->belongsTo('App\Models\ehr\Patients', 'pId');
	} 
}
?>
