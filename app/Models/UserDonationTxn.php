<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UserDonationTxn extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = ['donation_id','tracking_id','bank_ref_no','card_name','tran_mode','card_name','currency','payed_amount','tran_status','trans_date','cheque_payee_name','cheque_bank_name','cheque_no','cheque_date'];
    protected $table = 'user_donation_txn';
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


    public function UsersDonation()
    {
        return $this->belongsTo('App\Models\UsersDonation','donation_id');
    }
}
