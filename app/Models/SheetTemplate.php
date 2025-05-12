<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class SheetTemplate extends Model
{
    use HasFactory;
    use SoftDeletes;

    // Specify the table name (optional, if different from the model's plural name)
    protected $table = 'sheet_template';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'sheet_id',
        'html_module',
        'created_at',
        'updated_at',
        'deleted_at'    
    ];

    // Specify the dates that should be treated as Carbon instances
    protected $dates = ['created_at', 'updated_at', 'deleted_at'];

    // Define the relationship with SheetData
    public function sheetData()
    {
        return $this->hasMany(SheetData::class, 'sheet_temp_id');
    }
}
