<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientDietitianTemplate extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
     protected $connection = 'mysql_ehr';
    protected $table = 'patient_dietitian_template';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['patient_id','appointment_id','dietitian_temp_id','instructions','status','delete_status','added_by','order_id'];
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */

  public function DietitianReportTemplate()
  {
      return $this->belongsTo('App\Models\ehr\DietitianReportTemplate', 'dietitian_temp_id');
  }
}
?>
