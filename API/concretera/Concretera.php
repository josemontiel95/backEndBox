<?php 

include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");


class Concretera{
	//Variables de BD
	private $id_rol_usuario;
	private $rol;
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
			        id_concretera,
					concretera,
					createdON,
					lastEditedON,
					IF(concretera.active = 1,'Si','No') AS active
			      FROM 
			        concretera
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
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_concretera,
					concretera
			      FROM 
			        concretera
			      WHERE
			      	 active = 1
			      ORDER BY
			      	concretera
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

	public function insertAdmin($token,$rol_usuario_id,$concretera){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		//echo strpos($arr,":0");	Inspecciono si la cadena generada por json_encode contiene la bandera de que no existe error
		//echo strpos($arr,"error\":0");
		
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						concretera(concretera)

						VALUES
						('1QQ')
				",array($concretera),"INSERT");
				if(!$dbS->didQuerydied){
				$arr = array('id_herramienta' => 'No disponible, esto NO es un error', 'concretera' => $concretera, 'estatus' => 'Exito en insercion', 'error' => 0);
				}
				else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
				}
		}
		return json_encode($arr);
	}


	public function upDateAdmin($token,$rol_usuario_id,$id_concretera,$concretera){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							concretera
						SET
							concretera = '1QQ'
						WHERE
							id_concretera = 1QQ
					 "
					,array($concretera,$id_concretera),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_concretera' => 'No disponible, esto NO es un error', 'concretera' => $concretera, 'estatus' => 'Exito en actualizacion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}

	public function deactivate($token,$rol_usuario_id,$id_concretera){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							concretera
						SET
							active = '1QQ'
						WHERE
							active=1 AND
							id_concretera = 1QQ
					 "
					,array(0,$id_concretera),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_concretera' => $id_concretera,'estatus' => 'Concretera se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function activate($token,$rol_usuario_id,$id_concretera){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							concretera
						SET
							active = '1QQ'
						WHERE
							active=0 AND
							id_concretera = 1QQ
					 "
					,array(1,$id_concretera),"UPDATE"
			      	);
			if(!$dbS->didQuerydied){
				$arr = array('id_concretera' => $id_concretera,'estatus' => 'Concretera se activo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la activacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function getByIDAdmin($token,$rol_usuario_id,$id_concretera){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$s= $dbS->qarrayA("
			      SELECT 
			        id_concretera,
			        concretera,
			        createdON,
			        lastEditedON,
			        active
			      FROM 
			      	concretera
			      WHERE 
			      	id_concretera = 1QQ
			      ",
			      array($id_concretera),
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
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getByID , verifica tus datos y vuelve a intentarlo','error' => 2);
			}
		}
		return json_encode($arr);
	}








}



?>