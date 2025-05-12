<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UsersDonation extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $fillable = ['user_id','order_id','payment_mode','coupon_id','coupon_discount','tax','order_subtotal','order_total','order_status','ref_code','meta_data','added_by'];

    protected $table = 'user_donation';
    //public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id');
    }
    public function UserDonationTxn()
    {
        return $this->hasOne('App\Models\UserDonationTxn','donation_id');
    }
}
