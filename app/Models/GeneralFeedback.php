<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class GeneralFeedback extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'general_feedbacks';
 	protected $fillable = ['name','mobile','location','sub_location','suggestions','remark'];
    public $timestamps = true;
}
