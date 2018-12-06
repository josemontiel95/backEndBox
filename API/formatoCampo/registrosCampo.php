<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class registrosCampo{
	private $formatoCampo_id;
	private $claveEspecimen;
	private $fecha;
	private $fprima;
	private $revProyecto;
	private $revObra;
	private $tamagregado;
	private $volumen;
	private $tipoConcreto;
	private $unidad;
	private $horaMuestreo;
	private $tempMuestreo;
	private $tempRecoleccion;
	private $localizacion;

	private $wc = '/1QQ/';

	public function numberToRomanRepresentation($number) {
	    $map = array('M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100, 'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9, 'V' => 5, 'IV' => 4, 'I' => 1);
	    $returnValue = '';
	    while ($number > 0) {
	        foreach ($map as $roman => $int) {
	            if($number >= $int) {
	                $number -= $int;
	                $returnValue .= $roman;
	                break;
	            }
	        }
	    }
	    return $returnValue;
	}


	public function initInsert($token,$rol_usuario_id,$formatoCampo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			/*Obtenemos las configuraciones globales del systema*/
			$var_system = $dbS->qarrayA(
				"	SELECT
						maxNoOfRegistrosCCH,
						multiplosNoOfRegistrosCCH_VIGAS,
						multiplosNoOfRegistrosCCH,
						maxNoOfRegistrosCCH_VIGAS
					FROM
						systemstatus
					ORDER BY id_systemstatus DESC;
				",array(),
				"SELECT -- registrosCampo :: initInsert : 1");
			if(!$dbS->didQuerydied && ($var_system != "empty")){
				/*Consultamos cuantos registros exiten actualemnte*/
				$rows = $dbS->qarrayA(
					"   SELECT
							COUNT(*) AS numRows
						FROM
							registrosCampo
						WHERE
							formatoCampo_id = 1QQ
					"
					,
					array($formatoCampo_id),
					"SELECT -- registrosCampo :: initInsert : 2"
				);

				if($dbS->didQuerydied || ($rows=="empty")){
					$dbS->rollbackTransaction();
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 20);
					return json_encode($arr);
				}
				/*Obtenemos el tipo del formato para tratar diferente a las Vigas que a los Cubos y cilindros*/
				$tipo = $dbS->qarrayA(
					"SELECT
						tipo,
						ordenDeTrabajo_id
					FROM
						formatoCampo
					WHERE
						id_formatoCampo = 1QQ
					"
					,
					array($formatoCampo_id),
					"SELECT -- registrosCampo :: initInsert : 3"
				);
				if($dbS->didQuerydied || ($tipo=="empty")){
					$dbS->rollbackTransaction();
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 20);
					return json_encode($arr);
				}
				
				/*Obtenemos los valores de configuracion global acorde a si es viga o cubo/cilindro*/

				$NoDeRegistros;
				$tipoNo;
				if($tipo['tipo'] == "VIGAS"){
					$tipoNo= 4;
					$consecutivoTipo="consecutivoDocumentosCCH_VIGA";
					$consecutivoProbetaTipo="consecutivoProbetaCCH_VIGA";
					$NoDeRegistros = $var_system['multiplosNoOfRegistrosCCH_VIGAS'];
					$maxNoOfRegistrosCCH=(int)($var_system['maxNoOfRegistrosCCH_VIGAS']);
				}else if($tipo['tipo'] == "CILINDRO"){
					$tipoNo= 2;
					$consecutivoTipo="consecutivoDocumentosCCH_CILINDRO";
					$consecutivoProbetaTipo="consecutivoProbetaCCH_CILINDRO";
					$NoDeRegistros = $var_system['multiplosNoOfRegistrosCCH'];
					$maxNoOfRegistrosCCH=(int)($var_system['maxNoOfRegistrosCCH']);
				}else if($tipo['tipo'] == "CUBO"){
					$tipoNo= 3;
					$consecutivoTipo="consecutivoDocumentosCCH_CUBO";
					$consecutivoProbetaTipo="consecutivoProbetaCCH_CUBO";
					$NoDeRegistros = $var_system['multiplosNoOfRegistrosCCH'];
					$maxNoOfRegistrosCCH=(int)($var_system['maxNoOfRegistrosCCH']);
				}else{
					$dbS->rollbackTransaction();
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, no se encontro el tipo de formato. Verifica que hayas seleccionado el tipo.','error' => 80);
					return json_encode($arr);
				}
				/*Se genera el identificador de documento en bas al tipo. */
					//codigo migrado desde formatoCampo y refactorizado para contemplar diferentes consecutivos y estilos.
				if($rows['numRows'] == 0){ // solo sucede en la incercion del primer grupo.
					$infoObra= $dbS->qarrayA(
						"SELECT 
							id_obra,
							cotizacion,
							consecutivoDocumentosCCH_VIGA,
							consecutivoDocumentosCCH_CILINDRO,
							consecutivoDocumentosCCH_CUBO,
							prefijo,
							YEAR(NOW()) AS anio
						FROM
							obra,
							(
								SELECT
									obra_id
								FROM
									ordenDeTrabajo
								WHERE
									id_ordenDeTrabajo = 1QQ
		
							)AS ordenDeTrabajo
						WHERE
							id_obra = ordenDeTrabajo.obra_id
						",
						array($tipo['ordenDeTrabajo_id']),
						"SELECT -- registrosCampo :: initInsert : 4"
					);
					if($dbS->didQuerydied || ($infoObra=="empty")){
						$dbS->rollbackTransaction();
						$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 200);
						return json_encode($arr);
					}
					$anio = $infoObra['anio'] - 2000;
					
					$infoNo = $infoObra['prefijo']."/".$infoObra['cotizacion']."/".$anio."/".$infoObra[$consecutivoTipo];
					
					$dbS->squery(
						"UPDATE formatoCampo SET
							informeNo ='1QQ'
						WHERE
							id_formatoCampo = 1QQ
							
						",array($infoNo,$formatoCampo_id)
						,"UPDATE  -- registrosCampo :: initInsert : 5"
					);
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 201);
						return json_encode($arr);
					}
					$dbS->squery(
						"UPDATE
							obra
						SET
							1QQ = 1QQ + 1
						WHERE
							id_obra = 1QQ
						",array($consecutivoTipo,$consecutivoTipo,$infoObra['id_obra'])
						,"UPDATE  -- registrosCampo :: initInsert : 6"
					);
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 202);
						return json_encode($arr);
					}
				}
				/*Fin de la generacion de identificador */


				/*Calculamos cuantos habria si incertamos los proximos n registros y el asignamos el grupo para modificaciones colectivas*/
				$numRows=$rows['numRows']+$NoDeRegistros;
				$grupo=(floor($rows['numRows']/$NoDeRegistros)+1);
				//Verifique que la consulta no devlviera empty, en cualquier caso que se consulte a un formato que no existe devuelve 0
				if(!$dbS->didQuerydied && $numRows<=$maxNoOfRegistrosCCH){
					//Obtenemos la informacion para generar la claveEspecimen
					$ids=array(); // arreglo de id_registrosCampo
					for($j=0;$j<$NoDeRegistros;$j++){
						$a= $dbS->qarrayA(
							"SELECT
								id_obra,
								revenimiento,
								prefijo,
								formatoCampo.tipo AS tipo,
								consecutivoProbetaCCH_VIGA,
								consecutivoProbetaCCH_CILINDRO,
								consecutivoProbetaCCH_CUBO,
								MONTH(NOW()) AS mes,
								DAY(NOW()) AS dia
							FROM
								ordenDeTrabajo,
								obra,
								formatoCampo
							WHERE
								id_obra = obra_id AND
								id_ordenDeTrabajo =  formatoCampo.ordenDeTrabajo_id AND
								id_formatoCampo = 1QQ
							",
							array($formatoCampo_id),
							"SELECT"
						);
						if(!$dbS->didQuerydied && !($a=="empty")){
							//Hacemos la clave
							$mes = $this->numberToRomanRepresentation($a['mes']);
							$remplazable = '@UnitIO@';
							$clave = $a['prefijo']."-".$mes."-".$a['dia']."-".$remplazable."-".$a[$consecutivoProbetaTipo];
							//Obtenemos los dias de ensaye para este formato.
							$b= $dbS->qarrayA(
								"SELECT 
									tipoConcreto,
									prueba1,
									prueba2,
									prueba3,
									prueba4
								FROM
									formatoCampo
								WHERE
									id_formatoCampo = 1QQ
								",
								array($formatoCampo_id),
								"SELECT"
							);
							if(!$dbS->didQuerydied && !($b=="empty")){
								//obtenemos datos de registros de CCH que tiene el systema.
								$c= $dbS->qAll(
									"SELECT 
										diasEnsaye,
										formatoCampo_id
									FROM
										registrosCampo
									WHERE
										active=1 AND	
										formatoCampo_id = 1QQ
									",
									array($formatoCampo_id),
									"SELECT"
								);
								if(!$dbS->didQuerydied && !($c=="empty")){
									/*Creamos arrego que contenga los dias de prueba para iterarlo despues*/
									$pruebas=array();
									$groupsOf4=$grupo;
									if($tipo['tipo'] == "VIGAS"){
										for($i=0;$i<$groupsOf4;$i++){
											array_push($pruebas,$b['prueba1']);
											array_push($pruebas,$b['prueba2']);
											array_push($pruebas,$b['prueba3']);
										}
									}else{
										for($i=0;$i<$groupsOf4;$i++){
											array_push($pruebas,$b['prueba1']);
											array_push($pruebas,$b['prueba2']);
											array_push($pruebas,$b['prueba3']);
											array_push($pruebas,$b['prueba4']);
										}
									}

									// Generaremos opciones para un numero n de grupos
									// Creamos el arreglo de opcines y agregamos el primer elemento que sera Pendiente.
									$opciones=array();
									foreach ($pruebas as $key => $value) { // iteramos las pruebas disponibles
										$flag=true;
										$keyAux;
										foreach ($c as $key2 => $value2) { // iteramos los registros ya existentes
											if((string)$value2['diasEnsaye'] === (string)($key+1)){ //comparamos el dia de ensaye guardado con la posicion actualmente iterada en pruebas +1
												$flag=false;
												$keyAux=$key2; // guardamos el key del registro que levanto la bandera para borrarlo despues.., que pasa si hay dos? solo guardo el ultimo.. esta bien esto?
												break;
											}
										}
										if($flag){ // Si ningun registro existente tiene este dia, lo meto a las opciones
											$opciones[ (string)(($key+1)) ] = $value;
										}else{ // Alguien tiene este dia, borro el registro para que no vuela a darme un doble positivo.
											unset($b[$keyAux]);
										}
									}
									$diasEnsaye=-1;
									$valueR=-1;
									foreach ($opciones as $key => $value) {
										$diasEnsaye=$key;
										$valueR=$value;
										break;
									}
									
									$dbS->squery(
										"INSERT INTO
											registrosCampo(claveEspecimen,formatoCampo_id, fecha, revProyecto,diasEnsaye,consecutivoProbeta,grupo)

										VALUES
											('1QQ',1QQ, CURDATE(),'1QQ','1QQ','1QQ','1QQ')
									",array($clave,$formatoCampo_id, $a['revenimiento'], $diasEnsaye,$a[$consecutivoProbetaTipo],$grupo),
									"INSERT -- RegistrosCampo :: initInsert : insert ragNo=".$j." diasEnsaye=".$diasEnsaye." valueR=".$valueR." count(pruebas)=".count($pruebas));
									if(!$dbS->didQuerydied){
										$id = $dbS->lastInsertedID;
										$dbS->squery(
											"   UPDATE 
													obra
												SET
													1QQ = 1QQ + 1

												WHERE
													 id_obra = 1QQ
											"
											,
											array($consecutivoProbetaTipo,$consecutivoProbetaTipo,$a['id_obra']),
											"UPDATE"
										);
										if(!$dbS->didQuerydied){
											array_push($ids,$id);
											//$dbS->commitTransaction();
											//$arr = array('id_registrosCampo' => $id,'token' => $token,	'estatus' => 'Exito en la insersion','error' => 0);
											//return json_encode($arr);
										}
										else{
											$dbS->rollbackTransaction();
											$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 11);
											return json_encode($arr);
										}
										
									}else{
										$dbS->rollbackTransaction();
										$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 10);
										return json_encode($arr);
									}
								}else{
									if($c=="empty"){
										$dbS->squery(
											"INSERT INTO
												registrosCampo(claveEspecimen,formatoCampo_id, fecha, revProyecto,diasEnsaye,consecutivoProbeta,grupo)

											VALUES
												('1QQ',1QQ, CURDATE(),'1QQ',1,'1QQ','1QQ')
										",array($clave,$formatoCampo_id, $a['revenimiento'],$a[$consecutivoProbetaTipo],$grupo),"INSERT");
										if(!$dbS->didQuerydied){
											$id = $dbS->lastInsertedID;
											$dbS->squery(
												"   UPDATE 
														obra
													SET
														1QQ = 1QQ + 1
	
													WHERE
														 id_obra = 1QQ
												"
												,
												array($consecutivoProbetaTipo,$consecutivoProbetaTipo,$a['id_obra']),
												"UPDATE"
											);
											if(!$dbS->didQuerydied){
												array_push($ids,$id);
												//$dbS->commitTransaction();
												//$arr = array('id_registrosCampo' => $id,'token' => $token,	'estatus' => 'Exito en la insersion','error' => 0);
												//return json_encode($arr);
											}
											else{
												$dbS->rollbackTransaction();
												$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 9);
												return json_encode($arr);
											}
										}else{
											$dbS->rollbackTransaction();
											$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 8);
											return json_encode($arr);
										}
									}else{
										$dbS->rollbackTransaction();
										$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 7);
										return json_encode($arr);
									}	
								}	
							}else{
								$dbS->rollbackTransaction();
								$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 6);
								return json_encode($arr);
							}
						}else{
							if($dbS->didQuerydied){
								$dbS->rollbackTransaction();
								if($a == "empty"){
									$arr = array('id_registrosCampo' => 'NULL','token' => $token,'estatus' => 'No se encontro formato con id_formatoCampo:'.$formatoCampo_id,'error' => 15);
									return json_encode($arr);
								}
								else{
									$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
									return json_encode($arr);
								}
							}else{
								$dbS->rollbackTransaction();
								$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 25);
								return json_encode($arr);
							}
						}
					}
					$dbS->commitTransaction();
					$arr = array('id_registrosCampo' => $ids[0],'token' => $token,	'estatus' => 'Exito en la insersion','error' => 0);
					return json_encode($arr);
				}else{
					if($dbS->didQuerydied){
						$dbS->rollbackTransaction();
						$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta de rows, verifica tus datos y vuelve a intentarlo','error' => 12);
						return json_encode($arr);
					}
					else{
						if($rows == "empty"){
							$dbS->rollbackTransaction();
							$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'No se encontraron registros asociados.','error' => 13);
							return json_encode($arr);
						}
						else{
							$dbS->rollbackTransaction();
							$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Se alcanzo el maximo de registros.','error' => 14, 'numRows' => $numRows,'gettype($numRows)' => gettype($numRows), 'maxNoOfRegistrosCCH' => $maxNoOfRegistrosCCH,'gettype($maxNoOfRegistrosCCH)' => gettype($maxNoOfRegistrosCCH));
							return json_encode($arr);
						}
						
					}
				}
			}
			else{
				if(($var_system != "empty")){
					$dbS->rollbackTransaction();
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'No se encontraron registros de la consulta a las variables del sistema','error' => 16);
					return json_encode($arr);
				}
				else{
					$dbS->rollbackTransaction();
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta de las variables del sistema, verifica tus datos y vuelve a intentarlo','error' => 17);
					return json_encode($arr);
				}
			}
		}
		$dbS->rollbackTransaction();
		return json_encode($arr);

	}
	
	public function insertRegistroJefaLaboratorio($token,$rol_usuario_id,$campo,$valor,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$info = $dbS->qarrayA(
				"SELECT
					rc.formatoCampo_id AS formatoCampo_id,
					rc.grupo AS grupo
				FROM
					registrosCampo AS rc
				WHERE
					rc.id_registrosCampo = 1QQ
				"
				,
				array($id_registrosCampo),
				"SELECT -- registrosCampo :: insertRegistroJefaLaboratorio : 1"
			);

			if($dbS->didQuerydied || $info == "empty"){
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 9);
				return json_encode($arr);
			}
			$isGroupProperty;
			switch ($campo) {
				case '1':
					$campo = 'herramienta_id';
					$isGroupProperty=false;
					break;
				case '2':
					$campo = 'fprima';
					$isGroupProperty=true;
					break;
				case '3':
					$campo = 'revObra';
					$isGroupProperty=true;
					break;
				case '4':
					$campo = 'tamAgregado';
					$isGroupProperty=true;
					break;
				case '5':
					$campo = 'volumen';
					$isGroupProperty=true;
					break;
				case '6':
					$campo = 'diasEnsaye';
					$isGroupProperty=false;
					break;
				case '7':
					$campo = 'unidad';
					$isGroupProperty=true;
					break;
				case '8':
					$campo = 'tempMuestreo';
					$isGroupProperty=true;
					break;
				case '9':
					$campo = 'tempRecoleccion';
					$isGroupProperty=true;
					break;
				case '10':
					$campo = 'localizacion';
					$isGroupProperty=true;
					break;
				case '11':
					$campo = 'horaMuestreo';
					$isGroupProperty=true;
					break;
				case '12':
					$campo = 'status';
					$isGroupProperty=true;
					break;
				case '13':
					$campo = 'revProyecto';
					$isGroupProperty=true;
					break;
			}
			if($campo=="status" && $valor=="3"){
				$tipo= $dbS->qvalue(
					"SELECT
						 formatoCampo.tipo AS tipo
					  FROM 
					  	registrosCampo INNER JOIN formatoCampo ON id_formatoCampo = formatoCampo_id
					  WHERE 
					  	id_registrosCampo = 1QQ
					  ", array($id_registrosCampo),
					  "SELECT -- RegistrosRev :: insertRegistroJefaLaboratorio : 2"
					  );
				if($dbS->didQuerydied || ($tipo=="empty")){
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' =>20);
					return json_encode($arr);
				}
				$table = "";
				switch($tipo){
					case "CILINDRO":
						$table="ensayoCilindro";
						$id="id_ensayoCilindro";
					break;
					case "CUBO":
						$table="ensayoCubo";
						$id="id_ensayoCubo";
					break;
					case "VIGAS":
						$table="ensayoViga";
						$id="id_ensayoViga";
					break;
				}
				$dbS->squery(
						"UPDATE
							1QQ
						SET
							pdfFinal = NULL
						WHERE
							registrosCampo_id = 1QQ

				",array($table,$id_registrosCampo),
				"UPDATE -- registrosCampo :: insertRegistroJefaLaboratorio : 3");
			}
			if($campo == 'herramienta_id'){
				$herramienta = $dbS->qarrayA(
									"
										SELECT
											id_herramienta,
											placas
										FROM
											herramientas
										WHERE
											id_herramienta = 1QQ
									"
									,
									array($valor),
									"SELECT -- registrosCampo :: insertRegistroJefaLaboratorio : 4 "
								);
				if(!$dbS->didQuerydied && $herramienta != "empty"){
					$a = $dbS->qarrayA(
								"
									SELECT
									id_obra,
									revenimiento, 
									prefijo,
									registrosCampo.consecutivoProbeta,
									MONTH(NOW()) AS mes,
									DAY(NOW()) AS dia
									FROM
										ordenDeTrabajo,
										obra,
										formatoCampo,
										registrosCampo
									WHERE
										id_obra = obra_id AND
										id_ordenDeTrabajo =  formatoCampo.ordenDeTrabajo_id AND
										id_formatoCampo = formatoCampo_id AND
										id_registrosCampo = 1QQ
								"
								,
								array($id_registrosCampo),
								"SELECT -- registrosCampo :: insertRegistroJefaLaboratorio : 5 "
							);
					if(!$dbS->didQuerydied && $herramienta != "empty"){
						$mes = $this->numberToRomanRepresentation($a['mes']);
						$placa;
						if($valor<1000){
							$placa = '@UnitIO@';
						}else{
							$placa = $herramienta['placas'];
						}
						$new_clave = $a['prefijo']."-".$mes."-".$a['dia']."-".$placa."-".$a['consecutivoProbeta'];
						$dbS->squery(
							"UPDATE
								registrosCampo
							SET
								claveEspecimen = '1QQ',
								1QQ = '1QQ'
							WHERE
								id_registrosCampo = 1QQ AND
								status > 1

						",array($new_clave,$campo,$valor,$id_registrosCampo),
						"UPDATE -- registrosCampo :: insertRegistroJefaLaboratorio :6");
						if(!$dbS->didQuerydied){
							$dbS->commitTransaction();
							$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => '¡Exito en la inserccion de un registro!','clave'=>$new_clave,'error' => 0);
							return json_encode($arr);
						}
						else{
							$dbS->rollbackTransaction();
							$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 8);
							return json_encode($arr);
						}
						
					}
					else{
						$dbS->rollbackTransaction();
						$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 7);
						return json_encode($arr);
					}
				}
				else{
					$dbS->rollbackTransaction();
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 6);
					return json_encode($arr);
				}	
			}
			if($isGroupProperty){
				$dbS->squery("
					UPDATE
						registrosCampo
					SET
						1QQ = '1QQ'
					WHERE
						formatoCampo_id = 1QQ AND 
						grupo = 1QQ AND
						status > 1

					",array($campo,$valor,$info['formatoCampo_id'],$info['grupo']),
					"UPDATE -- registrosCampo :: insertRegistroJefeBrigada"
				);
			}else{
				$dbS->squery("
					UPDATE
						registrosCampo
					SET
						1QQ = '1QQ'
					WHERE
						id_registrosCampo = 1QQ AND
						status > 1

					",array($campo,$valor,$id_registrosCampo),
					"UPDATE -- registrosCampo :: insertRegistroJefeBrigada"
				);
			}
			
			if(!$dbS->didQuerydied){
				$dbS->commitTransaction();
				$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => '¡Exito en la inserccion de un registro!','error' => 0);
				return json_encode($arr);
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	public function insertRegistroJefeBrigada($token,$rol_usuario_id,$campo,$valor,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			$info = $dbS->qarrayA("
				SELECT
					rc.formatoCampo_id AS formatoCampo_id,
					rc.grupo AS grupo
				FROM
					registrosCampo AS rc
				WHERE
					rc.id_registrosCampo = 1QQ
				"
				,
				array($id_registrosCampo),
				"SELECT -- registrosCampo :: insertRegistroJefeBrigada"
			);

			if($dbS->didQuerydied || $info == "empty"){
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 9);
				return json_encode($arr);
			}
			$isGroupProperty;
			switch ($campo) {
				case '1':
					$campo = 'herramienta_id';
					$isGroupProperty=false;
					break;
				case '2':
					$campo = 'fprima';
					$isGroupProperty=true;
					break;
				case '3':
					$campo = 'revObra';
					$isGroupProperty=true;
					break;
				case '4':
					$campo = 'tamAgregado';
					$isGroupProperty=true;
					break;
				case '5':
					$campo = 'volumen';
					$isGroupProperty=true;
					break;
				case '6':
					$campo = 'diasEnsaye';
					$isGroupProperty=false;
					break;
				case '7':
					$campo = 'unidad';
					$isGroupProperty=true;
					break;
				case '8':
					$campo = 'tempMuestreo';
					$isGroupProperty=true;
					break;
				case '9':
					$campo = 'tempRecoleccion';
					$isGroupProperty=true;
					break;
				case '10':
					$campo = 'localizacion';
					$isGroupProperty=true;
					break;
				case '11':
					$campo = 'horaMuestreo';
					$isGroupProperty=true;
					break;
				case '12':
					$campo = 'status';
					$isGroupProperty=true;
					break;
				case '13':
					$campo = 'revProyecto';
					$isGroupProperty=true;
					break;
			}
			if($campo == 'herramienta_id'){
				$herramienta = $dbS->qarrayA(
									"
										SELECT
											id_herramienta,
											placas
										FROM
											herramientas
										WHERE
											id_herramienta = 1QQ
									"
									,
									array($valor),
									"SELECT"
								);
				if(!$dbS->didQuerydied && $herramienta != "empty"){
					$a = $dbS->qarrayA(
								"
									SELECT
									id_obra,
									revenimiento, 
									prefijo,
									registrosCampo.consecutivoProbeta,
									MONTH(NOW()) AS mes,
									DAY(NOW()) AS dia
									FROM
										ordenDeTrabajo,
										obra,
										formatoCampo,
										registrosCampo
									WHERE
										id_obra = obra_id AND
										id_ordenDeTrabajo =  formatoCampo.ordenDeTrabajo_id AND
										id_formatoCampo = formatoCampo_id AND
										id_registrosCampo = 1QQ
								"
								,
								array($id_registrosCampo),
								"SELECT -- registrosCampo :: insertRegistroJefeBrigada"
							);
					if(!$dbS->didQuerydied && $herramienta != "empty"){
						$mes = $this->numberToRomanRepresentation($a['mes']);
						$placa;
						if($valor<1000){
							$placa = '@UnitIO@';
						}else{
							$placa = $herramienta['placas'];
						}
						$new_clave = $a['prefijo']."-".$mes."-".$a['dia']."-".$placa."-".$a['consecutivoProbeta'];
						$dbS->squery("
							UPDATE
								registrosCampo
							SET
								claveEspecimen = '1QQ',
								1QQ = '1QQ'
							WHERE
								id_registrosCampo = 1QQ AND
								status < 2

						",array($new_clave,$campo,$valor,$id_registrosCampo),
						"UPDATE -- registrosCampo :: insertRegistroJefeBrigada");
						if(!$dbS->didQuerydied){
							$dbS->commitTransaction();
							$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => '¡Exito en la inserccion de un registro!','clave'=>$new_clave,'error' => 0);
							return json_encode($arr);
						}
						else{
							$dbS->rollbackTransaction();
							$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 8);
							return json_encode($arr);
						}
						
					}
					else{
						$dbS->rollbackTransaction();
						$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 7);
						return json_encode($arr);
					}
				}
				else{
					$dbS->rollbackTransaction();
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 6);
					return json_encode($arr);
				}	
			}
			if($isGroupProperty){
				$dbS->squery("
					UPDATE
						registrosCampo
					SET
						1QQ = '1QQ'
					WHERE
						formatoCampo_id = 1QQ AND 
						grupo = 1QQ AND
						status < 2

					",array($campo,$valor,$info['formatoCampo_id'],$info['grupo']),
					"UPDATE -- registrosCampo :: insertRegistroJefeBrigada"
				);
			}else{
				$dbS->squery("
					UPDATE
						registrosCampo
					SET
						1QQ = '1QQ'
					WHERE
						id_registrosCampo = 1QQ AND
						status < 2

					",array($campo,$valor,$id_registrosCampo),
					"UPDATE -- registrosCampo :: insertRegistroJefeBrigada"
				);
			}
			
			if(!$dbS->didQuerydied){
				$dbS->commitTransaction();
				$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => '¡Exito en la inserccion de un registro!','error' => 0);
				return json_encode($arr);
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
				return json_encode($arr);
			}
		}
		return json_encode($arr);
	}

	public function getRegistrosByID($token,$rol_usuario_id,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$a= $dbS->qarrayA("
				SELECT
					rc.id_registrosCampo,
					rc.formatoCampo_id,
					rc.claveEspecimen,
					rc.fecha,
					rc.fprima,
					rc.revProyecto,
					rc.revObra,
					rc.tamagregado,
					rc.volumen,
					rc.diasEnsaye,
					rc.unidad,
					rc.horaMuestreo,
					rc.tempMuestreo,
					rc.tempRecoleccion,
					rc.localizacion,
					rc.status,
					rc.herramienta_id,
					rc.grupo,
					fc.tipo
				FROM 
					registrosCampo AS rc,
					formatoCampo AS fc
				WHERE 
					rc.formatoCampo_id  = fc.id_formatoCampo AND
					rc.active = 1 AND
					id_registrosCampo = 1QQ
				",
				array($id_registrosCampo),
				"SELECT"
			);
			if($dbS->didQuerydied || $s=="empty"){
				$arr = array('No existen registro relacionados con el id_registrosCampo'=>$id_registrosCampo,'error' => 7);
				return json_encode($arr);
			}
			$s= $dbS->qAll("
				SELECT
					rc.id_registrosCampo,
					rc.formatoCampo_id,
					rc.claveEspecimen,
					rc.consecutivoProbeta,
					rc.herramienta_id,
					rc.diasEnsaye,
					h.placas
				FROM 
					registrosCampo AS rc
					LEFT JOIN
					herramientas AS h
					ON rc.herramienta_id = h.id_herramienta
				WHERE 
					rc.formatoCampo_id = '1QQ' AND
					rc.grupo = '1QQ'
				ORDER BY rc.id_registrosCampo ASC
				",
				array($a['formatoCampo_id'],$a['grupo']),
				"SELECT"
			);
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_registrosCampo'=>$id_registrosCampo,'error' => 5);
				}
				else{
					//$s = json_encode($s);
					$arr = array(
						'groupMembers' => $s,
						'id_registrosCampo' => $a['id_registrosCampo'],
						'formatoCampo_id' => $a['formatoCampo_id'],
						'claveEspecimen' => $a['claveEspecimen'],
						'fecha' => $a['fecha'],
						'fprima' => $a['fprima'],
						'revProyecto' => $a['revProyecto'],
						'revObra' => $a['revObra'],
						'tamagregado' => $a['tamagregado'],
						'volumen' => $a['volumen'],
						'diasEnsaye' => $a['diasEnsaye'],
						'unidad' => $a['unidad'],
						'horaMuestreo' => $a['horaMuestreo'],
						'tempMuestreo' => $a['tempMuestreo'],
						'tempRecoleccion' => $a['tempRecoleccion'],
						'localizacion' => $a['localizacion'],
						'status' => $a['status'],
						'herramienta_ids' => $a['herramienta_ids'],
						'tipo' => $a['tipo']

					);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getRegistrosByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}



	/*
		Obtienes todos los registros relacionados con un formato de Campo
	*/
	public function getAllRegistrosByID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$var_system = $dbS->qarrayA(
				"	SELECT
						maxNoOfRegistrosCCH,
						multiplosNoOfRegistrosCCH_VIGAS,
						multiplosNoOfRegistrosCCH,
						maxNoOfRegistrosCCH_VIGAS
					FROM
						systemstatus
					ORDER BY id_systemstatus DESC;
				",array(),"SELECT");
			if($dbS->didQuerydied || ($var_system == "empty")){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 7);
				return json_encode($arr);
			}
			$tipo = $dbS->qarrayA(
				"   SELECT
						tipo
					FROM
						formatoCampo
					WHERE
						id_formatoCampo = 1QQ
				"
				,
				array($id_formatoCampo),
				"SELECT"
			);
			if($dbS->didQuerydied || ($tipo=="empty")){
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 8);
				return json_encode($arr);
			}
			if($tipo['tipo']=="VIGAS"){
				$s= $dbS->qAll(
					"SELECT
				      	rc.id_registrosCampo,
						rc.formatoCampo_id,
				        rc.claveEspecimen,
						rc.fecha,
						rc.fprima,
						rc.revProyecto,
						rc.revObra,
						rc.tamagregado,
						rc.volumen,
						rc.unidad,
						rc.horaMuestreo,
						rc.tempMuestreo,
						rc.tempRecoleccion,
						rc.localizacion,
						rc.status,
						rc.herramienta_id,
						CASE
							WHEN MOD(rc.diasEnsaye,3) = 1 THEN fc.prueba1  
							WHEN MOD(rc.diasEnsaye,3) = 2 THEN fc.prueba2  
							WHEN MOD(rc.diasEnsaye,3) = 0 THEN fc.prueba3  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye,
						rc.footerEnsayo_id,
						id_ensayoViga AS id_ensayo,
						IF (id_ensayoViga IS NOT NULL, 1,0) AS ensayadoNo,
						IF (id_ensayoViga IS NOT NULL, 'Si','No') AS ensayado,
						fc.tipo AS tipo,
						ensayoViga.status AS statusEnsayo,
						pdfFinal,
						jefaLabApproval_id,
						CASE 
							WHEN ensayoViga.status IS NULL THEN 'Ensayo Pendiente'
							WHEN ensayoViga.status = 0 THEN 'Ensayo Pendiente'
							WHEN ensayoViga.status <> 0 AND pdfFinal IS NULL THEN 'Ensayo Terminado'
							WHEN ensayoViga.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NULL THEN 'PDF Generado'
							WHEN ensayoViga.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NOT NULL THEN 'Autorizado'
							ELSE 'Error, contacte a soporte'
						END AS statusGeneral,
						CASE 
							WHEN ensayoViga.status IS NULL THEN 0
							WHEN ensayoViga.status = 0 THEN 0
							WHEN ensayoViga.status <> 0 AND pdfFinal IS NULL THEN 0
							WHEN ensayoViga.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NULL THEN 0
							WHEN ensayoViga.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NOT NULL THEN 1
							ELSE -1
						END AS statusGeneralNo,
						CASE
							WHEN MOD(rc.diasEnsaye,3) = 1 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba1 DAY)
							WHEN MOD(rc.diasEnsaye,3) = 2 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba2 DAY)  
							WHEN MOD(rc.diasEnsaye,3) = 0 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba3 DAY)  
							ELSE 'Error, Contacta a soporte'
						END AS fechaEnsayeAsignado  
					FROM 
				      	registrosCampo AS rc LEFT JOIN ensayoViga ON registrosCampo_id = id_registrosCampo,
				      	formatoCampo AS fc
				      WHERE 
				      	rc.formatoCampo_id= fc.id_formatoCampo AND
				      	rc.active = 1 AND
				      	rc.formatoCampo_id = 1QQ
				      ",
					array($id_formatoCampo),
					"SELECT"
			    );
			}else if($tipo['tipo']=="CILINDRO"){
				$s= $dbS->qAll(
					" SELECT
				      	rc.id_registrosCampo,
						rc.formatoCampo_id,
				        rc.claveEspecimen,
						rc.fecha,
						rc.fprima,
						rc.revProyecto,
						rc.revObra,
						rc.tamagregado,
						rc.volumen,
						rc.unidad,
						rc.horaMuestreo,
						rc.tempMuestreo,
						rc.tempRecoleccion,
						rc.localizacion,
						rc.status,
						rc.herramienta_id,
						CASE
							WHEN MOD(rc.diasEnsaye,4) = 1 THEN fc.prueba1  
							WHEN MOD(rc.diasEnsaye,4) = 2 THEN fc.prueba2  
							WHEN MOD(rc.diasEnsaye,4) = 3 THEN fc.prueba3  
							WHEN MOD(rc.diasEnsaye,4) = 0 THEN fc.prueba4  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye,
						rc.footerEnsayo_id,
						id_ensayoCilindro AS id_ensayo,
						IF (id_ensayoCilindro IS NOT NULL, 1,0) AS ensayadoNo,
						IF (id_ensayoCilindro IS NOT NULL, 'Si','No') AS ensayado,
						fc.tipo AS tipo,
						ensayoCilindro.status AS statusEnsayo,
						pdfFinal,
						jefaLabApproval_id,
						CASE 
							WHEN ensayoCilindro.status IS NULL THEN 'Ensayo Pendiente'
							WHEN ensayoCilindro.status = 0 THEN 'Ensayo Pendiente'
							WHEN ensayoCilindro.status <> 0 AND pdfFinal IS NULL THEN 'Ensayo Terminado'
							WHEN ensayoCilindro.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NULL THEN 'PDF Generado'
							WHEN ensayoCilindro.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NOT NULL THEN 'Autorizado'
							ELSE 'Error, contacte a soporte'
						END AS statusGeneral,
						CASE 
							WHEN ensayoCilindro.status IS NULL THEN 0
							WHEN ensayoCilindro.status = 0 THEN 0
							WHEN ensayoCilindro.status <> 0 AND pdfFinal IS NULL THEN 0
							WHEN ensayoCilindro.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NULL THEN 0
							WHEN ensayoCilindro.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NOT NULL THEN 1
							ELSE -1
						END AS statusGeneralNo,
						CASE
							WHEN MOD(rc.diasEnsaye,4) = 1 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba1 DAY)
							WHEN MOD(rc.diasEnsaye,4) = 2 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba2 DAY)  
							WHEN MOD(rc.diasEnsaye,4) = 3 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba3 DAY)  
							WHEN MOD(rc.diasEnsaye,4) = 0 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba4 DAY)  
							ELSE 'Error, Contacta a soporte'
						END AS fechaEnsayeAsignado 
					FROM 
						registrosCampo AS rc LEFT JOIN ensayoCilindro ON registrosCampo_id = id_registrosCampo,
				      	formatoCampo AS fc
				      WHERE 
				      	rc.formatoCampo_id= fc.id_formatoCampo AND
				      	rc.active = 1 AND
				      	rc.formatoCampo_id = 1QQ
				      ",
					array($id_formatoCampo),
					"SELECT"
			    );
			}else if($tipo['tipo']=="CUBO"){
				$s= $dbS->qAll(
					" SELECT
				      	rc.id_registrosCampo,
						rc.formatoCampo_id,
				        rc.claveEspecimen,
						rc.fecha,
						rc.fprima,
						rc.revProyecto,
						rc.revObra,
						rc.tamagregado,
						rc.volumen,
						rc.unidad,
						rc.horaMuestreo,
						rc.tempMuestreo,
						rc.tempRecoleccion,
						rc.localizacion,
						rc.status,
						rc.herramienta_id,
						CASE
							WHEN MOD(rc.diasEnsaye,4) = 1 THEN fc.prueba1  
							WHEN MOD(rc.diasEnsaye,4) = 2 THEN fc.prueba2  
							WHEN MOD(rc.diasEnsaye,4) = 3 THEN fc.prueba3  
							WHEN MOD(rc.diasEnsaye,4) = 0 THEN fc.prueba4  
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye,
						rc.footerEnsayo_id,
						id_ensayoCubo AS id_ensayo,
						IF (id_ensayoCubo IS NOT NULL, 1,0) AS ensayadoNo,
						IF (id_ensayoCubo IS NOT NULL, 'Si','No') AS ensayado,
						fc.tipo AS tipo,
						ensayoCubo.status AS statusEnsayo,
						pdfFinal,
						jefaLabApproval_id,
						CASE 
							WHEN ensayoCubo.status IS NULL THEN 'Ensayo Pendiente'
							WHEN ensayoCubo.status = 0 THEN 'Ensayo Pendiente'
							WHEN ensayoCubo.status <> 0 AND pdfFinal IS NULL THEN 'Ensayo Terminado'
							WHEN ensayoCubo.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NULL THEN 'PDF Generado'
							WHEN ensayoCubo.status <> 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NOT NULL THEN 'Autorizado'
							ELSE 'Error, contacte a soporte'
						END AS statusGeneral,
						CASE 
							WHEN ensayoCubo.status IS NULL THEN 0
							WHEN ensayoCubo.status = 0 THEN 0
							WHEN ensayoCubo.status = 0 AND pdfFinal IS NULL THEN 0
							WHEN ensayoCubo.status = 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NULL THEN 0
							WHEN ensayoCubo.status = 0 AND pdfFinal IS NOT NULL AND jefaLabApproval_id IS NOT NULL THEN 1
							ELSE -1
						END AS statusGeneralNo,
						CASE
							WHEN MOD(rc.diasEnsaye,4) = 1 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba1 DAY)
							WHEN MOD(rc.diasEnsaye,4) = 2 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba2 DAY)  
							WHEN MOD(rc.diasEnsaye,4) = 3 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba3 DAY)  
							WHEN MOD(rc.diasEnsaye,4) = 0 THEN DATE_ADD(rc.fecha, INTERVAL fc.prueba4 DAY)  
							ELSE 'Error, Contacta a soporte'
						END AS fechaEnsayeAsignado 
					FROM 
						registrosCampo AS rc LEFT JOIN ensayoCubo ON registrosCampo_id = id_registrosCampo,
				      	formatoCampo AS fc
				      WHERE 
				      	rc.formatoCampo_id= fc.id_formatoCampo AND
				      	rc.active = 1 AND
				      	rc.formatoCampo_id = 1QQ
				      ",
					array($id_formatoCampo),
					"SELECT"
			    );
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getAllRegistrosByID , verifica tus datos y vuelve a intentarlo','error' => 20);
				return json_encode($arr);
			}
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_formatoCampo'=>$id_formatoCampo,'error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);

	}

	public function deactivate($token,$rol_usuario_id,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							registrosCampo
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_registrosCampo = 1QQ
					 "
					,array(0,$id_registrosCampo),"UPDATE"
			      	);
		//PENDIENTE por la herramienta_tipo_id para poderla imprimir tengo que cargar las variables de la base de datos?
			if(!$dbS->didQuerydied){
				$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => 'Registro se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}

		}
		return json_encode($arr);
	}

	public function completeRegistro($token,$rol_usuario_id,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
								registrosCampo
							SET
								status = 1
							WHERE
								active = 1 AND
								id_registrosCampo = 1QQ
					 "
					,array($id_registrosCampo),"UPDATE"
			      	);
			$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => 'Exito Registro completado','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en completar Registro , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}
	public function ping($data){
		return $data;
	}

	public function getRegistrosForToday($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorioUser=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$a= $dbS->qAll(
				"SELECT 
					* 
				FROM 
					(
					SELECT 
						id_registrosCampo,
						fecha,
						informeNo,
						claveEspecimen,
						MOD(diasEnsaye,4) AS W,
						id_ordenDeTrabajo,
						laboratorio_id,
						tipo,
						registrosCampo.active,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						CASE
							WHEN MOD(diasEnsaye,4) = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN MOD(diasEnsaye,4) = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							WHEN MOD(diasEnsaye,4) = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN MOD(diasEnsaye,4) = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							WHEN MOD(diasEnsaye,4) = 3 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN MOD(diasEnsaye,4) = 3 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							WHEN MOD(diasEnsaye,4) = 0 AND DATE_ADD(fecha, INTERVAL prueba4 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN MOD(diasEnsaye,4) = 0 AND DATE_ADD(fecha, INTERVAL prueba4 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							ELSE 'Error, Contacta a soporte'
						END AS estado,
						CASE
							WHEN MOD(diasEnsaye,4) = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) < CURDATE() THEN 2
							WHEN MOD(diasEnsaye,4) = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) = CURDATE() THEN 1
							WHEN MOD(diasEnsaye,4) = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) < CURDATE() THEN 2
							WHEN MOD(diasEnsaye,4) = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) = CURDATE() THEN 1
							WHEN MOD(diasEnsaye,4) = 3 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) < CURDATE() THEN 2
							WHEN MOD(diasEnsaye,4) = 3 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) = CURDATE() THEN 1
							WHEN MOD(diasEnsaye,4) = 0 AND DATE_ADD(fecha, INTERVAL prueba4 DAY) < CURDATE() THEN 2
							WHEN MOD(diasEnsaye,4) = 0 AND DATE_ADD(fecha, INTERVAL prueba4 DAY) = CURDATE() THEN 1
							ELSE 'Error, Contacta a soporte'
						END AS color,
						CASE
							WHEN MOD(diasEnsaye,4) = 1 THEN prueba1  
							WHEN MOD(diasEnsaye,4) = 1 THEN prueba1
							WHEN MOD(diasEnsaye,4) = 2 THEN prueba2  
							WHEN MOD(diasEnsaye,4) = 2 THEN prueba2
							WHEN MOD(diasEnsaye,4) = 3 THEN prueba3  
							WHEN MOD(diasEnsaye,4) = 3 THEN prueba3
							WHEN MOD(diasEnsaye,4) = 0 THEN prueba4  
							WHEN MOD(diasEnsaye,4) = 0 THEN prueba4
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye,
						CASE
							WHEN MOD(diasEnsaye,4) = 1 THEN DATE_ADD(fecha, INTERVAL prueba1 DAY)
							WHEN MOD(diasEnsaye,4) = 1 THEN DATE_ADD(fecha, INTERVAL prueba1 DAY)
							WHEN MOD(diasEnsaye,4) = 2 THEN DATE_ADD(fecha, INTERVAL prueba2 DAY)  
							WHEN MOD(diasEnsaye,4) = 2 THEN DATE_ADD(fecha, INTERVAL prueba2 DAY)
							WHEN MOD(diasEnsaye,4) = 3 THEN DATE_ADD(fecha, INTERVAL prueba3 DAY)  
							WHEN MOD(diasEnsaye,4) = 3 THEN DATE_ADD(fecha, INTERVAL prueba3 DAY)
							WHEN MOD(diasEnsaye,4) = 0 THEN DATE_ADD(fecha, INTERVAL prueba4 DAY)  
							WHEN MOD(diasEnsaye,4) = 0 THEN DATE_ADD(fecha, INTERVAL prueba4 DAY)
							ELSE 'Error, Contacta a soporte'
						END AS fechaEnsayeAsignado  
					FROM
						registrosCampo,formatoCampo,ordenDeTrabajo
					WHERE
						tipo <> 'VIGAS' AND 
						registrosCampo.footerEnsayo_id IS NULL AND
						id_formatoCampo = formatoCampo_id AND
						id_ordenDeTrabajo = ordenDeTrabajo_id AND
						(registrosCampo.status > 1) AND
						(formatoCampo.status > 0) AND
						laboratorio_id = 1QQ
					) AS T1
				WHERE
					DATE_ADD(fecha, INTERVAL diasEnsaye DAY) <= CURDATE()
				",
				array($usuario->laboratorio_id),
				"SELECT --registrosCampo :: getRegistrosForToday : 1"
			);
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 7);
				return json_encode($arr);
			}
			$b= $dbS->qAll(
				"SELECT 
					* 
				FROM 
					(
					SELECT 
						id_registrosCampo,
						fecha,
						informeNo,
						claveEspecimen,
						MOD(diasEnsaye,4) AS W,
						id_ordenDeTrabajo,
						laboratorio_id,
						tipo,
						registrosCampo.active,
						IF(registrosCampo.status = 3,'SI','NO') AS completado,
						CASE
							WHEN MOD(diasEnsaye,3) = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN MOD(diasEnsaye,3) = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							WHEN MOD(diasEnsaye,3) = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN MOD(diasEnsaye,3) = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							WHEN MOD(diasEnsaye,3) = 0 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN MOD(diasEnsaye,3) = 0 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							ELSE 'Error, Contacta a soporte'
						END AS estado,
						CASE
							WHEN MOD(diasEnsaye,3) = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) < CURDATE() THEN 2
							WHEN MOD(diasEnsaye,3) = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) = CURDATE() THEN 1
							WHEN MOD(diasEnsaye,3) = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) < CURDATE() THEN 2
							WHEN MOD(diasEnsaye,3) = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) = CURDATE() THEN 1
							WHEN MOD(diasEnsaye,3) = 0 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) < CURDATE() THEN 2
							WHEN MOD(diasEnsaye,3) = 0 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) = CURDATE() THEN 1
							ELSE 'Error, Contacta a soporte'
						END AS color,
						CASE
							WHEN MOD(diasEnsaye,3) = 1 THEN prueba1  
							WHEN MOD(diasEnsaye,3) = 1 THEN prueba1
							WHEN MOD(diasEnsaye,3) = 2 THEN prueba2  
							WHEN MOD(diasEnsaye,3) = 2 THEN prueba2
							WHEN MOD(diasEnsaye,3) = 0 THEN prueba3  
							WHEN MOD(diasEnsaye,3) = 0 THEN prueba3
							ELSE 'Error, Contacta a soporte'
						END AS diasEnsaye,
						CASE
							WHEN MOD(diasEnsaye,3) = 1 THEN DATE_ADD(fecha, INTERVAL prueba1 DAY)
							WHEN MOD(diasEnsaye,3) = 1 THEN DATE_ADD(fecha, INTERVAL prueba1 DAY)
							WHEN MOD(diasEnsaye,3) = 2 THEN DATE_ADD(fecha, INTERVAL prueba2 DAY)  
							WHEN MOD(diasEnsaye,3) = 2 THEN DATE_ADD(fecha, INTERVAL prueba2 DAY)
							WHEN MOD(diasEnsaye,3) = 0 THEN DATE_ADD(fecha, INTERVAL prueba3 DAY)  
							WHEN MOD(diasEnsaye,3) = 0 THEN DATE_ADD(fecha, INTERVAL prueba3 DAY)
							ELSE 'Error, Contacta a soporte'
						END AS fechaEnsayeAsignado  
					FROM
						registrosCampo,formatoCampo,ordenDeTrabajo
					WHERE
						tipo = 'VIGAS' AND 
						registrosCampo.footerEnsayo_id IS NULL AND
						id_formatoCampo = formatoCampo_id AND
						id_ordenDeTrabajo = ordenDeTrabajo_id AND
						(registrosCampo.status > 1) AND
						laboratorio_id = 1QQ
					) AS T1
				WHERE
					DATE_ADD(fecha, INTERVAL diasEnsaye DAY) <= CURDATE()
				",
				array($usuario->laboratorio_id),
				"SELECT --registrosCampo :: getRegistrosForToday : 2"
			);
			if(!$dbS->didQuerydied){
				if($a != "empty" && $b != "empty"){
					$arr = array_merge($a,$b);
				}
				else{
					if($a == "empty" && $b == "empty"){
						$arr = array('estatus' =>"No hay registros", 'error' => 5); 
					}
					else{
						if($b == "empty"){
							return json_encode($a);
						}
						else{
							return json_encode($b);
						}
					}
				}

			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);

	}
	//$token,$rol_usuario_id,++



	public function getDaysPruebasForCompletition($token,$rol_usuario_id,$id_formato){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			/*Obtenemos las configuraciones globales del systema*/
			$var_system = $dbS->qarrayA(
				"	SELECT
						maxNoOfRegistrosCCH,
						multiplosNoOfRegistrosCCH_VIGAS,
						multiplosNoOfRegistrosCCH,
						maxNoOfRegistrosCCH_VIGAS
					FROM
						systemstatus
					ORDER BY id_systemstatus DESC;
				",array(),"SELECT");

			if($dbS->didQuerydied || ($var_system=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 20);
				return json_encode($arr);
			}
			/*Consultamos cuantos registros exiten actualemnte*/
			$rows = $dbS->qarrayA(
				"
					SELECT
						COUNT(*) AS numRows
					FROM
						registrosCampo
					WHERE
						formatoCampo_id = 1QQ
				"
				,
				array($id_formato),
				"SELECT"
			);

			if($dbS->didQuerydied || ($rows=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 20);
				return json_encode($arr);
			}
			/*Obtenemos el tipo del formato para tratar diferente a las Vigas que a los Cubos y cilindros*/
			$tipo = $dbS->qarrayA(
				"
					SELECT
						tipo
					FROM
						formatoCampo
					WHERE
						id_formatoCampo = 1QQ
				"
				,
				array($id_formato),
				"SELECT"
			);
			if($dbS->didQuerydied || ($tipo=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 20);
				return json_encode($arr);
			}
			
			/*Obtenemos los valores de configuracion global acorde a si es viga o cubo/cilindro*/

			$NoDeRegistros;
			if($tipo['tipo'] == "VIGAS"){
				$NoDeRegistros = $var_system['multiplosNoOfRegistrosCCH_VIGAS'];
				$maxNoOfRegistrosCCH=(int)($var_system['maxNoOfRegistrosCCH_VIGAS']);
			}else{
				$NoDeRegistros = $var_system['multiplosNoOfRegistrosCCH'];
				$maxNoOfRegistrosCCH=(int)($var_system['maxNoOfRegistrosCCH']);
			}
			/*Calculamos cuantos habria si incertamos los proximos n registros y el asignamos el grupo para modificaciones colectivas*/
			$numRows=$rows['numRows']+$NoDeRegistros;
			$grupo=(floor($rows['numRows']/$NoDeRegistros)+1);

			$a= $dbS->qarrayA("
		      	SELECT 
					tipoConcreto,
					prueba1,
					prueba2,
					prueba3,
					prueba4
				FROM
					formatoCampo
				WHERE
					id_formatoCampo = 1QQ
				",
				array($id_formato),
				"SELECT"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				$b= $dbS->qAll("
			      	SELECT 
						diasEnsaye,
						formatoCampo_id
					FROM
						registrosCampo
					WHERE
						active=1 AND
						formatoCampo_id = 1QQ
					",
					array($id_formato),
					"SELECT"
				);
				if(!$dbS->didQuerydied && !($b=="empty")){
					$pruebas=array();
					$groupsOf4=$grupo;
					if($tipo['tipo'] == "VIGAS"){
						for($i=0;$i<$groupsOf4;$i++){
							array_push($pruebas,$a['prueba1']);
							array_push($pruebas,$a['prueba2']);
							array_push($pruebas,$a['prueba3']);
						}
					}else{
						for($i=0;$i<$groupsOf4;$i++){
							array_push($pruebas,$a['prueba1']);
							array_push($pruebas,$a['prueba2']);
							array_push($pruebas,$a['prueba3']);
							array_push($pruebas,$a['prueba4']);
						}
					}					
					$opciones=array("Pendiente"=> "Pendiente");
					foreach ($pruebas as $key => $value) {

						$opciones[ (string)(($key+1)) ] = $value;
					}
					$dbS->commitTransaction();
					return json_encode($opciones);
				}else{
					if($b=="empty"){
						$dbS->commitTransaction();
						return json_encode(array("Pendiente"=> "Pendiente","1"=>$a['prueba1'],"2"=>$a['prueba2'],"3"=>$a['prueba3'],"4"=>$a['prueba4']));
					}else{
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' =>null,	'estatus' => 'Error inesperado en la funcion getDaysPruebasForDropDown , verifica tus datos y vuelve a intentarlo','error' => 7);
						return json_encode($arr);
					}
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' =>null,	'estatus' => 'Error inesperado en la funcion getDaysPruebasForDropDown , verifica tus datos y vuelve a intentarlo','error' => 7);
				return json_encode($arr);
			}
		}
		$dbS->rollbackTransaction();
		return json_encode($arr);
	}

	public function getDaysPruebasForDropDown($token,$rol_usuario_id,$id_formato){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
			/*Obtenemos las configuraciones globales del systema*/
			$var_system = $dbS->qarrayA(
				"	SELECT
						maxNoOfRegistrosCCH,
						multiplosNoOfRegistrosCCH_VIGAS,
						multiplosNoOfRegistrosCCH,
						maxNoOfRegistrosCCH_VIGAS
					FROM
						systemstatus
					ORDER BY id_systemstatus DESC;
				",array(),"SELECT");

			if($dbS->didQuerydied || ($var_system=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 20);
				return json_encode($arr);
			}
			/*Consultamos cuantos registros exiten actualemnte*/
			$rows = $dbS->qarrayA(
				"
					SELECT
						COUNT(*) AS numRows
					FROM
						registrosCampo
					WHERE
						formatoCampo_id = 1QQ
				"
				,
				array($id_formato),
				"SELECT"
			);

			if($dbS->didQuerydied || ($rows=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 20);
				return json_encode($arr);
			}
			/*Obtenemos el tipo del formato para tratar diferente a las Vigas que a los Cubos y cilindros*/
			$tipo = $dbS->qarrayA(
				"
					SELECT
						tipo
					FROM
						formatoCampo
					WHERE
						id_formatoCampo = 1QQ
				"
				,
				array($id_formato),
				"SELECT"
			);
			if($dbS->didQuerydied || ($tipo=="empty")){
				$dbS->rollbackTransaction();
				$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 20);
				return json_encode($arr);
			}
			
			/*Obtenemos los valores de configuracion global acorde a si es viga o cubo/cilindro*/

			$NoDeRegistros;
			if($tipo['tipo'] == "VIGAS"){
				$NoDeRegistros = $var_system['multiplosNoOfRegistrosCCH_VIGAS'];
				$maxNoOfRegistrosCCH=(int)($var_system['maxNoOfRegistrosCCH_VIGAS']);
			}else{
				$NoDeRegistros = $var_system['multiplosNoOfRegistrosCCH'];
				$maxNoOfRegistrosCCH=(int)($var_system['maxNoOfRegistrosCCH']);
			}
			/*Calculamos cuantos habria si incertamos los proximos n registros y el asignamos el grupo para modificaciones colectivas*/
			$numRows=$rows['numRows']+$NoDeRegistros;
			$grupo=(floor($rows['numRows']/$NoDeRegistros)+1);

			$a= $dbS->qarrayA("
		      	SELECT 
					tipoConcreto,
					prueba1,
					prueba2,
					prueba3,
					prueba4
				FROM
					formatoCampo
				WHERE
					id_formatoCampo = 1QQ
				",
				array($id_formato),
				"SELECT"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				$b= $dbS->qAll("
			      	SELECT 
						diasEnsaye,
						formatoCampo_id
					FROM
						registrosCampo
					WHERE
						active=1 AND
						formatoCampo_id = 1QQ
					",
					array($id_formato),
					"SELECT"
				);
				if(!$dbS->didQuerydied && !($b=="empty")){
					$pruebas=array();
					$groupsOf4=$grupo;
					if($tipo['tipo'] == "VIGAS"){
						for($i=0;$i<$groupsOf4;$i++){
							array_push($pruebas,$a['prueba1']);
							array_push($pruebas,$a['prueba2']);
							array_push($pruebas,$a['prueba3']);
						}
					}else{
						for($i=0;$i<$groupsOf4;$i++){
							array_push($pruebas,$a['prueba1']);
							array_push($pruebas,$a['prueba2']);
							array_push($pruebas,$a['prueba3']);
							array_push($pruebas,$a['prueba4']);
						}
					}					
					$opciones=array("Pendiente"=> "Pendiente");
					foreach ($pruebas as $key => $value) {
						$flag=true;
						$keyAux;
						foreach ($b as $key2 => $value2) {
							if((string)$value2['diasEnsaye'] === (string)($key+1)){
								//echo "value2[diasEnsaye]: ".$value2['diasEnsaye']." key: ".$key;
								$flag=false;
								$keyAux=$key2;
								break;
							} 
						}
						if($flag){
							$opciones[ (string)(($key+1)) ] = $value;
						}else{
							unset($b[$keyAux]);
						}
					}
					$dbS->commitTransaction();
					return json_encode($opciones);
				}else{
					if($b=="empty"){
						$dbS->commitTransaction();
						return json_encode(array("Pendiente"=> "Pendiente","1"=>$a['prueba1'],"2"=>$a['prueba2'],"3"=>$a['prueba3'],"4"=>$a['prueba4']));
					}else{
						$dbS->rollbackTransaction();
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' =>null,	'estatus' => 'Error inesperado en la funcion getDaysPruebasForDropDown , verifica tus datos y vuelve a intentarlo','error' => 7);
						return json_encode($arr);
					}
				}
			}else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' =>null,	'estatus' => 'Error inesperado en la funcion getDaysPruebasForDropDown , verifica tus datos y vuelve a intentarlo','error' => 7);
				return json_encode($arr);
			}
		}
		$dbS->rollbackTransaction();
		return json_encode($arr);
	}

	public function getRegistrosForTodayByID($token,$rol_usuario_id,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorioUser=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      	SELECT 
						id_registrosCampo,
						fecha,
						informeNo,
						claveEspecimen,
						diasEnsaye,
						tipo
					FROM
						registrosCampo,formatoCampo
					WHERE
						id_formatoCampo = formatoCampo_id AND
						id_registrosCampo = 1QQ
			      ",
			      array($id_registrosCampo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No hay especimenes por ensayar'=>'NULL','error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);

	}
	

}
?>