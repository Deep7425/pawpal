<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UsersOTP extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'users_otp';
 	protected $fillable = ['user_id','mobile_no','otp','email','type','device_type','is_login','fcm_token','expiry_date','created_at','updated_at', 'password'];
     public $timestamps = true;
}
