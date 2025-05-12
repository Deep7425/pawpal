<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PlanPeriods extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql'; 
	protected $appends = ['appt_ids'];
	 protected $fillable = ['subscription_id','subscribed_plan_id','user_plan_id','user_id','start_trail','end_trail','remaining_appointment','specialist_appointment_cnt','lab_pkg_remaining','appointment_ids','plan_id','status'];

    protected $table = 'plan_periods';
    public $timestamps = true;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function getApptIdsAttribute()
	{
		return explode(",",$this->appointment_ids);
	} 
    public function user()
   	{
   		return $this->belongsTo('App\Models\User');
   	}
    public function Plans()
    {
        return $this->belongsTo('App\Models\Plans','user_plan_id');
    }
    public function UsersSubscriptions()
    {
        return $this->belongsTo('App\Models\UsersSubscriptions','subscription_id');
    }
    public function SubscribedPlans()
    {
        return $this->belongsTo('App\Models\SubscribedPlans','subscribed_plan_id');
    }
	public function UserSubscribedPlans()
    {
        return $this->belongsTo('App\Models\UserSubscribedPlans','subscribed_plan_id');
    }
}
