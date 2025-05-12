<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhTracker extends Authenticatable{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_tracker';
 	protected $fillable = ['user_id','s_date','sleep_cycle','exercise','energy_level'];
    public $timestamps = true;
}
