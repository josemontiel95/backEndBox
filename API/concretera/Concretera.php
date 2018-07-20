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

	public function insert($token,$rol_usuario_id,$concretera){
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
				if($dbS->didQuerydied){
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
				}
		}
		return json_encode($arr);
	}


	public function upDate($token,$rol_usuario_id,$id_concretera,$concretera){
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
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}		
		}
		return json_encode($arr);
	}

	public function deactive($token,$rol_usuario_id,$id_concretera){
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
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function active($token,$rol_usuario_id,$id_concretera){
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
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la activacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}





}



?>