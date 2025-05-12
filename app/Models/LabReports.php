<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class LabReports extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'lab_reports';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['company_id','order_id','user_id','report_xml_name','report_pdf_name','origin_lab_report'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */

}
?>
