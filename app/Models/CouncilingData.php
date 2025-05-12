<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class CouncilingData extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['council_name','status','delete_status','created_at','updated_at'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'counciling_data';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
