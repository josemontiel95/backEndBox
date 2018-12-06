<?php
	include_once("./../../disenoFormatos/InformeCilindros.php");
	include_once("./../../disenoFormatos/InformeVigas.php");
	include_once("./../../disenoFormatos/InformeCubos.php");
	include_once("./../../disenoFormatos/CCH.php");
	include_once("./../../disenoFormatos/EnsayoCuboPDF.php");
	include_once("./../../disenoFormatos/EnsayoCilindroPDF.php");
	include_once("./../../disenoFormatos/EnsayoVigaPDF.php");
	include_once("./../../disenoFormatos/InformeRevenimiento.php");
	include_once("./../../disenoFormatos/Revenimiento.php");
	include_once("./../../configSystem.php"); 
	include_once("./../../usuario/Usuario.php");
	include_once("./../../FPDF/MyPDF.php");

	class ValidatorTextPDF{


		/*
			Funcion que valida las celdas que comparten todos los formatos(header)
		*/
		function validatedInfo($campoFront,$string){
			$band = 0;
			switch ($campoFront) {
					case 'informeNo';
							$campo = 'tam_informeText';
					break;
					case 'obra';
							$campo = 'tam_nomObraText';
							$band = 1;
					break;
					case 'localizacion';
							$campo = 'tam_localizacionText';
							$band = 1;
					break;
					case 'razonSocial';
							$campo = 'tam_razonText';
					break;
					case 'direccion';
							$campo = 'tam_dirClienteText';
					break;
					default:
						$arr = array('campoFront' => $campoFront,'estatus' => 'Error, no existe relacion con ese campo.','error' => 11);
						return json_encode($arr);
					break;
			}

			//Instanciamos los formatos
			$infoCubos = new InformeCubos();
			$infoCilindros = new InformeCilindros();
			$infoVigas = new InformeVigas();
			$infoRev = new InformeRevenimiento();

			//Lammamos a la funcion que gener las celdas de informacion de cada formato
			$arrayInfoCubos = $infoCubos->generateCellsInfoForvalidation();
			$arrayInfoCilindros = $infoCilindros->generateCellsInfoForvalidation();
			$arrayinfoVigas = $infoVigas->generateCellsInfoForvalidation();
			$arrayinfoRev = $infoRev->generateCellsInfoForvalidation();
			
			$arr = array(
							$arrayInfoCubos,
							$arrayInfoCilindros,
							$arrayinfoVigas,
							$arrayinfoRev
						);


			$tam_font = 'tam_font_left';

			if($campo == 'tam_informeText'){
				$tam_font = 'tam_font_right';
				array_pop($arr);
			}
					
						
			//Asignamos arbitrariamente a uno que sera el mas "pequeño" hasta enontrar otro, en este caso $arr[0]
			$posicion = 0;
			$min = $arr[$posicion][$campo];
			//Empeamos el ciclo despues del que se selecciono como minimo
			for($i=$posicion+1;$i<sizeof($arr);$i++){
				if($arr[$i][$campo] < $min){
					$min = $arr[$i][$campo];
					$posicion = $i;
				}
			}

			$pdf = new MyPDF();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',$arr[$posicion][$tam_font]);
			$tam_string = $pdf->GetStringWidth($string);

			//Metodo para campos que no son el nombre de la obra o la localizacion
			if($tam_string <= $min && $band == 0){
				$arr = array('string' => $string,'tam_string' => $tam_string,'tam_campo' => $min,'estatus' => 'Texto valido.','error' => 0);
			}else{
				if($band == 0){
					$new_string = $this->truncaCadena($arr[$posicion][$tam_font],$string,$min);
					$tam_new_string = $pdf->GetStringWidth($new_string);
					$arr = array('string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo' => $min,'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
				}
				
			}

			//Metodo para el campo de la obra o localizacion de la obra

			if($band == 1){
				$tam_celdaAlto = $arr[$posicion]['tam_CellsLeftAlto'];
				$arr = $pdf->printInfoObraAndLocObra($arr[$posicion][$tam_font],$min,$tam_celdaAlto,$string,3);
			}
			return json_encode($arr);
		}


		function validatedCamposCCH($campo,$string){
			//Instanciamos los formatos que usaremos

			$infoCubos = new InformeCubos();
			$infoCilindros = new InformeCilindros();
			$infoVigas = new InformeVigas();
			$ensayoVigas = new EnsayoVigaPDF();
			$rev = new InformeRevenimiento();
			$cch = new CCH();
			$r = new Revenimiento();

			//Generamos las celdas
			$infoCubos->generateCellsCampos();
			$cch->generateCellsCampos();
			$infoCilindros->generateCellsCampos();
			$rev->generateCellsCampos();
			$r->generateCellsCampos();

			//En estos formatos los campos que posiblemente queremos estan en el header por eso se generan de otra manera
			$infoVigas->generateCellsInfo();
			$ensayoVigas->generateCellsInfo();



			//Guardamos las celdas generadas en arreglos para tratar esa informacion despues
			$arrayInfoCubos = $infoCubos->getCellsTables();
			$arrayCCH = $cch->getCellsTables();
			$arrayInfoCilindros = $infoCilindros->getCellsTables();
			$arrayRev = $rev->getCellsTables();
			$arrayR = $r->getCellsTables();

			//Estos formatos llaman a una funcion diferente porque tiene la informacion que posiblemente queramos en e header
			$arrayInfoVigas = $infoVigas->getCellsInfo();
			$arrayInfoEnsayoVigas = $ensayoVigas->getCellsInfo();

			/*
			print_r($arrayInfoEnsayoVigas);
			echo "Arreglo del revenimiento:";
			print_r($arrayRev);
			echo "Arreglo del CCH";
			print_r($arrayCCH);
			*/
			//Generamos los campos de los detalles para validar las observaciones e instrumentos
			$infoCubos->generateCellsDetails();
			$cch->generateCellsDetails();
			$infoCilindros->generateCellsDetails();
			$infoVigas->generateCellsDetails();
			$r->generateCellsDetails();

			
			//Unimos los dos arreglos para validar todos los campos que se necesiten
			$arrayInfoCubos = array_merge($arrayInfoCubos, $infoCubos->getcellsDetails());
			//echo "Hola soy CUbos";
			//print_r($arrayInfoCubos);
			$arrayCCH = array_merge($arrayCCH, $cch->getcellsDetails());
			//echo "Hola soy CCH";
			//print_r($arrayCCH);
			$arrayInfoCilindros = array_merge($arrayInfoCilindros, $infoCilindros->getcellsDetails());
			//echo "Hola soy CIli";
			//print_r($arrayInfoCilindros);
			$arrayInfoVigas = array_merge($arrayInfoVigas, $infoVigas->getcellsDetails());
			//echo "Hola soy Vigas";
			//print_r($arrayInfoVigas);
			$arrayR = array_merge($arrayR, $r->getcellsDetails());
			//print_r($arrayR);


			//Inicialmente podemos asignar la fuente como la el tamaño de fuente de los rows, despues cambiaremos dependiendo si lo necesitamos para otros campos como las observaciones o las herramientas del inventario
			$font = 'tam_font_CellsRows';

			
			/*
				Estados de la bandera:
										- band = 1		Se selecciono un campo que involucra los formatos de ensayoViga y informe final viga
										- band = 2		Se selecciono un campos que involucra mas formatos pero ese campo contiene varios reglones para escribir (Por el momento solo la localizacion tiene esa caracteristica)
										- band = 0		No se selecciono ninguna de las anteriores
			*/

			$band = 0;

			//Utilizamos la relacion que ya se ha hecho previamente
			switch ($campo) {
				case '2':
					$campo = 'tam_fprimaAncho';
					$band = 1;
					break;
				//Revenimiento de la obra
				case '3':
					$campo = 'tam_obraAncho';
					break;
				case '4':
					$campo = 'tam_agregadoAncho';
					break;
				case '5':
					$campo = 'tam_volumenAncho';
					break;
				case '7':
					$campo = 'tam_unidadAncho';
					break;
				case '8':
					$campo = 'tam_muestreoAncho';
					break;
				case '9':
					$campo = 'tam_recoleccionAncho';
					break;
				case '10':
					$campo = 'tam_elementoAncho';
					$band = 2;
					break;
				case '11':
					$campo = 'tam_horaAncho';
					break;
				//Este es el revenimiento del proyecto
				case '13':
					$campo = 'tam_proAncho';
					break;
				//Campos que se añadieron a la relacion existente en la clase "registroCampo.php"
				case '14':
					$campo = 'tam_observacionAnchoTxt';
					$font = 'tam_font_details';
					break;
				case '15';
					$campo = 'tam_termo';
					$font = 'tam_font_inventario';
					break;
				case '16';
					$campo = 'tam_cono';
					$font = 'tam_font_inventario';

					break;
				case '17';
					$campo = 'tam_varilla';
					$font = 'tam_font_inventario';
					break;
				case '18';
					$campo = 'tam_flexometro';
					$font = 'tam_font_inventario';
					break;
				default:
						$arr = array('campoFront' => $campo,'estatus' => 'Error, no existe relacion con ese campo.','error' => 11);
						return json_encode($arr);
						break;
			}

			//Ingresamos todos los campos generados a un arreglo de "arreglos"
			$arrayAux =  array(
							$arrayInfoCubos,
							$arrayCCH,
							$arrayInfoCilindros,
							$arrayRev,
							$arrayR		
						);

			//Buscamos el valor mas grande de la fuente para evitar errores en algun formato
			
			$tam_font_max = 0; //Inicializamos el tamaño de fuente maximo en 0 para buscar el maximo en los arreglos
			
			for ($i=0; $i <sizeof($arrayAux) ; $i++){
				if(array_key_exists($font,$arrayAux[$i])){
					if($arrayAux[$i][$font]>$tam_font_max){
					$tam_font_max = $arrayAux[$i][$font];
					}
				}	
			}



			//NOTA: Las siguiente comparaciones estan puestas con un campo de fuente definido ya que en las unicas que involucran son en el header de los formatos de vigas, cambiar si hay modificaciones futuras.

			//Comparamos con el tamaño de fuente de las vigas, que difiere por estar en el header
			if($tam_font_max < $arrayInfoVigas['tam_font_left'] && ($band == 2 || $band == 1) ){
				$tam_font_max = $arrayInfoVigas['tam_font_left'];
			}

			//COmparamos con el tamaño de fuente del ensayo de vigas que difiere por estar en el header
			if($tam_font_max < $arrayInfoEnsayoVigas['tam_font_left'] && ($band == 2 || $band == 1) ){
				$tam_font_max = $arrayInfoEnsayoVigas['tam_font_left'];
			}

			//echo "TAmaño de fuente".$tam_font_max;

			//Unimos los arreglos para posteriormente buscar el campos que se necesita
			array_push($arrayAux,$arrayInfoVigas);
			array_push($arrayAux,$arrayInfoEnsayoVigas);

			//Declaramos el array donde guardaremos los arreglo que tienen el campo
			$arr = array();

			//Verificamos si el campo seleccionado se encuentra en mas formatos y lo extraemos
			for ($i=0; $i < sizeof($arrayAux); $i++) { 
				if(array_key_exists($campo,$arrayAux[$i])){
					array_push($arr,$arrayAux[$i][$campo]);
				}
			}

			//Buscamos el minimo
			$min = $arr[0];
			for ($i=1; $i <sizeof($arr) ; $i++) { 
				if($arr[$i] < $min){
					$min = $arr[$i];
				}
			}

			$pdf = new MyPDF();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',$tam_font_max);
			$tam_string = $pdf->GetStringWidth($string);

			//echo "Array que se va a ocupar:";
			//print_r($arr);

			if($tam_string <= $min && ($band == 0 || $band == 1) ){
				$arr = array('string' => $string,'tam_string' => $tam_string,'tam_campo' => $min,'estatus' => 'Texto valido.','error' => 0);
			}else{
				if($band == 0 || $band == 1){
					$new_string = $this->truncaCadena($tam_font_max,$string,$min);
					$tam_new_string = $pdf->GetStringWidth($new_string);
					$arr = array('string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo' => $min,'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
				}
			}

			//Veamos que tamaño tiene los elementos del array cuando el valor que se requiere es el del elemento colado
			

			//echo "HOla soy el mas pequeño:".$min;


			if($band == 2){
				//Tomamos arbitrariamente la el tamaño de la celda(en altura) ya que no repercute para la ejecucion, en este caso tomamos la mas pequeña
				$tam_celdaAlto = $arrayAux[0]['tam_cellsTablesAlto'];
				$arr = $pdf->printInfoObraAndLocObra($tam_font_max,$min,$tam_celdaAlto,$string,3);
				
			}

			return json_encode($arr);

		}

		function validatedCamposEnsayoCubo($campo,$string){
			//Instanciamos los formatos que usaremos
			$infoCubos = new InformeCubos();
			$ensayoCubo = new EnsayoCuboPDF();

			//Generamos los campos
			$infoCubos->generateCellsCampos();

			//Añadimos una pagina por error del formato
			$ensayoCubo->AddPage();
			$ensayoCubo->generateArrayCampo();

			$arrayInfoCubos = $infoCubos->getCellsTables();
			$arrayEnsayoCubo = $ensayoCubo->getArrayCampo();

			$font = 'tam_font_CellsRows';

			switch ($campo) {
				case '1':
					$campo = 'tam_lado1Ancho';
					break;
				case '2':
					$campo = 'tam_lado2Ancho';
					break;
				//La carga de los ensayos
				case '3':
					$campo = 'tam_kgAncho';
					break;
				/*
					No esta en ninguno de los formatos	
					case '4':
					$campo = 'falla';
					break;
	
				*/
				//Se agregaron a la relacion ya existente de la clase "EnsayoCubo.php"
				case '6':
					$campo = 'tam_observacionAnchoTxt';
					$font = 'tam_font_observaciones';
					break;
				case '7':
					$campo = 'tam_bascula';
					$font = 'tam_font_inventario';
					break;
				case '8':
					$campo = 'tam_flexo';
					$font = 'tam_font_inventario';
					break;
				case '9':
					$campo = 'tam_prensa';
					$font = 'tam_font_inventario';
					break;
				default:
						$arr = array('campoFront' => $campo,'estatus' => 'Error, no existe relacion con ese campo.','error' => 11);
						return json_encode($arr);
						break;
			}

			//Ingresamos todos los campos generados a un arreglo de "arreglos"
			$arrayAux =  array(
							$arrayInfoCubos,
							$arrayEnsayoCubo
						);

			$tam_font_max = 0; //Inicializamos el tamaño de fuente maximo en 0 para buscar el maximo en los 

			for ($i=0; $i <sizeof($arrayAux) ; $i++){
				if(array_key_exists($font,$arrayAux[$i])){
					if($arrayAux[$i][$font]>$tam_font_max){
					$tam_font_max = $arrayAux[$i][$font];
					}
				}	
			}
			

			//Declaramos el array donde guardaremos los arreglo que tienen el campo
			$arr = array();

			//Verificamos si el campo seleccionado se encuentra en mas formatos
			for ($i=0; $i < sizeof($arrayAux); $i++) { 
				if(array_key_exists($campo,$arrayAux[$i])){
					array_push($arr,$arrayAux[$i][$campo]);
				}
			}

			//print_r($arr);
			
			$min = $arr[0];
			for ($i=1; $i <sizeof($arr) ; $i++) { 
				if($arr[$i] < $min){
					$min = $arr[$i];
				}
			}

			


			$pdf = new fpdf();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',$tam_font_max);
			$tam_string = $pdf->GetStringWidth($string);

			if($tam_string <= $min){
				$arr = array('string' => $string,'tam_string' => $tam_string,'tam_campo' => $min,'estatus' => 'Texto valido.','error' => 0);
			}else{
				$new_string = $this->truncaCadena($tam_font_max,$string,$min);
				$tam_new_string = $pdf->GetStringWidth($new_string);
				$arr = array('string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo' => $min,'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
			}

			return json_encode($arr);
		}


		function validatedCamposEnsayoCilindros($campo,$string){
			//Instanciamos los formatos que usaremos
			$infoCilindros = new InformeCilindros();
			$ensayoCilindros = new EnsayoCilindroPDF();

			//Generamos los campos
			$infoCilindros->generateCellsCampos();

			//Añadimos una pagina por error del formato
			$ensayoCilindros->AddPage();
			$ensayoCilindros->generateArrayCampo();

			$arrayInfoCilindros = $infoCilindros->getCellsTables();
			$arrayEnsayoCilindros = $ensayoCilindros->getArrayCampo();

			$font = 'tam_font_CellsRows';

			switch ($campo) {
				case '1':
					$campo = 'tam_pesoAncho';
					break;
				case '2':
					$campo = 'tam_d1';
					break;
				case '3':
					$campo = 'tam_d2';
					break;
				case '4':
					$campo = 'tam_h1';
					break;
				case '5':
					$campo = 'tam_h2';
					break;
				case '6':
					$campo = 'tam_kgAncho';
					break;
				case '7':
					$campo = 'tam_fallaAncho';
					break;
				case '9':
					$campo = 'velAplicacionExp';
					break;
				/*
						No est en ninguno de los formatos
				case '10':
					$campo = 'tiempoDeCarga';
					break;
				*/
				//Se agregaron a la relacion ya existente de la calse "EnsayoCubo.php"
				case '11':
					$campo = 'tam_observacionAnchoTxt';
					$font = 'tam_font_observaciones';
					break;
				case '12':
					$campo = 'tam_bascula';
					$font = 'tam_font_inventario';
					break;
				case '13':
					$campo = 'tam_flexo';
					$font = 'tam_font_inventario';
					break;
				case '14':
					$campo = 'tam_prensa';
					$font = 'tam_font_inventario';
					break;					
				default:
						$arr = array('campoFront' => $campo,'estatus' => 'Error, no existe relacion con ese campo.','error' => 11);
						return json_encode($arr);
						break;
			}

			//Ingresamos todos los campos generados a un arreglo de "arreglos"
			$arrayAux =  array(
							$arrayInfoCilindros,
							$arrayEnsayoCilindros
						);

			$tam_font_max = 0; //Inicializamos el tamaño de fuente maximo en 0 para buscar el maximo en los 

			for ($i=0; $i <sizeof($arrayAux) ; $i++){
				if(array_key_exists($font,$arrayAux[$i])){
					if($arrayAux[$i][$font]>$tam_font_max){
					$tam_font_max = $arrayAux[$i][$font];
					}
				}	
			}
			

			//Declaramos el array donde guardaremos los arreglo que tienen el campo
			$arr = array();

			//Verificamos si el campo seleccionado se encuentra en mas formatos
			for ($i=0; $i < sizeof($arrayAux); $i++) { 
				if(array_key_exists($campo,$arrayAux[$i])){
					array_push($arr,$arrayAux[$i][$campo]);
				}
			}

			//print_r($arr);
			
			$min = $arr[0];
			for ($i=1; $i <sizeof($arr) ; $i++) { 
				if($arr[$i] < $min){
					$min = $arr[$i];
				}
			}

			


			$pdf = new fpdf();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',$tam_font_max);
			$tam_string = $pdf->GetStringWidth($string);

			if($tam_string <= $min){
				$arr = array('string' => $string,'tam_string' => $tam_string,'tam_campo' => $min,'estatus' => 'Texto valido.','error' => 0);
			}else{
				$new_string = $this->truncaCadena($tam_font_max,$string,$min);
				$tam_new_string = $pdf->GetStringWidth($new_string);
				$arr = array('string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo' => $min,'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
			}

			return json_encode($arr);



		}


		function validatedCamposEnsayoVigas($campo,$string){
			//Instanciamos los formatos que usaremos
			$infoVigas = new InformeVigas();
			$ensayoVigas = new EnsayoVigaPDF();

			//Generamos los campos
			$infoVigas->generateCellsCampos();
			$ensayoVigas->generateCellsCampos();
			
			//Extraemos las medidas de los campos
			$arrayInfoVigas = $infoVigas->getCellsTables();
			$arrayInfoEnsayoVigas = $ensayoVigas->getCellsTables();

			

			//Extraemos la informacion del campo RegistroNo
			$ensayoVigas->generateCellsInfo();
			$arrayInfoEnsayoVigas = array_merge($arrayInfoEnsayoVigas,$ensayoVigas->getCellsInfo());

			//print_r($arrayInfoVigas);
			//print_r($arrayInfoEnsayoVigas);

			$font = 'tam_font_CellsRows';

			switch ($campo) {
				/*
					No lo valido porque lo proporciona el sistema

				case '1':
					$campo = 'condiciones';
					$arr = json_decode($this->updateCampo($campo,$valor,$id_ensayoViga),true);
					break;
				*/
				/*
						No lo valido porque lo proporciona el sistema

				case '2': // Si uso
					$campo = 'lijado';
					$arr = json_decode($this->updateCampo($campo,$valor,$id_ensayoViga),true);
					break;
				*/
				/*
					No lo valido proque no esta en el formato

				case '3':
					$campo = 'posFractura';
					$arr = json_decode($this->updateCampo($campo,$valor,$id_ensayoViga),true);
					break;
				*/
				case '4':
					$campo = 'tam_lec1';
					break;
				case '5':
					$campo = 'tam_lec2';
					break;
				case '6':
					$campo = 'tam_per_lec1';
					break;
				case '7':
					$campo = 'tam_per_lec2';
					break;
				case '8':
					$campo = 'tam_l1';
					break;
				case '9':
					$campo = 'tam_l2';
					break;
				case '10':
					$campo = 'tam_l3';
					break;
				case '11':
					$campo = 'tam_distanciaApoyos';
					break;
				case '12':
					$campo = 'tam_distanciaPuntos';
					break;
				case '13':
					$campo = 'tam_carga';
					break;
				case '14':
					$campo = 'tam_defEscpecimen';
					break;
				case '15':
					$campo = 'tam_velocidad';
					break;
				case '16':
					$campo = 'tam_regNoText';
					break;
				/*
					No lo valido porque no viene en el formato

				case '16':
					$campo = 'tiempoDeCarga';
					$arr = json_decode($this->updateCampo($campo,$valor,$id_ensayoViga),true);
					break;	
				*/		
				default:
						$arr = array('campoFront' => $campo,'estatus' => 'Error, no existe relacion con ese campo.','error' => 11);
						return json_encode($arr);
						break;
			}

			//Ingresamos todos los campos generados a un arreglo de "arreglos"
			$arrayAux =  array(
							$arrayInfoVigas,
							$arrayInfoEnsayoVigas
						);

			$tam_font_max = 0; //Inicializamos el tamaño de fuente maximo en 0 para buscar el maximo en los 

			for ($i=0; $i <sizeof($arrayAux) ; $i++){
				if(array_key_exists($font,$arrayAux[$i])){
					if($arrayAux[$i][$font]>$tam_font_max){
					$tam_font_max = $arrayAux[$i][$font];
					}
				}	
			}
			

			//Declaramos el array donde guardaremos los arreglo que tienen el campo
			$arr = array();

			//Verificamos si el campo seleccionado se encuentra en mas formatos
			for ($i=0; $i < sizeof($arrayAux); $i++) { 
				if(array_key_exists($campo,$arrayAux[$i])){
					array_push($arr,$arrayAux[$i][$campo]);
				}
			}

			//print_r($arr);
			
			$min = $arr[0];
			for ($i=1; $i <sizeof($arr) ; $i++) { 
				if($arr[$i] < $min){
					$min = $arr[$i];
				}
			}

			$pdf = new fpdf();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',$tam_font_max);
			$tam_string = $pdf->GetStringWidth($string);

			if($tam_string <= $min){
				$arr = array('string' => $string,'tam_string' => $tam_string,'tam_campo' => $min,'estatus' => 'Texto valido.','error' => 0);
			}else{
				$new_string = $this->truncaCadena($tam_font_max,$string,$min);
				$tam_new_string = $pdf->GetStringWidth($new_string);
				$arr = array('string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo' => $min,'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
			}

			return json_encode($arr);
		}


		function validatedCamposRevenimiento($campo,$string){
			//Instanciamos los formatos que usaremos
			$rev = new InformeRevenimiento();
			$r = new Revenimiento();

			//Generamos los campos
			$rev->generateCellsCampos();
			$r->generateCellsCampos();

			$arrayRev = $rev->getCellsTables();
			$arrayR = $r->getCellsTables();

			$r->generateCellsDetails();
			$arrayR = array_merge($arrayR, $r->getcellsDetails());


			//print_r($arrayRev);
			//print_r($arrayR);

			$font = 'tam_font_CellsRows';

			switch ($campo) {
				/*
					No lo valido porque lo genera el sistema

				case '1':
					$campo = 'fecha';
					break;
				*/
				case '2':
					$campo = 'tam_proAncho';
					break;
				case '3':
					$campo = 'tam_revObtenido';
					break;
				case '4':
					$campo = 'tam_agregadoAncho';
					break;
				case '5':
					$campo = 'tam_anchoIden';
					break;
				case '6':
					$campo = 'tam_volumenAncho';
					break;
				/*
					No lo valido porque la hora tiene una longitud definida por el sistema

				case '7':
					$campo = 'tam_ancho_hora_determinacion';
					break;
				*/
				case '8':
					$campo = 'tam_unidadAncho';
					break;
				case '9':
					$campo = 'tam_provedor';
					break;
				case '10':
					$campo = 'tam_remision';
					break;
				/*
					No lo valido porque la hora tiene una longitud definida por el sistema

				case '11':
					$campo = 'horaSalida';
					break;
				case '12':
					$campo = 'horaLlegada';
					break;
				*/

				/*
					No lo valido porque no viene en el formato

				case '13':
					$campo = 'status';
					break;
				*/
				case '14':
					$campo = 'tam_observacionAnchoTxt';
					$font = 'tam_font_details';
					break;
				case '15';
					$campo = 'tam_cono';
					$font = 'tam_font_inventario';

					break;
				case '16';
					$campo = 'tam_varilla';
					$font = 'tam_font_inventario';
					break;
				case '17';
					$campo = 'tam_flexometro';
					$font = 'tam_font_inventario';
					break;
				default:
						$arr = array('campoFront' => $campo,'estatus' => 'Error, no existe relacion con ese campo.','error' => 11);
						return json_encode($arr);
						break;
			}

			//Ingresamos todos los campos generados a un arreglo de "arreglos"
			$arrayAux =  array(
							$arrayRev,
							$arrayR
						);

			$tam_font_max = 0; //Inicializamos el tamaño de fuente maximo en 0 para buscar el maximo en los 

			for ($i=0; $i <sizeof($arrayAux) ; $i++){
				if(array_key_exists($font,$arrayAux[$i])){
					if($arrayAux[$i][$font]>$tam_font_max){
					$tam_font_max = $arrayAux[$i][$font];
					}
				}	
			}
			

			//Declaramos el array donde guardaremos los arreglo que tienen el campo
			$arr = array();

			//Verificamos si el campo seleccionado se encuentra en mas formatos
			for ($i=0; $i < sizeof($arrayAux); $i++) { 
				if(array_key_exists($campo,$arrayAux[$i])){
					array_push($arr,$arrayAux[$i][$campo]);
				}
			}

			//print_r($arr);
			
			$min = $arr[0];
			for ($i=1; $i <sizeof($arr) ; $i++) { 
				if($arr[$i] < $min){
					$min = $arr[$i];
				}
			}

			//echo "Fuente".$tam_font_max;


			$pdf = new fpdf();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',$tam_font_max);
			$tam_string = $pdf->GetStringWidth($string);

			if($tam_string <= $min){
				$arr = array('string' => $string,'tam_string' => $tam_string,'tam_campo' => $min,'estatus' => 'Texto valido.','error' => 0);
			}else{
				$new_string = $this->truncaCadena($tam_font_max,$string,$min);
				$tam_new_string = $pdf->GetStringWidth($new_string);
				$arr = array('string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo' => $min,'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
			}

			return json_encode($arr);

		}

		

		function truncaCadena($tam_font,$string,$tam){
				$pdf = new fpdf();
				$pdf->AddPage();
				/*
					Solucionar error, no se puede abrir una ruta en la libreria

				if($arrayFont['style'] == null){
					$pdf->SetFont($arrayFont['family'],'',$arrayFont['size']);
				}
				else{
					$pdf->SetFont($arrayFont['family'],$arrayFont['style'],$arrayFont['size']);
				}*/
				$pdf->SetFont('Arial','',$tam_font);
				
				$tam_string = $pdf->GetStringWidth($string); 
				while($tam_string>$tam){
					$string = substr($string,0,(strlen($string))-1);
					$tam_string =  $pdf->GetStringWidth($string);
				}
				//Quitamos la ultima letra y ponemos puntos suspensivos para demostrar que no es valido
				$string = substr($string,0,(strlen($string))-2);
				$string.='...';
			return $string;
		}

		
	}

?>
