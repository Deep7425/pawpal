<?php
namespace App\Models\Admin;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class SymptomsSpeciality extends Authenticatable {
	
	use Notifiable;
    
    protected $fillable = ['speciality_id','symptoms_id'];

    protected $table = 'symptoms_speciality';
    public $timestamps = false;

    public function Symptom()
    {
        return $this->belongsTo('App\Models\Admin\Symptoms','symptoms_id');
    }
	public function Speciality() {
		return $this->belongsTo('App\Models\Speciality', 'speciality_id' );
	}
	public function DoctorsInfo()
    {
        return $this->belongsTo('App\Models\ehr\DoctorsInfo','speciality_id','speciality');
    }
	public function practiceDetails(){
        return $this->belongsTo('App\Models\ehr\PracticeDetails','speciality_id','specialization');
    }
	public function Doctors() {
        return $this->hasOne('App\Models\Doctors','speciality','speciality_id');
    }
}