<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketUser extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'email',
        'mobile_no',
        'department_id'
    ];

    public function files()
    {
        return $this->hasMany(File::class,'user_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function suggest_messages()
    {
        return $this->belongsToMany(SuggestMessage::class, 'user_suggest_messages')
            ->withPivot('id', 'file_id', 'deleted_at')
            ->withTimestamps();
    }
    public function departments()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }


}
