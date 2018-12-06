<?php 
	include_once("./../../FPDF/MyPDF.php");

	class EnsayoCuboPDF extends MyPDF{
		public $arrayCampos;



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
			$this->SetX((210-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo_linea1),0,'C');
			$this->Ln(8);

			//Titulo del informe
			$tam_font_tituloInforme = 7;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '"ENSAYO A COMPRESIÓN DE CUBOS DE CONCRETO HIDRÁULICO"';
			$tam_titulo_informe = $this->GetStringWidth($titulo_informe)+3;
			$this->SetX((210-$tam_titulo_informe)/2);
			$this->Cell($titulo_informe,$tam_font_tituloInforme - 3,utf8_decode($titulo_informe),0,'C');

			$this->ln(4);
			
			//Put the watermark
   			$this->SetFont('Arial','B',75);
	    	$this->SetTextColor(192,192,192);
    		$this->RotatedText(55.5,172,'PREELIMINAR',45);
		}

		function getArrayCampo(){
			return $this->array_campo;
		}

		function generateArrayCampo(){
			$tam_font_Cells = 5;	
			$tam_font_CellsRows = 5;
			$tam_cellsTablesAlto = 	$tam_font_Cells - 2;
		

			
			$this->SetFont('Arial','',$tam_font_Cells);

			$fechaColado = 'FECHA DE COLADO';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+3;
			$this->Cell($tam_fechaColado,$tam_font_Cells+3,$fechaColado,1,0,'C');

			$infoNumero = 'INFORME NUMERO';
			$tam_infoNumero = $this->GetStringWidth($infoNumero)+3;
			$this->Cell($tam_infoNumero,$tam_font_Cells+3,$infoNumero,1,0,'C');

			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave)+25;
			$this->Cell($tam_clave,$tam_font_Cells+3,$clave,1,0,'C');

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$edad = 'EDAD DE ENSAYE';
			$tam_edad = $this->GetStringWidth($edad)+3;
			$this->Cell($tam_edad,($tam_font_Cells+3)/2,$edad,'L,T,R',2,'C');
			$this->cell($tam_edad,($tam_font_Cells+3)/2,utf8_decode('EN DIÁS'),'L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_edad,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$lado = 'LADO (cm)';
			$tam_lado = $this->GetStringWidth($lado)+20;
			$this->Cell($tam_lado,($tam_font_Cells+3)/2,$lado,'L,T,R',2,'C');
			$tam_l1 = $tam_l2 = $tam_lado/2;
			$this->cell($tam_l1,($tam_font_Cells+3)/2,'L1',1,0,'C');
			$this->cell($tam_l2,($tam_font_Cells+3)/2,'L2',1,0,'C');

			$this->SetXY($posicion_x + $tam_lado,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$resisCompresion = 'RESISTENCIA A';
			$tam_resisCompresion = $this->GetStringWidth($resisCompresion)+3;
			$this->Cell($tam_resisCompresion,($tam_font_Cells+3)/2,$resisCompresion,'L,T,R',2,'C');
			$this->cell($tam_resisCompresion,($tam_font_Cells+3)/6,utf8_decode('COMPRESIÓN'),'L,R',2,'C');
			$this->cell($tam_resisCompresion,($tam_font_Cells+3)/3,'kg','L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_resisCompresion,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$area = 'AREA';
			$tam_area = $this->GetStringWidth($area)+10;
			$this->Cell($tam_area,($tam_font_Cells+3)/2,$area,'L,T,R',2,'C');
			$this->cell($tam_area,($tam_font_Cells+3)/2,utf8_decode('cm²'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_area,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$velocidad = 'kg/cm2 por minuto';
			$tam_velocidad = $this->GetStringWidth($velocidad)+2;
			$posicion_x = $this->GetX();
			$this->Cell($tam_velocidad,($tam_font_Cells+3)/2,utf8_decode('Vel. Aplicación'),'L,T,R',2,'C');
			$this->cell($tam_velocidad,($tam_font_Cells+3)/2,utf8_decode('kg/cm2 por minuto'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_velocidad,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$resis = 'COMPRESIÓN kg/cm²';
			$posicion_x = $this->GetX();
			
			$this->Cell(0,($tam_font_Cells+3)/2,'RESISTENCIA A','L,T,R',2,'C');
			$this->cell(0,($tam_font_Cells+3)/2,utf8_decode($resis),'L,B,R',2,'C');

			
			$tam_resis =  196 - (
											$tam_fechaColado +
											$tam_infoNumero +
											$tam_clave +
											$tam_edad +
											$tam_l1 +
											$tam_l2 +
											$tam_resisCompresion +
											$tam_area +
											$tam_velocidad
										);
			$this->lN(0);

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
		

			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									'tam_font_Cells' => $tam_font_Cells,
									'tam_font_CellsRows' => $tam_font_CellsRows,
									'tam_cellsTablesAlto' => $tam_cellsTablesAlto,
									'tam_fechaColado'	=> $tam_fechaColado,
									'tam_infoNumero'	=> $tam_infoNumero,
									'tam_clave'	=> $tam_clave,
									'tam_edad'	=> $tam_edad,
									'tam_lado1Ancho'	=> $tam_l1,
									'tam_lado2Ancho'	=> $tam_l2,
									'tam_kgAncho'	=> $tam_resisCompresion,
									'tam_area'	=> $tam_area,
									'tam_velocidad'	=> $tam_velocidad,
									'tam_resis'	=> $tam_resis,
									'tam_observacionAnchoTxt' => $tam_observacionAnchoTxt,
									'tam_bascula' => $tam_bascula,
									'tam_flexo' => $tam_flexo,
									'tam_prensa' => $tam_prensa,
									'tam_font_inventario' => $tam_font_inventario,
									'tam_font_observaciones' => $tam_font_observaciones
							);

			$this->array_campo = $array_campo;
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
			$this->Cell(0,$tam_font_right - 3,$infoFormato['fechaEnsayo'],1,0,'C');
			$this->Ln($tam_font_right - 2);

			//Salto de linea 
			$this->ln(4);
			
		}

		function putTables($infoFormato,$regisFormato,$infoU){

			$tam_font_head = 5;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave


			$fechaColado = 'FECHA DE COLADO';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+3;
			$this->Cell($tam_fechaColado,$tam_font_head+3,$fechaColado,1,0,'C');

			$infoNumero = 'INFORME NUMERO';
			$tam_infoNumero = $this->GetStringWidth($infoNumero)+3;
			$this->Cell($tam_infoNumero,$tam_font_head+3,$infoNumero,1,0,'C');

			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave)+25;
			$this->Cell($tam_clave,$tam_font_head+3,$clave,1,0,'C');

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$edad = 'EDAD DE ENSAYE';
			$tam_edad = $this->GetStringWidth($edad)+3;
			$this->Cell($tam_edad,($tam_font_head+3)/2,$edad,'L,T,R',2,'C');
			$this->cell($tam_edad,($tam_font_head+3)/2,utf8_decode('EN DIÁS'),'L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_edad,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$lado = 'LADO (cm)';
			$tam_lado = $this->GetStringWidth($lado)+20;
			$this->Cell($tam_lado,($tam_font_head+3)/2,$lado,'L,T,R',2,'C');
			$tam_l1 = $tam_l2 = $tam_lado/2;
			$this->cell($tam_l1,($tam_font_head+3)/2,'L1',1,0,'C');
			$this->cell($tam_l2,($tam_font_head+3)/2,'L2',1,0,'C');

			$this->SetXY($posicion_x + $tam_lado,$posicion_y);
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$resisCompresion = 'RESISTENCIA A';
			$tam_resisCompresion = $this->GetStringWidth($resisCompresion)+3;
			$this->Cell($tam_resisCompresion,($tam_font_head+3)/2,$resisCompresion,'L,T,R',2,'C');
			$this->cell($tam_resisCompresion,($tam_font_head+3)/6,utf8_decode('COMPRESIÓN'),'L,R',2,'C');
			$this->cell($tam_resisCompresion,($tam_font_head+3)/3,'kg','L,B,R',0,'C');

			$this->SetXY($posicion_x + $tam_resisCompresion,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();
			$area = 'AREA';
			$tam_area = $this->GetStringWidth($area)+10;
			$this->Cell($tam_area,($tam_font_head+3)/2,$area,'L,T,R',2,'C');
			$this->cell($tam_area,($tam_font_head+3)/2,utf8_decode('cm²'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_area,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();



			$velocidad = 'kg/cm2 por minuto';
			$tam_velocidad = $this->GetStringWidth($velocidad)+2;
			$posicion_x = $this->GetX();
			$this->Cell($tam_velocidad,($tam_font_head+3)/2,utf8_decode('Vel. Aplicación'),'L,T,R',2,'C');
			$this->cell($tam_velocidad,($tam_font_head+3)/2,utf8_decode('kg/cm2 por minuto'),'L,B,R',2,'C');

			$this->SetXY($posicion_x + $tam_velocidad,$posicion_y);

			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$resis = 'COMPRESIÓN kg/cm²';
			$posicion_x = $this->GetX();
			
			$this->Cell(0,($tam_font_head+3)/2,'RESISTENCIA A','L,T,R',2,'C');
			$this->cell(0,($tam_font_head+3)/2,utf8_decode($resis),'L,B,R',2,'C');

			
			$tam_resis = $this->GetX() - $posicion_x;
			$this->lN(0);
		

			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									$tam_fechaColado,
									$tam_infoNumero,
									$tam_clave,
									$tam_edad,
									$tam_l1,
									$tam_l2,
									$tam_resisCompresion,
									$tam_area,
									$tam_velocidad,
									$tam_resis
							);

			$tam_font_head = 5;
			$tam_cellsTablesAlto = 	$tam_font_head - 2;
			$this->SetFont('Arial','',$tam_font_head);

			$grupos = 45;
			$num_rows = 0;
			foreach ($regisFormato as $campo) {
				$j = 0;
				foreach ($campo as $registro) {
					$this->cell($array_campo[$j],$tam_cellsTablesAlto,$registro,1,0,'C');
					$j++;
				}
				$num_rows++;
				$this->Ln();
			}
			if($num_rows<$grupos){
				for ($i=0; $i < ($grupos-$num_rows); $i++){
					for ($j=0; $j < sizeof($array_campo); $j++){ 
						$this->cell($array_campo[$j],$tam_cellsTablesAlto,'',1,0,'C');
					}
					$this->Ln();
				}
			}


			$this->Ln(4);

			$tam_font_head = 6.5; $this->SetFont('Arial','B',$tam_font_head);
			$observaciones = 'OBSERVACIONES:';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->cell(0,$tam_font_head - 3,utf8_decode($observaciones),'L,T',2);
			$this->cell($this->GetStringWidth($observaciones)+2,$tam_font_head,'','L,B',0);

			$this->SetXY($posicion_x + $this->GetStringWidth($observaciones)+2,$posicion_y);
			$this->cell($this->GetStringWidth('LA VELOCIDAD DE APLICACIÓN DE CARGA ES DE'),10,utf8_decode('LA VELOCIDAD DE APLICACIÓN DE CARGA ES DE'),'B',0);
			$this->SetFont('Arial','U',$tam_font_head);
			if($this->GetStringWidth($infoFormato['observaciones']) > 0){
				$this->cell($this->GetStringWidth($infoFormato['observaciones']),10,utf8_decode(' '.$infoFormato['observaciones'].' '),'B',0);
			}else{
				$this->cell(5,10,' N/A ','B',0);
			}

			
			$this->SetFont('Arial','B',$tam_font_head);
			$this->cell(0,10,utf8_decode('   kg/cm²*min'),'B,R',1);

			$this->ln(2);


			//Definimos el tamaño de las celdas que contiene la placa de las herramientas
			$termo = 'Termómetro';
			$tam_termo = $this->GetStringWidth($termo)+10;

			$inventario = 'Inventario de';
			$tam_inventario = $this->GetStringWidth($inventario)+10;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_inventario,(($tam_font_head)-1)/2,$inventario,'L,T,R',2,'C');
			$this->Cell($tam_inventario,(($tam_font_head)-1)/2,'instrumento','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_inventario,$posicion_y);
			$bascula = 'BASCULA';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_termo,(($tam_font_head)-1)/2,$bascula,1,2,'C');
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
			
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();

			$this->ln(2);
			$posicion_y = $this->GetY();

			$tam_image = 15;
			$tam_font_footer = 7; $this->SetFont('Arial','b',$tam_font_footer);

			$tam_boxElaboro = (196-40)/2;	$tam_first = 7.5; $tam_second = 7.5;
			$this->SetXY($posicion_x+24.5,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'REALIZO','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+20,$this->gety() - 5,utf8_decode('____________________________	'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('Nombre y firma')/2),$this->gety() - 2,utf8_decode('Nombre y firma'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth(utf8_decode($infoU['nombreRealizo']))/2),$this->gety() - 7,utf8_decode($infoU['nombreRealizo']));	

			if($infoU['firmaRealizo'] != "null"){
				$this->Image($infoU['firmaRealizo'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8.5,utf8_decode('NO HAY FIRMA'))	;	
			}

			$tam_boxElaboro = (196-40)/2;	$tam_first = 7.5; $tam_second = 7.5;
			$this->SetXY($posicion_x+$tam_boxElaboro,$posicion_y);
			$this->cell($tam_boxElaboro,$tam_first,'Vo. Bo.','L,T,R',2,'C');
			$posicion_x = $this->GetX();
			$this->cell($tam_boxElaboro,$tam_second,'','L,B,R',2,'C');

			$this->TextWithDirection($posicion_x+20,$this->gety() - 5,utf8_decode('___________________________________________'));	
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('Nombre y firma')/2),$this->gety() - 2,utf8_decode('Nombre y firma'));
			$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth(utf8_decode($infoU['nombreLaboratorista']))/2),$this->gety() - 7,utf8_decode($infoU['nombreLaboratorista']));		

			//print_r($infoU);

			if($infoU['firmaLaboratorista'] != "null"){
				$this->Image($infoU['firmaLaboratorista'],(($posicion_x+($tam_boxElaboro)/2)-($tam_image/2)),($posicion_y + (($tam_first + $tam_second)/2))-($tam_image/2),$tam_image,$tam_image);
			}else{
				$this->TextWithDirection(($posicion_x + ($tam_boxElaboro /2))-($this->GetStringWidth('NO HAY FIRMA')/2),$this->gety() - 8.5,utf8_decode('NO HAY FIRMA'))	;	
			}
	
					
		}
		
		function Footer(){
			$this->SetY(-15);
		    $this->SetFont('Arial','',8);
		    $tam_noPagina = $this->GetStringWidth('Page '.$this->PageNo().'/{nb}');
		    $posicion_x = (216 - $tam_noPagina)/2;
		    $this->SetX($posicion_x);
		    $this->Cell($tam_noPagina,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

		    //Clave de validacion
		    $clave = 'PENDIENTE';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');
		}
		//Funcion que crea un nuevo formato

		function CreateNew($infoFormato,$regisFormato,$infoU,$target_dir){
			$pdf  = new EnsayoCuboPDF('P','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($infoFormato,$regisFormato,$infoU);
			$pdf->Output();
			//$pdf->Output('F',$target_dir);
		}


		
	}
	
	
?>