<?php
namespace App\Models\ehr;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DentalMaster extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $fillable = ['image','name','status','delete_status'];
	protected $connection = 'mysql_ehr';
    protected $table = 'dental_master';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

   public function PatientDentals()
   {
       return $this->hasMany('App\Models\ehr\PatientDentals');
   }
}
