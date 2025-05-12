<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhQuesRange extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_ques_range';
 	protected $fillable = ['symp_id', 'category', 'category_hindi' ,  'type' , 'type_hindi', 'min_score','max_score','suggestion', 'suggestion_hindi','category_id','added_by'];
    public $timestamps = true;

    public function Symptoms()
    {
        return $this->belongsTo(Admin\Symptoms::class, 'symp_id');
    }

    public function MhResultType()
    {
        return $this->belongsTo('App\Models\MhResultType','category_id');
    }


}
