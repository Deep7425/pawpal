<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class OtpPracticeDetails extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['otp','user_id','mobile_no'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'otp_practice_details';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
