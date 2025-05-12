<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientAllergy extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_allergies';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id','patient_id','allergy_type','allergy_id','allergy_reactions','severity','notes','status','delete_status','created_at','added_by'
    ];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function students()
	{
	  return $this->belongsTo('App\Models\ehr\Students');
	}
	 public function Allergies()
        {
          return $this->belongsTo('App\Models\ehr\Allergies', 'allergy_id');
        }
	public function Appointment(){
		return $this->belongsTo('App\Models\ehr\Appointments', 'appointment_id');
	}
 }       
  ?>
