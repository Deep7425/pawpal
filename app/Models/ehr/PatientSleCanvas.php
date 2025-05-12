<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientSleCanvas extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $table = 'patient_sle_canvas';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['appointment_id','patient_id','canvas_img','created_at','updated_at','added_by'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
   public function appointments()
   {
       return $this->hasMany('App\Models\Appointments','pId');
   }

   public function practiceDetails()
   {
     return $this->belongsTo('App\Models\PracticeDetails', 'added_by','user_id');
   }
   public function Appointment(){
     return $this->belongsTo('App\Models\Appointments', 'appointment_id');
   }

 }
  ?>
