<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class VhtOrder extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','name','gender','birthday','mobile_no','org_id','third_key','ext_order_id','timestamp','signature','order_status','meta_data','vh_meta_data','order_from'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'vht_orders';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
     
}
