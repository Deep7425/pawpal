<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UserSubscribedPlans extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = ['subscription_id','plan_id','plan_type','plan_price','discount_price','plan_duration_type','plan_duration','promotional_sms_limit','meta_data'];
    protected $table = 'user_subscribed_plans';
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
        return $this->belongsTo('App\Models\Plans','plan_id');
    }
    public function PracticesSubscriptions()
    {
        return $this->belongsTo('App\Models\PracticesSubscriptions','subscription_id');
    }
    public function UserSubscriptionsTxn()
    {
        return $this->hasOne('App\Models\UserSubscriptionsTxn','subscription_id');
    }
    public function PlanPeriods()
    {
        return $this->hasOne('App\Models\PlanPeriods','subscribed_plan_id');
    }
}
