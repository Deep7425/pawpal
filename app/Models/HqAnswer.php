<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class HqAnswer extends Authenticatable{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'hq_answer';
	protected $fillable = ['hq_id','answer','type','delete_status'];
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
