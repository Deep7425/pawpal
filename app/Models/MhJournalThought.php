<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhJournalThought extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_journal_thoughts';
 	protected $fillable = ['title','title_hindi'];
    public $timestamps = true;

}
