<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class LabOrderedItems extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'lab_ordered_items';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
        'order_id','patient_lab_id','labtest_id','qty','cost','discount','discount_amt','discount_type','tax','tax_amt','total_amount'
    ];

    public function PatientLabs()
    {
        return $this->belongsTo('App\Models\ehr\PatientLabs','patient_lab_id');
    }
}
?>
