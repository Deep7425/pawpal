<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Laboratory extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'laboratories';
 	  protected $fillable = ['vendor_id','lab_id','price','discount','status','delete_status'];
    public $timestamps = true;

    public function LabVendor()
    {
        return $this->belongsTo('App\Models\LabVendor', 'vendor_id');
    }
    public function LabMaster()
    {
        return $this->belongsTo('App\Models\LabMaster', 'lab_id');
    }
}
