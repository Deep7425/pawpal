<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class LeaveRequest extends Authenticatable
{

    use Notifiable;
    use HasFactory;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'leave_req';

    protected $fillable = ['l_date','remark','type','status', 'added_by', 'manager_email', 'start_date', 'end_date'];
    public $timestamps = true;


    public function admin()
    {
        return $this->belongsTo(Admin::class, 'added_by', 'id');
    }
}
