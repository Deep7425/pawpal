<?php
namespace App\Models;
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
    protected $table = 'subscription_plans';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['plan_title','price','discount_price','plan_duration_type','plan_duration','max_appointment_fee','appointment_cnt','specialist_appointment_cnt','content','lab_pkg_title','lab_pkg','max_patient_count','status','created_at','updated_at','type','is_best','slug'];
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */
}
?>
