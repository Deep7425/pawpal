<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Corporate extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'corporate';
	protected $fillable = ['name','mobile','email','org_name','org_size','status','qry_from','created_at','updated_at'];
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
  /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

     public function HandleQueries()
     {
         return $this->hasOne(HandleQueries::class, 'table_id')->latestOfMany(); // latest by id
     }
     
}