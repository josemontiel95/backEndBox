<?php 
	
	include_once("./../../FPDF/fpdf.php"); //Chida
	//include_once("Firma.jpeg");
	//include_once("./../FPDF/fpdf.php");
	//Formato de campo de cilindros
	class InformeRevenimiento extends fpdf{
		private $infoFormato;


		var $angle=0;
		function Header(){
			$ancho_ema = 50;	$alto_ema = 20;
			$tam_lacocs = 20;
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de Lacocs
			$posicion_x = $this->GetX();

			$this->Image('http://lacocs.montielpalacios.com/SystemData/BackData/Assets/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$this->TextWithDirection($this->GetX(),$this->gety() + 24,utf8_decode('LACOCS S.A. DE C.V.'));	

			$this->Image('http://lacocs.montielpalacios.com/SystemData/BackData/Assets/ema.jpeg',206-$ancho_ema,$this->GetY(),$ancho_ema,$alto_ema);
			$this->SetY(30);
			//Definimos el las propiedades de la primera linea del titulo
			$tam_font_titulo = 9;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo_linea1 = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A. DE C.V.';
			$tam_cell = $this->GetStringWidth($titulo_linea1);
			$this->SetX((210-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo_linea1),0,'C');
			$this->Ln($tam_font_titulo - 5);

			//Definimos las propiedades de la segunda linea del titulo
			$this->SetFont('Arial','B',$tam_font_titulo);
			$titulo_linea2 = 'LACOCS S.A DE C.V';
			$tam_cell = $this->GetStringWidth($titulo_linea2);
			$this->SetX((210-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo_linea2),0,'C');
			$this->Ln($tam_font_titulo - 4);

			$tam_font_info = 7;
			//Direccion
			$this->SetFont('Arial','B',$tam_font_info);
			$direccion_lacocs = '35 NORTE No.3023, UNIDAD HABITACIONAL AQUILES SERDÁN, PUEBLA, PUE.';
			$tam_direccion = $this->GetStringWidth($direccion_lacocs)+6;
			$this->SetX((210-$tam_direccion)/2);
			//$this->SetX(-$tam_tituloInforme-10);
			$this->Cell($tam_direccion,$tam_font_info - 3,utf8_decode($direccion_lacocs),0,'C');

			$this->Ln($tam_font_info - 3);
			//Telefono y fax
			$this->SetX($this->GetX()+20);
			$telefono_fax = 'TELS. 8686-973/ 8686-974   FAX. 2315-836';
			$tam_telefono_fax = $this->GetStringWidth($telefono_fax)+6;
			$this->SetX((210-$tam_telefono_fax)/2);
			$this->Cell($tam_telefono_fax,$tam_font_info - 3,$telefono_fax,0,'C');

			$this->Ln($tam_font_info + 1); 

			$tam_font_titulo = 9;

			//Titulo del informe
			$tam_font_tituloInforme = 9;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '"INFORME EN LA DETERMINACIÓN DEL REVENIMIENTO EN EL CONCRETO';
			$tam_titulo_informe = $this->GetStringWidth($titulo_informe)+6;
			$this->SetX((210-$tam_titulo_informe)/2);
			$this->Cell($titulo_informe,$tam_font_info - 3,utf8_decode($titulo_informe),0,'C');

			$this->ln(4);

			$titulo_informe = 'FRESCO"';
			$tam_titulo_informe = $this->GetStringWidth($titulo_informe)+6;
			$this->SetX((210-$tam_titulo_informe)/2);
			$this->Cell($titulo_informe,$tam_font_info - 3,utf8_decode($titulo_informe),0,'C');

			$this->ln(4);
			
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
		

		function putInfo($infoFormato){

			$tam_font_right = 8;	$this->SetFont('Arial','B',$tam_font_right);
			$tam_line = 160;
			$regNo = 'REG. No.';
			$tam_regNo = $this->GetStringWidth($regNo)+6;
			$this->SetX(-($tam_regNo+40));
			$this->cell($tam_regNo,$tam_font_right - 4,utf8_decode($regNo));
			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 4,utf8_decode($infoFormato['regNo']),'B',0,'C');
			$this->Ln($tam_font_right - 2);

			$tam_font_left = 8;	$this->SetFont('Arial','B',$tam_font_left);
			//Cuadro con informacion
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 4,utf8_decode($infoFormato['obra']),'B',0);
			$this->Ln(4);
			$this->SetFont('Arial','B',$tam_font_left);
			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto
			$this->SetX(50);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 4,utf8_decode($infoFormato['localizacion']),'B',0);
			$this->Ln(4);
			$this->SetFont('Arial','B',$tam_font_left);
			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);
			//Caja de texto
			$this->SetX(50);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 4,utf8_decode($infoFormato['nombre']),'B',0);
			$this->Ln(4);
			//Direccion del cliente
			$this->SetFont('Arial','B',$tam_font_left);
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX(50);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 4,utf8_decode($infoFormato['direccion']),'B',0);

			$this->ln(8);


			$tam_font_left = 8;	$this->SetFont('Arial','',$tam_font_left);
			//Texto Adicional
			$texto_adicional = 'SE DETERMINA EL REVENIMIENTO EN CONCRETO FRESCO TOMANDO COMO BASE LA NORMA MEXICANA NMX-C-156-ONNCCE-2010';
			$tam_texto_adicional = $this->GetStringWidth($texto_adicional)+3;
			$this->Cell($tam_texto_adicional,$tam_font_left - 3,utf8_decode($texto_adicional),0);
			$this->Ln(8);

			$localizacion = 'LOCALIZACIÓN:';
			//Caja de texto
			$this->SetFont('Arial','B',$tam_font_left);
			$tam_localizacion = $this->GetStringWidth($localizacion)+3;
			$this->Cell($tam_localizacion,$tam_font_left - 3,utf8_decode($localizacion),0);
			//Caja de texto
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell(0,$tam_font_left - 4,utf8_decode($infoFormato['localizacionRev']),'B',0);

			$this->ln(8);
		}

		function putTables($infoFormato,$regisFormato){

			$tam_font_head = 7;	$this->SetFont('Arial','B',$tam_font_head);//Fuente para clave


			//Fecha
			$determinacion = 'DETERMINACIÓN';
			$fecha = 'FECHA DE';
			$tam_fecha = $this->GetStringWidth($fecha);
			$tam_ancho_determinacion = 13; $tam_alto_determinacion = $this->GetStringWidth($determinacion)+3;

			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->multicell($tam_ancho_determinacion,$tam_alto_determinacion,'',1);
			//tENEMOS QUE PONER POR SEPARADO EL TEXTO DENTRO DE LA CELDA
			$this->TextWithDirection($posicion_x+($tam_ancho_determinacion/2),$this->gety() - (($tam_alto_determinacion-$tam_fecha)/2),utf8_decode('FECHA DE'),'U');	
			$this->TextWithDirection($posicion_x+($tam_ancho_determinacion/2)+3,$this->gety() - 2,utf8_decode($determinacion),'U');	

			$this->SetXY(($posicion_x + $tam_ancho_determinacion),$posicion_y);
			$revPro = 'REV. DE'."\n".'PROYECTO'."\n".'EN cm';
			$tam_revPro = $this->GetStringWidth('PROYECTO')+3;
			$posicion_x = $this->GetX();
			$this->multicell($tam_revPro,$tam_alto_determinacion/3,$revPro,1,'C');

			$this->SetXY(($posicion_x + $tam_revPro),$posicion_y);
			$revObtenido = 'REV.'."\n".'OBTENIDO'."\n".'EN cm';
			$tam_revObtenido = $this->GetStringWidth('OBTENIDO')+3;
			$posicion_x = $this->GetX();
			$this->multicell($tam_revObtenido,$tam_alto_determinacion/3,$revObtenido,1,'C');

			$this->SetXY(($posicion_x + $tam_revObtenido),$posicion_y);
			$nominal = 'TAMAÑO.'."\n".'NOMINAL'."\n".'DEL'."\n".'AGREGADO'."\n".'mm';
			$tam_nominal = $this->GetStringWidth('AGREGADO')+3;
			$posicion_x = $this->GetX();
			$this->multicell($tam_nominal,$tam_alto_determinacion/5,utf8_decode($nominal),1,'C');
			
			$this->SetXY(($posicion_x + $tam_nominal),$posicion_y);
			$iden = "\n".'IDENTIFICACION'."\n".'DEL CONCRETO';
			$tam_iden = $this->GetStringWidth('IDENTIFICACION');
			$posicion_x = $this->GetX(); 
			$this->cell($tam_iden,$tam_alto_determinacion,'',1,2,'C');
			$this->TextWithDirection($posicion_x+($tam_iden/2),$this->gety() - (($tam_alto_determinacion-$tam_iden)/2),utf8_decode('IDENTIFICACIÓN'),'U');	
			$this->TextWithDirection($posicion_x+($tam_iden/2)+3,$this->gety() - 2,utf8_decode('DEL CONCRETO'),'U');	

			/*
			$this->cell($tam_iden,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,utf8_decode('IDENTIFICACIÓN'),'L,R',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,utf8_decode('DEL CONCRETO'),'L,R',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,'','L,R,B',2,'C');
			*/
			$this->SetXY(($posicion_x + $tam_iden),$posicion_y);
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$volumen = 'VOLUMEN m³';
			$tam_volumen = $this->GetStringWidth($volumen);
			$posicion_x = $this->GetX(); 
			$this->cell($tam_volumen - 8,$tam_alto_determinacion,'',1,2,'C');
			$this->TextWithDirection($posicion_x+(($tam_volumen - 8)/2),$this->gety() - (($tam_alto_determinacion-$tam_volumen)/2),utf8_decode($volumen),'U');	
			
			

			/*
			$this->cell($tam_volumen,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,utf8_decode('VOLUMEN'),'L,R',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,utf8_decode('m³'),'L,R',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,'','L,R,B',2,'C');
			*/
			

			$this->SetXY(($posicion_x + ($tam_volumen - 8)),$posicion_y);
			$hora_determinacion = 'DETERMINACIÓN';
			$hora = 'HORA DE LA';
			$tam_hora = $this->GetStringWidth($hora);
			$tam_ancho_hora_determinacion = 13; 
			$posicion_x = $posicion_x + ($tam_volumen - 8);
			$this->multicell($tam_ancho_hora_determinacion,$tam_alto_determinacion,'',1);
			//tENEMOS QUE PONER POR SEPARADO EL TEXTO DENTRO DE LA CELDA
			$this->TextWithDirection($posicion_x+($tam_ancho_hora_determinacion/2),$this->gety() - (($tam_alto_determinacion-$tam_hora)/2),utf8_decode($hora),'U');	
			$this->TextWithDirection($posicion_x+($tam_ancho_hora_determinacion/2)+3,$this->gety() - 2,utf8_decode($determinacion),'U');	


			$this->SetXY(($posicion_x + $tam_ancho_hora_determinacion),$posicion_y);
			$unidad = 'UNIDAD';
			$tam_unidad = $this->GetStringWidth('UNIDAD')+2;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_unidad,$tam_alto_determinacion,$unidad,1,2,'C');

			$this->SetXY(($posicion_x + $tam_unidad),$posicion_y);
			$provedor = 'PROVEDOR DEL'."\n".'CONCRETO';
			$tam_provedor = $this->GetStringWidth('PROVEDOR DEL')+8;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_provedor,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_provedor,$tam_alto_determinacion/4,utf8_decode('PROVEDOR DEL'),'L,R',2,'C');
			$this->cell($tam_provedor,$tam_alto_determinacion/4,utf8_decode('CONCRETO'),'L,R',2,'C');
			$this->cell($tam_provedor,$tam_alto_determinacion/4,'','L,R,B',2,'C');

			$this->SetXY(($posicion_x + $tam_provedor),$posicion_y);
			$remision = 'NUMERO DE'."\n".'REMISIÓN';
			$tam_remision = $this->GetStringWidth('NUMERO DE')+2;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_remision,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_remision,$tam_alto_determinacion/4,utf8_decode('NUMERO DE'),'L,R',2,'C');
			$this->cell($tam_remision,$tam_alto_determinacion/4,utf8_decode('REMISIÓN'),'L,R',2,'C');
			$this->cell($tam_remision,$tam_alto_determinacion/4,'','L,R,B',2,'C');

			$this->SetXY(($posicion_x + $tam_remision),$posicion_y);
			$salida = 'HORA DE'."\n".'SALIDA DE'."\n".'PLANTA';
			$tam_salida = $this->GetStringWidth('SALIDA DE')+2.5;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_salida,$tam_alto_determinacion/5,'','L,R,T',2,'C');
			$this->cell($tam_salida,$tam_alto_determinacion/5,utf8_decode('HORA DE'),'L,R',2,'C');
			$this->cell($tam_salida,$tam_alto_determinacion/5,utf8_decode('SALIDA DE'),'L,R',2,'C');
			$this->cell($tam_salida,$tam_alto_determinacion/5,utf8_decode('PLANTA'),'L,R',2,'C');
			$this->cell($tam_salida,$tam_alto_determinacion/5,'','L,R,B',2,'C');

			$this->SetXY(($posicion_x + $tam_salida),$posicion_y);
			$salida = 'HORA DE'."\n".'LLEGADA A'."\n".'OBRA';
			$posicion_x = $this->GetX(); 
			$this->cell(0,$tam_alto_determinacion/5,'','L,R,T',2,'C');
			$this->cell(0,$tam_alto_determinacion/5,utf8_decode('HORA DE'),'L,R',2,'C');
			$this->cell(0,$tam_alto_determinacion/5,utf8_decode('LLEGADA A'),'L,R',2,'C');
			$this->cell(0,$tam_alto_determinacion/5,utf8_decode('OBRA'),'L,R',2,'C');
			$this->cell(0,$tam_alto_determinacion/5,'','L,R,B',2,'C');
			$tam_llegada = $this->GetX() - $posicion_x;
			$this->Ln(0);
		
			$tam_volumen-=8;
			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									$tam_ancho_determinacion,
									$tam_revPro,
									$tam_revObtenido,
									$tam_nominal,
									$tam_iden,
									$tam_volumen,
									$tam_ancho_hora_determinacion,
									$tam_unidad,
									$tam_provedor,
									$tam_remision,
									$tam_salida,
									$tam_llegada
							);

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			$num_rows = 0;
			foreach ($regisFormato as $registro) {
				$j=0;

				foreach ($registro as $campo) {
					//Funcion para truncar la cadena
					/* Problema se ejecuta mucho tiempo
					$tam_campo = $this->GetStringWidth($campo); //Tamaño de la informacion del campo
					while($tam_campo>$array_campo[$j]){
						$campo = substr($campo,0,(strlen($campo))-1);
						$tam_campo =  $this->GetStringWidth($campo)+2;
					}*/	
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
			/*
			for($i=0;$i<11;$i++{
				$this->cell(0,$tam_alto_determinacion/5,'','L,R,B',2,'C');
				$this->cell($array_campo[$j],$tam_font_head - 2.5,'',1,0,'C');
			}*/
			$this->Ln(4);


			$this->SetY(-100);	
			$tam_font_footer = 7;	
			$tam_observaciones = 5;
			$tam_font_footer = 7;	$this->SetFont('Arial','B',$tam_font_footer);
			$observaciones = 'OBSERVACIONES:';

			//Observaciones
			//$this->SetY($posicion_y);
			$this->cell(0,2*($tam_font_footer - 2.5),$observaciones,'L,T,R',2);
			$this->SetFont('Arial','',$tam_font_footer);
			$this->cell(0,$tam_observaciones,utf8_encode($infoFormato['observaciones']),'L,B,R',2);

			//Metodos
			$metodos = 'METODOS EMPLEADOS: NMX-C-161-ONNCCE-2013, NMX-C-159-ONNCCE-2016, NMX-C156-ONNCCE-2010';
			$this->cell($this->GetStringWidth($metodos)+2,10,$metodos,1,0);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			


			//Lado derecho

			$tam_box = 206-$posicion_x;
			$this->SetXY((-($tam_box+10)),$posicion_y);
			$simbologia = 'SIMBOLOGIA';
			$posicion_x = $this->GetX(); 
			$this->cell($this->GetStringWidth($simbologia)+2,10,$simbologia,'L,T,B',0,'C');
			$posicion_y = $this->GetY();




			$this->SetXY($posicion_x+$this->GetStringWidth($simbologia)+2,$posicion_y);
			$this->cell(0,10/3,'CA = CON ADITIVO','R',0);
			$this->SetXY($posicion_x+$this->GetStringWidth($simbologia)+2,$posicion_y+10/3);
			$this->cell(0,10/3,'RR = RESISTENCIA RAPIDA','R',0);
			$this->SetXY($posicion_x+$this->GetStringWidth($simbologia)+2,$posicion_y+2*(10/3));
			$this->cell(0,10/3,'CA = NORMAL','B,R',0);
			$this->Ln(0);
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			$this->SetXY($posicion_x,$posicion_y+10/3);
			
			$this->multicell(0,4,utf8_decode('ESTE INFORME DE RESULTADOS SE REFIERE EXCLUSIVAMENTE AL ENSAYE REALIZADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS, Y SOLO TIENE VALIDES SI NO PRESENTA TACHADURAS O ENMIENDAS'),1,2);



			$this->Cell(0,10,'',1,2	);
			$tam_image = 20;
			$tam_font_footer = 8; $this->SetFont('Arial','',$tam_font_footer);
			
			$tam_boxElaboro = 196/3;	$tam_first = 12.5; $tam_second = 12.5;
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
			$this->Ln(0);

			
					
		}
		function Footer(){
			$this->SetY(-15);
		    $this->SetFont('Arial','',8);
		    $tam_noPagina = $this->GetStringWidth('Page '.$this->PageNo().'/{nb}');
		    $posicion_x = (216 - $tam_noPagina)/2;
		    $this->SetX($posicion_x);
		    $this->Cell($tam_noPagina,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

		    //Clave de validacion
		    $clave = 'FI-02-LCC-01-1.4';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');
			

			
		}
		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato,$target_dir){
			$pdf  = new InformeRevenimiento('P','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($infoFormato,$regisFormato);
			$pdf->Output('F',$target_dir);
			//$pdf->Output();
		}


		/*
		function CreateNew($infoFormato,$regisFormato){
			//Asignacion de la informacion a las variables locales

			$this->infoFormato = $infoFormato;

			$pdf  = new Revenimiento('P','mm','Letter');
			$pdf->AddPage();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($infoFormato,$regisFormato);
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
	
	/*
	$pdf  = new Revenimiento('P','mm','Letter');
	$pdf->AddPage();
	$pdf->putInfo();
	$pdf->putTables();
	$pdf->Output();
	*/
	


?>