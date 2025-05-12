<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class SessionAssigendData extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'session_assigend_data';
	protected $fillable = ['quiz_id','group_session_assigned','group_session_taken','ind_session_assigned','ind_session_taken','parent_session_assigned','parent_session_taken','screening_date','next_screening_date','parent_screening_date'];
    public $timestamps = false;
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
