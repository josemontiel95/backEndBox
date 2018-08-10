<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class Herramienta_ordenDeTrabajo{
	private $ordenDeServicio_id;
	private $herramienta_id;
	private $fechaDevolucion;
	private $status;

	/* Variables de utilería */
	private $wc = '/1QQ/';

	/*

		PROTOTIPO DE QUE EL LOOP SIRVE
	foreach ($herramientasArray as $herramienta_id){
				$dbS->squery("
						INSERT INTO
						herramienta_ordenDeTrabajo(ordenDeTrabajo_id,herramienta_id,status)

						VALUES
						(1QQ,1QQ,'PENDIENTE')",array($ordenDeTrabajo_id,$herramienta_id),"INSERT");

				if(!$dbS->didQuerydied){
					$arr = array('id_herramienta_ordenDeTrabajo' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en insercion', 'error' => 0);
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
					break;
				}
			}
	*/

	//INSERTA VARIAS HERRAMIENTAS (UTILIZA UN FOREACH)
    /*
		EN EL PRIMER PARAMETRO (EL ARRAY), SE COLOCA EL ARRAY QUE SE ASOCIARA AL SEGUNDO PARAMETRO QUE ES EL DESTINO
    */

	//TESTEADO 
	public function insertAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token,$rol_usuario_id),true);
		if($arr['error'] == 0){
			$herramientasArray = array(1045,1046,10810,1047); $ordenDeTrabajo_id = 1001; // los recibes del front
			$id = $dbS->transquery("
						INSERT INTO
						herramienta_ordenDeTrabajo(herramienta_id,ordenDeTrabajo_id,status)
						VALUES
						(1QQ,1QQ,'PENDIENTE')"
						,$herramientasArray,$ordenDeTrabajo_id,
						"INSERT_TS");
				if(!$dbS->didQuerydied){ // habria que cambiar el enfoque
					$arr = array('id_herramienta_ordenDeTrabajo' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en insercion', 'error' => 0);
				}
				else{
					$arr = array('Se detecto error en id:' => $id, 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
				}
		}
		return json_encode($arr);

	}


	/*	
				----FUNCION---

	public function insertAdmin($token,$rol_usuario_id,$ordenDeTrabajo_id,$herramientasArray){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token,$rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->transquery("
						INSERT INTO
						herramienta_ordenDeTrabajo(herramienta_id,ordenDeTrabajo_id,status)
						VALUES
						(1QQ,1QQ,'PENDIENTE')",array($herramientasArray,$ordenDeTrabajo_id),"INSERT");

				if(!$dbS->didQuerydied){
					$arr = array('id_herramienta_ordenDeTrabajo' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en insercion', 'error' => 0);
				}
				else{
					$id=$dbS->lastInsertedID;
					$arr = array('Se detecto error en id:' => $id, 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
					break;
				}
		}
		return json_encode($arr);

	}


	*/

	//FUNCION QUE DESACTIVA UNA HERRAMIENTA RELACIONADA CON LA ORDEN DE TRABAJO
	public function deactivateHerra($token,$rol_usuario_id,$herramientasArray){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			foreach ($herramientasArray as $herramienta_id){
				$dbS->squery("	UPDATE
								herramienta_ordenDeTrabajo
							SET
								active = 1QQ
							WHERE
								active=1 AND
								herramienta_id = 1QQ
						 "
						,array(0,$herramienta_id),"UPDATE"
				      	);
				if(!$dbS->didQuerydied){
					$arr = array('herramienta_id' => $herramienta_id,'estatus' => 'Herramienta se desactivo','error' => 0);
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
				}
			}

		}
		return json_encode($arr);
	}

	//FUNCION QUE ELIMINA UNA TUPLA MEDIANTE EL ID DE LA HERRAMIENTA
	public function deleteHerra($token,$rol_usuario_id,$herramienta_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	DELETE FROM
							herramienta_ordenDeTrabajo
							WHERE
								active=1 AND
								herramienta_id = 1QQ
					 "
					,array($herramienta_id),"DELETE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('herramienta_id' => $herramienta_id,'estatus' => 'Herramienta se elimino','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}

		}
		return json_encode($arr);
	}


	public function getByIDAdminHerra($token,$rol_usuario_id,$id_herramienta){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
			      SELECT 
			        ordenDeTrabajo_id,
			        herramienta_id,
			        placas,
			        nombre AS nombre_jefe_brigada,
			        jefe_brigada_id,
					ordenDeTrabajo.fechaInicio AS fechaDePrestamo,
			        IF(fechaDevolucion = '0000-00-00','NO SE HA DEVUELTO','fechaDevolucion')AS FECHA_DE_DEVOLUCION,
					status,
					CASE
		  				WHEN herramienta_ordenDeTrabajo.active = 1 AND CURDATE()>ordenDeTrabajo.fechaInicio THEN 'En Curso'
		    			WHEN herramienta_ordenDeTrabajo.active = 0 AND CURDATE()>ordenDeTrabajo.fechaInicio THEN 'Completado'
		    			WHEN herramienta_ordenDeTrabajo.active = 1 AND CURDATE()<ordenDeTrabajo.fechaInicio THEN 'Agendado'
		    				ELSE 'Error'
					END AS estado
				  FROM 
			      	ordenDeTrabajo,usuario,herramienta_ordenDeTrabajo,herramientas
			      WHERE 
			      		ordenDeTrabajo_id = id_ordenDeTrabajo AND
			      		id_usuario = jefe_brigada_id AND	
			      		herramienta_id = id_herramienta AND
			      		herramienta_id = 1QQ  
			      ",
			      array($id_herramienta),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_herramienta' => $id_herramienta,'estatus' => 'Error no se encontro ese id','error' => 5);
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


	public function getAllHerraOrden($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
			      SELECT 
			      	id_herramienta,
					id_herramienta_tipo,
			        tipo,
					placas,
					condicion
				  FROM 
			      	herramientas,herramienta_tipo,herramienta_ordenDeTrabajo
			      WHERE 
			      		herramienta_id = id_herramienta AND
			      		herramienta_tipo_id = id_herramienta_tipo AND
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

}





?>