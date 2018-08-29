<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class Tecnicos_ordenDeTrabajo{
	private $tecnico_id;
	private $ordenDeTrabajo_id;
	/* Variables de utilería */
	private $wc = '/1QQ/';

	/*
	public function insertAdmin($token,$rol_usuario_id,$tecnico_id,$ordenDeTrabajo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						tecnicos_ordenDeTrabajo(tecnico_id,ordenDeTrabajo_id)

						VALUES
						(1QQ,1QQ)
				",array($tecnico_id,$ordenDeTrabajo_id),"INSERT");

			if(!$dbS->didQuerydied){
				$arr = array('id_tecnicos_ordenDeTrabajo' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}*/

	public function getTecAvailable($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      	SELECT 
					    id_usuario,
					    CONCAT(nombre,' ',apellido) AS nombre
					FROM 
						usuario LEFT JOIN
						(
							SELECT
								tecnico_id,
								IF(tecnicos_ordenDeTrabajo.active = 0 AND CURDATE()>ordenDeTrabajo.fechaInicio, 'SI','NO') AS estado
							FROM
								tecnicos_ordenDeTrabajo,
								ordenDeTrabajo
							WHERE
								ordenDeTrabajo_id = id_ordenDeTrabajo 
						) AS estado_tec
						ON usuario.id_usuario = estado_tec.tecnico_id
					WHERE
					  	usuario.active = 1 AND
					  	rol_usuario_id = 1004 AND
					  	(estado_tec.estado='SI' OR estado_tec.estado IS NULL)

			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		
				
		}
		return json_encode($arr);


	}

	public function insertAdmin($token,$rol_usuario_id,$ordenDeTrabajo_id,$tecnicosArray){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token,$rol_usuario_id),true);
		$tecnicosArray=json_decode($tecnicosArray);

		if($arr['error'] == 0){
			$dbS->transquery("
						INSERT INTO
						tecnicos_ordenDeTrabajo(tecnico_id,ordenDeTrabajo_id)
						VALUES
						(1QQ,1QQ)"
						,$tecnicosArray,$ordenDeTrabajo_id,
						"INSERT_TS");

				if(!$dbS->didQuerydied){
					$arr = array('id_tecnicos_ordenDeTrabajo' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en insercion', 'error' => 0);
				}
				else{
					$id=$dbS->lastInsertedID;
					$arr = array('Se detecto error en id:' => $id, 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
				}

		}
		return json_encode($arr);

	}


	public function deleteTec($token,$rol_usuario_id,$tecnicosArray){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token,$rol_usuario_id),true);
		$tecnicosArray=json_decode($tecnicosArray);

		if($arr['error'] == 0){
			$dbS->transquery("
						DELETE FROM
							tecnicos_ordenDeTrabajo
							WHERE
								active=1 AND
								tecnico_id = 1QQ
						"
						,$tecnicosArray,$ordenDeTrabajo_id,
						"DELET-TS");

				if(!$dbS->didQuerydied){
					$arr = array('id_tecnicos_ordenDeTrabajo' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en eliminacion', 'error' => 0);
				}
				else{
					$id=$dbS->lastInsertedID;
					$arr = array('Se detecto error en id:' => $id, 'token' => $token,	'estatus' => 'Error en la eliminacion , verifica tus datos y vuelve a intentarlo','error' => 5);
				}

		}
		return json_encode($arr);

	}


	public function upDateAdmin($token,$rol_usuario_id,$tecnico_id,$ordenDeTrabajo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						UPDATE
						tecnicos_ordenDeTrabajo
						SET
						tecnico_id = 1QQ,
						ordenDeTrabajo_id = 1QQ
				",array($tecnico_id,$ordenDeTrabajo_id),"INSERT");

			if(!$dbS->didQuerydied){
				$arr = array('Se actualizo tecnico_id a:' => $tecnico_id, 'Se actualizo ordenDeTrabajo_id a:' => $ordenDeTrabajo_id,'estatus' => 'Exito en la actualizacion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}
	

	public function getAllTecOrden($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
			      SELECT 
			      	id_usuario,
					nombre,
			        apellido,
					foto
				  FROM 
			      	tecnicos_ordenDeTrabajo,usuario
			      WHERE 
			      		tecnico_id = id_usuario AND 
			      		usuario.active = 1 AND
			      		ordenDeTrabajo_id = 1QQ 

			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array("No hay herramientas asociadas a la orden:" => $id_ordenDeTrabajo, "error" =>5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion query , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}

	public function pasarLista($token,$rol_usuario_id,$id_tecnicos_ordenDeTrabajo,$email,$contrasena){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			//validamos el registro del tecnico
			if($usuario->getByEmail($email)=="success"){
				$contrasenaSHA= hash('sha512', $contrasena);
				if($usuario->validatedContrasena($contrasenaSHA)){
					$dbS->squery("
						INSERT INTO
						listaAsistencia(tecnicos_ordenDeTrabajo_id)

						VALUES
						(1QQ)
						",array($id_tecnicos_ordenDeTrabajo),"INSERT");
					if(!$dbS->didQuerydied){
						 $dbS->squery(" 
									UPDATE
										tecnicos_ordenDeTrabajo
									SET
										asistencias = asistencias+1
									WHERE
										id_tecnicos_ordenDeTrabajo = 1QQ

									",
									array($id_tecnicos_ordenDeTrabajo),"UPDATE");
						 if (!$dbS->didQuerydied) {
						 	$arr = array('id_tecnicos_ordenDeTrabajo' => $id_tecnicos_ordenDeTrabajo, 'estatus' => 'Exito en el inicio de sesión', 'error' => 0);
						 }
						 else{
						$arr = array('Se detecto error en id:' => $id_tecnicos_ordenDeTrabajo, 'token' => $token,	'estatus' => 'Error en el inicio de sesión problema en suma de asistencias , verifica tus datos y vuelve a intentarlo','error' => 5);
						 }
						
					}
					else{
						$arr = array('Se detecto error en id:' => $id_tecnicos_ordenDeTrabajo, 'token' => $token,	'estatus' => 'Error en el inicio de sesión problema en registro de asistencia, verifica tus datos y vuelve a intentarlo','error' => 6);
					}
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'Pasword incorrecto','error' => 7);
				}
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => 'NULL','estatus' => 'El usuario no existe','error' => 8);
			}

			

		}
		return json_encode($arr);
	}

	/*
	public function getTecAsistencia($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
					
			      	SELECT 
					    id_usuario,
					    CONCAT(nombre,' ',apellido) AS nombre,
					    estado
					FROM 
						tecnicos_ordenDeTrabajo,usuario LEFT JOIN
						(
							SELECT
								tecnico_id,
								IF(CURDATE()=DATE(listaAsistencia.createdON),'SI','NO') AS estado
							FROM
								tecnicos_ordenDeTrabajo,
								listaAsistencia
							WHERE
								tecnicos_ordenDeTrabajo_id = id_tecnicos_ordenDeTrabajo 
						) AS estado_tec
						ON usuario.id_usuario = estado_tec.tecnico_id
					WHERE
					  	usuario.active = 1 AND
					  	rol_usuario_id = 1004 AND
					  	ordenDeTrabajo_id = 1QQ

			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		
				
		}
		return json_encode($arr);
	}*/
	



	/*
		Añadir la funcion de evaluar a los tecnicos y generar un reporte
	*/








}

?>