<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class  LabRequests extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'lab_requests';
	 protected $fillable = ['user_id','pres_id','mobile_no','delete_status'];
     public $timestamps = true;
	 
	 public function user(){
        return $this->belongsTo('App\Models\User', 'user_id');
     }
	 public function MedicinePrescriptions(){
        return $this->belongsTo('App\Models\MedicinePrescriptions', 'pres_id');
     }
}
