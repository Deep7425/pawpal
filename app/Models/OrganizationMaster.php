<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class OrganizationMaster extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'organization_master';

    protected $fillable = ['title','logo','slug','pwd','delete_status'];

    public $timestamps = true;

    public function subscriptions()
	{
		return $this->hasMany('App\Models\UsersSubscriptions', 'organization_id');
	}
}
