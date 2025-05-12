<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatConversation extends Model
{
    use HasFactory;

    protected $table = 'chat_conversations';

    protected $fillable = ['appointment_id', 'doc_id', 'patient_id', 'agent_id', 'last_message_at'];

    public function messages() {
        return $this->hasMany(ChatMessage::class);
    }

    public function doctor() {
        return $this->belongsTo(User::class, 'doc_id');
    }

    public function patient() {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
