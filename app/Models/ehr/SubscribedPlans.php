<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class SubscribedPlans extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $fillable = ['subscription_id','plan_id','plan_type','plan_price','discount_price','plan_duration_type','plan_duration','promotional_sms_limit','meta_data'];
    protected $table = 'subscribed_plans';
    //public $timestamps = false;
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

    public function Plans()
    {
        return $this->belongsTo('App\Models\ehr\Plans','plan_id');
    }
    public function PracticesSubscriptions()
    {
        return $this->belongsTo('App\Models\ehr\PracticesSubscriptions','subscription_id');
    }
    public function SubscriptionsTxn()
    {
        return $this->hasOne('App\Models\ehr\SubscriptionsTxn','subscription_id');
    }
    public function ManageTrailPeriods()
    {
        return $this->hasOne('App\Models\ehr\ManageTrailPeriods','subscribed_plan_id');
    }
}
