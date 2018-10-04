<?php 
	//include_once("./../../FPDF/fpdf.php");
	include_once("./../../FPDF/fpdf.php");

	//Formato de campo de cilindros
	class InformeCubos extends fpdf{

		//Variables

		public $arrayCampos;
		public $arrayInfo;
		


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

		function Header()
		{
								//Espacio definido para los logotipos

			//Definimos las dimensiones del logotipo de ema
			$ancho_ema = 50;	$alto_ema = 20;
			$tam_lacocs = 20;
			
			$posicion_x = $this->GetX();

			$this->Image('http://lacocs.montielpalacios.com/SystemData/BackData/Assets/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$this->TextWithDirection($this->GetX(),$this->gety() + 24,utf8_decode('LACOCS S.A. DE C.V.'));	

			$this->Image('http://lacocs.montielpalacios.com/SystemData/BackData/Assets/ema.jpeg',269-$ancho_ema,$this->GetY(),$ancho_ema,$alto_ema);


			//Información de la empresa
			$this->SetY(30);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A. DE C.V.';
			$tam_cell = $this->GetStringWidth($titulo);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo),0,'C');

			$this->Ln(5);

			//Titulo del informe
			$tam_font_tituloInforme = 7.5;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '"INFORME DE PRUEBAS A COMPRESIÓN DE CUBOS DE CONCRETO HIDRÁULICO"';
			$tam_tituloInforme = $this->GetStringWidth($titulo_informe)+130;

			$tam_font_info = 6.5;
			//Fecha
			$this->SetFont('Arial','B',$tam_font_info);
			$direccion_lacocs = '35 NORTE No.3023, UNIDAD HABITACIONAL AQUILES SERDÁN, PUEBLA, PUE.';
			$tam_direccion = $this->GetStringWidth($direccion_lacocs)+6;
			$this->SetX(-$tam_tituloInforme-10);
			$this->Cell($tam_direccion,$tam_font_info - 3,utf8_decode($direccion_lacocs),0,'C');

			//Telefono
			$this->SetX($this->GetX()+20);
			$telefono = 'TELS. 8686-973/ 8686-974';
			$this->Cell($this->GetStringWidth($telefono)+6,$tam_font_info - 3,$telefono,0,'C');

			//FAX
			$this->SetX($this->GetX()+15);
			$FAX = 'FAX. 2315-836';
			$this->Cell($this->GetStringWidth($FAX)+6,$tam_font_info - 3,$FAX,0,'C');

			$this->Ln();

			$this->SetFont('Arial','B',$tam_font_tituloInforme); 
			$this->SetX(-($tam_tituloInforme+10))	;
			$this->Cell($tam_tituloInforme,$tam_font_tituloInforme - 3,utf8_decode($titulo_informe),1,0,'C');

			//---Divide espacios entre el titulo del formato y la información del formato
			$this->Ln($tam_font_tituloInforme - 2);
		}

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.


		/*
			Recordar:
						Quitarle el calculo de los tamaños
		*/
		function putInfo($infoFormato){

			/*
			Lado derecho:
							-Informe No.
							-Este informe sustituye a:
			*/
			$tam_font_right = 7.5;	$this->SetFont('Arial','B',$tam_font_right);

			//Numero del informe
			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;
			$this->SetX(-($tam_informeNo+50));
			$this->Cell($tam_informeNo,$tam_font_right - 3,$informeNo,0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 3,utf8_decode($infoFormato['informeNo']),'B',0,'C');

			$this->Ln($tam_font_right - 2);

			//-Informe al cual sustituye
			$this->SetFont('Arial','B',$tam_font_right);
			$sustituyeInforme = 'ESTE INFORME SUSTITUYE A:';
			$tam_sustituyeInforme = $this->GetStringWidth($sustituyeInforme)+6;
			$this->SetX(-$tam_sustituyeInforme-50);
			$this->Cell($tam_sustituyeInforme,$tam_font_right - 3,$sustituyeInforme,0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 3,'N/A','B',0,'C');

			//--Divide la informacion de la derecha y la izquierda
			$this->Ln($tam_font_right - 2);

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			$tam_font_left = 7.5;	$this->SetFont('Arial','',$tam_font_left);

			//Cuadro con informacion
			$this->SetFont('Arial','B',$tam_font_left);
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['obra']),'B',0);

			$this->Ln($tam_font_left - 2);

			$this->SetFont('Arial','B',$tam_font_left);
			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);

			//Caja de texto
			$this->SetX(50);

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['localizacion']),'B',0);

			$this->Ln($tam_font_left - 2);
			$this->SetFont('Arial','B',$tam_font_left);
			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);

			//Caja de texto
			$this->SetX(50);

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['razonSocial']),'B',0);

			$this->Ln($tam_font_left - 2);

			//Direccion del cliente
			$this->SetFont('Arial','B',$tam_font_left);
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);

			//Caja de texto
			$this->SetX(50);

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['direccion']),'B',0);

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($tam_font_left-1);

		}
		
		function getTamInfo(){

			/*
			Lado derecho:
							-Informe No.
							-Este informe sustituye a:
			*/
			$tam_font_right = 7.5;	$this->SetFont('Arial','B',$tam_font_right);

			//Numero del informe
			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;
			$this->SetX(-($tam_informeNo+50));
			$this->Cell($tam_informeNo,$tam_font_right - 3,$informeNo,0,0,'C');

			//Caja de texto

			$posicion_x = $this->GetX();	//Obtenemos la posicion de inicio de la caja de texto.

			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 3,'','B',0,'C');

			//Calculamos el tamaño de la caja de texto, restando la posicion actual de la x (despues de pintar la celda) y la "posicion_x" que fue la ultima posicion de x que teniamos
			$tam_informe = $this->GetX() - $posicion_x;

			$this->Ln($tam_font_right - 2);

			//-Informe al cual sustituye
			$this->SetFont('Arial','B',$tam_font_right);
			$sustituyeInforme = 'ESTE INFORME SUSTITUYE A:';
			$tam_sustituyeInforme = $this->GetStringWidth($sustituyeInforme)+6;
			$this->SetX(-$tam_sustituyeInforme-50);
			$this->Cell($tam_sustituyeInforme,$tam_font_right - 3,$sustituyeInforme,0,0,'C');

			//Caja de texto

			$posicion_x = $this->GetX();

			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 3,'','B',0,'C');

			$tam_sustituye = $this->GetX() - $posicion_x;

			//--Divide la informacion de la derecha y la izquierda
			$this->Ln($tam_font_right - 2);

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			$tam_font_left = 7.5;	$this->SetFont('Arial','',$tam_font_left);

			//Cuadro con informacion
			$this->SetFont('Arial','B',$tam_font_left);
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);

			$posicion_x = $this->GetX();

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,'','B',0);

			$tam_nomObra = $this->GetX() - $posicion_x;

			$this->Ln($tam_font_left - 2);

			$this->SetFont('Arial','B',$tam_font_left);
			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);

			//Caja de texto
			$this->SetX(50);

			$posicion_x = $this->GetX();

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,'','B',0);

			$tam_localizacion= $this->GetX() - $posicion_x;

			$this->Ln($tam_font_left - 2);
			$this->SetFont('Arial','B',$tam_font_left);
			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);

			//Caja de texto
			$this->SetX(50);

			$posicion_x = $this->GetX();

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,'','B',0);

			$tam_razon = $this->GetX() - $posicion_x;

			$this->Ln($tam_font_left - 2);

			//Direccion del cliente
			$this->SetFont('Arial','B',$tam_font_left);
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);

			//Caja de texto
			$this->SetX(50);

			$posicion_x = $this->GetX();

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,'','B',0);

			$tam_dirCliente = $this->GetX() - $posicion_x;

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($tam_font_left-1);

			$this->arrayInfo = array(
										$tam_informe,
										$tam_sustituye,
										$tam_nomObra,
										$tam_localizacion,
										$tam_razon,
										$tam_dirCliente
								);
		}


		/*
			Funcion para obtener el maximo de caracteres que puede tener una celda
		*/

		function getMaxString($sizeString,$tam){
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
				$count = 1;
				//Realizamos un ciclo iterativo para aumentar la cadena hasta que no queda en el tamaño de la celda y devolver el total de caracteres que puede almacenar la celda
				while ($tam_string < $tam) {
					$string .= 'W';
					$tam_string = $pdf->GetStringWidth($string);
					$count++;
				}
				return $count - 1;
			}
		
		}

		function putCaracInfo($array){
			/*
			Lado derecho:
							-Informe No.
							-Este informe sustituye a:
			*/
			$tam_font_right = 7.5;	$this->SetFont('Arial','B',$tam_font_right);


			//Numero del informe
			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;
			$this->SetX(-($tam_informeNo+50));
			$this->Cell($tam_informeNo,$tam_font_right - 3,$informeNo,0,0,'C');



			//Caja de texto
			$this->SetFont('Arial','',$tam_font_right);

			$this->Cell(0,$tam_font_right - 3,$this->getMaxString($tam_font_right,$array[0]),'B',0,'C');

			$this->Ln($tam_font_right - 2);

			//-Informe al cual sustituye
			$this->SetFont('Arial','B',$tam_font_right);
			$sustituyeInforme = 'ESTE INFORME SUSTITUYE A:';
			$tam_sustituyeInforme = $this->GetStringWidth($sustituyeInforme)+6;
			$this->SetX(-$tam_sustituyeInforme-50);
			$this->Cell($tam_sustituyeInforme,$tam_font_right - 3,$sustituyeInforme,0,0,'C');

			//Caja de texto

			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 3,$this->getMaxString($tam_font_right,$array[1]),'B',0,'C');

			//--Divide la informacion de la derecha y la izquierda
			$this->Ln($tam_font_right - 2);

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			$tam_font_left = 7.5;	$this->SetFont('Arial','',$tam_font_left);

			//Cuadro con informacion
			$this->SetFont('Arial','B',$tam_font_left);
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,$this->getMaxString($tam_font_right,$array[2]),'B',0);

			$this->Ln($tam_font_left - 2);

			$this->SetFont('Arial','B',$tam_font_left);
			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);

			//Caja de texto
			$this->SetX(50);

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,$this->getMaxString($tam_font_left,$array[3]),'B',0);

			$this->Ln($tam_font_left - 2);
			$this->SetFont('Arial','B',$tam_font_left);
			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);

			//Caja de texto
			$this->SetX(50);

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,$this->getMaxString($tam_font_left,$array[4]),'B',0);

			$this->Ln($tam_font_left - 2);

			//Direccion del cliente
			$this->SetFont('Arial','B',$tam_font_left);
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);

			//Caja de texto
			$this->SetX(50);

			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 3,$this->getMaxString($tam_font_left,$array[5]),'B',0);

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($tam_font_left-1);
		}

		function demo(){
			$pdf  = new InformeCubos('L','mm','Letter');
			$pdf->AddPage();
			$pdf->getTamInfo();
			$pdf->calculateSize();
			$array_info = $pdf->arrayInfo;
			$array_campos = $pdf->arrayCampos;
			unset($pdf);
			$pdf  = new InformeCubos('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putCaracInfo($array_info);
			$pdf->putCaracCampos($array_campos);
			$pdf->Output();
		}

		function putTables($infoFormato,$regisFormato){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY();

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			
			$resistencia = 'RESISTENCIA';	
			$tam_resistencia = $this->GetStringWidth($resistencia)+3;

			$this->SetY($posicion_y);
			$this->SetX(-($tam_resistencia + 10));

			$posicion_x = $this->GetX();
			$this->multicell($tam_resistencia,(1.5*($tam_font_head - 3)),utf8_decode('% DE'."\n".$resistencia),1,'C');

			$proyecto = 'PROYECTO';
			$tam_proyecto = $this->GetStringWidth($proyecto)+3;

			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $tam_proyecto);

			$posicion_x = $this->GetX();
			$this->multicell($tam_proyecto,$tam_font_head - 3,utf8_decode("F'c"."\n".'PROYECTO'."\n".'(kg/cm²)'),1,'C');


			//----------------------SE MUEVEN FUENTES---------------------

			//Resistencia a compresion
			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);

			$resis_compresion  = 'RESISTENCIA A';
			$tam_resis = $this->GetStringWidth($resis_compresion)+10;

			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $tam_resis);

			$posicion_x = $this->GetX();
			$this->multicell($tam_resis,0.75*($tam_font_head - 2.5),utf8_decode($resis_compresion."\n".'COMPRESIÓN'),1,'C');

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			//Abajo de resistencia a compresion
			$kgcm = 'kg/cm²';
			$tam_kgcm = $tam_resis/2;
			//$aux_posy = $posicion_y;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	

			$this->SetX($posicion_x + ($tam_resis/2));
			$this->multicell($tam_resis/2,1.5*($tam_font_head - 3),utf8_decode($kgcm),1,'C');

			$mp = 'MPa';
			$tam_mp = $tam_resis/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));
			$this->SetX($posicion_x);
			$this->multicell($tam_resis/2,1.5*($tam_font_head - 3),utf8_decode($mp),1,'C');

			//Carga
			$carga = 'CARGA';
			$tam_carga = $this->GetStringWidth($carga) + 12;
			$this->SetY($posicion_y);	$this->SetX($posicion_x - $tam_carga);	$posicion_x = ($this->GetX());
			$this->cell($tam_carga,1.5*($tam_font_head - 3),$carga,1,0,'C');

			//Abajo de carga
			$kg = '(kg)';
			$tam_kg = $tam_carga/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x + ($tam_carga/2));
			$this->cell($tam_carga/2,1.5*($tam_font_head - 3),$kg,1,0,'C');

			$kN = 'kN';
			$tam_kN = $tam_carga/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x);
			$this->cell($tam_carga/2,1.5*($tam_font_head - 3),$kN,1,0,'C');

			//--- SE MUEVE LA FUENTE PARA AJUSTAR AL TAMAÑO

			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);

			//Abajo de Escpecimenes
			$area = 'AREA EN';
			$tam_area = $this->GetStringWidth($area) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_area); $posicion_x = $this->GetX();
			$this->multicell($tam_area,0.75*($tam_font_head - 2.5),utf8_decode($area."\n".'cm²'),1,'C');

			$lado2 = 'LADO 2 EN';
			$tam_lado2 = $this->GetStringWidth($lado2) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_lado2); $posicion_x = $this->GetX();
			$this->multicell($tam_lado2,0.75*($tam_font_head - 2.5),$lado2."\n".'cm',1,'C');

			$lado1 = 'LADO 1 EN';
			$tam_lado1 = $this->GetStringWidth($lado1) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_lado1); $posicion_x = $this->GetX();
			$this->multicell($tam_lado1,0.75*($tam_font_head - 2.5),$lado1."\n".'cm',1,'C');

			$edad = 'EDAD EN';
			$tam_edad = $this->GetStringWidth($edad) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_edad); $posicion_x = $this->GetX();
			$this->multicell($tam_edad,0.75*($tam_font_head - 2.5),utf8_decode($edad."\n".'DÍAS'),1,'C');

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);


			$rev = 'REV. cm';
			$tam_rev = $this->GetStringWidth($rev) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x - $tam_rev);	$posicion_x = $this->GetX();
			$this->cell($tam_rev,1.5*($tam_font_head - 3),$rev,1,2,'C');

			

			//Especimenes
			$especimenes = 'ESPECIMENES';
			$tam_especimenes = ($tam_area + $tam_lado1 + $tam_lado2 + $tam_edad + $tam_rev);
			$this->SetY($posicion_y);	$this->SetX($posicion_x); 
			$this->cell($tam_especimenes,1.5*($tam_font_head - 3),$especimenes,1,2,'C');

			//Clave
			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave) + 25;
			$this->SetY($posicion_y);	$this->SetX($posicion_x - $tam_clave);	$posicion_x = $this->GetX();
			$this->cell($tam_clave,1.5*(2*($tam_font_head) - 6),$clave,1,0,'C');

			//Fecha de ensaye
			$fecha = 'FECHA DE ENSAYE';
			$tam_fecha = $this->GetStringWidth($fecha) + 3;
			$this->SetX($posicion_x - $tam_fecha); $posicion_x = $this->GetX();
			$this->cell($tam_fecha,1.5*(2*($tam_font_head) - 6),$fecha,1,0,'C');

			//Elemento
			$elemento = 'ELEMENTO MUESTREADO';
			$tam_ele = $posicion_x - 10;
			$this->SetX(10);
			$this->cell($tam_ele,1.5*(2*($tam_font_head) - 6),$elemento,1,2,'C');

			$this->ln(1);

			//Definimos el array con los tamaños de cada celda para crear las duplas

			$array_campo = 	array(

									$tam_resistencia,
									$tam_proyecto,
									$tam_kgcm,
									$tam_mp,
									$tam_kg,
									$tam_kN,
									$tam_area,
									$tam_lado2,
									$tam_lado1,
									$tam_edad,
									$tam_rev,
									$tam_clave,
									$tam_fecha

							);


			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY(); 
			$num_rows = 0;
			//Guardamos el ultimo valor porque es el mas dificil
			$array_aux = array_pop($regisFormato);
			foreach ($regisFormato as $registro) {
				$this->SetX(-10); $posicion_x = $this->GetX();
				$j=0;
				foreach ($registro as $campo) {
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					if($j < sizeof($array_campo)){
						$this->cell($array_campo[$j],$tam_font_head - 3,utf8_decode($campo),1,0,'C');
					}
					$j++;
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<8){
				for ($i=0; $i < (8-$num_rows); $i++){
				//Definimos la posicion de X para tomarlo como referencia
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < sizeof($array_campo); $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					if($j < sizeof($array_campo)){
						$this->cell($array_campo[$j],$tam_font_head - 3,'',1,0,'C');
					}
					
				}	
				$this->Ln();
				}
			}
			//Guardamos la posicion en donde se quedo la Y para comparar con el tamaño de la celda del "Elemento"
			$endDown_table = $this->GetY();
			
			//Modificamos el valor de Y para empezar en el inicio de la tabla
			$this->SetY($ele_posicion_y);
			
			$this->multicell($tam_ele,$tam_font_head - 3,utf8_decode($array_aux),'L,T');
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($tam_font_head - 3));
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_ele,$tam_font_head - 3,'','L',2);
				}
			}

			
				
			//Separados
			$this->cell(0,$tam_font_head - 3,'',1,2);
			$this->cell(0,1.5*($tam_font_head - 3),'',1,2);

			
			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY();
		
			for ($i=0; $i < 8; $i++){
				//Definimos la posicion de X para tomarlo como referencia
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < sizeof($array_campo); $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					if($j < sizeof($array_campo)){
					$this->cell($array_campo[$j],$tam_font_head - 3,'',1,0,'C');
					}
				}	
				$this->Ln();
			}
			//Guardamos la posicion en donde se quedo la Y para comparar con el tamaño de la celda del "Elemento"
			$endDown_table = $this->GetY();
			
			//Modificamos el valor de Y para empezar en el inicio de la tabla
			$this->SetY($ele_posicion_y);
			//Insertamos las celdas de "Elemento muestreado"
			$this->multicell($tam_ele,$tam_font_head - 3,'','L,T');
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($tam_font_head - 3));
				for ($i=0; $i < $num_iteraciones - 1; $i++) { 
					$this->cell($tam_ele,$tam_font_head - 3,'','L',2);
				}
				$this->cell($tam_ele,$tam_font_head - 3,'','L,B',2);
			}

			$this->cell(0,$tam_font_head - 3,'',1,2);

			
			$tam_footer = 20;
			
			$tam_font_footer = 7;	$this->SetFont('Arial','B',$tam_font_footer);
			

			//Observaciones
			$observaciones = 'OBSERVACIONES:';
			
			$this->cell($this->GetStringWidth($observaciones)+2,2*($tam_font_footer - 4),$observaciones,'L,T,B',0);
			$this->SetFont('Arial','',$tam_font_footer);
			$this->cell(0,2*($tam_font_footer - 4),utf8_decode($infoFormato['observaciones']),'R,T,B',2);

			$this->SetFont('Arial','B',$tam_font_footer);
			//Metodos empleados
			$metodos = 'METODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			//$this->multicell(0,($tam_font_head - 2.5),$metodos,1,2);

			//Incertidumbre
			$incertidumbre = 'INCERTIDUMBRE';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+20;
			$this->SetX(-($tam_incertidumbre + 10));
			//Guardamos las posiciones de esa linea
			$posicion_x = $this->GetX();	$posicion_y = $this->GetY();

			$this->multicell($tam_incertidumbre,($tam_font_footer - 3),$incertidumbre."\n".'PENDIENTE',1,'C');

			//Metodos empleados
			$this->SetY($posicion_y);
			$metodos = 'METODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			$tam_metodos = $this->GetStringWidth($metodos)+3;
			$this->multicell($posicion_x -10,($tam_font_footer - 3),$metodos,1,2);

			$this->Ln(1);

			

			$tam_image = 20;
			$tam_font_footer = 8; $this->SetFont('Arial','B',$tam_font_footer);
			
			$tam_boxElaboro = 259/3;	$tam_first = 12.5; $tam_second = 12.5;
			$posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('LAURA CASTILLO DE LA ROSA')/2),$this->gety() - 12,utf8_decode('LAURA CASTILLO DE LA ROSA'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			

			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	

			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('M en I. MARCO ANTONIO CERVANTES M.')/2),$this->gety() - 12,utf8_decode('M en I. MARCO ANTONIO CERVANTES M.'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->SetFont('Arial','',$tam_font_footer);



			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);

			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tam_boxElaboro,$tam_first,'Recibe','L,T,R',2,'C');
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX();
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NOMBRE DE QUIEN RECIBE')/2),$this->gety() - 3,utf8_decode('NOMBRE DE QUIEN RECIBE'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->Ln(0);

			$tam_font_footer = 6; $this->SetFont('Arial','',$tam_font_footer);
			$mensaje1 = 'ESTE INFORME DE RESULTADOS SE REFIERE EXCLUSIVAMENTE AL ENSAYE REALIZADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS, Y SOLO TIENE VALIDEZ SI NO PRESENTA TACHADURAS O ENMIENDAS';
			$this-> multicell(0,($tam_font_footer - 2.5),utf8_decode($mensaje1),0,2);
			


			
		}

		function putCaracCampos($array){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY();

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			
			$resistencia = 'RESISTENCIA';	
			$tam_resistencia = $this->GetStringWidth($resistencia)+3;

			$this->SetY($posicion_y);
			$this->SetX(-($tam_resistencia + 10));

			$posicion_x = $this->GetX();
			$this->multicell($tam_resistencia,(1.5*($tam_font_head - 3)),utf8_decode('% DE'."\n".$resistencia),1,'C');

			$proyecto = 'PROYECTO';
			$tam_proyecto = $this->GetStringWidth($proyecto)+3;

			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $tam_proyecto);

			$posicion_x = $this->GetX();
			$this->multicell($tam_proyecto,$tam_font_head - 3,utf8_decode("F'c"."\n".'PROYECTO'."\n".'(kg/cm²)'),1,'C');


			//----------------------SE MUEVEN FUENTES---------------------

			//Resistencia a compresion
			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);

			$resis_compresion  = 'RESISTENCIA A';
			$tam_resis = $this->GetStringWidth($resis_compresion)+10;

			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $tam_resis);

			$posicion_x = $this->GetX();
			$this->multicell($tam_resis,0.75*($tam_font_head - 2.5),utf8_decode($resis_compresion."\n".'COMPRESIÓN'),1,'C');

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			//Abajo de resistencia a compresion
			$kgcm = 'kg/cm²';
			$tam_kgcm = $tam_resis/2;
			//$aux_posy = $posicion_y;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	

			$this->SetX($posicion_x + ($tam_resis/2));
			$this->multicell($tam_resis/2,1.5*($tam_font_head - 3),utf8_decode($kgcm),1,'C');

			$mp = 'MPa';
			$tam_mp = $tam_resis/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));
			$this->SetX($posicion_x);
			$this->multicell($tam_resis/2,1.5*($tam_font_head - 3),utf8_decode($mp),1,'C');

			//Carga
			$carga = 'CARGA';
			$tam_carga = $this->GetStringWidth($carga) + 12;
			$this->SetY($posicion_y);	$this->SetX($posicion_x - $tam_carga);	$posicion_x = ($this->GetX());
			$this->cell($tam_carga,1.5*($tam_font_head - 3),$carga,1,0,'C');

			//Abajo de carga
			$kg = '(kg)';
			$tam_kg = $tam_carga/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x + ($tam_carga/2));
			$this->cell($tam_carga/2,1.5*($tam_font_head - 3),$kg,1,0,'C');

			$kN = 'kN';
			$tam_kN = $tam_carga/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x);
			$this->cell($tam_carga/2,1.5*($tam_font_head - 3),$kN,1,0,'C');

			//--- SE MUEVE LA FUENTE PARA AJUSTAR AL TAMAÑO

			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);

			//Abajo de Escpecimenes
			$area = 'AREA EN';
			$tam_area = $this->GetStringWidth($area) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_area); $posicion_x = $this->GetX();
			$this->multicell($tam_area,0.75*($tam_font_head - 2.5),utf8_decode($area."\n".'cm²'),1,'C');

			$lado2 = 'LADO 2 EN';
			$tam_lado2 = $this->GetStringWidth($lado2) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_lado2); $posicion_x = $this->GetX();
			$this->multicell($tam_lado2,0.75*($tam_font_head - 2.5),$lado2."\n".'cm',1,'C');

			$lado1 = 'LADO 1 EN';
			$tam_lado1 = $this->GetStringWidth($lado1) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_lado1); $posicion_x = $this->GetX();
			$this->multicell($tam_lado1,0.75*($tam_font_head - 2.5),$lado1."\n".'cm',1,'C');

			$edad = 'EDAD EN';
			$tam_edad = $this->GetStringWidth($edad) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_edad); $posicion_x = $this->GetX();
			$this->multicell($tam_edad,0.75*($tam_font_head - 2.5),utf8_decode($edad."\n".'DÍAS'),1,'C');

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);


			$rev = 'REV. cm';
			$tam_rev = $this->GetStringWidth($rev) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x - $tam_rev);	$posicion_x = $this->GetX();
			$this->cell($tam_rev,1.5*($tam_font_head - 3),$rev,1,2,'C');

			

			//Especimenes
			$especimenes = 'ESPECIMENES';
			$tam_especimenes = ($tam_area + $tam_lado1 + $tam_lado2 + $tam_edad + $tam_rev);
			$this->SetY($posicion_y);	$this->SetX($posicion_x); 
			$this->cell($tam_especimenes,1.5*($tam_font_head - 3),$especimenes,1,2,'C');

			//Clave
			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave) + 25;
			$this->SetY($posicion_y);	$this->SetX($posicion_x - $tam_clave);	$posicion_x = $this->GetX();
			$this->cell($tam_clave,1.5*(2*($tam_font_head) - 6),$clave,1,0,'C');

			//Fecha de ensaye
			$fecha = 'FECHA DE ENSAYE';
			$tam_fecha = $this->GetStringWidth($fecha) + 3;
			$this->SetX($posicion_x - $tam_fecha); $posicion_x = $this->GetX();
			$this->cell($tam_fecha,1.5*(2*($tam_font_head) - 6),$fecha,1,0,'C');

			//Elemento
			$elemento = 'ELEMENTO MUESTREADO';
			$tam_ele = $posicion_x - 10;
			$this->SetX(10);
			$this->cell($tam_ele,1.5*(2*($tam_font_head) - 6),$elemento,1,2,'C');

			$this->ln(1);			

			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY(); 
			$band = 0;
			$num_rows = 0;
			//Guardamos el ultimo valor porque es el mas dificil
			for($k=0;$k<7;$k++) {
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < 13; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array[$j]); $posicion_x = $this->GetX();
					/*
					$a = $this->getMaxString($tam_font_head,$array[$j]);
					$string = 'W'; //Definimos la cadena
					//El contador lo inicializamos en 1, porque siguiendo el razonamiento del algoritmo, esa es la longitud que ya tenemos de la cadena, entonces falta llegar al limite definido por el numero de caracteres que se obtuvo del otro algoritmo
					for ($i=1; $i < $a; $i++) { 
						$string .='W';
					}*/
					$this->cell($array[$j],$tam_font_head - 3,$this->getMaxString($tam_font_head,$array[$j]),1,0,'C');
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<7){
				for ($i=0; $i < (7-$num_rows); $i++){
				//Definimos la posicion de X para tomarlo como referencia
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < 13; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					
					$this->cell($array[$j],$tam_font_head - 3,'',1,0,'C');
					
					
				}	
				$this->Ln();
				}
			}
			$endDown_table = $this->GetY();
			$this->SetY($ele_posicion_y);

			$this->multicell($tam_ele,$tam_font_head - 3,$this->getMaxString($tam_font_head,$array[13]*6.6),'L,T');
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($tam_font_head - 3));
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_ele,$tam_font_head - 3,'','L',2);
				}
			}

			
			/*
			$a = $this->getMaxString($tam_font_head,$array[14]);
			$string = 'W'; //Definimos la cadena
			//El contador lo inicializamos en 1, porque siguiendo el razonamiento del algoritmo, esa es la longitud que ya tenemos de la cadena, entonces falta llegar al limite definido por el numero de caracteres que se obtuvo del otro algoritmo
			for ($i=1; $i < $a; $i++) { 
				$string .='W';
			}*/
			$this->cell(0,$tam_font_head - 2,$this->getMaxString($tam_font_head,$array[14]),1,2,'C');

			
			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY();
		
			for($k=0;$k<7;$k++) {
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < 13; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array[$j]); $posicion_x = $this->GetX();
					/*
					$a = $this->getMaxString($tam_font_head,$array[$j]);
					$string = 'W'; //Definimos la cadena
					//El contador lo inicializamos en 1, porque siguiendo el razonamiento del algoritmo, esa es la longitud que ya tenemos de la cadena, entonces falta llegar al limite definido por el numero de caracteres que se obtuvo del otro algoritmo
					for ($i=1; $i < $a; $i++) { 
						$string .='W';
					}*/
					$this->cell($array[$j],$tam_font_head - 3,$this->getMaxString($tam_font_head,$array[$j]),1,0,'C');
				}
				$num_rows++;
				$this->Ln();
			}
			$endDown_table = $this->GetY();
			$this->SetY($ele_posicion_y);

			$this->multicell($tam_ele,$tam_font_head - 3,$this->getMaxString($tam_font_head,$array[13]*6.6),'L,T');
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($tam_font_head - 3));
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_ele,$tam_font_head - 3,'','L',2);
				}
			}

			$this->cell(0,$tam_font_head - 3,'',1,2);
			
			$tam_font_footer = 7;	$this->SetFont('Arial','B',$tam_font_footer);
			

			//Observaciones
			$observaciones = 'OBSERVACIONES:';
			
			$this->cell($this->GetStringWidth($observaciones)+2,2*($tam_font_footer - 4),$observaciones,'L,T,B',0);
			$this->SetFont('Arial','',$tam_font_footer);
			/*
			$a = $this->getMaxString($tam_font_footer,$array[14]);
					$string = 'W'; //Definimos la cadena
					//El contador lo inicializamos en 1, porque siguiendo el razonamiento del algoritmo, esa es la longitud que ya tenemos de la cadena, entonces falta llegar al limite definido por el numero de caracteres que se obtuvo del otro algoritmo
					for ($i=1; $i < $a; $i++) { 
						$string .='W';
					}
					*/
			
			$this->cell(0,2*($tam_font_footer - 4),$this->getMaxString($tam_font_footer,$array[15]),'R,T,B',2);
			$this->SetFont('Arial','B',$tam_font_footer);
			//Metodos empleados
			$metodos = 'METODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			
			//Incertidumbre
			$incertidumbre = 'INCERTIDUMBRE';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+20;
			$this->SetX(-($tam_incertidumbre + 10));
			//Guardamos las posiciones de esa linea
			$posicion_x = $this->GetX();	$posicion_y = $this->GetY();
			/*
			$a = $this->getMaxString($tam_font_footer,$tam_incertidumbre);
					$string = 'W'; //Definimos la cadena
					//El contador lo inicializamos en 1, porque siguiendo el razonamiento del algoritmo, esa es la longitud que ya tenemos de la cadena, entonces falta llegar al limite definido por el numero de caracteres que se obtuvo del otro algoritmo
					for ($i=1; $i < $a; $i++) { 
						$string .='W';
					}*/
			$this->cell($tam_incertidumbre,($tam_font_footer - 3),$incertidumbre,1,2,'C');
			$this->cell($tam_incertidumbre,($tam_font_footer - 3),$this->getMaxString($tam_font_footer,$array[16]),1,0,'C');

			//Metodos empleados
			$this->SetY($posicion_y);
			$metodos = 'METODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			$tam_metodos = $this->GetStringWidth($metodos)+3;
			$this->multicell($posicion_x -10,($tam_font_footer - 3),$metodos,1,2);

			$this->Ln(1);

			

			$tam_image = 20;
			$tam_font_footer = 8; $this->SetFont('Arial','B',$tam_font_footer);
			
			$tam_boxElaboro = 259/3;	$tam_first = 12.5; $tam_second = 12.5;
			$posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('LAURA CASTILLO DE LA ROSA')/2),$this->gety() - 12,utf8_decode('LAURA CASTILLO DE LA ROSA'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			

			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	

			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('M en I. MARCO ANTONIO CERVANTES M.')/2),$this->gety() - 12,utf8_decode('M en I. MARCO ANTONIO CERVANTES M.'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->SetFont('Arial','',$tam_font_footer);



			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);

			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tam_boxElaboro,$tam_first,'Recibe','L,T,R',2,'C');
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX();
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NOMBRE DE QUIEN RECIBE')/2),$this->gety() - 3,utf8_decode('NOMBRE DE QUIEN RECIBE'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->Ln(0);

			$tam_font_footer = 6; $this->SetFont('Arial','',$tam_font_footer);
			$mensaje1 = 'ESTE INFORME DE RESULTADOS SE REFIERE EXCLUSIVAMENTE AL ENSAYE REALIZADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS, Y SOLO TIENE VALIDEZ SI NO PRESENTA TACHADURAS O ENMIENDAS';
			$this-> multicell(0,($tam_font_footer - 2.5),utf8_decode($mensaje1),0,2);
			



			
		}

		function calculateSize(){
			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);

			$resistencia = 'RESISTENCIA';	
			$tam_resistencia = $this->GetStringWidth($resistencia)+3;
			

			$proyecto = 'PROYECTO';
			$tam_proyecto = $this->GetStringWidth($proyecto)+3;
			
			//----------------------SE MUEVEN FUENTES---------------------

			//Resistencia a compresion
			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);
			$resis_compresion  = 'RESISTENCIA A';
			$tam_resis = $this->GetStringWidth($resis_compresion)+10;
			

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
		
			$kgcm = 'kg/cm²';
			$tam_kgcm = $tam_resis/2;

			$mp = 'MPa';
			$tam_mp = $tam_resis/2;
	

			//Carga
			$carga = 'CARGA';
			$tam_carga = $this->GetStringWidth($carga) + 12;

			//Abajo de carga
			$kg = '(kg)';
			$tam_kg = $tam_carga/2;
			

			$kN = 'kN';
			$tam_kN = $tam_carga/2;

			//--- SE MUEVE LA FUENTE PARA AJUSTAR AL TAMAÑO

			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);

			//Abajo de Escpecimenes
			$area = 'AREA EN';
			$tam_area = $this->GetStringWidth($area) + 3;


			$lado2 = 'LADO 2 EN';
			$tam_lado2 = $this->GetStringWidth($lado2) + 3;

			$lado1 = 'LADO 1 EN';
			$tam_lado1 = $this->GetStringWidth($lado1) + 3;

			$edad = 'EDAD EN';
			$tam_edad = $this->GetStringWidth($edad) + 3;


			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);


			$rev = 'REV. cm';
			$tam_rev = $this->GetStringWidth($rev) + 3;


			

			//Especimenes
			$especimenes = 'ESPECIMENES';
			$tam_especimenes = ($tam_area + $tam_lado1 + $tam_lado2 + $tam_edad + $tam_rev);

			//Clave
			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave) + 25;


			//Fecha de ensaye
			$fecha = 'FECHA DE ENSAYE';
			$tam_fecha = $this->GetStringWidth($fecha) + 3;

			//Elemento
			$elemento = 'ELEMENTO MUESTREADO';

			$posicion_x = 	$tam_resistencia +
							$tam_proyecto +
							$tam_kgcm +
							$tam_mp +
							$tam_kg +
							$tam_kN +
							$tam_area +
							$tam_lado2 +
							$tam_lado1 +
							$tam_edad +
							$tam_rev +
							$tam_clave +
							$tam_fecha +
							10;

			$this->SetX($posicion_x);							
			$this->Cell(0,($tam_font_head+5)/2,'','L,T,R',0,'C');
			$tam_ele = $this->GetX() - $posicion_x;
			$this->ln(0);

			$tam_font_footer = 7;	$this->SetFont('Arial','',$tam_font_footer);

			//Separador de las tablas
			$tam_separador = 259;

			//Observaciones
			$observaciones = 'OBSERVACIONES:';
			
			$this->cell($this->GetStringWidth($observaciones)+2,2*($tam_font_footer - 4),$observaciones,'L,T,B',0);
			$tam_observaciones = 259-($this->GetStringWidth($observaciones)+2);
			
			$incertidumbre = 'INCERTIDUMBRE';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+20;
		

			$array_campo = 	array(

									$tam_resistencia,
									$tam_proyecto,
									$tam_kgcm,
									$tam_mp,
									$tam_kg,
									$tam_kN,
									$tam_area,
									$tam_lado2,
									$tam_lado1,
									$tam_edad,
									$tam_rev,
									$tam_clave,
									$tam_fecha,
									$tam_ele,
									$tam_separador,
									$tam_observaciones,
									$tam_incertidumbre

							);
			$this->arrayCampos = $array_campo;
		}

		

		function Footer(){
			$this->SetY(-15);
		    $this->SetFont('Arial','',8);
		    $tam_noPagina = $this->GetStringWidth('Page '.$this->PageNo().'/{nb}');
		    $posicion_x = (279.4 - $tam_noPagina)/2;
		    $this->SetX($posicion_x);
		    $this->Cell($tam_noPagina,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

		    //Clave de validacion
		    $clave = 'PENDIENTE';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');

		}

		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato,$target_dir){
			$pdf  = new InformeCubos('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($infoFormato,$regisFormato);
			//$pdf->Output('F',$target_dir);
			$pdf->Output();
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
	$pdf  = new informeCilindros('L','mm','Letter');
			$pdf->AddPage();

			$pdf->putTables();
			$pdf->Output();*/

	


?>