<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class JobCategory extends Authenticatable{

    use Notifiable;
 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $connection = 'mysql_admin';
     protected $fillable = ['title','job_code','status','delete_status' , 'description'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'job_categories';
    public function userDetails()
    {
        return $this->hasOne('App\Models\pp\userDetails','user_id');
    }



}
