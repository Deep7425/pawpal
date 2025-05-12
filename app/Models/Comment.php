<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Comment
 * 
 * @property int $id
 * @property int $tickets_id
 * @property string|null $comments
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * 
 * @property Ticket $ticket
 *
 * @package App\Models
 */
class Comment extends Model
{
	use SoftDeletes;
	protected $table = 'comments';

	protected $casts = [
		'tickets_id' => 'int'
	];

	protected $fillable = [
		'tickets_id',
		'comments',
	];

	public function ticket()
	{
		return $this->belongsTo(Ticket::class, 'tickets_id');
	}
    public function ticketReply()
    {
        return $this->hasMany(TicketReply::class, 'comment_id');
    }
}
