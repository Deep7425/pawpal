<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class HealthQuestion extends Authenticatable{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'health_questions';
	protected $fillable = ['title','order_id','level_type','type','answer','answer_type','lang','meta_data','status','delete_status'];
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
