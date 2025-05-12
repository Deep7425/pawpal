<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UniversityList extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'university_list';
    	protected $fillable = ['name','status','delete_status','created_at','updated_at'];
    public $timestamps = true;


}
