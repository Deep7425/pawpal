<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhResultType extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
   protected $table = 'mh_result_type';
 	protected $fillable = ['s_type','title','symp_id'];
   public $timestamps = false;

   public function Symptoms() {
		return $this->belongsTo('App\Models\Admin\Symptoms', 'symp_id');
	}

   public function MhWeeklyProgram() {
      return $this->hasMany('App\Models\MhWeeklyProgram','s_type');
  }

}
