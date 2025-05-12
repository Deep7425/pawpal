<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class UserSuggestMessage
 * 
 * @property int $id
 * @property int $suggest_message_id
 * @property int|null $user_id
 * @property int|null $file_id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property File|null $file
 * @property SuggestMessage $suggest_message
 * @property User|null $user
 *
 * @package App\Models
 */
class UserSuggestMessage extends Model
{
	use SoftDeletes;
	protected $table = 'user_suggest_messages';

	protected $casts = [
		'suggest_message_id' => 'int',
		'user_id' => 'int',
		'file_id' => 'int'
	];

	protected $fillable = [
		'suggest_message_id',
		'user_id',
		'file_id'
	];

	public function file()
	{
		return $this->belongsTo(File::class);
	}

	public function suggest_message()
	{
		return $this->belongsTo(SuggestMessage::class);
	}

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}
