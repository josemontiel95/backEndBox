<?php 
include_once("./../../configSystem.php");
include_once("./../../usuario/Usuario.php");
class Herramienta{
	private $id_herramienta;
	private $herramienta_tipo_id;
	private $fechaDeCompra;
	private $condicion;

	/* Variables de utilería */
	private $wc = '/1QQ/';


	public function insert($token,$rol_usuario_id,$herramienta_tipo_id,$fechaDeCompra,$condicion){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("
						INSERT INTO
						herramientas(herramienta_tipo_id,fechaDeCompra,condicion)

						VALUES
						('1QQ','1QQ','1QQ')
				",array($herramienta_tipo_id,$fechaDeCompra,$condicion),"INSERT");
			$arr = array('id_herramienta' => 'No disponible, esto NO es un error', 'herramienta_tipo_id' => $herramienta_tipo_id, 'estatus' => 'Exito en insercion', 'error' => 0);
		}
		return json_encode($arr);
	}

	public function upDate($token,$rol_usuario_id,$id_herramienta,$herramienta_tipo_id,$fechaDeCompra,$condicion){
		global $dbS;
		$usuario = new Usuario();
		$arr = json_decode($usuario->validateSesion($token, $rol_usuario_id),true);
		if($arr['error'] == 0){
			$dbS->squery("	UPDATE
							herramientas
						SET
							herramienta_tipo_id ='1QQ',
							fechaDeCompra = '1QQ', 
							condicion = '1QQ'
						WHERE
							active=1 AND
							id_herramienta = 1QQ
					 "
					,array($herramienta_tipo_id,$fechaDeCompra,$condicion,$id_herramienta),"UPDATE"
			      	);
		$arr = array('id_herramienta' => $id_herramienta, 'herramienta_tipo_id' => $herramienta_tipo_id,'estatus' => 'Exito de actualizacion','error' => 0);
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
		$arr = array('id_herramienta' => $id_herramienta, 'herramienta_tipo_id' => $herramienta_tipo_id,'estatus' => 'Herramienta se desactivo','error' => 0);
		}
		return json_encode($arr);
	}







}





?>