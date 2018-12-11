<?php 

	/*
		PENDIENTE1:
						EL TEXTO QUE APARECE DEBAJO DE LA INFORMACION GENERAL DE L FORMATO ESTA A UNA MEDIDA DE 8, PERO DEBE DE SER UNA MEDIDA DE 7 SIGUIENDO LA ESTANDARIZACION DE TODOS LOS FORMATOS
	*/

	
	include_once("./../../FPDF/MyPDF.php");

	class InformeRevenimiento extends MyPDF{
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

		function demo(){
			$pdf  = new InformeRevenimiento('P','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putCaracInfo();
			$pdf->putCaracCampos();
			$pdf->putCaracDetails();
			$pdf->Output();
		}

		function generateCellsInfoForvalidation(){
			$pdf  = new InformeRevenimiento('P','mm','Letter');
			$pdf->AddPage();
			return $pdf->generateCellsInfo();
		}

		function generateCellsInfo(){
			


			$tam_font_right = 7.5;	
			$this->SetFont('Arial','B',$tam_font_right);
			$separacion = 50; //Separacion que vaa tener el Registro numero del margen

			//Registro Numero
			$regNo = 'REG. No.';
			$tam_regNo = $this->GetStringWidth($regNo)+3;
			$tam_regNoText = $separacion - 10;



			//Incertidumbre
			$this->SetFont('Arial','B',$tam_font_right);
			$incertidumbre = 'INCERTIDUMBRE:';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+3;
			$tam_incertidumbreText = $separacion - 10;


			//PARTE IZQUIERDA DEL FORMATO
			$posicionCellsText  = 50;
			$linea_Text = 216 - ($posicionCellsText+10);

			$tam_font_left = 7;	
			$this->SetFont('Arial','B',$tam_font_left);
			
			$obra = 'NOMBRE DE LA OBRA:';
			$tam_obra = $this->GetStringWidth($obra)+2;

			$locObra = 'LOCALIZACIÓN DE LA OBRA:';
			$tam_locObra = $this->GetStringWidth($locObra)+2;

			$nomCli = 'NOMBRE DEL CLIENTE:';
			$tam_nomCli = $this->GetStringWidth($nomCli)+2;
		
			$dirCliente = 'DIRECCIÓN DEL CLIENTE:';
			$tam_dirCliente = $this->GetStringWidth($nomCli)+2;

			$tam_nomObraText = $tam_localizacionText = $tam_razonText = $tam_dirClienteText = $tam_eleColadoText = $linea_Text;

			//$tam_font_left = 8; PENDIENTE1	
			$this->SetFont('Arial','',$tam_font_left);
			//Texto Adicional
			$texto_adicional = 'SE DETERMINA EL REVENIMIENTO EN CONCRETO FRESCO TOMANDO COMO BASE LA NORMA MEXICANA NMX-C-156-ONNCCE-2010';
			$tam_texto_adicional = $this->GetStringWidth($texto_adicional)+3;


			$this->SetFont('Arial','B',$tam_font_left);
			
			$localizacion = 'LOCALIZACIÓN:';
			$tam_localizacion = $this->GetStringWidth($localizacion)+3;

			//Caja de texto
			$tam_localizacionText = 196 - $tam_localizacion;

			$tam_CellsRightAlto = $tam_font_right - 4;
			$tam_CellsLeftAlto = $tam_font_left - 4;

			$this->cellsInfo 	= 	array(

											'separacion'		=>	$separacion,
											'posicionCellsText'	=>	$posicionCellsText,


											'tam_font_right'				=>	$tam_font_right,
											'tam_font_left'					=>	$tam_font_left,

											'tam_CellsRightAlto'			=>	$tam_CellsRightAlto,

											'tam_CellsLeftAlto'				=>	$tam_CellsLeftAlto,


											'regNo'							=>	$regNo,
											'tam_regNo'						=>	$tam_regNo,
											'tam_regNoText'					=>	$tam_regNoText,

											'incertidumbre'					=>	$incertidumbre,
											'tam_incertidumbre'				=>	$tam_incertidumbre,
											'tam_incertidumbreText'			=>	$tam_incertidumbreText,


											'obra'					=> $obra,
											'tam_obra'				=> $tam_obra,

											'locObra'				=> $locObra,
											'tam_locObra'			=> $tam_locObra,

											'nomCli'				=> $nomCli,
											'tam_nomCli'			=> $tam_nomCli,

											'dirCliente'			=> $dirCliente,
											'tam_dirCliente'		=> $tam_dirCliente,


											'tam_nomObraText'			=>	$tam_nomObraText,
											'tam_localizacionText'		=>	$tam_localizacionText,
											'tam_razonText'				=>	$tam_razonText,
											'tam_dirClienteText'		=>	$tam_dirClienteText
									);

			return $this->cellsInfo;
		}

		function getCellsTables(){
			return $this->cellsTables;
		}

		function generateCellsCampos(){
			//Tamaños de fuente para los letreros de las celdas y para el contenido de las celdas
			$tam_font_Cells = 6.5;
			$tam_font_CellsRows = 5;
			$tam_cellsTablesAlto = $tam_font_Cells - 3;

			//Configuramos el tamaño de fuente
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
			$tam_iden = $this->GetStringWidth('IDENTIFICACION');
			$tam_anchoIden = $tam_iden-11;

			//Volumen
			$volumen = 'VOLUMEN m³';
			$tam_volumen = $this->GetStringWidth($volumen);
			$tam_volumenAncho = $tam_volumen - 8;
	
			//Hora de la determinacion
			$hora_determinacion = 'DETERMINACIÓN';
			$hora = 'HORA DE LA';
			$tam_hora = $this->GetStringWidth($hora);
			$tam_ancho_hora_determinacion = 13; 

			//Unidad
			$unidad = 'UNIDAD';
			$tam_unidadAncho = $this->GetStringWidth('UNIDAD')+2;
			
			//Provedor del concreto
			$provedor = 'PROVEDOR DEL'."\n".'CONCRETO';
			$tam_provedor = $this->GetStringWidth('PROVEDOR DEL')+24;
		
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

		
		function Header(){
			$ancho_ema = 50;	$alto_ema = 20;
			$tam_lacocs = 20;
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de Lacocs
			$posicion_x = $this->GetX();

			$this->Image('./../../disenoFormatos/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$this->TextWithDirection($this->GetX(),$this->gety() + 24,utf8_decode('LACOCS S.A. DE C.V.'));	

			$this->Image('./../../disenoFormatos/ema.jpeg',206-$ancho_ema,$this->GetY(),$ancho_ema,$alto_ema);
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

		

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.

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

			//Incertidumbre
			$this->SetFont('Arial','B',$tam_font_right);
			$incertidumbre = 'INCERTIDUMBRE:';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+3;
			$tam_incertidumbreText = $separacion - 10;

			$this->SetX(-($tam_incertidumbre + $separacion));

			$this->cell($tam_incertidumbre,$tam_font_right - 4,utf8_decode($incertidumbre));



			//Caja de texto
			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 4,$this->getMaxString($tam_font_right,$tam_incertidumbreText,'tam_stringCarac'),'B',0,'C');

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

			$string = 'hola'."\n".'como'."\n"."estas?";
			$this->multicell($linea_Text,$tam_font_left - 4,$string,'B','C');
			$this->Ln(1);

			$locObra = 'LOCALIZACIÓN DE LA OBRA:';
			$this->SetFont('Arial','B',$tam_font_left);
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto
			$this->SetX($posicionCellsText);
			$this->SetFont('Arial','',$tam_font_left);
			$this->multicell($linea_Text,$tam_font_left - 4,$string,'B','C');

			$this->Ln(1);

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
			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 4,utf8_decode(	$infoFormato['regNo']	),'B',0,'C');

			$this->Ln($tam_font_right - 2);

			//Incertidumbre
			$this->SetFont('Arial','B',$tam_font_right);
			$incertidumbre = 'INCERTIDUMBRE:';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+3;
			$tam_incertidumbreText = $separacion - 10;

			$this->SetX(-($tam_incertidumbre + $separacion));

			$this->cell($tam_incertidumbre,$tam_font_right - 4,utf8_decode($incertidumbre));

			//Caja de texto
			$this->SetFont('Arial','',$tam_font_right);
			$this->Cell(0,$tam_font_right - 4,utf8_decode(	$infoFormato['incertidumbre']	),'B',0,'C');

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

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,$linea_Text,$tam_font_left - 4,$infoFormato['obra'],3);

			if($resultado['error'] == 0){
				$this->SetFont('Arial','',$resultado['sizeFont']);
			}else{
				$this->SetFont('Arial','B',$tam_font_left);
				$infoFormato['obra'] = $resultado['estatus'];
			}

		
			$this->multicell($linea_Text,$tam_font_left - 4,utf8_decode(	$infoFormato['obra']	),'B','C');

			$this->Ln(1);

			$locObra = 'LOCALIZACIÓN DE LA OBRA:';
			$this->SetFont('Arial','B',$tam_font_left);
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto

			$this->SetX($posicionCellsText);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,$linea_Text,$tam_font_left - 4,$infoFormato['locObra'],3);

			if($resultado['error'] == 0){
				$this->SetFont('Arial','',$resultado['sizeFont']);
			}else{
				$this->SetFont('Arial','B',$tam_font_left);
				$infoFormato['locObra'] = $resultado['estatus'];
			}

			$this->multicell($linea_Text,$tam_font_left - 4,utf8_decode(	$infoFormato['locObra']	),'B','C');

			$this->Ln(1);

			$this->SetFont('Arial','B',$tam_font_left);
			$nomCli = 'NOMBRE DEL CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);
			//Caja de texto
			$this->SetX($posicionCellsText);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell($linea_Text,$tam_font_left - 4,utf8_decode(	$infoFormato['razonSocial']	),'B',0);

			$this->Ln(4);
			//Direccion del cliente
			$this->SetFont('Arial','B',$tam_font_left);
			$dirCliente = 'DIRECCIÓN DEL CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX($posicionCellsText);
			$this->SetFont('Arial','',$tam_font_left);
			$this->Cell($linea_Text,$tam_font_left - 4,utf8_decode(	$infoFormato['direccion']	),'B',0);

			$this->ln(8);


			$tam_font_left = 7;	
			$this->SetFont('Arial','',$tam_font_left);

			//Texto Adicional
			$texto_adicional = 'SE DETERMINA EL REVENIMIENTO EN CONCRETO FRESCO TOMANDO COMO BASE LA NORMA MEXICANA NMX-C-156-ONNCCE-2010';
			$tam_texto_adicional = $this->GetStringWidth($texto_adicional)+3;
			//$this->Cell($tam_texto_adicional,$tam_font_left - 3,utf8_decode($texto_adicional),0);
			//$this->Ln(8);

			$localizacion = 'ELEMENTO COLADO:';
			//Caja de texto
			$this->SetFont('Arial','B',$tam_font_left);
			$tam_localizacion = $this->GetStringWidth($localizacion)+3;
			$tam_localizacionText = 196 - $tam_localizacion;

			$this->Cell($tam_localizacion,$tam_font_left - 3,utf8_decode($localizacion),0);
			//Caja de texto
			$this->SetFont('Arial','',$tam_font_left);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,$tam_localizacionText,$tam_font_left - 4,$infoFormato['locRev'],3);


			if($resultado['error'] == 0){
				$this->SetFont('Arial','',$resultado['sizeFont']);
			}else{
				$this->SetFont('Arial','B',$tam_font_left);
				$infoFormato['locRev'] = $resultado['estatus'];
			}

			$this->multicell($tam_localizacionText,$tam_font_left - 4,utf8_decode(	$infoFormato['locRev']	),'B','C');

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
			$tam_iden = $this->GetStringWidth('IDENTIFICACION');
			$posicion_x = $this->GetX(); 
			$this->cell($tam_iden-11,$tam_alto_determinacion,'',1,2,'C');
			$this->TextWithDirection($posicion_x+(($tam_iden-11)/2),$this->gety() - (($tam_alto_determinacion-$tam_iden)/2),utf8_decode('IDENTIFICACIÓN'),'U');	
			$this->TextWithDirection($posicion_x+(($tam_iden-11)/2)+3,$this->gety() - 2,utf8_decode('DEL CONCRETO'),'U');
			$tam_iden-=11;	

		
			$this->SetXY(($posicion_x + $tam_iden),$posicion_y);
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$volumen = 'VOLUMEN m³';
			$tam_volumen = $this->GetStringWidth($volumen);
			$posicion_x = $this->GetX(); 
			$this->cell($tam_volumen - 8,$tam_alto_determinacion,'',1,2,'C');
			$this->TextWithDirection($posicion_x+(($tam_volumen - 8)/2),$this->gety() - (($tam_alto_determinacion-$tam_volumen)/2),utf8_decode($volumen),'U');

			$tam_volumen-=8;//Asignamos la medida que debe ser, debido a que la anterior es para una mejor centracion del texto en vertical	

		
			

			$this->SetXY(($posicion_x + ($tam_volumen)),$posicion_y);
			$hora_determinacion = 'DETERMINACIÓN';
			$hora = 'HORA DE LA';
			$tam_hora = $this->GetStringWidth($hora);
			$tam_ancho_hora_determinacion = 13; 
			$posicion_x = $posicion_x + ($tam_volumen);
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
			$tam_provedor = $this->GetStringWidth('PROVEDOR DEL')+24;
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
			$iden = "\n".'IDENTIFICACION'."\n".'DEL CONCRETO';
			$tam_iden = $this->GetStringWidth('IDENTIFICACION');
			$posicion_x = $this->GetX(); 
			$this->cell($tam_iden-11,$tam_alto_determinacion,'',1,2,'C');
			$this->TextWithDirection($posicion_x+(($tam_iden-11)/2),$this->gety() - (($tam_alto_determinacion-$tam_iden)/2),utf8_decode('IDENTIFICACIÓN'),'U');	
			$this->TextWithDirection($posicion_x+(($tam_iden-11)/2)+3,$this->gety() - 2,utf8_decode('DEL CONCRETO'),'U');
			$tam_iden-=11;	

		
			$this->SetXY(($posicion_x + $tam_iden),$posicion_y);
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$volumen = 'VOLUMEN m³';
			$tam_volumen = $this->GetStringWidth($volumen);
			$posicion_x = $this->GetX(); 
			$this->cell($tam_volumen - 8,$tam_alto_determinacion,'',1,2,'C');
			$this->TextWithDirection($posicion_x+(($tam_volumen - 8)/2),$this->gety() - (($tam_alto_determinacion-$tam_volumen)/2),utf8_decode($volumen),'U');

			$tam_volumen-=8;//Asignamos la medida que debe ser, debido a que la anterior es para una mejor centracion del texto en vertical	

		
			

			$this->SetXY(($posicion_x + ($tam_volumen)),$posicion_y);
			$hora_determinacion = 'DETERMINACIÓN';
			$hora = 'HORA DE LA';
			$tam_hora = $this->GetStringWidth($hora);
			$tam_ancho_hora_determinacion = 13; 
			$posicion_x = $posicion_x + ($tam_volumen);
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
			$tam_provedor = $this->GetStringWidth('PROVEDOR DEL')+24;
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
			$metodos = 'METODOS EMPLEADOS: NMX-C-156-ONNCCE-2010, NMX-C-161-ONNCCE-2013';
			$this->cell($this->GetStringWidth($metodos)+15,10,$metodos,1,0);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			


			//Lado derecho

			$tam_box = 206-$posicion_x;
			$this->SetXY((-($tam_box+10)),$posicion_y);
			$simbologia = 'SIMBOLOGIA';
			$posicion_x = $this->GetX(); 
			$this->cell($this->GetStringWidth($simbologia)+2,10,$simbologia,'L,T,B',0,'C');
			$posicion_y = $this->GetY();




			$this->SetXY($posicion_x+$this->GetStringWidth($simbologia)+2,$posicion_y);
			$this->cell(0,10/3,'CA = Con aditivo','R',0);
			$this->SetXY($posicion_x+$this->GetStringWidth($simbologia)+2,$posicion_y+10/3);
			$this->cell(0,10/3,'RR = Resistencia Rapida','R',0);
			$this->SetXY($posicion_x+$this->GetStringWidth($simbologia)+2,$posicion_y+2*(10/3));
			$this->cell(0,10/3,'N = Normal','B,R',0);
			$this->Ln(0);
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			$this->SetXY($posicion_x,$posicion_y+10/3);
			
			$this->multicell(0,4,utf8_decode('ESTE INFORME DE RESULTADOS SE REFIERE EXCLUSIVAMENTE AL ENSAYE REALIZADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS, Y SOLO TIENE VALIDES SI NO PRESENTA TACHADURAS O ENMIENDAS'),1,2);



			$this->Cell(0,10,'',0,2	);
			$tam_image = 20;
			$tam_font_footer = 8; $this->SetFont('Arial','B',$tam_font_footer);
			
			$tam_boxElaboro = 196/3;	$tam_first = 12.5; $tam_second = 12.5;


			$this->SetX($this->GetX() + $tam_boxElaboro/2);

			$posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('_____________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	

			if($infoU['nombreLaboratorista'] != "null"){
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreLaboratorista'])/2),$this->gety() - 12,utf8_decode($infoU['nombreLaboratorista']));		
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('No hay nombre.')/2),$this->gety() - 12,utf8_decode("No hay nombre."));	
			}


			if($infoU['firmaLaboratorista'] != "null"){
				
				$this->Image($infoU['firmaLaboratorista'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}
			else{

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'))	;	

			}

			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('_____________________________'));	

			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	
			$this->SetFont('Arial','B',$tam_font_footer);

			if($infoU['nombreG'] != "null"){
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreG'])/2),$this->gety() - 12,utf8_decode($infoU['nombreG']));	
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('No hay nombre.')/2),$this->gety() - 12,utf8_decode("No hay nombre."));	
			}
			

			if($infoU['firmaG'] != "null"){
				$this->Image($infoU['firmaG'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'));	
			}

			
					
		}

		function getcellsDetails(){
			return $this->cellsDetails;
		}

		function generateCellsDetails(){
			$tam_font_details = 7;	
			$tam_observacionAnchoTxt =  195.9;

			$this->cellsDetails = array(
											'tam_font_details' => $tam_font_details,
											'tam_observacionAnchoTxt' => $tam_observacionAnchoTxt
									);	

		}

		function putCaracDetails(){
			$this->ln(5);	
			$tam_font_details = 7;	
			$tam_observaciones = 5;
	
			$this->SetFont('Arial','B',$tam_font_details);
			$observaciones = 'OBSERVACIONES:';

			//Observaciones
			//$this->SetY($posicion_y);
			$this->cell(0,2*($tam_font_details - 2.5),$observaciones,'L,T,R',2);
			$this->SetFont('Arial','',$tam_font_details);
			$this->cell(0,$tam_observaciones,$this->getMaxString($tam_font_details,196,'tam_stringCarac'),'L,B,R',2);

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
			$this->cell(0,10/3,'N = NORMAL','B,R',0);
			$this->Ln(0);
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			$this->SetXY($posicion_x,$posicion_y+10/3);
			
			$this->multicell(0,4,utf8_decode('ESTE INFORME DE RESULTADOS SE REFIERE EXCLUSIVAMENTE AL ENSAYE REALIZADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS, Y SOLO TIENE VALIDES SI NO PRESENTA TACHADURAS O ENMIENDAS'),1,2);



			$this->Cell(0,10,'',0,2	);
			$tam_image = 20;
			$tam_font_details = 8; $this->SetFont('Arial','B',$tam_font_details);
			
			$tam_boxElaboro = 196/3;	$tam_first = 12.5; $tam_second = 12.5;
			$posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'REALIZO','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->SetFont('Arial','',$tam_font_details);
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('_____________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			
			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->SetFont('Arial','B',$tam_font_details);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('_____________________________'));	

			$this->SetFont('Arial','',$tam_font_details);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	
			$this->SetFont('Arial','B',$tam_font_details);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('M en I. MARCO ANTONIO CERVANTES M.')/2),$this->gety() - 12,utf8_decode('M en I. MARCO ANTONIO CERVANTES M.'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->SetFont('Arial','',$tam_font_details);



			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);

			$this->SetFont('Arial','B',$tam_font_details);
			$this->cell($tam_boxElaboro,$tam_first,'RECIBE','L,T,R',2,'C');
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX();
			$this->SetFont('Arial','',$tam_font_details);
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('_____________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NOMBRE DE QUIEN RECIBE')/2),$this->gety() - 3,utf8_decode('NOMBRE DE QUIEN RECIBE'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
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
		function CreateNew($infoFormato,$regisFormato,$infoU,$target_dir){
			$pdf  = new InformeRevenimiento('P','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($infoFormato,$regisFormato,$infoU);
			$pdf->Output('F',$target_dir);
			//$pdf->Output();
		}


		
	}
	
	
	


?>