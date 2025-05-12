<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SheetData extends Model
{
    use HasFactory;
    protected $table = 'sheet_data';

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'user__id',
        'meta_data',
        'sheet_temp_id',
        'created_at',
        'updated_at'
    ];

    // Specify the dates that should be treated as Carbon instances
    protected $dates = ['created_at', 'updated_at'];

    // Define the relationship with SheetTemplate
    public function sheetTemplate()
    {
        return $this->belongsTo(SheetTemplate::class, 'sheet_temp_id');
    }
}
