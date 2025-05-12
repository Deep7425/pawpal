<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MedicineOrders extends Authenticatable {
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $fillable = ['appId','presIds','pres_type','type','user_id','order_id','address_id','payment_mode','coupon_id','coupon_discount','tax','order_subtotal','order_total','status','order_status','delivery_date','delivery_charge','cancel_reason','delete_status','seller_detail','meta_data','order_by','order_from'];

    protected $table = 'medicine_orders';
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
    public function MedicineOrderedItems()
    {
        return $this->hasMany('App\Models\MedicineOrderedItems','order_id');
    }
    public function MedicineTxn()
    {
        return $this->hasOne('App\Models\MedicineTxn','order_id');
    }
	public function UsersLaborderAddresses()
    {
        return $this->belongsTo('App\Models\UsersLaborderAddresses','address_id');
    }
}