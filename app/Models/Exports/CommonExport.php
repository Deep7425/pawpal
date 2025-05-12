<?php
namespace App\Models\Exports;

use App\Models\Doctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CommonExport implements FromCollection,WithHeadings {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $data;
    }
	public function headings(): array {
        return ['Sr. No.','Name','Location','Total Visitors','Online Plan','Cash Plan','Cash Amount','Date','Time','Total Online Subscription', 'Total Cash Subscription'];
    }
	public function collection() {
        return collect($this->data);
    }
}