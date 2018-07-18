<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class Obra{
	private $id_obra;
	private $obra;
	private $prefijo;
	private $fechaDeCreacion;
	private $descripcion;
	private $cliente_id;
	private $concretera;
	private $tipo;


	/* Variables de utilería */
	private $wc = '/1QQ/';

	public function insert($token,$rol_usuario_id,$obra,$prefijo,$fechaDeCreacion,$descripcion,$cliente_id,$concretera,$tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						obra(obra,prefijo,fechaDeCreacion,descripcion,cliente_id,concretera,tipo)

						VALUES
						('1QQ','1QQ','1QQ','1QQ',1QQ,'1QQ','1QQ')
				",array($obra,$prefijo,$fechaDeCreacion,$descripcion,$cliente_id,$concretera,$tipo),"INSERT");
				$arr = array('id_obra' => 'No disponible, esto NO es un error', 'obra' => $obra, 'estatus' => 'Exito en insercion', 'error' => 0);
			if($dbS->didQuerydied){
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}

	public function upDate($token,$rol_usuario_id,$id_obra,$obra,$prefijo,$fechaDeCreacion,$descripcion,$cliente_id,$concretera,$tipo){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							obra
						SET
							obra ='1QQ',
							prefijo = '1QQ', 
							fechaDeCreacion = '1QQ',
							descripcion ='1QQ',
							cliente_id = '1QQ', 
							concretera = '1QQ',
							tipo = '1QQ'
						WHERE
							active=1 AND
							id_obra = 1QQ
					 "
					,array($obra,$prefijo,$fechaDeCreacion,$descripcion,$cliente_id,$concretera,$tipo,$id_obra),"UPDATE"
			      	);
			$arr = array('id_obra' => $id_obra, 'obra' => $obra,'estatus' => 'Exito de actualizacion','error' => 0);
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
			        id_obra,
					obra,
					prefijo,
					fechaDeCreacion,
					descripcion,
					cliente_id,
					concretera,
					tipo,
					obra.createdON,
					obra.lastEditedON,
					obra.active,
					id_cliente,
					nombre
			      FROM 
			        obra,cliente
			      WHERE
			      	 cliente_id = id_cliente
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



	public function getAllUser($token,$rol_usuario_id){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$arr= $dbS->qAll("
			      SELECT 
			        id_obra,
					obra
			      FROM 
			        obra
			      WHERE
			      	 active = 1
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








}

?>