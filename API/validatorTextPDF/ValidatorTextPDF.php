<?php
	include_once("./../../disenoFormatos/InformeCilindros.php");
	include_once("./../../disenoFormatos/InformeVigas.php");
	include_once("./../../disenoFormatos/InformeCubos.php");
	include_once("./../../disenoFormatos/CCH.php");
	include_once("./../../disenoFormatos/EnsayoCuboPDF.php");
	include_once("./../../disenoFormatos/EnsayoCilindroPDF.php");
	include_once("./../../disenoFormatos/InformeRevenimiento.php");
	include_once("./../../configSystem.php"); 
	include_once("./../../usuario/Usuario.php");
	include_once("./../../FPDF/fpdf.php");

	class ValidatorTextPDF{


		/*
			Funcion que valida las celdas que comparten todos los formatos(header)
		*/
		function validatedInfo($campoFront,$string){
			switch ($campoFront) {
					case 'informeNo';
							$campo = 'tam_informeText';
						break;
					case 'obra';
							$campo = 'tam_nomObraText';
						break;
					case 'localizacion';
							$campo = 'tam_localizacionText';
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
					
						
			/*
				//Mostramos los arrays

			echo "Cubos";
			print_r($arrayInfoCubos);
			echo "CILINDROS";
			print_r($arrayInfoCilindros);
			echo "VIGAS";
			print_r($arrayinfoVigas);
			echo "REVENIMIENTO";
			print_r($arrayinfoRev);
			
			*/
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



			
			$pdf = new fpdf();
			$pdf->AddPage();
			$pdf->SetFont('Arial','',$arr[$posicion][$tam_font]);
			$tam_string = $pdf->GetStringWidth($string);

			if($tam_string <= $min){
				$arr = array('string' => $string,'tam_string' => $tam_string,'tam_campo' => $min,'estatus' => 'Texto valido.','error' => 0);
			}else{
				$new_string = $this->truncaCadena($arr[$posicion][$tam_font],$string,$min);
				$tam_new_string = $pdf->GetStringWidth($new_string);
				$arr = array('string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo' => $min,'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
			}

			return json_encode($arr);
			
			/*
				Mostramos el resultado

			echo "Valor minimo encontrado: ".$min;
			echo "Posicion: ".$posicion." Valor de campo:".$arr[$posicion][$campo];
			*/			
			


			
		}


		function validatedCamposCCH($campo,$string){
			//Instanciamos los formatos que usaremos
			$infoCubos = new InformeCubos();
			$infoCilindros = new InformeCilindros();
			$infoVigas = new InformeVigas();

			$cch = new CCH();

			$infoCubos->generateCellsCampos();
			$cch->generateCellsCampos();
			$infoCilindros->generateCellsCampos();
			$infoVigas->generateCellsInfo();


			//Generamos las celdas
			$arrayInfoCubos = $infoCubos->getCellsTables();
			$arrayCCH = $cch->getCellsTables();
			$arrayInfoCilindros = $infoCilindros->getCellsTables();
			$arrayInfoVigas = $infoVigas->getCellsInfo();

			


			//Generamos los campso de los detalles para validar las observaciones e instrumentos

			$infoCubos->generateCellsDetails();
			$cch->generateCellsDetails();
			$infoCilindros->generateCellsDetails();
			$infoVigas->generateCellsDetails();

			

			$arrayInfoCubos = array_merge($arrayInfoCubos, $infoCubos->getcellsDetails());
			$arrayCCH = array_merge($arrayCCH, $cch->getcellsDetails());
			$arrayInfoCilindros = array_merge($arrayInfoCilindros, $infoCilindros->getcellsDetails());
			$arrayInfoVigas = array_merge($arrayInfoVigas, $infoVigas->getcellsDetails());

			//Modificamos la medidas de la localizacion por estandarizacion en otros formatos
			$arrayInfoCubos['tam_elementoAncho'] = $arrayInfoCubos['tam_elementoAncho']*3;
			$arrayCCH['tam_elementoAncho'] = $arrayCCH['tam_elementoAncho']*3;
			$arrayInfoCilindros['tam_elementoAncho'] = $arrayInfoCilindros['tam_elementoAncho']*3;

			$band = 0;

			//Utilizamos la relacion que ya se ha hecho previamente
			switch ($campo) {
				case '2':
					$campo = 'tam_fprimaAncho';
					$band = 1;
					break;
				//Revenimiento de la obra
				case '3':
					$campo = 'tam_revAncho';
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
					$band = 1;
					break;
				case '11':
					$campo = 'tam_horaAncho';
					break;
				//Este es el revenimiento del proyecto
				case '13':
					$campo = 'tam_revAncho';
					break;
				case '14':
					$campo = 'tam_observacionAnchoTxt';
					break;
				case '15';
					$campo = 'tam_termo';
					break;
				case '16';
					$campo = 'tam_cono';
					break;
				case '17';
					$campo = 'tam_varilla';
					break;
				case '17';
					$campo = 'tam_flexometro';
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
						);

			//Buscamos el valor mas grande de la fuente para evitar errores en algun formato
			$tam_font_max = $arrayInfoCubos['tam_font_CellsRows'];
			for ($i=1; $i <sizeof($arrayAux) ; $i++){ 
				if($arrayAux[$i]['tam_font_CellsRows']>$tam_font_max){
					$tam_font_max = $arrayAux[$i]['tam_font_CellsRows'];
				}
			}

			//Comparamos con el tamaño de fuente de las vigas, que difiere por estar en el header
			if($tam_font_max < $arrayInfoVigas['tam_font_left'] && $band == 1){
				$tam_font_max = $arrayInfoVigas['tam_font_left'];
			}

			array_push($arrayAux,$arrayInfoVigas);

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

			//Verificamos el maximo tamaño de funete de los formatos
			if($arrayInfoCubos['tam_font_CellsRows'] < $arrayEnsayoCubo['tam_font_CellsRows']){
				$tam_font_max = $arrayEnsayoCubo['tam_font_CellsRows'];
			}else{
				$tam_font_max =$arrayInfoCubos['tam_font_CellsRows'];
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
			$infoCubos->generateCellsCampos();

		}

		/*
		function validatedCamposFinalCubos($campoFront,$string){
			//Instanciamos los formatos que usaremos
			$infoCubos = new InformeCubos();
			$cch = new CCH();
			$ensayoCubo = new EnsayoCuboPDF();

			$infoCubos->AddPage();
			$cch->AddPage();
			$ensayoCubo->AddPage();

			//Generamos las celdas
			$infoCubos->generateCellsCampos();
			$cch->generateCellsCampos();
			$ensayoCubo->generateArrayCampo();

			$arrayinfoCubos = $infoCubos->getCellsTables();
			$arraycch = $cch->getCellsTables();
			$arrayensayoCubo = $ensayoCubo->getArrayCampo();
		
			switch ($campoFront) {
				case '2':
					$campoFront = 'tam_fprimaAncho';
					$arr = $arraycch;
					break;
				case '3':
					$campoFront = 'tam_agregadoAncho';
					$arr = $arraycch;
					break;
				case '4':
					$campoFront = 'tam_volumenAncho';
					$arr = $arraycch;
					break;
				case '5':
					$campoFront = 'tam_concretoAncho';
					$arr = $arraycch;
					break;
				case '7':
					$campoFront = 'tam_unidadAncho';
					$arr = $arraycch;
					break;
				case '8':
					$campoFront = 'tam_muestreoAncho';
					$arr = $arraycch;
					break;
				case '9':
					$campoFront = 'tam_recoleccionAncho';
					$arr = $arraycch;
					break;
				case '10':
					$campoFront = 'tam_fechaAncho';
					$arr = $arraycch;
					break;
				//Tamaño del revenimiento en obra
				case '11':
					$campoFront = 'tam_revAncho';
					$arr = $arraycch;
					break;
				//-------Campos del Ensayo-------
				case '12':
					$campoFront = 'tam_resisCompresion';	//Esta es la carga
					$arr = $arrayensayoCubo;
					break;
				//-------Campos del formato final-------
				case '13':
					$campoFront = 'tam_resistenciaAncho';
					$arr = $arrayinfoCubos;
					break;
				case '14':
					$campoFront = 'tam_proyectoAncho';
					$arr = $arrayinfoCubos;
					break;
				case '15':
					$campoFront = 'tam_resis_compresionAncho';
					$arr = $arrayinfoCubos;
					break;
				case '16':
					$campoFront = 'tam_kgcmAncho';
					$arr = $arrayinfoCubos;
					break;
				case '17':
					$campoFront = 'tam_mpAncho';
					$arr = $arrayinfoCubos;
					break;
				case '18':
					$campoFront = 'tam_cargaAncho';
					$arr = $arrayinfoCubos;
					break;
				case '19':
					$campoFront = 'tam_kgAncho';
					$arr = $arrayinfoCubos;
					break;
				case '20':
					$campoFront = 'tam_kNAncho';
					$arr = $arrayinfoCubos;
					break;
				case '21':
					$campoFront = 'tam_areaAncho';
					$arr = $arrayinfoCubos;
					break;
				case '22':
					$campoFront = 'tam_lado2Ancho';
					$arr = $arrayinfoCubos;
					break;
				case '23':
					$campoFront = 'tam_lado1Ancho';
					$arr = $arrayinfoCubos;
					break;
				case '24':
					$campoFront = 'tam_edadAncho';
					$arr = $arrayinfoCubos;
					break;
				case '25':
					$campoFront = 'tam_revAncho';
					$arr = $arrayinfoCubos;
					break;
				case '26':
					$campoFront = 'tam_claveAncho';
					$arr = $arrayinfoCubos;
					break;
				//Es fecha de ensaye
				case '27':
					$campoFront = 'tam_fechaAncho';
					$arr = $arrayinfoCubos;
					break;
				case '28':
					$campoFront = 'tam_elementoAncho';
					$arr = $arrayinfoCubos;
					break;
				default:
						$arr = array('campoFront' => $campoFront,'estatus' => 'Error, no existe relacion con ese campo.','error' => 11);
						return json_encode($arr);
						break;
			}

			//Validamos el tamaño de fuente mas grande para verificar correctamente si en los campso entra la informacion
			if($arrayInfoCubos['tam_font_CellsRows']  < $arrayensayoCubo['tam_font_CellsRows'] < $arrayCCH['tam_font_CellsRows']){
				$tam_font = $arrayCCH['tam_font_CellsRows'];
			}
			else{
				if($arrayensayoCubo['tam_font_CellsRows'] < $arrayCCH['tam_font_CellsRows'] < $arrayInfoCubos['tam_font_CellsRows']){
					$tam_font = $arrayInfoCubos['tam_font_CellsRows'];
				}
				else{
					if($arrayCCH['tam_font_CellsRows'] < $arrayInfoCubos['tam_font_CellsRows'] < $arrayensayoCubo['tam_font_CellsRows']){
						$tam_font = $arrayInfoCubos['tam_font_CellsRows'];
					}
				}

				
			}

			//Instanciamos el nuevo pdf que realizara las opereaciones para validar las cadenas

			$pdf = new fpdf();

			$pdf->SetFont('Arial','',$tam_font);

			$tam_string = $pdf->GetStringWidth($string);

			if($tam_string <= $arr[$campoFront]){
				$arr = array('string' => $string,'tam_string' => $tam_string,'tam_campo' => $arr[$campoFront],'estatus' => 'Texto valido.','error' => 0);
			}else{
				$new_string = $this->truncaCadena($tam_font,$string,$arr[$campoFront]);
				$tam_new_string = $pdf->GetStringWidth($new_string);
				$arr = array('string' => $string,'tam_string' => $tam_string,'new_string' => $new_string,'tam_new_string' => $tam_new_string,'tam_campo' =>  $arr[$campoFront],'estatus' => 'El texto excedió el tamaño permitido.','error' => 100);
			}

			return json_encode($arr);
			
		}*/

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
