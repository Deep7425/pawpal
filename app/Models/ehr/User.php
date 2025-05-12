<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'member_id','first_name','last_name','email','mobile_no','varify_status','reg_device_type','social_id','login_type','fcm_token','device_id','is_login','profile_status','otp','delete_status','api_token','password','is_curl','is_curl_profile','mac_address'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'users';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','api_token',
    ];

    /**
     * User Details Relationships
     *
     * @var array
     */
    public function practiceDetails()
    {
        return $this->hasOne('App\Models\ehr\PracticeDetails','user_id');
    }
    public function doctorInfo()
    {
        return $this->hasOne('App\Models\ehr\DoctorsInfo','user_id');
    }   
	public function OpdTimings()
  	{
  		return $this->hasOne('App\Models\ehr\OpdTimings','user_id');
  	}
	public function RoleUser()
    {
        return $this->hasOne('App\Models\ehr\RoleUser','user_id','id');
    }
}
