<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class NotificationUserId extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'notification_user_ids';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['id' , 'notification_id' , 'user_id' ];
    /**
	 * A profile belongs to a user
     * 
	 * @return mixed
	 */

}
?>