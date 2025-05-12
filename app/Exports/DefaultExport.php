<?php
namespace App\Models\Exports;

use App\Models\Doctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class DefaultExport implements FromCollection {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $data;
    }
    
	
	public function collection() {
        return collect($this->data);
    }
}