<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class OpdTimings extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $fillable = ['user_id','practice_id','schedule'];
	protected $connection = 'mysql_ehr'; 
    protected $table = 'opd_timings';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

    public function User()
    {
        return $this->belongsTo('App\Models\ehr\User', 'user_id');
    }
	
}
