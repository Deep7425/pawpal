<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientSubLabs extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_sub_labs';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'parent_id','lab_id','sub_lab_id','lab_name','instructions','lr_unit','lr_range','result_type','result','comment','file_name','status','delete_status','added_by','order_id'
    ];
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */
	public function PatientLabs()
  {
    return $this->belongsTo('App\Models\ehr\PatientLabs','parent_id');
  }
  public function SubLabs()
  {
    return $this->belongsTo('App\Models\ehr\SubLabs','sub_lab_id');
  }
}
?>
