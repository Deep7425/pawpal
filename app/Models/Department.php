<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Department
 * 
 * @property int $id
 * @property string $name
 * @property string|null $deleted_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @package App\Models
 */
class Department extends Model
{
	use SoftDeletes;
	protected $table = 'departments';

	protected $fillable = [
		'name'
	];
    public function user()
    {
        $this->hasMany(TicketUser::class, 'department_id');
    }
}
