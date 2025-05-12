<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use App\Models\LabCollection;
class LabPackage extends Authenticatable{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $appends = ['labs','ptype']; // additional values returned in JSON
    protected $table = 'lab_package';
 	protected $fillable = ['login_id','name' , 'company_id','lab_id','title','price','discount_price','image','status','delete_status', 'description'];
    public $timestamps = true;
	function getPtypeAttribute($query) {
	  return 'PACKAGE';
    }	
	function getLabsAttribute($query) {
      $labIds = explode(",",$this->lab_id);
	  return LabCollection::with('DefaultLabs')->whereIn('id',$labIds)->get();
    }
	public function LabCompany() {
        return $this->belongsTo('App\Models\LabCompany', 'company_id');
    }
	public function DefaultLabs() {
        return $this->belongsTo('App\Models\DefaultLabs', 'lab_id');
    }
    public function admin(){
    return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
    }

}
