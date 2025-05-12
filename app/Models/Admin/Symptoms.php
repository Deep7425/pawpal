<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Symptoms extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    
    protected $table = 'symptoms';
    public $timestamps = true;
	protected $fillable = ['symptom','symptom_hindi','description' , 'description_web','description_hindi','disease','treatment','treatment_hindi','cause','cause_hindi','status','delete_status', 'treatment_web', 'cause_web', 'symp_details_web'];
	
	public function SymptomsSpeciality() {
		return $this->hasMany('App\Models\Admin\SymptomsSpeciality', 'symptoms_id');
	}
	public function SymptomTags() {
		return $this->hasMany('App\Models\Admin\SymptomTags', 'symptoms_id' );
	}
}
