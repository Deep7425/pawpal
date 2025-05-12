<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class NotificationSchedule extends Authenticatable
{
    use Notifiable;
    protected $table = "notification_schedule";
    protected $fillable  = ['title','n_type','last_run_date', 'schedule_type', 'notification_type', 'type_id', 'image', 'content', 'from_date', 'to_date','start_date','end_date', 'application_page', 'user_id', 'status'];
    public function admin()
    {
        return $this->belongsTo(Admin\Admin::class, 'user_id', 'id');
    }
}