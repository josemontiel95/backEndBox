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



		function generateCellsInfo(){
			//Estilo y tamaño de la informacion de la IZQUIERDA

			/*
				Notas:
						-Hay un errro con los tamaños de las celdas, no repercute pero modificar cuando se pueda	
			*/
			$tam_font_left = 7;	
			$this->SetFont('Arial','B',$tam_font_left);
			$tam_cellsAlto =  $tam_font_left - 3;

			$obra 			= 'Nombre de la Obra:';
			$tam_obra 		= $this->GetStringWidth($obra)+2;

			$locObra		= 'Localización de la Obra:';
			$tam_locObra 	= $this->GetStringWidth($locObra)+2;

			$nomCli 		= 'Nombre del Cliente:';
			$tam_nomCli		= $this->GetStringWidth($nomCli)+2;

			$dirCliente 	= 'Dirección del Cliente:';
			$tam_dirCliente	= $this->GetStringWidth($nomCli)+2;

			//Estilo y tamaño de la informacion de la DERECHA

			$tam_font_right = 7.5;	
			$this->SetFont('Arial','B',$tam_font_right);
			$tam_cellsRight = $tam_font_right - 3;
			$informeNo 		= 'INFORME No.';
			$tam_informeNo 	= $this->GetStringWidth($informeNo)+6;

			//Definimos el tamaño de la linea de toda la información
			$tam_nomObraText = $tam_localizacionText = $tam_razonText = $tam_dirClienteText = 160;
			$tam_informeText = 30;

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
											'tam_informeNo'			=> $tam_locObra,

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
			$volumen = $volumen."\n".'(mm)';
			
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
			$tam_localizacionAncho = 259.3975 - (
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
											'tam_localizacionAncho'		=>	$tam_localizacionAncho,
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
											$tam_localizacionAncho									
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

			$this->SetFont('Arial','',$tam_font_inventario);

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

			$this->Image('http://lacocs.montielpalacios.com/SystemData/BackData/Assets/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
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
			$this->Cell(30,$tam_font_right - 3,utf8_decode($infoFormato['informeNo']),'B',0,'C');

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

			$result = $this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_nomObraText']);
			$this->Cell($this->cellsInfo['tam_nomObraText'],$this->cellsInfo['tam_cellsAlto'],$result['tam_stringCarac'],'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
	
			//Localizacion de la obra
			$this->Cell($this->cellsInfo['tam_locObra'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['locObra']),0);

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);

			$this->SetX(50);

			$result = $this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_localizacionText']);
			$this->Cell($this->cellsInfo['tam_localizacionText'],$this->cellsInfo['tam_cellsAlto'],$result['tam_stringCarac'],'B',0);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_right']); 

			//Numero del informe
			$this->SetX(-($this->cellsInfo['tam_informeNo']+40));
			$this->Cell($this->cellsInfo['tam_informeNo'],$this->cellsInfo['tam_cellsAlto'],$this->cellsInfo['informeNo'],0,0,'C');

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_right']);
			$result = $this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_informeText']); 
			$this->Cell($this->cellsInfo['tam_informeText'],$this->cellsInfo['tam_cellsAlto'],$result['tam_stringCarac'],'B',0,'C');

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
			//Nombre del cliente
			$this->Cell($this->cellsInfo['nomCli'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['nomCli']),0);

			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']); 
			$this->SetX(50);
			$result = $this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_razonText']); 
			$this->Cell($this->cellsInfo['tam_razonText'],$this->cellsInfo['tam_cellsAlto'],$result['tam_stringCarac'],'B',0);

			$this->Ln($this->cellsInfo['tam_font_left'] - 2);

			//Direccion del cliente
			$this->SetFont('Arial','B',$this->cellsInfo['tam_font_left']); 
			$this->Cell($this->cellsInfo['tam_dirCliente'],$this->cellsInfo['tam_cellsAlto'],utf8_decode($this->cellsInfo['dirCliente']),0);
			//Caja de texto
			$this->SetFont('Arial','',$this->cellsInfo['tam_font_left']);
			$this->SetX(50);
			$result = $this->getMaxString($this->cellsInfo['tam_font_left'],$this->cellsInfo['tam_dirClienteText']);  
			$this->Cell($this->cellsInfo['tam_dirClienteText'],$this->cellsInfo['tam_cellsAlto'],$result['tam_stringCarac'],'B',0);

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln(10);

			//Titulo del CCH
			$tam_font_tituloCCH = 12; //Definimos el tamaño de la fuente
			$this->SetFont('Arial','B',$tam_font_tituloCCH);
			$titulo_CHH = 'CONTROL DE CONCRETO HIDRÁULICO';
			$tam_cell = $this->GetStringWidth($titulo_CHH);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_tituloCCH - 3,utf8_decode($titulo_CHH),0,2,'C');
			$this->Ln(2);

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
			$this->cell($this->cellsTables['tam_localizacionAncho'],$this->cellsTables['tam_localizacionAlto'],utf8_decode($this->cellsTables['localizacion']),1,2,'C');
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

			$resultado = $this->getMaxString($this->cellsTables['tam_font_CellsRows'],$tam_localizacion*($grupos - 0.1));

			$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$resultado['tam_stringCarac'],'R,T');
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

			$resultado = $this->getMaxString($this->cellsTables['tam_font_CellsRows'],$tam_localizacion*($grupos - 0.1));

			$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$resultado['tam_stringCarac'],'R,T');
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

			$resultado = $this->getMaxString($this->cellsTables['tam_font_CellsRows'],$tam_localizacion*($grupos - 0.1));

			$this->multicell($tam_localizacion,$this->cellsTables['tam_cellsTablesAlto'],$resultado['tam_stringCarac'],'R,T');
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



			$this->Ln(10);//Separacion con la parte de los detalles
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

			$resultado = $this->getMaxString($this->cellsDetails['tam_font_details'],$this->cellsDetails['tam_observacionAnchoTxt']);

			$posicion_x = $this->GetX();
			//Lo dejamos en 0 para que ocupe todo el espacio de la hoja
			$this->cell(0,$this->cellsDetails['tam_observacionesAltoTxt'],$resultado['tam_stringCarac'],'L,B,R',0);
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

			$resultado = $this->getMaxString($this->cellsDetails['tam_font_inventario'],$this->cellsDetails['tam_cono']);

			//Caja de texto
			$this->cell($this->cellsDetails['tam_cono'],$this->cellsDetails['tam_inventarioAlto'],$resultado['tam_stringCarac'],1,2,'C');

			$this->SetXY(($posicion_x + $this->cellsDetails['tam_cono']),$posicion_y);

			
			//Varilla
			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();
			$this->cell($this->cellsDetails['tam_varilla'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['varilla'],1,2,'C');

			$resultado = $this->getMaxString($this->cellsDetails['tam_font_inventario'],$this->cellsDetails['tam_varilla']);

			//Caja de texto
			$this->cell($this->cellsDetails['tam_varilla'],$this->cellsDetails['tam_inventarioAlto'],$resultado['tam_stringCarac'],1,2,'C');


			$this->SetXY(($posicion_x + $this->cellsDetails['tam_varilla']),$posicion_y);

			//Felxometro
			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();

			$this->cell($this->cellsDetails['tam_flexometro'],$this->cellsDetails['tam_inventarioAlto'],$this->cellsDetails['flexometro'],1,2,'C');

			$resultado = $this->getMaxString($this->cellsDetails['tam_font_inventario'],$this->cellsDetails['tam_flexometro']);

			//Caja de texto
			$this->cell($this->cellsDetails['tam_flexometro'],$this->cellsDetails['tam_inventarioAlto'],$resultado['tam_stringCarac'],1,2,'C');

			$this->SetXY(($posicion_x + $this->cellsDetails['tam_flexometro']),$posicion_y);
			
			//Termometro
			$this->cell($this->cellsDetails['tam_termo'],$this->cellsDetails['tam_inventarioAlto'],utf8_decode($this->cellsDetails['termo']),1,2,'C');

			$posicion_y = $this->GetY(); 
			$posicion_x = $this->GetX();

			$resultado = $this->getMaxString($this->cellsDetails['tam_font_inventario'],$this->cellsDetails['tam_termo']);
			//Caja de texto
			$this->cell($this->cellsDetails['tam_termo'],$this->cellsDetails['tam_inventarioAlto'],$resultado['tam_stringCarac'],1,2,'C');

			

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

			$this->cell($this->cellsDetails['tamCelda_firmaAncho'],$this->cellsDetails['tamCelda_firmaAlto'],'','B',2);
			$this->SetFont('Arial','B',$this->cellsDetails['tam_font_details']);
			$this->cell($this->cellsDetails['tam_laboratoristaAncho'],$this->cellsDetails['tam_laboratoristaAlto'],$this->cellsDetails['laboratorista'],0,0,'C');

			$this->Image('https://upload.wikimedia.org/wikipedia/commons/a/a0/Firma_de_Morelos.png',(($posicion_x+($this->cellsDetails['tamCelda_firmaAncho'])/2)-($tam_image/2)),($posicion_y + (($this->cellsDetails['tamCelda_firmaAlto'])/2))-($tam_image/2),$tam_image,$tam_image);
		}

		//function putTables($infoFormato,$regisFormato) el chido
		function putTables($infoFormato,$regisFormato){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$tam_font_head = 7;	$this->SetFont('Arial','B',$tam_font_head);//Fuente para clave

			//Clave
			$clave = 'CLAVE DEL ESPECIMEN';
			$tam_clave = $this->GetStringWidth($clave) + 10; 
			$this->cell($tam_clave,1.5*(2*($tam_font_head) - 6),$clave,1,0,'C');

			//Fecha
			$fecha = 'FECHA';
			$tam_fecha = $this->GetStringWidth($fecha) + 7;
			$this->cell($tam_fecha,1.5*(2*($tam_font_head) - 6),$fecha,1,0,'C');
			//F´c
			$fprima = 'F ` C ';
			$tam_fprima = $this->GetStringWidth($fecha) + 2; $posicion_x = $this->GetX();
			$this->multicell($tam_fprima,0.5*(1.5*(2*($tam_font_head) - 6)),utf8_decode($fprima."\n".'kg/cm²'),1,'C');

			

			//Proyecto
			$proyecto = 'PROYECTO';
			$tam_pro = $this->GetStringWidth($proyecto) + 2;
			$this->SetY($posicion_y + 0.5*(1.5*(2*($tam_font_head) - 6))); $this->SetX($posicion_x + $tam_fprima);	$posicion_x = $this->GetX();
			$this->cell($tam_pro,0.5*(1.5*(2*($tam_font_head) - 6)),$proyecto,1,0,'C');

			//Obra
			$obra = 'OBRA';
			$tam_obra = $this->GetStringWidth($obra) + 2;
			$this->SetY($posicion_y + 0.5*(1.5*(2*($tam_font_head) - 6)));	$this->SetX($posicion_x + $tam_pro);
			$this->cell($tam_obra,0.5*(1.5*(2*($tam_font_head) - 6)),$obra,1,2,'C');


			//Revenimiento
			$rev = 'REVENIMENTO (cm)';
			$this->SetY($posicion_y); $this->SetX($posicion_x); $posicion_x = $this->GetX();
			$this->cell($tam_pro + $tam_obra,0.5*(1.5*(2*($tam_font_head) - 6)),$rev,1,0,'C');

			$tam_font_head = $tam_font_head-2;	
			$this->SetFont('Arial','B',$tam_font_head);

			//Guardamos la posicion de la x
			$posicion_x = $this->GetX();

			//Agregado
			$agregado = 'AGREGADO';
			$tam_agregado = $this->GetStringWidth($agregado) + 3;
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
			$tam_unidad = $this->GetStringWidth($unidad) + 5;
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

			$tam_font_head = 7;	$this->SetFont('Arial','B',$tam_font_head);//Fuente para clave

			$localizacion = 'LOCALIZACIÓN';
			$tam_localizacion = 269- $posicion_x;
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

			$this->Ln(2);
			$tam_observaciones = 10;
			$tam_font_footer = 7;	$this->SetFont('Arial','B',$tam_font_footer);
			$observaciones = 'OBSERVACIONES:';

			//Observaciones
			
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
			//$pdf->Output('F',$target_dir);
			$pdf->Output();
		}
	
		
	}
?>