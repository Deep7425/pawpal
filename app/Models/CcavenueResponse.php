<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class CcavenueResponse extends Authenticatable
{
    use Notifiable;

    /**
     * The database table used by the model.
     * @var string
     */
    protected $table = 'ccavenue_response';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = ['slug','meta_data'];

    /**
	 * A profile belongs to a user
	 *
	 * @return mixed
	 */


}
?>
