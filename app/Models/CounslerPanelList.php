<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class CounslerPanelList extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'counsler_panel_list';
	protected $fillable = ['quiz_id','counselor_id','assigned_date','session_status','note','created_at','updated_at'];
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
