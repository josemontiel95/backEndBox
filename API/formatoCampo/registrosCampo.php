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


	public function initInsert($token,$rol_usuario_id,$formatoCampo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$a= $dbS->qarrayA("
		      	SELECT 
		      		id_obra,
					revenimiento
				FROM
					formatoCampo, ordenDeTrabajo, obra
				WHERE
					ordenDeTrabajo_id=id_ordenDeTrabajo AND
					obra_id = id_obra AND 
					id_formatoCampo = 1QQ
				",
				array($formatoCampo_id),
				"SELECT"
			);
			if(!$dbS->didQuerydied && !$a=="empty"){
				$dbS->squery("
							INSERT INTO
								registrosCampo(formatoCampo_id, fecha)

							VALUES
								(1QQ, CURDATE(), )
					",array($formatoCampo_id),"INSERT");
				if(!$dbS->didQuerydied){
					$id=$dbS->lastInsertedID;
					$arr = array('id_registrosCampo' => $id,'estatus' => '¡Exito en la inicializacion','error' => 0);
						return json_encode($arr);
				}else{
					$arr = array('id_registrosCampo' => 'NULL','token' => $token,	'estatus' => 'Error en la insersion, verifica tus datos y vuelve a intentarlo','error' => 5);
					return json_encode($arr);
				}
			}else{
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
		if($arr['error'] == 0){
			switch ($campo) {
				case '1':
					$campo = 'claveEspecimen';
					break;
				case '2':
					$campo = 'fecha';
					break;
				case '3':
					$campo = 'fprima';
					break;
				case '4':
					$campo = 'revProyecto';
					break;
				case '5':
					$campo = 'revObra';
					break;
				case '6':
					$campo = 'tamAgregado';
					break;
				case '7':
					$campo = 'volumen';
					break;
				case '8':
					$campo = 'diasEnsaye';
					break;
				case '9':
					$campo = 'unidad';
					break;
				case '10':
					$campo = 'horaMuestreo';
					break;
				case '11':
					$campo = 'tempMuestreo';
					break;
				case '12':
					$campo = 'tempRecoleccion';
					break;
				case '13':
					$campo = 'localizacion';
					break;
				case '14':
					$campo = 'status';
					break;
			}

			$dbS->squery("
						UPDATE
							registrosCampo
						SET
							1QQ = '1QQ'
						WHERE
							id_registrosCampo = 1QQ AND
							status = 0

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
					tipoConcreto,
					unidad,
					horaMuestreo,
					tempMuestreo,
					tempRecoleccion,
					localizacion,
					status
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
			      	id_registrosCampo,
					formatoCampo_id,
			        claveEspecimen,
					fecha,
					fprima,
					revProyecto,
					revObra,
					tamagregado,
					volumen,
					unidad,
					horaMuestreo,
					tempMuestreo,
					tempRecoleccion,
					localizacion,
					status
			      FROM 
			      	registrosCampo
			      WHERE 
			      	registrosCampo.active = 1 AND
			      	formatoCampo_id = 1QQ
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
						id_registrosCampo,
						fecha,
						informeNo,
						claveEspecimen,
						diasEnsaye,
						tipo,
						DATE_ADD(fecha,INTERVAL diasEnsaye DAY) AS FechaAgendadaDeEnsaye,
						IF(DATE_ADD(fecha, INTERVAL diasEnsaye DAY) < CURDATE(),'ATRASADO','AGENDADO PARA HOY') AS estado,
						CASE
							WHEN diasEnsaye = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN diasEnsaye = 1 AND DATE_ADD(fecha, INTERVAL prueba1 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							WHEN diasEnsaye = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN diasEnsaye = 2 AND DATE_ADD(fecha, INTERVAL prueba2 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							WHEN diasEnsaye = 3 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN diasEnsaye = 3 AND DATE_ADD(fecha, INTERVAL prueba3 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							WHEN diasEnsaye = 4 AND DATE_ADD(fecha, INTERVAL prueba4 DAY) < CURDATE() THEN 'ATRASADO'
							WHEN diasEnsaye = 4 AND DATE_ADD(fecha, INTERVAL prueba4 DAY) = CURDATE() THEN 'AGENDADO PARA HOY'
							ELSE 'Error, Contacta a soporte'
						END AS estado 
					FROM
						registrosCampo,formatoCampo,ordenDeTrabajo
					WHERE
						id_formatoCampo = formatoCampo_id AND
						id_ordenDeTrabajo = ordenDeTrabajo_id AND
						registrosCampo.status = 2 AND
						DATE_ADD(fecha, INTERVAL diasEnsaye DAY) <= CURDATE() AND
						laboratorio_id = 1QQ 
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
	//$token,$rol_usuario_id,
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
					$groupsOf4=(floor($aux/4)+1);
					$opciones=array("Pendiente"=> "Pendiente");
					for($i=0;$i<$groupsOf4;$i++){
						foreach ($pruebas as $key => $value) {
							$flag=true;
							$keyAux;
							foreach ($b as $key2 => $value2) {
								if((string)$value2['diasEnsaye'] === (string)$key){
									//echo "value2[diasEnsaye]: ".$value2['diasEnsaye']." key: ".$key;
									$flag=false;
									$keyAux=$key2;
									break;
								} 
							}
							if($flag){
								$opciones[ (string)($key+(4*$i)) ] = $value;
							}else{
								unset($b[$keyAux]);
							}
						}
					}
					$dbS->commitTransaction();
					return json_encode($opciones);
				}else{
					$dbS->commitTransaction();
					return json_encode(array("Pendiente"=> "Pendiente","0"=>$a['prueba1'],"1"=>$a['prueba2'],"2"=>$a['prueba3'],"3"=>$a['prueba4']));
				}
			}
			else{
				$dbS->rollbackTransaction();
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' =>null,	'estatus' => 'Error inesperado en la funcion getDaysPruebasForDropDown , verifica tus datos y vuelve a intentarlo','error' => 7);
				return json_encode($arr);
			}
		}
		$dbS->rollbackTransaction();
		$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => null,	'estatus' => 'Error inesperado en la funcion getDaysPruebasForDropDown , verifica tus datos y vuelve a intentarlo','error' => 6);
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