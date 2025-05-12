<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MedicineOrderedItems extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'medicine_ordered_items';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['order_id','medicine_id','qty','return_qty','cost','margin','item_type','discount_amt','discount_type','tax','tax_amt','total_amount'];

	public function MedicineProductDetails()
    {
        return $this->belongsTo('App\Models\MedicineProductDetails','medicine_id');
    }
}
?>
