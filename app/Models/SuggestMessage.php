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
 * Class SuggestMessage
 * 
 * @property int $id
 * @property string $content
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Collection|User[] $users
 *
 * @package App\Models
 */
class SuggestMessage extends Model
{
	use SoftDeletes;
	protected $table = 'suggest_messages';

	protected $fillable = [
		'content'
	];

	public function users()
	{
		return $this->belongsToMany(User::class, 'user_suggest_messages')
					->withPivot('id', 'file_id', 'deleted_at')
					->withTimestamps();
	}
}
