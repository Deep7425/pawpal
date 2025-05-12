<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class LabOrderTxn extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = ['order_id','tracking_id','bank_ref_no','card_name','currency','payed_amount','tran_mode','tran_status','trans_date','cheque_date','cheque_no','cheque_bank_name','cheque_payee_name'];
    protected $table = 'lab_order_txn';
    public $timestamps = false;
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
