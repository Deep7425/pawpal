<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientEyes extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_eyes';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id','patient_id','dental_id','d_od_s','d_od_c','d_od_a','d_od_p','d_od_b','d_os_s','d_os_c','d_os_a','d_os_p','d_os_b','a_od_s','a_os_s','eye_note','status','delete_status','created_at','updated_at','added_by'
    ];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
   public function appointments()
   {
       return $this->hasMany('App\Models\ehr\Appointments','pId');
   }

   public function practiceDetails()
   {
     return $this->belongsTo('App\Models\ehr\PracticeDetails', 'added_by','user_id');
   }
   public function Appointment(){
     return $this->belongsTo('App\Models\ehr\Appointments', 'appointment_id');
   }	

 }
  ?>
