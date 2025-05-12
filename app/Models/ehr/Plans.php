<?php
namespace App\Models\ehr;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Plans extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
	 protected $connection = 'mysql_ehr'; 
    protected $table = 'subscription_plans';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['plan_type','plan_title','plan_price','discount_price','payment_duration_type','plan_duration_type','plan_duration','promotional_sms_limit','core_modules','other_text','status','delete_status','created_at','updated_at'];
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */

   public function SubscribedPlans()
   {
       return $this->hasMany('App\Models\ehr\SubscribedPlans','subscription_id');
   }
}
?>
