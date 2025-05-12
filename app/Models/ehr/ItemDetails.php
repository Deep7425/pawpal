<?php

namespace App\Models\ehr;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ItemDetails extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $connection = 'mysql_ehr';
    protected $table = 'items';
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','company_id','item_code','item_category','item_type','item_name','composition_name','strength','unit','hsn','gst','status','delete_status','added_by'
    ];
    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
   public function InventoryDetails()
   {
       return $this->hasMany('App\Models\ehr\InventoryDetails','item_id');
   }
   public function itemType()
   {
      return $this->belongsTo('App\Models\ehr\ItemType','item_type');
   }
   public function ItemCategory()
   {
      return $this->belongsTo('App\Models\ehr\ItemCategory','item_category');
   }
}
