<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class UserPrescription extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['pId','appointment_id','user_id','patient_number','aptTime','aptDate','doc_name','prescription','status','delete_status'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_prescription_details';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
