<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LabPrice  extends Authenticatable
{
    use HasFactory;
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'lab_prices';
    //public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'company_id', 'lab_id', 'amount'];
    /**
     * A profile belongs to a user
     *
     * @return mixed
     */

    public function company()
    {
        return $this->belongsTo(LabCompany::class,'company_id');
    }
    public function defaultLab()
    {
        return $this->belongsTo(DefaultLabs::class,'lab_id');
    }
}
