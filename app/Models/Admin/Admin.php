<?php
namespace App\Models\Admin;
use App\Models\AttendanceSheet;
use App\Models\LeaveRequest;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Admin extends Authenticatable {

	use Notifiable;

    protected $fillable = ['name','email' ,'logo', 'department_id' ,'mobile_no','password','module_permissions','remember_token','subs_amount','status','delete_status','city','shift_t_strt','shift_t_end','subs_amount','week_off', 'manager_id','ref_code'];
 
    protected $table = 'admins';
    public $timestamps = true;
    public function leaves()
    {
        return $this->hasMany(LeaveRequest::class, 'added_by');
    }
    public function attendance()
    {
        return $this->hasMany(AttendanceSheet::class, 'added_by');
    }
    public function manager()
    {
        return $this->belongsTo(Admin::class, 'manager_id');
    }

}
