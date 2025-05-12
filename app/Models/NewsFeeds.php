<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
class NewsFeeds extends Authenticatable {
    use Notifiable;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['login_id', 'slug','title','keyword','image','video','video_publish', 'blog_desc' , 'name' , 'description','status','type','blog_count','publish_date','show_date','delete_status','created_at','updated_at', 'doctor_id'];
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'news_feeds';
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

public function admin()
{
    return $this->belongsTo(Admin\Admin::class, 'login_id', 'id');
}

public function doctors(){
    return $this->belongsTo(Doctors::class, 'doctor_id', 'id');
}

}
