<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhCommonSheet extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_common_sheets';
 	protected $fillable = ['title','title_hindi','description','description_hindi','sheet_name','symp_id','status'];
    public $timestamps = true;
    public function Sheet() {
        return $this->hasOne(MhSheetData::class, 'sheet_id');
    }
      public function MhSheetData()
    {
        return $this->hasMany(MhSheetData::class, 'sheet_id', 'id'); 
    }
    public function Symptoms() {
        return $this->belongsTo(Admin\Symptoms::class, 'symp_id');
    }
}
