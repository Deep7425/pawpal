<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhWeeklyProgram extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_weekly_program';
 	protected $fillable = ['title','icon','week_type','s_type','description','title_hindi','description_hindi','symp_id'];
    public $timestamps = true;

    public function AssessmentOverview(){
      return $this->hasMany(AssessmentOverview::class, 'weekly_program_id', 'id');
    }
  
    public function SymptomsName() {
      return $this->belongsTo('App\Models\Admin\Symptoms', 'symp_id');
    }
    public function MhResultType() {
      return $this->belongsTo('App\Models\MhResultType', 's_type');
    }
   
    public function MhQuesRange() {
      return $this->belongsTo('App\Models\MhQuesRange', 'category_id');
    }
  
    public function MhWpFeedback() {
      return $this->hasMany('App\Models\MhWpFeedback', 'week_id');
    }
  
    public function mhProgramMatrix(){
        return $this->hasOne(MhProgramMatrix::class, 'program_id', 'id');
    }
}
