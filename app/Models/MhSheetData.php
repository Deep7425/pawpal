<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhSheetData extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_sheet_data';
 	protected $fillable = ['user_id','sheet_value','sheet_id'];
    public $timestamps = true;

    public function MhCommonSheet()
    {
        return $this->belongsTo(MhCommonSheet::class, 'sheet_id', 'id'); 
    }


}
