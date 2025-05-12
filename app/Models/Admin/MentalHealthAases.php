<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentalHealthAases extends Model
{
    use HasFactory;
    
    protected $table = 'mental_health_aases';
 
    public $timestamps = true;
}
