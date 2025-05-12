<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PreAssesmentAnswer extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_pre_assesment_answers';
 	protected $fillable = ['user_id','meta_data','depression_score','anxiety_score','stress_score','total_score','mental_status'];
    public $timestamps = true;


}
