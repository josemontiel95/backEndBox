<?php 
	//include_once("./../../FPDF/fpdf.php");
	include_once("./../FPDF/fpdf.php");
	//Formato de campo de cilindros
	class InformeVigas extends fpdf{
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
			//$this->SetX(-($ancho_ema + 10));
			//$this->Image('ema.jpeg',null,null,$ancho_ema,$alto_ema);
			$this->cell(0,20,'',1,2);
			//Información de la empresa
			$tam_font_titulo = 8;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A DE C.V';
			$tam_cell = $this->GetStringWidth($titulo);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo),0,'C');
			$this->Ln(5);
			//Titulo del informe
			$tam_font_tituloInforme = 8.5;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '" INFORME DE ENSAYE DE LA RESISTENCIA A LA FLEXION EN VIGAS, CON CARGA EN  LOS TERCIOS DEL CLARO"';
			$tam_tituloInforme = $this->GetStringWidth($titulo_informe)+3;

			$tam_font_info = 6.5;
			//Direccion
			$this->SetFont('Arial','',$tam_font_info);
			$direccion_lacocs = '35 NORTE No.3023, UNIDAD HABITACIONAL AQUILES SERDAN, PUEBLA, PUE.  TEL: 01 222 2315836, 8686973, 8686974.';
			$tam_direccion = $this->GetStringWidth($direccion_lacocs)+6;
			$this->SetX((279-$tam_direccion)/2);
			$this->Cell($tam_direccion,$tam_font_info - 3,$direccion_lacocs,0,'C');
			
			$this->Ln(4);

			$this->SetFont('Arial','B',$tam_font_tituloInforme); 
			$this->SetX((279-$tam_tituloInforme)/2)	;
			$this->Cell($tam_tituloInforme,$tam_font_tituloInforme - 3,utf8_decode($titulo_informe),0,0,'C');
			//---Divide espacios entre el titulo del formato y la información del formato
			$this->Ln($tam_font_tituloInforme - 2);
		}
		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.
		function putInfo(){
			/*
			Lado derecho:
							-Informe No.
							-Este informe sustituye a:
			*/
			$tam_font_right = 7.5;	$this->SetFont('Arial','B',$tam_font_right);
			//Numero del informe
			/*
			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;
			$this->SetX(-($tam_informeNo+50));
			$this->Cell($tam_informeNo,$tam_font_right - 3,$informeNo,0,0,'C');
			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');
			$this->Ln($tam_font_right - 2);
			//-Informe al cual sustituye
			$sustituyeInforme = 'ESTE INFORME SUSTITUYE A:';
			$tam_sustituyeInforme = $this->GetStringWidth($sustituyeInforme)+6;
			$this->SetX(-$tam_sustituyeInforme-50);
			$this->Cell($tam_sustituyeInforme,$tam_font_right - 3,$sustituyeInforme,0,0,'C');
			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');
			//--Divide la informacion de la derecha y la izquierda
			$this->Ln($tam_font_right - 1);*/
			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/
			$tam_linea_info = 135;
			$tam_font_left = 7;	$this->SetFont('Arial','',$tam_font_left);
			//Cuadro con informacion
			$obra = 'NOMBRE DE LA OBRA:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_linea_info,$tam_font_left - 3,'DUMMY','B',0);

			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;
			$this->SetX(-($tam_informeNo+50));
			$this->Cell($tam_informeNo,$tam_font_right - 3,$informeNo,0,0,'C');
			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');
			$this->Ln($tam_font_right - 2);

			$locObra = 'LOCALIZACIÓND DE LA OBRA:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_linea_info,$tam_font_left - 3,'DUMMY','B',0);
			
			$fechaEnsaye = 'FECHA DE ENSAYE:';
			$tam_fechaEnsaye = $this->GetStringWidth($fechaEnsaye)+6;
			$this->SetX(-($tam_fechaEnsaye+50));
			$this->Cell($tam_fechaEnsaye,$tam_font_right - 3,$fechaEnsaye,0,0,'C');
			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');
			$this->Ln($tam_font_right - 2);

			$nomCli = 'NOMBRE DEL CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_linea_info,$tam_font_left - 3,'DUMMY','B',0);
			
			$tipoConcreto = 'TIPO DE CONCRETO:';
			$tam_tipoConcreto = $this->GetStringWidth($tipoConcreto)+6;
			$this->SetX(-($tam_tipoConcreto+50));
			$this->Cell($tam_tipoConcreto,$tam_font_right - 3,$tipoConcreto,0,0,'C');
			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');
			$this->Ln($tam_font_right - 2);

			//Direccion del cliente
			$dirCliente = 'DIRECCIÓN DEL CLINTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_linea_info,$tam_font_left - 3,'DUMMY','B',0);
			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$mrProyecto = 'MR DE PROYECTO:';
			$tam_mrProyecto = $this->GetStringWidth($mrProyecto)+6;
			$this->SetX(-($tam_mrProyecto+50));
			$this->Cell($tam_mrProyecto,$tam_font_right - 3,$mrProyecto,0,0,'C');
			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');
			$this->Ln($tam_font_right - 2);

			//Direccion del cliente
			$eleColado = 'ELEMENTO COLADO::';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_linea_info,$tam_font_left - 3,'DUMMY','B',0);

			$infoSustituye = 'ESTE INFORME SUSTITUYE A:';
			$tam_infoSustituye = $this->GetStringWidth($infoSustituye)+6;
			$this->SetX(-($tam_infoSustituye+50));
			$this->Cell($tam_infoSustituye,$tam_font_right - 3,$infoSustituye,0,0,'C');
			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');

			$this->Ln(8);
		}
		function putTables(){
			$tam_font_head = 8;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave


			$iden = 'Identificacion de la ';
			$tam_iden = $this->GetStringWidth($iden)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_iden,($tam_font_head+5)/2,$iden,'L,T,R',2,'C');
			$this->Cell($tam_iden,($tam_font_head+5)/2,'muestra','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_iden,$posicion_y);
			$fechaColado = 'Fecha de';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+6;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_fechaColado,($tam_font_head+5)/2,$fechaColado,'L,T,R',2,'C');
			$this->Cell($tam_fechaColado,($tam_font_head+5)/2,'Colado','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_fechaColado,$posicion_y);
			$edad = 'Edad de';
			$tam_edad = $this->GetStringWidth($edad)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_edad,($tam_font_head+5)/3,$edad,'L,T,R',2,'C');
			$this->Cell($tam_edad,($tam_font_head+5)/3,'Ensaye','L,R',2,'C');
			$this->Cell($tam_edad,($tam_font_head+5)/3,utf8_decode('(días)'),'L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_edad,$posicion_y);
			$apoyos = 'Lijado/cuero';
			$tam_apoyos = $this->GetStringWidth($apoyos)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_apoyos,($tam_font_head+5)/3,'Puntos de','L,T,R',2,'C');
			$this->Cell($tam_apoyos,($tam_font_head+5)/3,utf8_decode('apoyo'),'L,R',2,'C');
			$this->Cell($tam_apoyos,($tam_font_head+5)/3,'Lijado/cuero','L,B,R',0,'C');
			
			$this->SetXY($posicion_x+$tam_apoyos,$posicion_y);
			$condiCurado = 'Condiciones de';
			$tam_condiCurado = $this->GetStringWidth($condiCurado)+8;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_condiCurado,($tam_font_head+5)/4,$condiCurado,'L,T,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_head+5)/4,utf8_decode('curado y'),'L,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_head+5)/4,'humedad','L,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_head+5)/4,utf8_decode('húmedo/seco'),'L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_condiCurado,$posicion_y);
			$anchoPromedio = 'promedio';
			$tam_anchoPromedio = $this->GetStringWidth($anchoPromedio)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_anchoPromedio,($tam_font_head+5)/3,'Ancho','L,T,R',2,'C');
			$this->Cell($tam_anchoPromedio,($tam_font_head+5)/3,utf8_decode($anchoPromedio),'L,R',2,'C');
			$this->Cell($tam_anchoPromedio,($tam_font_head+5)/3,'(cm)','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_anchoPromedio,$posicion_y);
			$peralPromedio = 'promedio';
			$tam_peralPromedio = $this->GetStringWidth($peralPromedio)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_peralPromedio,($tam_font_head+5)/3,'Peralte','L,T,R',2,'C');
			$this->Cell($tam_peralPromedio,($tam_font_head+5)/3,utf8_decode($peralPromedio),'L,R',2,'C');
			$this->Cell($tam_peralPromedio,($tam_font_head+5)/3,'(cm)','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_peralPromedio,$posicion_y);
			$entreApoyos = 'entre apoyos';
			$tam_entreApoyos = $this->GetStringWidth($entreApoyos)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_entreApoyos,($tam_font_head+5)/3,'Distancia','L,T,R',2,'C');
			$this->Cell($tam_entreApoyos,($tam_font_head+5)/3,utf8_decode($entreApoyos),'L,R',2,'C');
			$this->Cell($tam_entreApoyos,($tam_font_head+5)/3,'(cm)','L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_entreApoyos,$posicion_y);
			$cargaMaxima = 'Carga máxima';
			$tam_cargaMaxima = $this->GetStringWidth($cargaMaxima)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_cargaMaxima,($tam_font_head+5)/2,utf8_decode($cargaMaxima),'L,T,R',2,'C');
			$this->Cell($tam_cargaMaxima,($tam_font_head+5)/2,utf8_decode('aplicada (kg)'),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_cargaMaxima,$posicion_y);
			$modRuptura = 'Modulo de Ruptura';
			$tam_modRuptura = $this->GetStringWidth($modRuptura)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_modRuptura,($tam_font_head+5)/2,$modRuptura,'L,T,R',2,'C');
			$this->Cell($tam_modRuptura,($tam_font_head+5)/2,utf8_decode('(kg/cm²)'),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_modRuptura,$posicion_y);
			$modRuptura2 = 'Modulo de';
			$tam_modRuptura2 = $this->GetStringWidth($modRuptura2)+6;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_modRuptura2,($tam_font_head+5)/3,$modRuptura2,'L,T,R',2,'C');
			$this->Cell($tam_modRuptura2,($tam_font_head+5)/3,'Ruptura','L,R',2,'C');
			$this->Cell($tam_modRuptura2,($tam_font_head+5)/3,utf8_decode('(kPa)'),'L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_modRuptura2,$posicion_y);
			$defectos = 'Defectos del';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell(0,($tam_font_head+5)/2,$defectos,'L,T,R',2,'C');
			$this->Cell(0,($tam_font_head+5)/2,'Especimen','L,B,R',2,'C');
			$tam_defectos = $this->GetX() - $posicion_x;

			$this->Ln(0);
			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									$tam_iden,
									$tam_fechaColado,
									$tam_edad,
									$tam_apoyos,
									$tam_condiCurado,
									$tam_anchoPromedio,
									$tam_peralPromedio,
									$tam_entreApoyos,
									$tam_cargaMaxima,
									$tam_modRuptura,
									$tam_modRuptura2,
									$tam_defectos
							);


			$tam_font_head = 7;	$this->SetFont('Arial','',$tam_font_head);

			for ($i=0; $i < 10; $i++){
				//Definimos la posicion de X para tomarlo como referenci
				for ($j=0; $j < 12; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->cell($array_campo[$j],$tam_font_head - 2.5,'',1,0,'C');
				}	
				$this->Ln();
			}

			
			$this->SetY(135.50125);
			$observaciones = 'Observaciones: ';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell(0,($tam_font_head+3),$observaciones,1,2);
			$this->Ln(0);

			$this->SetFont('Arial','B',$tam_font_head);
			$metodos = 'Metodos: ';
			$metodos_usuados = 'NMX-C-161-ONNCCE-2013, NMX-C-159-ONNCCE-2016, NMX-C-191-ONNCE-2015';
			$this->Cell($this->GetStringWidth($metodos)+2,($tam_font_head),$metodos,'L,T,B',0);
			$this->SetFont('Arial','',$tam_font_head);
			$this->Cell($this->GetStringWidth($metodos_usuados)+40,($tam_font_head),utf8_decode($metodos_usuados),'R,T,B',0);
			$this->Cell(0,($tam_font_head),utf8_decode('INCERTIDUMBRE: '),1,1);

			$this->Ln(0);
			$tam_font_head = 6;
			$this->SetFont('Arial','',$tam_font_head);

			$mensaje1 = 'ESTE DOCUMENTO SE REFIERE EXCLUSIVAMENTE A LAS PRUEBAS REALIZADAS Y NO DEBE SER REPRODUCIDO DE FORMA PARCIAL SIN  LA AUTORIZACION DEL LABORATORIO LACOCS, ASI MISMO';
			$this->Cell($this->GetStringWidth($mensaje1),($tam_font_head),utf8_decode($mensaje1),0,2);
			$mensaje2 = 'ESTE DOCUMENTO NO TENDRA VALIDEZ SI PRESENTA TACHADURA O INMIENDA ALGUNA';
			$this->Cell($this->GetStringWidth($mensaje2),($tam_font_head),utf8_decode($mensaje2),0,0);

			$this->Ln(8);
			$tam_image = 20;
			$tam_font_footer = 8; $this->SetFont('Arial','',$tam_font_footer);
			
			$tam_boxElaboro = 259/3;	$tam_first = 12.5; $tam_second = 12.5;
			$posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			

			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	

			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('M en I. MARCO ANTONIO CERVANTES M.')/2),$this->gety() - 12,utf8_decode('M en I. MARCO ANTONIO CERVANTES M.'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->SetFont('Arial','',$tam_font_footer);



			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);

			$this->cell($tam_boxElaboro,$tam_first,'Recibe','L,T,R',2,'C');
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX();
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NOMBRE DE QUIEN RECIBE')/2),$this->gety() - 3,utf8_decode('NOMBRE DE QUIEN RECIBE'));	
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			

		}
		function Footer(){
			/*
			$tam_footer = 28;
			
			$tam_font_footer = 7;	$this->SetFont('Arial','B',$tam_font_footer);
			
			//Observaciones
			$this->cell(0,2*($tam_font_footer - 2.5),'',1,2,'C');
			//Metodos empleados
			$metodos = 'METODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			//$this->multicell(0,($tam_font_head - 2.5),$metodos,1,2);
			//Incertidumbre
			$incertidumbre = 'INCERTIDUMBRE';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+20;
			$this->SetX(-($tam_incertidumbre + 10));
			//Guardamos las posiciones de esa linea
			$posicion_x = $this->GetX();	$posicion_y = $this->GetY();
			$this->multicell($tam_incertidumbre,($tam_font_footer - 3),$incertidumbre."\n".'DUMMY',1,'C');
			//Metodos empleados
			$this->SetY($posicion_y);
			$metodos = 'METODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			$tam_metodos = $this->GetStringWidth($metodos)+3;
			$this->multicell($posicion_x -10,($tam_font_footer - 3),$metodos,1,2);
			$this->SetY(-($tam_footer + 10)); //Defenimos el margen de abajo
			$this->cell(0,$tam_footer,'',1,'C');*/
		}
		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato){
			$pdf  = new informeCilindros('L','mm','Letter');
			$pdf->AddPage();
			$pdf->putInfo($infoFormato);
			$pdf->putTables();
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

	$pdf  = new InformeVigas('L','mm','Letter');
	$pdf->AddPage();
	$pdf->putInfo();
	$pdf->putTables();
	$pdf->Output();
	
?>