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
 * Class Ticket
 *
 * @property int $id
 * @property string $uuid
 * @property int $user_id
 * @property string $ticket_no
 * @property string $status
 * @property string $priority
 * @property string $assign_by
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Comment[] $comments
 *
 * @package App\Models
 */
class Ticket extends Model
{
	use SoftDeletes;
	protected $table = 'tickets';

	protected $casts = [
		'user_id' => 'int'
	];

	protected $fillable = [
		'uuid',
		'user_id',
		'ticket_no',
		'status',
		'priority',
        'assign_by',
        'case_type',
        'category',
		'assignee_status',
		'subject',
		'msg_id'
	];

	public function comments()
	{
		return $this->hasMany(Comment::class, 'tickets_id');
	}
    public function ticketReply()
    {
        return $this->hasMany(TicketReply::class, 'ticket_id');
    }
    public function user()
    {
        return $this->belongsTo(TicketUser::class);
    }
    public function assignByUser()
    {
        return $this->belongsTo(TicketUser::class,'assign_by');
    }

	public function chatMessage()
    {
        return $this->belongsTo(ChatMessage::class, 'msg_id', 'id');
    }

	public function admin()
{
    return $this->belongsTo(Admin\Admin::class, 'user_id', 'id');
}
}
