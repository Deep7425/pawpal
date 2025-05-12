<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ThyrocarePackageGroup extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $appends = ['labs'];
    protected $fillable = ['group_name','image','status','delete_status' , 'lab_ids'];

    protected $table = 'thyrocare_package_group';
    public $timestamps = true;
	function getLabsAttribute($query) {
      $labIds = explode(",",$this->lab_ids);
	  return LabCollection::with('DefaultLabs','LabCompany')->whereIn('id',$labIds)->get();
    }
    /**
     * The attributes that are mass assignable.
     *
     * @var array
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

}
