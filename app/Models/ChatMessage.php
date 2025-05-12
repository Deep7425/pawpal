<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $table = 'messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',        // sender's ID
        'message',
        'is_read',
        'sent_at',
        'sender_type',
        'image'
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'sent_at' => 'datetime',
    ];
   public function getSenderDetailsAttribute()
    {
        switch ($this->sender_type) {
            case 1:
                return \App\Models\ehr\User::on('mysql_ehr')->find($this->sender_id);
            case 2:
                return \App\Models\User::on('mysql')->find($this->sender_id);
            case 3:
                return \App\Models\Admin\Admin::on('mysql')->find($this->sender_id);
            default:
                return null;
        }
    }
    

    // 🔹 Relationship to sender (user)
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    // 🔹 Relationship to conversation
    public function conversation()
    {
        return $this->belongsTo(ChatConversation::class);
    }
    public function ticket()
    {
        return $this->hasOne(Ticket::class, 'msg_id', 'id');
    }
}
