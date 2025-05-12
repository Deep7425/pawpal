<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DailyReport extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'instant_subs_report';
	protected $fillable = ['off_today','total_students','plan_online','plan_cash','actual_sub_cash','actual_sub_online','amount','status','added_by','created_at','updated_at'];
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
	public function Admin(){
  		  return $this->belongsTo('App\Models\Admin\Admin', 'added_by');
  	} 
      public function UsersSubscriptions(){
        return $this->belongsTo('App\Models\UsersSubscriptions', 'added_by');
      }
}
