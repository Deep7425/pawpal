<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class HandleQueries extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'handle_queries';
	protected $fillable = ['r_from','table_id','type','note','followUpDate','delete_status','createBy','created_at','updated_at'];
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
}
