<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class UserWallet extends Authenticatable {
	use SoftDeletes;
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_wallet';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $appends = ['title'];  
    protected $fillable = ['user_id','entity_type','type','amount','status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function user() {
		return $this->belongsTo('App\Models\User');
	}
	function getTitleAttribute($query) {
		$type_ = "Reward";
		if($this->type == 2){
			$type_ = "Appointment";
		}
		else if($this->type == 3){
			$type_ = "Lab";
		}
		else if($this->type == 4){
			$type_ = "Subscription";
		}
		else if($this->type == 5){
			$type_ = "Lab Claim";
		}
		else if($this->type == 6){
			$type_ = "Appointment Claim";
		}
		else if($this->type == 7){
			$type_ = "Subscription Claim";
		}
		if($this->status == 0){
			$type_ = "Expired";
		}
		return $type_;	
    }
}
