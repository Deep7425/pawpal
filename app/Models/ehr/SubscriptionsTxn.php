<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class SubscriptionsTxn extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $fillable = ['subscription_id','tracking_id','bank_ref_no','tran_id','tran_mode','card_name','currency','payed_amount','tran_status','trans_date'];
    protected $table = 'subscriptions_txn';
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


    public function PracticesSubscriptions()
    {
        return $this->belongsTo('App\Models\ehr\PracticesSubscriptions','subscription_id');
    }
}
