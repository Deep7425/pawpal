<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class CampData extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'camp_data';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','thy_lead_id','thy_ref_order_no','camp_id'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function user() {
		return $this->belongsTo('App\Models\User','user_id');
	}
	public function CampTitleMaster() {
		return $this->belongsTo('App\Models\CampTitleMaster','camp_id');
	}
}
