<?php

namespace App\Http\Controllers;
use \setasign\Fpdi\Fpdi;

class PDFHF extends Fpdi {
	function Header()
	{
		// Logo
		$this->Image(public_path() . '/labIcon.jpg',10,6,30);
		// Arial bold 15
		$this->SetFont('Arial','B',22);
		// Move to the right
		$this->Cell(90);
		// Title
		$this->SetTextColor(255,97,0);
		$this->Cell(110,10,'HEALTH GENNIE',0,0,'C','','https://www.healthgennie.com/');
		// Line break
		$this->Ln(20);
	}

	// Page footer
	function Footer()
	{
		// Position at 1.5 cm from bottom
		$this->SetY(-8);
		// Arial italic 8
		$this->SetFont('Arial','B',7);
		// $this->SetFillColor(4,106,183);
		$this->SetTextColor(4,106,183);
		// Page number
		$this->Cell(0,6,'C-92, Satya Vihar, Lal kothi, Jaipur-302015 | Contact No.:8929920932 | Whatsapp : 8690006254 | Email : labs@healthgennie.com | website : www.healthgennie.com ',0,0,'C');
	}
}
