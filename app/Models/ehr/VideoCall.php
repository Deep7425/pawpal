<?php

namespace App\Models\ehr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCall extends Model
{
    use HasFactory;
    protected $connection = 'mysql_ehr'; 
    protected $table = 'video_calls';
}
