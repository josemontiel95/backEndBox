<?php
	include_once("./../../disenoFormatos/InformeCilindros.php");
	include_once("./../../disenoFormatos/InformeVigas.php");
	include_once("./../../disenoFormatos/InformeCubos.php");
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



			//Tomamos cualqueira de los objetos tipo (MYPDF) instanciados para hacer los calculos de dlas medidas
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

		function validatedCamposFinalCubos($campoFront,$string){
			//Instanciamos el objeto
			$pdf = new InformeCubos();
			$pdf->AddPage();

			//Generamos las celdas de los campos
			$pdf->generateCellsCampos();

			print_r($pdf->getCellsTables());

			


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
