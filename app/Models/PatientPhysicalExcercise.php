<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientPhysicalExcercise extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
     protected $connection = 'mysql_ehr';
    protected $table = 'patient_physical_excercise';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = [
        'patient_id','appointment_id','physical_excercise_id','instructions','status','delete_status','added_by','order_id'
    ];
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */

  public function PhysicalExcerciseMaster()
  {
      return $this->belongsTo('App\Models\ehr\PhysicalExcerciseMaster', 'physical_excercise_id');
  }
}
?>
