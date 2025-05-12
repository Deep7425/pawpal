<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ReminderUserNotificatios extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = ['user_id','module_slug','notification','login_id' , 'name' ,'view_status','delete_status'];
    protected $table = 'reminder_user_notificatios';
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

     public function user(){
        return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
     }
   
}
