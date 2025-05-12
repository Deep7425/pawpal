<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MedicalStoreDetails extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'medical_store_details';
 	protected $fillable = ['name','address','owner_name','mobile','document','acc_no','acc_name','ifsc_no','bank_name','paytm_no','coupon_id','generate_by','country_id','state_id','city_id'];
     public $timestamps = true;
}
