<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Coupons extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'coupons';
    protected $fillable = ['type','coupon_sub_type','coupon_title','coupon_code', 'name', 'login_id' ,'coupon_discount_type','coupon_discount','plan_type','coupon_duration_type','coupon_duration','coupon_last_date','other_text','term_conditions','apply_type','status','added_by','generated_by','max_uses','is_show','created_at','updated_at'];
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
	public function MedicalStoreDetails() {
        return $this->hasOne('App\Models\MedicalStoreDetails','coupon_id');
    }

    public function admin()
    {
        return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
    }
}
