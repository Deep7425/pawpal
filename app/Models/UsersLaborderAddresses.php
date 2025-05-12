<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class UsersLaborderAddresses extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */

    protected $table = 'users_laborder_addresses';
    protected $fillable = ['user_id','locality','pincode','address','landmark','label_type','label_name','deliverTo','deliverToMobile','created_at','updated_at'];
    public $timestamps = true;


}
