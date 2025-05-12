<?php
namespace App\Models\ehr;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PracticesSubscriptions extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr';  
     protected $fillable = ['user_id','payment_mode','coupon_id','coupon_discount','tax','order_subtotal','order_total','order_status'];

    protected $table = 'practices_subscriptions';
    //public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

    public function PracticeDetails()
    {
        return $this->belongsTo('App\Models\ehr\PracticeDetails','user_id');
    }
    public function SubscribedPlans()
    {
        return $this->hasMany('App\Models\ehr\SubscribedPlans','subscription_id');
    }
    public function SubscriptionsTxn()
    {
        return $this->hasOne('App\Models\ehr\SubscriptionsTxn','subscription_id');
    }
}
