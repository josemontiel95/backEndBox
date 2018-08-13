<?php 
	include_once("./../FPDF/fpdf.php");
	/*
		-Medidas de un documento pdf A4-
			En milimetros:
							Ancho:	210
							Alto:	297
			En pulgadas:
							Ancho:	8.25
							Alto:	11.75
	*/





	class PDF extends fpdf
	{
		/*	
			Funcion que establece los valores del "Header" se ejecuta en automatico al hacer una instancia de la clase. El motod ya se encuentra en la libreria, al se heredara solo queda sobreescribirla
		*/
		function Header()
		{

			//Definimos la celda en donde estara la cabezera, que es este caso seran las dos imagenes
			$this->cell(0,20,'',1,1);

			/*
		    // Logo lacocs
		    $this->Image('lacocs.jpeg',10,8);
		    $this->SetFont('Arial','B',15);
		    $this->SetX(190);
		    $this->multicell(50,5,'Lacocs asdasdasdasdasdasdasdsadas',1,'C');
		   	
		   	//Logo ema
		   	$this->Image('ema.jpeg',90,8);*/
		}


		/*
			Funciones para alinear el texto en una columna
			Fuente: https://huguidugui.wordpress.com/2013/11/26/fpdf-ajustar-texto-en-celdas/
		*/
		function CellFit($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='', $scale=false, $force=true)
	    {
	        //Get string width
	        $str_width=$this->GetStringWidth($txt);
	 
	        //Calculate ratio to fit cell
	        if($w==0)
	            $w = $this->w-$this->rMargin-$this->x;
	        $ratio = ($w-$this->cMargin*2)/$str_width;
	 
	        $fit = ($ratio < 1 || ($ratio > 1 && $force));
	        if ($fit)
	        {
	            if ($scale)
	            {
	                //Calculate horizontal scaling
	                $horiz_scale=$ratio*100.0;
	                //Set horizontal scaling
	                $this->_out(sprintf('BT %.2F Tz ET',$horiz_scale));
	            }
	            else
	            {
	                //Calculate character spacing in points
	                $char_space=($w-$this->cMargin*2-$str_width)/max($this->MBGetStringLength($txt)-1,1)*$this->k;
	                //Set character spacing
	                $this->_out(sprintf('BT %.2F Tc ET',$char_space));
	            }
	            //Override user alignment (since text will fill up cell)
	            $align='';
	        }
	 
	        //Pass on to Cell method
	        $this->Cell($w,$h,$txt,$border,$ln,$align,$fill,$link);
	 
	        //Reset character spacing/horizontal scaling
	        if ($fit)
	            $this->_out('BT '.($scale ? '100 Tz' : '0 Tc').' ET');
	    }
	 
	    function CellFitSpace($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=false, $link='')
	    {
	        $this->CellFit($w,$h,$txt,$border,$ln,$align,$fill,$link,false,false);
	    }
	 
	    //Patch to also work with CJK double-byte text
	    function MBGetStringLength($s)
	    {
	        if($this->CurrentFont['type']=='Type0')
	        {
	            $len = 0;
	            $nbbytes = strlen($s);
	            for ($i = 0; $i < $nbbytes; $i++)
	            {
	                if (ord($s[$i])<128)
	                    $len++;
	                else
	                {
	                    $len++;
	                    $i++;
	                }
	            }
	            return $len;
	        }
	        else
	            return strlen($s);
	    }



	}

	$fuente_titulo = 'Arial';
	$fuente_cuerpo = ''




	$pdf = new PDF('L');
	
	$pdf->AddPage();

	//Imprimimos el titulo (La informacion de Lacocs)
	$pdf->SetFont('Arial','B',15); //Antes que todo necesitamos definir la fuente
	$titulo = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A DE C.V';
	$tam_cell = $pdf->GetStringWidth($titulo)+6; //Calculamos el tamaño de nuestra cadena y asi obtener una referencia del tamaño de nuestra celda
	$pdf->SetX((297-$tam_cell)/2); //Calculamos el centro del documento
	$pdf->Cell($tam_cell,9,utf8_decode($titulo),1,1,'C');

	$direccion_lacocs = '35 NORTE No.3023, UNIDAD HABITACIONAL AQUILES SERDAN, PUEBLA, PUE.';
	$pdf->Cell(100,5,$direccion_lacocs,1,2,'C');







	$pdf->SetX(0);
	$pdf->ln(40);
	/*
		Falta definir la cadena mas larga del titulo par acalcular correctamente el tamaño de la cell
	*/

	//Impresion de tabla

	/*
		IDEAS:
				-Elaborar un array con el titulo de todos los campos, calcular los tamaños con la misma metodologia que se implemento en el titulo central, extaer ese array en cada inserccion de una fila.
	*/


	//Declaramos los campos en un array

	$titulo_campos = array('Clave del especimen','Unidad','Localización');
	for($i = 0;$i<=2;$i++) {
		$pdf->SetFont('Arial','B',15);
		$tam_cell = $pdf->GetStringWidth($titulo_campos[$i])+4;
		$pdf->cell($tam_cell,6,$titulo_campos[$i],1);
	}
	$pdf->Ln();
	for($i = 0;$i<=2;$i++) {
			$pdf->SetFont('Arial','B',15);
			$pdf->cell($pdf->GetStringWidth($titulo_campos[$i])+4,6,'',1);
	}




	$pdf->Output();






	/*
		NO SIRVE POR LO MIENTRAS
	//Definimos donde ira el titulo de Laccos
	$ancho_titulo = 20;	$alto_titulo = 4;

	
	$pdf->SetFont('Arial','B',15);

	$pdf->CellFitSpace(4,4,'PRUEBA',1,1,'C');
	
	*/



?>