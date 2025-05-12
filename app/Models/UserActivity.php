<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UserActivity extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'user_activity';
 	protected $fillable = ['action','table_name','table_id','method','url','ip','agent','device','os','browser','mac','user_id','location'];
     public $timestamps = true;

	// public function Commet(){
  // 		  return $this->belongsTo('App\Models\State', 'state_id');
  // 	}
	// public function CityLocalities(){
  // 	  return $this->hasMany('App\Models\CityLocalities');
  // 	}
}
