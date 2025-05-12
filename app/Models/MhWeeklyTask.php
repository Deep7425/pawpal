<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhWeeklyTask extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_weekly_task';
 	protected $fillable = ['user_id','program_id','mhp_mid','task_value','sheet_title'];
    public $timestamps = true;

    function getTaskValueAttribute($value) {
        return json_decode($value);
    }

}
