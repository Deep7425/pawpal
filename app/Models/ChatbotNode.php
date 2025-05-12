<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotNode extends Model
{
    use HasFactory;

    protected $table = 'chatbot_nodes';

    protected $fillable = [
        'parent_id',
        'node_type',
        'content',
        'question_type',
        'sort_order',
    ];

    public function parent()
    {
        return $this->belongsTo(ChatbotNode::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(ChatbotNode::class, 'parent_id');
    }

    public function responses()
    {
        return $this->hasMany(ChatbotUserResponse::class, 'node_id');
    }


}
