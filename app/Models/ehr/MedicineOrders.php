<?php
namespace App\Models\ehr;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MedicineOrders extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $table = 'medicine_orders';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     * @var array
     */
     protected $fillable = ['appointment_id','patient_id','order_by','doctor_type','status','practice_id','delete_status','order_status'];
    /**
	 * A profile belongs to a user
	 * @return mixed
	 */
    public function Patients()
    {
      return $this->belongsTo('App\Models\ehr\Patients','patient_id');
    }
    public function PatientMedications()
    {
      return $this->hasMany('App\Models\ehr\PatientMedications','order_id')->where('delete_status','=',1);
    }
    public function User()
    {
      return $this->belongsTo('App\Models\ehr\User','order_by');
    }
    public function MedicineBill()
    {
      return $this->hasOne('App\Models\ehr\MedicineBill','order_id');
    }
    public function ReferralsMaster()
    {
      return $this->belongsTo('App\Models\ehr\ReferralsMaster','order_by');
    }
    public function OrderedMedicine()
    {
      return $this->hasMany('App\Models\ehr\OrderedMedicine','order_id');
    }
    public function RefundMedicineBill()
    {
      return $this->hasOne('App\Models\ehr\RefundMedicineBill','order_id');
    }
    public function RefundOrderedMedicine()
    {
      return $this->hasMany('App\Models\ehr\RefundOrderedMedicine','order_id');
    }
    public function Appointments()
    {
      return $this->belongsTo('App\Models\ehr\Appointments','appointment_id');
    }
}
?>
