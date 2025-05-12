<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class AssementStuFeedback extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'assement_stu_feedback';
	protected $fillable = ['counselor_feedback','content','quality','hg_rating','user_id','quiz_id','session_id','rating'];
    public $timestamps = true;
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

     public function QuizForm(){
        return $this->belongsTo('App\Models\QuizForm', 'quiz_id');
    }
}
