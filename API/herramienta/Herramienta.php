<?php 

include_once("./../../usuario/Usuario.php");
class Herramienta{
	private $id_herramienta;
	private $herramienta_tipo_id;
	private $fechaDeCompra;
	private $condicion;
	public  $observaciones;

	/* Variables de utilería */
	private $wc = '/1QQ/';



	/*
		Completar las funciones

	*/
	public function evaluateHerra($token,$rol_usuario_id,$id_herramienta,$condicion,$observaciones){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->beginTransaction();
			$dbS->squery("	UPDATE
							herramientas
							SET 
								condicion = '1QQ',
								observaciones = '1QQ'
							WHERE
								id_herramienta = 1QQ
					 "
					,array($condicion,$observaciones,$id_herramienta),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$dbS->squery("	UPDATE
							herramienta_ordenDeTrabajo
							SET 
								active = 0
							WHERE
								herramienta_id = 1QQ
					 "
					,array($id_herramienta),"UPDATE"
			      	);
				if(!$dbS->didQuerydied){
					$arr = array('id_herramienta' => $id_herramienta, 'herramienta_tipo_id' => $herramienta_tipo_id, 'estatus' => 'Exito en actualizacion', 'error' => 0);
					$dbS->commitTransaction();
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
					$dbS->rollbackTransaction();
				}

			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
				$dbS->rollbackTransaction();
			}
		}
		return json_encode($arr);
	}

	public function getForDroptdownBasculas($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	 SELECT
					 	H.id_herramienta AS id_herramienta,
					    H.placas AS placas 
					 FROM
					 	herramienta_tipo AS HT,herramientas AS H 
					 WHERE
					 	HT.id_herramienta_tipo=H.herramienta_tipo_id AND
					   	H.herramienta_tipo_id = 1005
			      ",
			      array(),
			      "SELECT"
			      );
			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}

	public function getForDroptdownPrensas($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      	 SELECT
					 	H.id_herramienta AS id_herramienta,
					    H.placas AS placas 
					 FROM
					 	herramienta_tipo AS HT,herramientas AS H 
					 WHERE
					 	HT.id_herramienta_tipo=H.herramienta_tipo_id AND
					   	H.herramienta_tipo_id = 1008
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}
	/*
		Validar las reglas y los vernier disponibles, porque pueden estar asignados a una orden de trabajo
	*/

	public function getForDroptdownReglasVerFlex($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      	SELECT 
						*
					FROM
						(SELECT 
						    id_herramienta,
							placas,
							tipo,
							estado_herramienta.estado AS estado
						FROM 
							herramienta_tipo,herramientas LEFT JOIN
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
							herramientas.herramienta_tipo_id = herramienta_tipo.id_herramienta_tipo AND
						  	herramientas.active = 1 AND
						  	(herramientas.id_herramienta > 1000) AND
						  	(herramientas.herramienta_tipo_id = 1006 OR herramientas.herramienta_tipo_id = 1003 OR herramientas.herramienta_tipo_id = 1007)) AS T1
					WHERE 
						(T1.estado='SI' OR T1.estado IS NULL)
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}

	public function getForDroptdownReglas($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      	SELECT 
						*
					FROM
						(SELECT 
						    id_herramienta,
							placas,
							estado_herramienta.estado AS estado
						FROM 
							herramienta_tipo,herramientas LEFT JOIN
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
							herramientas.herramienta_tipo_id = herramienta_tipo.id_herramienta_tipo AND
						  	herramientas.active = 1 AND
						  	(herramientas.id_herramienta > 1000) AND
						  	(herramientas.herramienta_tipo_id = 1006)) AS T1
					WHERE 
						(T1.estado='SI' OR T1.estado IS NULL)
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}

	public function getForDroptdownVernier($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      	SELECT 
						*
					FROM
						(SELECT 
						    id_herramienta,
							placas,
							estado_herramienta.estado AS estado
						FROM 
							herramienta_tipo,herramientas LEFT JOIN
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
							herramientas.herramienta_tipo_id = herramienta_tipo.id_herramienta_tipo AND
						  	herramientas.active = 1 AND
						  	(herramientas.id_herramienta > 1000) AND
						  	(herramientas.herramienta_tipo_id = 1007)) AS T1
					WHERE 
						(T1.estado='SI' OR T1.estado IS NULL)
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}

	public function getForDroptdownFlexo($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
					SELECT
					 	id_herramienta AS id_herramienta,
					 	placas AS placas 
					FROM
					 	herramientas
					WHERE
					 	id_herramienta = 30
					UNION
			      	SELECT 
						id_herramienta AS id_herramienta,
					 	placas AS placas 
					FROM
						(SELECT 
						    id_herramienta,
							placas,
							estado_herramienta.estado AS estado
						FROM 
							herramienta_tipo,herramientas LEFT JOIN
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
							herramientas.herramienta_tipo_id = herramienta_tipo.id_herramienta_tipo AND
						  	herramientas.active = 1 AND
						  	(herramientas.id_herramienta > 1000) AND
						  	(herramientas.herramienta_tipo_id = 1003)) AS T1
					WHERE 
						(T1.estado='SI' OR T1.estado IS NULL)
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}

	public function getForDroptdownJefeBrigadaCono($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
					 SELECT
					 		id_herramienta AS id_herramienta,
					 		placas AS placas 
					 	FROM
					 		herramientas
					 	WHERE
					 		id_herramienta = 10
					 UNION				
			      	 SELECT
					 	H.id_herramienta AS id_herramienta,
					    H.placas AS placas 
					 FROM
					 	herramienta_tipo AS HT,herramientas AS H,herramienta_ordenDeTrabajo AS HO 
					 WHERE
					 	HO.herramienta_id = H.id_herramienta AND
					 	HT.id_herramienta_tipo=H.herramienta_tipo_id AND
					   	HO.active = 1 AND
					   	H.herramienta_tipo_id = 1001 AND
					   	HO.ordenDeTrabajo_id = 1QQ
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}

	public function getForDroptdownJefeBrigadaVarilla($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
					SELECT
					 		id_herramienta AS id_herramienta,
					 		placas AS placas 
					 	FROM
					 		herramientas
					 	WHERE
					 		id_herramienta = 20
					UNION
			     	SELECT
					 	H.id_herramienta AS id_herramienta,
					    H.placas AS placas 
					 FROM
					 	herramienta_tipo AS HT,herramientas AS H,herramienta_ordenDeTrabajo AS HO 
					 WHERE
					 	HO.herramienta_id = H.id_herramienta AND
					 	HT.id_herramienta_tipo=H.herramienta_tipo_id AND
					   	HO.active = 1 AND
					   	H.herramienta_tipo_id = 1002 AND
					   	HO.ordenDeTrabajo_id = 1QQ
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}



	

	public function getForDroptdownJefeBrigadaFlexometro($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
					 SELECT
					 		id_herramienta AS id_herramienta,
					 		placas AS placas 
					 	FROM
					 		herramientas
					 	WHERE
					 		id_herramienta = 30
					 UNION
			       	 SELECT
					 	H.id_herramienta AS id_herramienta,
					    H.placas AS placas 
					 FROM
					 	herramienta_tipo AS HT,herramientas AS H,herramienta_ordenDeTrabajo AS HO 
					 WHERE
					 	HO.herramienta_id = H.id_herramienta AND
					 	HT.id_herramienta_tipo=H.herramienta_tipo_id AND
					   	HO.active = 1 AND
					   	H.herramienta_tipo_id = 1003 AND
					   	HO.ordenDeTrabajo_id = 1QQ
					 
					 	
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}


	public function getForDroptdownJefeBrigadaTermometro($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
					 SELECT
					 		id_herramienta AS id_herramienta,
					 		placas AS placas 
					 	FROM
					 		herramientas
					 	WHERE
					 		id_herramienta = 40
					 UNION
			      	 SELECT
					 	H.id_herramienta AS id_herramienta,
					    H.placas AS placas 
					 FROM
					 	herramienta_tipo AS HT,herramientas AS H,herramienta_ordenDeTrabajo AS HO 
					 WHERE
					 	HO.herramienta_id = H.id_herramienta AND
					 	HT.id_herramienta_tipo=H.herramienta_tipo_id AND
					   	HO.active = 1 AND
					   	H.herramienta_tipo_id = 1003 AND
					   	HO.ordenDeTrabajo_id = 1QQ
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($arr == "empty")
					$arr = array('estatus' =>"No hay registros", 'error' => 5); //Pendiente
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la query , verifica tus datos y vuelve a intentarlo','error' => 6);	
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
			        id_herramienta,
					herramienta_tipo_id,
					fechaDeCompra,
					placas,
					condicion,
					id_herramienta_tipo,
					tipo,
					observaciones,
					herramientas.createdON,
					herramientas.lastEditedON,
					IF(herramientas.active = 1,'Si','No') AS active
			      FROM 
			        herramienta_tipo,
					herramientas
			      WHERE
			      	 id_herramienta_tipo =  herramienta_tipo_id AND
			      	 id_herramienta > 1000
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
					observaciones,
					herramientas.createdON,
					herramientas.lastEditedON
			      FROM 
			        herramienta_tipo,
					herramientas
			      WHERE
			      	 id_herramienta_tipo =  herramienta_tipo_id AND
			      	 herramientas.active = 1 AND
			      	 id_herramienta > 1000
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


	public function insertAdmin($token,$rol_usuario_id,$herramienta_tipo_id,$fechaDeCompra,$placas,$condicion,$observaciones){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						herramientas(herramienta_tipo_id,fechaDeCompra,placas,condicion,observaciones)

						VALUES
						('1QQ','1QQ','1QQ','1QQ','1QQ')
				",array($herramienta_tipo_id,$fechaDeCompra,$placas,$condicion,$observaciones),"INSERT");

			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta' => 'No disponible, esto NO es un error', 'herramienta_tipo_id' => $herramienta_tipo_id, 'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function upDateAdmin($token,$rol_usuario_id,$id_herramienta,$herramienta_tipo_id,$fechaDeCompra,$placas,$condicion,$observaciones){
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
							condicion = '1QQ',
							observaciones = '1QQ'
						WHERE
							id_herramienta = 1QQ
					 "
					,array($herramienta_tipo_id,$fechaDeCompra,$placas,$condicion,$observaciones,$id_herramienta),"UPDATE"
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

	public function getByIDAdmin($token,$rol_usuario_id,$id_herramienta){
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
					observaciones,
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

				

	public function getForDroptdownTipo($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      	SELECT
   				       	id_herramienta_tipo,
   				       	tipo
   				    FROM
   				        herramienta_tipo
   				    WHERE
            			herramienta_tipo.active = 1 ;
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


	public function getAllFromTipo($token,$rol_usuario_id,$herramienta_tipo_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      	SELECT 
						id_herramienta,
						placas,
						id_herramienta_tipo,
						tipo,
						condicion,
						T2.active
					FROM 
						(SELECT 
						  	id_herramienta,
						  	placas,
							id_herramienta_tipo,
							tipo,
							condicion
						FROM 
						    herramienta_tipo,herramientas
						WHERE
						   	herramientas.active = 1 AND
						    herramienta_tipo_id = id_herramienta_tipo AND
						    herramienta_tipo_id = 1QQ AND 
						    id_herramienta > 1000) AS T1 
					    LEFT JOIN 
					    	(SELECT
							*
							FROM 
							herramienta_ordenDeTrabajo
							WHERE 
							herramienta_ordenDeTrabajo.active=1) AS T2 
					    ON
					    	herramienta_id=id_herramienta
					WHERE 
						T2.active IS NULL
			      ",
			      array($herramienta_tipo_id),
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

	
	












}





?>