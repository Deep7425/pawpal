<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class FollowUp extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['patient_id','doc_id','appointment_id','follow_up_date','follow_up_status','added_by'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'follow_up';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    /**
     * User Details Relationships
     *
     * @var array
     */
     public function appointments()
     {
         return $this->hasMany('App\Models\ehr\Appointments','patient_id');
     }
	 public function practiceDetails()
	 {
	   return $this->hasMany('App\Models\ehr\PracticeDetails', 'added_by');
	 }
	 public function patient()
	 {
	   return $this->belongsTo('App\Models\ehr\Patients','patient_id');
	 }
	  public function user()
 	{
 		return $this->belongsTo('App\Models\ehr\User', 'doc_id');
 	}
}
