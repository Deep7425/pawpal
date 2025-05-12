<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use setasign\Fpdi\Fpdi;
use App\Http\Controllers\PDFHF;


class PdfManager extends BaseController {
	public function getPdf(){
		$pdf = new PDFHF();
    	$pdf->AliasNbPages();
		
    	// $pdf->AddPage();
    	// $pdf->SetFont('Times','',12);
		
		$pagecount = $pdf->setSourceFile("public/labData.pdf");

		if($pagecount>0) {
			for($i=1;$i<=$pagecount;$i++) {
				// $pdf->endPage();
				$tplId = $pdf->importPage($i);
				$pdf->AddPage();
				$pdf->useTemplate($tplId);
				//$pdf->SetPrintHeader(false);
				//$pdf->SetPrintFooter(false); 
			}
		}
		// $tplId = $pdf->importPage(1);
		// $pdf->useTemplate($tplId);
	    $pdf->Output();
	    exit;
		// Create new PDF object 
		// $pdf = new Fpdi('P','mm','A4'); 
		/*$pdf = new Fpdi(); 

		$pdf->header = 0;
		$pdf->footer = 0;
		$pdf->addPage('','',false); 

		// Output pdf file 
		$pdf->Output('test.pdf','D'); */
		
		
		// initiate FPDI
		// $pdf = new Fpdi();
		// add a page
		
		// $pdf->header = 0;
		// $pdf->footer = 0;
		// $pdf->AddPage();
		// set the source file
		// $pdf->setSourceFile("public/labData.pdf");
		// import page 1
		// $tplId = $pdf->importPage(1);

		// use the imported page and place it at point 10,10 with a width of 100 mm
		// $pdf->useTemplate($tplId, 100, 100, 100);

		// $pdf->Output();  
	}

}
