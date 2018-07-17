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



	/*
		Completar las funciones

	*/



	public function getAll($token,$rol_usuario_id){
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
					herramientas.createdON,
					herramientas.lastEditedON,
					herramientas.active
			      FROM 
			        herramienta_tipo,
					herramientas
			      WHERE
			      	 id_herramienta_tipo =  herramienta_tipo_id
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

			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta' => 'No disponible, esto NO es un error', 'herramienta_tipo_id' => $herramienta_tipo_id, 'estatus' => 'Exito en insercion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la insercion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
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
			if(!$dbS->didQuerydied){
				$arr = array('id_herramienta' => 'No disponible, esto NO es un error', 'herramienta_tipo_id' => $herramienta_tipo_id, 'estatus' => 'Exito en actualizacion', 'error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la actualizacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}
		}
		return json_encode($arr);
	}



	public function getHerramientaByID($token,$rol_usuario_id,$id_herramienta){
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
			        id_herramienta_tipo,
					tipo,
					herramientas.createdON,
					herramientas.lastEditedON,
					herramientas.active
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
					return "empty";
				}
				else{
					$arr = array(			'id_herramienta' => $s['id_herramienta'],
								 			'herramienta_tipo_id' => $s['herramienta_tipo_id'], 
								 			'fechaDeCompra' => $s['fechaDeCompra'],
								 			'placas' => $s['placas'],
							 				'condicion' => $s['condicion'],
							 				'id_herramienta_tipo' => $s['id_herramienta_tipo'],
							 				'tipo' => $s['tipo'],
							 				'createdON' => $s['createdON'],
							 				'lastEditedON' => $s['lastEditedON'],
							 				'active' => $s['active'],
							 				'estatus' => 'Exito',
							 				'error' => 0
							 		);
				}
			}
			else{
					$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la funcion getHerramientaByID , verifica tus datos y vuelve a intentarlo','error' => 2);
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
				$arr = array('id_herramienta' => $id_herramienta, 'herramienta_tipo_id' => $herramienta_tipo_id,'estatus' => 'Herramienta se desactivo','error' => 0);
			}
			else{
				$arr = array('id_usuario' => 'NULL', 'nombre' => 'NULL', 'token' => $token,	'estatus' => 'Error en la desactivacion , verifica tus datos y vuelve a intentarlo','error' => 5);
			}



		
		}
		return json_encode($arr);
	}







}





?>