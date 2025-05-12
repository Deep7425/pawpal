<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class SubLabs extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'sub_labs';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['lab_id','title','short_name','data_type','num_high_value','num_low_value','unit','cost','results','status','added_by','delete_status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function Labs()
	{
		return $this->belongsTo('App\Models\ehr\Labs','lab_id');
	}
}
?>
