<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class herramienta_ordenDeSevicio{
	private $ordenDeServicio_id;
	private $herramienta_id;
	private $fechaDevolucion;
	private $status;

	/* Variables de utilería */
	private $wc = '/1QQ/';



	/*
		Completar las funciones

	*/

		/*
	//Esta funcion no sirve, se deven realizar los cambio necesarios para que sirva
	public function getForDroptdownAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			      	id_herramienta,
			        tipo,
			        placas
			      FROM 
			        herramienta_tipo,herramientas
			       WHERE
			       	id_herramienta_tipo=herramienta_tipo_id AND
			       	herramientas.active = 1
			      ORDER BY 
			      	tipo
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if(count($arr) == 0)
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}

	//Esta funcion no sirve, se deven realizar los cambio necesarios para que sirva
	public function getAllAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_herramienta,
					herramienta_tipo_id,
					fechaDeCompra,
					placas,
					condicion,
					id_herramienta_tipo,
					tipo,
					herramientas.createdON,
					herramientas.lastEditedON,
					IF(herramientas.active = 1,'Si','No') AS active
			      FROM 
			        herramienta_tipo,
					herramientas
			      WHERE
			      	 id_herramienta_tipo =  herramienta_tipo_id
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
						if(count($arr) == 0)
							$arr = array('estatus' =>"No hay registros", 'error' => 5); 
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}

	public function getAllJefaLab($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_herramienta,
					herramienta_tipo_id,
					fechaDeCompra,
					placas,
					condicion,
					tipo,
					herramientas.createdON,
					herramientas.lastEditedON
			      FROM 
			        herramienta_tipo,
					herramientas
			      WHERE
			      	 id_herramienta_tipo =  herramienta_tipo_id AND
			      	 herramientas.active = 1
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
						if(count($arr) == 0)
							$arr = array('estatus' =>"No hay registros", 'error' => 5); 
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}


	public function insertAdmin($token,$rol_usuario_id,$herramienta_tipo_id,$fechaDeCompra,$placas,$condicion){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						herramientas(herramienta_tipo_id,fechaDeCompra,placas,condicion)

						VALUES
						('1QQ','1QQ','1QQ','1QQ')
				",array($herramienta_tipo_id,$fechaDeCompra,$placas,$condicion),"INSERT");

			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta' => 'No disponible, esto NO es un error', 'herramienta_tipo_id' => $herramienta_tipo_id, 'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function upDateAdmin($token,$rol_usuario_id,$id_herramienta,$herramienta_tipo_id,$fechaDeCompra,$placas,$condicion){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							herramientas
						SET
							herramienta_tipo_id ='1QQ',
							fechaDeCompra = '1QQ',
							placas = '1QQ', 
							condicion = '1QQ'
						WHERE
							id_herramienta = 1QQ
					 "
					,array($herramienta_tipo_id,$fechaDeCompra,$placas,$condicion,$id_herramienta),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta' => 'No disponible, esto NO es un error', 'herramienta_tipo_id' => $herramienta_tipo_id, 'estatus' => 'Exito en actualizacion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}
	*/

	/*
		-El campo de fecha de devolucion´podria estar asociado a la fecha de termino de la orden de servicio?
		-La funcion que imprime las herramientas disponibles para asignarlas a una nueva orden de servicio deberian estar en la clase herramientas?
	*/
	public function insertAdmin($token,$rol_usuario_id,$ordenDeServicio_id,$herramienta_id,$fechaDevolucion,$status){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						herramienta_ordenDeSevicio(ordenDeServicio_id,herramienta_id,fechaDevolucion,status)

						VALUES
						(1QQ,1QQ,'1QQ','1QQ')
				",array($ordenDeServicio_id,$herramienta_id,$fechaDevolucion,$status),"INSERT");

			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta_ordenDeServicio' => 'No disponible, esto NO es un error', 'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
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
			        ordenDeServicio_id,
			        herramienta_id,
			        placas,
			        nombre AS nombre_jefe_brigada,
			        jefe_brigada_id,
					ordenDeServicio.fechaInicio AS fechaDePrestamo,
			        fechaDevolucion,
					status,
					CASE
		  				WHEN herramienta_ordenDeSevicio.active = 1 AND CURDATE()>ordenDeServicio.fechaInicio THEN 'En Curso'
		    			WHEN herramienta_ordenDeSevicio.active = 0 AND CURDATE()>ordenDeServicio.fechaInicio THEN 'Completado'
		    			WHEN herramienta_ordenDeSevicio.active = 1 AND CURDATE()<ordenDeServicio.fechaInicio THEN 'Agendado'
		    				ELSE 'Error'
					END AS estado
				  FROM 
			      	ordenDeServicio,usuario,herramienta_ordenDeSevicio,herramientas
			      WHERE 
			      		ordenDeServicio_id = id_ordenDeServicio AND
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

	/*

	//Realizar cambios pertinenetes para que la funcion sirva
	public function getByIDAdminOrden($token,$rol_usuario_id,$ordenDeServicio_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT 
			        id_herramienta,
			        herramienta_tipo_id,
			        fechaDeCompra,
			        placas,
			        condicion,
					tipo,
					herramientas.createdON,
					herramientas.lastEditedON,
					herramientas.active,
					herramienta_tipo.active AS isHerramienta_tipoActive
			      FROM 
			      	herramienta_tipo,
					herramientas
			      WHERE 
			      	id_herramienta_tipo =  herramienta_tipo_id AND
			      	id_herramienta = 1QQ
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
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}





	public function deactivate($token,$rol_usuario_id,$id_herramienta){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							herramientas
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_herramienta = 1QQ
					 "
					,array(0,$id_herramienta),"UPDATE"
			      	);
		//PENDIENTE por la herramienta_tipo_id para poderla imprimir tengo que cargar las variables de la base de datos?
			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta' => $id_herramienta,'estatus' => 'Herramienta se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}

		}
		return json_encode($arr);
	}

	public function activate($token,$rol_usuario_id,$id_herramienta){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							herramientas
						SET
							active = 1QQ
						WHERE
							active=0 AND
							id_herramienta = 1QQ
					 "
					,array(1,$id_herramienta),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta' => $id_herramienta,'estatus' => 'Herramienta se activo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la activacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	*/









}





?>