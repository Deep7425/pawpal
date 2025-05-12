<?php

namespace App\Models\ehr;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleSetting extends Model
{
    protected $table = 'setting_modules';
	protected $connection = 'mysql_ehr';  
}
