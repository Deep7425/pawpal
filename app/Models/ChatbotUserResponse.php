<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotUserResponse extends Model
{
    use HasFactory;
    protected $table = 'chatbot_user_responses';

    protected $fillable = [
        'user_id',
        'node_id',
        'input_text',
    ];

    public function node()
    {
        return $this->belongsTo(ChatbotNode::class, 'node_id');
    }
}
