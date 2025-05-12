<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Enquiry extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'enquiry_forms';
	protected $fillable = ['name','mobile','email','status','created_at','updated_at'];
    public $timestamps = true;
	
	public function User() {
        return $this->belongsTo('App\Models\User','mobile','mobile_no');
    }

    public function HandleQueries()
    {
        return $this->hasOne('App\Models\HandleQueries', 'table_id')->latestOfMany(); // latest by id
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
