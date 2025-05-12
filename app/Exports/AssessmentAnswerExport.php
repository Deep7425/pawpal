<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use App\Models\AssesmentAnswer;

class AssessmentAnswerExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    // public function collection()
    // {
    //     return AssesmentAnswer::All();
    // }
    use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $data;
    }
	
	public function collection() {
        return collect($this->data);
    }
}
