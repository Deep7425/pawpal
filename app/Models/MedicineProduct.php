<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MedicineProduct extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'medicine_product';
 	protected $fillable = ['api_id','name','medicine_type','type','pack_in','pack_unit','weight','manufacturer','packing_label','price','composition_name','banned','images','rx_req','meta_data','status'];
     public $timestamps = true;

}
