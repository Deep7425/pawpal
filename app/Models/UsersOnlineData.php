<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UsersOnlineData extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users_online_data';
	protected $fillable = ['data_type','name','gender','dob','title','type','email','url','mobile','com_name','other','bp_s','bp_d','sugar','vitals','chief_complaints','advice','created_at','updated_at'];
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
}
