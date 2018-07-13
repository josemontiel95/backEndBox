<?php 

include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");


class Rol{
	//Variables de BD
	private $id_rol_usuario;
	private $rol;
	/* Variables de utilería */
	private $wc = '/1QQ/';



	/*
		Siguiendo la metodologia de POO las acciones las seguiria haciendo el usuario
	*/

	function getAll($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_rol_usuario,
					rol
			      FROM 
			        rol_usuario
			      WHERE
			      	 active = 1
			      ",
			      array(),
			      "SELECT"
			      );
			if(count($arr) == 0)
				$arr = array('estatus' =>"No hay registros", 'error' => 1); //Pendiente
		}
		return json_encode($arr);	

	}

	public function insert($token,$rol_usuario_id,$rol){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		//echo strpos($arr,":0");	Inspecciono si la cadena generada por json_encode contiene la bandera de que no existe error
		//echo strpos($arr,"error\":0");
		
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						rol_usuario(rol)

						VALUES
						('1QQ')
				",array($rol),"INSERT");
			$arr = array('id_rol_usuario' => 'No dispinible, esto NO es un error', 'rol' => $rol,'estatus' => 'Exito de insercion','error' => 0);
		}
		return json_encode($arr);
	}


	public function upDate($token,$rol_usuario_id,$id_rol_usuario,$rol){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							rol_usuario
						SET
							rol = '1QQ'
						WHERE
							active=1 AND
							id_rol_usuario = 1QQ
					 "
					,array($rol,$id_rol_usuario),"UPDATE"
			      	);
			$arr = array('id_rol_usuario' => $id_rol_usuario, 'rol' => $rol,'estatus' => 'Exito de actualizacion','error' => 0);
		}
		return json_encode($arr);
	}

	public function deactive($token,$rol_usuario_id,$id_rol_usuario){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							rol_usuario
						SET
							active = '1QQ'
						WHERE
							active=1 AND
							id_rol_usuario = 1QQ
					 "
					,array(0,$id_rol_usuario),"UPDATE"
			      	);
			$arr = array('id_rol_usuario' => $id_rol_usuario,'estatus' => 'Rol desactivado','error' => 0);
		}
		return json_encode($arr);
	}





}



?>