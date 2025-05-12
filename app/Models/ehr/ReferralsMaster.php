<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ReferralsMaster extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'referrals_master';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['doctor_name','clinic_name','speciality_id','phone_no','email','address','city_id','state_id','country_id','zipcode','status','added_by','delete_status'];

   public function doctorSpecialities() {
       return $this->belongsTo('App\Models\ehr\Speciality','speciality');
   } 
}
?>
