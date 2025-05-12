<?php

namespace App\Models\ehr;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class EmailTemplate extends Model
{
    use SoftDeletes;
	protected $connection = 'mysql_ehr';
    protected $table = "email_templates";
    protected $fillable = ['title', 'slug', 'subject', 'description', 'status', 'created_at', 'updated_at'];
    protected $dates = ['deleted_at'];


}
