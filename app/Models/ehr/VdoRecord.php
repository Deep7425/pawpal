<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Auth;
class VdoRecord extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'vdo_records';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['is_from','p_id','doc_id','uid','sid','cname','resourceId','appId','end_call','status','meta_data','updated_m_data','stop_m_data'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
}
?>
