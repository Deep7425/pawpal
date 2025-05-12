<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Appointments extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';
    protected $table = 'appointments';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'doc_id','ipd_request_id','pId','type','doc_room_no','insurance_status','insurance_company_id','other_insurence_company','billable_status','visit_type','blood_group','consultation_fees','payed_status','start','end','added_by','status','delete_status','cancel_reason','other_cancel_reason','referred_by','other_referred_by','check_in','check_out','tot_spend_time','visit_status','appointment_confirmation','app_click_status','current_status','call_type','is_document_uploaded'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
  public function user()
  {
    return $this->belongsTo('App\Models\ehr\User', 'doc_id');
  }
  public function patient()
  {
    return $this->belongsTo('App\Models\ehr\Patients', 'pId');
  }
  public function visitType()
  {
    return $this->belongsTo('App\Models\ehr\VisitTypes', 'visit_type');
  }
  public function chiefComplaints()
  {
    return $this->hasMany('App\Models\ehr\ChiefComplaints', 'appointment_id');
  }
  public function practiceDetails()
  {
    return $this->belongsTo('App\Models\ehr\PracticeDetails', 'added_by','user_id');
  }
  public function getPatient()
  {
    return $this->belongsTo('App\Models\ehr\Patients', 'pId')->select(array('id','first_name','last_name','email','mobile_no','image','gender'));
  }
  public function followUp()
  {
    return $this->hasOne('App\Models\ehr\FollowUp', 'appointment_id');
  }
  public function PatientProcedures()
  {
    return $this->hasMany('App\Models\ehr\PatientProcedures', 'appointment_id');
  }
  public function PatientDiagnosis()
  {
    return $this->hasMany('App\Models\ehr\PatientDiagnosis', 'appointment_id');
  }
   public function AppointmentTxn()
  {
    return $this->hasOne('App\Models\ehr\AppointmentTxn', 'appointment_id');
  }  
  public function AppointmentOrder()
  {
    return $this->hasOne('App\Models\ehr\AppointmentOrder', 'appointment_id');
  }
   public function NotifyUserSms()
  {
    return $this->hasOne('App\Models\ehr\NotifyUserSms', 'apptId')->orderBy("id","DESC");
  }
  public function Doctors()
  {
    return $this->belongsTo('App\Models\Doctors', 'doc_id','user_id');
  }
  public function UserPP()
  {
    return $this->belongsTo('App\Models\User','pId','id');
  }
  public function PatientLabs()
  {
    return $this->hasMany('App\Models\ehr\PatientLabs', 'appointment_id')->where('delete_status',1);
  }

  public function PatientLabsOne()
  {
    return $this->hasOne('App\Models\ehr\PatientLabs', 'appointment_id')->where('delete_status',1);
  }

    public function PatientDiagnosticImagings()
  {
    return $this->hasOne('App\Models\ehr\PatientDiagnosticImagings','appointment_id');
  }
  public function PatientFeedback()
  {
    return $this->belongsTo('App\Models\PatientFeedback','appointment_id');
  }
  public function PatientAdvice()
  {
    return $this->hasMany('App\Models\ehr\PatientAdvice', 'appointment_id');
  }
    public function PsycologicalEvaluationReport()
  {
    return $this->hasOne('App\Models\ehr\PsycologicalEvaluationReport', 'appointment_id');
  }
  public function PatientLabNew()
  {
    return $this->hasMany('App\Models\ehr\PatientLabs', 'appointment_id');
  }
  public function PatientDiagnosticNew()
  {
    return $this->hasMany('App\Models\ehr\PatientDiagnosticImagings','appointment_id');
  }    
  public function labCalling()
  {
    return $this->hasOne('App\Models\labCalling', 'appointment_id');
  }
}
?>
