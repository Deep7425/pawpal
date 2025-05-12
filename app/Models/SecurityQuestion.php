<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class SecurityQuestion extends Authenticatable
{
    protected $table = "security_questions";

    use HasFactory;
    protected $fillable = ['question'];
    
    public function userSecurityQuestions()
    {
        return $this->hasMany(UserSecurityQuestion::class);
    }
}
