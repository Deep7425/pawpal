<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientFeedback extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'patient_feedback';
	protected $fillable = ['user_id','appointment_id','doc_id','doc_type','rating','recommendation','visit_type','waiting_time','suggestions','experience','publish_status','publish_admin','status','delete_status','created_at','updated_at','random_no','resource'];
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
  /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
	public function Doctors(){
        return $this->belongsTo('App\Models\Doctors','doc_id','id');
    }
	public function User(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function DoctorsInfo(){
        return $this->belongsTo('App\Models\ehr\DoctorsInfo','doc_id','id');
    }

    public function HandleQueries()
    {
        return $this->hasOne(HandleQueries::class, 'table_id')->latestOfMany(); // latest by id
    }
    
}
