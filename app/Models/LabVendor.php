<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class LabVendor extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lab_vendor';
 	  protected $fillable = ['title','address','days','open_time','close_time','status','delete_status'];
    public $timestamps = true;
}
