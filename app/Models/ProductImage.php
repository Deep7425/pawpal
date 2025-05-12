<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class ProductImage extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
     protected $table = 'product_image';
 	protected $fillable = ['api_id','pid','name','status'];
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
