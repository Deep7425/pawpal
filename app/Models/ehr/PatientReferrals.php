<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientReferrals extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_referrals';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
        'patient_id','appointment_id','referral_date','referral_by','email','phone_no','referral_to','referral_to_other','speciality_id','speciality_other','added_by','status','delete_status'
    ];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	 public function patient()
 {
   return $this->belongsTo('App\Models\ehr\Patients', 'patient_id');
 }
 public function ReferralsMaster(){
   return $this->belongsTo('App\Models\ehr\ReferralsMaster', 'referral_to');
 }


}
?>
