<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class UserReferral extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username','mobile','referralCode','status','delete_status'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_refferal';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
