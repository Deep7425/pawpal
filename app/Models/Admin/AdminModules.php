<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class AdminModules extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['module_name'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'admin_modules';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
}
