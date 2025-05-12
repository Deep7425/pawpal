<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
class PrintSettings extends Authenticatable
{
    use Notifiable;
    /**
     * The database table used by the model.
     * @var string
     */
	protected $connection = 'mysql_ehr';  
    protected $table = 'print_settings';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','left_margin','right_margin','top_margin','bottom_margin','font_size','print_layout','note_print_layout','note_print_settings','billing_print_layout','billing_print_settings','pharmacy_print_layout','pharmacy_print_settings', 'laboratory_print_layout','laboratory_print_settings','radiology_print_layout','radiology_print_settings'];
    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
   public function user()
   {
     return $this->belongsTo('App\Models\ehr\User','user_id');
   }
}
?>
