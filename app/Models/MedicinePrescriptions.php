<?php
namespace App\Models;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MedicinePrescriptions extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'medicine_prescriptions';
	 protected $fillable = ['user_id','prescription','delete_status'];
     public $timestamps = true;
}
