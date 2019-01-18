<?php 

	include_once("./../../FPDF/MyPDF.php");

	//Formato de campo de cilindros
	class EnsayoCilindroPDF extends MyPDF{

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

		public $error = 0;

		//Funcion que coloca una vista previa de la información

		function getArrayCampo(){
			return $this->array_campo;
		}

		function generateArrayCampo(){
			$tam_font_Cells = 5;	//Equivalente a $tam_font_head
			$tam_font_CellsRows = 5;	//Equivalente al cambio de fuente cuando se imprime la informacion de cada row
			$tam_cellsTablesAlto = 	$tam_font_Cells - 2;


			$tam_font_head = 5;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave


			$fechaColado = 'FECHA DE COLADO';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+3;
			$this->Cell($tam_fechaColado,$tam_font_head+3,$fechaColado,1,0,'C');

			$infoNumero = 'INFORME NUMERO';
			$tam_infoNumero = $this->GetStringWidth($infoNumero)+3;
			$this->Cell($tam_infoNumero,$tam_font_head+3,$infoNumero,1,0,'C');

			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave)+24;
			$this->Cell($tam_clave,$tam_font_head+3,$clave,1,0,'C');

			$peso = 'PESO EN kg';
			$tam_pesoAncho = $this->GetStringWidth($peso)+2;
			$this->Cell($tam_pesoAncho,$tam_font_head+3,$peso,1,0,'C');

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$edad = 'EDAD DE';
			$tam_edad = $this->GetStringWidth($edad)+3;
			$this->Cell($tam_edad,($tam_font_head+3)/3,$edad,'L,T,R',2,'C');
			$this->cell($tam_edad,($tam_font_head+3)/3,utf8_decode('ENSAYE'),'L,R',2,'C');
			$this->cell($tam_edad,($tam_font_head+3)/3,utf8_decode('EN DIÁS'),'L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_edad,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$diametro = 'DIAMETROS (cm)';
			$tam_diametro = $this->GetStringWidth($diametro)+5;
			$this->Cell($tam_diametro,($tam_font_head+3)/2,$diametro,'L,T,R',2,'C');
			$tam_d1 = $tam_d2 = $tam_diametro/2;
			$this->cell($tam_d1,($tam_font_head+3)/2,'D1',1,0,'C');
			$this->cell($tam_d2,($tam_font_head+3)/2,'D2',1,0,'C');

			$this->SetXY($posicion_x + $tam_diametro,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$altura = 'ALTURAS (cm)';
			$tam_altura = $this->GetStringWidth($altura)+5;
			$this->Cell($tam_altura,($tam_font_head+3)/2,$altura,'L,T,R',2,'C');
			$tam_h1 = $tam_h2 = $tam_altura/2;
			$this->cell($tam_h1,($tam_font_head+3)/2,'H1',1,0,'C');
			$this->cell($tam_h2,($tam_font_head+3)/2,'H2',1,0,'C');

			$this->SetXY($posicion_x + $tam_altura,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$carga = 'CARGA';
			$tam_kgAncho = $this->GetStringWidth($carga)+3;
			$this->Cell($tam_kgAncho,($tam_font_head+3)/2,$carga,'L,T,R',2,'C');
			$this->cell($tam_kgAncho,($tam_font_head+3)/2,'kg','L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_kgAncho,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$area = 'AREA';
			$tam_area = $this->GetStringWidth($area)+6;
			$this->Cell($tam_area,($tam_font_head+3)/2,$area,'L,T,R',2,'C');
			$this->cell($tam_area,($tam_font_head+3)/2,utf8_decode('cm²'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_area,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$resis = 'COMPRESIÓN kg/cm²';
			$tam_resis = $this->GetStringWidth($resis)+2;
			$posicion_x = $this->GetX();
			$this->Cell($tam_resis,($tam_font_head+3)/2,'RESISTENCIA A','L,T,R',2,'C');
			$this->cell($tam_resis,($tam_font_head+3)/2,utf8_decode($resis),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_resis,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();


			$velocidad = 'kg/cm² por minuto';
			$tam_velocidad = $this->GetStringWidth($velocidad)+2;
			$posicion_x = $this->GetX();
			$this->Cell($tam_velocidad,($tam_font_head+3)/2,utf8_decode('Vel. Aplicación'),'L,T,R',2,'C');
			$this->cell($tam_velocidad,($tam_font_head+3)/2,utf8_decode('kg/cm² por minuto'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_velocidad,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$falla = 'FALLA N°';
			
			$posicion_x = $this->GetX();
			$this->cell(0,($tam_font_head+3),utf8_decode($falla),1,2,'C');
			$tam_fallaAncho = 195.9 - (
											$tam_fechaColado +
											$tam_infoNumero +
											$tam_clave +
											$tam_pesoAncho +
											$tam_edad +
											$tam_diametro +
											$tam_altura +
											$tam_kgAncho +
											$tam_area +
											$tam_resis +
											$tam_velocidad 
										);
		

			$this->Ln(0);

			


			
			$tam_font_observaciones = 6.5;
			$tam_observacionAnchoTxt = 10;

			$tam_font_inventario = 6.5;

			$this->SetFont('Arial','B',$tam_font_inventario);

			//La herramienta
			$termo = 'Termómetro';
			$tam_termo = $this->GetStringWidth($termo)+10;

			$bascula = 'BASCULA';
			$tam_bascula = $tam_termo;

			$flexo = 'REGLA VERNIER O FLEXOMETRO';
			$tam_flexo = $tam_termo;

			$prensa = 'PRENSA';
			$tam_prensa = $tam_termo;


			$this->array_campo = array(
										'tam_font_Cells' => $tam_font_Cells,
										'tam_font_CellsRows' => $tam_font_CellsRows,
										'tam_cellsTablesAlto' => $tam_cellsTablesAlto,

										'tam_fechaColado' => $tam_fechaColado,
										'tam_infoNumero' => $tam_infoNumero,
										'tam_clave' => $tam_clave,
										'tam_pesoAncho' => $tam_pesoAncho,
										'tam_edad' => $tam_edad,
										'tam_d1' => $tam_d1,
										'tam_d2' => $tam_d2,
										'tam_h1' => $tam_h1,
										'tam_h2' => $tam_h2,
										'tam_kgAncho' => $tam_kgAncho,
										'tam_area' => $tam_area,
										'tam_resis' => $tam_resis,
										'tam_velocidad' => $tam_velocidad,
										'tam_fallaAncho' => $tam_fallaAncho,
										'tam_observacionAnchoTxt' => $tam_observacionAnchoTxt,
										'tam_bascula' => $tam_bascula,
										'tam_flexo' => $tam_flexo,
										'tam_prensa' => $tam_prensa,
										'tam_font_inventario' => $tam_font_inventario,
										'tam_font_observaciones' => $tam_font_observaciones
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

			$posicion_x = $this->GetX();

			//Definimos el las propiedades de la primera linea del titulo
			$tam_font_titulo = 9;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo_linea1 = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A. DE C.V.';
			$tam_cell = $this->GetStringWidth($titulo_linea1);
			$this->SetX((216-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo_linea1),0,'C');
			$this->Ln(8);

			//Titulo del informe
			$tam_font_tituloInforme = 7;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '"ENSAYO A COMPRESIÓN DE CILINDROS DE CONCRETO HIDRÁULICO"';
			$tam_titulo_informe = $this->GetStringWidth($titulo_informe)+3;
			$this->SetX((216-$tam_titulo_informe)/2);
			$this->Cell($titulo_informe,$tam_font_tituloInforme - 3,utf8_decode($titulo_informe),0,'C');

			$this->ln(4);
			
			//Put the watermark
   			$this->SetFont('Arial','B',75);
	    	$this->SetTextColor(192,192,192);
    		$this->RotatedText(55.5,172,'PREELIMINAR',45);
		}

		

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.
		

		function putInfo($infoFormato){

			$this->Ln(4);
			$tam_font_right = 8;	$this->SetFont('Arial','',$tam_font_right);
			$tam_line = 160;
			$fechaEnsaye = 'FECHA DE ENSAYE:';
			$tam_fechaEnsaye = $this->GetStringWidth($fechaEnsaye)+20;
			$this->SetX(-($tam_fechaEnsaye+35));
			$this->Cell($tam_fechaEnsaye,$tam_font_right - 3,$fechaEnsaye,1,0,'C');

			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,utf8_decode($infoFormato['fechaEnsayo']),1,0,'C');
			$this->Ln($tam_font_right - 2);

			//Salto de linea 
			$this->ln(4);

		}

		function putTablesWithOutJefeLab($infoFormato,$regisFormato,$infoU){

			$tam_font_head = 5;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave


			$fechaColado = 'FECHA DE COLADO';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+3;
			$this->Cell($tam_fechaColado,$tam_font_head+3,$fechaColado,1,0,'C');

			$infoNumero = 'INFORME NUMERO';
			$tam_infoNumero = $this->GetStringWidth($infoNumero)+3;
			$this->Cell($tam_infoNumero,$tam_font_head+3,$infoNumero,1,0,'C');

			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave)+24;
			$this->Cell($tam_clave,$tam_font_head+3,$clave,1,0,'C');

			$peso = 'PESO EN kg';
			$tam_pesoAncho = $this->GetStringWidth($peso)+2;
			$this->Cell($tam_pesoAncho,$tam_font_head+3,$peso,1,0,'C');

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$edad = 'EDAD DE';
			$tam_edad = $this->GetStringWidth($edad)+3;
			$this->Cell($tam_edad,($tam_font_head+3)/3,$edad,'L,T,R',2,'C');
			$this->cell($tam_edad,($tam_font_head+3)/3,utf8_decode('ENSAYE'),'L,R',2,'C');
			$this->cell($tam_edad,($tam_font_head+3)/3,utf8_decode('EN DIÁS'),'L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_edad,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$diametro = 'DIAMETROS (cm)';
			$tam_diametro = $this->GetStringWidth($diametro)+5;
			$this->Cell($tam_diametro,($tam_font_head+3)/2,$diametro,'L,T,R',2,'C');
			$tam_d1 = $tam_d2 = $tam_diametro/2;
			$this->cell($tam_d1,($tam_font_head+3)/2,'D1',1,0,'C');
			$this->cell($tam_d2,($tam_font_head+3)/2,'D2',1,0,'C');

			$this->SetXY($posicion_x + $tam_diametro,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$altura = 'ALTURAS (cm)';
			$tam_altura = $this->GetStringWidth($altura)+5;
			$this->Cell($tam_altura,($tam_font_head+3)/2,$altura,'L,T,R',2,'C');
			$tam_h1 = $tam_h2 = $tam_altura/2;
			$this->cell($tam_h1,($tam_font_head+3)/2,'H1',1,0,'C');
			$this->cell($tam_h2,($tam_font_head+3)/2,'H2',1,0,'C');

			$this->SetXY($posicion_x + $tam_altura,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$carga = 'CARGA';
			$tam_kgAncho = $this->GetStringWidth($carga)+3;
			$this->Cell($tam_kgAncho,($tam_font_head+3)/2,$carga,'L,T,R',2,'C');
			$this->cell($tam_kgAncho,($tam_font_head+3)/2,'kg','L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_kgAncho,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$area = 'AREA';
			$tam_area = $this->GetStringWidth($area)+6;
			$this->Cell($tam_area,($tam_font_head+3)/2,$area,'L,T,R',2,'C');
			$this->cell($tam_area,($tam_font_head+3)/2,utf8_decode('cm²'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_area,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$resis = 'COMPRESIÓN kg/cm²';
			$tam_resis = $this->GetStringWidth($resis)+2;
			$posicion_x = $this->GetX();
			$this->Cell($tam_resis,($tam_font_head+3)/2,'RESISTENCIA A','L,T,R',2,'C');
			$this->cell($tam_resis,($tam_font_head+3)/2,utf8_decode($resis),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_resis,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$velocidad = 'kg/cm² por minuto';
			$tam_velocidad = $this->GetStringWidth($velocidad)+2;
			$posicion_x = $this->GetX();
			$this->Cell($tam_velocidad,($tam_font_head+3)/2,utf8_decode('Vel. Aplicación'),'L,T,R',2,'C');
			$this->cell($tam_velocidad,($tam_font_head+3)/2,utf8_decode('kg/cm² por minuto'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_velocidad,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$falla = 'FALLA N°';
			
			$posicion_x = $this->GetX();
			$this->cell(0,($tam_font_head+3),utf8_decode($falla),1,2,'C');
			$tam_fallaAncho = 196 - (
										$tam_fechaColado +
										$tam_infoNumero +
										$tam_clave +
										$tam_pesoAncho +
										$tam_edad +
										$tam_d1 +
										$tam_d2 +
										$tam_h1 +
										$tam_h2 +
										$tam_kgAncho +
										$tam_area +
										$tam_resis +
										$tam_velocidad 
									);
		

			$this->Ln(0);
			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									$tam_fechaColado,
									$tam_infoNumero,
									$tam_clave,
									$tam_pesoAncho,
									$tam_edad,
									$tam_d1,
									$tam_d2,
									$tam_h1,
									$tam_h2,
									$tam_kgAncho,
									$tam_area,
									$tam_resis,
									$tam_velocidad,
									$tam_fallaAncho
							);

			$tam_font_head = 5;	
			$tam_cellsTablesAlto = 	$tam_font_head - 2;
			$this->SetFont('Arial','',$tam_font_head);
		
			$grupos = 49;
			$num_rows = 0;
			foreach ($regisFormato as $campo) {
				$j = 0;
				foreach ($campo as $registro) {


					$resultado = $this->printInfoObraAndLocObra($tam_font_head,$array_campo[$j],$tam_cellsTablesAlto,$registro,1);

					$this->SetFont('Arial','',$resultado['sizeFont']);
					$registro = $resultado['new_string'];

					if($resultado['error'] == 100){
							$this->error = $resultado['error'];
					}

					$this->cell($array_campo[$j],$tam_cellsTablesAlto,$registro,1,0,'C');
					$j++;
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<$grupos){
				//Guardamos la posicion de la X y Y para poner la linea que cancela
				$posicion_x = $this->GetX();
				$posicion_y = $this->GetY();


				for ($i=0; $i < ($grupos-$num_rows); $i++){
					for ($j=0; $j < sizeof($array_campo); $j++){ 
						$this->cell($array_campo[$j],$tam_cellsTablesAlto,'',1,0,'C');
					}
					$this->Ln();
				}

				//Linea que cancela
				$this->Line($posicion_x,$posicion_y,206,$this->GetY());
			}

			


			$tam_font_head = 6.5; $this->SetFont('Arial','B',$tam_font_head);
			$observaciones = 'OBSERVACIONES: ';
			$this->cell($this->GetStringWidth($observaciones.'LA VELOCIDAD DE APLICACIÓN DE CARGA ES DE'),10,utf8_decode($observaciones.'LA VELOCIDAD DE APLICACIÓN DE CARGA ES DE'),0);
			$this->SetFont('Arial','U',$tam_font_head);
			if($this->GetStringWidth($infoFormato['observaciones']) > 0){
				$this->cell($this->GetStringWidth($infoFormato['observaciones']),10,utf8_decode(' '.$infoFormato['observaciones'].' '),0);
			}else{
				$this->cell(5,10,' N/A ','B',0);
			}
			$this->SetFont('Arial','B',$tam_font_head);
			$this->cell(0,10,utf8_decode('  kg/cm²*min'),'R',1);
			
			$this->cell(20,$tam_font_head - 1,'',0,0);

			//Definimos el tamaño de las celdas que contiene la placa de las herramientas
			$termo = 'Termómetro';
			$tam_termo = $this->GetStringWidth($termo)+10;

			$inventario = 'Inventario de';
			$tam_inventario = $this->GetStringWidth($inventario)+10;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_inventario,(($tam_font_head)-1)/2,$inventario,'L,T,R',2,'C');
			$this->Cell($tam_inventario,(($tam_font_head)-1)/2,'instrumento','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_inventario,$posicion_y);
			$prensa = 'BASCULA';
			$tam_termo = $this->GetStringWidth($prensa)+12;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_termo,(($tam_font_head)-1)/2,$prensa,1,2,'C');
			$this->Cell($tam_termo,(($tam_font_head)-1)/2,utf8_decode($infoFormato['buscula_placas']),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_termo,$posicion_y);
			$flexo = 'REGLA VERNIER O FLEXOMETRO';
			$tam_flexo = $this->GetStringWidth($flexo)+12;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_flexo,(($tam_font_head)-1)/2,$flexo,1,2,'C');
			$this->Cell($tam_flexo,(($tam_font_head)-1)/2,utf8_decode($infoFormato['regVerFle_id_placas']),'L,B,R',0,'C');
		
			
			$prensa = 'PRENSA';
			$this->SetXY($posicion_x+$tam_flexo,$posicion_y);
			$tam_termo = $this->GetStringWidth($prensa)+12;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_termo,(($tam_font_head)-1)/2,$prensa,1,2,'C');
			$this->Cell($tam_termo,(($tam_font_head)-1)/2,utf8_decode($infoFormato['prensa_placas']),'L,B,R',1,'C');

			$this->SetY($posicion_y);
			$this->cell(0,(($tam_font_head)-1),'','B,R',2);
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$tam_abajo = 5;
			$this->multicell(40,$tam_abajo,'SIMBOLOGIA:'."\n".'                 D = DIAMETRO'."\n".'                 H = ALTURA',1);

			$tam_image = 15;
			$tam_font_footer = 7; $this->SetFont('Arial','B',$tam_font_footer);
			
			$tam_boxElaboro = (196-40)/2;	$tam_first = 7.5; $tam_second = 7.5;
			$this->SetXY($posicion_x+40,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'REALIZO','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+20,$this->gety() - 5,utf8_decode('____________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('Nombre y firma')/2),$this->gety() - 2,utf8_decode('Nombre y firma'));	

			if($infoU['nombreRealizo'] != "null"){
				/*
					-Restamos -2 a el ancho de la celda porque no contemple las negritas, entonces como esta vez imprimire negritas el espacio de la letra aumenta.
					-Ponemos la altura de la celda del mismo tamaño que el de la letra ya que no existe una celda como tal en la que va el texto, y el alto de la celda no repercute en el resultado.
				*/	

				$resultado = $this->printInfoObraAndLocObra($tam_font_footer,$tam_boxElaboro-3,$tam_font_footer,$infoU['nombreRealizo'],1);

				$this->SetFont('Arial','B',$resultado['sizeFont']);
				$infoU['nombreRealizo'] = $resultado['new_string'];

				if($resultado['error'] == 100){
					$this->error = $resultado['error'];
				}

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreRealizo'])/2),$this->gety() - 6,utf8_decode($infoU['nombreRealizo']));		
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('No hay nombre.')/2),$this->gety() - 6,utf8_decode("No hay nombre."));	
			}

			$this->SetFont('Arial','B',$tam_font_footer);

			if($infoU['firmaRealizo'] != "null"){
				
				$this->Image($infoU['firmaRealizo'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}
			else{

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'))	;	

			}

			$tam_boxElaboro = (196-40)/2;	$tam_first = 7.5; $tam_second = 7.5;
			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+20,$this->gety() - 5,utf8_decode('____________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('Nombre y firma')/2),$this->gety() - 2,utf8_decode('Nombre y firma'));	

			if($infoU['nombreLaboratorista'] != "null"){
				/*
					-Restamos -2 a el ancho de la celda porque no contemple las negritas, entonces como esta vez imprimire negritas el espacio de la letra aumenta.
					-Ponemos la altura de la celda del mismo tamaño que el de la letra ya que no existe una celda como tal en la que va el texto, y el alto de la celda no repercute en el resultado.
				*/	

				$resultado = $this->printInfoObraAndLocObra($tam_font_footer,$tam_boxElaboro-3,$tam_font_footer,$infoU['nombreLaboratorista'],1);

				$this->SetFont('Arial','B',$resultado['sizeFont']);
				$infoU['nombreLaboratorista'] = $resultado['new_string'];

				if($resultado['error'] == 100){
					$this->error = $resultado['error'];
				}

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth($infoU['nombreLaboratorista'])/2),$this->gety() - 6,utf8_decode($infoU['nombreLaboratorista']));		
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('No hay nombre.')/2),$this->gety() - 6,utf8_decode("No hay nombre."));	
			}

			$this->SetFont('Arial','B',$tam_font_footer);

			if($infoU['firmaLaboratorista'] != "null"){
				
				$this->Image($infoU['firmaLaboratorista'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}
			else{

				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8,utf8_decode('NO HAY FIRMA'))	;	

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
		    $clave = 'F1-05-LCC-03-3.1';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');
		}
		//Funcion que crea un nuevo formato

		function CreateNew($infoFormato,$regisFormato,$infoU,$target_dir){
			$pdf  = new EnsayoCilindroPDF('P','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putInfo($infoFormato);
			$pdf->putTablesWithOutJefeLab($infoFormato,$regisFormato,$infoU);
			//$pdf->Output();
			$pdf->Output('F',$target_dir);
			return $pdf->error;
		}



		
	}
	
	


?>