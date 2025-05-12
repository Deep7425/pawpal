<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class PatientMedications extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
     protected $connection = 'mysql_ehr';
    protected $table = 'patient_medications';
    //public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['patient_id','appointment_id','drug_id','eye','strength','unit','quantity','frequency','route','duration','notes','delete_status','added_by','order_id','frequency_type','duration_type','medi_instruc'];
    /**
    * A profile belongs to a user
    * @return mixed
   */
    public function patient()
    {
        return $this->belongsTo('App\Models\ehr\Patients','patient_id');
    }
    public function itemDetails()
    {
		return $this->belongsTo('App\Models\ehr\ItemDetails','drug_id');
    }
    public function Appointment()
    {
		return $this->belongsTo('App\Models\ehr\Appointments','appointment_id');
    }
    public function MedicineBill()
    {
        return $this->hasOne('App\Models\ehr\MedicineBill','order_id');
    }
    public function MedicineOrders()
    {
        return $this->belongsTo('App\Models\ehr\MedicineOrders','order_id');
    }
    public function ItemRoute()
    {
       return $this->belongsTo('App\Models\ehr\ItemRoute','route');
    }
    public function TreatmentFrequency()
    {
        return $this->belongsTo('App\Models\ehr\TreatmentFrequency','frequency');
    }
    public function TreatmentInstruction()
    {
        return $this->belongsTo('App\Models\ehr\TreatmentInstructionMaster','medi_instruc');
    }
}
?>
