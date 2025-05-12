<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class File
 * 
 * @property int $id
 * @property string|null $name
 * @property string|null $local_path
 * @property string|null $s3key
 * @property int|null $user_id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property TicketUser|null $user
 * @property Collection|UserSuggestMessage[] $user_suggest_messages
 *
 * @package App\Models
 */
class File extends Model
{
	use SoftDeletes;
	protected $table = 'files';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'name',
		'local_path',
		's3key',
		'user_id'
	];

	public function user()
	{
		return $this->belongsTo(TicketUser::class);
	}

	public function user_suggest_messages()
	{
		return $this->hasMany(UserSuggestMessage::class);
	}
}
