<?php 

	include_once("./../../FPDF/MyPDF.php");
	//Formato de campo de cilindros
	class InformeCilindros extends MyPDF{
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
			$pdf  = new InformeCilindros('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->generateCellsInfo();
			$pdf->generateCellsCampos();
			$pdf->generateCellsDetails();
			$pdf->putCaracInfo();
			$pdf->putCaracCampos();
			$pdf->putCaracDetails();
			$pdf->Output();
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
			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;

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
			$tam_CellsLeftAlto = $tam_font_left - 3;
			$this->SetFont('Arial','',$tam_font_left);
			
			$obra = 'Nombre de la Obra:';
			$tam_obra = $this->GetStringWidth($obra)+2;

			$locObra = 'Localización de la Obra:';
			$tam_locObra = $this->GetStringWidth($locObra)+2;

			$nomCli = 'Nombre del Cliente:';
			$tam_nomCli = $this->GetStringWidth($nomCli)+2;
			
			$dirCliente = 'Dirección del Cliente:';
			$tam_dirCliente = $this->GetStringWidth($nomCli)+2;

			$tam_nomObraText = $tam_localizacionText = $tam_razonText = $tam_dirClienteText = 279.3975 - ($posicionCellsText+10);
			$tam_informeText  = $tam_sustituyeInformeText = $separacion - 10;

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

											'tam_nomObraText'			=>	$tam_nomObraText,
											'tam_localizacionText'		=>	$tam_localizacionText,
											'tam_informeText'			=>	$tam_informeText,
											'tam_razonText'				=>	$tam_razonText,
											'tam_dirClienteText'		=>	$tam_dirClienteText
									);

		}

		function generateCellsCampos(){
			//Definimos el tamaño de fuente y configuramos el tamaño
			$tam_font_Cells = 6;
			$tam_font_CellsSmall = 5.5;		
			$tam_font_CellsRows = 5;
			$tam_cellsTablesAlto = $tam_font_Cells - 3;
			$this->SetFont('Arial','B',$tam_font_Cells);


			$falla = 'FALLA';
			$tam_fallaAncho = $this->GetStringWidth($falla)+3;
			$tam_fallaAlto = 1.5*($tam_font_Cells - 3);
			$falla = $falla.'N°';

			$resistencia = 'RESISTENCIA';	
			$tam_resistenciaAncho = $this->GetStringWidth($resistencia)+3;
			$tam_resistenciaAlto = 1.5*($tam_font_Cells - 3);
			$resistencia = '% DE'."\n".$resistencia;

			$proyecto = 'PROYECTO';
			$tam_proyectoAncho = $this->GetStringWidth($proyecto)+3;
			$tam_proyectoAlto = $tam_font_Cells - 3;
			$proyecto = "F'c"."\n".'PROYECTO'."\n".'(kg/cm²)';

			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$tam_font_CellsSmall);

			$resis_compresion  = 'RESISTENCIA A';
			$tam_resis_compresionAncho = $this->GetStringWidth($resis_compresion)+10;
			$tam_resis_compresionAlto = 0.75*($tam_font_CellsSmall - 2.5);
			$resis_compresion = $resis_compresion."\n".'COMPRESIÓN';

			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$tam_font_Cells);

			$kgcm = 'kg/cm²';
			$tam_kgcmAncho = $tam_resis_compresionAncho/2;
			$tam_kgcmAlto = 1.5*($tam_font_Cells - 3);

			$mp = 'MPa';
			$tam_mpAncho = $tam_resis_compresionAncho/2;
			$tam_mpAlto = 1.5*($tam_font_Cells - 3);

			$carga = 'CARGA';
			$tam_cargaAncho = $this->GetStringWidth($carga) + 12;
			$tam_cargaAlto = 1.5*($tam_font_Cells - 3);

			$kg = '(kg)';
			$tam_kgAncho = $tam_cargaAncho/2;
			$tam_kgAlto = 1.5*($tam_font_Cells - 3);

			$kN = 'kN';
			$tam_kNAncho = $tam_cargaAncho/2;
			$tam_kNAlto = 1.5*($tam_font_Cells - 3);


			//--- SE MUEVE LA FUENTE PARA AJUSTAR AL TAMAÑO
			$this->SetFont('Arial','B',$tam_font_CellsSmall);

			$area = 'AREA EN';
			$tam_areaAncho = $this->GetStringWidth($area) + 3;
			$tam_areaAlto = 0.75*($tam_font_CellsSmall - 2.5);
			$area = $area."\n".'cm²';
	
			$altura = 'ALTURA';
			$tam_alturaAncho = $this->GetStringWidth($area) + 3;
			$tam_alturaAlto = 0.75*($tam_font_CellsSmall - 2.5);
			$altura = $altura."\n".'EN cm';

			$diametro = 'DIAMETRO EN';
			$tam_diametroAncho = $this->GetStringWidth($diametro) + 3;
			$tam_diametroAlto = 0.75*($tam_font_CellsSmall - 2.5);
			$diametro = $diametro."\n".'cm';

			$edad = 'EDAD EN';
			$tam_edadAncho = $this->GetStringWidth($edad) + 3;
			$tam_edadAlto = 0.75*($tam_font_CellsSmall - 2.5);
			$edad = $edad."\n".'DIAS';

			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$tam_font_Cells);

			$peso = 'PESO EN kg';
			$tam_pesoAncho = $this->GetStringWidth($peso) + 3;
			$tam_pesoAlto = 1.5*($tam_font_Cells - 3);

			$rev = 'REV. cm';
			$tam_revAncho = $this->GetStringWidth($rev) + 3;
			$tam_revAlto = 1.5*($tam_font_Cells - 3);

			$especimenes = 'ESPECIMENES';
			$tam_especimenesAncho = ($tam_areaAncho + $tam_alturaAncho + $tam_diametroAncho + $tam_edadAncho + $tam_pesoAncho + $tam_revAncho);
			$tam_especimenesAlto = 1.5*($tam_font_Cells - 3);

			$clave = 'CLAVE';
			$tam_claveAncho = $this->GetStringWidth($clave) + 25;
			$tam_claveAlto = 1.5*($tam_font_Cells - 3);

			$fecha = 'FECHA DE ENSAYE';
			$tam_fechaAncho = $this->GetStringWidth($fecha) + 3;
			$tam_fechaAlto = 1.5*($tam_font_Cells - 3);

			$elemento = 'ELEMENTO MUESTREADO';
			$tam_elementoAncho = 259.3975 - 	(
											$tam_fallaAncho	+
											$tam_resistenciaAncho	+
											$tam_proyectoAncho	+
											$tam_resis_compresionAncho	+
											$tam_cargaAncho	+
											$tam_especimenesAncho	+
											$tam_claveAncho	+
											$tam_fechaAncho	
										);
			$tam_elementoAlto = 1.5*($tam_font_Cells - 3);
			
			$this->cellsTables = array(
										'tam_font_Cells'			=>	$tam_font_Cells,
										'tam_font_CellsSmall'		=>	$tam_font_CellsSmall,
										'tam_font_CellsRows'		=>	$tam_font_CellsRows,
										'tam_cellsTablesAlto'		=>	$tam_cellsTablesAlto,

										'falla'						=>	$falla,
										'tam_fallaAncho'			=>	$tam_fallaAncho,
										'tam_fallaAlto'				=>	$tam_fallaAlto,
										
										'resistencia'				=>	$resistencia,
										'tam_resistenciaAncho'		=>	$tam_resistenciaAncho,
										'tam_resistenciaAlto'		=>	$tam_resistenciaAlto,

										'proyecto'					=>	$proyecto,
										'tam_proyectoAncho'			=>	$tam_proyectoAncho,
										'tam_proyectoAlto'			=>	$tam_proyectoAlto,

										'resis_compresion'			=>	$resis_compresion,
										'tam_resis_compresionAncho'	=>	$tam_resis_compresionAncho,
										'tam_resis_compresionAlto'	=>	$tam_resis_compresionAlto,

										'kgcm'						=>	$kgcm,
										'tam_kgcmAncho'				=>	$tam_kgcmAncho,
										'tam_kgcmAlto'				=>	$tam_kgcmAlto,

										'mp'						=>	$mp,
										'tam_mpAncho'				=>	$tam_mpAncho,
										'tam_mpAlto'				=>	$tam_mpAlto,
										
										'carga'						=>	$carga,
										'tam_cargaAncho'			=>	$tam_cargaAncho,
										'tam_cargaAlto'				=>	$tam_cargaAlto,

										'kg'						=>	$kg,
										'tam_kgAncho'				=>	$tam_kgAncho,
										'tam_kgAlto'				=>	$tam_kgAlto,

										'kN'						=>	$kN,
										'tam_kNAncho'				=>	$tam_kNAncho,
										'tam_kNAlto'				=>	$tam_kNAlto,
										
										'area'						=>	$area,
										'tam_areaAncho'				=>	$tam_areaAncho,
										'tam_areaAlto'				=>	$tam_areaAlto,
										
										'altura'					=>	$altura,
										'tam_alturaAncho'			=>	$tam_alturaAncho,
										'tam_alturaAlto'			=>	$tam_alturaAlto,
										
										'diametro'					=>	$diametro,
										'tam_diametroAncho'			=>	$tam_diametroAncho,
										'tam_diametroAlto'			=>	$tam_diametroAlto,

										'edad'						=>	$edad,
										'tam_edadAncho'				=>	$tam_edadAncho,
										'tam_edadAlto'				=>	$tam_edadAlto,
										
										'peso'						=>	$peso,
										'tam_pesoAncho'				=>	$tam_pesoAncho,
										'tam_pesoAlto'				=>	$tam_pesoAlto,
										
										'rev'						=>	$rev,
										'tam_revAncho'				=>	$tam_revAncho,
										'tam_revAlto'				=>	$tam_revAlto,

										'especimenes'				=>	$especimenes,
										'tam_especimenesAncho'		=>	$tam_especimenesAncho,
										'tam_especimenesAlto'		=>	$tam_especimenesAlto,

										'clave'						=>	$clave,
										'tam_claveAncho'			=>	$tam_claveAncho,
										'tam_claveAlto'				=>	$tam_claveAlto,

										'fecha'						=>	$fecha,
										'tam_fechaAncho'			=>	$tam_fechaAncho,
										'tam_fechaAlto'				=>	$tam_fechaAlto,

										'elemento'					=>	$elemento,
										'tam_elementoAncho'			=>	$tam_elementoAncho,
										'tam_elementoAlto'			=>	$tam_elementoAlto

								);
			 $this->arrayCampos = 	array(
											$tam_fechaAncho,
											$tam_claveAncho,
											$tam_revAncho,
											$tam_pesoAncho,
											$tam_edadAncho,
											$tam_diametroAncho,
											$tam_alturaAncho,
											$tam_areaAncho,
											$tam_kNAncho,
											$tam_kgAncho,
											$tam_mpAncho,
											$tam_kgcmAncho,
											$tam_proyectoAncho,
											$tam_resistenciaAncho,
											$tam_fallaAncho,
											$tam_elementoAncho
									);
			
		}

		function generateCellsDetails(){
			$tam_font_details = 7;	
			$this->SetFont('Arial','B',$tam_font_details);

			//Observaciones
			$observaciones = 'OBSERVACIONES:';
			$tam_observacionesAncho = $this->GetStringWidth($observaciones)+2;
			$tam_observacionesAlto = 2*($tam_font_details - 4); //Este alto lo comparten la caja de texto y las observaciones

			//Tamaño de la celda donde va el texto de observaciones
			$tam_observacionAnchoTxt = 259.3975 - $tam_observacionesAncho;
			

			$this->SetFont('Arial','B',$tam_font_details);
			

			//Incertidumbre
			$incertidumbre = 'INCERTIDUMBRE';
			$tam_incertidumbreAncho = $this->GetStringWidth($incertidumbre)+20;
			$tam_incertidumbreAlto = $tam_font_details - 3;

			//Metodos empleados
			$metodos = 'MÉTODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			$tam_metodosAncho = 259.3975 - $tam_incertidumbreAncho;
			$tam_metodosAlto = $tam_font_details - 3;

			$this->cellsDetails = array(
											'tam_font_details'			=>	$tam_font_details,
											'observaciones'				=>	$observaciones,
											'tam_observacionesAncho'	=>	$tam_observacionesAncho,
											'tam_observacionesAlto'		=>	$tam_observacionesAlto,
											'tam_observacionAnchoTxt'	=>	$tam_observacionAnchoTxt,
											'incertidumbre'				=>	$incertidumbre,
											'tam_incertidumbreAncho'	=>	$tam_incertidumbreAncho,
											'tam_incertidumbreAlto'		=>	$tam_incertidumbreAlto,
											'metodos'					=>	$metodos,
											'tam_metodosAncho'			=>	$tam_metodosAncho,
											'tam_metodosAlto'			=>	$tam_metodosAlto
										);
		}


		function putCaracInfo(){
			/*
			Lado derecho:
							-Informe No.
							-Este informe sustituye a:
			*/
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);


			//Numero del informe
			$this->SetX(-($this->cellsInfo['tam_informeNo'] + $this->cellsInfo['separacion']));
			$this->Cell($this->cellsInfo['tam_informeNo'],$this->cellsInfo['tam_CellsRightAlto'],$this->cellsInfo['informeNo'],0,0,'C');



			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']); 

			$this->Cell($this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_CellsRightAlto'],$this->getMaxString($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_informeText'],'tam_stringCarac'),'B',0,'C');

			$this->Ln($this->cellsInfo['tam_font_right'] - 2);

			//-Informe al cual sustituye
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->SetX(-($this->cellsInfo['tam_sustituyeInforme'] + $this->cellsInfo['separacion']));
			$this->Cell($this->cellsInfo['tam_sustituyeInforme'],$this->cellsInfo['tam_CellsRightAlto'],$this->cellsInfo['sustituyeInforme'],0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);

			$this->Cell($this->cellsInfo['tam_sustituyeInformeText'],$this->cellsInfo['tam_CellsRightAlto'],$this->getMaxString($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_sustituyeInformeText'],'tam_stringCarac'),'B',0,'C');

			//--Divide la informacion de la derecha y la izquierda
			$this->Ln($this->cellsInfo['tam_font_right'] - 1);

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);

			//Cuadro con informacion
			//Obra
			$this->Cell($this->cellsInfo['tam_obra'],$this->cellsInfo['tam_CellsLeftAlto'],$this->cellsInfo['obra'],0);
			
			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_CellsLeftAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_nomObraText'],'tam_stringCarac'),'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			//Localizacion de la obra
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_locObra'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['locObra']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_CellsLeftAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_localizacionText'],'tam_stringCarac'),'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);

			//Nombre del cliente
			$this->Cell($this->cellsInfo['tam_nomCli'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['nomCli']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_CellsLeftAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_razonText'],'tam_stringCarac'),'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			//Direccion del cliente
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			
			$this->Cell($this->cellsInfo['tam_dirCliente'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['dirCliente']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_CellsLeftAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_dirClienteText'],'tam_stringCarac'),'B',0);

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($this->cellsInfo['tam_font_left']);
		}

		function putCaracCampos(){
			$posicion_y = $this->GetY();

			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);

			//Falla
			$this->SetX(-($this->cellsTables['tam_fallaAncho'] + 10));
			$posicion_x = $this->GetX();

			$this->multicell($this->cellsTables['tam_fallaAncho'],$this->cellsTables['tam_fallaAlto'],utf8_decode($this->cellsTables['falla']),1,'C');



			//Resistencia
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $this->cellsTables['tam_resistenciaAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_resistenciaAncho'],$this->cellsTables['tam_resistenciaAlto'],utf8_decode($this->cellsTables['resistencia']),1,'C');

			
			//Proyecto
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $this->cellsTables['tam_proyectoAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_proyectoAncho'],$this->cellsTables['tam_proyectoAlto'],utf8_decode($this->cellsTables['proyecto']),1,'C');
			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$this->cellsTables['tam_font_CellsSmall']);

			//Resistencia a compresion
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $this->cellsTables['tam_resis_compresionAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_resis_compresionAncho'],$this->cellsTables['tam_resis_compresionAlto'],utf8_decode($this->cellsTables['resis_compresion']),1,'C');


			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);
			//kg/cm²
			$this->SetY($posicion_y + $this->cellsTables['tam_kgcmAlto']);	
			$this->SetX($posicion_x + $this->cellsTables['tam_kgcmAncho']);
			$this->multicell($this->cellsTables['tam_kgcmAncho'],$this->cellsTables['tam_kgcmAlto'],utf8_decode($this->cellsTables['kgcm']),1,'C');

			//MPa
			$this->SetY($posicion_y + $this->cellsTables['tam_mpAlto']);
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_mpAncho'],$this->cellsTables['tam_mpAlto'],utf8_decode($this->cellsTables['mp']),1,'C');
			
			//Carga
			$this->SetY($posicion_y);	
			$this->SetX($posicion_x - $this->cellsTables['tam_cargaAncho']);	
			$posicion_x = ($this->GetX());
			$this->cell($this->cellsTables['tam_cargaAncho'],$this->cellsTables['tam_cargaAlto'],$this->cellsTables['carga'],1,0,'C');
			
			//kg
			$this->SetY($posicion_y + $this->cellsTables['tam_kgAlto']);	
			$this->SetX($posicion_x + $this->cellsTables['tam_kgAncho']);
			$this->cell($this->cellsTables['tam_kgAncho'],$this->cellsTables['tam_kgAlto'],$this->cellsTables['kg'],1,0,'C');

			//kN
			$this->SetY($posicion_y +  $this->cellsTables['tam_kNAlto']);	
			$this->SetX($posicion_x);
			$this->cell($this->cellsTables['tam_kNAncho'],$this->cellsTables['tam_kNAlto'],$this->cellsTables['kN'],1,0,'C');

			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$this->cellsTables['tam_font_CellsSmall']);

			//Abajo de Escpecimenes

			//Area
			$this->SetY($posicion_y + (2*$this->cellsTables['tam_areaAlto']));	
			$this->SetX($posicion_x - $this->cellsTables['tam_areaAncho']); 
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_areaAncho'],$this->cellsTables['tam_areaAlto'],utf8_decode($this->cellsTables['area']),1,'C');

			//Altura
			$this->SetY($posicion_y + (2*$this->cellsTables['tam_alturaAlto']));	
			$this->SetX($posicion_x - $this->cellsTables['tam_alturaAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_alturaAncho'],$this->cellsTables['tam_alturaAlto'],utf8_decode($this->cellsTables['altura']),1,'C');

			//Diametro
			$this->SetY($posicion_y + (2*$this->cellsTables['tam_diametroAlto']));		
			$this->SetX($posicion_x - $this->cellsTables['tam_diametroAncho']); 
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_diametroAncho'],$this->cellsTables['tam_diametroAlto'],$this->cellsTables['diametro'],1,'C');

			//Edad en dias
			$this->SetY($posicion_y + (2*$this->cellsTables['tam_edadAlto']));		
			$this->SetX($posicion_x - $this->cellsTables['tam_edadAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_edadAncho'],$this->cellsTables['tam_edadAlto'],utf8_decode($this->cellsTables['edad']),1,'C');

			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);

			//Peso en Kg
			$this->SetY($posicion_y + $this->cellsTables['tam_pesoAlto']);	
			$this->SetX($posicion_x -  $this->cellsTables['tam_pesoAncho']);	
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_pesoAncho'],$this->cellsTables['tam_pesoAlto'],$this->cellsTables['peso'],1,0,'C');

			//Rev en cm
			$this->SetY($posicion_y + $this->cellsTables['tam_revAlto']);	
			$this->SetX($posicion_x - $this->cellsTables['tam_revAncho']);	
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_revAncho'],$this->cellsTables['tam_revAlto'],$this->cellsTables['rev'],1,2,'C');
			
			//Especimenes
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x); 
			$this->cell($this->cellsTables['tam_especimenesAncho'],$this->cellsTables['tam_especimenesAlto'],$this->cellsTables['especimenes'],1,2,'C');
			
			$posicion_y = $this->GetY();
			//Clave
			$this->SetX($posicion_x - $this->cellsTables['tam_claveAncho']);	
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_claveAncho'],$this->cellsTables['tam_claveAlto'],$this->cellsTables['clave'],1,0,'C');
			//Fecha de ensaye
			$this->SetX($posicion_x - $this->cellsTables['tam_fechaAncho']);
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_fechaAncho'],$this->cellsTables['tam_fechaAlto'],$this->cellsTables['fecha'],1,0,'C');
			//Elemento
			$this->SetX(10);
			$this->cell($this->cellsTables['tam_elementoAncho'],$this->cellsTables['tam_elementoAlto'],$this->cellsTables['elemento'],1,2,'C');
			$this->ln(1);
			
			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY(); 
			$tam_elementoAncho = array_pop($this->arrayCampos);

			//Nos posicionamos al final de la celda "Elemento muestreado" para imprimir ahi todos los rows
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY(); // Guardamos las posiciones iniciales para cuando tengamos que imprimir el "Elemento muestreado"

			$grupos = 8;
			$this->putInfoTablesWithPosition($tam_elementoAncho + 10,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto'],'tam_stringCarac');

			$endDown_table = $this->GetY();
			//Imprimimos el "elemento muestreado"
			$this->SetXY($posicion_x,$posicion_y);

			$this->multicell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],$this->getMaxStringMultiCell($this->cellsTables['tam_font_CellsRows'],$tam_elementoAncho,'tam_stringCarac',$grupos),'L,T','C');
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / $this->cellsTables['tam_cellsTablesAlto']);
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],'','L',2);
				}
			}

			//Linea de decoración
			$this->cell(0,$this->cellsTables['tam_cellsTablesAlto'],'',1,1);

			//Linea con posibilidad de separador
			$this->cell(0,$this->cellsTables['tam_elementoAlto'],'-------PENDIENTE-------',1,1,'C');

			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY(); 

			//Nos posicionamos al final de la celda "Elemento muestreado" para imprimir ahi todos los rows
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY(); // Guardamos las posiciones iniciales para cuando tengamos que imprimir el "Elemento muestreado"

			$grupos = 8;
			$this->putInfoTablesWithPosition($tam_elementoAncho + 10,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto'],'tam_stringCarac');

			$endDown_table = $this->GetY();
			//Imprimimos el "elemento muestreado"
			$this->SetXY($posicion_x,$posicion_y);

			$this->multicell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],$this->getMaxStringMultiCell($this->cellsTables['tam_font_CellsRows'],$tam_elementoAncho,'tam_stringCarac',$grupos),'L,T','C');
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / $this->cellsTables['tam_cellsTablesAlto']);
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],'','L',2);
				}
			}
			//Linea de decoración
			$this->cell(0,$this->cellsTables['tam_cellsTablesAlto'],'',1,1);
		}

		function putCaracDetails(){
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			//Observaciones
			
			$this->cell($this->cellsDetails['tam_observacionesAncho'],$this->cellsDetails['tam_observacionesAlto'],$this->cellsDetails['observaciones'],'L,T,B',0);

			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);

			$this->cell($this->cellsDetails['tam_observacionAnchoTxt'],$this->cellsDetails['tam_observacionesAlto'],$this->getMaxString($this->cellsDetails['tam_font_details'],$this->cellsDetails['tam_observacionAnchoTxt'],'tam_stringCarac'),'R,T,B',2);

			$this->ln(0);

			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			//Metodos empleados
			$posicion_x = $this->GetX() + $this->cellsDetails['tam_metodosAncho'];
			$posicion_y = $this->GetY();
			$this->multicell($this->cellsDetails['tam_metodosAncho'],$this->cellsDetails['tam_metodosAlto'],$this->cellsDetails['metodos'],1);

			$this->SetXY($posicion_x,$posicion_y);
			
			//Incertidumbre
			$this->cell($this->cellsDetails['tam_incertidumbreAncho'],$this->cellsDetails['tam_incertidumbreAlto'],$this->cellsDetails['incertidumbre'],'L,R,T',2,'C');

			$this->cell($this->cellsDetails['tam_incertidumbreAncho'],$this->cellsDetails['tam_incertidumbreAlto'],$this->getMaxString($this->cellsDetails['tam_font_details'],$this->cellsDetails['tam_incertidumbreAncho'],'tam_stringCarac'),'L,R,B',1,'C');

			$this->Ln(1);

			

			$tam_image = 20;
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			
			$tam_boxElaboro = 259/3;	$tam_first = 12.5; $tam_second = 12.5;
			$posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('LAURA CASTILLO DE LA ROSA')/2),$this->gety() - 12,utf8_decode('LAURA CASTILLO DE LA ROSA'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			

			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	

			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('M en I. MARCO ANTONIO CERVANTES M.')/2),$this->gety() - 12,utf8_decode('M en I. MARCO ANTONIO CERVANTES M.'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);



			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);

			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			$this->cell($tam_boxElaboro,$tam_first,'Recibe','L,T,R',2,'C');
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX();
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NOMBRE DE QUIEN RECIBE')/2),$this->gety() - 3,utf8_decode('NOMBRE DE QUIEN RECIBE'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->Ln(0);

			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details'] - 1);
			$mensaje1 = 'ESTE INFORME DE RESULTADOS SE REFIERE EXCLUSIVAMENTE AL ENSAYE REALIZADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS, Y SOLO TIENE VALIDEZ SI NO PRESENTA TACHADURAS O ENMIENDAS';
			$this-> multicell(0,($this->cellsDetails['tam_font_details'] - 2.5),utf8_decode($mensaje1),0,2);
		}

		function Header()
		{
			$ancho_ema = 50;	$alto_ema = 20;
			$tam_lacocs = 20;
			//$this->SetX(-($ancho_ema + 10));
			//$this->Image('ema.jpeg',null,null,$ancho_ema,$alto_ema);
			$posicion_x = $this->GetX();

			$this->Image('./../../disenoFormatos/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$this->TextWithDirection($this->GetX(),$this->gety() + 24,utf8_decode('LACOCS S.A. DE C.V.'));	

			$this->Image('./../../disenoFormatos/ema.jpeg',269-$ancho_ema,$this->GetY(),$ancho_ema,$alto_ema);
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
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);


			//Numero del informe
			$this->SetX(-($this->cellsInfo['tam_informeNo'] + $this->cellsInfo['separacion']));
			$this->Cell($this->cellsInfo['tam_informeNo'],$this->cellsInfo['tam_CellsRightAlto'],$this->cellsInfo['informeNo'],0,0,'C');



			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']); 

			$this->Cell($this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_CellsRightAlto'],utf8_decode(	$this->printInfo($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_informeText'],$infoFormato['informeNo'])	),'B',0,'C');

			$this->Ln($this->cellsInfo['tam_font_right'] - 2);

			//-Informe al cual sustituye
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']);
			$this->SetX(-($this->cellsInfo['tam_sustituyeInforme'] + $this->cellsInfo['separacion']));
			$this->Cell($this->cellsInfo['tam_sustituyeInforme'],$this->cellsInfo['tam_CellsRightAlto'],$this->cellsInfo['sustituyeInforme'],0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);

			$this->Cell($this->cellsInfo['tam_sustituyeInformeText'],$this->cellsInfo['tam_CellsRightAlto'],'N/A','B',0,'C');

			//--Divide la informacion de la derecha y la izquierda
			$this->Ln($this->cellsInfo['tam_font_right'] - 1);

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			
			*/
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			
			//Cuadro con informacion
			//Obra
			$this->Cell($this->cellsInfo['tam_obra'],$this->cellsInfo['tam_CellsLeftAlto'],$this->cellsInfo['obra'],0);
			
			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode(	$this->printInfo($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_nomObraText'],$infoFormato['obra'])	),'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			//Localizacion de la obra
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			$this->Cell($this->cellsInfo['tam_locObra'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['locObra']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode(	$this->printInfo($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_localizacionText'],$infoFormato['localizacion'])	),'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);

			//Nombre del cliente
			$this->Cell($this->cellsInfo['tam_nomCli'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['nomCli']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode(	$this->printInfo($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_razonText'],$infoFormato['razonSocial'])	),'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			//Direccion del cliente
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			
			$this->Cell($this->cellsInfo['tam_dirCliente'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode($this->cellsInfo['dirCliente']),0);

			//Caja de texto
			$this->SetX($this->cellsInfo['posicionCellsText']);

			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->Cell($this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_CellsLeftAlto'],utf8_decode(	$this->printInfo($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_dirClienteText'],$infoFormato['direccion'])	),'B',0);

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($this->cellsInfo['tam_font_left']);
		}

		function putTables($infoFormato,$regisFormato){
			$posicion_y = $this->GetY();

			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);

			//Falla
			$this->SetX(-($this->cellsTables['tam_fallaAncho'] + 10));
			$posicion_x = $this->GetX();

			$this->multicell($this->cellsTables['tam_fallaAncho'],$this->cellsTables['tam_fallaAlto'],utf8_decode($this->cellsTables['falla']),1,'C');



			//Resistencia
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $this->cellsTables['tam_resistenciaAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_resistenciaAncho'],$this->cellsTables['tam_resistenciaAlto'],utf8_decode($this->cellsTables['resistencia']),1,'C');

			
			//Proyecto
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $this->cellsTables['tam_proyectoAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_proyectoAncho'],$this->cellsTables['tam_proyectoAlto'],utf8_decode($this->cellsTables['proyecto']),1,'C');
			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$this->cellsTables['tam_font_CellsSmall']);

			//Resistencia a compresion
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $this->cellsTables['tam_resis_compresionAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_resis_compresionAncho'],$this->cellsTables['tam_resis_compresionAlto'],utf8_decode($this->cellsTables['resis_compresion']),1,'C');


			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);
			//kg/cm²
			$this->SetY($posicion_y + $this->cellsTables['tam_kgcmAlto']);	
			$this->SetX($posicion_x + $this->cellsTables['tam_kgcmAncho']);
			$this->multicell($this->cellsTables['tam_kgcmAncho'],$this->cellsTables['tam_kgcmAlto'],utf8_decode($this->cellsTables['kgcm']),1,'C');

			//MPa
			$this->SetY($posicion_y + $this->cellsTables['tam_mpAlto']);
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_mpAncho'],$this->cellsTables['tam_mpAlto'],utf8_decode($this->cellsTables['mp']),1,'C');
			
			//Carga
			$this->SetY($posicion_y);	
			$this->SetX($posicion_x - $this->cellsTables['tam_cargaAncho']);	
			$posicion_x = ($this->GetX());
			$this->cell($this->cellsTables['tam_cargaAncho'],$this->cellsTables['tam_cargaAlto'],$this->cellsTables['carga'],1,0,'C');
			
			//kg
			$this->SetY($posicion_y + $this->cellsTables['tam_kgAlto']);	
			$this->SetX($posicion_x + $this->cellsTables['tam_kgAncho']);
			$this->cell($this->cellsTables['tam_kgAncho'],$this->cellsTables['tam_kgAlto'],$this->cellsTables['kg'],1,0,'C');

			//kN
			$this->SetY($posicion_y +  $this->cellsTables['tam_kNAlto']);	
			$this->SetX($posicion_x);
			$this->cell($this->cellsTables['tam_kNAncho'],$this->cellsTables['tam_kNAlto'],$this->cellsTables['kN'],1,0,'C');

			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$this->cellsTables['tam_font_CellsSmall']);

			//Abajo de Escpecimenes

			//Area
			$this->SetY($posicion_y + (2*$this->cellsTables['tam_areaAlto']));	
			$this->SetX($posicion_x - $this->cellsTables['tam_areaAncho']); 
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_areaAncho'],$this->cellsTables['tam_areaAlto'],utf8_decode($this->cellsTables['area']),1,'C');

			//Altura
			$this->SetY($posicion_y + (2*$this->cellsTables['tam_alturaAlto']));	
			$this->SetX($posicion_x - $this->cellsTables['tam_alturaAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_alturaAncho'],$this->cellsTables['tam_alturaAlto'],utf8_decode($this->cellsTables['altura']),1,'C');

			//Diametro
			$this->SetY($posicion_y + (2*$this->cellsTables['tam_diametroAlto']));		
			$this->SetX($posicion_x - $this->cellsTables['tam_diametroAncho']); 
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_diametroAncho'],$this->cellsTables['tam_diametroAlto'],$this->cellsTables['diametro'],1,'C');

			//Edad en dias
			$this->SetY($posicion_y + (2*$this->cellsTables['tam_edadAlto']));		
			$this->SetX($posicion_x - $this->cellsTables['tam_edadAncho']);
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_edadAncho'],$this->cellsTables['tam_edadAlto'],utf8_decode($this->cellsTables['edad']),1,'C');

			//----------------------SE MUEVEN FUENTES---------------------
			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);

			//Peso en Kg
			$this->SetY($posicion_y + $this->cellsTables['tam_pesoAlto']);	
			$this->SetX($posicion_x -  $this->cellsTables['tam_pesoAncho']);	
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_pesoAncho'],$this->cellsTables['tam_pesoAlto'],$this->cellsTables['peso'],1,0,'C');

			//Rev en cm
			$this->SetY($posicion_y + $this->cellsTables['tam_revAlto']);	
			$this->SetX($posicion_x - $this->cellsTables['tam_revAncho']);	
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_revAncho'],$this->cellsTables['tam_revAlto'],$this->cellsTables['rev'],1,2,'C');
			
			//Especimenes
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x); 
			$this->cell($this->cellsTables['tam_especimenesAncho'],$this->cellsTables['tam_especimenesAlto'],$this->cellsTables['especimenes'],1,2,'C');
			
			$posicion_y = $this->GetY();
			//Clave
			$this->SetX($posicion_x - $this->cellsTables['tam_claveAncho']);	
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_claveAncho'],$this->cellsTables['tam_claveAlto'],$this->cellsTables['clave'],1,0,'C');
			//Fecha de ensaye
			$this->SetX($posicion_x - $this->cellsTables['tam_fechaAncho']);
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_fechaAncho'],$this->cellsTables['tam_fechaAlto'],$this->cellsTables['fecha'],1,0,'C');
			//Elemento
			$this->SetX(10);
			$this->cell($this->cellsTables['tam_elementoAncho'],$this->cellsTables['tam_elementoAlto'],$this->cellsTables['elemento'],1,2,'C');
			$this->ln(1);
			

			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			$num_grupo = 1;
			$arrayGrupo1 = array();
			$arrayGrupo2 = array();

			if(!array_key_exists('error',$regisFormato)){
				for ($i=0; $i < count($regisFormato) ; $i++) {
					if($regisFormato[$i]['grupo'] == $num_grupo){
						$arrayLoc[$num_grupo] = $regisFormato[$i]['localizacion'];
						$num_grupo++;
					}
					switch ($regisFormato[$i]['grupo']) {
					 	case '1':
					 			unset($regisFormato[$i]['grupo'],$regisFormato[$i]['localizacion']);
					 			array_push($arrayGrupo1,$regisFormato[$i]);
					 		break;
					 	case '2':
					 			unset($regisFormato[$i]['grupo'],$regisFormato[$i]['localizacion']);
					 			array_push($arrayGrupo2,$regisFormato[$i]);
					 		break;
					 	case '3':
					 			unset($regisFormato[$i]['grupo'],$regisFormato[$i]['localizacion']);
					 			array_push($arrayGrupo3,$regisFormato[$i]);
					 		break;
					 } 
					
				}
			}

			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY(); 

			$tam_elementoAncho = array_pop($this->arrayCampos);

			//Nos posicionamos al final de la celda "Elemento muestreado" para imprimir ahi todos los rows
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY(); // Guardamos las posiciones iniciales para cuando tengamos que imprimir el "Elemento muestreado"
			$grupos = 8;
			if(count($arrayGrupo1)!=0){
				$this->putInfoTablesWithPositionInformes($tam_elementoAncho + 10,$arrayGrupo1,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$endDown_table = $this->GetY();
				//Imprimimos el "elemento muestreado"
				$this->SetXY($posicion_x,$posicion_y);

				$this->multicell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],utf8_decode($arrayLoc[1]),'L,T','C');
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / $this->cellsTables['tam_cellsTablesAlto']);
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],'','L',2);
					}
				}
			}else{
				$this->putInfoTablesWithPositionInformesWithoutInfo($tam_elementoAncho + 10,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$posicion_xfinal = $this->GetX();
				$posicion_yfinal = $this->Gety();

				$endDown_table = $this->GetY();
				//Imprimimos el "elemento muestreado"
				$this->SetXY($posicion_x,$posicion_y);

				$this->multicell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],'','L,T','C');
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / $this->cellsTables['tam_cellsTablesAlto']);
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],'','L',2);
					}
				}
				$this->Line($posicion_x, $posicion_y,269.3975,$posicion_yfinal);//Linea que completa la ultima celda
			}
			


			//Linea de decoración
			$this->cell(0,$this->cellsTables['tam_cellsTablesAlto'],'',1,1);

			//Linea con posibilidad de separador
			$this->cell(0,$this->cellsTables['tam_elementoAlto'],'-------PENDIENTE-------',1,1,'C');

			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();



			if(count($arrayGrupo2)!=0){
				$this->putInfoTablesWithPositionInformes($tam_elementoAncho + 10,$arrayGrupo2,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$endDown_table = $this->GetY();
				//Imprimimos el "elemento muestreado"
				$this->SetXY($posicion_x,$posicion_y);

				$this->multicell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],utf8_decode($arrayLoc[1]),'L,T','C');
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / $this->cellsTables['tam_cellsTablesAlto']);
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],'','L',2);
					}
				}
			}else{
				$this->putInfoTablesWithPositionInformesWithoutInfo($tam_elementoAncho + 10,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$posicion_xfinal = $this->GetX();
				$posicion_yfinal = $this->Gety();

				$endDown_table = $this->GetY();
				//Imprimimos el "elemento muestreado"
				$this->SetXY($posicion_x,$posicion_y);

				$this->multicell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],'','L,T','C');
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / $this->cellsTables['tam_cellsTablesAlto']);
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_elementoAncho,$this->cellsTables['tam_cellsTablesAlto'],'','L',2);
					}
				}
				$this->Line($posicion_x, $posicion_y,269.3975,$posicion_yfinal);//Linea que completa la ultima celda
			}

			//Linea de decoración
			$this->cell(0,$this->cellsTables['tam_cellsTablesAlto'],'',1,1);
	
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			//Observaciones
			
			$this->cell($this->cellsDetails['tam_observacionesAncho'],$this->cellsDetails['tam_observacionesAlto'],$this->cellsDetails['observaciones'],'L,T,B',0);

			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);

			$this->cell($this->cellsDetails['tam_observacionAnchoTxt'],$this->cellsDetails['tam_observacionesAlto'],utf8_decode($this->printInfo($this->cellsDetails['tam_font_details'],$this->cellsDetails['tam_observacionAnchoTxt'],$infoFormato['observaciones']))	,'R,T,B',2);

			$this->ln(0);

			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			//Metodos empleados
			$posicion_x = $this->GetX() + $this->cellsDetails['tam_metodosAncho'];
			$posicion_y = $this->GetY();
			$this->multicell($this->cellsDetails['tam_metodosAncho'],$this->cellsDetails['tam_metodosAlto'],$this->cellsDetails['metodos'],1);

			$this->SetXY($posicion_x,$posicion_y);
			
			//Incertidumbre
			$this->cell($this->cellsDetails['tam_incertidumbreAncho'],$this->cellsDetails['tam_incertidumbreAlto'],$this->cellsDetails['incertidumbre'],'L,R,T',2,'C');

			$this->cell($this->cellsDetails['tam_incertidumbreAncho'],$this->cellsDetails['tam_incertidumbreAlto'],utf8_decode($this->printInfo($this->cellsDetails['tam_font_details'],$this->cellsDetails['tam_incertidumbreAncho'],$infoFormato['incertidumbreCilindro'])),'L,R,B',1,'C');

			$this->Ln(1);

			

			$tam_image = 20;
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			
			$tam_boxElaboro = 259/3;	$tam_first = 12.5; $tam_second = 12.5;
			$posicion_y = $this->GetY();
			$this->cell($tam_boxElaboro,$tam_first,'Realizo','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('SIGNATARIO/JEFE DE LABORATORIO')/2),$this->gety() - 3,utf8_decode('SIGNATARIO/JEFE DE LABORATORIO'));	
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('LAURA CASTILLO DE LA ROSA')/2),$this->gety() - 12,utf8_decode('LAURA CASTILLO DE LA ROSA'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);

			

			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();

			
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	

			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('DIRECTOR GENERAL/GERENTE GENERAL')/2),$this->gety() - 3,utf8_decode('DIRECTOR GENERAL/GERENTE GENERAL'));	
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('M en I. MARCO ANTONIO CERVANTES M.')/2),$this->gety() - 12,utf8_decode('M en I. MARCO ANTONIO CERVANTES M.'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);



			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);

			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			$this->cell($tam_boxElaboro,$tam_first,'Recibe','L,T,R',2,'C');
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');
			$posicion_x = $this->GetX();
			$this->TextWithDirection($posicion_x+10,$this->gety() - 7,utf8_decode('___________________________________________'));	
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NOMBRE DE QUIEN RECIBE')/2),$this->gety() - 3,utf8_decode('NOMBRE DE QUIEN RECIBE'));	
			$this->Image('./../../disenoFormatos/firma.png',(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			$this->Ln(0);

			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details'] - 1);
			$mensaje1 = 'ESTE INFORME DE RESULTADOS SE REFIERE EXCLUSIVAMENTE AL ENSAYE REALIZADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS, Y SOLO TIENE VALIDEZ SI NO PRESENTA TACHADURAS O ENMIENDAS';
			$this-> multicell(0,($this->cellsDetails['tam_font_details'] - 2.5),utf8_decode($mensaje1),0,2);		
			
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
			$pdf  = new InformeCilindros('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->generateCellsInfo();
			$pdf->putInfo($infoFormato);
			$pdf->generateCellsCampos();
			$pdf->generateCellsDetails();
			$pdf->putTables($infoFormato,$regisFormato);
			$pdf->Output('F',$target_dir);
			//$pdf->Output();
		}
		
	}
	
	
?>