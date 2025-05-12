<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class SymptomTags extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    
    protected $table = 'symptom_tags';
    public $timestamps = false;
	protected $fillable = ['symptoms_id','text'];
	
	
}
