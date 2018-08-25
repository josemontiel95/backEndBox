<?php 
	include_once("./../tcpdf/tcpdf.php");

	/*
		REFERENCIAS:
					https://programacion.net/articulo/crea_documentos_pdf_online_con_tcpdf_1639

	*/
	class MYPDF extends TCPDF
	{
		/*
			Define caracteristicas que tendra el Header
		*/
		public function Header(){
			$this->setJPEGQuality(90); //Se define la calidad de la imagen
            //$this->Image('logo.png', 120, 10, 75, 0, 'PNG', 'http://www.finalwebsites.com'); //Se define la imagen que tendra el encabezado, para revisar todos los posibles parametros que tendra visita: https://stackoverflow.com/questions/44061583/what-are-the-parameter-of-the-image-in-tcpdf
		}

		/*
			Define caracteristicas que tendra el Footer
		*/
		public function Footer() {
                $this->SetY(-15); //Posicion
                $this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8); //Fuente
                $this->Cell(0, 10, 'finalwebsites.com - PHP Script Resource, PHP classes and code for web developer', 0, false, 'C'); //Se define la celda del texto de pie
        }

        /*
			Crea cuadros de texto en alguna parte del PDF
        */
        public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
                $this->SetXY($x+20, $y); // 20 = margin left
                $this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
                $this->Cell($width, $height, $textval, 0, false, $align);
        } 
	}

	//--------------------------------------Creamos una instancia de MYPDF
	/*$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('LACOCS S.A DE C.V');
	$pdf->SetTitle('INFORME');
	$pdf->SetSubject('<SUBTITULO>');
	$pdf->SetKeywords('TCPDF, PDF, example, tutorial');

	$pdf->Write(1, 'Hello world');*/      // 1 is line height
	//echo "EN DESARROLLO";
	$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false); 
	 
	// set document information 
	$pdf->SetCreator(PDF_CREATOR); 
	$pdf->SetAuthor('LACOCS S.A DE C.V');
	$pdf->SetTitle('INFORME');
	$pdf->SetSubject('<SUBTITULO>');
	$pdf->SetKeywords('TCPDF, PDF, example, tutorial');
	 
	// set default header data 
	$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 001', PDF_HEADER_STRING, array(0,64,255), array(0,64,128)); 
	$pdf->setFooterData(array(0,64,0), array(0,64,128)); 
	 
	// set header and footer fonts 
	$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN)); 

	$pdf->AddPage();                    // pretty self-explanatory
	$pdf->Write(1, 'Hello world');      // 1 is line height
	$pdf->Output('hello_world.pdf'); 



	

?>