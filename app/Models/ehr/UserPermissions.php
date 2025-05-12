<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UserPermissions extends Authenticatable {
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'users_permissions';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','practice_id','modules_access','settings_access'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
}
?>
