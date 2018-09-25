<?php 
	//include_once("./../../FPDF/fpdf.php");
	include_once("./../../FPDF/fpdf.php");
	//Formato de campo de cilindros
	class InformeCilindros extends fpdf{
		public $arrayCampos;
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
			$ancho_ema = 50;	$alto_ema = 20;
			$tam_lacocs = 20;
			//$this->SetX(-($ancho_ema + 10));
			//$this->Image('ema.jpeg',null,null,$ancho_ema,$alto_ema);
			$posicion_x = $this->GetX();

			$this->Image('http://lacocs.montielpalacios.com/SystemData/BackData/Assets/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$this->TextWithDirection($this->GetX(),$this->gety() + 24,utf8_decode('LACOCS S.A. DE C.V.'));	

			$this->Image('http://lacocs.montielpalacios.com/SystemData/BackData/Assets/ema.jpeg',269-$ancho_ema,$this->GetY(),$ancho_ema,$alto_ema);
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
			$titulo_informe = '"INFORME DE PRUEBAS A COMPRESIÓN DE CILINDROS DE CONCRETO HIDRÁULICO"';
			$tam_tituloInforme = $this->GetStringWidth($titulo_informe)+130;
			$tam_font_info = 6.5;
			//Direccion
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
			$this->Cell(0,$tam_font_right - 3,utf8_decode('N/A'),'B',0,'C');
			//--Divide la informacion de la derecha y la izquierda
			$this->Ln($tam_font_right - 1);
			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/
			$tam_font_left = 7;	$this->SetFont('Arial','',$tam_font_left);
			//Cuadro con informacion
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['obra']),'B',0);
			$this->Ln($tam_font_left - 2);
			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['localizacion']),'B',0);
			$this->Ln($tam_font_left - 2);
			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['razonSocial']),'B',0);
			$this->Ln($tam_font_left - 2);
			//Direccion del cliente
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['direccion']),'B',0);
			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($tam_font_left);
		}
		function putTables($infoFormato,$regisFormato){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY();
			$tam_font_head = 6;	$this->SetFont('Arial','B',$tam_font_head);
			$falla = 'FALLA';
			$tam_falla = $this->GetStringWidth($falla)+3;
			$this->SetX(-($tam_falla + 10));
			$posicion_x = $this->GetX();
			$this->multicell($tam_falla,(1.5*($tam_font_head - 3)),utf8_decode($falla.'N°'),1,'C');
			$resistencia = 'RESISTENCIA';	
			$tam_resistencia = $this->GetStringWidth($resistencia)+3;
			$this->SetY($posicion_y);
			// $this->SetX($this->GetX() - $tam_resistencia);  (Se modifica el valor de la posicion de X cuando se imprime, por eso no se calcula bien)
			$this->SetX($posicion_x - $tam_resistencia);
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
			$tam_font_head = 5.5;	
			$resis_compresion  = 'RESISTENCIA A';
			$tam_resis = $this->GetStringWidth($resis_compresion)+10;
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $tam_resis);
			$posicion_x = $this->GetX();
			$this->multicell($tam_resis,0.75*($tam_font_head - 2.5),utf8_decode($resis_compresion."\n".'COMPRESIÓN'),1,'C');
			$tam_font_head = 6;
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
			$tam_font_head = 5.5;	
			//Abajo de Escpecimenes
			$area = 'AREA EN';
			$tam_area = $this->GetStringWidth($area) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_area); $posicion_x = $this->GetX();
			$this->multicell($tam_area,0.75*($tam_font_head - 2.5),utf8_decode($area."\n".'cm²'),1,'C');
			$altura = 'ALTURA';
			$tam_altura = $this->GetStringWidth($area) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_altura); $posicion_x = $this->GetX();
			$this->multicell($tam_altura,0.75*($tam_font_head - 2.5),utf8_decode($altura."\n".'EN cm'),1,'C');
			$diametro = 'DIAMETRO EN';
			$tam_diametro = $this->GetStringWidth($diametro) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_diametro); $posicion_x = $this->GetX();
			$this->multicell($tam_diametro,0.75*($tam_font_head - 2.5),$diametro."\n".'cm',1,'C');
			$edad = 'EDAD EN';
			$tam_edad = $this->GetStringWidth($edad) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_edad); $posicion_x = $this->GetX();
			$this->multicell($tam_edad,0.75*($tam_font_head - 2.5),utf8_decode($edad."\n".'DIAS'),1,'C');
			$tam_font_head = 6;	
			$peso = 'PESO EN kg';
			$tam_peso = $this->GetStringWidth($peso) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x - $tam_peso);	$posicion_x = $this->GetX();
			$this->cell($tam_peso,1.5*($tam_font_head - 3),$peso,1,0,'C');
			$rev = 'REV. cm';
			$tam_rev = $this->GetStringWidth($rev) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x - $tam_rev);	$posicion_x = $this->GetX();
			$this->cell($tam_rev,1.5*($tam_font_head - 3),$rev,1,2,'C');
			
			//Especimenes
			$especimenes = 'ESPECIMENES';
			$tam_especimenes = ($tam_area + $tam_altura + $tam_diametro + $tam_edad + $tam_peso + $tam_rev);
			$this->SetY($posicion_y); $this->SetX($posicion_x); 
			$this->cell($tam_especimenes,1.5*($tam_font_head - 3),$especimenes,1,2,'C');
			//$this->SetY($posicion_y + 3*($tam_font_head - 3)); pone al final de la celda para el salto
			$posicion_y = $this->GetY();
			//Clave
			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave) + 25;
			$this->SetX($posicion_x - $tam_clave);	$posicion_x = $this->GetX();
			$this->cell($tam_clave,1.5*($tam_font_head - 3),$clave,1,0,'C');
			//Fecha de ensaye
			$fecha = 'FECHA DE ENSAYE';
			$tam_fecha = $this->GetStringWidth($fecha) + 3;
			$this->SetX($posicion_x - $tam_fecha); $posicion_x = $this->GetX();
			$this->cell($tam_fecha,1.5*($tam_font_head - 3),$fecha,1,0,'C');
			//Elemento
			$elemento = 'ELEMENTO MUESTREADO';
			$tam_ele = $posicion_x - 10;
			$this->SetX(10);
			$this->cell($tam_ele,1.5*($tam_font_head - 3),$elemento,1,2,'C');
			$this->ln(1);
			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									$tam_falla,
									$tam_resistencia,
									$tam_proyecto,
									$tam_kgcm,
									$tam_mp,
									$tam_kg,
									$tam_kN,
									$tam_area,
									$tam_altura,
									$tam_diametro,
									$tam_edad,
									$tam_peso,
									$tam_rev,
									$tam_clave,
									$tam_fecha,
									$tam_ele
							);
			/*
			$tam_resistencia,
									*/
			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$this->SetFont('Arial','',$tam_font_head);
			$ele_posicion_y = $this->GetY();
			
			$ele_posicion_y = $this->GetY(); 
			$band = 0;
			$num_rows = 0;
			$array_aux = array_pop($regisFormato);
			foreach ($regisFormato as $registro) {
				$this->SetX(-10); $posicion_x = $this->GetX();
				$j=0;
				foreach ($registro as $campo) {
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					
					$this->cell($array_campo[$j],$tam_font_head - 3,utf8_decode($campo),1,0,'C');
					
					$j++;
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<8){
				for ($i=0; $i < (8-$num_rows); $i++){
				//Definimos la posicion de X para tomarlo como referencia
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < sizeof($array_campo) -1 ; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					
					$this->cell($array_campo[$j],$tam_font_head - 3,'',1,0,'C');
					
					
				}	
				$this->Ln();
				}
			}
			//Guardamos la posicion en donde se quedo la Y para comparar con el tamaño de la celda del "Elemento"
			$endDown_table = $this->GetY();
			
			//Modificamos el valor de Y para empezar en el inicio de la tabla
			$this->SetY($ele_posicion_y);
			//Insertamos las celdas de "Elemento muestreado"
			/*
			$campo = $regisFormato[0]['localizacion'];
			$tam_campo = $this->GetStringWidth($campo); //Tamaño de la 
			while($tam_campo>($tam_ele*6)){
						$campo = substr($campo,0,(strlen($campo))-1);
						$tam_campo =  $this->GetStringWidth($campo)+2;
			}*/
			$this->multicell($tam_ele,$tam_font_head - 3,utf8_decode($array_aux),'L,T');
			
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($tam_font_head - 3));
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_ele,$tam_font_head - 3,'','L',2);
				}
			}
			
				

			$this->cell(0,$tam_font_head - 3,'',1,2);
			$this->cell(0,1.5*($tam_font_head - 3),'',1,2);

			
			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY();
		
			for ($i=0; $i < 8; $i++){
				//Definimos la posicion de X para tomarlo como referencia
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < sizeof($array_campo)-1; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					
					$this->cell($array_campo[$j],$tam_font_head - 3,'',1,0,'C');
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

			/*
					BYUENAS
			for ($i=0; $i < 8; $i++){
				//Definimos la posicion de X para tomarlo como referencia
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < 15; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					$this->cell($array_campo[$j],$tam_font_head - 2.5,'',1,0,'C');
				}
				$this->Ln();
			}
			*/
			/*
			for ($i=0; $i < 8; $i++)
				$this->cell(0,$tam_font_head - 2.5,'DUMMY',1,2,'C');
			*/
			$this->ln(2);
			$tam_footer = 20;
			
			$tam_font_footer = 7;	$this->SetFont('Arial','B',$tam_font_footer);
			

			//Observaciones
			$observaciones = 'OBSERVACIONES:';
			
			$this->cell($this->GetStringWidth($observaciones)+2,2*($tam_font_footer - 4),$observaciones,'L,T,B',0);
			$this->SetFont('Arial','',$tam_font_footer);
			$this->cell(0,2*($tam_font_footer - 4),utf8_decode($infoFormato['observaciones']),'R,T,B',2);

			$this->SetFont('Arial','B',$tam_font_footer);
			//Metodos empleados
			//
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
			$metodos = 'MÉTODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			$tam_metodos = $this->GetStringWidth($metodos)+3;
			$this->multicell($posicion_x -10,($tam_font_footer - 3),utf8_decode($metodos),1,2);

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
			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			

			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->SetFont('Arial','B',$tam_font_footer);
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
			$falla = 'FALLA';
			$tam_falla = $this->GetStringWidth($falla)+3;
			
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
			//Abajo de resistencia a compresion
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
			
			$altura = 'ALTURA';
			$tam_altura = $this->GetStringWidth($area) + 3;
			
			$diametro = 'DIAMETRO EN';
			$tam_diametro = $this->GetStringWidth($diametro) + 3;
			
			$edad = 'EDAD EN';
			$tam_edad = $this->GetStringWidth($edad) + 3;
			
			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			$peso = 'PESO EN kg';
			$tam_peso = $this->GetStringWidth($peso) + 3;
			
			$rev = 'REV. cm';
			$tam_rev = $this->GetStringWidth($rev) + 3;
			
			//Especimenes
			$especimenes = 'ESPECIMENES';
			$tam_especimenes = ($tam_area + $tam_altura + $tam_diametro + $tam_edad + $tam_peso + $tam_rev);
			
			
			$posicion_y = $this->GetY();
			//Clave
			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave) + 25;
			
			//Fecha de ensaye
			$fecha = 'FECHA DE ENSAYE';
			$tam_fecha = $this->GetStringWidth($fecha) + 3;
			
			//Elemento
			$elemento = 'ELEMENTO MUESTREADO';

			$posicion_x = 	$tam_falla +
							$tam_resistencia +
							$tam_proyecto +
							$tam_kgcm +
							$tam_mp +
							$tam_kg +
							$tam_kN +
							$tam_area +
							$tam_altura +
							$tam_diametro +
							$tam_edad +
							$tam_peso +
							$tam_rev +
							$tam_clave +
							$tam_fecha +
							10;


			$this->SetX($posicion_x);							
			$this->Cell(0,($tam_font_head+5)/2,'','L,T,R',0,'C');
			$tam_ele = $this->GetX() - $posicion_x;

			$array_campo = array(
									$tam_falla,
									$tam_resistencia,
									$tam_proyecto,
									$tam_kgcm,
									$tam_mp,
									$tam_kg,
									$tam_kN,
									$tam_area,
									$tam_altura,
									$tam_diametro,
									$tam_edad,
									$tam_peso,
									$tam_rev,
									$tam_clave,
									$tam_fecha,
									$tam_ele
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
		    $clave = 'FI-05-LCC-01-6.1';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');
		}
		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato,$target_dir){
			$pdf  = new informeCilindros('L','mm','Letter');
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
	$pdf  = new informeCilindros('L','mm','Letter');
			$pdf->AddPage();
			$pdf->putTables();
			$pdf->Output();*/
	
?>