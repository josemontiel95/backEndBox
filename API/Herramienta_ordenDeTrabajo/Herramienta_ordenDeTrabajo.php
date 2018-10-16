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

	/* 								USAR PARA TESTEADO 
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
	*/
	//
	
	public function getHerraOrdenComplete($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll(
							  "
						      	SELECT
									id_herramienta,
									id_herramienta_tipo,
								    tipo,
									placas,
									condicion AS condicionActual,
									fechaDevolucion,
									herramienta_ordenDeTrabajo.status AS condicionDespuesDeEvaluar,	
									herramienta_ordenDeTrabajo.observaciones			      		
								FROM
									herramientas,
									herramienta_tipo,
									herramienta_ordenDeTrabajo,
									ordenDeTrabajo
								WHERE
									herramienta_ordenDeTrabajo.herramienta_id = herramientas.id_herramienta AND
									herramientas.herramienta_tipo_id = herramienta_tipo.id_herramienta_tipo AND
									ordenDeTrabajo.id_ordenDeTrabajo = herramienta_ordenDeTrabajo.ordenDeTrabajo_id AND
									ordenDeTrabajo.status = 3 AND
									herramienta_ordenDeTrabajo.active = 0 AND
									herramienta_ordenDeTrabajo.ordenDeTrabajo_id = 1QQ

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

	}


	public function getHerramientaForDropdownRegistro($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$a= $dbS->qarrayA("
		      	SELECT 
				    tipo
				FROM 
					formatoCampo
				WHERE
				  	id_formatoCampo='1QQ'
				",
				array($id_formatoCampo),
				"SELECT"
			);
			if(!$dbS->didQuerydied && !($a=="empty")){
				$herra_tipo=0;
				$id_herramienta=0;
				switch($a['tipo']){
					case"CILINDRO":
						$herra_tipo=1011;
						$id_herramienta=70;
					break;
					case"CUBO":
						$herra_tipo=1009;
						$id_herramienta=50;
					break;
					case"VIGAS":
						$herra_tipo=1010;
						$id_herramienta=60;
					break;
				}
				$arr= $dbS->qAll("
					SELECT 
						id_herramienta AS id_herramienta,
						placas AS placas
					FROM
							(
							SELECT
							 	id_herramienta AS id_herramienta,
							 	'Pendiente' AS placas 
							FROM
							 	herramientas
							WHERE
							 	id_herramienta = '1QQ'
							UNION
					      	SELECT 
					      		id_herramienta,
							    placas
							FROM 
								herramienta_ordenDeTrabajo,
								formatoCampo,
								herramientas
							WHERE
								herramienta_id=id_herramienta AND
								herramienta_ordenDeTrabajo.active=1 AND 
								herramienta_tipo_id='1QQ' AND
								herramienta_ordenDeTrabajo.ordenDeTrabajo_id=formatoCampo.ordenDeTrabajo_id AND 
							  	id_formatoCampo='1QQ'
							) AS dispOrServ
						LEFT JOIN 
							(
							SELECT 
								id_registrosCampo,
								herramienta_id
							FROM 
								registrosCampo
							WHERE 
								DATE(createdON) = CURDATE()

							) AS rc
						ON 
							rc.herramienta_id = dispOrServ.id_herramienta
					WHERE 
						id_registrosCampo IS NULL
					",
					array($id_herramienta,$herra_tipo,$id_formatoCampo),
					"SELECT"
				);
				if(!$dbS->didQuerydied && !($arr=="empty")){

				}else if($arr=="empty"){
					$arr = array('estatus' =>"No hay registros", 'error' => 5); 
				}else{
					$arr = array('estatus' =>"No hay registros", 'error' => 6); 
				}
			}else{
				$arr = array('estatus' =>"No hay registros", 'error' => 7); 
			}
		}
		return json_encode($arr);
	}	


	//Añadimos a que obra esta agendada???? PENDIENTE
	public function getAllHerraAvailable($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      	SELECT 
					    id_herramienta,
						fechaDeCompra,
						placas,
						condicion,
						herramientas.observaciones,
						herramientas.createdON,
						herramientas.lastEditedON
					FROM 
						herramientas LEFT JOIN
						(
							SELECT
								herramienta_id,
								IF(herramienta_ordenDeTrabajo.active = 0 AND CURDATE()>ordenDeTrabajo.fechaInicio, 'SI','NO') AS estado
							FROM
								herramienta_ordenDeTrabajo,
								ordenDeTrabajo
							WHERE
								ordenDeTrabajo_id = id_ordenDeTrabajo 
						) AS estado_herramienta
						ON herramientas.id_herramienta = estado_herramienta.herramienta_id
					WHERE
					  	herramientas.active = 1 AND
					  	(estado_herramienta.estado='SI' OR estado_herramienta.estado IS NULL) AND 
				        id_herramienta > 1000

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



	public function insertAdmin($token,$rol_usuario_id,$ordenDeTrabajo_id,$herramientasArray){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token,$rol_usuario_id),true);
		$i=0;
		$herramientasArray=json_decode($herramientasArray);
	
		if($arr['error'] == 0){
			$dbS->transquery("
						INSERT INTO
						herramienta_ordenDeTrabajo(herramienta_id,ordenDeTrabajo_id,status)
						VALUES
						(1QQ,1QQ,'PENDIENTE')"
						,$herramientasArray,$ordenDeTrabajo_id,
						"INSERT_TS");

				if(!$dbS->didQuerydied){
					$arr = array('id_herramienta_ordenDeTrabajo' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en insercion', 'error' => 0);
				}
				else{
					$id=$dbS->lastInsertedID;
					$arr = array('Se detecto error en id:' => $id, 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
				}

		}
		return json_encode($arr);
		
	}


	

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
	public function deleteHerra($token,$rol_usuario_id,$herramientasArray){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token,$rol_usuario_id),true);
		$i=0;
		$herramientasArray=json_decode($herramientasArray);
	
		if($arr['error'] == 0){
			$dbS->transquery("
						DELETE FROM
							herramienta_ordenDeTrabajo
							WHERE
								active=1 AND
								herramienta_id = 1QQ

						"
						,$herramientasArray,$ordenDeTrabajo_id,
						"DELET-TS");


				if(!$dbS->didQuerydied){
					$arr = array('id_herramienta_ordenDeTrabajo' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en eliminación', 'error' => 0);
				}
				else{
					$id=$dbS->lastInsertedID;
					$arr = array('Se detecto error en id:' => $id, 'token' => $token,	'estatus' => 'Error en la eliminacion , verifica tus datos y vuelve a intentarlo','error' => 5);				
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
			        CONCAT(nombre,' ',apellido) AS nombre_jefe_brigada,
			        jefe_brigada_id,
					DATE(herramienta_ordenDeTrabajo.createdON) AS fechaDePrestamo,
			        IF(fechaDevolucion = '0000-00-00','NO SE HA DEVUELTO','fechaDevolucion')AS FECHA_DE_DEVOLUCION,
					herramienta_ordenDeTrabajo.status AS status,
					CASE
		  				WHEN herramienta_ordenDeTrabajo.active = 1 AND NOW()>TIMESTAMP(CONCAT(odt.fechaInicio,' ',odt.horaInicio)) AND NOW()<TIMESTAMP(CONCAT(odt.fechaFin,' ',odt.horaFin)) THEN 'En Curso'
		  				WHEN herramienta_ordenDeTrabajo.active = 1 AND NOW()<TIMESTAMP(CONCAT(odt.fechaInicio,' ',odt.horaInicio)) THEN 'Agendado'
		  				WHEN herramienta_ordenDeTrabajo.active = 1 AND NOW()>TIMESTAMP(CONCAT(odt.fechaFin,' ',odt.horaFin)) THEN 'Vencido'
		  				WHEN herramienta_ordenDeTrabajo.active = 0 AND NOW()>TIMESTAMP(CONCAT(odt.fechaFin,' ',odt.horaFin)) THEN 'Terminado'
		    			ELSE 'Error'
					END AS estado
				  FROM 
			      	ordenDeTrabajo AS odt,usuario,herramienta_ordenDeTrabajo,herramientas
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
			      		herramienta_ordenDeTrabajo.active = 1 AND
			      		ordenDeTrabajo_id = 1QQ 

			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array("No hay herramientas asociadas a la orden:" => $id_ordenDeTrabajo, "error" =>0, "registros" => 0);
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