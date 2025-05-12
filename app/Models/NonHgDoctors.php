<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class NonHgDoctors extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $fillable = ['member_id','user_id','reg_no','first_name','last_name','clinic_email','email','mobile_no','clinic_mobile','speciality', 'address_1', 'gender','country_id','state_id','city_id','zipcode','profile_pic','opd_timings','slot_duration','status','delete_status','hg_doctor','urls','clinic_name','clinic_image','my_visits','consultation_fees','experience','qualification','recommend','lat','lng','note'];

    protected $table = 'non_hg_doctor';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	 
	public function docSpeciality()
    {
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
	public function SpecialitySymptoms()
    {
        return $this->hasMany('App\Models\Admin\SpecialitySymptoms','spaciality_id','speciality');
    }

}
