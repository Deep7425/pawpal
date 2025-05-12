<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientName extends Model
{

    protected $table = 'client_name';

    protected $fillable = ['client_name','prefix' ,'status'];
    public $timestamps = true;

    use HasFactory;
}
