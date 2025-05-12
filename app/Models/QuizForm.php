<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class QuizForm extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'quiz_forms';
 	protected $fillable = ['org_id','name','gender','mobile','age','dob','meta_data','institute_id','class','subject','negative_effect','detechment','antagonism','disinhibition','psychoticism','data_a','data_b','data_c','data_d','total_score','status'];
    public $timestamps = true;


    public function SessionAssesment(){
        return $this->hasMany('App\Models\SessionAssesment', 'quiz_id');
    }
	
	public function SessionAssigendData(){
        return $this->hasone('App\Models\SessionAssigendData', 'quiz_id');
    }

    public function OrganizationMaster(){
        return $this->belongsTo('App\Models\OrganizationMaster', 'org_id');
    }


}
