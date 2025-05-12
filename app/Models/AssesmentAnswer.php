<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class AssesmentAnswer extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_assesment_answers';
 	protected $fillable = ['user_id','symp_id','suggestion','meta_data','feedback','feed_msg','total_score','score_data','mental_status'];
    public $timestamps = true;

    public function MhQuesRange() {
        return $this->belongsTo('App\Models\MhQuesRange','suggestion');
    }

    public function AssessmentOverview() {
        return $this->hasMany('App\Models\AssessmentOverview','symptom_id','symp_id');
    }

    public function MhWeeklyProgram() {
        return $this->hasMany('App\Models\MhWeeklyProgram','symp_id','symp_id');
    }
    public function MhWeeklyPrograms()  {
        return $this->hasOne('App\Models\MhWeeklyProgram','symp_id','symp_id');
    }
    
    public function Symptoms() {
        return $this->belongsTo('App\Models\Admin\Symptoms','symp_id');
    }

    public function userDetails() {
        return $this->belongsTo('App\Models\User','user_id');
    }
   
    public function Appointments()
    {
        return $this->belongsTo(ehr\Appointments::class, 'user_id','pId');
    }
    public function MhMood()
    {
      return $this->belongsTo(MhMood::class,  'user_id');
    }
    public function PreAssesmentAnswer()
	{
    return $this->hasMany(PreAssesmentAnswer::class, 'user_id', 'user_id');
	}
    public function AppointmentsAll()
    {
        return $this->hasMany(ehr\Appointments::class, 'pId', 'user_id');
    }
}
