<?php
namespace App\Models\Exports;

use App\Models\Doctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriptionCashbackExport implements FromCollection,WithHeadings {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $data;
    }
	public function headings(): array {
        return ['Sr. No.','Order Id', 'Referral  Name','Referral Mobile No', 'Referral Type', 'Referred  Name','Referred Mobile No', 'Referred Type','Status', 'Organization', 'Date',];
    }
	public function collection() {
        return collect($this->data);
    }
}