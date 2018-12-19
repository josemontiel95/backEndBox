<?php
	include_once("./../../FPDF/fpdf.php"); //Salieron problemas con la extension, pero en un principio no existian esos problemas

	/*
		Clase con fines de almacenar las funciones que necesito en las demas clases que generan los formatos y hacer mas limpio el codigo de los demas formatos
	*/
	class MyPDF extends fpdf{

		var $angle=0;


		/*
				FUNCION PENDIENTE POR SI NO SIRVE EL METODO DE SOLO PONER UN MULTICELL EN TEXTOS DE OBRA Y LOCALIZACION DE LA OBRA


		function configPosition($tam_font,$string,$tamAltoCells,$tamAnchoCells){
			//Contador de las iteracion que realiza el ciclo
			$count = 0;

			//Maximo de iteracion que puede hacer el ciclo
			$max = 3;

		}
		*/
		function putInfoTables($grupos,$tam_font,$arrayTamCells,$tamAltoCells,$valor){
			$this->SetFont('Arial','',$tam_font);
			$num_rows = 0;
			//Modificar de modo a que acepte los resultados de la query PENDIENTE
			for($k=0;$k<$grupos;$k++) {	
				for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
					$this->cell($arrayTamCells[$j],$tamAltoCells,$this->getMaxString($tam_font,$arrayTamCells[$j],$valor),1,0,'C');
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<$grupos){
				for ($i=0; $i < ($grupos-$num_rows); $i++){
					for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
						$this->cell($arrayTamCells[$j],$tamAltoCells,$this->getMaxString($tam_font,$arrayTamCells[$j],$valor),1,0,'C');
					}
					$this->Ln();
				}
			}
		}

		function putRowsWithoutInfo($grupos,$tam_font,$arrayTamCells,$tamAltoCells){
			$this->SetFont('Arial','',$tam_font);
			$num_rows = 0;
			//Modificar de modo a que acepte los resultados de la query PENDIENTE
			for($k=0;$k<$grupos;$k++) {	
				for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
					$this->cell($arrayTamCells[$j],$tamAltoCells,'',1,0,'C');
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<$grupos){
				for ($i=0; $i < ($grupos-$num_rows); $i++){
					for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
						$this->cell($arrayTamCells[$j],$tamAltoCells,'',1,0,'C');
					}
					$this->Ln();
				}
			}
		}

		function putRowsCCH($regisFormato,$grupos,$tam_font,$arrayTamCells,$tamAltoCells){
			$error = 0;
			$this->SetFont('Arial','',$tam_font);
			$num_rows = 0;
			foreach ($regisFormato as $registro) {
				$j=0;
				foreach ($registro as $campo) {

					$resultado = $this->printInfoObraAndLocObra($tam_font,$arrayTamCells[$j],$tamAltoCells,$campo,1);

					$this->SetFont('Arial','',$resultado['sizeFont']);
					$campo = $resultado['new_string'];

					if($resultado['error'] == 100){
						$error = $resultado['error'];
					}


					$this->cell($arrayTamCells[$j],$tamAltoCells,utf8_decode($campo),1,0,'C');
					$j++;
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<$grupos){
				for ($i=0; $i < ($grupos-$num_rows); $i++){
				//Definimos la posicion de X para tomarlo como referencia
				for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
						$this->cell($arrayTamCells[$j],$tamAltoCells,'',1,0,'C');
				}	
				$this->Ln();
				}
			}

			return $error;
		}

		function putInfoTablesWithPosition($positionPrint,$grupos,$tam_font,$arrayTamCells,$tamAltoCells,$valor){
			$this->SetFont('Arial','',$tam_font);
			$num_rows = 0;
			//Modificar de modo a que acepte los resultados de la query PENDIENTE
			for($k=0;$k<$grupos;$k++) {	
				$this->SetX($positionPrint);
				for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
					$this->cell($arrayTamCells[$j],$tamAltoCells,$this->getMaxString($tam_font,$arrayTamCells[$j],$valor),1,0,'C');
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<$grupos){
				for ($i=0; $i < ($grupos-$num_rows); $i++){
					$this->SetX($positionPrint);
					for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
						$this->cell($arrayTamCells[$j],$tamAltoCells,$this->getMaxString($tam_font,$arrayTamCells[$j],$valor),1,0,'C');
					}
					$this->Ln();
				}
			}
		}

		function putInfoTablesWithPositionInformesWithoutInfo($positionPrint,$grupos,$tam_font,$arrayTamCells,$tamAltoCells){
			$this->SetFont('Arial','',$tam_font);
			$num_rows = 0;
			//Modificar de modo a que acepte los resultados de la query PENDIENTE
			for($k=0;$k<$grupos;$k++) {	
				$this->SetX($positionPrint);
				for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
					$this->cell($arrayTamCells[$j],$tamAltoCells,'',1,0,'C');
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<$grupos){
				for ($i=0; $i < ($grupos-$num_rows); $i++){
					$this->SetX($positionPrint);
					for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
						$this->cell($arrayTamCells[$j],$tamAltoCells,'',1,0,'C');
					}
					$this->Ln();
				}
			}
		}

		function putInfoTablesWithPositionInformes($positionPrint,$regisFormato,$grupos,$tam_font,$arrayTamCells,$tamAltoCells){
			$this->SetFont('Arial','',$tam_font);
			$num_rows = 0;
			foreach ($regisFormato as $registro) {
				$j=0;
				$this->SetX($positionPrint);
				foreach ($registro as $campo) {
					$this->cell($arrayTamCells[$j],$tamAltoCells,utf8_decode($campo),1,0,'C');
					$j++;
				}
				$num_rows++;
				$this->Ln();
			}
			
			if($num_rows<$grupos){
				
				for ($i=0; $i < ($grupos-$num_rows); $i++){
					$this->SetX($positionPrint);
					//Definimos la posicion de X para tomarlo como referencia
					for ($j=0; $j < sizeof($arrayTamCells); $j++){ 
							$this->cell($arrayTamCells[$j],$tamAltoCells,'',1,0,'C');
					}	
					$this->Ln();
				}
			}
		}

		function putMaxString($sizeString,$totalCharacters){
			$this->SetFont('Arial','',$sizeString);
			$string = 'W'; //Definimos la cadena
			//El contador lo inicializamos en 1, porque siguiendo el razonamiento del algoritmo, esa es la longitud que ya tenemos de la cadena, entonces falta llegar al limite definido por el numero de caracteres que se obtuvo del otro algoritmo
			for ($i=1; $i < $totalCharacters; $i++) 
				$string .='W';
			return $string;
		}

		function getMaxString($sizeFont,$tam,$value){
			//Instanciamos el objeto para crear el pdf
			$pdf = new fpdf();
			//Configuramos el tamaño y fuente de la letra
			$pdf->SetFont('Arial','',$sizeFont);
			//Declaramos el estado inicial de la cadena
			$string = 'W';	
			//Calculamos el tamaño inicial de la cadena
			$tam_string = $pdf->GetStringWidth($string);
			if($tam_string >= $tam){
				return array('estatus' => 'No se pudo calcular, el tamaño de la celda es insuficiente para ese tamaño de letra.','error' => '1');
			}
			else{

				$totalCharacters = intval($tam/$tam_string) - 1;

				switch ($value) {
					case 'tam_stringCarac':
							return 	$totalCharacters;
						break;
					case 'string':
							return $this->putMaxString($sizeFont,$totalCharacters);
						break;
					case 'tam_stringPoints':
							return $this->GetStringWidth(	$this->putMaxString($sizeFont,$totalCharacters)	);
						break;
					
				}
			}

		}

		function printInfo($sizeFont,$tam,$string){
			//Obtenemos el total de caracteres que se puede almacenar en esa cadena
			$totalCharacters = $this->getMaxString($sizeFont,$tam,'tam_stringCarac');
			
			if(strlen($string) > $totalCharacters){
				$string = substr($string,0,$totalCharacters - 1);//Extraemos la parte de la cadena que cabe en la celda
				$string.='...';
			}
			return $string;
		}


		function TruncSaltosLinea($string,$numRows){
			//Contador de los saltos de linea
			$contSalto = 0;

			$error = 0;

			//echo "Numero de caracteres:".strlen($string);
			
			//Realizamos un ciclo para contar los saltos de linea
			for ($i=0; $i < strlen($string); $i++) { 
				//Si encontramos un salto de linea aumentamos el contador
				if($string[$i] == "\n"){
					$contSalto++;
					//Si el numero de saltos de linea llego al numero de renglones menos 1 rompemos el ciclo
					if($contSalto == $numRows){
						if($i == (strlen($string) - 1)){
							$string = substr($string,0,$i);
							//echo "Tamaño de cadena".strlen($string);
						}else{
							$string = substr($string,0,$i-2);
							$string.='...';
							$error = 1;
						}		
						break;
					}
				}

			}
			return array(
						'string' => $string,
						'error' => $error
					);
		}


		function printInfoObraAndLocObra($sizeFont,$tam,$tamaño_alto,$string,$numRows){
			//Creamos un objeto tipo MyPDF para hacer las operaciones que necesitamos
			$pdf = new MyPDF('L','mm','Letter');

			$pdf->AddPage();

			$resultado = $this->TruncSaltosLinea($string,$numRows);

			$new_string = $resultado['string'];

			//Guardamos la posicion inicial de "Y" para comprobar posteriormente que solo se crearon el numero de rows que se necesitaba
			$posIniY = $pdf->GetY();

			$limit = $pdf->GetY() + ($tamaño_alto*$numRows);

			$pdf->SetFont('Arial','',$sizeFont);

			$pdf->multicell($tam,$tamaño_alto,$new_string,1,'C');



			$posiciony = $pdf->GetY();

			//Calculamos los rows que se generan al escribir

			$totalRows = ($posiciony-$posIniY)/$tamaño_alto;

			

			//Este ciclo decrementara el tamaño de fuente
			while($posiciony > $limit && $sizeFont>1){
				//Decrementamos el tamaño de fuente
				$sizeFont-=1;

				//Configuramos el tamaño de fuente
				$pdf->SetFont('Arial','',$sizeFont);

				//Nos posicionamos en la posicion de "Y" que tenia inicialmente
				$pdf->sety($posIniY);

				$pdf->multicell($tam,$tamaño_alto,$new_string,1,'C');

				$posiciony = $pdf->gety();
			}

			if($posiciony <= $limit){
				if($resultado['error'] == 0){
					$tam_string = $this->GetStringWidth($string);
					$tam_new_string = $pdf->GetStringWidth($new_string);
					return array('sizeFont' => $sizeFont,'string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'Total de renglones que serian' => $totalRows, 'estatus' => 'Texto valido','error' => 0);
				}else{
					$tam_new_string = $pdf->GetStringWidth($new_string);
					$tam_string = $this->GetStringWidth($string);
					return array('sizeFont' => $sizeFont,'string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo aprox' => $tam,'estatus' => 'El texto excedió el tamaño permitido por saltos de linea.','error' => 100);
				}
				
			}else{
				$new_string = $this->truncMulticell($sizeFont,$tam,$tamaño_alto,$new_string,$numRows);
				$tam_new_string = $pdf->GetStringWidth($new_string);
				$tam_string = $this->GetStringWidth($string);
				return array('sizeFont' => $sizeFont,'string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo aprox' => $tam,'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
			}
			/*
			$pdf->sety(100);	

			$pdf->multicell($tam,$tamaño_alto,$string,1,'C');

			$pdf->output();*/

			

		}


		function truncMulticell($sizeFont,$tam,$tamaño_alto,$string,$numRows){
			//Creamos un objeto tipo MyPDF para hacer las operaciones que necesitamos
			$pdf = new MyPDF('L','mm','Letter');



			$pdf->AddPage();

			//Guardamos la posicion inicial de "Y" para comprobar posteriormente que solo se crearon el numero de rows que se necesitaba
			$posIniY = $pdf->GetY();

			$limit = $pdf->GetY() + ($tamaño_alto*$numRows);

			$pdf->SetFont('Arial','',$sizeFont);

			$pdf->multicell($tam,$tamaño_alto,$string,1,'C');

			//ECHO "HOLA ESTOY EN truncMulticell";

			$posiciony = $pdf->GetY();

			//Calculamos los rows que se generan al escribir

			$totalRows = ($posiciony-$posIniY)/$tamaño_alto;

			//Este ciclo decrementara el tamaño de fuente
			while($posiciony > $limit){
				$string = substr($string,0,strlen($string) - 1);
				$pdf->sety($posIniY);
				$pdf->multicell($tam,$tamaño_alto,$string,1,'C');
				$posiciony = $pdf->gety();
			}



			/*
			$pdf->sety(100);	

			$pdf->multicell($tam,$tamaño_alto,$string,1,'C');

			$pdf->output();*/
			//Quitamos la ultima letra y ponemos puntos suspensivos para demostrar que no es valido
			$string = substr($string,0,(strlen($string))-2);
			$string.='...';
			return $string;
		}

		/*
		//Funcion para obra y locacalizacion de la obra
		function printInfoObraAndLocObra($sizeFont,$tam,$string){
			/*
				Quite los contadores, porque no contaria el numero de iteraciones sino que aun quede un tamaño de fuente bueno

			//Contador de las iteracion del ciclo
			$cont = 0;

			//Maximo de iteracion que puede realizar el ciclo
			$max = 3;
			

			//Configuramos el tamaño de fuente para la cadena
			$this->SetFont('Arial','',$sizeFont);

			//Tamaño de la cadena
			$tam_string = $this->GetStringWidth($string);

			//echo "Tamaño de fuente antes del ciclo:".$sizeFont;
			while($tam_string > $tam && $sizeFont>=1){
				//Decrementamos el tamaño de fuente
				$sizeFont-=0.1;

				//Configuramos el tamaño de fuente nuevamente para calcular el tamaño de la cadena con el nuevo tamaño de fuente
				$this->SetFont('Arial','',$sizeFont);

				$tam_string = $this->GetStringWidth($string); 
			}
			//echo "Tamaño del espacio:".$tam."Tamaño de la cadena".$tam_string."Tamaño de fuente".$sizeFont;
			if($tam_string <= $tam){
				return array('sizeFont' => $sizeFont, 'estatus' => 'Texto valido','error' => 0);
			}
				return array('estatus' => 'Error, el texto excede el tamaño, aun reduciendo el tamaño de fuente','error' => 1);
		}
		*/
		function getMaxStringMultiCell($sizeString,$tam,$value,$group){
			//Instanciamos el objeto para crear el pdf
			$pdf = new fpdf();
			//Configuramos el tamaño y fuente de la letra
			$pdf->SetFont('Arial','',$sizeString);
			//Declaramos el estado inicial de la cadena
			$string = 'W';	
			//Calculamos el tamaño inicial de la cadena
			$tam_string = $pdf->GetStringWidth($string);
			if($tam_string >= $tam){
				return array('estatus' => 'No se pudo calcular, el tamaño de la celda es insuficiente para ese tamaño de letra.','error' => '1');
			}
			else{

				$totalCharacters = (intval($tam/$tam_string) - 1) * $group;

				switch ($value) {
					case 'tam_stringCarac':
							return 	$totalCharacters;
						break;
					case 'string':
							return $this->putMaxString($sizeString,$totalCharacters);
						break;
					case 'tam_stringPoints':
							return $this->GetStringWidth(	$this->putMaxString($sizeString,$totalCharacters)	);
						break;
					
				}
			}

		}

		
		function RotatedText($x, $y, $txt, $angle)
		{
		    //Text rotated around its origin
		    $this->Rotate($angle,$x,$y);
		    $this->Text($x,$y,$txt);
		    $this->Rotate(0);
		}

		function Rotate($angle,$x=-1,$y=-1)
		{
			if($x==-1)
				$x=$this->x;
			if($y==-1)
				$y=$this->y;
			if($this->angle!=0)
				$this->_out('Q');
			$this->angle=$angle;
			if($angle!=0)
			{
				$angle*=M_PI/180;
				$c=cos($angle);
				$s=sin($angle);
				$cx=$x*$this->k;
				$cy=($this->h-$y)*$this->k;
				$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
			}
		}

		function _endpage()
		{
			if($this->angle!=0)
			{
				$this->angle=0;
				$this->_out('Q');
			}
			parent::_endpage();
		}

		function TextWithDirection($x, $y, $txt, $direction='R')
		{
		    if ($direction=='R')
		        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',1,0,0,1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		    elseif ($direction=='L')
		        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',-1,0,0,-1,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		    elseif ($direction=='U')
		        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,1,-1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		    elseif ($direction=='D')
		        $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',0,-1,1,0,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		    else
		        $s=sprintf('BT %.2F %.2F Td (%s) Tj ET',$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		    if ($this->ColorFlag)
		        $s='q '.$this->TextColor.' '.$s.' Q';
		    $this->_out($s);
		}

		function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
		{
		    $font_angle+=90+$txt_angle;
		    $txt_angle*=M_PI/180;
		    $font_angle*=M_PI/180;

		    $txt_dx=cos($txt_angle);
		    $txt_dy=sin($txt_angle);
		    $font_dx=cos($font_angle);
		    $font_dy=sin($font_angle);

		    $s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		    if ($this->ColorFlag)
		        $s='q '.$this->TextColor.' '.$s.' Q';
		    $this->_out($s);
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


?>