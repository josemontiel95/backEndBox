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



	public function insertJefeBrigada($token,$rol_usuario_id,$informeNo,$ordenDeTrabajo_id,$observaciones,$tipo,$posInicial,$posFinal){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						formatoCampo(informeNo,ordenDeTrabajo_id,observaciones,tipo,posInicial,posFinal)
						VALUES
						('1QQ',1QQ,'1QQ','1QQ','1QQ','1QQ')
				",array($informeNo,$ordenDeTrabajo_id,$observaciones,$tipo,$posInicial,$posFinal),"INSERT");
			$arr = array('id_formatoCampo' => 'No disponible, esto NO es un error','estatus' => 'Exito en insercion', 'error' => 0);
			if($dbS->didQuerydied){
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);

	}



	public function getHeader($token,$rol_usuario_id,$id_ordenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT 
			        obra,
					localizacion,
					razonSocial,
					direccion
			      FROM 
			        ordenDeTrabajo,cliente,obra
			      WHERE 
			      	obra_id = id_obra AND
			      	cliente_id = id_cliente AND
			      	id_ordenDeTrabajo = 1QQ  
			      ",
			      array($id_ordenDeTrabajo),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if($s=="empty"){
					return "empty";
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getClienteByID , verifica tus datos y vuelve a intentarlo','error' => 2);
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
					return "empty";
				}
				else{
					return json_encode($s);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getClienteByID , verifica tus datos y vuelve a intentarlo','error' => 2);
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