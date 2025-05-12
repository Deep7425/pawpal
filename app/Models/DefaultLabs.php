<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DefaultLabs extends Authenticatable{
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'default_labs';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title', 'login_id' ,'name' ,'short_name','data_type','num_high_value','num_low_value','unit','multiple_range','results','status','delete_status', 'description'];
    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	 function getTitleAttribute($value) {
      return strtoupper($value);
    }
	public function LabCollection() {
        return $this->hasOne('App\Models\LabCollection', 'lab_id');
    }
    public function admin(){
        return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
    }
}
?>
