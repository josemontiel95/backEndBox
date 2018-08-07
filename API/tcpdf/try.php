<?php 


	/*
	-----------------------------------------------------------------------------------------------------

												A V I S O

								No pude terminar de leer la documentacion pero al menos se que si sirve la libreria, he aqui un ejemplo

	-----------------------------------------------------------------------------------------------------

	*/

	//Taken from http://www.tcpdf.org/examples/example_001.phps 
 
	// Include the main TCPDF library (search for installation path). 
	require_once('tcpdf.php'); 
	 
	// create new PDF document 
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
	 
	// set document information 
	$pdf->SetCreator(PDF_CREATOR); 
	$pdf->SetAuthor('Nicola Asuni'); 
	$pdf->SetTitle('TCPDF Example 001'); 
	$pdf->SetSubject('TCPDF Tutorial'); 
	$pdf->SetKeywords('TCPDF, PDF, example, test, guide'); 
	 
	// set default header data 
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128)); 
	$pdf->setFooterData(array(0,64,0), array(0,64,128)); 
	 
	// set header and footer fonts 
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN)); 

	$pdf->AddPage();                    // pretty self-explanatory
	$pdf->Write(1, 'Hello world');      // 1 is line height
	$pdf->Output('hello_world.pdf');  
?>