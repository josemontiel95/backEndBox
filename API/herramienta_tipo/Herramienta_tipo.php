<?php 

include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");


class Herramienta_tipo{
	//Variables de BD
	private $id_herramienta_tipo;
	private $tipo;
	private $placas;
	/* Variables de utilería */
	private $wc = '/1QQ/';



	/*
		Siguiendo la metodologia de POO las acciones las seguiria haciendo el usuario
	*/

	public function getAllAdmin($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_herramienta_tipo,
					tipo,
					createdON,
					lastEditedON,
					IF(active = 1,'Si','No') AS active
			      FROM 
			        herramienta_tipo
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


	public function getForDroptdownAdmin($token,$rol_usuario_id){
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
			      	active=1
			      ORDER BY
			      	tipo
			      ",
			      array(),
			      "SELECT"
			      );

			if(!$dbS->didQuerydied){
				if(count($arr) == 0)
					$arr = array('estatus' =>"No hay registros", 'error' => 5); 
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 6);	
			}
		}
		return json_encode($arr);
	}


		//PENDIENTE ¿Sustituir por getForDropdown?
	public function getAllUser($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_herramienta_tipo,
					tipo,
					asignableenOrdenDeTrabajo
			      FROM 
			        herramienta_tipo
			      WHERE
			      	 active = 1
			      ORDER BY
			     	 tipo
			      ",
			      array(),
			      "SELECT"
			      );
			if(!$dbS->didQuerydied){
						if(count($arr) == 0)
							$arr = array('estatus' =>"No hay registros", 'error' => 1); //Pendiente
						
			}else
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en el query, verifica tus datos y vuelve a intentarlo','error' => 2);
			}
		return json_encode($arr);	

	}


	public function getByIDAdmin($token,$rol_usuario_id,$id_herramienta_tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT
			      	id_herramienta_tipo, 
			        tipo,
					createdON,
					lastEditedON,
					active,
					asignableenOrdenDeTrabajo
			      FROM 
			      	herramienta_tipo
			      WHERE 
			      	id_herramienta_tipo = 1QQ
			      ",
			      array($id_herramienta_tipo),
			      "SELECT"
			      );
			
			if(!$dbS->didQuerydied){
				if($s=="empty"){
					$arr = array('id_herramienta_tipo' => $id_herramienta_tipo,'estatus' => 'Error no se encontro ese id','error' => 5);
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

	public function insertAdmin($token,$rol_usuario_id,$tipo,$asignableenOrdenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);		
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						herramienta_tipo(tipo,asignableenOrdenDeTrabajo)

						VALUES
						('1QQ','1QQ')
				",array($tipo,$asignableenOrdenDeTrabajo),"INSERT");
			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta_tipo' => 'No disponible, esto NO es un error', 'tipo' => $tipo, 'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}


	public function upDateAdmin($token,$rol_usuario_id,$id_herramienta_tipo,$tipo,$asignableenOrdenDeTrabajo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	
						UPDATE
							herramienta_tipo
						SET
							tipo = '1QQ',
							asignableenOrdenDeTrabajo = '1QQ'
						WHERE
							active=1 AND
							id_herramienta_tipo = 1QQ
					 "
					,array($tipo,$asignableenOrdenDeTrabajo,$id_herramienta_tipo),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta_tipo' => $id_herramienta_tipo, 'tipo' => $tipo, 'estatus' => 'Exito en actualizacion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function deactivate($token,$rol_usuario_id,$id_herramienta_tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							herramienta_tipo
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_herramienta_tipo = 1QQ
					 "
					,array(0,$id_herramienta_tipo),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta_tipo' => $id_herramienta_tipo,'estatus' => 'Herramienta_tipo se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function activate($token,$rol_usuario_id,$id_herramienta_tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							herramienta_tipo
						SET
							active = 1QQ
						WHERE
							active=0 AND
							id_herramienta_tipo = 1QQ
					 "
					,array(1,$id_herramienta_tipo),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta_tipo' => $id_herramienta_tipo,'estatus' => 'Herramienta_tipo se activo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

}



?>