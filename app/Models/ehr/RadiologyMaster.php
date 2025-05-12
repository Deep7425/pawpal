<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class RadiologyMaster extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	 protected $connection = 'mysql_ehr';
    protected $table = 'radiology_master';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','type','cost','status','added_by','delete_status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
}
?>
