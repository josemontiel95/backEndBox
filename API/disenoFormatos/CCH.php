<?php 
	include_once("./../../FPDF/MyPDF.php");
	
	//Formato de campo de cilindros
	class CCH extends MyPDF{

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

		function getCellsTables(){
			return $this->cellsTables;
		}

		function getcellsDetails(){
			return $this->cellsDetails;
		}


		function generateCellsInfo(){
			//Estilo y tamaño de la informacion de la IZQUIERDA

			/*
				Notas:
						-Hay un errro con los tamaños de las celdas, no repercute pero modificar cuando se pueda	
			*/
			$tam_font_left = 7;	
			$this->SetFont('Arial','B',$tam_font_left);
			$tam_cellsAlto =  $tam_font_left - 3;

			$obra 			= 'NOMBRE DE OBRA:';
			$tam_obra 		= $this->GetStringWidth($obra)+2;

			$locObra		= 'LOCALIZACIÓN DE LA OBRA:';
			$tam_locObra 	= $this->GetStringWidth($locObra)+2;

			$nomCli 		= 'NOMBRE DE LA EMPRESA:';
			$tam_nomCli		= $this->GetStringWidth($nomCli)+2;

			$dirCliente 	= 'DIRECCIÓN DE LA EMPRESA:';
			$tam_dirCliente	= $this->GetStringWidth($nomCli)+2;

			//Estilo y tamaño de la informacion de la DERECHA

			$tam_font_right = 7.5;	
			$this->SetFont('Arial','B',$tam_font_right);
			$tam_cellsRight = $tam_font_right - 3;
			$informeNo 		= 'INFORME No.';
			$tam_informeNo 	= $this->GetStringWidth($informeNo)+2;

			//Definimos el tamaño de la linea de toda la información
			$tam_nomObraText = $tam_localizacionText = $tam_razonText = $tam_dirClienteText = 160;
			$tam_informeText = 40;

			$this->cellsInfo 	= 	array(
											'tam_font_right'		=>	$tam_font_right,
											'tam_font_left'			=>	$tam_font_left,

											'tam_cellsAlto'			=> $tam_cellsAlto,

											'tam_cellsRight'		=>	$tam_cellsRight,
											'obra'					=> $obra,
											'tam_obra'				=> $tam_obra,

											'locObra'				=> $locObra,
											'tam_locObra'			=> $tam_locObra,



											'informeNo'				=> $informeNo,
											'tam_informeNo'			=> $tam_informeNo,

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
			//Tamaño y estilo de la letra para los campos
			$tam_font_Cells = 7;	$this->SetFont('Arial','B',$tam_font_Cells);
			$tam_font_CellsRows = 5;
			$tam_cellsTablesAlto = $tam_font_Cells - 3;

			//Clave
			$clave = 'CLAVE DEL ESPECIMEN';
			$tam_claveAncho = $this->GetStringWidth($clave) + 10; 
			$tam_claveAlto = 1.5*(2*($tam_font_Cells) - 6);

			//Fecha
			$fecha = 'FECHA';
			$tam_fechaAncho = $this->GetStringWidth($fecha) + 7;
			$tam_fechaAlto = 1.5*(2*($tam_font_Cells) - 6);

			//F´c
			$fprima = 'F ` C ';
			$tam_fprimaAncho = $this->GetStringWidth($fecha) + 2; 
			$tam_fprimaAlto = 0.5*(1.5*(2*($tam_font_Cells) - 6));
			$fprima = $fprima."\n".'kg/cm²';

			//Proyecto
			$proyecto = 'PROYECTO';
			$tam_proAncho = $this->GetStringWidth($proyecto) + 2;
			$tam_proAlto = 0.5*(1.5*(2*($tam_font_Cells) - 6));

			//Obra
			$obra = 'OBRA';
			$tam_obraAncho = $this->GetStringWidth($obra) + 2;
			$tam_obraAlto = 0.5*(1.5*(2*($tam_font_Cells) - 6));

			//Revenimiento
			$rev = 'REVENIMENTO (cm)';
			$tam_revAncho = $tam_proAncho + $tam_obraAncho;
			$tam_revAlto = 0.5*(1.5*(2*($tam_font_Cells) - 6));
			
			//Movimiento de tamaño de fuente

			$tam_font_Cells = $tam_font_Cells-2;	
			$this->SetFont('Arial','B',$tam_font_Cells);

			//Agregado
			$agregado = 'AGREGADO';
			$tam_agregadoAncho = $this->GetStringWidth($agregado) + 3;
			$tam_agregadoAlto = (1.5*(2*($tam_font_Cells + 2) - 6))/5;
			$agregado = 'TAMAÑO'."\n".'NOMINAL'."\n".'DEL'."\n".'AGREGADO'."\n".'(mm)';
			
			//VOLUMEN
			$volumen = 'VOLUMEN';
			$tam_volumenAncho = $this->GetStringWidth($volumen) + 3;
			$tam_volumenAlto = (1.5*(2*($tam_font_Cells + 2) - 6))/2;
			$volumen = $volumen."\n".'(m³)';
			
			//Tipo de concreto
			$concreto = 'CONCRE';
			$tam_concretoAncho = $this->GetStringWidth($concreto) + 3;
			$tam_concretoAlto = (1.5*(2*($tam_font_Cells + 2) - 6))/3;
			$concreto = 'TIPO DE'."\n".$concreto."\n".'TO';

			//Unidad
			$unidad = 'UNIDAD';
			$tam_unidadAncho = $this->GetStringWidth($unidad) + 5;
			$tam_unidadAlto = (1.5*(2*($tam_font_Cells + 2) - 6));
			
			//Hora de muestreo en obra
			$hora = 'HORA DE';
			$tam_horaAncho = $this->GetStringWidth($hora) + 5;
			$tam_horaAlto = (1.5*(2*($tam_font_Cells + 2) - 6))/4;
			$hora = $hora."\n".'MUESTREO'."\n".'EN'."\n".'OBRA';
			
			//Temperatura ambiente de muestrero
			$muestreo = 'MUESTREO';
			$tam_muestreoAncho = $this->GetStringWidth($muestreo) + 3;
			$tam_muestreoAlto = (1.5*(2*($tam_font_Cells + 2) - 6))/5;
			$muestreo = 'TEMP.'."\n".'AMBIENTE'."\n".'DE'."\n".$muestreo."\n".'(°C)';

			//Temperatura de recoleccion
			$recoleccion = 'RECOLECCIÓN';
			$tam_recoleccionAncho = $this->GetStringWidth($recoleccion) + 2;
			$tam_recoleccionAlto = (1.5*(2*($tam_font_Cells + 2) - 6))/5;
			$recoleccion = 'TEMP.'."\n".'AMBIENTE'."\n".'DE'."\n".$recoleccion."\n".'(°C)';
		
			//Se Mueven el tamaño de las fuentes

			$tam_font_Cells = 7;	$this->SetFont('Arial','B',$tam_font_Cells);//Fuente para clave

			//Localizacion
			$localizacion = 'LOCALIZACIÓN';
			$tam_elementoAncho = 259.3975 - (
													$tam_claveAncho +
													$tam_fechaAncho +
													$tam_fprimaAncho +
													$tam_proAncho +
													$tam_obraAncho +
													$tam_agregadoAncho +
													$tam_volumenAncho +
													$tam_concretoAncho +
													$tam_unidadAncho +
													$tam_horaAncho +
													$tam_muestreoAncho +
													$tam_recoleccionAncho
												);

			$tam_localizacionAlto = 1.5*(2*($tam_font_Cells) - 6);
			
			//Definimos el arreglo que contiene todos los tamaños de las celdas

			$this->cellsTables = array(
											'tam_font_Cells'			=>	$tam_font_Cells,
											'tam_font_CellsRows'		=>	$tam_font_CellsRows,

											'tam_cellsTablesAlto'		=>	$tam_cellsTablesAlto,

											'clave' 					=>	$clave,
											'tam_claveAncho'			=>	$tam_claveAncho,
											'tam_claveAlto'				=>	$tam_claveAlto,

											'fecha' 					=>	$fecha,
											'tam_fechaAncho'			=>	$tam_fechaAncho,
											'tam_fechaAlto'				=>	$tam_fechaAlto,

											'fprima' 					=>	$fprima,
											'tam_fprimaAncho'			=>	$tam_fprimaAncho,
											'tam_fprimaAlto'			=>	$tam_fprimaAlto,

											'proyecto'					=>	$proyecto,
											'tam_proAncho'				=>	$tam_proAncho,
											'tam_proAlto'				=>	$tam_proAlto,

											'obra' 						=>	$obra,
											'tam_obraAncho'				=>	$tam_obraAncho,
											'tam_obraAlto'				=>	$tam_obraAlto,

											'rev' 						=>	$rev,
											'tam_revAncho'				=>	$tam_revAncho,
											'tam_revAlto'				=>	$tam_revAlto,

											'agregado'					=>	$agregado,
											'tam_agregadoAncho'			=>	$tam_agregadoAncho,
											'tam_agregadoAlto'			=>	$tam_agregadoAlto,

											'volumen'					=>	$volumen,
											'tam_volumenAncho'			=>	$tam_volumenAncho,
											'tam_volumenAlto'			=>	$tam_volumenAlto,

											'concreto'					=>	$concreto,
											'tam_concretoAncho'			=>	$tam_concretoAncho,
											'tam_concretoAlto'			=>	$tam_concretoAlto,

											'unidad'					=>	$unidad,
											'tam_unidadAncho'			=>	$tam_unidadAncho,
											'tam_unidadAlto'			=>	$tam_unidadAlto,

											'hora'						=>	$hora,
											'tam_horaAncho'				=>	$tam_horaAncho,
											'tam_horaAlto'				=>	$tam_horaAlto,

											'muestreo'					=>	$muestreo,
											'tam_muestreoAncho'			=>	$tam_muestreoAncho,
											'tam_muestreoAlto'			=>	$tam_muestreoAlto,

											'recoleccion'				=>	$recoleccion,
											'tam_recoleccionAncho'		=>	$tam_recoleccionAncho,
											'tam_recoleccionAlto'		=>	$tam_recoleccionAlto,

											'localizacion'				=>	$localizacion,							
											'tam_elementoAncho'		=>	$tam_elementoAncho,
											'tam_localizacionAlto'		=>	$tam_localizacionAlto
							);
			 
			 $this->arrayCampos = 	array(
											$tam_claveAncho,
											$tam_fechaAncho,
											$tam_fprimaAncho,
											$tam_proAncho,
											$tam_obraAncho,
											$tam_agregadoAncho,
											$tam_volumenAncho,
											$tam_concretoAncho,
											$tam_unidadAncho,
											$tam_horaAncho,
											$tam_muestreoAncho,
											$tam_recoleccionAncho,
											$tam_elementoAncho									
									);

		}

		function generateCellsDetails(){
			//Tamaño de fuente de la que depende todos los detalles
			$tam_font_details = 7;

			

			//Recordar que cuando se pinta la celda con valor 259 se pone 0 como ancho
			$observaciones = 'OBSERVACIONES:';
			$tam_observacionesAlto = 2*($tam_font_details - 2.5);
			$tam_observacionesAltoTxt = 10;	//Tamaño del alto de la celda
			$tam_observacionAnchoTxt = 259;		//Tamaño de ancho de la celda


			$metodos = 'METODOS EMPLEADOS: NMX-C-161-ONNCCE-2013, NMX-C-159-ONNCCE-2016, NMX-C156-ONNCCE-2010';
			$tam_metodosAlto = $tam_font_details - 3;
			$tam_metodosAncho = 0;


			//Definimos el tamaño de letra del inventario y el tamaño de las celdas de alto
			$tam_font_inventario = 6.5; 
			$tam_inventarioAlto = $tam_font_inventario - 2;


			$this->SetFont('Arial','B',$tam_font_inventario);

			$instrumentos = 'Inventario de'."\n".'instrumentos';
			$tam_instrumentos = $this->GetStringWidth('Inventario de')+5;

			

			$termo = 'Termómetro';
			$tam_termo = $this->GetStringWidth($termo)+10;

			$cono = 'Cono';
			$tam_cono = $tam_termo;

			$varilla = 'Varilla';
			$tam_varilla = $tam_termo;

			$flexometro = 'Flexometro';
			$tam_flexometro = $tam_termo;

			//Usamos como referencia el tamaño de la cadena almacenada en la variable $termo

			$cili = 'CILINDROS';
			$tam_cili = $this->GetStringWidth($termo)+10;


			$cubos = 'CUBOS';
			$tam_cubos = $tam_cili;


			$vigas = 'VIGAS';
			$tam_vigas = $tam_cili;

			//Celda que indica que tipo de formato es

			$tam_selectCell = $this->GetStringWidth($termo);

			//Celda donde va la firma

			$tamCelda_firmaAncho = 90;

			
			$tamCelda_firmaAlto = 10;

			//Letrero debajo de la firma

			$laboratorista = 'LABORATORISTA/ASIGNATARIO';
			$tam_laboratoristaAncho = $tamCelda_firmaAncho;
			$tam_laboratoristaAlto = $tam_font_details - 3;

			$this->cellsDetails = array(
											'tam_font_details'			=>	$tam_font_details,
											'tam_font_inventario'		=>	$tam_font_inventario,

											'observaciones'				=>	$observaciones,
											'tam_observacionesAlto'		=>	$tam_observacionesAlto,
											'tam_observacionAnchoTxt'		=>	$tam_observacionAnchoTxt,
											'tam_observacionesAltoTxt'		=>	$tam_observacionesAltoTxt,

											'metodos'					=>	$metodos,
											'tam_metodosAncho'			=>	$tam_metodosAncho,
											'tam_metodosAlto'			=>	$tam_metodosAlto,

											'tam_inventarioAlto'		=>	$tam_inventarioAlto,
											'instrumentos'				=>	$instrumentos,
											'tam_instrumentos'			=>	$tam_instrumentos,

											'termo'						=>	$termo,
											'tam_termo'					=>	$tam_termo,

											'cono'						=>	$cono,
											'tam_cono'					=>	$tam_cono,

											'varilla'					=>	$varilla,
											'tam_varilla'				=>	$tam_varilla,

											'flexometro'				=>	$flexometro,
											'tam_flexometro'			=>	$tam_flexometro,

											'cili'						=>	$cili,
											'tam_cili'					=>	$tam_cili,

											'cubos'						=>	$cubos,
											'tam_cubos'					=>	$tam_cubos,

											'vigas'						=>	$vigas,
											'tam_vigas'					=>	$tam_vigas,

											'tam_selectCell'			=>	$tam_selectCell,

											'tamCelda_firmaAncho'		=>	$tamCelda_firmaAncho,
											'tamCelda_firmaAlto'		=>	$tamCelda_firmaAlto,

											'laboratorista'				=>	$laboratorista,
											'tam_laboratoristaAncho'	=>	$tam_laboratoristaAncho,
											'tam_laboratoristaAlto'		=>	$tam_laboratoristaAlto
										);


			
		}

		function Header(){
			//Tamaños imagen LACOCS
			$tam_lacocs = 20;

			$posicion_x = $this->GetX();

			$this->Image('./../../disenoFormatos/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$posicion_x = $this->GetX();

			//Definimos el las propiedades de la primera linea del titulo
			$tam_font_titulo = 9;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo_linea1 = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A. DE C.V.';
			$tam_cell = $this->GetStringWidth($titulo_linea1);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo_linea1),0,2,'C');

			//Definimos las propiedades de la segunda linea del titulo
			$tam_font_titulo = 16; //Definimos el tamaño de la fuente
			$this->SetFont('Arial','B',$tam_font_titulo);
			$titulo_linea2 = 'LACOCS S.A. DE C.V.';
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
			//$this->ln(2);	

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			//Cuadro con informacion

			//Nombre de la obra
			$this->Cell($this->cellsInfo['tam_obra'],$this->cellsInfo['tam_cellsAlto'],$this->cellsInfo['obra'],0);


			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->SetX(50);

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_cellsAlto'],$infoFormato['obra'],3);


			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['obra'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado;
			}


			$this->multicell($this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($infoFormato['obra']),'B','C');
			

			$this->Ln(1);
			
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
			
			//Localizacion de la obra
			$this->Cell($this->cellsInfo['tam_locObra'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['locObra']),0);

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->SetX(50);
			
			//Guardamos esta posicion de "Y" para posteriormente imprimir el campo informe No.
			$posiciony = $this->GetY();

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_cellsAlto'],$infoFormato['localizacion'],3);


			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['localizacion'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado;
			}

			$this->multicell($this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($infoFormato['localizacion']),'B','C');

			$posicionyAux = $this->GetY();
			

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 

			//$this->SetY($posiciony);

			//Numero del informe
			$this->SetXY(-($this->cellsInfo['tam_informeNo']+$this->cellsInfo['tam_informeText'] + 10),$posiciony);
			$this->Cell($this->cellsInfo['tam_informeNo'],$this->cellsInfo['tam_cellsAlto'],$this->cellsInfo['informeNo'],0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_right'],$this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_cellsAlto'],$infoFormato['informeNo'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['informeNo'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado;
			}

			$this->multicell($this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($infoFormato['informeNo']),'B','C');

			$this->SetY($posicionyAux + 1);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
			//Nombre del cliente
			$this->Cell($this->cellsInfo['nomCli'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['nomCli']),0);

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']); 
			$this->SetX(50);


			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_cellsAlto'],$infoFormato['razonSocial'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['razonSocial'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado;
			}

			
			$this->multicell($this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($infoFormato['razonSocial']),'B','C');

			$this->Ln(1);

			//Direccion del cliente
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
			$this->Cell($this->cellsInfo['tam_dirCliente'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['dirCliente']),0);
			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);
			$this->SetX(50);

			$resultado = $this->printInfoObraAndLocObra($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_cellsAlto'],$infoFormato['direccion'],1);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['direccion'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado;
			}


			$this->multicell($this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($infoFormato['direccion']),'B','C');

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln(2);

			//Titulo del CCH
			$tam_font_tituloCCH = 12; //Definimos el tamaño de la fuente
			$this->SetFont('Arial','B',$tam_font_tituloCCH);
			$titulo_CHH = 'CONTROL DE CONCRETO HIDRÁULICO';
			$tam_cell = $this->GetStringWidth($titulo_CHH);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_tituloCCH - 3,utf8_decode($titulo_CHH),0,2,'C');
			$this->Ln(2);
			
		}

		function putCaracInfo(){
			$this->ln(4);	

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']);
			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			//Cuadro con informacion

			//Nombre de la obra
			$this->Cell($this->cellsInfo['tam_obra'],$this->cellsInfo['tam_cellsAlto'],$this->cellsInfo['obra'],0);


			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->SetX(50);

			$string = 'gola'."\n".'como'."\n"."estas'";

			$this->multicell($this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_cellsAlto'],$string,'B','C');

			$this->Ln(1);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
	
			//Localizacion de la obra
			$this->Cell($this->cellsInfo['tam_locObra'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['locObra']),0);

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->SetX(50);

			//Guardamos la posicion de y para poner la siguiente celda

			$posiciony = $this->GetY();

			$string = 'gola'."\n".'como'."\n"."estas'";

			$this->multicell($this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_cellsAlto'],$string,'B','C');

			$posicionyAux = $this->GetY();


			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']); 

			$this->SetY($posiciony);

			//Numero del informe
			$this->SetX(-($this->cellsInfo['tam_informeNo']+40));
			$this->Cell($this->cellsInfo['tam_informeNo'],$this->cellsInfo['tam_cellsAlto'],$this->cellsInfo['informeNo'],0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);
		
			$this->Cell($this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_cellsAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_informeText'],'tam_stringCarac'),'B',0,'C');

			$this->Sety($posicionyAux + 1);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
			//Nombre del cliente
			$this->Cell($this->cellsInfo['nomCli'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['nomCli']),0);

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']); 
			$this->SetX(50);
			
			$this->Cell($this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_cellsAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_razonText'],'tam_stringCarac') ,'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			//Direccion del cliente
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
			$this->Cell($this->cellsInfo['tam_dirCliente'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['dirCliente']),0);
			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);
			$this->SetX(50);

			$this->Cell($this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_cellsAlto'],$this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_dirClienteText'],'tam_stringCarac'),'B',0);

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln(3);

			//Titulo del CCH
			$tam_font_tituloCCH = 12; //Definimos el tamaño de la fuente
			$this->SetFont('Arial','B',$tam_font_tituloCCH);
			$titulo_CHH = 'CONTROL DE CONCRETO HIDRÁULICO';
			$tam_cell = $this->GetStringWidth($titulo_CHH);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_tituloCCH - 5,utf8_decode($titulo_CHH),0,2,'C');
			

		}


		function demo(){
			$pdf  = new CCH('L','mm','Letter');
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

		

		function putCaracCampos(){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);//Fuente para clave

			//Clave
			$this->cell($this->cellsTables['tam_claveAncho'],$this->cellsTables['tam_claveAlto'],$this->cellsTables['clave'],1,0,'C');

			//Fecha
			$this->cell($this->cellsTables['tam_fechaAncho'],$this->cellsTables['tam_fechaAlto'],$this->cellsTables['fecha'],1,0,'C');

			//F´c
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_fprimaAncho'],$this->cellsTables['tam_fprimaAlto'],utf8_decode($this->cellsTables['fprima']),1,'C');

			//Proyecto
			$this->SetY($posicion_y + $this->cellsTables['tam_proAlto']); 
			$this->SetX($posicion_x + $this->cellsTables['tam_fprimaAncho']);	
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_proAncho'],$this->cellsTables['tam_proAlto'],$this->cellsTables['proyecto'],1,0,'C');

			//Obra
			$this->SetY($posicion_y + $this->cellsTables['tam_proAlto']);	
			$this->SetX($posicion_x + $this->cellsTables['tam_proAncho']);
			$this->cell($this->cellsTables['tam_obraAncho'],$this->cellsTables['tam_obraAlto'],$this->cellsTables['obra'],1,2,'C');


			//Revenimiento
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x); 
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_revAncho'],$this->cellsTables['tam_revAlto'],$this->cellsTables['rev'],1,0,'C');

			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells'] - 2);

			//Guardamos la posicion de la x
			$posicion_x = $this->GetX();

			//Agregado
			$this->multicell($this->cellsTables['tam_agregadoAncho'],$this->cellsTables['tam_agregadoAlto'],utf8_decode($this->cellsTables['agregado']),1,'C');

			$posicion_x = ($posicion_x + $this->cellsTables['tam_agregadoAncho']);
			//VOLUMEN
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_volumenAncho'],$this->cellsTables['tam_volumenAlto'],utf8_decode($this->cellsTables['volumen']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_volumenAncho'];

			//Concreto
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_concretoAncho'],$this->cellsTables['tam_concretoAlto'],utf8_decode($this->cellsTables['concreto']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_concretoAncho'];

			//Unidad
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->cell($this->cellsTables['tam_unidadAlto'],$this->cellsTables['tam_unidadAncho'],utf8_decode($this->cellsTables['unidad']),1,0,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_unidadAlto'];

			//Hora
			$this->multicell($this->cellsTables['tam_horaAncho'],$this->cellsTables['tam_horaAlto'],utf8_decode($this->cellsTables['hora']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_horaAncho'];

			//Muestreo
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_muestreoAncho'],$this->cellsTables['tam_muestreoAlto'],utf8_decode($this->cellsTables['muestreo']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_muestreoAncho'];

			//Recolecicon
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_recoleccionAncho'], $this->cellsTables['tam_recoleccionAlto'],utf8_decode( $this->cellsTables['recoleccion']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_recoleccionAncho'];

			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);

			//Localizacion
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_elementoAncho'],$this->cellsTables['tam_localizacionAlto'],utf8_decode($this->cellsTables['localizacion']),1,2,'C');
			$posicion_y = $this->GetY();
						
			$this->SetX(10); //Definimos donde empieza los 

			//Hacemos un "switch" para ver de que tipo de CCH devemos imprimi
			$grupos = 4;
			$bandC = 0;
			$bandCi = 0;
			$bandV = 0;
			switch ('VIGAS') {
				case 'CILINDRO':
					$bandCi = 1;
					break;
				case 'CUBO':
					$bandC = 1;
					break;
				case 'VIGAS':
					$bandV = 1;
					$grupos = 3;
					break;
			}

			$num_rows = 0;
			//Guardamos el ultimo valor porque es el mas dificil
			
			$tam_localizacion = array_pop($this->arrayCampos);
			$this->putInfoTables($grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto'],'tam_stringCarac');
			
			
			$endDown_table = $this->GetY();


			//Nos posicionamos abajo de la localizacion
			$this->SetXY($posicion_x,$posicion_y);

			$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$this->getMaxStringMultiCell($this->cellsTables['tam_font_CellsRows'],$tam_localizacion,'string',$grupos),'R,T');
			$this->SetX($posicion_x);
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
				}
			}
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->ln(0);
			

			//Segundo grupo
			$this->putInfoTables($grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto'],'tam_stringCarac');

			$endDown_table = $this->GetY();

			//Nos posicionamos abajo de la localizacion
			$this->SetXY($posicion_x,$posicion_y);

			//Lineas de codigo para hacer una demostracion de los caracteres que entran en la celda del elemento muetreado
			$string = 'Demostracion de la cantidad de caracteres que pueden entrar en esta celda.Como esta previsto para el peor de los casos solo hay 138 carac.';
			$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],utf8_decode($string),'R,T');
			//$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$this->getMaxStringMultiCell($this->cellsTables['tam_font_CellsRows'],$tam_localizacion,'tam_stringCarac',$grupos),'R,T');
			$this->SetX($posicion_x);
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
				}
			}
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->ln(0);

			//Tercer grupo
			$this->putInfoTables($grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto'],'tam_stringCarac');
			
			$endDown_table = $this->GetY();

			//Nos posicionamos abajo de la localizacion
			$this->SetXY($posicion_x,$posicion_y);

			//$string = 'Prueba de Localización del elemento muestreado, si existe algun error, tal como: mal saltos de linea, ortograficos o tamaño de letra contacte a 01-800-TEC4U. ¡Que tenga buen día! :)';
			//$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'Cadena:'.utf8_decode($string).'-Cantidad de caractares:'.strlen($string),'R,T');
			$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$this->getMaxStringMultiCell($this->cellsTables['tam_font_CellsRows'],$tam_localizacion,'tam_stringCarac',$grupos),'R,T');
			$this->SetX($posicion_x);
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
				}
			}
			//Pintamos la ultima linea que nos falta
			$this->line($this->GetX(),$this->GetY(),$this->GetX()+$tam_localizacion,$this->GetY());

			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			/*
				-Cancelar celdas-

			$this->line($posicion_xCancel,$posicion_yCancel,$this->GetX(),$this->GetY());
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();*/



			$this->Ln(5);//Separacion con la parte de los detalles
			/*
			
			
			*/

		}

		function putCaracDetails(){
			//Hacemos un "switch" para ver de que tipo de CCH devemos imprimi
			$grupos = 4;
			$bandC = 0;
			$bandCi = 0;
			$bandV = 0;
			switch ('VIGAS') {
				case 'CILINDRO':
					$bandCi = 1;
					break;
				case 'CUBO':
					$bandC = 1;
					break;
				case 'VIGAS':
					$bandV = 1;
					$grupos = 3;
					break;
			}
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);

			//Observaciones
			
			$this->cell(0,$this->cellsDetails['tam_observacionesAlto'],$this->cellsDetails['observaciones'],'L,T,R',2);
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);
			//Restamos uno porque el no centrar el texto hace que se salga la cena

			$posicion_x = $this->GetX();
			//Lo dejamos en 0 para que ocupe todo el espacio de la hoja
			$this->cell(0,$this->cellsDetails['tam_observacionesAltoTxt'],$this->getMaxString($this->cellsDetails['tam_font_details'],$this->cellsDetails['tam_observacionAnchoTxt'],'tam_stringCarac'),'L,B,R',0);
			$tam_final = $this->GetX() - $posicion_x;
			$this->ln();
			//Metodos
			
			$this->cell($this->cellsDetails['tam_metodosAncho'],$this->cellsDetails['tam_metodosAlto'],$this->cellsDetails['metodos'],1,2,'C');


			$this->cell(0,2,'',1,2,'C');


			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			//Instrumentos
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_inventario']);
			$this->multicell($this->cellsDetails['tam_instrumentos'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['instrumentos'],1,'C');


			$this->SetXY(($posicion_x + $this->cellsDetails['tam_instrumentos']),$posicion_y);

			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();

			//Cono
			$this->cell($this->cellsDetails['tam_cono'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['cono'],1,2,'C');

			//Caja de texto
			$this->cell($this->cellsDetails['tam_cono'],$this->cellsDetails['tam_inventarioAlto'],$this->getMaxString($this->cellsDetails['tam_font_inventario'],$this->cellsDetails['tam_cono'],'tam_stringCarac'),1,2,'C');

			$this->SetXY(($posicion_x + $this->cellsDetails['tam_cono']),$posicion_y);

			
			//Varilla
			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();
			$this->cell($this->cellsDetails['tam_varilla'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['varilla'],1,2,'C');

			//Caja de texto
			$this->cell($this->cellsDetails['tam_varilla'],$this->cellsDetails['tam_inventarioAlto'],$this->getMaxString($this->cellsDetails['tam_font_inventario'],$this->cellsDetails['tam_varilla'],'tam_stringCarac'),1,2,'C');


			$this->SetXY(($posicion_x + $this->cellsDetails['tam_varilla']),$posicion_y);

			//Felxometro
			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();

			$this->cell($this->cellsDetails['tam_flexometro'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['flexometro'],1,2,'C');

			//Caja de texto
			$this->cell($this->cellsDetails['tam_flexometro'],$this->cellsDetails['tam_inventarioAlto'],$this->getMaxString($this->cellsDetails['tam_font_inventario'],$this->cellsDetails['tam_flexometro'],'tam_stringCarac'),1,2,'C');

			$this->SetXY(($posicion_x + $this->cellsDetails['tam_flexometro']),$posicion_y);
			
			//Termometro
			$this->cell($this->cellsDetails['tam_termo'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['termo']),1,2,'C');

			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();

			//Caja de texto
			$this->cell($this->cellsDetails['tam_termo'],$this->cellsDetails['tam_inventarioAlto'],$this->getMaxString($this->cellsDetails['tam_font_inventario'],$this->cellsDetails['tam_termo'],'tam_stringCarac'),1,2,'C');

			

			$this->SetXY(($posicion_x + $this->cellsDetails['tam_termo'] + 12),$posicion_y);

			//Cilindros
			$this->cell($this->cellsDetails['tam_cili'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['cili']),0,0,'C');

			//Caja de texto
			$this->cell($this->cellsDetails['tam_selectCell'],$this->cellsDetails['tam_inventarioAlto'],'',1,0,'C',$bandCi);
			$posicion_x = $this->GetX();


			//Cubos
			$this->cell($this->cellsDetails['tam_cubos'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['cubos']),0,0,'C');

			//Caja de texto
			$this->cell($this->cellsDetails['tam_selectCell'],$this->cellsDetails['tam_inventarioAlto'],'',1,0,'C',$bandC);

			//Vigas
			$this->cell($this->cellsDetails['tam_vigas'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['vigas']),0,0,'C');
			//Caja de texto
			$this->cell($this->cellsDetails['tam_selectCell'],$this->cellsDetails['tam_inventarioAlto'],'',1,0,'C',$bandV);
			

			$tam_image = 15;

			$this->Ln(12);	
			$this->SetX((279.4-$this->cellsDetails['tamCelda_firmaAncho'])/2);

			$posicion_x = $this->GetX(); 
			$posicion_y = $this->GetY();

			$this->cell($this->cellsDetails['tamCelda_firmaAncho'],$this->cellsDetails['tamCelda_firmaAlto'],'NOMBRE DE QUIEN FIRMA','B',2,'C');
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			$this->cell($this->cellsDetails['tam_laboratoristaAncho'],$this->cellsDetails['tam_laboratoristaAlto'],$this->cellsDetails['laboratorista'],0,0,'C');
			//$this->Image('./../../disenoFormatos/firmas/firma.png',(($posicion_x+($this->cellsDetails['tamCelda_firmaAncho'])/2)-($tam_image/2)),($posicion_y + (($this->cellsDetails['tamCelda_firmaAlto'])/2))-($tam_image/2),$tam_image,$tam_image);
		}

	
		function putTables($infoFormato,$regisFormato){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);//Fuente para clave

			//Clave
			$this->cell($this->cellsTables['tam_claveAncho'],$this->cellsTables['tam_claveAlto'],$this->cellsTables['clave'],1,0,'C');

			//Fecha
			$this->cell($this->cellsTables['tam_fechaAncho'],$this->cellsTables['tam_fechaAlto'],$this->cellsTables['fecha'],1,0,'C');

			//F´c
			$posicion_x = $this->GetX();
			$this->multicell($this->cellsTables['tam_fprimaAncho'],$this->cellsTables['tam_fprimaAlto'],utf8_decode($this->cellsTables['fprima']),1,'C');

			//Proyecto
			$this->SetY($posicion_y + $this->cellsTables['tam_proAlto']); 
			$this->SetX($posicion_x + $this->cellsTables['tam_fprimaAncho']);	
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_proAncho'],$this->cellsTables['tam_proAlto'],$this->cellsTables['proyecto'],1,0,'C');

			//Obra
			$this->SetY($posicion_y + $this->cellsTables['tam_proAlto']);	
			$this->SetX($posicion_x + $this->cellsTables['tam_proAncho']);
			$this->cell($this->cellsTables['tam_obraAncho'],$this->cellsTables['tam_obraAlto'],$this->cellsTables['obra'],1,2,'C');


			//Revenimiento
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x); 
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_revAncho'],$this->cellsTables['tam_revAlto'],$this->cellsTables['rev'],1,0,'C');

			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells'] - 2);

			//Guardamos la posicion de la x
			$posicion_x = $this->GetX();

			//Agregado
			$this->multicell($this->cellsTables['tam_agregadoAncho'],$this->cellsTables['tam_agregadoAlto'],utf8_decode($this->cellsTables['agregado']),1,'C');

			$posicion_x = ($posicion_x + $this->cellsTables['tam_agregadoAncho']);
			//VOLUMEN
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_volumenAncho'],$this->cellsTables['tam_volumenAlto'],utf8_decode($this->cellsTables['volumen']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_volumenAncho'];

			//Concreto
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_concretoAncho'],$this->cellsTables['tam_concretoAlto'],utf8_decode($this->cellsTables['concreto']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_concretoAncho'];

			//Unidad
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->cell($this->cellsTables['tam_unidadAlto'],$this->cellsTables['tam_unidadAncho'],utf8_decode($this->cellsTables['unidad']),1,0,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_unidadAlto'];

			//Hora
			$this->multicell($this->cellsTables['tam_horaAncho'],$this->cellsTables['tam_horaAlto'],utf8_decode($this->cellsTables['hora']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_horaAncho'];

			//Muestreo
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_muestreoAncho'],$this->cellsTables['tam_muestreoAlto'],utf8_decode($this->cellsTables['muestreo']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_muestreoAncho'];

			//Recolecicon
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$this->multicell($this->cellsTables['tam_recoleccionAncho'], $this->cellsTables['tam_recoleccionAlto'],utf8_decode( $this->cellsTables['recoleccion']),1,'C');

			$posicion_x = $posicion_x + $this->cellsTables['tam_recoleccionAncho'];

			$this->SetFont('Arial','B',$this->cellsTables['tam_font_Cells']);

			//Localizacion
			$this->SetY($posicion_y); 
			$this->SetX($posicion_x);
			$posicion_x = $this->GetX();
			$this->cell($this->cellsTables['tam_elementoAncho'],$this->cellsTables['tam_localizacionAlto'],utf8_decode($this->cellsTables['localizacion']),1,2,'C');
			$posicion_y = $this->GetY();
						
			$this->SetX(10); //Definimos donde empieza los 

			//Hacemos un "switch" para ver de que tipo de CCH devemos imprimi
			$grupos = 4;
			$bandC = 0;
			$bandCi = 0;
			$bandV = 0;
			switch ($infoFormato['tipo_especimen']) {
				case 'CILINDRO':
					$bandCi = 1;
					break;
				case 'CUBO':
					$bandC = 1;
					break;
				case 'VIGAS':
					$bandV = 1;
					$grupos = 3;
					break;
			}

			$tam_localizacion = array_pop($this->arrayCampos);

			//Extraemos la informacion del array para saber cuantos grupos son y cuantos registros contiene cada grupo
			
			$num_grupo = 1;
			$arrayGrupo1 = array();
			$arrayGrupo2 = array();
			$arrayGrupo3 = array();
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
			
			if(count($arrayGrupo1)!=0){
				$this->putRowsCCH($arrayGrupo1,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$endDown_table = $this->GetY();


				//Nos posicionamos abajo de la localizacion
				$this->SetXY($posicion_x,$posicion_y);


				$resultado = $this->printInfoObraAndLocObra($this->cellsTables['tam_font_CellsRows'],$tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$arrayLoc[1],3);

				if($resultado['error'] == 0){
					$this->SetFont('Arial','',$resultado['sizeFont']);
				}else{
					$this->SetFont('Arial','B',$resultado['tam_font_CellsRows']);
					$arrayLoc[1] = $resultado['estatus'];
				}


				$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],utf8_decode($arrayLoc[1]),'R,T');

				$this->SetX($posicion_x);
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
					}
				}
				$this->Line($this->GetX(), $this->GetY(),$this->GetX()+$tam_localizacion,$this->GetY());//Linea que completa la ultima celda
				$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
				$this->ln(0);
			}else{
				//Cancelacion
				$posicion_xLine = $this->GetX();
				$posicion_yLine = $this->GetY();

				$this->putRowsWithoutInfo($grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$endDown_table = $this->GetY();


				//Nos posicionamos abajo de la localizacion
				$this->SetXY($posicion_x,$posicion_y);

				$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R,T');
				$this->SetX($posicion_x);
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
					}
				}
				$this->Line($this->GetX(), $this->GetY(),$this->GetX()+$tam_localizacion,$this->GetY());//Linea que completa la ultima celda

				$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
				$this->Line($posicion_xLine, $posicion_yLine, $this->GetX()+$tam_localizacion, $this->GetY());//Linea que cancela
				$this->ln(0);
				
			}

			if(count($arrayGrupo2)!=0){
				$this->putRowsCCH($arrayGrupo2,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$endDown_table = $this->GetY();


				//Nos posicionamos abajo de la localizacion
				$this->SetXY($posicion_x,$posicion_y);

				$resultado = $this->printInfoObraAndLocObra($this->cellsTables['tam_font_CellsRows'],$tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$arrayLoc[2],3);

				if($resultado['error'] == 0){
					$this->SetFont('Arial','',$resultado['sizeFont']);
				}else{
					$this->SetFont('Arial','B',$resultado['tam_font_CellsRows']);
					$arrayLoc[2] = $resultado['estatus'];
				}

				$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],utf8_decode($arrayLoc[2]),'R,T');
				$this->SetX($posicion_x);
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
					}
				}
				$this->Line($this->GetX(), $this->GetY(),$this->GetX()+$tam_localizacion,$this->GetY());//Linea que completa la ultima celda
				$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
				$this->ln(0);
			}else{
				//Cancelacion
				$posicion_xLine = $this->GetX();
				$posicion_yLine = $this->GetY();

				$this->putRowsWithoutInfo($grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$endDown_table = $this->GetY();


				//Nos posicionamos abajo de la localizacion
				$this->SetXY($posicion_x,$posicion_y);

				$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R,T');
				$this->SetX($posicion_x);
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
					}
				}
				$this->Line($this->GetX(), $this->GetY(),$this->GetX()+$tam_localizacion,$this->GetY());//Linea que completa la ultima celda

				$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
				$this->Line($posicion_xLine, $posicion_yLine, $this->GetX()+$tam_localizacion, $this->GetY());//Linea que cancela
				$this->ln(0);
				
			}

			if(count($arrayGrupo3)!=0 && $grupos == 3){
				$this->putRowsCCH($arrayGrupo3,$grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$endDown_table = $this->GetY();


				//Nos posicionamos abajo de la localizacion
				$this->SetXY($posicion_x,$posicion_y);


				$resultado = $this->printInfoObraAndLocObra($this->cellsTables['tam_font_CellsRows'],$tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$arrayLoc[3],3);

				if($resultado['error'] == 0){
					$this->SetFont('Arial','',$resultado['sizeFont']);
				}else{
					$this->SetFont('Arial','B',$resultado['tam_font_CellsRows']);
					$arrayLoc[3] = $resultado['estatus'];
				}

				$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],utf8_decode($arrayLoc[3]),'R,T');
				$this->SetX($posicion_x);
				if($this->GetY() < $endDown_table){
					$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
					for ($i=0; $i < $num_iteraciones; $i++) { 
						$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
					}
				}
				$this->Line($this->GetX(), $this->GetY(),$this->GetX()+$tam_localizacion,$this->GetY());//Linea que completa la ultima celda
				$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
				$this->ln(0);
			}else{
				if($grupos == 3){
					//Cancelacion
					$posicion_xLine = $this->GetX();
					$posicion_yLine = $this->GetY();

					$this->putRowsWithoutInfo($grupos,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
					$endDown_table = $this->GetY();


					//Nos posicionamos abajo de la localizacion
					$this->SetXY($posicion_x,$posicion_y);

					$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R,T');
					$this->SetX($posicion_x);
					if($this->GetY() < $endDown_table){
						$num_iteraciones = (($endDown_table - $this->GetY()) / ($this->cellsTables['tam_cellsTablesAlto']));
						for ($i=0; $i < $num_iteraciones; $i++) { 
							$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'','R',2);
						}
					}
					$this->Line($this->GetX(), $this->GetY(),$this->GetX()+$tam_localizacion,$this->GetY());//Linea que completa la ultima celda

					$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
					$this->Line($posicion_xLine, $posicion_yLine, $this->GetX()+$tam_localizacion, $this->GetY());//Linea que cancela
					$this->ln(0);
				}
				
			}

			if($grupos == 4){
				$this->putRowsWithoutInfo(1,$this->cellsTables['tam_font_CellsRows'],$this->arrayCampos,$this->cellsTables['tam_cellsTablesAlto']);
				$this->SetXY($posicion_x,$posicion_y);
				$this->cell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],'',1,1);
			}
			
			$this->Ln(5);
		}

		function putDetails($infoFormato,$infoU){
			//Hacemos un "switch" para ver de que tipo de CCH devemos imprimi
			$grupos = 4;
			$bandC = 0;
			$bandCi = 0;
			$bandV = 0;
			switch ($infoFormato['tipo_especimen']) {
				case 'CILINDRO':
					$bandCi = 1;
					break;
				case 'CUBO':
					$bandC = 1;
					break;
				case 'VIGAS':
					$bandV = 1;
					$grupos = 3;
					break;
			}
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);

			//Observaciones
			
			$this->cell(0,$this->cellsDetails['tam_observacionesAlto'],$this->cellsDetails['observaciones'],'L,T,R',2);
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_details']);
			//Restamos uno porque el no centrar el texto hace que se salga la cena

			$posicion_x = $this->GetX();
			//Lo dejamos en 0 para que ocupe todo el espacio de la hoja

			$this->cellsDetails['tam_observacionesAltoTxt'] = $this->cellsDetails['tam_observacionesAltoTxt']/2;


			$resultado = $this->printInfoObraAndLocObra($this->cellsDetails['tam_font_details'],258.4,$this->cellsDetails['tam_observacionesAltoTxt'],$infoFormato['observaciones'],2);

			$this->SetFont('Arial','',$resultado['sizeFont']);
			$infoFormato['observaciones'] = $resultado['new_string'];

			if($resultado['error'] == 100){
				$this->error = $resultado;
			}

			if(array_key_exists('Total de renglones que serian', $resultado)){
				if($resultado['Total de renglones que serian'] == 1){
					$this->cellsDetails['tam_observacionesAltoTxt'] = $this->cellsDetails['tam_observacionesAltoTxt']*2;
				}
			}

			$this->multicell(0,$this->cellsDetails['tam_observacionesAltoTxt'],utf8_encode($infoFormato['observaciones']),'L,B,R');
			$tam_final = $this->GetX() - $posicion_x;


			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);

			//Metodos
			$this->cell($this->cellsDetails['tam_metodosAncho'],$this->cellsDetails['tam_metodosAlto'],$this->cellsDetails['metodos'],1,2,'C');

			$this->cell(0,2,'',1,2,'C');


			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			//Instrumentos
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_inventario']);
			$this->multicell($this->cellsDetails['tam_instrumentos'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['instrumentos'],1,'C');


			$this->SetXY(($posicion_x + $this->cellsDetails['tam_instrumentos']),$posicion_y);

			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();

			//Cono
			$this->cell($this->cellsDetails['tam_cono'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['cono'],1,2,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_inventario']);
			$this->cell($this->cellsDetails['tam_cono'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($infoFormato['CONO']),1,2,'C');

			$this->SetXY(($posicion_x + $this->cellsDetails['tam_cono']),$posicion_y);

			
			//Varilla
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_inventario']);
			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();
			$this->cell($this->cellsDetails['tam_varilla'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['varilla'],1,2,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_inventario']);
			$this->cell($this->cellsDetails['tam_varilla'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($infoFormato['VARILLA']),1,2,'C');


			$this->SetXY(($posicion_x + $this->cellsDetails['tam_varilla']),$posicion_y);

			//Felxometro
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_inventario']);
			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();

			$this->cell($this->cellsDetails['tam_flexometro'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['flexometro'],1,2,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_inventario']);
			$this->cell($this->cellsDetails['tam_flexometro'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($infoFormato['FLEXOMETRO']),1,2,'C');

			$this->SetXY(($posicion_x + $this->cellsDetails['tam_flexometro']),$posicion_y);
			
			//Termometro
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_inventario']);
			$this->cell($this->cellsDetails['tam_termo'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['termo']),1,2,'C');

			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsDetails['tam_font_inventario']);
			$this->cell($this->cellsDetails['tam_termo'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($infoFormato['TERMOMETRO']),1,2,'C');

			

			$this->SetXY(($posicion_x + $this->cellsDetails['tam_termo'] + 12),$posicion_y);

			//Cilindros
			$this->cell($this->cellsDetails['tam_cili'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['cili']),0,0,'C');

			//Caja de texto
			$this->cell($this->cellsDetails['tam_selectCell'],$this->cellsDetails['tam_inventarioAlto'],'',1,0,'C',$bandCi);
			$posicion_x = $this->GetX();


			//Cubos
			$this->cell($this->cellsDetails['tam_cubos'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['cubos']),0,0,'C');

			//Caja de texto
			$this->cell($this->cellsDetails['tam_selectCell'],$this->cellsDetails['tam_inventarioAlto'],'',1,0,'C',$bandC);

			//Vigas
			$this->cell($this->cellsDetails['tam_vigas'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['vigas']),0,0,'C');
			//Caja de texto
			$this->cell($this->cellsDetails['tam_selectCell'],$this->cellsDetails['tam_inventarioAlto'],'',1,0,'C',$bandV);
			

			$tam_image = 15;

			$this->Ln(7);	
			$this->SetX((279.4-$this->cellsDetails['tamCelda_firmaAncho'])/2);


			$posicion_x = $this->GetX(); 
			$posicion_y = $this->GetY();
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			
			

			if($infoU['firmaRealizo'] != "null"){
				$this->cell($this->cellsDetails['tamCelda_firmaAncho'],$this->cellsDetails['tamCelda_firmaAlto'],$infoU['nombreRealizo'],'B',2,'C');	
				$this->Image($infoU['firmaRealizo'],(($posicion_x+($this->cellsDetails['tamCelda_firmaAncho'])/2)-($tam_image/2)),($posicion_y + (($this->cellsDetails['tamCelda_firmaAlto'])/2))-($tam_image/2),$tam_image,$tam_image);
			}else{
				$this->cell($this->cellsDetails['tamCelda_firmaAncho'],$this->cellsDetails['tamCelda_firmaAlto'],utf8_decode($infoU['nombreRealizo']),0,2,'C');	
				$this->cell($this->cellsDetails['tamCelda_firmaAncho'],$this->cellsDetails['tam_laboratoristaAlto'],'No hay firma.','B',2,'C');	
			}
			$this->cell($this->cellsDetails['tam_laboratoristaAncho'],$this->cellsDetails['tam_laboratoristaAlto'],$this->cellsDetails['laboratorista'],0,0,'C');
			
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
		function CreateNew($infoFormato,$regisFormato,$infoU,$target_dir){
			$pdf  = new CCH('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->generateCellsInfo();
			$pdf->putInfo($infoFormato);
			$pdf->generateCellsCampos();	
			$pdf->putTables($infoFormato,$regisFormato);
			$pdf->generateCellsDetails();
			$pdf->putDetails($infoFormato,$infoU);
			//$pdf->Output('F',$target_dir);
			$pdf->Output();
		}
	
		
	}
?>