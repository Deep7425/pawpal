<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Patients extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $connection = 'mysql_ehr';  
    protected $fillable = [
        'patient_number','first_name','last_name','email','image','student_id','mobile_no','other_mobile_no','aadhar_no','dob','gender','country_id','state_id','city_id','address','zipcode',
        'father_name','notifyBy','status','added_by','parent_id','practices_id'
  ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patients';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

	public function PatientRagistrationNumbers(){
		return $this->hasOne('App\Models\ehr\PatientRagistrationNumbers','pid');
	 }
    /**
     * User Details Relationships
     *
     * @var array
     */
   
}
