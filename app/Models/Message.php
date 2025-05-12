<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Message
 * 
 * @property int $id
 * @property string|null $content
 * @property int|null $user_id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property User|null $user
 *
 * @package App\Models
 */
class Message extends Model
{
	use SoftDeletes;
	protected $table = 'messages';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'content',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(TicketUser::class);
	}
}
