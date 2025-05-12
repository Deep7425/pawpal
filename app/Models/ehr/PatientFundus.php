<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientFundus extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $table = 'patient_fundus';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id','patient_id','fundus_img_check','fundus_img_right_eye','fundus_right_eye_note','fundus_img_left_eye','fundus_left_eye_note','fundus_master_id_left','fundus_master_id_right','status','delete_status','added_by'
    ];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

	public function Appointment() {
		return $this->belongsTo('App\Models\ehr\Appointments', 'appointment_id');
	}
	public function FundusMasterLeft() {
		return $this->belongsTo('App\Models\ehr\FundusMaster', 'fundus_master_id_left');
	}
	public function FundusMasterRight() {
		return $this->belongsTo('App\Models\ehr\FundusMaster', 'fundus_master_id_right');
	}
 }
  ?>
