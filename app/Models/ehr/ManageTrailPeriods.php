<?php
namespace App\Models\ehr;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ManageTrailPeriods extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
	 protected $connection = 'mysql_ehr'; 
     protected $fillable = ['subscription_id','subscribed_plan_id','user_plan_id','user_id','start_trail','end_trail','remaining_sms','old_date','status'];

    protected $table = 'manage_trail_periods';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
    public function user()
   	{
   		return $this->belongsTo('App\Models\ehr\User');
   	}

    public function Plans()
    {
        return $this->belongsTo('App\Models\ehr\Plans','user_plan_id');
    }
    public function PracticesSubscriptions()
    {
        return $this->belongsTo('App\Models\ehr\PracticesSubscriptions','subscription_id');
    }
    public function SubscribedPlans()
    {
        return $this->belongsTo('App\Models\ehr\SubscribedPlans','subscribed_plan_id');
    }
}
