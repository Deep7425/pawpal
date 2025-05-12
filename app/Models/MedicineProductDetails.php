<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MedicineProductDetails extends Authenticatable{
    use Notifiable;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'medicine_product_origin';
	protected $fillable = ['api_id','name','medicine_type','type','pack_in','pack_unit','weight','manufacturer','packing_label','unit','price','composition_name','banned','images','rx_req','status','delete_status','added_by'];
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
}
