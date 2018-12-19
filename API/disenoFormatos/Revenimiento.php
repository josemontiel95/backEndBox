<?php 
	
	include_once("./../../FPDF/MyPDF.php");

	class Revenimiento extends MyPDF{
		/*
			Informacion extra:
								-Ancho de una celda cuando su ancho = 0 : 259.3975
		*/

		//Variables
		public $arrayCampos;
		public $arrayInfo;
		
		//Array que contiene los letreros de la información
		private $cellsInfo;

		//Array que contiene los letreso de las tablas
		private $cellsTables;

		//Array que contiene los letreros de los detalles
		private $cellsDetails;


		public $error;

		function demo(){
			$pdf  = new Revenimiento('P','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putCaracInfo();
			$pdf->putCaracCampos();
			$pdf->putCaracDetails();
			$pdf->Output();
		}

		function getCellsTables(){
			return $this->cellsTables;
		}

		function generateCellsCampos(){
			$tam_font_Cells = 6.5;
			$tam_font_CellsRows = 5;
			$tam_cellsTablesAlto = $tam_font_Cells - 3;
			
			$this->SetFont('Arial','B',$tam_font_Cells);


			//Fecha
			$determinacion = 'DETERMINACIÓN';
			$fecha = 'FECHA DE';
			$tam_fecha = $this->GetStringWidth($fecha);
			$tam_ancho_determinacion = 19; 
			$tam_alto_determinacion = $this->GetStringWidth($determinacion)+3;

			//Revenimiento del proyecto
			$revPro = 'REV. DE'."\n".'PROYECTO'."\n".'EN cm';
			$tam_proAncho = $this->GetStringWidth('PROYECTO')+3;
			
			//Revenimietno Obtenido
			$revObtenido = 'REV.'."\n".'OBTENIDO'."\n".'EN cm';
			$tam_revObtenido = $this->GetStringWidth('OBTENIDO')+3;
				
			//Tamaño nominal del agregado
			$nominal = 'TAMAÑO.'."\n".'NOMINAL'."\n".'DEL'."\n".'AGREGADO'."\n".'mm';
			$tam_agregadoAncho = $this->GetStringWidth('AGREGADO')+3;
			
			//Identificacion del concreto
			$iden = "\n".'IDENTIFICACION'."\n".'DEL CONCRETO';
			$tam_iden = $this->GetStringWidth('IDENTIFICACION')+3;
			$tam_anchoIden = $tam_iden;

			//Volumen
			$volumen = "\n".'VOLUMEN'."\n".'m³';
			$tam_volumen = $this->GetStringWidth('VOLUMEN')+2;
			$tam_volumenAncho = $tam_volumen;

 			//Hora de la determinacion
			$hora_determinacion = 'DETERMINACIÓN';
			$hora = 'HORA DE LA';
			$tam_hora = $this->GetStringWidth($hora);
			$tam_ancho_hora_determinacion = 13; 

			//Unidad
			$unidad = 'UNIDAD';
			$tam_unidadAncho = $this->GetStringWidth('UNIDAD')+3;
	
			//Provedor del concreto
			$provedor = 'PROVEDOR DEL'."\n".'CONCRETO';
			$tam_provedor = $this->GetStringWidth('PROVEDOR DEL')+6;

			//Numero de remision
			$remision = 'NUMERO DE'."\n".'REMISIÓN';
			$tam_remision = $this->GetStringWidth('NUMERO DE')+2;
			

			//Hora de salida de la planta
			$salida = 'HORA DE'."\n".'SALIDA DE'."\n".'PLANTA';
			$tam_salida = $this->GetStringWidth('SALIDA DE')+3;
			
			//Hora de llegada a la planta
			$salida = 'HORA DE'."\n".'LLEGADA A'."\n".'OBRA';
			$tam_llegada = 195.9 - (
									$tam_ancho_determinacion+
									$tam_proAncho+
									$tam_revObtenido+
									$tam_agregadoAncho+
									$tam_anchoIden+
									$tam_volumenAncho+
									$tam_ancho_hora_determinacion+
									$tam_unidadAncho+
									$tam_provedor+
									$tam_remision+
									$tam_salida
								);

			
		
			$this->cellsTables = array(
											'tam_font_Cells' => $tam_font_Cells,
											'tam_font_CellsRows' => $tam_font_CellsRows,
											'tam_cellsTablesAlto' => $tam_cellsTablesAlto,

											'tam_ancho_determinacion' => $tam_ancho_determinacion,
											'tam_proAncho' => $tam_proAncho,
											'tam_revObtenido' => $tam_revObtenido,
											'tam_agregadoAncho' => $tam_agregadoAncho,
											'tam_anchoIden' => $tam_anchoIden,
											'tam_volumenAncho' => $tam_volumenAncho,
											'tam_ancho_hora_determinacion' => $tam_ancho_hora_determinacion,
											'tam_unidadAncho' => $tam_unidadAncho,
											'tam_provedor' => $tam_provedor,
											'tam_remision' => $tam_remision,
											'tam_salida' => $tam_salida,
											'tam_llegada' => $tam_llegada

								);
		}


		function getcellsDetails(){
			return $this->cellsDetails;
		}

		function generateCellsDetails(){
			$tam_font_details = 7;
			$tam_font_inventario = 6.5;	
			$tam_inventarioAlto = $tam_font_inventario-2;

			//Tamaño de las herramientas, copie y pegue el mismo codigo que en el CCH para que esten del mismo tamaño

			$this->SetFont('Arial','B',$tam_font_details);
			$observaciones = 'OBSERVACIONES: ';
			
			$tam_observacionAnchoTxt = 195.9 - $this->GetStringWidth($observaciones);


			//Instrumentos
			$this->SetFont('Arial','B',$tam_font_inventario);
			
			$termo = 'Termómetro';
			$tam_termo = $this->GetStringWidth($termo)+10;

			$cono = 'Cono';
			$tam_cono = $tam_termo;

			$varilla = 'Varilla';
			$tam_varilla = $tam_termo;

			$flexometro = 'Flexometro';
			$tam_flexometro = $tam_termo;

			$this->cellsDetails = 	array(
													'tam_font_details' => $tam_font_details,
													'tam_font_inventario' => $tam_font_inventario,
													'tam_inventarioAlto' => $tam_inventarioAlto,
													'tam_observacionAnchoTxt' => $tam_observacionAnchoTxt,
													'tam_cono' => $tam_cono,
													'tam_varilla' => $tam_varilla,
													'tam_flexometro' => $tam_flexometro

												);

		}


		function Header(){
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de Lacocs
			$ancho_ema = 50;	$alto_ema = 20;
			$tam_lacocs = 20;
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de Lacocs
			$posicion_x = $this->GetX();


			$this->Image('./../../disenoFormatos/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$this->TextWithDirection($this->GetX(),$this->gety() + 24,utf8_decode('LACOCS S.A. DE C.V.'));	

			$posicion_x = $this->GetX();

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
			$titulo_linea2 = 'LACOCS S.A. DE C.V.';
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
			$titulo_informe = '"REGISTRO EN LA DETERMINACIÓN DEL REVENIMENTO EN EL CONCRETO';
			$tam_titulo_informe = $this->GetStringWidth($titulo_informe)+6;
			$this->SetX((210-$tam_titulo_informe)/2);
			$this->Cell($titulo_informe,$tam_font_info - 3,utf8_decode($titulo_informe),0,'C');

			$this->ln(4);

			$titulo_informe = 'FRESCO"';
			$tam_titulo_informe = $this->GetStringWidth($titulo_informe)+6;
			$this->SetX((210-$tam_titulo_informe)/2);
			$this->Cell($titulo_informe,$tam_font_info - 3,utf8_decode($titulo_informe),0,'C');

			$this->ln(4);
			
			//Put the watermark
   			$this->SetFont('Arial','B',75);
	    	$this->SetTextColor(192,192,192);
    		$this->RotatedText(55.5,172,'PREELIMINAR',45);
		}

		

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.
		

		function putInfo($infoFormato){
			$tam_font_right = 7.5;	
			$this->SetFont('Arial','B',$tam_font_right);
			$separacion = 50; //Separacion que vaa tener el Registro numero del margen

			//Registro Numero
			$regNo = 'REG. No.';
			$tam_regNo = $this->GetStringWidth($regNo)+3;
			$tam_regNoText = $separacion - 10;

			$this->SetX(-($tam_regNo + $separacion));

			$this->cell($tam_regNo,$tam_font_right - 4,utf8_decode($regNo));

			//Caja de texto


			$resultado = $this->printInfoObraAndLocObra($tam_font_right,$tam_regNoText,$tam_font_right - 4,$infoFormato['regNo'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['regNo'] = $resultado['new_string'];

			$this->error = $resultado['error'];
			


			$this->multicell(0,$tam_font_right - 4,utf8_decode(	$infoFormato['regNo']	),'B','C');

			$this->Ln(2);


			//PARTE IZQUIERDA DEL FORMATO
			$posicionCellsText  = 50;
			$linea_Text = 216 - ($posicionCellsText+10);

			$tam_font_left = 7;	
			$this->SetFont('Arial','B',$tam_font_left);
			
			$obra = 'NOMBRE DE LA OBRA:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);

			//Caja de texto
			$this->SetX($posicionCellsText);


			$resultado = $this->printInfoObraAndLocObra($tam_font_left,$linea_Text,$tam_font_left - 4,$infoFormato['obra'],3);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['obra'] = $resultado['new_string'];
			
			$this->error = $resultado['error'];

		
			$this->multicell($linea_Text,$tam_font_left - 4,utf8_decode(	$infoFormato['obra']	),'B','C');

			$this->Ln(1);

			$locObra = 'LOCALIZACIÓN DE LA OBRA:';
			$this->SetFont('Arial','B',$tam_font_left);
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto

			$this->SetX($posicionCellsText);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,$linea_Text,$tam_font_left - 4,$infoFormato['locObra'],3);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['locObra'] = $resultado['new_string'];

			$this->error = $resultado['error'];

			$this->multicell($linea_Text,$tam_font_left - 4,utf8_decode(	$infoFormato['locObra']	),'B','C');

			$this->Ln(1);

			$this->SetFont('Arial','B',$tam_font_left);
			$nomCli = 'NOMBRE DEL CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);
			//Caja de texto
			$this->SetX($posicionCellsText);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,$linea_Text,$tam_font_left - 4,$infoFormato['razonSocial'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['razonSocial'] = $resultado['new_string'];

			$this->error = $resultado['error'];
			

			$this->multicell($linea_Text,$tam_font_left - 4,utf8_decode(	$infoFormato['razonSocial']	),'B','C');

			$this->Ln(1);
			//Direccion del cliente
			$this->SetFont('Arial','B',$tam_font_left);
			$dirCliente = 'DIRECCIÓN DEL CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX($posicionCellsText);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,$linea_Text,$tam_font_left - 4,$infoFormato['direccion'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['direccion'] = $resultado['new_string'];

			$this->error = $resultado['error'];
	


			$this->multicell($linea_Text,$tam_font_left - 4,utf8_decode(	$infoFormato['direccion']	),'B','C');

			$this->ln(4);

			$tam_font_left = 7;	$this->SetFont('Arial','',$tam_font_left);
			//Texto Adicional
			$texto_adicional = 'SE DETERMINA EL REVENIMIENTO EN CONCRETO FRESCO TOMANDO COMO BASE LA NORMA MEXICANA NMX-C-156-ONNCCE-2010';
			$tam_texto_adicional = $this->GetStringWidth($texto_adicional)+3;
			$this->Cell($tam_texto_adicional,$tam_font_left - 3,utf8_decode($texto_adicional),0);
			$this->Ln(8);

			$localizacion = 'ELEMENTO COLADO:';
			//Caja de texto
			$this->SetFont('Arial','B',$tam_font_left);
			$tam_localizacion = $this->GetStringWidth($localizacion)+3;
			$tam_localizacionText = 196 - $tam_localizacion;

			$this->Cell($tam_localizacion,$tam_font_left - 3,utf8_decode($localizacion),0);
			//Caja de texto
			$this->SetFont('Arial','B',$tam_font_left);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,$tam_localizacionText,$tam_font_left - 4,$infoFormato['locRev'],3);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['locRev'] = $resultado['new_string'];

			$this->error = $resultado['error'];
			

			$this->multicell($tam_localizacionText,$tam_font_left - 4,utf8_decode(	$infoFormato['locRev']	),'B','C');

			$this->ln(8);
		}

		function putCaracInfo(){
			$tam_font_right = 7.5;	
			$this->SetFont('Arial','B',$tam_font_right);
			$separacion = 50; //Separacion que vaa tener el Registro numero del margen

			//Registro Numero
			$regNo = 'REG. No.';
			$tam_regNo = $this->GetStringWidth($regNo)+3;
			$tam_regNoText = $separacion - 10;

			$this->SetX(-($tam_regNo + $separacion));

			$this->cell($tam_regNo,$tam_font_right - 4,utf8_decode($regNo));

			//Caja de texto
			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 4,$this->getMaxString($tam_font_right,$tam_regNoText,'tam_stringCarac'),'B',0,'C');

			$this->Ln($tam_font_right - 2);


			//PARTE IZQUIERDA DEL FORMATO
			$posicionCellsText  = 50;
			$linea_Text = 216 - ($posicionCellsText+10);

			$tam_font_left = 7;	
			$this->SetFont('Arial','B',$tam_font_left);
			
			$obra = 'NOMBRE DE LA OBRA:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);

			//Caja de texto
			$this->SetX($posicionCellsText);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell($linea_Text,$tam_font_left - 4,$this->getMaxString($tam_font_left,$linea_Text,'string'),'B',0);
			$this->Ln(4);

			$locObra = 'LOCALIZACIÓN DE LA OBRA:';
			$this->SetFont('Arial','B',$tam_font_left);
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto
			$this->SetX($posicionCellsText);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell($linea_Text,$tam_font_left - 4,$this->getMaxString($tam_font_left,$linea_Text,'tam_stringCarac'),'B',0);

			$this->Ln(4);

			$this->SetFont('Arial','B',$tam_font_left);
			$nomCli = 'NOMBRE DEL CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);
			//Caja de texto
			$this->SetX($posicionCellsText);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell($linea_Text,$tam_font_left - 4,$this->getMaxString($tam_font_left,$linea_Text,'tam_stringCarac'),'B',0);

			$this->Ln(4);
			//Direccion del cliente
			$this->SetFont('Arial','B',$tam_font_left);
			$dirCliente = 'DIRECCIÓN DEL CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX($posicionCellsText);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell($linea_Text,$tam_font_left - 4,$this->getMaxString($tam_font_left,$linea_Text,'tam_stringCarac'),'B',0);

			$this->ln(8);


			$tam_font_left = 7;	$this->SetFont('Arial','',$tam_font_left);
			//Texto Adicional
			$texto_adicional = 'SE DETERMINA EL REVENIMIENTO EN CONCRETO FRESCO TOMANDO COMO BASE LA NORMA MEXICANA NMX-C-156-ONNCCE-2010';
			$tam_texto_adicional = $this->GetStringWidth($texto_adicional)+3;
			$this->Cell($tam_texto_adicional,$tam_font_left - 3,utf8_decode($texto_adicional),0);
			$this->Ln(8);

			$localizacion = 'LOCALIZACIÓN:';
			//Caja de texto
			$this->SetFont('Arial','B',$tam_font_left);
			$tam_localizacion = $this->GetStringWidth($localizacion)+3;
			$tam_localizacionText = 196 - $tam_localizacion;

			$this->Cell($tam_localizacion,$tam_font_left - 3,utf8_decode($localizacion),0);
			//Caja de texto
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell($tam_localizacionText,$tam_font_left - 4,$this->getMaxString($tam_font_left,$tam_localizacionText,'tam_stringCarac'),'B',0);

			$this->ln(8);
		}

		function putCaracCampos(){
			$tam_font_Cells = 6.5;
			$tam_font_CellsRows = 5;
			$tam_cellsTablesAlto = $tam_font_Cells - 3;
			
			$this->SetFont('Arial','B',$tam_font_Cells);//Fuente para clave


			//Fecha
			$determinacion = 'DETERMINACIÓN';
			$fecha = 'FECHA DE';
			$tam_fecha = $this->GetStringWidth($fecha);
			$tam_ancho_determinacion = 19; $tam_alto_determinacion = $this->GetStringWidth($determinacion)+3;

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
			$tam_iden = $this->GetStringWidth('IDENTIFICACION')+3;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_iden,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,utf8_decode('IDENTIFICACIÓN'),'L,R',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,utf8_decode('DEL CONCRETO'),'L,R',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,'','L,R,B',2,'C');

			$this->SetXY(($posicion_x + $tam_iden),$posicion_y);
			$volumen = "\n".'VOLUMEN'."\n".'m³';
			$tam_volumen = $this->GetStringWidth('VOLUMEN')+2;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_volumen,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,utf8_decode('VOLUMEN'),'L,R',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,utf8_decode('m³'),'L,R',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,'','L,R,B',2,'C');
			

			$this->SetXY(($posicion_x + $tam_volumen),$posicion_y);
			$hora_determinacion = 'DETERMINACIÓN';
			$hora = 'HORA DE LA';
			$tam_hora = $this->GetStringWidth($hora);
			$tam_ancho_hora_determinacion = 13; 
			$posicion_x = $posicion_x + $tam_volumen;
			$this->multicell($tam_ancho_hora_determinacion,$tam_alto_determinacion,'',1);
			//tENEMOS QUE PONER POR SEPARADO EL TEXTO DENTRO DE LA CELDA
			$this->TextWithDirection($posicion_x+($tam_ancho_hora_determinacion/2),$this->gety() - (($tam_alto_determinacion-$tam_hora)/2),utf8_decode($hora),'U');	
			$this->TextWithDirection($posicion_x+($tam_ancho_hora_determinacion/2)+3,$this->gety() - 2,utf8_decode($determinacion),'U');	


			$this->SetXY(($posicion_x + $tam_ancho_hora_determinacion),$posicion_y);
			$unidad = 'UNIDAD';
			$tam_unidad = $this->GetStringWidth('UNIDAD')+3;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_unidad,$tam_alto_determinacion,$unidad,1,2,'C');

			$this->SetXY(($posicion_x + $tam_unidad),$posicion_y);
			$provedor = 'PROVEDOR DEL'."\n".'CONCRETO';
			$tam_provedor = $this->GetStringWidth('PROVEDOR DEL')+6;
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
			$tam_salida = $this->GetStringWidth('SALIDA DE')+3;
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
			$tam_llegada = 205.9 - $posicion_x;
			$this->Ln(0);
		
			$this->SetFont('Arial','',$tam_font_Cells);//Fuente para clave
			//Definimos el array con los tamaños de cada celda para crear las duplas
			$arrayCampos = 	array(
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

			$this->putInfoTables(10,$tam_font_CellsRows,$arrayCampos,$tam_cellsTablesAlto,'tam_stringCarac');
		}

		function putCaracDetails(){
			$this->SetY(-100);	

			$tam_font_details = 7;
			$tam_font_inventario = 6.5;	
			$tam_inventarioAlto = $tam_font_inventario-2;

			//Tamaño de las herramientas, copie y pegue el mismo codigo que en el CCH para que esten del mismo tamaño


			

			$this->SetFont('Arial','B',$tam_font_details);
			$observaciones = 'OBSERVACIONES: ';
			$this->SetFont('Arial','',$tam_font_details);

			$this->multicell(0,($tam_font_details - 2.5),utf8_decode($observaciones.$this->getMaxString($tam_font_details,196 - $this->GetStringWidth($observaciones),'tam_stringCarac')),'B',2);

			$this->ln(4);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			//Instrumentos
			$this->SetFont('Arial','B',$tam_font_inventario);
			$instrumentos = 'Inventario de'."\n".'instrumentos';
			$tam_instrumentos = $this->GetStringWidth('Inventario de')+5;
			$this->multicell($tam_instrumentos,$tam_font_inventario - 2,$instrumentos,1,'C');

			$this->SetFont('Arial','',$tam_font_inventario);
			$termo = 'Termómetro';
			$tam_termo = $this->GetStringWidth($termo)+10;

			$cono = 'Cono';
			$tam_cono = $tam_termo;

			$varilla = 'Varilla';
			$tam_varilla = $tam_termo;

			$flexometro = 'Flexometro';
			$tam_flexometro = $tam_termo;

			$this->SetXY(($posicion_x + $tam_instrumentos),$posicion_y);
			$cono = 'Cono';
			$this->SetFont('Arial','B',$tam_font_inventario);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->cell($tam_cono,($tam_font_inventario - 2),$cono,1,2,'C');
			$this->SetFont('Arial','',$tam_font_inventario);
			$this->cell($tam_cono,$tam_inventarioAlto,$this->getMaxString($tam_font_inventario,$tam_cono,'tam_stringCarac'),1,2,'C');

			$this->SetXY(($posicion_x + $tam_cono),$posicion_y);
			$varilla = 'Varilla';
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->SetFont('Arial','B',$tam_font_inventario);
			$this->cell($tam_varilla,($tam_font_inventario - 2),$varilla,1,2,'C');
			$this->SetFont('Arial','',$tam_font_inventario);
			$this->cell($tam_varilla,$tam_inventarioAlto,$this->getMaxString($tam_font_inventario,$tam_varilla,'tam_stringCarac'),1,2,'C');


			$this->SetXY(($posicion_x + $tam_varilla),$posicion_y);
			$flexometro = 'Flexometro';
			$this->SetFont('Arial','B',$tam_font_inventario);
			$this->cell($tam_flexometro,($tam_font_inventario - 2),$flexometro,1,2,'C');
			$this->SetFont('Arial','',$tam_font_inventario);
			$this->cell($tam_flexometro,$tam_inventarioAlto,$this->getMaxString($tam_font_inventario,$tam_flexometro,'tam_stringCarac'),1,2,'C');


			//Lado derecho
			$this->SetFont('Arial','B',$tam_font_details);
			$tam_box = 90;
			$this->SetXY((-($tam_box+10)),$posicion_y);
			$simbologia = 'SIMBOLOGIA';
			$posicion_x = $this->GetX(); 
			$this->cell($tam_box,($tam_font_details - 2),$simbologia,'L,T,R',2,'C');
			$posicion_y = $this->GetY();
			$this->SetXY($posicion_x,$posicion_y);
			$this->cell($tam_box/3,($tam_font_details - 1),'CA = CON ADITIVO','L,B',0,'C');

			$this->cell($tam_box/3,($tam_font_details - 1),'RR = RESISTENCIA RAPIDA','B',0,'C');

			$this->cell($tam_box/3,($tam_font_details - 1),'N = NORMAL','B,R',0,'C');

			$this->ln(12);

			$tam_font_details = 8; 
			$this->SetFont('Arial','B',$tam_font_details);

			$posicion_y = $this->GetY();
			$tam_boxElaboro = 70;	$tam_first = 10; $tam_second = 20;
			$this->SetX(20); $posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'ELABORO','L,T,R',2,'C');
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX(); 

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('________________________________'));	
			$this->TextWithDirection($tam_boxElaboro/2,$this->gety() - 3,utf8_decode('SIGNATARIO/LABORATORISTA'));	
			
			//Tamaño de la imagen
			$tam_image = 25;
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);


			$tam_font_details = 8; 
			$tam_boxCliente = 70;
			$this->SetXY(120,$posicion_y);
			$posicion_y = $this->GetY();
			$this->cell($tam_boxCliente,$tam_first,'ENTERADO(CLIENTE)','L,T,R',2,'C');
			$this->cell($tam_boxCliente,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX(); 

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('________________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxCliente /2))-($this->GetStringWidth('NOMBRE DE QUIEN RECIBE')/2),$this->gety() - 3,utf8_decode('NOMBRE DE QUIEN RECIBE'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxCliente)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
		


			
		}


		function putTables($infoFormato,$regisFormato,$infoU){
			$tam_font_Cells = 6.5;
			$tam_font_CellsRows = 5;
			$tam_cellsTablesAlto = $tam_font_Cells - 3;
			
			$this->SetFont('Arial','B',$tam_font_Cells);//Fuente para clave


			//Fecha
			$determinacion = 'DETERMINACIÓN';
			$fecha = 'FECHA DE';
			$tam_fecha = $this->GetStringWidth($fecha);
			$tam_ancho_determinacion = 19; $tam_alto_determinacion = $this->GetStringWidth($determinacion)+3;

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
			$iden = "\n".'IDENTIFICACIÓN'."\n".'DEL CONCRETO';
			$tam_iden = $this->GetStringWidth('IDENTIFICACION')+3;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_iden,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,utf8_decode('IDENTIFICACIÓN'),'L,R',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,utf8_decode('DEL CONCRETO'),'L,R',2,'C');
			$this->cell($tam_iden,$tam_alto_determinacion/4,'','L,R,B',2,'C');

			$this->SetXY(($posicion_x + $tam_iden),$posicion_y);
			$volumen = "\n".'VOLUMEN'."\n".'m³';
			$tam_volumen = $this->GetStringWidth('VOLUMEN')+2;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_volumen,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,utf8_decode('VOLUMEN'),'L,R',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,utf8_decode('m³'),'L,R',2,'C');
			$this->cell($tam_volumen,$tam_alto_determinacion/4,'','L,R,B',2,'C');
			

			$this->SetXY(($posicion_x + $tam_volumen),$posicion_y);
			$hora_determinacion = 'DETERMINACIÓN';
			$hora = 'HORA DE LA';
			$tam_hora = $this->GetStringWidth($hora);
			$tam_ancho_hora_determinacion = 13; 
			$posicion_x = $posicion_x + $tam_volumen;
			$this->multicell($tam_ancho_hora_determinacion,$tam_alto_determinacion,'',1);
			//tENEMOS QUE PONER POR SEPARADO EL TEXTO DENTRO DE LA CELDA
			$this->TextWithDirection($posicion_x+($tam_ancho_hora_determinacion/2),$this->gety() - (($tam_alto_determinacion-$tam_hora)/2),utf8_decode($hora),'U');	
			$this->TextWithDirection($posicion_x+($tam_ancho_hora_determinacion/2)+3,$this->gety() - 2,utf8_decode($determinacion),'U');	


			$this->SetXY(($posicion_x + $tam_ancho_hora_determinacion),$posicion_y);
			$unidad = 'UNIDAD';
			$tam_unidad = $this->GetStringWidth('UNIDAD')+3;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_unidad,$tam_alto_determinacion,$unidad,1,2,'C');

			$this->SetXY(($posicion_x + $tam_unidad),$posicion_y);
			$provedor = 'PROVEEDOR DEL'."\n".'CONCRETO';
			$tam_provedor = $this->GetStringWidth('PROVEDOR DEL')+6;
			$posicion_x = $this->GetX(); 
			$this->cell($tam_provedor,$tam_alto_determinacion/4,'','L,R,T',2,'C');
			$this->cell($tam_provedor,$tam_alto_determinacion/4,utf8_decode('PROVEEDOR DEL'),'L,R',2,'C');
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
			$tam_salida = $this->GetStringWidth('SALIDA DE')+3;
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
			$tam_llegada = 205.9 - $posicion_x;
			$this->Ln(0);
		
			$this->SetFont('Arial','',$tam_font_Cells);//Fuente para clave
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


			

			$this->SetFont('Arial','',$tam_font_CellsRows);
			$num_rows = 0;
			foreach ($regisFormato as $registro) {
				$j=0;

				foreach ($registro as $campo) {

					$resultado = $this->printInfoObraAndLocObra($tam_font_CellsRows,$array_campo[$j],$tam_cellsTablesAlto,$campo,1);


					$this->SetFont('Arial','',$resultado['sizeFont']);
					$campo = $resultado['new_string'];
	
					$this->error = $resultado['error'];
					

	
					$this->cell($array_campo[$j],$tam_cellsTablesAlto,utf8_decode($campo),1,0,'C');
					$j++;
				}
				$num_rows++;
				$this->Ln();
			}

			if($num_rows<10){
				//Cancelacion
				$posicion_xLine = $this->GetX();
				$posicion_yLine = $this->GetY();

				for ($i=0; $i < (10-$num_rows); $i++){
					//Definimos la posicion de X para tomarlo como referencia
					for ($j=0; $j < sizeof($array_campo); $j++){ 
						//Definimos la posicion apartir de la cual vamos a insertar la celda
						if($j < sizeof($array_campo)){
							$this->cell($array_campo[$j],$tam_cellsTablesAlto,'',1,0,'C');
						}
						
					}
					$posicion_xEnd = $this->GetX();		$posicion_yEnd = $this->GetY() + $tam_cellsTablesAlto;
					$this->Ln();
				}
				$this->Line($posicion_xLine, $posicion_yLine, $posicion_xEnd, $posicion_yEnd);//Linea que cancela
				
			}
			
			$this->Ln(4);


			//$this->SetY(-100);	

			$tam_font_details = 7;
			$tam_font_inventario = 6.5;	
			$tam_inventarioAlto = $tam_font_inventario-2;

			//Tamaño de las herramientas, copie y pegue el mismo codigo que en el CCH para que esten del mismo tamaño
			$this->SetFont('Arial','B',$tam_font_details);
			$observaciones = 'OBSERVACIONES:';
			$tam_observacionesAncho = $this->GetStringWidth($observaciones)+2;
			$tam_observacionAnchoTxt = 	196 - $tam_observacionesAncho;

			//Observaciones
			$this->Cell($tam_observacionesAncho,2*($tam_font_details - 2.5),$observaciones,'L,B,T',0,'C');

			$alto_obsercaciones = ($tam_font_details - 2.5);


			$resultado = $this->printInfoObraAndLocObra($tam_font_details,$tam_observacionAnchoTxt,$alto_obsercaciones,$infoFormato['observaciones'],2);


			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['observaciones'] = $resultado['new_string'];

			$this->error = $resultado['error'];
			

			if(array_key_exists('Total de renglones que serian', $resultado)){
				if($resultado['Total de renglones que serian'] == 1){
					$alto_obsercaciones = $alto_obsercaciones*2;
				}
			}
			
			$this->multicell(0,$alto_obsercaciones,utf8_decode(	$infoFormato['observaciones']	),'T,R,B');
		
			$this->ln(4);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			//Instrumentos
			$this->SetFont('Arial','B',$tam_font_inventario);
			$instrumentos = 'Inventario de'."\n".'instrumentos';
			$tam_instrumentos = $this->GetStringWidth('Inventario de')+5;
			$this->multicell($tam_instrumentos,$tam_font_inventario - 2,$instrumentos,1,'C');

			$this->SetFont('Arial','',$tam_font_inventario);
			$termo = 'Termómetro';
			$tam_termo = $this->GetStringWidth($termo)+10;

			$cono = 'Cono';
			$tam_cono = $tam_termo;

			$varilla = 'Varilla';
			$tam_varilla = $tam_termo;

			$flexometro = 'Flexometro';
			$tam_flexometro = $tam_termo;

			$this->SetXY(($posicion_x + $tam_instrumentos),$posicion_y);
			$cono = 'Cono';
			$this->SetFont('Arial','B',$tam_font_inventario);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->cell($tam_cono,($tam_font_inventario - 2),$cono,1,2,'C');
			$this->SetFont('Arial','',$tam_font_inventario);
			$this->cell($tam_cono,$tam_inventarioAlto,utf8_decode($infoFormato['CONO']),1,2,'C');

			$this->SetXY(($posicion_x + $tam_cono),$posicion_y);
			$varilla = 'Varilla';
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$this->SetFont('Arial','B',$tam_font_inventario);
			$this->cell($tam_varilla,($tam_font_inventario - 2),$varilla,1,2,'C');
			$this->SetFont('Arial','',$tam_font_inventario);
			$this->cell($tam_varilla,$tam_inventarioAlto,utf8_decode($infoFormato['VARILLA']),1,2,'C');


			$this->SetXY(($posicion_x + $tam_varilla),$posicion_y);
			$flexometro = 'Flexometro';
			$this->SetFont('Arial','B',$tam_font_inventario);
			$this->cell($tam_flexometro,($tam_font_inventario - 2),$flexometro,1,2,'C');
			$this->SetFont('Arial','',$tam_font_inventario);
			$this->cell($tam_flexometro,$tam_inventarioAlto,utf8_decode($infoFormato['FLEXOMETRO']),1,2,'C');


			//Lado derecho
			$this->SetFont('Arial','B',$tam_font_details);
			$tam_box = 90;
			$this->SetXY((-($tam_box+10)),$posicion_y);
			$simbologia = 'SIMBOLOGIA';
			$posicion_x = $this->GetX(); 
			$this->cell($tam_box,($tam_font_details - 2),$simbologia,'L,T,R',2,'C');
			$posicion_y = $this->GetY();
			$this->SetXY($posicion_x,$posicion_y);
			$this->cell($tam_box/3,($tam_font_details - 1),'CA = CON ADITIVO','L,B',0,'C');

			$this->cell($tam_box/3,($tam_font_details - 1),'RR = RESISTENCIA RAPIDA','B',0,'C');

			$this->cell($tam_box/3,($tam_font_details - 1),'N = NORMAL','B,R',0,'C');

			$this->ln(12);

			$tam_font_details = 8; 
			$this->SetFont('Arial','B',$tam_font_details);

			$posicion_y = $this->GetY();
			$tam_boxElaboro = 70;	$tam_first = 10; $tam_second = 20;


			$this->SetX((216 - $tam_boxElaboro)/2); $posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'ELABORO','L,T,R',2,'C');
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX(); 

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('________________________________'));	

			$tam_sig = $this->GetStringWidth('SIGNATARIO/LABORATORISTA');

			$this->TextWithDirection((216 - $tam_sig)/2,$this->gety() - 3,utf8_decode('SIGNATARIO/LABORATORISTA'));	

			if($infoU['nombreRealizo'] != "null"){
				/*
					-Restamos -2 a el ancho de la celda porque no contemple las negritas, entonces como esta vez imprimire negritas el espacio de la letra aumenta.
					-Ponemos la altura de la celda del mismo tamaño que el de la letra ya que no existe una celda como tal en la que va el texto, y el alto de la celda no repercute en el resultado.
				*/
				$resultado = $this->printInfoObraAndLocObra($tam_font_details,$tam_boxElaboro-2,$tam_font_details,$infoU['nombreRealizo'],1);

				$this->SetFont('Arial','B',$resultado['sizeFont']);
				$infoU['nombreRealizo'] = $resultado['new_string'];

				$this->error = $resultado['error'];

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreRealizo'])/2),$this->gety() - 12,utf8_decode($infoU['nombreRealizo']));		
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('No hay nombre.')/2),$this->gety() - 12,utf8_decode("No hay nombre."));	
			}

			$this->SetFont('Arial','B',$tam_font_details);

			//Tamaño de la imagen
			$tam_image = 25;

			if($infoU['firmaRealizo'] != "null"){
				
				$this->Image($infoU['firmaRealizo'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}
			else{

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'));	

			}
			
		}
		
		function Footer(){
			$this->SetY(-15);
		    $this->SetFont('Arial','',8);
		    $noPagina = 'Pág. '.$this->PageNo().' de {nb}';
		    $tam_noPagina = $this->GetStringWidth($noPagina);
		    $posicion_x = (216 - $tam_noPagina)/2;
		    $this->SetX($posicion_x);
		    $this->Cell($tam_noPagina,10,utf8_decode($noPagina),0,0,'C');

		    //Clave de validacion
		    $clave = 'FI-02-LCC-02-0.1';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');
			

			
		}
		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato,$infoU,$target_dir){
			$pdf  = new Revenimiento('P','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($infoFormato,$regisFormato,$infoU);
			//$pdf->Output('F',$target_dir);
			$pdf->Output();
			return $pdf->error;
		}


		
	}
	

?>