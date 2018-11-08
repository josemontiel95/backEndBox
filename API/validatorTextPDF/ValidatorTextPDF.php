<?php
	include_once("./../../disenoFormatos/InformeCilindros.php");
	include_once("./../../disenoFormatos/InformeVigas.php");
	include_once("./../../disenoFormatos/InformeCubos.php");
	include_once("./../../disenoFormatos/InformeRevenimiento.php");
	include_once("./../../configSystem.php"); 
	include_once("./../../usuario/Usuario.php");

	class ValidatorTextPDF{

		function validatedInfo($token,$rol_usuario_id,$numCampo){
			global $dbS;
			$usuario = new Usuario();
			$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
			if($arr['error'] == 0){
				//echo $numCampo;
				switch ($numCampo) {
						case 1:
							$campo = 'tam_nomObraText';
							break;
						case 2:
							$campo = 'tam_localizacionText';
							break;
						case 3:
							$campo = 'tam_razonText';
							break;
						case 4:
							$campo = 'tam_dirClienteText';
							break;
						case 4:
							$campo = 'tam_informeText';
							break;
						default:
							$arr = array('numCampo' => $numCampo,'estatus' => 'Error, no existe relacion con ese numero','error' => 5);
							return json_encode($arr);
							break;
				}

						//Instanciamos los formatos
						$infoCubos = new InformeCubos();
						$infoCilindros = new InformeCilindros();
						$infoVigas = new InformeVigas();
						$infoRev = new InformeRevenimiento();

						$arrayInfoCubos = $infoCubos->generateCellsInfoForvalidation();
						$arrayInfoCilindros = $infoCilindros->generateCellsInfoForvalidation();
						$arrayinfoVigas = $infoVigas->generateCellsInfoForvalidation();
						$arrayinfoRev = $infoRev->generateCellsInfoForvalidation();

						//Asignamos arbitrariamente a uno que sera el mas "pequeño" hasta enontrar otro
						
						//Metemos los restantes en un arreglo que nos servira despues para hacer iteraciones

						//echo "Campo:".$campo."-".$arrayInfoCubos[$campo];
						
						$arr = array(
										$arrayInfoCubos,
										$arrayInfoCilindros,
										$arrayinfoVigas,
										$arrayinfoRev
									);
						echo "Cubos";
						print_r($arrayInfoCubos);
						echo "CILINDROS";
						print_r($arrayInfoCilindros);
						echo "VIGAS";
						print_r($arrayinfoVigas);
						echo "REVENIMIENTO";
						print_r($arrayinfoRev);

						echo "Valor dentro del array Campo:".$campo."-".$arr[0][$campo];
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
						
						echo "Valor minimo encontrado: ".$min;
						echo "Posicion: ".$posicion." Valor de campo:".$arr[$posicion][$campo];
						
						
						
			}
			else{
				return json_encode($arr);
			}
		}


		
	}

?>
