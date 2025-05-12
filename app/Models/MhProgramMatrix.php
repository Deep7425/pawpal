<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhProgramMatrix extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_program_matrix';
 	protected $fillable = ['user_id','week_type','program_id','symp_id','program_status'];
    public $timestamps = true;

    public function MhWeeklyTask() {
        return $this->hasMany('App\Models\MhWeeklyTask','mhp_mid');
    }

}
