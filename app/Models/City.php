<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class City extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'cities';
    public $timestamps = false;
	
	public function State(){
  		  return $this->belongsTo('App\Models\State', 'state_id');
  	}
	public function CityLocalities(){
  	  return $this->hasMany('App\Models\CityLocalities');
  	}
}
