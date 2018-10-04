<?php 
	//include_once("./../library_watermark/rotation.php");
	include_once("./../../FPDF/fpdf.php");
	//include_once("./../FPDF/fpdf.php");
	//Formato de campo de cilindros
	class CCH extends fpdf{
		var $angle=0;
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
		function Header(){
			$ancho_ema = 50;	$alto_ema = 20;
			$tam_lacocs = 20;
			//$this->SetX(-($ancho_ema + 10));
			//$this->Image('ema.jpeg',null,null,$ancho_ema,$alto_ema);
			$posicion_x = $this->GetX();

			$this->Image('http://lacocs.montielpalacios.com/SystemData/BackData/Assets/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			//$this->TextWithDirection($this->GetX(),$this->gety() + 24,utf8_decode('LACOCS S.A. DE C.V.'));

			$posicion_x = $this->GetX();

			//Definimos el las propiedades de la primera linea del titulo
			$tam_font_titulo = 9;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo_linea1 = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A DE C.V';
			$tam_cell = $this->GetStringWidth($titulo_linea1);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo_linea1),0,2,'C');

			//Definimos las propiedades de la segunda linea del titulo
			$tam_font_titulo = 16; //Definimos el tamaño de la fuente
			$this->SetFont('Arial','B',$tam_font_titulo);
			$titulo_linea2 = 'LACOCS S.A DE S.V';
			$tam_cell = $this->GetStringWidth($titulo_linea2);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo_linea2),0,2,'C');
			$this->Ln();

			//Definimos la altura a la que estara la informacion del documento
			$this->SetY(34); $posicion_y = $this->GetY();
			
			//Put the watermark
   			$this->SetFont('Arial','B',50);
	    	$this->SetTextColor(192,192,192);
    		$this->RotatedText(100,130,'PREELIMINAR',45);
		}

		

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.
		

		function putInfo($infoFormato){

			$this->ln(4);
			$tam_font_right = 7.5;	$this->SetFont('Arial','B',$tam_font_right);
			$tam_line = 160;
			

			

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			$tam_font_left = 7;	$this->SetFont('Arial','B',$tam_font_left); 

			//Cuadro con informacion
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetFont('Arial','',$tam_font_left); 
			$this->SetX(50);
			$this->Cell($tam_line,$tam_font_left - 3,utf8_decode($infoFormato['obra']),'B',0);

			$this->Ln($tam_font_left - 2);
			$this->SetFont('Arial','B',$tam_font_left); 
			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);

			//Caja de texto
			$this->SetX(50);
			$this->SetFont('Arial','',$tam_font_left); 
			$this->Cell($tam_line,$tam_font_left - 3,utf8_decode($infoFormato['localizacion']),'B',0);
			$this->SetFont('Arial','B',$tam_font_left); 
			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;
			$this->SetX(-($tam_informeNo+40));
			$this->Cell($tam_informeNo,$tam_font_right - 3,$informeNo,0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$tam_font_left); 
			$this->Cell(0,$tam_font_right - 3,utf8_decode($infoFormato['informeNo']),'B',0,'C');

			$this->Ln($tam_font_left - 2);

			$this->SetFont('Arial','B',$tam_font_left); 
			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);

			//Caja de texto
			$this->SetFont('Arial','',$tam_font_left); 
			$this->SetX(50);
			$this->Cell($tam_line,$tam_font_left - 3,utf8_decode($infoFormato['razonSocial']),'B',0);

			$this->Ln($tam_font_left - 2);

			//Direccion del cliente
			$this->SetFont('Arial','B',$tam_font_left); 
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX(50);
			$this->SetFont('Arial','',$tam_font_left); 
			$this->Cell($tam_line,$tam_font_left - 3,utf8_decode($infoFormato['direccion']),'B',0);

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($tam_font_left-2);

			//Titulo del CCH
			$tam_font_tituloCCH = 12; //Definimos el tamaño de la fuente
			$this->SetFont('Arial','B',$tam_font_tituloCCH);
			$titulo_CHH = 'CONTROL DE CONCRETO HIDRÁULICO';
			$tam_cell = $this->GetStringWidth($titulo_CHH);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_tituloCCH - 3,utf8_decode($titulo_CHH),0,2,'C');
			$this->Ln(2);

		}
		//function putTables($infoFormato,$regisFormato) el chido
		function putTables($infoFormato,$regisFormato){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$tam_font_head = 8;	$this->SetFont('Arial','B',$tam_font_head);//Fuente para clave

			//Clave
			$clave = 'CLAVE DEL ESPECIMEN';
			$tam_clave = $this->GetStringWidth($clave) + 10; 
			$this->cell($tam_clave,1.5*(2*($tam_font_head) - 6),$clave,1,0,'C');

			//Fecha
			$fecha = 'FECHA';
			$tam_fecha = $this->GetStringWidth($fecha) + 15;
			$this->cell($tam_fecha,1.5*(2*($tam_font_head) - 6),$fecha,1,0,'C');
			//F´c
			$fprima = 'F ` C ';
			$tam_fprima = $this->GetStringWidth($fecha) + 5; $posicion_x = $this->GetX();
			$this->multicell($tam_fprima,0.5*(1.5*(2*($tam_font_head) - 6)),utf8_decode($fprima."\n".'kg/cm²'),1,'C');

			

			//Proyecto
			$proyecto = 'PROYECTO';
			$tam_pro = $this->GetStringWidth($proyecto) + 3;
			$this->SetY($posicion_y + 0.5*(1.5*(2*($tam_font_head) - 6))); $this->SetX($posicion_x + $tam_fprima);	$posicion_x = $this->GetX();
			$this->cell($tam_pro,0.5*(1.5*(2*($tam_font_head) - 6)),$proyecto,1,0,'C');

			//Obra
			$obra = 'OBRA';
			$tam_obra = $this->GetStringWidth($obra) + 4;
			$this->SetY($posicion_y + 0.5*(1.5*(2*($tam_font_head) - 6)));	$this->SetX($posicion_x + $tam_pro);
			$this->cell($tam_obra,0.5*(1.5*(2*($tam_font_head) - 6)),$obra,1,2,'C');


			//Revenimiento
			$rev = 'REVENIMENTO (cm)';
			$this->SetY($posicion_y); $this->SetX($posicion_x); $posicion_x = $this->GetX();
			$this->cell($tam_pro + $tam_obra,0.5*(1.5*(2*($tam_font_head) - 6)),$rev,1,0,'C');

			$tam_font_head = $tam_font_head-2;	$this->SetFont('Arial','B',$tam_font_head);

			//Guardamos la posicion de la x
			$posicion_x = $this->GetX();

			//Agregado
			$agregado = 'AGREGADO';
			$tam_agregado = $this->GetStringWidth($agregado) + 4;
			$this->multicell($tam_agregado,(1.5*(2*($tam_font_head + 2) - 6))/5,utf8_decode('TAMAÑO'."\n".'NOMINAL'."\n".'DEL'."\n".'AGREGADO'."\n".'(mm)'),1,'C');

			$posicion_x = ($posicion_x + $tam_agregado);
			//VOLUMEN
			$volumen = 'VOLUMEN';
			$tam_volumen = $this->GetStringWidth($volumen) + 3;
			$this->SetY($posicion_y); $this->SetX($posicion_x);
			$this->multicell($tam_volumen,(1.5*(2*($tam_font_head + 2) - 6))/2,utf8_decode($volumen."\n".'(mm)'),1,'C');

			$posicion_x = $posicion_x + $tam_volumen;

			$concreto = 'CONCRE';
			$tam_concreto = $this->GetStringWidth($concreto) + 3;
			$this->SetY($posicion_y); $this->SetX($posicion_x);
			$this->multicell($tam_concreto,(1.5*(2*($tam_font_head + 2) - 6))/3,utf8_decode('TIPO DE'."\n".$concreto."\n".'TO'),1,'C');

			$posicion_x = $posicion_x + $tam_concreto;

			$unidad = 'UNIDAD';
			$tam_unidad = $this->GetStringWidth($unidad) + 3;
			$this->SetY($posicion_y); $this->SetX($posicion_x);
			$this->cell($tam_unidad,(1.5*(2*($tam_font_head + 2) - 6)),utf8_decode($unidad),1,0,'C');

			$posicion_x = $posicion_x + $tam_unidad;

			$hora = 'HORA DE';
			$tam_hora = $this->GetStringWidth($hora) + 5;
			$this->multicell($tam_hora,(1.5*(2*($tam_font_head + 2) - 6))/4,utf8_decode($hora."\n".'MUESTREO'."\n".'EN'."\n".'OBRA'),1,'C');

			$posicion_x = $posicion_x + $tam_hora;

			$muestreo = 'MUESTREO';
			$tam_muestreo = $this->GetStringWidth($muestreo) + 3;
			$this->SetY($posicion_y); $this->SetX($posicion_x);
			$this->multicell($tam_muestreo,(1.5*(2*($tam_font_head + 2) - 6))/5,utf8_decode('TEMP.'."\n".'AMBIENTE'."\n".'DE'."\n".$muestreo."\n".'(°C)'),1,'C');

			$posicion_x = $posicion_x + $tam_muestreo;

			$recoleccion = 'RECOLECCIÓN';
			$tam_recoleccion = $this->GetStringWidth($recoleccion) + 3;
			$this->SetY($posicion_y); $this->SetX($posicion_x);
			$this->multicell($tam_recoleccion,(1.5*(2*($tam_font_head + 2) - 6))/5,utf8_decode('TEMP.'."\n".'AMBIENTE'."\n".'DE'."\n".$recoleccion."\n".'(°C)'),1,'C');

			$posicion_x = $posicion_x + $tam_recoleccion;

			$tam_font_head = 8;	$this->SetFont('Arial','B',$tam_font_head);//Fuente para clave

			$localizacion = 'LOCALIZACIÓN';
			$tam_localizacion = (279 - 10) - $posicion_x;
			$this->SetY($posicion_y); $this->SetX($posicion_x);
			$this->cell($tam_localizacion,1.5*(2*($tam_font_head) - 6),utf8_decode($localizacion),1,2,'C');
			

			//Definimos el array con los tamaños de cada celda para crear las duplas
			$this->SetFont('Arial','',$tam_font_head);//Fuente para clave
			$tam_localizacion; //Verificar como mostrare la locaclizacion
			$array_campo = 	array(
									$tam_clave,
									$tam_fecha,
									$tam_fprima,
									$tam_pro,
									$tam_obra,
									$tam_agregado,
									$tam_volumen,
									$tam_concreto,
									$tam_unidad,
									$tam_hora,
									$tam_muestreo,
									$tam_recoleccion,
									$tam_localizacion									
							);


						
			$this->SetX(10); //Definimos donde empieza los 
			
			$num_rows = 0;
			foreach ($regisFormato as $registro) {
				$j=0;
				foreach ($registro as $campo) {

					//Funcion para truncar la cadena
					$tam_campo = $this->GetStringWidth($campo); //Tamaño de la informacion del campo
					while($tam_campo>$array_campo[$j]){
						$campo = substr($campo,0,(strlen($campo))-1);
						$tam_campo =  $this->GetStringWidth($campo)+2;
					}
					$this->cell($array_campo[$j],$tam_font_head - 2.5,utf8_decode($campo),1,0,'C');
					$j++;
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<9){
				for ($i=0; $i < (9-$num_rows); $i++){
				//Definimos la posicion de X para tomarlo como referencia
				for ($j=0; $j < sizeof($array_campo); $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					if($j < sizeof($array_campo)){
						$this->cell($array_campo[$j],$tam_font_head - 2.5,'',1,0,'C');
					}
					
				}	
				$this->Ln();
				}
			}

			
			//Calculo del tamaño de los registros
			/*
			for($i=0;$i<8;$i++){
				$this->cell(0,$tam_font_head - 2.5,'',1,2,'C');
			}*/

			$this->Ln(2);
			//$this->cell(0,10,$this->GetY(),1,2,'C');

			
				//$posicion_y = 140;
			$tam_observaciones = 10;
			$tam_font_footer = 7;	$this->SetFont('Arial','B',$tam_font_footer);
			$observaciones = 'OBSERVACIONES:';

			//Observaciones
			//$this->SetY($posicion_y);
			$this->cell(0,2*($tam_font_footer - 2.5),$observaciones,'L,T,R',2);
			$this->cell(0,$tam_observaciones,utf8_encode($infoFormato['observaciones']),'L,B,R',2);

			//Metodos
			$metodos = 'METODOS EMPLEADOS: NMX-C-161-ONNCCE-2013, NMX-C-159-ONNCCE-2016, NMX-C156-ONNCCE-2010';
			$this->cell(0,($tam_font_footer - 3),$metodos,1,2,'C');

			$this->cell(0,2,'',1,2,'C');


			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			//Instrumentos
			$tam_font_footer = 6.5; $this->SetFont('Arial','',$tam_font_footer);
			$instrumentos = 'Inventario de'."\n".'instrumentos';
			$tam_instrumentos = $this->GetStringWidth('Inventario de')+5;
			$this->multicell($tam_instrumentos,$tam_font_footer - 2,$instrumentos,1,'C');

			$this->SetXY(($posicion_x + $tam_instrumentos),$posicion_y);
			$cono = 'Cono';
			$tam_cono = $this->GetStringWidth($cono)+15;
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->cell($tam_cono,($tam_font_footer - 2),$cono,1,2,'C');
			$this->cell($tam_cono,($tam_font_footer - 2),utf8_decode($infoFormato['CONO']),1,2,'C');

			$this->SetXY(($posicion_x + $tam_cono),$posicion_y);
			$varilla = 'Varilla';
			$tam_varilla = $this->GetStringWidth($varilla)+15;
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->cell($tam_varilla,($tam_font_footer - 2),$varilla,1,2,'C');
			$this->cell($tam_varilla,($tam_font_footer - 2),utf8_decode($infoFormato['VARILLA']),1,2,'C');


			$this->SetXY(($posicion_x + $tam_varilla),$posicion_y);
			$flexometro = 'Flexometro';
			$tam_flexometro = $this->GetStringWidth($flexometro)+15;
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->cell($tam_flexometro,($tam_font_footer - 2),$flexometro,1,2,'C');
			$this->cell($tam_flexometro,($tam_font_footer - 2),utf8_decode($infoFormato['FLEXOMETRO']),1,2,'C');

			$this->SetXY(($posicion_x + $tam_flexometro),$posicion_y);
			$termo = 'TERMÓMETRO';
			$tam_termo = $this->GetStringWidth($termo)+15;
			$this->cell($tam_termo,($tam_font_footer - 2),utf8_decode($termo),1,2,'C');
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->cell($tam_termo,($tam_font_footer - 2),utf8_decode($infoFormato['TERMOMETRO']),1,2,'C');

			$bandC = 0;
			$bandCi = 0;
			$bandV = 0;
			switch ($infoFormato['tipo_especimen']) {
				case 'CILINDRO':
					$bandC = 1;
					break;
				case 'CUBO':
					$bandCi = 1;
					break;
				case 'VIGAS':
					$bandV = 1;
					break;
			}

			$this->SetXY(($posicion_x + $tam_flexometro + 12),$posicion_y);
			$cili = 'CILINDROS';
			$tam_cili = $this->GetStringWidth($termo)+10;
			$this->cell($tam_cili,($tam_font_footer - 2),utf8_decode($cili),0,0,'C');

			$this->cell($tam_cili-10,($tam_font_footer - 2),'',1,0,'C',$bandC);
			$posicion_x = $this->GetX();


			$cubos = 'CUBOS';
			$tam_cubos = $this->GetStringWidth($termo)+10;
			$this->cell($tam_cubos,($tam_font_footer - 2),utf8_decode($cubos),0,0,'C');
			$this->cell($tam_cubos-10,($tam_font_footer - 2),'',1,0,'C',$bandCi);

			$vigas = 'VIGAS';
			$tam_vigas = $this->GetStringWidth($termo)+10;
			$this->cell($tam_vigas,($tam_font_footer - 2),utf8_decode($vigas),0,0,'C');
			$this->cell($tam_vigas-10,($tam_font_footer - 2),'',1,0,'C',$bandV);
			

			$tam_image = 15;

			$tam_font_footer = 7;
			$this->Ln(12);	
			$tamCelda_ancho = 90;
			$this->SetX((279.4-$tamCelda_ancho)/2);
			$tamCelda_alto = 10;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->cell($tamCelda_ancho,$tamCelda_alto,'','B',2);
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tamCelda_ancho,($tam_font_footer - 3),'LABORATORISTA/ASIGNATARIO',0,0,'C');

			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tamCelda_ancho)/2)-($tam_image/2)),($posicion_y + (($tamCelda_alto)/2))-($tam_image/2),$tam_image,$tam_image);

			/*
			$tamCelda_ancho = 60;
			$this->SetXY($posicion_x + 150,$posicion_y);
			$posicion_x = $this->GetX();
			$this->cell($tamCelda_ancho,$tamCelda_alto,'','B',2);
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tamCelda_ancho,($tam_font_footer - 3),'ENTERADO',0,0,'C');

			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tamCelda_ancho)/2)-($tam_image/2)),($posicion_y + (($tamCelda_alto)/2))-($tam_image/2),$tam_image,$tam_image);
			*/
					
		}
		
		function Footer(){
			$this->SetY(-15);
		    $this->SetFont('Arial','',8);
		    $tam_noPagina = $this->GetStringWidth('Page '.$this->PageNo().'/{nb}');
		    $posicion_x = (279.4 - $tam_noPagina)/2;
		    $this->SetX($posicion_x);
		    $this->Cell($tam_noPagina,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

		    //Clave de validacion
		    $clave = 'FI-05-LCC-02-6.5';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');
			
		}
		//Funcion que crea un nuevo formato

		function CreateNew($infoFormato,$regisFormato,$target_dir){
			$pdf  = new CCH('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($infoFormato,$regisFormato);
			$pdf->Output('F',$target_dir);
			//$pdf->Output();
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
	/*
	$pdf  = new CCH('L','mm','Letter');
	$pdf->AddPage();
	$pdf->putTables();
	$pdf->Output();
	*/
	
	


?>