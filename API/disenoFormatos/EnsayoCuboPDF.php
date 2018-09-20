<?php 
	
	//include_once("./../../FPDF/fpdf.php");
	include_once("./../FPDF/fpdf.php");
	//Formato de campo de cilindros
	class EnsayoCuboPDF extends fpdf{
		var $angle=0;
		function Header(){
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de Lacocs
			$ancho_lacocs = 30;	$alto_lacocs = 10;
			$this->cell($ancho_lacocs,$alto_lacocs,'',1);

			$posicion_x = $this->GetX();

			//Definimos el las propiedades de la primera linea del titulo
			$tam_font_titulo = 9;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo_linea1 = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A DE C.V';
			$tam_cell = $this->GetStringWidth($titulo_linea1);
			$this->SetX((210-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo_linea1),0,'C');
			$this->Ln(8);

			//Titulo del informe
			$tam_font_tituloInforme = 7;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '"ENSAYO A COMPRESIÓN DE CUBOS DE CONCRETO HIDRÁULICO"';
			$tam_titulo_informe = $this->GetStringWidth($titulo_informe)+3;
			$this->SetX((210-$tam_titulo_informe)/2);
			$this->Cell($titulo_informe,$tam_font_tituloInforme - 3,utf8_decode($titulo_informe),0,'C');

			$this->ln(4);
			
			//Put the watermark
   			$this->SetFont('Arial','B',50);
	    	$this->SetTextColor(192,192,192);
    		$this->RotatedText(100,130,'PREELIMINAR',45);
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

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.
		

		function putInfo(){

			$this->Ln(4);
			$tam_font_right = 8;	$this->SetFont('Arial','',$tam_font_right);
			$tam_line = 160;
			$fechaEnsaye = 'FECHA DE ENSAYE:';
			$tam_fechaEnsaye = $this->GetStringWidth($fechaEnsaye)+20;
			$this->SetX(-($tam_fechaEnsaye+35));
			$this->Cell($tam_fechaEnsaye,$tam_font_right - 3,$fechaEnsaye,1,0,'C');

			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'',1,0,'C');
			$this->Ln($tam_font_right - 2);

			//Salto de linea 
			$this->ln(4);
			/*
			$tam_font_left = 8;	$this->SetFont('Arial','B',$tam_font_left);
			//Cuadro con informacion
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 4,$infoFormato['obra'],'B',0);
			$this->Ln(4);
			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 4,$infoFormato['localizacion'],'B',0);
			$this->Ln(4);
			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 4,$infoFormato['nombre'],'B',0);
			$this->Ln(4);
			//Direccion del cliente
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 4,$infoFormato['direccion'],'B',0);

			$this->ln(8);


			$tam_font_left = 8;	$this->SetFont('Arial','',$tam_font_left);
			//Texto Adicional
			$texto_adicional = 'SE DETERMINA EL REVENIMIENTO EN CONCRETO FRESCO TOMANDO COMO BASE LA NORMA MEXICANA NMX-C-156-ONNCCE-2010';
			$tam_texto_adicional = $this->GetStringWidth($texto_adicional)+3;
			$this->Cell($tam_texto_adicional,$tam_font_left - 3,utf8_decode($texto_adicional),0);
			$this->Ln(8);

			$localizacion = 'LOCALIZACIÓN:';
			$tam_localizacion = $this->GetStringWidth($localizacion)+3;
			$this->Cell($tam_localizacion,$tam_font_left - 3,utf8_decode($localizacion),0);
			//Caja de texto
			$this->Cell(0,$tam_font_left - 4,$infoFormato['localizacionRev'],'B',0);

			*/
		}

		function putTables(){

			$tam_font_head = 6.5;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave


			$fechaColado = 'FECHA DE COLADO';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+3;
			$this->Cell($tam_fechaColado,$tam_font_head+3,$fechaColado,1,0,'C');

			$infoNumero = 'INFORME NUMERO';
			$tam_infoNumero = $this->GetStringWidth($infoNumero)+3;
			$this->Cell($tam_infoNumero,$tam_font_head+3,$infoNumero,1,0,'C');

			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave)+14;
			$this->Cell($tam_clave,$tam_font_head+3,$clave,1,0,'C');

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$edad = 'EDAD DE ENSAYE';
			$tam_edad = $this->GetStringWidth($edad)+3;
			$this->Cell($tam_edad,($tam_font_head+3)/2,$edad,'L,T,R',2,'C');
			$this->cell($tam_edad,($tam_font_head+3)/2,utf8_decode('EN DIÁS'),'L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_edad,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$lado = 'LADO (cm)';
			$tam_lado = $this->GetStringWidth($lado)+20;
			$this->Cell($tam_lado,($tam_font_head+3)/2,$lado,'L,T,R',2,'C');
			$tam_l1 = $tam_l2 = $tam_lado/2;
			$this->cell($tam_l1,($tam_font_head+3)/2,'L1',1,0,'C');
			$this->cell($tam_l2,($tam_font_head+3)/2,'L2',1,0,'C');

			$this->SetXY($posicion_x + $tam_lado,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$resisCompresion = 'RESISTENCIA A';
			$tam_resisCompresion = $this->GetStringWidth($resisCompresion)+8;
			$this->Cell($tam_resisCompresion,($tam_font_head+3)/2,$resisCompresion,'L,T,R',2,'C');
			$this->cell($tam_resisCompresion,($tam_font_head+3)/6,utf8_decode('COMPRESIÓN'),'L,R',2,'C');
			$this->cell($tam_resisCompresion,($tam_font_head+3)/3,'kg','L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_resisCompresion,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$area = 'AREA';
			$tam_area = $this->GetStringWidth($area)+10;
			$this->Cell($tam_area,($tam_font_head+3)/2,$area,'L,T,R',2,'C');
			$this->cell($tam_area,($tam_font_head+3)/2,utf8_decode('cm²'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_area,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$resis = 'COMPRESIÓN kg/cm²';
			$posicion_x = $this->GetX();
			
			$this->Cell(0,($tam_font_head+3)/2,'RESISTENCIA A','L,T,R',2,'C');
			$this->cell(0,($tam_font_head+3)/2,utf8_decode($resis),'L,B,R',2,'C');

			
			$tam_resis = $this->GetX() - $posicion_x;
			$this->lN(0);
		

			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									$tam_fechaColado,
									$tam_infoNumero,
									$tam_clave,
									$tam_edad,
									$tam_l1,
									$tam_l2,
									$tam_resisCompresion,
									$tam_area,
									$tam_resis
							);

			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);
			for ($i=0; $i < 45; $i++){
				//Definimos la posicion de X para tomarlo como referenci
				for ($j=0; $j < 9; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->cell($array_campo[$j],$tam_font_head - 2,'',1,0,'C');
				}	
				$this->Ln();
			}

			$this->Ln(4);

			$tam_font_head = 7; $this->SetFont('Arial','B',$tam_font_head);
			$observaciones = 'OBSERVACIONES:';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->cell(0,$tam_font_head - 4,utf8_decode($observaciones),'L,T,R',2);
			//$this->cell(0,$tam_font_head - 2,utf8_decode($observaciones.' LA VELOCIDAD DE APLICACIÓN ES DE'.''.'kg/cm²*min'),'L,T,R',2);
			$this->cell($this->GetStringWidth($observaciones)+2,$tam_font_head,'','L,B',0);

			$this->SetXY($posicion_x + $this->GetStringWidth($observaciones)+2,$posicion_y);
			$this->cell(0,10,utf8_decode('LA VELOCIDAD DE APLICACIÓN DE CARGA ES DE ______ kg/cm²*min'),'T,B,R',2);

			$inventario = 'Inventario de';
			$tam_inventario = $this->GetStringWidth($inventario)+10;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_inventario,(($tam_font_head)-1)/2,$inventario,'L,T,R',2,'C');
			$this->Cell($tam_inventario,(($tam_font_head)-1)/2,'instrumento','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_inventario,$posicion_y);
			$prensa = 'BASCULA';
			$tam_prensa = $this->GetStringWidth($prensa)+12;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_prensa,(($tam_font_head)-1)/2,$prensa,1,2,'C');
			$this->Cell($tam_prensa,(($tam_font_head)-1)/2,'','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_prensa,$posicion_y);
			$flexo = 'REGLA VERNIER O FLEXOMETRO';
			$tam_flexo = $this->GetStringWidth($flexo)+12;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_flexo,(($tam_font_head)-1)/2,$flexo,1,2,'C');
			$this->Cell($tam_flexo,(($tam_font_head)-1)/2,'','L,B,R',0,'C');
		
			
			$prensa = 'PRENSA';
			$this->SetXY($posicion_x+$tam_flexo,$posicion_y);
			$tam_prensa = $this->GetStringWidth($prensa)+12;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_prensa,(($tam_font_head)-1)/2,$prensa,1,2,'C');
			$this->Cell($tam_prensa,(($tam_font_head)-1)/2,'','L,B,R',1,'C');
			
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();


			$tam_image = 15;
			$tam_font_footer = 6; $this->SetFont('Arial','b',$tam_font_footer);

			$tam_boxElaboro = (196-40)/2;	$tam_first = 7.5; $tam_second = 7.5;
			$this->SetXY($posicion_x+24.5,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'REALIZO','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+20,$this->gety() - 5,utf8_decode('___________________________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('Nombre y firma')/2),$this->gety() - 2,utf8_decode('Nombre y firma'));	
			//$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('TEC. GABINO MENA PEREZ')/2),$this->gety() - 6,utf8_decode('TEC. GABINO MENA PEREZ'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);


			$tam_boxElaboro = (196-40)/2;	$tam_first = 7.5; $tam_second = 7.5;
			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+20,$this->gety() - 5,utf8_decode('___________________________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('Nombre y firma')/2),$this->gety() - 2,utf8_decode('Nombre y firma'));	
			//$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('ING. LAURA CATILLO DE LA ROSA')/2),$this->gety() - 6,utf8_decode('ING. LAURA CATILLO DE LA ROSA'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			/*
			foreach ($regisFormato as $registro) {
				$j=0;
				foreach ($registro as $campo) {

					//Funcion para truncar la cadena
					$tam_campo = $this->GetStringWidth($campo); //Tamaño de la informacion del campo
					while($tam_cadena_prueba>$array_campo[$j]){
						$campo = substr($$campo,0,(strlen($campo))-1);
						$tam_campo =  $pdf->GetStringWidth($$campo)+2;
					}
					$this->cell($array_campo[$j],$tam_font_head - 2.5,$campo,1,0,'C');
					$j++;
				}
				$this->Ln();
			}*/
			/*
			for($i=0;$i<11;$i++{
				$this->cell(0,$tam_alto_determinacion/5,'','L,R,B',2,'C');
				$this->cell($array_campo[$j],$tam_font_head - 2.5,'',1,0,'C');
			}*/
			$this->Ln(4);
			
					
		}
		
		function Footer(){
			/*
			$tam_font_footer = 7;	$this->SetFont('Arial','',$tam_font_footer);
			$observaciones = 'OBSERVACIONES:';

			$this->multicell(0,($tam_font_footer - 2.5),$observaciones,'B',2);

			$this->ln(4);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			//Instrumentos
			$tam_font_footer = 6.5; $this->SetFont('Arial','',$tam_font_footer);
			$instrumentos = 'Inventario de'."\n".'instrumentos';
			$tam_instrumentos = $this->GetStringWidth('Inventario de')+5;
			$this->multicell($tam_instrumentos,$tam_font_footer - 2,$instrumentos,1,'C');

			$this->SetXY(($posicion_x + $tam_instrumentos),$posicion_y);
			$cono = 'Cono';
			$tam_cono = $this->GetStringWidth($cono)+12;
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->cell($tam_cono,($tam_font_footer - 2),$cono,1,2,'C');
			$this->cell($tam_cono,($tam_font_footer - 2),'',1,2,'C');

			$this->SetXY(($posicion_x + $tam_cono),$posicion_y);
			$varilla = 'Varilla';
			$tam_varilla = $this->GetStringWidth($varilla)+10;
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->cell($tam_varilla,($tam_font_footer - 2),$varilla,1,2,'C');
			$this->cell($tam_varilla,($tam_font_footer - 2),'',1,2,'C');


			$this->SetXY(($posicion_x + $tam_varilla),$posicion_y);
			$flexometro = 'Flexometro';
			$tam_flexometro = $this->GetStringWidth($flexometro)+10;
			$this->cell($tam_flexometro,($tam_font_footer - 2),$flexometro,1,2,'C');
			$this->cell($tam_flexometro,($tam_font_footer - 2),'',1,2,'C');


			//Lado derecho

			$tam_box = 90;
			$this->SetXY((-($tam_box+10)),$posicion_y);
			$simbologia = 'SIMBOLOGIA';
			$posicion_x = $this->GetX(); 
			$this->cell($tam_box,($tam_font_footer - 2),$simbologia,'L,T,R',2,'C');
			$posicion_y = $this->GetY();
			$this->SetXY($posicion_x,$posicion_y);
			$this->cell($tam_box/3,($tam_font_footer - 1),'CA = CON ADITIVO','L,B',0,'C');

			$this->cell($tam_box/3,($tam_font_footer - 1),'RR = RESISTENCIA RAPIDA','B',0,'C');

			$this->cell($tam_box/3,($tam_font_footer - 1),'CA = NORMAL','B,R',0,'C');
			*/
			
		}
		//Funcion que crea un nuevo formato

		function CreateNew($infoFormato,$regisFormato,$target_dir){
			$pdf  = new Revenimiento('P','mm','Letter');
			$pdf->AddPage();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($regisFormato);
			$pdf->Output('F',$target_dir);
		}


		/*
		function CreateNew($infoFormato,$regisFormato){
			$pdf  = new Revenimiento('P','mm','Letter');
			$pdf->AddPage();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($regisFormato);
			$pdf->Output();
		}*/

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
	
	
	$pdf  = new EnsayoCuboPDF('P','mm','Letter');
	$pdf->AddPage();
	$pdf->putInfo();
	$pdf->putTables();
	$pdf->Output();
	
	


?>