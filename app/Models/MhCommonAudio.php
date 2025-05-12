<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class MhCommonAudio extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mh_common_audios';
 	protected $fillable = ['title','audio_file','audio_file_hindi','tot_listeners'];
    public $timestamps = true;

}
