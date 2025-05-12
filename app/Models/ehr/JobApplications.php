<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class JobApplications extends Authenticatable {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $connection = 'mysql_admin';
     protected $fillable = ['job_id','cat_id','first_name', 'state' , 'last_name','experience','qualification','phone','email','country_id','city','address','position','other_position','message','file_data','urls','status','delete_status'];

     protected $table = 'job_applications';
}
