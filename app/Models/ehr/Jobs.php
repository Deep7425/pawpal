<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Jobs extends Authenticatable {
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $connection = 'mysql_admin';
     protected $fillable = ['cat_id','title','experience','description','status','delete_status'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'jobs';
	public function JobCategory() {
        return $this->belongsTo('App\Models\JobCategory','cat_id');
    }
}
