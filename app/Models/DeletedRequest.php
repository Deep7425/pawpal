<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DeletedRequest extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $fillable = ['reason','user_id','status','delete_status'];
    protected $table = 'deleted_request';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
}