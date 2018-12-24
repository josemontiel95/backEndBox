<?php 
	include_once("./../../FPDF/MyPDF.php");
	//include_once("./../FPDF/fpdf.php");
	//Formato de campo de cilindros
	class InformeVigas extends MyPDF{
		/*
			Informacion extra:
								-Ancho de una celda cuando su ancho = 0 : 259.3975
								-Se omitio el generador de celdas el numero de campos a cnsiderar no es tan extenso, ademas el tiene un mejor diseño y es facil de modificar
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

		//Variable que nos dira si ocurrio un error al generar el formato
		public $error = 0;

		//Variable donde guardaremos la informacion necesario para poner las firmas
		public $infoU;
		


		function generateCellsInfoForvalidation(){
			$pdf  = new InformeVigas('L','mm','Letter');
			$pdf->AddPage();
			return $pdf->generateCellsInfo();
		}

		function demo(){
			$pdf  = new InformeVigas('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->generateCellsInfo();
			//Generador de los campos no se aplico debido a la simplesa y la mejor elaboracion del codigo
			//$pdf->generateCellsDetails();
			$pdf->putCaracInfo();
			$pdf->putCaracCampos();
			$pdf->putCaracDetails();
			$pdf->Output();
		}

		function getCellsInfo(){
			return $this->cellsInfo;
		}

		function getcellsDetails(){
			return $this->cellsDetails;
		}

		function generateCellsDetails(){
			$tam_font_details = 7;
			$this->SetFont('Arial','B',$tam_font_details);

			$observaciones = 'OBSERVACIONES:';
			$tam_observacionesAncho = $this->GetStringWidth($observaciones)+2;
			$tam_observacionAnchoTxt = 	259.3975 - $tam_observacionesAncho;

			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			$this->cellsDetails = array(
											'tam_font_details'			=>	$tam_font_details,
											'observaciones'				=>	$observaciones,
											'tam_observacionesAncho'	=>	$tam_observacionesAncho,
											'tam_observacionAnchoTxt'	=>	$tam_observacionAnchoTxt
										);
		}

		function generateCellsInfo(){
			/*
			Lado derecho:
							-Informe No.
							-Este informe sustituye a:
			*/
			$tam_font_right = 7.5;
			$tam_CellsRightAlto =	$tam_font_right - 3;	
			$this->SetFont('Arial','B',$tam_font_right);

			//Separacion del margen
			$separacion = 50;

			//Numero del informe
			$informeNo = 'INFORME:';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;

			$fechaEnsaye = 'FECHA DE ENSAYE:';
			$tam_fechaEnsaye = $this->GetStringWidth($fechaEnsaye)+6;

			$tipoConcreto = 'TIPO DE CONCRETO:';
			$tam_tipoConcreto = $this->GetStringWidth($tipoConcreto)+6;

			$mrProyecto = 'MR DE PROYECTO:';
			$tam_mrProyecto = $this->GetStringWidth($mrProyecto)+6;

			//-Informe al cual sustituye
			$sustituyeInforme = 'ESTE INFORME SUSTITUYE A:';
			$tam_sustituyeInforme = $this->GetStringWidth($sustituyeInforme)+6;

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/
			$posicionCellsText = 50;

			$tam_font_left = 7;	
			$tam_CellsLeftAlto = $tam_font_left - 4;
			$this->SetFont('Arial','',$tam_font_left);
			
			$obra = 'NOMBRE DE LA OBRA:';
			$tam_obra = $this->GetStringWidth($obra)+2;

			$locObra = 'LOCALIZACIÓN DE LA OBRA:';
			$tam_locObra = $this->GetStringWidth($locObra)+2;

			$nomCli = 'NOMBRE DEL CLIENTE:';
			$tam_nomCli = $this->GetStringWidth($nomCli)+2;
			
			$dirCliente = 'DIRECCIÓN DEL CLIENTE:';
			$tam_dirCliente = $this->GetStringWidth($nomCli)+2;

			$eleColado = 'ELEMENTO COLADO:';
			$tam_eleColado = $this->GetStringWidth($nomCli)+2;

			$tam_nomObraText = $tam_localizacionText = $tam_razonText = $tam_dirClienteText = $tam_elementoAncho = 135;
			$tam_informeText  = $tam_sustituyeInformeText = $tam_tipoConcretoText = $tam_fechaEnsayeText = $tam_fprimaAncho = $tam_sustituyeInformeText = $separacion - 10;

			$this->cellsInfo 	= 	array(
											'separacion'					=>	$separacion,
											'posicionCellsText'				=>	$posicionCellsText,

											'tam_font_right'				=>	$tam_font_right,
											'tam_font_left'					=>	$tam_font_left,

											'tam_CellsRightAlto'			=> $tam_CellsRightAlto,

											'tam_CellsLeftAlto'				=>	$tam_CellsLeftAlto,

											'obra'					=> $obra,
											'tam_obra'				=> $tam_obra,

											'locObra'				=> $locObra,
											'tam_locObra'			=> $tam_locObra,



											'informeNo'				=> $informeNo,
											'tam_informeNo'			=> $tam_informeNo,
											'tam_informeText'		=>	$tam_informeText,

											'sustituyeInforme'			=>	$sustituyeInforme,
											'tam_sustituyeInforme'		=>	$tam_sustituyeInforme,
											'tam_sustituyeInformeText'	=>	$tam_sustituyeInformeText,

											'nomCli'				=> $nomCli,
											'tam_nomCli'			=> $tam_nomCli,

											'dirCliente'			=> $dirCliente,
											'tam_dirCliente'		=> $tam_dirCliente,

											'fechaEnsaye'			=>	$fechaEnsaye,
											'tam_fechaEnsaye'		=>	$tam_fechaEnsaye,

											'tipoConcreto'			=>	$tipoConcreto,
											'tam_tipoConcreto'		=>	$tam_tipoConcreto,

											'mrProyecto'			=>	$mrProyecto,
											'tam_mrProyecto'		=>	$tam_mrProyecto,

											'eleColado'				=>	$eleColado,
											'tam_eleColado'			=>	$tam_eleColado,

											'tam_nomObraText'			=>	$tam_nomObraText,
											'tam_localizacionText'		=>	$tam_localizacionText,
											'tam_razonText'				=>	$tam_razonText,
											'tam_dirClienteText'		=>	$tam_dirClienteText,

											'tam_fechaEnsayeText'				=>	$tam_fechaEnsayeText,
											'tam_tipoConcretoText'				=>	$tam_tipoConcretoText,
											'tam_fprimaAncho'				=>	$tam_fprimaAncho,
											'tam_elementoAncho'					=>	$tam_elementoAncho
									);
			return $this->cellsInfo;
		}


		function getCellsTables(){
			return $this->cellsTables;
		
		}

		function generateCellsCampos(){
			$tam_font_Cells = 7;	
			$this->SetFont('Arial','B',$tam_font_Cells);

			$iden = 'Identificación de la ';
			$tam_iden = $this->GetStringWidth($iden)+10;
			
			$fechaColado = 'Fecha de';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+6;
			
			$edad = 'Edad de';
			$tam_edad = $this->GetStringWidth($edad)+4;

			$apoyos = '(lijado/cuero)';
			$tam_apoyos = $this->GetStringWidth($apoyos)+4;

			$condiCurado = 'Condiciones de';
			$tam_condiCurado = $this->GetStringWidth($condiCurado)+4;

			$anchoPromedio = 'promedio';
			$tam_anchoPromedio = $this->GetStringWidth($anchoPromedio)+3;
			
			$peralPromedio = 'promedio';
			$tam_peralPromedio = $this->GetStringWidth($peralPromedio)+3;
			
			$entreApoyos = 'entre apoyos';
			$tam_entreApoyos = $this->GetStringWidth($entreApoyos)+3;

			$cargaMaxima = 'Carga máxima';
			$tam_cargaMaxima = $this->GetStringWidth($cargaMaxima)+3;
			
			$modRuptura = 'Modulo de Ruptura';
			$tam_modRuptura = $this->GetStringWidth($modRuptura)+3;
			
			$modRuptura2 = 'Modulo de';
			$tam_modRuptura2 = $this->GetStringWidth($modRuptura2)+4;

			$defectos = 'Defectos del';
			
			$tam_defectos = 259.4 - (		$tam_iden +
											$tam_fechaColado +
											$tam_edad +
											$tam_apoyos +
											$tam_condiCurado +
											$tam_anchoPromedio +
											$tam_peralPromedio +
											$tam_entreApoyos +
											$tam_cargaMaxima +
											$tam_modRuptura +
											$tam_modRuptura2
										);

			$tam_font_CellsRows = 5;
			$tam_cellsTablesAlto = $tam_font_CellsRows - 2.5;


			
			$this->cellsTables = array(

											'tam_font_Cells'			=>	$tam_font_Cells,
											'tam_font_CellsRows'		=>	$tam_font_CellsRows,
											'tam_cellsTablesAlto'		=>	$tam_cellsTablesAlto,

											'tam_iden' => $tam_iden,
											'tam_fechaColado' => $tam_fechaColado,
											'tam_edad' => $tam_edad,
											'tam_apoyos' => $tam_apoyos,
											'tam_condiCurado' => $tam_condiCurado,
											'tam_anchoPromedio' => $tam_anchoPromedio,
											'tam_peralPromedio' => $tam_peralPromedio,
											'tam_entreApoyos' => $tam_entreApoyos,
											'tam_cargaMaxima' => $tam_cargaMaxima,
											'tam_modRuptura' => $tam_modRuptura,
											'tam_modRuptura2' => $tam_modRuptura2,
											'tam_defectos' => $tam_defectos
										);
		}

		function putCaracInfo(){
			
			//Guardamos la posicion de x para posteriormente imprimir la siguiente celda
			$posicion_y = $this->GetY();

			//Nombre de la obra
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->cell($this->cellsInfo['tam_obra'],$this->cellsInfo['tam_CellsLeftAlto'],$this->cellsInfo['obra'],0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);
			$this->multicell($this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_CellsLeftAlto'],'PRUEBA DE TEXTO LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1','B','C');

			$posicion_yAux = $this->GetY();

			//Nos colocamos en la misma posicion que la anterior celda
			$this->SetY($posicion_y);

			//Informe No.
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->SetX(-($this->cellsInfo['tam_informeNo'] + $this->cellsInfo['separacion']));
			$this->Cell($this->cellsInfo['tam_informeNo'],$this->cellsInfo['tam_CellsRightAlto'],$this->cellsInfo['informeNo'],0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_CellsRightAlto'],$this->getMaxString($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_informeText'],'tam_stringCarac'),'B','C');

			$this->SetY($posicion_yAux + 2);

			

			//Localizacion de la obra

			//Guardamos la posicion de la y para imprimir despues la siguiente celda

			$posicion_y = $this->GetY();

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_locObra'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['locObra']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->multicell($this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_CellsLeftAlto'],'PRUEBA DE TEXTO LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1LINEA 1','B','C');

			$posicion_yAux = $this->GetY();

			$this->SetY($posicion_y);
			
			//Fecha de ensaye
			$this->SetX(-($this->cellsInfo['tam_fechaEnsaye'] + $this->cellsInfo['separacion']));
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_fechaEnsaye'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode($this->cellsInfo['fechaEnsaye']),0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_fechaEnsayeText'],$this->cellsInfo['tam_CellsRightAlto'],$this->getMaxString($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_fechaEnsayeText'],'tam_stringCarac'),'B',0,'C');

			$this->SetY($posicion_yAux + 2);

			//$this->Ln($this->cellsInfo['tam_font_right'] - 2);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);

			//Nombre del cliente
			$this->Cell($this->cellsInfo['tam_nomCli'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['nomCli']),0);

			///Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_CellsLeftAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_razonText'],'tam_stringCarac'),'B',0);

			//Tipo de concreto
			$this->SetX(-($this->cellsInfo['tam_tipoConcreto'] + $this->cellsInfo['separacion']));
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_tipoConcreto'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode($this->cellsInfo['tipoConcreto']),0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_tipoConcretoText'],$this->cellsInfo['tam_CellsRightAlto'],$this->getMaxString($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_tipoConcretoText'],'tam_stringCarac'),'B',0,'C');

			$this->Ln($this->cellsInfo['tam_font_right'] - 2);


			//Direccion del cliente
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_dirCliente'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['dirCliente']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_CellsLeftAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_dirClienteText'],'tam_stringCarac'),'B',0);

			//MR del proyecto
			$this->SetX(-($this->cellsInfo['tam_mrProyecto'] + $this->cellsInfo['separacion']));
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_mrProyecto'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode($this->cellsInfo['mrProyecto']),0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_fprimaAncho'],$this->cellsInfo['tam_CellsRightAlto'],$this->getMaxString($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_fprimaAncho'],'tam_stringCarac'),'B',0,'C');

			$this->Ln($this->cellsInfo['tam_font_right'] - 2);


			//Elemento colado
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_eleColado'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['eleColado']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_elementoAncho'],$this->cellsInfo['tam_CellsLeftAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_elementoAncho'],'tam_stringCarac'),'B',0);


			//-Informe al cual sustituye
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->SetX(-($this->cellsInfo['tam_sustituyeInforme'] + $this->cellsInfo['separacion']));
			$this->Cell($this->cellsInfo['tam_sustituyeInforme'],$this->cellsInfo['tam_CellsRightAlto'],$this->cellsInfo['sustituyeInforme'],0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);

			$this->Cell($this->cellsInfo['tam_sustituyeInformeText'],$this->cellsInfo['tam_CellsRightAlto'],$this->getMaxString($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_sustituyeInformeText'],'tam_stringCarac'),'B',0,'C');

			$this->Ln(15);
		}

		function Header()
		{
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de ema
			$ancho_ema = 50;	$alto_ema = 20;
			$tam_lacocs = 20;
			$posicion_x = $this->GetX();

			$this->Image('./../../disenoFormatos/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$this->TextWithDirection($this->GetX(),$this->gety() + 24,utf8_decode('LACOCS S.A. DE C.V.'));	

			$this->Image('./../../disenoFormatos/ema.jpeg',269-$ancho_ema,$this->GetY(),$ancho_ema,$alto_ema);

			//$this->cell(0,20,'',1,2);

			//Información de la empresa
			$this->SetY(25);
			//$tam_font_titulo = 8;
			$this->SetFont('Arial','B'); 
			$titulo = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A. DE C.V.';
			$tam_cell = $this->GetStringWidth($titulo);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo),0,'C');
			$this->Ln(5);
			//Titulo del informe
			$tam_font_tituloInforme = 8.5;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '"INFORME DE ENSAYE DE LA RESISTENCIA A LA FLEXIÓN EN VIGAS, CON CARGA EN LOS TERCIOS DEL CLARO"';
			$tam_tituloInforme = $this->GetStringWidth($titulo_informe)+3;

			$tam_font_info = 7;
			//Direccion
			$this->SetFont('Arial','',$tam_font_info);
			$direccion_lacocs = '35 NORTE No.3023, UNIDAD HABITACIONAL AQUILES SERDÁN, PUEBLA, PUE.  TEL: 01 222 2315836, 8686973, 8686974.';
			$tam_direccion = $this->GetStringWidth($direccion_lacocs)+6;
			$this->SetX((279-$tam_direccion)/2);
			$this->Cell($tam_direccion,$tam_font_info - 3,utf8_decode($direccion_lacocs),0,'C');
			
			$this->Ln(4);

			$this->SetFont('Arial','B',$tam_font_tituloInforme); 
			$this->SetX((279-$tam_tituloInforme)/2)	;
			$this->Cell($tam_tituloInforme,$tam_font_tituloInforme - 3,utf8_decode($titulo_informe),0,0,'C');
			//---Divide espacios entre el titulo del formato y la información del formato
			$this->Ln($tam_font_tituloInforme - 2);
		}


		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.
		function putInfo($infoFormato){
			//Guardamos la posicion de y para posteriormente imprimir la siguiente celda
			$posicion_y = $this->GetY();

			//Nombre de la obra
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_obra'],$this->cellsInfo['tam_CellsLeftAlto'],$this->cellsInfo['obra'],0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_CellsLeftAlto'],$infoFormato['obra'],3);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['obra'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}

			$this->multicell($this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($infoFormato['obra']),'B','C');

			$posicion_yAux = $this->GetY();

			//Nos colocamos en la misma posicion que la anterior celda
			$this->SetY($posicion_y);

			//Informe No.
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->SetX(-($this->cellsInfo['tam_informeNo'] + $this->cellsInfo['separacion']));
			$this->Cell($this->cellsInfo['tam_informeNo'],$this->cellsInfo['tam_CellsRightAlto'],$this->cellsInfo['informeNo'],0,0,'C');

			//Caja de texto

			

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_CellsRightAlto'],$infoFormato['informeNo'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['informeNo'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}

			$this->multicell($this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode(		$infoFormato['informeNo']	),'B','C');

			$posicion_yRight = $this->GetY();

			$this->SetY($posicion_yAux + 1);

			//Guardamos la posicion de y para posteriormente imprimir la siguiente celda
			$posicion_y = $this->GetY();

			//Localizacion de la obra
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_locObra'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['locObra']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_CellsLeftAlto'],$infoFormato['obraLocalizacion'],3);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['obraLocalizacion'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}

			$this->multicell($this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($infoFormato['obraLocalizacion']),'B','C');

			$posicion_yAux = $this->GetY();

			//Nos colocamos en la misma posicion que la anterior celda
			$this->SetY($posicion_yRight + 1);
			
			//Fecha de ensaye
			$this->SetX(-($this->cellsInfo['tam_fechaEnsaye'] + $this->cellsInfo['separacion']));
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_fechaEnsaye'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode($this->cellsInfo['fechaEnsaye']),0,0,'C');

			//Caja de texto

			

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_fechaEnsayeText'],$this->cellsInfo['tam_CellsRightAlto'],$infoFormato['fecha'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['fecha'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}

			$this->multicell($this->cellsInfo['tam_fechaEnsayeText'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode(	$infoFormato['fecha']	),'B','C');

			$posicion_yRight = $this->GetY();

			$this->SetY($posicion_yAux + 1);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);

			//Nombre del cliente
			$this->Cell($this->cellsInfo['tam_nomCli'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['nomCli']),0);

			///Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_CellsLeftAlto'],$infoFormato['razonSocial'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['razonSocial'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}


			$this->multicell($this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode(	$infoFormato['razonSocial']	),'B','C');

			$posicion_yAux = $this->GetY();


			//Tipo de concreto
			$this->SetXY(-($this->cellsInfo['tam_tipoConcreto'] + $this->cellsInfo['separacion']),$posicion_yRight + 1);
	

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_tipoConcreto'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode($this->cellsInfo['tipoConcreto']),0,0,'C');

			//Caja de texto

			

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_tipoConcretoText'],$this->cellsInfo['tam_CellsRightAlto'],$infoFormato['tipoConcreto'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['tipoConcreto'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}


			$this->multicell($this->cellsInfo['tam_tipoConcretoText'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode(	$infoFormato['tipoConcreto']	),'B','C');

			$posicion_yRight = $this->GetY();

			$this->SetY($posicion_yAux + 1);


			//Direccion del cliente
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_dirCliente'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['dirCliente']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_CellsLeftAlto'],$infoFormato['direccion'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['direccion'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}


			$this->multicell($this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode(	$infoFormato['direccion']	),'B','C');

			$posicion_yAux = $this->GetY();

			//MR del proyecto
			$this->SetXY(-($this->cellsInfo['tam_mrProyecto'] + $this->cellsInfo['separacion']),$posicion_yRight + 1);
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->Cell($this->cellsInfo['tam_mrProyecto'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode($this->cellsInfo['mrProyecto']),0,0,'C');

			//Caja de texto

			

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_fprimaAncho'],$this->cellsInfo['tam_CellsRightAlto'],$infoFormato['fprima'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['fprima'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}


			$this->multicell($this->cellsInfo['tam_fprimaAncho'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode(	$infoFormato['fprima']	),'B','C');

			$posicion_yRight = $this->GetY();

			$this->SetY($posicion_yAux + 1);

			//Elemento colado
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_eleColado'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['eleColado']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);



			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_elementoAncho'],$this->cellsInfo['tam_CellsLeftAlto'],$infoFormato['localizacion'],3);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['localizacion'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}

			$this->multicell($this->cellsInfo['tam_elementoAncho'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode( $infoFormato['localizacion'] ),'B','C');

			$posicion_yAux = $this->GetY();


			//-Informe al cual sustituye
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->SetXY(-($this->cellsInfo['tam_sustituyeInforme'] + $this->cellsInfo['separacion']),$posicion_yRight + 1);
			$this->Cell($this->cellsInfo['tam_sustituyeInforme'],$this->cellsInfo['tam_CellsRightAlto'],$this->cellsInfo['sustituyeInforme'],0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);

			$this->Cell($this->cellsInfo['tam_sustituyeInformeText'],$this->cellsInfo['tam_CellsRightAlto'],'N/A','B',0,'C');

			$this->SetY($posicion_yAux + 3);
		}

		function putCaracCampos(){
			$tam_font_Cells = 7;	
			$this->SetFont('Arial','B',$tam_font_Cells);

			$iden = 'Identificación de la ';
			$tam_iden = $this->GetStringWidth($iden)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_iden,($tam_font_Cells+5)/2,utf8_decode($iden),'L,T,R',2,'C');
			$this->Cell($tam_iden,($tam_font_Cells+5)/2,'muestra','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_iden,$posicion_y);
			$fechaColado = 'Fecha de';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+6;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_fechaColado,($tam_font_Cells+5)/2,$fechaColado,'L,T,R',2,'C');
			$this->Cell($tam_fechaColado,($tam_font_Cells+5)/2,'Colado','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_fechaColado,$posicion_y);
			$edad = 'Edad de';
			$tam_edad = $this->GetStringWidth($edad)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_edad,($tam_font_Cells+5)/3,$edad,'L,T,R',2,'C');
			$this->Cell($tam_edad,($tam_font_Cells+5)/3,'Ensaye','L,R',2,'C');
			$this->Cell($tam_edad,($tam_font_Cells+5)/3,utf8_decode('(días)'),'L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_edad,$posicion_y);
			$apoyos = '(lijado/cuero)';
			$tam_apoyos = $this->GetStringWidth($apoyos)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_apoyos,($tam_font_Cells+5)/3,'Puntos de','L,T,R',2,'C');
			$this->Cell($tam_apoyos,($tam_font_Cells+5)/3,utf8_decode('apoyo'),'L,R',2,'C');
			$this->Cell($tam_apoyos,($tam_font_Cells+5)/3,'(lijado/cuero)','L,B,R',0,'C');
			
			$this->SetXY($posicion_x+$tam_apoyos,$posicion_y);
			$condiCurado = 'Condiciones de';
			$tam_condiCurado = $this->GetStringWidth($condiCurado)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_condiCurado,($tam_font_Cells+5)/4,$condiCurado,'L,T,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_Cells+5)/4,utf8_decode('curado y'),'L,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_Cells+5)/4,'humedad','L,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_Cells+5)/4,utf8_decode('(húmedo/seco)'),'L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_condiCurado,$posicion_y);
			$anchoPromedio = 'promedio';
			$tam_anchoPromedio = $this->GetStringWidth($anchoPromedio)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_anchoPromedio,($tam_font_Cells+5)/3,'Ancho','L,T,R',2,'C');
			$this->Cell($tam_anchoPromedio,($tam_font_Cells+5)/3,utf8_decode($anchoPromedio),'L,R',2,'C');
			$this->Cell($tam_anchoPromedio,($tam_font_Cells+5)/3,'(cm)','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_anchoPromedio,$posicion_y);
			$peralPromedio = 'promedio';
			$tam_peralPromedio = $this->GetStringWidth($peralPromedio)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_peralPromedio,($tam_font_Cells+5)/3,'Peralte','L,T,R',2,'C');
			$this->Cell($tam_peralPromedio,($tam_font_Cells+5)/3,utf8_decode($peralPromedio),'L,R',2,'C');
			$this->Cell($tam_peralPromedio,($tam_font_Cells+5)/3,'(cm)','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_peralPromedio,$posicion_y);
			$entreApoyos = 'entre apoyos';
			$tam_entreApoyos = $this->GetStringWidth($entreApoyos)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_entreApoyos,($tam_font_Cells+5)/3,'Distancia','L,T,R',2,'C');
			$this->Cell($tam_entreApoyos,($tam_font_Cells+5)/3,utf8_decode($entreApoyos),'L,R',2,'C');
			$this->Cell($tam_entreApoyos,($tam_font_Cells+5)/3,'(cm)','L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_entreApoyos,$posicion_y);
			$cargaMaxima = 'Carga máxima';
			$tam_cargaMaxima = $this->GetStringWidth($cargaMaxima)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_cargaMaxima,($tam_font_Cells+5)/2,utf8_decode($cargaMaxima),'L,T,R',2,'C');
			$this->Cell($tam_cargaMaxima,($tam_font_Cells+5)/2,utf8_decode('aplicada (kg)'),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_cargaMaxima,$posicion_y);
			$modRuptura = 'Modulo de Ruptura';
			$tam_modRuptura = $this->GetStringWidth($modRuptura)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_modRuptura,($tam_font_Cells+5)/2,$modRuptura,'L,T,R',2,'C');
			$this->Cell($tam_modRuptura,($tam_font_Cells+5)/2,utf8_decode('(kg/cm²)'),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_modRuptura,$posicion_y);
			$modRuptura2 = 'Modulo de';
			$tam_modRuptura2 = $this->GetStringWidth($modRuptura2)+6;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_modRuptura2,($tam_font_Cells+5)/3,$modRuptura2,'L,T,R',2,'C');
			$this->Cell($tam_modRuptura2,($tam_font_Cells+5)/3,'Ruptura','L,R',2,'C');
			$this->Cell($tam_modRuptura2,($tam_font_Cells+5)/3,utf8_decode('(Mpa)'),'L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_modRuptura2,$posicion_y);
			$defectos = 'Defectos del';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell(0,($tam_font_Cells+5)/2,$defectos,'L,T,R',2,'C');
			$this->Cell(0,($tam_font_Cells+5)/2,'Espécimen','L,B,R',0,'C');

			$tam_defectos = $this->GetX() - ($tam_iden +
											$tam_fechaColado +
											$tam_edad +
											$tam_apoyos +
											$tam_condiCurado +
											$tam_anchoPromedio +
											$tam_peralPromedio +
											$tam_entreApoyos +
											$tam_cargaMaxima +
											$tam_modRuptura +
											$tam_modRuptura2 +
											10);

			$this->Ln();

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

			$this->arrayCampos = $array_campo;

			$tam_font_CellsRows = 6;
			$tam_cellsTablesAlto = $tam_font_CellsRows - 2.5;

			$grupos = 12; //Ponemos todos los rows que pueden venir en el formato ya que no hay grupos como tal
			$this->putInfoTables($grupos,$tam_font_CellsRows,$this->arrayCampos,$tam_cellsTablesAlto,'tam_stringCarac');
		}

		function putCaracDetails(){
			$tam_font_details = 7;
			$this->SetFont('Arial','B',$tam_font_details);

			$observaciones = 'OBSERVACIONES:';
			$tam_observacionesAncho = $this->GetStringWidth($observaciones)+2;
			$tam_observacionAnchoTxt = 	259.3975 - $tam_observacionesAncho;

			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			$this->Cell($tam_observacionesAncho,($tam_font_details+3),$observaciones,'L,B,T',0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$tam_font_details);





			$this->Cell(0,($tam_font_details+3),$this->getMaxString($tam_font_details,$tam_observacionAnchoTxt,'tam_stringCarac'),'T,R,B',2);

			$this->Ln(0);

			$this->SetFont('Arial','B',$tam_font_details);
			$metodos = 'MÉTODOS EMPLEADOS: ';
			$metodos_usuados = 'NMX-C-161-ONNCCE-2013, NMX-C-159-ONNCCE-2016, NMX-C-191-ONNCCE-2015';
			$this->Cell($this->GetStringWidth($metodos)+2,($tam_font_details),$metodos,'L,T,B',0);
			$this->SetFont('Arial','',$tam_font_details);
			$this->Cell($this->GetStringWidth($metodos_usuados)+40,($tam_font_details),utf8_decode($metodos_usuados),'R,T,B',0);

			$incertidumbre = 'INCERTIDUMBRE:';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+2;

			$this->SetFont('Arial','B',$tam_font_details);
			$this->Cell($tam_incertidumbre,($tam_font_details),$incertidumbre,'L,B,T',0);

			$tam_incertidumbreText = 269.3975 - $this->GetX(); //Obtenemos el tamaño de la incertidumbre, restando la posicion en donde quedo al imprimir la ultima celda

			$this->SetFont('Arial','',$tam_font_details);
			//Caja de texto
			$this->Cell($tam_incertidumbreText,($tam_font_details),$this->getMaxString($tam_font_details,$tam_incertidumbreText,'tam_stringCarac'),'T,R,B',2);

			$this->Ln(0);

			$this->SetFont('Arial','',$tam_font_details);

			$mensaje1 = 'ESTE DOCUMENTO SE REFIERE EXCLUSIVAMENTE A LAS PRUEBAS REALIZADAS Y NO DEBE SER REPRODUCIDO DE FORMA PARCIAL SIN  LA AUTORIZACION DEL LABORATORIO LACOCS, ASI MISMO ';
			$this->Cell($this->GetStringWidth($mensaje1),($tam_font_details- 2.5),utf8_decode($mensaje1),0,2);
			$mensaje2 = 'ESTE DOCUMENTO NO TENDRA VALIDEZ SI PRESENTA TACHADURA O INMIENDA ALGUNA';
			$this->Cell($this->GetStringWidth($mensaje2),($tam_font_details- 2.5),utf8_decode($mensaje2),0,0);

			$this->Ln(8);
			$tam_image = 20;
			$tam_font_footer = 8; $this->SetFont('Arial','',$tam_font_footer);
			
			$tam_boxElaboro = 259/3;	$tam_first = 12.5; $tam_second = 12.5;
			$posicion_y = $this->GetY();
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			//$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreLaboratorista'])/2),$this->gety() - 12,utf8_decode($infoU['nombreLaboratorista']));	
			//$this->Image($infoU['firmaLaboratorista'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

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
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

		}


		function putTables($infoFormato,$regisFormato,$id_registrosCampo){

			$this->SetY(85);

			$tam_font_Cells = 7;	
			$this->SetFont('Arial','B',$tam_font_Cells);

			$iden = 'Identificación de la ';
			$tam_iden = $this->GetStringWidth($iden)+10;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_iden,($tam_font_Cells+5)/2,utf8_decode($iden),'L,T,R',2,'C');
			$this->Cell($tam_iden,($tam_font_Cells+5)/2,'muestra','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_iden,$posicion_y);
			$fechaColado = 'Fecha de';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+6;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_fechaColado,($tam_font_Cells+5)/2,$fechaColado,'L,T,R',2,'C');
			$this->Cell($tam_fechaColado,($tam_font_Cells+5)/2,'Colado','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_fechaColado,$posicion_y);
			$edad = 'Edad de';
			$tam_edad = $this->GetStringWidth($edad)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_edad,($tam_font_Cells+5)/3,$edad,'L,T,R',2,'C');
			$this->Cell($tam_edad,($tam_font_Cells+5)/3,'Ensaye','L,R',2,'C');
			$this->Cell($tam_edad,($tam_font_Cells+5)/3,utf8_decode('(días)'),'L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_edad,$posicion_y);
			$apoyos = '(lijado/cuero)';
			$tam_apoyos = $this->GetStringWidth($apoyos)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_apoyos,($tam_font_Cells+5)/3,'Puntos de','L,T,R',2,'C');
			$this->Cell($tam_apoyos,($tam_font_Cells+5)/3,utf8_decode('apoyo'),'L,R',2,'C');
			$this->Cell($tam_apoyos,($tam_font_Cells+5)/3,'(lijado/cuero)','L,B,R',0,'C');
			
			$this->SetXY($posicion_x+$tam_apoyos,$posicion_y);
			$condiCurado = 'Condiciones de';
			$tam_condiCurado = $this->GetStringWidth($condiCurado)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_condiCurado,($tam_font_Cells+5)/4,$condiCurado,'L,T,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_Cells+5)/4,utf8_decode('curado y'),'L,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_Cells+5)/4,'humedad','L,R',2,'C');
			$this->Cell($tam_condiCurado,($tam_font_Cells+5)/4,utf8_decode('(húmedo/seco)'),'L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_condiCurado,$posicion_y);
			$anchoPromedio = 'promedio';
			$tam_anchoPromedio = $this->GetStringWidth($anchoPromedio)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_anchoPromedio,($tam_font_Cells+5)/3,'Ancho','L,T,R',2,'C');
			$this->Cell($tam_anchoPromedio,($tam_font_Cells+5)/3,utf8_decode($anchoPromedio),'L,R',2,'C');
			$this->Cell($tam_anchoPromedio,($tam_font_Cells+5)/3,'(cm)','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_anchoPromedio,$posicion_y);
			$peralPromedio = 'promedio';
			$tam_peralPromedio = $this->GetStringWidth($peralPromedio)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_peralPromedio,($tam_font_Cells+5)/3,'Peralte','L,T,R',2,'C');
			$this->Cell($tam_peralPromedio,($tam_font_Cells+5)/3,utf8_decode($peralPromedio),'L,R',2,'C');
			$this->Cell($tam_peralPromedio,($tam_font_Cells+5)/3,'(cm)','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_peralPromedio,$posicion_y);
			$entreApoyos = 'entre apoyos';
			$tam_entreApoyos = $this->GetStringWidth($entreApoyos)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_entreApoyos,($tam_font_Cells+5)/3,'Distancia','L,T,R',2,'C');
			$this->Cell($tam_entreApoyos,($tam_font_Cells+5)/3,utf8_decode($entreApoyos),'L,R',2,'C');
			$this->Cell($tam_entreApoyos,($tam_font_Cells+5)/3,'(cm)','L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_entreApoyos,$posicion_y);
			$cargaMaxima = 'Carga máxima';
			$tam_cargaMaxima = $this->GetStringWidth($cargaMaxima)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_cargaMaxima,($tam_font_Cells+5)/2,utf8_decode($cargaMaxima),'L,T,R',2,'C');
			$this->Cell($tam_cargaMaxima,($tam_font_Cells+5)/2,utf8_decode('aplicada (kgf)'),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_cargaMaxima,$posicion_y);
			$modRuptura = 'Modulo de Ruptura';
			$tam_modRuptura = $this->GetStringWidth($modRuptura)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_modRuptura,($tam_font_Cells+5)/2,$modRuptura,'L,T,R',2,'C');
			$this->Cell($tam_modRuptura,($tam_font_Cells+5)/2,utf8_decode('(kgf/cm²)'),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_modRuptura,$posicion_y);
			$modRuptura2 = 'Modulo de';
			$tam_modRuptura2 = $this->GetStringWidth($modRuptura2)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_modRuptura2,($tam_font_Cells+5)/3,$modRuptura2,'L,T,R',2,'C');
			$this->Cell($tam_modRuptura2,($tam_font_Cells+5)/3,'Ruptura','L,R',2,'C');
			$this->Cell($tam_modRuptura2,($tam_font_Cells+5)/3,utf8_decode('(MPa)'),'L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_modRuptura2,$posicion_y);
			$defectos = 'Defectos del';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell(0,($tam_font_Cells+5)/2,$defectos,'L,T,R',2,'C');
			$this->Cell(0,($tam_font_Cells+5)/2,utf8_decode('Espécimen'),'L,B,R',0,'C');

			$tam_defectos = $this->GetX() - ($tam_iden +
											$tam_fechaColado +
											$tam_edad +
											$tam_apoyos +
											$tam_condiCurado +
											$tam_anchoPromedio +
											$tam_peralPromedio +
											$tam_entreApoyos +
											$tam_cargaMaxima +
											$tam_modRuptura +
											$tam_modRuptura2 +
											10);

			$this->Ln();

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

			$this->arrayCampos = $array_campo;

			$tam_font_CellsRows = 6;
			$tam_cellsTablesAlto = $tam_font_CellsRows - 2.5;
			$grupos = 12;

			$this->SetFont('Arial','',$tam_font_CellsRows);
			$num_rows = 0;
			$num_rowsExtra = 0;
			if(!array_key_exists('error',$regisFormato)){
				foreach ($regisFormato as $registro) {

					//Bandera con la que sabremos si el registro que llego es bueno o no.
					$good = true;

					//Contador de las iteraciones
					$j=0;

					//Verificamos si el arreglo que vamos a imprimir esta autorizado o no
					if($registro['status'] != 4 && $registro['id_registrosCampo'] != $id_registrosCampo){
						//Declaramos una bandera para saber como debemos imprimir
						$good = false;

						//Declaramos las posiciones en donde debemos imprimir lo que tenemos permitido
						$posicionClave = 0;
						$posicionFechaColado = 1;
						$posicionEdad = 2;
					}

					//Quitamos la informacion que no necesitamos y que viene en el arreglo (id_registroCampo y status)
					unset($registro['status']);
					unset($registro['id_registrosCampo']);

					foreach ($registro as $campo) {

						if($good == true){

							//Algoritmo que encoje y trunca

							$resultado = $this->printInfoObraAndLocObra($tam_font_CellsRows,$this->arrayCampos[$j],$tam_cellsTablesAlto,$campo,1);

							$this->SetFont('Arial','',$resultado['sizeFont']);
							$campo = $resultado['new_string'];


							if($resultado['error'] == 100){
								$this->error = $resultado['error'];
							}


							$this->cell($this->arrayCampos[$j],$tam_cellsTablesAlto,utf8_decode($campo),1,0,'C');

						}else{

							if($j == $posicionClave || $j == $posicionFechaColado || $j == $posicionEdad){

								//Algoritmo que encoje y trunca

								$resultado = $this->printInfoObraAndLocObra($tam_font_CellsRows,$this->arrayCampos[$j],$tam_cellsTablesAlto,$campo,1);

								$this->SetFont('Arial','',$resultado['sizeFont']);
								$campo = $resultado['new_string'];


								if($resultado['error'] == 100){
									$this->error = $resultado['error'];
								}

								$this->cell($this->arrayCampos[$j],$tam_cellsTablesAlto,utf8_decode($campo),1,0,'C');

							}else{
								$this->cell($this->arrayCampos[$j],$tam_cellsTablesAlto,'-',1,0,'C');
				
							}
						}

						$j++;
					}
					$num_rows++;
					if($num_rows % 3 == 0){
						$this->Ln();
						$posicion_x = $this->GetX();
						$posicion_y = $this->GetY();
						for ($k=0; $k < sizeof($this->arrayCampos); $k++){ 
							$this->cell($this->arrayCampos[$k],$tam_cellsTablesAlto,'',1,0,'C');
						}
						$num_rowsExtra++;
					}
					$this->Ln();
					
				}
			}
			$num_rows = $num_rows + $num_rowsExtra;
			if($num_rows<$grupos){
				for ($i=0; $i < ($grupos-$num_rows); $i++){
				//Definimos la posicion de X para tomarlo como referencia
				for ($j=0; $j < sizeof($this->arrayCampos); $j++){ 
					$this->cell($this->arrayCampos[$j],$tam_cellsTablesAlto,'',1,0,'C');
				}	
				$this->Ln();
				}
				$this->Line($posicion_x,$posicion_y,269.3975,$this->GetY());
			}
			$this->Line($posicion_x,$posicion_y,269.3975,$this->GetY());
		}

		function putDetails($infoFormato,$infoU){
			$tam_font_details = 7;
			$this->SetFont('Arial','B',$tam_font_details);

			$observaciones = 'OBSERVACIONES:';
			$tam_observacionesAncho = $this->GetStringWidth($observaciones)+2;
			$tam_observacionAnchoTxt = 	259.3975 - $tam_observacionesAncho;

			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			$this->Cell(0,$tam_font_details-2,$observaciones,'R,L',1);

			//Caja de texto
			$this->SetFont('Arial','',$tam_font_details);

			$alto_observacionesText = 3;

			$resultado = $this->printInfoObraAndLocObra($tam_font_details,259.4,$alto_observacionesText,$infoFormato['observaciones'],3);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['observaciones'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}

			//print_r($resultado);
			$this->multicell(0,$alto_observacionesText,utf8_decode(	$infoFormato['observaciones']	),'R,L','J');

			$this->Ln(0);

			$this->SetFont('Arial','B',$tam_font_details);
			$metodos = 'MÉTODOS EMPLEADOS: ';
			$metodos_usuados = 'NMX-C-161-ONNCCE-2013, NMX-C-159-ONNCCE-2016, NMX-C-191-ONNCCE-2015';
			$this->Cell($this->GetStringWidth($metodos)+2,($tam_font_details),utf8_decode($metodos),'L,T,B',0);
			$this->SetFont('Arial','',$tam_font_details);
			$this->Cell($this->GetStringWidth($metodos_usuados)+40,($tam_font_details),utf8_decode($metodos_usuados),'R,T,B',0);

			$incertidumbre = 'INCERTIDUMBRE kg/cm²:';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+2;

			$this->SetFont('Arial','B',$tam_font_details);
			$this->Cell($tam_incertidumbre,($tam_font_details),utf8_decode($incertidumbre),'L,B,T',0);

			$tam_incertidumbreText = 269.3975 - $this->GetX(); //Obtenemos el tamaño de la incertidumbre, restando la posicion en donde quedo al imprimir la ultima celda

			//Caja de texto

			$resultado = $this->printInfoObraAndLocObra($tam_font_details,$tam_incertidumbreText,$tam_font_details,$infoFormato['incertidumbreVigas'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['incertidumbreVigas'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado['error'];
			}

			$this->multicell($tam_incertidumbreText,($tam_font_details),utf8_decode(	$infoFormato['incertidumbreVigas']	),'T,R,B');

			$this->ln(1);

			$this->SetFont('Arial','',$tam_font_details);

			$mensaje1 = 'ESTE DOCUMENTO SE REFIERE EXCLUSIVAMENTE A LAS PRUEBAS REALIZADAS Y NO DEBE SER REPRODUCIDO DE FORMA PARCIAL SIN LA AUTORIZACIÓN DEL LABORATORIO LACOCS, ASÍ MISMO';
			$this->Cell($this->GetStringWidth($mensaje1),($tam_font_details - 4),utf8_decode($mensaje1),0,2);
			$mensaje2 = 'ESTE DOCUMENTO NO TENDRÁ VALIDEZ SI PRESENTA TACHADURA O INMIENDA ALGUNA';
			$this->Cell($this->GetStringWidth($mensaje2),($tam_font_details - 4),utf8_decode($mensaje2),0,0);

			$this->Ln(8);
			/*
			$tam_image = 20;
			$tam_font_footer = 8; $this->SetFont('Arial','',$tam_font_footer);
			
			$tam_boxElaboro = 259/3;	$tam_first = 12.5; $tam_second = 12.5;

			$this->SetX($this->GetX() + $tam_boxElaboro/2);

			$posicion_y = $this->GetY();
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreLaboratorista'])/2),$this->gety() - 12,utf8_decode($infoU['nombreLaboratorista']));	

			if($infoU['firmaLaboratorista'] != "null"){
				
				$this->Image($infoU['firmaLaboratorista'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}
			else{

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'))	;	

			}

			//$this->Image($infoU['firmaLaboratorista'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			

			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	

			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	

			$this->SetFont('Arial','B',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreG'])/2),$this->gety() - 12,utf8_decode($infoU['nombreG']));	

			if($infoU['firmaG'] != "null"){
				$this->Image($infoU['firmaG'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'));	
			}*/
			//$this->Image($infoU['firmaG'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			
		}
		
		function Footer(){
			//Como las fimas ya estaban desarrolladas, solo copie  las variables que ya ocupaba las nuevas variables que pertenecen a la clase.
			$infoU['firmaLaboratorista'] = $this->infoU['firmaLaboratorista'];
			$infoU['nombreLaboratorista'] = $this->infoU['nombreLaboratorista'];
			$infoU['nombreG'] = $this->infoU['nombreG'];
			$infoU['firmaG'] = $this->infoU['firmaG'];

			//Espacio para las firmas
			$tam_image = 20;
			$tam_font_footer = 8; 
			$this->SetFont('Arial','',$tam_font_footer);
			
			$tam_boxElaboro = 259/3;	$tam_first = 12.5; $tam_second = 12.5;

			//Configuramos las posiciones en donde estaran las firmas
			$this->SetXY(10 + $tam_boxElaboro/2,-($tam_first + $tam_second + 15));
			//$this->SetY(-($tam_first + $tam_second + 15)); //15 es el espcio de el numero de pagina

			$posicion_y = $this->GetY();
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			$this->SetFont('Arial','B',$tam_font_footer);

			//Nombre del laboratorista
			if($infoU['nombreLaboratorista'] != "null"){
				/*
					-Restamos -2 a el ancho de la celda porque no contemple las negritas, entonces como esta vez imprimire negritas el espacio de la letra aumenta.
					-Ponemos la altura de la celda del mismo tamaño que el de la letra ya que no existe una celda como tal en la que va el texto, y el alto de la celda no repercute en el resultado.
				*/
				$resultado = $this->printInfoObraAndLocObra($tam_font_footer,$tam_boxElaboro-2,$tam_font_footer,$infoU['nombreLaboratorista'],1);

				$this->SetFont('Arial','B',$resultado['sizeFont']);
				$infoU['nombreLaboratorista'] = $resultado['new_string'];

				if($resultado['error'] == 100){
					$this->error = $resultado['error'];
				}

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreLaboratorista'])/2),$this->gety() - 12,utf8_decode($infoU['nombreLaboratorista']));	
		
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('No hay nombre.')/2),$this->gety() - 12,utf8_decode("No hay nombre."));	
			}


			//Firma del laboratorista
			$this->SetFont('Arial','',$tam_font_footer);
			if($infoU['firmaLaboratorista'] != "null"){
				
				$this->Image($infoU['firmaLaboratorista'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}
			else{

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'))	;	

			}


			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->SetFont('Arial','B',$tam_font_footer);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	

			$this->SetFont('Arial','',$tam_font_footer);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	

			$this->SetFont('Arial','B',$tam_font_footer);


			//Nombre del Gerente
			if($infoU['nombreG'] != "null"){
				/*
					-Restamos -2 a el ancho de la celda porque no contemple las negritas, entonces como esta vez imprimire negritas el espacio de la letra aumenta.
					-Ponemos la altura de la celda del mismo tamaño que el de la letra ya que no existe una celda como tal en la que va el texto, y el alto de la celda no repercute en el resultado.
				*/

				$resultado = $this->printInfoObraAndLocObra($tam_font_footer,$tam_boxElaboro-2,$tam_font_footer,$infoU['nombreG'],1);

				$this->SetFont('Arial','B',$resultado['sizeFont']);
				$infoU['nombreG'] = $resultado['new_string'];

				if($resultado['error'] == 100){
					$this->error = $resultado['error'];
				}

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreG'])/2),$this->gety() - 12,utf8_decode($infoU['nombreG']));	
		
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('No hay nombre.')/2),$this->gety() - 12,utf8_decode("No hay nombre."));	
			}

			
			//Firma del Gerente
			$this->SetFont('Arial','',$tam_font_footer);
			if($infoU['firmaG'] != "null"){
				$this->Image($infoU['firmaG'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'));	
			}

			//Numero de Pagina
		    $this->SetY(-15);
		    $this->SetFont('Arial','',8);
		    $noPagina = 'Pág. '.$this->PageNo().' de {nb}';
		    $tam_noPagina = $this->GetStringWidth($noPagina);
		    $posicion_x = (279.4 - $tam_noPagina)/2;
		    $this->SetX($posicion_x);
		    $this->Cell($tam_noPagina,10,utf8_decode($noPagina),0,0,'C');

		    //Clave de validacion
		    $clave = 'F1-09-LCC-02-0.1';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');
		}

		

		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato,$infoU,$target_dir,$id_registrosCampo){

			$pdf  = new InformeVigas('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->generateCellsInfo();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($infoFormato,$regisFormato,$id_registrosCampo);
			$pdf->putDetails($infoFormato,$infoU);
			$pdf->infoU = $infoU;
			//$pdf->Output('F',$target_dir);
			$pdf->Output();
			return $pdf->error;
		}
		
	}

	
	
?>