<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class labspincode extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['company_id','pincode'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'labs_pincode';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
     public function labpackage() {
           return $this->belongsTo('App\Models\LabPackage', 'company_id');
       }
}
