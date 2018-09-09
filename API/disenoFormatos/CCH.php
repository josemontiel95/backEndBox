<?php 
	include_once("./../../FPDF/fpdf.php");

	//Formato de campo de cilindros
	class CCH extends fpdf{
		function Header(){
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de Lacocs
			$ancho_lacocs = 40;	$alto_lacocs = 20;
			$this->cell($ancho_lacocs,$alto_lacocs,'',1);

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
			
		}

		//Funcion que coloca la informacion del informe, como: el No. de informe, Obra, etc.
		

		function putInfo($infoFormato){

		
			$tam_font_right = 7.5;	$this->SetFont('Arial','B',$tam_font_right);
			$tam_line = 160;
			

			

			/*
				Lado izquierdo:
								-Obra
								-Localizacion
								-Cliente
								-Direccion
			*/

			$tam_font_left = 7;	$this->SetFont('Arial','',$tam_font_left);

			//Cuadro con informacion
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_line,$tam_font_left - 3,$infoFormato['obra'],'B',0);

			$this->Ln($tam_font_left - 2);

			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);

			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_line,$tam_font_left - 3,$infoFormato['localizacion'],'B',0);

			$informeNo = 'INFORME No.';
			$tam_informeNo = $this->GetStringWidth($informeNo)+6;
			$this->SetX(-($tam_informeNo+40));
			$this->Cell($tam_informeNo,$tam_font_right - 3,$informeNo,0,0,'C');

			//Caja de texto
			$this->Cell(0,$tam_font_right - 3,'DUMMY','B',0,'C');

			$this->Ln($tam_font_left - 2);

			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);

			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_line,$tam_font_left - 3,$infoFormato['razonSocial'],'B',0);

			$this->Ln($tam_font_left - 2);

			//Direccion del cliente
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell($tam_line,$tam_font_left - 3,$infoFormato['direccion'],'B',0);

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

		function putTables(){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY(); $posicion_x = $this->GetX();

			$tam_font_head = 8;	$this->SetFont('Arial','',$tam_font_head);//Fuente para clave

			//Clave
			$clave = 'CLAVE DEL ESPECIMEN';
			$tam_clave = $this->GetStringWidth($clave) + 10; 
			$this->cell($tam_clave,1.5*(2*($tam_font_head) - 6),$clave,1,0,'C');

			//Fecha
			$fecha = 'FECHA';
			$tam_fecha = $this->GetStringWidth($fecha) + 15;
			$this->cell($tam_fecha,1.5*(2*($tam_font_head) - 6),$fecha,1,0,'C');
			//F´c
			$fprima = 'F ` C ';
			$tam_fprima = $this->GetStringWidth($fecha) + 5; $posicion_x = $this->GetX();
			$this->multicell($tam_fprima,0.5*(1.5*(2*($tam_font_head) - 6)),utf8_decode($fprima."\n".'kg/cm²'),1,'C');

			

			//Proyecto
			$proyecto = 'PROYECTO';
			$tam_pro = $this->GetStringWidth($proyecto) + 3;
			$this->SetY($posicion_y + 0.5*(1.5*(2*($tam_font_head) - 6))); $this->SetX($posicion_x + $tam_fprima);	$posicion_x = $this->GetX();
			$this->cell($tam_pro,0.5*(1.5*(2*($tam_font_head) - 6)),$proyecto,1,0,'C');

			//Obra
			$obra = 'OBRA';
			$tam_obra = $this->GetStringWidth($obra) + 4;
			$this->SetY($posicion_y + 0.5*(1.5*(2*($tam_font_head) - 6)));	$this->SetX($posicion_x + $tam_pro);
			$this->cell($tam_obra,0.5*(1.5*(2*($tam_font_head) - 6)),$obra,1,2,'C');


			//Revenimiento
			$rev = 'REVENIMENTO (cm)';
			$this->SetY($posicion_y); $this->SetX($posicion_x); $posicion_x = $this->GetX();
			$this->cell($tam_pro + $tam_obra,0.5*(1.5*(2*($tam_font_head) - 6)),$rev,1,0,'C');

			$tam_font_head = $tam_font_head-2;	$this->SetFont('Arial','',$tam_font_head);

			//Guardamos la posicion de la x
			$posicion_x = $this->GetX();

			//Agregado
			$agregado = 'AGREGADO';
			$tam_agregado = $this->GetStringWidth($agregado) + 4;
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
			$tam_unidad = $this->GetStringWidth($unidad) + 3;
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

			$localizacion = 'LOCALIZACIÓN';
			$tam_localizacion = $posicion_x - 10;
			
			/*
				

			
			
			/*

			

			/* PUNTO DE REFERENCIA
			$falla = 'FALLA';
			$tam_falla = $this->GetStringWidth($falla)+3;

			$this->SetX(-($tam_falla + 10));
			$posicion_x = $this->GetX();
			$this->multicell($tam_falla,(1.5*($tam_font_head - 3)),utf8_decode($falla.'N°'),1,'C');
			
			$resistencia = 'RESISTENCIA';	
			$tam_resistencia = $this->GetStringWidth($resistencia)+3;
			$this->SetY($posicion_y);
			// $this->SetX($this->GetX() - $tam_resistencia);  (Se modifica el valor de la posicion de X cuando se imprime, por eso no se calcula bien)
			$this->SetX(-($tam_resistencia + 10));
			$posicion_x = $this->GetX();
			$this->multicell($tam_resistencia,(1.5*($tam_font_head - 3)),utf8_decode('% DE'."\n".$resistencia),1,'C');

			$proyecto = 'PROYECTO';
			$tam_proyecto = $this->GetStringWidth($proyecto)+3;
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $tam_proyecto);
			$posicion_x = $this->GetX();
			$this->multicell($tam_proyecto,$tam_font_head - 3,utf8_decode("F'c"."\n".'PROYECTO'."\n".'(kg/cm²)'),1,'C');


			//----------------------SE MUEVEN FUENTES---------------------

			//Resistencia a compresion
			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);
			$resis_compresion  = 'RESISTENCIA A';
			$tam_resis = $this->GetStringWidth($resis_compresion)+10;
			$this->SetY($posicion_y);
			$this->SetX($posicion_x - $tam_resis);
			$posicion_x = $this->GetX();
			$this->multicell($tam_resis,0.75*($tam_font_head - 2.5),utf8_decode($resis_compresion."\n".'COMPRESIÓN'),1,'C');

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			//Abajo de resistencia a compresion
			$kgcm = 'kg/cm²';
			$tam_kgcm = $tam_resis/2;
			//$aux_posy = $posicion_y;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	

			$this->SetX($posicion_x + ($tam_resis/2));
			$this->multicell($tam_resis/2,1.5*($tam_font_head - 3),utf8_decode($kgcm),1,'C');

			$mp = 'MPa';
			$tam_mp = $tam_resis/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));
			$this->SetX($posicion_x);
			$this->multicell($tam_resis/2,1.5*($tam_font_head - 3),utf8_decode($mp),1,'C');

			//Carga
			$carga = 'CARGA';
			$tam_carga = $this->GetStringWidth($carga) + 12;
			$this->SetY($posicion_y);	$this->SetX($posicion_x - $tam_carga);	$posicion_x = ($this->GetX());
			$this->cell($tam_carga,1.5*($tam_font_head - 3),$carga,1,0,'C');

			//Abajo de carga
			$kg = '(kg)';
			$tam_kg = $tam_carga/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x + ($tam_carga/2));
			$this->cell($tam_carga/2,1.5*($tam_font_head - 3),$kg,1,0,'C');

			$kN = 'kN';
			$tam_kN = $tam_carga/2;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x);
			$this->cell($tam_carga/2,1.5*($tam_font_head - 3),$kN,1,0,'C');

			//--- SE MUEVE LA FUENTE PARA AJUSTAR AL TAMAÑO

			$tam_font_head = 5.5;	$this->SetFont('Arial','',$tam_font_head);

			//Abajo de Escpecimenes
			$area = 'AREA EN';
			$tam_area = $this->GetStringWidth($area) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_area); $posicion_x = $this->GetX();
			$this->multicell($tam_area,0.75*($tam_font_head - 2.5),utf8_decode($area."\n".'cm²'),1,'C');

			$lado2 = 'LADO 2 EN';
			$tam_lado2 = $this->GetStringWidth($lado2) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_lado2); $posicion_x = $this->GetX();
			$this->multicell($tam_lado2,0.75*($tam_font_head - 2.5),$lado2."\n".'cm',1,'C');

			$lado1 = 'LADO 1 EN';
			$tam_lado1 = $this->GetStringWidth($lado1) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_lado1); $posicion_x = $this->GetX();
			$this->multicell($tam_lado1,0.75*($tam_font_head - 2.5),$lado1."\n".'cm',1,'C');

			$edad = 'EDAD EN';
			$tam_edad = $this->GetStringWidth($edad) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_edad); $posicion_x = $this->GetX();
			$this->multicell($tam_edad,0.75*($tam_font_head - 2.5),utf8_decode($edad."\n".'DIAS'),1,'C');

			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);


			$rev = 'REV. cm';
			$tam_rev = $this->GetStringWidth($rev) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x - $tam_rev);	$posicion_x = $this->GetX();
			$this->cell($tam_rev,1.5*($tam_font_head - 3),$rev,1,2,'C');

			

			//Especimenes
			$especimenes = 'ESPECIMENES';
			$tam_especimenes = ($tam_area + $tam_lado1 + $tam_lado2 + $tam_edad + $tam_rev);
			$this->SetY($posicion_y);	$this->SetX($posicion_x); 
			$this->cell($tam_especimenes,1.5*($tam_font_head - 3),$especimenes,1,2,'C');

			//$this->SetY($posicion_y + 3*($tam_font_head - 3)); pone al final de la celda para el salto
			//$posicion_y = $this->GetY(); Esta era la posicion a la que brincaba cuando pasaban los especimenes

			

			//Fecha de ensaye
			$fecha = 'FECHA DE ENSAYE';
			$tam_fecha = $this->GetStringWidth($fecha) + 3;
			$this->SetX($posicion_x - $tam_fecha); $posicion_x = $this->GetX();
			$this->cell($tam_fecha,1.5*(2*($tam_font_head) - 6),$fecha,1,0,'C');

			//Elemento
			$elemento = 'ELEMENTO MUESTREADO';
			$tam_ele = $posicion_x - 10;
			$this->SetX(10);
			$this->cell($tam_ele,1.5*(2*($tam_font_head) - 6),$elemento,1,2,'C');

			$this->ln(1);

			//Definimos el array con los tamaños de cada celda para crear las duplas

			$array_campo = 	array(
									$tam_resistencia,
									$tam_proyecto,
									$tam_kgcm,
									$tam_mp,
									$tam_kg,
									$tam_kN,
									$tam_area,
									$tam_lado2,
									$tam_lado1,
									$tam_edad,
									$tam_rev,
									$tam_clave,
									$tam_fecha

							);


			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY();
			
			foreach ($regisFormato as $registro) {
				$this->SetX(-10); $posicion_x = $this->GetX();
				$j=0;
				foreach ($registro as $campo) {
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					$this->cell($array_campo[$j],$tam_font_head - 2.5,$campo,1,0,'C');
					$j++;
				}
				$this->Ln();
			}
			//Guardamos la posicion en donde se quedo la Y para comparar con el tamaño de la celda del "Elemento"
			$endDown_table = $this->GetY();
			
			//Modificamos el valor de Y para empezar en el inicio de la tabla
			$this->SetY($ele_posicion_y);
			//Insertamos las celdas de "Elemento muestreado"
			$this->multicell($tam_ele,$tam_font_head - 2.5,'','L,T');
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($tam_font_head - 2.5));
				for ($i=0; $i < $num_iteraciones; $i++) { 
					$this->cell($tam_ele,$tam_font_head - 2.5,'','L',2);
				}
			}

				

			$this->cell(0,$tam_font_head - 2.5,'',1,2);
			$this->cell(0,1.5*($tam_font_head - 3),'',1,2);


			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY();
		
			for ($i=0; $i < 8; $i++){
				//Definimos la posicion de X para tomarlo como referencia
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < sizeof($array_campo); $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					$this->cell($array_campo[$j],$tam_font_head - 2.5,'',1,0,'C');
				}	
				$this->Ln();
			}
			//Guardamos la posicion en donde se quedo la Y para comparar con el tamaño de la celda del "Elemento"
			$endDown_table = $this->GetY();
			
			//Modificamos el valor de Y para empezar en el inicio de la tabla
			$this->SetY($ele_posicion_y);
			//Insertamos las celdas de "Elemento muestreado"
			$this->multicell($tam_ele,$tam_font_head - 2.5,'','L,T');
			if($this->GetY() < $endDown_table){
				$num_iteraciones = (($endDown_table - $this->GetY()) / ($tam_font_head - 2.5));
				for ($i=0; $i < $num_iteraciones - 1; $i++) { 
					$this->cell($tam_ele,$tam_font_head - 2.5,'','L',2);
				}
				$this->cell($tam_ele,$tam_font_head - 2.5,'','L,B',2);
			}
			/*
					BYUENAS
			for ($i=0; $i < 8; $i++){
				//Definimos la posicion de X para tomarlo como referencia
				$this->SetX(-10); $posicion_x = $this->GetX();	
				for ($j=0; $j < 15; $j++){ 
					//Definimos la posicion apartir de la cual vamos a insertar la celda
					$this->SetX($posicion_x - $array_campo[$j]); $posicion_x = $this->GetX();
					$this->cell($array_campo[$j],$tam_font_head - 2.5,'',1,0,'C');
				}
				$this->Ln();
			}
			*/



			/*
			for ($i=0; $i < 8; $i++)
				$this->cell(0,$tam_font_head - 2.5,'DUMMY',1,2,'C');
			*/

			//$this->ln(2); BORRAR DESPUES DE QUE TODO ESTE BIEN

			


			
		}
		/*
		function Footer(){
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

		}
		*/
		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato){
			$pdf  = new CCH('L','mm','Letter');
			$pdf->AddPage();
			$pdf->putInfo($infoFormato);
			$pdf->putTables();
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
	/*
	$pdf  = new CCH('L','mm','Letter');
	$pdf->AddPage();
	$pdf->Output();
	*/
	


?>