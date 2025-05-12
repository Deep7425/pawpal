<?php

namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class LabPincode extends Authenticatable{
    use Notifiable;
    protected $table = 'labs_pincode';
	protected $fillable = ['company_id','pincode' , 'login_id' , 'name'];
    public $timestamps = false;
	
	public function LabCompany() {
	   return $this->belongsTo('App\Models\LabCompany', 'company_id');
    }
public function admin(){
    return $this->belongsTo(Admin\Admin::class , 'login_id' , 'id');
}

}