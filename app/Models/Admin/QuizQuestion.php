<?php

namespace App\Models\Admin;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
class QuizQuestion extends Authenticatable{
    use Notifiable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    
    protected $table = 'quiz_questions';
    public $timestamps = true;
    protected $fillable = ['symptom_id','oid','question','optionA','optionB','optionC','optionD','optionE','optionF','correctOption','question_hindi','optionA_hindi','optionB_hindi','optionC_hindi','optionD_hindi','optionE_hindi','optionF_hindi','optionA_val','optionA_val','optionB_val','optionC_val','optionD_val','optionE_val','optionF_val','status'];
	
    public function SymptomsName() {
		return $this->belongsTo('App\Models\Admin\Symptoms', 'symptom_id');
	  }
}
