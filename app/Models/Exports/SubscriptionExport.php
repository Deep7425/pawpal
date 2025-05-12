<?php
namespace App\Models\Exports;

use App\Models\Doctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SubscriptionExport implements FromCollection,WithHeadings {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $data;
    }
    // public function collection() {
        // return Doctors::limit(10)->get();
    // }
	public function headings(): array {
        return ['Sr. No.','Sale By','Order ID','User Name','Mobile','Payment Mode','Plan Type','Plan Actual rate','Discount offer','Tax','Payble Amount','Ref Code','Corportae name','Order Status','Subscription Date','Subs Time','Total Done Appointment','Remark'];
    }
	
	public function collection() {
        return collect($this->data);
    }
}