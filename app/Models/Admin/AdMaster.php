<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class AdMaster extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','image','status','type','expiry_date','area','link_url'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'ad_master';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
