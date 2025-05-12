<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhJournal extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_journal';
 	protected $fillable = ['user_id','meta'];
    public $timestamps = true;
    public function MhJournalThought() {
        return $this->belongsTo('App\Models\MhJournalThought','thought_id');
    }

}
