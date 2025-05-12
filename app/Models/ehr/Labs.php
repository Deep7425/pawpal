<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class Labs extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'labs';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','short_name','data_type','num_high_value','num_low_value','unit','cost','results','component','status','added_by','delete_status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function SubLabs()
	{
		return $this->hasMany('App\Models\ehr\SubLabs','lab_id')->where('added_by','!=',0);
	}
}
?>
