<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Support extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'support';
	protected $fillable = ['tocken','user_id','name','mobile','email','subject','message','note','is_view','type'];
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
	 
	  public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function HandleQueries()
    {
        return $this->hasOne(HandleQueries::class, 'table_id')->latestOfMany(); // latest by id
    }
}
