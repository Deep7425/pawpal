<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class  MhMood extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_moods';
 	protected $fillable = ['user_id','ques_1','ques_2','message','mood', 'ip'];
    public $timestamps = true;

}
