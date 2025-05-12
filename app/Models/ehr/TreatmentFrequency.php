<?php
namespace App\Models\ehr;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class TreatmentFrequency extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
     protected $connection = 'mysql_ehr';
    protected $table = 'treatment_frequency_master';
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['title','conversion_value','added_by','delete_status'];
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */
}
?>
