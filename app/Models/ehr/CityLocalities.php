<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class CityLocalities extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr';
    protected $table = 'city_localities';
	protected $fillable = ['slug','name','city_id','state_id','country_id','status','top_status'];
    
	public $timestamps = false;
	
	public function Country(){
  		  return $this->belongsTo('App\Models\Country', 'country_id');
  	}
  	public function State(){
  		  return $this->belongsTo('App\Models\State', 'state_id');
  	}
  	public function City(){
  		  return $this->belongsTo('App\Models\City', 'city_id');
  	}
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
  /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
}
