<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DoctorSlug extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'doctor_slug';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['doc_id','practice_id','name_slug','clinic_name_slug','city_id'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */


}
?>
