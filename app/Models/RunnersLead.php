<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class RunnersLead extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'runners_lead';
	protected $fillable = ['name','mobile_no','address','app_download','appointment','plan_sold','created_by','status','created_at','updated_at'];
    public $timestamps = true;
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
	public function SalesTeam()
	   {
		  return $this->belongsTo('App\Models\SalesTeam','created_by');
	   }
}
