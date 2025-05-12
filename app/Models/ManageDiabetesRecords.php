<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ManageDiabetesRecords extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'manageDiabetes_records';
	protected $fillable = ['user_id','sugar_level','test_id','date','time','notes','ques_meta','result','result_note','order_id','date','time','ques_meta','result','result_note','status','delete_status'];
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
		return $this->hasMany('App\Models\HealthQuestion','order_id','order_id')->where('type',2)->where('delete_status',1);
    }
	public function getHinQuestion() {
		return $this->hasOne('App\Models\HealthQuestion','order_id','order_id')->where('type',2)->where('delete_status',1)->where('lang','=', 2);
	}
	public function getEnQuestion() {
		return $this->hasOne('App\Models\HealthQuestion','order_id','order_id')->where('type',2)->where('delete_status',1)->where('lang','=', 1);
	}
}
