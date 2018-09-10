<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class Laboratorio_cliente{
	private $ordenDeServicio_id;
	private $herramienta_id;
	private $fechaDevolucion;
	private $status;

	/* Variables de utilería */
	private $wc = '/1QQ/';

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
					case"VIGA":
						$herra_tipo=1010;
						$id_herramienta=60;
					break;
				}
				$arr= $dbS->qAll("
					SELECT
					 	id_herramienta AS id_herramienta,
					 	placas AS placas 
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
					  	id_formatoCampo='1QQ'
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


	public function getAllLabsForCli($token,$rol_usuario_id,$cliente_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qAll("
			      SELECT 
			      		id_laboratorio,
			      		laboratorio,
			      		CL.active,
			      		IF(CL.active=1,1,0) AS estadoNo,
			      		IF(CL.active=1,'Si','No') AS estado
				  FROM 
				  		laboratorio 
				  	LEFT JOIN 
				  		(
				  		SELECT
				  			active,
				  			laboratorio_id
					  	FROM
				  			laboratorio_cliente
				  		WHERE
				  			cliente_id=1QQ
				  		) AS CL 
				  	ON id_laboratorio = laboratorio_id
			      WHERE 
			      	laboratorio.active=1;

			      ",
			      array($cliente_id),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('estatus' => 'Error en la funcion query , verifica tus datos y vuelve a intentarlo', "error" =>5);
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



	//FUNCION QUE DESACTIVA UNA HERRAMIENTA RELACIONADA CON LA ORDEN DE TRABAJO
	public function changeLaboratorio_clienteState($token,$rol_usuario_id,$id_laboratorio,$estadoNo,$cliente_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);

		if($arr['error'] == 0){
			$a=$dbS->qarrayA("	
				SELECT 
					id_laboratorio_cliente 
				FROM 
					laboratorio_cliente
				WHERE
					laboratorio_id= 1QQ AND
					cliente_id= 1QQ
			 	"
				,array($id_laboratorio,$cliente_id),"UPDATE"
	      	);
	      	if(!$dbS->didQuerydied){
	      		if($a=="empty"){
	      			if($estadoNo==1){ // ESTA ACTIVA Y SE DESEA DESACTIVAR PERO ESTA EN EMPTY => ERROR
						$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error, verifica tus datos y vuelve a intentarlo','error' => 8);
	      				return json_encode($arr);
	      			}else{ // ESTA INACTIVA Y SE DESEA ACTIVAR PERO ESTA EN EMPTY => INSERT
	      				$dbS->squery("	
							INSERT INTO laboratorio_cliente
								(laboratorio_id,
								cliente_id)
							VALUES
								(1QQ,1QQ)
						 "
						,array($id_laboratorio,$cliente_id),"INSERT"
				      	);
	      			}
	      		}else{
	      			if($estadoNo==1){ // ESTA ACTIVA Y SE DESEA DESACTIVAR PERO NO ESTA EN EMPTY => UPDATE
	      				$dbS->squery("	
							UPDATE
								laboratorio_cliente
							SET
								active = 0
							WHERE
								id_laboratorio_cliente = 1QQ
						 "
						,array($a['id_laboratorio_cliente']),"UPDATE"
				      	);
	      			}else{ // ESTA INACTIVA Y SE DESEA ACTIVAR PERO ESTA EN EMPTY => UPDATE
	      				$dbS->squery("	
							UPDATE
								laboratorio_cliente
							SET
								active = 1
							WHERE
								id_laboratorio_cliente = 1QQ
						 "
						,array($a['id_laboratorio_cliente']),"UPDATE"
				      	);
	      			}
	      		}
	      	}
			
			if(!$dbS->didQuerydied){
				$arr = array('herramienta_id' => $herramienta_id,'estatus' => 'Se cambio la visibilidad correctamente','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}

		}
		return json_encode($arr);
	}
}

?>