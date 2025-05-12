<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class CovidHospital extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'covid_hospital';
	protected $fillable = ['name','total_general_beds','o_gen_beds','a_gen_beds','total_oxygen_beds','o_oxy_beds','a_oxy_beds','total_icu_beds_w_v','o_icu_beds_w_v','a_icu_beds_w_v','total_icu_beds_v','o_icu_beds_v','a_icu_beds_v','nodal_officer','asst_nodal_officer','help_line','mobile_no','url','locality','city','state','status','created_at','updated_at'];
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
	public function CovidHospitalDoctors(){
        return $this->hasMany('App\Models\CovidHospitalDoctors','hos_id');
    }
}
