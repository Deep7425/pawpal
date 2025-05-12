<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UserLead extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'user_lead';
    	protected $fillable = ['name','mobile_no','intrested','ip','created_at','updated_at'];
    public $timestamps = true;


}
