<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class BannerMaster extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','image','status','type','link_url','package_id','banner_type'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'banner_master';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
