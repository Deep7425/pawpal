<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DoctorData extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'doctors_data';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['doc_id','followup_count','plan_consult_fee','alternate_address'];
}
?>