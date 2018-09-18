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
			$var_system = $dbS->qarrayA(
										"	SELECT
												maxNoOfRegistrosCCH	
											FROM
												systemstatus
											ORDER BY id_systemstatus DESC;
										",array(),"SELECT");
			if(!$dbS->didQuerydied && ($var_system != "empty")){
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
						array($formatoCampo_id),
						"SELECT"
					);

				//Verifique que la consulta no devlviera empty, en cualquier caso que se consulte a un formato que no existe devuelve 0
				if(!$dbS->didQuerydied && $rows['numRows']<$var_system['maxNoOfRegistrosCCH']){
					//Obtenemos la informacion para generar la claveEspecimen
					$a= $dbS->qarrayA("
											SELECT
												id_obra,
												revenimiento, 
												prefijo,
												consecutivoProbeta,
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
							$clave = $a['prefijo']."-".$mes."-".$a['dia']."-".$remplazable."-".$a['consecutivoProbeta'];
							$b= $dbS->qarrayA("
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
								array($formatoCampo_id),
								"SELECT"
							);
							if(!$dbS->didQuerydied && !($b=="empty")){
								$c= $dbS->qAll("
							      	SELECT 
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

									$aux=0;
									foreach ($c as $row) {
										$row['diasEnsaye'];
										$aux++;
									}
									$pruebas=array($b['prueba1'],$b['prueba2'],$b['prueba3'],$b['prueba4']);
									$groupsOf4=(floor($aux/4)+1);
									$opciones=array("Pendiente"=> "Pendiente");
									for($i=0;$i<$groupsOf4;$i++){
										foreach ($pruebas as $key => $value) {
											$flag=true;
											$keyAux;
											foreach ($c as $key2 => $value2) {
												if((string)$value2['diasEnsaye'] === (string)($key+1)){
													//echo "value2[diasEnsaye]: ".$value2['diasEnsaye']." key: ".$key;
													$flag=false;
													$keyAux=$key2;
													break;
												} 
											}
											if($flag){
												$opciones[ (string)(($key+1)+(4*$i)) ] = $value;
											}else{
												unset($b[$keyAux]);
											}
										}
									}
									$diasEnsaye;
									foreach ($opciones as $key => $value) {
										$diasEnsaye=$key;
										break;
									}
									
									$dbS->squery("
										INSERT INTO
											registrosCampo(claveEspecimen,formatoCampo_id, fecha, revProyecto,diasEnsaye,consecutivoProbeta)

										VALUES
											('1QQ',1QQ, CURDATE(),'1QQ','1QQ','1QQ')
									",array($clave,$formatoCampo_id, $a['revenimiento'], $diasEnsaye,$a['consecutivoProbeta']),"INSERT");
									if(!$dbS->didQuerydied){
										$id = $dbS->lastInsertedID;
										$dbS->squery(
											"
												UPDATE 
													obra
												SET
													consecutivoProbeta = consecutivoProbeta+1

												WHERE
													 id_obra = 1QQ
											"
											,
											array($a['id_obra']),
											"UPDATE"
										);
										if(!$dbS->didQuerydied){
											$dbS->commitTransaction();
											$arr = array('id_registrosCampo' => $id,'token' => $token,	'estatus' => 'Exito en la insersion','error' => 0);
											return json_encode($arr);
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
										$dbS->squery("
											INSERT INTO
												registrosCampo(claveEspecimen,formatoCampo_id, fecha, revProyecto,diasEnsaye,consecutivoProbeta)

											VALUES
												('1QQ',1QQ, CURDATE(),'1QQ',1,'1QQ')
										",array($clave,$formatoCampo_id, $a['revenimiento'],$a['consecutivoProbeta']),"INSERT");
										if(!$dbS->didQuerydied){
											$id = $dbS->lastInsertedID;
											$dbS->squery(
												"
													UPDATE 
														obra
													SET
														consecutivoProbeta = consecutivoProbeta+1

													WHERE
														 id_obra = 1QQ
												"
												,
												array($a['id_obra']),
												"UPDATE"
											);
											if(!$dbS->didQuerydied){
												$dbS->commitTransaction();
												$arr = array('id_registrosCampo' => $id,'token' => $token,	'estatus' => 'Exito en la insersion','error' => 0);
												return json_encode($arr);
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
							$dbS->rollbackTransaction();
							if($a == "empty"){
								$arr = array('id_registrosCampo' => 'NULL','token' => $token,'estatus' => 'No se encontro formato con id_formatoCampo:'.$formatoCampo_id,'error' => 15);
								return json_encode($arr);
							}
							else{
								$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
								return json_encode($arr);
							}
							

						}
				}
				else{
					$dbS->rollbackTransaction();
					if($dbS->didQuerydied){
						$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la consulta de rows, verifica tus datos y vuelve a intentarlo','error' => 12);
						return json_encode($arr);
					}
					else{
						if($rows == "empty"){
							$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'No se encontraron registros asociados.','error' => 13);
							return json_encode($arr);
						}
						else{
							$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Se alcanzo el maximo de registros.','error' => 14);
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
	
	public function insertRegistroJefeBrigada($token,$rol_usuario_id,$campo,$valor,$id_registrosCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'herramienta_id';
					break;
				case '2':
					$campo = 'fprima';
					break;
				case '3':
					$campo = 'revObra';
					break;
				case '4':
					$campo = 'tamAgregado';
					break;
				case '5':
					$campo = 'volumen';
					break;
				case '6':
					$campo = 'diasEnsaye';
					break;
				case '7':
					$campo = 'unidad';
					break;
				case '8':
					$campo = 'tempMuestreo';
					break;
				case '9':
					$campo = 'tempRecoleccion';
					break;
				case '10':
					$campo = 'localizacion';
					break;
				case '11':
					$campo = 'horaMuestreo';
					break;
				case '12':
					$campo = 'status';
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
								"SELECT"
							);
					if(!$dbS->didQuerydied && $herramienta != "empty"){
						$mes = $this->numberToRomanRepresentation($a['mes']);
						$new_clave = $a['prefijo']."-".$mes."-".$a['dia']."-".$herramienta['placas']."-".$a['consecutivoProbeta'];
						$dbS->squery("
							UPDATE
								registrosCampo
							SET
								claveEspecimen = '1QQ',
								1QQ = '1QQ'
							WHERE
								id_registrosCampo = 1QQ AND
								status < 2

						",array($new_clave,$campo,$valor,$id_registrosCampo),"UPDATE");
						if(!$dbS->didQuerydied){
							$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => '¡Exito en la inserccion de un registro!','clave'=>$new_clave,'error' => 0);
							return json_encode($arr);
						}
						else{
							$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 8);
							return json_encode($arr);
						}
						
					}
					else{
						$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 7);
						return json_encode($arr);
					}
				}
				else{
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 6);
					return json_encode($arr);
				}	
			}
			$dbS->squery("
						UPDATE
							registrosCampo
						SET
							1QQ = '1QQ'
						WHERE
							id_registrosCampo = 1QQ AND
							status < 2

				",array($campo,$valor,$id_registrosCampo),"UPDATE");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if(!$dbS->didQuerydied){
				$arr = array('id_registrosCampo' => $id_registrosCampo,'estatus' => '¡Exito en la inserccion de un registro!','error' => 0);
				return json_encode($arr);
			}else{
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
			$s= $dbS->qarrayA("
			      SELECT
			      	id_registrosCampo,
					formatoCampo_id,
			        claveEspecimen,
					fecha,
					fprima,
					revProyecto,
					revObra,
					tamagregado,
					volumen,
					diasEnsaye,
					unidad,
					horaMuestreo,
					tempMuestreo,
					tempRecoleccion,
					localizacion,
					status,
					herramienta_id
			      FROM 
			      	registrosCampo
			      WHERE 
			      	registrosCampo.active = 1 AND
			      	id_registrosCampo = 1QQ
			      ",
			      array($id_registrosCampo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('No existen registro relacionados con el id_registrosCampo'=>$id_registrosCampo,'error' => 5);
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



	/*
		Obtienes todos los registros relacionados con un formato de Campo
	*/
	public function getAllRegistrosByID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
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
					END AS diasEnsaye     
				FROM 
			      	registrosCampo AS rc, 
			      	formatoCampo AS fc
			      WHERE 
			      	rc.formatoCampo_id= fc.id_formatoCampo AND
			      	rc.active = 1 AND
			      	rc.formatoCampo_id = 1QQ
			      ",
			      array($id_formatoCampo),
			      "SELECT"
			      );
			
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


	public function getRegistrosForToday($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$laboratorioUser=$usuario->laboratorio_id;
		if($arr['error'] == 0){
			$s= $dbS->qAll("
					SELECT 
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
	//$token,$rol_usuario_id,++

	public function getDaysPruebasForCompletition($token,$rol_usuario_id,$id_formato){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
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
				return json_encode(array("Pendiente"=> "Pendiente","1"=>$a['prueba1'],"2"=>$a['prueba2'],"3"=>$a['prueba3'],"4"=>$a['prueba4']));
			}else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => null,	'estatus' => 'Error inesperado en la funcion getDaysPruebasForCompletition , verifica tus datos y vuelve a intentarlo','error' => 6);
				return json_encode($arr);
			}
		}
		$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => null,	'estatus' => 'Error inesperado en la funcion getDaysPruebasForCompletition , verifica tus datos y vuelve a intentarlo','error' => 6);
		return json_encode($arr);

	}

	public function getDaysPruebasForDropDown($token,$rol_usuario_id,$id_formato){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		$dbS->beginTransaction();
		if($arr['error'] == 0){
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
					$aux=0;
					foreach ($b as $row) {
						$row['diasEnsaye'];
						$aux++;
					}
					$pruebas=array($a['prueba1'],$a['prueba2'],$a['prueba3'],$a['prueba4']);
					$groupsOf4=(ceil($aux/4));
					$opciones=array("Pendiente"=> "Pendiente");
					for($i=0;$i<$groupsOf4;$i++){
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
								$opciones[ (string)(($key+1)+(4*$i)) ] = $value;
							}else{
								unset($b[$keyAux]);
							}
						}
					}
					$dbS->commitTransaction();
					return json_encode($opciones);
				}else{
					$dbS->commitTransaction();
					return json_encode(array("Pendiente"=> "Pendiente","1"=>$a['prueba1'],"2"=>$a['prueba2'],"3"=>$a['prueba3'],"4"=>$a['prueba4']));
				}
			}
			else{
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
	
	

	/*

	public function insertAdmin($token,$rol_usuario_id,$claveEspecimen,$fecha,$fprima,$revProyecto,$revObra,$tamagregado,$volumen,$tipoConcreto,$unidad,$horaMuestreo,$tempMuestreo,$tempRecoleccion,$localizacion){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						registrosCampo(claveEspecimen,fecha,fprima,revProyecto,revObra,tamagregado,volumen,tipoConcreto,unidad,horaMuestreo,tempMuestreo,tempRecoleccion,localizacion)

						VALUES
						('1QQ','1QQ',1QQ,1QQ,1QQ,1QQ,1QQ,'1QQ','1QQ','1QQ',1QQ,1QQ,'1QQ')
				",array($formatoCampo_id,$claveEspecimen,$fecha,$fprima,$revProyecto,$revObra,$tamagregado,$volumen,$tipoConcreto,$unidad,$horaMuestreo,$tempMuestreo,$tempRecoleccion,$localizacion),"INSERT");
			$arr = array('estatus' => 'Exito en insercion', 'error' => 0);
			if($dbS->didQuerydied){
					$arr = array('token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function upDateAdmin($token,$rol_usuario_id,$id_cliente,$rfc,$razonSocial,$nombre,$direccion,$email,$telefono,$nombreContacto,$telefonoDeContacto){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							cliente
						SET
							rfc ='1QQ',
							razonSocial = '1QQ',
							nombre = '1QQ',
							direccion = '1QQ',
							email = '1QQ',
							telefono ='1QQ',
							nombreContacto = '1QQ', 
							telefonoDeContacto = '1QQ'
						WHERE
							active=1 AND
							id_cliente = 1QQ
					 "
					,array($rfc,$razonSocial,$nombre,$direccion,$email,$telefono,$nombreContacto,$telefonoDeContacto,$id_cliente),"UPDATE"
			      	);
			$arr = array('estatus' => 'Exito de actualizacion','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);

	}

	public function getAllAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_cliente,
					rfc,
					razonSocial,
					nombre,
					direccion,
					email,
					foto,
					telefono,
					nombreContacto,
					telefonoDeContacto,
					createdON,
					lastEditedON,
					IF(active = 1,'Si','No') AS active
			      FROM 
			        cliente
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
						if(count($arr) == 0)
							$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}

	*/


	




}
?>