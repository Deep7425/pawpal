<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Speciality extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_ehr';
    protected $table = 'doctor_specialities';
    public $timestamps = false;
    
	protected $fillable = ['slug','specialities','spaciality','keywords','speciality_icon','group_id','tags','status','delete_status','spec_desc'];
	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	/**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function SpecialitySymptoms()
    {
        return $this->hasMany('App\Models\Admin\SpecialitySymptoms','spaciality_id');
    } 
	public function DoctorsInfo()
    {
        return $this->belongsTo('App\Models\ehr\DoctorsInfo','id','speciality');
    }
	public function Doctors()
    {
        return $this->belongsTo('App\Models\Doctors','id','speciality');
    }
    public function NonHgDoctors()
    {
        return $this->belongsTo('App\Models\NonHgDoctors','id','speciality');
    }
}
