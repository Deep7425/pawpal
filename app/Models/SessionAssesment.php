<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class SessionAssesment extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'session_assessment';
	protected $fillable = ['app_id','user_id','quiz_id','counselor_id','session_status'];
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
     public function QuizForm() {
        return $this->belongsTo('App\Models\QuizForm', 'quiz_id');
    }
}
