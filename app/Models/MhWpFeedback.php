<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhWpFeedback extends Authenticatable {
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_wp_feedback';
 	protected $fillable = ['user_id','week_id','program_id','ques_1','ques_2','ques_3','ques_4','comment'];
    public $timestamps = true;
     public function MhWeeklyProgram()
    {
        return $this->belongsTo(MhWeeklyProgram::class, 'week_id', 'id'); // 'week_id' references the primary key 'id' in MhWeeklyProgram
    }
}
