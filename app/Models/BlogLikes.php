<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class BlogLikes extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'blog_likes';
 	protected $fillable = ['user_id','blog_id','status','ip'];
     public $timestamps = true;

	// public function Commet(){
  // 		  return $this->belongsTo('App\Models\State', 'state_id');
  // 	}
	// public function CityLocalities(){
  // 	  return $this->hasMany('App\Models\CityLocalities');
  // 	}
}
