<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class PatientRagistrationNumbers extends Authenticatable
{
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $connection = 'mysql_ehr';  
    protected $fillable = [
        'pid','reg_no','file_no','status','added_by'
    ];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patient_ragistration_numbers';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */


    /**
     * User Details Relationships
     *
     * @var array
     */
     public function patient()
    {
      return $this->belongsTo('App\Models\Patients', 'pid');
    }
}
