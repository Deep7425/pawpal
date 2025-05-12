<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientDentalDetails extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'patient_dental_details';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'student_id'
    ];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function students()
	{
		return $this->belongsTo('App\Models\ehr\Students');
	}
}
?>
