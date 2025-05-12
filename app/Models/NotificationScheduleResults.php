<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class NotificationScheduleResults extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'notification_schedule_id',
        'result',
    ];

    protected $casts = [
        'result' => 'json', // This will automatically cast the result to an array when accessed
    ];
}
