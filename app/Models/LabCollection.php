<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\DefaultLabs;
class LabCollection extends Authenticatable{
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'lab_collection';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $appends = ['sub_labs','ptype']; 
    protected $fillable = ['company_id', 'login_id' , 'name' ,'lab_id' ,'sub_lab_id','method','instruction','information','cost','offer_rate','reporting','status','delete_status'];
    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	function getSubLabsAttribute($query) {
      $subLabId = explode(",",$this->sub_lab_id);
	  return DefaultLabs::whereIn('id',$subLabId)->get();
    }
	function getPtypeAttribute($query) {
	  return 'CUSTOM';
    }	
	public function LabCompany() {
        return $this->belongsTo('App\Models\LabCompany', 'company_id');
    }
	public function DefaultLabs() {
        return $this->belongsTo('App\Models\DefaultLabs', 'lab_id');
    }

    public function admin(){
        return $this->belongsTo(Admin\Admin::class, 'login_id' , 'id');
    }

}
?>
