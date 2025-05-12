<?php

namespace App\Models\ehr;

use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
	protected $connection = 'mysql_ehr';
    protected $fillable = ['user_id', 'role_id','practice_id'];
    protected $table = 'role_user';
    public $timestamps = false;
	
	
	public function PracticeDetails() {
  		return $this->belongsTo('App\Models\ehr\PracticeDetails','practice_id','user_id');
  	}
	public function user()
  	{
  		return $this->belongsTo('App\Models\User');
  	}
  	public function role()
       {
         	return $this->belongsTo('App\Models\Role', 'role_id');
       }
}
