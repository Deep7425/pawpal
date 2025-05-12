<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class OutSideAppointments extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'outside_appointments';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'doc_id','pId','doc_room_no','insurance_status','insurance_company_id','other_insurence_company','billable_status','visit_type','blood_group','consultation_fees','payed_status','start','end','added_by','status','delete_status','cancel_reason','other_cancel_reason','referred_by','other_referred_by','check_in','check_out','tot_spend_time','visit_status'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
  public function user()
  {
    return $this->belongsTo('App\Models\User', 'doc_id');
  }
  
}
?>
