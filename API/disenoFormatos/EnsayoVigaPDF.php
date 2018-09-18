<?php 
	//include_once("./../../FPDF/fpdf.php");
	include_once("./../FPDF/fpdf.php");

	//Formato de campo de cilindros
	class EnsayoVigaPDF extends fpdf{
		function Header()
		{
			

			//Información de la empresa
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A DE C.V';
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
			$tituloMetodos = 'METODOS EMPLEADOS: NMX-C-161-ONNCCE-2013,NMX-C-191-ONNCCE-1015';
			$tam_tituloMetodos = $this->GetStringWidth($tituloMetodos)+3;
			$this->SetX((279 - $tam_tituloMetodos)/2);
			$this->Cell($tam_tituloMetodos,$tam_font_info - 3,utf8_decode($tituloMetodos),0,'C');

			//---Divide espacios entre el titulo del formato y la información del formato
			$this->Ln($tam_font_tituloInforme - 2);
		}

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.

		function putInfo(){

			/*
			Lado derecho:
							-Informe No.
							-Este informe sustituye a:
			*/
			/*
			$tam_font_right = 7.5;	$this->SetFont('Arial','B',$tam_font_right);

			//Numero del informe
			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;
			$this->SetX(-($tam_informeNo+50));
			$this->Cell($tam_informeNo,$tam_font_right - 3,$informeNo,0,0,'C');

			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');

			$this->Ln($tam_font_right - 2);

			//-Informe al cual sustituye
			$sustituyeInforme = 'ESTE INFORME SUSTITUYE A:';
			$tam_sustituyeInforme = $this->GetStringWidth($sustituyeInforme)+6;
			$this->SetX(-$tam_sustituyeInforme-50);
			$this->Cell($tam_sustituyeInforme,$tam_font_right - 3,$sustituyeInforme,0,0,'C');

			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');

			//--Divide la informacion de la derecha y la izquierda
			$this->Ln($tam_font_right - 1);

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			$tam_font_left = 7;	$this->SetFont('Arial','',$tam_font_left);

			$nomCli = 'CLIENTE:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);

			//Caja de texto
			$this->SetX(50);
			$this->Cell(170,$tam_font_left - 3,'DUMMY','B',0);

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

		function putTables(){
			$tam_font_head = 6.5;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave


			$iden = 'Identificacion de la ';
			$tam_iden = $this->GetStringWidth($iden)+4;
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
			$tam_locFalla = $this->GetStringWidth($locFalla)+6;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_locFalla,(($tam_font_head+5)/2),$locFalla,'L,T,R',2,'C');
			
			$tam_l1 = $tam_l2 = $tam_l3 = $tam_prom = $tam_locFalla/4;

			$this->Cell($tam_locFalla/4,($tam_font_head+5)/2,'L1',1,0,'C');
			$this->Cell($tam_locFalla/4,($tam_font_head+5)/2,utf8_decode('L2'),1,0,'C');
			$this->Cell($tam_locFalla/4,($tam_font_head+5)/2,'L3',1,0,'C');
			$this->Cell($tam_locFalla/4,($tam_font_head+5)/2,utf8_decode('Prom'),1,0,'C');

			$this->SetXY($posicion_x+$tam_locFalla,$posicion_y);
			$distanciaApoyos = 'entre apoyos';
			$tam_distanciaApoyos = $this->GetStringWidth($distanciaApoyos)+6;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_distanciaApoyos,(($tam_font_head+5))/3,'Distancia','L,T,R',2,'C');
			$this->Cell($tam_distanciaApoyos,(($tam_font_head+5))/3,$distanciaApoyos,'L,R',2,'C');
			$this->Cell($tam_distanciaApoyos,(($tam_font_head+5))/3,'(cm)','L,B,R',2,'C');


			$this->SetXY($posicion_x+$tam_distanciaApoyos,$posicion_y);
			$distanciaPuntos = 'de carga(cm)';
			$tam_distanciaPuntos = $this->GetStringWidth($distanciaPuntos)+4;
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
			$tam_modulo = $this->GetStringWidth($modulo)+4;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_modulo,(($tam_font_head+5))/3,$modulo,'L,T,R',2,'C');
			$this->Cell($tam_modulo,(($tam_font_head+5))/3,'ruptura','L,R',2,'C');
			$this->Cell($tam_modulo,(($tam_font_head+5))/3,utf8_decode('(kgf/cm²)'),'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_modulo,$posicion_y);
			$defEscpecimen = 'escpecimen';
			$tam_defEscpecimen = $this->GetStringWidth($defEscpecimen)+10;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_defEscpecimen,(($tam_font_head+5))/2,'Defectos','L,T,R',2,'C');
			$this->Cell($tam_defEscpecimen,(($tam_font_head+5))/2,$defEscpecimen,'L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_defEscpecimen,$posicion_y);
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
									$tam_realizo
							);

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);

			for ($i=0; $i < 27; $i++){
				//Definimos la posicion de X para tomarlo como referenci
				for ($j=0; $j < 21; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->cell($array_campo[$j],$tam_font_head - 2.5,'',1,0,'C');
				}	
				$this->Ln();
			}

			
			
			
		}

		function Myfooter(){
			//Definimos la altura del footer
			$tam_font_head =8;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave

			$this->SetY(-50);
			$notas = 'Notas:';

			$tam_notas = $this->GetStringWidth($notas)+20;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY()+1;
			$this->Cell($tam_notas,($tam_font_head),$notas,0,2);
			
			$tam_font_head =7;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave
			$this->SetXY($posicion_x+$tam_notas,$posicion_y);
			$inventario = 'Inventario de';
			$tam_inventario = $this->GetStringWidth($inventario)+10;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_inventario,(($tam_font_head))/2,$inventario,'L,T,R',2,'C');
			$this->Cell($tam_inventario,(($tam_font_head))/2,'instrumento','L,B,R',2,'C');
			
			$this->SetXY($posicion_x+$tam_inventario,$posicion_y);
			$prensa = 'PRENSA';
			$tam_prensa = $this->GetStringWidth($prensa)+12;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_prensa,(($tam_font_head))/2,$prensa,1,2,'C');
			$this->Cell($tam_prensa,(($tam_font_head))/2,'','L,B,R',2,'C');

			$this->SetXY($posicion_x+$tam_prensa,$posicion_y);
			$flexo = 'FLEXO';
			$tam_flexo = $this->GetStringWidth($flexo)+12;
			$posicion_x = $this->GetX(); $posicion_y = $this->GetY();
			$this->Cell($tam_flexo,(($tam_font_head))/2,$flexo,1,2,'C');
			$this->Cell($tam_flexo,(($tam_font_head))/2,'','L,B,R',0,'C');

			$this->Ln();

			$aviso = 'ESTE DOCUMENTO SE REFIERE EXCLUSIVAMENTE AL CONCRETO ENSAYADO Y NO DEBE SER REPRODUCIDO EN FORMA PARCIAL SIN LA AUTORIZACIÓN POR ESCRITO DEL LABORATORIO LACOCS';
			$tam_aviso = $this->GetStringWidth($aviso);
			$tam_font_head =5;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave
			$this->Cell($tam_aviso,(($tam_font_head)),$aviso,'T',2);

			$this->Ln(4);

			$tam_font_head =8;	$this->SetFont('Arial','B',$tam_font_head);//Fuente para clave
			$superviso = 'Supervisó:';
			$this->SetX(70);
			$this->Cell($this->GetStringWidth($superviso)+2,(($tam_font_head)),utf8_decode($superviso),0,0);
			$this->Cell($this->GetStringWidth($superviso)+20,(($tam_font_head)-2),'','B',2);
			$this->Cell($this->GetStringWidth($superviso)+20,(($tam_font_head)-2),'Nombre,firma y puesto',0,0);

		}

		function Footer(){/*
			$tam_footer = 28;
			
			$tam_font_footer = 7;	$this->SetFont('Arial','B',$tam_font_footer);
			

			//Observaciones
			$this->cell(0,2*($tam_font_footer - 2.5),'',1,2,'C');

			//Metodos empleados
			$metodos = 'METODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			//$this->multicell(0,($tam_font_head - 2.5),$metodos,1,2);

			//Incertidumbre
			$incertidumbre = 'INCERTIDUMBRE';
			$tam_incertidumbre = $this->GetStringWidth($incertidumbre)+20;
			$this->SetX(-($tam_incertidumbre + 10));
			//Guardamos las posiciones de esa linea
			$posicion_x = $this->GetX();	$posicion_y = $this->GetY();

			$this->multicell($tam_incertidumbre,($tam_font_footer - 3),$incertidumbre."\n".'DUMMY',1,'C');

			//Metodos empleados
			$this->SetY($posicion_y);
			$metodos = 'METODOS EMPLEADOS: EL ENSAYO REALIZADO CUMPLE CON LAS NORMAS MEXICANAS NMX-C-161-ONNCCE-2013, NMX-C-156-ONNCCE-2010,'."\n".'NMX-C-159-ONNCCE-2016,NMX-C-109-ONNCCE-2013,NMX-C-083-ONNCCE-2014';
			$tam_metodos = $this->GetStringWidth($metodos)+3;
			$this->multicell($posicion_x -10,($tam_font_footer - 3),$metodos,1,2);

			$this->SetY(-($tam_footer + 10)); //Defenimos el margen de abajo
			$this->cell(0,$tam_footer,'',1,'C');
			*/
		}

		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato){
			$pdf  = new InformeCubos('L','mm','Letter');
			$pdf->AddPage();
			$pdf->putInfo($infoFormato);
			$pdf->putTables($regisFormato);
			$pdf->Output();
		}


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
	
	$pdf  = new EnsayoVigaPDF('L','mm','Letter');
	$pdf->AddPage();
	$pdf->putInfo();
	$pdf->putTables();
	$pdf->Myfooter();
	$pdf->Output();

	


?>