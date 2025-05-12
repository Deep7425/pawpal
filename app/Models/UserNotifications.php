<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UserNotifications extends Authenticatable {
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_notifications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['type','title','message','icon','route','created_by','meta_data'];


	
     public function NotificationUserId() {
		return $this->hasMany(NotificationUserId::class, 'notification_id');
	 }
     // public function users(){
         // return $this->hasMany(User::class, 'id','user_id');
     // }

}
































































