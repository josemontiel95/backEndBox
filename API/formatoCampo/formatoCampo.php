<?php 


include_once("./../../usuario/Usuario.php");
class formatoCampo{
	private $id_formatoCampo;
	private $informeNo;
	private $ordenDeServicio_id;
	private $observaciones;
	private $mr;
	private $tipo;


	private $wc = '/1QQ/';

	/*
	PENDIENTE
	public function insertPositionFinal($position,$point){
		$longitud = $point[0];	$latitud = $point[1];
		$dbS->squery("
						INSERT INTO
						formatoCampo(${position})
						VALUES
						(geomfromtext('point(1QQ,1QQ)'))
				",array($informeNo,$ordenDeTrabajo_id,$observaciones,$tipo,$posInicial,$posFinal),"INSERT");

	}*/


	
	public function getAllAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			     	SELECT
						formatoCampo.id_formatoCampo,
						CONO,
						VARILLA,
						FLEXOMETRO,
						TERMOMETRO
					FROM
						formatoCampo,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS CONO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.cono_id = herramientas.id_herramienta
						)AS cono,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS VARILLA
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.varilla_id = herramientas.id_herramienta
						)AS varilla,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS FLEXOMETRO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.flexometro_id = herramientas.id_herramienta
						)AS flexometro,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS TERMOMETRO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.termometro_id = herramientas.id_herramienta
						)AS termometro
					WHERE
						cono.id_formatoCampo = formatoCampo.id_formatoCampo AND
						varilla.id_formatoCampo = formatoCampo.id_formatoCampo AND
						flexometro.id_formatoCampo = formatoCampo.id_formatoCampo AND
						termometro.id_formatoCampo = formatoCampo.id_formatoCampo 
					ORDER BY 
						formatoCampo.id_formatoCampo	        

			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
						if($arr == "empty")
							$arr = array('estatus' =>"No hay registros", 'error' => 5); 
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 6);
		}
		return json_encode($arr);	
	}
	

	public function insertJefeBrigada($token,$rol_usuario_id,$informeNo,$ordenDeTrabajo_id,$tipo,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$longitud,$latitud){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			

			$dbS->squery("
						INSERT INTO
						formatoCampo(informeNo,ordenDeTrabajo_id,tipo,cono_id,varilla_id,flexometro_id,termometro_id,posInicial)
						VALUES
						('1QQ',1QQ,'1QQ',1QQ,1QQ,1QQ,1QQ,PointFromText('POINT(1QQ 1QQ)'))
				",array($informeNo,$ordenDeTrabajo_id,$tipo,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$longitud,$latitud),"INSERT");
			if(!$dbS->didQuerydied){
				$id=$dbS->lastInsertedID;
				$arr = array('id_formatoCampo' =>$id,'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);

	}


	public function getInfoByID($token,$rol_usuario_id,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	informeNo,
			        obra,
					localizacion,
					formatoCampo.observaciones,
					nombre,
					razonSocial,
					direccion,
					formatoCampo.tipo,
					CONO,
					VARILLA,
					FLEXOMETRO,
					TERMOMETRO
			      FROM 
			        ordenDeTrabajo,cliente,obra,formatoCampo,
			        (
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS CONO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.cono_id = herramientas.id_herramienta
						)AS cono,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS VARILLA
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.varilla_id = herramientas.id_herramienta
						)AS varilla,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS FLEXOMETRO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.flexometro_id = herramientas.id_herramienta
						)AS flexometro,
						(
							SELECT
								id_formatoCampo,
								IF(herramientas.placas IS NULL,'NO HAY',herramientas.placas) AS TERMOMETRO
							FROM
								formatoCampo
							LEFT JOIN
								herramientas
							ON
								formatoCampo.termometro_id = herramientas.id_herramienta
						)AS termometro
			      WHERE 
			      	obra_id = id_obra AND
			      	cliente_id = id_cliente AND
			      	cono.id_formatoCampo = formatoCampo.id_formatoCampo AND
					varilla.id_formatoCampo = formatoCampo.id_formatoCampo AND
					flexometro.id_formatoCampo = formatoCampo.id_formatoCampo AND
					termometro.id_formatoCampo = formatoCampo.id_formatoCampo AND
			      	formatoCampo.id_formatoCampo = 1QQ
			      ",
			      array($id_formatoCampo),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro ese id','error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getInfoByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}



	public function getHeader($token,$rol_usuario_id,$id_ordenDeTrabajo,$id_formatoCampo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	informeNo,
			        obra,
					localizacion,
					razonSocial,
					direccion
			      FROM 
			        ordenDeTrabajo,cliente,obra,formatoCampo
			      WHERE 
			      	obra_id = id_obra AND
			      	cliente_id = id_cliente AND
			      	id_formatoCampo = 1QQ AND
			      	id_ordenDeTrabajo = 1QQ  
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro ese id','error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getClienteByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}

	public function updateFooter($token,$rol_usuario_id,$id_formatoCampo,$observaciones,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
								formatoCampo
							SET
								observaciones ='1QQ',
								cono_id = 1QQ,
								varilla_id = 1QQ,
								flexometro_id = 1QQ,
								termometro_id = 1QQ,
								tipo ='1QQ'
							WHERE
								active=1 AND
								id_formatoCampo = 1QQ
					 "
					,array($observaciones,$cono_id,$varilla_id,$flexometro_id,$termometro_id,$tipo,$id_formatoCampo),"UPDATE"
			      	);
			$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Exito de actualizacion de footer','error' => 0);	
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}

	/*
	public function insertAdmin($token,$rol_usuario_id,$informeNo,$ordenDeServicio_id,$observaciones,$mr,$tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						cliente(rfc,razonSocial,nombre,direccion,email,telefono,nombreContacto,telefonoDeContacto)

						VALUES
						('1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ','1QQ')
				",array($rfc,$razonSocial,$nombre,$direccion,$email,$telefono,$nombreContacto,$telefonoDeContacto),"INSERT");
			$arr = array('id_cliente' => 'No disponible, esto NO es un error', 'razonSocial' => $razonSocial, 'estatus' => 'Exito en insercion', 'error' => 0);
			if($dbS->didQuerydied){
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
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
			$arr = array('id_cliente' => $id_cliente, 'razonSocial' => $razonSocial,'estatus' => 'Exito de actualizacion','error' => 0);	
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

	public function getForDroptdownAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token,$rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_cliente,
					nombre
			      FROM 
			        cliente
			      WHERE
			      	active=1
			      ORDER BY
			      	nombre
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

	public function deactivate($token,$rol_usuario_id,$id_cliente){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							cliente
						SET
							active = '1QQ'
						WHERE
							active=1 AND
							id_cliente = 1QQ
					 "
					,array(0,$id_cliente),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_cliente' => $id_cliente,'estatus' => 'Cliente se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function activate($token,$rol_usuario_id,$id_cliente){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							cliente
						SET
							active = '1QQ'
						WHERE
							active=0 AND
							id_cliente = 1QQ
					 "
					,array(1,$id_cliente),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_cliente' => $id_cliente,'estatus' => 'Cliente se activo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la activacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}



	public function getByIDAdmin($token,$rol_usuario_id,$id_cliente){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
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
					active
			      FROM 
			      	cliente
			      WHERE 
			      	id_cliente = 1QQ
			      ",
			      array($id_cliente),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_formatoCampo' => $id_formatoCampo,'estatus' => 'Error no se encontro ese id','error' => 5);
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getClienteByID , verifica tus datos y vuelve a intentarlo','error' => 6);
			}
		}
		return json_encode($arr);
	}

	public function upDateContrasena($token,$rol_usuario_id,$id_cliente,$constrasena){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$contrasenaValida = hash('sha512', $constrasena);
			$dbS->squery("	UPDATE
							cliente
						SET
							contrasena = '1QQ'
						WHERE
							id_cliente = 1QQ
					 "
					,array($contrasenaValida,$id_cliente),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_cliente' => $id_cliente,'estatus' => 'Se actualizo la contraseña correctamente','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion de la conttraseña , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function upLoadFoto($token,$rol_usuario_id,$id_cliente,$foto){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						UPDATE
							cliente
						SET
							foto = '1QQ'
						WHERE
							id_cliente = 1QQ
						",
						array($foto,$id_cliente),"UPDATE"

					);
			if(!$dbS->didQuerydied){
				$arr = array('id_cliente' => $id_cliente,'estatus' => '¡Exito!, redireccionando...','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en subir la foto, verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}
	*/

	




}
?>