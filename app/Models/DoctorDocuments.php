<?php
namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class DoctorDocuments extends Authenticatable {
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'doctor_documents';
    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['doc_id','user_id','type','file_name'];
}
?>