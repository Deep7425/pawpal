<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ManageUsersNotifications extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = ['notification','practice_id','view_status'];
    protected $table = 'manage_user_notifications';
    //public $timestamps = false;
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
