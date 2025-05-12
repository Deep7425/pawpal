<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class CompanyName extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'company_name';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
     protected $fillable = ['company_name','added_by','status','delete_status'];
  /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
}
