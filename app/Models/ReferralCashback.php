<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ReferralCashback extends Authenticatable{
   use  Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
         'order_id','referral_id','referred_id','meta_data','status','paytm_status'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'referral_cashback';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    /**
     * User Details Relationships
     *
     * @var array
     */
	public function ReferralUser() {
        return $this->belongsTo('App\Models\User','referral_id');
    }
	public function ReferredUser() {
        return $this->belongsTo('App\Models\User','referred_id');
    }
}
