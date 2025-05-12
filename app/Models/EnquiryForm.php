<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class EnquiryForm extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'enquiry_forms';
 	protected $fillable = ['name','email','mobile','req_from','city'];
    public $timestamps = true;


}
