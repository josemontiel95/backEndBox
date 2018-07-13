<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");

class Laboratorio{
	//Variables de BD
	private $id_laboratorio;
	private $laboratorio;
	private $estado;
	private $municipio;


	//Cargo mediante algun metodo las variables de la base de datos en las variables locales?

	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function insert($token,$rol_usuario_id,$laboratorio,$estado,$municipio){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						laboratorio(laboratorio,estado,municipio)

						VALUES
						('1QQ','1QQ','1QQ')
				",array($laboratorio,$estado,$municipio),"INSERT");
			$arr = array('id_laboratorio' => 'No disponible, esto NO es un error', 'laboratorio' => $laboratorio, 'estatus' => 'Exito en insercion', 'error' => 0);
		}
		return json_encode($arr);

	}

	public function upDate($token,$rol_usuario_id,$id_laboratorio,$laboratorio,$estado,$municipio){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							laboratorio
						SET
							laboratorio ='1QQ',
							estado = '1QQ', 
							municipio = '1QQ'
						WHERE
							active=1 AND
							id_laboratorio = 1QQ
					 "
					,array($laboratorio,$estado,$municipio,$id_laboratorio),"UPDATE"
			      	);
			$arr = array('id_laboratorio' => $id_laboratorio, 'laboratorio' => $laboratorio,'estatus' => 'Exito de actualizacion','error' => 0);
		}		
		return json_encode($arr);
	}

	public function deactivate($token,$rol_usuario_id,$id_laboratorio){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							laboratorio
						SET
							active = 1QQ
						WHERE
							active=1 AND
							id_laboratorio = 1QQ
					 "
					,array(0,$id_laboratorio),"UPDATE"
			      	);
			$arr = array('id_laboratorio' => $id_laboratorio,'estatus' => 'Laboratorio desactivado','error' => 0);
		}
		return json_encode($arr);
	}


	public function getAll($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_laboratorio,
					laboratorio
			      FROM 
			        laboratorio
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


	/*	Cuando se cree la vista del usuario (pagina web) con esta funcion el usuario puede ingresar el id y se reflejaran los datos de los usuario que esten vinculados con ese laboratorio. Posteriormente el usuario podra filtarlos a su gusto-----No tiene caso porque en las tablas que ya existen los usuarios ya pueden filtrar el numero de id_laboratorio y getallordenes de servicio*/

	public function getAllUsuarios($id_laboratorio){
		global $dbS;
		$arr = $dbS->qAll("
						SELECT
							id_usuario,
							nombre,
							apellios,
							rol
						FROM
							usuarios,
							rol_usuario
						WHERE
							laboratorio_id = 1QQ AND rol_usuario_id = id_rol_usuario
					"
					,array($id_laboratorio),"SELECT"
					);

		if(count($arr) == 0)
			$arr = array('id_laboratorio' => $id_laboratorio, 'estatus' =>"No hay registros", 'error' => 1); //Pendiente
			
		else{
			return json_encode($arr);
		}
	}

}



?>