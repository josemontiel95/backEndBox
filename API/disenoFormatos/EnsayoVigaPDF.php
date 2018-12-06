<?php 
	include_once("./../../FPDF/MyPDF.php");

	//Formato de campo de cilindros
	class EnsayoVigaPDF extends MyPDF{

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


		
		//Funcion que coloca una vista previa de la información
		function generateCellsInfo(){
			//Asignamos el tamñao de la fuente
			$tam_font_left = 7;	
			$this->SetFont('Arial','',$tam_font_left);
			$tam_nomObraText;

			$nomCli = 'CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);

			//Caja de texto
			$this->SetX(50);
			$this->Cell(170,$tam_font_left - 3,'B',0);

			$this->SetX($this->GetX()+11.5);
			$regNo = 'Registro N°:';
			$this->Cell($this->GetStringWidth($regNo)+.5,$tam_font_left - 3,utf8_decode($regNo),0);
			//Caja de texto
			$this->Cell(0,$tam_font_left - 3,'DUMMY','B',0);

			//Caja de texto
			$this->SetX(50);
			$this->Cell(170,$tam_font_left - 3,'DUMMY','B',0);

			$this->Ln($tam_font_left - 2);


			//Cuadro con informacion
			$obra = 'OBRA:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(170,$tam_font_left - 3,'DUMMY','B',0);

			$this->SetX($this->GetX()+5);
			$tipoConcreto = 'Tipo de Concreto:';
			$this->Cell($this->GetStringWidth($tipoConcreto)+2,$tam_font_left - 3,utf8_decode($tipoConcreto),0);
			//Caja de texto
			$this->Cell(0,$tam_font_left - 3,'DUMMY','B',0);

			$this->Ln($tam_font_left - 2);

			$locObra = 'DIRECCIÓN DE LA OBRA:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);

			//Caja de texto
			$this->SetX(50);
			$this->Cell(170,$tam_font_left - 3,'DUMMY','B',0);

			$this->SetX($this->GetX()+6.5);
			$mrProyecto = 'MR de proyecto:';
			$this->Cell($this->GetStringWidth($mrProyecto)+2,$tam_font_left - 3,utf8_decode($mrProyecto),0);
			//Caja de texto
			$this->Cell(0,$tam_font_left - 3,'DUMMY','B',0);

			$this->Ln($tam_font_left - 2);

			
			//Direccion del cliente
			$eleColado = 'ELEMENTO COLADO:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($eleColado),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(170,$tam_font_left - 3,'DUMMY','B',0);

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($tam_font_left);

		}




		function Header()
		{
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de Lacocs
			$tam_lacocs = 15;
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de Lacocs
			$posicion_x = $this->GetX();


			$this->Image('./../../disenoFormatos/lacocs.jpg',$posicion_x,$this->GetY(),$tam_lacocs + 10,$tam_lacocs);

			//Información de la empresa
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A. DE C.V.';
			$tam_cell = $this->GetStringWidth($titulo);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo),0,'C');

			$this->Ln(
			);

			$tam_font_info = 6.5;
			//Fecha
			$this->SetFont('Arial','B',$tam_font_info);
			$direccion_lacocs = '35 NORTE No.3023, UNIDAD HABITACIONAL AQUILES SERDAN. TEL:. 01 222 2315836,8686973,8686974';
			$tam_direccion = $this->GetStringWidth($direccion_lacocs)+6;
			$this->SetX((279 - $tam_direccion)/2);
			$this->Cell($tam_direccion,$tam_font_info - 3,$direccion_lacocs,0,'C');
			$this->Ln(5);

			$tam_font_tituloInforme = 7.5;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '"REGISTRO DEL ENSAYO A FLEXIÓN DE VIGAS DE CONCRETO CON CARGA A LOS TERCIOS DEL CLARO"';
			$tam_tituloInforme = $this->GetStringWidth($titulo_informe)+3;
			$this->SetX((279 - $tam_tituloInforme)/2);
			$this->Cell($tam_tituloInforme,$tam_font_info - 3,utf8_decode($titulo_informe),0,'C');
			$this->Ln(4);

			$tam_font_tituloInforme = 7;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$tituloMetodos = 'METODOS EMPLEADOS: NMX-C-161-ONNCCE-2013,NMX-C-191-ONNCCE-2015';
			$tam_tituloMetodos = $this->GetStringWidth($tituloMetodos)+3;
			$this->SetX((279 - $tam_tituloMetodos)/2);
			$this->Cell($tam_tituloMetodos,$tam_font_info - 3,utf8_decode($tituloMetodos),0,'C');

			//---Divide espacios entre el titulo del formato y la información del formato
			$this->Ln($tam_font_tituloInforme - 2);

			//Put the watermark
   			$this->SetFont('Arial','B',50);
	    	$this->SetTextColor(192,192,192);
    		$this->RotatedText(100,130,'PREELIMINAR',45);
		}

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.

		function putInfo($infoFormato){
			$tam_font_left = 7;	$this->SetFont('Arial','',$tam_font_left);

			$nomCli = 'CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);

			//Caja de texto
			$this->SetX(50);
			$this->Cell(170,$tam_font_left - 3,utf8_decode($infoFormato['razonSocial']),'B',0);

			$this->SetX($this->GetX()+11.5);
			$regNo = 'Registro N°:';
			$this->Cell($this->GetStringWidth($regNo)+.5,$tam_font_left - 3,utf8_decode($regNo),0);
			//Caja de texto
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['informeNo']),'B',0);
		

			$this->Ln($tam_font_left - 2);


			//Guardamos las posicion de la y
			$posiciony = $this->GetY();

			//Cuadro con informacion
			$obra = 'OBRA:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,170,$tam_font_left - 3,$infoFormato['obra'],3);

			$this->multicell(170,$tam_font_left - 3,utf8_decode($infoFormato['obra']),'B','C');

			$posiciony_aux = $this->GetY();

			$this->SetY($posiciony);

			$this->SetX(225);
			$tipoConcreto = 'Tipo de Concreto:';
			$this->Cell($this->GetStringWidth($tipoConcreto)+2,$tam_font_left - 3,utf8_decode($tipoConcreto),0);
			//Caja de texto
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['tipoConcreto']),'B',0);

			$this->SetY($posiciony_aux + 1);

			$posiciony = $this->GetY();			

			$locObra = 'DIRECCIÓN DE LA OBRA:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);

			//Caja de texto
			$this->SetX(50);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,170,$tam_font_left - 3,$infoFormato['obraLocalizacion'],3);

			$this->multicell(170,$tam_font_left - 3,utf8_decode($infoFormato['obraLocalizacion']),'B','C');

			$posiciony_aux = $this->GetY();

			$this->SetY($posiciony);

			$this->SetX(226.5);

			$mrProyecto = 'MR de proyecto:';
			$this->Cell($this->GetStringWidth($mrProyecto)+2,$tam_font_left - 3,utf8_decode($mrProyecto),0);
			//Caja de texto
			$this->Cell(0,$tam_font_left - 3,utf8_decode($infoFormato['fprima']),'B',0);

			$this->Ln($tam_font_left - 2);

			
			//Direccion del cliente
			$eleColado = 'ELEMENTO COLADO:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($eleColado),0);
			//Caja de texto
			$this->SetX(50);

			$resultado = $this->printInfoObraAndLocObra($tam_font_left,170,$tam_font_left - 3,$infoFormato['eleColado'],3);


			$this->multicell(170,$tam_font_left - 3,utf8_decode($infoFormato['eleColado']),'B','C');

			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($tam_font_left);

		}

		function putTables($regisFormato){
			$tam_font_head = 5;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave


			$iden = 'Identificacion de la ';
			$tam_iden = $this->GetStringWidth($iden)+16;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_iden,($tam_font_head+5)/2,$iden,'L,T,R',2,'C');
			$this->Cell($tam_iden,($tam_font_head+5)/2,'muestra','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_iden,$posicion_y);
			$fechaColado = 'Fecha de';
			$tam_fechaColado = $this->GetStringWidth($fechaColado)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_fechaColado,($tam_font_head+5)/2,$fechaColado,'L,T,R',2,'C');
			$this->Cell($tam_fechaColado,($tam_font_head+5)/2,'Colado','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_fechaColado,$posicion_y);
			$fechaEnsayo = 'Fecha de';
			$tam_fechaEnsayo = $this->GetStringWidth($fechaEnsayo)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_fechaEnsayo,($tam_font_head+5)/2,$fechaEnsayo,'L,T,R',2,'C');
			$this->Cell($tam_fechaEnsayo,($tam_font_head+5)/2,'ensayo','L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_fechaColado,$posicion_y);
			$edad = 'Edad';
			$tam_edad = $this->GetStringWidth($edad)+2;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_edad,($tam_font_head+5)/2,$edad,'L,T,R',2,'C');
			$this->Cell($tam_edad,($tam_font_head+5)/2,utf8_decode('días'),'L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_edad,$posicion_y);
			$condiciones = 'Condiciones';
			$tam_condiciones = $this->GetStringWidth($condiciones)+2;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_condiciones,(($tam_font_head+5)/2)/3,$condiciones,'L,T,R',2,'C');
			$this->Cell($tam_condiciones,(($tam_font_head+5)/2)/3,utf8_decode('de curado y'),'L,R',2,'C');
			$this->Cell($tam_condiciones,(($tam_font_head+5)/2)/3,utf8_decode('humedad'),'L,B,R',2,'C');

			//Otra parde de abajo
			$this->Cell($tam_condiciones,(($tam_font_head+5)/2)/2,utf8_decode('humedo/seco'),'L,R',2,'C');
			$this->Cell($tam_condiciones,(($tam_font_head+5)/2)/2,utf8_decode('(intemperie)'),'L,B,R',0,'C');

			$this->SetXY($posicion_x+$tam_condiciones,$posicion_y);
			$apoyos = 'apoyos (si/no)';
			$tam_apoyos = $this->GetStringWidth($apoyos)+2;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_apoyos,(($tam_font_head+5)/2)/2,'Puntos de','L,T,R',2,'C');
			$this->Cell($tam_apoyos,(($tam_font_head+5)/2)/2,utf8_decode($apoyos),'L,B,R',2,'C');
			$tam_lijado = $tam_cuero = $tam_apoyos/2;
			$this->Cell($tam_apoyos/2,($tam_font_head+5)/2,'Lijado',1,0,'C');
			$this->Cell($tam_apoyos/2,($tam_font_head+5)/2,utf8_decode('Cuero'),1,0,'C');

			$this->SetXY($posicion_x+$tam_apoyos,$posicion_y);
			$ancho = 'Ancho';
			$tam_ancho = $this->GetStringWidth($ancho)+8;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_ancho,(($tam_font_head+5)/2)/2,$ancho,'L,T,R',2,'C');
			$this->Cell($tam_ancho,(($tam_font_head+5)/2)/2,utf8_decode('cm'),'L,B,R',2,'C');
			
			$tam_lec1 = $tam_lec2 = $tam_ancho/2;

			$this->Cell($tam_ancho/2,($tam_font_head+5)/2,'Lec 1',1,0,'C');
			$this->Cell($tam_ancho/2,($tam_font_head+5)/2,utf8_decode('Lec 2'),1,0,'C');
			
			$this->SetXY($posicion_x+$tam_ancho,$posicion_y);
			$peralte = 'Peralte';
			$tam_peralte = $this->GetStringWidth($peralte)+8;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_peralte,(($tam_font_head+5)/2)/2,$peralte,'L,T,R',2,'C');
			$this->Cell($tam_peralte,(($tam_font_head+5)/2)/2,utf8_decode('cm'),'L,B,R',2,'C');

			$tam_per_lec1 = $tam_per_lec2 = $tam_peralte/2;
			$this->Cell($tam_peralte/2,($tam_font_head+5)/2,'Lec 1',1,0,'C');
			$this->Cell($tam_peralte/2,($tam_font_head+5)/2,utf8_decode('Lec 2'),1,0,'C');

			$this->SetXY($posicion_x+$tam_peralte,$posicion_y);
			$locFalla = 'Localizacion de la falla en mm';
			$tam_locFalla = $this->GetStringWidth($locFalla)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_locFalla,(($tam_font_head+5)/2),$locFalla,'L,T,R',2,'C');
			
			$tam_l1 = $tam_l2 = $tam_l3 = $tam_prom = $tam_locFalla/4;

			$this->Cell($tam_locFalla/4,($tam_font_head+5)/2,'L1',1,0,'C');
			$this->Cell($tam_locFalla/4,($tam_font_head+5)/2,utf8_decode('L2'),1,0,'C');
			$this->Cell($tam_locFalla/4,($tam_font_head+5)/2,'L3',1,0,'C');
			$this->Cell($tam_locFalla/4,($tam_font_head+5)/2,utf8_decode('Prom'),1,0,'C');

			$this->SetXY($posicion_x+$tam_locFalla,$posicion_y);
			$distanciaApoyos = 'entre apoyos';
			$tam_distanciaApoyos = $this->GetStringWidth($distanciaApoyos)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_distanciaApoyos,(($tam_font_head+5))/3,'Distancia','L,T,R',2,'C');
			$this->Cell($tam_distanciaApoyos,(($tam_font_head+5))/3,$distanciaApoyos,'L,R',2,'C');
			$this->Cell($tam_distanciaApoyos,(($tam_font_head+5))/3,'(cm)','L,B,R',2,'C');


			$this->SetXY($posicion_x+$tam_distanciaApoyos,$posicion_y);
			$distanciaPuntos = 'de carga(cm)';
			$tam_distanciaPuntos = $this->GetStringWidth($distanciaPuntos)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_distanciaPuntos,(($tam_font_head+5))/3,'Distancia','L,T,R',2,'C');
			$this->Cell($tam_distanciaPuntos,(($tam_font_head+5))/3,'entre puntos','L,R',2,'C');
			$this->Cell($tam_distanciaPuntos,(($tam_font_head+5))/3,$distanciaPuntos,'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_distanciaPuntos,$posicion_y);
			$carga = 'aplicada';
			$tam_carga = $this->GetStringWidth($carga)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_carga,(($tam_font_head+5))/3,'Carga','L,T,R',2,'C');
			$this->Cell($tam_carga,(($tam_font_head+5))/3,$carga,'L,R',2,'C');
			$this->Cell($tam_carga,(($tam_font_head+5))/3,'(kgf)','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_carga,$posicion_y);
			$modulo = 'Modulo de';
			$tam_modulo = $this->GetStringWidth($modulo)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_modulo,(($tam_font_head+5))/3,$modulo,'L,T,R',2,'C');
			$this->Cell($tam_modulo,(($tam_font_head+5))/3,'ruptura','L,R',2,'C');
			$this->Cell($tam_modulo,(($tam_font_head+5))/3,utf8_decode('(kgf/cm²)'),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_modulo,$posicion_y);
			$defEscpecimen = 'escpecimen';
			$tam_defEscpecimen = $this->GetStringWidth($defEscpecimen)+3;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_defEscpecimen,(($tam_font_head+5))/2,'Defectos','L,T,R',2,'C');
			$this->Cell($tam_defEscpecimen,(($tam_font_head+5))/2,$defEscpecimen,'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_defEscpecimen,$posicion_y);
			
			$velocidad = 'kg/cm2 por minuto';
			$tam_velocidad = $this->GetStringWidth($velocidad)+2;
			$posicion_x = $this->GetX();
			$this->Cell($tam_velocidad,(($tam_font_head+5))/2,utf8_decode('Vel. Aplicación'),'L,T,R',2,'C');
			$this->cell($tam_velocidad,(($tam_font_head+5))/2,utf8_decode('kg/cm² por minuto'),'L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_velocidad,$posicion_y);
			$realizo = 'Realizó';
			//$tam_realizo = $this->GetStringWidth($realizo)+8;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell(0,(($tam_font_head+5)),utf8_decode($realizo),1,2,'C');
			$tam_realizo = $this->GetX() - $posicion_x;

			$this->Ln(0);

			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									$tam_iden,
									$tam_fechaColado,
									$tam_fechaEnsayo,
									$tam_edad,
									$tam_condiciones,
									$tam_lijado,
									$tam_cuero,
									$tam_lec1,
									$tam_lec2,
									$tam_per_lec1,
									$tam_per_lec2,
									$tam_l1,
									$tam_l2,
									$tam_l3,
									$tam_prom,
									$tam_distanciaApoyos,
									$tam_distanciaPuntos,
									$tam_carga,
									$tam_modulo,
									$tam_defEscpecimen,
									$tam_velocidad,
									$tam_realizo
							);

			$tam_font_head = 5.5;	
			$tam_cellsTablesAlto = $tam_font_head - 2.5;
			$this->SetFont('Arial','',$tam_font_head);
			$grupos = 27;
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
			
			
		}

		function Myfooter($infoFormato,$infoU){
			//Definimos la altura del footer
			$tam_font_head =8;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave

			$this->SetY(-50);
			$notas = 'Notas:';

			$tam_notas = $this->GetStringWidth($notas)+20;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY()+1;
			$this->Cell($tam_notas,($tam_font_head),$notas,0,2);
			
			$tam_font_head =6.5;	
			$this->SetFont('Arial','',$tam_font_head);//Fuente para clave
			$this->SetXY($posicion_x+$tam_notas,$posicion_y);

			//Definimos el tamaño de las celdas que contiene la placa de las herramientas
			$termo = 'Termómetro';
			$tam_termo = $this->GetStringWidth($termo)+10;

			$inventario = 'Inventario de';
			$tam_inventario = $this->GetStringWidth($inventario)+10;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_inventario,(($tam_font_head))/2,$inventario,'L,T,R',2,'C');
			$this->Cell($tam_inventario,(($tam_font_head))/2,'instrumento','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_inventario,$posicion_y);
			$prensa = 'PRENSA';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_termo,(($tam_font_head))/2,$prensa,1,2,'C');
			$this->Cell($tam_termo,(($tam_font_head))/2,utf8_decode($infoFormato['prensa_placas']),'L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_termo,$posicion_y);
			$flexo = 'FLEXO';
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_termo,(($tam_font_head))/2,$flexo,1,2,'C');
			$this->Cell($tam_termo,(($tam_font_head))/2,utf8_decode($infoFormato['regVerFle_id_placas']),'L,B,R',0,'C');
			//Aqui van las notas
			$this->SetXY($posicion_x+$tam_termo,$posicion_y);
			$this->Cell(0,(($tam_font_head)),'',1,0,'C');

			$this->Ln();

			$aviso = 'ESTE DOCUMENTO SE REFIERE EXCLUSIVAMENTE AL CONCRETO ENSAYADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS';
			$tam_aviso = $this->GetStringWidth($aviso);
			$tam_font_head =5;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave
			$this->Cell($tam_aviso,(($tam_font_head)),utf8_decode($aviso),'T',2);

			$this->Ln(4);

			$tam_image = 15;
			$tam_font_head =8;	$this->SetFont('Arial','B',$tam_font_head);//Fuente para clave
			$superviso = 'Supervisó:';
			$this->SetX(70);

			if($infoU['firmaLaboratorista'] != "null"){
				$this->Cell($this->GetStringWidth($superviso)+2,(($tam_font_head)),utf8_decode($superviso.$this->Image($infoU['firmaLaboratorista'],$this->GetX()+($this->GetStringWidth($superviso)+10),$this->GetY()-3,$tam_image,$tam_image)),0,0);
			}else{
				$this->Cell($this->GetStringWidth($superviso)+2,(($tam_font_head)),utf8_decode($superviso."                                         NO HAY FIRMA"));
			}
			$this->Cell($this->GetStringWidth($superviso)+20,(($tam_font_head)-2),utf8_decode($infoU['nombreLaboratorista']),'B',2,'C');
			$this->Cell($this->GetStringWidth($superviso)+20,(($tam_font_head)-2),'Nombre,firma y puesto',0,0);



		}

		function Footer(){
			$this->SetY(-15);
		    $this->SetFont('Arial','',8);
		    $tam_noPagina = $this->GetStringWidth('Page '.$this->PageNo().'/{nb}');
		    $posicion_x = (279.4 - $tam_noPagina)/2;
		    $this->SetX($posicion_x);
		    $this->Cell($tam_noPagina,10,'Page '.$this->PageNo().'/{nb}',0,0,'C');

		    //Clave de validacion
		    $clave = 'FI-09-LCC-01-0.2';
		    $tam_clave = $this->GetStringWidth($clave);
		    $this->SetX(-($tam_clave + 10));
		    $this->Cell($tam_noPagina,10,$clave,0,0,'C');
		}

		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato,$infoU,$target_dir){
			$pdf  = new EnsayoVigaPDF('L','mm','Letter');
			$pdf->AddPage();
			$pdf->AliasNbPages();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($regisFormato);
			$pdf->Myfooter($infoFormato,$infoU);
			//$pdf->Output();
			$pdf->Output('F',$target_dir);
		}

	}
	
?>