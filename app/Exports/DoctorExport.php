<?php
namespace App\Models\Exports;

use App\Models\Doctors;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DoctorExport implements FromCollection,WithHeadings {
	use Exportable;
	private $data;

    public function __construct($data) {
        $this->data = $data;
    }
    // public function collection() {
        // return Doctors::limit(10)->get();
    // }
	public function headings(): array {
        return [
            'Sr. No.','Doc Id','Type','Name','Registartion Number','Email','Clinic Email','Mobile','Clinic Mobile','Gender','Speciality','Qualification','Speciality Group','Clnic Name','Address','State','City','Locality','Zipcode','Consultation Fee','Experience','Signature','Opd Time','Tele Consultation Fee','Servtel Api Key','Live','Updation Date','Registartion Date'
        ];
    }

	public function collection() {
        return collect($this->data);
    }
}
