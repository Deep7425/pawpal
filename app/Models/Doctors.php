<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Doctors extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
		protected $connection = 'mysql';
	  protected $fillable = ['login_id' , 'name' , 'name','member_id','user_id','practice_id','reg_no','reg_year','reg_council','last_obtained_degree','degree_year','university','first_name','last_name','email','password','clinic_email','mobile_no','clinic_mobile','clinic_mobile_2','speciality', 'address_1', 'gender','country_id','state_id','city_id','other_cities','locality_id','zipcode','profile_pic','opd_timings','slot_duration','claim_profile_web','varify_status','status','delete_status','hg_doctor','urls','clinic_name','clinic_image','my_visits','consultation_fees','consultation_discount','fees_show','experience','qualification','recommend','note','clinic_speciality','print_layout','billing_print_layout','centric_emailid','centric_mobileno','bday_msg_status','bday_msg','website','doctor_sign','sign_view','content','claim_status','my_visits','lat','lng','practice_type','hg_interested','ref_code','oncall_fee','oncall_status','acc_name','acc_no','ifsc_no','bank_name','paytm_no','convenience_fee','servtel_api_key','admin_note','age','about','doctor_signature'];

    protected $table = 'doctors';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

	public function docSpeciality() {
        return $this->belongsTo('App\Models\Speciality','speciality');
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
	public function CityLocalities(){
  	  return $this->belongsTo('App\Models\ehr\CityLocalities','locality_id');
  	}
	public function SymptomsSpeciality() {
        return $this->hasMany('App\Models\Admin\SymptomsSpeciality','speciality_id','speciality');
    }
	// public function DoctorRatingReviews() {
        // return $this->hasMany('App\Models\DoctorRatingReviews','doc_id','id');
    // }
	public function DoctorRatingReviews() {
		return $this->hasMany('App\Models\PatientFeedback','doc_id','id')->where(["status"=>1,"delete_status"=>1,"publish_admin"=>1])->orderBy('created_at','DESC');
    }
    public function ManageSponsored() {
        return $this->hasOne('App\Models\ManageSponsored','user_id','user_id');
    }
	public function RegCouncil() {
        return $this->hasOne('App\Models\CouncilingData','id','reg_council');
    }
    public function University() {
        return $this->hasOne('App\Models\UniversityList','id','university');
    }
	public function DoctorSlug() {
        return $this->hasOne('App\Models\DoctorSlug','doc_id');
    }
	public function DoctorDocuments() {
        return $this->hasMany('App\Models\DoctorDocuments','doc_id','id');
    }
	public function DoctorData() {
        return $this->hasOne('App\Models\DoctorData','doc_id','id');
    }

    public function DoctorsInfo() {
        return $this->hasOne('App\Models\ehr\DoctorsInfo','user_id','user_id');
    }

    public function admin()
{
    return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
}


}
