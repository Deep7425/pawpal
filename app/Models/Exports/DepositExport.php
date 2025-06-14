<?php
namespace App\Models\Exports;

use App\Models\Doctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DepositExport implements FromCollection,WithHeadings {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $data;
    }
    // public function collection() {
        // return Doctors::limit(10)->get();
    // }
	public function headings(): array {
        return ['Sr. No.','Name','City','Deposit Amount','Pending Amount','Status','Date','Time'];
    }
	
	public function collection() {
        return collect($this->data);
    }
}