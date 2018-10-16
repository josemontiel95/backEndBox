<?php
	include_once("./../../FPDF/fpdf.php"); //Salieron problemas con la extension, pero en un principio no existian esos problemas

	/*
		Clase con fines de almacenar las funciones que necesito en las demas clases que generan los formatos y hacer mas limpio el codigo de los demas formatos
	*/
	class MyPDF extends fpdf{

		var $angle=0;

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
			$this->SetFont('Arial','',$tam_font);
			$num_rows = 0;
			foreach ($regisFormato as $registro) {
				$j=0;
				foreach ($registro as $campo) {
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
						$this->cell($arrayTamCells[$j],$tamAltoCells,utf8_decode($campo),1,0,'C');
				}	
				$this->Ln();
				}
			}
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