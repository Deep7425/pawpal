<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UserDetails extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_details';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','aadhar_no','father_name','heightCm','weight','bmi','bp_systolic','bp_diastolic','pulse_rate','temprature','head_circumference','smoking_habits','alcohol_consumption','activity_level','food_preference','occupation','notes','referral_code','referred_code','wallet_amount'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function user() {
		return $this->belongsTo('App\Models\User');
	}
	
	public function foodPreferenceMaster() {
		return $this->belongsTo('App\Models\foodPreferenceMaster','food_preference');
	}
	
	public function smokingHabitsMaster() {
		return $this->belongsTo('App\Models\smokingHabitsMaster','smoking_habits');
	}
	
	public function occupationMaster() {
		return $this->belongsTo('App\Models\occupationMaster','occupation');
	}
	
	public function alcoholConsumptionMaster() {
		return $this->belongsTo('App\Models\alcoholConsumptionMaster','alcohol_consumption');
	}
	
	public function activityLevelMaster() {
		return $this->belongsTo('App\Models\activityLevelMaster','activity_level');
	}
	
	
	
}
