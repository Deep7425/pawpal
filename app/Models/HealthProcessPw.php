<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class HealthProcessPw extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'health_process_pw';
 	protected $fillable = ['type','student_name','age','gender','reg_no','past_medical_his','chronic_illness','regular_medicine','medical_platform','other'];
    public $timestamps = true;


    public function OrganizationMaster()
    {
        return $this->belongsTo(OrganizationMaster::class, 'type', 'id');
    }
    


}
