<?php

namespace App\Models;

use App\Models\Admin\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class AttendanceSheet extends Authenticatable
{
    use Notifiable;
    use HasFactory;

    protected $table = 'attendance_sheet';
    protected $fillable = ['start_time','end_time','start_pic','end_pic','shift_time','weak_off','lat','lng', 'live_location','location', 'added_by'];
    public $timestamps = true;
    public function admin()
    {
        return $this->belongsTo(Admin::class, 'added_by', 'id');
    }
}
