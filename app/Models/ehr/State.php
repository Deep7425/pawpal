<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class State extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $connection = 'mysql_ehr';
    protected $table = 'states';
    public $timestamps = false;
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
