<?php 
	//include_once("./../library_watermark/rotation.php");
	include_once("./../FPDF/fpdf.php");


	
	$pdf  = new fpdf('L','mm','Letter');
	$pdf->AddPage();
	$tam_font_titulo = 9;
	$pdf->SetFont('Arial','B',$tam_font_titulo);
	$cadena = 'CADENA DE PRUEBA';
	$tam_cadena = $pdf->GetStringWidth($cadena)+2;
	$cadena_prueba = $cadena;
	$tam_cadena_prueba = $pdf->GetStringWidth($cadena_prueba)+2;

	//Funcion 
	while($tam_cadena_prueba>$tam_cadena){
		$cadena_prueba = substr($cadena_prueba,0,(strlen($cadena_prueba))-1);
		$tam_cadena_prueba =  $pdf->GetStringWidth($cadena_prueba)+2;
		$pdf->cell($tam_cadena,5,$cadena_prueba,1,2);
	}

	$pdf->cell($tam_cadena,5,'',1,2);
	$pdf->cell($tam_cadena,5,$cadena,1,2);
	$pdf->cell($tam_cadena,5,$cadena_prueba,1);

	$tam_cadenaphp = strlen($cadena);
	$tam_A = $pdf->GetStringWidth('A');
	$tam_a = $pdf->GetStringWidth('a');
	$tam_B = $pdf->GetStringWidth('B');
	$tam_b = $pdf->GetStringWidth('b');
	$tam_C = $pdf->GetStringWidth('C');
	$tam_c = $pdf->GetStringWidth('c');
	$tam_D = $pdf->GetStringWidth('D');
	$tam_d = $pdf->GetStringWidth('d');
	$tam_E = $pdf->GetStringWidth('E');
	$tam_e = $pdf->GetStringWidth('e');
	$tam_F = $pdf->GetStringWidth('F');
	$tam_f = $pdf->GetStringWidth('f');
	//$pdf->cell(0,5,$tam_A.'-'.$tam_a.'-'.$tam_B.'-'.$tam_b.'-'.$tam_C.'-'.$tam_c.'-'.$tam_D.'-'.$tam_d.'-'.$tam_E.'-'.$tam_e.'-'.$tam_F.'-'.$tam_f,1);




	$pdf->Output();



	function TruncaString($string,$font,$style,$tam_font,$tam_cell){
	$this->SetFont($font,$style,$tam_font); //Configuramos las especificaciones del texto para calcular el tamaño apropiedamente
	$tam_string = $this->GetStringWidth($string); //Obtenemos el tamaño de la cadena
	//Funcion 
	while($tam_cadena_prueba>$tam_cadena){
		$cadena_prueba = substr($cadena_prueba,0,(strlen($cadena_prueba))-1);
		$tam_cadena_prueba =  $pdf->GetStringWidth($cadena_prueba)+2;
		$pdf->cell($tam_cadena,5,$cadena_prueba,1,2);
	}
}
	

?>