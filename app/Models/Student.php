<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;


class Student extends Authenticatable
{
    use HasFactory;

    protected $table = 'students'; // Specifies the table name

    protected $fillable = [
        'student_id', 'org_id',
    ];

    // Relationship with User
    public function user()
    {
        return $this->hasOne(User::class, 'student_id', 'id');
    }
}
