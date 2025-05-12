<?php

namespace App\Models;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ehr\Appointments;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable{
   use HasApiTokens, Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $connection = 'mysql';  
    protected $fillable = [
         'patient_number','pId','reg_no', 'department_id' ,'first_name','last_name','email','aadhar_no','password','image','mobile_no','other_mobile_no','dob','fb_social_id','google_social_id','login_type','fcm_token','device_token','device_type','is_login','notification_status','profile_status','status','added_by','parent_id','otp','notifyBy','gender','country_id','state_id','city_id','locality_id','address','zipcode','urls','practices_id','content','note','profession_type','organization','location_meta','is_cashBack','followup_date','register_by','api_token', 'student_id', 'fcm_token_current'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
	protected $appends = ['tot_appointment'];   
    protected $hidden = [
        'password', 'remember_token','api_token'
    ];

    /**
     * User Details Relationships
     *
     * @var array
     */
	function getTotAppointmentAttribute($query) {
		if(!empty($this->pId)){
		return Appointments::select("id")->where('pId',$this->pId)->where("delete_status",1)->whereIn('app_click_status',array(5,6))->where("added_by","!=",24)->count();
		}
		else return 0;
	} 
	public function userDetails() {
        return $this->hasOne('App\Models\UserDetails','user_id');
    }
	public function getCityName(){
  		  return $this->belongsTo('App\Models\City', 'city_id');
  	}
	public function State(){
  		  return $this->belongsTo('App\Models\State', 'state_id');
  	}
	public function CityLocalities(){
  	  return $this->belongsTo('App\Models\ehr\CityLocalities','locality_id');
  	} 
	public function OrganizationMaster(){
  	  return $this->belongsTo('App\Models\OrganizationMaster','organization' );
  	} 
	public function childUsers() {
		return $this->hasMany('App\Models\User','parent_id','pId');
    }
	public function UserCashback() {
        return $this->belongsTo('App\Models\UserCashback','user_id');
    }
    public function LabOrders() {
        return $this->hasOne('App\Models\LabOrders','user_id');
    }
	public function UsersSubscriptions() {
        return $this->hasOne('App\Models\UsersSubscriptions','user_id');
    }
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
