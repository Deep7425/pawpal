<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientImmunizations extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_immunizations';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id','patient_id','immunization_type','dose_qty','dose_unit','dose_status','dose_no','route','other_route','body_location','comment','given_by','given_date','status','delete_status','added_by'
    ];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	
	 public function Immunizations()
        {
          return $this->belongsTo('App\Models\ehr\Immunizations', 'immunization_type');
        }
 }       
  ?>
