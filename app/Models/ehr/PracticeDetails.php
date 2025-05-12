<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PracticeDetails extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'practice_details';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','first_name','last_name','clinic_name','practice_type','specialization','gstin_no','address_1','address_2','country_id','state_id','city_id','locality_id','zipcode','mobile','email','website','logo','slot_duration','my_visits','print_layout'
    ];

    /**
	 * A detail belongs to a user
	 *
	 * @return mixed
	 */
	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}
  public function PracticesSubscriptions()
  {
      return $this->hasMany('App\Models\PracticesSubscriptions','user_id','user_id');//for complete payment use order_status=1
  }
    public function PrintSettings()
  {
      return $this->hasOne('App\Models\ehr\PrintSettings','user_id','user_id');
  }
  	public function state(){
		return $this->belongsTo('App\Models\ehr\State', 'state_id');
	}
	public function city(){
		return $this->belongsTo('App\Models\ehr\City', 'city_id');
	}
}
