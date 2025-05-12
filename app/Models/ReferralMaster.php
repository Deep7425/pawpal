<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ReferralMaster extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'referral_master';
    protected $fillable = [ 'login_id' ,'org_id', 'name' , 'title','code','referral_discount_type','referral_discount','referral_duration_type','referral_duration','code_last_date','other_text','term_conditions','max_uses','plan_ids','is_show','added_by','status','delete_status','created_at','updated_at'];
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
    public function admin()
    {
        return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
    }
}
