<?php

namespace App\Models\ehr;

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
	
	public function CityLocalities(){
  	  return $this->hasMany('App\Models\CityLocalities');
  	}
}
