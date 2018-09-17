<?php 
	//include_once("./../../FPDF/fpdf.php");
	include_once("./../../FPDF/fpdf.php");
	//Formato de campo de cilindros
	class InformeCilindros extends fpdf{
		function Header()
		{
			//Espacio definido para los logotipos
			//Definimos las dimensiones del logotipo de ema
			$ancho_ema = 50;	$alto_ema = 20;
			//$this->SetX(-($ancho_ema + 10));
			//$this->Image('ema.jpeg',null,null,$ancho_ema,$alto_ema);
			$this->cell(0,20,'',1,2);
			//Información de la empresa
			$tam_font_titulo = 8.5;
			$this->SetFont('Arial','B',$tam_font_titulo); 
			$titulo = 'LABORATORIO DE CONTROL DE CALIDAD Y SUPERVISIÓN S.A DE C.V';
			$tam_cell = $this->GetStringWidth($titulo);
			$this->SetX((279-$tam_cell)/2);
			$this->Cell($tam_cell,$tam_font_titulo - 3,utf8_decode($titulo),1,'C');
			$this->Ln(7);
			//Titulo del informe
			$tam_font_tituloInforme = 7.5;
			$this->SetFont('Arial','B',$tam_font_tituloInforme);
			$titulo_informe = '"INFORME DE PRUEBAS A COMPRESIÓN DE CILINDROS DE CONCRETO HIDRÁULICO"';
			$tam_tituloInforme = $this->GetStringWidth($titulo_informe)+130;
			$tam_font_info = 6.5;
			//Direccion
			$this->SetFont('Arial','B',$tam_font_info);
			$direccion_lacocs = '35 NORTE No.3023, UNIDAD HABITACIONAL AQUILES SERDAN, PUEBLA, PUE.';
			$tam_direccion = $this->GetStringWidth($direccion_lacocs)+6;
			$this->SetX(-$tam_tituloInforme-10);
			$this->Cell($tam_direccion,$tam_font_info - 3,$direccion_lacocs,0,'C');
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
			//Cuadro con informacion
			$obra = 'Nombre de la Obra:';
			$this->Cell($this->GetStringWidth($obra)+2,$tam_font_left - 3,$obra,0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 3,$infoFormato['obra'],'B',0);
			$this->Ln($tam_font_left - 2);
			$locObra = 'Localización de la Obra:';
			$this->Cell($this->GetStringWidth($locObra)+2,$tam_font_left - 3,utf8_decode($locObra),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 3,$infoFormato['localizacion'],'B',0);
			$this->Ln($tam_font_left - 2);
			$nomCli = 'Nombre del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($nomCli),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 3,$infoFormato['razonSocial'],'B',0);
			$this->Ln($tam_font_left - 2);
			//Direccion del cliente
			$dirCliente = 'Dirección del Cliente:';
			$this->Cell($this->GetStringWidth($nomCli)+2,$tam_font_left - 3,utf8_decode($dirCliente),0);
			//Caja de texto
			$this->SetX(50);
			$this->Cell(0,$tam_font_left - 3,$infoFormato['direccion'],'B',0);
			//Divide la informacion del formato de la Tabla (Esta en funcion del tamaño de fuente de la informacion de la derecha)
			$this->Ln($tam_font_left);
		}
		function putTables(){
			//Guardamos la posicion de la Y para alinear todas las celdas a la misma altura
			$posicion_y = $this->GetY();
			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			$falla = 'FALLA';
			$tam_falla = $this->GetStringWidth($falla)+3;
			$this->SetX(-($tam_falla + 10));
			$posicion_x = $this->GetX();
			$this->multicell($tam_falla,(1.5*($tam_font_head - 3)),utf8_decode($falla.'N°'),1,'C');
			$resistencia = 'RESISTENCIA';	
			$tam_resistencia = $this->GetStringWidth($resistencia)+3;
			$this->SetY($posicion_y);
			// $this->SetX($this->GetX() - $tam_resistencia);  (Se modifica el valor de la posicion de X cuando se imprime, por eso no se calcula bien)
			$this->SetX($posicion_x - $tam_resistencia);
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
			$altura = 'ALTURA';
			$tam_altura = $this->GetStringWidth($area) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_altura); $posicion_x = $this->GetX();
			$this->multicell($tam_altura,0.75*($tam_font_head - 2.5),utf8_decode($altura."\n".'EN cm'),1,'C');
			$diametro = 'DIAMETRO EN';
			$tam_diametro = $this->GetStringWidth($diametro) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_diametro); $posicion_x = $this->GetX();
			$this->multicell($tam_diametro,0.75*($tam_font_head - 2.5),$diametro."\n".'cm',1,'C');
			$edad = 'EDAD EN';
			$tam_edad = $this->GetStringWidth($edad) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 2.5)));	$this->SetX($posicion_x - $tam_edad); $posicion_x = $this->GetX();
			$this->multicell($tam_edad,0.75*($tam_font_head - 2.5),utf8_decode($edad."\n".'DIAS'),1,'C');
			$tam_font_head = 6;	$this->SetFont('Arial','',$tam_font_head);
			$peso = 'PESO EN kg';
			$tam_peso = $this->GetStringWidth($peso) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x - $tam_peso);	$posicion_x = $this->GetX();
			$this->cell($tam_peso,1.5*($tam_font_head - 3),$peso,1,0,'C');
			$rev = 'REV. cm';
			$tam_rev = $this->GetStringWidth($rev) + 3;
			$this->SetY($posicion_y + (1.5*($tam_font_head - 3)));	$this->SetX($posicion_x - $tam_rev);	$posicion_x = $this->GetX();
			$this->cell($tam_rev,1.5*($tam_font_head - 3),$rev,1,2,'C');
			
			//Especimenes
			$especimenes = 'ESPECIMENES';
			$tam_especimenes = ($tam_area + $tam_altura + $tam_diametro + $tam_edad + $tam_peso + $tam_rev);
			$this->SetY($posicion_y); $this->SetX($posicion_x); 
			$this->cell($tam_especimenes,1.5*($tam_font_head - 3),$especimenes,1,2,'C');
			//$this->SetY($posicion_y + 3*($tam_font_head - 3)); pone al final de la celda para el salto
			$posicion_y = $this->GetY();
			//Clave
			$clave = 'CLAVE';
			$tam_clave = $this->GetStringWidth($clave) + 25;
			$this->SetX($posicion_x - $tam_clave);	$posicion_x = $this->GetX();
			$this->cell($tam_clave,1.5*($tam_font_head - 3),$clave,1,0,'C');
			//Fecha de ensaye
			$fecha = 'FECHA DE ENSAYE';
			$tam_fecha = $this->GetStringWidth($fecha) + 3;
			$this->SetX($posicion_x - $tam_fecha); $posicion_x = $this->GetX();
			$this->cell($tam_fecha,1.5*($tam_font_head - 3),$fecha,1,0,'C');
			//Elemento
			$elemento = 'ELEMENTO MUESTREADO';
			$tam_ele = $posicion_x - 10;
			$this->SetX(10);
			$this->cell($tam_ele,1.5*($tam_font_head - 3),$elemento,1,2,'C');
			$this->ln(1);
			//Definimos el array con los tamaños de cada celda para crear las duplas
			$array_campo = 	array(
									$tam_falla,
									$tam_resistencia,
									$tam_proyecto,
									$tam_kgcm,
									$tam_mp,
									$tam_kg,
									$tam_kN,
									$tam_area,
									$tam_altura,
									$tam_diametro,
									$tam_edad,
									$tam_peso,
									$tam_rev,
									$tam_clave,
									$tam_fecha
							);
			/*
			$tam_resistencia,
									*/
			//Guardamos la posicion de Y para insertar la cellda de "Elemento muestreado"
			$ele_posicion_y = $this->GetY();
			
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
				for ($j=0; $j < 15; $j++){ 
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
			$this->ln(2);
			
			
		}
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
		//Funcion que crea un nuevo formato
		function CreateNew($infoFormato,$regisFormato){
			$pdf  = new informeCilindros('L','mm','Letter');
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
	$pdf  = new informeCilindros('L','mm','Letter');
			$pdf->AddPage();
			$pdf->putTables();
			$pdf->Output();*/
	
?>