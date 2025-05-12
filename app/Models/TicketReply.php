<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TicketReply
 * 
 * @property int $id
 * @property string|null $message
 * @property int|null $ticket_id
 * @property int|null $department_id
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ticket|null $ticket
 *
 * @package App\Models
 */
class TicketReply extends Model
{
	use SoftDeletes;
	protected $table = 'ticket_replies';

	protected $casts = [
		'ticket_id' => 'int',
		'department_id' => 'int'
	];

	protected $fillable = [
		'message',
		'ticket_id',
		'department_id',
        'comment_id'
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class);
	}
    public function comments()
	{
		return $this->belongsTo(Comment::class);
	}
}
