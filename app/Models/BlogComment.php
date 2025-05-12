<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class BlogComment extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'blog_comment';
 	protected $fillable = ['user_id','blog_id','comment','publish','status'];
     public $timestamps = true;

     public function user()
     {
         return $this->belongsTo('App\Models\User', 'user_id');
     }
	  public function Blog()
     {
         return $this->belongsTo('App\Models\NewsFeeds', 'blog_id');
     }

}
