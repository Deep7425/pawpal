<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class SubSpecialities extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'sub_specialities';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['speciality_id','name','status','delete_status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
  
   
}
?>
