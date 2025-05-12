<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class PpSliders extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','image','description','status','delete_status','created_at','updated_at'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'pp_sliders';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
