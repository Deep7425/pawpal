<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class AssessmentOverview extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    
    protected $table = 'mh_assessment_overview';
    public $timestamps = true;
    protected $fillable = ['weekly_program_id','week_type','symptom_id','title','title_hindi','s_type','program','program_hindi','audio_file','status'];
	
    public function mhWeeklyProgram()
    {
        return $this->belongsTo('App\Models\MhWeeklyProgram', 'weekly_program_id');
    }
    public function MhProgramMatrix() {
        return $this->hasMany('App\Models\MhProgramMatrix', 'program_id');
      }
    public function Symptoms() {
        return $this->belongsTo('App\Models\Admin\Symptoms','symptom_id');
    }
}
