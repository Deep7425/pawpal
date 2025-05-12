<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class AppointmentTxn extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $fillable = ['appointment_id','order_id','type','tracking_id','bank_ref_no','card_name','currency','payed_amount','tran_mode','tran_status','trans_date','received_by'];
    protected $table = 'appointment_txn';
    public $timestamps = false;
	public function AppointmentOrder(){
		return $this->belongsTo('App\Models\ehr\AppointmentOrder', 'order_id');
	}
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
}
