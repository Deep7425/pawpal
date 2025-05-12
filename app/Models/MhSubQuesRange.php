<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhSubQuesRange extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_subques_range';
 	protected $fillable = ['symp_id','score_type','type','category','min_score','max_score','suggestion','added_by'];
    public $timestamps = false;


}
