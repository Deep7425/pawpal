<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ManageBpRecords extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'manageBp_records';
	protected $fillable = ['user_id','bp_systolic','bp_diastolic','pulse_rate','weight','notes','date','time','ques_meta','result','result_note','order_id','status','delete_status'];
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
  /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function Speciality() {
        return $this->belongsTo('App\Models\Speciality','result');
    } 
	public function HealthQuestion() {
		return $this->hasMany('App\Models\HealthQuestion','order_id','order_id')->where('type',3)->where('delete_status',1);
    }
	public function getHinQuestion() {
		return $this->hasOne('App\Models\HealthQuestion','order_id','order_id')->where('type',3)->where('delete_status',1)->where('lang','=', 2);
	}
	public function getEnQuestion() {
		return $this->hasOne('App\Models\HealthQuestion','order_id','order_id')->where('type',3)->where('delete_status',1)->where('lang','=', 1);
	}
}
