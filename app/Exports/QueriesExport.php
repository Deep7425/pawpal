<?php
namespace App\Models\Exports;

use App\Models\Doctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
//use Maatwebsite\Excel\Concerns\WithHeadings;

class QueriesExport implements FromCollection {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $data;
    }
    // public function collection() {
        // return Doctors::limit(10)->get();
    // }
	
	
	public function collection() {
        return collect($this->data);
    }
}