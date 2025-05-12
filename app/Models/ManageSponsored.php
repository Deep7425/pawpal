<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ManageSponsored extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $fillable = ['user_id','package_id','state_ids','city_ids','start_date','end_date','status'];

    protected $table = 'manage_sponsored';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
    public function Doctors()
    {
      return $this->belongsTo('App\Models\Doctors','user_id','practice_id');
    }
}
