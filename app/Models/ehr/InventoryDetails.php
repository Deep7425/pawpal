<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class InventoryDetails extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
	protected $connection = 'mysql_ehr'; 
    protected $table = 'inventory';
    //public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     protected $fillable = ['id','item_id','batch_no','qty','sku','unit','unit_id','real_qty','cost','cost_unit','mrp','mrp_unit','mfg_date','expire_date','manufacturer','added_by','taxable','cgst','sgst','cgst_inr','sgst_inr','supplier_id'];


	/**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
   public function ItemDetails()
   {
       return $this->belongsTo('App\Models\ehr\ItemDetails','item_id');
   }
   public function SupplierInfo()
   {
       return $this->belongsTo('App\Models\ehr\SupplierInfo','supplier_id');
   }
}
