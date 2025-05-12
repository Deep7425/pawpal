<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class Campaigns extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'campaigns';
    //public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id','campaign_type','to_users','tot_recipients','subject','message','sender_id','created_at','updated_at','tot_success','tot_success_users','tot_fail','fail_users','type','msg_length_cnt'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */
}
