<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DoctorsInfo extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr';
    protected $table = 'doctors_info';
	
	protected $fillable = ['user_id','first_name', 'last_name','gender', 'reg_no', 'reg_year','reg_council','last_obtained_degree','degree_year','university','consultation_discount','mobile', 'speciality', 'address_1', 'educations','experience','consultation_fee','address_2', 'country_id', 'state_id', 'city_id','locality_id' ,'zipcode','profile_pic','doctor_sign','sign_view','content',  'daily_schedule_sms', 'daily_schedule_email', 'appointment_sms', 'appointment_email','is_notification','practice_id','lat','lng','hg_doctor','ref_code','claim_status','room_no','display_status','manage_eye','oncall_fee','oncall_status','acc_no','ifsc_no','bank_name','paytm_no','acc_name','servtel_api_key'];
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function docSpeciality() {
        return $this->belongsTo('App\Models\ehr\Speciality','speciality');
    }
	public function user(){
		return $this->belongsTo('App\Models\ehr\User');
	}
	public function getCountryName(){
		return $this->belongsTo('App\Models\Country', 'country_id');
	}
	public function getStateName(){
		return $this->belongsTo('App\Models\State', 'state_id');
	}
	public function getCityName(){
		return $this->belongsTo('App\Models\City', 'city_id');
	}
	public function practiceDetails(){
        return $this->belongsTo('App\Models\ehr\PracticeDetails','user_id');
    }
	public function RoleUser(){
        return $this->belongsTo('App\Models\ehr\RoleUser','user_id','user_id');
    }
	public function OpdTimings(){
		return $this->hasOne('App\Models\ehr\OpdTimings','user_id','user_id')->select(['schedule', 'user_id']);
    }
}
