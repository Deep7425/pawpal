<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class LabCompany extends Authenticatable{
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
	protected $appends = ['icon_url'];  
    protected $table = 'lab_companies';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','login_id' , 'name' ,'desc','discount','icon','start_time','end_time','slot_duration'];
	function getIconUrlAttribute($query) {
		return url("/").'/public/others/company_logos/'.$this->icon;
    }
    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

     public function admin()
     {
        return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
     }

}
?>
